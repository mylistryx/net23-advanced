<?php

use common\components\user\WebUser;
use yii\console\Application as ConsoleApplication;
use yii\db\Connection as DbConnection;
use yii\queue\redis\Queue;
use yii\rbac\DbManager;
use yii\redis\Connection as RedisConnection;
use yii\web\Application as WebApplication;

class Yii
{
    public static ConsoleApplication|__Application|WebApplication $app;
}

/**
 * @property DbConnection $db
 * @property RedisConnection $redis
 * @property DbManager $authManager
 * @property WebUser $user
 * @property Queue $queue
 */
class __Application
{
}
