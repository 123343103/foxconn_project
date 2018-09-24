<?php
namespace app\modules\app\controllers;

use yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use app\modules\ptdt\models\PdFirm;
use app\modules\hr\models\HrStaff;
use app\controllers\AppBaseController;
use app\modules\ptdt\models\PdAccompany;
use app\modules\app\models\show\FirmVisitPlanShow;
use app\modules\ptdt\models\show\PdVisitPlanShow;
use app\modules\ptdt\models\PdVisitPlan;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsCategory;

/**
 * 厂商拜访计划控制器  V1.0
 *
 * @author F1676269 By 2017-06-21
 * 
 */
class FirmVisitPlanController extends AppBaseController 
{
	public $modelClass = 'app\modules\ptdt\models\PdVisitPlan';
	
	/**
	 * 获取拜访计划列表
	 */
	public function actionList($companyId)
	{
		$searchModel = new FirmVisitPlanShow();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$companyId);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
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
	
	/**
	 * 新增廠商拜訪計畫
	 */
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
			$transaction->commit();
			return $this->success('APP厂商拜访计划:新增厂商'.$firm["firm_sname"].'的拜访计划');
			
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
	 * 修改廠商拜訪計畫
	 * 
	 **/
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
                $AccompanyModel->staff_code = str_replace('"', '', strtoupper($item));
                $AccompanyModel->h_id = $id;
                if(!$AccompanyModel->save()){
                    throw  new \Exception("添加失败");
                }
            }
			$transaction->commit();
        	return $this->success('APP厂商拜访计划:修改厂商'.$firm["firm_sname"].'的拜访计划');
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
       
    }
	
	/**
	 * 获取下拉列表数据
	 */
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
     * 獲取拜訪人信息
     * @param $code
     * @return string
     */
    public function actionGetVisitManager($code)
    {
    	return HrStaff::getStaffByIdCode($code);
    }
	
	public  function getModel($id)
    {
        if ($model = PdVisitPlan::findOne($id)) {
            return $model;
        } else {
            throw new yii\web\NotFoundHttpException("頁面未找到");
        }
    }
}
?>