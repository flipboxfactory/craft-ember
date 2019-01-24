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
     * @return array|string
     */
    protected function parseFieldValue($value)
    {
        return QueryHelper::prepareParam(
            $value,
            function(string $handle) {
                return Craft::$app->getFields()->getFieldByHandle($handle);
            }
        );
    }
}
