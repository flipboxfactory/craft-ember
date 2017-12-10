<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\ember\filters;

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
class CallableFilter extends ActionFilter
{
    use traits\ActionTrait,
        traits\FormatTrait;

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
        if ($this->formatMatch($action->id) &&
            $this->resultMatch($result)
        ) {
            return $this->call($action, $result);
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
     * @param $data
     * @return mixed
     */
    protected function call(Action $action, $data)
    {
        if (!$callable = $this->findAction($action->id)) {
            return $data;
        }

        if (is_callable($callable)) {
            return call_user_func_array($callable, [$data, $this]);
        }

        return $data;
    }
}
