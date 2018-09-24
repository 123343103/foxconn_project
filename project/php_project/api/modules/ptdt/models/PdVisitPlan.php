<?php

namespace app\modules\ptdt\models;
use app\models\Common;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\show\PdFirmShow;
use Yii;
use yii\db\BaseActiveRecord;
use app\modules\hr\models\HrStaff;
use yii\behaviors\TimestampBehavior;
use app\behaviors\FormCodeBehavior;
use yii\helpers\HtmlPurifier;
/**
 * This is the model class for table "pd_visit_plan".
 *
 * @property integer $pvp_planID
 * @property integer $bus_code
 * @property integer $pvp_type
 * @property integer $pvp_plancode
 * @property integer $firm_id
 * @property string $plan_date
 * @property string $plan_time
 * @property string $plan_place
 * @property string $pvp_contact_man
 * @property string $pvp_contact_position
 * @property string $pvp_contact_tel
 * @property string $pvp_contact_mobile
 * @property integer $pvp_staff_id
 * @property string $purpose
 * @property string $purpose_write
 * @property string $pvp_status
 * @property string $pvp_senddate
 * @property string $pvp_verifydate
 * @property string $pvp_verifyter
 * @property string $note
 * @property string $create_at
 * @property integer $creator
 */
class PdVisitPlan extends Common
{
    const STATUS_DELETE = 0;
    const STATUS_DEFAULT = 10;
    const PLAN_STATUS_ACTION = 20;
    const PLAN_STATUS_OVER = 30;

    /**
     * @inheritdoc
     */
//    public $planStatus = [self::STATUS_DEFAULT=>'新增',self::PLAN_STATUS_ACTION=>'执行中'];

    public static function tableName()
    {
        return 'pd_visit_plan';
    }

    /**
     * 关联厂商信息表
     *@return \yii\db\ActiveQuery
     */
    public function getFirm(){
        return $this->hasOne(PdFirmShow::className(),['firm_id'=>"firm_id"]);
    }
    public function getFirmType(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'firm_type'])->via('firm');
    }
    /**
     * 关联商品经理人
     * @return array
     */
    public function getVisitManager(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'pvp_staff_id']);
    }

    /**
     * 关联创建人信息
     */
    public function getCreatorStaff(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'create_by']);
    }

    public function getVisitPurpose(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'purpose']);
    }

    /**
     * 关联陪同人员信息表
     * @return  array
     */
    public function getPdAccompany(){
        return $this->hasMany(PdAccompany::className(),['h_id'=>'pvp_planID'])->where(['vacc_type'=>1]);
    }
    public function getStaff(){
        return $this->hasMany(HrStaff::className(),['staff_code'=>'staff_code'])->via('pdAccompany');
    }

    public function getPlanType(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pvp_type']);
    }



    public static function getVisitPlanOne($id){
        return self::find()->where(['pvp_planID'=>$id])->one();
    }

    public function behaviors()
    {
        return [
            'timeStamp'=>[
                'class'=>TimestampBehavior::className(),    //时间字段自动赋值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],  //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['update_at']            //更新
                ],
                'value'=>function(){
                    return date("Y-m-d H:i:s",time());          //赋值的值来源,如不同複写
                }
            ],
            'formCodeBehavior'=>[
                'class'=>FormCodeBehavior::className(),     //计画编号自动赋值
                'codeField'=>'pvp_plancode',               //注释字段名
                'formName'=>'pd_visit_plan',               //注释表名
                'model'=>$this
            ]
        ];
    }
    /**
     * @inheritdoc
     */
    public $plan_starttime;
    public $plan_endtime;
    public function rules()
    {
        return [
            [['plan_date','plan_place','pvp_contact_man','pvp_contact_position','pvp_contact_mobile','purpose','pvp_type'], 'required'],
            [['bus_code', 'pvp_type','plan_status', 'firm_id', 'pvp_verifyter','plan_status','pvp_status','pvp_staff_id','create_by','company_id'], 'integer'],
            [['pvp_planID', 'pvp_staff_id', 'create_at','create_by','update_at','update_by', ], 'safe'],
            [['note'], 'string', 'max' => 400],
            [['purpose_write'], 'string', 'max' => 255],
            [['plan_time'], 'string', 'max' => 20],
            [['pvp_plancode'], 'string', 'max' => 50],
            ['pvp_status', 'default', 'value' => self::STATUS_DEFAULT ],
            ['plan_status', 'default', 'value' => self::STATUS_DEFAULT ],
        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pvp_planID' => '主键ID',
            'bus_code' => '业务对象代码',
            'pvp_type' => '计划类别',
            'pvp_plancode' => '计划编号',
            'firm_id' => '厂商信息ID',
            'plan_date' => '计划日期',
            'plan_starttime' => '计划时间',
            'plan_place' => '计划地点',
            'pvp_contact_man' => '厂商联系人',
            'pvp_contact_position' => '职位',
            'pvp_contact_mobile' => '联系手机',
            'pvp_staff_id' => '拜访人(商品经理人)',
            'purpose' => '目的',
            'purpose_write' => '目的',
            'pvp_status' => '状态',
            'pvp_senddate' => '提交日期',
            'pvp_verifydate' => '审核日期',
            'pvp_verifyter' => '审核人',
            'note' => '备注',
            'plan_status' => '计画状态',
            'plan_endtime' => '结束时间',
            'create_at' => '建立计画时间',
            'create_by' => '建立人'
        ];
    }

}
