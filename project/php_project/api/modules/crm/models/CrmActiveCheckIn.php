<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models;
use app\models\Common;
use yii\db\ActiveRecord;
use app\behaviors\FormCodeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
//活动签到表模型
class CrmActiveCheckIn extends Common
{
    const DELETE_STATUS=0;//删除状态
    const DEFAULT_STATUS=10;//默认状态
    //表名
    public static function tableName()
    {
        return 'crm_act_checkin';
    }

    //验证规格
    public function rules()
    {
        return [
            [['acth_id', 'actbs_id', 'actcin_status', 'create_by', 'update_by'], 'integer'],
            [['actcin_datetime', 'create_at', 'update_at'], 'safe'],
            [['actcin_ischeckin'], 'string', 'max' => 10],
            [['actcin_nocause', 'actcin_email', 'actcin_remark'], 'string', 'max' => 200],
            [['actcin_name', 'actcin_position', 'actcin_phone'], 'string', 'max' => 20],
        ];
    }

    //行为
    public function behaviors()
    {
        return [
            'timeStamp'=>[
                'class'=>TimestampBehavior::className(),
                'attributes'=>[
                    BaseActiveRecord::EVENT_BEFORE_INSERT=>['create_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE=>['update_at'],
                ]
            ],
        ];
    }
}