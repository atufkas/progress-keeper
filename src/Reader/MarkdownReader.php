<?php

namespace atufkas\ProgressKeeper\Reader;

use atufkas\ProgressKeeper\Changelog;
use atufkas\ProgressKeeper\LogEntry\LogEntry;
use atufkas\ProgressKeeper\Release\Release;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\Block\Element\FencedCode;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Block\Element\IndentedCode;
use League\CommonMark\Block\Element\ListBlock;
use League\CommonMark\Block\Element\ListItem;
use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\Inline\Element\Text;

/**
 * Class MarkdownReader
 * @package atufkas\ProgressKeeper\Reader
 */
class MarkdownReader extends AbstractReader implements ReaderInterface
{
    protected $rawVersionLog;
    protected $dataSource;

    /**
     * JsonReader constructor.
     * @param null $dataSource
     */
    public function __construct($dataSource = null)
    {
        $this->setDataSource($dataSource);
    }

    /**
     * @return Changelog
     * @throws \atufkas\ProgressKeeper\ChangelogException
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     * @throws \atufkas\ProgressKeeper\Release\ReleaseException
     * @throws \Exception
     */
    public function getChangelog()
    {
//        $rawVersionLog = $this->getRawVersionLog();
        $changelog = new Changelog();

        // Obtain a pre-configured Environment with all the CommonMark parsers/renderers ready-to-go
        $environment = Environment::createCommonMarkEnvironment();
        $environment->mergeConfig(['html_input' => 'escape']);

        // Create a parser and retrieve the AST
        $docParser = new DocParser($environment);
        $astDocument = $docParser->parse($this->getFromDataSource());

        // Walk through every node and try to auto-discover structured elements
        $walker = $astDocument->walker();

        /* @var Release $currentRelease */
        $currentRelease = null;
        /* @var LogEntry $currentLogEntry */
        $currentLogEntry = null;

        while ($event = $walker->next()) {
            $node = $event->getNode();

            switch (get_class($node)) {

                case Text::class:
                case Document::class:
                default:
                    // Note: Text nodes are ignored in favor for Paragraph nodes
                    break;

                case Heading::class:
                    /* @var Heading $node */

                    if ($event->isEntering()) {

                        switch ($node->getLevel()) {
                            case 1:
                                // assume this to be (part of) the application description
                                $changelog->setApplicationName($node->getStringContent());
                                break;

                            default:
                                // otherwise assume this to be the version string of a release
                                $currentRelease = new Release();
                                $currentRelease->setVersionString($node->getStringContent());
                                break;
                        }
                    }

                    break;

                case ListBlock::class:
                    /* @var ListBlock $node */

                    if ($event->isEntering()) {

                        if ($currentRelease === null) {
                            // there was no heading detected for this list block
                            // generate an "anonymous" one:
                            $currentRelease = new Release('?');
                        }
                    } else {
                        $changelog->addRelease($currentRelease);
                        $currentRelease = null;
                    }

                    break;

                case ListItem::class:
                    /* @var ListItem $node */

                    if ($event->isEntering()) {
                        $currentLogEntry = new LogEntry();
                    } else {
                        $currentRelease->addLogEntry($currentLogEntry);
                        $currentLogEntry = null;
                    }

                    break;

                case Paragraph::class:
                case IndentedCode::class:
                case FencedCode::class:
                    /* @var Paragraph $node */

                    if ($event->isEntering()) {

                        $traversedStringContent = '';
                        if (in_array(get_class($node), [IndentedCode::class, FencedCode::class])) {
                            // Convert fenced and indented code always to indented code
                            $codeLines = explode("\n", $node->getStringContent());
                            foreach ($codeLines as $codeLine) {
                                $traversedStringContent .= str_repeat(' ', 6) . $codeLine . "\n";
                            }
                        } else {
                            $traversedStringContent = $node->getStringContent();
                        }

                        if ($currentLogEntry !== null) {
                            // if there's a log entry object instance assume this to be either a log entry date
                            // or (part of) the log entry description/comment
                            $parsedDate = static::getDateFromString($traversedStringContent);

                            if ($parsedDate) {
                                $currentLogEntry->setDate($parsedDate);
                            } else {
                                $parsedCcMessage = LogEntry::parseElementsFromCcMessage($traversedStringContent);

                                if ($parsedCcMessage['type']) {
                                    $currentLogEntry->setType($parsedCcMessage['type']);
                                    $currentLogEntry->setScope($parsedCcMessage['scope']);
                                    $currentLogEntry->setDesc($parsedCcMessage['desc']);
                                } else {
                                    $logEntryDesc = $currentLogEntry->getDesc() ? $currentLogEntry->getDesc() . "\n\n" : '';
                                    $currentLogEntry->setDesc($logEntryDesc . $parsedCcMessage['desc']);
                                }
                            }

                        } elseif ($currentRelease !== null) {
                            // otherwise if there's a release object instance assume this to be either a release date
                            // or (part of) the release description/comment
                            $parsedDate = static::getDateFromString($traversedStringContent);

                            if ($parsedDate) {
                                $currentRelease->setDate($parsedDate);
                            } else {
                                $releaseDesc = $currentRelease->getDesc() ? $currentRelease->getDesc() . "\n\n" : '';
                                $currentRelease->setDesc($releaseDesc . $traversedStringContent);
                            }
                        } else {
                            // in all other cases assume this to be (part of) the application description
                            $applicationDesc = $changelog->getApplicationDesc() ? $changelog->getApplicationDesc() . "\n\n" : '';
                            $changelog->setApplicationDesc($applicationDesc . $traversedStringContent);
                        }
                    }

                    break;
            }
        }

        return $changelog;
    }

    /**
     * Try to parse some popular (de/en) date formats from given string
     * @param $string
     * @return \DateTimeImmutable|null
     */
    protected static function getDateFromString($string)
    {
        if (preg_match('/^Date:[\s]?(\d{4}-\d{2}-\d{2})$/', $string, $matches)) {
            return \DateTimeImmutable::createFromFormat('Y-m-d', $matches[1]);
        }

        if (preg_match('/^Date:[\s]?(\d{2}\.\d{2}\.\d{4})$/', $string, $matches)) {
            return \DateTimeImmutable::createFromFormat('d.m.Y', $matches[1]);
        }

        if (preg_match('/^Date:[\s]?(.*[\s]{1}\d{1,2},[\s]{1}\d{4})$/', $string, $matches)) {
            return \DateTimeImmutable::createFromFormat('F d, Y', $matches[1]);
        }

        return null;
    }

    /**
     * Read data from given data source.
     * @return mixed|string
     */
    protected function getFromDataSource()
    {
        $markdownData = null;
        $dataSource = $this->getDataSource();

        if (!$dataSource) {
            return '';
        }

        if (is_string($dataSource)) {
            $markdownData = file_get_contents($dataSource);
        } else {
            $markdownData = $dataSource;
        }

        return $markdownData;
    }
}