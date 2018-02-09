<?php

namespace atufkas\ProgressKeeper\Tests;
use atufkas\ProgressKeeper\Changelog;


/**
 * Class ChangelogTest
 * @package atufkas\ProgressKeeper\Tests
 */
class ChangelogTest extends JsonSampleTestCase
{
    /**
     * @test
     */
    public function testCreateChangelog()
    {
        $changelog = new Changelog();
        $this->assertNull($changelog->getApplicationDesc());
        $this->assertNull($changelog->getApplicationName());
        $this->assertEmpty($changelog->getReleases());

        $appName = 'Wonder App';
        $appDesc = 'The ultimate tool for everyone.';
        $changelog = new Changelog($appName, $appDesc);

        $this->assertEquals($appName, $changelog->getApplicationName());
        $this->assertEquals($appDesc, $changelog->getApplicationDesc());
    }

    /**
     * @test
     * @throws \atufkas\ProgressKeeper\ChangelogException
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     * @throws \atufkas\ProgressKeeper\Release\ReleaseException
     */
    public function testCreateChangelogFromArray()
    {
        // Get samples from "base format" fixture file
        $jsonData = json_decode(file_get_contents(static::$jsonReleaseInfoSampleFile), true);

        $changelog = new Changelog();
        $changelog->parseFromArray($jsonData);

        $this->assertEquals($jsonData['name'], $changelog->getApplicationName());
        $this->assertEquals($jsonData['desc'], $changelog->getApplicationDesc());
        $this->assertNotEmpty($changelog->getReleases());
    }
}
