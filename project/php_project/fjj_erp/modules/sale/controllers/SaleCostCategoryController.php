<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/2/21
 * Time: 下午 01:39
 */
namespace app\modules\sale\controllers;

use Yii;
use yii\helpers\Json;

/*销售业务分类控制器*/

class SaleCostCategoryController extends \app\controllers\BaseController
{
    private $_url = 'sale/sale-cost-category/';

    public function actionIndex()
    {
        $url = $this->findApiUrl() . 'sale/sale-cost-category/index';
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

    public function actionCreate()
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $postData['SaleCostList']['create_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . 'create';
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->post($url));
            if ($data->status == 1) {
                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "發生未知錯誤，新增失敗", "flag" => 0]);
            }
        } else {
            $saleCostType = $this->getCostType();//獲取費用類別列表
            $saleCostTypeValue = [];
            foreach ($saleCostType as $key => $val) {
                $saleCostTypeValue[$val['scost_id']] = $val['scost_sname'];
            }
            return $this->render("create",
                [
                    'saleCostTypeValue' => $saleCostTypeValue,
                ]
            );
        }
    }

    public function actionUpdate($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $postDate = Yii::$app->request->post();
            $postDate['SaleCostList']['update_by'] = Yii::$app->user->identity->staff_id;
//            dumpE($postDate);
            $url = $this->findApiUrl() . $this->_url . 'update?id=' . $id ;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postDate));
            $data = json_decode($curl->put($url));
            if ($data->status == 1) {
                return Json::encode(['msg' => '编辑成功', 'flag' => 1, 'url' => yii\helpers\Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "發生未知錯誤，编辑失敗", "flag" => 0]);
            }
        } else {
            $model = $this->findModel($id);
            $stclStatus = array(
                ''=>'请选择',
                '1'=>'有效',
                '0'=>'无效',
            );
            $saleCostType = $this->getCostType();//獲取費用類別列表
            $saleCostTypeValue = [];
            foreach ($saleCostType as $key => $val) {
                $saleCostTypeValue[$val['scost_id']] = $val['scost_sname'];
            }
            return $this->render("update", [
                'model'=>$model,
                'saleCostTypeValue' => $saleCostTypeValue,
                'stclStatus'=>$stclStatus
            ]);

        }
    }
    public function actionView($id){
        $model = $this->findModel($id);
        $scostType = $model->scost_id;
        $scostTypeName = $this->getCostTypeName($scostType);
        $typeName = $scostTypeName['scost_sname'];
        if ($model->stcl_status == 1){
            $stclStatus = '有效';
        }else{
            $stclStatus = '无效';
        }
        return $this->render('view',[
           'model'=>$model,
            'typeName'=>$typeName,
            'stclStatus'=>$stclStatus
        ]);
    }
    public function actionDelete($id){
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $result = $this->findCurl()->delete($url);
        if (json_decode($result)->status == 1) {
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失敗", "flag" => 0]);
        }
    }

    /*
     * 获取费用类型
     */
    public function getCostType()
    {
        $url = $this->findApiUrl() . $this->_url . "cost-type";
        return Json::decode($this->findCurl()->get($url));
    }
    /*
     * 获取费用类型名称
     */
    public function getCostTypeName($id){
        $url = $this->findApiUrl() . $this->_url . "cost-type-name?id=".$id;
        return Json::decode($this->findCurl()->get($url));
    }

    public function findModel($id){
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url), false);
        if (!$model) {
            throw new yii\web\NotFoundHttpException("頁面未找到");
        }
        return $model;
    }
}