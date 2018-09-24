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
 * 载体模型
 */
class CrmCarrier extends ActiveRecord
{
    /**
     * 状态
     */
    const DELETE_STATUS=0;//删除
    const ENABLED_STATUS=10;//启用
    const DISABLED_STATUS=20;//禁用

    /**
     * 表名
     */
    public static function tableName()
    {
        return 'crm_bs_carrier';
    }

    /**
     * 验证规格
     */
    public function rules()
    {
        return [
            ['cc_name', 'unique', 'message' => '载体已存在', 'filter' => ['!=', 'cc_status', self::DELETE_STATUS]],
            [['cc_name', 'cc_carrier', 'cc_sale_way', 'company_id'], 'required'],
            [['cc_status', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['cc_code', 'cc_name', 'cc_carrier', 'cc_sale_way'], 'string', 'max' => 50],
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
                'codeField'=>'cc_code',
                'formName'=>self::tableName(),
                'model'=>$this
            ]
        ];
    }

    /**
     * 获取状态
     */
    public static function getStatus()
    {
        return [
            self::ENABLED_STATUS=>'启用',
            self::DISABLED_STATUS=>'禁用'
        ];
    }
}