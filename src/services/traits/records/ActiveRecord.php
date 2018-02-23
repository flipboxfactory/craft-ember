<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits\records;

use flipbox\ember\helpers\QueryHelper;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord as Record;
use yii\db\Connection;

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
     * @return Connection
     */
    protected static function getDb(): Connection
    {
        /** @var Record $recordClass */
        $recordClass = static::recordClass();

        return $recordClass::getDb();
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
        /** @var Record $recordClass */
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
