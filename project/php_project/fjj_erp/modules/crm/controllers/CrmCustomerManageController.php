<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/16
 * Time: 9:16
 */
namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\models\User;
use app\modules\system\models\SystemLog;
use app\widgets\ueditor\Ftp;
use yii\helpers\Json;
use yii;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

class CrmCustomerManageController extends BaseController
{
    private $_url = "crm/crm-customer-manage/";  //对应api控制器URL

    /**
     * @return mixed|string
     * 客户管理列表
     */
    public function actionIndex()
    {
        $companyId = Yii::$app->user->identity->company_id;
        $url = $this->findApiUrl() . $this->_url . "index?companyId=" . $companyId;
        $queryParam = Yii::$app->request->queryParams;
        $manageId = '';
        $get = Yii::$app->request->get();
        if($get['manageId']==null&&$get['searchKeyword']==null){
            $manageId = Yii::$app->user->identity->staff_id;
        }else{
            if($get['manageId'] == Yii::$app->user->identity->staff_id){
                $manageId = Yii::$app->user->identity->staff_id;
            }else if($get['manageId'] == '0'){
                $queryParam['manageId'] = '';
            }
        }
        if (!empty($queryParam)) {
            //排除超级管理员
            if(empty(Yii::$app->user->identity->is_supper)){
                $url .= '&staffId=' . Yii::$app->user->identity->staff_id.'&manageId='.$manageId ;
            }
            $url .= '&' . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = Json::decode($this->findCurl()->get($url));
            foreach ($dataProvider['rows'] as $key => $val){
                $dataProvider['rows'][$key]['cust_filernumber']='<a href="'. yii\helpers\Url::to(['view','id'=>$val['cust_id']]).'">'.$val['cust_filernumber'].'</a>';
                $dataProvider['rows'][$key]['cust_sname']='<a href="'. yii\helpers\Url::to(['view','id'=>$val['cust_id']]).'">'.$val['cust_sname'].'</a>';
                $dataProvider['rows'][$key]['create_at']=date("Y-m-d", strtotime($val['create_at']));
            }
            return Json::encode($dataProvider);
        }
        $columns=$this->getField("/crm/crm-customer-manage/index");
        $planColumns=$this->getField("/crm/crm-customer-manage/visit-plan");
        $recordColumns=$this->getField("/crm/crm-customer-manage/visit-record");
        $orderColumns=$this->getField("/crm/crm-customer-manage/last-sale-order");
        $priceColumns=$this->getField("/crm/crm-customer-manage/last-sale-quotedprice");
        $applyColumns=$this->getField("/crm/crm-customer-manage/verify");
        $prdColumns=$this->getField("/crm/crm-customer-manage/requirement-product");
        $module = $this->getModuleShow(Yii::$app->user->identity->staff_id);
//        dumpE($module);
        return $this->render('index', [
            'queryParam' => $queryParam,
            'manageId'=>$manageId,
            'columns'=>$columns,
            'planColumns'=>$planColumns,
            'recordColumns'=>$recordColumns,
            'orderColumns'=>$orderColumns,
            'priceColumns'=>$priceColumns,
            'applyColumns'=>$applyColumns,
            'prdColumns'=>$prdColumns,
            'module'=>$module
        ]);
    }
    public function getEmployee($id){
        $url = $this->findApiUrl()."/crm/crm-customer-info/get-employee?id=".$id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function getModuleShow($id){
        $url = $this->findApiUrl().$this->_url."get-module-show?id=".$id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }


    /**
     * @param null $id
     * @return mixed
     * 拜访计划列表
     */
    public function actionVisitPlan($id="")
    {
        $url = $this->findApiUrl() . $this->_url . "visit-plan?id={$id}&&companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        $queryParam['staff_id'] = Yii::$app->user->identity->staff_id;
        $queryParam['user'] = Yii::$app->user->identity->user_account;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = Json::decode($this->findCurl()->get($url));
            if (!empty($dataProvider['rows'])) {
                foreach ($dataProvider['rows'] as &$val) {
                    $val['svp_code'] = "<a onclick='window.location.href=\"" . Url::to(['crm-visit-plan/view', 'id' => $val['svp_id']]) . "\";event.stopPropagation();'>" . $val['svp_code'] . "</a>";
                    $nowtime = date('Y-m-d H:i:s', time());
                    if ($val['svp_status'] == 10) {
                        if ($nowtime > $val['end']) {
                            $val["status"] = "已实施";
                        }
                        if ($nowtime > $val['start'] && $nowtime < $val['end']) {
                            $val["status"] = "实施中";
                        }
                        if ($nowtime < $val['start']) {
                            $val["status"] = "待实施";
                        }
                    }
                    if ($val['svp_status'] == 30) {
                        $val["status"] = "<span style='color: red'   onclick=\"showcause('3','" . $val['svp_id'] . "','" . $val['cancl_rs'] . "'" . ")\">已取消</span>";
                    }

                    if ($val['svp_status'] == 40) {
                        $val["status"]="实施中";
                    }

                    if ($val['svp_status'] == 50) {
                        $val["status"] = "<span style='color: red'   onclick=\"showcause('4','" . $val['svp_id'] . "','" . $val['cancl_rs'] . "'" . ")\">已终止</span>";
                    }


                }
            }
            return Json::encode($dataProvider);
        }
    }

    /**
     * @param null $id
     * @return mixed
     * 拜访记录列表
     */
    public function actionVisitRecord($id="")
    {
        $url = $this->findApiUrl() . $this->_url . "visit-record?id={$id}";
        $queryParam = Yii::$app->request->queryParams;
        $queryParam['staff_id'] = Yii::$app->user->identity->staff_id;
        $queryParam['companyId'] = Yii::$app->user->identity->company_id;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $data = $this->findCurl()->get($url);
        $data=Json::decode($data);
        foreach($data["rows"] as &$row){
            $row["sil_code"]="<a href='".Url::to(['crm-visit-record/view-records','mainId'=>$row['sih_id']])."'>".$row["sil_code"]."</a>";
        }
        $data=Json::encode($data);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $data;
        }
    }

    /**
     * @return mixed
     *
     * 我的申请
     */
    public function actionVerify($id=""){
        $url = $this->findApiUrl().$this->_url."verify?id={$id}";
        $queryParam = Yii::$app->request->queryParams;
        if(!\Yii::$app->user->identity->is_supper){
            $queryParam['staff_id'] = Yii::$app->user->identity->staff_id;
            $queryParam['companyId']=Yii::$app->user->identity->company_id;
        }
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
    }

    /**
     * @param null $id
     * @return mixed
     * CRD/PRD列表
     */
    public function actionRequirementProduct($id = null)
    {
        $url = $this->findApiUrl() . $this->_url . "requirement-product?id=" . $id;
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
     * @param null $id
     * @return mixed
     * 最近交易订单列表
     */
    public function actionLastSaleOrder($id = null)
    {
        $url = $this->findApiUrl() . $this->_url . "last-sale-order?companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam, $id);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
    }

    /**
     * @param null $id
     * @return mixed
     * 设备信息表
     */
    public function actionCustDevice($id = null)
    {
        $url = $this->findApiUrl() . $this->_url . "cust-device?id=" . $id;
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
     * @param null $id
     * @return mixed
     * 联系人列表
     */
    public function actionContactPerson($id = null)
    {
        $url = $this->findApiUrl() . $this->_url . "contact-person?id=" . $id;
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
     * @param null $id
     * @return mixed
     * 主营产品列表
     */
    public function actionCustMainProduct($id = null)
    {
        $url = $this->findApiUrl() . $this->_url . "cust-main-product?id=" . $id;
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
     * @param null $id
     * @return mixed
     * 主要客户列表
     */
    public function actionCustMainCustomer($id = null)
    {
        $url = $this->findApiUrl() . $this->_url . "cust-main-customer?id=" . $id;
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
     * @param null $id
     * @return mixed
     * 最近报价单
     */
    public function actionLastSaleQuotedprice($id = null)
    {
        $url = $this->findApiUrl() . $this->_url . "last-sale-quotedprice?companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam, $id);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
    }
    /**
     * @param null $id
     * @return mixed
     * 商机商品
     */
    public function actionCheckCustOddsitem($id)
    {
        $url = $this->findApiUrl() . $this->_url . "check-cust-oddsitem?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }
    public function actionCustLinkComp($id)
    {
        $url = $this->findApiUrl() . $this->_url . "cust-link-comp?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $data = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $data;
        }
    }
    public function actionCustPersonInch($id)
    {
        $url = $this->findApiUrl() . $this->_url . "cust-person-inch?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }
    public function actionCustPurchase($id)
    {
        $url = $this->findApiUrl() . $this->_url . "cust-purchase?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }
    public function actionSaleOrder($id)
    {
        $url = $this->findApiUrl() . $this->_url . "sale-order?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }
    public function actionSaleQuotedprice($id)
    {
        $url = $this->findApiUrl() . $this->_url . "sale-quotedprice?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }
    public function actionCostInfo($id)
    {
        $url = $this->findApiUrl() . $this->_url . "cost-info?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }
    public function actionCooperationProduct($id)
    {
        $url = $this->findApiUrl() . $this->_url . "cooperation-product?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
    }
    public function actionProjectFollow($id)
    {
        $url = $this->findApiUrl() . $this->_url . "project-follow?id=".$id;
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
     * 客户详情
     */
    public function actionView($id)
    {
        $model = $this->getModel($id);
        $downList = $this->getDownList();
        $companyId = Yii::$app->user->identity->company_id;
        $managerId = Yii::$app->user->identity->staff_id;
        $resultList = $this->getResultList($id, $companyId, $managerId);
        $visit = $this->getIndexList($id, $companyId);
        $crmcertf = $this->getCrmCertf($id);
        $newnName1 = $crmcertf['bs_license'];
        $newnName1 = substr($newnName1, 2, 6);
//        $newnName1 = str_replace('-', '', $newnName1);
        $newnName2 = $crmcertf['tx_reg'];
        $newnName2 = substr($newnName2, 2, 6);
//        $newnName2 = str_replace('-', '', $newnName2);
        $newnName3 = $crmcertf['qlf_certf'];
        $newnName3 = substr($newnName3, 2, 6);
//        $newnName3 = str_replace('-', '', $newnName3);
        return $this->render('view', [
            'newnName1' => $newnName1,
            'newnName2' => $newnName2,
            'newnName3' => $newnName3,
            'model' => $model,
            'id' => $id,
            'downList' => $downList,
            'resultList' => $resultList,
            'visit' => $visit,
            'crmcertf'=>$crmcertf
        ]);
    }

    public function getCrmCertf($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-apply/crm-certf?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        }
    }
    /**
     * @return mixed
     * 单据类别
     */
    public function getBusinessType(){
        $url = $this->findApiUrl() . "/system/verify-record/business-type";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function getResultList($id, $companyId, $managerId)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-manage/result-list?id=" . $id . "&companyId=" . $companyId . "&managerId=" . $managerId;
        $result = $this->findCurl()->get($url);
        return $result;
    }

    public function getIndexList($id, $companyId)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-manage/index-list?id=" . $id . "&companyId=" . $companyId;
        $result = $this->findCurl()->get($url);
        return $result;
    }

    /**
     * @param $id
     * @return string
     * 修改客户基本信息弹出层
     */
    public function actionBaseCustomer($id)
    {
        $this->layout = '@app/views/layouts/ajax';
        $model = $this->getModel($id);
        $downList = $this->getDownList();
        $districtId2 = $model['cust_district_2'];
        $districtId3 = $model['cust_district_3'];
        $districtId4 = $model['invoice_title_district'];
        $districtId5 = $model['invoice_mail_district'];
        $districtAll2 = $this->getAllDistrict($districtId2);
        $district = $this->getDistrict();
        $isSuper=User::isSupper(Yii::$app->user->identity->user_id);
        $u = $this->getEmployee(Yii::$app->user->identity->staff_id);
        return $this->render('base-customer', [
            'model' => $model,
            'downList' => $downList,
            'districtAll2' => $districtAll2,
            'id' => $id,
            'district'=>$district,
            'isSuper'=>$isSuper,
            'u'=>$u
        ]);
    }

    /**
     * @param $id
     * @return string
     * 修改客户公司信息弹出层
     */
    public function actionCompanyCustomer($id)
    {
        $this->layout = '@app/views/layouts/ajax';
        $model = $this->getModel($id);
        $downList = $this->getDownList();
        $industryType = $this->getIndustryType();
        $districtId2 = $model['cust_district_2'];
        $districtId3 = $model['cust_district_3'];
        $districtId4 = $model['invoice_title_district'];
        $districtId5 = $model['invoice_mail_district'];
        $districtAll2 = $this->getAllDistrict($districtId2);
        $districtAll3 = $this->getAllDistrict($districtId3);
        $districtAll4 = $this->getAllDistrict($districtId4);
        $districtAll5 = $this->getAllDistrict($districtId5);
        return $this->render('company-customer', [
            'model' => $model,
            'downList' => $downList,
            'districtAll2' => $districtAll2,
            'districtAll3' => $districtAll3,
            'districtAll4' => $districtAll4,
            'districtAll5' => $districtAll5,
            'id' => $id,
            'industryType' => $industryType,
        ]);
    }

    /*认证信息*/
    public function actionAuthInfo($id)
    {

        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();


//-------------------------------上传start---------------------------------



            $licnameA = date('Y-m-d H:i:s');//获取当前时间
            $licnameA = str_replace(':', '', $licnameA);
            $licnameA = str_replace(' ', '', $licnameA);
            $licnameA = str_replace('-', '', $licnameA);
            $licnameB = rand(0, 999);//获取0-999的随机数
            $licnameD = rand(1000, 1999);//获取1000-1999的随机数
            $licnameE = rand(2000, 2999);//获取2000-2999的随机数
//            $licnameC = pathinfo($postData['CrmC']['o_license'], PATHINFO_EXTENSION);
            if($postData['CrmC']['crtf_type'] == 0){
                $licnameC = pathinfo($postData['CrmC']['o_license'], PATHINFO_EXTENSION);
            }else{
                $licnameC = pathinfo($postData['CrmC']['o_license_new'], PATHINFO_EXTENSION);
                $postData['CrmC']['o_license']=$postData['CrmC']['o_license_new'];
            }
            $licnameF = pathinfo($postData['CrmC']['o_reg'], PATHINFO_EXTENSION);
            $licnameG = pathinfo($postData['CrmC']['o_cerft'], PATHINFO_EXTENSION);
            $remotefilelic = $licnameA . $licnameB . '.' . $licnameC;//公司营业执照证和三证合一新文件名
            $remotefiletax = $licnameA . $licnameD . '.' . $licnameF;//税务登记证新文件名
            $remotefileorg = $licnameA . $licnameE . '.' . $licnameG;//一般纳税人资格证新文件名
            $father = Yii::$app->ftpPath['CMP']['father'];
            $pathlcn = Yii::$app->ftpPath['CMP']['BsLcn'];
            $pathreg = Yii::$app->ftpPath['CMP']['TaxReg'];
            $pathqlf = Yii::$app->ftpPath['CMP']['TaxQlf'];
            $uploadaddress = date("ymd");
            $filelic = $_FILES['upfiles-lic']['tmp_name'];
            $filetax = $_FILES['upfiles-tax']['tmp_name'];
            $fileorg = $_FILES['upfiles-org']['tmp_name'];
//            $postData['CrmC']['bs_license'] = $remotefilelic;//公司营业执照证和三证合一新文件名
//            $postData['CrmC']['tx_reg'] = $remotefiletax;//税务登记证新文件名
//            $postData['CrmC']['qlf_certf'] = $remotefileorg;//一般纳税人资格证新文件名
//            dumpE($_FILES);


            if($filelic || $filetax || $fileorg){
                $ftp = new Ftp();
                if (!empty($filelic)) {
                    $fullDir = trim($father, "/") . "/" . trim($pathlcn, "/") . "/" . $uploadaddress;
                    if (!$ftp->ftp_dir_exists($fullDir)) {
                        $ftp->mkdirs($fullDir);
                    }
                    $tmpfile = \Yii::$app->getRuntimePath() . "\\tmpfile" . time();
                    $tmpfile = str_replace("\\", "/", $tmpfile);
                    $dest = $fullDir . "/" . trim($remotefilelic, "/");
                    if (move_uploaded_file($filelic, $tmpfile) && $ftp->put($dest, $tmpfile) && @unlink($tmpfile)) {
                        $postData['CrmC']['bs_license'] = $remotefilelic;
                    }
                }

                if (!empty($filetax)) {
                    $fullDir = trim($father, "/") . "/" . trim($pathreg, "/") . "/" . $uploadaddress;
                    if (!$ftp->ftp_dir_exists($fullDir)) {
                        $ftp->mkdirs($fullDir);
                    }
                    $tmpfile = \Yii::$app->getRuntimePath() . "\\tmpfile" . time();
                    $tmpfile = str_replace("\\", "/", $tmpfile);
                    $dest = $fullDir . "/" . trim($remotefiletax, "/");
                    if (move_uploaded_file($filetax, $tmpfile) && $ftp->put($dest, $tmpfile) && @unlink($tmpfile)) {
                        $postData['CrmC']['tx_reg'] = $remotefiletax;
                    }
                }

                if (!empty($fileorg)) {
                    $fullDir = trim($father, "/") . "/" . trim($pathqlf, "/") . "/" . $uploadaddress;
                    if (!$ftp->ftp_dir_exists($fullDir)) {
                        $ftp->mkdirs($fullDir);
                    }
                    $tmpfile = \Yii::$app->getRuntimePath() . "\\tmpfile" . time();
                    $tmpfile = str_replace("\\", "/", $tmpfile);
                    $dest = $fullDir . "/" . trim($remotefileorg, "/");
                    if (move_uploaded_file($fileorg, $tmpfile) && $ftp->put($dest, $tmpfile) && @unlink($tmpfile)) {
                        $postData['CrmC']['qlf_certf'] = $remotefileorg;
                    }
                }
            }

//-------------------------------上传end---------------------------------



            $url = $this->findApiUrl() . $this->_url . "auth-info?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "更新客户认证信息成功", "flag" => 1]);
            } else {
                return Json::encode(['msg' => $data["msg"], "flag" => 0]);
            }

        }else{
            $this->layout = '@app/views/layouts/ajax';
            $model = $this->getModel($id);
            $downList = $this->getDownList();
            $industryType = $this->getIndustryType();
            $districtId3 = $model['cust_district_3'];
            $districtAll3 = $this->getAllDistrict($districtId3);
            $crmcertf = $this->getCrmCertf($id);
            $newnName1 = $crmcertf['bs_license'];
            $newnName1 = substr($newnName1, 2, 8);
            $newnName1 = str_replace('-', '', $newnName1);
            $newnName2 = $crmcertf['tx_reg'];
            $newnName2 = substr($newnName2, 2, 8);
            $newnName2 = str_replace('-', '', $newnName2);
            $newnName3 = $crmcertf['qlf_certf'];
            $newnName3 = substr($newnName3, 2, 8);
            $newnName3 = str_replace('-', '', $newnName3);
            return $this->render('auth-info', [
                'newnName1' => $newnName1,
                'newnName2' => $newnName2,
                'newnName3' => $newnName3,
                'model' => $model,
                'downList' => $downList,
                'districtAll3' => $districtAll3,
                'id' => $id,
                'industryType' => $industryType,
                'crmcertf'=>$crmcertf
            ]);
        }
//-----------------------------------------------------------------------
//        if (Yii::$app->request->isPost) {
//            $postData = Yii::$app->request->post();
//            $url = $this->findApiUrl() . $this->_url . "auth-info?id=" . $id;
//            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
//            $data = Json::decode($curl->put($url));
//            if ($data['status'] == 1) {
//                SystemLog::addLog($data['data']);
//                return Json::encode(['msg' => "更新客户认证信息成功", "flag" => 1, "url" => yii\helpers\Url::to(['view', 'id' => $id])]);
//            } else {
//                return Json::encode(['msg' => $data["msg"], "flag" => 0]);
//            }
//        }else{
//            $this->layout = '@app/views/layouts/ajax';
//            $model = $this->getModel($id);
//            $downList = $this->getDownList();
//            $industryType = $this->getIndustryType();
//            $districtId3 = $model['cust_district_3'];
//            $districtAll3 = $this->getAllDistrict($districtId3);
//            $crmcertf = $this->getCrmCertf($id);
//            return $this->render('auth-info', [
//                'model' => $model,
//                'downList' => $downList,
//                'districtAll3' => $districtAll3,
//                'id' => $id,
//                'industryType' => $industryType,
//                'crmcertf'=>$crmcertf
//            ]);
//        }
    }
    /**
     * @param $id
     * @return string
     * 更新客户基本信息
     */
    public function actionUpdateCustomer($id)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "update-customer?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "更新客户基本信息成功", "flag" => 1, "url" => yii\helpers\Url::to(['view', 'id' => $id])]);
            } else {
                return Json::encode(['msg' => $data["msg"], "flag" => 0]);
            }
        }
    }



    /**
     * @param $id
     * @return string
     * 更新客户公司信息
     */
    public function actionCustomerCompany($id)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "customer-company?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "更新客户公司信息成功", "flag" => 1, "url" => yii\helpers\Url::to(['view', 'id' => $id])]);
            } else {
                return Json::encode(['msg' => $data["msg"], "flag" => 0]);
            }
        }
    }

    /**
     * @param $id
     * @return string
     * 联系人弹出层
     */
    public function actionCustomerContactPerson($id = null, $ccperId = null,$type = null)
    {
        $this->layout = '@app/views/layouts/ajax';
        $district = $this->getDistrict();
        if ($id != null && $ccperId == null) {
            return $this->render('contact-person', [
                'district' => $district,
                'id' => $id,
                'ccperId' => $ccperId,
            ]);
        } else if ($id != null && $ccperId != null) {
            $url = $this->findApiUrl() . $this->_url . "contact-edit?id=" . $ccperId;
            $result = Json::decode($this->findCurl()->get($url));
            return $this->render('contact-person', [
                'district' => $district,
                'result' => $result,
                'id' => $id,
                'ccperId' => $ccperId,
                'type' => $type
            ]);
        }
    }

    /**
     * @param $id
     * @return string
     * 新增联系人
     */
    public function actionContactCreate($id)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "contact-create?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "新增联系人成功", "flag" => 1, "url" => yii\helpers\Url::to(['view', 'id' => $id])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }
    }

    /**
     * @param $id
     * @return string
     * 更新联系人
     */
    public function actionContactUpdate($id)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $custId = $postData['CrmCustomerPersion']['cust_id'];
            $url = $this->findApiUrl() . $this->_url . "contact-update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "修改联系人成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }
    }

    /**
     * @param $id
     * @return string
     * 新增拜访计划
     */
    public function actionCreateVisit($id)
    {
        $post = Yii::$app->request->post();
        $post['CrmVisitPlan']['spend_time'] = serialize([$post['day'], $post['hours'], $post['minutes']]);
        $post['CrmVisitPlan']['title'] = '拜访' . $post['cust_sname'];
        $post['CrmVisitPlan']['start'] = $post['startDate'];
        $post['CrmVisitPlan']['end'] = $post['endDate'];
        $post['CrmVisitPlan']['color'] = '#FEE188';
        $post['CrmVisitPlan']['create_by'] = Yii::$app->user->identity->staff_id;
        $post['CrmVisitPlan']['company_id'] = Yii::$app->user->identity->company_id;
        $url = $this->findApiUrl() . $this->_url . "create-visit?id=" . $id;
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $data = Json::decode($curl->post($url));
        if ($data['status'] == 1) {
            SystemLog::addLog($data['data']);
            return Json::encode(['msg' => "新增拜访计划成功", "flag" => 1, "url" => Url::to(['view', 'id' => $id])]);
        } else {
            return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return string
     * 编修改拜访计划
     */
    public function actionEditPlan($id)
    {
        if ($post = Yii::$app->request->post()) {
            $custId = $post['CrmVisitPlan']['cust_id'];
            $post['CrmVisitPlan']['spend_time'] = serialize([$post['day'], $post['hours'], $post['minutes']]);
            $post['CrmVisitPlan']['title'] = '拜访' . $post['cust_sname'];
            $post['CrmVisitPlan']['start'] = $post['startDate'];
            $post['CrmVisitPlan']['end'] = $post['endDate'];
            $url = $this->findApiUrl() . $this->_url . "edit-plan?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $curl->post($url);
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "编辑拜访计划成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        }
    }

    /**
     * @param null $id
     * @param null $svpId
     * @return string
     * 拜访计划弹出层
     */
    public function actionVisitPlanInfo($id = null, $svpId = null)
    {
        $this->layout = '@app/views/layouts/ajax';
        $downList = $this->getManageList();
        $model = $this->getModel($id);
        if ($id != null && $svpId == null) {
            return $this->render('visit-plan-info', [
                'model' => $model,
                'downList' => $downList,
                'id' => $id,
                'svpId' => $svpId,
            ]);
        } else if ($id != null && $svpId != null) {
            $visitPlan = $this->getOnePlan($svpId);
            $spendTime = unserialize($visitPlan['spend_time']);
            return $this->render('visit-plan-info', [
                'model' => $model,
                'spendTime' => $spendTime,
                'visitPlan' => $visitPlan,
                'downList' => $downList,
                'id' => $id,
                'svpId' => $svpId,
            ]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 拜访记录弹出层
     */
    public function actionVisitPlanRecord($id = null, $silId = null)
    {
        $this->layout = '@app/views/layouts/ajax';
//        $downList=Yii::$app->runAction('crm/crm-plan-manage/get-down-list');
        $downList = $this->getManageList();
        $model = $this->getModel($id);
        if ($id != null && $silId == null) {
            return $this->render('visit-record', [
                'model' => $model,
                'downList' => $downList,
                'id' => $id,
                'silId' => $silId,
            ]);
        } else if ($id != null && $silId != null) {
            $url = $this->findApiUrl() . "crm/crm-visit-record/get-record-one?id=" . $silId;
            $visitRecord = Json::decode($this->findCurl()->get($url));
//            dumpE($visitRecord);
            $spendTime = unserialize($visitRecord['sil_time']);
            return $this->render('visit-record', [
                'model' => $model,
                'spendTime' => $spendTime,
                'record' => $visitRecord,
                'downList' => $downList,
                'id' => $id,
                'silId' => $silId,
            ]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 新增拜访记录
     */
    public function actionCreateInfo($id)
    {
        $post = Yii::$app->request->post();
        $post['CrmVisitRecordChild']['sil_time'] = serialize([$post['day-1'], $post['hours-1'], $post['minutes-1']]);
        $post['CrmVisitRecordChild']['title'] = '拜访' . $post['cust_sname'];
        $post['CrmVisitRecordChild']['start'] = $post['arriveDate'];
        $post['CrmVisitRecordChild']['end'] = $post['leaveDate'];
//        $post['CrmVisitInfoChild']['color'] = '#FEE188';
        $post['CrmVisitRecord']['create_by'] = $post['CrmVisitRecordChild']['create_by'] = Yii::$app->user->identity->staff_id;
        $post['CrmVisitRecord']['company_id'] = $post['CrmVisitRecordChild']['company_id'] = Yii::$app->user->identity->company_id;
        $url = $this->findApiUrl() . $this->_url . "create-info?id=" . $id;
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $data = Json::decode($curl->post($url));
        if ($data['status'] == 1) {
            SystemLog::addLog($data['data']);
            return Json::encode(['msg' => "新增拜访记录成功", "flag" => 1, "url" => Url::to(['view', 'id' => $id])]);
        } else {
            return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 编辑拜访记录
     */
    //编辑拜访记录
    public function actionEditInfo($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $custId = $postData['CrmVisitRecord']['cust_id'];
            $postData['CrmVisitRecordChild']['company_id'] = Yii::$app->user->identity->company_id;
            $postData['CrmVisitRecordChild']['sil_time'] = serialize([$postData['day-1'], $postData['hours-1'], $postData['minutes-1']]);
            $postData['CrmVisitRecordChild']['start'] = $postData['arriveDate'];
            $postData['CrmVisitRecordChild']['end'] = $postData['leaveDate'];
            $url = $this->findApiUrl() . $this->_url . "edit-info?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "修改拜访记录成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }
    }

    /**
     * @param $id
     * @return mixed
     * 设备弹出层
     */
    public function actionCustDeviceInfo($id = null, $custdId = null)
    {
        $this->layout = '@app/views/layouts/ajax';
        if ($id != null && $custdId == null) {
            return $this->render('cust-device', [
                'id' => $id,
                'custdId' => $custdId,
            ]);
        } else if ($id != null && $custdId != null) {
            $url = $this->findApiUrl() . $this->_url . "get-cust-device-one?id=" . $custdId;
            $result = Json::decode($this->findCurl()->get($url));
            return $this->render('cust-device', [
                'result' => $result,
                'id' => $id,
                'custdId' => $custdId,
            ]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 新增设备
     */
    public function actionCreateDevice($id)
    {
        $post = Yii::$app->request->post();
        $post['CrmCustDevice']['create_by'] = $post['CrmVisitInfoChild']['create_by'] = Yii::$app->user->identity->staff_id;
        $post['CrmCustDevice']['company_id'] = $post['CrmVisitInfoChild']['company_id'] = Yii::$app->user->identity->company_id;
        $url = $this->findApiUrl() . $this->_url . "create-device?id=" . $id;
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $data = Json::decode($curl->post($url));
        if ($data['status'] == 1) {
            SystemLog::addLog($data['data']);
            return Json::encode(['msg' => "新增设备成功", "flag" => 1, "url" => Url::to(['view', 'id' => $id])]);
        } else {
            return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 修改设备信息
     */
    public function actionUpdateDevice($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $custId = $postData['cust_id'];
            $url = $this->findApiUrl() . $this->_url . "update-device?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "修改设备信息成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }
    }


    /**
     * @param $id
     * @return mixed
     * 主营产品弹出层
     */
    public function actionMainProduct($id = null, $ccpId = null)
    {
        $this->layout = '@app/views/layouts/ajax';
        if ($id != null && $ccpId == null) {
            return $this->render('main-product', [
                'id' => $id,
                'ccpId' => $ccpId,
            ]);
        } else if ($id != null && $ccpId != null) {
            $url = $this->findApiUrl() . $this->_url . "get-main-product-one?id=" . $ccpId;
            $result = Json::decode($this->findCurl()->get($url));
            return $this->render('main-product', [
                'result' => $result,
                'id' => $id,
                'ccpId' => $ccpId,
            ]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 新增主营产品
     */
    public function actionCreateMainProduct($id)
    {
        $post = Yii::$app->request->post();
        $post['CrmCustProduct']['create_by'] = Yii::$app->user->identity->staff_id;
        $url = $this->findApiUrl() . $this->_url . "create-main-product?id=" . $id;
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $data = Json::decode($curl->post($url));
        if ($data['status'] == 1) {
            SystemLog::addLog($data['data']);
            return Json::encode(['msg' => "新增主营产品成功", "flag" => 1, "url" => Url::to(['view', 'id' => $id])]);
        } else {
            return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 修改主营产品信息
     */
    public function actionUpdateMainProduct($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $custId = $postData['cust_id'];
            $url = $this->findApiUrl() . $this->_url . "update-main-product?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "修改主营产品信息成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }
    }


    /**
     * @param $id
     * @return mixed
     * 主要客户弹出层
     */
    public function actionMainCustomer($id = null, $ccId = null)
    {
        $this->layout = '@app/views/layouts/ajax';
        $downList = $this->getDownList();
        if ($id != null && $ccId == null) {
            return $this->render('main-customer', [
                'id' => $id,
                'ccId' => $ccId,
                'downList' => $downList
            ]);
        } else if ($id != null && $ccId != null) {
            $url = $this->findApiUrl() . $this->_url . "get-main-customer-one?id=" . $ccId;
            $result = Json::decode($this->findCurl()->get($url));
            return $this->render('main-customer', [
                'result' => $result,
                'id' => $id,
                'ccId' => $ccId,
                'downList' => $downList
            ]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 新增主要客户
     */
    public function actionCreateMainCustomer($id)
    {
        $post = Yii::$app->request->post();
        $post['CrmCustCustomer']['create_by'] = Yii::$app->user->identity->staff_id;
        $url = $this->findApiUrl() . $this->_url . "create-main-customer?id=" . $id;
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $data = Json::decode($curl->post($url));
        if ($data['status'] == 1) {
            SystemLog::addLog($data['data']);
            return Json::encode(['msg' => "新增主要客户成功", "flag" => 1, "url" => Url::to(['view', 'id' => $id])]);
        } else {
            return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 修改主要客户信息
     */
    public function actionUpdateMainCustomer($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $custId = $postData['cust_id'];
            $url = $this->findApiUrl() . $this->_url . "update-main-customer?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "修改主要客户成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }
    }


    /**
     * @param $id
     * @return mixed
     * 商机商品弹出层
     */
    public function actionCustOddsitem($id = null, $oddsId = null)
    {
        $this->layout = '@app/views/layouts/ajax';
        $firmCategory = $this->getFirmCategory();
//        $bsProduct = $this->getBsProduct();
        if ($id != null && $oddsId == null) {
            return $this->render('cust-oddsitem', [
                'id' => $id,
                'oddsId' => $oddsId,
                'firmCategory' => $firmCategory,
//                'bsProduct' => $bsProduct,
            ]);
        } else if ($id != null && $oddsId != null) {
            $url = $this->findApiUrl() . $this->_url . "get-cust-oddsitem-one?id=" . $oddsId;
            $result = Json::decode($this->findCurl()->get($url));
//            dumpE($result);
            return $this->render('cust-oddsitem', [
                'result' => $result,
                'id' => $id,
                'oddsId' => $oddsId,
                'firmCategory' => $firmCategory,
//                'bsProduct' => $bsProduct,
            ]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 添加商机商品
     */
    public function actionCreateCustOddsitem($id)
    {
        $post = Yii::$app->request->post();
        $post['CrmCustOddsitem']['create_by'] = Yii::$app->user->identity->staff_id;
        $url = $this->findApiUrl() . $this->_url . "create-cust-oddsitem?id=" . $id;
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $data = Json::decode($curl->post($url));
        if ($data['status'] == 1) {
            SystemLog::addLog($data['data']);
            return Json::encode(['msg' => "新增商机商品成功", "flag" => 1, "url" => Url::to(['view', 'id' => $id])]);
        } else {
            return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 修改商机商品
     */
    public function actionUpdateCustOddsitem($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $custId = $postData['cust_id'];
            $url = $this->findApiUrl() . $this->_url . "update-cust-oddsitem?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "修改商机商品成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }
    }

    /**
     * @param $id
     * @return mixed
     * 关联公司弹出层
     */
    public function actionLinkComp($id = null, $lincId = null)
    {
        $this->layout = '@app/views/layouts/ajax';
        $downList = $this->getDownList();
        if ($id != null && $lincId == null) {
            return $this->render('link-comp', [
                'downList' => $downList,
                'id' => $id,
                'lincId' => $lincId,
            ]);
        } else if ($id != null && $lincId != null) {
            $url = $this->findApiUrl() . $this->_url . "get-link-comp-one?id=" . $lincId;
            $result = Json::decode($this->findCurl()->get($url));
            $districtId2 = $result['linc_district'];
            $districtAll2 = $this->getAllDistrict($districtId2);
            return $this->render('link-comp', [
                'downList' => $downList,
                'id' => $id,
                'lincId' => $lincId,
                'result' => $result,
                'districtAll2' => $districtAll2
            ]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 添加关联公司
     */
    public function actionCreateLinkComp($id)
    {
        $post = Yii::$app->request->post();
        $post['CrmCustLinkcomp']['create_by'] = Yii::$app->user->identity->staff_id;
        $url = $this->findApiUrl() . $this->_url . "create-link-comp?id=" . $id;
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $data = Json::decode($curl->post($url));
        if ($data['status'] == 1) {
            SystemLog::addLog($data['data']);
            return Json::encode(['msg' => "新增关联公司成功", "flag" => 1, "url" => Url::to(['view', 'id' => $id])]);
        } else {
            return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 修改关联公司
     */
    public function actionUpdateLinkComp($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $custId = $postData['cust_id'];
            $url = $this->findApiUrl() . $this->_url . "update-link-comp?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "修改关联公司成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }
    }

    /**
     * @param $id
     * @return mixed
     * 认领信息弹出层
     */
    public function actionPersonInch()
    {
        $this->layout = '@app/views/layouts/ajax';
        $get = Yii::$app->request->get();
        $downList = $this->getDownList();
        if ($get['status'] == 0) {
            return $this->render('person-inch', [
                'downList' => $downList,
                'status' => $get['status'],
                'id' => $get['id']
            ]);
        } else {
            $model = $this->getPersonInchOne($get['ccpichId']);
            $sales = $this->getSales($model['manager']['id']);
            return $this->render('person-inch', [
                'downList' => $downList,
                'model' => $model,
                'status' => $get['status'],
                'id' => $get['id'],
                'sales' => $sales
            ]);
        }
    }

    /*获取认领信息*/
    public function getPersonInchOne($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-info/get-person-inch-one?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function getSales($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-info/get-sales?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     * 修改认领信息
     */
    public function actionUpdatePersonInch($id, $ccpichId = null, $status)
    {
        if ($status == '0') {
            if (Yii::$app->request->isPost) {
                $postData = Yii::$app->request->post();
                $postData['CrmCustPersoninch']['ccpich_date'] = date("Y-m-d H:i:s", time());
                $url = $this->findApiUrl() . $this->_url . "update-person-inch?id=" . $id . "&status=" . $status;
                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                $data = Json::decode($curl->post($url));
                if ($data['status'] == 1) {
                    SystemLog::addLog($data['data']);
                    return Json::encode(['msg' => "新增认领信息成功", "flag" => 1, "url" => yii\helpers\Url::to(['view', 'id' => $id])]);
                } else {
                    return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
                }
            }
        } else if ($status == '10') {
            if (Yii::$app->request->isPost) {
                $postData = Yii::$app->request->post();
                $postData['CrmCustPersoninch']['ccpich_date'] = date("Y-m-d H:i:s", time());
                $url = $this->findApiUrl() . $this->_url . "update-person-inch?id=" . $ccpichId . "&status=" . $status;
                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                $data = Json::decode($curl->put($url));
                if ($data['status'] == 1) {
                    SystemLog::addLog($data['data']);
                    return Json::encode(['msg' => "修改认领信息成功", "flag" => 1, "url" => yii\helpers\Url::to(['view', 'id' => $id])]);
                } else {
                    return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
                }
            }
        }
    }

    /*取消认领信息*/
    public function actionCanclePersonInch($id, $ccpichId)
    {
        $url = $this->findApiUrl() . $this->_url . "cancle-person-inch?id=" . $ccpichId;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == '1') {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "取消成功", "flag" => 1, "url" => Url::to(['view', 'id' => $id])]);
        } else {
            return Json::encode(["msg" => "取消失败", "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 采购弹出层
     */
    public function actionCustPurchaseInfo($id = null, $cpurchId = null)
    {
        $this->layout = '@app/views/layouts/ajax';
        $downList = $this->getDownList();
        if ($id != null && $cpurchId == null) {
            return $this->render('cust-purchase', [
                'id' => $id,
                'cpurchId' => $cpurchId,
                'downList'=>$downList
            ]);
        } else if ($id != null && $cpurchId != null) {
            $url = $this->findApiUrl() . $this->_url . "get-cust-purchase-one?id=" . $cpurchId;
            $result = Json::decode($this->findCurl()->get($url));
            return $this->render('cust-purchase', [
                'result' => $result,
                'id' => $id,
                'cpurchId' => $cpurchId,
                'downList'=>$downList
            ]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 添加采购信息
     */
    public function actionCreateCustPurchase($id)
    {
        $post = Yii::$app->request->post();
        $post['CrmCustPurchase']['create_by'] = Yii::$app->user->identity->staff_id;
        $url = $this->findApiUrl() . $this->_url . "create-cust-purchase?id=" . $id;
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $data = Json::decode($curl->post($url));
        if ($data['status'] == 1) {
            SystemLog::addLog($data['data']);
            return Json::encode(['msg' => "新增采购信息成功", "flag" => 1, "url" => Url::to(['view', 'id' => $id])]);
        } else {
            return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 修改采购信息
     */
    public function actionUpdateCustPurchase($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $custId = $postData['cust_id'];
            $url = $this->findApiUrl() . $this->_url . "update-cust-purchase?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "修改采购成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }
    }

    /**
     * @param $id 表ID
     * @param $str 路径
     * @return string
     * 删除各项信息
     */
    public function actionDeleteMessage($id,$str){
        $url = $this->findApiUrl() . $this->_url . $str."?id=" . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == '1') {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "删除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "删除失败", "flag" => 0]);
        }
    }

    /*修改表格列弹出层*/
    public function actionSysList($id,$str=null)
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "update-sys-list?id=".$id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
//                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "更改列成功", "flag" => 1]);
            } else {
                return Json::encode(['msg' => "更改列失败", "flag" => 0]);
            }
        }
        $this->layout = '@app/views/layouts/ajax';
        $url = $this->findApiUrl().$this->_url.'sys-list?id='.$id;
        $result = Json::decode($this->findCurl()->get($url));
        $select = $this->getSelectField($id);
        return $this->render('_select',[
            'result'=>$result,
            'select'=>$select,
            'id'=>$id,
            'str'=>$str
        ]);
    }

    /**
     * @param $id
     * @return string
     * 模块设置
     */
    public function actionModule($id){
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "module?id=".$id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
//            dumpE($data);
            if ($data['status'] == 1) {
//                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "设置成功", "flag" => 1]);
            } else {
                return Json::encode(['msg' => "设置失败", "flag" => 0]);
            }
        }
        $this->layout = '@app/views/layouts/ajax';
        $module = $this->getModuleShow(Yii::$app->user->identity->staff_id);
//        dumpE($module);
        return $this->render('_module',[
            'id'=>$id,
            'module'=>$module
        ]);
    }

    public function actionModuleDelete($id,$name){
        $url = $this->findApiUrl() . $this->_url . "module-delete?id=" . $id . '&name='.$name;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
//            SystemLog::addLog($result['data']['msg']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失敗", "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 获取隐藏列
     */
    public function getSelectField($id){
        $url = $this->findApiUrl().$this->_url.'sys-select-list?id='.$id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*认领信息客户经理人*/
    public function actionGetManagerStaffInfo($code)
    {
        $url = $this->findApiUrl() . $this->_url . "get-manager-staff-info?code=" . $code;
        $result = $this->findCurl()->get($url);
        return $result;
    }

    /*认领信息 销售人员*/
    public function actionGetSaleStaffInfo($code, $leaderId)
    {
        $url = $this->findApiUrl() . $this->_url . "get-sale-staff-info?code=" . $code . "&leaderId=" . $leaderId;
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

    /*客户类型等下拉菜单*/
    public function getDownList()
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-info/down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function getManageList()
    {
        $url = $this->findApiUrl() . "/crm/crm-plan-manage/down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function getOnePlan($id)
    {
        $url = $this->findApiUrl() . '/crm/crm-visit-plan/get-one-plan?id=' . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*获取省份*/
    public function getDistrict()
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-info/district";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }


    /*行业类别*/
    public function getIndustryType()
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-info/industry-type";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*分级分类*/
    public function getFirmCategory()
    {
        $url = $this->findApiUrl() . $this->_url . "firm-category";
        return Json::decode($this->findCurl()->get($url));
    }

    /*商品信息*/
    public function getBsProduct()
    {
        $url = $this->findApiUrl() . $this->_url . "get-bs-product";
        return Json::decode($this->findCurl()->get($url));
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
}
