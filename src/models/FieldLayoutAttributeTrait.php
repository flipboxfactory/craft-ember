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
trait FieldLayoutAttributeTrait
{
    use FieldLayoutRulesTrait, \flipbox\craft\ember\objects\FieldLayoutAttributeTrait;

    /**
     * @return array
     */
    protected function fieldLayoutAttributes(): array
    {
        return [
            'fieldLayoutId'
        ];
    }

    /**
     * @return array
     */
    protected function fieldLayoutAttributeLabels(): array
    {
        return [
            'fieldLayoutId' => Craft::t('app', 'Field Layout Id')
        ];
    }
}
