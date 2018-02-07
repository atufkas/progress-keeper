<?php

namespace atufkas\ProgressKeeper\Reader;

use atufkas\ProgressKeeper\ChangeLog;

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
     * @return ChangeLog
     */
    public function getChangeLog();
}