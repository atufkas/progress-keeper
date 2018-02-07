<?php

namespace atufkas\ProgressKeeper;

use atufkas\ProgressKeeper\Release\Release;
use atufkas\ProgressKeeper\Release\ReleaseException;

/**
 * Class Log
 * @package atufkas\ProgressKeeper
 */
class ChangeLog
{
    /**
     * @var string
     */
    protected $applicationName;

    /**
     * @var string
     */
    protected $applicationDesc;

    /**
     * @var array
     */
    protected $releases;

    /**
     * ChangeLog constructor.
     * @param string $applicationName
     * @param string $applicationDesc
     * @param array $releases
     */
    public function __construct($applicationName = null, $applicationDesc = null, array $releases = array())
    {
        $this->applicationName = $applicationName;
        $this->applicationDesc = $applicationDesc;
        $this->releases = $releases;
    }

    /**
     * @param array $changeLogArr
     * @throws ChangeLogException
     * @throws LogEntry\LogEntryException
     * @throws ReleaseException
     */
    public function parseFromArray(array $changeLogArr)
    {
        $mandatoryKeys = ['name', 'desc', 'releases'];

        foreach ($mandatoryKeys as $mandatoryKey) {
            if (!isset($changeLogArr[$mandatoryKey])) {
                throw new ChangeLogException(sprintf('Mandatory key "%s" not found in log release array.', $mandatoryKey));
            }
        }

        foreach ($changeLogArr as $key => $value) {

            switch ($key) {
                case 'name':
                    $this->setApplicationName($value);
                    break;

                case 'desc':
                case 'remark':
                case 'remarks':
                    $this->setApplicationDesc($value);
                    break;

                case 'releases':
                    foreach ($value as $rawRelease) {
                        $this->addReleaseFromArray($rawRelease);
                    }
                    break;
            }
        }
    }

    /**
     * @param Release $release
     */
    public function addRelease(Release $release)
    {
        array_push($this->releases, $release);
    }

    /**
     * @param array $releaseArr
     * @throws LogEntry\LogEntryException
     * @throws ReleaseException
     */
    public function addReleaseFromArray(array $releaseArr)
    {
        $release = new Release();
        $release->parseFromArray($releaseArr);
        $this->addRelease($release);
    }

    /**
     * @return mixed
     */
    public function getApplicationName()
    {
        return $this->applicationName;
    }

    /**
     * @param string $applicationName
     * @return $this
     */
    public function setApplicationName($applicationName)
    {
        $this->applicationName = $applicationName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApplicationDesc()
    {
        return $this->applicationDesc;
    }

    /**
     * @param string $applicationDesc
     * @return $this
     */
    public function setApplicationDesc($applicationDesc)
    {
        $this->applicationDesc = $applicationDesc;
        return $this;
    }

    /**
     * @return array
     */
    public function getReleases()
    {
        return $this->releases;
    }

    /**
     * @param array $releases
     * @return $this
     */
    public function setReleases(array $releases)
    {
        $this->releases = $releases;
        return $this;
    }
}