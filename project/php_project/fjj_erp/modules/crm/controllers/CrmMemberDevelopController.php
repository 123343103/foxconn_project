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
use \app\classes\Menu;
/**
 * CrmMemberController implements the CRUD actions for CrmMember model.
 */
class CrmMemberDevelopController extends BaseController
{
    public $_url = "crm/crm-member-develop/";  //对应api控制器URL
    public function beforeAction($action)
    {
        $this->ignorelist=array_merge($this->ignorelist,[
            '/crm/crm-member-develop/load-info',
            '/crm/crm-member-develop/check-time',
            '/crm/crm-member/validate',
            '/ptdt/firm/get-district',
            '/hr/staff/get-staff-info'
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
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            if(Yii::$app->user->identity->is_supper!=1){
                $url.='&staffId='.Yii::$app->user->identity->staff_id;
            }
            $dataProvider = Json::decode($this->findCurl()->get($url));
            if(array_key_exists('customers',$queryParam)){
                foreach ($dataProvider['rows'] as $key => $val) {
                    if(Menu::isAction('/crm/crm-member-develop/view')) {
                        $dataProvider['rows'][$key]['cust_filernumber'] = '<a href="' . Url::to(['view', 'id' => $val['cust_id'], 'ctype' => '1']) . '">' . $val['cust_filernumber'] . '</a>';
                    }

                }
            }else{
                foreach ($dataProvider['rows'] as $key => $val) {
                    if(Menu::isAction('/crm/crm-member-develop/cust-view')){
                        $dataProvider['rows'][$key]['cust_sname'] = '<a href="' . Url::to(['cust-view', 'id' => $val['cust_id']]) . '">' . $val['cust_sname'] . '</a>';
                    }
                    if(Menu::isAction('/crm/crm-member-develop/view')) {
                        $dataProvider['rows'][$key]['cust_filernumber'] = '<a href="' . Url::to(['view', 'id' => $val['cust_id'], 'ctype' => '1']) . '">' . $val['cust_filernumber'] . '</a>';
                    }

                }
            }
            return Json::encode($dataProvider);
        }
        $downList = $this->getDownList();
        $columns=$this->getField("/crm/crm-member-develop/index");
        return $this->render('index', [
            'downList' => $downList,
            'queryParam' => $queryParam,
            'columns' =>$columns
        ]);

    }

    /**
     * @return string
     * 新增客户信息
     */
    public function actionCreate()
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['CrmCustomerInfo']['company_id'] = Yii::$app->user->identity->company_id;
            $post['CrmCustomerInfo']['create_by'] = Yii::$app->user->identity->staff_id;
            if(Yii::$app->user->identity->is_supper==1){
                $post['adminId']=Yii::$app->user->identity->staff_id;
            }
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
                if ($data['status'] == 1) {
                    return Json::encode(['msg' => "新增客户成功", "flag" => 1, "url" => Url::to(['cust-view','id'=>$data['data']])]);
                } else {
                    return Json::encode(['msg' => $data['msg'], "flag" => 0]);
                }
        } else {
            $downList = $this->getDownList();
            return $this->render('create-form',[
                'downList'=>$downList,
            ]);
        }

    }

    /**
     * @param $id
     * @return string
     * 修改客户信息
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
                return Json::encode(['msg' => "修改信息成功", "flag" => 1, "url" => Url::to(['cust-view','id'=>$id])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        } else {
            $model = $this->getModel($id);
            $downList = $this->getDownList();
            $districtId = $model['cust_district_2'];
            $districtAll = $this->getAllDistrict($districtId);
            return $this->render('update-form', [
                'model' => $model,
                'downList' => $downList,
                'districtAll' => $districtAll,
            ]);
        }
    }

    /**
     * @param $id
     * @return string
     * 客户详情
     */
    public function actionCustView($id){
        $result = $this->getCustomerInfo($id);
        $downList = $this->getDownList();
        $districtId = $result['cust_district_2'];
        $districtAll = $this->getAllDistrict($districtId);
        return $this->render('cust-view', [
            'result' => $result,
            'downList' => $downList,
            'districtAll' => $districtAll,
        ]);
    }

    /**
     * @param null $id
     * @param null $ctype
     * @return string
     * 新增拜访
     */
    public function actionVisitCreate($id=null,$ctype=null){
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['ctype']  = $ctype;
            $post['CrmVisitRecordChild']['title'] = '拜访'.$post['cust_name'];
            $post['CrmVisitRecordChild']['start'] =  $post['arriveDate'];
            $post['CrmVisitRecordChild']['end']   =  $post['leaveDate'];
//            $post['CrmVisitInfoChild']['color'] = '#F00';
            $post['CrmVisitRecord']['create_by'] = $post['CrmVisitRecordChild']['create_by'] =Yii::$app->user->identity->staff_id;
            $post['CrmVisitRecord']['company_id'] = $post['CrmVisitRecordChild']['company_id'] =Yii::$app->user->identity->company_id;
            $url = $this->findApiUrl() . $this->_url."visit-create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['message']);
                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']['id'],'childId'=>$data['data']['childId'],'ctype'=>$ctype])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        } else {
            $downList = $this->getDownList();
            $url = $this->findApiUrl() . '/crm/crm-visit-info/visit-person?id=' . Yii::$app->user->identity->staff_id;
            $visitPerson = json_decode($this->findCurl()->get($url));
            if (!empty($id)) {
                $member = $this->getCustomerInfo($id);
                $districtId = $member['cust_district_2'];
                $districtAll = $this->getAllDistrict($districtId);
                return $this->render('create', [
                    'downList' => $downList,
                    'member' => $member,
                    'districtAll' => $districtAll,
                    'visitPerson' => $visitPerson,
                    'id' => $id,
                    'ctype'=>$ctype
                ]);
            }else{
                return $this->render('create', [
                    'downList' => $downList,
                    'visitPerson'=>$visitPerson,
                    'ctype'=>$ctype
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
            $post['CrmVisitRecordChild']['sil_time'] = serialize([$post['day'], $post['hour'], $post['minute']]);
            $post['CrmVisitRecordChild']['title'] = '拜访'.$post['cust_name'];
            $post['CrmVisitRecordChild']['start'] =  $post['arriveDate'];
            $post['CrmVisitRecordChild']['end']   =  $post['leaveDate'];
            $post['CrmVisitRecord']['create_by'] = $post['CrmVisitRecordChild']['create_by'] =Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url."visit-update?id=" . $id. '&childId='.$childId;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['message']);
                return Json::encode(['msg' => "修改成功", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']['id'],'childId'=>$data['data']['childId'],'ctype'=>$ctype])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }

        } else {
            $downList = $this->getDownList();
            $member = $this->getCustomerInfo($id);
            $districtId = $member['cust_district_2'];
            $districtAll = $this->getAllDistrict($districtId);
            $childCount = $this->getCountChild($member['sih_id']);
            $child = $this->getVisitChild($childId);
            $visit = $this->getCustInfo($id);
            return $this->render('update', [
                'downList' => $downList,
                'member' => $member,
                'districtAll' => $districtAll,
                'childCount' => $childCount,
                'child' => $child,
                'ctype'=>$ctype,
                'visit'=>$visit,
                'id' => $id,
                'childId' => $childId
            ]);
        }
    }

    /**
     * @param null $id
     * @param null $childId
     * @return string
     * 删除拜访记录
     */
    public function actionDelete($id,$childId){
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id . "&childId=" . $childId;
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
     * @param $childId
     * @return string
     * 查看拜访详情
     */
    public function actionView($id,$childId = null,$ctype=null){
        $type = 0;
        $member = $this->getCustomerInfo($id);
        $record = $this->getCustInfo($id);
        if($childId == null){
            $child = $this->getAllVisitChild($member['sih_id']);
            $num = 0;
        }else{
            $allchild = $this->getAllVisitChild($member['sih_id']);
            $child = array($this->getVisitChild($childId));
//            dumpE($allchild);
            if($child[0]['create_at'] < $allchild[0]['create_at']){
                $type = 1;
            }
            $num = 1;
        }
        $downList = $this->getDownList();
        return $this->render('view', [
            'downList' => $downList,
            'member' => $member,
            'child'=>$child,
            'type'=>$type,
            'num' =>$num,
            'childid'=>$childId,
            'ctype'=>$ctype,
            'record'=>$record,
            'id'=>$id
        ]);

    }

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
            $url = $this->findApiUrl() . "/crm/crm-member/create-reminders";
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
            $url = $this->findApiUrl() . "/crm/crm-member/update-reminders?id=".$id;
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
     * @param $id
     * @return mixed
     * 查询提醒事项
     */
    public function getReminder($id){
        $url = $this->findApiUrl() . "/crm/crm-member/get-reminder?id=".$id;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    public function getCountChild($id){
        $url = $this->findApiUrl() .$this->_url. "count-child?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /*获取一条子表数据*/
    public function getVisitChild($id){
        $url = $this->findApiUrl() .$this->_url. "visit-child?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /*获取所有拜访记录信息*/
    public function getAllVisitChild($id){
        $url = $this->findApiUrl() .$this->_url. "all-visit-child?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /*根据客户选择主表*/
    public function getCustInfo($id){
        $url = $this->findApiUrl() ."/crm/crm-return-visit/cust-info?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /**
     * 选择会员客户
     */
    public function actionSelectCustomer($ctype=null)
    {
        $this->layout = '@app/views/layouts/ajax';
        $url = $this->findApiUrl() . $this->_url."select-customer?companyId=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        $queryParam['ctype'] = $ctype;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        return $this->render('select-customer',[
            'queryParam'=>$queryParam,
            'ctype'=>$ctype
            ]);
    }
    /**
     * 获取客户信息
     */
    public function getCustomerInfo($id){
        $url = $this->findApiUrl()."/crm/crm-member-develop/models?id=".$id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /**
     * @param $id
     * @return string
     * 提醒事项
     */
    public function actionReminders($id)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['CrmImessage']['imesg_sentman'] = Yii::$app->user->identity->staff_id;
            $postData['CrmImessage']['imesg_sentdate'] = date('Y-m-d h:i:s', time());
            $url = $this->findApiUrl() . $this->_url . "reminders?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                return Json::encode(['msg' => "新增提醒成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        } else {
            $this->layout = '@app/views/layouts/ajax';
            $model = $this->getModel($id);
            $employee = $this->getEmployee();
            return $this->render('reminders', [
                'model' => $model,
                'employee'=>$employee
            ]);
        }
    }

    /**
     * @return mixed
     * 客户经理人
     */
    private function getEmployee()
    {
        $url = $this->findApiUrl() . "/crm/crm-return-visit/employee";
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }
    /**
     * @param $id
     * @return mixed
     * 拜访记录等列表信息
     */
    public function actionLoadInfo($id)
    {
        $this->layout = '@app/views/layouts/ajax';
        $record=$this->getField("/crm/crm-member-develop/load-record");
        $reminder=$this->getField("/crm/crm-member-develop/load-reminder");
        $message=$this->getField("/crm/crm-member-develop/load-message");
        return $this->render('load-message', [
            'record'=>$record,
            'reminder'=>$reminder,
            'message'=>$message,
            'id'=>$id
        ]);
    }

    /**
     * @param $id
     * @return string
     * 回访记录
     */
    public function actionLoadRecord($id){
        $url = $this->findApiUrl().$this->_url."load-record?id=".$id."&companyId=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $result = Json::decode($this->findCurl()->get($url));
        foreach ($result['rows'] as $key => $val) {
            if(Menu::isAction('/crm/crm-member/visit-view')) {
                $result['rows'][$key]['sil_code'] = '<a href="' . Url::to(['view', 'id' => $id, 'childId' => $val['sil_id'], 'ctype' => '1']) . '">' . $val['sil_code'] . '</a>';
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


    /*获取拜访主表信息*/
    public function getVisitInfo($id){
        $url = $this->findApiUrl() ."/crm/crm-return-visit/visit-info?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /**
     * @param $id
     * @return string
     * 转会员
     */
    public function actionTurnMember($id,$from="")
    {
//        $url = $this->findApiUrl() . $this->_url . "turn-member?str=" . $str;
//        $result = Json::decode($this->findCurl()->get($url));
//        if ($result['status'] == 1) {
//            SystemLog::addLog($result['data']);
//            return Json::encode(["msg" => "转会员成功!", "flag" => 1]);
//        } else {
//            return Json::encode(["msg" => "转会员失敗!", "flag" => 0]);
//        }
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['CrmCustomerInfo']['update_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "turn-member?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(["msg" => "转会员成功!", "flag" => 1,"url"=>Url::to([$from])]);
            } else {
                return Json::encode(["msg" => "转会员失敗!", "flag" => 0]);
            }
        }else {
            $this->layout = '@app/views/layouts/ajax';
            $downList = $this->getDownList();
            $model = $this->getModel($id);
//            dumpE($model);
            $districtId = $model['cust_district_2'];
            $districtAll = $this->getAllDistrict($districtId);
            return $this->render('turn-member', [
                'downList' => $downList,
                'model'=>$model,
                'districtAll'=>$districtAll
            ]);
        }
    }
    /**
     * @param $id
     * @return string
     * 转招商
     */
    public function actionTurnInvestment($str)
    {
        $url = $this->findApiUrl() . "/crm/crm-member/turn-investment?str=" . $str;
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
        $url = $this->findApiUrl() . "/crm/crm-member/turn-sales?str=" . $str;
        $result = Json::decode($this->findCurl()->get($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "转销售成功!", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "转销售失敗!", "flag" => 0]);
        }
    }

    public function actionSendMail()
    {
//        $mail = Yii::$app->mailer->compose();
//        $mail->setTo('hj_gon@163.com');
//        $mail->setSubject("邮件测试");
////$mail->setTextBody('zheshisha ');   //发布纯文字文本
//        $mail->setHtmlBody("<br>问我我我我我");    //发布可以带html标签的文本
//        if ($mail->send())
//            echo "success";
//        else
//            echo "false";
//        die();
        $postData = Yii::$app->request->post();
        $mail = new SendMail();
        $data = $mail->sendmail($postData['Select'],$postData['Other'],$postData['subject'],$postData['body']);
        if ($data->SendResult->status == 1) {
            return Json::encode(["msg" => "邮件发送成功!", "flag" => 1, "url" => Url::to(['index'])]);
        } else {
            return Json::encode(["msg" => "发送失败!", "flag" => 0]);
        }
    }

    public function getDownList()
    {
        $url = $this->findApiUrl() . "/crm/crm-member/down-list";
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
     * 导出
     */
//    private function export($data)
//    {
//        $objPHPExcel = new \PHPExcel();
//        $objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue('A1', '序号')
//            ->setCellValue('B1', '注册时间')
//            ->setCellValue('C1', '用户名')
//            ->setCellValue('D1', '公司中文名称')
//            ->setCellValue('E1', '注册资金')
//            ->setCellValue('F1', '营业执照注册号')
//            ->setCellValue('G1', '用户姓名')
//            ->setCellValue('H1', '公司电话')
//            ->setCellValue('I1', '邮箱')
//            ->setCellValue('J1', '手机')
//            ->setCellValue('K1', '会员类型')
//            ->setCellValue('L1', '会员积分')
//            ->setCellValue('M1', '会员等级')
//            ->setCellValue('N1', '认证完成时间');
//        foreach ($data as $key => $val) {
//            $num = $key + 2;
//            $objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A' . $num, $num - 1)
//                ->setCellValue('B' . $num, $val['member_regtime'])
//                ->setCellValue('C' . $num, $val['member_name'])
//                ->setCellValue('D' . $num, $val['cust_sname'])
//                ->setCellValue('E' . $num, $val['cust_regfunds'])
//                ->setCellValue('F' . $num, $val['cust_regnumber'])
//                ->setCellValue('G' . $num, $val['cust_contacts'])
//                ->setCellValue('H' . $num, $val['cust_tel1'])
//                ->setCellValue('I' . $num, $val['cust_email'])
//                ->setCellValue('J' . $num, $val['cust_tel1'])
//                ->setCellValue('K' . $num, $val['bsPubdata']['memberType'])
//                ->setCellValue('L' . $num, $val['member_points'])
//                ->setCellValue('M' . $num, $val['bsPubdata']['memberLevel'])
//                ->setCellValue('N' . $num, $val['member_certification']);
//        }
//        $date = date("Y_m_d", time()) . rand(0, 99);
//        $fileName = "_{$date}.xls";
//        // 创建PHPExcel对象，注意，不能少了\
//        $fileName = iconv("utf-8", "gb2312", $fileName);
//        $objPHPExcel->setActiveSheetIndex(0);
//        ob_end_clean(); // 清除缓冲区,避免乱码
//        header('Content-Type: application/vnd.ms-excel');
//        header("Content-Disposition: attachment;filename=" . $fileName);
//        header('Cache-Control: max-age=0');
//
//        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//        $objWriter->save('php://output'); // 文件通过浏览器下载
//        exit();
//    }

    /**
     * excel插入數據
     */
    public function actionInsertExcel()
    {

        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
//                $fileName = $model->file->baseName;
                $fileName = date('Y-m-d', time()) . '_' . rand(1, 9999);
                $fileExt = $model->file->extension;
                $resultSave = $model->file->saveAs('uploads/' . $fileName . '.' . $fileExt);
            }
        }
        if (!empty($resultSave)) {
            $url = $this->findApiUrl() . $this->_url . "save-member?companyId=" . Yii::$app->user->identity->company_id . "&createBy=" . Yii::$app->user->identity->staff_id;
            $e2a = new ExcelToArr();
            $arr = $e2a->excel2arry($fileName, $fileExt);
            $arr1 = array_slice($arr, 1);   // 处理第一行标题
            $arr2 = array_chunk($arr1, 1); // 防止一次post传输的数据过大
            foreach ($arr2 as $key => $v) {
                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($v));
                $data = Json::decode($curl->post($url));
//               $a =  round((($key+1)/(count($arr)-1)),2)*100;
            }
            if ($data['status'] == 1) {
                return Json::encode(["msg" => "导入成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(["msg" => "导入失败", "flag" => 0]);
            }
        }
    }

    //抛至公海
    public function actionThrowSea($arrId)
    {
        $url=$this->findApiUrl().'/crm/crm-member-develop/throw-sea?arrId='.$arrId;
        return $this->findCurl()->get($url);
    }
    /**
     * @param $id
     * @param $code
     * @param $start
     * @param $end
     * @return int|string
     * 判断某个时间内是否有拜访记录
     */
    public function actionCheckTime($id,$childId,$code,$start,$end){
        $url = $this->findApiUrl() . $this->_url . "check-time";
        $url = $url . "?id={$id}&childId={$childId}&code={$code}&start={$start}&end={$end}";
        return $this->findCurl()->get($url);
    }

    /**
     * @param $id
     * @param $code
     * @param $start
     * @param $end
     * @return int|string
     * 判断某个时间内是否有提醒事项
     */
    public function actionCheckReminderTime($id,$childId,$code,$start,$end){
        $url = $this->findApiUrl() . $this->_url . "check-reminder-time";
        $url = $url . "?id={$id}&childId={$childId}&code={$code}&start={$start}&end={$end}";
        return $this->findCurl()->get($url);
    }

    /**
     * @param $id
     * @param $attr
     * @param $val
     * @return mixed
     * 验证客户唯一性
     */
//    public function actionValidateDevelop($id, $attr, $val)
//    {
//        $val = urlencode($val);
//        $url = $this->findApiUrl() . $this->_url . "validate-develop";
//        $url = $url . "?id={$id}&attr={$attr}&val={$val}";
//        return $this->findCurl()->get($url);
//    }
}
