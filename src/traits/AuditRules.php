<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\traits;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait AuditRules
{
    use DateCreatedRules, DateUpdatedRules, UidRules;

    /**
     * @inheritdoc
     */
    public function auditRules()
    {
        return array_merge(
            $this->uidRules(),
            $this->dateCreatedRules(),
            $this->dateUpdatedRules()
        );
    }
}
