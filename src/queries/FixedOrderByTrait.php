<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queries;

use craft\db\Connection as CraftConnection;
use craft\db\FixedOrderExpression;
use craft\db\QueryAbortedException;
use craft\helpers\StringHelper;
use yii\base\Exception;
use yii\db\Connection;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @method void traitApplyEmptyOrderByParams(Connection $db)
 */
trait FixedOrderByTrait
{
    use OrderByTrait {
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
