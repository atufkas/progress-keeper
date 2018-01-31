<?php

namespace atufkas\ProgressKeeper\Reader;

/**
 * Class JsonReader
 * @package atufkas\ProgressKeeper\Reader
 */
class JsonReader implements ReaderInterface
{
    protected $dataSource;

    /**
     * JsonReader constructor.
     * @param null $dataSource
     */
    public function __construct($dataSource = null)
    {
        $this->setDataSource($dataSource);
    }

    public function read()
    {
        $jsonData = null;
        $dataSource = $this->getDataSource();

        if (is_string($dataSource)) {
            $jsonData = file_get_contents($dataSource);
        } else {
            $jsonData = $dataSource;
        }

        $logEntries = json_decode($jsonData);
        return $logEntries;
    }

    /**
     * @return mixed
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @param mixed $dataSource
     */
    public function setDataSource($dataSource)
    {
        $this->dataSource = $dataSource;
    }
}