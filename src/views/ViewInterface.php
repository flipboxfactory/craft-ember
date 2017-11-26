<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/guardian/license
 * @link       https://www.flipboxfactory.com/software/guardian/
 */

namespace flipbox\ember\views;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
interface ViewInterface
{
    /**
     * @param array $params
     * @return mixed
     */
    public function render(array $params = []);
}
