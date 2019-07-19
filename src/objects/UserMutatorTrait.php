<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

use Craft;
use craft\elements\User;

/**
 * This trait accepts both an User or and User Id and ensures that the both
 * the User and the Id are in sync. If one changes (and does not match the other) it
 * resolves (removes / updates) the other.
 *
 * In addition, this trait is primarily useful when a new User is set and saved; the User
 * Id can be retrieved without needing to explicitly set the newly created Id.
 *
 * @property User|null $user
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
     * Internally set the User Id.  This can be overridden. A record for example
     * should use `setAttribute`.
     *
     * @param int|null $id
     * @return $this
     */
    abstract protected function internalSetUserId(int $id = null);

    /**
     * Internally get the User Id.  This can be overridden.  A record for example
     * should use `getAttribute`.
     *
     * @return int|null
     */
    abstract protected function internalGetUserId();
    
    /**
     * @return bool
     */
    public function isUserSet(): bool
    {
        return null !== $this->user;
    }

    /**
     * Set associated userId
     *
     * @param $id
     * @return $this
     */
    public function setUserId(int $id)
    {
        $this->internalSetUserId($id);

        if (null !== $this->user && $id !== $this->user->id) {
            $this->user = null;
        }

        return $this;
    }

    /**
     * Get associated userId
     *
     * @return int|null
     */
    public function getUserId()
    {
        if (null === $this->internalGetUserId() && null !== $this->user) {
            $this->setUserId($this->user->id);
        }

        return $this->internalGetUserId();
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
        $this->internalSetUserId(null);

        if (null !== ($user = $this->verifyUser($user))) {
            $this->user = $user;
            $this->internalSetUserId($user->id);
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

        $userId = $this->internalGetUserId();
        if ($userId !== null && $userId !== $this->user->id) {
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
        if ($user = $this->resolveUserFromId()) {
            return $user;
        }

        return null;
    }

    /**
     * @return User|null
     */
    private function resolveUserFromId()
    {
        if (null === ($userId = $this->internalGetUserId())) {
            return null;
        }

        return Craft::$app->getUsers()->getUserById($userId);
    }

    /**
     * @param $user
     * @return User|null
     */
    protected function verifyUser($user = null)
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

        return null;
    }
}
