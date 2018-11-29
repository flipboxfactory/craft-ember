<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queries;

use Craft;
use craft\base\Field;
use craft\base\FieldInterface;
use flipbox\craft\ember\helpers\QueryHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait FieldAttributeTrait
{
    /**
     * The field(s) that the resulting organizations’ fields must have.
     *
     * @var string|string[]|int|int[]|FieldInterface|FieldInterface[]|null
     */
    public $field;

    /**
     * @param string|string[]|int|int[]|FieldInterface|FieldInterface[]|null $value
     * @return static The query object
     */
    public function setField($value)
    {
        $this->field = $value;
        return $this;
    }

    /**
     * @param string|string[]|int|int[]|FieldInterface|FieldInterface[]|null $value
     * @return static The query object
     */
    public function field($value)
    {
        return $this->setField($value);
    }

    /**
     * @param string|string[]|int|int[]|FieldInterface|FieldInterface[]|null $value
     * @return static The query object
     */
    public function setFieldId($value)
    {
        return $this->setField($value);
    }

    /**
     * @param string|string[]|int|int[]|FieldInterface|FieldInterface[]|null $value
     * @return static The query object
     */
    public function fieldId($value)
    {
        return $this->setField($value);
    }

    /**
     * @param $value
     * @param string $join
     * @return array
     */
    protected function parseFieldValue($value, string $join = 'or'): array
    {
        if (false === QueryHelper::parseBaseParam($value, $join)) {
            foreach ($value as $operator => &$v) {
                $this->resolveFieldValue($operator, $v);
            }
        }

        // Filter null and empties
        $value = array_filter($value, function ($arr): bool {
            return $arr !== null && $arr !== '';
        });

        if (empty($value)) {
            return [];
        }

        return array_merge([$join], $value);
    }

    /**
     * @param $operator
     * @param $value
     */
    protected function resolveFieldValue($operator, &$value)
    {
        if (false === QueryHelper::findParamValue($value, $operator)) {
            if (is_string($value)) {
                $value = $this->resolveFieldStringValue($value);
            }

            if ($value instanceof FieldInterface) {
                /** @var Field $value */
                $value = $value->id;
            }

            if ($value) {
                $value = QueryHelper::assembleParamValue($value, $operator);
            }
        }
    }

    /**
     * @param string $value
     * @return int|null
     */
    protected function resolveFieldStringValue(string $value)
    {
        /** @var Field $field */
        if (!$field = Craft::$app->getFields()->getFieldByHandle($value)) {
            return null;
        }
        return $field->id;
    }
}
