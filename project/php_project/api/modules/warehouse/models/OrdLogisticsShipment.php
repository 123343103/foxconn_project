<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "ord_logistics_shipment".
 *
 * @property integer $ship_id
 * @property string $orderno
 * @property integer $orderno_item
 * @property integer $pdt_id
 * @property string $pdt_no
 * @property string $qty
 * @property string $freight
 * @property integer $saleorder_id
 * @property integer $saleorder_detailid
 * @property integer $invh_id
 * @property integer $invl_id
 * @property string $update_date
 * @property string $receipt_no
 * @property string $ship_status
 * @property integer $Carr_id
 * @property string $create_at
 * @property integer $create_by
 */
class OrdLogisticsShipment extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ord_logistics_shipment';
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
            [['ship_id'], 'required'],
            [['ship_id', 'orderno_item', 'prt_pkid', 'saleorder_id', 'saleorder_detailid', 'o_whpkid', 'o_whdtid', 'Carr_id'], 'integer'],
            [['qty', 'freight'], 'number'],
            [['update_date', 'create_at'], 'safe'],
            [['orderno', 'receipt_no'], 'string', 'max' => 40],
            [['part_no', 'create_by'], 'string', 'max' => 20],
            [['ship_status'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ship_id' => '物流訂單ID',
            'orderno' => '物流訂單編號',
            'orderno_item' => '物流訂單編號項次',
            'prt_pkid' => '商品ID',
            'part_no' => '商品料號',
            'qty' => '出貨數量',
            'freight' => '物流费用',
            'saleorder_id' => '客戶訂單ID',
            'saleorder_detailid' => '訂單明細ID',
            'o_whpkid' => '出貨單ID',
            'o_whdtid' => '出貨單子表ID',
            'update_date' => '數據更新時間',
            'receipt_no' => '送貨簽收單',
            'ship_status' => '狀態',
            'Carr_id' => '承運商ID',
            'create_at' => '創建時間',
            'create_by' => '創建人',
        ];
    }
//    //关联物流进度子表
//    public function getOrdLogisticLog()
//    {
//        return $this->hasOne(OrdLogisticLog::className(),['ship_id'=>'ship_id']);
//    }
//    //关联订单主表
//    public function getSaleOrderh()
//    {
//        return $this->hasOne(SaleOrderh::className(),['soh_id'=>'saleorder_id']);
//    }
public static function getOrderno($partno,$owhdtid)
{
    return self::find()
        ->select([
            self::tableName().".orderno",
            self::tableName().".ship_id"
        ])
        ->where([
            self::tableName().'.part_no' => $partno
        ])->andWhere([self::tableName().'.o_whdtid'=>$owhdtid])
        ->asArray()
        ->one();
}
}
