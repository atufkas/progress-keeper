<?php

namespace atufkas\ProgressKeeper\Tests;


use atufkas\ProgressKeeper\ChangeLog;
use atufkas\ProgressKeeper\ProgressKeeperFactory;

/**
 * Class ProgressKeeperFactoryTest
 * @package atufkas\ProgressKeeper\Tests
 */
class ProgressKeeperFactorySampleTest extends JsonSampleTestCase
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
    public function testGetChangeLogJson2Html()
    {
        $htmlChangeLog = ProgressKeeperFactory::getConvertedChangeLog(static::$jsonReleaseInfoSampleFile, 'json', 'html');
        $this->assertStringStartsWith('<div class="pk">', $htmlChangeLog);
        $this->assertStringEndsWith('</div>', $htmlChangeLog);
    }
}
