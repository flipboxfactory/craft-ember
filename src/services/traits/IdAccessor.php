<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\services\traits;

use flipbox\ember\exceptions\ObjectNotFoundException;
use flipbox\ember\exceptions\RecordNotFoundException;
use flipbox\ember\interfaces\IdAttributeInterface;
use yii\base\BaseObject;
use yii\db\ActiveRecord as Record;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait IdAccessor
{
    /**
     * @var BaseObject[]
     */
    protected $cacheById = [];

    /**
     * @param $condition
     * @param string|null $toScenario
     * @return mixed
     */
    abstract public function findRecordByCondition($condition, string $toScenario = null);

    /**
     * @param Record $record
     * @param string|null $toScenario
     * @return BaseObject
     */
    abstract public function findByRecord(Record $record, string $toScenario = null): BaseObject;

    /**
     * @param Record $record
     * @param string|null $toScenario
     * @return BaseObject
     */
    abstract protected function createFromRecord(Record $record, string $toScenario = null): BaseObject;


    /*******************************************
     * FIND/GET BY ID
     *******************************************/

    /**
     * @param int $id
     * @param string|null $toScenario
     * @return BaseObject|null
     */
    public function findById(int $id, string $toScenario = null)
    {
        if (!$object = $this->findCacheById($id)) {
            if ($record = $this->findRecordByCondition(
                ['id' => $id]
            )
            ) {
                $object = $this->findByRecord($record, $toScenario);
            } else {
                $this->cacheById[$id] = null;

                return null;
            }
        }

        return $object;
    }

    /**
     * @param int $id
     * @param string|null $toScenario
     * @return BaseObject
     * @throws ObjectNotFoundException
     */
    public function getById(int $id, string $toScenario = null): BaseObject
    {
        if (!$object = $this->findById($id, $toScenario)) {
            $this->notFoundByIdException($id);
        }

        return $object;
    }

    /**
     * @param int $id
     * @param string|null $toScenario
     * @return BaseObject|null
     */
    public function freshFindById(int $id, string $toScenario = null)
    {
        if (!$record = $this->findRecordById($id)) {
            return null;
        }

        return $this->createFromRecord($record, $toScenario);
    }

    /**
     * @param int $id
     * @param string|null $toScenario
     * @return BaseObject
     * @throws ObjectNotFoundException
     */
    public function freshGetById(int $id, string $toScenario = null): BaseObject
    {
        if (!$object = $this->freshFindById($id, $toScenario)) {
            $this->notFoundByIdException($id);
        }

        return $object;
    }


    /*******************************************
     * CACHE
     *******************************************/

    /**
     * Find an existing cache by ID
     *
     * @param $id
     * @return BaseObject|null
     */
    public function findCacheById(int $id)
    {
        if ($this->isCachedById($id)) {
            return $this->cacheById[$id];
        }

        return null;
    }

    /**
     * Identify whether in cache by ID
     *
     * @param $id
     * @return bool
     */
    protected function isCachedById(int $id)
    {
        return array_key_exists($id, $this->cacheById);
    }

    /**
     * @param IdAttributeInterface $object
     * @return $this
     */
    protected function cacheById(IdAttributeInterface $object)
    {
        if (!$id = $this->isCachedById($object->getId())) {
            // Cache it
            $this->cacheById[$id] = $object;
        }

        return $this;
    }


    /*******************************************
     * RECORD BY ID
     *******************************************/

    /**
     * @param int $id
     * @param string|null $toScenario
     * @return Record|null
     */
    public function findRecordById(int $id, string $toScenario = null)
    {
        return $this->findRecordByCondition(
            [
                'id' => $id
            ],
            $toScenario
        );
    }

    /**
     * @param int $id
     * @param string|null $toScenario
     * @throws RecordNotFoundException
     * @return Record
     */
    public function getRecordById(int $id, string $toScenario = null)
    {
        if (!$record = $this->findRecordById($id, $toScenario)) {
            $this->notFoundRecordByIdException($id);
        }

        return $record;
    }

    /*******************************************
     * EXCEPTIONS
     *******************************************/

    /**
     * @param int|null $id
     * @throws ObjectNotFoundException
     */
    protected function notFoundByIdException(int $id = null)
    {
        throw new ObjectNotFoundException(
            sprintf(
                'Object does not exist with the id "%s".',
                (string)$id
            )
        );
    }

    /**
     * @param int|null $id
     * @throws RecordNotFoundException
     */
    protected function notFoundRecordByIdException(int $id = null)
    {
        throw new RecordNotFoundException(
            sprintf(
                'Record does not exist with the id "%s".',
                (string)$id
            )
        );
    }
}
