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
        $ret  = $this->gi(0) . '<div class="pk">' . "\n";
        $ret .= $this->gi(1) . '<h1 class="pk-name">' . $this->changelog->getApplicationName() . '</h1>' . "\n";
        $ret .= $this->gi(1) . '<span class="pk-desc">' . $this->changelog->getApplicationDesc() . '</span>' . "\n";
        $ret .= $this->gi(1) . '<div class="pk-releases">' . "\n";

        foreach ($this->changelog->getReleases() as $release) {
            /* @var Release $release */
            $ret .= $this->gi(2) . '<div class="pk-release">' . "\n";
            $ret .= $this->gi(3) . '<h2 class="pk-release-version">' . $release->getVersionString() . '</h2>' . "\n";
            $ret .= $this->gi(3) . '<p class="pk-release-date">Date: ' . $release->getDate()->format('d.m.Y') . '</p>' . "\n";
            $ret .= $this->gi(3) . '<p class="pk-release-desc">' . $release->getDesc() . '</p>' . "\n";
            $ret .= $this->gi(3) . '<ul class="pk-logentries">' . "\n";

            foreach ($release->getLogEntries() as $logEntry) {
                /* @var LogEntry $logEntry */
                $ret .= $this->gi(4) . '<li class="pk-logentry">' . "\n";
                $ret .= $this->gi(5) . '<span class="pk-logentry-type">[' . $logEntry->getType() . ']</span>' . "\n";
                $ret .= $this->gi(5) . '<span class="pk-logentry-desc">' . $logEntry->getDesc() . '</span>' . "\n";
                $ret .= $this->gi(4) . '</li>' . "\n";
            }

            $ret .= $this->gi(3) . '</ul>' . "\n";
            $ret .= $this->gi(2) . '</div>' . "\n";
        }

        $ret .= $this->gi(1) . '</div>' . "\n";
        $ret .= $this->gi(0) . '</div>' . "\n";

        return $ret;
    }

    /**
     * Get spaces for next indention level by adding given $step to current indention level
     * @param $step
     * @return string
     */
    public function gi($depth = 0)
    {
        return str_repeat('    ', $depth);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getOutput();
    }
}