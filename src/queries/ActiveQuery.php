<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queries;

use craft\base\ClonefixTrait;
use craft\db\QueryAbortedException;

/**
 * This class provides Element Query like functionality to Yii's ActiveQuery.  Primarily,
 * the query can throw a `QueryAbortedException` which is caught and handled appropriately.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class ActiveQuery extends \yii\db\ActiveQuery
{
    use ClonefixTrait;

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
