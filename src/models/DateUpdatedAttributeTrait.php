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
 * @property DateTime|null $dateUpdated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait DateUpdatedAttributeTrait
{
    use DateUpdatedRulesTrait, \flipbox\craft\ember\objects\DateUpdatedAttributeTrait;

    /**
     * @return array
     */
    protected function dateUpdatedAttributes(): array
    {
        return [
            'dateUpdated'
        ];
    }

    /**
     * @inheritdoc
     */
    public function dateUpdatedAttributeLabels(): array
    {
        return [
            'dateUpdated' => Craft::t('app', 'Date Updated')
        ];
    }
}
