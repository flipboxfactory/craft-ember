<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queries;

use Craft;
use craft\models\Site;
use flipbox\craft\ember\helpers\QueryHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait SiteAttributeTrait
{
    /**
     * The sites(s) that the resulting records must have.
     *
     * @var string|string[]|int|int[]|Site|Site[]|null
     */
    public $site;

    /**
     * @param string|string[]|int|int[]|Site|Site[]|null $value
     * @return static The query object
     */
    public function setSite($value)
    {
        $this->site = $value;
        return $this;
    }

    /**
     * @param string|string[]|int|int[]|Site|Site[]|null $value
     * @return static The query object
     */
    public function site($value)
    {
        return $this->setSite($value);
    }

    /**
     * @param string|string[]|int|int[]|Site|Site[]|null $value
     * @return static The query object
     */
    public function setSiteId($value)
    {
        return $this->setSite($value);
    }

    /**
     * @param string|string[]|int|int[]|Site|Site[]|null $value
     * @return static The query object
     */
    public function siteId($value)
    {
        return $this->setSite($value);
    }

    /**
     * @param $value
     * @param string $join
     * @return array
     */
    protected function parseSiteValue($value, string $join = 'or'): array
    {
        if (false === QueryHelper::parseBaseParam($value, $join)) {
            foreach ($value as $operator => &$v) {
                $this->resolveSiteValue($operator, $v);
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
    protected function resolveSiteValue($operator, &$value)
    {
        if (false === QueryHelper::findParamValue($value, $operator)) {
            if (is_string($value)) {
                $value = $this->resolveStringStringValue($value);
            }

            if ($value instanceof Site) {
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
    protected function resolveStringStringValue(string $value)
    {
        if (!$site = Craft::$app->getSites()->getSiteByHandle($value)) {
            return null;
        }
        return $site->id;
    }
}
