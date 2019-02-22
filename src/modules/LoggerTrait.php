<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\modules;

use Craft;
use yii\log\Logger;

/**
 * This trait will create and attach a separate log dispatcher / logger.  It allows modules to log to a separate
 * log file, while still supporting the use of categories.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait LoggerTrait
{
    /**
     * The log categories
     *
     * @param $category
     * @return string
     */
    protected static function loggerCategory($category): string
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $prefix = static::$category ?? '';

        if (empty($category)) {
            return $prefix;
        }

        return ($prefix ? $prefix . ':' : '') . $category;
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
        Craft::getLogger()->log($message, Logger::LEVEL_TRACE, static::loggerCategory($category));
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
        Craft::getLogger()->log($message, Logger::LEVEL_ERROR, static::loggerCategory($category));
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
        Craft::getLogger()->log($message, Logger::LEVEL_WARNING, static::loggerCategory($category));
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
        Craft::getLogger()->log($message, Logger::LEVEL_INFO, static::loggerCategory($category));
    }
}
