<?php

namespace atufkas\ProgressKeeper\Tests;


use atufkas\ProgressKeeper\ProgressKeeperFactory;
use atufkas\ProgressKeeper\Reader\JsonReader;
use PHPUnit\Framework\TestCase;

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
