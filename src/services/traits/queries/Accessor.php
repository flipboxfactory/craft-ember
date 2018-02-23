<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits\queries;

use Craft;
use flipbox\ember\exceptions\NotFoundException;
use flipbox\ember\helpers\RecordHelper;
use yii\base\BaseObject;
use yii\caching\Dependency;
use yii\db\Connection;
use yii\db\QueryInterface;

/**
 * A set of robust methods commonly used to retrieve data from the attached database.  An optional
 * cache layer can be applied to circumvent heavy queries.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait Accessor
{
    /*******************************************
     * QUERY
     *******************************************/

    /**
     * @param array $config
     * @return \yii\db\ActiveQuery
     */
    abstract public function getQuery($config = []): QueryInterface;

    /*******************************************
     * CACHE
     *******************************************/

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
    protected static function getDb(): Connection
    {
        return Craft::$app->getDb();
    }

    /*******************************************
     * FIND / GET
     *******************************************/

    /**
     * @return array[]
     */
    public function findAll()
    {
        return $this->findAllByCondition(null);
    }

    /**
     * @param $identifier
     * @return mixed|null
     */
    public function find($identifier)
    {
        return $this->findByCondition($identifier);
    }

    /**
     * @param $identifier
     * @return mixed
     * @throws NotFoundException
     */
    public function get($identifier)
    {
        if (null === ($object = $this->find($identifier))) {
            $this->notFoundException();
        }

        return $object;
    }


    /*******************************************
     * ONE CONDITION
     *******************************************/

    /**
     * @param $condition
     * @return mixed|null
     */
    public function findByCondition($condition)
    {
        return $this->findByCriteria(
            RecordHelper::conditionToCriteria($condition)
        );
    }

    /**
     * @param $condition
     * @return mixed
     * @throws NotFoundException
     */
    public function getByCondition($condition)
    {
        if (null === ($object = $this->findByCondition($condition))) {
            $this->notFoundException();
        }

        return $object;
    }


    /*******************************************
     * ONE CRITERIA
     *******************************************/

    /**
     * @param $criteria
     * @return mixed|null
     */
    public function findByCriteria($criteria)
    {
        $object = $this->queryOne(
            $this->getQuery($criteria)
        );

        return $object;
    }

    /**
     * @param $criteria
     * @return mixed
     * @throws NotFoundException
     */
    public function getByCriteria($criteria)
    {
        if (null === ($record = $this->findByCriteria($criteria))) {
            $this->notFoundException();
        }

        return $record;
    }


    /*******************************************
     * ALL CONDITION
     *******************************************/

    /**
     * @param array $condition
     * @return BaseObject[]
     */
    public function findAllByCondition($condition = []): array
    {
        return $this->findAllByCriteria(
            RecordHelper::conditionToCriteria($condition)
        );
    }

    /**
     * @param array $condition
     * @return BaseObject[]
     * @throws NotFoundException
     */
    public function getAllByCondition($condition = []): array
    {
        $records = $this->findAllByCondition($condition);
        if (empty($records)) {
            $this->notFoundException();
        }

        return $records;
    }

    /*******************************************
     * ALL CRITERIA
     *******************************************/

    /**
     * @param array $criteria
     * @return BaseObject[]
     */
    public function findAllByCriteria($criteria = []): array
    {
        $records = $this->queryAll(
            $this->getQuery($criteria)
        );

        return $records;
    }

    /**
     * @param array $criteria
     * @return BaseObject[]
     * @throws NotFoundException
     */
    public function getAllByCriteria($criteria = []): array
    {
        $records = $this->findAllByCriteria($criteria);
        if (empty($records)) {
            $this->notFoundException();
        }

        return $records;
    }


    /*******************************************
     * CACHE
     *******************************************/

    /**
     * @param QueryInterface $query
     * @return mixed|null
     */
    protected function queryOne(QueryInterface $query)
    {
        $db = static::getDb();

        try {
            if (false === ($cacheDuration = static::cacheDuration())) {
                return $query->one($db);
            }

            $result = $db->cache(function ($db) use ($query) {
                return $query->one($db);
            }, $cacheDuration, static::cacheDependency());
        } catch (\Exception $e) {
            return null;
        }

        return $result;
    }

    /**
     * @param QueryInterface $query
     * @return mixed[]
     */
    protected function queryAll(QueryInterface $query)
    {
        $db = static::getDb();

        try {
            if (false === ($cacheDuration = static::cacheDuration())) {
                return $query->all($db);
            }

            $results = $db->cache(function ($db) use ($query) {
                return $query->all($db);
            }, $cacheDuration, static::cacheDependency());
        } catch (\Exception $e) {
            return [];
        }

        return $results;
    }

    /*******************************************
     * EXCEPTIONS
     *******************************************/

    /**
     * @throws NotFoundException
     */
    protected function notFoundException()
    {
        throw new NotFoundException(
            sprintf(
                "Results not found."
            )
        );
    }
}
