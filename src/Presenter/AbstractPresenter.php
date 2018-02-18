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
     * Default labels for displaying log entry types
     * @var array
     */
    protected $mappedTypes = [
        'doc' => 'Docs',
        'feat' => 'New',
        'upd' => 'Updated',
        'rem' => 'Removed',
        'fix' => 'Fixed',
        'perf' => 'Performance',
        'secur' => 'Security',
        'refac' => 'Refactored',
        'revert' => 'Reverted',
        'style' => 'Code Style',
        'test' => 'Tests',
        'chore' => 'Housekeeping'
    ];

    /**
     * @var Changelog
     */
    protected $changelog;

    /**k
     * @param array $releases
     * @return $this|PresenterInterface
     */
    public function setChangelog(Changelog $changelog)
    {
        $this->changelog = $changelog;
        return $this;
    }
}