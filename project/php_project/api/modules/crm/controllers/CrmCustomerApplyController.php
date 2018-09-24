<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\models\User;
use app\modules\common\models\BsCategory;
use app\modules\crm\models\CrmC;
use app\modules\crm\models\CrmCustomerApply;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\crm\models\search\CrmCustomerApplySearch;
use app\modules\system\models\show\UserShow;
use Yii;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsIndustrytype;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmDistrictSalearea;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrStaff;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use app\modules\crm\models\search\CrmCustomerInfoSearch;
use app\modules\crm\models\CrmCustCustomer;
use app\modules\crm\models\CrmCustDevice;
use app\modules\crm\models\CrmCustomerPersion;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmCustProduct;
use app\modules\crm\models\show\CrmCustPersoninchShow;
use app\modules\crm\models\show\CrmEmployeeShow;
use yii\widgets\ActiveForm;
use app\modules\system\models\Verifyrecord;

class CrmCustomerApplyController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmCustomerApply';

    public function actionIndex($companyId, $staffName)
    {
        $searchModel = new CrmCustomerApplySearch();
        $postdata = Yii::$app->request->queryParams;
        if (isset($postdata['CrmCustomerApplySearch']['cust_filernumber'])) {
            $postdata['CrmCustomerApplySearch']['cust_filernumber'] = trim($postdata['CrmCustomerApplySearch']['cust_filernumber']);
        }

        $dataProvider = $searchModel->search($postdata, $companyId, $staffName);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @return array
     * 保存及申請操作
     */
    public function actionCreateCustomer($id)
    {
        $crmCustomerInfo = $this->getModel($id);
//        $crmCustomerInfo = new CrmCustomerInfo();
        $customerApply = new CrmCustomerApply();
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            /*客户信息*/
            $crmCustomerInfo->load($post);
            if (!empty($post['CrmCustomerInfo']['cust_regname'])) {
                $custRegname = $post['CrmCustomerInfo']['cust_regname'];
            }
            if (!empty($post['CrmCustomerInfo']['cust_regnumber'])) {
                $custRegnumber = $post['CrmCustomerInfo']['cust_regnumber'];
            }
            $managerId = $post['CrmCustomerInfo']['cust_manager'];
            if (!empty($post['CrmCustomerInfo']['cust_ismember'])) {
                $ismember = $post['CrmCustomerInfo']['cust_ismember'];
            }
            if (empty($managerId)) {
                $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_NO;
            } else {
                $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_YES;
            };
            if (!empty($custRegname)) {
                $crmCustomerInfo->cust_regname = serialize($custRegname);
            }
            if (!empty($custRegnumber)) {
                $crmCustomerInfo->cust_regnumber = serialize($custRegnumber);
            }
            if (!empty($post['CrmC']['crtf_type'])) {
                $crmCustomerInfo->three_to_one = $post['CrmC']['crtf_type'];//是否三证合一 0否，1是
            }
            if (!$crmCustomerInfo->save()) {
//                throw new \Exception('aaaaaaaaa');
                throw new \Exception(json_encode($crmCustomerInfo->getErrors(), JSON_UNESCAPED_UNICODE));//,"新增客户信息失败"
            }
            $result = new CrmCustomerStatus();
            $models = $result->findone($id);
            $custId = $crmCustomerInfo->cust_id;
            $customer = $crmCustomerInfo->cust_sname;
            $createBy = $crmCustomerInfo->create_by;
            /*状态*/
            $models->customer_id = $custId;
            $models->sale_status = CrmCustomerStatus::STATUS_DEFAULT;
            if (!empty($ismember) && $ismember == '1') {
                $models->member_status = CrmCustomerStatus::STATUS_DEFAULT;
            } else {
                $models->member_status = CrmCustomerStatus::STATUS_DEL;
            }
            if (!$models->save()) {
                throw new \Exception("新增失败");
            }
            /*新增客户编码申请*/
            //$customerApply = isset($post['CrmCustomerApply']) ? $post['CrmCustomerApply'] : false;
            if ($custId != null) {
                $customerApply->load($post);
                $customerApply->cust_id = $custId;
                $customerApply->applyperson = $post['CrmCustomerApply']['applyperson'];
                $customerApply->applydep = $post['CrmCustomerApply']['applydep'];
                $customerApply->company_id = $post['CrmCustomerApply']['company_id'];
                $customerApply->is_delete = $post['CrmCustomerApply']['is_delete'];
                $customerApply->status = CrmCustomerApply::STATUS_WAIT;
                if (!$customerApply->save()) {
                    throw new \Exception("新增客户编码申请失败");
                }
            }
            /*认领信息*/
            if ($managerId != null) {
                $personinch = new CrmCustPersoninch();
                $personinch->cust_id = $custId;
                $personinch->ccpich_personid = $managerId;
                $personinch->ccpich_date = date('Y-m-d', time());
                $personinch->ccpich_status = CrmCustPersoninch::STATUS_DEFAULT;
                $personinch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;
                if (!$personinch->save()) {
                    throw new \Exception("新增认领信息失败");
                }
            }
            /*主要联系人*/
            $conetionArr = isset($post['CrmCustomerPersion']) ? $post['CrmCustomerPersion'] : false;
            if ($conetionArr) {
                foreach ($conetionArr as $value) {
                    if ($value['ccper_name'] != null) {
                        $crmCustPersion = new CrmCustomerPersion();
                        $products['CrmCustomerPersion'] = $value;
                        $crmCustPersion->load($products);
                        $crmCustPersion->cust_id = $custId;
                        if (!$crmCustPersion->save()) {
                            throw  new \Exception("新增联系人失败");
                        };
                    }
                }
            }
//            /*设备信息*/
            $devideArr = isset($post['CrmCustDevice']) ? $post['CrmCustDevice'] : false;
            if ($devideArr) {
                foreach ($devideArr as $value) {
                    if ($value['type'] != null) {
                        $crmCustDevice = new CrmCustDevice();
                        $products['CrmCustDevice'] = $value;
                        $crmCustDevice->load($products);
                        $crmCustDevice->cust_id = $custId;
                        $crmCustDevice->create_by = $createBy;
                        if (!$crmCustDevice->save()) {
                            throw  new \Exception("新增设备失败");
                        };
                    }
                }
            }
            /*认证信息*/
            $certfArr = isset($post['CrmC']) ? $post['CrmC'] : false;
            if ($certfArr) {
                $crmC = new CrmC();
                $crmC->load($post);
                $crmC->cust_id = $custId;
                $crmC->bs_license = $post['CrmC']['bs_license'];
                $crmC->o_license = $post['CrmC']['o_license'];
                if ($post['CrmC']['crtf_type'] == 1)//当证件类型为新版三合一证时税务登记证为空
                {
                    $crmC->o_reg = "";
                    $crmC->tx_reg = "";
                } else {
                    $crmC->tx_reg = $post['CrmC']['tx_reg'];
                    $crmC->o_reg = $post['CrmC']['o_reg'];
                }
                if (!empty($post['CrmC']['o_cerft']))//一般纳税人资格证不为空
                {
                    $crmC->qlf_certf = $post['CrmC']['qlf_certf'];
                    $crmC->o_cerft = $post['CrmC']['o_cerft'];
                } else {
                    $crmC->qlf_certf = "";
                    $crmC->o_cerft = "";
                }
                if (!$crmC->save()) {
                    throw  new \Exception("新增认证信息失败");
                }
            }
//            /*产品信息*/
            $productArr = isset($post['CrmCustProduct']) ? $post['CrmCustProduct'] : false;
            if ($productArr) {
                foreach ($productArr as $value) {
                    if ($value['ccp_sname']) {
                        $crmCustProduct = new CrmCustProduct();
                        $products['CrmCustProduct'] = $value;
                        $crmCustProduct->load($products);
                        $crmCustProduct->cust_id = $custId;
                        $crmCustProduct->create_by = $createBy;
                        if (!$crmCustProduct->save()) {
                            throw  new \Exception("新增产品失败");
                        };
                    }
                }
            }
//            /*主要客户信息*/
            $customerArr = isset($post['CrmCustCustomer']) ? $post['CrmCustCustomer'] : false;
            if ($customerArr) {
                foreach ($customerArr as $value) {
                    if ($value['cc_customer_name'] != null) {
                        $crmCustCustomer = new CrmCustCustomer();
                        $products['CrmCustCustomer'] = $value;
                        $crmCustCustomer->load($products);
                        $crmCustCustomer->cust_id = $custId;
                        $crmCustCustomer->create_by = $createBy;
                        if (!$crmCustCustomer->save()) {
                            throw  new \Exception("新增主要客户失败");
                        };
                    }
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $msg = array('id' => $custId, 'msg' => '新增销售客户"' . $customer . '"');
        return $this->success('jjjj', $msg);
    }

    /**
     * @param $id
     * @return array
     *
     */
    public function actionUpdate($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $crmCustomerInfo = CrmCustomerInfo::findOne($id);
        $post = Yii::$app->request->post();
        try {
            /*客户信息*/
            $crmCustomerInfo->load(Yii::$app->request->post());
            $custRegname = $post['CrmCustomerInfo']['cust_regname'];
            $custRegnumber = $post['CrmCustomerInfo']['cust_regnumber'];
            $post['CrmCustomerInfo']['cust_regfunds'] = (int)$post['CrmCustomerInfo']['cust_regfunds'];
            $managerId = $post['CrmCustomerInfo']['cust_manager'];
            $ismember = $post['CrmCustomerInfo']['cust_ismember'];
            if (empty($managerId)) {
                $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_NO;
            } else {
                $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_YES;
            };
            $crmCustomerInfo->cust_regname = serialize($custRegname);
            $crmCustomerInfo->cust_regnumber = serialize($custRegnumber);
            $crmCustomerInfo->three_to_one = $post['CrmC']['crtf_type'];//是否三证合一 0否，1是
            if (!$crmCustomerInfo->save()) {
                throw new \Exception("修改客户信息失败" . json_encode($crmCustomerInfo->getErrors()));
            }
            $updateBy = $crmCustomerInfo->update_by;
            /*新增客户编码申请*/
            //$customerApply = isset($post['CrmCustomerApply']) ? $post['CrmCustomerApply'] : false;
            $customerApply = CrmCustomerApply::find()->where(['cust_id' => $id])->one();
            if ($customerApply == null) {
//                $customerApply->load($post);
                $customerApply = new CrmCustomerApply();
                $customerApply->cust_id = $id;
                $customerApply->applyperson = $post['CrmCustomerApply']['applyperson'];
                $customerApply->applydep = $post['CrmCustomerApply']['applydep'];
                $customerApply->company_id = $post['CrmCustomerApply']['company_id'];
                $customerApply->is_delete = $post['CrmCustomerApply']['is_delete'];
//                $customerApply->status = $post['CrmCustomerApply']['status'];
                if (!$customerApply->save()) {
                    throw new \Exception("新增客户编码申请失败" . json_encode($crmCustomerInfo->getErrors()));
                }
            } else {
//                $customerApply->capply_id =
                $customerApply->applyperson = $post['CrmCustomerApply']['applyperson'];
                $customerApply->applydep = $post['CrmCustomerApply']['applydep'];
                $customerApply->company_id = $post['CrmCustomerApply']['company_id'];
                $customerApply->is_delete = $post['CrmCustomerApply']['is_delete'];
                $customerApply->status = $post['CrmCustomerApply']['status'];
                if (!$customerApply->save()) {
                    throw new \Exception('修改客户编码申请失败' . json_encode($crmCustomerInfo->getErrors()));
                }
            }

            /*认证信息*/
//            $certfArr = isset($post['CrmC']) ? $post['CrmC'] : false;
            $crmC = CrmC::find()->where(['cust_id' => $id])->one();
            if ($crmC == null) {
                $crmC = new CrmC();
                $crmC->load($post);
                $crmC->cust_id = $id;
                $crmC->bs_license = $post['CrmC']['bs_license'];
                $crmC->o_license = $post['CrmC']['o_license'];
                if ($post['CrmC']['crtf_type'] == 1)//当证件类型为新版三合一证时税务登记证为空
                {
                    $crmC->o_reg = "";
                    $crmC->tx_reg = "";
                } else {
                    $crmC->tx_reg = $post['CrmC']['tx_reg'];
                    $crmC->o_reg = $post['CrmC']['o_reg'];
                }
                if (!empty($post['CrmC']['o_cerft']))//一般纳税人资格证不为空
                {
                    $crmC->qlf_certf = $post['CrmC']['qlf_certf'];
                    $crmC->o_cerft = $post['CrmC']['o_cerft'];
                } else {
                    $crmC->qlf_certf = "";
                    $crmC->o_cerft = "";
                }
                if (!$crmC->save()) {
                    throw  new \Exception("新增认证信息失败" . json_encode($crmCustomerInfo->getErrors()));
                }
            } else {
                $crmC->load($post);
//                $crmC->cust_id = $id;
                $crmC->bs_license = $post['CrmC']['bs_license'];
                $crmC->o_license = $post['CrmC']['o_license'];
                if ($post['CrmC']['crtf_type'] == 1)//当证件类型为新版三合一证时税务登记证为空
                {
                    $crmC->o_reg = "";
                    $crmC->tx_reg = "";
                } else {
                    $crmC->tx_reg = $post['CrmC']['tx_reg'];
                    $crmC->o_reg = $post['CrmC']['o_reg'];
                }
                if (!empty($post['CrmC']['o_cerft']))//一般纳税人资格证不为空
                {
                    $crmC->qlf_certf = $post['CrmC']['qlf_certf'];
                    $crmC->o_cerft = $post['CrmC']['o_cerft'];
                } else {
                    $crmC->qlf_certf = "";
                    $crmC->o_cerft = "";
                }
                if (!$crmC->save()) {
                    throw  new \Exception("新增认证信息失败" . json_encode($crmCustomerInfo->getErrors()));
                }
            }
            /*认领信息*/
            $status = CrmCustomerStatus::find()->where(['customer_id' => $id])->one();
            if ($ismember == '1') {
                $status->member_status = CrmCustomerStatus::STATUS_DEFAULT;
            } else {
                $status->member_status = CrmCustomerStatus::STATUS_DEL;
            }
            if (!$status->save()) {
                throw new \Exception("修改失败");
            }
            $personinch = CrmCustPersoninch::find()->where(['cust_id' => $id])->one();
            if ($personinch == null) {
                if ($managerId != null) {
                    $personinch = new CrmCustPersoninch();
                    $personinch->cust_id = $id;
                    $personinch->ccpich_personid = $managerId;
                    $personinch->ccpich_status = CrmCustPersoninch::STATUS_DEFAULT;
                    $personinch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;
                    if (!$personinch->save()) {
                        throw new \Exception("新增认领信息失败" . json_encode($crmCustomerInfo->getErrors()));
                    }
                }
            } else {
                $personinch->ccpich_personid = $managerId;
                if (!$personinch->save()) {
                    throw new \Exception("修改认领信息失败" . json_encode($crmCustomerInfo->getErrors()));
                }
            }
            /*主要联系人*/
            $countPerson = CrmCustomerPersion::find()->where(['cust_id' => $id])->count();
            if (CrmCustomerPersion::deleteAll(['cust_id' => $id]) < $countPerson) {
                throw  new \Exception("更新主要联系人失败" . json_encode($crmCustomerInfo->getErrors()));
            };
            $conetionArr = isset($post['CrmCustomerPersion']) ? $post['CrmCustomerPersion'] : false;
            if ($conetionArr) {
                foreach ($conetionArr as $value) {
                    if ($value['ccper_name'] != null) {
                        $crmCustPersion = new CrmCustomerPersion();
                        $products['CrmCustomerPersion'] = $value;
                        $crmCustPersion->load($products);
                        $crmCustPersion->cust_id = $id;
                        if (!$crmCustPersion->save()) {
                            throw  new \Exception("更新联系人失败" . json_encode($crmCustPersion->getErrors(),JSON_UNESCAPED_UNICODE));
                        };
                    }
                }
            }

//            /*设备信息*/
            $countDevice = CrmCustDevice::find()->where(['cust_id' => $id])->count();
            if (CrmCustDevice::deleteAll(['cust_id' => $id]) < $countDevice) {
                throw  new \Exception("更新设备失败" . json_encode($crmCustomerInfo->getErrors()));
            };
            $devideArr = isset($post['CrmCustDevice']) ? $post['CrmCustDevice'] : false;
            if ($devideArr) {
                foreach ($devideArr as $value) {
                    if ($value['type'] != null) {
                        $crmCustDevice = new CrmCustDevice();
                        $products['CrmCustDevice'] = $value;
                        $crmCustDevice->load($products);
                        $crmCustDevice->cust_id = $id;
                        $crmCustDevice->update_by = $updateBy;
                        if (!$crmCustDevice->save()) {
                            throw  new \Exception('更新设备失败'.json_encode($crmCustDevice->getErrors(),JSON_UNESCAPED_UNICODE));
                        };
                    }
                }
            }

//            /*产品信息*/
            $countProduct = CrmCustProduct::find()->where(['cust_id' => $id])->count();
            if (CrmCustProduct::deleteAll(['cust_id' => $id]) < $countProduct) {
                throw  new \Exception("更新产品失败" . json_encode($crmCustomerInfo->getErrors()));
            };
            $productArr = isset($post['CrmCustProduct']) ? $post['CrmCustProduct'] : false;
            if ($productArr) {
                foreach ($productArr as $value) {
                    if ($value['ccp_sname']) {
                        $crmCustProduct = new CrmCustProduct();
                        $products['CrmCustProduct'] = $value;
                        $crmCustProduct->load($products);
                        $crmCustProduct->cust_id = $id;
                        $crmCustProduct->update_by = $updateBy;
//                    return ActiveForm::validate($crmCustProduct);
                        if (!$crmCustProduct->save()) {
                            throw  new \Exception("更新产品失败" . json_encode($crmCustomerInfo->getErrors()));
                        };
                    }
                }
            }
//            /*主要客户信息*/
            $countCustomer = CrmCustCustomer::find()->where(['cust_id' => $id])->count();
            if (CrmCustCustomer::deleteAll(['cust_id' => $id]) < $countCustomer) {
                throw  new \Exception("更新客户失败" . json_encode($crmCustomerInfo->getErrors()));
            };
            $customerArr = isset($post['CrmCustCustomer']) ? $post['CrmCustCustomer'] : false;
            if ($customerArr) {
                foreach ($customerArr as $value) {
                    if ($value['cc_customer_name'] != null) {
                        $crmCustCustomer = new CrmCustCustomer();
                        $products['CrmCustCustomer'] = $value;
                        $crmCustCustomer->load($products);
                        $crmCustCustomer->cust_id = $id;
                        $crmCustCustomer->update_by = $updateBy;
                        if (!$crmCustCustomer->save()) {
                            throw  new \Exception("更新主要客户失败" . json_encode($crmCustomerInfo->getErrors()));
                        };
                    }
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        $msg = array('id' => $id, 'msg' => '修改销售客户"' . $crmCustomerInfo["cust_sname"] . '"');
        return $this->success('', $msg);
    }

    /*
     * 删除
     */
    public function actionDelete($id)
    {
        $model = $this->getApplyModel($id);
        $model->is_delete = CrmCustomerApply::DELETE;
        if ($result = $model->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * @return mixed
     * 導出excel
     */
    public function actionExcel()
    {
        $searchModel = new CrmCustomerApplySearch();
        $data = $searchModel->export(Yii::$app->request->queryParams)->all();
        return $data;
    }

    public function actionGetApply($id)
    {
        $applyModel = $this->getApplyModel($id);
        $cust_id = $applyModel['cust_id'];
        return $model = $this->actionModels($cust_id);
    }

    public function actionGetCrmCertf($id)
    {
        $applyModel = $this->getApplyModel($id);
        $cust_id = $applyModel['cust_id'];
        return $model = $result = CrmC::getCrmCertfOne($cust_id);
    }

    public function actionSelectComs()
    {
        $searchModel = new CrmCustomerInfoSearch();
        $dataProvider = $searchModel->searchManage(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /*下拉菜单*/
    public function actionDownList()
    {
//        $downList['customerType'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_TYPE);       //客户类型
//        $downList['customerClass'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_CLASS);     //客户类别
//        $downList['companyProperty']=BsPubdata::getList(BsPubdata::CRM_COMPANY_PROPERTY);  //公司属性
//        $downList['managementType'] = BsPubdata::getList(BsPubdata::CRM_MANAGEMENT_TYPE);  //经营类型
        $downList['customerType'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_TYPE);       //客户类型
        $downList['customerClass'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_CLASS);     //客户类别
        $downList['companyProperty'] = BsPubdata::getList(BsPubdata::CRM_COMPANY_PROPERTY);  //公司属性
        $downList['managementType'] = BsPubdata::getList(BsPubdata::CRM_MANAGEMENT_TYPE);  //经营类型
        $downList['custLevel'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_LEVEL);  //客户等级
        $downList['latentDemand'] = BsPubdata::getList(BsPubdata::CRM_LATENT_DEMAND);//潜在需求
        $downList['companyScale'] = BsPubdata::getList(BsPubdata::CRM_COMPANY_SCALE);//公司规模
        $downList['tradeCurrency'] = BsPubdata::getList(BsPubdata::SUPPLIER_TRADE_CURRENCY); //币别
        $downList['customerSource'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_SOURCE);//客户来源
        $downList['productType'] = BsCategory::getLevelOne();//需求类目
        $downList['salearea'] = CrmSalearea::getSalearea();//所在军区
        $downList['country'] = BsDistrict::getDisLeveOne();//所在国家
        $downList['industryType'] = BsIndustrytype::getIndustryType();//行业类别
        $downList['storeinfo'] = CrmStoresinfo::getStoreInfo();//销售点
        $downList['manager'] = CrmEmployee::getManagerInfo();//客户经理人
        $downList['allsales'] = CrmEmployee::getAllSales();//所有销售人员
        $downList['invoiceType'] = BsPubdata::getList(BsPubdata::CRM_INVOICE_TYPE);  //发票类型
        return $downList;
    }

    /*客户经理人*/
    public function actionGetVisitManager($code)
    {
        return HrStaff::getStaffByIdCode($code);
    }

    /*所在地区一级省份*/
    public function actionGetDistrict()
    {
        return BsDistrict::getDisProvince();
    }

    /*根据地址五级查出所有信息*/
    public function actionGetAllDistrict($id)
    {
        $disId = BsDistrict::find()->where(['district_id' => $id])->one();
        $name = $disId['district_name'];
        $dis1 = BsDistrict::find()->where(['district_id' => $disId['district_pid']])->one();
        $dis2 = BsDistrict::find()->where(['district_id' => $dis1['district_pid']])->one();
        $dis3 = BsDistrict::find()->where(['district_id' => $dis2['district_pid']])->one();
        $dis4 = BsDistrict::find()->where(['district_id' => $dis3['district_pid']])->one();
//        $disName = $dis4['district_name'].$dis3['district_name'].$dis2['district_name'].$dis1['district_name'].$disId['district_name'];
        $arr = [$dis3, $dis2, $dis1, $disId];
        return $arr;
    }

    /*检测厂商是否重复*/
    public function actionSelectSname($name)
    {
        $result = CrmCustomerInfo::find()->where(['cust_sname' => $name])->count();
        if ($result) {
            return "0";
        } else {
            return "1";
        }
    }

    public function actionGetCountry()
    {
        return BsDistrict::getDisLeveOne();
    }

    public function actionGetDistrictSalearea($id)
    {
        return CrmDistrictSalearea::getDisSalearea($id);
    }

    public function actionModels($id)
    {
        $result = CrmCustomerInfoShow::getCustomerInfoOne($id);
        return $result;
    }

    public function actionCrmCertf($id)
    {
        $result = CrmC::getCrmCertfOne($id);
        return $result;
    }

    public function actionSalearea()
    {
        return CrmSalearea::getSalearea();
    }

    public function actionIndustryType()
    {
        return BsIndustrytype::getIndustryType();
    }

    /*获取客户信息*/
    public function actionGetCustOne($id)
    {
        $result = CrmCustomerInfoShow::getOneInfo($id, 'cust_id,cust_sname,cust_adress');
        return $result;
    }

    /*
     * 客户等级
     */
    public function actionCustLevel()
    {
        return BsPubdata::getData(BsPubdata::CRM_CUSTOMER_LEVEL);
    }

    public function actionSelectCustomer()
    {
        return CrmCustomerInfo::find()->all();

    }

    protected function getModel($id)
    {
        if (($model = CrmCustomerInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getApplyModel($id)
    {
        if (($model = CrmCustomerApply::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionApply($id)
    {
        if (($model = CrmCustomerApply::getCustomerInfoOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //获取认证信息
    public function actionCertf($id)
    {
        return $result = CrmC::getCertfInfo($id);
    }

    //取消客户代码申请
    public function actionCannelCustApply($capply_id)
    {
        try {
            if (strpos($capply_id, '-')) {
                $capply_id = explode('-', $capply_id);
                unset($capply_id[count($capply_id) - 1]);
                foreach ($capply_id as $id) {

                    $model = CrmCustomerApply::findone($id);
                    $model->status = CrmCustomerApply::STATUS_CANCEL;
                    if (!$model->save()) {
                        throw new Exception(current($model->errors));
                    }
                }
            } else {
                $model = CrmCustomerApply::findone($capply_id);
                $model->status = CrmCustomerApply::STATUS_CANCEL;
                if (!$model->save()) {
                    throw new Exception(current($model->errors));
                }
            }
            return ['msg' => '取消成功!', 'flag' => 1];
        } catch (\Exception $e) {
            throw new Exception($this->error($e->getMessage()));
        }
    }

    public function actionEmployeeInfo($staff_code)
    {
        $result = CrmEmployee::getEmployeeInfo($staff_code);
        return $result;
    }

    //是否超级管理员
    public function actionIsSupper($id)
    {
        $result = User::isSupper($id);
        return $result;
    }

    //取消代码申请
    public function actionCannelApply($capply_id, $remark)
    {
        $post = Yii::$app->request->post();
        $capply_id = $post["capply_id"];
        $remark = $post["remark"];
        try {
            if (strpos($capply_id, '-')) {
                $capply_id = explode('-', $capply_id);
                unset($capply_id[count($capply_id) - 1]);
                $transaction=Yii::$app->db->beginTransaction();
                foreach ($capply_id as $id) {
                    $affect1 = Verifyrecord::find(['but_code' => 'khbmsh', 'vco_busid' => $id])->one();
                    $affect1->vco_status = Verifyrecord::STATUS_CANNEL;
                    if (!$affect1->save()) {
                        throw new \Exception('取消失败' . $affect1->getFirstError());
                    }
                    $model = CrmCustomerApply::findone($id);
                    $model->status = CrmCustomerApply::STATUS_CANCEL;
                    $model->remark = $remark;
                    if (!$model->save()) {
                        throw new Exception("取消失败!". $model->getFirstError());
                    }
                }
                $transaction->commit();
            } else {
                $transaction=Yii::$app->db->beginTransaction();
                $affect1 = Verifyrecord::find()->one();
                $affect1->vco_status = Verifyrecord::STATUS_CANNEL;
                if (!$affect1->save()) {
                    throw new \Exception('取消失败' . $affect1->getFirstError());
                }
                $model = CrmCustomerApply::findone($capply_id);
                $model->status = CrmCustomerApply::STATUS_CANCEL;
                $model->remark = $remark;
                if (!$model->save()) {
                    throw new Exception("取消失败!". $model->getFirstError());
                }
                $transaction->commit();
            }
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //审核完成时的保存
    public function actionSaveCustInfo($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $crmCustomerInfo = $this->getModel($id);
        $post = Yii::$app->request->post();
        try {
            //客户信息
            $crmCustomerInfo->load(Yii::$app->request->post());
            if (!$crmCustomerInfo->save()) {
                throw new \Exception("修改客户信息失败");
            }
            $updateBy = $crmCustomerInfo->update_by;
            /*主要联系人*/
            $countPerson = CrmCustomerPersion::find()->where(['cust_id' => $id])->count();
            if (CrmCustomerPersion::deleteAll(['cust_id' => $id]) < $countPerson) {
                throw  new \Exception("更新主要联系人失败");
            };
            $conetionArr = isset($post['CrmCustomerPersion']) ? $post['CrmCustomerPersion'] : false;
            if ($conetionArr) {
                foreach ($conetionArr as $value) {
                    if ($value['ccper_name'] != null) {
                        $crmCustPersion = new CrmCustomerPersion();
                        $products['CrmCustomerPersion'] = $value;
                        $crmCustPersion->load($products);
                        $crmCustPersion->cust_id = $id;
                        if (!$crmCustPersion->save()) {
                            throw  new \Exception("更新联系人失败");
                        };
                    }
                }
            }

//            /*设备信息*/
            $countDevice = CrmCustDevice::find()->where(['cust_id' => $id])->count();
            if (CrmCustDevice::deleteAll(['cust_id' => $id]) < $countDevice) {
                throw  new \Exception("更新设备失败");
            };
            $devideArr = isset($post['CrmCustDevice']) ? $post['CrmCustDevice'] : false;
            if ($devideArr) {
                foreach ($devideArr as $value) {
                    if ($value['type'] != null) {
                        $crmCustDevice = new CrmCustDevice();
                        $products['CrmCustDevice'] = $value;
                        $crmCustDevice->load($products);
                        $crmCustDevice->cust_id = $id;
                        $crmCustDevice->update_by = $updateBy;
                        if (!$crmCustDevice->save()) {
                            throw  new \Exception("更新设备失败");
                        };
                    }
                }
            }

//            /*产品信息*/
            $countProduct = CrmCustProduct::find()->where(['cust_id' => $id])->count();
            if (CrmCustProduct::deleteAll(['cust_id' => $id]) < $countProduct) {
                throw  new \Exception("更新产品失败");
            };
            $productArr = isset($post['CrmCustProduct']) ? $post['CrmCustProduct'] : false;
            if ($productArr) {
                foreach ($productArr as $value) {
                    if ($value['ccp_sname']) {
                        $crmCustProduct = new CrmCustProduct();
                        $products['CrmCustProduct'] = $value;
                        $crmCustProduct->load($products);
                        $crmCustProduct->cust_id = $id;
                        $crmCustProduct->update_by = $updateBy;
//                    return ActiveForm::validate($crmCustProduct);
                        if (!$crmCustProduct->save()) {
                            throw  new \Exception("更新产品失败");
                        };
                    }
                }
            }
//            /*主要客户信息*/
            $countCustomer = CrmCustCustomer::find()->where(['cust_id' => $id])->count();
            if (CrmCustCustomer::deleteAll(['cust_id' => $id]) < $countCustomer) {
                throw  new \Exception("更新客户失败");
            };
            $customerArr = isset($post['CrmCustCustomer']) ? $post['CrmCustCustomer'] : false;
            if ($customerArr) {
                foreach ($customerArr as $value) {
                    if ($value['cc_customer_name'] != null) {
                        $crmCustCustomer = new CrmCustCustomer();
                        $products['CrmCustCustomer'] = $value;
                        $crmCustCustomer->load($products);
                        $crmCustCustomer->cust_id = $id;
                        $crmCustCustomer->update_by = $updateBy;
                        if (!$crmCustCustomer->save()) {
                            throw  new \Exception("更新主要客户失败");
                        };
                    }
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }

        $transaction->commit();
        $msg = array('id' => $id, 'msg' => '修改销售客户"' . $crmCustomerInfo["cust_sname"] . '"');
        return $this->success('', $msg);
    }
}