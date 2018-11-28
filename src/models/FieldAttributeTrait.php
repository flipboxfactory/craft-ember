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
trait FieldAttributeTrait
{
    use FieldRulesTrait, \flipbox\craft\ember\objects\FieldAttributeTrait;

    /**
     * @return array
     */
    protected function fieldAttributes(): array
    {
        return [
            'fieldId'
        ];
    }

    /**
     * @return array
     */
    protected function fieldAttributeLabels(): array
    {
        return [
            'fieldId' => Craft::t('app', 'Field Id')
        ];
    }
}
