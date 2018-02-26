<?php

namespace atufkas\ProgressKeeper\Tests;

use atufkas\ProgressKeeper\Changelog;
use PHPUnit\Framework\TestCase;


/**
 * Class ChangelogTestCase
 * @package atufkas\ProgressKeeper\Tests
 */
abstract class ChangelogTestCase extends TestCase
{
    static $jsonChangelogSampleFile = __DIR__ . '/fixtures/pk-changelog-sample.json';
    static $jsonData = null;

    /**
     * @return mixed
     */
    protected static function getJsonDataFromSampleFile()
    {
        if (static::$jsonData === null) {
            static::$jsonData = json_decode(file_get_contents(static::$jsonChangelogSampleFile), true);
        }

        return static::$jsonData;
    }

    /**
     * @return Changelog
     * @throws \atufkas\ProgressKeeper\ChangelogException
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     * @throws \atufkas\ProgressKeeper\Release\ReleaseException
     */
    protected function getChangelogFromSampleFile()
    {
        $changelog = new Changelog();
        return $changelog->createFromArray(static::getJsonDataFromSampleFile());
    }
}
