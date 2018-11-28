<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\records;

use Craft;
use flipbox\craft\ember\models\StateRulesTrait;
use flipbox\craft\ember\objects\StateMutatorTrait;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait StateAttributeTrait
{
    use StateRulesTrait, StateMutatorTrait;

    /**
     * @inheritdoc
     */
    public function stateAttributeLabel()
    {
        return [
            'state' => Craft::t('app', 'State')
        ];
    }
}
