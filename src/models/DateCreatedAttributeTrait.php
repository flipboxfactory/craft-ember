<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\models;

use Craft;
use DateTime;

/**
 * @property DateTime|null $dateCreated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait DateCreatedAttributeTrait
{
    use DateCreatedRulesTrait, \flipbox\craft\ember\objects\DateCreatedAttributeTrait;

    /**
     * @return array
     */
    protected function dateCreatedAttributes(): array
    {
        return [
            'dateCreated'
        ];
    }

    /**
     * @inheritdoc
     */
    public function dateCreatedAttributeLabels(): array
    {
        return [
            'dateCreated' => Craft::t('app', 'Date Created')
        ];
    }
}
