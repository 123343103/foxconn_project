<?php

namespace app\modules\warehouse\models;

use app\modules\common\models\BsCurrency;
use app\modules\warehouse\models\BsWhPrice;
use Yii;

/**
 * This is the model class for table "wh_pricel".
 *
 * @property string $whpl_id
 * @property string $whp_id
 * @property string $whpb_id
 * @property string $whpb_num
 * @property integer $whpb_curr
 * @property string $whpb_remark
 * @property string $whpb_vdef1
 * @property string $whpb_vdef2
 */
class WhPricel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wms.wh_pricel';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['whp_id', 'whpb_id', 'whpb_num', 'whpb_curr'], 'required'],
            [['whp_id', 'whpb_id', 'whpb_curr'], 'integer'],
            [['whpb_num'], 'number'],
            [['whpb_remark', 'whpb_vdef1', 'whpb_vdef2'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'whpl_id' => 'Whpl ID',
            'whp_id' => 'Whp ID',
            'whpb_id' => 'Whpb ID',
            'whpb_num' => 'Whpb Num',
            'whpb_curr' => 'Whpb Curr',
            'whpb_remark' => 'Whpb Remark',
            'whpb_vdef1' => 'Whpb Vdef1',
            'whpb_vdef2' => 'Whpb Vdef2',
        ];
    }

    //关联费用名称表
    public function getBsWhPrice(){
        return $this->hasOne(BsWhPrice::className(),['whpb_id'=>'whpb_id']);
    }
    //关联币别表
    public function getBsCurrency(){
        return $this->hasOne(BsCurrency::className(),['cur_id'=>'whpb_curr']);
    }

}
