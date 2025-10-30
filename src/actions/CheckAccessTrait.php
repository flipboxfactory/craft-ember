<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions;

use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @property int $errorCodeForbidden
 * @property string $messageForbidden
 */
trait CheckAccessTrait
{
    /**
     * @var null|callable
     */
    public $checkAccess = null;

    /**
     * @param mixed ...$params
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function checkAccess(...$params): mixed
    {
        if ($this->checkAccess) {
            if (call_user_func_array($this->checkAccess, $params) === false) {
                return $this->handleForbiddenResponse();
            };
        }

        return true;
    }

    /**
     * HTTP forbidden response code
     *
     * @return int|null
     */
    protected function errorCodeForbidden(): ?int
    {
        return $this->errorCodeForbidden ?? null;
    }

    /**
     * @return string
     */
    protected function messageForbidden(): string
    {
        return $this->messageForbidden ?? 'Unable to perform action.';
    }

    /**
     * @throws ForbiddenHttpException
     */
    protected function handleForbiddenResponse()
    {
        throw new ForbiddenHttpException(
            $this->messageForbidden(),
            $this->errorCodeForbidden()
        );
    }
}
