<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/guardian/license
 * @link       https://www.flipboxfactory.com/software/guardian/
 */

namespace flipbox\ember\filters;

use Craft;
use flipbox\ember\helpers\ControllerHelper;
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
                return ControllerHelper::redirectToPostedUrl(
                    $action->controller,
                    $result
                );
            }
        }
        return parent::afterAction($action, $result);
    }
}
