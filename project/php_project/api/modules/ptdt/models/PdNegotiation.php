<?php

namespace app\modules\ptdt\models;

use app\behaviors\FormCodeBehavior;
use app\behaviors\StaffBehavior;
use app\models\Common;
use app\modules\ptdt\models\show\PdNegotiationProductShow;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * F3859386
 * 2016.10.22
 * 厂商谈判模型
 *
 * @property integer $pdn_id
 * @property string $pdn_code
 * @property string $pdn_date
 * @property integer $firm_id
 * @property integer $vil_id
 * @property string $vil_location
 * @property integer $vil_plan_id
 * @property string $pdn_status
 * @property string $pdn_senddate
 * @property string $pdn_verifydate
 * @property integer $pdn_verifyter
 * @property string $pdn_remark
 * @property integer $creator
 * @property string $create_at
 * @property integer $updater
 * @property string $update_at
 */
class PdNegotiation extends Common
{
    //删除
    const STATUS_DELETE = 0;
    //新增
    const STATUS_NEW = 10;
    //谈判中
    const STATUS_HALF = 20;
     //谈判完成
    const STATUS_END  = 30;
    public static function tableName()
    {
        return 'pd_negotiation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pdn_code','vil_location','pdn_remark','pdn_date', 'pdn_senddate', 'pdn_verifydate','firm_id', 'vil_id', 'vil_plan_id', 'pdn_verifyter'], 'safe'],
            [['pdn_status'], 'default', 'value' => self::STATUS_HALF ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pdn_id' => 'Pdn ID',
            'pdn_code' => '谈判履历编号',
            'pdn_date' => '建立履历日期',
            'firm_id' => '厂商ID',
            'vil_id' => '关联拜访履历',
            'vil_location' => '计画谈判地点',
            'vil_plan_id' => '关联计画ID',
            'pdn_status' => '状态',
            'pdn_senddate' => '提交日期',
            'pdn_verifydate' => '审核日期',
            'pdn_verifyter' => '审核人',
            'pdn_remark' => '备注',
            'create_by' => 'create_by',
            'create_at' => 'Create At',
            'update_by' => 'Updater',
            'update_at' => 'Update At',
        ];
    }

    public function behaviors()
    {
        return [
            'timeStamp'=>[
                'class'=>TimestampBehavior::className(),    //时间字段自动赋值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at','pdn_date'],  //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['update_at']  //更新
                ],
                'value'=>function(){
                    return date("Y-m-d H:i:s",time());       //赋值的值来源,如不同複写
                }
            ],
            'formCode'=>[
                'class'=>FormCodeBehavior::className(),     //计画编号自动赋值
                'codeField'=>'pdn_code',                    //注释字段名
                'formName'=>self::tableName(),               //注释表名
            ]
        ];
    }

   //关联厂商
    public function getFirm(){
        return $this->hasOne(PdFirm::className(),['firm_id'=>'firm_id']);
    }
   //关联子表
    public function getChild(){
        return $this->hasMany(PdNegotiationChild::className(),['pdn_id'=>'pdn_id']);
    }
}
