<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2016/11/23
 * Time: 上午 11:22
 */
namespace app\modules\ptdt\controllers;

use yii;
use yii\helpers\Json;
use app\modules\ptdt\models\PdFirmEvaluate;
use app\modules\ptdt\models\PdFirmEvaluateApply;
use app\modules\ptdt\models\PdFirmEvaluateResult;
use app\modules\ptdt\models\PdFirmReport;
use app\modules\ptdt\models\PdFirmReportCompared;
use app\modules\ptdt\models\PdFirmReportProduct;
use app\modules\ptdt\models\PdNegotiation;
use app\modules\ptdt\models\PdNegotiationAnalysis;
use app\modules\ptdt\models\PdReception;
use app\modules\ptdt\models\PdSupplier;
use app\modules\ptdt\models\PdVisitPlan;
use app\modules\ptdt\models\PdVisitResume;
use app\controllers\BaseActiveController;
use app\modules\ptdt\models\PdFirm;
use app\modules\ptdt\models\search\PdFirmQuery;
use app\modules\ptdt\models\show\PdFirmShow;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsCategory;
use app\modules\hr\models\HrOrganization;
use app\modules\common\models\BsDistrict;

/**
 * 厂商API控制层
 */
class FirmController extends BaseActiveController{

    public $modelClass = 'app\modules\ptdt\models\PdFirm';

    /*
     * 厂商列表数据
     */
    public function actionIndex($companyId){
        $search = new PdFirmQuery();
        $dataProvider =  $search->search(Yii::$app->request->queryParams,$companyId);
//      dumpE($dataProvider);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }
    /**
     * @return string
     * 廠商詳情頁面
     */
    public function actionView($id){
        return $this->render('view', [
            'firm' => $this->getModel($id),
        ]);

    }

    /**
     * @return string
     * 廠商新增頁面
     */
    public function actionCreate()
    {
        $model = new PdFirm();
        if ($model->load($post=Yii::$app->request->post())) {
            if ($model->save()) {
                //弹出层添加厂商进入
                if(!empty($post['type'])){
                    return $this->success($model);
                }
                return $this->success();
            } else {
                return $this->error();
            }
        } else {
            return $this->error();
        }
    }

    /**
     * @return string
     * 廠商修改頁面
     */
    public function actionUpdate($id){
        $model = $this->getModel($id);
        if ($model->load($post = Yii::$app->request->post()) ){
            if ($model->save()){
                return $this->success();
            }else{
                return $this->error();
            }
        }else {
            $list[] = $model;
            return $list;
        }
    }

    private function getModel($id){
        $model = PdFirm::findOne($id);
        return $model;
    }
    /**
     * @param $id
     * @return yii\web\Response
     * 厂商删除操作
     */
    public function actionDelete($id){
        $model = $this->getModel($id);
        if($model){
            $model->firm_status = PdFirm::STATUS_DELETE;
            if ($result = $model->save()) {
                return $this->success();
            } else {
                return $this->error();
            }
        }else{
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
        $vid = PdVisitPlan::find()->where(['firm_id'=>$id])->andWhere(['!=','pvp_status',PdVisitPlan::STATUS_DELETE])->count();
        $rid = PdVisitResume::find()->where(['firm_id'=>$id])->andWhere(['!=','vih_status',PdVisitResume::VISIT_DELETE])->count();
        $nid = PdNegotiation::find()->where(['firm_id'=>$id])->andWhere(['!=','pdn_status',PdNegotiation::STATUS_DELETE])->count();
        $naid = PdNegotiationAnalysis::find()->where(['firm_id'=>$id])->count();
        $npid = PdNegotiationAnalysis::find()->where(['firm_id'=>$id])->count();
        $eid = PdFirmEvaluate::find()->where(['firm_id'=>$id])->andWhere(['!=','evaluate_status',PdFirmEvaluate::EVALUATE_DELETE])->count();
        $eaid = PdFirmEvaluateApply::find()->where(['firm_id'=>$id])->andWhere(['!=','apply_status',PdFirmEvaluateApply::STATUS_DELETE])->count();
        $erid = PdFirmEvaluateResult::find()->where(['firm_id'=>$id])->count();
        $rpid = PdFirmReport::find()->where(['firm_id'=>$id])->andWhere(['!=','report_status',PdFirmReport::REPORT_DELETE])->count();
        $rcid = PdFirmReportCompared::find()->where(['firm_id'=>$id])->count();
        $rppid = PdFirmReportProduct::find()->where(['firm_id'=>$id])->count();
        $rcpid = PdReception::find()->where(['firm_id'=>$id])->count();
        $cid = PdSupplier::find()->where(['firm_id'=>$id])->andWhere(['!=','supplier_status',PdSupplier::STATUS_DELETE])->count();
        if($vid != 0 || $rid != 0 || $nid != 0 || $rpid != 0 || $naid != 0 || $npid != 0 || $eid != 0 || $eaid != 0 || $erid != 0 || $rcid != 0 || $rppid != 0 || $rcpid != 0 || $cid != 0){
            return 'false';
        }else{
            return 'true';
        }
    }

    /**
     * 获取一级地址
     * @return array|yii\db\ActiveRecord[]
     */
    public function actionDistrictLevelOne(){
        return BsDistrict::getDisLeveOne();
    }
    /**
     * AJAX獲取地址子类
     * @param $id
     * @return string
     */
    public function actionGetDistrict($id)
    {
        return Json::encode(BsDistrict::getChildByParentId($id));
    }

    /**
     * 链接新增拜访计划
     * @param $id
     * @return string
     */

    public function actionAddVisitPlan($id){
        $model = $this->getModel($id);
        return Json::encode($model);
    }


    public function actionModel($id,$companyId)
    {
        $model = PdFirmShow::getOne($id,$companyId);
        return $model;

    }

    /**
     * 獲取廠商類型列表
     * @return mixed
     */
    public function actionFirmTypeList()
    {
        return BsPubdata::getData(BsPubdata::FIRM_TYPE);
    }

    /**
     * 獲取廠商來源列表
     * @return mixed
     */
    public function actionFirmSoucrceList()
    {
        return BsPubdata::getData(BsPubdata::FIRM_SOURCE);
    }

    /**
     * 獲取廠商地位列表
     * @return mixed
     */
    public function actionFirmPositionList()
    {
        return BsPubdata::getData(BsPubdata::FIRM_LEVEL);
    }

    /**
     * 獲取分級分類信息(一级分类)
     * @return array|yii\db\ActiveRecord[]
     */
    public function actionFirmCategory(){
//        return PdProductType::getLevelOne();
        return BsCategory::getLevelOne();
    }

    /**
     * 選擇廠商信息
     */
    public function actionSelectCom()
    {
        $searchModel = new PdFirmQuery();
        $dataProvider = $searchModel->searchQuery(Yii::$app->request->queryParams);
        return $this->renderAjax('select-com', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 獲取組織名稱在新增時顯示
     * @param $code
     * @return array|null|yii\db\ActiveRecord
     */
    public function actionOrgName($code){
        return HrOrganization::find()->andWhere(['organization_code'=>$code])->one();
    }
}