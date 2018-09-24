<?php
namespace app\modules\ptdt\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsCategory;
use app\modules\ptdt\models\PdFirmReport;
use app\modules\ptdt\models\PdFirmReportProduct;
use app\modules\ptdt\models\PdNegotiationProduct;
use app\modules\ptdt\models\PdRequirement;
use app\modules\ptdt\models\search\PdProductManagerSearch;
use app\modules\ptdt\models\show\PdRequirementProductShow;
use app\modules\system\models\show\VerifyrecordChildShow;
use app\modules\system\models\Verifyrecord;
use Yii;
use app\modules\hr\models\HrOrganization;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\search\PdRequirementSearch;
use app\modules\ptdt\models\PdRequirementProduct;
use app\modules\ptdt\models\show\PdRequirementShow;

/**
 * 商品开发需求控制器
 * Class ProductDvlpController
 * @package app\modules\ptdt\controllers
 */
class ProductDvlpController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\PdRequirement';
    /*
     * 生成表格页数据
     */
    public function actionIndex()
    {
        $model = new PdRequirementSearch();
        $dataProvider = $model->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }
    /**
     * 新增
     * @return array
     */
    public function actionAdd()
    {
        $planModel = new PdRequirement();
        $post = Yii::$app->request->post();
        if(!empty($post['type'])){
            $post['PdRequirement']['pdq_status']=PdRequirement::STATUS_REVIEW;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($planModel->load($post) && $planModel->save()) {
                $i = 1;
                    foreach ($post['PdRequirementProduct'] as $key => $val) {
                        $productModel = new PdRequirementProduct();
//                        $productModel->product_name = $val['product_name'];
//                        $productModel->product_size = $val['product_size'];
                        $productModel->product_index = $i++;
                        $productModel->load(['PdRequirementProduct'=>$val]);
//                        $productModel->product_level_id = $val['product_level_id'];
//                        $productModel->product_type_id = $val['product_type_id'];
//                        $productModel->product_requirement = $val['product_requirement'];
//                        $productModel->product_process_requirement = $val['product_process_requirement'];
//                        $productModel->product_quality_requirement = $val['product_quality_requirement'];
                        $productModel->requirement_id = $planModel->pdq_id;
//                        $productModel->other_des = $val['other_des'];
                        if (!$productModel->save()) {
                            $transaction->rollBack();
                            throw new \Exception("保存失败");
                        };
                    }
            } else {
                $transaction->rollBack();
                throw new \Exception("保存失败");
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();

        return $this->success($planModel->pdq_code,$planModel->pdq_id);
    }

    /**
     * 修改
     * @param $id
     * @return array
     */
    public function actionEdit($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $planModel = $this->getModel($id);
        if(!$planModel){
            return $this->error();
        }
        $post = Yii::$app->request->post();
        try {
            if ($planModel->load($post) && $planModel->save()) {
                $count = PdRequirementProduct::find()->where(['requirement_id' => $planModel->pdq_id])->count();
                if (PdRequirementProduct::deleteAll(['requirement_id' => $planModel->pdq_id]) < $count) {
                    throw  new \Exception("更新商品失败");
                };
                $i = 1;    //index
                if (isset($post['PdRequirementProduct'])) {
                    foreach ($post['PdRequirementProduct'] as $key => $val) {
                        $productModel = new PdRequirementProduct();
//                        $productModel->product_name = $val['product_name'];
//                        $productModel->product_size = $val['product_size'];
                        $productModel->product_index = $i++;
//                        $productModel->product_level_id = $val['product_level_id'];
//                        $productModel->product_unit = $val['product_unit'];
//                        $productModel->product_type_id = $val['product_type_id'];
//                        $productModel->product_requirement = $val['product_requirement'];
//                        $productModel->product_process_requirement = $val['product_process_requirement'];
//                        $productModel->product_quality_requirement = $val['product_quality_requirement'];
                        $productModel->requirement_id = $planModel->pdq_id;
                        $productModel->load(['PdRequirementProduct'=>$val]);
                        if (!$productModel->save()) {
                            throw  new \Exception("更新商品失败");
                        };
                    }
                }
            }else{
                throw new \Exception("更新需求失败");
            };
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success($planModel['pdq_code']);
    }

    /**
     * 删除
     * @param $id
     * @return array
     */
    public function actionDelete($id){
        $model = $this->getModel($id);
        $model->pdq_status = PdRequirement::STATUS_DELETE;
        if ($model->save()) {
            return $this->success($model->pdq_code);
        }
        return $this->error();

    }

    /**
     * @param $id
     * @return string
     * 查询该商品是否被引用
     * F1678086 -- 龚浩晋
     */
    public function actionDeleteCount($id){
        $rid = PdFirmReportProduct::find()->where(['demand_id'=>$id])->count();
        $nid = PdNegotiationProduct::find()->where(['demand_id'=>$id])->andWhere(['!=','pdnp_status',PdNegotiationProduct::STATUS_DEL])->count();
        if($rid != 0 || $nid != 0){
            return 'false';
        }else{
            return 'true';
        }
    }

    public function actionCheck($id){
        $transaction = Yii::$app->db->beginTransaction();
        $post = Yii::$app->request->post();
        $model = $this->getModel($id);
        if($model){
            try {
                $model->offer_staff = $post['staff'];
                $model->offer_date = date('Y-m-d H:i:s',time());
                $model->pdq_status = PdRequirement::STATUS_REVIEW;
                $model->save();
                $transaction->commit();
                return $this->success();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }else{
            return $this->error();
        }
    }


    /**
     * 获取需求商品信息
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionProducts($id)
    {
        $productList = PdRequirementProductShow::getProductByPlanId($id);

        return $productList;
    }


    /**
     * 下拉列表
     */
    public function actionDownList(){
        $downList['developCenters']  = $this->developCenters();    //开发中心
        $downList['requirementTypes']= $this->requirementTypes();  //需求类型
        $downList['developTypes']    = $this->developTypes();      //开发类型
        $downList['productLevel']    = $this->productLevels();     //商品定位
        $downList['staffManager']    = $this->pms();               //商品经理人
        $downList['productTypes']    = $this->productTypes();      //一阶分类
        $downList['pdqStatus']   = [
            PdRequirement::STATUS_DEFAULT=>'新增',//新增
            PdRequirement::STATUS_REVIEW=>'审核中', //审核
            PdRequirement::STATUS_FINISH=>'已通过', //通过
            PdRequirement::STATUS_REJECT=>'被驳回', //驳回
        ];
        return $downList;
     }
    /**
     * 获取开发中心选择列表
     * @return mixed
     */
    private function developCenters()
    {
        return HrOrganization::getPDvlpOption(HrOrganization::PD_DEPARTMENT_CODE);
    }

    /**
     * 需求类型选择列表
     * @return mixed
     */
    private function requirementTypes()
    {
        return BsPubdata::getData(BsPubdata::PD_REQUIREMENT_TYPE);
    }


    /**
     * 开发类型数据
     * @return array
     */
    private function developTypes()
    {
        return BsPubdata::getData(BsPubdata::PD_DEVELOP_TYPE);
    }

    /**
     * 商品定位列表
     * @return array
     */
    private  function productLevels()
    {
        return BsPubdata::getData(BsPubdata::DP_PRODUCT_LEVEL);
    }

    /**
     * 商品经理人
     * @return array
     */
    private function pms(){
        return PdProductManagerSearch::find()->all();
    }

    /**
     * 一阶分类
     * @return array|\yii\db\ActiveRecord[]
     */
    private function productTypes()
    {
        return BsCategory::getLevelOne();
    }

    /**
     * 获取开发部门
     * @param $code
     * @return mixed
     */
    public function actionDevelopDeps($code)
    {
        return HrOrganization::getPDvlpOption($code);
    }

    /**
     * 获取大类子类
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionProductTypeChildren($id)
    {
        return BsCategory::getChildrenByParentId($id);
    }

    /**
     * 模型获取API
     * @param $id
     * @return array|null|static
     */
    public function actionModel($id)
    {
        $model = PdRequirementShow::getOne($id);
        return $model;

    }

    private function getModel($id){
        $model = PdRequirement::findOne($id);
        return $model;
    }

    public function actionGetProduct($id){
        $data['model'] = PdRequirementShow::findOne($id);
        return $data;
    }
}