<?php
//aaa
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'gmam',
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
//        'mailer' => [
//            'class' => 'yii\swiftmailer\Mailer',
//            // send all mails to a file by default. You have to set
//            // 'useFileTransport' to false and configure a transport
//            // for the mailer to send real emails.
//            'useFileTransport' => true,
//        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', "info"],
                ],
            ],
        ],
        //'db' => require(__DIR__ . '/db.php'),
        //  默認的ERP數據庫
//        'db' => [
//            'class' => 'yii\db\Connection',
//            'dsn' => 'mysql:host=10.134.100.101;dbname=erp',
//            'username' => 'dep',
//            'password' => 'Foxconn@88',
//            'charset' => 'utf8',
//        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysqld;dbname=erp',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        //        連接pdt數據庫
        'pdt' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.151.18.157;dbname=pdt',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
//        連接wms數據庫
        'wms' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.151.18.157;dbname=wms',
            'username' => 'wms',
            'password' => 'wms',
            'charset' => 'utf8',
        ],
        //        連接spp供應商數據庫，隻有新增、修改、查詢權限
        'spp' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.151.18.157;dbname=spp',
            'username' => 'spp',
            'password' => 'spp',
            'charset' => 'utf8',
        ],

        //        連接oms訂單數據庫，隻有新增、修改、查詢權限
        'oms' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.151.18.157;dbname=oms',
            'username' => 'oms',
            'password' => 'oms',
            'charset' => 'utf8',
        ],

        //        連接prch採購數據庫，隻有新增、修改、查詢權限
        'prch' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.151.18.157;dbname=prch',
            'username' => 'prch',
            'password' => 'prch',
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
//            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
//                '<controller:\w+>/'=>'<controller>/',
//                '<controller:\w+>/<id:\d+>' => '<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'ntag',
                ],
            ],
        ],

    ],
    'modules' => [
        'ptdt' => [
            'class' => 'app\modules\ptdt\ptdt',
        ],
        'crm' => [
            'class' => 'app\modules\crm\Crm',
        ],
        'hr' => [
            'class' => 'app\modules\hr\hr',
        ],
        'system' => [
            'class' => 'app\modules\system\system',
        ],
        'sale' => [
            'class' => 'app\modules\sale\Sale',
        ],
        'purchase' => [
            'class' => 'app\modules\purchase\purchase',
        ],
        'app' => [
            'class' => 'app\modules\app\app',
        ],
        'common' => [
            'class' => 'app\modules\common\Common',
        ],
        'rpt' => [
            'class' => 'app\modules\rpt\rpt',
        ],
        'warehouse' => [
            'class' => 'app\modules\warehouse\warehouse'
        ],
        'spp' => [
            'class' => 'app\modules\spp\Spp',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
