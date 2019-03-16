<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\helpers;

use Craft;
use craft\log\FileTarget;
use yii\log\Logger;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class LoggerHelper
{

    /**
     * Takes an array of log categories and creates log target configs
     *
     * @param array $categories
     * @return array
     */
    public static function targetConfigs(array $categories): array
    {
        $configs = [];

        foreach ($categories as $category) {
            $configs[$category] = static::targetConfig($category);
        }

        return array_filter($configs);
    }

    /**
     * Takes a log category and creates a log target config
     *
     * @param string $category
     * @return array
     */
    public static function targetConfig(string $category): array
    {
        // Only log console requests and web requests that aren't getAuthTimeout requests
        $isConsoleRequest = Craft::$app->getRequest()->getIsConsoleRequest();
        if (!$isConsoleRequest && !Craft::$app->getUser()->enableSession) {
            return [];
        }

        $generalConfig = Craft::$app->getConfig()->getGeneral();

        $target = [
            'class' => FileTarget::class,
            'fileMode' => $generalConfig->defaultFileMode,
            'dirMode' => $generalConfig->defaultDirMode,
            'logVars' => [],
            'categories' => [$category, $category . ':*'],
            'logFile' => '@storage/logs/'.$category.'.log'
        ];

        if (!$isConsoleRequest) {
            // Only log errors and warnings, unless Craft is running in Dev Mode or it's being installed/updated
            if (!YII_DEBUG
                && Craft::$app->getIsInstalled()
                && !Craft::$app->getUpdates()->getIsCraftDbMigrationNeeded()
            ) {
                $target['levels'] = Logger::LEVEL_ERROR | Logger::LEVEL_WARNING;
            }
        }

        return $target;
    }
}
