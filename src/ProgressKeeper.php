<?php

namespace atufkas\ProgressKeeper;


use atufkas\ProgressKeeper\Presenter\PresenterInterface;
use atufkas\ProgressKeeper\Reader\ReaderInterface;

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
     */
    public function getOutput()
    {
        return $this->getPresenter()->transform($this->getReader()->read());
    }

    /**
     * @return ReaderInterface
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * @param mixed $reader
     */
    public function setReader($reader)
    {
        $this->reader = $reader;
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
     */
    public function setPresenter($presenter)
    {
        $this->presenter = $presenter;
    }
}