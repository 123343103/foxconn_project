<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/2/23
 * Time: 11:40
 */

namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\classes\Menu;

/**
 * CrmMemberController implements the CRUD actions for CrmMember model.
 */
class CrmReturnVisitController extends BaseController
{
    private $_url = "crm/crm-return-visit/";  //对应api控制器URL

    /**
     * 主页
     */
    public function actionIndex(){
        $url = $this->findApiUrl().$this->_url."index?companyId=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = Json::decode($this->findCurl()->get($url));
            foreach ($dataProvider['rows'] as $key => $val) {
                if(Menu::isAction('/crm/crm-return-visit/view')) {
                    $dataProvider['rows'][$key]['cust_filernumber'] = '<a href="' . Url::to(['view', 'id' => $val['sih_id']]) . '">' . $val['cust_filernumber'] . '</a>';
                }
            }
            return Json::encode($dataProvider);
        }
        $downList = $this->getDownList();
        $columns=$this->getField("/crm/crm-return-visit/index");
        return $this->render('index',[
            'downList'=>$downList,
            'queryParam' => $queryParam,
            'columns' =>$columns
        ]);
    }

    /**
     * 新增回访
     */
    public function actionCreate($id=null,$ctype=null){
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
//            $post['CrmVisitRecordChild']['sil_time'] = serialize([$post['day'], $post['hour'], $post['minute']]);
            $post['CrmVisitRecordChild']['title'] = '拜访'.$post['cust_name'];
            $post['CrmVisitRecordChild']['start'] =  $post['arriveDate'];
            $post['CrmVisitRecordChild']['end']   =  $post['leaveDate'];
            $post['CrmVisitInfoChild']['color'] = '#F00';
            $post['CrmVisitRecord']['create_by'] = $post['CrmVisitRecordChild']['create_by'] =Yii::$app->user->identity->staff_id;
            $post['CrmVisitRecord']['company_id'] = $post['CrmVisitRecordChild']['company_id'] =Yii::$app->user->identity->company_id;
            $url = $this->findApiUrl() . $this->_url."create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['message']);
                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" => Url::to(['/crm/crm-return-visit/view','id'=>$data['data']['id'],'childId'=>$data['data']['childId'],'ctype'=>$ctype])]);
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
                    'id' => $id
                ]);
            }else{
                return $this->render('create', [
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
    public function actionUpdate($id,$childId,$ctype=null){
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
//            $post['CrmVisitRecordChild']['sil_time'] = serialize([$post['day'], $post['hour'], $post['minute']]);
            $post['CrmVisitRecordChild']['title'] = '拜访'.$post['cust_name'];
            $post['CrmVisitRecordChild']['start'] =  $post['arriveDate'];
            $post['CrmVisitRecordChild']['end']   =  $post['leaveDate'];
            $post['CrmVisitRecord']['create_by'] = $post['CrmVisitRecordChild']['create_by'] =Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url."update?id=" . $id. '&childId='.$childId;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->put($url));
//            dumpE($data);
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['message']);
                return Json::encode(['msg' => "修改成功", "flag" => 1, "url" => Url::to(['/crm/crm-return-visit/view','id'=>$data['data']['id'],'childId'=>$data['data']['childId'],'ctype'=>$ctype])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }

        } else {
            $downList = $this->getDownList();
            $sih = $this->getVisitInfo($id);
            $member = $this->getCustomerInfo($sih['cust_id']);
            $districtId = $member['cust_district_2'];
            $districtAll = $this->getAllDistrict($districtId);
            $childCount = $this->getCountChild($member['sih_id']);
            $child = $this->getVisitChild($childId);
            return $this->render('update', [
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
     * 查看详情
     */
    public function actionView($id,$childId = null,$ctype=null){
        $type = 0;
        $sih = $this->getVisitInfo($id);
        $member = $this->getCustomerInfo($sih['cust_id']);
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
        return $this->render('view', [
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

    /*获取客户状态*/
    public function getStatus($id){
        $url = $this->findApiUrl() ."/crm/crm-member/get-status?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /**
     * @return string
     * 新增
     */
    public function actionCreateCustomer()
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['CrmCustomerInfo']['company_id'] = Yii::$app->user->identity->company_id;
            $post['CrmCustomerInfo']['create_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() ."/crm/crm-member/create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                return Json::encode(['msg' => "新增客户成功", "flag" => 1]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        } else {
            $this->layout = '@app/views/layouts/ajax';
            $downList = $this->getDownList();
            return $this->render('update-customer',[
                'downList'=>$downList,
            ]);
        }

    }
    /**
     * 编辑客户信息
     */
    public function actionUpdateCustomer($id,$childId){
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['CrmCustomerInfo']['update_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . "/crm/crm-member/update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if($id != null && $childId ==null){
                if ($data['status'] == 1) {
                    SystemLog::addLog($data['data']);
                    return Json::encode(['msg' => "修改信息成功", "flag" => 1, "url" => Url::to(['/crm/crm-return-visit/create','id'=>$id])]);
                } else {
                    return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
                }
            }else if($id != null && $childId != null){
                $sihId = $this->getCustInfo($id);
                if ($data['status'] == 1) {
                    SystemLog::addLog($data['data']);
                    return Json::encode(['msg' => "修改信息成功", "flag" => 1, "url" => Url::to(['/crm/crm-return-visit/update','id'=>$sihId['sih_id'],'childId'=>$childId])]);
                } else {
                    return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
                }
            }

        } else {
            $this->layout = '@app/views/layouts/ajax';
            $downList = $this->getDownList();
            $model = $this->getCustomerInfo($id);
            $districtId = $model['cust_district_2'];
            $districtAll = $this->getAllDistrict($districtId);
            return $this->render('update-customer', [
                'downList' => $downList,
                'model' => $model,
                'districtAll' => $districtAll,
                'id' => $id
            ]);
        }
    }
    /**
    *   删除客户
     */
    public function actionDeleteCustomer($id){
        $url = $this->findApiUrl() . $this->_url . "delete-customer?id=" . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => $result['msg'].'删除失败', "flag" => 0]);
        }
    }
    /**
     * @param null $id
     * @param null $childId
     * @return string
     * 删除回访记录
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
                'employee'=>$employee
            ]);
        }
    }

    /**
     * @param $id
     * @param $code
     * @param $start
     * @param $end
     * @return int|string
     * 判断某个时间内是否有记录
     */
    public function actionCheckTime($id,$childId,$code,$start,$end){
        $url = $this->findApiUrl() . $this->_url . "check-time";
        $url = $url . "?id={$id}&childId={$childId}&code={$code}&start={$start}&end={$end}";
        return $this->findCurl()->get($url);
    }


    private function getModel($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-member/models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    private function getEmployee()
    {
        $url = $this->findApiUrl() . $this->_url."employee";
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    /**
     * @param null $id
     * @return string
     * 删除提醒
     */
    public function actionDeleteReminders($id=null){
        $url = $this->findApiUrl() . $this->_url . "delete-reminders?id=" . $id;
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
     * 列表
     */

    public function actionLoadContent($id){
        $this->layout = '@app/views/layouts/ajax';
        $record=$this->getField("/crm/crm-return-visit/load-record");
        $reminder=$this->getField("/crm/crm-return-visit/load-reminder");
        return $this->render('load-content', [
//            'result' => $result,
            'record'=>$record,
            'reminder'=>$reminder,
            'id'=>$id
        ]);
    }

    /**
     * @param $id
     * @return string
     * 拜訪記錄
     */
    public function actionLoadRecord($id){
        $url = $this->findApiUrl().$this->_url."load-record?id=".$id."&companyId=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $result = Json::decode($this->findCurl()->get($url));
        foreach ($result['rows'] as $key => $val) {
            if(Menu::isAction('/crm/crm-return-visit/view')) {
                $result['rows'][$key]['sil_code'] = '<a href="' . Url::to(['view', 'id' => $val['sih_id'], 'childId' => $val['sil_id']]) . '">' . $val['sil_code'] . '</a>';
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
     * 选择会员客户
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
     * 下拉列表
     */
    public function getDownList(){
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * 获取客户信息
     */
    public function getCustomerInfo($id){
        $url = $this->findApiUrl()."/crm/crm-member/models?id=".$id;
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
    /**/
    /*获取主表信息*/
    public function getVisitInfo($id){
        $url = $this->findApiUrl() .$this->_url. "visit-info?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /*获取对应主表下拜访子表总数*/
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
    /*获取提醒事项*/
    public function getReminderOne($id){
        $url = $this->findApiUrl() .$this->_url. "reminder-one?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*根据客户选择主表*/
    public function getCustInfo($id){
        $url = $this->findApiUrl() .$this->_url. "cust-info?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
}