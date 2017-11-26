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
trait IdAttribute
{
    use IdRules, IdMutator;

    /**
     * @var integer
     */
    public $id;

    /**
     * @inheritdoc
     */
    protected function idAttributeLabel()
    {
        return [
            'id' => Craft::t('app', 'Id')
        ];
    }
}
