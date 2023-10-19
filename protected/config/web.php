<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$dblog = require __DIR__ . '/dblog.php';
$mailer = require __DIR__ . '/mailer.php';

$config = [
    'id' => 'basic',
    'timeZone' => 'Asia/Jakarta',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['debug'],
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'defaultRoute' => 'site/login',
    'modules' => [
        'masterdata' => [
            'class' => 'app\modules\masterdata\Module',
        ],
		'profile' => [
            'class' => 'app\modules\profile\Module',
        ],
		'verification' => [
            'class' => 'app\modules\verification\Module',
        ],
		'attendance' => [
            'class' => 'app\modules\attendance\Module',
        ],
		'leave' => [
            'class' => 'app\modules\leave\Module',
        ],
		'overtime' => [
            'class' => 'app\modules\overtime\Module',
        ],
		'payroll' => [
            'class' => 'app\modules\payroll\Module',
        ],
		'sk' => [
            'class' => 'app\modules\sk\Module',
        ],
		'project' => [
            'class' => 'app\modules\project\Module',
        ],
        //sahrul
		'report' => [
            'class' => 'app\modules\report\Module',
        ],
        'monitoring' => [
            'class' => 'app\modules\monitoring\Module',
        ],
		'admin' => [
            'class' => 'mdm\admin\Module',
        ],
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['1.2.3.4', '127.0.0.1', '::1']
        ]
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'timeout' => 10800 * 30,
        ],
        'view' => [
            'class' => 'yii\web\View',
            'theme' => [
                'class' => 'yii\base\Theme',
                'pathMap' => [
                    '@app' => [
                        '@app/../themes/cuba'
                    ]
                ],
                'baseUrl' => '@web/themes/cuba',
            ],
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => []
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => []
                ],
            ],
        ],
        'view' => [
            'class' => 'yii\web\View',
            'theme' => [
                'class' => 'yii\base\Theme',
                'pathMap' => [
                    '@app' => [
                        '@app/../themes/cuba'
                    ]
                ],
                'baseUrl' => '@web/themes/cuba',
            ],
        ],
        'session' => [
            // 'timeout' => 10,
            'cookieParams' => [
                'httpOnly' => true,
                // 'secure' => true,
                // 'sameSite' => yii\web\Cookie::SAME_SITE_STRICT,
            // 'lifetime' => 10
            ]
        ],
        'cookies' => [
            'class' => 'yii\web\Cookie',
            'httpOnly' => true,
            // 'secure' => true,
            // 'sameSite' => yii\web\Cookie::SAME_SITE_STRICT
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'A_4EmEHuBqVzjs8eSvvmOzqe3cpUZ82w',
            'csrfCookie' => [
                'httpOnly' => true,
                // 'secure' => true,
                // 'sameSite' => yii\web\Cookie::SAME_SITE_STRICT
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'authTimeout' => 10800
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
		'mailer' => $mailer,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => [],
                    'logFile' => '@runtime/myfile.log',
                ]
            ],
        ],
        'db' => $db,
        'dblog' => $dblog,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            // 'rules' => [
                // [
                    // 'class' => 'app\components\GlobalEncryptDecryptSsl',
                    // 'pattern' => '',
                    // 'suffix' => '',
                    // 'route' => ''
                // ],
            // ],
        ]
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
			'training/evaluasi/index',
            'admin/*',
            'debug/*',
            // 'site/allnotifikasi',
            'site/login',
            // 'site/logout',
            'site/error',
            'gii/*',
            'report/*', 
            'monitoring/*'
        ]
    ],
    'params' => $params,
];

// if (YII_ENV_DEV) {
	// $config['bootstrap'][] = 'debug';
	// $config['modules']['debug'] = [
		// 'class' => 'yii\debug\Module',
		// 'allowedIPs' => ['127.0.0.1', '::1'],
	// ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
             'allowedIPs' => ['*'],
    ];
//}

return $config;
