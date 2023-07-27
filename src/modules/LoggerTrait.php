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
 * This trait will prefix log items to a specific category.
 *
 * To properly implement, add a static $category attribute to your module such as:
 *
 * ```public static $category = 'some-category';```
 *
 * Then log a message through the module using the static log methods found within this trait.
 *
 * Additionally, you can log to a separate log file by appending log targets via the `config/app.php` file
 *
 * ```php
 * [
 *  'components' => [
 *      'log' => function() {
 *          $config = craft\helpers\App::logConfig();
 *
 *          $targetConfigs = \flipbox\craft\ember\helpers\LoggerHelper::targetConfigs(
 *              ['some-category', 'some-other-category']
 *          );
 *
 *          foreach ($targetConfigs as $key => $targetConfig) {
 *              $config['targets'][$key] = $targetConfig;
 *          }
 *
 *          return $config ? Craft::createObject($config) : null;
 *      }
 *  ]
 * ]
 * ```
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait LoggerTrait
{
    /**
     * @return Logger
     *
     * @deprecated
     */
    public static function getLogger()
    {
        return Craft::getLogger();
    }

    /**
     * The log categories
     *
     * @param string|null $category
     * @param bool $audit flag as an audit message.
     * @return string
     */
    public static function loggerCategory(string $category = null, bool $audit = false): string
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $prefix = static::$category ? (static::$category . ($audit ? ':audit' : '')) : '';

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
     * @param bool $audit flag as an audit message.
     * @since 2.0.0
     */
    public static function debug($message, $category = 'general', bool $audit = false)
    {
        Craft::getLogger()->log($message, Logger::LEVEL_TRACE, static::loggerCategory($category, $audit));
    }

    /**
     * Logs an error message.
     * An error message is typically logged when an unrecoverable error occurs
     * during the execution of an application.
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as array.
     * @param string $category the category of the message.
     * @param bool $audit flag as an audit message.
     * @since 2.0.0
     */
    public static function error($message, $category = 'general', bool $audit = false)
    {
        Craft::getLogger()->log($message, Logger::LEVEL_ERROR, static::loggerCategory($category, $audit));
    }

    /**
     * Logs a warning message.
     * A warning message is typically logged when an error occurs while the execution
     * can still continue.
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as array.
     * @param string $category the category of the message.
     * @param bool $audit flag as an audit message.
     * @since 2.0.0
     */
    public static function warning($message, $category = 'general', bool $audit = false)
    {
        Craft::getLogger()->log($message, Logger::LEVEL_WARNING, static::loggerCategory($category, $audit));
    }

    /**
     * Logs an informative message.
     * An informative message is typically logged by an application to keep record of
     * something important (e.g. an administrator logs in).
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as array.
     * @param string $category the category of the message.
     * @param bool $audit flag as an audit message.
     * @since 2.0.0
     */
    public static function info($message, $category = 'general', bool $audit = false)
    {
        Craft::getLogger()->log($message, Logger::LEVEL_INFO, static::loggerCategory($category, $audit));
    }
}
