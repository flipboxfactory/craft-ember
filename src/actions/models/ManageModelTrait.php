<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\models;

use flipbox\craft\ember\actions\ManageTrait;
use yii\base\Model;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @method Model traitRunInternal(Model $model)
 */
trait ManageModelTrait
{
    use ManageTrait {
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
