<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\services\records;

use flipbox\craft\ember\services\queries\QueryAccessor;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait ActiveRecordAccessorTrait
{
    use QueryAccessor, ActiveRecordTrait {
        ActiveRecordTrait::getDb insteadof QueryAccessor;
        ActiveRecordTrait::getQuery insteadof QueryAccessor;
    }
}
