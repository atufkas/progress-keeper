<?php

namespace atufkas\ProgressKeeper\Release;

use atufkas\ProgressKeeper\LogEntry\LogEntry;
use atufkas\ProgressKeeper\LogEntry\LogEntryType;

/**
 * Class Release
 * @package atufkas\ProgressKeeper\Release
 */
class Release
{
    /**
     * @var string
     */
    protected $versionString;

    /**
     * @var \DateTimeImmutable
     */
    protected $date;

    /**
     * @var string
     */
    protected $desc;

    /**
     * @var array
     */
    protected $logEntries = [];

    /**
     * Release constructor.
     * @param string $versionString
     * @param \DateTimeImmutable $date
     * @param string $desc
     */
    public function __construct($versionString = null, \DateTimeImmutable $date = null, $desc = null)
    {
        $this->versionString = $versionString;

        if (!$date) {
            $date = new \DateTimeImmutable('now');
        }

        $this->date = $date;
        $this->desc = $desc;
    }

    /**
     * @param array $releaseArr
     * @throws ReleaseException
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     */
    public function parseFromArray(array $releaseArr)
    {
        $mandatoryKeys = ['date', 'version'];

        foreach ($mandatoryKeys as $mandatoryKey) {
            if (!isset($releaseArr[$mandatoryKey])) {
                throw new ReleaseException(sprintf('Mandatory key "%s" not found in log release array.', $mandatoryKey));
            }
        }

        foreach ($releaseArr as $key => $value) {

            switch ($key) {
                case 'version':
                    $this->setVersionString($value);
                    break;

                case 'date':
                    $this->setDate(\DateTimeImmutable::createFromFormat('Y-m-d', substr($value, 0, 10)));
                    break;

                case 'desc':
                case 'remark':
                case 'remarks':
                    $this->setDesc($value);
                    break;

                case 'changelog':
                    foreach ($value as $rawLogEntry) {
                        $this->addLogEntryFromArray($rawLogEntry);
                    }
                    break;
            }
        }
    }

    /**
     * @param LogEntry $logEntry
     */
    public function addLogEntry(LogEntry $logEntry)
    {
        array_push($this->logEntries, $logEntry);
    }

    /**
     * @param array $logEntryArr
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException#
     */
    public function addLogEntryFromArray(array $logEntryArr)
    {
        $logEntry = new LogEntry();
        $logEntry->parseFromArray($logEntryArr);
        $this->addLogEntry($logEntry);
    }

    /**
     * @param LogEntryType $type
     * @return array
     */
    public function getLogEntriesByType(LogEntryType $type)
    {
        return array_filter($this->getLogEntries(), function ($logEntry) use ($type) {
            /* @var LogEntry $logEntry */
            return $logEntry->getType() === $type;
        });
    }

    /**
     * @param \DateTimeImmutable $startDate
     * @param \DateTimeImmutable $endDate
     * @return array
     */
    public function getLogEntriesByDaterange(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate)
    {
        return array_filter($this->getLogEntries(), function ($logEntry) use ($startDate, $endDate) {
            /* @var LogEntry $logEntry */
            $leDate = $logEntry->getDate();
            return $startDate <= $leDate && $endDate >= $leDate;
        });
    }

    /**
     * @return string
     */
    public function getVersionString()
    {
        return $this->versionString;
    }

    /**
     * @param string $versionString
     * @return $this
     */
    public function setVersionString($versionString)
    {
        $this->versionString = $versionString;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTimeImmutable $date
     */
    public function setDate(\DateTimeImmutable $date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param string $desc
     * @return $this
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
        return $this;
    }

    /**
     * @return array
     */
    public function getLogEntries()
    {
        return $this->logEntries;
    }

    /**
     * @param array $logEntries
     * @return $this
     */
    public function setLogEntries($logEntries)
    {
        $this->logEntries = $logEntries;
        return $this;
    }
}