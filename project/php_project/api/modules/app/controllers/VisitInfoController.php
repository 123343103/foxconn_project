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
use app\modules\crm\models\CrmVisitRecord;
use app\modules\crm\models\CrmVisitRecordChild;
use app\modules\app\models\search\VisitInfoSearch;
use app\modules\app\models\show\CustomerAppShow;
use app\modules\app\models\show\VisitInfoChildShow;
use app\modules\app\models\show\VisitInfoShow;
use app\modules\app\models\show\VisitPlanShow;
use app\modules\hr\models\HrStaff;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmEmployee;
use Yii;
use app\modules\crm\models\CrmVisitPlan;
use app\modules\crm\models\CrmReception;
use yii\db\Exception;
use app\modules\crm\models\CrmAccompany;

class VisitInfoController extends AppBaseController
{
    public $modelClass = 'app\modules\crm\models\CrmVisitRecord';

    /**
     * 拜访记录主表列表
     */
    public function actionIndex()
    {
        $model = new VisitInfoSearch();
        $dataProvider = $model->searchChild(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 临时拜访记录主列表
     */
    public function actionIndexTemp()
    {
        $model = new VisitInfoSearch();
        $dataProvider = $model->searchTemp(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->count;
        return $list;
    }

    /**
     * 获取记录 id为客户id
     */
    public function actionLoadRecord()
    {
        $searchModel = new VisitInfoChildShow();
        $dataProvider = $searchModel->AppSearch(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    //新增拜访记录
    public function actionCreateInfo()
    {
        $child = new CrmVisitRecordChild();
        $personinch = new CrmCustPersoninch();
        $post = Yii::$app->request->post();
        $crmCustomerInfo = CrmCustomerInfo::findOne(intval($post['CrmVisitRecord']['cust_id']));

        $staff = CrmEmployee::findOne(['staff_code' => $post['CrmVisitRecordChild']['sil_staff_code']]);
        $id = $post["CrmVisitRecordChild"]['create_by'];

        $model = CrmVisitRecord::find()->where(['cust_id' => intval($post['CrmVisitRecord']['cust_id'])])->andWhere(['<>', 'sih_status', CrmVisitRecord::STATUS_DELETE])->one();
        $model = !empty($model->sih_id) ? $model : new CrmVisitRecord();
        $transaction = Yii::$app->db->beginTransaction();


        $start = $post['CrmVisitRecordChild']['start'];
        $end = $post['CrmVisitRecordChild']['end'];
        try {
            //判断时间内是否有拜访记录
            $childCount =
                CrmVisitRecordChild::find()
                    ->andWhere(['sil_staff_code' => $post['CrmVisitRecordChild']['sil_staff_code']])
                    ->andWhere(['=', 'sil_status', CrmVisitRecordChild::STATUS_DEFAULT])
                    ->andWhere(['or', ['and', ['<=', 'start', $start], ['>=', 'end', $end]], ['between', 'start', $start, $end], ['between', 'end', $start, $end]])->count();
            if ($childCount > 0) {
                throw new Exception("您该时间段已有记录!");
            }
            /*认领信息
            *如果添加人是客户经理人默认认领
            */
            if (($staff['isrule'] == 1) && $crmCustomerInfo && $crmCustomerInfo->personinch_status == 0) {

                $personinch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;
                $personinch->cust_id = intval($post['CrmVisitRecord']['cust_id']);
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

            $model->load($post);
            $model->sih_status = "10";
            $model->save();
            $sihId = $model->sih_id;
            $child->load($post);
            $child->sih_id = $sihId;
            $child->save();
            //接待人表
            if (!empty($post['reception'])) {
                foreach ($post['reception'] as $val) {
                    if (!empty($val['CrmReception']['rece_sname'])) {
                        $receptionModel = new CrmReception();
                        $receptionModel->cust_id = $model->cust_id;
                        $receptionModel->h_id = $model->sih_id;
                        $receptionModel->l_id = $child->sil_id;
                        if (!$receptionModel->load($val) || !$receptionModel->save()) {
                            throw new Exception(json_encode($receptionModel->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
            }
            //陪同人表
            if (!empty($post['accompany'])) {
                foreach ($post['accompany'] as $val) {
                    if (!empty($val['CrmAccompany']['acc_id']) && !empty($val['CrmAccompany']['acc_mobile'])) {
                        $accompanyModel = new CrmAccompany();
                        $accompanyModel->type = 2;
                        $accompanyModel->pid = $child->sil_id;
                        if (!$accompanyModel->load($val) || !$accompanyModel->save()) {
                            throw new Exception(json_encode($accompanyModel->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
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
     * 选择拜访计划
     */
    public function actionGetPlan($id)
    {
        return VisitPlanShow::findOne($id);
    }

    /**
     * 通过ID获取记录信息
     */
    public function actionGetOneInfo($id)
    {
        $data = VisitInfoChildShow::find()->where(['sil_id' => $id])->one();
        return $data;
    }

    /**
     * 更新
     */
    public function actionEditInfo($id)
    {
        $child = CrmVisitRecordChild::find()->where(['sil_id' => $id])->andWhere(['<>', 'sil_status', CrmVisitRecordChild::STATUS_DELETE])->one();
        $post = Yii::$app->request->post();
        $model = CrmVisitRecord::find()->where(['cust_id' => intval($post['CrmVisitRecord']['cust_id'])])->andWhere(['<>', 'sih_status', CrmVisitRecord::STATUS_DELETE])->one();
        if (empty($model)) {
            $model = new CrmVisitRecord();
            $model->create_by = $post['staff'];
        } else {
            $model->update_by = $post['staff'];
        }

        $old_model = CrmVisitRecord::find()->where(['sih_id' => $child->sih_id])->andWhere(['<>', 'sih_status', CrmVisitRecord::STATUS_DELETE])->one();
        $old_child = count($old_model->visitInfoChild);
        $transaction = Yii::$app->db->beginTransaction();

        $start = $post['CrmVisitRecordChild']['start'];
        $end = $post['CrmVisitRecordChild']['end'];

        try {
            //判断时间内是否有拜访记录
            $childCount =
                CrmVisitRecordChild::find()
                    ->where(['!=', 'sil_id', $id])
                    ->andWhere(['sil_staff_code' => $child->sil_staff_code])
                    ->andWhere(['=', 'sil_status', CrmVisitRecordChild::STATUS_DEFAULT])
                    ->andWhere(['or', ['and', ['<=', 'start', $start], ['>=', 'end', $end]], ['between', 'start', $start, $end], ['between', 'end', $start, $end]])->count();
            if ($childCount > 0) {
                throw new Exception("您该时间段已有记录!");
            }

            if ($model->cust_id == $old_model->cust_id) {
                $model->load($post);
                $model->save();
            } else {
                if ($old_child == 1) {
                    $old_model->delete();
                }
                $model->load($post);
                $model->save();
            }
            $sihId = $model->sih_id;
            $child->load($post);
            $child->sih_id = $sihId;
            $child->save();
            //接待人表
            CrmReception::deleteAll(['l_id' => $child->sil_id]);
            if (!empty($post['reception'])) {
                foreach ($post['reception'] as $val) {
                    if (!empty($val['CrmReception']['rece_sname'])) {
                        $receptionModel = new CrmReception();
                        $receptionModel->cust_id = $model->cust_id;
                        $receptionModel->h_id = $model->sih_id;
                        $receptionModel->l_id = $child->sil_id;
                        if (!$receptionModel->load($val) || !$receptionModel->save()) {
                            throw new Exception(json_encode($receptionModel->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
            }
            //陪同人表
            CrmAccompany::deleteAll(['type' => 2, 'pid' => $id]);
            if (!empty($post['accompany'])) {
                foreach ($post['accompany'] as $val) {
                    if (!empty($val['CrmAccompany']['acc_id']) && !empty($val['CrmAccompany']['acc_mobile'])) {
                        $accompanyModel = new CrmAccompany();
                        $accompanyModel->type = 2;
                        $accompanyModel->pid = $child->sil_id;
                        if (!$accompanyModel->load($val) || !$accompanyModel->save()) {
                            throw new Exception(json_encode($accompanyModel->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 查看拜访记录
     */
    public function actionView($id)
    {
        $visitInfoChild = VisitInfoChildShow::findOne($id);
        if (!empty($visitInfoChild)) {
            $visitInfoMain = VisitInfoShow::findOne($visitInfoChild->sih_id);
        }
        return [
            'visitInfoChild' => $visitInfoChild,
            'visitInfoMain' => !empty($visitInfoMain) ? $visitInfoMain : null,
        ];
    }

    /**
     * 获取修改数据
     */
    public function actionEdit($id)
    {
        $recordChildData = VisitInfoChildShow::findOne($id);
        if (!empty($recordChildData)) {
            $recordMainData = $recordChildData->visitInfo;
            if (!empty($recordMainData)) {
                $customerData = CustomerAppShow::find()->where(['cust_id' => $recordMainData->cust_id])->one();
                $receptionData = CrmReception::find()->where(['l_id' => $id])->all();
                $accompanyData = $this->getAccompany($id);
                if (!empty($customerData)) {
                    return [
                        'customerData' => $customerData,
                        'recordChildData' => $recordChildData,
                        'receptionData' => $receptionData,
                        'accompanyData' => $accompanyData,
                    ];
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    //获取陪同人
    private function getAccompany($id)
    {
        return Yii::$app->db->createCommand("select b.staff_code,b.staff_id,b.staff_name,c.title_name,a.acc_mobile from erp.crm_accompany a left join erp.hr_staff b on b.staff_id = a.acc_id left join erp.hr_staff_title c on c.title_id = b.position where a.type = 2 and a.pid = {$id}")->queryAll();
    }

    /**
     * 获取新增数据
     */
    public function actionGetCustomerData($id)
    {
        return CustomerAppShow::findOne($id);
    }

    /**
     * 获取新增数据
     */
    public function actionGetCustomer($id)
    {
        return CustomerAppShow::findOne($id);
    }

    /**
     * 删除拜访记录主表
     */
    public function actionDeleteMain($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $visitRecordMain = CrmVisitRecord::findOne($id);
            if (!empty($visitRecordMain)) {
                $visitRecordMain->sih_status = CrmVisitRecord::STATUS_DELETE;
                if (!$visitRecordMain->save()) {
                    throw  new \Exception("删除拜访记录主表失败");
                }
                $visitRecordChild = CrmVisitRecordChild::find()->where(['sih_id' => $visitRecordMain->sih_id])->all();
                if (!empty($visitRecordChild)) {
                    foreach ($visitRecordChild as $val) {
                        $val->sil_status = CrmVisitRecordChild::STATUS_DELETE;
                        if (!$val->save()) {
                            throw  new \Exception("删除拜访记录子表失败");
                        }
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 删除拜访记录子表
     */
    public function actionDeleteChild($id)
    {
        $visitRecordChild = CrmVisitRecordChild::findOne($id);
        if (!empty($visitRecordChild)) {
            $visitRecordChild->sil_status = CrmVisitRecordChild::STATUS_DELETE;
            if ($visitRecordChild->save()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
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
     * 获取拜访记录列表页数据
     */
    public function actionIndexData()
    {
        return [
            //获取客户类型
            'customerType' => BsPubdata::getData(BsPubdata::CRM_CUSTOMER_TYPE),
        ];
    }

    /**
     * 获取拜访人信息
     */
    public function actionVisitPerson($id)
    {
        return HrStaff::find()->where(['staff_id' => $id])->select('staff_id,staff_name,staff_code')->one();
    }

    /**
     * 获取拜访类型
     */
    public function actionVisitType()
    {
        return BsPubdata::getData(BsPubdata::CRM_VISIT_TYPE);
    }

    /*下拉菜单*/
    public function actionDownList()
    {
        $downList['visitType'] = BsPubdata::getList(BsPubdata::CRM_VISIT_TYPE);  //经营类型
        return $downList;
    }
}