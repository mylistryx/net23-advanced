<?php

use yii\db\Connection as DbConnection;
use yii\helpers\ArrayHelper;
use yii\log\FileTarget;
use yii\queue\redis\Queue;
use yii\redis\Cache;
use yii\redis\Connection as RedisConnection;
use yii\symfonymailer\Mailer;

$params = ArrayHelper::merge(
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php',
);

return [
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'bootstrap'           => ['log'],
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'components' => [
        'cache'  => [
            'class' => Cache::class,
        ],
        'db'     => [
            'class'    => DbConnection::class,
            'dsn'      => 'mysql:host=mysql;dbname=yii2advanced;port=3306',
            'username' => 'root',
            'password' => 'secret_root_password',
            'charset'  => 'utf8mb4',
        ],
        'redis'  => [
            'class'    => RedisConnection::class,
            'hostname' => 'redis',
            'port'     => 6379,
        ],
        'queue'  => [
            'class'   => Queue::class,
            'redis'   => 'redis',
            'channel' => 'queue',
        ],
        'mailer' => [
            'class'            => Mailer::class,
            'viewPath'         => '@common/mail',
            'useFileTransport' => true,
        ],
        'log' => [
            'targets' => [
                [
                    'class'  => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params'     => $params,
];
