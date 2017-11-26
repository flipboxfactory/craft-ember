<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\records\traits;

use DateTime;
use flipbox\ember\traits\DateCreatedMutator;
use flipbox\ember\traits\DateCreatedRules;

/**
 * @property DateTime|null $dateCreated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait DateCreatedAttribute
{
    use DateCreatedRules, DateCreatedMutator;
}
