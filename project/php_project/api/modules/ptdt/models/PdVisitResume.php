<?php
namespace app\modules\ptdt\models;
use app\behaviors\FormCodeBehavior;
use app\models\Common;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
//厂商拜访履历主表模型
class PdVisitResume extends Common
{
    //状态
    const VISIT_DELETE=0;//拜访删除
    const VISIT_ING=10;//拜访中
    const VISIT_COMPLETE=20;//拜访完成

    //厂商拜访履历主表名
    public static function tableName()
    {
        return 'pd_visit_resume';
    }

    //验证规则
    public function rules()
    {
        return [
            [['firm_id'], 'required'],
            [['vih_Date', 'vih_senddate', 'vih_verifydate', 'create_at', 'update_at'], 'safe'],
            [['firm_id', 'vih_vis_person', 'vih_status', 'vih_verifyter', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['vih_code'], 'string', 'max' => 50],
            [['vih_location', 'vih_remark'], 'string', 'max' => 200],
        ];
    }

    //行为
    public function behaviors()
    {
        return [
            'timeStamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at', 'vih_Date'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']
                ],
            ],
            "formCode" => [
                "class"=>FormCodeBehavior::className(),
                "codeField"=>'vih_code',
                "formName"=>self::tableName(),
                'model'=>$this
            ]
        ];
    }

    //获取拜访状态
    public static function visitStatus()
    {
        return [
            self::VISIT_ING=>'拜访中',
            self::VISIT_COMPLETE=>'拜访完成',
        ];
    }

    //关联厂商
//    public function getFirm()
//    {
//        return $this->hasOne(PdFirm::className(),['firm_id'=>'firm_id']);
//    }
}
