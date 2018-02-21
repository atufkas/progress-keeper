<?php

namespace atufkas\ProgressKeeper\Reader;

use atufkas\ProgressKeeper\Changelog;

/**
 * Class JsonReader
 * @package atufkas\ProgressKeeper\Reader
 */
class JsonReader extends AbstractReader implements ReaderInterface
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
     * @return Changelog
     * @throws \atufkas\ProgressKeeper\ChangelogException
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     * @throws \atufkas\ProgressKeeper\Release\ReleaseException
     */
    public function getChangelog()
    {
        $rawVersionLog = $this->getRawVersionLog();
        $changelog = new Changelog();
        $changelog->parseFromArray($rawVersionLog);
        return $changelog;
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
}