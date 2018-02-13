<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\traits;

use Craft;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait FieldLayoutAttribute
{
    use FieldLayoutRules, FieldLayoutMutator;

    /**
     * @var int|null
     */
    private $fieldLayoutId;

    /**
     * @return array
     */
    protected function fieldLayoutFields(): array
    {
        return [
            'fieldLayoutId'
        ];
    }

    /**
     * @return array
     */
    protected function fieldLayoutAttributes(): array
    {
        return [
            'fieldLayoutId'
        ];
    }

    /**
     * @return array
     */
    protected function fieldLayoutAttributeLabels(): array
    {
        return [
            'fieldLayoutId' => Craft::t('organization', 'Field Layout Id')
        ];
    }
}
