<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits;

use yii\base\BaseObject;
use yii\db\ActiveRecord as Record;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @deprecated
 */
trait AccessorByString
{
    use Accessor, StringAccessor {
        find as parentFind;
        findCache as parentFindCache;
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

        if (!is_string($identifier)) {
            return null;
        }

        return $this->findByString($identifier, $toScenario);
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

        if (!is_string($identifier)) {
            return null;
        }

        return $this->findCacheByString($identifier);
    }

    /**
     * @inheritdoc
     */
    public function addToCache(BaseObject $object)
    {
        $this->cacheByString($object);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function findCacheByRecord(Record $record)
    {
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
        $stringValue = $this->stringValue($object);
        if (!is_string($stringValue)) {
            return null;
        }

        return $this->findRecordByString($stringValue);
    }
}
