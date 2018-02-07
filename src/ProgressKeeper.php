<?php

namespace atufkas\ProgressKeeper;


use atufkas\ProgressKeeper\Presenter\PresenterInterface;
use atufkas\ProgressKeeper\Reader\ReaderInterface;
use atufkas\ProgressKeeper\Release\ReleaseException;

/**
 * Class ProgressKeeper
 * @package atufkas\ProgressKeeper
 */
class ProgressKeeper
{
    /**
     * @var ReaderInterface
     */
    protected $reader;

    /**
     * @var PresenterInterface
     */
    protected $presenter;

    /**
     * ProgressKeeper constructor.
     * @param ReaderInterface $reader
     * @param PresenterInterface $presenter
     */
    public function __construct(ReaderInterface $reader, PresenterInterface $presenter)
    {
        $this->setReader($reader);
        $this->setPresenter($presenter);
    }

    /**
     * @return mixed
     * @throws LogEntry\LogEntryException
     * @throws ReleaseException
     */
    public function getOutput()
    {
        return $this->getPresenter()
            ->setChangeLog($this->getReader()->getChangeLog())
            ->getOutput();
    }

    /**
     * @return ReaderInterface
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * @param $reader
     * @return $this
     */
    public function setReader($reader)
    {
        $this->reader = $reader;
        return $this;
    }

    /**
     * @return PresenterInterface
     */
    public function getPresenter()
    {
        return $this->presenter;
    }

    /**
     * @param $presenter
     * @return $this
     */
    public function setPresenter($presenter)
    {
        $this->presenter = $presenter;
        return $this;
    }
}