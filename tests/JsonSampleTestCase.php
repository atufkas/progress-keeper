<?php

namespace atufkas\ProgressKeeper\Tests;

use PHPUnit\Framework\TestCase;


/**
 * Class JsonSampleTestCase
 * @package atufkas\ProgressKeeper\Tests
 */
abstract class JsonSampleTestCase extends TestCase
{
    static $jsonReleaseInfoSampleFile = __DIR__ . '/fixtures/release-info-sample.json';

    /**
     * @return mixed
     */
    protected function getJsonDataFromSampleFile()
    {
        return json_decode(file_get_contents(static::$jsonReleaseInfoSampleFile), true);
    }
}
