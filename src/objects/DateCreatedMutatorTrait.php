<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

use craft\helpers\DateTimeHelper;
use DateTime;

/**
 * @property DateTime|null $dateCreated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait DateCreatedMutatorTrait
{
    /**
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setDateCreated($value)
    {
        if ($value) {
            $value = DateTimehelper::toDateTime($value);
        }

        $this->dateCreated = $value ?: null;

        return $this;
    }

    /**
     * @return DateTime|false|null
     * @throws \Exception
     */
    public function getDateCreated()
    {
        if (empty($this->dateCreated)) {
            return DateTimeHelper::toDateTime(
                new DateTime('now')
            );
        }

        return $this->dateCreated;
    }
}
