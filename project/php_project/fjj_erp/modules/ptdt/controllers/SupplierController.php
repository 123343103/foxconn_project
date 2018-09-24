<?php
namespace app\modules\ptdt\controllers;

use app\controllers\BaseController;
use app\modules\ptdt\models\PdMaterialCode;
use app\modules\ptdt\models\PdSupplier;
use app\modules\ptdt\models\PdFirm;
use app\modules\system\models\SystemLog;
use yii\helpers\Json;
use yii\helpers\Url;
use yii;
/**
 * 供应商控制器
 * F3858995
 * 2016/10/25
 */
class SupplierController extends BaseController{


    private $_url = "ptdt/supplier/";
    /**
     * 首页
     * @return string
     */
    public function actionIndex(){

            $url = $this->findApiUrl().$this->_url."index?PdSupplierSearch%5BcompanyId%5D=".Yii::$app->user->identity->company_id."&";
                if (!empty($queryParam=Yii::$app->request->queryParams)) {
                    $url .=http_build_query($queryParam);
                }
            $dataProvider = $this->findCurl()->get($url);
            if(Yii::$app->request->isAjax){
                return $dataProvider;
            }
            return $this->render('index',[
                'model'=>Json::decode($dataProvider)
            ]);
    }

    /**
     * 创建
     * @return string
     */
    public function actionCreate(){

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            //商品料号
            $postData['PdSupplier']['material_id']=isset($postData['PdSupplier']['material_id'])?serialize(array_unique(array_filter($postData['PdSupplier']['material_id']))):'';
            $postData['PdSupplier']['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['PdSupplier']['company_id'] = Yii::$app->user->identity->company_id;
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                return Json::encode(['msg' => "新增需求成功", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        }
        $downList=$this->getDownList();
        return $this->render("create",[
            'downList'=>Json::decode($downList),
        ]);
    }

    //更新
    public function actionUpdate($id){
        if ($postData = Yii::$app->request->post()) {
            $postData['PdSupplier']['update_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->put($url));
            if ($data->status == 1) {
                return Json::encode(['msg' => "修改供应商成功", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        } else {
            $url = $this->findApiUrl().$this->_url."info-all?id=".$id;
            $dataProvider = Json::decode($this->findCurl()->get($url));
            return $this->render("update",[
                'model'=>$dataProvider['model'],
                'materialList'=>$dataProvider['materialList'],
                'downList'=>$dataProvider['downList'],
                'mainList'=>$dataProvider['mainList'],
                'persionList'=>$dataProvider['persionList']
            ]);
        }
    }

    /**
     * 删除
     */
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $result = $this->findCurl()->delete($url);
        if (json_decode($result)->status == 1) {
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失敗", "flag" => 0]);
        }
    }

    /**
     * 查看
     */
    public function actionView($id)
    {
        $url = $this->findApiUrl() . $this->_url . "view?id=" . $id;
        $dataProvider = $this->findCurl()->get($url);
        $dataProvider = json_decode($dataProvider);
        return $this->render("view",[
            'model'=>$dataProvider->model,
            'mainList'=>$dataProvider->mainList,
            'persionList'=>$dataProvider->persionList
        ]);
    }

    /**
     * 选择厂商信息
     */
    public function actionSelectCom()
    {
////        $searchModel = new PdFirmQuery();
//        $url = $this->findApiUrl().$this->_url."index?PdSupplierSearch%5BcompanyId%5D=".Yii::$app->user->identity->company_id."&";
//        if (!empty($queryParam=Yii::$app->request->queryParams)) {
//            $url .=http_build_query($queryParam);
//        }
//        $dataProvider = Json::decode($this->findCurl()->get($url));
//        return $this->renderAjax('select-com', [
//            'dataProvider' => $dataProvider,
//        ]);

        $url = $this->findApiUrl() . "ptdt/visit-plan/select-com";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
        return $this->renderAjax('select-com', [
            'dataProvider' => $dataProvider,
            'queryParam' =>$queryParam
        ]);
    }

    /**
     * 选择商品信息
     */
    public function actionSelectMaterial()
    {
        $url = $this->findApiUrl() . "ptdt/supplier/select-material";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        return $this->renderAjax('select-material', [
            'searchModel' => $queryParam,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 加载供应商资料
     * @return string
     */
    public function actionLoadInfo($id)
    {
        $url = $this->findApiUrl().$this->_url."load-info?id=".$id;
        $dataProvider = $this->findCurl()->get($url);

        $data=Json::decode($dataProvider);
        return $this->renderAjax('load-info',[
            'dataProvider'=>$data['info'],
            'dataMaterial'=>$data['material'],
        ]);
    }

    /**
     * 加载供应商资料库信息
     * @return string
     */
    public function actionLoadData()
    {
        $id = Yii::$app->request->post('id');
        $model=PdSupplier::find()->select(['firm_id','supplier_id','supplier_code','create_at','supplier_pre_annual_sales','supplier_pre_annual_profit','supplier_source','material_id'])->where(['supplier_id'=>$id])->one();
        $MaterialCode='';
        $MaterialArr=unserialize($model->material_id);
        foreach ($MaterialArr as $val){
            $materialModel=PdMaterialCode::find()->where(['m_id'=>$val])->one();
            $MaterialCode[]=$materialModel;
        }
        return $this->renderAjax('load-data', [
            'model'=>$model,
            'materialCode'=>$MaterialCode
        ]);
    }

    //厂商信息
    public function actionFirmInfo($id){
        $firmData = PdFirm::find()->where(['firm_id'=>$id])->one();
        $categoryType = $firmData->CategoryName;
        return yii\helpers\Json::encode([$firmData,$categoryType]);
    }

    //主营商品信息
    public function actionMaterialInfo($id){
        $data = PdMaterialCode::find()->where(['m_id'=>$id])->one();
        return yii\helpers\Json::encode([$data]);
    }

    protected function getDownList(){
        $url = $this->findApiUrl().$this->_url."down-list";
        return $this->findCurl()->get($url);
    }
}