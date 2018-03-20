<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\ember\filters;

use Craft;
use craft\web\Controller as CraftController;
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
     * @param Action $action
     * @param mixed $result
     * @return mixed|Response
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
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
     * @return Response
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    protected function redirect(Action $action, $result)
    {
        return $this->redirectToPostedUrl(
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
     * @param Controller $controller
     * @param null $object
     * @param string|null $default
     * @return Response
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    protected function redirectToPostedUrl(
        Controller $controller,
        $object = null,
        string $default = null
    ): Response {
        if ($controller instanceof CraftController) {
            return $controller->redirectToPostedUrl($object, $default);
        }

        $url = Craft::$app->getRequest()->getValidatedBodyParam('redirect');

        if ($url === null) {
            $url = Craft::$app->getRequest()->getPathInfo();
        }

        if ($object !== null) {
            $url = Craft::$app->getView()->renderObjectTemplate($url, $object);
        }

        return $controller->redirect($url);
    }
}
