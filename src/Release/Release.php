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
    protected $logEntries;

    /**
     * Release constructor.
     * @param string $versionString
     * @param \DateTimeImmutable $date
     * @param string $desc
     */
    public function __construct($versionString = null, \DateTimeImmutable $date = null, $desc = null)
    {
        $this->versionString = $versionString;
        $this->date = $date;
        $this->desc = $desc;
    }
    /**
     * @param $entryArr
     * @throws ReleaseException
     */
    public function parseFromArray($releaseArr)
    {
        $mandatoryKeys = ['date', 'version'];

        foreach ($mandatoryKeys as $mandatoryKey) {
            if (!isset($releaseArr[$mandatoryKey])) {
                throw new ReleaseException(sprintf('Mandatory key "%s" not found in log release array.', $mandatoryKey));
            }

            $value = $releaseArr[$mandatoryKey];

            switch($mandatoryKey) {
                case 'version':
                    $this->setVersionString($value);
                    break;

                case 'date':
                    $this->setDate(\DateTimeImmutable::createFromFormat('Y-m-d', $value));
                    break;

                case 'desc':
                case 'remark':
                case 'remarks':
                    $this->setDesc($value);
                    break;
            }
        }
    }

    /**
     * @param LogEntry $logEntry
     */
    public function addLogEntry(LogEntry $logEntry)
    {
        $this->logEntries[] = $logEntry;
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
     */
    public function setVersionString($versionString)
    {
        $this->versionString = $versionString;
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
    public function setDate($date)
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
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
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
     */
    public function setLogEntries($logEntries)
    {
        $this->logEntries = $logEntries;
    }
}