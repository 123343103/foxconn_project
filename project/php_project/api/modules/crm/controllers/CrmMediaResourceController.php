<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/3
 * Time: 下午 03:16
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsCurrency;
use app\modules\crm\models\CrmBsMediaType;
use app\modules\crm\models\CrmMediaCount;
use app\modules\crm\models\CrmMediaCountChild;
use app\modules\crm\models\search\CrmMediaCountSearch;
use yii\data\Pagination;

class CrmMediaResourceController extends BaseActiveController{
    public $modelClass='app\modules\crm\models\CrmMediaCount';
    public function actionIndex(){
        $params=\Yii::$app->request->queryParams;
        $dataProvider=CrmMediaCountSearch::search($params);
        return [
            "rows"=>$dataProvider->getModels(),
            "total"=>$dataProvider->totalCount
        ];
    }
    public function actionCreate(){
        try{
            $params = \Yii::$app->request->post();
            $model = new CrmMediaCount();
            $model->load($params);
            if (!($model->validate() && $model->save())) {
                throw new \Exception(json_encode(["新增失败",$model->primaryKey,$model->medic_code]));
            }
            return $this->success(json_encode(["新增成功",$model->primaryKey,$model->medic_code]));
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
    public function actionEdit($id){
        try{
            $params = \Yii::$app->request->post();
            $model =CrmMediaCount::findOne($id);
            $model->load($params);
            $model->cmt_status=(string)$model->cmt_status;
            if ($model->validate() && $model->save()) {
                return $this->success(json_encode(["修改成功",$model->primaryKey,$model->medic_code]));
            }
            throw new \Exception(json_encode(["修改失败",$model->primaryKey,$model->medic_code]));
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
    public function actionRemove($id){
        $model=CrmMediaCount::findOne($id);
        $model->cmt_status=0;
        if($model->save()){
            return $this->success(json_encode(["删除成功",$model->primaryKey,$model->medic_code]));
        }
        return $this->error(json_encode(["删除失败",$model->primaryKey,$model->medic_code]));
    }

    public function actionView($id){
        return CrmMediaCountSearch::findOne($id);
    }

    public function actionChildData($id,$page){
        $query=CrmMediaCountChild::find()->where(["medic_id"=>$id]);
        $pagination=new Pagination([
            "totalCount"=>$query->count(),
            "pageSize"=>2
        ]);
        $data=$query->offset($pagination->offset)->limit($pagination->limit)->all();
        return [
            "total"=>$query->count(),
            "rows"=>$data
        ];
    }

    public function actionNewService($id){
        try{
            if(!CrmMediaCount::findOne($id)){
                throw new \Exception("操作对象不存在");
            }
            $model=new CrmMediaCountChild();
            $model->medic_id=$id;
            $data=\Yii::$app->request->post();
            $model->load($data);
            if($model->validate() && $model->save()){
                return $this->success("新增成功");
            }
            throw new \Exception("新增失败");
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function actionGetModel($id){
        return CrmMediaCount::findOne($id);
    }
    public function actionGetOption(){
        $result["mediaType"]=CrmBsMediaType::find()->select(["cmt_type"])->where(["cmt_status"=>10])->indexBy("cmt_id")->column();
        $result["isSupplier"]=CrmMediaCount::isSupplier;
        $result["serviceLevel"]=CrmMediaCount::serviceLevel;
        $result["currency"]=BsCurrency::find()->select("cur_sname")->indexBy("cur_id")->column();
        return $result;
    }
}
?>