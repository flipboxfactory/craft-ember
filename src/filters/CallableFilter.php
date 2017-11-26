<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/guardian/license
 * @link       https://www.flipboxfactory.com/software/guardian/
 */

namespace flipbox\ember\filters;

use Craft;
use yii\base\ActionFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property Controller $sender
 */
class CallableFilter extends ActionFilter
{
    /**
     * @var array this property defines the transformers for each action.
     * Each action that should only support one transformer.
     *
     * You can use `'*'` to stand for all actions. When an action is explicitly
     * specified, it takes precedence over the specification given by `'*'`.
     *
     * For example,
     *
     * ```php
     * [
     *   'create' => function () {
     *      return 'foo';
     *   },
     *   '*' => function () {
     *      return $this->redirect('https://google.com');
     *   }
     * ]
     * ```
     */
    public $actions = [];

    /**
     * @var Callable
     */
    public $callable;

    /**
     * Allow redirection of a null result
     * @var bool
     */
    public $allowNull = false;

    /**
     * @param \yii\base\Action $action
     * @param mixed $result
     * @return mixed|Response
     */
    public function afterAction($action, $result)
    {
        if (Craft::$app->getResponse()->format === Response::FORMAT_RAW) {
            if ($result !== null || ($this->allowNull === true)) {
                return $this->call($result);
            }
        }
        return parent::afterAction($action, $result);
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function call($data)
    {
        if (!$callable = $this->findCallable()) {
            return $data;
        }

        if (is_callable($callable)) {
            return call_user_func_array($callable, [$data, $this]);
        }

        return $data;
    }

    /**
     * @return callable|null
     */
    protected function findCallable()
    {
        // The requested action
        $action = Craft::$app->requestedAction->id;

        // Default callable
        $callable = $this->callable;

        // Look for definitions
        if (isset($this->actions[$action])) {
            $callable = $this->actions[$action];
        } elseif (isset($this->actions['*'])) {
            $callable = $this->actions['*'];
        }

        return $callable;
    }
}
