<?php

namespace atufkas\ProgressKeeper\Reader;
use atufkas\ProgressKeeper\Changelog;

/**
 * Class AbstractReader
 * @package atufkas\ProgressKeeper\Reader
 */
abstract class AbstractReader implements ReaderInterface
{
    /**
     * @var Changelog
     */
    protected $changelog;

    /**
     * @var
     */
    protected $dataSource;

    /**
     * @return mixed
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @param $dataSource
     * @return $this
     */
    public function setDataSource($dataSource)
    {
        $this->dataSource = $dataSource;
        return $this;
    }
}