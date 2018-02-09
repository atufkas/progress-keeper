<?php

namespace atufkas\ProgressKeeper\Tests;


use atufkas\ProgressKeeper\ChangeLog;
use atufkas\ProgressKeeper\ProgressKeeperFactory;

/**
 * Class ProgressKeeperFactoryTest
 * @package atufkas\ProgressKeeper\Tests
 */
class ProgressKeeperFactoryTest extends JsonSampleTestCase
{
    /**
     * @test
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function testGetChangeLog()
    {
        $changeLog = ProgressKeeperFactory::getChangeLog(static::$jsonReleaseInfoSampleFile, 'json');
        $this->assertInstanceOf(ChangeLog::class, $changeLog);
        $this->assertObjectHasAttribute('applicationName', $changeLog);
        $this->assertObjectHasAttribute('applicationDesc', $changeLog);
        $this->assertObjectHasAttribute('releases', $changeLog);
    }

    /**
     * @test
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function testGetFilteredChangeLog()
    {
        $changeLog = ProgressKeeperFactory::getChangeLog(static::$jsonReleaseInfoSampleFile, 'json', '*');
        $releases = $changeLog->getReleases();
        // Second release has 3 entries with audience "*" (all), 2 with audience "dev" and 1 with audience "user":
        $this->assertCount(6, $releases[ 1 ]->getLogEntries());

        $changeLog = ProgressKeeperFactory::getChangeLog(static::$jsonReleaseInfoSampleFile, 'json', 'dev');
        $releases = $changeLog->getReleases();
        // There are 2 entry explicitly addressed to audience "dev" and 3 with audience "*":
        $this->assertCount(5, $releases[ 1 ]->getLogEntries());

        $changeLog = ProgressKeeperFactory::getChangeLog(static::$jsonReleaseInfoSampleFile, 'json', 'user');
        $releases = $changeLog->getReleases();
        // There is only 1 entry explicitly addressed to audience "user" and 3 with audience "*":
        $this->assertCount(4, $releases[ 1 ]->getLogEntries());
    }

    /**
     * @test
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function testGetChangeLogJson2Html()
    {
        $htmlChangeLog = ProgressKeeperFactory::getConvertedChangeLog(static::$jsonReleaseInfoSampleFile, 'json', 'html');
        $this->assertStringStartsWith('<div class="pk">', $htmlChangeLog);
        $this->assertStringEndsWith('</div>', $htmlChangeLog);
    }
}
