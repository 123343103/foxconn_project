<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsSettlement;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
/**
 * This is the model class for table "crm_cust_customer".
 *
 * @property integer $cc_customerid
 * @property integer $cust_id
 * @property string $cc_customer_name
 * @property string $cc_customer_type
 * @property string $cc_customer_person
 * @property string $cc_customer_mobile
 * @property string $cc_customer_tel
 * @property string $cc_customer_ratio
 * @property string $cc_customer_remark
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 */
class CrmCustCustomer extends Common
{
    const TYPE_CUSTOMER = 1; //主要客户
    const TYPE_SUPPLIER= 2; //主要供应商

    const STATUS_DELETE = 0;    //删除
    const STATUS_DEFAULT = 10;    //默认
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_cust_customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['cc_customerid'], 'required'],
            [['cc_customerid', 'cust_id', 'create_by', 'update_by','status'], 'integer'],
            [['create_at', 'update_at','payment_clause','cust_type'], 'safe'],
            [['cc_customer_name', 'cc_customer_type', 'cc_customer_person', 'cc_customer_mobile', 'cc_customer_tel', 'cc_customer_ratio', 'cc_customer_remark'], 'string', 'max' => 255],
            [['payment_clause'], 'string', 'max' => 20],
            [['cust_type'],'default','value'=>self::TYPE_CUSTOMER],
            [['status'],'default','value'=>self::STATUS_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cc_customerid' => '主要客户ID',
            'cust_id' => '关联客户信息表',
            'cc_customer_name' => '客户名称',
            'cc_customer_type' => '经营类型',
            'cc_customer_person' => '公司负责人',
            'cc_customer_mobile' => '电话(手机)',
            'cc_customer_tel' => '公司电话',
            'cc_customer_ratio' => '占营收比例',
            'cc_customer_remark' => '备注',
            'cust_type' => '客户类型',
            'payment_clause' => '付款条件',
            'create_by' => '创建人',
            'create_at' => '创建时间',
            'update_by' => '更新人',
            'update_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     * 经营类型
     */
    public function getCustomerType(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'cc_customer_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * 付款条件
     */
    public function getCaluse(){
        return $this->hasOne(BsSettlement::className(),['bnt_id'=>'payment_clause']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * 付款条件
     */
    public function getCaluseCode(){
        return $this->hasOne(BsSettlement::className(),['bnt_code'=>'payment_clause']);
    }

    /**
     * @return array
     * 行为
     */
    public function behaviors()
    {
        return [
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
