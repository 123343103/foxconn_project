<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/7/10
 * Time: 上午 08:45
 */
namespace app\modules\warehouse\controllers;

use app\controllers\BaseController;
use yii;
use yii\helpers\Json;

class LogisticsCompanyController extends BaseController
{
    private $_url = 'warehouse/logistics-company/';
    //主页
    public function actionIndex()
    {
        $url = $this->findApiUrl() . "/warehouse/logistics-company/index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        $companytype = $this->actionCompanyTypeList();
        $companyname = $this->actionCompanyNameList();
        foreach ($companytype as $key=>$val)
        {
            $companytype[$key]['para_name']=$companytype[$key]['para_name'].'型';
        }
        $get = Yii::$app->request->get();
        if (!isset($get['LogCmpSearch'])) {
            $get['LogCmpSearch'] = null;
        }
//        print_r($dataProvider['rows']);
        if (Yii::$app->request->isAjax) {
            foreach ($dataProvider['rows'] as $key=>$val)
            {
                $dataProvider['rows'][$key]['log_type_name']=$dataProvider['rows'][$key]['log_type_name'].'型';
            }
            return Json::encode($dataProvider);
        }
        return $this->render('index',
            [
                'companyname'=>$companyname,
                'companytype'=>$companytype
            ]
        );
    }

    //新增
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
            $companytype = $this->actionCompanyTypeList();
            $paracompanyname = $this->actionParaCompanyNameList();
            foreach ($companytype as $key => $val) {
                $companytype[$key]['para_name'] = $companytype[$key]['para_name'] . '型';
            }
            return $this->render('add',
                [
                    'companyname' => $paracompanyname,
                    'companytype' => $companytype
                ]
            );
        }
    }

    //详情
    public function actionViews($id)
    {
        $companytype = $this->actionCompanyTypeList();
        $paracompanyname = $this->actionParaCompanyNameList();
        foreach ($companytype as $key => $val) {
            $companytype[$key]['para_name'] = $companytype[$key]['para_name'] . '型';
        }
        $url = $this->findApiUrl() . $this->_url . "views?id=".$id;
        $views= Json::decode($this->findCurl()->get($url));
        return $this->render('views',[
            'views'=>$views,
            'companyname' => $paracompanyname,
            'companytype' => $companytype
        ]);
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
            $companytype = $this->actionCompanyTypeList();
            $paracompanyname = $this->actionParaCompanyNameList();
            foreach ($companytype as $key => $val) {
                $companytype[$key]['para_name'] = $companytype[$key]['para_name'] . '型';
            }
            $url = $this->findApiUrl() . $this->_url . "views?id=" . $id;
            $views = Json::decode($this->findCurl()->get($url));
            return $this->render('update', [
                'views' => $views,
                'companyname' => $paracompanyname,
                'companytype' => $companytype
            ]);
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

    //获取公司类型
    public function actionCompanyTypeList()
    {
        $url = $this->findApiUrl() . $this->_url . "para-company-type-list";
        return Json::decode($this->findCurl()->get($url));
    }

    //获取运输方式
    public function actionParaCompanyNameList()
    {
        $url = $this->findApiUrl() . $this->_url . "para-company-name-list";
        return Json::decode($this->findCurl()->get($url));
    }
}