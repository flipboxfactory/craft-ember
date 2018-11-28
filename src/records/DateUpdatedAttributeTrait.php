<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\records;

use Craft;
use DateTime;
use flipbox\craft\ember\models\DateUpdatedRulesTrait;
use flipbox\craft\ember\objects\DateUpdatedMutatorTrait;

/**
 * Intended to be used on an ActiveRecord, this class provides `$this->dateUpdated` attribute along with 'getters'
 * and 'setters' to ensure a `DateTime` object usage.  In addition, ActiveRecord rules are available.
 *
 * @property DateTime|null $dateUpdated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait DateUpdatedAttributeTrait
{
    use DateUpdatedRulesTrait, DateUpdatedMutatorTrait;

    /**
     * @inheritdoc
     */
    public function dateUpdatedAttributes(): array
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
