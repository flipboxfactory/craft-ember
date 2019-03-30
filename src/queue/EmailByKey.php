<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queries;

use Craft;
use craft\elements\User;
use craft\helpers\ArrayHelper;
use craft\helpers\StringHelper;
use craft\queue\BaseJob;
use yii\helpers\Json;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.3.2
 */
class EmailByKey extends BaseJob implements \Serializable
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
     * @return string|null
     */
    protected function defaultDescription()
    {
        return 'Sending an email by key.';
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function execute($queue)
    {
        // A random tracking string
        $id = StringHelper::randomString();

        try {
            Craft::info(
                sprintf(
                    "[Sending email via job '%s'.] %s",
                    (string)$id,
                    (string)Json::encode(
                        [
                            'tracking' => $id,
                            'key' => $this->key,
                            'recipients' => $this->recipients
                        ]
                    )
                ), __METHOD__
            );

            foreach ($this->recipients as $recipient) {
                if (null === ($recipient = $this->resolveUser($recipient))) {
                    continue;
                }

                /** @var User $recipient */

                if (!Craft::$app->getMailer()
                    ->composeFromKey(
                        $this->key,
                        array_merge(
                            ['recipient' => $recipient],
                            $this->params
                        )
                    )->setTo($recipient)
                    ->send()
                ) {
                    Craft::error(
                        sprintf(
                            "Failed to send email via job '%s'",
                            (string)$id
                        ), __METHOD__
                    );
                    continue;
                }

                Craft::info(
                    sprintf(
                        "Successfully sent email via job '%s'",
                        (string)$id
                    ), __METHOD__
                );
            }

        } catch (\Exception $e) {
            Craft::error(
                sprintf(
                    "Exception caught while trying to run '%s' (Id: %s) job. Exception: [%s].",
                    (string)get_class($this),
                    $id,
                    (string)Json::encode([
                        'Trace' => $e->getTraceAsString(),
                        'File' => $e->getFile(),
                        'Line' => $e->getLine(),
                        'Code' => $e->getCode(),
                        'Message' => $e->getMessage()
                    ])
                ), __METHOD__
            );

            throw $e;
        }
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
    function resolveUser($user)
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
