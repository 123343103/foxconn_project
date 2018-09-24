<?php
/**
 * User: F1676624 Date: 2017/2/7
 */

namespace app\modules\app\controllers;

use app\controllers\AppBaseController;
use app\modules\app\models\show\CustomerAppShow;
use app\modules\crm\models\CrmCustomerInfo;
use yii;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\hr\models\HrStaff;
use yii\web\NotFoundHttpException;
use app\modules\ptdt\models\PdProductType;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsIndustrytype;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmDistrictSalearea;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\search\CrmCustomerPersionSearch;
use app\modules\crm\models\search\CrmCustProductSearch;
use app\modules\crm\models\search\CrmCustDeviceSearch;
use app\modules\crm\models\search\CrmCustCustomerSearch;
use app\modules\crm\models\CrmCustomerPersion;
use yii\helpers\Json;
use app\modules\ptdt\models\BsCategory;
use app\modules\crm\models\CrmEmployee;

class CustomerInfoController extends AppBaseController
{

    public $modelClass = 'app\modules\crm\models\CrmCustomerInfo';


    public function actionList()
    {
        $searchModel = new CustomerAppShow();
        $dataProvider = $searchModel->AppSearch(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    //资料认领列表  显示为自己未认领的客户
    public function actionAllList()
    {
        $searchModel = new CustomerAppShow();
        $dataProvider = $searchModel->AppPersoninchSearch(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    public function actionCreate()
    {
        $crmCustomerInfo = new CrmCustomerInfo();
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        $staffId = $post['CrmCustomerInfo']['create_by'];
        $staffcode = HrStaff::find()->where(['staff_id' => $staffId])->select('staff_code')->one();
        $staff = CrmEmployee::find()->where(['staff_code' => $staffcode['staff_code']])->select('isrule,leader_id,sale_area,sts_id')->one();
        try {
            /*客户信息*/
            $crmCustomerInfo->load(Yii::$app->request->post());
            $custRegname = $post['CrmCustomerInfo']['cust_regname'];
            $custRegnumber = $post['CrmCustomerInfo']['cust_regname'];
            $crmCustomerInfo->cust_regname = serialize($custRegname);
            $crmCustomerInfo->cust_regnumber = serialize($custRegnumber);

            if (!$crmCustomerInfo->save()) {
                throw new \Exception("新增客户信息失败" . Json::encode($crmCustomerInfo->getErrors()));
            }

            $custId = $crmCustomerInfo->cust_id;
            /*状态*/
            $status = new CrmCustomerStatus();
            $status->customer_id = $custId;
            $status->sale_status = CrmCustomerStatus::STATUS_DEFAULT;
            if (!$status->save()) {
                throw new \Exception("新增客户信息失败" . Json::encode($status->getErrors()));
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
                            throw  new \Exception("新增联系人失败" . Json::encode($crmCustPersion->getErrors()));
                        };
                    }
                }
            }
            /*认领信息
            *如果添加人是客户经理人默认认领
            */
            if ($staff['isrule'] == 1) {
                $personinch = new CrmCustPersoninch();
                //默认查询有效客户经理人
                $personinch->ccpich_personid = $staffId;
                $personinch->ccpich_personid2 = $staffId;
                $personinch->csarea_id = $staff["sale_area"];
                $personinch->sts_id = $staff["sts_id"];
                $personinch->cust_id = $custId;
                $personinch->ccpich_date = date('Y-m-d', time());
                $personinch->ccpich_status = CrmCustPersoninch::STATUS_DEFAULT;
                $personinch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;

                if (!$personinch->save()) {
                    throw new \Exception("新增认领信息失败" . Json::encode($personinch->getErrors()));
                } else {
                    $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_YES;
                    if (!$crmCustomerInfo->save()) {
                        throw new \Exception("新增客户信息失败" . Json::encode($crmCustomerInfo->getErrors()));
                    }
                }

            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success();

    }

    public function actionUpdate($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $crmCustomerInfo = $this->getModel($id);
        $post = Yii::$app->request->post();
        try {
            /*客户信息*/
            $crmCustomerInfo->load($post);
            if (!$crmCustomerInfo->save()) {
                throw new \Exception("修改客户信息失败");
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
//                    return ActiveForm::validate($crmCustPersion);
                        if (!$crmCustPersion->save()) {
                            throw  new \Exception("更新联系人失败");
                        };
                    }
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success();
    }

    public function actionDelete($id)
    {
        $model = $this->getStatusModel($id);
        $model->sale_status = CrmCustomerStatus::STATUS_DEL;
        if ($result = $model->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function actionPersoninch($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $staffId = Yii::$app->request->queryParams['staffId'];
        $staffcode = HrStaff::find()->where(['staff_id' => $staffId])->select('staff_code')->one();
        $staff = CrmEmployee::find()->where(['staff_code' => $staffcode['staff_code']])->select('isrule,leader_id,sale_area,sts_id')->one();
        if ($staff["isrule"] != 1) {
            return $this->error("非客户经理人不能认领");
        }
        try {
            $crmCustomerInfo = $this->getModel($id);
            /*认领信息
            *如果添加人是客户经理人默认认领
            */
            $personinch = new CrmCustPersoninch();
            //默认查询有效客户经理人
            $personinch->ccpich_personid = $staffId;
            $personinch->ccpich_personid2 = $staffId;
            $personinch->csarea_id = $staff["sale_area"];
            $personinch->sts_id = $staff["sts_id"];
            $personinch->cust_id = $id;
            $personinch->ccpich_date = date('Y-m-d', time());
            $personinch->ccpich_status = CrmCustPersoninch::STATUS_DEFAULT;
            $personinch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;

            if (!$personinch->save()) {
                throw new \Exception("认领信息失败" . Json::encode($personinch->getErrors()));
            } else {
                $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_YES;
            }
            if (!$crmCustomerInfo->save()) {
                throw new \Exception("认领信息失败");
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success();
    }

    /*下拉菜单*/
    public function actionDownList()
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

    /**
     * AJAX獲取地址子类
     * @param $id
     * @return string
     */
    public function actionGetDistricts($id)
    {
        return BsDistrict::getChildByParentId($id);
    }

    /*根据地址五级查出所有信息*/
//    public function actionGetAllDistrict($id)
//    {
//        $disId = BsDistrict::find()->where(['district_id' => $id])->one();
//        $name = $disId['district_name'];
//        $dis1 = BsDistrict::find()->where(['district_id' => $disId['district_pid']])->one();
//        $dis2 = BsDistrict::find()->where(['district_id' => $dis1['district_pid']])->one();
//        $dis3 = BsDistrict::find()->where(['district_id' => $dis2['district_pid']])->one();
//        $dis4 = BsDistrict::find()->where(['district_id' => $dis3['district_pid']])->one();
////        $disName = $dis4['district_name'].$dis3['district_name'].$dis2['district_name'].$dis1['district_name'].$disId['district_name'];
//        $arr = [$dis3, $dis2, $dis1, $disId];
//        return $arr;
//    }
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

    /*获取下一级地址*/
    public function actionGetChildDistrict($id)
    {
        $dis = BsDistrict::getChildByParentId($id);
        return $dis;
    }

    //获取一条等级数据
    public function getLevel($id)
    {
        return BsDistrict::findOne($id);
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

    public function actionSalearea()
    {
        return CrmSalearea::getSalearea();
    }

    public function actionIndustryType()
    {
        return BsIndustrytype::getIndustryType();
    }

    /*获取客户信息*/
//    public function actionGetCustOne($id)
//    {
//        $result = CrmCustomerInfoShow::getOneInfo($id, 'cust_id,cust_sname,cust_adress');
//        return $result;
//    }

    public function actionModels($id)
    {
        $result = CustomerAppShow::getCustomerInfoOne($id);
        return $result;
    }

    public function actionSelectCustomer()
    {
        return CrmCustomerInfo::find()->all();

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
     * @param $id
     * @return mixed
     * 联系人列表
     */
    public function actionContactPerson($id)
    {
        $searchModel = new CrmCustomerPersionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * 设备列表
     */
    public function actionCustDevice($id)
    {
        $searchModel = new CrmCustDeviceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * 主要产品列表
     */
    public function actionCustMainProduct($id)
    {
        $searchModel = new CrmCustProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * 主要客户列表
     */
    public function actionCustMainCustomer($id)
    {
        $searchModel = new CrmCustCustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
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


}