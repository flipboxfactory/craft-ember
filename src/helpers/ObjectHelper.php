<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\helpers;

use craft\helpers\Json as JsonHelper;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class ObjectHelper
{
    /**
     * Configures an object with the initial property values.
     *
     * @param BaseObject $object
     * @param array $properties
     * @return BaseObject
     */
    public static function populate(BaseObject $object, $properties = []): BaseObject
    {
        // Set properties
        foreach ($properties as $name => $value) {
            if ($object->canSetProperty($name)) {
                $object->$name = $value;
            }
        }

        return $object;
    }

    /**
     * Create a new object
     *
     * @param $config
     * @param string|null $instanceOf
     * @throws InvalidConfigException
     * @return BaseObject
     */
    public static function create($config, string $instanceOf = null): BaseObject
    {
        // Get class from config
        $class = static::checkConfig($config, $instanceOf);

        // New object
        $object = new $class();

        // Populate
        if ($config) {
            static::populate($object, $config);
        }

        return $object;
    }

    /**
     * Checks the config for a valid class
     *
     * @param $config
     * @param string|null $instanceOf
     * @param bool $removeClass
     * @throws InvalidConfigException
     * @return string
     */
    public static function checkConfig(&$config, string $instanceOf = null, bool $removeClass = true): string
    {
        // Get class from config
        $class = static::getClassFromConfig($config, $removeClass);

        // Make sure we have a valid class
        if ($instanceOf && !is_subclass_of($class, $instanceOf)) {
            throw new InvalidConfigException(
                sprintf(
                    "The class '%s' must be an instance of '%s'",
                    (string)$class,
                    (string)$instanceOf
                )
            );
        }

        return $class;
    }

    /**
     * Get a class from a config
     *
     * @param $config
     * @param bool $removeClass
     * @throws InvalidConfigException
     * @return string
     */
    public static function getClassFromConfig(&$config, bool $removeClass = false): string
    {
        // Find class
        $class = static::findClassFromConfig($config, $removeClass);

        if (empty($class)) {
            throw new InvalidConfigException(
                sprintf(
                    "The configuration must specify a 'class' property: '%s'",
                    JsonHelper::encode($config)
                )
            );
        }

        return $class;
    }

    /**
     * Find a class from a config
     *
     * @param $config
     * @param bool $removeClass
     * @return null|string
     */
    public static function findClassFromConfig(&$config, bool $removeClass = false)
    {
        // Normalize the config
        if (is_string($config)) {
            // Set as class
            $class = $config;

            // Clear class from config
            $config = '';
        } elseif (is_object($config)) {
            return get_class($config);
        } else {
            // Force Array
            if (!is_array($config)) {
                $config = ArrayHelper::toArray($config, [], false);
            }

            if ($removeClass) {
                if (!$class = ArrayHelper::remove($config, 'class')) {
                    $class = ArrayHelper::remove($config, 'type');
                }
            } else {
                $class = ArrayHelper::getValue(
                    $config,
                    'class',
                    ArrayHelper::getValue($config, 'type')
                );
            }
        }

        return $class;
    }
}
