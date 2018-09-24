<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_warr".
 *
 * @property string $wrr_id
 * @property string $prt_pkid
 * @property integer $item
 * @property string $wrr_prd
 * @property integer $wrr_fee
 * @property integer $cry
 */
class BsWarr extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_warr';
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
            [['prt_pkid'], 'required'],
            [['item', 'wrr_prd', 'cry'], 'integer'],
            [['wrr_fee'], 'number'],
            [['prt_pkid'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wrr_id' => 'Wrr ID',
            'prt_pkid' => 'Prt Pkid',
            'item' => 'Item',
            'wrr_prd' => 'Wrr Prd',
            'wrr_fee' => 'Wrr Fee',
            'cry' => 'Cry',
        ];
    }


//    public function afterSave($insert, $changedAttributes ){
//        $logModel=new LWarr();
//        $logModel->setAttributes($this->attributes);
//        $logModel->l_prt_pkid=$this->prt_pkid;
//        $logModel->yn=0;
//        if(!($logModel->validate() && $logModel->save())){
//            throw new \Exception("包装日志信息保存失败");
//        }
//    }
}
