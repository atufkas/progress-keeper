<?php

namespace atufkas\ProgressKeeper\Tests;


use atufkas\ProgressKeeper\Reader\JsonReader;

/**
 * Class ReaderTest
 * @package atufkas\ProgressKeeper\Tests
 */
class ReaderTest extends JsonSampleTestCase
{
    static $releaseEntryMeta = [
        'version',
        'date',
        'remarks',
        'changelog'
    ];

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
