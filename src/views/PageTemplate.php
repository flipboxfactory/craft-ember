<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\ember\views;

use Craft;
use craft\web\View;
use yii\base\BaseObject;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class PageTemplate extends Template
{
    /**
     * @inheritdoc
     */
    protected function renderTemplate(array $params = []): string
    {
        return Craft::$app->getView()->renderPageTemplate(
            $this->template,
            $params
        );
    }
}
