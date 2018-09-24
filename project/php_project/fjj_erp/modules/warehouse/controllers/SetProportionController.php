<?php
/**
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2018/1/15
 * Time: 15:59
 */
namespace app\modules\warehouse\controllers;
use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;


class SetProportionController extends BaseController {
    private $_url = 'warehouse/set-propor/'; //对应api
    //index
    public function actionIndex(){
        $url=$this->findApiUrl().'warehouse/set-propor/index';
        if (Yii::$app->request->isAjax){
            $url.='?'.http_build_query(Yii::$app->request->queryParams);
            $url.='&'.http_build_query(Yii::$app->request->queryParams);
            $dataProvider=$this->findCurl()->get($url);
            return $dataProvider;
        }
        $dataProvider=$this->findCurl()->get($url);
        $data=Json::decode($dataProvider);
//        dumpE($data);
        $downlist=$this->getDownlist();
        return $this->render('index',['data'=>$data,'downlist'=>$downlist]);
    }
    //下拉框
    public function getDownlist(){
        $url=$this->findApiUrl()."warehouse/set-propor/downlist";
        return Json::decode($this->findCurl()->get($url));
    }
    //禁用
    public function actionCancel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "cancel?id=" . $id;
        $data = Json::decode($this->findCurl()->delete($url));
        if ($data['status']) {
            SystemLog::addLog('数据禁用:'.$data['msg']);
            return Json::encode(["msg" => "禁用成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "禁用失败", "flag" => 0]);
        }
    }

    //启用
    public function actionOpen($id)
    {
        $url = $this->findApiUrl() . $this->_url . "open?id=" . $id;
        $data = Json::decode($this->findCurl()->delete($url));
        if ($data['status']) {
            SystemLog::addLog('数据启用:'.$data['msg']);
            return Json::encode(["msg" => "启用成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "启用失败", "flag" => 0]);
        }
    }
    //新增比例设置
    public function actionCreate()
    {
        $url=$this->findApiUrl().$this->_url."create";
        $url.='?id='.Yii::$app->user->identity->staff_id;
        if($data=Yii::$app->request->post())
        {
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('新增比例信息');
                return json_encode([
                    'msg'=>$result['msg'],
                    'flag'=>1,
                    'url'=>Url::to(['index']),
                ]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);

        }
        $downlists=$this->getDownlists();
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render('create',[
            'downlists'=>$downlists
        ]);
    }
    //新增下拉框
    public function getDownlists(){
        $url=$this->findApiUrl()."warehouse/set-propor/downlists";
        return Json::decode($this->findCurl()->get($url));
    }
    //修改比例设置
    public function actionUpdate($id)
    {
        if($data=Yii::$app->request->post()){
            $_id=Yii::$app->user->identity->staff_id;
            $url=$this->findApiUrl().'warehouse/set-propor/update';
            $url.='?id='.$id;
            $url.='&_id='.$_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('修改比例信息');
                return json_encode([
                    'msg'=>$result['msg'],
                    'flag'=>1,
                    'url'=>Url::to(['index','id'=>$id]),
                ]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $downlist=$this->getDownlist();
        $sql=$this->getModels($id);
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render('update',[
            'sql'=>$sql,
            'downlist'=>$downlist
        ]);
    }
    //修改页数据获取方法定义
    public function getModels($id){
        $url=$this->findApiUrl()."warehouse/set-propor/models";
        $url.='?id='.$id;
        return Json::decode($this->findCurl()->get($url));
    }
}

