<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\ember\filters;

use Craft;
use craft\helpers\ArrayHelper;
use flipbox\ember\helpers\ControllerHelper;
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
class RedirectFilter extends ActionFilter
{
    /**
     * @var array this property defines the status code mapping for each action.
     * For each action that should only support limited set of status codes
     * you add a status code with the action id as array key and an array value of
     * allowed status codes (e.g. 'create' => [201, 204], 'delete' => [204]).
     * If an action is not defined the default statusCode property will be used.
     *
     * You can use `'*'` to stand for all actions. When an action is explicitly
     * specified, it takes precedence over the specification given by `'*'`.
     *
     * For example,
     *
     * ```php
     * [
     *   'create' => [201, 204],
     *   'update' => [200],
     *   'delete' => [204],
     *   '*' => [200],
     * ]
     * ```
     */
    public $actions = [];

    /**
     * Allow redirection of a null result
     * @var bool
     */
    public $statusCode = null;

    /**
     * Allow redirection of a null result
     * @var bool
     */
    public $allowNull = false;

    /**
     * @var string
     */
    public $format = Response::FORMAT_RAW;

    /**
     * @var array
     */
    public $formats = [];

    /**
     * @param \yii\base\Action $action
     * @param mixed $result
     * @return mixed|Response
     */
    public function afterAction($action, $result)
    {
        if ($this->formatMatch($action->id) &&
            $this->statusCodeMatch($action->id) &&
            $this->resultMatch($result)
        ) {
            return $this->redirect($action, $result);
        }

        return parent::afterAction($action, $result);
    }

    /**
     * @param Action $action
     * @param $result
     * @return \craft\web\Response|Response
     */
    protected function redirect(Action $action, $result)
    {
        return ControllerHelper::redirectToPostedUrl(
            $action->controller,
            $result
        );
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
     * @param string $action
     * @return bool
     */
    protected function statusCodeMatch(string $action): bool
    {
        if (!$statusCode = $this->findStatusCode($action)) {
            return true;
        }

        return Craft::$app->getResponse()->getStatusCode() === $statusCode;
    }

    /**
     * @param string $action
     * @return string|null
     */
    protected function findStatusCode(string $action)
    {
        // Default status code
        $statusCode = ArrayHelper::getValue($this->actions, '*', $this->actions);

        // Look for definitions
        if (isset($this->actions[$action])) {
            $statusCode = $this->actions[$action];
        }

        return $statusCode;
    }

    /**
     * @param string $action
     * @return bool
     */
    protected function formatMatch(string $action): bool
    {
        if (!$format = $this->findFormat($action)) {
            return true;
        }

        return Craft::$app->getResponse()->format === $format;
    }

    /**
     * @param string $action
     * @return string|null
     */
    protected function findFormat(string $action)
    {
        // Default format
        $format = ArrayHelper::getValue($this->formats, '*', $this->format);

        // Look for definitions
        if (isset($this->formats[$action])) {
            $format = $this->formats[$action];
        }

        return $format;
    }
}
