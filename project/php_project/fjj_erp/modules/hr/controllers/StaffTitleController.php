<?php
namespace app\modules\hr\controllers;

use app\controllers\BaseController;
use Yii;
use app\models\UploadForm;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use app\modules\hr\models\HrStaffTitle;
class StaffTitleController extends BaseController{

    private $_url = "hr/staff-title/";  //对应api控制器URL
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        $titleName = $queryParam['HrStaffTitleSearch']['title_name'];
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {
            $dataProvider = $this->findCurl()->get($url);
            return $dataProvider;
        }
        //获取动态列
        $columns=$this->getField();
        return $this->render('index', [
            'search'=>$queryParam['HrStaffTitleSearch'],
            'columns' =>$columns
        ]);
    }

    public function actionCreate()
    {
        if (Yii::$app->request->getIsPost()) {
            $url = $this->findApiUrl() . $this->_url . "create";
            $postData = Yii::$app->request->post();
            $postData['HrStaffTitle']['title_status'] = 10;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                return Json::encode(['msg' => "新增岗位信息成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        }
        return $this->render('create');

    }

    public function actionUpdate($id){

    if( Yii::$app->request->isPost){
        $url = $this->findApiUrl().$this->_url."update?id=".$id;
        $post = Yii::$app->request->post();
        $post['HrStaffTitle']['update_by'] = Yii::$app->user->identity->staff_id;
        $result = $this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($post))->put($url);
        if(json_decode($result)->status){
            return Json::encode(['msg'=>"修改岗位信息成功","flag"=>1,"url"=> Url::to(['index'])]);
        }else{
            return Json::encode(['msg'=>"修改失败",'flag'=>0]);
        }
    }
    $model = $this->getModel($id);
    return $this->render('update',["model"=>$model]);
    }

    /*查看页面*/
    public function actionView($id){
        $url = $this->findApiUrl() . $this->_url . "view?id=".$id;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $this->render('view', [
            'model' => $dataProvider,
        ]);
    }

    /*删除页面*/
    public function actionDelete($id){
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 0) {
            return Json::encode(["msg" => "删除岗位信息成功", "flag" => 1, "url" => Url::to(['index'])]);
        } else {
            return Json::encode(["msg" => "删除失败", "flag" => 0]);
        }
    }

   private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=".$id;
        $model = Json::decode($this->findCurl()->get($url));
        if (!$model) {
            throw new NotFoundHttpException("页面未找到");
        }
        return $model;
    }

}
