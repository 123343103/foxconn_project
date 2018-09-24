<?php
namespace app\modules\crm\models\search;

use app\classes\Trans;
use app\models\User;
use app\modules\common\models\BsCompany;
use app\modules\crm\models\CrmVisitPlan;
use app\modules\crm\models\show\CrmVisitPlanShow;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 拜访计划搜索
 */
class CrmVisitPlanSearch extends CrmVisitPlan
{
    public $planCode;
    public $customerName;
    public $visitPersonName;
    public $createPersonName;
    public $visitType;
    public $planStatus;
    public $planStartDate;
    public $planEndDate;
    public $createStartDate;
    public $createEndDate;

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['planCode', 'customerName', 'visitPersonName', 'createPersonName', 'visitType', 'planStatus', 'planStartDate', 'planEndDate', 'createStartDate', 'createEndDate'], 'safe'],
        ];
    }

    /**
     * 场景
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * 搜索拜访计划
     */
    public function search($params)
    {
        $staff = HrStaff::find()->select(['staff_code'])->where(['staff_id' => $params['staff_id']])->one();
        $userModel = User::findByUsername($params['user']);
        $planWhere = ['svp_staff_code' => $staff['staff_code']];
        if (!empty($userModel->is_supper) && $userModel->is_supper) {
            $planWhere = '';
        }
        if ($params['id'] == null) {
            $query = CrmVisitPlanShow::find()->where(['!=', 'svp_status', self::STATUS_DELETE])
                ->andWhere($planWhere)
                ->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
        } else {
            $query = CrmVisitPlanShow::find()->where(['!=', 'svp_status', self::STATUS_DELETE])
                ->andWhere($planWhere)
                ->andWhere(['and', ['cust_id' => $params['id']]])
                ->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
        }
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 5;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query
//        ]);
        //默认排序
        if (!Yii::$app->request->get('sort')) {
            $query->orderBy("create_at desc");
        }
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }

    /**
     * 搜索拜访计划
     */
//    public function searchPlan($params)
//    {
//        $trans = new Trans();
//        $staffInfo = HrStaff::getStaffById($params['staff']);
//        $userModel = User::findByUsername($params['user']);
//        $planWhere = ['svp_staff_code' => $staffInfo['staff_code']];
//        if (!empty($userModel->is_supper) && $userModel->is_supper) {
//            $planWhere = '';
//        }
//        $query = CrmVisitPlanShow::find()
//            ->where(['in', 'crm_visit_plan.company_id', BsCompany::getIdsArr($params['companyId'])])
//            ->andWhere($planWhere);
//        if (isset($params['rows'])) {
//            $pageSize = $params['rows'];
//        } else {
//            $pageSize = '';
//        }
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => [
//                'pageSize' => $pageSize,
//            ],
//        ]);
//        //默认排序
//        if (!Yii::$app->request->get('sort')) {
//            $query->orderBy('create_at DESC')->groupBy(['svp_id', 'svp_code']);
//        }
//        $this->load($params);
//        if (!$this->validate()) {
//            return $dataProvider;
//        }
//        $query->joinWith('crmCustomer');
//        $query->joinWith('visitPerson');
//        $query->joinWith('createPerson');
//        $query->joinWith('visitType');
////        $query->andFilterWhere([
////            'bs_pubdata.bsp_id' => $this->visitType,
////            'crm_visit_plan.svp_status' => $this->planStatus,
////        ]);
//        $query->andFilterWhere(['like', "crm_visit_plan.svp_code", $this->planCode])
////            ->andFilterWhere(["crm_visit_plan.svp_status" => $this->planStatus])
//            ->andFilterWhere(["crm_visit_plan.svp_type" => $this->visitType])
//            ->andFilterWhere(['or',
//                ['like', "crm_bs_customer_info.cust_sname", $this->customerName],
//                ['like', "crm_bs_customer_info.cust_sname", $trans->t2c($this->customerName)],
//                ['like', "crm_bs_customer_info.cust_sname", $trans->c2t($this->customerName)],
//            ])
//            ->andFilterWhere(['or',
//                ['like', "visitPersonAlias.staff_name", $this->visitPersonName],
//                ['like', "visitPersonAlias.staff_name", $trans->t2c($this->visitPersonName)],
//                ['like', "visitPersonAlias.staff_name", $trans->c2t($this->visitPersonName)],
//            ])
//            ->andFilterWhere(['or',
//                ['like', "hr_staff.staff_name", $this->createPersonName],
//                ['like', "hr_staff.staff_name", $trans->c2t($this->createPersonName)],
//                ['like', "hr_staff.staff_name", $trans->t2c($this->createPersonName)]
//            ]);
//        $nowtime = date('Y-m-d H:i:s', time());
//        if ($this->planStatus == 10) {
//            $query->andFilterWhere(["crm_visit_plan.svp_status" => 10]);
//            $query->andFilterWhere([">=", "crm_visit_plan.start", $nowtime]);
//
//        } else if ($this->planStatus == 40) {
//            $query->andFilterWhere(["crm_visit_plan.svp_status" => 10]);
//            $query->andFilterWhere(["<=", "crm_visit_plan.start", $nowtime]);
//            $query->andFilterWhere([">=", "crm_visit_plan.end", $nowtime]);
//        } else if ($this->planStatus == 20) {
//            $query->andFilterWhere(['or', ["crm_visit_plan.svp_status" => 20], ['and', ["crm_visit_plan.svp_status" => 10], ['<=', "crm_visit_plan.end", $nowtime]]]);
//        } else {
//            $query->andFilterWhere(["crm_visit_plan.svp_status" => $this->planStatus]);
//        }
//        if ($this->planStartDate && !$this->planEndDate) {
//            $query->andFilterWhere([">=", "crm_visit_plan.start", $this->planStartDate]);
//        }
//        if ($this->planEndDate && !$this->planStartDate) {
//            $query->andFilterWhere(["<=", "crm_visit_plan.start", date("Y-m-d H:i:s", strtotime($this->planEndDate . '+1 day'))]);
//        }
//        if ($this->planEndDate && $this->planStartDate) {
//            $query->andFilterWhere([">=", "crm_visit_plan.start", $this->planStartDate]);
//            $query->andFilterWhere(["<=", "crm_visit_plan.end", date("Y-m-d H:i:s", strtotime($this->planEndDate . '+1 day'))]);
//        }
//        if ($this->createStartDate && !$this->createEndDate) {
//            $query->andFilterWhere([">=", "crm_visit_plan.create_at", date("Y-m-d H:i:s", strtotime($this->createStartDate))]);
//        }
//        if ($this->createEndDate && !$this->createStartDate) {
//            $query->andFilterWhere(["<=", "crm_visit_plan.create_at", date("Y-m-d H:i:s", strtotime($this->createEndDate . '+1 day'))]);
//        }
//        if ($this->createEndDate && $this->createStartDate) {
//            $query->andFilterWhere(["between", "crm_visit_plan.create_at", date("Y-m-d H:i:s", strtotime($this->createStartDate)), date("Y-m-d H:i:s", strtotime($this->createEndDate . '+1 day'))]);
//        }
//        return $dataProvider;
//    }
}
