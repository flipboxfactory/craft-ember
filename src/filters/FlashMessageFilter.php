<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\ember\filters;

use Craft;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property Controller $sender
 */
class FlashMessageFilter extends ActionFilter
{

    use traits\ActionTrait,
        traits\FormatTrait;

    /**
     * Allow redirection of a null result
     * @var bool
     */
    public $allowNull = false;

    /**
     * @var array this property defines the message mapping for each action.
     * For each action that should only support limited set of messages
     * you add a message with the action id as array key and an array of
     * allowed messages using an (optional) status code as a key and the message
     * as the value (e.g. '200' => 'Success', '401' => 'Fail').
     * If an action is not defined a message will not be presented.
     *
     * You can use `'*'` to stand for all actions. When an action is explicitly
     * specified, it takes precedence over the specification given by `'*'`.
     *
     * For example,
     *
     * ```php
     * [
     *   'create' => [201 => 'Created successfully.', 401 => 'Failed to create.'],
     *   'update' => [200 => 'Updated successfully.', 401 => 'Failed to update.'],
     *   '*' => ['*' => 'General message'],
     * ]
     * ```
     */
    public $actions = [];

    /**
     * @var string
     */
    public $message;

    /**
     * @param \yii\base\Action $action
     * @param mixed $result
     * @return mixed|Response
     */
    public function afterAction($action, $result)
    {
        if ($this->formatMatch($action->id) &&
            $this->resultMatch($result)
        ) {
            $this->setMessage($action);
        }
        return parent::afterAction($action, $result);
    }

    /**
     * @param $result
     * @return bool
     */
    protected function resultMatch($result): bool
    {
        return $result !== null || ($this->allowNull === true);
    }

    /**
     * @param Action $action
     */
    protected function setMessage(Action $action)
    {
        if (!$message = $this->findMessage($action->id)) {
            return;
        }

        if (Craft::$app->getResponse()->getIsSuccessful()) {
            Craft::$app->getSession()->setNotice($message);
            return;
        }

        Craft::$app->getSession()->setError($message);
        return;
    }

    /**
     * @param string $action
     * @return null|string
     */
    protected function findMessage(string $action)
    {
        // Look for definitions
        if ($message = $this->findMessageFromAction($action)) {
            return $message;
        }

        return $this->message;
    }

    /**
     * @param string $action
     * @return null|string
     */
    protected function findMessageFromAction(string $action)
    {
        // Default format
        $messages = $this->findAction($action);

        if (is_array($messages)) {
            return $this->resolveMessageStatusCode($messages);
        }

        if (!empty($messages)) {
            return (string)$messages;
        }

        return null;
    }

    /**
     * @param array $messages
     * @return string|null
     */
    protected function resolveMessageStatusCode(array $messages)
    {
        $statusCode = Craft::$app->getResponse()->getStatusCode();

        if (isset($messages[$statusCode])) {
            return (string)$messages[$statusCode];
        };

        if (isset($messages['*'])) {
            return (string)$messages['*'];
        }

        return null;
    }
}
