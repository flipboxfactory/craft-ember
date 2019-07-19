<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\records;

use Craft;
use craft\elements\User;
use craft\records\User as UserRecord;
use flipbox\craft\ember\models\UserRulesTrait;
use flipbox\craft\ember\objects\UserMutatorTrait;
use yii\db\ActiveQueryInterface;

/**
 * Intended to be used on an ActiveRecord, this class provides `$this->userId` attribute along with 'getters'
 * and 'setters' to ensure continuity between the Id and Object.  An user object is lazy loaded when called.
 * In addition, ActiveRecord rules are available.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @property UserRecord $userRecord
 */
trait UserAttributeTrait
{
    use ActiveRecordTrait,
        UserRulesTrait,
        UserMutatorTrait;

    /**
     * @inheritdoc
     */
    public function userAttributes(): array
    {
        return [
            'userId'
        ];
    }

    /**
     * @inheritdoc
     */
    public function userAttributeLabels(): array
    {
        return [
            'userId' => Craft::t('app', 'User Id')
        ];
    }

    /**
     * @inheritDoc
     */
    protected function internalSetUserId(int $id = null)
    {
        $this->setAttribute('userId', $id);
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function internalGetUserId()
    {
        if (null === ($id = $this->getAttribute('userId'))) {
            return null;
        }
        return (int)$id;
    }

    /**
     * @return User|null
     */
    protected function resolveUser()
    {
        if ($model = $this->resolveUserFromRelation()) {
            return $model;
        }

        return $this->resolveUserFromId();
    }

    /**
     * @return User|null
     */
    private function resolveUserFromRelation()
    {
        if (false === $this->isRelationPopulated('userRecord')) {
            return null;
        }

        if (null === ($record = $this->getRelation('userRecord'))) {
            return null;
        }

        /** @var UserRecord $record */

        return Craft::$app->getUsers()->getUserById($record->id);
    }

    /**
     * Get the associated User
     *
     * @return ActiveQueryInterface
     */
    public function getUserRecord(): ActiveQueryInterface
    {
        return $this->hasOne(
            UserRecord::class,
            ['id' => 'userId']
        );
    }
}
