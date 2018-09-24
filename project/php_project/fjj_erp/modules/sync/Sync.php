<?php

namespace app\modules\sync;

/**
 * system module definition class
 */
class Sync extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\sync\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \Yii::configure($this,[
            "components"=>[
                "db"=>[
                    'class' => 'yii\db\Connection',
                    'dsn' => 'mysql:host=10.151.18.157;dbname=erp',
                    'username' => 'root',
                    'password' => 'root',
                    'charset' => 'utf8'
                ]
            ]
        ]);
    }
}
