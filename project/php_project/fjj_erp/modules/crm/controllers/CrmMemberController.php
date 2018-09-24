<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\models\UploadForm;
use app\modules\common\tools\ExcelToArr;
use app\modules\common\tools\SendMail;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\classes\Menu;
/**
 * CrmMemberController implements the CRUD actions for CrmMember model.
 */
class CrmMemberController extends BaseController
{
    public $_url = "crm/crm-member/";  //对应api控制器URL
    public function beforeAction($action)
    {
        $this->ignorelist=array_merge($this->ignorelist,[
            "/ptdt/firm/get-district",
            "/crm/crm-member/cust-validation",
            "/crm/crm-member/load-info",
            "/crm/crm-member/send-message",
            "/crm/crm-member/select-customer",
            "/crm/crm-member/select-com-one",
        ]);
        return parent::beforeAction($action);
    }
    /**
     * @return mixed|string
     * 列表页
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index?companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
//        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = Json::decode($this->findCurl()->get($url));
            foreach ($dataProvider['rows'] as $key => $val){
                if(Menu::isAction('/crm/crm-member/view')) {
                    $dataProvider['rows'][$key]['cust_filernumber'] = '<a href="' . Url::to(['view', 'id' => $val['cust_id']]) . '">' . $val['cust_filernumber'] . '</a>';
                }
            }
            return Json::encode($dataProvider);
        }
        $downList = $this->getDownList();
        $columns=$this->getField("/crm/crm-member/index");
        return $this->render('index', [
            'downList' => $downList,
            'queryParam' => $queryParam,
            'columns' =>$columns
        ]);

    }
    public function actionExport()
    {
        $url = $this->findApiUrl() . $this->_url . "index?export=1&companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        \Yii::$app->controller->action->id = 'index';
        SystemLog::addLog('导出会员列表');
        return $this->exportFiled($dataProvider['rows']);
    }
    /**
     * @return string
     * 新增
     */
    public function actionCreate()
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['CrmCustomerInfo']['company_id'] = Yii::$app->user->identity->company_id;
            $post['CrmCustomerInfo']['create_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg']);
                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']['id']])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        } else {
            $downList = $this->getDownList();
            return $this->render('create', [
                'downList' => $downList,
            ]);
        }

    }

    /**
     * @param $id
     * @return string
     * 修改
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['CrmCustomerInfo']['update_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "修改信息成功", "flag" => 1, "url" => Url::to(['view','id'=>$id])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        } else {
            $model = $this->getModel($id);
            $downList = $this->getDownList();
            $districtId = $model['cust_district_2'];
            $districtAll = $this->getAllDistrict($districtId);
            return $this->render('update', [
                'model' => $model,
                'downList' => $downList,
                'districtAll' => $districtAll,
            ]);
        }
    }

    /**
     * @param $id
     * @return string
     * 详情
     */
    public function actionView($id)
    {
        $result = $this->getModel($id);
        $status = $this->getStatus($id);
        $downList = $this->getDownList();
        $districtId = $result['cust_district_2'];
        $districtAll = $this->getAllDistrict($districtId);
        return $this->render('view', [
            'result' => $result,
            'downList' => $downList,
            'districtAll' => $districtAll,
            'status' => $status
        ]);
    }

    /**
     * @param $id
     * @return string
     * 删除客户
     */
    public function actionDeleteCustomer($id){
        $url = $this->findApiUrl() . $this->_url . "delete-customer?id=" . $id;
        $result = Json::decode($this->findCurl()->delete($url));
//        dumpE($result);
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => $result['msg'], "flag" => 1]);
        } else {
            return Json::encode(["msg" => '客户被引用!', "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return string
     * 删除回访记录等信息
     */
//    public function actionDelete($id){
//        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
//        $result = Json::decode($this->findCurl()->delete($url));
//        if ($result['status'] == 1) {
//            SystemLog::addLog($result['data']);
//            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
//        } else {
//            return Json::encode(["msg" => $result['msg'].' 删除失败', "flag" => 0]);
//        }
//    }

    /**
     * @param $id
     * @return string
     * 新增提醒事项
     */
    public function actionCreateReminders($id=null)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['CrmImessage']['imesg_sentman'] = Yii::$app->user->identity->staff_id;
            $postData['CrmImessage']['imesg_sentdate'] = date('Y-m-d h:i:s', time());
            $url = $this->findApiUrl() . $this->_url . "create-reminders";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "新增提醒成功", "flag" => 1]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        } else {
            $this->layout = '@app/views/layouts/ajax';
            $model = $this->getModel($id);
            $employee = $this->getEmployee();
            return $this->render('reminders', [
                'model' => $model,
                'employee'=>$employee,
            ]);
        }
    }
    /**
     * @param $id
     * @return string
     * 修改提醒事项
     */
    public function actionUpdateReminders($id=null)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['CrmImessage']['imesg_sentman'] = Yii::$app->user->identity->staff_id;
            $postData['CrmImessage']['imesg_sentdate'] = date('Y-m-d H:i:s', time());
            $url = $this->findApiUrl() . $this->_url . "update-reminders?id=".$id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "修改提醒成功", "flag" => 1]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        } else {
            $this->layout = '@app/views/layouts/ajax';
            $reminder = $this->getReminder($id);
            $model = $this->getModel($reminder['cust_id']);
            $employee = $this->getEmployee();
            return $this->render('reminders', [
                'model' => $model,
                'employee'=>$employee,
                'reminder' => $reminder,
            ]);
        }
    }

    /**
     * @param null $id
     * @return string
     * 删除提醒
     */
    public function actionDeleteReminders($id=null){
        $url = $this->findApiUrl() . "/crm/crm-return-visit/delete-reminders?id=" . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失败", "flag" => 0]);
        }
    }

    /**
     * 新增回访
     */
    public function actionVisitCreate($id=null,$ctype=null){
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
//            $post['CrmVisitRecordChild']['sil_time'] = serialize([$post['day'], $post['hour'], $post['minute']]);
            $post['CrmVisitRecordChild']['title'] = '拜访'.$post['cust_name'];
            $post['CrmVisitRecordChild']['start'] =  $post['arriveDate'];
            $post['CrmVisitRecordChild']['end']   =  $post['leaveDate'];
            $post['CrmVisitInfoChild']['color'] = '#F00';
            $post['CrmVisitRecord']['create_by'] = $post['CrmVisitRecordChild']['create_by'] =Yii::$app->user->identity->staff_id;
            $post['CrmVisitRecord']['company_id'] = $post['CrmVisitRecordChild']['company_id'] =Yii::$app->user->identity->company_id;
            $url = $this->findApiUrl() . "/crm/crm-return-visit/create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['message']);
                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" => Url::to(['visit-view','id'=>$data['data']['id'],'childId'=>$data['data']['childId'],'ctype'=>$ctype])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        } else {
            $downList = $this->getDownList();
            $url = $this->findApiUrl() . '/crm/crm-visit-info/visit-person?id=' . Yii::$app->user->identity->staff_id;
            $visitPerson = json_decode($this->findCurl()->get($url));
            if (!empty($id)) {
                $member = $this->getModel($id);
                $districtId = $member['cust_district_2'];
                $districtAll = $this->getAllDistrict($districtId);
                return $this->render('/crm-return-visit/create', [
                    'downList' => $downList,
                    'member' => $member,
                    'districtAll' => $districtAll,
                    'visitPerson' => $visitPerson,
                    'id' => $id
                ]);
            }else{
                return $this->render('/crm-return-visit/create', [
                    'downList' => $downList,
                    'visitPerson'=>$visitPerson,
                ]);
            }
        }
    }

    /**
     * @param $id
     * @return string
     * 更新拜访记录
     */
    public function actionVisitUpdate($id,$childId,$ctype=null){
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
//            $post['CrmVisitRecordChild']['sil_time'] = serialize([$post['day'], $post['hour'], $post['minute']]);
            $post['CrmVisitRecordChild']['title'] = '拜访'.$post['cust_name'];
            $post['CrmVisitRecordChild']['start'] =  $post['arriveDate'];
            $post['CrmVisitRecordChild']['end']   =  $post['leaveDate'];
            $post['CrmVisitRecord']['create_by'] = $post['CrmVisitRecordChild']['create_by'] =Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . "/crm/crm-return-visit/update?id=" . $id. '&childId='.$childId;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->put($url));
//            dumpE($data);
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['message']);
                return Json::encode(['msg' => "修改成功", "flag" => 1, "url" => Url::to(['visit-view','id'=>$data['data']['id'],'childId'=>$data['data']['childId'],'ctype'=>$ctype])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }

        } else {
            $downList = $this->getDownList();
            $sih = $this->getVisitInfo($id);
            $member = $this->getModel($sih['cust_id']);
            $districtId = $member['cust_district_2'];
            $districtAll = $this->getAllDistrict($districtId);
            $childCount = $this->getCountChild($member['sih_id']);
            $child = $this->getVisitChild($childId);
            return $this->render('/crm-return-visit/update', [
                'downList' => $downList,
                'member' => $member,
                'districtAll' => $districtAll,
                'childCount' => $childCount,
                'child' => $child,
                'sih'=>$sih,
                'id' => $id,
                'childId' => $childId
            ]);
        }
    }
    /**
     * @param $id
     * @param $childId
     * @return string
     * 查看拜访详情
     */
    public function actionVisitView($id,$childId = null,$ctype=null){
        $type = 0;
        $sih = $this->getVisitInfo($id);
        $member = $this->getModel($sih['cust_id']);
        $record = $this->getCustInfo($sih['cust_id']);
        if($childId == null){
            $child = $this->getAllVisitChild($member['sih_id']);
            $num = 0;
        }else{
            $allchild = $this->getAllVisitChild($member['sih_id']);
            $child = array($this->getVisitChild($childId));
            if($child[0]['create_at'] < $allchild[0]['create_at']){
                $type = 1;
            }
            $num = 1;
        }
        $downList = $this->getDownList();
        $status = $this->getStatus($sih['cust_id']);
        return $this->render('/crm-return-visit/view', [
            'downList' => $downList,
            'member' => $member,
            'child'=>$child,
            'type'=>$type,
            'num' =>$num,
            'ctype'=>$ctype,
            'record'=>$record,
            'id'=>$id,
            'status'=>$status
        ]);

    }
    /**
     * @param null $id
     * @param null $childId
     * @return string
     * 删除回访记录
     */
    public function actionVisitDelete($id,$childId){
        $url = $this->findApiUrl() .  "/crm/crm-return-visit/delete?id=" . $id . "&childId=" . $childId;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失败", "flag" => 0]);
        }
    }

    //查看活动报名
    public function actionActiveView($id)
    {
        $url=$this->findApiUrl().'crm/crm-active-apply/view?id='.$id;
        $data=Json::decode($this->findCurl()->get($url));
        if(empty($data)){
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('/crm-active-apply/view',['data'=>$data]);
    }
    /*获取主表信息*/
    public function getVisitInfo($id){
        $url = $this->findApiUrl() . "/crm/crm-return-visit/visit-info?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /*获取对应主表下拜访子表总数*/
    public function getCountChild($id){
        $url = $this->findApiUrl() ."/crm/crm-return-visit/count-child?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /*获取一条子表数据*/
    public function getVisitChild($id){
        $url = $this->findApiUrl() ."/crm/crm-return-visit/visit-child?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /*获取所有拜访记录信息*/
    public function getAllVisitChild($id){
        $url = $this->findApiUrl() ."/crm/crm-return-visit/all-visit-child?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /**
     * @param $id
     * @return mixed
     * 查询提醒事项
     */
    public function getReminder($id){
        $url = $this->findApiUrl() . $this->_url. "get-reminder?id=".$id;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    /**
     * @return mixed
     * 获取客户经理人
     */
    private function getEmployee()
    {
        $url = $this->findApiUrl() . "/crm/crm-return-visit/employee";
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    /*获取主表信息*/
    public function getStatus($id){
        $url = $this->findApiUrl() .$this->_url."get-status?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     * 列表
     */
    public function actionLoadInfo($id)
    {
        $this->layout = '@app/views/layouts/ajax';
        $record=$this->getField("/crm/crm-member/load-record");
        $reminder=$this->getField("/crm/crm-member/load-reminder");
        $active=$this->getField("/crm/crm-member/load-active");
        $message=$this->getField("/crm/crm-member/load-message");
        return $this->render('load-message', [
            'record'=>$record,
            'reminder'=>$reminder,
            'active'=>$active,
            'message'=>$message,
            'id'=>$id
        ]);
    }
    /**
     * @param $id
     * @return mixed
     * 根据子表查询提醒事项
     */
    public function actionLoadReminder($id){
        $url = $this->findApiUrl().$this->_url."load-reminder?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }

    /**
     * @param $id
     * @return string
     * 回访记录
     */
    public function actionLoadRecord($id){
        $url = $this->findApiUrl().$this->_url."load-record?id=".$id."&companyId=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        $sih = $this->getCustInfo($id);
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $result = Json::decode($this->findCurl()->get($url));
        foreach ($result['rows'] as $key => $val) {
            if(Menu::isAction('/crm/crm-member/visit-view')) {
                $result['rows'][$key]['sil_code'] = '<a href="' . Url::to(['visit-view', 'id' => $sih['sih_id'], 'childId' => $val['sil_id'], 'ctype' => '2']) . '">' . $val['sil_code'] . '</a>';
            }
        }
        $dataProvider = Json::encode($result);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }

    /**
     * @param $id
     * @return string
     * 活动信息
     */
    public function actionLoadActive($id){
        $url = $this->findApiUrl().$this->_url."load-active?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
//        $dataProvider = $this->findCurl()->get($url);
        $result = Json::decode($this->findCurl()->get($url));
        foreach ($result['rows'] as $key => $val) {
            if(Menu::isAction('/crm/crm-member/active-view')) {
                $result['rows'][$key]['acth_code'] = '<a href="' . Url::to(['/crm/crm-member/active-view', 'id' => $result['active']['rows'][$key]['acth_id']]) . '">' . $val['acth_code'] . '</a>';
            }
        }
        $dataProvider = Json::encode($result);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }

    /**
     * @param $id
     * @return mixed
     * 通讯记录
     */
    public function actionLoadMessage($id){
        $url = $this->findApiUrl().$this->_url."load-message?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }
    /*获取主表信息*/
    public function getCustInfo($id){
        $url = $this->findApiUrl() ."/crm/crm-return-visit/cust-info?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /**
     * @return mixed|string
     * 新增提醒 选择客户
     */
    public function actionSelectCustomer()
    {
        $this->layout = '@app/views/layouts/ajax';
        $url = $this->findApiUrl() . $this->_url."select-customer?companyId=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        return $this->render('select-customer',['queryParam'=>$queryParam]);
    }

    /**
     * @param $id
     * @return string
     * 转招商
     */
    public function actionTurnInvestment($str)
    {
        $url = $this->findApiUrl() . $this->_url . "turn-investment?str=" . $str;
        $result = Json::decode($this->findCurl()->get($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "转招商成功!", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "转招商失敗!", "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return string
     * 转销售
     */
    public function actionTurnSales($str)
    {
        $url = $this->findApiUrl() . $this->_url . "turn-sales?str=" . $str;
        $result = Json::decode($this->findCurl()->get($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "转销售成功!", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "转销售失敗!", "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return string
     * 提醒事项关闭
     */
    public function actionCloseReminders($id)
    {
        $url = $this->findApiUrl() . $this->_url . "close-reminders?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "关闭成功!", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "关闭失敗!", "flag" => 0]);
        }
    }

    /**
     * @return mixed
     * 下拉菜单
     */
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }


    /*根据地址五级获取全部信息*/
    public function getAllDistrict($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-info/get-all-district?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    /**
     * @param $data
     * @return mixed
     * 客户名称搜索(全部)
     */
    public function actionSelectCom($data){
        $url = $this->findApiUrl() . $this->_url . "select-com?data=" . $data;
        $model = Json::decode($this->findCurl()->get($url));
        return Json::encode($model);
    }
    /**
     * @param $data
     * @return mixed
     * 客户名称搜索(单个),带出详细信息
     */
    public function actionSelectComOne($data){
        $url = $this->findApiUrl() . $this->_url . "select-com-one?data=" . $data;
        $model = Json::decode($this->findCurl()->get($url));
        return Json::encode($model);
    }

    /**
     * @param $id
     * @param $attr
     * @param $val
     * @return mixed
     * 验证客户名称唯一性
     */
    public function actionValidateMember($id, $attr, $val)
    {
        $val = urlencode($val);
        $url = $this->findApiUrl() . $this->module->id . "/" . $this->id . "/" . "validate-member";
        $url = $url . "?id={$id}&attr={$attr}&val={$val}";
        return $this->findCurl()->get($url);
    }

    /**
     * @param $id
     * @param $attr
     * @param $val
     * @return mixed
     * 验证客户用户名唯一性
     */
    public function actionValidateName($id, $attr, $val)
    {
        $val = urlencode($val);
        $url = $this->findApiUrl() . $this->module->id . "/" . $this->id . "/" . "validate-name";
        $url = $url . "?id={$id}&val={$val}";
        return $this->findCurl()->get($url);
    }

    /**
     * 导出会员模板
     */
    public function actionDownTemplate()
    {
        $objPHPExcel = new \PHPExcel();
        $field = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O'];
        foreach($field as $key => $value){
            //宽度
            $objPHPExcel->getActiveSheet()->getColumnDimension($value)->setWidth(15);
            //标题垂直居中
            $objPHPExcel->getActiveSheet()->getStyle($value)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle( $value)->getFont()->setName('黑体' );
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '注册时间')
            ->setCellValue('C1', '用户名')
            ->setCellValue('D1', '公司中文名称')
            ->setCellValue('E1', '注册资金')
            ->setCellValue('F1', '营业执照注册号')
            ->setCellValue('G1', '用户姓名')
            ->setCellValue('H1', '公司电话')
            ->setCellValue('I1', '邮箱')
            ->setCellValue('J1', '手机')
            ->setCellValue('K1', '会员类型')
            ->setCellValue('L1', '会员积分')
            ->setCellValue('M1', '会员等级')
            ->setCellValue('N1', '认证完成时间')
            ->setCellValue('O1', '注册网站');
//        foreach ($data as $key => $val) {
//            $num = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . 2, '1')
                ->setCellValue('B' . 2, 'xxxx/xx/xx')
                ->setCellValue('C' . 2, 'XXX')
                ->setCellValue('D' . 2, 'XXX')
                ->setCellValue('E' . 2, '8888')
                ->setCellValue('F' . 2, 'XXX')
                ->setCellValue('G' . 2, 'XXX')
                ->setCellValue('H' . 2, '0755-88888888')
                ->setCellValue('I' . 2, 'XXX@XX.COM')
                ->setCellValue('J' . 2, '13699999999')
                ->setCellValue('K' . 2, '普通会员')
                ->setCellValue('L' . 2, '100')
                ->setCellValue('M' . 2, '白银会员')
                ->setCellValue('N' . 2, 'xxxx/xx/xx')
                ->setCellValue('O' . 2, 'XXX')
            ;
//        }
        $fileName = "member.xlsx";
        // 创建PHPExcel对象，注意，不能少了\
        $fileName = iconv("utf-8", "gb2312", $fileName);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean(); // 清除缓冲区,避免乱码
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=" . $fileName);
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_clean();
        $objWriter->save('php://output'); // 文件通过浏览器下载
        exit();
    }
    
}
