<?php

use common\components\user\WebUser;
use common\models\Identity;

return [
    'id'         => 'app-common-tests',
    'basePath'   => dirname(__DIR__),
    'components' => [
        'user' => [
            'class'         => WebUser::class,
            'identityClass' => Identity::class,
        ],
        'urlManager' => [
            'enablePrettyUrl' => false,
        ]
    ],
];
