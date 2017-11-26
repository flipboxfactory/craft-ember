<?php

namespace flipbox\ember\actions\element\traits;

use Craft;
use craft\base\ElementInterface;

trait Lookup
{
    /**
     * @param int $id
     * @return null|ElementInterface
     */
    protected function findById(int $id)
    {
        return Craft::$app->getElements()->getElementById($id);
    }
}
