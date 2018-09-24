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
/**
 * This is the model class for table "crm_visit_info_child".
 *
 * @property string $sil_id
 * @property string $sih_id
 * @property string $sil_code
 * @property string $svp_plan_id
 * @property string $sil_date
 * @property string $sil_time
 * @property string $sil_location
 * @property string $sil_staff_code
 * @property string $rece_id
 * @property string $sil_process_descript
 * @property string $sil_interview_conclus
 * @property string $sil_track_item
 * @property string $sil_next_interview_notice
 * @property string $sih_vis_person
 * @property string $vacc_id
 * @property string $other
 * @property integer $sil_status
 * @property string $remark
 * @property string $title
 * @property string $start
 * @property string $end
 * @property string $color
 * @property string $editable
 * @property string $type
 * @property integer $sil_type
 * @property string $execute_project
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 * @property integer $company_id
 * @property string $img_url
 * @property integer $data_from
 */
//客户拜访记录子表模型
class CrmVisitRecordChild extends Common
{
    //状态 type 字段为string 别乱改
    const STATUS_DELETE  = 0;
    const STATUS_DEFAULT = 10;
    const TYPE_RECORD    = '20';   //拜访记录
    const TYPE_LINSHI    = '30';   //临时记录
    const TYPE_MEMBER    = '40';   //会员回访
    const TYPE_POTENTIAL = '50'; //潜在客户回访
    const TYPE_INVESTMENT= '60'; //招商客户开发

    public $codeType;
    //表名
    public static function tableName()
    {
        return 'crm_visit_info_child';
    }

    //关联拜访计划
    public function getVisitPlan(){
        return $this->hasOne(CrmVisitPlan::className(),['svp_id'=>'svp_plan_id']);
    }

    //拜访人
    public function getStaff(){
        return $this->hasOne(HrStaff::className(),['staff_code'=>'sil_staff_code']);
    }

    //sql 关联  --F1678086 START
    //拜访记录主表
    public function getVisitInfo()
    {
        return $this->hasOne(CrmVisitRecord::className(), ['sih_id' => 'sih_id']);
    }

    //关联拜访计划
    public function getPlanInfo()
    {
        return $this->hasOne(CrmVisitPlan::className(), ['svp_id' => 'svp_plan_id']);
    }

    /**
     * 获取拜访人
     */
    public function getVisitPerson()
    {
        return $this->hasOne(HrStaff::className(), ['staff_code' => 'sil_staff_code']);
    }

    /**
     * 获取拜访类型
     */
    public function getVisitType()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'sil_type']);
    }

    /**
     * 获取创建人
     */
    public function getCreatePerson()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'create_by']);
    }
    //sql 关联  --F1678086  END

    //验证规则
    public function rules()
    {
        return [
            [['sih_id'], 'required'],
            [['sih_id', 'svp_plan_id', 'rece_id', 'sih_vis_person', 'vacc_id', 'sil_status', 'sil_type', 'create_by', 'update_by', 'company_id', 'data_from'], 'integer'],
            [['sil_date', 'start', 'end', 'create_at', 'update_at'], 'safe'],
            [['sil_code'], 'string', 'max' => 30],
            [['sil_time', 'img_url'], 'string', 'max' => 100],
            [['sil_location', 'other', 'remark', 'title'], 'string', 'max' => 200],
            [['sil_staff_code'], 'string', 'max' => 20],
            [['sil_process_descript', 'sil_interview_conclus', 'sil_track_item', 'sil_next_interview_notice', 'execute_project'], 'string', 'max' => 255],
            [['color', 'type'], 'string', 'max' => 10],
            [['editable'], 'string', 'max' => 5],
            [['sil_status'],'default','value'=>self::STATUS_DEFAULT]
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
                'codeField'=>'sil_code',
                'formName'=>self::tableName(),
                'model'=>$this,
            ]
        ];
    }
}