<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/13
 * Time: 下午 02:18
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmCarrier;
use app\modules\crm\models\CrmCommunity;
use app\modules\crm\models\CrmCommunityChild;
use app\modules\crm\models\search\CrmCommunityChildSearch;
use app\modules\crm\models\search\CrmCommunitySearch;
use app\modules\crm\models\show\CrmCommunityShow;
use app\modules\system\models\SystemLog;
use yii\helpers\Html;
use yii\helpers\Json;

class CrmCommunityMarketingController extends BaseActiveController{
    public $modelClass='app\modules\crm\models\CrmCommunity';
    public function actionIndex(){
        $params=\Yii::$app->request->queryParams;
        $dataProvider=CrmCommunitySearch::search($params);
        return [
            "total"=>$dataProvider->totalCount,
            "rows"=>$dataProvider->models
        ];
    }

    public function actionCreate(){
        $model=new CrmCommunity();
        $post=\Yii::$app->request->post();
        $model->load($post);
        $model->create_at=date("Y-m-d");
        if($model->validate() && $model->save()){
            return $this->success(json_encode([$model->primaryKey,"新增成功",$model->commu_code]));
        }
        return $this->error();
    }

    public function actionEdit($id){
        $model=CrmCommunity::findOne($id);
        $post=\Yii::$app->request->post();
        $model->load($post);
        $model->update_at=date("Y-m-d");
        if($model->validate() && $model->save()){
            return $this->success(json_encode([$model->primaryKey,"修改成功",$model->commu_code]));
        }
        return $this->error();
    }

    public function actionStatusSet($id){
        $model=CrmCommunity::findOne($id);
        $post=\Yii::$app->request->post();
        $model->load($post);
        $model->update_at=date("Y-m-d");
        if($model->validate() && $model->save()){
            return $this->success(json_encode([$model->primaryKey,"状态修改成功",$model->commu_code]));
        }
        return $this->error(json_encode([$model->primaryKey,"状态修改失败",$model->commu_code]));
    }


    public function actionRemove($id){
        $model=CrmCommunity::findOne($id);
        if($model->delete()){
            return $this->success(json_encode([$model->primaryKey,"删除成功",$model->commu_code]));
        }
        return $this->error(json_encode([$model->primaryKey,"删除失败",$model->commu_code]));
    }

    public function actionView($id){
        return CrmCommunityShow::findOne($id);
    }

    public function actionGetModel($id){
        return CrmCommunity::findOne($id);
    }

    public function actionGetChildModel($id){
        return CrmCommunityChild::findOne($id);
    }

    public function actionNewCount($id){
        $post=\Yii::$app->request->post();
        $model=new CrmCommunityChild();
        $model->load($post);
        $model->commu_ID=$id;
        if($model->validate() && $model->save()){
            return $this->success();
        }
        return $this->error();
    }

    public function actionCountData(){
        $params=\Yii::$app->request->queryParams;
        $dataProvider=CrmCommunityChildSearch::search($params);
        return [
            "total"=>$dataProvider->totalCount,
            "rows"=>$dataProvider->models
        ];
    }

    public function actionRemoveChild($id){
        $model=CrmCommunityChild::findOne($id);
        if($model->delete()){
            return $this->success();
        }
        return $this->error();
    }

    public function actionGetPublishCarriers($id){
        return CrmCommunity::getCarrierTypes($id);
    }

    public function actionGetCarrierNames($type,$id){
        return CrmCommunity::getCarrierNames($type,$id);
    }

    public static function actionGetOptions(){
        $params=\Yii::$app->request->get();
        $publishCarriers="";
        $carrierNames="";
        if(isset($params["id"]) && $model=CrmCommunity::findOne($params["id"])){
            $publishCarriers=CrmCommunity::getCarrierTypes($model->commu_type);
            $carrierNames=CrmCommunity::getCarrierNames($model->commu_type,$model->cmt_id);
        }
        if(isset($params["commu_type"])){
            $publishCarriers=CrmCommunity::getCarrierTypes($params["commu_type"]);
        }
        if(isset($params["cmt_id"])){
            $carrierNames=CrmCommunity::getCarrierNames($params["commu_type"],$params["cmt_id"]);
        }
        return [
            "commu_type"=>CrmCommunity::getCommuTypes(),
            "publish_carrier"=>$publishCarriers,
            "carrier_names"=>$carrierNames,
            "plan_from"=>CrmCommunity::getPlanFroms(),
            "plan_type"=>CrmCommunity::getPlanTypes(),
            "commu_status"=>CrmCommunity::getStatus(),
            "status"=>CrmCommunity::getStatus(),
            "act_type"=>CrmCommunity::getActType()
        ];
    }
}
?>