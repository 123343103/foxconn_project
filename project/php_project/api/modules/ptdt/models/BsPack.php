<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_pack".
 *
 * @property string $prt_pkid
 * @property integer $pck_type
 * @property string $pdt_length
 * @property string $pdt_width
 * @property string $pdt_height
 * @property string $pdt_weight
 * @property string $pdt_mater
 * @property string $pdt_qty
 */
class BsPack extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_pack';
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
            [['prt_pkid', 'pck_type'], 'integer'],
            [['pdt_length', 'pdt_width', 'pdt_height', 'pdt_weight', 'net_weight', 'pdt_qty'], 'number'],
            [['pdt_mater'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prt_pkid' => 'Prt Pkid',
            'pck_type' => 'Pck Type',
            'pdt_length' => 'Pdt Length',
            'pdt_width' => 'Pdt Width',
            'pdt_height' => 'Pdt Height',
            'pdt_weight' => 'Pdt Weight',
            'pdt_mater' => 'Pdt Mater',
            'pdt_qty' => 'Pdt Qty',
        ];
    }


//    public function afterSave($insert, $changedAttributes ){
//        $logModel=new LPack();
//        $logModel->setAttributes($this->attributes);
//        $logModel->l_prt_pkid=$this->prt_pkid;
//        $logModel->yn=0;
//        if(!($logModel->validate() && $logModel->save())){
//            throw new \Exception("包装日志信息保存失败");
//        }
//        return true;
//    }


//获取包装类型为销售包装的商品的重量
    public static function getWeightAndVolume($id)
    {
        return self::find()->select(['prt_pkid','pdt_length','pdt_width','pdt_height','pdt_weight','pdt_qty'])->where(['prt_pkid'=>$id,'pck_type'=>2])->one();
    }

}
