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
