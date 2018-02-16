<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits;

use flipbox\ember\exceptions\RecordNotFoundException;
use flipbox\ember\helpers\QueryHelper;
use flipbox\ember\helpers\RecordHelper;
use yii\caching\Dependency;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord as Record;
use yii\db\Connection;
use yii\db\TableSchema;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ActiveRecord
{
    /**
     * @return string
     */
    public abstract static function recordClass(): string;

    /**
     * @return string
     */
    public static function recordClassInstance(): string
    {
        return Record::class;
    }

    /**
     * @return int|null
     */
    protected static function cacheDuration()
    {
        return false;
    }

    /**
     * @return null|Dependency
     */
    protected static function cacheDependency()
    {
        return null;
    }

    /**
     * @return Connection
     */
    public static function getDb(): Connection
    {
        /** @var Record $recordClass */
        $recordClass = static::recordClass();

        return $recordClass::getDb();
    }

    /**
     * @return TableSchema
     * @throws \yii\base\InvalidConfigException
     */
    public static function getTableSchema(): TableSchema
    {
        /** @var Record $recordClass */
        $recordClass = static::recordClass();

        return $recordClass::getTableSchema();
    }

    /*******************************************
     * QUERY
     *******************************************/

    /**
     * @param array $config
     * @return \yii\db\ActiveQuery
     */
    public function getQuery($config = []): ActiveQuery
    {
        /** @var Record $recordClass */
        $recordClass = static::recordClass();

        $query = $recordClass::find();

        if ($config) {
            QueryHelper::configure(
                $query,
                $config
            );
        }

        return $query;
    }

    /*******************************************
     * CREATE
     *******************************************/

    /**
     * @param array $attributes
     * @param string $toScenario
     * @return Record
     */
    public function create(array $attributes = [], string $toScenario = null): Record
    {
        /** @var string $recordClass */
        $recordClass = static::recordClass();

        /** @var Record $record */
        $record = new $recordClass();

        // Set scenario
        if ($toScenario) {
            $record->setScenario($toScenario);
        }

        // Do we need to set properties too
        if (!empty($attributes)) {
            $record->setAttributes($attributes);
        }

        return $record;
    }


    /*******************************************
     * FIND / GET
     *******************************************/

    /**
     * @param string $toScenario
     * @return Record[]
     */
    public function findAll(string $toScenario = null)
    {
        return $this->findAllByCondition(null, $toScenario);
    }

    /**
     * @param $identifier
     * @param string|null $toScenario
     * @return Record|null
     */
    public function find($identifier, string $toScenario = null)
    {
        if ($identifier instanceof Record) {
            if (null !== $toScenario) {
                $identifier->setScenario($toScenario);
            }
            return $identifier;
        }

        return $this->findByCondition($identifier, $toScenario);
    }

    /**
     * @param $identifier
     * @param string $toScenario
     * @return Record
     * @throws RecordNotFoundException
     */
    public function get($identifier, string $toScenario = null): Record
    {
        if (!$object = $this->find($identifier, $toScenario)) {
            $this->notFoundException();
        }

        return $object;
    }


    /*******************************************
     * ONE CONDITION
     *******************************************/

    /**
     * @param $condition
     * @param string $toScenario
     * @return Record|null
     */
    public function findByCondition($condition, string $toScenario = null)
    {
        return $this->findByCriteria(
            RecordHelper::conditionToCriteria($condition),
            $toScenario
        );
    }

    /**
     * @param $condition
     * @param string $toScenario
     * @return Record
     * @throws RecordNotFoundException
     */
    public function getByCondition($condition, string $toScenario = null)
    {
        if (!$record = $this->findByCondition($condition, $toScenario)) {
            $this->notFoundException();
        }

        return $record;
    }


    /*******************************************
     * ONE CRITERIA
     *******************************************/

    /**
     * @param $criteria
     * @param string|null $toScenario
     * @return mixed
     */
    public function findByCriteria($criteria, string $toScenario = null)
    {
        $record = $this->queryOne(
            $this->getQuery($criteria)
        );

        if ($record && $toScenario) {
            $record->setScenario($toScenario);
        }

        return $record;
    }

    /**
     * @param $criteria
     * @param string $toScenario
     * @return Record
     * @throws RecordNotFoundException
     */
    public function getByCriteria($criteria, string $toScenario = null)
    {
        if (!$record = $this->findByCriteria($criteria, $toScenario)) {
            $this->notFoundException();
        }

        return $record;
    }


    /*******************************************
     * ALL CONDITION
     *******************************************/

    /**
     * @param array $condition
     * @param string $toScenario
     * @return Record[]
     */
    public function findAllByCondition($condition = [], string $toScenario = null)
    {
        return $this->findAllByCriteria(
            RecordHelper::conditionToCriteria($condition),
            $toScenario
        );
    }

    /**
     * @param array $condition
     * @param string $toScenario
     * @return Record[]
     * @throws RecordNotFoundException
     */
    public function getAllByCondition($condition = [], string $toScenario = null)
    {
        if (!$records = $this->findAllByCondition($condition, $toScenario)) {
            $this->notFoundException();
        }

        return $records;
    }

    /*******************************************
     * ALL CRITERIA
     *******************************************/

    /**
     * @param array $criteria
     * @param string $toScenario
     * @return Record[]
     */
    public function findAllByCriteria($criteria = [], string $toScenario = null)
    {
        $records = $this->queryAll(
            $this->getQuery($criteria)
        );

        if ($toScenario) {
            foreach ($records as $record) {
                $record->setScenario($toScenario);
            }
        }

        return $records;
    }

    /**
     * @param array $criteria
     * @param string $toScenario
     * @return Record[]
     * @throws RecordNotFoundException
     */
    public function getAllByCriteria($criteria = [], string $toScenario = null)
    {
        if (!$records = $this->findAllByCriteria($criteria, $toScenario)) {
            $this->notFoundException();
        }

        return $records;
    }


    /*******************************************
     * CACHE
     *******************************************/

    /**
     * @param ActiveQuery $query
     * @return Record|null
     */
    protected function queryOne(ActiveQuery $query)
    {
        $db = static::getDb();

        try {
            if (false === ($cacheDuration = static::cacheDuration())) {
                return $query->one($db);
            }

            $record = $db->cache(function ($db) use ($query) {
                return $query->one($db);
            }, $cacheDuration, static::cacheDependency());
        } catch (\Exception $e) {
            return null;
        }

        return $record;
    }

    /**
     * @param ActiveQuery $query
     * @return Record[]
     */
    protected function queryAll(ActiveQuery $query)
    {
        $db = static::getDb();

        try {
            if (false === ($cacheDuration = static::cacheDuration())) {
                return $query->all($db);
            }

            $record = $db->cache(function ($db) use ($query) {
                return $query->all($db);
            }, $cacheDuration, static::cacheDependency());
        } catch (\Exception $e) {
            return [];
        }

        return $record;
    }

    /*******************************************
     * EXCEPTIONS
     *******************************************/

    /**
     * @throws RecordNotFoundException
     */
    protected function notFoundException()
    {
        throw new RecordNotFoundException(
            sprintf(
                "Record does not exist."
            )
        );
    }
}
