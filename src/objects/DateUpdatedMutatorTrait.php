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
 * @property DateTime|null $dateUpdated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait DateUpdatedMutatorTrait
{
    /**
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setDateUpdated($value)
    {
        if ($value) {
            $value = DateTimeHelper::toDateTime($value);
        }

        $this->dateUpdated = $value ?: null;

        return $this;
    }

    /**
     * @return DateTime|false|null
     * @throws \Exception
     */
    public function getDateUpdated()
    {
        if (empty($this->dateUpdated)) {
            return DateTimeHelper::toDateTime(
                new DateTime('now')
            );
        }

        return $this->dateUpdated;
    }

    /**
     * @return false|string|null
     * @throws \Exception
     */
    public function getDateUpdatedIso8601()
    {

        // Get the datetime
        if (!$dateCreated = $this->getDateUpdated()) {
            return null;
        }

        // Convert it to iso
        if (!$iso = DateTimeHelper::toIso8601($dateCreated)) {
            return null;
        }

        return $iso;
    }
}
