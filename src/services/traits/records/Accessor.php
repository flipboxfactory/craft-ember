<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits\records;

use flipbox\ember\services\traits\queries\Accessor as QueryAccessor;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait Accessor
{
    use QueryAccessor, ActiveRecord {
        ActiveRecord::getDb insteadof QueryAccessor;
        ActiveRecord::getQuery insteadof QueryAccessor;
    }
}
