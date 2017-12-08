<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits;

use flipbox\ember\exceptions\ObjectNotFoundException;
use flipbox\ember\exceptions\RecordNotFoundException;
use yii\base\BaseObject;
use yii\db\ActiveRecord as Record;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait StringAccessor
{
    /**
     * @var BaseObject[]
     */
    protected $cacheByString = [];

    /**
     * @return string
     */
    abstract protected function stringProperty(): string;

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
    abstract protected function findByRecord(Record $record, string $toScenario = null): BaseObject;

    /**
     * @param Record $record
     * @param string|null $toScenario
     * @return BaseObject
     */
    abstract protected function createFromRecord(Record $record, string $toScenario = null): BaseObject;


    /*******************************************
     * STRING
     *******************************************/

    /**
     * @return string
     */
    protected function recordStringProperty(): string
    {
        return $this->stringProperty();
    }

    /**
     * @param BaseObject $object
     * @return string
     */
    protected function stringValue(BaseObject $object)
    {
        $property = $this->stringProperty();

        return $object->{$property};
    }

    /*******************************************
     * FIND/GET BY STRING
     *******************************************/

    /**
     * @param string $string
     * @param string|null $toScenario
     * @return BaseObject|null
     */
    public function findByString(string $string, string $toScenario = null)
    {

        // Check cache
        if (!$model = $this->findCacheByString($string)) {
            // Find record in db
            if ($record = $this->findRecordByString($string)) {
                $model = $this->findByRecord($record, $toScenario);
            } else {
                $this->cacheByString[$string] = null;

                return null;
            }
        }

        return $model;
    }

    /**
     * @param string $string
     * @param string|null $toScenario
     * @return BaseObject|null
     * @throws ObjectNotFoundException
     */
    public function getByString(string $string, string $toScenario = null): BaseObject
    {

        if (!$model = $this->findByString($string, $toScenario)) {
            $this->notFoundByStringException($string);
        }

        return $model;
    }

    /**
     * @param string $string
     * @param string|null $toScenario
     * @return BaseObject|null
     */
    public function freshFindByString(string $string, string $toScenario = null)
    {

        // Find record in db
        if (!$record = $this->findRecordByString($string)) {
            return null;
        }

        return $this->createFromRecord($record, $toScenario);
    }

    /**
     * @param string $string
     * @param string|null $toScenario
     * @return BaseObject
     * @throws ObjectNotFoundException
     */
    public function freshGetByString(string $string, string $toScenario = null): BaseObject
    {

        if (!$model = $this->freshFindByString($string, $toScenario)) {
            $this->notFoundByStringException($string);
        }

        return $model;
    }

    /*******************************************
     * CACHE
     *******************************************/

    /**
     * Find an existing cache by string
     *
     * @param string $string
     * @return null
     */
    public function findCacheByString(string $string)
    {

        // Check if already in cache
        if (!$this->isCachedByString($string)) {
            return null;
        }

        return $this->cacheByString[$string];
    }

    /**
     * Identify whether in cache by string
     *
     * @param string $string
     * @return bool
     */
    private function isCachedByString(string $string): bool
    {
        return array_key_exists($string, $this->cacheByString);
    }

    /**
     * @param BaseObject $model
     * @return static
     */
    protected function cacheByString(BaseObject $model)
    {

        $stringValue = $this->stringValue($model);

        if (null === $stringValue) {
            return $this;
        }

        // Check if already in cache
        if (!$this->isCachedByString($stringValue)) {
            // Cache it
            $this->cacheByString[$stringValue] = $model;
        }

        return $this;
    }

    /*******************************************
     * RECORD BY STRING
     *******************************************/

    /**
     * @param string $string
     * @param string|null $toScenario
     * @return Record|null
     */
    public function findRecordByString(string $string, string $toScenario = null)
    {
        return $this->findRecordByCondition(
            [
                $this->recordStringProperty() => $string
            ],
            $toScenario
        );
    }

    /**
     * @param string $string
     * @param string|null $toScenario
     * @throws RecordNotFoundException
     * @return Record|null
     */
    public function getRecordByString(string $string, string $toScenario = null)
    {
        if (!$record = $this->findRecordByString($string, $toScenario)) {
            $this->notFoundRecordByStringException($string);
        }

        return $record;
    }

    /*******************************************
     * EXCEPTIONS
     *******************************************/

    /**
     * @param string|null $string
     * @throws ObjectNotFoundException
     */
    protected function notFoundByStringException(string $string = null)
    {

        throw new ObjectNotFoundException(
            sprintf(
                'Object does not exist with the string "%s".',
                (string)$string
            )
        );
    }

    /**
     * @param string|null $string
     * @throws RecordNotFoundException
     */
    protected function notFoundRecordByStringException(string $string = null)
    {

        throw new RecordNotFoundException(
            sprintf(
                'Record does not exist with the string "%s".',
                (string)$string
            )
        );
    }
}
