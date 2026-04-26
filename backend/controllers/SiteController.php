<?php

namespace backend\controllers;

use common\components\actions\LoginAction;
use common\components\actions\LogoutAction;
use common\components\actions\RenderAction;
use common\components\controllers\WebController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ErrorAction;

final class SiteController extends WebController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow'   => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions(): array
    {
        return [
            'error'  => ErrorAction::class,
            'login'  => LoginAction::class,
            'logout' => LogoutAction::class,
            'index'  => RenderAction::class,
        ];
    }
}
