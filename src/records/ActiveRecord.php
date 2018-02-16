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
     * These attributes will have their 'getter' methods take priority over the normal attribute lookup.  It's
     * VERY important to note, that the value returned from the getter should NEVER be different than the raw
     * attribute value set.  If, for whatever reason, the getter determines the attribute value is
     * incorrect, it should set the new value prior to returning it.
     *
     * These getters are commonly used to ensure an associated model and their identifier are in sync.  For example,
     * a userId attribute and a user object (with an id attribute).  An operation may have saved and set an Id on the
     * model, but the userId attribute remains null.  The getter method may check (and set) the value prior to
     * returning it.
     *
     * @var array
     */
    protected $getterPriorityAttributes = [];

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

    /*******************************************
     * MAGIC
     *******************************************/

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (in_array($name, $this->getterPriorityAttributes, true) &&
            method_exists($this, $getter)
        ) {
            return $this->$getter();
        }

        return parent::__get($name);
    }
}
