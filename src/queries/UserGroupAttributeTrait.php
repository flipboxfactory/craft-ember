<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queries;

use craft\db\Query;
use craft\db\QueryAbortedException;
use craft\models\UserGroup;
use craft\records\UserGroup as UserGroupRecord;
use flipbox\craft\ember\helpers\QueryHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait UserGroupAttributeTrait
{
    /**
     * The user group(s) that the resulting organizations’ users must be in.
     *
     * @var string|string[]|int|int[]|UserGroup|UserGroup[]|null
     */
    public $userGroup;

    /**
     * @param string|string[]|int|int[]|UserGroup|UserGroup[]|null $value
     * @return static The query object
     */
    public function setUserGroup($value)
    {
        $this->userGroup = $value;
        return $this;
    }

    /**
     * @param string|string[]|int|int[]|UserGroup|UserGroup[]|null $value
     * @return static The query object
     */
    public function userGroup($value)
    {
        return $this->setUserGroup($value);
    }

    /**
     * @param string|string[]|int|int[]|UserGroup|UserGroup[]|null $value
     * @return static The query object
     */
    public function setUserGroupId($value)
    {
        return $this->setUserGroup($value);
    }

    /**
     * @param string|string[]|int|int[]|UserGroup|UserGroup[]|null $value
     * @return static The query object
     */
    public function userGroupId($value)
    {
        return $this->setUserGroup($value);
    }

    /**
     * @param $value
     * @return int
     * @throws QueryAbortedException
     */
    protected function parseUserGroupValue($value)
    {
        $return = QueryHelper::prepareParam(
            $value,
            function (string $handle) {
                $value = (new Query())
                    ->select(['id'])
                    ->from([UserGroupRecord::tableName()])
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
