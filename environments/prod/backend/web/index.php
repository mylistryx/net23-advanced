<?php

use yii\helpers\ArrayHelper;

defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

require dirname(__DIR__, 2) . '/vendor/autoload.php';
require dirname(__DIR__, 2) . '/vendor/yiisoft/yii2/Yii.php';
require dirname(__DIR__, 2) . '/common/config/bootstrap.php';
require dirname(__DIR__) . '/config/bootstrap.php';

$config = ArrayHelper::merge(
    require dirname(__DIR__, 2) . '/common/config/main.php',
    require dirname(__DIR__, 2) . '/common/config/main-local.php',
    require dirname(__DIR__) . '/config/main.php',
    require dirname(__DIR__) . '/config/main-local.php',
);

(new yii\web\Application($config))->run();
