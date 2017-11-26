<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\traits;

use Craft;
use craft\elements\User as UserElement;

/**
 * @property int|null $userId
 * @property UserElement|null $user
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait UserMutator
{
    /**
     * Set associated userId
     *
     * @param $id
     * @return $this
     */
    public function setUserId(int $id)
    {
        // Has the id changed?
        if ($id !== $this->userId) {
            // Invalidate existing user
            if ($this->user !== null && $this->user->getId() !== $id) {
                $this->user = null;
            };

            $this->userId = $id;
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
        return $this->userId;
    }


    /**
     * Associate a user
     *
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        // Clear cache
        $this->user = null;

        // Find element
        if (!$user = $this->findUserElement($user)) {
            // Clear property / cache
            $this->userId = $this->user = null;
        } else {
            // Set property
            $this->userId = $user->getId();

            // Set cache
            $this->user = $user;
        }

        return $this;
    }

    /**
     * @return UserElement|null
     */
    public function getUser()
    {
        // Check cache
        if (is_null($this->user)) {
            // Check property
            if (!empty($this->userId)) {
                // Find element
                if ($userElement = Craft::$app->getUsers()->getUserById($this->userId)) {
                    // Set
                    $this->setUser($userElement);
                } else {
                    // Clear property (it's invalid)
                    $this->userId = null;

                    // Prevent subsequent look-ups
                    $this->user = false;
                }
            } else {
                // Prevent subsequent look-ups
                $this->user = false;
            }
        }

        return !$this->user ? null : $this->user;
    }

    /**
     * @param $user
     * @return UserElement|null
     */
    private function findUserElement($user)
    {
        // Element
        if ($user instanceof UserElement) {
            return $user;

            // Id
        } elseif (is_numeric($user)) {
            return Craft::$app->getUsers()->getUserById($user);

            // Username / Email
        } elseif (!is_null($user)) {
            return Craft::$app->getUsers()->getUserByUsernameOrEmail($user);
        }

        return null;
    }
}
