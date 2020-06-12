<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'CGPtMbvc40Sbs-Jh_nKtsPoqa4hdE0Eg',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.33.*']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.33.*'],
//        'generators' => [
//            'curd' => [
//                'class' => 'yii\gii\generators\curd\Generator',
//                'templates' => [
//                    'layuiCrud' => '@common/gii/crud'
//                ]
//            ]
//        ]
    ];
}

return $config;
