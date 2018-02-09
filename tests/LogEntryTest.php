<?php

namespace atufkas\ProgressKeeper\Tests;


use atufkas\ProgressKeeper\LogEntry\LogEntry;
use atufkas\ProgressKeeper\LogEntry\LogEntryType;

/**
 * Class LogEntryTest
 * @package atufkas\ProgressKeeper\Tests
 */
class LogEntryTest extends JsonSampleTestCase
{
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
}
