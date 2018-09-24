<?php

namespace app\modules\sale\controllers;
use Yii;
use yii\helpers\Json;
class SaleTripController extends \app\controllers\BaseController
{
    private $_url = 'sale/sale-trip/'; //对应api

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
//        dumpE($queryParam);
        $dataProvider = $this->findCurl()->get($url);
//        dumpJ($dataProvider);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
        return $this->render('index', [
            'indexdata' => 'this is saletripcontroller data',
        ]);
    }
    /*
     * 新增出差申請
     */
    public function actionCreateTravelApply(){
        if (Yii::$app->request->getIsPost()){
            $post = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status']==1){
                return Json::encode(['msg' => "申請提交成功", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            }else{
                return Json::encode(['msg' => "發生未知錯誤，提交失敗", "flag" => 0]);
            }
        }else{
            return $this->render('create-travel-apply');
        }
    }
    /*
     * 新增消單報告
     */
    public function actionCreateReport(){
        return $this->render('create-report');
    }
    /*
     * 新增费用报销
     */
    public function actionCreateCost(){
        return $this->render('create-cost');
    }

}
