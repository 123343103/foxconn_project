<?php

namespace app\modules\sale\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsTransaction;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
/**
 * This is the model class for table "ord_info".
 *
 * @property string $ord_id
 * @property integer $os_id
 * @property string $ord_no
 * @property string $price_id
 * @property string $ord_date
 * @property string $origin_hid
 * @property integer $ord_type
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
 *
 * @property OrdDt[] $ordDts
 * @property OrdFile[] $ordFiles
 * @property OrdStatus $os
 */
class OrdInfo extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.ord_info';
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
            [['os_id', 'price_id', 'origin_hid', 'ord_type', 'corporate', 'trade_mode', 'invoice_type', 'distinct_id', 'invoice_Title_AreaID', 'receipt_areaid', 'cur_id', 'pac_id', 'pay_type', 'Is_org', 'audit_id', 'nwer', 'opper', 'caner'], 'integer'],
            [['ord_date', 'nw_date', 'opp_date', 'can_date'], 'safe'],
            [['ex_rate', 'org_prf', 'prd_org_amount', 'tax_freight', 'freight', 'req_tax_amount', 'req_amount', 'prd_loc_amount', 'loc_prf'], 'number'],
            [['ord_no', 'contract_no', 'receipter', 'receipter_Tel', 'addr_tel', 'cust_code', 'cust_contacts', 'cust_tel2', 'cust_tel1'], 'string', 'max' => 20],
            [['invoice_title', 'invoice_Address', 'invoice_Title_Addr', 'receipt_Address'], 'string', 'max' => 255],
            [['cust_addr', 'remark', 'can_reason'], 'string', 'max' => 200],
            [['nw_ip', 'opp_id'], 'string', 'max' => 15],
            [['can_ip'], 'string', 'max' => 16],
            [['os_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrdStatus::className(), 'targetAttribute' => ['os_id' => 'os_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ord_id' => 'Ord ID',
            'os_id' => 'Os ID',
            'ord_no' => 'Ord No',
            'price_id' => 'Price ID',
            'ord_date' => 'Ord Date',
            'origin_hid' => 'Origin Hid',
            'ord_type' => 'Ord Type',
            'contract_no' => 'Contract No',
            'corporate' => 'Corporate',
            'trade_mode' => 'Trade Mode',
            'invoice_title' => 'Invoice Title',
            'invoice_type' => 'Invoice Type',
            'distinct_id' => 'Distinct ID',
            'invoice_Address' => 'Invoice  Address',
            'invoice_Title_AreaID' => 'Invoice  Title  Area ID',
            'invoice_Title_Addr' => 'Invoice  Title  Addr',
            'receipter' => 'Receipter',
            'receipter_Tel' => 'Receipter  Tel',
            'addr_tel' => 'Addr Tel',
            'receipt_areaid' => 'Receipt Areaid',
            'receipt_Address' => 'Receipt  Address',
            'cust_code' => 'Cust Code',
            'cust_contacts' => 'Cust Contacts',
            'cust_tel2' => 'Cust Tel2',
            'cust_addr' => 'Cust Addr',
            'cust_tel1' => 'Cust Tel1',
            'cur_id' => 'Cur ID',
            'pac_id' => 'Pac ID',
            'pay_type' => 'Pay Type',
            'Is_org' => 'Is Org',
            'ex_rate' => 'Ex Rate',
            'org_prf' => 'Org Prf',
            'prd_org_amount' => 'Prd Org Amount',
            'tax_freight' => 'Tax Freight',
            'freight' => 'Freight',
            'req_tax_amount' => 'Req Tax Amount',
            'req_amount' => 'Req Amount',
            'prd_loc_amount' => 'Prd Loc Amount',
            'loc_prf' => 'Loc Prf',
            'remark' => 'Remark',
            'audit_id' => 'Audit ID',
            'nwer' => 'Nwer',
            'nw_date' => 'Nw Date',
            'nw_ip' => 'Nw Ip',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_id' => 'Opp ID',
            'can_reason' => 'Can Reason',
            'caner' => 'Caner',
            'can_date' => 'Can Date',
            'can_ip' => 'Can Ip',
        ];
    }
    public function getCust()
    {
        return $this->hasOne(CrmCustomerInfo::className(), ['cust_code' => 'cust_code']);
    }
    public function getTradeMode()
    {
        return $this->hasOne(BsTransaction::className(), ['tac_id' => 'trade_mode']);
    }

    public function getCorporateCompany()
    {
        return $this->hasOne(BsCompany::className(), ['company_id' => 'corporate']);
    }

    public function getOrdType()
    {
        return $this->hasOne(BsBusinessType::className(), ['business_type_id' => 'ord_type']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdDts()
    {
        return $this->hasMany(OrdDt::className(), ['ord_id' => 'ord_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdFiles()
    {
        return $this->hasMany(OrdFile::className(), ['ord_id' => 'ord_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOs()
    {
        return $this->hasOne(OrdStatus::className(), ['os_id' => 'os_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdPays()
    {
        return $this->hasMany(OrdPay::className(), ['ord_id' => 'ord_id']);
    }

    public function getCurrency()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'cur_id']);
    }

    public function getInvoiceType()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'invoice_type']);
    }

    public function getOrdStatus(){
        return $this->hasOne(OrdStatus::className(),['os_id' => 'os_id'])->where(['yn'=>'1']);
    }

    public function getHrStaff(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'nwer']);
    }

    public function behaviors()
    {
        return [
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                'codeField' => 'ord_no',
                "formName" => 'ord_info',
                "model" => $this
            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //�r�g�ֶ��Ԅ��xֵ
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['nw_date'],            //����
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['opp_date']            //����
                ]
            ],
        ];
    }
}
