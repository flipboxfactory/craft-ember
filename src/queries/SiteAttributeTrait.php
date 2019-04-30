<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queries;

use craft\db\Query;
use craft\db\QueryAbortedException;
use craft\models\Site;
use craft\records\Site as SiteRecord;
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
     * @return array|string
     * @throws QueryAbortedException
     */
    protected function parseSiteValue($value)
    {
        $return = QueryHelper::prepareParam(
            $value,
            function (string $handle) {
                $value = (new Query())
                    ->select(['id'])
                    ->from([SiteRecord::tableName()])
                    ->where(['handle' => $handle])
                    ->scalar();
                return empty($value) ? false : $value;
            }
        );

        if ($return !== null && empty($return)) {
            throw new QueryAbortedException();
        }

        return $return;
    }
}
