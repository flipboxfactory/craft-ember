<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queue;

use craft\queue\BaseJob;
use Craft;
use craft\elements\User;
use craft\helpers\StringHelper;
use yii\helpers\Json;
use yii\mail\MessageInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.3.2
 */
abstract class AbstractEmailByKey extends BaseJob
{
    /**
     * The email key
     *
     * @return string
     */
    abstract public function getKey(): string;

    /**
     * The recipients
     *
     * @return User[]
     */
    abstract public function getRecipients(): array;

    /**
     * The email params
     *
     * @return array
     */
    abstract public function getParams(): array;

    /**
     * Returns a default description for [[getDescription()]].
     *
     * @return string|null
     */
    protected function defaultDescription()
    {
        return 'Sending an email by key.';
    }

    /**
     * @param \craft\queue\QueueInterface|\yii\queue\Queue $queue
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
                            'key' => $this->getKey(),
                            'recipients' => count($this->getRecipients())
                        ]
                    )
                ),
                __METHOD__
            );

            foreach ($this->getRecipients() as $recipient) {
                if (!$this->composeMessage($recipient, $this->getParams())->send()) {
                    Craft::warning(
                        sprintf(
                            "Failed to send email via job '%s'",
                            (string)$id
                        ),
                        __METHOD__
                    );
                    continue;
                }

                Craft::info(
                    sprintf(
                        "Successfully sent email via job '%s'",
                        (string)$id
                    ),
                    __METHOD__
                );
            }
        } catch (\Exception $e) {
            Craft::warning(
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
                ),
                __METHOD__
            );

            throw $e;
        }
    }

    /**
     * @param $recipient
     * @param array $params
     * @return MessageInterface
     * @throws \yii\base\InvalidConfigException
     */
    protected function composeMessage($recipient, array $params): MessageInterface
    {
        return Craft::$app->getMailer()
            ->composeFromKey(
                $this->getKey(),
                array_merge(
                    ['recipient' => $recipient],
                    $params
                )
            )->setTo($recipient);
    }
}
