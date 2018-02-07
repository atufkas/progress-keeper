<?php

namespace atufkas\ProgressKeeper\Presenter;

use atufkas\ProgressKeeper\ChangeLog;

/**
 * Class AbstractPresenter
 * @package atufkas\ProgressKeeper\Presenter
 */
abstract class AbstractPresenter implements PresenterInterface
{
    /**
     * @var ChangeLog
     */
    protected $changeLog;

    /**
     * @param array $releases
     * @return $this|PresenterInterface
     */
    public function setChangeLog(ChangeLog $changeLog)
    {
        $this->changeLog = $changeLog;
        return $this;
    }
}