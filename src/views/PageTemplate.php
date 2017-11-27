<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/guardian/license
 * @link       https://www.flipboxfactory.com/software/guardian/
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
