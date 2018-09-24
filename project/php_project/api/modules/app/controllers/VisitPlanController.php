<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2017/2/8
 * Time: 下午 02:51
 */

namespace app\modules\app\controllers;

use app\controllers\AppBaseController;
use app\modules\common\models\BsPubdata;
use app\modules\app\models\show\VisitPlanShow;
use Yii;
use app\modules\crm\models\CrmVisitPlan;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmEmployee;
use yii\web\NotFoundHttpException;
use app\modules\hr\models\HrStaff;

class VisitPlanController extends AppBaseController
{
    public $modelClass = 'app\modules\crm\models\CrmVisitPlan';

    /**
     * 计划列表
     */
    public function actionIndex($companyId)
    {
        $model = new VisitPlanShow();
        $dataProvider = $model->AppSearch(Yii::$app->request->queryParams, $companyId);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    //新增拜访计划
    public function actionCreateVisit()
    {
        $model = new CrmVisitPlan();
        $personinch = new CrmCustPersoninch();
        $post = Yii::$app->request->post();
        $crmCustomerInfo = CrmCustomerInfo::findOne(intval($post['CrmVisitPlan']['cust_id']));

        $staff = CrmEmployee::findOne(['staff_code' => $post['CrmVisitPlan']['svp_staff_code']]);
        $id = $post["CrmVisitPlan"]['create_by'];

        $model->load($post);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            /*认领信息
            *如果添加人是客户经理人默认认领
            */
            if (($staff['isrule'] == 1) && $crmCustomerInfo && $crmCustomerInfo->personinch_status == 0) {

                $personinch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;
                $personinch->cust_id = intval($post['CrmVisitPlan']['cust_id']);
                $personinch->ccpich_status = CrmCustPersoninch::STATUS_DEFAULT;
                $personinch->ccpich_date = date("Y-m-d H:i:s", time());
                $personinch->csarea_id = $staff["sale_area"];
                $personinch->sts_id = $staff["sts_id"];
                $personinch->ccpich_personid = $id;
                $personinch->ccpich_personid2 = $id;

                if (!$personinch->save()) {
                    throw new \Exception($personinch->errors);
                } else {
                    $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_YES;
                }
            }
            if (!$crmCustomerInfo->save()) {
                throw new \Exception("新增认领信息失败");
            }
            $result = $model->save();
            if (!$result) {
                throw new \Exception("新增拜访计划失败");
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 选择拜访计划
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionSelectPlan()
    {
        return VisitPlanShow::find()->all();
    }

    /**
     * 通过ID获取计划信息
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function actionGetOnePlan($id)
    {
        $data = VisitPlanShow::find()->where(['svp_id' => $id])->one();
        return $data;
    }

    /**
     * 更新
     */
    public function actionEditPlan($id)
    {
        $model = CrmVisitPlan::findOne($id);
        $post = Yii::$app->request->post();
        $model->load($post);
        $result = $model->save();
        if (!$result) {
            return $this->error();
        }
        return $this->success();
    }

    /**
     * 视图
     */
    public function actionView($id)
    {
        return VisitPlanShow::findOne($id);
    }

    /**
     * Creates a new CrmVisitPlan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CrmVisitPlan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->svp_planID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CrmVisitPlan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->svp_planID]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
    }

    /**
     * 判断是否可以删除
     */
    public function actionDeleteJudge($id)
    {
        $model = CrmVisitPlan::findOne($id);
        if (!empty($model)) {
            if ($model->svp_status == CrmVisitPlan::VISIT_PLAN_COMPLETE) {
                return 20;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 删除拜访计划
     */
    public function actionDeletePlan($id)
    {
        $model = CrmVisitPlan::findOne($id);
        if (!empty($model)) {
            $model->svp_status = CrmVisitPlan::STATUS_DELETE;
            if ($model->save()) {
                return true;
            } else {
                throw new NotFoundHttpException('拜访计划删除失败');
            }
        } else {
            return false;
        }
    }

    /**
     * 获取计划列表数据
     */
    public function actionIndexData()
    {
        //获取拜访类型
        $indexData['visitType'] = BsPubdata::getData(BsPubdata::CRM_VISIT_TYPE);
        //获取计划状态
        $indexData['planStatus'][CrmVisitPlan::STATUS_DEFAULT] = '待实施';
        $indexData['planStatus'][CrmVisitPlan::VISIT_PLAN_COMPLETE] = '已实施';
        return $indexData;
    }

    /*下拉菜单*/
    public function actionDownList()
    {
        $downList['visitType'] = BsPubdata::getList(BsPubdata::CRM_VISIT_TYPE);  //经营类型
        return $downList;
    }
}