<?php

namespace atufkas\ProgressKeeper\Reader;

use atufkas\ProgressKeeper\ChangeLog;

/**
 * Class JsonReader
 * @package atufkas\ProgressKeeper\Reader
 */
class JsonReader implements ReaderInterface
{
    protected $rawVersionLog;
    protected $dataSource;

    /**
     * JsonReader constructor.
     * @param null $dataSource
     */
    public function __construct($dataSource = null)
    {
        $this->setDataSource($dataSource);
    }

    /**
     * @return ChangeLog
     * @throws \atufkas\ProgressKeeper\ChangeLogException
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     * @throws \atufkas\ProgressKeeper\Release\ReleaseException
     */
    public function getChangeLog()
    {
        $rawVersionLog = $this->getRawVersionLog();
        $changeLog = new ChangeLog();
        $changeLog->parseFromArray($rawVersionLog);
        return $changeLog;
    }

    /**
     * @return mixed
     */
    public function getRawVersionLog()
    {
        if ($this->rawVersionLog === null) {
            $this->rawVersionLog = $this->getFromFile();
        }

        return $this->rawVersionLog;
    }

    /**
     * Read data from JSON file in "release-info.json" format.
     * @return mixed|string
     */
    protected function getFromFile()
    {
        $jsonData = null;
        $dataSource = $this->getDataSource();

        if (!$dataSource) {
            return '';
        }

        if (is_string($dataSource)) {
            $jsonData = file_get_contents($dataSource);
        } else {
            $jsonData = $dataSource;
        }

        return json_decode($jsonData, true);
    }

    /**
     * @param $rawVersionlog
     * @return $this
     */
    public function setRawVersionLog($rawVersionlog)
    {
        $this->rawVersionLog = $rawVersionlog;
        return $this;
    }

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