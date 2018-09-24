<?php
namespace app\modules\ptdt\models;
use app\models\Common;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
/**
 * This is the model class for table "pd_firm_evaluate_result".
 * @property string $result_id
 * @property string $evaluate_id
 * @property string $evaluate_child_id
 * @property string $firm_id
 * @property string $evaluate_department
 * @property string $evaluate_result
 * @property string $cause_description
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 */
/**
 * 厂商评鉴结果表模型
 */
class PdFirmEvaluateResult extends Common
{
    //评鉴部门
    const FIRM = '10';//厂商评鉴
    const PROCUREMENT = '20';//采购意见
    const MANAGEMENT = '30';//工管意见

    //评鉴结果
    const PASS = '1';//通过
    const NO_PASS = '2';//不通过
    const EVALUATE_PASS = '3';//评鉴通过
    const CANCEL_ADD = '4';//取消新增
    const PLAN_TUTOR = '5';//安排辅导
    const IMPROVE_REVIEW = '6';//改善后复查

    /**
     * 表名
     */
    public static function tableName()
    {
        return 'pd_firm_evaluate_result';
    }

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['evaluate_id', 'evaluate_child_id', 'firm_id', 'update_by'], 'integer'],
            [['evaluate_department', 'evaluate_result','create_at', 'update_at', 'create_by'], 'safe'],
            [['cause_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * 属性标签
     */
    public function attributeLabels()
    {
        return [
            'result_id' => '厂商评鉴结果id',
            'evaluate_id' => '评鉴主表id',
            'evaluate_child_id' => '评鉴子表id',
            'firm_id' => '厂商id',
            'evaluate_department' => '评鉴部门',
            'evaluate_result' => '评鉴结果',
            'cause_description' => '原因说明',
            'create_by' => '创建人',
            'create_at' => '创建时间',
            'update_by' => '修改人',
            'update_at' => '修改时间',
        ];
    }

    /**
     * 行为
     */
    public function behaviors()
    {
        return [
            'timeStamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']
                ],
            ],
        ];
    }

    /**
     * 厂商评鉴结果
     */
    public static function firmEvaluateResultList()
    {
        return [
            self::PASS => '通过',
            self::NO_PASS => '不通过',
        ];
    }

    /**
     * 采购工管评鉴结果
     */
    public static function purchaseManageEvaluateResultList()
    {
        return [
            self::EVALUATE_PASS => '评鉴通过',
            self::CANCEL_ADD => '取消新增',
            self::PLAN_TUTOR => '安排辅导',
            self::IMPROVE_REVIEW => '改善后复查',
        ];
    }
}
