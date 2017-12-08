<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits;

use Craft;
use craft\db\ActiveRecord as Record;
use flipbox\ember\exceptions\RecordNotFoundException;
use flipbox\ember\helpers\QueryHelper;
use flipbox\ember\helpers\RecordHelper;
use yii\db\ActiveQuery;

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
     * @param array $config
     * @return \yii\db\ActiveQuery
     */
    public function getRecordQuery($config = []): ActiveQuery
    {
        /** @var Record $recordClass */
        $recordClass = $this->recordClass();

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
    public function createRecord(array $attributes = [], string $toScenario = null)
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

    /**
     * @param $condition
     * @param string $toScenario
     * @return Record|null
     */
    public function findRecordByCondition($condition, string $toScenario = null)
    {
        if (empty($condition)) {
            return null;
        }

        return $this->findRecordByCriteria(
            RecordHelper::conditionToCriteria($condition),
            $toScenario
        );
    }

    /**
     * @param $criteria
     * @param string $toScenario
     * @return Record
     */
    public function findRecordByCriteria($criteria, string $toScenario = null)
    {
        $query = $this->getRecordQuery($criteria);

        /** @var Record $record */
        if ($record = $query->one()) {
            // Set scenario
            if ($toScenario) {
                $record->setScenario($toScenario);
            }
        }

        return $record;
    }

    /**
     * @param $condition
     * @param string $toScenario
     * @return Record
     * @throws RecordNotFoundException
     */
    public function getRecordByCondition($condition, string $toScenario = null)
    {
        if (!$record = $this->findRecordByCondition($condition, $toScenario)) {
            $this->notFoundRecordException();
        }

        return $record;
    }

    /**
     * @param $criteria
     * @param string $toScenario
     * @return Record
     * @throws RecordNotFoundException
     */
    public function getRecordByCriteria($criteria, string $toScenario = null)
    {
        if (!$record = $this->findRecordByCriteria($criteria, $toScenario)) {
            $this->notFoundRecordException();
        }

        return $record;
    }


    /**
     * @param string $toScenario
     * @return Record[]
     */
    public function findAllRecords(string $toScenario = null)
    {
        return $this->findAllRecordsByCondition(null, $toScenario);
    }

    /**
     * @param array $condition
     * @param string $toScenario
     * @return Record[]
     */
    public function findAllRecordsByCondition($condition = [], string $toScenario = null)
    {
        return $this->findAllRecordsByCriteria(
            RecordHelper::conditionToCriteria($condition),
            $toScenario
        );
    }

    /**
     * @param array $criteria
     * @param string $toScenario
     * @return Record[]
     */
    public function findAllRecordsByCriteria($criteria = [], string $toScenario = null)
    {
        $query = $this->getRecordQuery($criteria);

        /** @var Record[] $record s */
        $records = $query->all();

        // Set scenario
        if ($toScenario) {

            /** @var Record $record */
            foreach ($records as $record) {
                // Set scenario
                $record->setScenario($toScenario);
            }
        }

        return $records;
    }


    /**
     * @deprecated
     * @param array $condition
     * @param string $toScenario
     * @return Record[]
     * @throws RecordNotFoundException
     */
    public function getAllRecords($condition = [], string $toScenario = null)
    {
        Craft::$app->getDeprecator()->log(
            __METHOD__,
            'Use the "getAllRecordsByCondition" method'
        );

        return $this->getAllRecordsByCondition($condition, $toScenario);
    }

    /**
     * @param array $condition
     * @param string $toScenario
     * @return Record[]
     * @throws RecordNotFoundException
     */
    public function getAllRecordsByCondition($condition = [], string $toScenario = null)
    {
        if (!$records = $this->findAllRecordsByCondition($condition, $toScenario)) {
            $this->notFoundRecordException();
        }

        return $records;
    }

    /**
     * @param array $criteria
     * @param string $toScenario
     * @return Record[]
     * @throws RecordNotFoundException
     */
    public function getAllRecordsByCriteria($criteria = [], string $toScenario = null)
    {
        if (!$records = $this->findAllRecordsByCriteria($criteria, $toScenario)) {
            $this->notFoundRecordException();
        }

        return $records;
    }

    /*******************************************
     * EXCEPTIONS
     *******************************************/

    /**
     * @throws RecordNotFoundException
     */
    protected function notFoundRecordException()
    {
        throw new RecordNotFoundException(
            sprintf(
                "Record does not exist."
            )
        );
    }
}
