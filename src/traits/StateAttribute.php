<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\traits;

use Craft;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait StateAttribute
{
    use StateRules, StateMutator;

    /**
     * @var boolean Enabled
     */
    public $enabled;

    /**
     * @inheritdoc
     */
    public function stateAttributeLabel()
    {
        return [
            'state' => Craft::t('app', 'State')
        ];
    }
}
