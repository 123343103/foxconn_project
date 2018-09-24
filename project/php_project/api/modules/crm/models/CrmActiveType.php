<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models;
use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\common\models\BsPubdata;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Query;
//活动类型模型
class CrmActiveType extends Common
{
    //状态
    const DELETE_STATUS=0;//删除
    const VALID_STATUS=10;//有效
    const INVALID_STATUS=20;//无效

    //表名
    public static function tableName()
    {
        return 'crm_bs_acttype';
    }

    //验证规格
    public function rules()
    {
        return [
            ['acttype_name', 'unique', 'message' => '活动类型已存在', 'filter' => ['!=', 'acttype_status', self::DELETE_STATUS]],
            [['acttype_way', 'acttype_status', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['acttype_code'], 'string', 'max' => 50],
            [['acttype_name'], 'string', 'max' => 30],
            [['acttype_description', 'acttype_remark'], 'string', 'max' => 200],
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
                'codeField'=>'acttype_code',
                'formName'=>self::tableName(),
                'model'=>$this
            ]
        ];
    }

    //获取状态
    public static function getStatus()
    {
        return [
            self::VALID_STATUS=>'启用',
            self::INVALID_STATUS=>'禁用'
        ];
    }

    //获取活动类型
    public static function getActiveType($where=[])
    {
        return self::find()->leftJoin(BsPubdata::tableName(),BsPubdata::tableName().'.bsp_id='.self::tableName().'.acttype_name')->select(['acttype_id',BsPubdata::tableName().'.bsp_svalue as acttype_name'])->where($where)->andWhere(['acttype_status'=>self::VALID_STATUS])->orderBy(['acttype_id'=>SORT_DESC])->all();
    }
}