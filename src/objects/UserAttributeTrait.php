<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

/**
 * @property int|null $userId
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait UserAttributeTrait
{
    use UserMutatorTrait;

    /**
     * @var int|null
     */
    private $userId;

    /**
     * @inheritDoc
     */
    protected function internalSetUserId(int $id = null)
    {
        $this->userId = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function internalGetUserId()
    {
        return $this->userId === null ? null : (int) $this->userId;
    }
}
