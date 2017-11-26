<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/ember/license
 * @link       https://www.flipboxfactory.com/ember/domains/
 */

namespace flipbox\ember\db\traits;

use craft\db\Connection as CraftConnection;
use craft\db\FixedOrderExpression;
use craft\db\QueryAbortedException;
use craft\helpers\StringHelper;
use yii\base\Exception;
use yii\db\Connection;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait FixedOrderBy
{
    use OrderBy {
        applyEmptyOrderByParams as traitApplyEmptyOrderByParams;
    }

    /**
     * @var bool Whether results should be returned in the order specified by [[domain]].
     */
    public $fixedOrder = false;

    /**
     * @return string
     */
    abstract protected function fixedOrderColumn(): string;

    /**
     * @param Connection $db
     * @throws Exception
     * @throws QueryAbortedException
     */
    protected function applyEmptyOrderByParams(Connection $db)
    {
        if ($this->fixedOrder) {
            $values = $this->{$this->fixedOrderColumn()};
            if (!is_array($values)) {
                $values = is_string($values) ? StringHelper::split($values) : [$values];
            }

            if (empty($values)) {
                throw new QueryAbortedException;
            }

            // Order the elements in the exact order that the Search service returned them in
            if (!$db instanceof CraftConnection) {
                throw new Exception('The database connection doesn\'t support fixed ordering.');
            }

            $this->orderBy = [new FixedOrderExpression($this->fixedOrderColumn(), $values, $db)];
        } else {
            $this->traitApplyEmptyOrderByParams($db);
        }
    }
}
