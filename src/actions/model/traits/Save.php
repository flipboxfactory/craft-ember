<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\model\traits;

use flipbox\ember\actions\traits\Populate;
use yii\base\Model;

/**
 * @method Model populate(Model $model)
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait Save
{
    use Populate, Manage;

    /**
     * @param Model $model
     * @return mixed
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
