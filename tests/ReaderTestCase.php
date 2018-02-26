<?php

namespace atufkas\ProgressKeeper\Tests;

use atufkas\ProgressKeeper\Changelog;
use PHPUnit\Framework\TestCase;


/**
 * Class ReaderTestCase
 * @package atufkas\ProgressKeeper\Tests
 */
 abstract class ReaderTestCase extends TestCase
{
    static $jsonChangelogSampleFile = __DIR__ . '/fixtures/pk-changelog-sample.json';
    static $jsonData = null;

     /**
      * @param Changelog $changelog
      * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
      */
    protected function checkChangelogContents(Changelog $changelog)
    {
        $this->assertEquals('PK Sample-App', $changelog->getApplicationName());
        $this->assertEquals('The app with forms that make you happy!', $changelog->getApplicationDesc());
        $this->assertEquals('1.0', $changelog->getLatestVersionString());
        $this->assertCount(4, $changelog->getReleases());

        $release = $changelog->getRelease('0.2.0');
        $this->assertEquals('2018-01-24', $release->getDate()->format('Y-m-d'));
        $this->assertCount(6, $release->getLogEntries());

        $featureLogentries = $release->getLogEntriesByType('feat');
        $this->assertCount(3, $featureLogentries);

        $uiFeatureLogentries = $release->getLogEntriesByCcType('upd(ui)');
        $this->assertCount(1, $uiFeatureLogentries);
    }
}
