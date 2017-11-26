<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\actions\element;

use flipbox\ember\actions\model\ModelView;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class ElementView extends ModelView
{
    use traits\Lookup;
}
