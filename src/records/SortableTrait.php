<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\records;

use Craft;
use flipbox\craft\ember\helpers\SortOrderHelper;
use yii\db\ActiveQuery;

/**
 * This class bootstraps record sorting, ensuring a sequential order is upheld.  There are a couple key concepts to
 * consider:
 *
 * Target Attribute - The attribute used as the anchor.  For example, if your table consists of users and categories
 * and you need to order users per category, the target would be the category column.  User's would be sorted
 * per category.
 *
 * Sort Attribute - By default this will likely be 'sortOrder', but it's possible to name it something else like
 * 'userOrder'.
 *
 * Sort Order Condition - A query condition used to accurately identify the records in a sort order. For example,
 * if your table consists of users and categories and the sort order is ordering users per category, the condition
 * would look like:
 *
 * ```
 * [
 *      'userId' => $this->userId
 * ]
 * ```
 * Additionally, some sort orders may be site specific, therefore also passing a 'siteId' condition would only apply the
 * re-ordering to the specified site.
 *
 * ### Usage Examples
 *
 * public function beforeSave($insert)
 * {
 *      $this->ensureSortOrder(
 *           [
 *               'userId' => $this->userId
 *           ],
 *            'userOrder' // overriding the default 'sortOrder'
 *        );
 *
 *      return parent::beforeSave($insert);
 * }
 *
 * public function afterSave($insert, $changedAttributes)
 * {
 *      $this->autoReOrder(
 *          'categoryId',
 *           [
 *               'userId' => $this->userId
 *           ],
 *            'userOrder' // overriding the default 'sortOrder'
 *        );
 *
 *      parent::afterSave($insert, $changedAttributes);
 * }
 *
 * public function afterDelete()
 * {
 *      $this->sequentialOrder(
 *          'categoryId',
 *           [
 *               'userId' => $this->userId
 *           ],
 *            'userOrder' // overriding the default 'sortOrder'
 *        );
 *
 *      parent::afterDelete();
 * }
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait SortableTrait
{
    use ActiveRecordTrait;

    /**
     * Whether sort order should be checked.
     *
     * @var bool
     */
    private $saveSortOrder = true;

    /**
     * @return static
     */
    public function enforceSortOrder(): self
    {
        $this->saveSortOrder = true;
        return $this;
    }

    /**
     * @return static
     */
    public function ignoreSortOrder(): self
    {
        $this->saveSortOrder = false;
        return $this;
    }

    /**
     * Returns the table name
     *
     * @return string
     */
    abstract public static function tableName();

    /**
     * @inheritdoc
     *
     * @return ActiveQuery
     */
    abstract public static function find();

    /**
     * Ensure a sort order is set.  If a sort order is not provided, it will be added to the end.
     *
     * @param array $sortOrderCondition
     * @param string $sortOrderAttribute
     */
    protected function ensureSortOrder(
        array $sortOrderCondition = [],
        string $sortOrderAttribute = 'sortOrder'
    ) {
        if (!$this->saveSortOrder) {
            return;
        }

        if ($this->getAttribute($sortOrderAttribute) === null) {
            $this->setAttribute(
                $sortOrderAttribute,
                $this->nextSortOrder(
                    $sortOrderCondition,
                    $sortOrderAttribute
                )
            );
        }
    }

    /**
     * Ensure all sort order's following this record are in sequential order. As an
     * example, a record may update the sort order from '4' to '1' which would result in all records after
     * this one to be altered in sequential order.
     *
     * @param string $targetAttribute
     * @param array $sortOrderCondition
     * @param string $sortOrderAttribute
     * @throws \yii\db\Exception
     */
    protected function sequentialOrder(
        string $targetAttribute,
        array $sortOrderCondition = [],
        string $sortOrderAttribute = 'sortOrder'
    ) {
        if (!$this->saveSortOrder) {
            return;
        }

        // All records (sorted)
        $sortOrder = $this->sortOrderQuery($sortOrderCondition, $sortOrderAttribute)
            ->indexBy($targetAttribute)
            ->select([$sortOrderAttribute])
            ->column();

        if (count($sortOrder) > 0) {
            $this->saveNewOrder(
                array_flip(array_combine(
                    range($sortOrder, count($sortOrder)),
                    array_keys($sortOrder)
                )),
                $targetAttribute,
                $sortOrderCondition,
                $sortOrderAttribute
            );
        }
    }

    /**
     * Ensure all sort order's following this record are in sequential order. As an
     * example, a record may update the sort order from '4' to '1' which would result in all records after
     * this one to be altered in sequential order.
     *
     * @param string $targetAttribute
     * @param array $sortOrderCondition
     * @param string $sortOrderAttribute
     * @throws \yii\db\Exception
     */
    protected function autoReOrder(
        string $targetAttribute,
        array $sortOrderCondition = [],
        string $sortOrderAttribute = 'sortOrder'
    ) {
        if (!$this->saveSortOrder) {
            return;
        }

        // All records (sorted)
        $sortOrder = $this->sortOrderQuery($sortOrderCondition, $sortOrderAttribute)
            ->indexBy($targetAttribute)
            ->select([$sortOrderAttribute])
            ->column();

        $affectedItems = SortOrderHelper::insertSequential(
            $sortOrder,
            $this->getAttribute($targetAttribute),
            $this->{$sortOrderAttribute}
        );

        if (empty($affectedItems) || is_bool($affectedItems)) {
            return;
        }

        $this->saveNewOrder(
            $affectedItems,
            $targetAttribute,
            $sortOrderCondition,
            $sortOrderAttribute
        );
    }

    /**
     * Get the next available sort order available
     *
     * @param array $sortOrderCondition
     * @param string $sortOrderAttribute
     * @return int
     */
    protected function nextSortOrder(
        array $sortOrderCondition = [],
        string $sortOrderAttribute = 'sortOrder'
    ): int {
        $maxSortOrder = $this->sortOrderQuery(
            $sortOrderCondition,
            $sortOrderAttribute
        )->max('[[' . $sortOrderAttribute . ']]');

        return ++$maxSortOrder;
    }


    /**
     * Creates a sort order query which will display all siblings ordered by their sort order
     *
     * @param array $sortOrderCondition
     * @param string $sortOrderAttribute
     * @return ActiveQuery
     */
    protected function sortOrderQuery(
        array $sortOrderCondition = [],
        string $sortOrderAttribute = 'sortOrder'
    ): ActiveQuery {
        return static::find()
            ->andWhere($sortOrderCondition)
            ->orderBy([
                $sortOrderAttribute => SORT_ASC,
                'dateUpdated' => SORT_DESC
            ]);
    }

    /**
     * Saves a new sort order.
     *
     * @param array $sortOrder The new sort order that needs to be saved.  The 'key' represents the target value and
     * the 'value' represent the sort order.
     * @param string $targetAttribute The target attribute that the new order is keyed on.
     * @param array $sortOrderCondition Additional condition params used to accurately identify the sort order that
     * need to be changed.  For example, some sort orders may be site specific, therefore passing a 'siteId' condition
     * would only apply the re-ordering to the specified site.
     * @param string $sortOrderAttribute The sort order attribute that needs to be updated
     * @return bool
     * @throws \yii\db\Exception
     */
    protected function saveNewOrder(
        array $sortOrder,
        string $targetAttribute,
        array $sortOrderCondition = [],
        string $sortOrderAttribute = 'sortOrder'
    ): bool {
        foreach ($sortOrder as $target => $order) {
            Craft::$app->getDb()->createCommand()
                ->update(
                    static::tableName(),
                    [$sortOrderAttribute => $order],
                    array_merge(
                        $sortOrderCondition,
                        [
                            $targetAttribute => $target
                        ]
                    )
                )
                ->execute();
        }

        return true;
    }
}
