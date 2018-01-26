<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\model\traits;

use flipbox\ember\actions\traits\Manage as BaseManage;
use yii\base\Model;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait Manage
{
    use BaseManage {
        runInternal as traitRunInternal;
    }

    /**
     * @param Model $model
     * @return bool
     */
    abstract protected function performAction(Model $model): bool;

    /**
     * @inheritdoc
     * @param Model $model
     * @return Model
     */
    protected function runInternal(Model $model)
    {
        return $this->traitRunInternal($model);
    }
}
