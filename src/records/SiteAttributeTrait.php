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
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @method SiteModel parentResolveSite()
 */
trait SiteAttributeTrait
{
    use ActiveRecordTrait,
        SiteRulesTrait,
        SiteMutatorTrait {
        resolveSite as parentResolveSite;
    }

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
            ['siteId' => 'id']
        );
    }
}
