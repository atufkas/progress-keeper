<?php

namespace atufkas\ProgressKeeper\Tests;


use atufkas\ProgressKeeper\Changelog;
use atufkas\ProgressKeeper\LogEntry\LogEntry;
use atufkas\ProgressKeeper\ProgressKeeperFactory;
use atufkas\ProgressKeeper\Release\Release;

/**
 * Class ProgressKeeperFactoryTest
 * @package atufkas\ProgressKeeper\Tests
 */
class ProgressKeeperFactoryTest extends ChangelogTestCase
{
    /**
     * @test
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function testGetChangelog()
    {
        $changelog = ProgressKeeperFactory::getChangelog(static::$jsonChangelogSampleFile, 'json');
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
        $changelog = ProgressKeeperFactory::getChangelog(
            static::$jsonChangelogSampleFile, 'json', '*');
        $releases = $changelog->getReleases();
        /* @var Release $release */
        $release = $releases[1];
        // Second release has 3 entries with audience "*" (all), 2 with audience "dev" and 1 with audience "user":
        $this->assertCount(6, $release->getLogEntries());

        $changelog = ProgressKeeperFactory::getChangelog(
            static::$jsonChangelogSampleFile, 'json', 'dev');
        $releases = $changelog->getReleases();
        /* @var Release $release */
        $release = $releases[1];
        // There are 2 entry explicitly addressed to audience "dev" and 3 with audience "*":
        $this->assertCount(5, $release->getLogEntries());

        $changelog = ProgressKeeperFactory::getChangelog(
            static::$jsonChangelogSampleFile, 'json', 'user');
        $releases = $changelog->getReleases();
        /* @var Release $release */
        $release = $releases[1];
        // There is only 1 entry explicitly addressed to audience "user" and 3 with audience "*":
        $this->assertCount(4, $release->getLogEntries());
    }

    /**
     * @test
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function testGetOrderedLogEntriesChangelog()
    {
        // RUN 1: Unordered
        $changelog = ProgressKeeperFactory::getChangelog(
            static::$jsonChangelogSampleFile, 'json', '*', false);
        $releases = $changelog->getReleases();
        /* @var Release $release */
        $release = $releases[1];
        $logEntries = $release->getLogEntries();

        $typeListUnordered = ['feat', 'feat', 'upd', 'feat', 'chore', 'chore'];

        foreach ($logEntries as $num => $logEntry) {
            /* @var LogEntry $logEntry */
            $this->assertEquals($typeListUnordered[$num], $logEntry->getType());
        }

        // RUN 1: Default order
        $changelog = ProgressKeeperFactory::getChangelog(
            static::$jsonChangelogSampleFile, 'json', '*', true);
        $releases = $changelog->getReleases();
        /* @var Release $release */
        $release = $releases[1];
        $logEntries = $release->getLogEntries();

        $typeListDefaultOrdered = ['feat', 'feat', 'feat', 'upd', 'chore', 'chore'];


        foreach ($logEntries as $num => $logEntry) {
            /* @var LogEntry $logEntry */
            $this->assertEquals($typeListDefaultOrdered[$num], $logEntry->getType());
        }

        // RUN 1: Custom order
        $customOrder = ['upd', 'feat', 'chore'];
        $changelog = ProgressKeeperFactory::getChangelog(
            static::$jsonChangelogSampleFile, 'json', '*', $customOrder);
        $releases = $changelog->getReleases();
        /* @var Release $release */
        $release = $releases[1];
        $logEntries = $release->getLogEntries();

        $typeListDefaultOrdered = ['upd', 'feat', 'feat', 'feat', 'chore', 'chore'];

        foreach ($logEntries as $num => $logEntry) {
            /* @var LogEntry $logEntry */
            $this->assertEquals($typeListDefaultOrdered[$num], $logEntry->getType());
        }
    }

    /**
     * @test
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function testGetConvertedChangelogJson2Html()
    {
        $htmlChangelog = ProgressKeeperFactory::getConvertedChangelog(
            static::$jsonChangelogSampleFile, 'json', 'html');
        $this->assertStringStartsWith('<div class="pk">', $htmlChangelog);
        $this->assertStringEndsWith("</div>\n", $htmlChangelog);
    }
}
