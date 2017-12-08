<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\records;

use flipbox\ember\traits\AuditRules;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class ActiveRecord extends \craft\db\ActiveRecord
{
    use AuditRules;

    /**
     * The table alias
     */
    const TABLE_ALIAS = '';

    /**
     * {@inheritdoc}
     */
    public static function tableAlias()
    {
        return static::TABLE_ALIAS;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%' . static::tableAlias() . '}}';
    }

    /*******************************************
     * RULES
     *******************************************/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            $this->auditRules()
        );
    }
}
