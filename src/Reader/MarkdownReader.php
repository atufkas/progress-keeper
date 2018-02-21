<?php

namespace atufkas\ProgressKeeper\Reader;

use atufkas\ProgressKeeper\Changelog;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\Block\Element\Heading;
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
     */
    public function getChangelog()
    {
//        $rawVersionLog = $this->getRawVersionLog();
        $changelog = new Changelog();
//        $changelog->parseFromArray($rawVersionLog);

        // Obtain a pre-configured Environment with all the CommonMark parsers/renderers ready-to-go
        $environment = Environment::createCommonMarkEnvironment();
        $environment->mergeConfig(['html_input' => 'escape']);
        $docParser = new DocParser($environment);
        $astDocument = $docParser->parse($this->getFromDataSource());
        $walker = $astDocument->walker();

        while ($event = $walker->next()) {
            $node = $event->getNode();
            echo 'I am ' . ($event->isEntering() ? 'entering' : 'leaving') . ' a ' . get_class($node) . ' node' . "\n";

            switch (get_class($node)) {
                default:
                    break;

                case Document::class:
                    /* @var Document $node */
                    break;

                case Heading::class:
                    /* @var Heading $node */
                    break;

                case Text::class:
                    /* @var Text $node */
                    break;

                case Paragraph::class:
                    /* @var Paragraph $node */
                    break;

                case ListBlock::class:
                    /* @var ListBlock $node */
                    break;

                case ListItem::class:
                    /* @var ListItem $node */
                    break;
            }
        }

        return $changelog;
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