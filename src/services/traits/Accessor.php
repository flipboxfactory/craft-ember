<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits;

use craft\helpers\ArrayHelper;
use craft\helpers\Json as JsonHelper;
use flipbox\ember\exceptions\ObjectNotFoundException;
use flipbox\ember\exceptions\RecordNotFoundException;
use flipbox\ember\helpers\ObjectHelper;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord as Record;
use yii\db\QueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait Accessor
{
    use ActiveRecord;

    /**
     * @var BaseObject[]
     */
    protected $cacheAll;


    /*******************************************
     * OBJECT CLASSES
     *******************************************/

    /**
     * @return string
     */
    abstract public static function objectClass(): string;

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
     * @param array|Record $config
     * @param string|null $toScenario
     * @throws InvalidConfigException
     * @return BaseObject
     */
    public function create($config = [], string $toScenario = null): BaseObject
    {
        // Treat records as known data and set via config
        if ($config instanceof Record) {
            return $this->createFromRecord($config, $toScenario);
        }

        // Force Array
        if (!is_array($config)) {
            $config = ArrayHelper::toArray($config, [], false);
        }

        // Auto-set the class
        if ($class = static::objectClass()) {
            $config['class'] = $class;
        }

        return ObjectHelper::create(
            $config,
            static::objectClassInstance()
        );
    }

    /**
     * @param Record $record
     * @param string|null $toScenario
     * @throws InvalidConfigException
     * @return BaseObject
     */
    protected function createFromRecord(Record $record, string $toScenario = null): BaseObject
    {
        if (null !== $toScenario) {
            $record->setScenario($toScenario);
        }

        $config = $record->toArray();

        // Auto-set the class
        if ($class = static::objectClass()) {
            $config['class'] = $class;
        }

        return ObjectHelper::create(
            $config,
            static::objectClassInstance()
        );
    }


    /*******************************************
     * FIND/GET ALL
     *******************************************/

    /**
     * @param string $toScenario
     * @return BaseObject[]
     */
    public function findAll(string $toScenario = null)
    {
        // Check addToCache
        if (is_null($this->cacheAll)) {
            $this->cacheAll = [];

            // Find record in db
            if ($records = $this->findAllRecords()) {
                foreach ($records as $record) {
                    $this->cacheAll[] = $this->findByRecord($record, $toScenario);
                }
            }
        }

        return $this->cacheAll;
    }

    /**
     * @param string $toScenario
     * @return BaseObject[]
     * @throws ObjectNotFoundException
     */
    public function getAll(string $toScenario = null): array
    {
        if (!$objects = $this->findAll($toScenario)) {
            $this->notFoundException();
        }

        return $objects;
    }

    /*******************************************
     * FIND/GET
     *******************************************/

    /**
     * @param $identifier
     * @param string $toScenario
     * @return BaseObject|null
     */
    public function find($identifier, string $toScenario = null)
    {
        if ($identifier instanceof BaseObject) {
            $this->addToCache($identifier);

            return $identifier;
        } elseif ($identifier instanceof Record) {
            return $this->findByRecord($identifier, $toScenario);
        }

        return null;
    }

    /**
     * @param $identifier
     * @param string $toScenario
     * @return BaseObject
     * @throws ObjectNotFoundException
     */
    public function get($identifier, string $toScenario = null): BaseObject
    {
        // Find model by ID
        if (!$object = $this->find($identifier, $toScenario)) {
            $this->notFoundException();
        }

        return $object;
    }

    /*******************************************
     * FIND/GET BY QUERY
     *******************************************/

    /**
     * @param QueryInterface $query
     * @param string $toScenario
     * @return BaseObject[]
     */
    public function findAllByQuery(QueryInterface $query, string $toScenario = null): array
    {
        $objects = array();

        foreach ($query->all() as $record) {
            $objects[] = $this->findByRecord($record, $toScenario);
        }

        return $objects;
    }

    /**
     * @param QueryInterface $query
     * @param string $toScenario
     * @return BaseObject|null
     */
    public function findByQuery(QueryInterface $query, string $toScenario = null)
    {
        /** @var Record $record */
        if (!$record = $query->one()) {
            return null;
        }

        return $this->findByRecord($record, $toScenario);
    }

    /*******************************************
     * FIND/GET BY CONDITION
     *******************************************/

    /**
     * @param $condition
     * @param string $toScenario
     * @return BaseObject[]
     */
    public function findAllByCondition($condition, string $toScenario = null): array
    {
        $objects = [];

        // Find record in db
        if ($records = $this->findAllRecordsByCondition($condition)) {
            foreach ($records as $record) {
                $objects[] = $this->findByRecord($record, $toScenario);
            }
        }

        return $objects;
    }

    /**
     * @param $condition
     * @param string $toScenario
     * @return BaseObject[]
     * @throws ObjectNotFoundException
     */
    public function getAllByCondition($condition, string $toScenario = null): array
    {
        if (!$objects = $this->findAllByCondition($condition, $toScenario)) {
            $this->notFoundByConditionException($condition);
        }

        return $objects;
    }

    /**
     * @param $condition
     * @param string $toScenario
     * @return BaseObject|null
     */
    public function findByCondition($condition, string $toScenario = null)
    {
        // Find record in db
        if ($record = $this->findRecordByCondition($condition)) {
            return $this->findByRecord($record, $toScenario);
        }

        return null;
    }

    /**
     * @param $condition
     * @param string $toScenario
     * @return BaseObject
     * @throws ObjectNotFoundException
     */
    public function getByCondition($condition, string $toScenario = null): BaseObject
    {
        if (!$object = $this->findByCondition($condition, $toScenario)) {
            $this->notFoundByConditionException($condition);
        }

        return $object;
    }

    /*******************************************
     * FIND/GET BY CRITERIA
     *******************************************/

    /**
     * @param $criteria
     * @param string $toScenario
     * @return BaseObject[]
     */
    public function findAllByCriteria($criteria, string $toScenario = null): array
    {
        $objects = [];

        // Find record in db
        if ($records = $this->findAllRecordsByCriteria($criteria)
        ) {
            foreach ($records as $record) {
                $objects[] = $this->findByRecord($record, $toScenario);
            }
        }

        return $objects;
    }

    /**
     * @param $criteria
     * @param string $toScenario
     * @return BaseObject[]
     * @throws ObjectNotFoundException
     */
    public function getAllByCriteria($criteria, string $toScenario = null): array
    {
        if (!$objects = $this->findAllByCriteria($criteria, $toScenario)) {
            $this->notFoundByCriteriaException($criteria);
        }

        return $objects;
    }

    /**
     * @param $criteria
     * @param string $toScenario
     * @return BaseObject|null
     */
    public function findByCriteria($criteria, string $toScenario = null)
    {
        // Find record in db
        if ($record = $this->findRecordByCriteria($criteria)) {
            return $this->findByRecord($record, $toScenario);
        }

        return null;
    }

    /**
     * @param $criteria
     * @param string $toScenario
     * @return BaseObject
     * @throws ObjectNotFoundException
     */
    public function getByCriteria($criteria, string $toScenario = null): BaseObject
    {
        if (!$object = $this->findByCriteria($criteria, $toScenario)) {
            $this->notFoundByCriteriaException($criteria);
        }

        return $object;
    }


    /*******************************************
     * FIND/GET BY RECORD
     *******************************************/

    /**
     * @param array $records
     * @param string|null $toScenario
     * @return BaseObject[]
     */
    public function findAllByRecords(array $records, string $toScenario = null): array
    {
        $models = [];

        foreach ($records as $index => $record) {
            $models[$index] = $this->findByRecord($record, $toScenario);
        }

        return $models;
    }

    /**
     * @param array $records
     * @param string|null $toScenario
     * @return BaseObject[]
     * @throws ObjectNotFoundException
     */
    public function getAllByRecords(array $records, string $toScenario = null): array
    {
        $models = $this->findAllByRecords($records, $toScenario);

        if (empty($models)) {
            throw new ObjectNotFoundException("Unable to get from records.");
        }

        return $models;
    }

    /**
     * @param Record $record
     * @param string $toScenario
     * @return BaseObject
     */
    public function findByRecord(Record $record, string $toScenario = null): BaseObject
    {
        // Check addToCache
        if (!$object = $this->findCacheByRecord($record)) {
            // New model
            $object = $this->createFromRecord($record, $toScenario);

            // Cache it
            $this->addToCache($object);
        }

        return $object;
    }

    /**
     * @param Record $record
     * @param string $toScenario
     * @return BaseObject
     */
    public function getByRecord(Record $record, string $toScenario = null): BaseObject
    {
        return $this->findByRecord($record, $toScenario);
    }


    /*******************************************
     * FIND/GET RECORD
     *******************************************/

    /**
     * @param BaseObject $object
     * @return Record|null
     */
    public function findRecordByObject(BaseObject $object)
    {
        return null;
    }

    /**
     * @param BaseObject $object
     * @return Record
     * @throws RecordNotFoundException
     */
    public function getRecordByObject(BaseObject $object): Record
    {
        if (!$record = $this->findRecordByObject($object)) {
            throw new RecordNotFoundException("Record does not exist found.");
        }
        return $record;
    }

    /**
     * @param BaseObject $object
     * @param Record $record
     * @return void
     */
    public function transferToRecord(BaseObject $object, Record $record)
    {
        $record->setAttributes(
            ArrayHelper::toArray($object)
        );
    }

    /**
     * @param BaseObject $object
     * @return Record
     */
    public function toRecord(BaseObject $object): Record
    {
        if (!$record = $this->findRecordByObject($object)) {
            $record = $this->createRecord();
        }

        $this->transferToRecord($object, $record);
        return $record;
    }

    /*******************************************
     * CACHE
     *******************************************/

    /**
     * @param $identifier
     * @return BaseObject|null
     */
    public function findCache($identifier)
    {
        if ($identifier instanceof Record) {
            return $this->findCacheByRecord($identifier);
        }

        return null;
    }

    /**
     * @param Record $record
     * @return BaseObject|null
     */
    public function findCacheByRecord(Record $record)
    {
        return null;
    }

    /**
     * @param BaseObject $object
     * @return static
     */
    public function addToCache(BaseObject $object)
    {
        return $this;
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
                "Object does not exist."
            )
        );
    }

    /**
     * @param null $criteria
     * @throws ObjectNotFoundException
     */
    protected function notFoundByCriteriaException($criteria = null)
    {
        throw new ObjectNotFoundException(
            sprintf(
                'Object does not exist with the criteria "%s".',
                (string)JsonHelper::encode($criteria)
            )
        );
    }

    /**
     * @param null $condition
     * @throws ObjectNotFoundException
     */
    protected function notFoundByConditionException($condition = null)
    {
        throw new ObjectNotFoundException(
            sprintf(
                'Object does not exist with the condition "%s".',
                (string)JsonHelper::encode($condition)
            )
        );
    }
}
