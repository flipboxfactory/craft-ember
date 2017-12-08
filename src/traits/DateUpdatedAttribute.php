<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\traits;

use Craft;
use DateTime;

/**
 * @property DateTime|null $dateUpdated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait DateUpdatedAttribute
{
    use DateUpdatedRules, DateUpdatedMutator;

    /**
     * @var DateTime|null
     */
    private $dateUpdated;

    /**
     * @inheritdoc
     */
    public function dateUpdatedAttributeLabel()
    {

        return [
            'dateUpdated' => Craft::t('app', 'Date Updated')
        ];
    }
}
