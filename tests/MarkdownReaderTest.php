<?php

namespace atufkas\ProgressKeeper\Tests;


use atufkas\ProgressKeeper\Reader\MarkdownReader;

/**
 * Class MarkdownReaderTest
 * @package atufkas\ProgressKeeper\Tests
 */
class MarkdownReaderTest extends ReaderTestCase
{
    static $markdownChangelogSampleFile = __DIR__ . '/fixtures/pk-changelog-sample.md';

    static $releaseEntryMeta = [
        'version',
        'date',
        'remarks',
        'changelog'
    ];

    /**
     * @test
     * @throws \atufkas\ProgressKeeper\ChangelogException
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     * @throws \atufkas\ProgressKeeper\Release\ReleaseException
     */
    public function testMarkdownReader()
    {
        $markdownReader = new MarkdownReader();
        $markdownReader->setDataSource(static::$markdownChangelogSampleFile);
        $changelog = $markdownReader->getChangelog();
        $this->checkChangelogContents($changelog);
    }
}
