<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

use DateTime;

/**
 * @property DateTime|null $dateUpdated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait DateUpdatedAttributeTrait
{
    use DateUpdatedMutatorTrait;

    /**
     * @var DateTime|null
     */
    private $dateUpdated;
}
