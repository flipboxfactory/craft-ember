<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\ember\db;

use craft\base\ClonefixTrait;
use craft\db\QueryAbortedException;

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
        } catch (QueryAbortedException $e) {
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
            if (false === ($result = parent::one($db))) {
                $result = null;
            }
        } catch (QueryAbortedException $e) {
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
        } catch (QueryAbortedException $e) {
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
        } catch (QueryAbortedException $e) {
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
        } catch (QueryAbortedException $e) {
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
        } catch (QueryAbortedException $e) {
            return false;
        }
    }
}
