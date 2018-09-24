<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsSettlement;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
/**
 * This is the model class for table "crm_credit_limit".
 *
 * @property integer $laid
 * @property integer $aid
 * @property integer $cust_id
 * @property string $credit_type
 * @property string $credit_limit
 * @property string $approval_limit
 * @property string $currency
 * @property string $payment_clause
 * @property string $payment_method
 * @property string $initial_day
 * @property string $pay_day
 * @property string $validity
 * @property string $remark
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 * @property string $standby1
 * @property string $standby2
 * @property string $standby3
 */
class CrmCreditLimit extends Common
{
    const Y_APPROVAL = 20;    //授予
    const N_APPROVAL = 10;    //不授予
    const D_APPROVAL = 30;    //默认
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_credit_limit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['credit_id', 'is_approval', 'limit_status'], 'integer'],
            [['credit_limit', 'approval_limit', 'used_limit', 'surplus_limit'], 'number'],
            [['validity_date'], 'safe'],
            [['credit_type', 'standby1', 'standby2', 'standby3'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 255],
            [['credit_id'], 'exist', 'skipOnError' => true, 'targetClass' => CrmCreditApply::className(), 'targetAttribute' => ['credit_id' => 'credit_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'limit_id' => 'ID',
            'credit_id' => '关联账信申请表',
            'credit_type' => '信用额度类型',
            'credit_limit' => '申请额度',
            'approval_limit' => '批复额度',
            'used_limit' => '使用额度',
            'surplus_limit' => '剩余额度',
            'validity_date' => '额度有效期',
            'is_approval' => '是否授予',
            'limit_status' => '状态',
            'remark' => '备注',
            'standby1' => '备用字段1',
            'standby2' => '备用字段2',
            'standby3' => '备用字段3',
        ];
    }
    /*额度类型*/
    public function getCreditType(){
        return $this->hasOne(CrmCreditMaintain::className(),['id'=>'credit_type']);
    }
    /*起算日*/
    public function getInitial(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'initial_day']);
    }
    /*付款日*/
    public function getPay(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pay_day']);
    }
    /*付款方式*/
    public function getPayMethod(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'payment_method']);
    }
    /*付款条件*/
    public function getPayClause(){
        return $this->hasOne(BsSettlement::className(),['bnt_id'=>'payment_clause']);
    }
    /*审核时查询额度*/
    public static function getCreditLimit($id,$select=null){
        return self::find()->where(['aid'=>$id])->select($select)->where(['=','is_approval',CrmCreditLimit::D_APPROVAL])->all();
    }

}
