<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models;
use app\models\Common;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
//活动缴费表模型
class CrmActivePay extends Common
{
    //状态
    const DELETE_STATUS=0;//删除状态
    const DEFAULT_STATUS=10;//默认状态

    //表名
    public static function tableName()
    {
        return 'crm_act_payment';
    }

    //验证规格
    public function rules()
    {
        return [
            [['acth_id', 'actbs_id', 'actpaym_status', 'create_by', 'update_by'], 'integer'],
            [['actpaym_date', 'create_at', 'update_at'], 'safe'],
            [['actpaym_amount', 'actpaym_name'], 'string', 'max' => 20],
            [['actpaym_isfp'], 'string', 'max' => 10],
            [['actpaym_paydesription', 'actpaym_remark'], 'string', 'max' => 200],
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
//                    BaseActiveRecord::EVENT_BEFORE_INSERT=>['create_at','actpaym_date'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE=>['update_at'],
                ]
            ],
        ];
    }
}