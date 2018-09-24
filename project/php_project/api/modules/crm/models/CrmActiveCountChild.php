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
 * 活动统计子表模型
 */
class CrmActiveCountChild extends ActiveRecord
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
        return 'crm_act_count_child';
    }

    /**
     * 验证规格
     */
    public function rules()
    {
        return [
            [['actch_id', 'actch_status', 'actc_dwqty', 'actc_sjkqty', 'actc_partyqty', 'actc_boxqty', 'actc_ldqty', 'actc_memqty', 'actc_artqty', 'actc_watqyt', 'actc_vqty', 'actc_PV', 'actc_SEM', 'cc_id', 'actc_custqty', 'create_by', 'update_by'], 'integer'],
            [['actc_datetime', 'create_at', 'update_at'], 'safe'],
            [['actc_travelqty', 'actc_countqty', 'actc_cpa', 'actc_bUV', 'actc_UV', 'actc_UVadd', 'actc_ordersqty', 'actc_ordcountqyt'], 'number'],
            [['actch_code', 'actc_emailqty'], 'string', 'max' => 50],
            [['actc_extent', 'actc_remark'], 'string', 'max' => 200],
            [['actc_watch', 'actc_cost'], 'string', 'max' => 20],
            [['actc_peopleqty'], 'string', 'max' => 2],
            [['actc_roi'], 'string', 'max' => 5],
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