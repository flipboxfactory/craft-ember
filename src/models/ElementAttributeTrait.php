<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\models;

use Craft;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait ElementAttributeTrait
{
    use ElementRulesTrait, \flipbox\craft\ember\objects\ElementAttributeTrait;

    /**
     * @return array
     */
    protected function elementAttributes(): array
    {
        return [
            'elementId'
        ];
    }

    /**
     * @return array
     */
    protected function elementAttributeLabels(): array
    {
        return [
            'elementId' => Craft::t('app', 'Element Id')
        ];
    }
}
