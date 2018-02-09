<?php

namespace atufkas\ProgressKeeper\Tests;


use atufkas\ProgressKeeper\Changelog;
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
    public function testGetChangelog()
    {
        $changelog = ProgressKeeperFactory::getChangelog(static::$jsonReleaseInfoSampleFile, 'json');
        $this->assertInstanceOf(Changelog::class, $changelog);
        $this->assertObjectHasAttribute('applicationName', $changelog);
        $this->assertObjectHasAttribute('applicationDesc', $changelog);
        $this->assertObjectHasAttribute('releases', $changelog);
    }

    /**
     * @test
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function testGetFilteredChangelog()
    {
        $changelog = ProgressKeeperFactory::getChangelog(static::$jsonReleaseInfoSampleFile, 'json', '*');
        $releases = $changelog->getReleases();
        // Second release has 3 entries with audience "*" (all), 2 with audience "dev" and 1 with audience "user":
        $this->assertCount(6, $releases[ 1 ]->getLogEntries());

        $changelog = ProgressKeeperFactory::getChangelog(static::$jsonReleaseInfoSampleFile, 'json', 'dev');
        $releases = $changelog->getReleases();
        // There are 2 entry explicitly addressed to audience "dev" and 3 with audience "*":
        $this->assertCount(5, $releases[ 1 ]->getLogEntries());

        $changelog = ProgressKeeperFactory::getChangelog(static::$jsonReleaseInfoSampleFile, 'json', 'user');
        $releases = $changelog->getReleases();
        // There is only 1 entry explicitly addressed to audience "user" and 3 with audience "*":
        $this->assertCount(4, $releases[ 1 ]->getLogEntries());
    }

    /**
     * @test
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function testGetChangelogJson2Html()
    {
        $htmlChangelog = ProgressKeeperFactory::getConvertedChangelog(static::$jsonReleaseInfoSampleFile, 'json', 'html');
        $this->assertStringStartsWith('<div class="pk">', $htmlChangelog);
        $this->assertStringEndsWith('</div>', $htmlChangelog);
    }
}
