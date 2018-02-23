<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits;

use flipbox\ember\interfaces\IdAttributeInterface;
use yii\base\BaseObject;
use yii\db\ActiveRecord as Record;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @deprecated
 */
trait AccessorByIdOrString
{
    use Accessor, IdAccessor, StringAccessor {
        find as parentFind;
        findCache as parentFindCache;
        findCacheByRecord as parentFindCacheByRecord;
    }

    /*******************************************
     * FIND OVERRIDES
     *******************************************/

    /**
     * @inheritdoc
     */
    public function find($identifier, string $toScenario = null)
    {
        if ($model = $this->parentFind($identifier, $toScenario)) {
            return $model;
        }

        if (is_numeric($identifier)) {
            return $this->findById($identifier);
        }

        if (is_string($identifier)) {
            return $this->findByString($identifier);
        }

        return null;
    }

    /*******************************************
     * CACHE OVERRIDES
     *******************************************/

    /**
     * @inheritdoc
     */
    public function findCache($identifier)
    {
        if ($model = $this->parentFindCache($identifier)) {
            return $model;
        }

        if (is_numeric($identifier)) {
            return $this->findCacheById($identifier);
        }

        if (is_string($identifier)) {
            return $this->findCacheByString($identifier);
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function addToCache(BaseObject $object)
    {
        if ($object instanceof IdAttributeInterface) {
            $this->cacheById($object);
        }

        $this->cacheByString($object);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function findCacheByRecord(Record $record)
    {
        if ($record instanceof IdAttributeInterface) {
            $id = $record->getId();
            if (is_numeric($id)) {
                return $this->findCacheById($id);
            }
        }

        $string = $record->{$this->recordStringProperty()};
        if (is_string($string)) {
            return $this->findCacheByString($string);
        }

        return null;
    }

    /*******************************************
     * RECORD
     *******************************************/

    /**
     * @param BaseObject $object
     * @return Record|null
     */
    public function findRecordByObject(BaseObject $object)
    {
        if ($object instanceof IdAttributeInterface) {
            $id = $object->getId();
            if (is_numeric($id)) {
                return $this->findRecordById($id);
            }
        }

        $string = $this->stringValue($object);
        if (is_string($string)) {
            return $this->findRecordByString($string);
        }

        return null;
    }
}
