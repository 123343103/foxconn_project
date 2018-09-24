<?php

namespace app\modules\system\models;

use app\models\Common;
use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "sys_verifyrecord_child".
 *
 * @property string $vcoc_id
 * @property string $vco_id
 * @property string $ver_scode
 * @property string $ver_acc_id
 * @property string $ver_acc_rule
 * @property string $acc_code_agent
 * @property string $rule_code_agent
 * @property integer $vcoc_status
 * @property string $vcoc_datetime
 * @property string $vcoc_remark
 * @property string $vcoc_computeip
 * @property string acc_code_agent_1
 */
class VerifyrecordChild extends Common
{

    const STATUS_DEFAULT  = 10;           //默认
    const STATUS_CHECKIND = 20;           //待审核
    const STATUS_PASS     = 30;           //通过
    const STATUS_REJECT   = 40;           //被驳回
    const STATUS_OVER   = 50;             //被驳回后跳过的
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_verifyrecord_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vcoc_id','vco_id', 'ver_scode', 'ver_acc_id', 'ver_acc_rule', 'acc_code_agent', 'rule_code_agent','vcoc_status','vcoc_datetime','vcoc_remark','vcoc_computeip','acc_code_agent_1'], 'safe'],
            [['vcoc_status'], 'default', 'value' => self::STATUS_DEFAULT]
        ];
    }

    public function getUserStaff(){
        return $this->hasOne(User::className(),['user_id'=>'ver_acc_id']);
    }
    public function getItem(){
        return $this->hasOne(AuthItem::className(),['name'=>'ver_acc_rule']);
    }

    public static function getCurrentAuditor($val){
        return  self::find()->where(['vco_id' => $val])->andWhere(['vcoc_status' => self::STATUS_CHECKIND])->one();
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vcoc_id' => 'ID',
            'vco_id' => '主表ID',
            'ver_scode' => '審核流顺序',
            'ver_acc_id' => '当前审核人',
            'ver_acc_rule' => '關聯用戶角色表,当前审核角色',
            'acc_code_agent' => '代理人',
            'rule_code_agent' => '代理角色',
            'vcoc_status' => 'Vcoc Status',
            'vcoc_datetime' => '审核时间',
            'vcoc_remark' => '审核意见',
            'vcoc_computeip' => '审核人IP',
        ];
    }

//    public function behaviors()
//    {
//        return [
//            'timeStamp'=>[
//                'class'=>TimestampBehavior::className(),    //時間字段自動賦值
//                'attributes' => [
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['vcoc_datetime'],  //插入
//                ],
//                'value'=>function(){
//                    return date("Y-m-d H:i:s",time());      //賦值的值來源,如不同複寫
//                }
//            ],
//        ];
//    }
}
