<?php

namespace app\modules\sale\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsPayment;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsTransaction;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%price_info}}".
 *
 * @property string $price_id
 * @property string $price_no
 * @property string $saph_code
 * @property string $price_date
 * @property string $origin_hid
 * @property integer $price_type
 * @property string $contract_no
 * @property integer $corporate
 * @property integer $trade_mode
 * @property string $invoice_title
 * @property integer $invoice_type
 * @property integer $distinct_id
 * @property string $invoice_Address
 * @property integer $invoice_Title_AreaID
 * @property string $invoice_Title_Addr
 * @property string $receipter
 * @property string $receipter_Tel
 * @property string $addr_tel
 * @property integer $receipt_areaid
 * @property string $receipt_Address
 * @property string $cust_code
 * @property string $cust_contacts
 * @property string $cust_tel2
 * @property string $cust_addr
 * @property string $cust_tel1
 * @property string $cur_id
 * @property integer $pac_id
 * @property integer $pay_type
 * @property integer $Is_org
 * @property string $ex_rate
 * @property string $org_prf
 * @property string $prd_org_amount
 * @property string $tax_freight
 * @property string $freight
 * @property string $req_tax_amount
 * @property string $req_amount
 * @property string $prd_loc_amount
 * @property string $loc_prf
 * @property string $remark
 * @property integer $audit_id
 * @property integer $nwer
 * @property string $nw_date
 * @property string $nw_ip
 * @property integer $opper
 * @property string $opp_date
 * @property string $opp_id
 * @property string $can_reason
 * @property integer $caner
 * @property string $can_date
 * @property string $can_ip
 */
class PriceInfo extends Common
{
    const STATUS_DEL = 0;   //删除
    const STATUS_DEFAULT = 10;   //待提交
    const STATUS_UNDER_REVIEW = 11;   //审核中
    const STATUS_REVIEW_OVER = 12;   //审核完成
    const STATUS_REVIEW_REJECT = 13;   //驳回
    const STATUS_CANCLE_OFFER = 14;   //取消报价
    const STATUS_WAIT_ORRER = 15;   //待报价
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.price_info';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
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
            [['price_date', 'nw_date', 'opp_date', 'can_date'], 'safe'],
            [['origin_hid', 'price_type', 'corporate', 'trade_mode', 'invoice_type', 'distinct_id', 'invoice_Title_AreaID', 'receipt_areaid', 'cur_id', 'pac_id', 'pay_type', 'Is_org', 'audit_id', 'nwer', 'opper', 'caner'], 'integer'],
            [['ex_rate', 'org_prf', 'prd_org_amount', 'tax_freight', 'freight', 'req_tax_amount', 'req_amount', 'prd_loc_amount', 'loc_prf'], 'number'],
            [['price_no', 'saph_code'], 'string', 'max' => 30],
            [['contract_no', 'receipter', 'receipter_Tel', 'addr_tel', 'cust_code', 'cust_contacts', 'cust_tel2', 'cust_tel1'], 'string', 'max' => 20],
            [['invoice_title', 'invoice_Address', 'invoice_Title_Addr', 'receipt_Address'], 'string', 'max' => 255],
            [['cust_addr', 'remark', 'can_reason'], 'string', 'max' => 200],
            [['nw_ip', 'opp_id'], 'string', 'max' => 15],
            [['can_ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'price_id' => '報價單pkid',
            'price_no' => '報價單號',
            'saph_code' => '需求單號,oms.req_info',
            'price_date' => '報價單下單時間',
            'origin_hid' => '訂單來源，erp.pubdata',
            'price_type' => '報價單類型,erp.bs_business_type,交易商城訂單',
            'contract_no' => '合同號',
            'corporate' => '交易法人,erp.bs_company. company_status=10',
            'trade_mode' => '交易模式,erp.bs_transaction ,內購內銷',
            'invoice_title' => '發票抬頭',
            'invoice_type' => '發票類型',
            'distinct_id' => '發票寄送地址區ID',
            'invoice_Address' => '發票寄送地址',
            'invoice_Title_AreaID' => '發票寄送地址區ID',
            'invoice_Title_Addr' => '發票抬頭地址',
            'receipter' => '收貨人',
            'receipter_Tel' => '聯繫電話',
            'addr_tel' => '固定電話',
            'receipt_areaid' => '收貨區ID',
            'receipt_Address' => '收貨詳細地址',
            'cust_code' => '客戶代碼',
            'cust_contacts' => '聯系人',
            'cust_tel2' => '聯系電話(手機)',
            'cust_addr' => '客戶地址',
            'cust_tel1' => '公司電話',
            'cur_id' => '幣別',
            'pac_id' => '支付方式:信用額度、預付款。erp. bs_payment',
            'pay_type' => '支付類型:0:全額；1：分期',
            'Is_org' => '是否原幣交易:1：是',
            'ex_rate' => '匯率:人民幣交易,匯率為1。本幣=原幣*匯率',
            'org_prf' => '原幣優惠，等於需求單明細各優惠之和',
            'prd_org_amount' => '商品原幣總金額:等於需求單明細各商品原幣總金額之和',
            'tax_freight' => '含稅運費:等於需求單明細含稅運費之和',
            'freight' => '未稅運費:等於需求單明細未稅運費之和',
            'req_tax_amount' => '訂單含稅總金額:商品原幣總金額+含稅運費',
            'req_amount' => '訂單未稅總金額',
            'prd_loc_amount' => '商品本幣總金額:等於需求單明細各商品本幣總金額之和',
            'loc_prf' => '本幣優惠，默認為0',
            'remark' => '備註',
            'audit_id' => '審核狀態，erp.audit_state',
            'nwer' => '創建人',
            'nw_date' => '創建日期',
            'nw_ip' => '創建人IP',
            'opper' => '修改人',
            'opp_date' => '修改日期',
            'opp_id' => '修改人IP',
            'can_reason' => '取消原因',
            'caner' => '取消人, erp.user',
            'can_date' => '取消時間',
            'can_ip' => '取消IP',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     * 客户信息
     * 表不用
     */
    public function getPriceCust()
    {
        return $this->hasOne(CrmCustomerInfo::className(), ['cust_code' => 'cust_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceDts()
    {
        return $this->hasMany(PriceDt::className(), ['price_id' => 'price_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceFiles()
    {
        return $this->hasMany(PriceFile::className(), ['price_id' => 'price_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * 付款方式(全部)
     */
    public function getPricePays()
    {
        return $this->hasMany(PricePay::className(), ['price_id' => 'price_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     * 付款方式(单条)
     *
     */
    public function getPricePay()
    {
        return $this->hasOne(PricePay::className(), ['price_id' => 'price_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     * 销售人员
     */
    public function getHrStaff(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'nwer']);
    }


    /**
     * @return \yii\db\ActiveQuery
     * 交易币别
     */
    public function getCurrency(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'cur_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * 支付方式
     */
    public function getPayment(){
        return $this->hasOne(BsPayment::className(),['pac_id'=>'pac_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * 支付类型
     */
    public function getPayType(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pay_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * 订单来源
     */
    public function getOrigin(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'origin_hid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * 订单类型
     */
    public function getBusinessType(){
        return $this->hasOne(BsBusinessType::className(),['business_type_id'=>'price_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * 交易法人
     */
    public function getCompany(){
        return $this->hasOne(BsCompany::className(),['company_id'=>'corporate']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * 交易模式
     */
    public function getTradeMode(){
        return $this->hasOne(BsTransaction::className(),['tac_id' => 'trade_mode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * 发票类型
     */
    public function getInvoiceType(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'invoice_type']);
    }

    public function behaviors()
    {
        return [
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                "codeField" => 'price_no',
                "formName" => 'price_info',
                "model" => $this,
            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['nw_date'],            //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['opp_date']            //更新
                ]
            ],
        ];
    }

}
