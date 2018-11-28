<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\filters;

use Craft;
use craft\helpers\Template;
use flipbox\craft\ember\helpers\ViewHelper;
use flipbox\craft\ember\views\ViewInterface;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\base\Exception;
use yii\web\Controller;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property Controller $sender
 */
class RawFilter extends ActionFilter
{
    use FormatTrait,
        ActionTrait;

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
     * @param Action $action
     * @param mixed $result
     * @return mixed|\Twig_Markup
     * @throws Exception
     */
    public function afterAction($action, $result)
    {
        if ($this->formatMatch($action->id)
        ) {
            return $this->renderTemplate($action, $result);
        }

        return parent::afterAction($action, $result);
    }

    /**
     * @param Action $action
     * @param $result
     * @return \Twig_Markup
     * @throws Exception
     */
    protected function renderTemplate(Action $action, $result)
    {
        if (!$view = $this->findAction($action->id)) {
            return $result;
        }

        return Template::raw(
            $this->resolveView($view)->render($result)
        );
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
