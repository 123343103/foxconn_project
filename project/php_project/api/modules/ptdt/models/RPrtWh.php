<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "r_prt_wh".
 *
 * @property string $prt_pkid
 * @property string $wh_id
 */
class RPrtWh extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_prt_wh';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('pdt');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prt_pkid', 'wh_id'], 'required'],
            [['prt_pkid', 'wh_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prt_pkid' => 'Prt Pkid',
            'wh_id' => 'Wh ID',
        ];
    }

//    public function afterSave($insert, $changedAttributes ){
//        $logModel=new LPrtWh();
//        $logModel->setAttributes($this->attributes);
//        $logModel->l_prt_pkid=$this->prt_pkid;
//        if(!($logModel->validate() && $logModel->save())){
//            throw new \Exception("自提仓库日志信息保存失败");
//        }
//    }
}
