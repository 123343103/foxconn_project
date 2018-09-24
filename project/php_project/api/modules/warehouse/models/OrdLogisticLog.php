<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use app\modules\sale\models\SaleOrderh;
use Yii;

/**
 * This is the model class for table "ord_logistic_log".
 *
 * @property integer $ship_id
 * @property integer $ship_iid
 * @property string $orderno
 * @property integer $itemno
 * @property string $FORWARDCODE
 * @property string $EXPRESSNO
 * @property string $STATION
 * @property string $ONWAYSTATUS
 * @property string $TRANSACTIONID
 * @property string $FLAG
 * @property string $ERRORMEMO
 * @property string $RCVDATE
 * @property string $STATUSCODE
 * @property string $ONWAYSTATUS_DATE
 * @property string $DELIVERY_MAN
 * @property string $FILE_NAME
 * @property string $REMARK
 * @property string $CUSTOMERID
 * @property string $CARRIERNO
 * @property string $TMS_ORD_CODE
 * @property string $TMS_ORDER_CODE
 * @property string $ACK_FLAG
 * @property string $CUSTOMER_SHOP
 * @property string $CREATE_BY
 * @property string $CREATEDATE
 * @property integer $DETAIL_ID
 * @property string $UPDATE_DATE
 * @property string $SOURCEFROM
 * @property string $TRANS_MODE
 * @property string $bdm_code
 */
class OrdLogisticLog extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ord_logistic_log';
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
            [['ship_id', 'ship_iid', 'itemno', 'DETAIL_ID'], 'integer'],
            [['RCVDATE', 'CREATEDATE', 'UPDATE_DATE'], 'safe'],
            [['orderno', 'FORWARDCODE', 'STATUSCODE', 'DELIVERY_MAN', 'CUSTOMERID', 'CARRIERNO', 'TMS_ORD_CODE', 'TMS_ORDER_CODE', 'CUSTOMER_SHOP', 'CREATE_BY', 'TRANS_MODE', 'bdm_code'], 'string', 'max' => 40],
            [['EXPRESSNO', 'TRANSACTIONID', 'ONWAYSTATUS_DATE'], 'string', 'max' => 60],
            [['STATION', 'ONWAYSTATUS', 'ERRORMEMO', 'FILE_NAME', 'REMARK'], 'string', 'max' => 400],
            [['FLAG', 'ACK_FLAG', 'SOURCEFROM'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ship_id' => '物流訂單ID,關聯物流訂單主表ID',
            'ship_iid' => '配送表ID，本表ID',
            'orderno' => '物流訂單號',
            'itemno' => '項次',
            'FORWARDCODE' => '承運商代碼',
            'EXPRESSNO' => '快遞單號',
            'STATION' => '站點',
            'ONWAYSTATUS' => '在途狀態',
            'TRANSACTIONID' => '批次號',
            'FLAG' => '標誌',
            'ERRORMEMO' => '錯誤信息',
            'RCVDATE' => 'RCVDATE',
            'STATUSCODE' => '配送狀態代碼',
            'ONWAYSTATUS_DATE' => '狀態發生時間-到某站點時間',
            'DELIVERY_MAN' => '操作人員或快遞人員',
            'FILE_NAME' => '文件名稱',
            'REMARK' => '在途狀態詳細信息描述',
            'CUSTOMERID' => '客戶編號',
            'CARRIERNO' => '配送車編號',
            'TMS_ORD_CODE' => 'TMS合作承運商代碼',
            'TMS_ORDER_CODE' => 'TMS合作承運單號',
            'ACK_FLAG' => 'Ack  Flag',
            'CUSTOMER_SHOP' => '渠道代碼',
            'CREATE_BY' => '資料創建人',
            'CREATEDATE' => 'CREATEDATE',
            'DETAIL_ID' => '訂單明細ID',
            'UPDATE_DATE' => '數據更新時間',
            'SOURCEFROM' => '資料來源',
            'TRANS_MODE' => '運輸方式，關聯運輸方式表',
            'bdm_code' => '配送方式，關聯配送方式表',
        ];
    }
    //关联主表
    public function getOrdLogisticsShipment()
    {
        return $this->hasOne(OrdLogisticsShipment::className(),['ship_id'=>'ship_id']);
    }
    //关联订单主表
    public function getSaleOrderh()
    {
        return $this->hasOne(SaleOrderh::className(),['soh_id'=>'saleorder_id'])->via('ordLogisticsShipment');
    }
//获取一条物流进度信息
    public static function getLogInfoOne($id)
    {
        return self::find()->where(['ship_iid' => $id])->one();
    }
}
