<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/7/10
 * Time: 下午 03:54
 * 商品异动控制器
 */

namespace app\modules\warehouse\controllers;
use app\controllers\BaseController;
use app\modules\hr\models\HrOrganization;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;


class InvChangehController extends BaseController
{
    private $_url = 'warehouse/inv-changeh/';  //对应api控制器URL

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;
//        dumpE($queryParam);
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) { //如果是分页获取数据则直接返回数据
            $data = Json::decode($dataProvider);
            foreach ($data["rows"] as &$item) {
                //给报废单号加一个a标签
                $item["chh_code"] = Html::a($item["chh_code"],['view', 'id' => $item['chh_id']],['class'=>'viewitem']);
            }
            return Json::encode($data);
        }
        $downList = $this->getDownList();
        if (\Yii::$app->request->get('export')) {
            $this->exportFiled(Json::decode($dataProvider)['rows']);
        }
        $businessType = $this->findCurl()->get($this->findApiUrl() . $this->_url . "business-type");
//        dumpJ($dataProvider);
        $whname=$this->actionWhname();//仓库
        return $this->render('index', [
            'model' => Json::decode($dataProvider),
            'downlist' => $downList,
            'queryParam' => $queryParam['InvChangehSearch'],
            'businessType' => $businessType,
            'whname'=>$whname

        ]);
    }

    /**
     * 新增报废申请
     */
    public function actionCreate(){
        if(Yii::$app->request->post()){
            $postData = Yii::$app->request->post();
            $postData['InvChangeh']['review_by'] = Yii::$app->user->identity->staff_id;
            $postData['InvChangeh']['review_at'] = date('Y-m-d',time());
            $postData['InvChangeh']['create_at'] = date('Y-m-d',time());
            $postData['InvChangeh']['chh_status'] = 10;
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
//            dumpE($data);
            if ($data['status'] == 1){
//                return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']['id']])]);
                return Json::encode(['msg' => "新增成功!", "flag" => 1, "id"=>$data['data']['id'],"chh_type"=>$data['data']['chh_type'],"url" => Url::to(['view','id'=>$data['data']['id']])]);
            }else{
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        }else{
            $whname=$this->actionWhname();//仓库
            $downList = $this->getDownList();
            return $this->render("create", [
                'downList' => $downList,
                'whname'=>$whname
            ]);
        }
    }

    //获取登录账号所拥有权限的仓库

    //获取仓库名称
    public function actionWhname()
    {
        $url = $this->findApiUrl() ."warehouse/other-out-stock/get-wh-jurisdiction?staff_id=".Yii::$app->user->identity->staff_id;
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * @param $id
     * 编辑报废申请
     */
    public function actionUpdate($id){
        if(Yii::$app->request->post()){
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "update?id=".$id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1){
                return Json::encode(['msg' => "修改成功!", "flag" => 1,'id'=>$data['data']['id'],'chh_type'=>$data['data']['chh_type'], "url" => Url::to(['view','id'=>$data['data']['id']])]);
            }else{
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }else{
            $invChangeInfoH = $this->getModelH($id);//主表
//            dumpE($invChangeInfoH);
            $invChangeInfoL = $this->getModelL($id);//子表
            $downList = $this->getDownList();
            $whname=$this->actionWhname();//仓库
//            dumpE($invChangeInfoL[0]['create_by']);
//            dumpE($invChangeInfoH['chh_id']);
            return $this->render("update", [
                'invChangeInfoH' => $invChangeInfoH,
                'invChangeInfoL' => $invChangeInfoL,
                'downList' => $downList,
                'whname'=>$whname
            ]);
        }
    }

    /**
     * @param $id
     * 查看
     */
    public function actionView($id){
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
//        dumpE($model);
        $verify=$this->getVerify($id,$model[0]['chh_type']);//查看審核狀態
        if ($model) {
            return $this->render("view",[
                "model" => $model,
                "verify"=>$verify,
                'id'=>$id
            ]);
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    //查看审核状态
    public function getVerify($id,$type){
        $url = $this->findApiUrl() . "/system/verify-record/find-verify?id=" . $id."&type=".$type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    /**
     * @return string
     * 删除
     */
//    public function actionDeleteInv($id)
//    {
//        $url=$this->findApiUrl().$this->_url."delete-inv?id=".$id;
//        $result=Json::decode($this->findCurl()->get($url));
////        dumpE($result);
////        return $result;
//        if($result['status']==1){
//            SystemLog::addLog('删除报废单'.$result['data']);
//            return Json::encode(['msg'=>$result['msg'],'flag'=>1]);
//        }
//        return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
//    }
    public function actionDeleteInv($id){
        $url = $this->findApiUrl() . $this->_url . "delete-inv?id=" . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']['msg']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "删除失败", "flag" => 0]);
        }

    }

    /**
     * @return mixed|string
     * 商品列表
     */
    public function actionSelectProduct()
    {
        $params = Yii::$app->request->queryParams;
        $urls = $this->findApiUrl() . "system/display-list/get-url-field?url=/sale/sale-cust-order/create&user=" . Yii::$app->user->identity->user_id . "&type=";
        $columns = Json::decode($this->findCurl()->get($urls));
        $url = $this->findApiUrl() . $this->_url . 'select-product';
        $downList = $this->getDownList();
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        if (Yii::$app->request->isAjax) {
            return $this->findCurl()->get($url);
        }
        return $this->renderAjax('select-product', ['params' => $params, 'columns' => $columns, 'downList' => $downList]);
    }

    /**
     * 加载子表信息
     */
    public function actionGetProduct($id){
        $url = $this->findApiUrl() . $this->_url . "get-product?id=" . $id;
//        dumpJ($this->findCurl()->get($url));
        return $this->findCurl()->get($url);
    }
    //下拉数据
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "get-down-list";
        return json::decode($this->findCurl()->get($url));
    }
    //验证料号是否存在
    public function actionPdtValidate($id, $attr, $val, $scenario)
    {
        $val = urlencode($val);
        $url = $this->findApiUrl() . "ptdt/product-library/" . "validate";
        $url = $url . "?id={$id}&attr={$attr}&val={$val}&scenario={$scenario}";
        return $this->findCurl()->get($url);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 报废主表
     */
    private function getModelH($id){
        $url = $this->findApiUrl() . $this->_url . "model?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 报废子表信息
     */
    private function getModelL($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }


    //获取储位

    public function actionSelectStore()
    {
        $params = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() .'/warehouse/warehouse-change/select-store';
        $downList = $this->getDownList();
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        if (Yii::$app->request->isAjax) {
            return $this->findCurl()->get($url);
        }
//        dumpE($params);
        return $this->renderAjax('select-store', ['params' => $params, 'downList' => $downList]);
    }

    //作废原因
    public function actionCanReason($id)
    {
        if($data=Yii::$app->request->post()){
            $url=$this->findApiUrl(). $this->_url .'can-reason';
            $url.='?id='.$id;
            return $this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data))->post($url);
        }
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render('can-reason');
    }

    //根据仓库id获取仓库代码
    public function actionWhCode($id)
    {
            $url=$this->findApiUrl(). $this->_url .'wh-code';
            $url.='?id='.$id;
            //$model = Json::decode($this->findCurl()->get($url));
            $model =$this->findCurl()->get($url);
            return $model;
    }


}
