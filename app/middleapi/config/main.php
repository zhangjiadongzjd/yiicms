<?php
$params = array_merge(
    require(__DIR__ . '/../../../common/config/params.php'),
    require(__DIR__ . '/../../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',
    'controllerNamespace' => 'middleapi\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        "v1" => [        
            'class' => 'middleapi\modules\v1\Module',
        ],
    ],
    'components' => [
        'user' => [
			'identityClass' => 'common\models\UserCopy',
			'enableAutoLogin' => true,
			'enableSession' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'enableStrictParsing' => true,
			'rules' => [
                ['class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/article'],
                    'ruleConfig'=>[
                        'class'=>'yii\web\UrlRule',
                        'defaults'=>[
                            'expand'=>'createdBy',
                        ]
                    ],
                    'extraPatterns'=>[
                        'POST search' => 'search',
                        'POST set-redis' => 'set-redis'
                    ],
                ],
				[
					'class' => 'yii\rest\UrlRule',
					'controller' => ['v1/user-copy'],
					'pluralize'=>false,
                    'extraPatterns' => [
                        'POST login' => 'login',
                    ]
				],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/verify'],
                    'pluralize'=>false,
                    'extraPatterns' => [
                        'POST yzm' => 'yzm',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/verify'],
                    'pluralize'=>false,
                    'extraPatterns' => [
                        'POST captcha' => 'captcha',
                    ]
                ]
			]
		],
    ],
    'params' => $params,
];
