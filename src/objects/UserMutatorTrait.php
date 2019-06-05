<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

use Craft;
use craft\elements\User;
use flipbox\craft\ember\helpers\ObjectHelper;

/**
 * @property int|null $userId
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait UserMutatorTrait
{
    /**
     * @var User|null
     */
    private $user;

    /**
     * Set associated userId
     *
     * @param $id
     * @return $this
     */
    public function setUserId(int $id)
    {
        $this->userId = $id;
        return $this;
    }

    /**
     * Get associated userId
     *
     * @return int|null
     */
    public function getUserId()
    {
        if (null === $this->userId && null !== $this->user) {
            $this->userId = $this->user->id;
        }

        return $this->userId;
    }

    /**
     * Associate a user
     *
     * @param mixed $user
     * @return $this
     */
    public function setUser($user = null)
    {
        $this->user = null;

        if (!$user = $this->internalResolveUser($user)) {
            $this->user = $this->userId = null;
        } else {
            $this->userId = $user->id;
            $this->user = $user;
        }

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        if ($this->user === null) {
            $user = $this->resolveUser();
            $this->setUser($user);
            return $user;
        }

        $userId = $this->userId;
        if ($userId !== null &&
            $userId !== $this->user->id
        ) {
            $this->user = null;
            return $this->getUser();
        }

        return $this->user;
    }

    /**
     * @return User|null
     */
    protected function resolveUser()
    {
        if ($model = $this->resolveUserFromId()) {
            return $model;
        }

        return null;
    }

    /**
     * @return User|null
     */
    private function resolveUserFromId()
    {
        if (null === $this->userId) {
            return null;
        }

        return Craft::$app->getUsers()->getUserById($this->userId);
    }

    /**
     * @param $user
     * @return User|null
     */
    protected function internalResolveUser($user = null)
    {
        if ($user === null) {
            return null;
        }

        if ($user instanceof User) {
            return $user;
        }

        if (is_numeric($user)) {
            return Craft::$app->getUsers()->getUserById($user);
        }

        if (is_string($user)) {
            return Craft::$app->getUsers()->getUserByUsernameOrEmail($user);
        }

        try {
            $object = Craft::createObject(User::class, [$user]);
        } catch (\Exception $e) {
            $object = new User();
            ObjectHelper::populate(
                $object,
                $user
            );
        }

        /** @var User $object */
        return $object;
    }
}
