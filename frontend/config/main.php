<?php

use common\components\user\WebUser;
use yii\helpers\ArrayHelper;

$params = ArrayHelper::merge(
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php',
);

return [
    'id'                  => 'app-frontend',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'modules'             => [],
    'components'          => [
        'request'      => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user'         => [
            'class'           => WebUser::class,
            'identityCookie'  => [
                'name'     => '_identity-frontend',
                'httpOnly' => true,
            ],
        ],
        'session'      => [
            'name' => 'advanced-frontend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [],
        ],
    ],
    'params'              => $params,
];
