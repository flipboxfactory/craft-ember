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
use yii\base\Model;
use yii\base\Module;
use yii\web\Controller;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property Controller|Module $sender
 */
class ModelErrorFilter extends ActionFilter
{
    use traits\FormatTrait,
        traits\ActionTrait;

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
     *   'create' => 'foo',
     *   'update' => 'bar',
     *   '*' => 'object,
     * ]
     * ```
     */
    public $actions = [];

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
            return $this->handleErrors($action, $result);
        }

        return parent::afterAction($action, $result);
    }

    /**
     * @param Action $action
     * @param $result
     * @return \craft\web\Response|Response
     */
    protected function handleErrors(Action $action, $result)
    {
        // Send the entry back to the template
        if ($routeParamKey = $this->findAction($action->id)) {
            Craft::$app->getUrlManager()->setRouteParams([
                $routeParamKey => $result
            ]);
        }

        return null;
    }

    /**
     * @param $result
     * @return bool
     */
    protected function resultMatch($result): bool
    {
        return $result instanceof Model && $result->hasErrors();
    }
}
