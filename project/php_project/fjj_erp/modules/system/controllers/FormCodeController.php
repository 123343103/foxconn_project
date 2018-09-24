<?php
/**
 * 單據編碼規則控制器
 * F3858995
 *2016/10/14
 */

namespace app\modules\system\controllers;
use app\modules\system\models\SystemLog;
use yii;
use yii\helpers\Json;
use yii\helpers\Url;

class FormCodeController extends \app\controllers\BaseController{


    private $_url = "system/form-code/";  //对应api控制器URL
    /**
     * 列表
     * @return string
     */
    public function actionIndex(){
        return $this->render('index',[
            'tree'=>$this->getTree()
        ]);
    }

    private function getTree(){
        $url = $this->findApiUrl() . $this->_url . "get-tree";
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $dataProvider;
    }

    /**
     * 修改
     * @param $id
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionEdit($code,$type){
        if($post = Yii::$app->request->post()){
            $url = $this->findApiUrl().$this->_url."edit?code=".$code.'&type='.$type;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if($data['status']){
                SystemLog::addLog('单据编码规则修改:'.$data['msg']);
                return Json::encode(['msg'=>"保存成功","flag"=>1,"url"=>Url::to(['index'])]);
            }else{
                return Json::encode(['msg'=>$data['msg'],'flag'=>0]);
            }
        }
        return $this->renderAjax('edit',[
            'model'=>$this->formCodeInfo($code,$type),
            'code'=>$code]
        );
    }

    private function formCodeInfo($code,$type){
        $url = $this->findApiUrl() . $this->_url . "form-code-info?code=".$code.'&type='.$type;;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $dataProvider;
    }



}