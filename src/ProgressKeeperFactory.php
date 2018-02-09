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
     * Get changelog object from given source and type.
     *
     * @param $source
     * @param $sourceType
     * @param array|string $audiences
     * @return Changelog
     */
    public static function getChangelog($source, $sourceType, $audiences = ['*'])
    {
        $readerNamespace = '\\atufkas\\ProgressKeeper\\Reader\\';
        $readerInterface = $readerNamespace . 'ReaderInterface';
        $readerClassName = $readerNamespace . ucfirst($sourceType) . 'Reader';

        if (is_string($audiences)) {
            $audiences = [$audiences];
        }

        try {
            static::checkClass($readerClassName, $readerInterface);
        } catch (\Exception $e) {
            echo "\n";
            echo 'Implementation missing or incomplete: ' . $e->getMessage() . "\n";
            echo 'in ' . $e->getFile() . ':' . $e->getLine() . "\n";
            exit;
        }

        try {
            /**
             * @var $reader ReaderInterface
             */
            $reader = new $readerClassName();
            $reader->setDataSource($source);
            return $reader->getChangelog()->filterToAudiences($audiences);
        } catch (\Exception $e) {
            echo "\n";
            echo 'Could not instantiate progress keeper: ' . $e->getMessage() . "\n";
            echo 'in ' . $e->getFile() . ':' . $e->getLine() . "\n";
            exit;
        }
    }

    /**
     * Get transformed changelog output from given source/type and converted
     * to given target type.
     *
     * @param $source
     * @param $sourceType
     * @param $targetType
     * @param array|string $audiences
     * @return mixed
     */
    public static function getConvertedChangelog($source, $sourceType, $targetType, $audiences = ['*'])
    {
        $presenterNamespace = '\\atufkas\\ProgressKeeper\\Presenter\\';
        $presenterInterface = $presenterNamespace . 'PresenterInterface';
        $presenterClassName = $presenterNamespace . ucfirst($targetType) . 'Presenter';

        if (is_string($audiences)) {
            $audiences = [$audiences];
        }

        try {
            static::checkClass($presenterClassName, $presenterInterface);
        } catch (\Exception $e) {
            echo "\n";
            echo 'Implementation missing or incomplete: ' . $e->getMessage() . "\n";
            echo 'in ' . $e->getFile() . ':' . $e->getLine() . "\n";
            exit;
        }

        try {
            /**
             * @var $presenter PresenterInterface
             */
            $presenter = new $presenterClassName();

            return $presenter
                ->setChangelog(static::getChangelog($source, $sourceType, $audiences))
                ->getOutput();

        } catch (\Exception $e) {
            echo "\n";
            echo 'Could not instantiate progress keeper: ' . $e->getMessage() . "\n";
            echo 'in ' . $e->getFile() . ':' . $e->getLine() . "\n";
            exit;
        }
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