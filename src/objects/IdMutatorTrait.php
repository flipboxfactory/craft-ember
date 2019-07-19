<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

/**
 * @property int|null $id
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait IdMutatorTrait
{
    /**
     * @return int|null
     */
    public function getId()
    {
        if (null === $this->id) {
            return null;
        }

        return (int) $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId(int $id = null)
    {
        $this->id = $id;
        return $this;
    }
}
