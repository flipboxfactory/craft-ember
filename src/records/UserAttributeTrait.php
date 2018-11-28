<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\records;

use Craft;
use craft\elements\User as UserElement;
use craft\records\User as UserRecord;
use flipbox\craft\ember\models\UserRulesTrait;
use flipbox\craft\ember\objects\UserMutatorTrait;
use yii\db\ActiveQueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @property UserRecord[] $userRecord
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
     * Get associated userId
     *
     * @return int|null
     */
    public function getUserId()
    {
        $id = $this->getAttribute('userId');
        if (null === $id && null !== $this->user) {
            $id = $this->userId = $this->user->id;
        }

        return $id;
    }

    /**
     * @return UserElement|null
     */
    protected function resolveUser()
    {
        if ($model = $this->resolveUserFromRelation()) {
            return $model;
        }

        return $this->resolveUserFromId();
    }

    /**
     * @return UserElement|null
     */
    private function resolveUserFromRelation()
    {
        if (false === $this->isRelationPopulated('userRecord')) {
            return null;
        }

        /** @var UserRecord $record */
        $record = $this->getRelation('userRecord');
        if (null === $record) {
            return null;
        }

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
            ['userId' => 'id']
        );
    }
}
