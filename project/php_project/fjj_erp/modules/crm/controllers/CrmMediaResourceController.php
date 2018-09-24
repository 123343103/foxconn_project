<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/13
 * Time: 上午 09:58
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseController;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\system\models\SystemLog;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class CrmMediaResourceController extends BaseController{
    public $_url="crm/crm-media-resource/";
    public function actionIndex(){
        if(\Yii::$app->request->isAjax){
            $params=\Yii::$app->request->queryParams;
            $url=$this->findApiUrl().$this->_url."index";
            $url.="?".http_build_query($params);
            $data=Json::decode($this->findCurl()->get($url));
            foreach($data["rows"] as &$row){
                $row["medic_code"]="<a href='".Url::to(['view','id'=>$row["medic_id"]])."'>".$row["medic_code"]."</a>";
            }
            return Json::encode($data);
        }
        $url=$this->findApiUrl().$this->_url."get-option";
        $options=Json::decode($this->findCurl()->get($url));
        $columns=$this->getField('/crm/crm-media-resource/index');
        return $this->render("index",["options"=>$options,"columns"=>$columns]);
    }

    public function actionCreate(){
        if(\Yii::$app->request->isPost){
            $url=$this->findApiUrl().$this->_url."create";
            $params=\Yii::$app->request->post();
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $res=Json::decode($curl->post($url));
            if($res["status"]==1){
                $data=Json::decode($res['msg']);
                SystemLog::addLog("新增媒体资源{$data[2]}成功");
                return Json::encode(["msg"=>$data[0],"flag"=>1,"url"=>Url::to(['view','id'=>$data[1]])]);
            }
            SystemLog::addLog("新增媒体资源失败");
            return Json::encode(["msg"=>$res["msg"],"flag"=>0]);
        }
        $url=$this->findApiUrl().$this->_url."get-option";
        $options=Json::decode($this->findCurl()->get($url));
        return $this->render("create",["options"=>$options]);
    }
    public function actionEdit($id){
        if(\Yii::$app->request->isPost){
            $url=$this->findApiUrl().$this->_url."edit?id={$id}";
            $params=\Yii::$app->request->post();
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $res=Json::decode($curl->post($url));
            $data=Json::decode($res['msg']);
            if($res["status"]==1){
                SystemLog::addLog("修改媒体资源{$data[2]}成功");
                return Json::encode(["msg"=>"修改成功","flag"=>1,"url"=>Url::to(['view','id'=>$id])]);
            }
            SystemLog::addLog("修改媒体资源{$data[2]}失败");
            return Json::encode(["msg"=>"修改失败","flag"=>0]);
        }
        $url=$this->findApiUrl().$this->_url."get-model?id={$id}";
        $model=Json::decode($this->findCurl()->get($url));
        $url=$this->findApiUrl().$this->_url."get-option";
        $options=Json::decode($this->findCurl()->get($url));
        return $this->render("edit",["model"=>$model,"options"=>$options]);
    }

    public function actionView($id){
        $url=$this->findApiUrl().$this->_url."view?id={$id}";
        $model=Json::decode($this->findCurl()->get($url));
        return $this->render("view",["model"=>$model]);
    }

    public function actionRemove($id){
        $url=$this->findApiUrl().$this->_url."remove?id={$id}";
        $res=Json::decode($this->findCurl()->get($url));
        $data=Json::decode($res['msg']);
        if($res["status"]==1){
            SystemLog::addLog("删除媒体资源{$data[2]}成功");
            return Json::encode(["msg"=>$data[0],"flag"=>1]);
        }
        SystemLog::addLog("删除媒体资源{$data[2]}成功");
        return Json::encode(["msg"=>$data[0],"flag"=>0]);
    }

    public function actionChildData($id,$page=""){
        if(\Yii::$app->request->isAjax){
            $url=$this->findApiUrl().$this->_url."child-data?id={$id}&page={$page}";
            return $this->findCurl()->get($url);
        }
        $columns=$this->getField('/crm/crm-media-resource/child-data');
        return $this->renderAjax("child-data",["columns"=>$columns]);
    }

    public function actionNewService($id){
        if(\Yii::$app->request->isPost){
            $params=\Yii::$app->request->post();
            $url=$this->findApiUrl().$this->_url."new-service?id={$id}";
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $data=Json::decode($curl->post($url));
            if($data["status"]==1){
                SystemLog::addLog("新增服务内容成功");
                return Json::encode(["msg"=>$data["msg"],"flag"=>1]);
            }
            SystemLog::addLog("新增服务内容失败");
            return Json::encode(["msg"=>$data["msg"],"flag"=>0]);
        }
        $url=$this->findApiUrl().$this->_url."get-model?id={$id}";
        $model=Json::decode($this->findCurl()->get($url));
        $url=$this->findApiUrl().$this->_url."get-option";
        $options=Json::decode($this->findCurl()->get($url));
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render("new-service",["model"=>$model,"options"=>$options]);
    }

    public function actionCompanySearch(){
        $params=\Yii::$app->request->get();
        $data=(new Query())->from("crm_bs_customer_info")->select('cust_sname name')->where(['like','cust_sname',$params['CrmMediaCount']['medic_compname']])->all();
        foreach ($data as &$item){
            $item['name']=Html::encode($item['name']);
        }
        return json_encode($data);
    }
}
?>