<?php

namespace atufkas\ProgressKeeper;


use atufkas\ProgressKeeper\Presenter\PresenterInterface;
use atufkas\ProgressKeeper\Reader\ReaderInterface;

/**
 * Class ProgressKeeperFactory
 * @package atufkas\ProgressKeeper
 */
class ProgressKeeperFactory
{
    /**
     * @param $sourceType
     * @param $targetType
     * @param $source
     */
    public static function getChangeLog($sourceType, $targetType, $source)
    {
        $readerNamespace = '\\atufkas\\ProgressKeeper\\Reader\\';
        $presenterNamespace = '\\atufkas\\ProgressKeeper\\Presenter\\';

        $readerInterface = $readerNamespace . 'ReaderInterface';
        $presenterInterface = $presenterNamespace . 'PresenterInterface';

        $readerClassName = $readerNamespace . ucfirst($sourceType) . 'Reader';
        $presenterClassName = $presenterNamespace . ucfirst($targetType) . 'Presenter';

        try {
            static::checkClass($readerClassName, $readerInterface);
            static::checkClass($presenterClassName, $presenterInterface);
        } catch (\Exception $e) {
            echo "Implementation missing or incomplete: " . $e->getMessage();
            exit;
        }

        /**
         * @var $reader ReaderInterface
         */
        $reader = new $readerClassName();
        $reader->setDataSource($source);

        /**
         * @var $presenter PresenterInterface
         */
        $presenter = new $presenterClassName();

        $pk = new ProgressKeeper($reader, $presenter);
        return $pk->getOutput();
    }

    /**
     * @param $className
     * @param $interfaceName
     * @throws \Exception
     * @throws \ReflectionException
     */
    private static function checkClass($className, $interfaceName)
    {
        if (!class_exists($className)) {
            throw new \Exception('Class not found: ' . $className);
        }

        $reflectedClass = new \ReflectionClass($className);

        // check if can be instantiable
        if (!$reflectedClass->IsInstantiable()) {
            throw new \Exception('Class is not Instantiable: ' . $className);
        }
        // Check interface
        if (!$reflectedClass->implementsInterface($interfaceName)) {
            throw new \Exception('Class does not implement interface: ' . $interfaceName);
        }
    }
}