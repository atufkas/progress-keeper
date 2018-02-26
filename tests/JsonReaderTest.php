<?php

namespace atufkas\ProgressKeeper\Tests;


use atufkas\ProgressKeeper\Reader\JsonReader;

/**
 * Class ReaderTest
 * @package atufkas\ProgressKeeper\Tests
 */
class SampleChangelogTest extends ReaderTestCase
{
    static $jsonChangelogSampleFile = __DIR__ . '/fixtures/pk-changelog-sample.json';

    /**
     * @test
     * @throws \atufkas\ProgressKeeper\ChangelogException
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     * @throws \atufkas\ProgressKeeper\Release\ReleaseException
     */
    public function testJsonReader()
    {
        $jsonReader = new JsonReader();
        $jsonReader->setDataSource(static::$jsonChangelogSampleFile);
        $changelog = $jsonReader->getChangelog();
        $this->checkChangelogContents($changelog);
    }
}
