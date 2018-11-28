<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\records;

use Craft;
use flipbox\craft\ember\models\IdRulesTrait;
use flipbox\craft\ember\objects\IdMutatorTrait;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait IdAttributeTrait
{
    use IdRulesTrait, IdMutatorTrait;

    /**
     * @inheritdoc
     */
    public function idAttributes(): array
    {
        return [
            'id'
        ];
    }

    /**
     * @inheritdoc
     */
    public function idAttributeLabels(): array
    {
        return [
            'id' => Craft::t('app', 'Id')
        ];
    }
}
