<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queries;

use craft\base\ClonefixTrait;
use craft\db\QueryAbortedException;
use flipbox\craft\ember\exceptions\RecordNotFoundException;
use flipbox\craft\ember\records\ActiveRecord;
use yii\db\Connection;

/**
 * This class provides Element Query like functionality to Yii's ActiveQuery.  Primarily,
 * the query can throw a `QueryAbortedException` which is caught and handled appropriately.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class ActiveQuery extends \craft\db\ActiveQuery
{
    use ClonefixTrait;

    /**
     * Executes query and returns all results as an array.  If results are not found, an exception is
     * thrown as we explicitly expect results.
     *
     * @param Connection $db the DB connection used to create the DB command.
     * If null, the DB connection returned by [[modelClass]] will be used.
     * @return array|ActiveRecord[] the query results. If the query results in nothing, an empty array will be returned.
     * @throws RecordNotFoundException
     */
    public function requireAll($db = null)
    {
        $records = $this->all($db);

        if (empty($records)) {
            throw new RecordNotFoundException(
                sprintf(
                    "Records not found."
                )
            );
        }

        return $records;
    }

    /**
     * Executes query and returns a single result.  If a result is not found, an exception is
     * thrown as we explicitly expect a result.
     *
     * @param Connection|null $db the DB connection used to create the DB command.
     * If `null`, the DB connection returned by [[modelClass]] will be used.
     * @return ActiveRecord|array a single row of query result. Depending on the setting of [[asArray]],
     * the query result may be either an array or an ActiveRecord object. `null` will be returned
     * if the query results in nothing.
     * @throws RecordNotFoundException
     */
    public function requireOne($db = null)
    {
        if (null === ($record = $this->one($db))) {
            throw new RecordNotFoundException(
                sprintf(
                    "Record not found."
                )
            );
        }

        return $record;
    }

    /**
     * @inheritdoc
     */
    public function all($db = null)
    {
        try {
            return parent::all($db);
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (QueryAbortedException $e) {
            return [];
        }
    }

    /**
     * @inheritdoc
     */
    public function one($db = null)
    {
        $limit = $this->limit;
        $this->limit = 1;
        try {
            $result = parent::one($db);
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (QueryAbortedException $e) {
            $result = null;
        }
        $this->limit = $limit;
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function scalar($db = null)
    {
        $limit = $this->limit;
        $this->limit = 1;
        try {
            $result = parent::scalar($db);
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (QueryAbortedException $e) {
            $result = false;
        }
        $this->limit = $limit;
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function column($db = null)
    {
        try {
            return parent::column($db);
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (QueryAbortedException $e) {
            return [];
        }
    }

    /**
     * @inheritdoc
     */
    public function exists($db = null)
    {
        try {
            return parent::exists($db);
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (QueryAbortedException $e) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    protected function queryScalar($selectExpression, $db)
    {
        try {
            return parent::queryScalar($selectExpression, $db);
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (QueryAbortedException $e) {
            return false;
        }
    }
}
