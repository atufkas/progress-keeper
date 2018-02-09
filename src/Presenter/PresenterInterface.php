<?php

namespace atufkas\ProgressKeeper\Presenter;

use atufkas\ProgressKeeper\Changelog;

/**
 * Interface PresenterInterface
 * @package atufkas\ProgressKeeper\Presenter
 */
interface PresenterInterface
{
    /**
     * Set changelog to be transformed
     * @param Changelog $changelog
     * @return $this|PresenterInterface
     */
    public function setChangelog(Changelog $changelog);

    /**
     * Get transformed output
     * @return mixed
     */
    public function getOutput();
}