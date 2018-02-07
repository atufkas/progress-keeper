<?php

namespace atufkas\ProgressKeeper\Presenter;

use atufkas\ProgressKeeper\LogEntry\LogEntry;
use atufkas\ProgressKeeper\Release\Release;

/**
 * Class HtmlPresenter
 * @package atufkas\ProgressKeeper\Presenter
 */
class HtmlPresenter extends AbstractPresenter implements PresenterInterface
{
    /**
     * @return string
     */
    public function getOutput()
    {
        $ret = '<div class="pk">';
        $ret .= '<h1 class="pk-name">' . $this->changeLog->getApplicationName() . '</h1>';
        $ret .= '<span class="pk-desc">' . $this->changeLog->getApplicationDesc() . '</span>';
        $ret .= '<ul class="pk-versions">';

        foreach ($this->changeLog->getReleases() as $release) {
            /* @var Release $release */
            $ret .= '<li class="pk-release">';
            $ret .= '<h2 class="pk-release-version">' . $release->getVersionString() . '</h2>';
            $ret .= '<span class="pk-release-date">[' . $release->getDate()->format('d.m.Y') . ']</span>';
            $ret .= '&nbsp;';
            $ret .= '<span class="pk-release-remarks">' . $release->getDesc() . '</span>';
            $ret .= '<ul>';

            foreach ($release->getLogEntries() as $logEntry) {
                /* @var LogEntry $logEntry */
                $ret .= '<li class="pk-logentries">';
                $ret .= '<span class="pk-logentries-type">[' . $logEntry->getType() . ' ]</span>';
                $ret .= '&nbsp;';
                $ret .= '<span class="pk-logentries-desc">' . $logEntry->getDesc() . '</span>';
                $ret .= '&nbsp;';
                $ret .= '<span class="pk-logentries-date">[ ' . $logEntry->getDate()->format('d.m.Y') . ' ]</span>';
                $ret .= '</li>';
            }

            $ret .= '</ul>';
            $ret .= '</li>';
        }

        $ret .= '</ul>';
        $ret .= '</div>';

        return $ret;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getOutput();
    }
}