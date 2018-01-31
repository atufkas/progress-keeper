<?php

namespace atufkas\ProgressKeeper\Reader;

/**
 * Interface ReaderInterface
 * @package atufkas\ProgressKeeper\Reader
 */
interface ReaderInterface
{
    public function read();
    public function setDataSource($dataSource);
}