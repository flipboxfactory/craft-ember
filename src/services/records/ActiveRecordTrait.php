<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\services\records;

use flipbox\craft\ember\helpers\QueryHelper;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait ActiveRecordTrait
{
    /**
     * @return string
     */
    public abstract static function recordClass(): string;

    /**
     * @return Connection
     */
    protected static function getDb(): Connection
    {
        /** @var ActiveRecord $recordClass */
        $recordClass = static::recordClass();

        return $recordClass::getDb();
    }

    /*******************************************
     * CREATE
     *******************************************/

    /**
     * @param array $attributes
     * @return ActiveRecord
     */
    public function create(array $attributes = []): ActiveRecord
    {
        /** @var string $recordClass */
        $recordClass = static::recordClass();

        /** @var ActiveRecord $record */
        $record = new $recordClass();

        // Do we need to set properties too
        if (!empty($attributes)) {
            $record->setAttributes($attributes);
        }

        return $record;
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
        /** @var ActiveRecord $recordClass */
        $recordClass = static::recordClass();

        $query = $recordClass::find();

        QueryHelper::configure(
            $query,
            $this->prepareQueryConfig($config)
        );

        return $query;
    }

    /**
     * @param array $config
     * @return array
     */
    protected function prepareQueryConfig($config = [])
    {
        return $config;
    }
}