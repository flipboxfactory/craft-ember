<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\records;

use craft\helpers\Json;
use flipbox\craft\ember\exceptions\RecordNotFoundException;
use flipbox\craft\ember\models\DateCreatedRulesTrait;
use flipbox\craft\ember\models\DateUpdatedRulesTrait;
use flipbox\craft\ember\models\UidRulesTrait;
use yii\base\InvalidConfigException;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;

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
     * These attributes will have their 'setter' methods take priority over the normal attribute setting.
     *
     * These setters are commonly used to ensure an associated model and their identifier are in sync.  For example,
     * a userId attribute and a user object (with an id attribute).
     *
     * @var array
     */
    protected $setterPriorityAttributes = [];

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
     * OVERRIDE CONDITION HANDLING
     *******************************************/

    /**
     * Finds ActiveRecord instance(s) by the given condition.
     * This method is internally called by [[findOne()]] and [[findAll()]].
     * @param mixed $condition please refer to [[findOne()]] for the explanation of this parameter
     * @return ActiveQueryInterface the newly created [[ActiveQueryInterface|ActiveQuery]] instance.
     * @throws InvalidConfigException if there is no primary key defined.
     * @internal
     */
    protected static function findByCondition($condition)
    {
        $query = static::find();

        if (!ArrayHelper::isAssociative($condition)) {
            // query by primary key
            $primaryKey = static::primaryKey();
            if (isset($primaryKey[0])) {
                $pk = $primaryKey[0];
                if (!empty($query->join) || !empty($query->joinWith)) {
                    $pk = static::tableName() . '.' . $pk;
                }
                // if condition is scalar, search for a single primary key, if it is array, search for
                // multiple primary key values
                $condition = [$pk => is_array($condition) ? array_values($condition) : $condition];
            } else {
                throw new InvalidConfigException('"' . get_called_class() . '" must have a primary key.');
            }
        } elseif (is_array($condition)) {
            foreach ($condition as $key => $value) {
                if ($query->canSetProperty($key)) {
                    $query->{$key} = $value;
                    unset($condition[$key]);
                }
            }

            /** @noinspection PhpInternalEntityUsedInspection */
            $condition = static::filterCondition($condition);
        }

        return $query->andWhere($condition);
    }


    /*******************************************
     * FIND
     *******************************************/

    /**
     * @inheritdoc
     */
    public static function findOne($condition)
    {
        if ($condition instanceof self) {
            return $condition;
        }

        return parent::findOne($condition);
    }


    /*******************************************
     * GET
     *******************************************/

    /**
     * @param $condition
     * @return static|null
     * @throws RecordNotFoundException
     */
    public static function getOne($condition)
    {
        if (null === ($record = static::findOne($condition))) {
            throw new RecordNotFoundException(
                sprintf(
                    "Record not found with the following condition: %s",
                    Json::encode($condition)
                )
            );
        }

        return $record;
    }

    /**
     * @param $condition
     * @return static[]
     * @throws RecordNotFoundException
     */
    public static function getAll($condition)
    {
        $records = static::findAll($condition);

        if (empty($records)) {
            throw new RecordNotFoundException(
                sprintf(
                    "Records not found with the following condition: %s",
                    Json::encode($condition)
                )
            );
        }

        return $records;
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
     * ATTRIBUTES
     *******************************************/

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($this->hasGetterPriority($name)) {
            $this->{'get' . $name}();
        }

        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if ($this->hasSetterPriority($name)) {
            $this->{'set' . $name}();
            return;
        }

        parent::__set($name, $value);
    }

    /**
     * @inheritdoc
     */
    public function getDirtyAttributes($names = null)
    {
        $attributes = $names ?: $this->getterPriorityAttributes;

        // Call each attribute to see if the 'getter' has a value
        foreach ($attributes as $attribute) {
            if ($this->hasGetterPriority($attribute)) {
                $this->{'get' . $attribute}();
            }
        }

        return parent::getDirtyAttributes($names);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function hasSetterPriority($name)
    {
        return in_array($name, $this->setterPriorityAttributes, true) &&
            method_exists($this, 'set' . $name);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function hasGetterPriority($name)
    {
        return in_array($name, $this->getterPriorityAttributes, true) &&
            method_exists($this, 'get' . $name);
    }
}
