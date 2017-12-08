<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\helpers;

use Craft;
use craft\web\Controller as CraftWebController;
use yii\web\Controller as WebController;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class ControllerHelper
{
    /**
     * @param WebController $controller
     * @param null $object
     * @param string|null $default
     * @return \yii\web\Response|\craft\web\Response
     */
    public static function redirectToPostedUrl(
        WebController $controller,
        $object = null,
        string $default = null
    ): Response {
        if ($controller instanceof CraftWebController) {
            return $controller->redirectToPostedUrl($object, $default);
        }

        $url = Craft::$app->getRequest()->getValidatedBodyParam('redirect');

        if ($url === null) {
            if ($default !== null) {
                $url = $default;
            } else {
                $url = Craft::$app->getRequest()->getPathInfo();
            }
        }

        if ($object) {
            $url = Craft::$app->getView()->renderObjectTemplate($url, $object);
        }

        return $controller->redirect($url);
    }
}
