<?php

namespace app\modules\crm\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsSettlement;
use app\modules\crm\models\show\CrmCustCustomerShow;
use app\modules\common\models\BsCompany;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "crm_credit_apply".
 *
 * @property integer $aid
 * @property integer $cust_id
 * @property string $credit_code
 * @property string $total_amount
 * @property string $accessory1
 * @property string $accessory2
 * @property string $apply_remark
 * @property string $credit_date
 * @property string $credit_people
 * @property string $credit_status
 * @property integer $company_id
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 * @property string $standby1
 * @property string $standby2
 * @property string $standby3
 */
class CrmCreditApply extends Common
{
    public $codeType;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_credit_apply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['l_credit_id', 'cust_id', 'credit_people', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['volume_trade', 'total_amount', 'credit_amount', 'allow_amount', 'used_limit', 'surplus_limit', 'grand_total_limit'], 'number'],
            [['credit_date', 'create_at', 'update_at'], 'safe'],
            [['credit_code', 'currency', 'credit_type', 'payment_type', 'payment_clause', 'payment_clause_day', 'payment_method', 'initial_day', 'pay_day', 'standby1', 'standby2', 'standby3'], 'string', 'max' => 20],
            [['file_new','file_old'], 'string', 'max' => 100],
            [['apply_remark'], 'string', 'max' => 255],
            [['credit_status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'credit_id' => 'ID',
            'l_credit_id' => '账信申请表ID',
            'cust_id' => '关联客户ID',
            'credit_code' => '申请单号',
            'currency' => '币别',
            'credit_type' => '信用额度类型',
            'volume_trade' => '预估月交易额',
            'total_amount' => '申请总额度',
            'credit_amount' => '批复总额度',
            'allow_amount' => '可用额度',
            'used_limit' => '已使用额度',
            'surplus_limit' => '剩余额度',
            'grand_total_limit' => '累计使用额度',
            'payment_type' => '付款条件',
            'payment_clause' => '付款条件(天)',
            'payment_clause_day' => '付款条件(日)',
            'payment_method' => '付款方式',
            'initial_day' => '起算日',
            'pay_day' => '付款日',
            'apply_remark' => '备注',
            'credit_date' => '申请日期',
            'credit_people' => '申请人',
            'credit_status' => '状态',
            'file_old' => '附件原名称',
            'file_new' => '附件新名称',
            'company_id' => '所属厂商ID',
            'create_by' => '创建人',
            'create_at' => '创建时间',
            'update_by' => '更新人',
            'update_at' => '更新时间',
            'standby1' => '备用字段1',
            'standby2' => '备用字段2',
            'standby3' => '备用字段3',
        ];
    }
    /*关联客户信息*/
    public function getCustomer(){
        return $this->hasOne(CrmCustomerInfo::className(),['cust_id'=>'cust_id']);
    }
    /*关联客户信息*/
    public function getCustPerson(){
        return $this->hasMany(CrmCustomerPersion::className(),['cust_id'=>'cust_id']);
    }
    /*营业额*/
    public function getTurnover(){
        return $this->hasMany(CrmTurnover::className(),['cust_id'=>'cust_id'])->groupBy(['year','currency']);
    }
    /*子公司*/
    public function getLinkComp(){
        return $this->hasMany(CrmCustLinkcomp::className(),['cust_id'=>'cust_id']);
    }
    /*币别*/
    public function getCurrencyType(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'currency']);
    }
    /*主要客户*/
    public function getCustCustomer(){
        return $this->hasMany(CrmCustCustomer::className(),['cust_id'=>'cust_id'])->where(['=','cust_type',CrmCustCustomer::TYPE_CUSTOMER]);
    }
    /*主要供应商*/
    public function getSupplier(){
        return $this->hasMany(CrmCustCustomerShow::className(),['cust_id'=>'cust_id'])->where(['=','cust_type',CrmCustCustomer::TYPE_SUPPLIER]);
    }
    /*主要往来银行*/
    public function getBank(){
        return $this->hasMany(CrmCorrespondentBank::className(),['cust_id'=>'cust_id']);
    }
    /*交易法人*/
    public function getCompany(){
        return $this->hasOne(BsCompany::className(),['company_id'=>'company_id']);
    }

    /*送審人*/
    public function getCreditPeople(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'credit_people']);
    }
    /*审核流*/
    public function getVerifyType(){
        return $this->hasOne(BsBusinessType::className(),['business_type_id'=>'verify_type']);
    }
    public static function getCreditApply($id,$select=null){
        return self::find()->where(['aid'=>$id])->select($select)->one();
    }
    /*账信类型*/
    public function getCreditType(){
        return $this->hasOne(BsBusinessType::className(),['business_type_id'=>'credit_type']);
    }
    /*起算日*/
    public function getInitialDay(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'initial_day']);
    }
    /*付款方式*/
    public function getPaymentMethod(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'payment_method']);
    }
    /*付款条件*/
    public function getPaymentClause(){
        return $this->hasOne(BsSettlement::className(),['bnt_code'=>'payment_clause']);
    }
    /*付款日*/
    public function getPayDay(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pay_day']);
    }
    /*账信额度*/
    public function getCreditLimit(){
        return $this->hasMany(CrmCreditLimit::className(),['credit_id'=>'credit_id']);
    }

    /*客户签字档*/
    public function getCreditFile1(){
        return $this->hasMany(LCrmCreditFile::className(),['l_credit_id'=>'l_credit_id'])->andWhere(['file_type'=>'10']);
    }
    /*附件*/
    public function getCreditFile2(){
        return $this->hasMany(LCrmCreditFile::className(),['l_credit_id'=>'l_credit_id'])->andWhere(['file_type'=>'11']);
    }

    public function behaviors()
    {
        return [
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                "codeField" => 'credit_code',
                "formName" => self::tableName(),
                "model" => $this,
            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],            //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']            //更新
                ]
            ],
        ];
    }

}
