<?php

namespace atufkas\ProgressKeeper\Presenter;

use atufkas\ProgressKeeper\ChangeLog;

/**
 * Interface PresenterInterface
 * @package atufkas\ProgressKeeper\Presenter
 */
interface PresenterInterface
{
    /**
     * Set change log to be transformed
     * @param ChangeLog $changeLog
     * @return $this|PresenterInterface
     */
    public function setChangeLog(ChangeLog $changeLog);

    /**
     * Get transformed output
     * @return mixed
     */
    public function getOutput();
}