<?php
namespace app\modules\ptdt\models;
use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
/**
 * This is the model class for table "pd_firm_evaluate".
 * @property string $evaluate_id
 * @property string $evaluate_code
 * @property integer $evaluate_status
 * @property string $evaluate_add_date
 * @property string $firm_id
 * @property string $firm_staff_number
 * @property string $firm_register_money
 * @property string $firm_create_date
 * @property string $applicant_id
 * @property string $applicant_department
 * @property string $applicant_class
 * @property string $server_customer
 * @property string $predict_evaluate_date
 * @property integer $apply_type
 * @property string $check_person
 * @property string $check_date
 * @property integer $company_id
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 */
/**
 * 厂商评鉴主表模型
 */
class PdFirmEvaluate extends Common
{
    const EVALUATE_DELETE = 0;//删除
    const EVALUATE_ING = 10;//评鉴中
    const EVALUATE_PASS = 20;//评鉴通过
    const EVALUATE_NO_PASS = 30;//评鉴不通过

    /**
     * 表名
     */
    public static function tableName()
    {
        return 'pd_firm_evaluate';
    }

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['firm_id'], 'required'],
            [['applicant_id', 'apply_type', 'check_person', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['evaluate_status', 'firm_create_date', 'predict_evaluate_date', 'check_date', 'create_at', 'update_at'], 'safe'],
            [['firm_register_money'], 'number'],
            [['evaluate_code', 'firm_staff_number', 'applicant_department', 'applicant_class', 'server_customer'], 'string', 'max' => 255],
        ];
    }

    /**
     * 属性标签
     */
    public function attributeLabels()
    {
        return [
            'evaluate_id' => '评鉴id',
            'evaluate_code' => '评鉴编码',
            'evaluate_status' => '评鉴状态',
            'firm_id' => '厂商id',
            'firm_staff_number' => '厂商员工人数',
            'firm_register_money' => '厂商注册资本',
            'firm_create_date' => '厂商建厂日期',
            'applicant_id' => '评鉴申请人',
            'applicant_department' => '评鉴申请人部门',
            'applicant_class' => '评鉴申请人课别',
            'server_customer' => '服务客户',
            'predict_evaluate_date' => '预评鉴日期',
            'apply_type' => '评鉴申请类型(1评鉴申请,2免评鉴申请)',
            'check_person' => '审核人',
            'check_date' => '审核日期',
            'company_id' => '公司id',
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
                "class"=>FormCodeBehavior::className(),
                "codeField"=>'evaluate_code',
                "formName"=>self::tableName()
            ]
        ];
    }

    /**
     * 厂商评鉴状态
     */
    public static function evaluateStatus()
    {
        return [
            self::EVALUATE_ING => '评鉴中',
            self::EVALUATE_PASS => '评鉴通过',
            self::EVALUATE_NO_PASS => '评鉴不通过',
        ];
    }

    /**
     * 关联厂商
     */
    public function getFirm()
    {
        return $this->hasOne(PdFirm::className(),['firm_id'=>'firm_id']);
    }
}
