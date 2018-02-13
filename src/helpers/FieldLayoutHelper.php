<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\helpers;

use Craft;
use craft\models\FieldLayout;
use craft\models\FieldLayout as FieldLayoutModel;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class FieldLayoutHelper
{
    /**
     * @param $fieldLayout
     * @return FieldLayout|null
     */
    public static function resolve($fieldLayout = null): FieldLayout
    {
        if ($fieldLayout instanceof FieldLayoutModel) {
            return $fieldLayout;
        }

        if (is_numeric($fieldLayout)) {
            return Craft::$app->getFields()->getLayoutById($fieldLayout);
        }

        if (is_string($fieldLayout)) {
            return Craft::$app->getFields()->getLayoutByType($fieldLayout);
        }

        try {
            $object = Craft::createObject(FieldLayout::class, [$fieldLayout]);
        } catch (\Exception $e) {
            $object = new FieldLayout();
            ObjectHelper::populate(
                $object,
                $fieldLayout
            );
        }

        /** @var FieldLayout $object */
        return $object;
    }
}
