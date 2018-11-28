<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queries;

use Craft;
use craft\elements\User as UserElement;
use flipbox\craft\ember\helpers\QueryHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait UserAttributeTrait
{
    /**
     * The user(s) that the resulting organizations’ users must have.
     *
     * @var string|string[]|int|int[]|UserElement|UserElement[]|null
     */
    public $user;

    /**
     * @param string|string[]|int|int[]|UserElement|UserElement[]|null $value
     * @return static The query object
     */
    public function setUser($value)
    {
        $this->user = $value;
        return $this;
    }

    /**
     * @param string|string[]|int|int[]|UserElement|UserElement[]|null $value
     * @return static The query object
     */
    public function user($value)
    {
        return $this->setUser($value);
    }

    /**
     * @param string|string[]|int|int[]|UserElement|UserElement[]|null $value
     * @return static The query object
     */
    public function setUserId($value)
    {
        return $this->setUser($value);
    }

    /**
     * @param string|string[]|int|int[]|UserElement|UserElement[]|null $value
     * @return static The query object
     */
    public function userId($value)
    {
        return $this->setUser($value);
    }

    /**
     * @param $value
     * @param string $join
     * @return array
     */
    protected function parseUserValue($value, string $join = 'or'): array
    {
        if (false === QueryHelper::parseBaseParam($value, $join)) {
            foreach ($value as $operator => &$v) {
                $this->resolveUserValue($operator, $v);
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
    protected function resolveUserValue($operator, &$value)
    {
        if (false === QueryHelper::findParamValue($value, $operator)) {
            if (is_string($value)) {
                $value = $this->resolveUserStringValue($value);
            }

            if ($value instanceof UserElement) {
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
    protected function resolveUserStringValue(string $value)
    {
        if (!$element = Craft::$app->getUsers()->getUserByUsernameOrEmail($value)) {
            return null;
        }
        return $element->id;
    }
}
