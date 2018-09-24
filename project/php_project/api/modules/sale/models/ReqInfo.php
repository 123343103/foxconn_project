<?php

namespace app\modules\sale\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsCompany;
use app\modules\crm\models\CrmCustomerApply;
use app\modules\crm\models\CrmCustomerInfo;
use yii\db\Expression;

/**
 * This is the model class for table "req_info".
 *
 * @property string $req_id
 * @property string $bus_code
 * @property integer $comp_id
 * @property string $saph_code
 * @property string $contract_no
 * @property integer $saph_type
 * @property string $saph_date
 * @property integer $corporate
 * @property integer $trade_mode
 * @property string $cust_contacts
 * @property string $cust_tel
 * @property string $invoice_title
 * @property integer $invoice_type
 * @property integer $invoice_AreaID
 * @property string $invoice_Address
 * @property integer $invoice_Title_AreaID
 * @property string $invoice_Title_Addr
 * @property string $receipter
 * @property string $receipter_Tel
 * @property string $addr_tel
 * @property integer $receipt_areaid
 * @property string $receipt_Address
 * @property string $saph_description
 * @property string $origin_hid
 * @property string $saph_remark
 * @property string $saph_status
 * @property string $cur_id
 * @property integer $pac_id
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
 * @property integer $nwer
 * @property string $nw_date
 * @property string $nw_ip
 * @property integer $opper
 * @property string $opp_date
 * @property string $opp_id
 * @property string $can_reason
 * @property string $can_date
 * @property string $can_ip
 * @property string $ba_id
 * @property integer $pay_type
 * @property ReqFile[] $reqFiles
 */
class ReqInfo extends Common
{
    const STATUS_CREATE = '10';   //������
    const STATUS_QUOTED = '20';    // ��ת����
    const STATUS_CANCEL = '30';   // ��ȡ��

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.req_info';
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
            [['cust_id', 'saph_type', 'corporate', 'trade_mode', 'invoice_type', 'invoice_AreaID', 'invoice_Title_AreaID', 'receipt_areaid', 'origin_hid', 'cur_id', 'pac_id', 'Is_org', 'nwer', 'opper', 'ba_id', 'pay_type'], 'integer'],
            [['saph_date', 'nw_date', 'opp_date', 'can_date'], 'safe'],
            [['ex_rate', 'org_prf', 'prd_org_amount', 'tax_freight', 'freight', 'req_tax_amount', 'req_amount', 'prd_loc_amount', 'loc_prf'], 'number'],
            [['bus_code', 'saph_code', 'contract_no', 'cust_contacts', 'cust_tel', 'receipter', 'receipter_Tel', 'addr_tel'], 'string', 'max' => 20],
            [['invoice_title', 'invoice_Address', 'invoice_Title_Addr', 'receipt_Address'], 'string', 'max' => 255],
            [['saph_description', 'saph_remark', 'can_reason'], 'string', 'max' => 200],
            [['saph_status'], 'string', 'max' => 2],
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
            'req_id' => 'Req ID',
            'bus_code' => 'Bus Code',
            'comp_id' => 'Comp ID',
            'saph_code' => 'Saph Code',
            'contract_no' => 'Contract No',
            'saph_type' => 'Saph Type',
            'saph_date' => 'Saph Date',
            'corporate' => 'Corporate',
            'trade_mode' => 'Trade Mode',
            'cust_contacts' => 'Cust Contacts',
            'cust_tel' => 'Cust Tel',
            'invoice_title' => 'Invoice Title',
            'invoice_type' => 'Invoice Type',
            'invoice_AreaID' => 'Invoice  Area ID',
            'invoice_Address' => 'Invoice  Address',
            'invoice_Title_AreaID' => 'Invoice  Title  Area ID',
            'invoice_Title_Addr' => 'Invoice  Title  Addr',
            'receipter' => 'Receipter',
            'receipter_Tel' => 'Receipter  Tel',
            'addr_tel' => 'Addr Tel',
            'receipt_areaid' => 'Receipt Areaid',
            'receipt_Address' => 'Receipt  Address',
            'saph_description' => 'Saph Description',
            'origin_hid' => 'Origin Hid',
            'saph_remark' => 'Saph Remark',
            'saph_status' => 'Saph Status',
            'cur_id' => 'Cur ID',
            'pac_id' => 'Pac ID',
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
            'nwer' => 'Nwer',
            'nw_date' => 'Nw Date',
            'nw_ip' => 'Nw Ip',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_id' => 'Opp ID',
            'can_reason' => 'Can Reason',
            'can_date' => 'Can Date',
            'can_ip' => 'Can Ip',
            'ba_id' => 'Ba ID',
            'pay_type' => 'Pay Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReqAddr()
    {
        return $this->hasOne(ReqAddr::className(), ['req_id' => 'req_id']);
    }

    /**
     * �����N�������ӱ�
     */
    public function getReqDts()
    {
        return $this->hasMany(ReqDt::className(), ['req_id' => 'req_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReqFiles()
    {
        return $this->hasMany(ReqFile::className(), ['req_id' => 'req_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReqPay()
    {
        return $this->hasOne(ReqPay::className(), ['req_id' => 'req_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReqRcvAddr()
    {
        return $this->hasOne(ReqRcvAddr::className(), ['req_id' => 'req_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReqSale()
    {
        return $this->hasOne(ReqSale::className(), ['req_id' => 'req_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReqValue()
    {
        return $this->hasOne(ReqValue::className(), ['req_id' => 'req_id']);
    }

    /**
     * �����ͻ�
     */
    public function getCustomerInfo()
    {
        return $this->hasOne(CrmCustomerInfo::className(), ['cust_id' => 'cust_id']);
    }

    /**
     * ������������
     */
    public function getOrderType()
    {
        return $this->hasOne(BsBusinessType::className(), ['business_type_id' => 'saph_type']);
    }

    /**
     * ����������
     */
    public function getCreatorStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'create_by']);
    }

    /**
     * �����޸���
     */
    public function getUpdateStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'update_by']);
    }

    /**
     * �����ͻ����������
     */
    public function getCustomerApply()
    {
        return $this->hasOne(CrmCustomerApply::className(), ['cust_id' => 'cust_id']);
    }

    /**
     * �������׷���
     */
    public function getCorporateCompany()
    {
        return $this->hasOne(BsCompany::className(), ['company_id' => 'corporate']);
    }

    /**
     * �����ͻ������˱�
     */
    public function getCustomerManager()
    {
        return $this->hasOne(CrmCustPersoninch::className(), ['cust_id' => 'cust_id']);
    }

    public function getCustomerManagerName()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'ccpich_personid'])->via('customerManager');
    }

    /**
     * ������ַ����Ʊ̧ͷ��ַ����Ʊ���͵�ַ���ջ���ַ��
     */
//    public function getAddress()
//    {
//        return $this->hasOne(BsAddress::className(),['cust_id'=>'cust_id']);
//    }
    public function behaviors()
    {
        return [
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                'codeField' => 'saph_code',
                "formName" => 'req_info',
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
