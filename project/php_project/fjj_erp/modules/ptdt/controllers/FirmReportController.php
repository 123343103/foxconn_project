<?php

namespace app\modules\ptdt\controllers;

use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use yii\helpers\Json;
use yii;
use yii\helpers\Url;
class FirmReportController extends BaseController
{
    private $_url = "ptdt/firm-report/";  //对应api控制器URL
    public function beforeAction($action)
    {
        $this->ignorelist=array_merge($this->ignorelist,[
            "/ptdt/firm-report/select-com",
            "/ptdt/firm-report/firm-info",
            "/hr/staff/get-staff-info",
            "/ptdt/firm-report/analysis-com",
            "/ptdt/firm-report/analysis-report",
            "/ptdt/firm-negotiation/load-dvlp",
            "/ptdt/firm-negotiation/dvlp-info",
            "/ptdt/product-dvlp/get-product-type",
            "/ptdt/firm-report/add-check",
            "/ptdt/firm-report/get-visit-manager",
            "/ptdt/firm-report/load-dvlp"
        ]);
        return parent::beforeAction($action);
    }
    /**
     * @return mixed|string
     * 首页
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl() . "ptdt/firm-report/index?companyId=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = Json::decode($this->findCurl()->get($url));
            foreach ($dataProvider['rows'] as $key => $val){
                if($val['report_status'] == '40' || $val['report_status'] == '50' || $val['report_status'] == '10'){
                    $dataProvider['rows'][$key]['report_code']='<a href="'. Url::to(['/ptdt/firm-report/view','id'=>$val['pfr_id']]).'">'.$val['report_code'].'</a>';
                }else{
                    $dataProvider['rows'][$key]['report_code']='<a href="'. Url::to(['/ptdt/firm-report/view','id'=>$val['pfr_id'],'type'=>'2']).'">'.$val['report_code'].'</a>';
                }
            }
            return Json::encode($dataProvider);
        }
        $downList = $this->getDownList();
        $productType = $downList['productTypes'];
        $productTypeIdToValue = [];
        foreach ($productType as $key => $val) {
            $productTypeIdToValue[$val['category_id']] = $val['category_sname'];
        }
        $downList['productTypes']=$productTypeIdToValue;
        $columns=$this->getField("/ptdt/firm-report/index");
//        $child=$this->getField("/ptdt/firm-report/load-report");
//        dumpE($child);
        return $this->render('index', [
            'downList'=>$downList,
            'queryParam'=>$queryParam['PdFirmReportSearch'],
            'columns' =>$columns,
//            'child'=>$child
        ]);
    }

    /**
     * @param null $pfrId
     * @return string
     * 新增
     */
    public function actionAdd($firmId = null){
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $msg = "新增成功";
            if($postData['PdFirmReport']['report_status'] == 40){
                $msg="提交送审成功";
            }
            $postData['PdFirmReport']['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['PdFirmReport']['company_id'] = Yii::$app->user->identity->company_id;
            $url = $this->findApiUrl() . $this->_url . "add";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['message']);
                if($postData['PdFirmReport']['report_status'] == 40 && !empty($data['data']['id'])){
                    $this->check($data['data']['id']);
                    return Json::encode(['msg' => $msg, "flag" => 1, "url" => Url::to(['view','id'=>$data['data']['id'],'childId'=>$data['data']['childId'],'type'=>'1'])]);
                }
                return Json::encode(['msg' => $msg, "flag" => 1, "url" => Url::to(['view','id'=>$data['data']['id'],'childId'=>$data['data']['childId'],'type'=>'2'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        } else {
            $downList = $this->getDownList();
            $productType = $downList['productTypes'];
            $productTypeIdToValue = [];
            foreach ($productType as $key => $val) {
                $productTypeIdToValue[$val['category_id']] = $val['category_sname'];
            }
            $downList['productTypes']=$productTypeIdToValue;
            $result = '';
            if ($firmId != null) {
                $result = $this->getAddModel($firmId);
            }
            return $this->render('add', [
                'downList' => $downList,
                'result' => $result,
            ]);
        }
    }


    /**
     * @param null $id
     * @param null $childId
     * @return string
     * 修改
     */
    public function actionUpdate($id=null,$childId=null){
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $msg="保存成功";
            if($postData['PdFirmReport']['report_status'] == 40){
                $msg="提交送审成功";
                //送审
                $this->check($id);
            }
            $postData['PdFirmReport']['update_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id . "&childId=" . $childId;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
//            dumpE($data);
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                if($postData['PdFirmReport']['report_status'] == 40){
                    $this->check($id);
                    return Json::encode(['msg' => $msg, "flag" => 1, "url" => Url::to(['view','id'=>$id,'childId'=>$childId,'type'=>'1'])]);
                }
                return Json::encode(['msg' => $msg, "flag" => 1, "url" => Url::to(['view','id'=>$id,'childId'=>$childId,'type'=>'2'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }else{
            $downList = $this->getDownList();
            $productType = $downList['productTypes'];
            $productTypeIdToValue = [];
            foreach ($productType as $key => $val) {
                $productTypeIdToValue[$val['category_id']] = $val['category_sname'];
            }
            $downList['productTypes']=$productTypeIdToValue;
            $result = $this->getUpdateModel($id,$childId);
            return $this->render('update',[
                'downList'=>$downList,
                'result'=>$result,
            ]);
        }
    }

    /**
     * @param null $id
     * @param null $childId
     * @param null $type
     * @return string
     * 详情
     */
    public function actionView($id = null,$childId = null,$type=null){
        $model = $this->getModel($id);
        if($childId == null){
            $childModel = $this->getNewChild($id);
        }else{
            $childModel = $this->getChildModel($childId);
        }
        $verify = $this->getVerify($id,$vtype=12);
        return $this->render('view', [
            'model'=>$model,
            'childModel' => $childModel,
            'type'=>$type,
            'verify'=>$verify
        ]);
    }

    /**
     * @param null $id
     * @param null $childId
     * @return string
     * 送审
     */
    public function actionCheck($id)
    {
        $dataResult=$this->check($id);
        if($dataResult){
            return Json::encode(["msg" => "送审成功", "flag" => 1]);
        }else{
            return Json::encode(["msg" => "送审失敗", "flag" => 0]);
        }
    }
    /**
     * 送审
     * @param $id
     */
    protected function check($id){
        $data['id']=$id;
        $data['type']=12;
        $data['staff']=Yii::$app->user->identity->staff_id;
        //送审,审核流程
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
        $verifyUrl = $this->findApiUrl() . '/system/verify-record/verify-record';
        return Json::decode($curl->post($verifyUrl));
//        dumpE($dataResult);
        //改变单据状态
//        $url = $this->findApiUrl().$this->_url."check?id=".$id;
    }
    /**
     * @param null $id
     * @param null $childId
     * @return string
     * 审核详情
     */
    public function actionCheckView($id=null,$childId=null){
        $model = $this->getModel($id);
        $firmCompared = $model['firmCompared'];
        $childModel = $this->getChildModel($childId);
        $verify = $this->getVerify($id,$type=12);
        if($firmCompared){
            $i=0;
            foreach ($firmCompared as $key=> $val){
                $lists[$i] = $this->getCompared($val['firm_id'],$i);
                $i++;
            }
        }else{
            $lists = $this->getCompared($model['firm_id'],$i=null);
        }
        return $this->render('check', [
            'model'=>$model,
            'childModel' => $childModel,
            'firmCompared'=>$firmCompared,
            "lists"=>$lists,
            'verify'=>$verify
        ]);
    }

    /**
     * @param null $id
     * @return string
     * 呈报分析表
     */
    public function actionAnalysis($id=null){
        $model = $this->getModel($id);
        $firmCompared = $model['firmCompared'];
        if($firmCompared){
            $i=0;
            foreach ($firmCompared as$key=> $val){
                $lists[$i] = $this->getCompared($val['firm_id'],$i);
                $i++;
            }
        }else{
            $lists = $this->getCompared($model['firm_id'],$i=null);
        }
        return $this->render('analysis', [
            'model'=>$model,
            'firmCompared'=>$firmCompared,
            "lists"=>$lists
        ]);

    }

    /**
     * @param null $id
     * @param null $childId
     * @return string
     * 删除
     */
    public function actionDelete($id=null,$childId=null){
        if($id != null && $childId == null){
            $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        }
        elseif($id != null && $childId != null){
            $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id . "&childId=" . $childId;
        }
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失败", "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 加载呈报子表信息
     */
    public function actionLoadReport($id){
        $url = $this->findApiUrl() . $this->_url . "load-report?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
    }

    /**
     * @param $id
     * @return mixed
     * 加载商品信息
     */
    public function actionLoadProduct($id){
        $url = $this->findApiUrl() . $this->_url . "load-products?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
    }

    /**
     * @param $id
     * @param $type
     * @return mixed
     * 签核记录
     */
    public function getVerify($id,$vtype){
        $url = $this->findApiUrl() . "/system/verify-record/find-verify?id=" . $id."&type=".$vtype;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    /**
     * @param $code
     * @return mixed
     *陪同人员
     */
    public function actionGetVisitManager($code){
        $url = $this->findApiUrl().$this->_url.'get-visit-manager?code='.$code;
        return $this->findCurl()->get($url);
    }

    /**
     * @return mixed|string
     * 选择厂商
     */
    public function actionSelectCom(){
        $this->layout = '@app/views/layouts/ajax';
        $url = $this->findApiUrl() . "ptdt/visit-plan/select-com";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
        return $this->render('select-com', [
            'dataProvider' => $dataProvider,
            'queryParam'=>$queryParam
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * 厂商信息带出
     */
    public function actionFirmInfo($id){
        $url = $this->findApiUrl().$this->_url."firm-info?id=".$id;
        $result = $this->findCurl()->get($url);
//        dumpE(Json::decode($result));
        return $result;
    }

    /**
     * 加载商品需求单
     * @return string
     */
    public function actionLoadDvlp(){
        $this->layout = '@app/views/layouts/ajax';
        $url = $this->findApiUrl() . "ptdt/firm-report/load-dvlp?PdRequirementSearch%5BcompanyId%5D=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
//        dumpJ($dataProvider);
        return $this->render('load-dvlp', [
            'dataProvider' => $dataProvider,
            'queryParam' => $queryParam
        ]);
    }

    /**
     * 加载商品信息
     * @param $id
     * @return string
     */
    public function actionDvlpInfo($id)
    {
        $url = $this->findApiUrl() . $this->_url."dvlp-info?id=".$id;
        $dataProvider = $this->findCurl()->get($url);
        return $dataProvider;
    }

    /**
     * @return mixed|string
     * 添加厂商
     */
    public function actionAnalysisCom($id=null){
        $this->layout = '@app/views/layouts/ajax';
        $a = '';
        if($id != null){
            $a = explode(',',$id);
        }
        $url = $this->findApiUrl().$this->_url."analysis-coms";
        $data = Json::decode($this->findCurl()->get($url));
        return $this->render('analysis-com', [
            'data' => $data,
            'str'=>$a,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * 分析信息
     */
    public function actionAnalysisReport($id){
        $url = $this->findApiUrl().$this->_url."analysis-report?id=".$id;
        $result = $this->findCurl()->get($url);
        return $result;
    }

    /**
     * @return mixed
     * 下拉菜单
     */
    public function getDownList(){
        $url = $this->findApiUrl() . $this->_url . "down-lists";
        return Json::decode($this->findCurl()->get($url));
    }



    /**
     * 獲取模型
     * @param $id
     * @return null|static
     * @throws \Exception
     */
    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if (!$model) {
            throw new yii\web\NotFoundHttpException("页面未找到");
        }
        return $model;
    }

    /**
     * @param $pfrId
     * @return mixed
     * 新增页带出厂商所有信息
     */
    private function getAddModel($firmId){
        $url = $this->findApiUrl().$this->_url."add-model?firmId=".$firmId;
        $childModel = Json::decode($this->findCurl()->get($url));
        return $childModel;
    }

    /**
     * @param $id
     * @param $childId
     * @return mixed
     * 修改页带出厂商所有信息
     */
    private function getUpdateModel($id,$childId){
        $url = $this->findApiUrl().$this->_url."update-model?id=".$id."&childId=".$childId;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    /**
     * @param $id
     * @param $i
     * @return mixed
     * 厂商对比
     */
    private function getCompared($id,$i){
        if($id != null && $i === null){
            $url = $this->findApiUrl().$this->_url."firm-compared?id=".$id;
            $lists =  Json::decode($this->findCurl()->get($url));
        }else{
            $url = $this->findApiUrl().$this->_url."firm-compared?id=".$id."&i=".$i;
            $lists =  Json::decode($this->findCurl()->get($url));
        }
        return $lists;
    }

    /**
     * @param $id
     * @return mixed
     * @throws yii\web\NotFoundHttpException
     * 呈报子表信息
     */
    private function getChildModel($id){
        $url = $this->findApiUrl().$this->_url."child-model?id=".$id;
        $childModel = Json::decode($this->findCurl()->get($url));
        if(!$childModel){
            throw new yii\web\NotFoundHttpException("页面未找到");
        }
        return $childModel;
    }

    /**
     * @param $id
     * @return mixed
     * @throws yii\web\NotFoundHttpException
     * 获取最新一条呈报子表
     */
    private function getNewChild($id){
        $url = $this->findApiUrl().$this->_url."check-child?id=".$id;
        $childModel = Json::decode($this->findCurl()->get($url));
        if(!$childModel){
            throw new yii\web\NotFoundHttpException("页面未找到");
        }
        return $childModel;
    }
}
