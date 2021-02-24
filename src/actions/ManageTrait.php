<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait ManageTrait
{
    use CheckAccessTrait, ResponseTrait;

    /**
     * @param mixed $data
     * @return bool
     */
    abstract protected function performAction($data): bool;

    /**
     * @param $data
     * @return mixed
     * @throws \yii\web\HttpException
     */
    protected function runInternal($data)
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
