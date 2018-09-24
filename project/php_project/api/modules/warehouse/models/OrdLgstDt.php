<?php

namespace app\modules\warehouse\models;

use app\modules\sale\models\SaleOrderh;
use app\modules\sale\models\SaleOrderl;
use Yii;

/**
 * This is the model class for table "ord_lgst_dt".
 *
 * @property string $odlgdt_pkid
 * @property string $ord_lg_id
 * @property string $shp_dt_pkid
 * @property string $sol_id
 * @property string $nums
 *
 * @property OrdLgst $ordLg
 * @property OrdShpDt $shpDtPk
 */
class OrdLgstDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ord_lgst_dt';
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
            [['ord_lg_id', 'o_whdtid', 'ord_dt_id'], 'integer'],
            [['nums'], 'number'],
            [['ord_lg_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrdLgst::className(), 'targetAttribute' => ['ord_lg_id' => 'ord_lg_id']],
            [['o_whdtid'], 'exist', 'skipOnError' => true, 'targetClass' => OWhpdtDt::className(), 'targetAttribute' => ['o_whdtid' => 'o_whdtid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'odlgdt_pkid' => '物流訂單明細PKID',
            'ord_lg_id' => '物流訂單PKID',
            'shp_dt_pkid' => '出貨單明細PKID',
            'ord_dt_id' => '訂單明細PKID',
            'nums' => '物流訂單數量',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdLg()
    {
        return $this->hasOne(OrdLgst::className(), ['ord_lg_id' => 'ord_lg_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getShpDtPk()
//    {
//        return $this->hasOne(OrdShpDt::className(), ['shp_dt_pkid' => 'shp_dt_pkid']);
//    }
//
//    //关联订单子表
//    public function getSaleOrderl(){
//        return $this->hasOne(SaleOrderl::className(), ['sol_id' => 'sol_id']);
//    }
//    //关联订单主表
//    public function getSaleOrderh(){
//        return $this->hasOne(SaleOrderh::className(),['soh_id'=>'soh_id'])->via('saleOrderl');
//    }
////关联出库表
//    public function getOrdShp(){
//        return $this->hasOne(OrdShp::className(),['ord_shp_PKID'=>'ord_shp_PKID']);
//    }
//    //关联仓库表
//    public function getBsWh(){
//        return $this->hasOne(BsWh::className(),['wh_id'=>'wh_id'])->via('ordShp');
//    }
}
