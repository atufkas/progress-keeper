<?php

namespace atufkas\ProgressKeeper\Presenter;

use atufkas\ProgressKeeper\Changelog;

/**
 * Class AbstractPresenter
 * @package atufkas\ProgressKeeper\Presenter
 */
abstract class AbstractPresenter implements PresenterInterface
{
    /**
     * @var Changelog
     */
    protected $changelog;

    /**
     * @param array $releases
     * @return $this|PresenterInterface
     */
    public function setChangelog(Changelog $changelog)
    {
        $this->changelog = $changelog;
        return $this;
    }
}