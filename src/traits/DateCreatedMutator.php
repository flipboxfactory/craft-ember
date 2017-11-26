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
 * @property DateTime|null $dateCreated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait DateCreatedMutator
{
    /**
     * @param $value
     * @return $this
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
     * @return DateTime
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

    /**
     * @return string|null
     */
    public function getDateCreatedIso8601()
    {

        // Get the datetime
        if (!$dateCreated = $this->getDateCreated()) {
            return null;
        }

        // Convert it to iso
        if (!$iso = DateTimeHelper::toIso8601($dateCreated)) {
            return null;
        }

        return $iso;
    }
}
