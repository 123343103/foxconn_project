<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/11/20
 * Time: 上午 10:48
 */
namespace app\modules\system\controllers;

use app\classes\Trans;
use app\controllers\BaseActiveController;
use app\modules\system\models\BsBtn;
use app\modules\system\models\search\SystemOperationSearch;
use Yii;

class SystemOperationController extends BaseActiveController
{
    public $modelClass='app\modules\system\models\BsBtn';
    public function actionIndex(){
        $model = new SystemOperationSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $model->search($queryParams);
        return $dataProvider;
    }

    //新增
    public function actionCreate(){
        $postdata= Yii::$app->request->post();
        $tr = new Trans();
        $model=new BsBtn();
        $model->opper=$postdata["BsBtn"]["opper"];//操作人
        $model->opp_date=date('Y-m-d H:i:s', time());
        $model->opp_ip=Yii::$app->request->getUserIP();//'//获取ip地址
        $model->btn_name=$tr->t2c($postdata["BsBtn"]["btn_name"]);
        if ($model->load($postdata) && $model->save()) {
            return $this->success('创建成功');
        } else {
            return $this->error($model->errors);
        }
    }

    public function actionUpdate($id){
        if(Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            try{
                $tr = new Trans();
                $BsBtn=BsBtn::findOne($id);
                $BsBtn->opper= $data["BsBtn"]["opper"];//操作人
                $BsBtn->opp_date=date('Y-m-d H:i:s', time());
                $BsBtn->opp_ip=Yii::$app->request->getUserIP();//'//获取ip地址
                $BsBtn->btn_name=$tr->t2c($data["BsBtn"]["btn_name"]);
                if (! $BsBtn->save()) {
                    throw new \Exception(json_encode($BsBtn->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }
            return $this->success();
        }
//        $model=BsBtn::findOne($id);
//        $post = Yii::$app->request->post();
//        try{
//            $model->load($post);
//            if(!$model->save()){
//                throw new \Exception("修改信息失败");
//            }
//        }catch (\Exception $e){
//            return $this->error($e->getMessage());
//        }
//        return $this->success();
    }
    public function actionModels($id)
    {
        $model=BsBtn::findOne($id);
        if($model!==null){
            return $model;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}