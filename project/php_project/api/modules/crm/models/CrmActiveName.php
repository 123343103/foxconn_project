<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models;
use app\behaviors\FormCodeBehavior;
use app\models\Common;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
//活动类型模型
class CrmActiveName extends Common
{
    //状态
    const DELETE_STATUS=0;//删除
    const ADD_STATUS=10;//未开始
    const ALREADY_START=20;//进行中
    const ALREADY_END=30;//已结束
    const ALREADY_CANCEL=40;//已取消
    const ALREADY_STOP=50;//已终止

    //表名
    public static function tableName()
    {
        return 'crm_bs_act';
    }

    //验证规格
    public function rules()
    {
        return [
            ['actbs_name', 'unique', 'targetAttribute' => ['actbs_name', 'acttype_id'], 'message' => '活动名称已存在', 'filter' => ['!=', 'actbs_status', self::DELETE_STATUS]],
            [['acttype_id', 'actbs_month', 'actbs_status', 'actbs_duty', 'actbs_industry', 'actbs_address_id', 'actbs_purpose', 'actbs_maintain', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['actbs_start_time', 'actbs_end_time', 'actbs_maintain_time', 'create_at', 'update_at'], 'safe'],
            [['actbs_code', 'actbs_active_url', 'actbs_pm', 'actbs_cost', 'actbs_roi', 'actbs_organizers', 'actbs_official_url'], 'string', 'max' => 50],
            [['actbs_name'], 'string', 'max' => 100],
            [['actbs_city', 'actbs_address'], 'string', 'max' => 20],
            [['actbs_exhibit', 'actbs_intro'], 'string', 'max' => 255],
            [['actbs_maintain_ip'], 'string', 'max' => 30],
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
                    BaseActiveRecord::EVENT_BEFORE_UPDATE=>['update_at']
                ]
            ],
            'formCode'=>[
                'class'=>FormCodeBehavior::className(),
                'codeField'=>'actbs_code',
                'formName'=>self::tableName(),
                'model'=>$this
            ]
        ];
    }

    //获取状态
    public static function getStatus()
    {
        return [
            self::ADD_STATUS=>'未开始',
            self::ALREADY_START=>'进行中',
            self::ALREADY_END=>'已结束',
        ];
    }
}