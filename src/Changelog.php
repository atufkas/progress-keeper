<?php

namespace atufkas\ProgressKeeper;

use atufkas\ProgressKeeper\Release\Release;
use atufkas\ProgressKeeper\Release\ReleaseException;

/**
 * Class Changelog
 * @package atufkas\ProgressKeeper
 */
class Changelog
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
     * Changelog constructor.
     * @param string $applicationName
     * @param string $applicationDesc
     * @param array $releases
     */
    public function __construct($applicationName = null, $applicationDesc = null, array $releases = [])
    {
        $this->applicationName = $applicationName;
        $this->applicationDesc = $applicationDesc;
        $this->releases = $releases;
    }

    /**
     * @param array $changelogArr
     * @return $this
     * @throws ChangelogException
     * @throws LogEntry\LogEntryException
     * @throws ReleaseException
     */
    public function parseFromArray(array $changelogArr)
    {
        $mandatoryKeys = ['name', 'desc', 'releases'];

        foreach ($mandatoryKeys as $mandatoryKey) {
            if (!isset($changelogArr[$mandatoryKey])) {
                throw new ChangelogException(sprintf('Mandatory key "%s" not found in log release array.', $mandatoryKey));
            }
        }

        foreach ($changelogArr as $key => $value) {

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

        return $this;
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
     * Filter log entries of all changelog releases to those marked
     * for given audience
     * @param array $audiences
     * @return $this
     */
    public function filterToAudiences($audiences = ['*'])
    {
        $this->releases = array_filter($this->releases, function ($release) use ($audiences) {
            /* @var Release $release */
            return $release->filterToAudiences($audiences);
        });

        return $this;
    }

    /**
     * Order log entries of releases by type. Default order (true) means: Order as listed in
     * LogEntryType::PGTYPE_ALIASES. An order value null or false means: Do nothing!
     * @param null|boolean|array $order
     * @return $this
     */
    public function orderLogEntriesByType($order = null)
    {
        if ($order === false || $order === null) {
            return $this;
        }

        foreach ($this->releases as $release) {
            /* @var Release $release */
            $release->orderByType($order);
        }

        return $this;
    }

    /**
     * @return Release|null
     */
    public function getLatestRelease()
    {
        if (count($this->releases)) {
            /** @var Release $latestRelease */
            return $this->releases[0];
        }
        return null;
    }

    /**
     * Get version string of latest (top most) release, i.e. get "current version".
     * @return null|string
     */
    public function getLatestVersionString()
    {
        $latestRelease = $this->getLatestRelease();
        return $latestRelease ? $latestRelease->getVersionString() : null;
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