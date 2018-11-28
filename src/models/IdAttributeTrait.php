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
trait IdAttributeTrait
{
    use IdRulesTrait, \flipbox\craft\ember\objects\IdAttributeTrait;

    /**
     * @inheritdoc
     */
    protected function idAttributes(): array
    {
        return [
            'id'
        ];
    }

    /**
     * @inheritdoc
     */
    protected function idAttributeLabels(): array
    {
        return [
            'id' => Craft::t('app', 'Id')
        ];
    }
}
