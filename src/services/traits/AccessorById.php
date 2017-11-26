<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\services\traits;

use flipbox\ember\interfaces\IdAttributeInterface;
use yii\db\ActiveRecord as Record;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait AccessorById
{
    use Accessor, IdAccessor {
        find as parentFind;
        findCache as parentFindCache;
    }

    /*******************************************
     * FIND/GET
     *******************************************/

    /**
     * @inheritdoc
     */
    public function find($identifier, string $toScenario = null)
    {
        if ($object = $this->parentFind($identifier, $toScenario)) {
            return $object;
        }

        if (!is_numeric($identifier)) {
            return null;
        }

        return $this->findById($identifier, $toScenario);
    }

    /*******************************************
     * CACHE
     *******************************************/

    /**
     * @inheritdoc
     */
    public function findCache($identifier)
    {
        if ($object = $this->parentFindCache($identifier)) {
            return $object;
        }

        if (!is_numeric($identifier)) {
            return null;
        }

        return $this->findCacheById($identifier);
    }

    /**
     * @inheritdoc
     */
    public function addToCache(IdAttributeInterface $object)
    {
        $this->cacheById($object);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function findCacheByRecord(IdAttributeInterface $record)
    {
        $id = $record->getId();
        if (is_numeric($id)) {
            return $this->findCacheById($id);
        }

        return null;
    }

    /*******************************************
     * RECORD
     *******************************************/

    /**
     * @param IdAttributeInterface $object
     * @return Record|null
     */
    public function findRecordByObject(IdAttributeInterface $object)
    {
        $idValue = $object->getId();
        if (!is_numeric($idValue)) {
            return null;
        }

        return $this->findRecordById($idValue);
    }
}
