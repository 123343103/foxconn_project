<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests/codeception');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.151.18.157;dbname=erp',
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
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
