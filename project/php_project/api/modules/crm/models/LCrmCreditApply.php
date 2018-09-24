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
 * This is the model class for table "l_crm_credit_apply".
 *
 * @property string $l_credit_id
 * @property string $credit_id
 * @property integer $cust_id
 * @property string $credit_code
 * @property string $currency
 * @property string $total_amount
 * @property string $credit_amount
 * @property string $allow_amount
 * @property string $used_limit
 * @property string $surplus_limit
 * @property string $grand_total_limit
 * @property string $apply_remark
 * @property string $credit_date
 * @property string $credit_people
 * @property string $credit_status
 * @property integer $company_id
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 * @property string $standby1
 * @property string $standby2
 * @property string $standby3
 *
 * @property LCrmCreditFile[] $lCrmCreditFiles
 * @property LCrmCreditLimit[] $lCrmCreditLimits
 */
class LCrmCreditApply extends Common
{
    const STATUS_DELETE = '10'; //取消
    const STATUS_PENDING = '11'; //待提交
    const STATUS_REVIEW = '12'; //审核中
    const STATUS_OVER = '13'; //审核完成
    const STATUS_REJECT = '14'; //驳回
//    const STATUS_FREEZE = '15';//冻结
    public $codeType;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_crm_credit_apply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id', 'credit_people', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['total_amount', 'credit_amount','volume_trade'], 'number'],
            [['credit_date', 'create_at', 'update_at'], 'safe'],
            [['credit_code', 'currency', 'credit_type', 'payment_type', 'payment_clause', 'payment_clause_day', 'payment_method', 'initial_day', 'pay_day', 'standby1', 'standby2', 'standby3'], 'string', 'max' => 20],
            [['apply_remark'], 'string', 'max' => 255],
            [['can_reason'], 'string', 'max' => 200],
            [['file_old','file_new'], 'string', 'max' => 100],
            [['credit_status'], 'string', 'max' => 2],
            [['credit_status'],'default','value'=>self::STATUS_PENDING],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_credit_id' => 'ID',
            'cust_id' => '关联客户ID',
            'credit_code' => '申请单号',
            'currency' => '币别',
            'credit_type' => '信用额度类型',
            'total_amount' => '申请总额度',
            'credit_amount' => '批复总额度',
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
            'can_reason' => '取消原因',
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

    /*账信额度*/
    public function getCreditLimit(){
        return $this->hasMany(LCrmCreditLimit::className(),['l_credit_id'=>'l_credit_id'])->andWhere(['limit_status'=>LCrmCreditLimit::YES_NEW]);
    }
    /*客户签字档*/
    public function getCreditFile1(){
        return $this->hasMany(LCrmCreditFile::className(),['l_credit_id'=>'l_credit_id'])->andWhere(['file_type'=>'10']);
    }
    /*附件*/
    public function getCreditFile2(){
        return $this->hasMany(LCrmCreditFile::className(),['l_credit_id'=>'l_credit_id'])->andWhere(['file_type'=>'11']);
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

    public static function getCreditApply($id,$select=null){
        return self::find()->where(['l_credit_id'=>$id])->select($select)->one();
    }
//
//    /*关联额度类型--未送审*/
//    public function getCreditLimitNa(){
//        return $this->hasMany(CrmCreditLimit::className(),['aid'=>'aid'])->where(['and',['>=','validity',date('Y-m-d',time())],['=','is_approval',CrmCreditLimit::D_APPROVAL]]);
//    }
//    /*关联额度类型--审核完成*/
//    public function getCreditLimitYa(){
//        return $this->hasMany(CrmCreditLimit::className(),['aid'=>'aid'])->where(['and',['>=','validity',date('Y-m-d',time())],['=','is_approval',CrmCreditLimit::Y_APPROVAL]]);
//    }
//    /*关联额度类型--审核驳回*/
//    public function getCreditLimitRa(){
//        return $this->hasMany(CrmCreditLimit::className(),['aid'=>'aid'])->where(['and',['>=','validity',date('Y-m-d',time())],['=','is_approval',CrmCreditLimit::N_APPROVAL]]);
//    }

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
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at','credit_date'],            //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']            //更新
                ]
            ],
        ];
    }

}
