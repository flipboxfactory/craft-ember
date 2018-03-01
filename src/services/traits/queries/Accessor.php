<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits\queries;

use flipbox\ember\exceptions\NotFoundException;
use flipbox\ember\helpers\RecordHelper;

/**
 * A set of robust methods commonly used to retrieve data from the attached database.  An optional
 * cache layer can be applied to circumvent heavy queries.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait Accessor
{
    use BaseAccessor;

    /*******************************************
     * FIND / GET
     *******************************************/

    /**
     * @return array[]
     */
    public function findAll()
    {
        return $this->findAllByCondition([]);
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
     * @return array
     */
    public function findAllByCondition($condition = []): array
    {
        return $this->findAllByCriteria(
            RecordHelper::conditionToCriteria($condition)
        );
    }

    /**
     * @param array $condition
     * @return array
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
     * @return array
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
     * @return array
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
}
