<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\web\assets\clipboard;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Clipboard extends AssetBundle
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
            'Clipboard.css'
        ];

        $this->js = [
            'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js',
            'Clipboard'.$this->dotJs()
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
