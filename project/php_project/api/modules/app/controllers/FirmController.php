<?php
namespace app\modules\app\controllers;

use yii;
use app\controllers\AppBaseController;
use app\modules\app\models\show\FirmAppShow;
use yii\helpers\Json;
use app\modules\common\models\BsCategory;
use yii\web\NotFoundHttpException;
use app\modules\ptdt\models\show\PdFirmShow;
use app\modules\ptdt\models\PdFirm;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsDistrict;
use app\modules\hr\models\HrOrganization;

/**
 * 厂商信息控制器
 * 
 * @author F1676269 By 2017-06-01
 *
 */
class FirmController extends AppBaseController{
    
    public $modelClass = 'app\modules\ptdt\models\PdFirm';
    
    /**
     * 厂商列表数据
     */
    public function actionList($companyId){
        $search = new FirmAppShow();
        $dataProvider =  $search->search(Yii::$app->request->queryParams,$companyId);
        //dumpE($dataProvider);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }
    
    /**
     * 厂商详情
     */
    public function actionModels($id)
    {
        $result = FirmAppShow::findOne($id);
        return $result;
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
     * 获取一级地址
     * @return array|yii\db\ActiveRecord[]
     */
    public function actionDistrictLevelOne(){
        return BsDistrict::getDisLeveOne();
    }
    
    /**
     * 獲取組織名稱在新增時顯示
     * @param $code
     * @return array|null|yii\db\ActiveRecord
     */
    public function actionOrgName($code){
        return HrOrganization::find()->andWhere(['organization_code'=>$code])->one();
    }
    
    /**
     * 检测厂商是否重复
     * @param unknown $name
     * @return string
     */
    public function actionSelectSname($name)
    {
        $result = PdFirm::find()->where(['firm_sname' => $name])->count();
        if ($result) {
            return "0";
        } else {
            return "1";
        }
    }
}