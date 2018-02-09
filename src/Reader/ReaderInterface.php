<?php

namespace atufkas\ProgressKeeper\Reader;

use atufkas\ProgressKeeper\Changelog;

/**
 * Interface ReaderInterface
 * @package atufkas\ProgressKeeper\Reader
 */
interface ReaderInterface
{
    /**
     * @param $dataSource
     * @return mixed
     */
    public function setDataSource($dataSource);

    /**
     * @return Changelog
     */
    public function getChangelog();
}