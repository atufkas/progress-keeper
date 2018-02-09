<?php

namespace atufkas\ProgressKeeper\Tests;


use atufkas\ProgressKeeper\Release\Release;

/**
 * Class ReleaseTest
 * @package atufkas\ProgressKeeper\Tests
 */
class ReleaseSampleTest extends JsonSampleTestCase
{
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
}
