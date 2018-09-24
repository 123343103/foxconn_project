<?php
/**
 * 招商客户开发
 * User: F3859386
 * Date: 2017/2/13
 * Time: 9:12
 */

namespace app\modules\crm\controllers;

use app\models\UploadForm;
use app\modules\common\tools\ExcelToArr;
use yii\web\UploadedFile;
use app\models\User;
use app\modules\common\tools\SendMail;
use app\modules\system\models\SystemLog;
use app\controllers\BaseController;
use yii;
use yii\helpers\Json;
use yii\helpers\Url;

class CrmInvestmentDvelopmentController extends BaseController
{

    public $_url = "crm/crm-investment-dvelopment/";//对应api控制器

    public function beforeAction($action)
    {
        $this->ignorelist = array_merge($this->ignorelist, [
//            '/crm/crm-investment-dvelopment/import',
            '/crm/crm-member-develop/view',
            '/crm/crm-member/reminders',
            '/crm/crm-member/turn-sales'

        ]);
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index?companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $export = Yii::$app->request->get('export');
        $dataProvider = Json::decode($this->findCurl()->get($url));
        if (isset($export)) {
            SystemLog::addLog('导出招商开发列表');
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
        $columns = $this->getField("/crm/crm-investment-dvelopment/index");
        return $this->render('index', [
            'downList' => $downList,
            'search' => $queryParam['CrmCustomerInfoSearch'],
            'columns' => $columns,
        ]);
    }

    /**
     * @return string
     * 新增客户信息
     */
    public function actionCreate($ctype = null)
    {
        if ($postData = Yii::$app->request->post()) {
            $postData['CrmCustomerInfo']['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['CrmCustomerInfo']['company_id'] = Yii::$app->user->identity->company_id;
            $postData['CrmCustomerInfo']['cust_pruchaseqty'] = round($postData['CrmCustomerInfo']['cust_pruchaseqty'], 6);
            $postData['CrmCustomerInfo']['member_compsum'] = round($postData['CrmCustomerInfo']['member_compsum'], 6);
            $postData['CrmCustomerInfo']['cust_regfunds'] = round($postData['CrmCustomerInfo']['cust_regfunds'], 6);
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($ctype == null) {
                if ($data['status']) {
                    SystemLog::addLog('招商客戶新增:' . $data['msg']['id']);
                    return Json::encode(['msg' => "新增客户成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['msg']['id']])]);
                } else {
                    return Json::encode(['msg' => $data['msg'], "flag" => 0]);
                }
            } else {
                if ($data['status']) {
                    SystemLog::addLog('招商客戶新增:' . $data['msg']['id']);
                    return Json::encode(['msg' => "新增客户成功", "flag" => 1]);
                } else {
                    return Json::encode(['msg' => $data['msg'], "flag" => 0]);
                }
            }
        } else {
            if ($ctype == null) {
                $downList = $this->getDownList();
                return $this->render("create", [
                    'downList' => $downList,
                ]);
            } else {
                $this->layout = '@app/views/layouts/ajax';
                $downList = $this->getDownList();
                return $this->render("create-form", [
                    'downList' => $downList,
                    'ctype' => $ctype
                ]);
            }
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
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }
        $model = $this->getModel($id);
        $districtId2 = $model['district'][3]['district_id'];
        $districtId3 = $model['district'][4]['district_id'];
//            dumpE($this->getAllDistrict($districtId2));
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

    /**
     * 详情
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $model = $this->getModel($id);
        //dumpE($model);
        return $this->render('view', [
            'model' => $model,
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
        SystemLog::addLog('导出招商开发列表');
        return $this->exportFiled($dataProvider['rows']);
    }

    /**
     * 删除
     * @param $id
     * @return string
     */
//    public function actionDelete($id)
//    {
//        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
//        $res = Json::decode($this->findCurl()->DELETE($url));
//        if ($res['status']) {
//            SystemLog::addLog("客户" . $res['msg'] . "删除成功");
//            return Json::encode(["msg" => "删除客户成功", 'flag' => 1]);
//        } else {
//            SystemLog::addLog("客户" . $res['msg'] . "删除失败");
//            return Json::encode(["msg" => "删除客户失败", 'flag' => 0]);
//        }
//    }

    /**
     * 删除店铺信息
     * @param $id
     * @return string
     */
    public function actionDeleteShop($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete-shop?id=" . $id;
        $res = Json::decode($this->findCurl()->delete($url));
        if ($res['status']) {
            SystemLog::addLog("店铺" . $res['msg'] . "删除成功");
            return Json::encode(["msg" => "删除店铺成功", 'flag' => 1]);
        } else {
            SystemLog::addLog("店铺" . $res['msg'] . "删除失败");
            return Json::encode(["msg" => "删除店铺失败", 'flag' => 0]);
        }
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


    /**
     * @param $id
     * @return string
     * 开店信息
     */
    public function actionShopInfo()
    {
        $url = $this->findApiUrl() . $this->_url . "shop-info";
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
            return $this->render('shop-info', [
                'downList' => $downList
            ]);
        }
    }


    public function actionShopEdit($id)
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "shop-edit?id={$id}";
            $data = Json::decode($this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params))->post($url));
            if ($data["status"] == 1) {
                return Json::encode(["msg" => $data["msg"], "flag" => 1]);
            }
            return Json::encode(["msg" => $data["msg"], "flag" => 0]);
        }
        $url = $this->findApiUrl() . $this->_url . "shop?id={$id}";
        $model = Json::decode($this->findCurl()->get($url));
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render("shop-edit", ["model" => $model]);
    }


    /**
     * 分配员工
     * @return string
     */
    public function actionAssignStaff($id, $class)
    {
        $url = $this->findApiUrl() . $this->_url . "assign-staff?id=" . $id . '&staff_id=' . Yii::$app->user->identity->staff_id . '&class=' . $class;
        if ($postData = Yii::$app->request->post()) {
            if ($class != "null") {
                $postData["mainType"] = $class;
            }
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
        $staffs = Json::decode($this->actionGetAssignStaff($class));
        return $this->render('assign-staff', [
            'type' => $data['mchpdtype'],
            'model' => $data['model'],
            'class' => $class,
            'staffs' => $staffs,
        ]);

    }


    /**
     * 点击加载
     */
    public function actionLoadInfo($id, $ctype = null)
    {

        $columns['visit'] = $this->getField("/crm/crm-investment-dvelopment/load-visit");
        $columns['shop'] = $this->getField("/crm/crm-investment-dvelopment/load-shop");
        $columns['reminder'] = $this->getField("/crm/crm-investment-dvelopment/load-reminder");
        $columns['message'] = $this->getField("/crm/crm-investment-dvelopment/load-message");
        $columns['contacts'] = $this->getField("/crm/crm-investment-dvelopment/load-contacts");

        return $this->renderAjax("load-info", [
            'columns' => $columns,
            'id' => $id,
            'ctype' => $ctype,
        ]);
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
                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" => Url::to(['view-visit', 'id' => $data['data']['id'], 'childId' => $data['data']['childId'], 'ctype' => 3])]);
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
                return Json::encode(['msg' => "修改成功", "flag" => 1, "url" => Url::to(['view-visit', 'id' => $data['data']['id'], 'childId' => $data['data']['childId'], 'ctype' => 3])]);
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
     * @param $id
     * @return string
     * 转销售
     */
    public function actionTurnSales($str)
    {
        $url = $this->findApiUrl() . "crm/crm-member/turn-sales?str=" . $str;
        $result = Json::decode($this->findCurl()->get($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "转销售成功!", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "转销售失敗!", "flag" => 0]);
        }
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

    public function actionUpdateContacts($id)
    {
        $postData = Yii::$app->request->post();
//        $post['_csrf'] = Yii::$app->request->csrfToken;
        $post['CrmCustomerPersion']['ccper_name'] = $postData['ccper_name'];
        $post['CrmCustomerPersion']['ccper_post'] = $postData['ccper_post'];
        $post['CrmCustomerPersion']['ccper_mobile'] = $postData['ccper_mobile'];
        $post['CrmCustomerPersion']['ccper_mail'] = $postData['ccper_mail'];
        $post['CrmCustomerPersion']['ccper_remark'] = $postData['ccper_remark'];
        $url = $this->findApiUrl() . $this->_url . "update-contacts?id=" . $id;
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
        $url = $this->findApiUrl() . $this->_url . "delete-contacts?id=" . $id;
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
     * 提醒功能
     * @param null $id
     * @param null $ctype
     * @return string
     */
    public function actionReminders($id = null, $ctype = null)
    {
        if ($postData = Yii::$app->request->post()) {
            $postData['CrmImessage']['imesg_sentman'] = Yii::$app->user->identity->staff_id;
            $postData['CrmImessage']['imesg_sentdate'] = date('Y-m-d h:i:s', time());
            $url = $this->findApiUrl() . "/crm/crm-member-develop/reminders";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = $curl->put($url);
            if ($data['status']) {
                SystemLog::addLog($data['data']);
                $localUrl = Url::to(['index']);
                if ($ctype == 6) {
                    $localUrl = Url::to(['/crm/crm-investment-customer/list']);
                }
                return Json::encode(['msg' => "新增提醒成功", "flag" => 1, "url" => $localUrl]);
            }
            return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
        }

        $this->layout = '@app/views/layouts/ajax';
        $model = '';
        $model = $this->getModel($id);
        $employee = $this->getEmployee();
        return $this->render('reminders', [
            'model' => $model,
            'employee' => $employee,
            'ctype' => $ctype
        ]);
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
            return $this->render('reminders', [
                'model' => $model,
                'employee' => $employee,
                'reminder' => $reminder
            ]);
        }
    }

    /**
     * @param null $id
     * @return string
     * 删除提醒提醒
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

    /**
     * @return mixed|string
     * 新增提醒 选择客户
     */
    public function actionSelectCustomer($ctype = null)
    {
//        dumpE($ctype);
        $this->layout = '@app/views/layouts/ajax';
        $url = $this->findApiUrl() . $this->_url . "index?companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if ($ctype == 6 || $queryParam['ctype'] == 6) {
            $url = $this->findApiUrl() . "crm/crm-investment-customer/index?companyId=" . Yii::$app->user->identity->staff_id;
        }
        if (!empty($queryParam['keywords'])) {
            $url .= "&" . http_build_query(['keywords' => trim($queryParam['keywords'])]);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        return $this->render('select-customer', [
            'queryParam' => $queryParam,
            'ctype' => $ctype
        ]);
    }

    //抛至公海
    public function actionThrowSea($arrId)
    {
        $url = $this->findApiUrl() . $this->_url . "throw-sea?arrId=" . $arrId;
        return $this->findCurl()->get($url);
    }

    private function getEmployee()
    {
        $url = $this->findApiUrl() . "/crm/crm-return-visit/employee";
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    public function actionGetAssignStaff($type)
    {
        $url = $this->findApiUrl() . $this->_url . "get-assign-staff?type=" . $type;
        $data = $this->findCurl()->get($url);
        return $data;
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

    //批量导入by201708/28更新
    public function actionImport()
    {
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $succ = 0;
            $err = 0;
            $err_log = [];
            $model->file = UploadedFile::getInstance($model, 'file');
            if (!$model->validate()) {
                echo "<script>parent.$('#import-ok').html('文件无效或损坏').fadeIn()</script>";
                ob_flush();
                flush();
                exit(0);
            }
            echo "<script>parent.$('#progressbar_div').show()</script>";
            $fileName = date('Y-m-d', time()) . '_' . rand(1, 9999);
            $fileExt = $model->file->extension;
            $resultSave = $model->file->saveAs('uploads/' . $fileName . '.' . $fileExt);

            if (!empty($resultSave)) {
                $url = $this->findApiUrl() . $this->_url . "import?companyId=" . \Yii::$app->user->identity->company_id . "&createBy=" . \Yii::$app->user->identity->staff_id;
                $e2a = new ExcelToArr();
                $arr = $e2a->excel2arry($fileName, $fileExt);
                $arr1 = array_slice($arr, 1);   // 处理第一行标题
                $count = 0;
                //总数去除空行
                foreach ($arr1 as $key => $value) {
                    $result = true;
                    foreach ($value as $k => $v) {
                        $result = $result && empty($v);
                    }
                    if (!$result) {
                        $count++;
                    };
                }
                $arr2 = array_chunk($arr1, 10); // 防止一次post传输的数据过大
                foreach ($arr2 as $key => $v) {
                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($v));
                    $data = Json::decode($curl->post($url));
                    $succ += $data["succ"];
                    $err += $data["error"];
                    $err_log = array_merge($err_log, $data["log"]);
                    echo $this->renderAjax("@app/views/base/import-progress", [
                        "total" => $succ + $err,
                        "success" => $succ,
                        "error" => $err,
                        "percent" => floor(($succ + $err) / $count * 100)
                    ]);
                    ob_flush();
                    flush();
                }
                $cache = \Yii::$app->cache;
                $key = "import-log-" . \Yii::$app->user->identity->staff_id;
                $cache->set($key, $err_log);
                echo "<script>parent.$('#import-ok,#import_over').fadeIn()</script>";
                if ($err > 0) {
                    echo "<script>parent.$('#err_log').fadeIn()</script>";
                }
            }
        } else {
            if (\Yii::$app->request->get("act") == "view-log") {
                $logs = \Yii::$app->cache->get("import-log-" . \Yii::$app->user->identity->staff_id);
                return $this->renderAjax("@app/views/base/import-log", ["logs" => $logs]);
            }
            $this->layout = "@app/views/layouts/ajax";
            return $this->render("import");
        }
    }

    //下载模板
    public function actionDownTemplate()
    {
        $headArr = ['序号', '公司名称', '简称', '联系人', '职位', '手机号码', '邮箱', '公司电话', '省份', '地级市', '地址', '经营模式', '客户来源', '潜在需求', '需求类目',];
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xlsx";
        $objPHPExcel = new \PHPExcel();
        $key = "A";
        foreach ($headArr as $v) {
            $colum = $key;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(15);
            // $objPHPExcel->getActiveSheet($v)->getStyle($key)->getAlignment($key)->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            if ($key == "Z") {
                $key = "AA";
            } elseif ($key == "AZ") {
                $key = "BA";
            } else {
                $key++;
            }
        }
        $fileName = iconv("utf-8", "gb2312", $fileName);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean(); // 清除缓冲区,避免乱码

        //以下导出-excel2003版本
//        header('Content-Type: application/vnd.ms-excel');
//        header("Content-Disposition: attachment;filename=" . $fileName);
//        header('Cache-Control: max-age=0');
//        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//        $objWriter->save('php://output'); // 文件通过浏览器下载

        //以下导出-excel2007版本
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_clean();    //用于清除缓冲区的内容,兼容
        $objWriter->save('php://output');
        exit();
    }


    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        //dumpE($model);
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
        $url = $this->findApiUrl() . $this->_url . "load-record?id=" . $id . "&companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $result = Json::decode($this->findCurl()->get($url));
//        dumpE($result);
        foreach ($result['rows'] as $key => $val) {
            $result['rows'][$key]['sil_code'] = '<a href="' . Url::to(['view-visit', 'id' => $id, 'childId' => $val['sil_id'], 'ctype' => 3]) . '">' . $val['sil_code'] . '</a>';
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
        $url = $this->findApiUrl() . $this->_url . "load-shop?id=" . $id . "&companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
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
        $url = $this->findApiUrl() . $this->_url . "load-reminders?id=" . $id . "&companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
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
        $url = $this->findApiUrl() . $this->_url . "load-message?id=" . $id . "&companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
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
        $url = $this->findApiUrl() . $this->_url . "load-contacts?id=" . $id . "&companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
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