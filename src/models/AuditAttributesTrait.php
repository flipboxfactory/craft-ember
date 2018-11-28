<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\models;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait AuditAttributesTrait
{
    use DateCreatedAttributeTrait, DateUpdatedAttributeTrait, UidAttributeTrait;

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
            'dateUpdated',
            'uid'
        ];
    }

    /**
     * @inheritdoc
     */
    public function auditAttributeLabels()
    {
        return array_merge(
            $this->uidAttributeLabel(),
            $this->dateCreatedAttributeLabels(),
            $this->dateUpdatedAttributeLabels()
        );
    }
}
