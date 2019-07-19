<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\records;

use Craft;
use craft\models\Site as SiteModel;
use craft\records\Site as SiteRecord;
use flipbox\craft\ember\models\SiteRulesTrait;
use flipbox\craft\ember\objects\SiteMutatorTrait;
use yii\db\ActiveQueryInterface;

/**
 * Intended to be used on an ActiveRecord, this class provides `$this->siteId` attribute along with 'getters'
 * and 'setters' to ensure continuity between the Id and Object.  A site object is lazy loaded when called.
 * In addition, ActiveRecord rules are available.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @property SiteRecord $siteRecord
 */
trait SiteAttributeTrait
{
    use ActiveRecordTrait,
        SiteRulesTrait,
        SiteMutatorTrait;

    /**
     * @inheritdoc
     */
    public function siteAttributes(): array
    {
        return [
            'siteId'
        ];
    }

    /**
     * @inheritdoc
     */
    public function siteAttributeLabels(): array
    {
        return [
            'siteId' => Craft::t('app', 'Site Id')
        ];
    }

    /**
     * @inheritDoc
     */
    protected function internalSetSiteId(int $id = null)
    {
        $this->setAttribute('siteId', $id);
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function internalGetSiteId()
    {
        if (null === ($id = $this->getAttribute('siteId'))) {
            return null;
        }
        return (int)$id;
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

        return $this->resolveSiteFromId();
    }

    /**
     * @return SiteModel|null
     */
    private function resolveSiteFromRelation()
    {
        if (false === $this->isRelationPopulated('siteRecord')) {
            return null;
        }

        if (null === ($record = $this->getRelation('siteRecord'))) {
            return null;
        }

        /** @var SiteRecord $record */

        return Craft::$app->getSites()->getSiteById($record->id);
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
            ['id' => 'siteId']
        );
    }
}
