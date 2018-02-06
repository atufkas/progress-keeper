<?php

namespace atufkas\ProgressKeeper\Tests;


use atufkas\ProgressKeeper\LogEntry\LogEntry;
use atufkas\ProgressKeeper\LogEntry\LogEntryType;
use atufkas\ProgressKeeper\ProgressKeeperFactory;
use atufkas\ProgressKeeper\Reader\JsonReader;
use atufkas\ProgressKeeper\Release\Release;
use PHPUnit\Framework\TestCase;

/**
 * Class ProgressKeeperTest
 * @package atufkas\ProgressKeeper\Tests
 */
class ProgressKeeperTest extends TestCase
{
    static $jsonReleaseInfoSampleFile = __DIR__ . '/fixtures/release-info-sample.json';

    static $releaseEntryMeta = [
        'version',
        'date',
        'remarks',
        'changelog'
    ];

    static $releaseEntryChangelogFields = [
        'date',
        'type',
        'desc'
    ];

    /**
     * @test
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     */
    public function testCreateLogEntry()
    {
        $logEntry1 = new LogEntry();
        $this->assertNull($logEntry1->getDesc());
        $this->assertNotNull($logEntry1->getDate());

        $dateTime = \DateTimeImmutable::createFromFormat('Y-m-d', '2018-01-04');
        $message = 'Added some glitter!';
        $logEntry2 = new LogEntry('feat', 'dev', $dateTime, $message);

        $this->assertArrayHasKey($logEntry2->getType(), LogEntryType::PGTYPE_ALIASES);
        $this->assertEquals($message, $logEntry2->getDesc());
        $this->assertEquals($dateTime, $logEntry2->getDate());
    }

    /**
     * @test
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     */
    public function testCreateLogEntryFromArray()
    {
        // Get samples from "base format" fixture file
        $jsonData = json_decode(file_get_contents(static::$jsonReleaseInfoSampleFile), true);

        foreach ($jsonData['releases'] as $releaseArr) {
            foreach ($releaseArr['changelog'] as $changelogArr) {
                $logEntry = new LogEntry();
                $logEntry->parseFromArray($changelogArr);
                $this->assertArrayHasKey($logEntry->getType(), LogEntryType::PGTYPE_ALIASES);
            }
        }
    }

    /**
     * @test
     */
    public function testCreateRelease()
    {
        $release1 = new Release();
        $this->assertNull($release1->getVersionString());
        $this->assertNull($release1->getDesc());
        $this->assertNotNull($release1->getDate());

        $dateTime = \DateTimeImmutable::createFromFormat('Y-m-d', '2018-01-04');
        $desc = 'A fantastic release providing a whole bunch of new features.';
        $release2 = new Release('1.0', $dateTime, $desc);

        $this->assertEquals($desc, $release2->getDesc());
        $this->assertEquals($dateTime, $release2->getDate());
    }

    /**
     * @test
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     * @throws \atufkas\ProgressKeeper\Release\ReleaseException
     */
    public function testCreateReleaseFromArray()
    {
        // Get samples from "base format" fixture file
        $jsonData = json_decode(file_get_contents(static::$jsonReleaseInfoSampleFile), true);

        foreach ($jsonData['releases'] as $releaseArr) {
            $release = new Release();
            $release->parseFromArray($releaseArr);

            $dateTime = \DateTimeImmutable::createFromFormat('Y-m-d', $releaseArr[ 'date' ]);

            $this->assertEquals($releaseArr[ 'version' ], $release->getVersionString());
            $this->assertEquals($releaseArr[ 'remarks' ], $release->getDesc());
            $this->assertEquals($dateTime, $release->getDate());

            $this->assertCount(count($releaseArr[ 'changelog' ]), $release->getLogEntries());
        }
    }

    /**
     * @test
     */
    public function testJsonReader()
    {
        $jsonReader = new JsonReader();
        $jsonReader->setDataSource(static::$jsonReleaseInfoSampleFile);

        $pkLog = $jsonReader->read();

        $this->assertObjectHasAttribute('name', $pkLog);
        $this->assertObjectHasAttribute('desc', $pkLog);
        $this->assertObjectHasAttribute('releases', $pkLog);

        foreach ($pkLog->releases as $release) {
            foreach (static::$releaseEntryMeta as $field) {
                $this->assertObjectHasAttribute($field, $release);
            }
        }
    }

    /**
     * @test
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function testGetChangelog()
    {
        $htmlChangeLog = ProgressKeeperFactory::getChangeLog('json', 'html', static::$jsonReleaseInfoSampleFile);
        $this->assertStringStartsWith('<div>', $htmlChangeLog);
        $this->assertStringEndsWith('</div>', $htmlChangeLog);
    }
}
