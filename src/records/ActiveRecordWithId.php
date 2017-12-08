<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\records;

use flipbox\ember\interfaces\IdAttributeInterface;
use flipbox\ember\records\traits\IdAttribute;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class ActiveRecordWithId extends ActiveRecord implements IdAttributeInterface
{
    use IdAttribute;

    /*******************************************
     * RULES
     *******************************************/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            $this->idRules()
        );
    }
}
