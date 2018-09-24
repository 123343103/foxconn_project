<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\system\models\SystemLog;
use Yii;
use app\behaviors\FormCodeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use app\modules\hr\models\HrStaff;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmCustomerPersion;
/**
 * This is the model class for table "crm_customer_apply".
 *
 * @property string $capply_id
 * @property string $cust_id
 * @property string $applyno
 * @property string $applydate
 * @property string $applydep
 * @property integer $applyperson
 * @property string $member_id
 * @property string $description
 * @property string $thereason
 * @property string $toverify
 * @property string $verifyperson
 * @property string $verifydate
 * @property string $status
 * @property string $remark
 * @property string $is_delete
 * @property integer $company_id
 */
class CrmCustomerApply extends Common
{
    const DELETE = 0;
    const NO_DELETE = 10;
    //const STATUS_WAIT =50;  //
    const STATUS_CREATE = 10;//新增
    const STATUS_WAIT=20; //待审核
    const STATUS_CHECKING=30; //审核中
    const STATUS_FINISH=40; //审核完成
    const STATUS_PREPARE = 50;//駁回
    const STATUS_CANCEL=60;//取消
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_customer_apply';
    }
    public static function getCustomerInfoOne($id)
    {
        return self::find()->where(['cust_id' => $id])->one();
    }

    /*主要联系人*/
    public function getContactPerson()
    {
        return $this->hasOne(CrmCustomerPersion::className(), ['cust_id' => 'cust_id'])->andFilterWhere(["=", "ccper_ismain", 1]);
    }

    /*列表页联系人*/
    public function getContactPersons()
    {
        return $this->hasOne(CrmCustomerPersion::className(), ['cust_id' => 'cust_id']);
    }

    /*设备信息*/
    public function getCustDevice()
    {
        return $this->hasMany(CrmCustDevice::className(), ['cust_id' => 'cust_id']);
    }

    /*产品信息*/
    public function getCustProduct()
    {
        return $this->hasMany(CrmCustProduct::className(), ['cust_id' => 'cust_id']);
    }

    /*客户信息*/
    public function getCustCustomer()
    {
        return $this->hasOne(CrmCustomerInfo::className(), ['cust_id' => 'cust_id']);
    }
    /*申请人*/
    public function getApplyStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'applyperson'])->from(['u2' => HrStaff::tableName()]);
    }
    /*客户等级*/
    public function getCustLevel1()
    {
        return $this->hasOne(CrmCustomerInfo::className(), ['cust_id' => 'cust_id'])->select('cust_level');
    }
    public function getCustLevel()
    {
        return $this->hasOne(CrmCustomerInfo::className(), ['cust_id' => 'cust_id']);
    }
    /*客户类型*/
    public function getCustType()
    {
        return $this->hasOne(CrmCustomerInfo::className(), ['cust_id' => 'cust_id'])->select('cust_type');
    }
    /*所在地区*/
    public function getCustArea()
    {
        return $this->hasOne(CrmCustomerInfo::className(), ['cust_id' => 'cust_id'])->select('cust_area');
    }
    /*所在军区*/
    public function getSaleAreaName()
    {
        return $this->hasOne(CrmCustomerInfo::className(), ['cust_id' => 'cust_id']);
    }
    public function getSaleArea()
    {
        return $this->hasOne(CrmSalearea::className(), ['csarea_id' => 'cust_salearea'])->via('saleAreaName');
    }
    /*客户经理人*/
    public function getPersoninch()
    {
        return $this->hasMany(CrmCustPersoninch::className(), ['cust_id' => 'cust_id']);
    }

    /*客户经理人*/
    public function getManager()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'ccpich_personid'])->via('personinch');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id', 'applyperson', 'member_id','company_id', 'verifyperson'], 'integer'],
            [['applydate','is_delete', 'verifydate','toverify', 'applyno', 'status'], 'safe'],
            [['applyno'], 'string', 'max' => 20],
            [['applydep'], 'string', 'max' => 50],
            [['description', 'thereason'], 'string', 'max' => 120],
            [['remark'], 'string', 'max' => 400],
        ];
    }
    public  static function getCustomerOne($id,$select=null){
        return self::find()->where(['capply_id'=>$id])->select($select)->one();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'capply_id' => '申請ID',
            'cust_id' => '客戶表ID',
            'applyno' => '申請編碼',
            'applydate' => '申請日期',
            'applydep' => '申請部門',
            'applyperson' => '申請人',
            'member_id' => '開發會員信息表ID',
            'description' => '描述',
            'thereason' => '申请理由',
            'toverify' => '送审人',
            'verifyperson' => '审核人',
            'verifydate' => '审核日期',
            'status' => '状态',
            'remark' => '备注',
            'is_delete' => '是否删除',
            'company_id' => '公司ID',
        ];
    }

    public function behaviors()
    {
//        if(BaseActiveRecord::EVENT_BEFORE_UPDATE){
//            return $this->status;
//        }
//        SystemLog::addLog($this->status);
//        if($this->status != 30){
//            return [
//                "formCode" => [
//                    "class" => FormCodeBehavior::className(),
//                    "codeField" => 'applyno',
//                    "formName" => 'crm_customer_apply',
//                    'model' => $this,
//                    'update'=>true
//                ]
//            ];
//        }else{
            return [
                "formCode" => [
                    "class" => FormCodeBehavior::className(),
                    "codeField" => 'applyno',
                    "formName" => self::tableName(),
                    "model" => $this,
                ],
                'timeStamp' => [
                    'class' => TimestampBehavior::className(),    //時間字段自動賦值
                    'attributes' => [
                        BaseActiveRecord::EVENT_BEFORE_INSERT => ['applydate'],            //插入
                    ]
                ]
            ];
//        }

    }
}
