<?php
namespace app\modules\ptdt\controllers;

use app\modules\system\models\SystemLog;
use yii;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;


/**
 * 廠商拜訪計畫控制器
 * User: F1678086
 *Date: 2016/9/21
 */
class VisitPlanController extends BaseController{
    private $_url = "ptdt/visit-plan/";  //对应api控制器URL
    public function beforeAction($action)
    {
        $this->ignorelist=array_merge($this->ignorelist,[
            "/ptdt/visit-plan/select-com",
            "/ptdt/visit-plan/firm-info",
            "/ptdt/visit-plan/add-firm",
            "/ptdt/visit-plan/firm-sname"
        ]);
        return parent::beforeAction($action);
    }
    /**
     * @return mixed|string
     * 廠商拜訪計畫列表
     */
    public function actionIndex(){
        $url = $this->findApiUrl() . "ptdt/visit-plan/index?companyId=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = Json::decode($this->findCurl()->get($url));
            foreach ($dataProvider['rows'] as $key => $val){
                $dataProvider['rows'][$key]['pvp_plancode']='<a href="'. yii\helpers\Url::to(['/ptdt/visit-plan/view','id'=>$val['pvp_planID']]).'">'.$val['pvp_plancode'].'</a>';
            }
            return Json::encode($dataProvider);
        }
        $downList = $this->getDownList();
        $columns=$this->getField("/ptdt/visit-plan/index");
        return $this->render('index',[
            'downList'=>$downList,
            'queryParam'=>$queryParam,
            'columns' =>$columns
        ]);
    }

    /**
     * @param $id
     * @return string
     * 厂商拜访计划详情
     */
    public function actionView($id){
        $model = $this->getModel($id);
        $downList = $this->getDownList();
//        dumpE($model);
        return $this->render('view',[
            'model'=>$model,
            'downList'=>$downList
        ]);
    }


    /**
     * @param null $id
     * @return string
     * 新增厂商拜访计划
     */
    public function actionAdd($id=null){
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $postData['PdVisitPlan']['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['PdVisitPlan']['company_id'] = Yii::$app->user->identity->company_id;
            $url = $this->findApiUrl() . $this->_url . "add";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg']);
                return Json::encode(['msg' => "新增拜访计划成功", "flag" => 1, "url" => yii\helpers\Url::to(['view','id'=>$data['data']['id']])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        }else{
            $downList = $this->getDownList();
        }
        if($id==null){
            return $this->render('add',[
                'downList'=>$downList,
                'id'=>$id
            ]);
        }else{
            $url = $this->findApiUrl() . $this->_url . "firm-info?id=".$id;
            $firmMessage = Json::decode($this->findCurl()->get($url));
            return $this->render('add',[
                'downList'=>$downList,
                'firmMessage'=>$firmMessage,
                'id'=>$id
            ]);
        }

    }


    /**
     * @param $id
     * @return string
     * 修改厂商拜访计划
     */
    public function actionEdit($id){
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['PdVisitPlan']['update_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "edit?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg']);
                return Json::encode(['msg' => "修改成功", "flag" => 1, "url" => yii\helpers\Url::to(['view','id'=>$data['data']['id']])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }else{
            $model = $this->getModel($id);
            $downList = $this->getDownList();
            return $this->render('edit',[
                'model'=>$model,
                'downList'=>$downList,
                'id'=>$id
            ]);
        }

    }

    /**
     * @return string
     * 新增--修改页面新增厂商信息弹出层
     */
    public function actionAddFirm(){
        $this->layout = '@app/views/layouts/ajax';
        $downList = $this->getDownList();
        return $this->render('add-firm',[
            'downList' => $downList,
        ]);
    }

    /**
     * @param $id
     * @return string
     * 删除拜访计划
     */
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失敗", "flag" => 0]);
        }
    }
    /**
     * @param $id
     * @return mixed
     * 查询该拜访计划  在拜访履历 谈判 中是否引用
     * F1678089 -- 龚浩晋
     */
    public function actionDeleteCount($id){
        $url = $this->findApiUrl() . $this->_url . "delete-count?id=" . $id;
        $result = $this->findCurl()->put($url);
        return $result;
    }
    /**
     * @param $code
     * @return int|mixed
     * 获取陪同人员信息
     */
    public function actionGetVisitManager($code)
    {
        $url = $this->findApiUrl() . $this->_url . "get-visit-manager?code=".$code;
        $arr = Json::decode($this->findCurl()->get($url));
        if(count($arr)){
            return $this->findCurl()->get($url);
        }else{
            return count($arr);
        }
    }
    /**
     * 链接新增拜访履历
     * @param $id
     * @return string
     */
    public function actionAddResume($id){
        $model = $this->getModel($id);
        return Json::encode($model->firm_id);
    }

    public function getDownList(){
        $url = $this->findApiUrl() . $this->_url . "down-list";
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * @return mixed|string
     * 选择厂商
     */
    public function actionSelectCom(){
        $url = $this->findApiUrl() . "ptdt/visit-plan/select-com";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
//        dumpJ($dataProvider);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
        return $this->renderAjax('select-com', [
            'dataProvider' => $dataProvider,
            'queryParam' =>$queryParam
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * 带出厂商信息
     */
    public function actionFirmInfo($id){
        $url = $this->findApiUrl().$this->_url."firm-info?id=".$id;
        $result = $this->findCurl()->get($url);
        return $result;
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
     * @return string
     * 验证厂商名称唯一性
     */
    public function actionFirmSname(){
        $name=Yii::$app->request->get()['name'];
        $data['name']=$name;
        $url = $this->findApiUrl() . $this->_url . "firm-sname";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
        $result = Json::decode($curl->post($url));
        return $result;
    }


}