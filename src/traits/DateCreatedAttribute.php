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
 * @property DateTime|null $dateCreated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait DateCreatedAttribute
{
    use DateCreatedRules, DateCreatedMutator;

    /**
     * @var DateTime|null
     */
    private $dateCreated;

    /**
     * @inheritdoc
     */
    public function dateCreatedAttributeLabel()
    {
        return [
            'dateCreated' => Craft::t('app', 'Date Created')
        ];
    }
}
