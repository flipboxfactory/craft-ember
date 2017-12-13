<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\helpers;

use yii\base\Model;
use yii\base\InvalidConfigException;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class ModelHelper
{
    /**
     * The default scenario
     */
    const DEFAULT_SCENARIO = self::SCENARIO_DEFAULT;

    /**
     * The scenario used by default
     */
    const SCENARIO_DEFAULT = Model::SCENARIO_DEFAULT;

    /**
     * @param $config
     * @param string|null $instanceOf
     * @param string|null $toScenario
     * @throws InvalidConfigException
     * @return Model
     */
    public static function create(
        $config,
        string $instanceOf = null,
        string $toScenario = null
    ): Model {
        // Get class from config
        $class = ObjectHelper::checkConfig($config, $instanceOf);

        // New model
        $model = new $class();

        return static::populate($model, $config, $toScenario);
    }

    /**
     * @param Model $model
     * @param array $attributes
     * @param string|null $toScenario
     * @return Model
     */
    public static function populate(
        Model $model,
        $attributes = [],
        string $toScenario = null
    ): Model {
        // Set scenario
        if (null !== $toScenario) {
            $model->setScenario($toScenario);
        }

        // Populate model attributes
        $model->setAttributes($attributes);

        return $model;
    }
}
