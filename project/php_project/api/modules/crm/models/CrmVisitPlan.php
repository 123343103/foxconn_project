<?php

namespace app\modules\crm\models;

use app\behaviors\FormCodeBehavior;
use app\controllers\BaseController;
use app\models\Common;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\show\CrmVisitPlanCountShow;
use app\modules\hr\models\HrStaff;
use Yii;
use app\modules\common\models\BsCompany;
use app\modules\crm\models\show\CrmVisitPlanShow;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "crm_visit_plan".
 *
 * @property string $svp_id
 * @property string $bus_code
 * @property string $svp_type
 * @property string $svp_code
 * @property string $cust_id
 * @property string $title
 * @property string $start
 * @property string $end
 * @property integer $editable
 * @property string $color
 * @property string $type
 * @property string $spend_time
 * @property string $plan_place
 * @property string $svp_contact_man
 * @property string $svp_contact_position
 * @property string $svp_contact_tel
 * @property string $svp_contact_mobile
 * @property string $svp_content
 * @property string $svp_staff_code
 * @property string $purpose
 * @property string $purpose_write
 * @property integer $svp_status
 * @property string $svp_senddate
 * @property string $svp_verifydate
 * @property string $svp_verifyter
 * @property string $svp_remark
 * @property string $create_at
 * @property string $create_by
 * @property integer $company_id
 * @property integer $data_from
 * @property string $cancl_rs
 */
class CrmVisitPlan extends Common
{
    /**
     * @inheritdoc
     */
    const STATUS_DELETE = 0;
    const STATUS_DEFAULT = 10;//待实施
    const VISIT_PLAN_COMPLETE = 20;//已实施
    const STATUS_CANCEL = 30;//已取消
    const STATUS_BUSY = 40;//实施中
    const STATUS_STOP = 50;//已终止
    const STATUS_END = 60;//已结束
    public $countPlan;

    public static function tableName()
    {
        return 'crm_visit_plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svp_id', 'cust_id', 'svp_verifyter', 'start', 'end','cancl_rs' ,'svp_verifydate', 'create_at', 'create_by', 'company_id', 'svp_remark', 'bus_code', 'svp_code', 'svp_contact_man', 'svp_content', 'svp_type', 'svp_contact_position', 'purpose', 'plan_place', 'purpose_write', 'svp_contact_tel', 'svp_contact_mobile', 'spend_time', 'title', 'data_from'], 'safe'],
            [['svp_status', 'type'], 'default', 'value' => self::STATUS_DEFAULT],
            [['color'], 'default', 'value' => '#FFFF66'],
            ['svp_staff_code', 'filter', 'filter' => function ($value) {
                return strtoupper($value);
            }]

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'svp_id' => 'Svp Plan ID',
            'bus_code' => '关係业务对表',
            'svp_type' => '关联公共数据字典表',
            'svp_plancode' => '按编码规则生成',
            'cust_id' => '关联客?信息表',
//            'plan_date' => 'Plan Date',
            'plan_time' => 'Plan Time',
            'plan_place' => 'Plan Place',
            'svp_contact_man' => 'Svp Contact Man',
            'svp_contact_position' => 'Svp Contact Position',
            'svp_contact_tel' => 'Svp Contact Tel',
            'svp_contact_mobile' => 'Svp Contact Mobile',
            'svp_staff_code' => 'Svp Staff ID',
            'purpose' => 'Purpose',
            'purpose_write' => 'Purpose Write',
            'svp_status' => 'Svp Status',
            'svp_senddate' => 'Svp Senddate',
            'svp_verifydate' => 'Svp Verifydate',
            'svp_verifyter' => 'Svp Verifyter',
        ];
    }

    public function behaviors()
    {
        return [
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //时间字段自动赋值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],  //插入
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['update_at']  //更新
                ],
                'value' => function () {
                    return date("Y-m-d H:i:s", time());       //赋值的值来源,如不同複写
                }
            ],
            'formCode' => [
                'class' => FormCodeBehavior::className(),     //计画编号自动赋值
                'codeField' => 'svp_code',                    //字段名
                'formName' => 'crm_visit_plan',               //表名
            ]
        ];
    }

    //关联客户信息
    public function getCrmCustomer()
    {
        return $this->hasOne(CrmCustomerInfo::className(), ['cust_id' => 'cust_id']);
    }

    /**
     * 获取拜访人
     */
    public function getVisitPerson()
    {
        return $this->hasOne(HrStaff::className(), ['staff_code' => 'svp_staff_code'])->from(['visitPersonAlias' => HrStaff::tableName()]);
    }

    /**
     * 获取创建人
     */
    public function getCreatePerson()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'create_by']);
    }

    /**
     * 获取拜访类型
     */
    public function getVisitType()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'svp_type']);
    }

    /**
     * 获取拜访统计信息
     */
    public function PlanCountSearch($start, $end, $staffCode, $isSupper)
    {
        if ($isSupper == 1) {
            $query = CrmVisitPlanCountShow::find()->select(['start', "count(*) countPlan"])->where(['!=', 'svp_status', self::STATUS_DELETE])
                ->andFilterWhere(['between', 'start', $start, $end])->groupBy(['DATE_FORMAT(start,\'%Y %m %d\')']);
        } else {
            $query = CrmVisitPlanCountShow::find()->select(['start', "count(*) countPlan"])->where(['!=', 'svp_status', self::STATUS_DELETE])
                ->andFilterWhere(['=', 'svp_staff_code', $staffCode])
                ->andFilterWhere(['between', 'start', $start, $end])->groupBy(['DATE_FORMAT(start,\'%Y %m %d\')']);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
//        return $query;
    }
}
