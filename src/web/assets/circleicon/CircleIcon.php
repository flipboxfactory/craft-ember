<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\web\assets\circleicon;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class CircleIcon extends AssetBundle
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
            'Card.css'
        ];

        $this->js = [
            'Card'.$this->dotJs()
        ];

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public $depends = [
        CpAsset::class,
    ];
}
