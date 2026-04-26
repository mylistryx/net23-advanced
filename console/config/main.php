<?php

use yii\console\controllers\FixtureController;
use yii\console\controllers\MigrateController;
use yii\helpers\ArrayHelper;

$params = ArrayHelper::merge(
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php',
);

return [
    'id'                  => 'app-console',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'controllerMap'       => [
        'fixture' => [
            'class'     => FixtureController::class,
            'namespace' => 'common\fixtures',
        ],
        'migrate' => [
            'class'        => MigrateController::class,
            'templateFile' => '@common/components/migrations/views/migration.php',
        ],
    ],
    'components'          => [],
    'params'              => $params,
];
