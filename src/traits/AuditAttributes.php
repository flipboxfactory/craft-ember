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
trait AuditAttributes
{
    use DateCreatedAttribute, DateUpdatedAttribute, UidAttribute;

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

    /**
     * @inheritdoc
     */
    public function auditAttributes()
    {
        return [
            'dateCreated',
            'dateUpdated'
        ];
    }

    /**
     * @inheritdoc
     */
    public function auditFields()
    {
        return [
            'dateCreated' => 'dateCreatedIso8601',
            'dateUpdated' => 'dateUpdatedIso8601'
        ];
    }

    /**
     * @inheritdoc
     */
    public function auditAttributeLabels()
    {
        return array_merge(
            $this->uidAttributeLabel(),
            $this->dateCreatedAttributeLabel(),
            $this->dateUpdatedAttributeLabel()
        );
    }
}
