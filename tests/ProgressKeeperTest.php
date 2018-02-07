<?php

namespace atufkas\ProgressKeeper\Tests;


use atufkas\ProgressKeeper\ChangeLog;
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

            $dateTime = \DateTimeImmutable::createFromFormat('Y-m-d', substr($releaseArr['date'], 0, 10));

            $this->assertEquals($releaseArr['version'], $release->getVersionString());
            $this->assertEquals($releaseArr['remarks'], $release->getDesc());
            $this->assertEquals($dateTime, $release->getDate());

            $this->assertCount(count($releaseArr['changelog']), $release->getLogEntries());
        }
    }

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

    /**
     * @test
     */
    public function testJsonReader()
    {
        $jsonReader = new JsonReader();
        $jsonReader->setDataSource(static::$jsonReleaseInfoSampleFile);

        $rawVersionLog = $jsonReader->getRawVersionLog();
        $this->checkRawVersionLogFields($rawVersionLog);
    }

    /**
     * @test
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function testGetChangeLogJson2Html()
    {
        $htmlChangeLog = ProgressKeeperFactory::getChangeLog('json', 'html', static::$jsonReleaseInfoSampleFile);
        $this->assertStringStartsWith('<div class="pk">', $htmlChangeLog);
        $this->assertStringEndsWith('</div>', $htmlChangeLog);
    }

    /**
     * Check given intermediate "raw version log" array for mandatory fields
     * @param $rawVersionLog
     */
    private function checkRawVersionLogFields($rawVersionLog)
    {
        $this->assertArrayHasKey('name', $rawVersionLog);
        $this->assertArrayHasKey('desc', $rawVersionLog);
        $this->assertArrayHasKey('releases', $rawVersionLog);

        foreach ($rawVersionLog['releases'] as $release) {
            foreach (static::$releaseEntryMeta as $field) {
                $this->assertArrayHasKey($field, $release);
            }
        }
    }
}
