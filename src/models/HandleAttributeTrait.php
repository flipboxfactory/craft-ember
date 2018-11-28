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
trait HandleAttributeTrait
{
    use HandleRulesTrait;

    /**
     * @var string Handle
     */
    public $handle;

    /**
     * @return array
     */
    public function handleAttributeLabels(): array
    {
        return [
            'handle' => Craft::t('app', 'Handle')
        ];
    }
}
