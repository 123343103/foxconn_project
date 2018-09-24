<?php
$params = require(__DIR__ . '/params.php');


$ftpPath=require(__DIR__ . '/FtpPath.php');
$config = [
    'language' =>"zh-CN",
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'index',
    'controllerMap'=>[
        'ueditor'=>[
            'class'=>'\app\widgets\ueditor\UeditorController'
        ]
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'rbac' => [
            'class' => 'vendor\rbac\NewManager',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'erp',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
//        'session' => [
//            'class' => 'yii\redis\Session',
//        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
        ],
        'errorHandler' => [
            'errorAction' => '/base/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.163.com',  //每种邮箱的host配置不一样
                'username' => '',
                'password' => '',
                'port' => '25',
                'encryption' => 'tls',

            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info'],
                ],
            ],
        ],
        //'db' => require(__DIR__ . '/db.php'),
        //  默認的ERP數據庫
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysqld;dbname=erp',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
//        連接pdt商品數據庫，隻有新增、修改、查詢權限

    ],
    'modules' => [
        'ptdt' => [
            'class' => 'app\modules\ptdt\Ptdt',
        ],
        'crm' => [
            'class' => 'app\modules\crm\Crm',
        ],
        'hr' => [
            'class' => 'app\modules\hr\hr',
        ],
        'system' => [
            'class' => 'app\modules\system\System',
        ],
        'supplier' => [
            'class' => 'app\modules\supplier\Supplier',
        ],
        'sale' => [
            'class' => 'app\modules\sale\Sale',
        ],
        'purchase' => [
            'class' => 'app\modules\purchase\purchase',
        ],
        'common' => [
            'class' => 'app\modules\common\Common',
        ],
        'rpt' => [
            'class' => 'app\modules\rpt\rpt',
        ],
        'sync' => [
            'class' => 'app\modules\sync\Sync'
        ],
        'warehouse'=>[
            'class' => 'app\modules\warehouse\warehouse'
        ],
        'spp' => [
            'class' => 'app\modules\spp\Spp',
        ],
        'test'=>[
            'class'=>'app\modules\test\test',
        ],
    ],
//    'test'=>[
//        'class'=>'app\test\test',
//    ],
    'params' => $params,
    'ftpPath'=>$ftpPath  //客户代码申请认证证书上传路径
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = [
//        'class' => 'yii\debug\Module',
//    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
