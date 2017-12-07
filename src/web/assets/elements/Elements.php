<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\web\assets\elements;

use craft\web\AssetBundle;
use flipbox\ember\assets\hud\Hud;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Elements extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = __DIR__ . '/dist';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->css = [
            'Elements.css'
        ];

        $this->js = [
            'Elements'.$this->dotJs()
        ];

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public $depends = [
        Hud::class,
    ];
}
