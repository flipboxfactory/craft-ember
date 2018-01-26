<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\element;

use craft\helpers\ArrayHelper;
use flipbox\ember\actions\model\ModelIndex;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class ElementIndex extends ModelIndex
{
    /**
     * @inheritdoc
     *
     * Ref: https://github.com/craftcms/cms/issues/2310
     */
    protected function normalizeQueryConfig(array $config = []): array
    {
        // OrderBy should be an array, not an empty string (which is set in the default element query)
        $config['orderBy'] = ArrayHelper::getValue($config, 'orderBy', []);
        return $config;
    }
}
