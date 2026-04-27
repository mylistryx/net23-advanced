<?php

namespace comomon\components\controllers;

use common\components\controllers\WebController;
use yii\rest\Controller;
use yii\web\Response;

abstract class ApiController extends WebController
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => Response::FORMAT_JSON,
        ];
        return $behaviors;
    }
}