<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\ember\filters;

use Craft;
use craft\helpers\Template;
use flipbox\ember\helpers\ViewHelper;
use flipbox\ember\views\ViewInterface;
use yii\base\ActionFilter;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property Controller $sender
 */
class RawFilter extends ActionFilter
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
     *   'create' => $view,
     *   'update' => $view,
     *   'delete' => $view,
     *   '*' => $view,
     * ]
     * ```
     */
    public $actions = [];

    /**
     * @var array|ViewInterface
     */
    public $view;

    /**
     * @param \yii\base\Action $action
     * @param mixed $result
     * @return mixed|\Twig_Markup
     */
    public function afterAction($action, $result)
    {
        if (Craft::$app->getResponse()->format === Response::FORMAT_RAW) {
            return $this->renderTemplate($result);
        }

        return parent::afterAction($action, $result);
    }

    /**
     * @param $data
     * @return \Twig_Markup
     */
    protected function renderTemplate($data)
    {
        if (!$view = $this->findView()) {
            return $data;
        }

        return Template::raw(
            $this->resolveView($view)->render($data)
        );
    }

    /**
     * @return ViewInterface|array|null
     * @throws Exception
     */
    protected function findView()
    {
        // The requested action
        $action = Craft::$app->requestedAction->id;

        // Default view
        $view = $this->view;

        // Look for definitions
        if (isset($this->actions[$action])) {
            $view = $this->actions[$action];
        } elseif (isset($this->actions['*'])) {
            $view = $this->actions['*'];
        }

        return $view;
    }

    /**
     * @param $view
     * @return ViewInterface
     * @throws Exception
     */
    protected function resolveView($view): ViewInterface
    {
        if (ViewHelper::isView($view)) {
            return $view;
        }

        if (ViewHelper::isViewClass($view)) {
            return new $view();
        }

        $view = Craft::createObject($view);

        if (!$view instanceof ViewInterface) {
            throw new Exception("Invalid view");
        }

        return $view;
    }
}
