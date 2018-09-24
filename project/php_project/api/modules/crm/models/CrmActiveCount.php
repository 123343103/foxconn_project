<?php
/**
 * User: F1677929
 * Date: 2017/6/5
 */
namespace app\modules\crm\models;
use app\behaviors\FormCodeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * 活动统计主表模型
 */
class CrmActiveCount extends ActiveRecord
{
    /**
     * 状态
     */
    const DELETE_STATUS=0;//删除
    const DEFAULT_STATUS=10;//默认

    /**
     * 表名
     */
    public static function tableName()
    {
        return 'crm_act_count';
    }

    /**
     * 验证规格
     */
    public function rules()
    {
        return [
            ['actbs_id', 'unique', 'message' => '已存在', 'filter' => ['!=', 'actch_status', self::DELETE_STATUS]],
            [['company_id', 'actbs_id', 'actch_status', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['actch_code'], 'string', 'max' => 50],
            [['acttype_id'], 'string', 'max' => 20],
            [['actch_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * 行为
     */
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
                'codeField'=>'actch_code',
                'formName'=>self::tableName(),
                'model'=>$this
            ]
        ];
    }
}