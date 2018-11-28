<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\services\objects;

use craft\helpers\ArrayHelper;
use flipbox\craft\ember\exceptions\ObjectNotFoundException;
use flipbox\craft\ember\helpers\ObjectHelper;
use flipbox\craft\ember\services\queries\QueryAccessor;
use yii\base\BaseObject;
use yii\db\ActiveRecord as Record;
use yii\db\QueryInterface;

/**
 * Used when an object configuration is stored in the database and upon it's retrieval, an object is
 * created and returned. Instances when the class name is stored at the record level, leave the static::objectClass()
 * null and the records 'type' or 'class' property will be used.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @method BaseObject parentQueryOne(QueryInterface $query)
 * @method BaseObject[] parentQueryAll(QueryInterface $query)
 */
trait ObjectAccessorTrait
{
    use QueryAccessor {
        queryOne as parentQueryOne;
        queryAll as parentQueryAll;
    }

    /*******************************************
     * OBJECT CLASSES
     *******************************************/

    /**
     * @return string|null
     */
    abstract public static function objectClass();

    /**
     * @return string
     */
    public static function objectClassInstance(): string
    {
        return BaseObject::class;
    }

    /*******************************************
     * CREATE
     *******************************************/

    /**
     * @param mixed $config
     * @return BaseObject
     * @throws \yii\base\InvalidConfigException
     */
    public function create($config = [])
    {
        if ($config instanceof Record) {
            $config = $this->prepareConfigFromRecord($config);
        }

        if (!is_array($config)) {
            $config = ArrayHelper::toArray($config, [], false);
        }

        return ObjectHelper::create(
            $this->prepareConfig($config),
            static::objectClassInstance()
        );
    }

    /**
     * @param array $config
     * @return array
     */
    protected function prepareConfig(array $config = []): array
    {
        // Auto-set the class
        $class = static::objectClass();
        if ($class !== null) {
            $config['class'] = $class;
        }

        return $config;
    }

    /**
     * @param Record $record
     * @return array
     */
    protected function prepareConfigFromRecord(Record $record): array
    {
        return array_merge(
            $record->getRelatedRecords(),
            $record->toArray()
        );
    }

    /*******************************************
     * FIND / GET
     *******************************************/

    /**
     * @inheritdoc
     */
    public function find($identifier)
    {
        $instance = static::objectClassInstance();
        if ($identifier instanceof $instance) {
            return $identifier;
        }

        return $this->findByCondition($identifier);
    }

    /*******************************************
     * CACHE
     *******************************************/

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    protected function queryOne(QueryInterface $query)
    {
        return $this->createFromQueryResult(
            $this->parentQueryOne($query)
        );
    }

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    protected function queryAll(QueryInterface $query)
    {
        return $this->createAllFromQueryResults(
            $this->parentQueryAll($query)
        );
    }

    /**
     * @param $result
     * @return null|BaseObject
     * @throws \yii\base\InvalidConfigException
     */
    protected function createFromQueryResult($result)
    {
        if ($result === null) {
            return null;
        }

        return $this->create($result);
    }

    /**
     * @param array $results
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function createAllFromQueryResults(array $results): array
    {
        $objects = [];
        foreach ($results as $key => $value) {
            $objects[$key] = $this->create($value);
        }

        return $objects;
    }

    /*******************************************
     * EXCEPTIONS
     *******************************************/

    /**
     * @throws ObjectNotFoundException
     */
    protected function notFoundException()
    {
        throw new ObjectNotFoundException(
            sprintf(
                "Object not found."
            )
        );
    }
}
