<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\modules;

use Craft;
use craft\helpers\StringHelper;
use craft\log\FileTarget;
use yii\log\Logger;

/**
 * This trait will create and attach a separate log dispatcher / logger.  It allows modules to log to a separate
 * log file, while still supporting the use of categories.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.2
 */
trait LoggerTrait
{
    private static $logTargetSet = false;

    /**
     * The log file name
     *
     * @return string
     */
    abstract protected static function getLogFileName(): string;

    /**
     * @inheritdoc
     */
    protected static function isDebugModeEnabled()
    {
        return false;
    }

    /**
     * The log categories
     *
     * @return string
     */
    protected static function getLogId(): string
    {
        return static::getLogFileName();
    }

    /**
     * The log categories
     *
     * @return array
     */
    protected static function getLogCategories(): array
    {
        $fileName = static::getLogId();
        return [$fileName, $fileName . ':*'];
    }

    /**
     * @return string
     */
    public static function getLogFile(): string
    {
        return '@storage/logs/' . self::prepLogFileName(static::getLogFileName());
    }

    /**
     * @return Logger
     */
    public static function getLogger(): Logger
    {
        if (static::$logTargetSet !== true) {
            static::addLoggerFileTarget();

            static::$logTargetSet = true;
        }

        return Craft::getLogger();
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @return FileTarget
     */
    protected static function createLoggerFileTarget(): FileTarget
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        /** @noinspection PhpUnhandledExceptionInspection */
        return Craft::createObject(
            static::loggerFileTarget()
        );
    }

    /**
     * Adds the file target logger
     */
    protected static function addLoggerFileTarget()
    {
        Craft::getLogger()->dispatcher->targets[static::getLogId()] = static::createLoggerFileTarget();
    }

    /**
     * @return array
     */
    protected static function loggerFileTarget()
    {
        $generalConfig = Craft::$app->getConfig()->getGeneral();

        return [
            'class' => FileTarget::class,
            'fileMode' => $generalConfig->defaultFileMode,
            'dirMode' => $generalConfig->defaultDirMode,
            'logVars' => [],
            'categories' => static::getLogCategories(),
            'levels' => array_merge(
                ['error', 'warning'],
                (static::isDebugModeEnabled() || YII_DEBUG) ? ['trace', 'info'] : []
            ),
            'logFile' => static::getLogFile()
        ];
    }

    /**
     * @param string $fileName
     * @return string
     */
    private static function prepLogFileName(string $fileName): string
    {
        return StringHelper::toKebabCase(
                StringHelper::removeRight($fileName, '.log')
            ) . '.log';
    }

    /**
     * Logs a debug message.
     * Trace messages are logged mainly for development purpose to see
     * the execution work flow of some code. This method will only log
     * a message when the application is in debug mode.
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as array.
     * @param string $category the category of the message.
     * @since 2.0.14
     */
    public static function debug($message, $category = 'general')
    {
        static::getLogger()->log($message, Logger::LEVEL_TRACE, static::getLogId() . ':' . $category);
    }

    /**
     * Logs an error message.
     * An error message is typically logged when an unrecoverable error occurs
     * during the execution of an application.
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as array.
     * @param string $category the category of the message.
     */
    public static function error($message, $category = 'general')
    {
        static::getLogger()->log($message, Logger::LEVEL_ERROR, static::getLogId() . ':' . $category);
    }

    /**
     * Logs a warning message.
     * A warning message is typically logged when an error occurs while the execution
     * can still continue.
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as array.
     * @param string $category the category of the message.
     */
    public static function warning($message, $category = 'general')
    {
        static::getLogger()->log($message, Logger::LEVEL_WARNING, static::getLogId() . ':' . $category);
    }

    /**
     * Logs an informative message.
     * An informative message is typically logged by an application to keep record of
     * something important (e.g. an administrator logs in).
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as array.
     * @param string $category the category of the message.
     */
    public static function info($message, $category = 'general')
    {
        static::getLogger()->log($message, Logger::LEVEL_INFO, static::getLogId() . ':' . $category);
    }
}
