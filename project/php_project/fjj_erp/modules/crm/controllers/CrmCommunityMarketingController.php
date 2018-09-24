<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/13
 * Time: 上午 09:58
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class CrmCommunityMarketingController extends BaseController{
    public $_url="crm/crm-community-marketing/";
    public function actionIndex(){
        $params=\Yii::$app->request->queryParams;
        if(\Yii::$app->request->isAjax){
            $url=$this->findApiUrl().$this->_url."index?".http_build_query($params);
            $data=Json::decode($this->findCurl()->get($url));
            foreach($data["rows"] as &$row){
                $row["commu_code"]="<a href='".Url::to(['info','id'=>$row["commu_ID"]])."'>".$row["commu_code"]."</a>";
            }
            return Json::encode($data);
        }
        $url=$this->findApiUrl().$this->_url."get-options?".http_build_query($params);
        $options=Json::decode($this->findCurl()->get($url));
        $columns=$this->getField('/crm/crm-community-marketing/index');
        return $this->render("index",["options"=>$options,"columns"=>$columns]);
    }

    public function actionCreate(){
        if(\Yii::$app->request->isPost){
            $post=\Yii::$app->request->post();
            $url=$this->findApiUrl().$this->_url."create";
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($post));
            $res=Json::decode($curl->post($url));
            if($res["status"]==1){
                $data=json_decode($res["msg"]);
                SystemLog::addLog("新增网络营销{$data[2]}成功");
                return Json::encode(['msg'=>$data[1],'flag'=>1,'url'=>Url::to(['view','id'=>$data[0]])]);
            }
            SystemLog::addLog("新增网络营销失败");
            return Json::encode(['msg'=>"新增失败",'flag'=>0]);
        }
        $url=$this->findApiUrl().$this->_url."get-options";
        $options=Json::decode($this->findCurl()->get($url));
        return $this->render("create",["options"=>$options]);
    }
    public function actionEdit($id){
        if(\Yii::$app->request->isPost){
            $post=\Yii::$app->request->post();
            $url=$this->findApiUrl().$this->_url."edit?id=".$id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($post));
            $res=Json::decode($curl->post($url));
            $data=json_decode($res["msg"]);
            if($res["status"]==1){
                SystemLog::addLog("修改网络营销{$data[2]}成功");
                return Json::encode(['msg'=>$data[1],'flag'=>1,'url'=>Url::to(['view','id'=>$data[0]])]);
            }
            SystemLog::addLog("修改网络营销{$data[2]}失败");
            return Json::encode(['msg'=>"修改失败",'flag'=>0]);
        }
        $url=$this->findApiUrl().$this->_url."get-options?id=".$id;
        $options=Json::decode($this->findCurl()->get($url));
        $url=$this->findApiUrl().$this->_url."get-model?id=".$id;
        $model=Json::decode($this->findCurl()->get($url));
        return $this->render("edit",["model"=>$model,"options"=>$options]);
    }

    public function actionRemove($id){
        $url=$this->findApiUrl().$this->_url."remove?id=".$id;
        $res=Json::decode($this->findCurl()->get($url));
        $data=json_decode($res["msg"]);
        if($res["status"]==1){
            SystemLog::addLog("删除网络营销{$data[2]}成功");
            return Json::encode(["msg"=>"删除成功","flag"=>1]);
        }
        SystemLog::addLog("删除网络营销{$data[2]}失败");
        return Json::encode(["msg"=>"删除失败","flag"=>0]);
    }

    public function actionView($id){
        $url=$this->findApiUrl().$this->_url."view?id=".$id;
        $model=Json::decode($this->findCurl()->get($url));
        return $this->render("view",["model"=>$model]);
    }

    public function actionInfo($id){
        $url=$this->findApiUrl().$this->_url."view?id=".$id;
        $model=Json::decode($this->findCurl()->get($url));
        $columns=$this->getField("/crm/crm-community-marketing/count-data?type=".$model['commu_type']);
//        $columns=Json::decode('['.$columns.']');
        return $this->render("info",["model"=>$model,"columns"=>$columns]);
    }

    public function actionNewCount($id){
        if(\Yii::$app->request->isPost){
            $post=\Yii::$app->request->post();
            $url=$this->findApiUrl().$this->_url."new-count?id=".$id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($post));
            $data=Json::decode($curl->post($url));
            if($data["status"]==1){
                SystemLog::addLog("网络营销-新增统计成功");
                return Json::encode(["msg"=>"新增统计成功","flag"=>1]);
            }
            SystemLog::addLog("网络营销-新增统计失败");
            return Json::encode(["msg"=>"新增统计失败","flag"=>0]);
        }
        $url=$this->findApiUrl().$this->_url."get-options?id=".$id;
        $options=Json::decode($this->findCurl()->get($url));
        $url=$this->findApiUrl().$this->_url."get-model?id=".$id;
        $model=Json::decode($this->findCurl()->get($url));
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render("new-count",[
            "model"=>$model,
            "options"=>$options
        ]);
    }


    public function actionStatusSet($id){
        if(\Yii::$app->request->isPost){
            $params=\Yii::$app->request->post();
            $url=$this->findApiUrl().$this->_url."status-set?id=".$id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $res=Json::decode($curl->post($url));
            $data=Json::decode($res['msg']);
            if($res["status"]==1){
                SystemLog::addLog("网络营销{$data[2]}状态设置成功");
                return Json::encode(["msg"=>"设置成功","flag"=>1]);
            }
            SystemLog::addLog("网络营销{$data[2]}状态设置失败");
            return Json::encode(["msg"=>"设置失败","flag"=>0]);
        }
        $url=$this->findApiUrl().$this->_url."get-options?id=".$id;
        $options=Json::decode($this->findCurl()->get($url));
        $url=$this->findApiUrl().$this->_url."get-model?id=".$id;
        $model=Json::decode($this->findCurl()->get($url));
        return $this->renderAjax("status_set",[
            "model"=>$model,
            "options"=>$options
        ]);
    }


    public function actionCountData($id){
        if(\Yii::$app->request->isAjax){
            $params=\Yii::$app->request->queryParams;
            $url=$this->findApiUrl().$this->_url."count-data?id={$id}".http_build_query($params);
            return $this->findCurl()->get($url);
        }
        $url=$this->findApiUrl().$this->_url."get-model?id=".$id;
        $model=Json::decode($this->findCurl()->get($url));
        $columns=$this->getField("/crm/crm-community-marketing/count-data?type=".$model["commu_type"]);
        return $this->renderAjax("count-data",["columns"=>$columns]);
    }


    public function actionRemoveChild($id){
        $url=$this->findApiUrl().$this->_url."remove-child?id=".$id;
        $data=Json::decode($this->findCurl()->get($url));
        if($data["status"]==1){
            return Json::encode(["msg"=>"删除成功","flag"=>1]);
        }
        return Json::encode(["msg"=>"删除失败","flag"=>0]);
    }

    public function actionEditChild($id){
        $url=$this->findApiUrl().$this->_url."get-child-model?id=".$id;
        $model=Json::decode($this->findCurl()->get($url));
        $this->redirect(['edit','id'=>$model['commu_ID']]);
    }

    public function actionGetPublishCarriers($id,$format="html"){
        $url=$this->findApiUrl().$this->_url."get-publish-carriers?id=".$id;
        $data=Json::decode($this->findCurl()->get($url));
        if($format=="html"){
            return Html::renderSelectOptions("",$data,$options=["prompt"=>"请选择","encodeSpaces"=>false]);
        }
        return $data;
    }
    public function actionGetCarrierNames($type,$id,$foramt="html"){
        $url=$this->findApiUrl().$this->_url."get-carrier-names?type=".$type."&id=".$id;
        $data=Json::decode($this->findCurl()->get($url));
        if($foramt=="html"){
            return Html::renderSelectOptions("",$data,$options=["prompt"=>"请选择","encodeSpaces"=>false]);
        }
        return $data;
    }
}
?>