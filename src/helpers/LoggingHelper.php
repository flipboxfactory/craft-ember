<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\helpers;

use Craft;
use craft\helpers\ArrayHelper;
use craft\log\FileTarget;
use craft\web\Request;
use flipbox\ember\Modules\interfaces\LoggableInterface;
use yii\base\Module;
use yii\log\Dispatcher;
use yii\log\Logger;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class LoggingHelper
{
    /**
     * prefixSessionData.
     *
     * @return callable
     */
    public static function prefixSessionData()
    {
        return function () {
            $request = Craft::$app->getRequest();
            $ip = $request instanceof Request ? $request->getUserIP() : '-';

            /* @var $user \craft\web\User */
            $user = Craft::$app->has('user', true) ? Craft::$app->get('user') : null;
            if ($user && ($identity = $user->getIdentity())) {
                $userID = $identity->getId() . ':' . $identity->username;
            } else {
                $userID = '-';
            }

            /* @var $session \yii\web\Session */
            $session = Craft::$app->has('session', true) ? Craft::$app->get('session') : null;
            $sessionID = $session && $session->getIsActive() ? $session->getId() : '-';

            return "[$ip][$userID][$sessionID]";
        };
    }

    /**
     * isDebugModeEnabled.
     *
     * @param Module $module
     *
     * @return bool
     */
    public static function isDebugModeEnabled(Module $module)
    {
        return Craft::$app->getConfig()->getGeneral()->devMode ||
            ($module instanceof LoggableInterface && $module->isDebugModeEnabled());
    }

    /**
     * getDispatchDefinition.
     *
     * @param Module $module
     * @param array $config
     *
     * @return array
     */
    public static function getDispatchDefinition(Module $module, array $config = [])
    {

        $configService = Craft::$app->getConfig()->getGeneral();

        $defaultConfig = [
            'logger' =>
            /* '\yii\log\Logger', */
                new Logger(),
            'class' => Dispatcher::class,
            'targets' => [
                /* 'file' => $fileTarget, */
                'file' => [
                    'class' => FileTarget::class,
                    'levels' => array_merge(
                        ['error', 'warning'],
                        static::isDebugModeEnabled($module) ? ['trace', 'info'] : []
                    ),
                    'logFile' => Craft::getAlias('@storage/logs/' . strtolower(str_replace(
                        '/',
                        '-',
                        $module->getUniqueId()
                    )) . '.log'),
                    'logVars' => [],
                    'fileMode' => $configService->defaultFileMode,
                    'dirMode' => $configService->defaultDirMode,
                    'prefix' => static::prefixSessionData(),
                ],
            ],
        ];

        return ArrayHelper::merge($config, $defaultConfig);
    }
}
