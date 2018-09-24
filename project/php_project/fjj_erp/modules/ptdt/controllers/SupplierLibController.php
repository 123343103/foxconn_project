<?php
namespace app\modules\ptdt\controllers;

use app\controllers\BaseController;
use app\modules\ptdt\models\BsVendorMainlist;
use app\modules\ptdt\models\PdVendorconetionPersion;
use yii\helpers\Json;
use yii;
use yii\helpers\Url;

class SupplierLibController extends BaseController{
    private $_url = "ptdt/supplier-lib/";
    /**
     * 首页
     */
    public function actionIndex(){
//        $searchModel = new PdSupplierSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $url = $this->findApiUrl().$this->_url . 'index';
        if (!empty($queryParam = Yii::$app->request->queryParams)) {
            $url .= '?' . http_build_query($queryParam);
        }
//        $dataProvider = $this->findCurl()->get($url);
        if(Yii::$app->request->isAjax){
//            return $dataProvider;
            $dataProvider = json::decode($this->findCurl()->get($url),true);
            foreach ($dataProvider['rows'] as $key => $val){
                $dataProvider['rows'][$key]['supplier_code']='<a href="'. Url::to(['view','id'=>$val['supplier_id']]).'">'.$val['supplier_code'].'</a>';
            }
//            dumpE($dataProvider);
            return Json::encode($dataProvider['rows']);
        }
        $downList=$this->getIndexDownList();
//        dumpE($downList);
        return $this->render("index",[
//            'searchModel'=>$searchModel,
//            'dataProvider' => $dataProvider,
            'downList' => $downList
        ]);
    }

    /**
     * 修改编辑
     */
    public function actionEdit($id){
        if ($postData = Yii::$app->request->post()) {
//            $postData['PdSupplier']['update_by'] = Yii::$app->user->identity->staff_id;
//            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
//            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
//            $data = json_decode($curl->put($url));
//            if ($data->status == 1) {
//                return Json::encode(['msg' => "修改供应商成功", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
//            } else {
//                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
//            }
        } else {
            $url = $this->findApiUrl().$this->_url."info-all?id=".$id;
            $dataProvider = Json::decode($this->findCurl()->get($url));
            return $this->render("edit",[
//                'model'=>$dataProvider['model'],
//                'materialList'=>$dataProvider['materialList'],
//                'downList'=>$dataProvider['downList'],
//                'mainList'=>$dataProvider['mainList'],
//                'persionList'=>$dataProvider['persionList']
            ]);
        }
    }

    /**
     * 查看详情
     */
    public function actionView($id)
    {
        $url = $this->findApiUrl() . $this->_url . "view?id=" . $id;
        $dataProvider = $this->findCurl()->get($url);
        $dataProvider = json_decode($dataProvider);
//        dumpE($dataProvider);
        return $this->render("view",[
            'model'=>$dataProvider->model,
            'mainList'=>$dataProvider->mainList,
            'persionList'=>$dataProvider->persionList
        ]);
    }

    /**
     * 加载供应商资料库
     * @return string
     */
    public function actionLoadInfo()
    {
        $id = Yii::$app->request->post('id');
        $product=BsVendorMainlist::find()->where(['vendor_id'=>$id])->andWhere(['vmanil_status'=>BsVendorMainlist::STATUS_DEFAULT])->all();
        $contacts=PdVendorconetionPersion::find()->where(['vendor_id'=>$id])->andWhere(['vcper_status'=>PdVendorconetionPersion::STATUS_DEFAULT])->all();
        return $this->renderAjax('load-info', [
            'product'=>$product,
            'contacts'=>$contacts
        ]);
    }

    protected function getDownList()
    {
        $url = $this->findApiUrl().$this->_url."down-list";
        return json::decode($this->findCurl()->get($url),true);
    }

    protected function getIndexDownList()
    {
        $url = $this->findApiUrl().$this->_url."index-down-list";
        return json::decode($this->findCurl()->get($url),true);
    }


}