<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions;

use yii\web\UnauthorizedHttpException;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @property int $statusCodeUnauthorized
 * @property string $messageUnauthorized
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
     * @throws UnauthorizedHttpException
     */
    public function checkAccess(...$params)
    {
        if ($this->checkAccess) {
            if (call_user_func_array($this->checkAccess, $params) === false) {
                /** @noinspection PhpVoidFunctionResultUsedInspection */
                return $this->handleUnauthorizedResponse();
            };
        }

        return true;
    }

    /**
     * HTTP forbidden response code
     *
     * @return int
     */
    protected function statusCodeUnauthorized(): int
    {
        return $this->statusCodeUnauthorized ?? 403;
    }

    /**
     * @return string
     */
    protected function messageUnauthorized(): string
    {
        return $this->messageUnauthorized ?? 'Unable to perform action.';
    }

    /**
     * @throws UnauthorizedHttpException
     */
    protected function handleUnauthorizedResponse()
    {
        throw new UnauthorizedHttpException(
            $this->messageUnauthorized(),
            $this->statusCodeUnauthorized()
        );
    }
}
