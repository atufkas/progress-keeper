<?php

namespace atufkas\ProgressKeeper\Tests;
use atufkas\ProgressKeeper\ChangeLog;


/**
 * Class ChangeLogTest
 * @package atufkas\ProgressKeeper\Tests
 */
class ChangeLogSampleTest extends JsonSampleTestCase
{
    /**
     * @test
     */
    public function testCreateChangeLog()
    {
        $changeLog1 = new ChangeLog();
        $this->assertNull($changeLog1->getApplicationDesc());
        $this->assertNull($changeLog1->getApplicationName());
        $this->assertEmpty($changeLog1->getReleases());

        $appName = 'Wonder App';
        $appDesc = 'The ultimate tool for everyone.';
        $changeLog2 = new ChangeLog($appName, $appDesc);

        $this->assertEquals($appName, $changeLog2->getApplicationName());
        $this->assertEquals($appDesc, $changeLog2->getApplicationDesc());
    }

    /**
     * @test
     * @throws \atufkas\ProgressKeeper\ChangeLogException
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     * @throws \atufkas\ProgressKeeper\Release\ReleaseException
     */
    public function testCreateChangeLogFromArray()
    {
        // Get samples from "base format" fixture file
        $jsonData = json_decode(file_get_contents(static::$jsonReleaseInfoSampleFile), true);

        $changeLog = new ChangeLog();
        $changeLog->parseFromArray($jsonData);

        $this->assertEquals($jsonData['name'], $changeLog->getApplicationName());
        $this->assertEquals($jsonData['desc'], $changeLog->getApplicationDesc());
        $this->assertNotEmpty($changeLog->getReleases());
    }
}
