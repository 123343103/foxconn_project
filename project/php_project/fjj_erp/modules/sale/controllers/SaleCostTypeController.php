<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/2/17
 * Time: 上午 09:57
 */

namespace app\modules\sale\controllers;
use yii;
use yii\helpers\Json;
use app\controllers\BaseController;
/* 销售业务费用类型控制器*/
class SaleCostTypeController extends \app\controllers\BaseController {
    private $_url = 'sale/sale-cost-type/';

    public function actionIndex(){
        $url = $this->findApiUrl() . 'sale/sale-cost-type/index';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        return $this->render("index");
    }

    public function actionCreate(){
        if (Yii::$app->request->getIsPost()){
            $postData = Yii::$app->request->post();
            $postData['SaleCostType']['create_by'] = Yii::$app->user->identity->staff_id;
//            dumpE($postData);
            $url = $this->findApiUrl() . $this->_url . 'create';
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($postData));
            $data = json_encode($curl->post($url));
            dumpJ($data);
            if ($data->status == 1){
                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            }else{
                return Json::encode(['msg' => "發生未知錯誤，新增失敗", "flag" => 0]);
            }
        }else{
            $costList = $this->getCostListAll();
            $saleCostListValue = [];
            foreach ($costList as $key => $val) {
                $saleCostListValue[$val['stcl_id']] = $val['stcl_code'];
            }
//            dumpE($saleCostListValue);
            return $this->render("create",[
                'saleCostListValue'=>$saleCostListValue,
                'costList' => Json::encode($costList),
            ]);
        }

    }
    /*
     * 獲取所有費用分類
     */
    public function getCostListAll(){
        $url = $this->findApiUrl() . $this->_url . "cost-list";
        return Json::decode($this->findCurl()->get($url));
    }
    /*
     * AJAX获取单条分类信息
     */
     public function actionGetCostList($id){
         $url = $this->findApiUrl() . $this->_url . 'get-cost-list?id='.$id;
         $costOne = Json::decode($this->findCurl()->get($url));
         if(count($costOne)){
             return $this->findCurl()->get($url);
         }else{
             return count($costOne);
         }
     }

    public function actionSelectCom()
    {
//        $url = $this->findApiUrl() . "ptdt/firm-report/select-coms";
//        $queryParam = Yii::$app->request->queryParams;
//        if (!empty($queryParam)) {
//            $url .= "?" . http_build_query($queryParam);
//        }
//        $dataProvider = $this->findCurl()->get($url);
//        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
//            return $dataProvider;
//        }
        return $this->renderAjax('select-com', [
//            'dataProvider' => $dataProvider,
        ]);
    }
}