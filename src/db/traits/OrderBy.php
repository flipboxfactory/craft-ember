<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\ember\db\traits;

use craft\db\QueryAbortedException;
use yii\base\Exception;
use yii\db\Connection;
use yii\db\Expression;

/**
 * @property string|array|Expression $orderBy
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait OrderBy
{
    /**
     * @var bool Whether results should be returned in the order specified by [[domain]].
     */
    public $fixedOrder = false;

    /**
     * Sets the ORDER BY part of the query.
     * @param string|array|Expression $columns the columns (and the directions) to be ordered by.
     * Columns can be specified in either a string (e.g. `"id ASC, name DESC"`) or an array
     * (e.g. `['id' => SORT_ASC, 'name' => SORT_DESC]`).
     *
     * The method will automatically quote the column names unless a column contains some parenthesis
     * (which means the column contains a DB expression).
     *
     * Note that if your order-by is an expression containing commas, you should always use an array
     * to represent the order-by information. Otherwise, the method will not be able to correctly determine
     * the order-by columns.
     *
     * Since version 2.0.7, an [[Expression]] object can be passed to specify the ORDER BY part explicitly in plain SQL.
     * @return $this the query object itself
     * @see addOrderBy()
     */
    abstract public function orderBy($columns);

    /**
     * Applies the 'fixedOrder' and 'orderBy' params to the query being prepared.
     *
     * @param Connection|null $db The database connection used to generate the SQL statement.
     * If this parameter is not given, the `db` application component will be used.
     */
    protected function applyOrderByParams(Connection $db)
    {
        if ($this->orderBy === null) {
            return;
        }

        // Any other empty value means we should set it
        if (empty($this->orderBy)) {
            $this->applyEmptyOrderByParams($db);
        }

        $this->orderBy($this->orderBy);
    }

    /**
     * @param Connection $db
     */
    protected function applyEmptyOrderByParams(Connection $db)
    {
        $this->orderBy = ['dateCreated' => SORT_DESC];
    }
}
