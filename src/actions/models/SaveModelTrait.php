<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\models;

use flipbox\craft\ember\actions\PopulateTrait;
use yii\base\Model;

/**
 * @method Model populate(Model $model)
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait SaveModelTrait
{
    use PopulateTrait, ManageModelTrait;

    /**
     * @param Model $model
     * @return mixed
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function runInternal(Model $model)
    {
        // Populate
        $this->populate($model);

        // Check access
        if (($access = $this->checkAccess($model)) !== true) {
            return $access;
        }

        if (!$this->performAction($model)) {
            return $this->handleFailResponse($model);
        }

        return $this->handleSuccessResponse($model);
    }
}
