<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\traits;

use Craft;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait HandleAttribute
{
    use HandleRules;

    /**
     * @var string Handle
     */
    public $handle;

    /**
     * @inheritdoc
     */
    public function handleAttributeLabel()
    {
        return [
            'handle' => Craft::t('app', 'Handle')
        ];
    }
}
