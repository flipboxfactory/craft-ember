<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\records;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @property int $statusCodeSuccess
 */
trait DeleteRecordTrait
{
    use ManageRecordTrait;

    /**
     * HTTP success response code
     *
     * @return int
     */
    protected function statusCodeSuccess(): int
    {
        return $this->statusCodeSuccess ?? 204;
    }
}
