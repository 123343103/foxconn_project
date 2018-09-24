<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/12/6
 * Time: 下午 02:28
 */

namespace app\modules\ptdt\controllers;


use app\controllers\BaseActiveController;
use app\models\UploadForm;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsPayCondition;
use app\modules\common\models\BsProduct;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsTradConditions;
use app\modules\ptdt\models\PartnoPrice;
use app\modules\ptdt\models\FpPartNo;
use app\modules\ptdt\models\FpPas;
use app\modules\ptdt\models\search\PdPartnoPriceConfirmSearch;
use app\modules\ptdt\models\show\PartnoPriceShow;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\FpBsCategory;
use yii\helpers\Json;
use yii\web\UploadedFile;
//定价控制器
class PartnoPriceConfirmController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\PartNoPriceShow';




    //定价列表
    public function actionIndex(){
        $searchModel=new PdPartnoPriceConfirmSearch();
        $dataProvider=$searchModel->search(\Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }


    //定价创建
    public function actionCreate(){
        $trans=\Yii::$app->db->beginTransaction();
        try{
            $partnoModel=new PartnoPrice();
            $pasModel=new FpPas();
            $post=\Yii::$app->request->post();
            $pasModel->load(["FpPas"=>$post]);
            $numArr =$post['num'];
            $priceArr = $post["price"];
            FpPas::deleteAll(['part_no' => $post['part_no']]);
            for ($x = 0; $x < count($numArr); $x++) {
                $pasModel = new FpPas();
                $pasModel->load($post);
                $pasModel->part_no=$post["part_no"];
                $pasModel->num_area = $numArr[$x];
                $pasModel->buy_price = $priceArr[$x];
                if(!($pasModel->validate() && $pasModel->save())){
                    throw new \Exception(Json::encode($pasModel->getErrors()));
                }
            }
            $partnoModel->load(["PartnoPrice"=>$post]);
            $partnoModel->status=4;
            $partnoModel->verifydate=date("Y-m-d");
            $partnoModel->part_no=$post["part_no"];
            if(!$partnoModel->save()){
                throw new \Exception("定价数据修改失败");
            }
            $trans->commit();
            return $this->success();
        }catch(Exception $e){
            $trans->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //定价修改
    public function actionEdit($id){
        $trans=\Yii::$app->db->beginTransaction();
        try {
            $post = \Yii::$app->request->post();
            $model = PartnoPrice::findOne($id);
            if (!$model) {
                throw new \Exception("定价记录不存在");
            }
            $numArr =$post['num_area'];
            $priceArr = $post["price"];
            FpPas::deleteAll(['part_no' => $model->part_no]);
            for ($x = 0; $x < count($numArr); $x++) {
                $pasModel = new FpPas();
                $pasModel->load(["FpPas"=>$post]);
                $pasModel->num_area = $numArr[$x];
                $pasModel->buy_price = $priceArr[$x];
                if(!($pasModel->validate() && $pasModel->save())){
                    throw new \Exception("核价信息修改失败");
                }
            }
            $model->load(["PartnoPrice"=>$post]);
            $model->setAttribute("num_area","");
            $model->status = 4;
            if (!($model->validate() && $model->save())) {
                throw new \Exception("定价信息修改失败");
            }


            $productModel = BsProduct::findOne(["pdt_no"=>$model->part_no]);
            if (!$productModel) {
                throw new \Exception("料号记录不存在");
            }
            $productModel->status = 4;
            if(!($productModel->validate() && $productModel->save())){
                throw new \Exception("料号信息修改失败");
            }
            $trans->commit();
            return $this->success();
        }catch (Exception $e){
            $trans->rollBack();
            return $this->error($e->getMessage());
        }
    }



    //定价删除
    public function actionDelete($id){
        $idArr=explode(",",$id);
        if(PartnoPrice::deleteAll(["id"=>$idArr])){
            return $this->success();
        }else{
            return $this->error();
        }
    }


    //关联定价
    public function actionRelationPrice(){
        $trans=\Yii::$app->db->beginTransaction();
        $post=\Yii::$app->request->post();
        $parent_partno=$post["parent-partno"];
        $sub_partno_arr=explode(",",$post["sub-partno"]);
        try{
            foreach($sub_partno_arr as $v){
                $model=PartnoPrice::findOne(["part_no"=>$v]);
                if(!$model){
                    throw new \Exception("记录不存在");
                }
                $model->isrelation=$parent_partno;
                if(!$model->save()){
                    throw new \Exception($model->getErrors());
                }
            }
            $trans->commit();
            return $this->success();
        }catch (Exception $e){
            $trans->rollBack();
            return $this->error($e->getMessage());
        }
    }


    //批量定价
    public function actionBatchPrice(){
        $batch_price=\Yii::$app->request->post("PartnoPrice");
        $filename=\Yii::$app->request->post("filename");
        $price_list=array();
        foreach($batch_price as $k=>$v){
            foreach($v as $kk=>$vv){
                $price_list[$kk][$k]=$vv;
                $price_list[$kk]["filename"]=$filename;
            }
        }
        foreach ($price_list as $v){
            $model=PartnoPrice::findOne($v['id']);
            $model->load(["PartnoPrice"=>$v]);
            if(!$model->validate() || !$model->save()){
                return $this->error();
            }
        }
        return $this->success();
    }



    //获取产品一级分类
    public function actionProductTypes()
    {
        return BsCategory::getLevelOne();
    }


    //定价详情
    public function actionModels($id)
    {
        $model = PartnoPriceShow::findOne($id);
        return $model;

    }

    //下拉列表数据
    public function actionGetDownList(){
        $list["status"]=['未定价','发起定价','商品开发维护','审核中','已定价','被驳回','已逾期','重新定价'];
        $list["price_from"]=BsPubdata::findAll(["bsp_stype"=>"djfqy","bsp_status"=>10]);
        $list["price_type"]=BsPubdata::findAll(["bsp_stype"=>"djlx","bsp_status"=>10]);
        $list["price_level"]=BsPubdata::findAll(["bsp_stype"=>"spdw","bsp_status"=>10]);
        $list["risk_level"]=BsPubdata::findAll(["bsp_stype"=>"fwfxdj","bsp_status"=>10]);
        $list["unit"]=BsPubdata::findAll(["bsp_stype"=>"jydw","bsp_status"=>10]);
        $list["currency"]=BsPubdata::findAll(["bsp_stype"=>"jybb","bsp_status"=>10]);
        $list["payment_terms"]=BsPayCondition::find()->all();
        $list["trading_terms"]=BsTradConditions::find()->all();
        return $list;
    }
}