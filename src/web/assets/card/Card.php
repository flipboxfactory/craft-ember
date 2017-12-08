<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\web\assets\card;

use craft\web\AssetBundle;
use flipbox\ember\web\assets\circleicon\CircleIcon;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Card extends AssetBundle
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
        CircleIcon::class,
    ];
}
