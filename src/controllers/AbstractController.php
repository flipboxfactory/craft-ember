<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\controllers;

use Craft;
use craft\helpers\ArrayHelper;
use craft\web\Controller;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\JsonParser;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
abstract class AbstractController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        // Add json parser (to accept json encoded body)
        $parsers = Craft::$app->getRequest()->parsers;
        if (!array_key_exists('application/json', $parsers)) {
            // Make sure the body wasn't already retrieved (incorrectly)
            /** @noinspection PhpUnhandledExceptionInspection */
            $bodyParams = Craft::$app->getRequest()->getBodyParams();
            if ($bodyParams !== null && empty($bodyParams)) {
                Craft::$app->getRequest()->setBodyParams(null);
            }

            Craft::$app->getRequest()->parsers = array_merge(
                $parsers,
                [
                    'application/json' => JsonParser::class
                ]
            );
        }
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'verbFilter' => [
                    'class' => VerbFilter::class,
                    'actions' => $this->verbs(),
                ],
                'contentNegotiator' => [
                    'class' => ContentNegotiator::class,
                    'formats' => [
                        'application/json' => Response::FORMAT_JSON,
                        'application/xml' => Response::FORMAT_XML,
                        'text/html' => Response::FORMAT_RAW
                    ]
                ]
            ]
        );
    }

    /**
     * @return array
     */
    protected function verbs(): array
    {
        return [];
    }
}
