<?php
namespace app\modules\ptdt\models;
use app\behaviors\FormCodeBehavior;
use app\models\Common;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
//厂商拜访履历子表模型
class PdVisitResumeChild extends Common
{
    //状态
    const STATUS_DELETE = 0;//删除
    const STATUS_DEFAULT = 10;//默认

    //表名
    public static function tableName()
    {
        return 'pd_visit_resume_child';
    }

    //验证规则
    public function rules()
    {
        return [
            [['vih_id', 'vih_vis_person', 'vil_status', 'visit_planID', 'create_by', 'update_by'], 'integer'],
            [['vil_start_time', 'vil_end_time', 'create_at', 'update_at'], 'safe'],
            [['vil_code', 'vil_process_Descript', 'vil_interview_Conclus', 'vil_track_Item', 'vil_next_Interview_Notice', 'vil_other', 'vil_remark'], 'string', 'max' => 255],
        ];
    }

    //行为
    public function behaviors()
    {
        return [
            'timeStamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at'],
                ],
            ],
            "formCode" => [
                "class"=>FormCodeBehavior::className(),
                "codeField"=>'vil_code',
                "formName"=>self::tableName(),
                'model'=>$this
            ]
        ];
    }
}
