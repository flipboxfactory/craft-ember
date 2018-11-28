<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\models;

use craft\validators\HandleValidator;
use flipbox\craft\ember\helpers\ModelHelper;

/**
 * @property string|null $handle
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait HandleRulesTrait
{
    /**
     * @var array
     */
    protected $reservedHandleWords = [
        'id',
        'uid',
    ];

    /**
     * @var int
     */
    protected $handleLength = 150;

    /**
     * @inheritdoc
     */
    public function handleRules()
    {
        return [
            [
                [
                    'handle'
                ],
                HandleValidator::class,
                'reservedWords' => $this->reservedHandleWords
            ],
            [
                [
                    'handle'
                ],
                'required'
            ],
            [
                [
                    'handle'
                ],
                'string',
                'max' => $this->handleLength
            ],
            [
                [
                    'handle'
                ],
                'safe',
                'on' => [
                    ModelHelper::SCENARIO_DEFAULT
                ]
            ]
        ];
    }
}
