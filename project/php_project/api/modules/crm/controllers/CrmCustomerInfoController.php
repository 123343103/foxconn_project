<?php

namespace app\modules\crm\controllers;

use app\classes\Trans;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsSettlement;
use app\modules\crm\models\CrmCorrespondentBank;
use app\modules\crm\models\CrmCreditApply;
use app\modules\crm\models\CrmCreditMaintain;
use app\modules\crm\models\CrmCustLinkcomp;
use app\modules\crm\models\CrmTurnover;
use app\modules\hr\models\HrOrganization;
use app\modules\ptdt\models\BsCategory;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsIndustrytype;
use app\modules\crm\models\CrmC;
use app\modules\crm\models\CrmCustCustomer;
use app\modules\crm\models\CrmCustDevice;
use app\modules\crm\models\CrmCustomerApply;
use app\modules\crm\models\CrmCustomerPersion;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmCustProduct;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\crm\models\search\CrmEmployeeSearch;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\crm\models\show\CrmCustPersoninchShow;
use app\modules\crm\models\show\CrmEmployeeShow;
use app\modules\common\models\BsCompany;
use Yii;
use app\controllers\BaseActiveController;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\search\CrmCustomerInfoSearch;
use app\modules\crm\models\CrmDistrictSalearea;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrStaff;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;

class CrmCustomerInfoController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmCustomerInfo';

    public function actionIndex()
    {
        $searchModel = new CrmCustomerInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
//        echo "<pre>";print_r(ArrayHelper::toArray($model));die();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }


    public function actionCreate()
    {
        $crmCustomerInfo = new CrmCustomerInfo();
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
            $crmCustomerInfo->codeType = CrmCustomerInfo::CODE_TYPE_CUSTOMER;
            if (!$crmCustomerInfo->save()) {
                throw new \Exception("新增客户信息失败".Json::encode($crmCustomerInfo->getErrors()));
            }
            $custId = $crmCustomerInfo->cust_id;
            $customer = $crmCustomerInfo->cust_sname;
            $createBy = $crmCustomerInfo->create_by;
            /*状态*/
            $status = new CrmCustomerStatus();
            $status->customer_id = $custId;
            $status->sale_status = CrmCustomerStatus::STATUS_DEFAULT;
            if (!empty($ismember) && $ismember == '1') {
                $status->member_status = CrmCustomerStatus::STATUS_DEFAULT;
            } else {
                $status->member_status = CrmCustomerStatus::STATUS_DEL;
            }
            if (!$status->save()) {
                throw new \Exception("新增失败");
            }
            /*认领信息*/
            if ($managerId != null) {
                $personinch = new CrmCustPersoninch();
                $hr=HrStaff::findOne($managerId);
                //默认查询有效客户经理人
                $a = CrmEmployeeShow::find()->with(["area","storeInfo"])->where(['and',['staff_code' => $hr['staff_code']],['sale_status' => CrmEmployee::SALE_STATUS_DEFAULT]])->asArray()->one();
                $personinch->ccpich_personid = $managerId;
                $personinch->ccpich_personid2=$managerId;
                $personinch->csarea_id = isset($a['area']['csarea_id'])?$a['area']['csarea_id']:"";
                $personinch->sts_id = isset($a['storeInfo']['sts_id'])?$a['storeInfo']['sts_id']:"";
                $personinch->cust_id = $custId;
                $personinch->ccpich_date = date('Y-m-d', time());
                $personinch->ccpich_status = CrmCustPersoninch::STATUS_DEFAULT;
                $personinch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;
                if (!$personinch->save()) {
                    throw new \Exception("新增认领信息失败".Json::encode($personinch->getErrors()));
                }
            }

            /*认证信息*/
            $certfArr = isset($post['CrmC']) ? $post['CrmC'] : false;
            if ($certfArr) {
                $crmC=new CrmC();
                $crmC->load($post);

                if($post['CrmC']['crtf_type'] == 0){
                    $post['CrmC']['o_license'] = $post['CrmC']['o_license'];
                }else{
                    $post['CrmC']['o_license'] = $post['CrmC']['o_license_new'];
                }
                $crmC->cust_id = $crmCustomerInfo->primaryKey;
                $crmC->o_license =$post['CrmC']['o_license'];
                if (!$crmC->save()) {
                    throw  new \Exception("新增认证信息失败".Json::encode($crmC->getErrors()));
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
                            throw  new \Exception("新增联系人失败".Json::encode($crmCustPersion->getErrors()));
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
                            throw  new \Exception("新增设备失败".Json::encode($crmCustDevice->getErrors()));
                        };
                    }

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
                            throw  new \Exception("新增产品失败".Json::encode($crmCustProduct->getErrors()));
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
                            throw  new \Exception("新增主要客户失败".Json::encode($crmCustCustomer->getErrors()));
                        };
                    }
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        $msg = array('id' => $custId, 'msg' => '新增销售客户"' . $customer . '"');
        return $this->success('', $msg);
    }

    public function actionUpdate($id,$type="")
    {
        $transaction = Yii::$app->db->beginTransaction();
        $crmCustomerInfo = $this->getModel($id);
        $post = Yii::$app->request->post();
        try {
            /*客户信息*/
            $crmCustomerInfo->load(Yii::$app->request->post());
            $custRegname = isset($post['CrmCustomerInfo']['cust_regname'])?$post['CrmCustomerInfo']['cust_regname']:"";
            $custRegnumber =isset($post['CrmCustomerInfo']['cust_regnumber'])?$post['CrmCustomerInfo']['cust_regnumber']:"";
            $managerId = isset($post['CrmCustomerInfo']['cust_manager'])?$post['CrmCustomerInfo']['cust_manager']:"";
            $ismember = isset($post['CrmCustomerInfo']['cust_ismember'])?$post['CrmCustomerInfo']['cust_ismember']:"";
            if (empty($managerId)) {
                $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_NO;
            } else {
                $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_YES;
            };
            $crmCustomerInfo->cust_regname = serialize($custRegname);
            $crmCustomerInfo->cust_regnumber = serialize($custRegnumber);
            if (!$crmCustomerInfo->save()) {
                throw new \Exception("修改客户信息失败");
            }

            $updateBy = $crmCustomerInfo->update_by;
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
                /*当未认领,然后修改页面认领时操作表*/
                if ($managerId != null) {
                    $personinch = new CrmCustPersoninch();
                    $hr=HrStaff::findOne($managerId);
                    $a = CrmEmployeeShow::find()->with(["area","storeInfo"])->where(['staff_code' => $hr['staff_code']])->asArray()->one();
//                    $a = CrmEmployeeShow::find()->where(['staff_code' => isset($post['code'])?$post['code']:""])->one();
                    $personinch->ccpich_personid = $managerId;
                    $personinch->ccpich_personid2 = $managerId;
                    $personinch->csarea_id = isset($a['area']['csarea_id'])?$a['area']['csarea_id']:"";
                    $personinch->sts_id = isset($a['storeInfo']['sts_id'])?$a['storeInfo']['sts_id']:"";
                    $personinch->cust_id = $id;
                    $personinch->ccpich_date = date('Y-m-d', time());
                    $personinch->ccpich_status = CrmCustPersoninch::STATUS_DEFAULT;
                    $personinch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;
                    if (!$personinch->save()) {
                        throw new \Exception("新增认领信息失败");
                    }
                }
            } else {
                /*当已认领,然后修改页面认领时操作表*/
                $arr = explode(',',$managerId);
                foreach ($arr as $key => $val){
                    $code = HrStaff::find()->where(['staff_id'=>$val])->one();
                    $hr=HrStaff::findOne($val);
                    $a = CrmEmployeeShow::find()->with(["area","storeInfo"])->where(['staff_code' => $hr['staff_code']])->asArray()->one();
                    $person = CrmCustPersoninch::find()->where(['and',['cust_id' => $id],['ccpich_personid' => $val]])->one();
                    if(empty($person)){
                        $personinch = new CrmCustPersoninch();
                    }else{
                        $personinch = $person;
                    }
                    $personinch->ccpich_personid = $val;
                    $personinch->ccpich_personid2 = $val;
                    $personinch->csarea_id = isset($a['area']['csarea_id'])?$a['area']['csarea_id']:"";
                    $personinch->sts_id = isset($a['storeInfo']['sts_id'])?$a['storeInfo']['sts_id']:"";
                    $personinch->cust_id = $id;
                    $personinch->ccpich_date = date('Y-m-d', time());
                    $personinch->ccpich_status = CrmCustPersoninch::STATUS_DEFAULT;
                    $personinch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;
                    if (!$personinch->save()) {
                       throw new \Exception("修改认领信息失败");
                    }
                }
            }
            /*认证信息*/
            if(empty($crmCustomerInfo['cust_code'])) {
                $certfArr = isset($post['CrmC']) ? $post['CrmC'] : false;
                if ($certfArr) {
                    $crmC = CrmC::findOne(["cust_id" => $crmCustomerInfo->primaryKey]);
                    if (!$crmC) {
                        $crmC = new CrmC();
                    }
                    $crmC->load($post);
                    if ($post['CrmC']['crtf_type'] == 0) {
                        $post['CrmC']['o_license'] = $post['CrmC']['o_license'];
                    } else {
                        $post['CrmC']['o_license'] = $post['CrmC']['o_license_new'];
                    }
                    $crmC->cust_id = $crmCustomerInfo->primaryKey;
                    $crmC->o_license = $post['CrmC']['o_license'];
//                if (!empty($post['CrmC']['o_cerft']))//一般纳税人资格证不为空
//                {
////                    $crmC->qlf_certf = $post['CrmC']['qlf_certf'];
//                    $crmC->qlf_certf =basename($post['CrmC']['qlf_certf']);
//                    $crmC->o_cerft = $post['CrmC']['o_cerft'];
//                } else {
//                    $crmC->qlf_certf = "";
//                    $crmC->o_cerft = "";
//                }
                    if (!$crmC->save()) {
                        throw  new \Exception("修改认证信息失败");
                    }
                }
            }

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
            $countCustomer = CrmCustCustomer::find()->where(['and', ['cust_id' => $id], ['=', 'cust_type', CrmCustCustomer::TYPE_CUSTOMER]])->count();
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

    /**
     * @return mixed
     * 选择客户经理人
     */
    public function actionSelectManage()
    {
        $searchModel = new CrmEmployeeSearch();
        $dataProvider = $searchModel->searchManager(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;

    }

    /**
     * @param $id
     * @return array
     * 转招商
     */
    public function actionTurnInvestment($str)
    {
//        $arr = explode(',', $str);
//        foreach ($arr as $key => $val) {
//            $model = $this->getStatusModel($val);
//            $model->sale_status = CrmCustomerStatus::STATUS_DEL;
//            $model->potential_status = CrmCustomerStatus::STATUS_DEL;
//            $model->investment_status = CrmCustomerStatus::STATUS_DEFAULT;
//            $cust = CrmCustomerInfo::getCustomerInfoOne($val);
//            $result = $model->save();
//        }
//        if ($result) {
//            return $this->success('', '客户[' . $cust["cust_sname"] . ']转招商');
//        } else {
//            return $this->error();
//        }
        /*F1678086 10/27 权限后台判断*/
        $arr = explode(',', $str);
        $suc = 0;
        $err = 0;
        $name = '';
        $result = false;
        foreach ($arr as $key => $val){
            $model = (new Query())->select([
                'status','sale_status','cust_code'
            ])->from(CrmCustomerInfo::tableName().' info')
                ->leftJoin(CrmCustomerStatus::tableName().' status','status.customer_id = info.cust_id')
                ->leftJoin(CrmCustomerApply::tableName().' apply','apply.cust_id = info.cust_id')
                ->where(['info.cust_id'=>$val])
                ->one();
            if($model['status'] != 20 && $model['status'] != 30 && empty($model['cust_code']) && $model['sale_status'] == 10){
                if($model['status'] == 50){
                    $apply = CrmCustomerApply::find()->where(['cust_id'=>$val])->one();
                    $apply->status = CrmCustomerApply::STATUS_CANCEL;
                    $apply->save();
                }
                $modela = $this->getStatusModel($val);
                $modela->sale_status = NULL;
                $modela->potential_status = NULL;
                $modela->investment_status = CrmCustomerStatus::STATUS_DEFAULT;
                $cust = CrmCustomerInfo::getCustomerInfoOne($val);
                $name = $name . $cust['cust_sname'] . ',';
                $result = $modela->save();
                $suc++;
            }else{
                $err++;
                continue;
            }
        }
        $cust_sname = trim($name, ',');
        if ($result) {
            return $this->success('成功'.$suc.'条,失败'.$err.'条', '荐招商[' . $cust_sname . ']信息');
        } else {
            return $this->error('成功'.$suc.'条,失败'.$err.'条');
        }
    }

    /**
     * @param $str
     * @return array
     * 抛至公海
     */
    public function actionThrowSea($str)
    {
//        $arr = explode(',', $str);
//        foreach ($arr as $key => $val) {
//            $personinch = CrmCustPersoninch::find()->where(['cust_id' => $val])->andWhere(['ccpich_stype' => CrmCustPersoninch::PERSONINCH_SALES])->one();
//            if($personinch){
//                $personinch->delete();
//            }
//            $crmCustomerInfo = $this->getModel($val);
//            $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_NO;
//            $crmCustomerInfo->save();
//            $model = $this->getStatusModel($val);
//            if ($crmCustomerInfo->cust_ismember == 1) {
//                $model->sale_status = CrmCustomerStatus::STATUS_DEL;
//            } else {
//                $model->sale_status = CrmCustomerStatus::STATUS_DEL;
//                $model->potential_status = CrmCustomerStatus::STATUS_DEFAULT;
//            }
//            $result = $model->save();
//        }
//        if ($result) {
//            return $this->success();
//        } else {
//            return $this->error();
//        }
        /*F1678086 10/27 权限后台判断*/
        $arr = explode(',', $str);
        $suc = 0;
        $err = 0;
        $name = '';
        $result = false;
        foreach ($arr as $key => $val){
            $model = (new Query())->select([
                'status','sale_status','cust_code'
            ])->from(CrmCustomerInfo::tableName().' info')
                ->leftJoin(CrmCustomerStatus::tableName().' status','status.customer_id = info.cust_id')
                ->leftJoin(CrmCustomerApply::tableName().' apply','apply.cust_id = info.cust_id')
                ->where(['info.cust_id'=>$val])
                ->one();
            if($model['status'] != 20 && $model['status'] != 30 && empty($model['cust_code']) && $model['sale_status'] == 10){
                /*驳回情况下更改申请状态*/
                if($model['status'] == 50){
                    $apply = CrmCustomerApply::find()->where(['cust_id'=>$val])->one();
                    $apply->status = CrmCustomerApply::STATUS_CANCEL;
                    $apply->save();
                }
                $personinch = CrmCustPersoninch::find()->where(['cust_id' => $val])->andWhere(['ccpich_stype' => CrmCustPersoninch::PERSONINCH_SALES])->one();
                if($personinch){
                    $personinch->delete();
                }
                $crmCustomerInfo = $this->getModel($val);
                $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_NO;
                $crmCustomerInfo->save();
                $model = $this->getStatusModel($val);
                if ($crmCustomerInfo->cust_ismember == 1) {
                    $model->sale_status = NULL;
                } else {
                    $model->sale_status = NULL;
                    $model->potential_status = CrmCustomerStatus::STATUS_DEFAULT;
                }
                $name = $name . $crmCustomerInfo['cust_sname'] . ',';
                $result = $model->save();
                $suc++;
            }else{
                $err++;
                continue;
            }
        }
        $cust_sname = trim($name, ',');
        if ($result) {
            return $this->success('成功'.$suc.'条,失败'.$err.'条', '抛公海[' . $cust_sname . ']');
        } else {
            return $this->error('成功'.$suc.'条,失败'.$err.'条');
        }
    }

    /*认领信息*/
    public function actionUpdatePersonInch()
    {
        /*F1678086 10/27 权限后台判断*/
        $suc = 0;
        $err = 0;
        $name = '';
        $result = false;
        $params=\Yii::$app->request->post();
        $customers=explode(",",$params['customers']);
        foreach ($customers as $customer){
            $model = (new Query())->select([
                'status','sale_status','cust_code'
            ])->from(CrmCustomerInfo::tableName().' info')
                ->leftJoin(CrmCustomerStatus::tableName().' status','status.customer_id = info.cust_id')
                ->leftJoin(CrmCustomerApply::tableName().' apply','apply.cust_id = info.cust_id')
                ->where(['info.cust_id'=>$customer])
                ->one();
            if($model['status'] != 20 && $model['status'] != 30  && $model['sale_status'] == 10 ){
                $crmCustomerInfo = $this->getModel($customer);
                foreach ($crmCustomerInfo as $k => $v) {
                    $crmCustomerInfo[$k] = html_entity_decode($v);
                }
                $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_YES;
//                $crmCustomerInfo->cust_regfunds = (int)$crmCustomerInfo['cust_regfunds'];
                $personinch = CrmCustPersoninch::findOne([
                    'cust_id'=>$customer,
                    'ccpich_personid'=>$params["CrmCustPersoninch"]["ccpich_personid"],
                    'ccpich_stype'=>CrmCustPersoninch::PERSONINCH_SALES,
                ]);
                $personinch = empty($personinch) ? new CrmCustPersoninch() : $personinch;
                $personinch->load(Yii::$app->request->post());
                $personinch->cust_id = $customer;
                $personinch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;
                $crmCustomerInfo->save();
                $personinch->load(Yii::$app->request->post());
                $personinch->cust_id = $customer;
                $personinch->ccpich_status = CrmCustPersoninch::STATUS_DEFAULT;
                $name = $name . $crmCustomerInfo['cust_sname'] . ',';
                $result = $personinch->save();
                $suc++;
            }else{
                $err++;
                continue;
            }
        }
        $cust_sname = trim($name, ',');
        if ($result) {
            return $this->success('成功'.$suc.'条,失败'.$err.'条', '认领[' . $cust_sname . ']');
        } else {
            return $this->error('成功'.$suc.'条,失败'.$err.'条');
        }
    }

    /*取消认领*/
    public function actionCanclePersonInch()
    {
        /*F1678086 10/27 权限后台判断*/
        $msg=[];
        $params=\Yii::$app->request->post();
        $customers=explode(",",$params['customers']);
        $suc = 0;
        $err = 0;
        foreach ($customers as $customer){
            $crmCustomerInfo = $this->getModel($customer);
//            $crmCustomerInfo->cust_regfunds = (int)$crmCustomerInfo['cust_regfunds'];
            foreach ($crmCustomerInfo as $k => $v) {
                $crmCustomerInfo[$k] = html_entity_decode($v);
            }
            $personinch = CrmCustPersoninch::findOne(["cust_id"=>$customer,"ccpich_stype"=>1,"ccpich_personid"=>$params["CrmCustPersoninch"]["ccpich_personid"]]);
            $personinch->delete();
            $count=CrmCustPersoninch::find()->where(["cust_id"=>$customer,"ccpich_stype"=>1])->count();
            if($count>0){
                $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_YES;
            }else{
                $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_NO;
            }
            $result = $crmCustomerInfo->save();
            if($result){
                $suc++;
            }else{
                $err++;
                continue;
            }
        }
        if ($result) {
            return $this->success('成功'.$suc.'条,失败'.$err.'条');
        } else {
            return $this->error('成功'.$suc.'条,失败'.$err.'条');
        }
    }

    /**
     * @param $id
     * @return array
     * 删除客户
     */
//    public function actionDelete($id)
//    {
//        $arr = explode(',', $id);
//        $name = '';
//        foreach ($arr as $key => $val) {
//            $model = $this->getStatusModel($val);
//            $firm = CrmCustomerInfo::getCustomerInfoOne($val);
//            $model->sale_status = CrmCustomerStatus::STATUS_DEL;
//            $name = $name . $firm['cust_sname'] . ',';
//            $result = $model->save();
//        }
//        $cust_sname = trim($name, ',');
//        if ($result) {
//            $msg = array('id' => $id, 'msg' => '删除销售客户"' . $cust_sname . '"');
//            return $this->success('', $msg);
//        } else {
//            return $this->error();
//        }
//    }
    public function actionDelete($id,$isSuper,$login){
        $em = $this->actionGetEmployee($login);
        if(empty($em) && $isSuper == 0){
            return $this->error('当前登录人非客户经理人,没有删除权限');
        }
        $arr = explode(',', $id);
        $suc = 0;
        $err = 0;
        $name = '';
        $result = false;
        foreach ($arr as $key => $val){
            $inch = CrmCustPersoninch::find()->where(['cust_id'=>$val])->andWhere(['and',['ccpich_personid'=>$login],['ccpich_stype'=>CrmCustPersoninch::PERSONINCH_SALES]])->one();
            if(empty($inch) && $isSuper == 0){
                $err++;
                continue;
            }
            $model = (new Query())->select([
                'status','sale_status','cust_code'
            ])->from(CrmCustomerInfo::tableName().' info')
                ->leftJoin(CrmCustomerStatus::tableName().' status','status.customer_id = info.cust_id')
                ->leftJoin(CrmCustomerApply::tableName().' apply','apply.cust_id = info.cust_id')
                ->where(['info.cust_id'=>$val])
                ->one();
            if($isSuper == 1 || !empty($inch)){
                if($model['status'] != 20 && $model['status'] != 30 && empty($model['cust_code']) && $model['sale_status'] == 10){
                    if($model['status'] == 50){
                        $apply = CrmCustomerApply::find()->where(['cust_id'=>$val])->one();
                        $apply->status = CrmCustomerApply::STATUS_CANCEL;
                        $apply->save();
                    }
                    $modela = $this->getStatusModel($val);
                    $firm = CrmCustomerInfo::getCustomerInfoOne($val);
                    $modela->sale_status = CrmCustomerStatus::STATUS_DEL;
                    $cust = CrmCustomerInfo::getCustomerInfoOne($val);
                    $name = $name . $cust['cust_sname'] . ',';
                    $result = $modela->save();
                    $suc++;
                }else{
                    $err++;
                    continue;
                }
            }
        }
        $cust_sname = trim($name, ',');
        if ($result) {
            return $this->success('成功'.$suc.'条,失败'.$err.'条', '删除[' . $cust_sname . ']信息');
        } else {
            return $this->error('成功'.$suc.'条,失败'.$err.'条');
        }
    }
    /**
     * @param $id
     * @return array
     * 激活客户
     */
    public function actionActivation($id)
    {
        $arr = explode(',',$id);
        $name = '';
        foreach ($arr as $key => $val){
            $model = $this->getStatusModel($val);
            $firm = CrmCustomerInfo::getCustomerInfoOne($val);
            $model->sale_status = CrmCustomerStatus::STATUS_DEFAULT;
            $name = $name.$firm['cust_sname'].',';
            $result = $model->save();
        }
        $cust_sname = trim($name,',');
        if ($result) {
            $msg = array('id' => $id, 'msg' => '激活销售客户"' . $cust_sname . '"');
            return $this->success('',$msg);
        } else {
            return $this->error();
        }
    }
    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 获取一条认领信息
     */
    public function actionGetPersonInchOne($id)
    {
        $result = CrmCustPersoninchShow::findOne(['ccpich_personid' =>$id,'ccpich_stype'=>1]);
        return $result;
    }

    public function actionGetSales($id)
    {
        $result = CrmEmployeeShow::find()->where(['and', ['leader_id' => $id], ['sale_status' => CrmEmployee::SALE_STATUS_DEFAULT]])->all();
//        $leader = $this->actionGetManagerStaffInfo($id);
//        $result = $this->actionGetSaleStaffInfo($leader['staff_id']);
        return $result;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 认领信息客户经理人
     */
    public function actionGetManagerStaffInfo($id)
    {
        $staff = HrStaff::find()->where(['staff_id' => $id])->select(['staff_code'])->one();
        $result = CrmEmployeeShow::getManagetRelation($staff['staff_code']);
        return $result;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 认领信息销售人员
     */
    public function actionGetSaleStaffInfo($leaderId)
    {
        $result = CrmEmployeeShow::getSaleInfo($leaderId);
        return $result;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 所有销售人员
     */
    public function actionGetAllSales()
    {
        $result = CrmEmployeeShow::getAllSales();
        return $result;
    }

    public function actionGetEmployee($id)
    {
        $staff = HrStaff::find()->where(['staff_id' => $id])->select(['staff_code'])->one();
        $leader = CrmEmployee::find()->where(['and', ['staff_code' => $staff['staff_code']], ['=', 'sale_status', CrmEmployee::SALE_STATUS_DEFAULT]])->select(['isrule', 'leader_id'])->one();
        if ($leader['isrule'] == 1) {
            $manage = CrmEmployeeShow::getManagetRelation($staff['staff_code']);
        } else {
            $manage = '';
        }
        return $manage;
    }
    /**
     * @param $companyId
     * @param $createBy
     * @return array
     * 导入
     */
    public function actionImport($companyId,$createBy){
        $post = Yii::$app->request->post();
        static $succ = 0;
        static $err = 0;
        $log=[];
        foreach ($post as $k => $v) {
            //跳过第一列标题
            if ($k >= 0) {
                $trans=Yii::$app->db->beginTransaction();
                try{
                    $time = 25569;
                    $tran = new Trans();
                    // 根據工號查詢數據，如存在則不插入數據
                    $model = new CrmCustomerInfo();
                    $model->cust_sname = !empty($v["B"])?$tran->t2c($v["B"]):null;     //"公司名称"
                    $model->cust_shortname = !empty($v["C"])?$tran->t2c($v["C"]):null;            //"公司简称"
                    $D = !empty($v["D"])?$tran->t2c($v["D"]):null;
                    $D1 = BsPubdata::getExcelData($D);
                    $model->cust_type = !empty($D1['bsp_id'])?$D1['bsp_id']:null;     //"客户类型"
                    $E = !empty($v["E"])?$v["E"]:null;
                    $E1 = BsPubdata::getExcelData($E);
                    $model->cust_level = !empty($E1['bsp_id'])?$E1['bsp_id']:null;     //"客户等级"
                    $model->cust_contacts = !empty($v["F"])?$v["F"]:null;     //"联系人"
                    $model->cust_tel2 = !empty($v["G"])?$v["G"]:null;     //"联系电话"

                    $model->cust_email = !empty($v["I"])?$v["I"]:null;     //"邮箱"
                    if(isset($v['J'])){
                        $salearea = CrmSalearea::findOne(['csarea_name'=>$tran->t2c($v['J'])]);
                        $model->cust_salearea = isset($salearea->csarea_id)?$salearea->csarea_id:null;     //"营销区域"
                    }

                    if(isset($v["K"])){
                        $district=BsDistrict::findOne(["district_name"=>$tran->t2c($v["K"])]);//"所在区域"
                        $model->cust_area=isset($district->district_id)?$district->district_id:null;
                    }

                    if(isset($v["L"])){
                        $district=BsDistrict::findOne(["district_name"=>$tran->t2c($v["L"])]);//"地址(区/县)"
                        $model->cust_district_2=isset($district->district_id)?$district->district_id:null;
                    }
                    $model->cust_adress = !empty($v["M"])?$v["M"]:null;     //"详细地址"

                    $N = !empty($v["N"])?$tran->t2c($v["N"]):null;
                    $N1 = BsPubdata::getExcelData($N);
                    $model->member_source = !empty($N1['bsp_id'])?$N1['bsp_id']:null;     //"客户来源"

                    $O = !empty($v["O"])?$tran->t2c($v["O"]):null;
                    $O1 = BsPubdata::getExcelData($O);
                    $model->cust_businesstype = !empty($O1['bsp_id'])?$O1['bsp_id']:null;//经营类型

                    $P = !empty($v["P"])?$v["P"]:null;
                    $P1 = BsPubdata::getExcelData($P);
                    $model->member_curr = !empty($P1['bsp_id'])?$P1['bsp_id']:null;//"交易货币"

                    $Q = !empty($v["Q"])?$tran->t2c($v["Q"]):null;
                    $Q1 = BsPubdata::getExcelData($Q);
                    $model->cust_industrytype = !empty($Q1['bsp_id'])?$Q1['bsp_id']:null;//"行业类型"

                    $R = !empty($v["R"])?$tran->t2c($v["R"]):null;
                    $R1 = BsPubdata::getExcelData($R);
                    $model->cust_compvirtue = !empty($R1['bsp_id'])?$R1['bsp_id']:null;//"公司属性"

                    // 插入數據
                    $model->company_id = $companyId;
                    $model->create_by = $createBy;
//                    $model->cust_ismember = CrmCustomerInfo::MEMBER_YES;
                    $model->codeType = CrmCustomerInfo::CODE_TYPE_CUSTOMER;
                    if(!($model->validate() && $model->save())){
                        throw new \Exception(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                    $custId = $model->cust_id;
                    if(!empty($v['H'])){
                        $inch = new CrmCustPersoninch();
                        $name = $tran->c2t($v['H']);
                        $hr = HrStaff::findOne(['staff_name'=>$name]);
                        if(!empty($hr)){
                            $inch->ccpich_personid = $hr["staff_id"];
                            $inch->cust_id = $custId;
                            $inch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;
                            $model->personinch_status = CrmCustomerInfo::PERSONINCH_YES;
                            $model->save();
                            $inch->save();
                        }
                    }else{
                        $model->personinch_status = CrmCustomerInfo::PERSONINCH_NO;  //认领信息
                        $model->save();
                    }
                    $status = new CrmCustomerStatus();
                    $status->customer_id = $custId;
                    $status->sale_status = CrmCustomerStatus::STATUS_DEFAULT;
                    $status->save();
                    $succ++;
                    $trans->commit();
                }catch (\Exception $e){
                    $log[]=[
                        'file'=>basename(get_class()).":".$e->getLine(),
                        'message'=>$e->getMessage()
                    ];
                    $err++;
                    $trans->rollBack();
                }
            }
        }
        return ["succ"=>$succ,"error"=>$err,"log"=>$log];
    }
    /**
     * @return mixed
     * 導出excel
     */
    public function actionExcel()
    {
        $searchModel = new CrmCustomerInfoSearch();
        $data = $searchModel->export(Yii::$app->request->queryParams)->all();
        return $data;
    }

    /*下拉菜单*/
    public function actionDownList($userId)
    {
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
        $downList['manager'] = CrmEmployee::getManager($userId);//客户经理人
        $downList['allsales'] = CrmEmployee::getAllSales();//所有销售人员
        $downList['invoiceType'] = BsPubdata::getList(BsPubdata::CRM_INVOICE_TYPE);  //发票类型
        $downList['visitType'] = BsPubdata::getData(BsPubdata::CRM_VISIT_TYPE);
        $downList['settlement_type'] = [
            '0'=>'发票日',
            '1'=>'月结日'
        ];
        $downList['settlement'] = BsSettlement::getSettlement(); //付款条件
        $downList['pay_method'] = BsPubdata::getList(BsPubdata::CRM_PAY_METHOD);//付款方式
        $downList['initial_day'] = BsPubdata::getList(BsPubdata::CRM_INITIAL_DAY);//起算日
        $downList['pay_day'] = BsPubdata::getList(BsPubdata::CRM_PAY_DAY);//付款日

//        $downList['status'] = [
//            CrmCreditApply::STATUS_PENDING => '待提交',
//            CrmCreditApply::STATUS_REVIEW => '审核中',
//            CrmCreditApply::STATUS_OVER => '审核完成',
//            CrmCreditApply::STATUS_REJECT => '驳回',
//        ];
//        $downList['list_status'] = [
//            CrmCreditApply::STATUS_OVER => '审核完成',
//            CrmCreditApply::STATUS_FREEZE => '冻结',
//        ];
        $downList['currency'] = BsPubdata::getList(BsPubdata::SUPPLIER_TRADE_CURRENCY); //币别
        $downList['credit_type'] = CrmCreditMaintain::find()->select(['id','credit_name'])->where(['=','credit_status',CrmCreditMaintain::STATUS_DEFAULT])->all(); //额度类型
        $downList['verifyType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'credit'])->all();    //账信类型
        $downList['company'] = BsCompany::find()->select(['company_id','company_name'])->where(['=','company_status',BsCompany::STATUS_DEFAULT])->all();

        return $downList;
    }

    /*客户经理人*/
    public function actionGetVisitManager($code)
    {
        return HrStaff::getStaffByIdCode($code);
    }

    public function actionGetStaff($id)
    {
        return HrStaff::find()->select(['staff_name', 'staff_code', 'staff_id'])->where(['staff_id' => $id])->one();
    }

    /*所在地区一级省份*/
    public function actionDistrict()
    {
        return BsDistrict::getDisProvince();
    }

    public function actionGetAllDistrict($id)
    {
        $fourLevel = $this->getLevel($id);
        if (empty($fourLevel)) {
            return $fourLevel;
        }
        $threeLevelId = $fourLevel->district_pid;
        $threeLevel = $this->getLevel($threeLevelId);
        $twoLevelId = $threeLevel->district_pid;
        $twoLevel = $this->getLevel($twoLevelId);
        $oneLevelId = $twoLevel->district_pid;
        $oneLevel = $this->getLevel($oneLevelId);
        $zeroLevelId = $oneLevel->district_pid;
        return [
            'oneLevel' => BsDistrict::getChildByParentId($zeroLevelId),
            'oneLevelId' => $oneLevelId,
            'twoLevel' => BsDistrict::getChildByParentId($oneLevelId),
            'twoLevelId' => $twoLevelId,
            'threeLevel' => BsDistrict::getChildByParentId($twoLevelId),
            'threeLevelId' => $threeLevelId,
            'fourLevel' => BsDistrict::getChildByParentId($threeLevelId),
            'fourLevelId' => $id,
        ];
    }

    //获取一条等级数据
    public function getLevel($id)
    {
        return BsDistrict::findOne($id);
    }

    public function actionGetDistrictSalearea($id)
    {
        return CrmDistrictSalearea::getDisSalearea($id);
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

    public function actionModels($id)
    {
        $result = CrmCustomerInfoShow::getCustomerInfoOne($id);
        return $result;
    }

    public function actionSelectCustomer()
    {
        $searchModel = new CrmCustomerInfoSearch();
        $dataProvider = $searchModel->searchSelect(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;

    }

    protected function getModel($id)
    {
        if (($model = CrmCustomerInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getStatusModel($id)
    {
        if (($model = CrmCustomerStatus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $staff_id
     * @return string
     * 获取当前登录人部门编码
     */
    public function actionGetUserOrg($staff_id){
        $staff=HrStaff::findOne($staff_id);
        return $staff->organization_code;
    }

    /**
     * @param $staff_id
     * @return array|\yii\db\ActiveRecord[]
     * 获取部门名称
     */
    public function actionGetUserOrgStaff($staff_id){
        $staff=HrStaff::findOne($staff_id);
        $org=HrOrganization::findOne(["organization_code"=>$staff->organization_code]);
        $res[]=$org->organization_code;
        $orgs=HrOrganization::getOrgChild($org->organization_id,$res);
        return (new Query())->select(['staff.staff_id','staff.staff_name'])->from(HrStaff::tableName().' staff')->leftJoin(CrmEmployee::tableName().' em','em.staff_code = staff.staff_code')->where(["in","organization_code",$orgs])->andWhere(['and',['em.sale_status'=>CrmEmployee::SALE_STATUS_DEFAULT],['em.isrule'=>CrmEmployee::SALE_MANAGER_Y]])->all();
    }

    /**
     * @param
     * @return array
     *分配部门下拉
     */
    public function actionClaimDropdownList($org_code=""){
        if($org_code){
            $org=HrOrganization::findOne(["organization_code"=>$org_code]);
            $orgs=HrOrganization::getOrgChild($org->organization_id);
//            return HrStaff::find()->select("staff_id,staff_code,staff_name,organization_code")->where(["in","organization_code",array_column($orgs,"organization_code")])->all();
            return (new Query())->select([
                "staff.staff_id","staff.staff_code","staff.staff_name","staff.organization_code"
            ])->from(HrStaff::tableName().' staff')
                ->leftJoin(CrmEmployee::tableName().' em','em.staff_code = staff.staff_code')
                ->where(["in","staff.organization_code",array_column($orgs,"organization_code")])
                ->andWhere(['and',['em.sale_status'=>CrmEmployee::SALE_STATUS_DEFAULT],['em.isrule'=>CrmEmployee::SALE_MANAGER_Y]])
                ->all();
        }else{
            $org=HrOrganization::findOne(["organization_pid"=>0]);
            $data=HrOrganization::getOrgChild($org->organization_id);
            return $data;
        }
    }

    /**
     * @param $id
     * @return array
     * 账信申请关联信息
     */
    public function actionCustomerRelation($id){
        $result['contact'] = CrmCustomerPersion::findAll(['cust_id'=>$id]);
        $result['turnover'] = CrmTurnover::findAll(['cust_id'=>$id]);
        $result['linkcomp'] = CrmCustLinkcomp::findAll(['cust_id'=>$id]);
        $result['custCustomer'] = CrmCustCustomer::findAll(['cust_id'=>$id,'cust_type'=>CrmCustCustomer::TYPE_CUSTOMER]);
        $result['supplier'] = CrmCustCustomer::findAll(['cust_id'=>$id,'cust_type'=>CrmCustCustomer::TYPE_SUPPLIER]);
        $result['bank'] = CrmCorrespondentBank::findAll(['cust_id'=>$id]);
        return $result;
    }

    public function actionValidateCode($id, $attr, $val)
    {
        $class = $this->modelClass;//默认使用moduleClass作为验证类
//        if($id){
        $model = $class::findOne($id);
//            $model->$attr=urldecode($val);
//            $model->validate($attr);
//            return $model->getFirstError($attr)?$model->getFirstError($attr):"";
        if ($val === $model['cust_tax_code']) {
            return '';
        } else {
            $model = $class::find()->where(['cust_tax_code' => $val])->one();
            if (!empty($model)) {
                return '该税籍编码已存在';
            } else {
                return '';
            }
        }
    }
}
