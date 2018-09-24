<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_orderh".
 *
 * @property string $soh_id
 * @property integer $comp_id
 * @property string $bus_code
 * @property string $soh_code
 * @property string $soh_date
 * @property string $consignment_date
 * @property string $soh_type
 * @property string $soh_status
 * @property integer $cust_id
 * @property integer $inv_cust_id
 * @property string $invoice_address
 * @property integer $pat_id
 * @property integer $pac_id
 * @property integer $dec_id
 * @property integer $cur_id
 * @property string $bill_oamount
 * @property string $bill_camount
 * @property integer $bill_from
 * @property integer $sell_delegate
 * @property integer $district_id
 * @property integer $sell_manager
 * @property string $consignment_ogan
 * @property string $packtype_desc
 * @property string $contract_no
 * @property string $logistics_type
 * @property string $istock
 * @property string $stock_status
 * @property string $bill_remark
 * @property integer $create_by
 * @property string $cdate
 * @property integer $review_by
 * @property string $rdate
 * @property integer $update_by
 * @property string $udate
 * @property integer $whs_id
 */
class SaleOrderh extends Common
{
    const STATUS_DEL = '0';         //删除
    const STATUS_DEFAULT = '10';    //已下单
    const STATUS_OUT = '20';        //已出货
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_orderh';
    }
    public static function getDb()
    {
        return Yii::$app->get('oms');
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['soh_id', 'pat_id', 'pac_id', 'dec_id', 'cur_id'], 'required'],
            [['soh_id', 'comp_id', 'cust_id', 'inv_cust_id', 'pat_id', 'pac_id', 'dec_id', 'cur_id', 'bill_from', 'district_id', 'sell_manager', 'create_by', 'review_by', 'update_by', 'whs_id'], 'integer'],
            [['soh_date', 'consignment_date', 'cdate', 'rdate', 'udate'], 'safe'],
            [['bill_oamount', 'bill_camount'], 'number'],
            [['bus_code', 'soh_code', 'soh_type', 'contract_no', 'logistics_type', 'stock_status', 'bill_remark','sell_delegate'], 'string', 'max' => 20],
            [['soh_status', 'istock'], 'string', 'max' => 4],
            [['invoice_address', 'consignment_ogan', 'packtype_desc'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'soh_id' => 'Soh ID',
            'comp_id' => '關聯公司表',
            'bus_code' => '關聯業務對象表',
            'soh_code' => '訂單號',
            'soh_date' => '訂單日期',
            'consignment_date' => '交貨日期',
            'soh_type' => '銷售類型 如內購內銷，內購外銷',
            'soh_status' => '訂單狀態',
            'cust_id' => '客戶ID 關聯客戶信息表',
            'inv_cust_id' => '發票法人 開發票單位',
            'invoice_address' => '開票地址',
            'pat_id' => '關聯付款方式表(bs_paymstype)',
            'pac_id' => '關聯付款條件表(bs_paymcond)',
            'dec_id' => '交貨條件 關聯交貨條件表',
            'cur_id' => '交易幣別 關聯幣別表(bs_currency)',
            'bill_oamount' => '訂單總額(原幣)',
            'bill_camount' => '訂單總額(本幣)',
            'bill_from' => '訂單來源 可能關聯公共數據字典表',
            'sell_delegate' => '銷售代表',
            'district_id' => '銷售區域',
            'sell_manager' => '客戶經濟',
            'consignment_ogan' => '發貨組織',
            'packtype_desc' => '包裝方式描述',
            'contract_no' => '合同號',
            'logistics_type' => '物流方式',
            'istock' => '是否採購',
            'stock_status' => '採購狀態 採購訂單反填',
            'bill_remark' => '訂單備註',
            'create_by' => '創建人',
            'cdate' => '創建日期',
            'review_by' => '審核人',
            'rdate' => '審核日期',
            'update_by' => '修改人',
            'udate' => '修改日期',
            'whs_id' => '發貨倉庫',
        ];
    }

    public function getSaleOrderl(){
        return $this->hasMany(SaleOrderl::className(),['soh_id'=>'soh_id']);
    }

    /*销售代表*/
    public function getSaleDelegate(){
        return $this->hasOne(CrmEmployee::className(),['staff_code'=>'sell_delegate']);
    }
    /*
     * 根据订单号在订单主表里面获取订单id以及状态
     */
    public static function getSohId($soh_code)
    {
        return self::find()
            ->select([
                self::tableName().".soh_id",
                self::tableName().".soh_status"
            ])
            ->where([
                self::tableName().'.soh_code' => $soh_code
            ])
            ->asArray()
            ->one();
    }
    //获取客户id
    public static function getCusId($soh_code)
    {
        return self::find()
            ->select([
                self::tableName().".cust_id"
            ])
            ->where([
                self::tableName().'.soh_code' => $soh_code
            ])
            ->asArray()
            ->one();
    }

}
