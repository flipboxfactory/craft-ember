<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\views;

use Craft;
use craft\web\View;
use yii\base\BaseObject;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
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
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function render(array $params = []): string
    {
        $view = Craft::$app->getView();

        $currentMode = $view->getTemplateMode();
        $view->setTemplateMode($this->mode);

        $html = $this->renderTemplate($params);

        $view->setTemplateMode($currentMode);

        return $html;
    }

    /**
     * @param array $params
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    protected function renderTemplate(array $params = []): string
    {
        return Craft::$app->getView()->renderTemplate(
            $this->template,
            $params
        );
    }
}
