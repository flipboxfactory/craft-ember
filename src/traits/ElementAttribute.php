<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\traits;

use Craft;
use craft\base\Element;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ElementAttribute
{
    use ElementRules, ElementMutator;

    /**
     * @var int|null
     */
    private $elementId;

    /**
     * @var Element|null
     */
    private $element;

    /**
     * @return array
     */
    protected function elementFields(): array
    {
        return [
            'elementId'
        ];
    }

    /**
     * @return array
     */
    protected function elementAttributes(): array
    {
        return [
            'elementId'
        ];
    }

    /**
     * @return array
     */
    protected function elementAttributeLabels(): array
    {
        return [
            'elementId' => Craft::t('app', 'Element Id')
        ];
    }
}
