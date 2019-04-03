<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queue;

use Craft;
use craft\elements\User;
use craft\helpers\ArrayHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.3.2
 */
class EmailByKey extends AbstractEmailByKey implements \Serializable
{
    /**
     *  The email key
     *
     * @var string
     */
    public $key;

    /**
     * The recipients
     *
     * @var array
     */
    protected $recipients = [];

    /**
     * The email params
     *
     * @var array
     */
    protected $params;

    /**
     * @inheritdoc
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @inheritdoc
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * @param array $recipients
     * @return $this
     */
    public function setRecipients(array $recipients)
    {
        foreach ($recipients as $recipient) {
            $this->addRecipient($recipient);
        }
        return $this;
    }

    /**
     * @param $recipient
     * @return $this
     */
    public function addRecipient($recipient)
    {
        if ($recipient instanceof User) {
            $recipient = [$recipient->email];
        }

        $this->recipients[] = $recipient;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = ArrayHelper::toArray($params);
        return $this;
    }

    /**
     * @param User|int|string|array $user
     * @return User
     */
    protected function resolveUser($user)
    {
        if ($user instanceof User) {
            return $user;
        }

        // An Id
        if (is_numeric($user)) {
            return Craft::$app->getUsers()->getUserById($user);
        }

        // An email
        if (is_string($user)) {
            if (!$element = Craft::$app->getUsers()->getUserByUsernameOrEmail($user)) {
                $element = new User([
                    'email' => $user
                ]);
            }

            return $element;
        }

        if (is_array($user)) {
            $email = key($user);
            $user = reset($user);

            // $user was an array [email => name]
            if (is_string($email)) {
                @list($firstName, $lastName) = explode(' ', $user);

                // Resolve user (and set name)
                if ($element = $this->resolveUser($email)) {
                    $element->firstName = $firstName ?: $element->firstName;
                    $element->lastName = $lastName ?: $element->lastName;
                    return $element;
                }

                return new User([
                    'email' => $email,
                    'firstName' => $firstName,
                    'lastName' => $lastName
                ]);
            }

            // An array of [$user]
            if (!$element = $this->resolveUser($user)) {
                return new User([
                    'email' => $user
                ]);
            }

            return $element;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function serialize()
    {
        return serialize([
            'key' => $this->key,
            'recipients' => $this->recipients,
            'params' => $this->params
        ]);
    }

    /**
     * @inheritdoc
     */
    public function unserialize($serialized)
    {
        Craft::configure(
            $this,
            unserialize($serialized)
        );
    }
}
