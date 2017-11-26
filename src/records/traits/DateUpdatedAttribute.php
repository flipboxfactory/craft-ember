<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\records\traits;

use DateTime;
use flipbox\ember\traits\DateUpdatedMutator;
use flipbox\ember\traits\DateUpdatedRules;

/**
 * @property DateTime|null $dateUpdated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait DateUpdatedAttribute
{
    use DateUpdatedRules, DateUpdatedMutator;
}
