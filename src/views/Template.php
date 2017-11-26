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
class Template extends BaseObject implements ViewInterface
{
    /**
     * @var string
     */
    public $mode = View::TEMPLATE_MODE_CP;

    /**
     * @var string
     */
    public $template = '';

    /**
     * @param array $params
     * @return string
     */
    public function render(array $params = []): string
    {
        $view = Craft::$app->getView();

        $currentMode = $view->getTemplateMode();
        $view->setTemplateMode($this->mode);

        $html = $view->renderTemplate(
            $this->template,
            $params
        );

        $view->setTemplateMode($currentMode);

        return $html;
    }
}
