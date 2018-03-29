<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\record\traits;

use flipbox\ember\actions\traits\Manage as BaseManage;
use yii\db\ActiveRecord;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @method ActiveRecord traitRunInternal(ActiveRecord $record)
 */
trait Manage
{
    use BaseManage {
        runInternal as traitRunInternal;
    }

    /**
     * @param ActiveRecord $record
     * @return bool
     */
    abstract protected function performAction(ActiveRecord $record): bool;

    /**
     * @inheritdoc
     * @param ActiveRecord $record
     * @return ActiveRecord
     */
    protected function runInternal(ActiveRecord $record)
    {
        return $this->traitRunInternal($record);
    }
}