<?php
/**
 * 招商会员列表
 * User: F3859386
 * Date: 2017/2/13
 * Time: 9:16
 */

namespace app\modules\crm\controllers;

use app\modules\common\tools\SendMail;
use app\modules\system\models\SystemLog;
use app\controllers\BaseController;
use yii;
use yii\helpers\Json;
use yii\helpers\Url;

class CrmInvestmentCustomerController extends BaseController
{

    public $_url = "crm/crm-investment-customer/";//对应api控制器

    public function beforeAction($action)
    {
        $this->ignorelist = array_merge($this->ignorelist, [
            '/crm/crm-return-visit/create',
            '/crm/crm-return-visit/delete',
            '/crm/crm-return-visit/edit',
            '/crm/crm-member/reminders',
            '/crm/crm-member/turn-sales'

        ]);
        return parent::beforeAction($action);
    }

    public function actionList()
    {
        $url = $this->findApiUrl() . $this->_url . "index?companyId=" . Yii::$app->user->identity->staff_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $dataProvider = Json::decode($this->findCurl()->get($url));
            SystemLog::addLog('导出招商客户列表');
            return $this->exportFiled($dataProvider['rows']);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = Json::decode($this->findCurl()->get($url));
            foreach ($dataProvider['rows'] as $key => $val) {
                $dataProvider['rows'][$key]['cust_filernumber'] = '<a href="' . Url::to(['view', 'id' => $val['cust_id']]) . '">' . $val['cust_filernumber'] . '</a>';
            }
            return Json::encode($dataProvider);
        }

        $downList = $this->getSearchDownList();
        $columns = $this->getField("/crm/crm-investment-customer/list");
        return $this->render('list', [
            'downList' => $downList,
            'search' => $queryParam['CrmCustomerInfoSearch'],
            'columns' => $columns
        ]);
    }

    /**
     * @return string
     * 新增客户信息
     */
    public function actionCreate()
    {
        if ($postData = Yii::$app->request->post()) {
            $postData['CrmCustomerInfo']['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['CrmCustomerInfo']['company_id'] = Yii::$app->user->identity->company_id;
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog('招商客戶新增:' . $data['msg']['id']);
                return Json::encode(['msg' => "新增客户成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['msg']['id']])]);
            } else {
                return Json::encode(['msg' => $data['msg']['name'], "flag" => 0]);
            }
        } else {
            $downList = $this->getDownList();
            return $this->render("create", [
                'downList' => $downList,
            ]);
        }
    }

    /**
     * 更新
     * @return string
     */
    public function actionUpdate($id)
    {
        if ($postData = Yii::$app->request->post()) {
            $postData['CrmCustomerInfo']['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['CrmCustomerInfo']['company_id'] = Yii::$app->user->identity->company_id;
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog('招商客戶修改:' . $id);
                return Json::encode(['msg' => "修改客户成功", "flag" => 1, "url" => Url::to(['view', 'id' => $id])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        }
        //客户信息和开店信息['cust','shop'];
        $model = $this->getModel($id);
        $districtId2 = $model['district'][3]['district_id'];
        $districtId3 = $model['district'][4]['district_id'];
        $districtAll2 = $this->getAllDistrict($districtId2);
        $districtAll3 = $this->getAllDistrict($districtId3);
        $downList = $this->getDownList();
        $district = $this->getDistrict();
        return $this->render("update", [
            'model' => $model,
            'downList' => $downList,
            'district' => $district,
            'districtAll2' => $districtAll2,
            'districtAll3' => $districtAll3,
        ]);
    }

    public function actionExport()
    {
        $url = $this->findApiUrl() . $this->_url . "index?export=1&companyId=" . Yii::$app->user->identity->staff_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        \Yii::$app->controller->action->id = 'list';
        SystemLog::addLog('导出招商客户列表');
        return $this->exportFiled($dataProvider['rows']);
    }

    /**
     * 详情
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $model = $this->getModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }


    /*获取所在地区一级地址*/
    public function getDistrict()
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-info/get-district";
        $result = $this->findCurl()->get($url);
        return $result;
    }

    /*根据地址五级获取全部信息*/
    public function getAllDistrict($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-info/get-all-district?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }


//    即时通讯
//    即时通讯
    public function actionSendMessage($type, $url = "")
    {
        return parent::actionSendMessage($type, Url::to(['list']));
    }


    /**
     * @param $id
     * @return string
     * 开店信息
     */
    public function actionShopInfo()
    {
        $url = $this->findApiUrl() . "crm/crm-investment-dvelopment/shop-info";
        if ($postData = Yii::$app->request->post()) {
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog($postData['cust_sname'] . '添加开店信息:' . $data['msg']);
                return Json::encode(['msg' => "新增成功", "flag" => 1]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        } else {
            $this->layout = '@app/views/layouts/ajax';
            $downList = Json::decode($this->findCurl()->get($url));
            return $this->render('/crm-investment-dvelopment/shop-info', [
                'downList' => $downList
            ]);
        }
    }

    public function actionShopEdit($id)
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            $url = $this->findApiUrl() . "crm/crm-investment-dvelopment/shop-edit?id={$id}";
            $data = Json::decode($this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params))->post($url));
            if ($data["status"] == 1) {
                return Json::encode(["msg" => $data["msg"], "flag" => 1]);
            }
            return Json::encode(["msg" => $data["msg"], "flag" => 0]);
        }
        $url = $this->findApiUrl() . "crm/crm-investment-dvelopment/shop?id={$id}";
        $model = Json::decode($this->findCurl()->get($url));
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render("/crm-investment-dvelopment/shop-edit", ["model" => $model]);
    }

    /**
     * 删除店铺信息
     * @param $id
     * @return string
     */
    public function actionDeleteShop($id)
    {
        $url = $this->findApiUrl() . "crm/crm-investment-dvelopment/delete-shop?id=" . $id;
        $res = Json::decode($this->findCurl()->delete($url));
        if ($res['status']) {
            SystemLog::addLog("店铺" . $res['msg'] . "删除成功");
            return Json::encode(["msg" => "删除店铺成功", 'flag' => 1]);
        } else {
            SystemLog::addLog("店铺" . $res['msg'] . "删除失败");
            return Json::encode(["msg" => "删除店铺失败", 'flag' => 0]);
        }
    }

    /**
     * 分配员工
     * @return string
     */
    public function actionAssignStaff($id)
    {
        $url = $this->findApiUrl() . $this->_url . "assign-staff?id=" . $id;
        if ($postData = Yii::$app->request->post()) {
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "操作成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $this->layout = '@app/views/layouts/ajax';
        $data = Json::decode($this->findCurl()->get($url));

        return $this->render('assign-staff', [
            'type' => $data['mchpdtype'],
            'model' => $data['model'],
            'assignStaff' => Json::encode($data['assignStaff']),
        ]);

    }


    /**
     * 点击加载
     */
    public function actionLoadInfo($id, $ctype = null)
    {
        $columns['visit'] = $this->getField("/crm/crm-investment-customer/load-visit");
        $columns['shop'] = $this->getField("/crm/crm-investment-customer/load-shop");
        $columns['reminder'] = $this->getField("/crm/crm-investment-customer/load-reminder");
        $columns['message'] = $this->getField("/crm/crm-investment-customer/load-message");
        $columns['contacts'] = $this->getField("/crm/crm-investment-customer/load-contacts");

        return $this->renderAjax("/crm-investment-dvelopment/load-info", [
            'id' => $id,
            'columns' => $columns,
            'ctype' => $ctype,
        ]);
    }

    /**
     * @param $id
     * @return string
     * 提醒事项
     */
    public function actionReminders($id = null, $ctype = null)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['CrmImessage']['imesg_sentman'] = Yii::$app->user->identity->staff_id;
            $postData['CrmImessage']['imesg_sentdate'] = date('Y-m-d h:i:s', time());
            $url = $this->findApiUrl() . "/crm/crm-member-develop/reminders";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status']) {
                SystemLog::addLog($data['data']);
                $localUrl = Url::to(['index']);
                if ($ctype == 6) {
                    $localUrl = Url::to(['/crm/crm-investment-customer/list']);
                }
                return Json::encode(['msg' => "新增提醒成功", "flag" => 1, "url" => $localUrl]);
            }
            return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
        } else {
            $this->layout = '@app/views/layouts/ajax';
            $model = $this->getModel($id);
            $employee = $this->getEmployee();
            return $this->render('/crm-investment-dvelopment/reminders', [
                'model' => $model,
                'employee' => $employee,
                'ctype' => $ctype
            ]);
        }
    }

    /**
     * @param $id
     * @return string
     * 修改提醒事项
     */
    public function actionUpdateReminders($id = null, $from = "")
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['CrmImessage']['imesg_sentman'] = Yii::$app->user->identity->staff_id;
            $postData['CrmImessage']['imesg_sentdate'] = date('Y-m-d h:i:s', time());
            $url = $this->findApiUrl() . "crm/crm-member/update-reminders?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "修改提醒成功", "flag" => 1, "url" => $from ? $from : Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        } else {
            $this->layout = '@app/views/layouts/ajax';
            $reminder = $this->getReminder($id);
            $model = $this->getModel($reminder['cust_id']);
            $employee = $this->getEmployee();
            return $this->render('/crm-investment-dvelopment/reminders', [
                'model' => $model,
                'employee' => $employee,
                'reminder' => $reminder
            ]);
        }
    }

    /**
     * @param null $id
     * @return string
     * 删除提醒
     */
    public function actionDeleteReminders($id = null)
    {
        $url = $this->findApiUrl() . "crm/crm-return-visit/delete-reminders?id=" . $id;
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
    public function getReminder($id)
    {
        $url = $this->findApiUrl() . "crm/crm-member/get-reminder?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    public function actionUpdateContacts($id)
    {
        $postData = Yii::$app->request->post();
//        $post['_csrf'] = Yii::$app->request->csrfToken;
        $post['CrmCustomerPersion']['ccper_name'] = $postData['ccper_name'];
        $post['CrmCustomerPersion']['ccper_post'] = $postData['ccper_post'];
        $post['CrmCustomerPersion']['ccper_mobile'] = $postData['ccper_mobile'];
        $post['CrmCustomerPersion']['ccper_mail'] = $postData['ccper_mail'];
        $post['CrmCustomerPersion']['ccper_remark'] = $postData['ccper_remark'];
        $url = $this->findApiUrl() . "crm/crm-investment-dvelopment/update-contacts?id=" . $id;
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $data = Json::decode($curl->post($url));
        if ($data['status']) {
            SystemLog::addLog("联系人" . $postData['ccper_name'] . "修改成功");
            return Json::encode(['msg' => "修改联系人成功", "flag" => 1]);
        }
        return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);

    }

    public function actionDeleteContacts($id)
    {
        $url = $this->findApiUrl() . "crm/crm-investment-dvelopment/delete-contacts?id=" . $id;
        $res = Json::decode($this->findCurl()->delete($url));
        if ($res['status']) {
            SystemLog::addLog("联系人" . $res['msg'] . "删除成功");
            return Json::encode(["msg" => "删除联系人成功", 'flag' => 1]);
        } else {
            SystemLog::addLog("联系人" . $res['msg'] . "删除失败");
            return Json::encode(["msg" => "删除联系人失败", 'flag' => 0]);
        }
    }

    /**
     * @param null $id
     * @param null $ctype
     * @return string
     * 新增拜访
     */
    public function actionVisitCreate($id = null, $ctype = null)
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['ctype'] = $ctype;
            $post['CrmVisitRecordChild']['title'] = '拜访' . $post['cust_name'];
            $post['CrmVisitRecordChild']['start'] = $post['arriveDate'];
            $post['CrmVisitRecordChild']['end'] = $post['leaveDate'];
//            $post['CrmVisitInfoChild']['color'] = '#F00';
            $post['CrmVisitRecord']['create_by'] = $post['CrmVisitRecordChild']['create_by'] = Yii::$app->user->identity->staff_id;
            $post['CrmVisitRecord']['company_id'] = $post['CrmVisitRecordChild']['company_id'] = Yii::$app->user->identity->company_id;
            $url = $this->findApiUrl() . "crm/crm-member-develop/visit-create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['message']);
                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" => Url::to(['view-visit', 'id' => $data['data']['id'], 'childId' => $data['data']['childId'], 'ctype' => 6])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        } else {
            $downList = $this->getList();
            $url = $this->findApiUrl() . '/crm/crm-visit-info/visit-person?id=' . Yii::$app->user->identity->staff_id;
            $visitPerson = json_decode($this->findCurl()->get($url));
            if (!empty($id)) {
                $member = $this->getCustomerInfo($id);
                $districtId = $member['cust_district_2'];
                $districtAll = $this->getAllDistrict($districtId);
                return $this->render('/crm-member-develop/create', [
                    'downList' => $downList,
                    'member' => $member,
                    'districtAll' => $districtAll,
                    'visitPerson' => $visitPerson,
                    'id' => $id,
                    'ctype' => $ctype
                ]);
            } else {
                return $this->render('/crm-member-develop/create', [
                    'downList' => $downList,
                    'visitPerson' => $visitPerson,
                    'ctype' => $ctype
                ]);
            }
        }
    }

    /**
     * @param $id
     * @param $childId
     * @return string
     * 查看拜访详情
     */
    public function actionViewVisit($id, $childId = null, $ctype = null)
    {
        $type = 0;
        $member = $this->getCustomerInfo($id);
        $record = $this->getCustInfo($id);
        if ($childId == null) {
            $child = $this->getAllVisitChild($member['sih_id']);
            $num = 0;
        } else {
            $allchild = $this->getAllVisitChild($member['sih_id']);
            $child = array($this->getVisitChild($childId));
//            dumpE($allchild);
            if ($child[0]['create_at'] < $allchild[0]['create_at']) {
                $type = 1;
            }
            $num = 1;
        }
        $downList = $this->getDownList();
        return $this->render('/crm-member-develop/view', [
            'downList' => $downList,
            'member' => $member,
            'child' => $child,
            'type' => $type,
            'num' => $num,
            'childid' => $childId,
            'ctype' => $ctype,
            'record' => $record,
            'id' => $id
        ]);

    }

    /**
     * @param $id
     * @return string
     * 更新拜访记录
     */
    public function actionVisitUpdate($id, $childId, $ctype = null)
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['CrmVisitRecordChild']['sil_time'] = serialize([$post['day'], $post['hour'], $post['minute']]);
            $post['CrmVisitRecordChild']['title'] = '拜访' . $post['cust_name'];
            $post['CrmVisitRecordChild']['start'] = $post['arriveDate'];
            $post['CrmVisitRecordChild']['end'] = $post['leaveDate'];
            $post['CrmVisitRecord']['create_by'] = $post['CrmVisitRecordChild']['create_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . "crm/crm-member-develop/visit-update?id=" . $id . '&childId=' . $childId;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['message']);
                return Json::encode(['msg' => "修改成功", "flag" => 1, "url" => Url::to(['view-visit', 'id' => $data['data']['id'], 'childId' => $data['data']['childId'], 'ctype' => 6])]);
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
            return $this->render('/crm-member-develop/update', [
                'downList' => $downList,
                'member' => $member,
                'districtAll' => $districtAll,
                'childCount' => $childCount,
                'child' => $child,
                'ctype' => $ctype,
                'visit' => $visit,
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
    public function actionDelete($id, $childId)

    {
        $url = $this->findApiUrl() . "crm/crm-member-develop/delete?id=" . $id . "&childId=" . $childId;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失败", "flag" => 0]);
        }
    }

    public function getCountChild($id)
    {
        $url = $this->findApiUrl() . "crm/crm-member-develop/count-child?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*获取一条子表数据*/
    public function getVisitChild($id)
    {
        $url = $this->findApiUrl() . "crm/crm-member-develop/visit-child?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*获取所有拜访记录信息*/
    public function getAllVisitChild($id)
    {
        $url = $this->findApiUrl() . "crm/crm-member-develop/all-visit-child?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*根据客户选择主表*/
    public function getCustInfo($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-return-visit/cust-info?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function getList()
    {
        $url = $this->findApiUrl() . "/crm/crm-member/down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * 获取客户信息
     */
    public function getCustomerInfo($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-member-develop/models?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    private function getEmployee()
    {
        $url = $this->findApiUrl() . "/crm/crm-return-visit/employee";
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }


    public function getDownList()
    {
        $url = $this->findApiUrl() . "crm/crm-investment-dvelopment/down-list";
        $data = Json::decode($this->findCurl()->get($url));
        return $data;
    }

    public function getSearchDownList()
    {
        $url = $this->findApiUrl() . "crm/crm-investment-dvelopment/down-list?search=1";
        $data = Json::decode($this->findCurl()->get($url));
        return $data;
    }

    //数据导出
    private function export($data)
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '客户编号')
            ->setCellValue('B1', '公司名称')
            ->setCellValue('C1', '公司简称')
            ->setCellValue('D1', '联系人')
            ->setCellValue('E1', '手机号码')
            ->setCellValue('F1', '邮箱')
            ->setCellValue('G1', '是否分配')
            ->setCellValue('H1', '分配员工')
            ->setCellValue('I1', '经营模式')
            ->setCellValue('J1', '经营范围')
            ->setCellValue('K1', '客户来源')
            ->setCellValue('L1', '潜在需求')
            ->setCellValue('M1', '需求类目')
            ->setCellValue('N1', '录入人员');
        foreach ($data as $key => $val) {
            $num = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $val['cust_filernumber'])
                ->setCellValue('B' . $num, $val['cust_sname'])
                ->setCellValue('C' . $num, $val['cust_shortname'])
                ->setCellValue('D' . $num, $val['cust_contacts'])
                ->setCellValue('E' . $num, $val['cust_tel2'])
                ->setCellValue('F' . $num, $val['cust_email'])
                ->setCellValue('G' . $num, $val['assign_status'])
                ->setCellValue('H' . $num, $val['personinch_name'])
                ->setCellValue('I' . $num, $val['cust_businesstype'])
                ->setCellValue('J' . $num, $val['member_businessarea'])
                ->setCellValue('K' . $num, $val['member_source'])
                ->setCellValue('L' . $num, $val['member_reqflag'])
                ->setCellValue('M' . $num, $val['member_reqitemclass'])
                ->setCellValue('N' . $num, $val['create_name']);
        }
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xls";
        // 创建PHPExcel对象，注意，不能少了\
        $fileName = iconv("utf-8", "gb2312", $fileName);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean(); // 清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=" . $fileName);
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); // 文件通过浏览器下载
        exit();
    }

    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new yii\web\NotFoundHttpException('页面未找到');
        }
    }

    /**
     * @param $id
     * @return string
     * 拜访记录
     */
    public function actionLoadRecord($id)
    {
        $url = $this->findApiUrl() . "crm/crm-investment-dvelopment/load-record?id=" . $id . "&companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $result = Json::decode($this->findCurl()->get($url));
        foreach ($result['rows'] as $key => $val) {
            $result['rows'][$key]['sil_code'] = '<a href="' . Url::to(['view-visit', 'id' => $id, 'childId' => $val['sil_id'], 'ctype' => 6]) . '">' . $val['sil_code'] . '</a>';
        }
        if (!empty($result['rows'][0])) {
            $result['rows'][0]['datagrid_columns_index'] = true;
        }
        $dataProvider = Json::encode($result);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }

    /**
     * @param $id
     * @return string
     * 开店信息
     */
    public function actionLoadShop($id)
    {
        $url = $this->findApiUrl() . "crm/crm-investment-dvelopment/load-shop?id=" . $id . "&companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        $sih = $this->getCustInfo($id);
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $result = Json::decode($this->findCurl()->get($url));
        foreach ($result['rows'] as $key => $val) {
            $result['rows'][$key]['shop_isbail'] = ($result['rows'][$key]['shop_isbail'] == '1' ? '是' : '否');
        }
        $dataProvider = Json::encode($result);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }

    /**
     * @param $id
     * @return string
     * 提醒事项
     */
    public function actionLoadReminders($id)
    {
        $url = $this->findApiUrl() . "crm/crm-investment-dvelopment/load-reminders?id=" . $id . "&companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        $sih = $this->getCustInfo($id);
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $result = Json::decode($this->findCurl()->get($url));
        $dataProvider = Json::encode($result);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }

    /**
     * @param $id
     * @return string
     * 通讯记录
     */
    public function actionLoadMessage($id)
    {
        $url = $this->findApiUrl() . "crm/crm-investment-dvelopment/load-message?id=" . $id . "&companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        $sih = $this->getCustInfo($id);
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $result = Json::decode($this->findCurl()->get($url));
        $dataProvider = Json::encode($result);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }

    /**
     * @param $id
     * @return string
     * 其他联系人
     */
    public function actionLoadContacts($id)
    {
        $url = $this->findApiUrl() . "crm/crm-investment-dvelopment/load-contacts?id=" . $id . "&companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        $sih = $this->getCustInfo($id);
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $result = Json::decode($this->findCurl()->get($url));
        $dataProvider = Json::encode($result);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }
}