<?php

namespace app\modules\crm\controllers;

use app\classes\Menu;
use app\controllers\BaseController;
use app\models\Upload;
use app\models\User;
use app\modules\system\models\SystemLog;
use app\widgets\ueditor\Ftp;
use app\widgets\ueditor\FtpUploader;
use yii\helpers\Json;
use yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class CrmCustomerInfoController extends BaseController
{
    public $_url = "crm/crm-customer-info/";  //对应api控制器URL

    public function beforeAction($action)
    {
        $this->ignorelist = array_merge($this->ignorelist, [
            "/ptdt/firm/get-district",
            "/hr/staff/get-staff-info",
            "/crm/crm-customer-info/get-district-salearea",
            "/crm/crm-customer-info/import",
            "/crm/crm-customer-info/select-manage",
            "/crm/crm-customer-info/select-customer",
            "/crm/crm-customer-info/validate-code",
            "/crm/crm-customer-info/allotman-select",
            "/crm/crm-customer-info/upload",
            "/crm/crm-credit-apply/settlement",
        ]);
        return parent::beforeAction($action);
    }
    public function actions(){
        return array_merge(parent::actions(),[
            'upload' => [
                'class' => \app\widgets\upload\UploadAction::className(),
                'scene'=>trim(\Yii::$app->ftpPath["CCA"]["father"],"/")."/".trim(\Yii::$app->ftpPath["CCA"]["Credit"],"/")
            ],
        ]);
    }
    /**
     * @return mixed|string
     * 客户资料列表
     */
    public function actionIndex()
    {
        $isSuper = User::isSupper(Yii::$app->user->identity->user_id) == true ? '1' : '0';

        $url = $this->findApiUrl() . $this->_url . "index?companyId=" . Yii::$app->user->identity->company_id;

        $queryParam = Yii::$app->request->queryParams;
        $queryParam['user_id'] = Yii::$app->user->identity->user_id;
        $queryParam['managerId'] = Yii::$app->user->identity->staff_id;
        $queryParam['isSuper'] = $isSuper;
        $u = $this->getEmployee(Yii::$app->user->identity->staff_id);
        if ($isSuper == '1' || empty($u)) {
            $staff = '';
        } else {
            $staff = \Yii::$app->user->identity->staff->staff_id;
        }
        $queryParam["CrmCustomerInfoSearch"]["custManager"] = isset($queryParam["CrmCustomerInfoSearch"]["custManager"]) ? $queryParam["CrmCustomerInfoSearch"]["custManager"] : $staff;
        $queryParam['CrmCustomerInfoSearch']['sale_status'] = isset($queryParam['CrmCustomerInfoSearch']['sale_status']) ? $queryParam['CrmCustomerInfoSearch']['sale_status'] : '10';
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
//        echo $this->findCurl()->get($url);exit;
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = Json::decode($this->findCurl()->get($url));
            foreach ($dataProvider['rows'] as $key => $val) {
                if (Menu::isAction('/crm/crm-customer-info/view')) {
                    $dataProvider['rows'][$key]['cust_filernumber'] = '<a href="' . yii\helpers\Url::to(['view', 'id' => $val['cust_id']]) . '">' . $val['cust_filernumber'] . '</a>';
                }
            }
            return Json::encode($dataProvider);
        }
//        $export = Yii::$app->request->get('export');
//        if (isset($export)) {
//            @ini_set('max_execution_time',600);
//            @ini_set('memory_limit','1024M');
//            $this->exportFiled(Json::decode($this->findCurl()->get($url))['rows']);
//        }
        $downList = $this->getDownList();
//        echo $downList['manager'];exit;
        $columns = $this->getField("/crm/crm-customer-info/index");
//        $isSuper=User::isSupper(Yii::$app->user->identity->user_id);
        $loginId = Yii::$app->user->identity->staff_id;
        return $this->render('index', [
            'downList' => $downList,
            'queryParam' => $queryParam,
            'columns' => $columns,
            'isSuper' => $isSuper == true ? 1 : 0,
            'e' => $u,
            'loginId' => $loginId
        ]);
    }

    /**
     * 导出销售客户
     */
    public function actionExport()
    {
        $isSuper = User::isSupper(Yii::$app->user->identity->user_id) == true ? '1' : '0';
        $url = $this->findApiUrl() . $this->_url . "index?export=1&companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        $queryParam['user_id'] = Yii::$app->user->identity->user_id;
        $queryParam['managerId'] = Yii::$app->user->identity->staff_id;
        $queryParam['isSuper'] = $isSuper;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        \Yii::$app->controller->action->id = 'index';
        SystemLog::addLog('导出销售客户列表');
        @ini_set('max_execution_time', 600);
        @ini_set('memory_limit', '1024M');
        return $this->exportFiled($dataProvider['rows']);
    }

    /**
     * @param $id
     * @return string
     * 新增客户信息
     */
    public function actionCreate()
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();

            $licnameA = date('Y-m-d H:i:s');//获取当前时间
            $licnameA = str_replace(':', '', $licnameA);
            $licnameA = str_replace(' ', '', $licnameA);
            $licnameA = str_replace('-', '', $licnameA);
            $licnameB = rand(0, 999);//获取0-999的随机数
            $licnameD = rand(1000, 1999);//获取1000-1999的随机数
            $licnameE = rand(2000, 2999);//获取2000-2999的随机数
            if($postData['CrmC']['crtf_type'] == 0){
                $licnameC = pathinfo($postData['CrmC']['o_license'], PATHINFO_EXTENSION);
            }else{
                $licnameC = pathinfo($postData['CrmC']['o_license_new'], PATHINFO_EXTENSION);
                $postData['CrmC']['o_license']=$postData['CrmC']['o_license_new'];
            }
            $licnameF = pathinfo($postData['CrmC']['o_reg'], PATHINFO_EXTENSION);
            $licnameG = pathinfo($postData['CrmC']['o_cerft'], PATHINFO_EXTENSION);
            $remotefilelic = $licnameA . $licnameB . '.' . $licnameC;//公司营业执照证和三证合一新文件名
            if($postData['CrmC']['crtf_type'] == 0){
                $remotefiletax = $licnameA . $licnameD . '.' . $licnameF;//税务登记证新文件名
            }else{
                $remotefiletax = '';
            }
            $remotefileorg = $licnameA . $licnameE . '.' . $licnameG;//一般纳税人资格证新文件名
            $postData['CrmC']['bs_license'] = $remotefilelic;//公司营业执照证和三证合一新文件名
            $postData['CrmC']['tx_reg'] = $remotefiletax;//税务登记证新文件名
            $postData['CrmC']['qlf_certf'] = $remotefileorg;//一般纳税人资格证新文件名
//-------------------------------上传start---------------------------------



            $father = Yii::$app->ftpPath['CMP']['father'];
            $pathlcn = Yii::$app->ftpPath['CMP']['BsLcn'];
            $pathreg = Yii::$app->ftpPath['CMP']['TaxReg'];
            $pathqlf = Yii::$app->ftpPath['CMP']['TaxQlf'];
            $uploadaddress = date("ymd");
            $filelic = $_FILES['upfiles-lic']['tmp_name'];
            $filetax = $_FILES['upfiles-tax']['tmp_name'];
            $fileorg = $_FILES['upfiles-org']['tmp_name'];

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
            $postData['CrmCustomerInfo']['company_id'] = Yii::$app->user->identity->company_id;
            $postData['CrmCustomerInfo']['create_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg']);
                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
            } else {
                return Json::encode(['msg' => $data["msg"], "flag" => 0]);
            }
        } else {
            $district = $this->getDistrict();
            $downList = $this->getDownList();
            $isSuper = User::isSupper(Yii::$app->user->identity->user_id);
            $u = $this->getEmployee(Yii::$app->user->identity->staff_id);
            return $this->render("create", [
                'downList' => $downList,
                'district' => $district,
                'isSuper' => $isSuper,
                'u' => $u
            ]);
        }
    }

    public function actionUpdate($id, $type = null)
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
            $postData['CrmC']['bs_license'] = $remotefilelic;//公司营业执照证和三证合一新文件名
            $postData['CrmC']['tx_reg'] = $remotefiletax;//税务登记证新文件名
            $postData['CrmC']['qlf_certf'] = $remotefileorg;//一般纳税人资格证新文件名

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

            $postData['CrmCustomerInfo']['update_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "update?id={$id}&type={$type}";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg']);
                return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
            } else {
                return Json::encode(['msg' => $data["msg"], "flag" => 0]);
            }
        } else {

            $model = $this->getModel($id);
            $crmcertf = $this->getCrmCertf($id);
            $districtId2 = $model['cust_district_2'];
            $districtId3 = $model['cust_district_3'];
            $districtId4 = $model['invoice_title_district'];
            $districtId5 = $model['invoice_mail_district'];
            $districtAll2 = $this->getAllDistrict($districtId2);
            $districtAll3 = $this->getAllDistrict($districtId3);
            $districtAll4 = $this->getAllDistrict($districtId4);
            $districtAll5 = $this->getAllDistrict($districtId5);
            $downList = $this->getDownList();
            $district = $this->getDistrict();
            $isSuper = User::isSupper(Yii::$app->user->identity->user_id);
            if(isset($type)) {
                return $this->render("_applyform", [
                    'model' => $model,
                    'downList' => $downList,
                    'district' => $district,
                    'districtAll2' => $districtAll2,
                    'districtAll3' => $districtAll3,
                    'districtAll4' => $districtAll4,
                    'districtAll5' => $districtAll5,
                    'isSuper' => $isSuper,
                    'crmcertf' => $crmcertf
                ]);
            }
            $u = $this->getEmployee(Yii::$app->user->identity->staff_id);
            return $this->render("update", [
                "crmcertf" => $crmcertf,
                'model' => $model,
                'downList' => $downList,
                'district' => $district,
                'districtAll2' => $districtAll2,
                'districtAll3' => $districtAll3,
                'districtAll4' => $districtAll4,
                'districtAll5' => $districtAll5,
                'isSuper' => $isSuper,
                'u' => $u,
            ]);
        }
    }

    /**
     * @return string
     * 新增
     */
    public function actionCreditCreate($id = null)
    {
        $uploadModel = new Upload();
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $isApply = Yii::$app->request->get('is_apply');
//            $type = Yii::$app->request->get('type');
            /*文件上传*/
//            $uploadModel->message = UploadedFile::getInstances($uploadModel, 'message');
//            $uploadModel->file = UploadedFile::getInstances($uploadModel, 'file');
//            if (!empty($uploadModel->message)) {
//                if (!$uploadModel->validate(['message'])) {
//                    return Json::encode(['msg' => current($uploadModel->getFirstErrors()), "flag" => 0]);
//                }
//                $father = Yii::$app->ftpPath['CCA']['father'];
//                $pathlcn = Yii::$app->ftpPath['CCA']['Credit'];
//                $uploadaddress = date("ymd");
//                $ftp = new Ftp();
//                $fullDir = trim($father, "/") . "/" . trim($pathlcn, "/") . "/" . $uploadaddress;
//                if (!$ftp->ftp_dir_exists($fullDir)) {
//                    $ftp->mkdirs($fullDir);
//                }
//                foreach ($uploadModel->message as $k => $attach) {
//                    $fileName = $attach->name;
//                    $newfileName = $uploadaddress . (time() + $k) . "." . pathinfo($fileName, PATHINFO_EXTENSION);
//                    $tempName = $attach->tempName;
//                    $tmpfile = \Yii::$app->getRuntimePath() . "\\tmpfile" . time();
//                    $tmpfile = str_replace("\\", "/", $tmpfile);
//                    $dest = "/".$fullDir . "/" . trim($newfileName, "/");
//                    if (move_uploaded_file($tempName, $tmpfile) && $ftp->put($dest, $tmpfile) && @unlink($tmpfile)) {
//                        $post['message'][$k]["file_old"] = $fileName;
//                        $post['message'][$k]["file_new"] = $newfileName;
//                    } else {
//                        return Json::encode(['msg' => "上传文件失败", "flag" => 0]);
//                    }
//                }
//            }
//
//            if (!empty($uploadModel->file)) {
//                if (!$uploadModel->validate(['file'])) {
//                    return Json::encode(['msg' => current($uploadModel->getFirstErrors()), "flag" => 0]);
//                }
//                $father = Yii::$app->ftpPath['CCA']['father'];
//                $pathlcn = Yii::$app->ftpPath['CCA']['Credit'];
//                $uploadaddress = date("ymd");
//
//                $ftp = new Ftp();
//                $fullDir = trim($father, "/") . "/" . trim($pathlcn, "/") . "/" . $uploadaddress;
//                if (!$ftp->ftp_dir_exists($fullDir)) {
//                    $ftp->mkdirs($fullDir);
//                }
//                foreach ($uploadModel->file as $k => $attach) {
//                    $fileName = $attach->name;
//                    $newfileName = $uploadaddress . (time() + $k + 1) . "." . pathinfo($fileName, PATHINFO_EXTENSION);
//                    $tempName = $attach->tempName;
//                    $tmpfile = \Yii::$app->getRuntimePath() . "\\tmpfile" . time();
//                    $tmpfile = str_replace("\\", "/", $tmpfile);
//                    $dest = $fullDir . "/" . trim($newfileName, "/");
//                    if (move_uploaded_file($tempName, $tmpfile) && $ftp->put($dest, $tmpfile) && @unlink($tmpfile)) {
//                        $post['file'][$k]["file_old"] = $fileName;
//                        $post['file'][$k]["file_new"] = $newfileName;
//                    } else {
//                        return Json::encode(['msg' => "上传文件失败", "flag" => 0]);
//                    }
//                }
//            }
            $post['LCrmCreditApply']['create_by'] = Yii::$app->user->identity->staff_id;
            $post['LCrmCreditApply']['credit_people'] = Yii::$app->user->identity->staff_id;
            $post['LCrmCreditApply']['company_id'] = Yii::$app->user->identity->company_id;
//            $post['LCrmCreditLimit']['payment_clause'] = serialize($post['LCrmCreditLimit']['payment_clause']);
//            $post['CrmCustomerInfo']['total_investment'] = serialize($post['CrmCustomerInfo']['total_investment']);

            $url = $this->findApiUrl() . "/crm/crm-credit-apply/create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog('新增' . $post['cust_name'] . '客户账信申请');
                if (!empty($isApply)) {
                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['/crm/crm-credit-apply/view','id'=>$data['data'],'is_apply'=>1])]);
                } else {
                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['/crm/crm-credit-apply/view','id'=>$data['data']])]);
                }
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        }
        $downList = $this->getDownList();
        $model = $this->getCustomer($id);
        $apply = $this->getCustomerRelation($id);
        $a=[];
        foreach ($apply['turnover'] as $k => $v){
            $a[$v['currency']][$v['year']] = $v['turnover'];
        }
        $apply['turnover'] = $a;
//        dumpE($apply['turnover']);
        return $this->render('/crm-credit-apply/create', [
            'downList' => $downList,
            'model' => $model,
            'apply' => $apply
        ]);
    }

    /**
     * 新增拜访计划
     * @return string
     */
    public function actionPlanCreate($customerId = null)
    {
        if ($post = Yii::$app->request->post()) {
            $post['CrmVisitPlan']['create_by'] = Yii::$app->user->identity->staff_id;
            $post['CrmVisitPlan']['company_id'] = Yii::$app->user->identity->company_id;
            $url = $this->findApiUrl() . "/crm/crm-visit-plan/create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog('新增编号为' . $data['data']['code'] . '的拜访计划');
                return Json::encode(['msg' => $data['msg'], "flag" => 1, "url" => yii\helpers\Url::to(['/crm/crm-visit-plan/view', 'id' => $data['data']['id']])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $downList = $this->getDownList();
        $model = $this->getCust($customerId);
        return $this->render('/crm-visit-plan/create', [
            'downList' => $downList,
            'userInfo' => $this->getUserInfo(),
            'model' => $model
        ]);
    }

    /**
     * @return string
     * 分配
     */
    public function actionAssign(){
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['CrmCustPersoninch']['ccpich_date'] = date("Y-m-d H:i:s", time());
            $url = $this->findApiUrl() . $this->_url . "update-person-inch";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => $data['msg'], "flag" => 1]);
            } else {
                return Json::encode(['msg' => $data["msg"], "flag" => 0]);
            }
        }
        $this->layout = '@app/views/layouts/ajax';
        $url=$this->findApiUrl().$this->_url."get-user-org?staff_id=".\Yii::$app->user->identity->staff_id;
        $orgCode=Json::decode($this->findCurl()->get($url));
        $url=$this->findApiUrl().$this->_url."get-user-org-staff?staff_id=".\Yii::$app->user->identity->staff_id;
        $orgStaff=Json::decode($this->findCurl()->get($url));
        $url=$this->findApiUrl().$this->_url."claim-dropdown-list";
        $res=Json::decode($this->findCurl()->get($url));
        return $this->render('_assign',["department"=>$res,"orgCode"=>$orgCode,"orgStaff"=>$orgStaff]);
    }

    //分配人部门下拉列表
    public function actionAllotmanSelect($org_code){
        $url=$url=$this->findApiUrl().$this->_url."claim-dropdown-list?org_code=".$org_code;
        return $this->findCurl()->get($url);
    }
    /**
     * @param string $customerId
     * @param string $planId
     * @return string
     * @throws NotFoundHttpException
     * 新增拜访记录
     */
//    public function actionRecordAdd($customerId = '', $planId = '')
//    {
//        $url = $this->findApiUrl() . 'crm/crm-visit-record/add?visitPersonId=' . Yii::$app->user->identity->staff_id . '&customerId=' . $customerId . '&planId=' . $planId;
//        if (Yii::$app->request->isPost) {
//            $data = Yii::$app->request->post();
//            $data['CrmVisitRecord']['create_by'] = Yii::$app->user->identity->staff_id;
//            $data['CrmVisitRecord']['company_id'] = Yii::$app->user->identity->company_id;
//            $data['CrmVisitRecordChild']['create_by'] = Yii::$app->user->identity->staff_id;
//            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
//            $result = Json::decode($curl->post($url));
//            if ($result['status'] == 1) {
//                SystemLog::addLog('新增客户拜访记录' . $result['msg']);
//                return Json::encode(['msg' => '新增成功', 'flag' => 1, 'url' => Url::to(['/crm/crm-visit-record/view-record', 'childId' => $result['data']])]);
//            }
//            return Json::encode(['msg' => $result['msg'], 'flag' => 0]);
//        }
//        $data = Json::decode($this->findCurl()->get($url));
//        if (!empty($customerId) && !$data['customerInfo']) {
//            throw new NotFoundHttpException('页面未找到！');
//        }
//        return $this->render('/crm-visit-record/add', ['data' => $data, 'planId' => $planId]);
//    }

    /**
     * @param $id
     * @return mixed
     * 客户信息
     */
    private function getCust($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-visit-plan/cust?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * 登陆人信息
     * @return mixed
     */
    private function getUserInfo()
    {
        $url = $this->findApiUrl() . "/hr/staff/return-info?id=" . Yii::$app->user->identity->staff_id;
        $result['staff'] = Json::decode($this->findCurl()->get($url));
//        $result['supper']=User::isSupper(Yii::$app->user->identity->user_id);
        return $result;
    }

    /**
     * 获取客户信息
     */
    public function getCustomer($custId)
    {
        $url = $this->findApiUrl() . "/crm/crm-credit-apply/customer?id=" . $custId;
        $result = Json::decode($this->findCurl()->get($url));
//        $result['csarea']['csarea_id'] = $result['csarea_id'];
//        $result['cust_type']['bsp_id'] = $result['cust_type'];
//        $result['cust_level']['bsp_id'] = $result['cust_level'];
//        $result['cust_compvirtue']['bsp_id'] = $result['cust_compvirtue'];
//        $result['manager']['staff_name'] = $result['staff_name'];
//        $result['manager']['staff_mobile'] = $result['staff_mobile'];
//        $result['total_investment'] = unserialize($result['total_investment']);
//        $result['total_investment']['investment'] = $result['total_investment'][0];
//        $result['total_investment']['currency'] = $result['total_investment'][1];
//        $result['official_receipts'] = unserialize($result['official_receipts']);
//        $result['official_receipts']['receipts'] = $result['total_investment'][0];
//        $result['official_receipts']['currency'] = $result['total_investment'][1];
        return $result;
    }

    /**
     * 获取客户关联信息
     */
    public function getCustomerRelation($custId)
    {
        $url = $this->findApiUrl() . $this->_url."customer-relation?id=" . $custId;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * @param $id
     * @return string
     * 删除客户信息
     */
    public function actionDelete($id)
    {
        $isSuper = User::isSupper(Yii::$app->user->identity->user_id) == true ? '1' : '0';
//        $u = $this->getEmployee(Yii::$app->user->identity->staff_id);
        $login = Yii::$app->user->identity->staff_id;
//        dumpE($u);
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id.'&isSuper='.$isSuper.'&login='.$login;
        $result = Json::decode($this->findCurl()->delete($url));
//        dumpE($result);
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']['data']);
            return Json::encode(["msg" => $result['msg'], "flag" => 1]);
        } else {
            return Json::encode(["msg" => $result['msg'], "flag" => 0]);
        }

    }

    /**
     * @param $id
     * @return string
     * 激活客户信息
     */
    public function actionActivation($id)
    {
        $url = $this->findApiUrl() . $this->_url . "activation?id=" . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']['msg']);
            return Json::encode(["msg" => "激活成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "激活失敗", "flag" => 0]);
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
        $isSuper = User::isSupper(Yii::$app->user->identity->user_id);
        $manage = $this->getEmployee(Yii::$app->user->identity->staff_id);
        $loginId = Yii::$app->user->identity->staff_id;
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
//        dumpE($crmcertf['crtf_type']);
        $u = $this->getEmployee(Yii::$app->user->identity->staff_id);
        return $this->render('view', [
            'newnName1' => $newnName1,
            'newnName2' => $newnName2,
            'newnName3' => $newnName3,
            'model' => $model,
            'isSuper' => $isSuper == true ? 1 : 0,
            'manage' => $manage,
            'loginId' => $loginId,
            'crmcertf' => $crmcertf,
            'u' => $u
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
     * 选择客户经理人
     */
    public function actionSelectManage($id = null, $code = null)
    {
        $this->layout = '@app/views/layouts/ajax';
        $url = $this->findApiUrl() . $this->_url . "select-manage";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        return $this->render('_manage', [
            'queryParam' => $queryParam,
            'id' => $id,
            'code' => $code
        ]);
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
        $isSuper = User::isSupper(Yii::$app->user->identity->user_id);
        $e = $this->getEmployee(Yii::$app->user->identity->staff_id);
//dumpE($downList['manager']);
        if (($get['status'] == 10 && $get['ml']==0) || $get['status'] == 0) {
            return $this->render('person-inch', [
                'downList' => $downList,
                'status' => $get['status'],
                'id' => $get['id'],
                'isSuper' => $isSuper,
                'employee' => $e,
                'ml' => $get['ml']
            ]);
        } else if($get['status'] == 10 && $get['ml']==1){
            $model = $this->getPersonInchOne($get['staff_id'],$get['customers']);
            $sales = $this->getStaff($model['ccpich_personid2']);
            return $this->render('person-inch', [
                'employee' => $e,
                'downList' => $downList,
                'model' => $model,
                'status' => $get['status'],
                'isSuper' => $isSuper,
                'customers' => $get['customers'],
                'sales' => $sales,
                'ml' => $get['ml']
            ]);
        }
    }

    /*增加-修改认领信息*/
    public function actionUpdatePersonInch()
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['CrmCustPersoninch']['ccpich_date'] = date("Y-m-d H:i:s", time());
            $url = $this->findApiUrl() . $this->_url . "update-person-inch";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => $data['msg'], "flag" => 1]);
            } else {
                return Json::encode(['msg' => $data["msg"], "flag" => 0]);
            }
        }

    }

    /*取消认领信息*/
    public function actionCanclePersonInch()
    {
        $params = \Yii::$app->request->post();
        $url = $this->findApiUrl() . $this->_url . "cancle-person-inch";
        $result = Json::decode($this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params))->post($url));
        if ($result['status'] == 1) {
//            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => $result['msg'], "flag" => 1]);
        } else {
            return Json::encode(["msg" => $result['msg'], "flag" => 0]);
        }
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
            return Json::encode(["msg" => $result['msg'], "flag" => 1]);
        } else {
            return Json::encode(["msg" => $result['msg'], "flag" => 0]);
        }
    }

    //抛掷公海
    public function actionThrowSea($str)
    {
        $url = $this->findApiUrl() . $this->_url . "throw-sea?str=" . $str;
        $result = Json::decode($this->findCurl()->get($url));
        if ($result['status'] == 1) {
            return Json::encode(["msg" => $result['msg'], "flag" => 1]);
        } else {
            return Json::encode(["msg" => $result['msg'], "flag" => 0]);
        }
    }

    /*认领信息客户经理人*/
    public function actionGetManagerStaffInfo($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-manager-staff-info?id=" . $id;
        $result = $this->findCurl()->get($url);
        return $result;
    }

    /*认领信息 销售人员*/
    public function actionGetSaleStaffInfo($leaderId)
    {
        $url = $this->findApiUrl() . $this->_url . "get-sale-staff-info?leaderId=" . $leaderId;
        $result = $this->findCurl()->get($url);
        return $result;
    }

    /*所有销售人员*/
    public function actionGetAllSales()
    {
        $url = $this->findApiUrl() . $this->_url . 'get-all-sales';
        return $this->findCurl()->get($url);
    }

    /*地区所属军区*/
    public function actionGetDistrictSalearea($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-district-salearea?id=" . $id;
        return $this->findCurl()->get($url);
    }

    /*获取所在地区一级地址*/
    public function getDistrict()
    {
        $url = $this->findApiUrl() . $this->_url . "district";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*根据地址五级获取全部信息*/
    public function getAllDistrict($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-all-district?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * 导出会员模板
     */
    public function actionDownTemplate()
    {
        $objPHPExcel = new \PHPExcel();
        $field = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R'];
        foreach ($field as $key => $value) {
            //宽度
            $objPHPExcel->getActiveSheet()->getColumnDimension($value)->setWidth(15);
            //标题垂直居中
            $objPHPExcel->getActiveSheet()->getStyle($value)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($value)->getFont()->setName('黑体');
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '客户名称')
            ->setCellValue('C1', '客户简称')
            ->setCellValue('D1', '客户类型')
            ->setCellValue('E1', '客户等级')
            ->setCellValue('F1', '联系人')
            ->setCellValue('G1', '手机')
            ->setCellValue('H1', '客户经理人')
            ->setCellValue('I1', '邮箱')
            ->setCellValue('J1', '营销区域')
            ->setCellValue('K1', '所在区域')
            ->setCellValue('L1', '地址(区/县)')
            ->setCellValue('M1', '详细地址')
            ->setCellValue('N1', '客户来源')
            ->setCellValue('O1', '经营类型')
            ->setCellValue('P1', '交易货币')
            ->setCellValue('Q1', '行业类型')
            ->setCellValue('R1', '公司属性');
//        foreach ($data as $key => $val) {
//            $num = $key + 2;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', '1')
            ->setCellValue('B2', 'XXX')
            ->setCellValue('C2', 'XXX')
            ->setCellValue('D2', 'XXX')
            ->setCellValue('E2', 'A')
            ->setCellValue('F2', 'XXX')
            ->setCellValue('G2', '13699999999')
            ->setCellValue('H2', 'XXX')
            ->setCellValue('I2', 'XXX@XX.COM')
            ->setCellValue('J2', '华北军区')
            ->setCellValue('K2', '北京')
            ->setCellValue('L2', '大兴区')
            ->setCellValue('M2', 'XXX')
            ->setCellValue('N2', '自动化论坛')
            ->setCellValue('O2', '国际货运代理')
            ->setCellValue('P2', 'RMB')
            ->setCellValue('Q2', '电子信息')
            ->setCellValue('R2', '国有企业');
//        }
        $fileName = "sale_customer.xlsx";
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

    /*客户类型等下拉菜单*/
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list?userId=".Yii::$app->user->identity->user_id;;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*获取人员信息*/
    public function getStaff($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-staff?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*获取认领信息*/
    public function getPersonInchOne($id,$cust_id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-person-inch-one?id=" . $id .'&cust_id='.$cust_id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function getSales($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-sales?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function getEmployee($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-employee?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function actionGetCustOne($id)
    {
        $data = $this->getModel($id);
        return Json::encode($data);
    }

    public function getIsSuper($id)
    {
        $url = $this->findApiUrl() . $this->_url . "is-super?id=" . $id;
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
     * @param $id
     * @return string
     * 更新客户及新增编码申请
     */
    public function actionCustomerInfo($id, $status = null)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $post = Yii::$app->request->post();
            $custId = $post['CrmCustomerInfo']['cust_id'];
            $applyInfo = $this->getApply($custId);;

            $licnameA = date('Y-m-d H:i:s');//获取当前时间
            $licnameA = str_replace(':', '', $licnameA);
            $licnameA = str_replace(' ', '', $licnameA);
            $licnameB = rand(0, 999);//获取0-999的随机数
            $licnameD = rand(1000, 1999);//获取1000-1999的随机数
            $licnameE = rand(2000, 2999);//获取2000-2999的随机数
            $licnameC = pathinfo($postData['CrmC']['o_license'], PATHINFO_EXTENSION);
            $licnameF = pathinfo($postData['CrmC']['o_reg'], PATHINFO_EXTENSION);
            $licnameG = pathinfo($postData['CrmC']['o_cerft'], PATHINFO_EXTENSION);
            $remotefilelic = $licnameA . $licnameB . '.' . $licnameC;//公司营业执照证和三证合一新文件名
            $remotefiletax = $licnameA . $licnameD . '.' . $licnameF;//税务登记证新文件名
            $remotefileorg = $licnameA . $licnameE . '.' . $licnameG;//一般纳税人资格证新文件名
            $postData['CrmC']['bs_license'] = $remotefilelic;//公司营业执照证和三证合一新文件名
            $postData['CrmC']['tx_reg'] = $remotefiletax;//税务登记证新文件名
            $postData['CrmC']['qlf_certf'] = $remotefileorg;//一般纳税人资格证新文件名

            if (empty($applyInfo)) {
                $postData['CrmCustomerInfo']['update_by'] = Yii::$app->user->identity->staff_id;

//                $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
//                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
//                $data = Json::decode($curl->put($url));
                //上传文件
                if (is_uploaded_file($_FILES['upfiles-lic']['tmp_name']) ||
                    is_uploaded_file($_FILES['upfiles-tax']['tmp_name']) ||
                    is_uploaded_file($_FILES['upfiles-org']['tmp_name'])
                ) {
                    $ftp_server = Yii::$app->ftpPath['ftpIP'];
                    $port = Yii::$app->ftpPath['port'];
                    if($port=="" ||$port==null)
                    {
                        $port='0';
                    }
                    $ftp_user_name = Yii::$app->ftpPath['ftpUser'];
                    $ftp_user_pass = Yii::$app->ftpPath['ftpPwd'];
                    $father = Yii::$app->ftpPath['CMP']['father'];
                    $pathlcn = Yii::$app->ftpPath['CMP']['BsLcn'];
                    $pathreg = Yii::$app->ftpPath['CMP']['TaxReg'];
                    $pathqlf = Yii::$app->ftpPath['CMP']['TaxQlf'];
                    $years = substr(date('Y'), -2);//当前年份的后两位
                    $month = date('m');
                    $day = date('d');
                    $uploadaddress = $years . $month . $day;
                    //$upaddress=trim($_POST['gonghao']);
                    $filelic = $_FILES['upfiles-lic']['tmp_name'];
                    $filetax = $_FILES['upfiles-tax']['tmp_name'];
                    $fileorg = $_FILES['upfiles-org']['tmp_name'];
                    $conn_id = ftp_connect($ftp_server,$port) or die("Couldn't connect to $ftp_server");
                    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
                    if ($login_result) {
                        $fa = ftp_nlist($conn_id, $father);
                        $reg = ftp_nlist($conn_id, $pathreg);
                        $qlf = ftp_nlist($conn_id, $pathqlf);
                        if ($fa != null)//有bslcns、 txrg  、txqlf其中一个文件夹
                        {
                            if ($postData['CrmC']['crtf_type'] == 0) {
                                //上传公司营业执照证
                                $i = 0;
                                $k = 0;
                                foreach ($fa as $lc) {
                                    $pathl = $father . $pathlcn;//公司营业执照证书的二级目录
                                    $pathr = $father . $pathreg;//税务登记证书的二级目录
                                    $pathq = $father . $pathqlf;//一般纳税人资格证书的二级目录
//                                dumpE($lc.$pathr);
                                    if ($lc == $pathl) //判断bslcns文件夹是否存在
                                    {
                                        $lcnchild = ftp_nlist($conn_id, $pathl);
                                        if ($lcnchild != null) //有时间文件夹
                                        {
                                            foreach ($lcnchild as $ch) {
                                                if ($ch == $pathl . '/' . $uploadaddress) {
                                                    $remote_filelic = $ch . '/' . $remotefilelic;
                                                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        echo "失败";
                                                    }
                                                } else {
                                                    $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                                    $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        echo "失败";
                                                    }
                                                }
                                            }
                                        } else {//没有时间文件夹
                                            $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                            $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                            if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                $i++;
                                            } else {
                                                $k++;
                                                echo "失败";
                                            }
                                        }
                                    } else //
                                    {
                                        $bb = @ftp_mkdir($conn_id, $pathl);//创建公司营业执照证书的二级目录
                                        $cc = @ftp_chdir($conn_id, $pathl);//选择公司营业执照证书的二级目录
                                        $dd = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                        if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                            $i++;
                                        } else {
                                            $k++;
                                            echo "失败";
                                        }
                                    }
                                    if ($lc == $pathr) //判断txrg文件夹是否存在
                                    {
                                        $lcnchild = ftp_nlist($conn_id, $pathr);
                                        if ($lcnchild != null) //有时间文件夹
                                        {
                                            foreach ($lcnchild as $ch) {
                                                if ($ch == $pathr . '/' . $uploadaddress) {
                                                    $remote_filetax = $ch . '/' . $remotefiletax;
                                                    if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        echo "失败";
                                                    }
                                                } else {
                                                    $bb = @ftp_mkdir($conn_id, $pathr . '/' . $uploadaddress);
                                                    $remote_filetax = $pathr . '/' . $uploadaddress . '/' . $remotefiletax;
                                                    if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        echo "失败";
                                                    }
                                                }
                                            }
                                        } else {//没有时间文件夹
                                            $bb = @ftp_mkdir($conn_id, $pathr . '/' . $uploadaddress);
                                            $remote_filetax = $pathr . '/' . $uploadaddress . '/' . $remotefiletax;
                                            if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                $i++;
                                            } else {
                                                $k++;
                                                echo "失败";
                                            }
                                        }
                                    } else //
                                    {
                                        $bb = @ftp_mkdir($conn_id, $pathr);//创建税务登记的二级目录
                                        $cc1 = @ftp_chdir($conn_id, $pathr);//选择税务登记的二级目录
                                        $dd1 = @ftp_mkdir($conn_id, $pathr . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_filetax = $pathr . '/' . $uploadaddress . '/' . $remotefiletax;
                                        if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                            $i++;
                                        } else {
                                            $k++;
                                            echo "失败";
                                        }
                                    }
                                    if ($lc == $pathq) //判断txqlf文件夹是否存在
                                    {
                                        $lcnchild = ftp_nlist($conn_id, $pathq);
                                        if ($lcnchild != null) //有时间文件夹
                                        {
                                            foreach ($lcnchild as $ch) {
                                                if ($ch == $pathq . '/' . $uploadaddress) {
                                                    $remote_fileorg = $ch . '/' . $remotefileorg;
                                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        echo "失败";
                                                    }
                                                } else {
                                                    $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                                    $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        echo "失败";
                                                    }
                                                }
                                            }
                                        } else {//没有时间文件夹
                                            $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                            $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                            if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                $i++;
                                            } else {
                                                $k++;
                                                echo "失败";
                                            }
                                        }
                                    } else //
                                    {
                                        $bb = @ftp_mkdir($conn_id, $pathq);//创建一般纳税人资格证的二级目录
                                        $cc2 = @ftp_chdir($conn_id, $pathq);//选择一般纳税人资格证的二级目录
                                        $dd2 = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                        if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                            $i++;
                                        } else {
                                            $k++;
                                            echo "失败";
                                        }
                                    }
                                }
                                if ($i >= 3 && $k == 0) {
                                    $url = $this->findApiUrl() . "/crm/crm-customer-apply/update?id=" . $id;
                                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                    $data = Json::decode($curl->put($url));
                                    if ($data['status'] == 1) {
                                        SystemLog::addLog($data['data']['msg']);
                                        return Json::encode(['msg' => "申请成功", "flag" => 1, "url" => Url::to(['/crm/crm-customer-apply/view', 'id' => $id])]);
                                    } else {
                                        return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                    }
                                }
//                                dumpE('i' . $i);
                            } else {
                                //上传公司营业执照证
                                $m = 0;
                                $n = 0;
                                foreach ($fa as $lc) {
                                    $pathl = $father . $pathlcn;//公司营业执照证书的二级目录
                                    $pathq = $father . $pathqlf;//一般纳税人资格证书的二级目录
                                    if ($lc == $pathl) //判断bslcns文件夹是否存在
                                    {
                                        $lcnchild = ftp_nlist($conn_id, $pathl);
                                        if ($lcnchild != null) //有时间文件夹
                                        {
                                            foreach ($lcnchild as $ch) {
                                                if ($ch == $pathl . '/' . $uploadaddress) {
                                                    $remote_filelic = $ch . '/' . $remotefilelic;
                                                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                        $m++;
                                                    } else {
                                                        $n++;
                                                        echo "失败";
                                                    }
                                                } else {
                                                    $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                                    $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                        $m++;
                                                    } else {
                                                        $n++;
                                                        echo "失败";
                                                    }
                                                }
                                            }
                                        } else {//没有时间文件夹
                                            $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                            $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                            if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                $m++;
                                            } else {
                                                $n++;
                                                echo "失败";
                                            }
                                        }
                                    } else //
                                    {
                                        $bb = @ftp_mkdir($conn_id, $pathl);//创建公司营业执照证书的二级目录
                                        $cc = @ftp_chdir($conn_id, $pathl);//选择公司营业执照证书的二级目录
                                        $dd = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                        if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                            $m++;
                                        } else {
                                            $n++;
                                            echo "失败";
                                        }
                                    }
                                    if ($lc == $pathq) //判断txqlf文件夹是否存在
                                    {
                                        $lcnchild = ftp_nlist($conn_id, $pathq);
                                        if ($lcnchild != null) //有时间文件夹
                                        {
                                            foreach ($lcnchild as $ch) {
                                                if ($ch == $pathq . '/' . $uploadaddress) {
                                                    $remote_fileorg = $ch . '/' . $remotefileorg;
                                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                        $m++;
                                                    } else {
                                                        $n++;
                                                        echo "失败";
                                                    }
                                                } else {
                                                    $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                                    $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                        $m++;
                                                    } else {
                                                        $n++;
                                                        echo "失败";
                                                    }
                                                }
                                            }
                                        } else {//没有时间文件夹
                                            $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                            $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                            if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                $m++;
                                            } else {
                                                $n++;
                                                echo "失败";
                                            }
                                        }
                                    } else //
                                    {
                                        $bb = @ftp_mkdir($conn_id, $pathq);//创建公司营业执照证书的二级目录
                                        $cc2 = @ftp_chdir($conn_id, $pathq);//选择公司营业执照证书的二级目录
                                        $dd2 = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                        if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                            $m++;
                                        } else {
                                            $n++;
                                            echo "失败";
                                        }
                                    }
                                }
                                if ($m >= 2 && $n == 0) {
                                    $url = $this->findApiUrl() . "/crm/crm-customer-apply/update?id=" . $id;
                                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                    $data = Json::decode($curl->put($url));
                                    if ($data['status'] == 1) {
                                        SystemLog::addLog($data['data']['msg']);
                                        return Json::encode(['msg' => "申请成功", "flag" => 1, "url" => Url::to(['/crm/crm-customer-apply/view', 'id' => $id])]);
                                    } else {
                                        return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                    }
                                }
//                                dumpE('m' . $m);
                            }

                        } else //没有bslcns、txrg、txqlf文件夹
                        {
                            if ($postData['CrmC']['crtf_type'] == 0) {
                                $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                                $cc = @ftp_chdir($conn_id, $father);//选择cmp文件夹
                                $bb1 = @ftp_mkdir($conn_id, $pathlcn);//创建bslcns文件夹
                                $bb2 = @ftp_mkdir($conn_id, $pathreg);//创建txrg文件夹
                                $bb3 = @ftp_mkdir($conn_id, $pathqlf);//创建txqlf文件夹
                                $cc1 = @ftp_chdir($conn_id, $bb1);//选择bslcns文件夹
                                $dd = @ftp_mkdir($conn_id, $bb1 . '/' . $uploadaddress);//创建日期文件夹
                                $cc2 = @ftp_chdir($conn_id, $bb2);//选择bslcns文件夹
                                $dd1 = @ftp_mkdir($conn_id, $bb2 . '/' . $uploadaddress);//创建日期文件夹
                                $cc3 = @ftp_chdir($conn_id, $bb3);//选择bslcns文件夹
                                $dd2 = @ftp_mkdir($conn_id, $bb3 . '/' . $uploadaddress);//创建日期文件夹
                                $remote_filelics = $dd . '/' . $remotefilelic;
                                $remote_filetaxs = $dd1 . '/' . $remotefiletax;
                                $remote_fileorgs = $dd2 . '/' . $remotefileorg;
                                if (ftp_put($conn_id, $remote_filelics, $filelic, FTP_BINARY)
                                    && ftp_put($conn_id, $remote_fileorgs, $fileorg, FTP_BINARY)
                                    && ftp_put($conn_id, $remote_filetaxs, $filetax, FTP_BINARY)
                                ) {
                                    $url = $this->findApiUrl() . "/crm/crm-customer-apply/update?id=" . $id;
                                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                    $data = Json::decode($curl->put($url));
                                    if ($data['status'] == 1) {
                                        SystemLog::addLog($data['data']['msg']);
                                        return Json::encode(['msg' => "申请成功", "flag" => 1, "url" => Url::to(['/crm/crm-customer-apply/view', 'id' => $id])]);
                                    } else {
                                        return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                    }
                                } else {
                                    echo "失败";
                                }
                            } else {
                                $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                                $cc = @ftp_chdir($conn_id, $father);//选择cmp文件夹
                                $bb1 = @ftp_mkdir($conn_id, $pathlcn);//创建bslcns文件夹
                                $bb3 = @ftp_mkdir($conn_id, $pathqlf);//创建txqlf文件夹
                                $cc1 = @ftp_chdir($conn_id, $bb1);//选择bslcns文件夹
                                $dd = @ftp_mkdir($conn_id, $bb1 . '/' . $uploadaddress);//创建日期文件夹
                                $cc3 = @ftp_chdir($conn_id, $bb3);//选择bslcns文件夹
                                $dd3 = @ftp_mkdir($conn_id, $bb3 . '/' . $uploadaddress);//创建日期文件夹
                                $remote_filelics1 = $dd . '/' . $remotefilelic;
                                $remote_fileorgs1 = $dd3 . '/' . $remotefileorg;
                                if (ftp_put($conn_id, $remote_filelics1, $filelic, FTP_BINARY)
                                    && ftp_put($conn_id, $remote_fileorgs1, $fileorg, FTP_BINARY)
                                ) {
                                    $url = $this->findApiUrl() . "/crm/crm-customer-apply/update?id=" . $id;
                                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                    $data = Json::decode($curl->put($url));
                                    if ($data['status'] == 1) {
                                        SystemLog::addLog($data['data']['msg']);
                                        return Json::encode(['msg' => "申请成功", "flag" => 1, "url" => Url::to(['/crm/crm-customer-apply/view', 'id' => $id])]);
                                    } else {
                                        return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                    }
                                } else {
                                    echo "失败";
                                }
                            }
                        }
                    } else {
                        echo '请先登录ftp';
                    }
                    ftp_close($conn_id);
                }
//                if ($data['status'] == 1) {
//                    SystemLog::addLog($data['data']['msg']);
//                    return Json::encode(['msg' => "申请成功", "flag" => 1, "url" => Url::to(['/crm/crm-customer-apply/view','id'=>$id])]);
//                } else {
//                    return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
//                }
            } else {
                return Json::encode(['msg' => "请勿重复保存", "flag" => 0]);
            }

        } else {
            $url = $this->findApiUrl() . "hr/staff/view?id=" . Yii::$app->user->identity->staff_id;
            $data = Json::decode($this->findCurl()->get($url));
            $staffname = $data['staff_name'];
            $url = $this->findApiUrl() . "/crm/crm-customer-apply/is-supper?id=" . Yii::$app->user->identity->getId();
            $result = Json::decode($this->findCurl()->get($url));
            $model = $this->getModel($id);//客户信息
            $caModel = $this->actionApplyInfo($id);//编码申请信息
            $districtId2 = $model['cust_district_2'];
            $districtId3 = $model['cust_district_3'];
            $districtAll2 = $this->getAllDistrict($districtId2);
            $districtAll3 = $this->getAllDistrict($districtId3);
            $downList = $this->getDownList();
            //dumpE($downList);
            $district = $this->getDistrict();
            $salearea = $this->getSalearea();
            $country = $this->getCountry();
            $crmcertf = $this->getCrmCertf($id);
//           dumpE($crmcertf);
            $industryType = $this->getIndustryType();
//            $keyValue = $key;//页面开关
            return $this->render("/crm-customer-apply/update", [
                'status' => $status,
                'result' => $result,
                'staffname' => $staffname,
                'model' => $model,
                'caModel' => $caModel,
                'downList' => $downList,
                'district' => $district,
                'salearea' => $salearea,
                'country' => $country,
                'industryType' => $industryType,
                'districtAll2' => $districtAll2,
                'districtAll3' => $districtAll3,
                'crmcertf' => $crmcertf
            ]);
        }
    }

    public function getApply($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-apply/apply?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 申请详情
     */
    public function actionApplyInfo($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-apply/apply?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    /*营销范围*/
    public function getSalearea()
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-apply/salearea";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*国家*/
    public function getCountry()
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-apply/get-country";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*行业类别*/
    public function getIndustryType()
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-apply/industry-type";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * @param $id
     * @param $attr
     * @param $val
     * @return mixed
     * 验证客户名称唯一性
     */
    public function actionValidateCode($id, $attr, $val)
    {
        $val = urlencode($val);
        $url = $this->findApiUrl() . $this->module->id . "/" . $this->id . "/" . "validate-code";
        $url = $url . "?id={$id}&attr={$attr}&val={$val}";
        return $this->findCurl()->get($url);
    }

}
