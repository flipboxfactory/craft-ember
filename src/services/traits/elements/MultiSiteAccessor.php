<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits\elements;

use craft\base\ElementInterface;
use craft\errors\ElementNotFoundException;
use flipbox\ember\helpers\SiteHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait MultiSiteAccessor
{
    use Accessor;

    /**
     * @param $identifier
     * @param int|null $siteId
     * @return array
     */
    protected function identifierCondition($identifier, int $siteId = null): array
    {
        $base = [
            'siteId' => SiteHelper::ensureSiteId($siteId),
            'status' => null
        ];

        if (is_array($identifier)) {
            return array_merge($base, $identifier);
        }

        $base['id'] = $identifier;

        return $base;
    }

    /*******************************************
     * FIND / GET
     *******************************************/

    /**
     * @param int|null $siteId
     * @return ElementInterface[]
     */
    public function findAll(int $siteId = null)
    {
        $config = [];
        if ($siteId !== null) {
            $config['siteId'] = $siteId;
        }
        return $this->getQuery($config)->all();
    }

    /**
     * @param $identifier
     * @param int|null $siteId
     * @return ElementInterface|null
     */
    public function find($identifier, int $siteId = null)
    {
        if ($identifier instanceof ElementInterface) {
            return $identifier;
        }

        return $this->findByQuery($this->getQuery(
            $this->identifierCondition($identifier, $siteId)
        ));
    }

    /**
     * @param $identifier
     * @param int $siteId
     * @return ElementInterface
     * @throws ElementNotFoundException
     */
    public function get($identifier, int $siteId = null): ElementInterface
    {
        if (!$object = $this->find($identifier, $siteId)) {
            $this->notFoundException();
        }

        return $object;
    }
}
