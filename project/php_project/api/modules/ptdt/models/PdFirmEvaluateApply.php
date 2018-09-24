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
 * This is the model class for table "pd_firm_evaluate_apply".
 * @property integer $apply_id
 * @property string $firm_id
 * @property integer $applicant_id
 * @property string $apply_code
 * @property integer $apply_type
 * @property integer $apply_status
 * @property string $apply_date
 * @property string $applicant_class
 * @property string $server_customer
 * @property string $trade_goods
 * @property integer $goods_position
 * @property string $predict_evaluate_date
 * @property integer $avoid_evaluate_condition
 * @property string $apply_reason
 * @property string $department_manager
 * @property string $evaluate_annex
 * @property string $evaluate_annex_name
 * @property string $condition_annex
 * @property string $condition_annex_name
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 */
/**
 * 厂商评鉴申请模型
 */
class PdFirmEvaluateApply extends Common
{
    //评鉴申请状态
    const STATUS_DELETE = 0;//删除评鉴申请
    const CHECK_WAIT = 10;//待审核
    const CHECK_ING = 20;//审核中
    const CHECK_COMPLETE = 30;//审核完成
    const CHECK_REJECT = 40;//被驳回

    /**
     * 表名
     */
    public static function tableName()
    {
        return 'pd_firm_evaluate_apply';
    }

    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            [['firm_id', 'applicant_id', 'apply_type', 'apply_status', 'goods_position', 'avoid_evaluate_condition', 'create_by', 'update_by'], 'integer'],
            [['company_id','apply_date', 'predict_evaluate_date', 'create_at', 'update_at'], 'safe'],
            [['apply_code', 'applicant_class', 'server_customer', 'trade_goods', 'apply_reason', 'department_manager', 'evaluate_annex', 'evaluate_annex_name', 'condition_annex', 'condition_annex_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * 属性标签
     */
    public function attributeLabels()
    {
        return [
            'apply_id' => '主键ID',
            'firm_id' => '廠商ID',
            'applicant_id' => '申請人ID',
            'apply_code' => '编码',
            'apply_type' => '1評鑒申請,2免評鑒評鑑',
            'apply_status' => '状态',
            'apply_date' => '申請日期',
            'applicant_class' => '课别',
            'server_customer' => '服务客户',
            'trade_goods' => '交易商品',
            'goods_position' => '商品定位',
            'predict_evaluate_date' => '预评鉴日期',
            'avoid_evaluate_condition' => '免评鉴条件',
            'apply_reason' => '申請理由/原因说明',
            'department_manager' => '部门主管',
            'evaluate_annex' => '评鉴申请附件/免评鉴申请附件',
            'evaluate_annex_name' => '评鉴申请附件名/免评鉴申请附件名',
            'condition_annex' => '免评鉴条件附件',
            'condition_annex_name' => '免评鉴条件附件名',
            'create_by' => '创建人',
            'create_at' => '创建时间',
            'update_by' => '更新人',
            'update_at' => '更新时间',
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
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at', 'apply_date'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at'],
                ],
            ],
            'formCode' => [
                'class' => FormCodeBehavior::className(),
                'codeField' => 'apply_code',
                'formName' => self::tableName(),
            ],
        ];
    }

    /**
     * 关联厂商
     */
    public function getFirm()
    {
        return $this->hasOne(PdFirm::className(), ['firm_id'=>'firm_id']);
    }

    /**
     * 获取厂商类型
     */
    public function getFirmType(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>"firm_type"])->via('firm');
    }

    /**
     * 获取评鉴申请类型
     */
    public function getEvaluateApplyType()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id'=>'apply_type']);
    }

    /**
     * 获取厂商来源
     */
//    public function getFirmSource(){
//        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'firm_source'])->via('firm');
//    }
//
//    /**
//     * 获取厂商来源
//     */
//    public function getFirmPosition(){
//        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'firm_position'])->via('firm');
//    }
//

//
//    /**
//     * 关联厂商呈报主表
//     */
//    public function getFirmReport()
//    {
//        return $this->hasOne(PdFirmReport::className(), ['firm_id'=>'firm_id']);
//    }
//
//    /**
//     * 关联厂商呈报子表
//     */
//    public function getFirmReportChild()
//    {
//        return $this->hasMany(PdFirmReportChild::className(), ['pfr_id'=>'pfr_id'])->via('firmReport');
//    }
//
//    /**
//     * 关联厂商呈报商品表
//     */
//    public function getFirmReportProduct()
//    {
//        return $this->hasMany(PdFirmReportProduct::className(), ['firm_id'=>'firm_id']);
//    }
//
//    /**
//     * 关联产品
//     */
//    public function getProductType()
//    {
//        return $this->hasMany(PdProductType::className(), ['type_id'=>'product_type_1'])->via('firmReportProduct');
//    }














    /**
     * 评鉴申请状态
     */
    public static function evaluateApplyStatus()
    {
        return [
            self::CHECK_WAIT => '待审核',
            self::CHECK_ING => '审核中',
            self::CHECK_COMPLETE => '审核完成',
            self::CHECK_REJECT => '被驳回',
        ];
    }
}
