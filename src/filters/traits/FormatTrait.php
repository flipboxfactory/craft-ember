<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\ember\filters\traits;

use Craft;
use craft\helpers\ArrayHelper;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait FormatTrait
{
    /**
     * @var array
     */
    public $formats = [];

    /**
     * The default format
     *
     * @var string
     */
    public $format = Response::FORMAT_RAW;

    /**
     * @param string $action
     * @return bool
     */
    protected function formatMatch(string $action): bool
    {
        if (!$format = $this->findFormat($action)) {
            return true;
        }

        return Craft::$app->getResponse()->format === $format;
    }

    /**
     * @param string $action
     * @return string|null
     */
    protected function findFormat(string $action)
    {
        // Default format
        $format = ArrayHelper::getValue($this->formats, '*', $this->format);

        // Look for definitions
        if (isset($this->formats[$action])) {
            $format = $this->formats[$action];
        }

        return $format;
    }
}
