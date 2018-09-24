<?php
namespace app\modules\ptdt\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsDistrict;
use app\modules\hr\models\HrStaff;
use app\modules\common\models\BsCategory;
use app\modules\ptdt\models\PdAccompany;
use app\modules\ptdt\models\PdFirm;
use app\modules\ptdt\models\PdNegotiationChild;
use app\modules\ptdt\models\PdVisitResumeChild;
use app\modules\ptdt\models\show\PdFirmShow;
use app\modules\ptdt\models\show\PdVisitPlanShow;
use yii;
use app\modules\common\models\BsPubdata;
use app\controllers\BaseController;
use app\modules\ptdt\models\PdVisitPlan;
use app\modules\ptdt\models\search\PdVisitPlanSearch;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use app\modules\system\models\SystemLog;

/**
 * 廠商拜訪計畫控制器
 * User: F1678086
 *Date: 2016/9/21
 */
class VisitPlanController extends BaseActiveController{

    public $modelClass = 'app\modules\ptdt\models\PdVisitPlan';
    /*廠商拜訪計畫列表*/
    public function actionIndex($companyId){
        $searchModel = new PdVisitPlanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$companyId);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }


    /*廠商拜訪計畫詳情頁*/
    public function actionView($id){
        $model = $this->getModel($id);
        $creator = $model->creatorStaff;
        $data = $model->staff;
        return $this->render('view',['model'=>$this->getModel($id),'data'=>$data,'creator'=>$creator]);
    }


    /*新增廠商拜訪計畫*/
    public function actionAdd(){
        $model = new PdVisitPlan();
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model->load(Yii::$app->request->post());
            $plantime = $post['PdVisitPlan']['plan_starttime'].'-'.$post['PdVisitPlan']['plan_endtime'];
            $model->plan_time = $plantime;
            if(!$model->save()){
                throw  new \Exception("新增失败");
            };
            $pid = $model->pvp_planID;
            $array = array_filter($post['vacc']);
            foreach ($array as $item) {
                $accompany = new PdAccompany();
                $accompany->vacc_type = '1';
                $accompany->h_id = $pid;
                $accompany->staff_code = strtoupper($item);
                if(!$accompany->save()){
                    throw  new \Exception("新增失败");
                };
            }
            $firm = PdFirm::getFirmById($post['PdVisitPlan']['firm_id']);
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        $arr = array('id'=>$pid,'msg'=>'厂商拜访计划:新增厂商'.$firm["firm_sname"].'的拜访计划');
        return $this->success('',$arr);
    }


    /*修改廠商拜訪計畫*/
    public function actionEdit($id){
        $transaction = Yii::$app->db->beginTransaction();
        $model = $this->getModel($id);
        $post = Yii::$app->request->post();
        try{
            $model->load(Yii::$app->request->post());
            $plantime = $post['PdVisitPlan']['plan_starttime'].'-'.$post['PdVisitPlan']['plan_endtime'];
            $model->plan_time = $plantime;
            $firm = PdFirm::getFirmById($post['PdVisitPlan']['firm_id']);
            if(!$model->save()){
                throw  new \Exception("修改失败");
            };
            $count = PdAccompany::find()->where(['h_id' => $id,'vacc_type'=>1])->count();
            if (PdAccompany::deleteAll(['h_id' => $id,'vacc_type'=>1]) < $count) {
                throw  new \Exception("删除失败");
            };
            $array = array_filter($post['vacc']);
            foreach ($array as $item) {
                $AccompanyModel = new PdAccompany();
                $AccompanyModel->staff_code = strtoupper($item);
                $AccompanyModel->h_id = $id;
                if(!$AccompanyModel->save()){
                    throw  new \Exception("添加失败");
                }
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        $arr = array('id'=>$id,'msg'=>'厂商拜访计划:修改厂商'.$firm["firm_sname"].'的拜访计划');
        return $this->success('',$arr);
    }
    /*刪除選中計畫列表*/
    public function actionDelete($id)
    {
        $model = $this->getModel($id);
        $firm = PdFirm::getFirmById($model['firm_id']);
        $model->pvp_status = PdVisitPlan::STATUS_DELETE;
        if ($result = $model->save()) {
            return $this->success('','厂商拜访计划:删除厂商'.$firm["firm_sname"].'的拜访计划');
        } else {
            return $this->error();
        }
    }
    /**
     * @param $id
     * @return mixed
     * 查询该厂商在拜访计划  拜访履历 谈判 呈报中是否引用
     * F1678089 -- 龚浩晋
     */
    public function actionDeleteCount($id){
        $nid = PdNegotiationChild::find()->where(['visit_planID'=>$id])->andWhere(['!=','pdnc_status',PdVisitResumeChild::STATUS_DELETE])->count();
        $rid = PdVisitResumeChild::find()->where(['visit_planID'=>$id])->andWhere(['!=','vil_status',PdVisitResumeChild::STATUS_DELETE])->count();
        if($nid != 0 || $rid != 0){
            return 'false';
        }else{
            return 'true';
        }
    }

    /**
     * 链接新增拜访履历
     * @param $id
     * @return string
     */
    public function actionAddResume($id){
        $model = $this->getModel($id);
        return Json::encode($model->firm_id);
    }
    /**
     * AJAX獲取拜訪人ID
     * @param $code
     * @return string
     */
    public function actionGetVisitManager($code)
    {
        return HrStaff::getStaffByIdCode($code);
    }


    public function actionSelectCom(){
        $searchModel = new PdVisitPlanSearch();
        $dataProvider = $searchModel->searchQuery(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }
    public function actionFirmInfo($id){
        $model = PdFirmShow::getFirmById($id);
        return $model;
    }

    /**
     * 獲取模型
     * @param $id
     * @return null|static
     * @throws \Exception
     */
    public function actionModels($id)
    {
        $model = PdVisitPlanShow::getVisitPlanOne($id);
        return $model;

    }

    public  function getModel($id)
    {
        if ($model = PdVisitPlan::findOne($id)) {
            return $model;
        } else {
            throw new yii\web\NotFoundHttpException("頁面未找到");
        }
    }

    public function actionDownList(){
        $downList['planType'] = BsPubdata::getList(BsPubdata::PD_PLAN_TYPE);
        $downList['visitPurpose'] = BsPubdata::getList(BsPubdata::PD_VISIT_PUR);
        $downList['firmSource'] = BsPubdata::getList(BsPubdata::FIRM_SOURCE);
        $downList['firmLevel'] = BsPubdata::getList(BsPubdata::FIRM_LEVEL);
        $downList['firmType'] = BsPubdata::getList(BsPubdata::FIRM_TYPE);
        $downList['country'] = BsDistrict::getDisLeveOne();//所在国家
        $downList['category'] = BsCategory::getLevelOne();;//分级分类
        return $downList;
    }

    /**
     * @return string
     * 验证厂商名称唯一性
     */
    public function actionFirmSname(){
        $post=Yii::$app->request->post();
        if(!empty($post['name'])){
            $model = PdFirm::find()->where(['firm_sname'=>$post['name']])->one();
            if(!empty($model)){
                return 'false';
            }else{
                return 'true';
            }
        }
    }
}