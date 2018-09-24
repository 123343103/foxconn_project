<?php
/**
 * User: F1677929
 * Date: 2017/3/30
 */
namespace app\modules\crm\models;
use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
//客户拜访记录主表模型
class CrmVisitRecord extends Common
{
    //状态
    const STATUS_DELETE=0;
    const STATUS_DEFAULT=10;

    public $codeType;

    //表名
    public static function tableName()
    {
        return 'crm_visit_info';
    }

    //验证规则
    public function rules()
    {
        return [
            [['cust_id'], 'required'],
            [['sih_date', 'sih_senddate', 'sih_verifydate', 'create_at', 'update_at'], 'safe'],
            [['cust_id', 'sih_status', 'sih_verifyter', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['sih_code'], 'string', 'max' => 50],
            [['sih_remark'], 'string', 'max' => 200],
            [['sih_status'],'default','value'=>self::STATUS_DEFAULT]
        ];
    }

    //行为
    public function behaviors()
    {
        return [
            'timeStamp'=>[
                'class'=>TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT=>['create_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE=>['update_at'],
                ],
                'value'=>function(){
                    return date("Y-m-d H:i:s",time());
                }
            ],
            'formCode'=>[
                'class'=>FormCodeBehavior::className(),
                'codeField'=>'sih_code',
                'formName'=>self::tableName(),
                'model'=>$this,
            ]
        ];
    }

    //SQL 关联 --F1678086  START
    //关联客户信息
    public function getCrmCustomer(){
        return $this->hasOne(CrmCustomerInfo::className(),['cust_id'=>'cust_id']);
    }
    //会员回访--关联会员信息
    public function getMemberStatus(){
        return $this->hasOne(CrmCustomerStatus::className(),['customer_id'=>'cust_id'])->via('crmCustomer');
    }

    //获取客户类型
    public function getCustomerType(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'cust_type'])->via('crmCustomer');
    }


    //获取客户经理人
    public function getCustomerManager(){
        return $this->hasOne(CrmCustPersoninch::className(),['cust_id'=>'cust_id']);
    }

    //获取营销区域
    public function getSalesArea(){
        return $this->hasOne(CrmSalearea::className(),['csarea_id'=>'cust_salearea'])->via('crmCustomer');
    }
    /*主要联系人*/
    public function getContactPerson()
    {
        return $this->hasOne(CrmCustomerPersion::className(), ['cust_id' => 'cust_id'])->andFilterWhere(["=", "ccper_ismain", 1])->via('crmCustomer');
    }

    /*拜访履历子表*/
    public function getVisitInfoChild(){
        return $this->hasMany(CrmVisitRecordChild::className(),['sih_id'=>'sih_id']);
    }
}