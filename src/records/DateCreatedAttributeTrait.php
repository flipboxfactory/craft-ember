<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\records;

use Craft;
use DateTime;
use flipbox\craft\ember\models\DateCreatedRulesTrait;
use flipbox\craft\ember\objects\DateCreatedMutatorTrait;

/**
 * Intended to be used on an ActiveRecord, this class provides `$this->dateCreated` attribute along with 'getters'
 * and 'setters' to ensure a `DateTime` object usage.  In addition, ActiveRecord rules are available.
 *
 * @property DateTime|null $dateCreated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait DateCreatedAttributeTrait
{
    use DateCreatedRulesTrait, DateCreatedMutatorTrait;

    /**
     * @inheritdoc
     */
    public function dateCreatedAttributes(): array
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
