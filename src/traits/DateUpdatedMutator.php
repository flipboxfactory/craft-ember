<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\traits;

use craft\helpers\DateTimeHelper;
use DateTime;

/**
 * @property DateTime|null $dateUpdated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait DateUpdatedMutator
{
    /**
     * @param $value
     * @return $this
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
     * @return DateTime
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
     * @return string|null
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
