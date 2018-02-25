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
    public function testCreateEmptyLogEntry()
    {
        $logEntry = new LogEntry();

        $this->assertEquals('misc', $logEntry->getType());
        $this->assertEquals('progress', $logEntry->getDesc());
        $this->assertNotNull($logEntry->getDate());
        $this->assertInstanceOf(\DateTimeImmutable::class, $logEntry->getDate());
    }

    /**
     * @test
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     */
    public function testCreateSimpleLogEntry()
    {
        $message = 'Added some more glitter.';
        $dateTime = \DateTimeImmutable::createFromFormat('Y-m-d', '2018-01-04');
        $logEntry = new LogEntry($message, 'dev', $dateTime);

        $this->assertEquals('misc', $logEntry->getType());
        $this->assertEquals($message, $logEntry->getDesc());
        $this->assertEquals($dateTime, $logEntry->getDate());
    }

    /**
     * @test
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     */
    public function testCreateLogEntryFromCcMessage()
    {
        $dateTime = \DateTimeImmutable::createFromFormat('Y-m-d', '2018-02-14');
        $ccMessage = 'change(ui): Added even more glitter.';
        $logEntry = new LogEntry($ccMessage, 'dev', $dateTime);

        $this->assertEquals('upd', $logEntry->getType());
        $this->assertEquals('ui', $logEntry->getScope());
        $this->assertEquals('Added even more glitter.', $logEntry->getDesc());
        $this->assertEquals($dateTime, $logEntry->getDate());
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
                $logEntry->createFromArray($changelogArr);
                $this->assertEquals($changelogArr['desc'], $logEntry->getDesc());

                $ccTypeElements = LogEntry::parseElementsFromCcType($changelogArr['type']);
                $this->assertEquals(LogEntryType::getCanonicalIdentifier($ccTypeElements['type']), $logEntry->getType());

                if (isset($changelogArr['scope'])) {
                    $this->assertEquals($changelogArr['scope'], $logEntry->getScope());
                }
            }
        }
    }
}
