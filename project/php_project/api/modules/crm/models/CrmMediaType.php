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
 * 媒体类型模型
 */
class CrmMediaType extends ActiveRecord
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
        return 'crm_bs_media_type';
    }

    /**
     * 验证规格
     */
    public function rules()
    {
        return [
            ['cmt_type', 'unique', 'message' => '媒体类型已存在', 'filter' => ['!=', 'cmt_status', self::DELETE_STATUS]],
            [['cmt_status', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['cmt_code', 'cmt_type'], 'string', 'max' => 50],
            [['cmt_intro'], 'string', 'max' => 200],
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
                'codeField'=>'cmt_code',
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

    /**
     * 获取媒体类型
     */
    public static function getMediaType()
    {
        return self::find()->select(['cmt_id','cmt_type'])->where(['cmt_status'=>self::ENABLED_STATUS])->all();
    }
}