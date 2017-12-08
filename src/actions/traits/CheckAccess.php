<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\traits;

use Craft;
use yii\web\HttpException;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait CheckAccess
{
    /**
     * @var int|null
     */
    public $statusCodeUnauthorized;

    /**
     * @var string|null
     */
    public $messageUnauthorized;

    /**
     * @var null|callable
     */
    public $checkAccess = null;

    /**
     * @param array ...$params
     * @return mixed
     * @throws HttpException
     */
    public function checkAccess(...$params)
    {
        if ($this->checkAccess) {
            if (call_user_func_array($this->checkAccess, $params) === false) {
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
        return $this->statusCodeUnauthorized ?: 403;
    }

    /**
     * @return string
     */
    protected function messageUnauthorized(): string
    {
        return $this->messageUnauthorized ?: 'Unable to perform action.';
    }

    /**
     * @throws HttpException
     */
    protected function handleUnauthorizedResponse()
    {
        throw new HttpException(
            $this->statusCodeUnauthorized(),
            $this->messageUnauthorized()
        );
    }
}
