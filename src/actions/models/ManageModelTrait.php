<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\models;

use Craft;
use flipbox\craft\ember\actions\CheckAccessTrait;
use yii\base\Model;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @method Model traitRunInternal(Model $model)
 */
trait ManageModelTrait
{
    use CheckAccessTrait;

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

    /**
     * HTTP success response code
     *
     * @return int
     */
    protected function statusCodeSuccess(): int
    {
        return $this->statusCodeSuccess ?? 200;
    }

    /**
     * HTTP fail response code
     *
     * @return int
     */
    protected function statusCodeFail(): int
    {
        return $this->statusCodeFail ?? 400;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function handleSuccessResponse($data)
    {
        // Success status code
        Craft::$app->getResponse()->setStatusCode($this->statusCodeSuccess());
        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function handleFailResponse($data)
    {
        Craft::$app->getResponse()->setStatusCode($this->statusCodeFail());
        return $data;
    }


}
