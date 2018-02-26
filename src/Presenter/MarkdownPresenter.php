<?php

namespace atufkas\ProgressKeeper\Presenter;
use atufkas\ProgressKeeper\LogEntry\LogEntry;
use atufkas\ProgressKeeper\Release\Release;

/**
 * Class MarkdownPresenter
 * @package atufkas\ProgressKeeper\Presenter
 */
class MarkdownPresenter extends AbstractPresenter implements PresenterInterface
{
    /**
     * @return string
     */
    public function getOutput()
    {
        $ret  = '# ' . $this->changelog->getApplicationName() . "\n";
        $ret .=  $this->changelog->getApplicationDesc() . "\n";
        $ret .= "\n";

        foreach ($this->changelog->getReleases() as $release) {
            /* @var Release $release */
            $ret .= '## ' . $release->getVersionString() . "\n";
            $ret .= "\n";
            $ret .= 'Date: ' . $release->getDate()->format('d.m.Y') . "\n";
            $ret .= "\n";
            $ret .= $release->getDesc() . "\n";
            $ret .= "\n";

            foreach ($release->getLogEntries() as $logEntry) {
                /* @var LogEntry $logEntry */
                $ret .= '- ';
                $ret .= $logEntry->getCcType();
                $ret .= ': ';
                $ret .= $logEntry->getDesc();
                $ret .= "\n";
            }

            $ret .= "\n";
        }

        return $ret;
    }
}