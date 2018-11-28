<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions;

use Craft;
use yii\base\BaseObject;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait PopulateTrait
{
    /**
     * These are the default body params that we're accepting.  You can lock down specific fact attributes this way.
     *
     * @return array
     */
    abstract protected function validBodyParams(): array;

    /**
     * @param BaseObject $object
     * @return BaseObject
     */
    protected function populate(BaseObject $object): BaseObject
    {
        // Valid attribute values
        $attributes = $this->attributeValuesFromBody();

        /** @var BaseObject $object */
        $object = Craft::configure(
            $object,
            $attributes
        );

        return $object;
    }

    /**
     * @return array
     */
    protected function attributeValuesFromBody(): array
    {
        $request = Craft::$app->getRequest();

        $attributes = [];
        foreach ($this->validBodyParams() as $bodyParam => $attribute) {
            if (is_numeric($bodyParam)) {
                $bodyParam = $attribute;
            }
            if (($value = $request->getBodyParam($bodyParam)) !== null) {
                $attributes[$attribute] = $value;
            }
        }

        return $attributes;
    }
}
