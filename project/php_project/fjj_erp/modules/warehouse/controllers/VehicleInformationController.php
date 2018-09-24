<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/6/29
 * Time: 下午 02:06
 */
namespace app\modules\warehouse\controllers;

use app\controllers\BaseController;
use yii;
use yii\helpers\Json;

class VehicleInformationController extends BaseController
{
    private $_url = 'warehouse/vehicle-information/';
    public function actionIndex()
    {
        $url = $this->findApiUrl() . "/warehouse/vehicle-information/index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        $companyname = $this->actionCompanyNameList();
        $get = Yii::$app->request->get();
        if (!isset($get['BsVehSearch'])) {
            $get['BsVehSearch'] = null;
        }
//        print_r($dataProvider['rows']);
        if (Yii::$app->request->isAjax) {
            return Json::encode($dataProvider);
        }
        return $this->render('index',['companyname'=>$companyname]);
    }

    //添加
    public function actionAdd()
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "add";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
//            print_r($data);
            if ($data['status'] == 1) {
//                SystemLog::addLog($data['data']['msg']);
                return Json::encode(['msg' => "新增成功!", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "新增失败!", "flag" => 0]);
            }
        }
        else {
            $companyname = $this->actionCompanyNameList();
            return $this->render('add', ['companyname' => $companyname]);
        }
    }

    //修改
    public function actionUpdate($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "update?id=".$id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
//            print_r($data);
            if ($data['status'] == 1) {
//                SystemLog::addLog($data['data']['msg']);
                return Json::encode(['msg' => "修改成功!", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "修改失败!", "flag" => 0]);
            }
        }
        else {
            $companyname = $this->actionCompanyNameList();
            $model = $this->actionBsvehByid($id);
            return $this->render('update', ['model' => $model, 'companyname' => $companyname]);
        }
    }

    //删除
    public function actionDelete($id,$staff_id)
    {
        $url = $this->findApiUrl() . $this->_url . "deletes?id=" . $id.'&staff_id='.$staff_id;
        $result = $this->findCurl()->delete($url);
        if (json_decode($result)->status == 1) {
//            SystemLog::addLog("刪除了ID=".$id.'的信息');
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失败", "flag" => 0]);
        }
    }

    //获取公司名称
    public function actionCompanyNameList()
    {
        $url = $this->findApiUrl() . $this->_url . "company-name-list";
        return Json::decode($this->findCurl()->get($url));
    }
    //根据id获取数据
    public function actionBsvehByid($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-bsveh-byid?id=".$id;
        return Json::decode($this->findCurl()->get($url));
    }
}