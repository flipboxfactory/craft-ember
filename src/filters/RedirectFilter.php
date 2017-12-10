<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\ember\filters;

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
    use traits\ActionTrait,
        traits\FormatTrait;

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
            $this->actionMatch($action->id) &&
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
}
