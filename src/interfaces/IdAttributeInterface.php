<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\interfaces;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
interface IdAttributeInterface
{
    /**
     * @return int|null
     */
    public function getId();

    /**
     * @param $id
     * @return static
     */
    public function setId($id);
}
