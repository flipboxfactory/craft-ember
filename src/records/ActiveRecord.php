<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\records;

use flipbox\craft\ember\models\DateCreatedRulesTrait;
use flipbox\craft\ember\models\DateUpdatedRulesTrait;
use flipbox\craft\ember\models\UidRulesTrait;

/**
 * This class provides additional functionality to Craft's ActiveRecord:
 *
 * Table Alias - By default the table alias is the name of the table, without the opening '{{%' and closing '}}'
 * syntax.  Additionally, the table alias (and therefore table name) is set via a constant.
 *
 * Audit Attributes - Craft defines 'dateCreated', 'dateUpdated' and 'UID' as audit attributes which are automatically
 * accounted for.  It is important to note that these attributes are also set as 'safe' for the 'default' scenario;
 * therefore making them easily set-able and accessible.
 *
 * Getter Priority Attributes - When set, the attribute will call the 'getter' method instead of the traditional
 * 'getAttribute' method.  Examine the case when using relational Id attributes; we'll use 'userId' as an example.
 * Calling the attribute directly `$this->userId` would call `$this->getUserId()` which should look at the relation
 * `$this->user->getId()` to ensure the related object and Id are the same.  Commonly, this is useful when continuity
 * between the Id an object need to be upheld.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
abstract class ActiveRecord extends \craft\db\ActiveRecord
{
    use DateCreatedRulesTrait,
        DateUpdatedRulesTrait,
        UidRulesTrait;

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
            $this->dateCreatedRules(),
            $this->dateUpdatedRules(),
            $this->uidRules()
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
