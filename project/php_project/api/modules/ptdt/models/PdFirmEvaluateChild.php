<?php
namespace app\modules\ptdt\models;
use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\show\PdFirmEvaluateResultShow;
use app\modules\ptdt\models\show\PdFirmShow;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
/**
 * This is the model class for table "pd_firm_evaluate_child".
 * @property string $evaluate_child_id
 * @property string $evaluate_id
 * @property string $report_id
 * @property string $report_child_id
 * @property string $evaluate_child_code
 * @property string $evaluate_person
 * @property string $evaluate_date
 * @property string $evaluate_reason
 * @property string $passage_server_score
 * @property string $passage_server_decide
 * @property string $price_delivery_score
 * @property string $price_delivery_decide
 * @property string $operate_finance_score
 * @property string $operate_finance_decide
 * @property string $manage_innovate_score
 * @property string $manage_innovate_decide
 * @property string $evaluate_synthesis_score
 * @property string $evaluate_level
 * @property string $evaluate_department
 * @property string $evaluate_result
 * @property string $cause_description
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 */
/**
 * 厂商评鉴子表模型
 */
class PdFirmEvaluateChild extends Common
{
    const STATUS_DELETE = 0;//删除
    const STATUS_DEFAULT = 10;//默认

    /**
     * 表名
     */
    public static function tableName()
    {
        return 'pd_firm_evaluate_child';
    }

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['evaluate_id', 'report_id', 'create_by', 'update_by', 'evaluate_level'], 'integer'],
            [['evaluate_child_status', 'evaluate_date', 'create_at', 'update_at'], 'safe'],
            [['report_child_id', 'evaluate_child_code', 'evaluate_reason'], 'string', 'max' => 255],
            [['passage_server_score', 'passage_server_decide', 'price_delivery_score', 'price_delivery_decide', 'operate_finance_score', 'operate_finance_decide', 'manage_innovate_score', 'manage_innovate_decide', 'evaluate_synthesis_score'], 'string', 'max' => 200],
        ];
    }

    /**
     * 属性标签
     */
    public function attributeLabels()
    {
        return [
            'evaluate_child_id' => '评鉴子表id',
            'evaluate_id' => '评鉴主表id',
            'report_id' => '厂商呈报主表id',
            'report_child_id' => '厂商呈报子表id',
            'evaluate_child_code' => '评鉴子表编码',
            'evaluate_date' => '评鉴日期',
            'evaluate_reason' => '评鉴理由',
            'passage_server_score' => '通路与服务能力评鉴得分',
            'passage_server_decide' => '通路与服务能力结果判定',
            'price_delivery_score' => '价格及交货能力评鉴得分',
            'price_delivery_decide' => '价格及交货能力结果判定',
            'operate_finance_score' => '经营与财务能力评鉴得分',
            'operate_finance_decide' => '经营与财务能力结果判定',
            'manage_innovate_score' => '管理与创新能力评鉴得分',
            'manage_innovate_decide' => '管理与创新能力结果判定',
            'evaluate_synthesis_score' => '综合得分',
            'evaluate_level' => '综合等级',
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
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                "codeField" => 'evaluate_child_code',
                "formName"=> self::tableName()
            ]
        ];
    }

    /**
     * 关联厂商评鉴结果表
     */
    public function getEvaluateResult()
    {
        return $this->hasOne(PdFirmEvaluateResult::className(),['evaluate_child_id'=>'evaluate_child_id'])->orderBy(['create_at'=>SORT_DESC]);
    }
    public function getFirmEvaluate()
    {
        return $this->hasOne(PdFirmEvaluateResult::className(),['evaluate_child_id'=>'evaluate_child_id'])->where(['evaluate_department'=>PdFirmEvaluateResult::FIRM]);
    }
    public function getPurchaseEvaluate()
    {
        return $this->hasOne(PdFirmEvaluateResult::className(),['evaluate_child_id'=>'evaluate_child_id'])->where(['evaluate_department'=>PdFirmEvaluateResult::PROCUREMENT]);
    }
    public function getManageEvaluate()
    {
        return $this->hasOne(PdFirmEvaluateResult::className(),['evaluate_child_id'=>'evaluate_child_id'])->where(['evaluate_department'=>PdFirmEvaluateResult::MANAGEMENT]);
    }

    /**
     * 关联厂商评鉴主表
     */
    public function getPdFirmEvaluate()
    {
        return $this->hasOne(PdFirmEvaluate::className(),['evaluate_id'=>'evaluate_id']);
    }

    /**
     * 获取综合等级
     */
    public function getSynthesisLevel()
    {
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'evaluate_level']);
    }
}
