<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\models;

use flipbox\craft\ember\actions\CheckAccessTrait;
use flipbox\craft\ember\actions\ResponseTrait;
use yii\base\Model;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @method Model traitRunInternal(Model $model)
 */
trait ManageModelTrait
{
    use CheckAccessTrait, ResponseTrait;

    /**
     * @param Model $model
     * @return bool
     */
    abstract protected function performAction(Model $model): bool;

    /**
     * @param Model $data
     * @return mixed
     * @throws \yii\web\HttpException
     */
    protected function runInternal(Model $data)
    {
        // Check access
        if (($access = $this->checkAccess($data)) !== true) {
            return $access;
        }

        if (!$this->performAction($data)) {
            return $this->handleFailResponse($data);
        }

        return $this->handleSuccessResponse($data);
    }
}
