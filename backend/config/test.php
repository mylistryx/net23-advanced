<?php

return [
    'id'         => 'app-backend-tests',
    'components' => [
        'assetManager' => [
            'basePath' => dirname(__DIR__) . '/web/assets',
        ],
        'urlManager'   => [
            'showScriptName' => true,
        ],
        'request'      => [
            'cookieValidationKey' => 'test',
        ],
    ],
];
