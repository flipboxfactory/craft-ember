<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\models;

use flipbox\craft\ember\actions\LookupTrait;
use yii\base\Model;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait LookupModelTrait
{
    use LookupTrait;

    /**
     * @inheritdoc
     * @param Model $model
     * @return Model|Response
     */
    abstract protected function runInternal(Model $model);
}
