<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\traits;

use Craft;
use craft\elements\User as UserElement;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait UserAttribute
{
    use UserRules, UserMutator;

    /**
     * @var int|null
     */
    private $userId;

    /**
     * @return array
     */
    protected function userFields(): array
    {
        return [
            'userId'
        ];
    }

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
