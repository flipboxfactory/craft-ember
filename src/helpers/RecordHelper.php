<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\helpers;

use flipbox\ember\records\Record;
use yii\base\InvalidConfigException;
use yii\db\QueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class RecordHelper
{
    /**
     * @param $config
     * @param string|null $instanceOf
     * @param string|null $toScenario
     * @return Record
     * @throws InvalidConfigException
     */
    public static function create($config, string $instanceOf = null, string $toScenario = null)
    {
        // Get class from config
        $class = ObjectHelper::checkConfig($config, $instanceOf);

        // New model
        $model = new $class();

        return static::populate($model, $config, $toScenario);
    }

    /**
     * @param Record $record
     * @param array $properties
     * @param string $toScenario
     * @return Record
     */
    public static function populate(Record $record, $properties = [], string $toScenario = null)
    {
        // Set properties
        foreach ($properties as $name => $value) {
            if ($record->canSetProperty($name)) {
                $record->$name = $value;
            }
        }

        // Set scenario
        if (null !== $toScenario) {
            $record->setScenario($toScenario);
        }

        return $record;
    }

    /**
     * @param $condition
     * @return array
     */
    public static function conditionToCriteria($condition)
    {
        if (empty($condition)) {
            return $condition;
        }

        // Assume it's an id
        if (!is_array($condition)) {
            $condition = [
                'id' => $condition
            ];
        }

        return ['where' => ['and', $condition]];
    }

    /**
     * @param string|Record $record
     * @param $criteria
     * @return QueryInterface
     */
    public static function configure($record, $criteria)
    {
        $query = $record::find();

        QueryHelper::configure(
            $query,
            $criteria
        );

        return $query;
    }
}
