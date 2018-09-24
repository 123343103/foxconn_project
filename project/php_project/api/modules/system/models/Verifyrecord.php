<?php

namespace app\modules\system\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\models\User;
use app\modules\common\models\BsBusinessType;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "sys_verifyrecord".
 *
 * @property string $vco_id
 * @property string $ver_id
 * @property string $vco_code
 * @property string $bus_code
 * @property string $but_code
 * @property string $vco_busid
 * @property string $vco_send_acc
 * @property string $vco_senddate
 * @property string $vco_send_dept
 * @property integer $vco_status
 */
class Verifyrecord extends Common
{
    const STATUS_DEFAULT  = 10;           //默认,审核中
    const STATUS_PASS     = 20;           //通过
    const STATUS_REJECT   = 40;           //被驳回
    const STATUS_CANNEL =50;             //取消
    const _PASS   = 1; //送审
    const _REJECT = 0; //驳回
    const _OTHER  = 2; //其他
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_verifyrecord';
    }
    public function getVerifyChild(){
        return $this->hasMany(VerifyrecordChild::className(),['vco_id'=>'vco_id']);
    }

    public function getCurrentVerify(){
        return $this->hasOne(VerifyrecordChild::className(),['vco_id'=>'vco_id'])->where(['vcoc_status'=>VerifyrecordChild::STATUS_CHECKIND]);
    }

    public function getVerifyCode(){
        return $this->hasOne(VerifyrecordChild::className(),['vco_id'=>'vco_id'])->where(['vcoc_status'=>VerifyrecordChild::STATUS_CHECKIND])->one();
    }


    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getLastVerify(){
        $code = $this->getVerifyCode();
        $codes=$code['ver_scode']-1;
        if($codes == 0){
            return null;
        }
        return $this->hasOne(VerifyrecordChild::className(),['vco_id'=>'vco_id'])->where(['ver_scode'=>$codes,'vcoc_status'=>VerifyrecordChild::STATUS_PASS])->one();
    }
    public function getNextVerify(){
        $code = $this->getVerifyCode();
        $codes=$code['ver_scode']+1;
//        $child=$this->getVerifyChild();
//        if($codes == $child->count()){
//            return null;
//        }
        return $this->hasOne(VerifyrecordChild::className(),['vco_id'=>'vco_id'])->where(['ver_scode'=>$codes])->one();
    }
    public function getBusinessType(){
        return $this->hasOne(BsBusinessType::className(),['business_type_id'=>'but_code']);
    }

    public function getStaff(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'vco_send_acc']);
    }

    public static function getVerifyOne($id){
        return self::find()->where(['vco_id'=>$id])->one();
    }
    public function getOrganization(){
        return $this->hasOne(HrOrganization::className(),['organization_code'=>'vco_send_dept']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vco_id','ver_id', 'bus_code', 'but_code', 'vco_busid', 'vco_send_acc', 'vco_status','vco_senddate','vco_code', 'vco_send_dept'], 'safe'],
            [['vco_status'], 'default', 'value' => self::STATUS_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vco_id' => 'ID',
            'ver_id' => '审核流ID',
            'vco_code' => '申请单号',
            'bus_code' => '业务类别代码',
            'but_code' => '关联业务类型表',
            'vco_busid' => '被送審的單據ID',
            'vco_send_acc' => '送审人',
            'vco_senddate' => '送审时间',
            'vco_send_dept' => '送审部门',
            'vco_status' => 'Vco Status',
        ];
    }

    public function behaviors()
    {
        return [
            'timeStamp'=>[
                'class'=>TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['vco_senddate'],  //插入
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']  //更新
                ],
                'value'=>function(){
                    return date("Y-m-d H:i:s",time());      //賦值的值來源,如不同複寫
                }
            ],
            'formCode'=>[
                'class'=>FormCodeBehavior::className(),     //計畫編號自動賦值
                'codeField'=>'vco_code',                    //字段名
                'formName'=>'system_verifyrecord',          //表名
                'model'=>$this,
            ]
        ];
    }
}
