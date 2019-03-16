<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\cli;

use craft\base\ElementInterface;
use craft\elements\db\ElementQuery;
use craft\elements\db\ElementQueryInterface;
use flipbox\craft\ember\helpers\QueryHelper;
use yii\base\Action;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\helpers\Inflector;

/**
 * @property string $elementType
 * @property array $queryConfig
 * @property Action $action
 */
trait ElementQueryOptionsTrait
{
    /**
     * @var array
     * The query conditional id logic for (ElementQuery only) used to source the sync operation.
     * example: -id=1,2,4
     */
    public $queryId = [];

    /**
     * @var array
     * The query conditional status logic for (ElementQuery only) used to source the sync operation.
     * example: -status=active,pending
     */
    public $queryStatus = [];

    /**
     * @var array
     * The query conditional used to source the sync operation.
     * example: -where='and,elements.dateUpdated > "2000-01-01",elements.dateCreated < "2010-01-01"'
     */
    public $queryWhere = [];

    /**
     * @var int
     * The query limit logic used to source the sync operation. Set to -1 to remove limit.
     * example: -limit=10
     */
    public $queryLimit = 100;

    /**
     * @var int
     * The query offset logic used to source the sync operation.
     * example: -limit=0
     */
    public $queryOffset = 0;

    /**
     * @var ElementQueryInterface|ElementQuery
     */
    private $query;

    /**
     * Returns properties corresponding to the options for the action id
     * Child classes may override this method to specify possible properties.
     *
     * @param string $actionID the action id of the current request
     * @return array properties corresponding to the options for the action
     */
    abstract public function getOptionValues($actionID);

    /**
     * Returns option alias names.
     * Child classes may override this method to specify alias options.
     *
     * @return array the options alias names valid for the action
     * where the keys is alias name for option and value is option name.
     *
     * @since 2.0.8
     * @see options()
     */
    abstract public function optionAliases();

    /**
     * @return string
     * @throws Exception
     */
    protected function elementType(): string
    {
        if (null === ($elementType = $this->elementType ?? null)) {
            throw new Exception("Invalid element type.");
        }
        return $elementType;
    }

    /**
     * @return array
     */
    protected function queryConfig(): array
    {
        return $this->queryConfig ?? [];
    }

    /**
     * @inheritdoc
     */
    protected function queryOptions()
    {
        return [
            'queryId',
            'queryStatus',
            'queryWhere',
            'queryLimit',
            'queryOffset',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function queryOptionAliases()
    {
        return [
            'id' => 'query-id',
            'status' => 'query-status',
            'where' => 'query-where',
            'limit' => 'query-limit',
            'offset' => 'query-offset',
        ];
    }

    /*******************************************
     * PREPARE
     *******************************************/

    /**
     * @throws Exception
     */
    protected function prepQuery()
    {
        $config = [];

        // Flip the array to grab the alias which matches the query method name
        $alias = array_flip($this->queryOptionAliases());

        // Defaults
        foreach ($this->getOptionValues($this->action->id) as $optionName => $optionValue) {
            // Convert CamelName to id-case
            $optionId  = Inflector::camel2id($optionName);

            // Only handle query params
            if (!array_key_exists($optionId, $alias)) {
                continue;
            }

            if (null !== ($queryMethod = $alias[$optionId] ?? null)) {
                try {
                    $optionValue = $this->prepOptionValue($optionName, $optionValue);
                    $config[$queryMethod] = $optionValue;
                } catch (InvalidArgumentException $e) {
                    // The option is ignored
                }
            }
        }

        QueryHelper::configure(
            $this->getQuery(
                $this->queryConfig()
            ),
            $config
        );
    }

    /**
     * @param $method
     * @param $value
     * @return mixed
     */
    private function prepOptionValue($method, $value)
    {
        // Add a specific prep method to the option
        $prepQueryOption = 'prep' . ucfirst($method);
        if (method_exists($this, $prepQueryOption)) {
            $value = $this->{$prepQueryOption}($value);
        }

        return $value;
    }

    /*******************************************
     * PREPARE SPECIFIC ATTRIBUTES
     *******************************************/

    /**
     * @param $value
     * @return null|array
     */
    protected function prepQueryId($value)
    {
        if (empty($value)) {
            return null;
        }

        return $value;
    }

    /**
     * @param $value
     * @return null|array
     */
    protected function prepQueryStatus($value)
    {
        if (empty($value)) {
            return null;
        }

        return $value;
    }

    /**
     * @param $value
     * @return null|array
     */
    protected function prepQueryWhere($value)
    {
        if (empty($value)) {
            return null;
        }

        return $value;
    }

    /**
     * @param array $config
     * @return ElementQuery|ElementQueryInterface
     * @throws Exception
     */
    protected function getQuery(array $config = []): ElementQueryInterface
    {
        if (null === $this->query) {
            /** @var ElementInterface $elementClass */
            $elementClass = $this->elementType();

            $this->query = $elementClass::find();

            QueryHelper::configure(
                $this->query,
                $config
            );
        }

        return $this->query;
    }
}
