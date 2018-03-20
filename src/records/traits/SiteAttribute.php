<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\records\traits;

use craft\models\Site as SiteModel;
use craft\records\Site as SiteRecord;
use flipbox\ember\helpers\SiteHelper;
use flipbox\ember\traits\SiteMutator;
use flipbox\ember\traits\SiteRules;
use yii\db\ActiveQueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @method SiteModel parentResolveSite()
 */
trait SiteAttribute
{
    use ActiveRecord,
        SiteRules,
        SiteMutator {
            resolveSite as parentResolveSite;
    }

    /**
     * Get associated siteId
     *
     * @return int|null
     */
    public function getSiteId()
    {
        $siteId = $this->getAttribute('siteId');
        if (null === $siteId && null !== $this->site) {
            $siteId = $this->siteId = $this->site->id;
        }

        return $siteId;
    }

    /**
     * @return SiteModel|null
     * @throws \yii\base\InvalidArgumentException
     */
    protected function resolveSite()
    {
        if ($site = $this->resolveSiteFromRelation()) {
            return $site;
        }

        return $this->parentResolveSite();
    }

    /**
     * @return SiteModel|null
     * @throws \yii\base\InvalidArgumentException
     */
    private function resolveSiteFromRelation()
    {
        if (false === $this->isRelationPopulated('siteRecord')) {
            return null;
        }

        return SiteHelper::resolve($this->getRelation('siteRecord'));
    }

    /**
     * Get the associated Site
     *
     * @return ActiveQueryInterface
     */
    public function getSiteRecord()
    {
        return $this->hasOne(
            SiteRecord::class,
            ['siteId' => 'id']
        );
    }
}
