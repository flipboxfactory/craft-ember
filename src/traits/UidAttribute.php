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
trait UidAttribute
{
    use UidRules;

    /**
     * @var string UID
     */
    public $uid;

    /**
     * @inheritdoc
     */
    public function uidAttributeLabel()
    {
        return [
            'uid' => Craft::t('app', 'UID')
        ];
    }
}
