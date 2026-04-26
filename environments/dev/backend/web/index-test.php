<?php

use yii\helpers\ArrayHelper;
use yii\web\Application;

if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require dirname(__DIR__, 2) . '/vendor/autoload.php';
require dirname(__DIR__, 2) . '/vendor/yiisoft/yii2/Yii.php';
require dirname(__DIR__, 2) . '/common/config/bootstrap.php';
require dirname(__DIR__) . '/config/bootstrap.php';

$config = ArrayHelper::merge(
    require dirname(__DIR__, 2) . '/common/config/main.php',
    require dirname(__DIR__, 2) . '/common/config/main-local.php',
    require dirname(__DIR__, 2) . '/common/config/test.php',
    require dirname(__DIR__, 2) . '/common/config/test-local.php',
    require dirname(__DIR__) . '/config/main.php',
    require dirname(__DIR__) . '/config/main-local.php',
    require dirname(__DIR__) . '/config/test.php',
    require dirname(__DIR__) . '/config/test-local.php',
);

new Application($config,)->run();
