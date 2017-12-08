<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\traits;

use Craft;
use craft\elements\User as UserElement;
use yii\web\HttpException;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait CurrentUser
{
    /**
     * @return int|null
     */
    public $statusCodeUserNotFound;

    /**
     * @return string|null
     */
    public $messageUserNotFound;

    /**
     * @return UserElement
     */
    protected function findUser()
    {
        return Craft::$app->getUser()->getIdentity();
    }

    /**
     * @return UserElement
     */
    protected function getUser()
    {
        if (($currentUser = $this->findUser()) === null) {
            return $this->handleUserNotFoundResponse();
        };

        return $currentUser;
    }

    /**
     * HTTP forbidden response code
     *
     * @return int
     */
    protected function statusCodeUserNotFound(): int
    {
        return $this->statusCodeUserNotFound ?: 401;
    }

    /**
     * @return string
     */
    protected function messageUserNotFound(): string
    {
        return $this->messageUserNotFound ?: 'Unable to establish identity.';
    }

    /**
     * @throws HttpException
     * @return mixed
     */
    protected function handleUserNotFoundResponse()
    {
        throw new HttpException(
            $this->statusCodeUserNotFound(),
            $this->messageUserNotFound()
        );
    }
}
