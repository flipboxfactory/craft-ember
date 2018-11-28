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
trait UserAttributeTrait
{
    use UserRulesTrait, \flipbox\craft\ember\objects\UserAttributeTrait;

    /**
     * @return array
     */
    protected function userAttributes(): array
    {
        return [
            'userId'
        ];
    }

    /**
     * @return array
     */
    protected function userAttributeLabels(): array
    {
        return [
            'userId' => Craft::t('app', 'User Id')
        ];
    }
}
