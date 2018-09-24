<?php
/**
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2016/12/13
 * Time: 11:19
 */

namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\models\User;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmVisitRecord;
use app\modules\crm\models\CrmVisitRecordChild;
use app\modules\crm\models\search\CrmCustomerInfoSearch;
use app\modules\crm\models\show\CrmVisitPlanCountShow;
use app\modules\hr\models\HrStaff;
use app\modules\system\models\AuthItem;
use Yii;
use app\modules\crm\models\CrmVisitPlan;

class CrmPlanManageController extends BaseActiveController
{

    public $modelClass='app\modules\crm\models\CrmCustomerInfo';

    /**
     * 新增拜访计划
     * @return array
     */
    public function actionCreatePlan()
    {
        $model=new CrmVisitPlan();
        $post=Yii::$app->request->post();
        $model->load($post);
        $result=$model->save();
        if(!$result){
            return $this->error();
        }
        return $this->success();

    }

    /**
     * 新增拜访记录，临时拜访
     * @return array
     */
    public function actionCreateInfo()
    {
        $child = new CrmVisitRecordChild();
        $post=Yii::$app->request->post();
        $model=CrmVisitRecord::find()->where(['cust_id'=>intval($post['CrmVisitInfo']['cust_id'])])->andWhere(['<>','sih_status',CrmVisitRecord::STATUS_DELETE])->one();
        $model=!empty($model) ? $model : new CrmVisitRecord();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->load($post);
            $model->save();
            $sihId=$model->sih_id;
            $child->load($post);
            $child->sih_id=$sihId;
            $child->save();
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
             $transaction->rollBack();
            return $this->error($e->getMessage());
        }
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

    /**
     * 计划 记录 数据
     * @return array
     */
    public function actionPlanData($staff,$user){
        $select="title,start,end,editable,color,type";
        $staffInfo=HrStaff::getStaffById($staff);
        $userModel=User::findByUsername($user);
        $planWhere=['svp_staff_code'=>$staffInfo['staff_code']];
        $infoWhere=['sil_staff_code'=>$staffInfo['staff_code']];
        if(!empty($userModel->is_supper) && $userModel->is_supper){
            $planWhere='';
            $infoWhere='';
        }

        $visitPlan=CrmVisitPlan::find()->where(['<>','svp_status',CrmVisitPlan::STATUS_DELETE])->andWhere($planWhere)->select("svp_id,$select")->all();
        $visitInfo=CrmVisitRecordChild::find()->where(['<>','sil_status',CrmVisitPlan::STATUS_DELETE])->andWhere($infoWhere)->andWhere(['in','type',[CrmVisitRecordChild::TYPE_RECORD,CrmVisitRecordChild::TYPE_LINSHI]])->select("sil_id,$select")->all();

        $data=array_merge($visitPlan,$visitInfo);
        //删除错误数据
        foreach ($data as $key=>$value){
            if($value['start']==null || $value['end']==null){
                unset($data[$key]);
            }
        }
        return array_merge($data);
    }

    /*下拉菜单*/
    public function actionDownList(){
        $downList['visitType'] = BsPubdata::getList(BsPubdata::CRM_VISIT_TYPE);  //经营类型
        return $downList;
    }

    // 统计行程计划数据
    public function actionPlanCount()
    {
        $param = Yii::$app->request->post();
        $model = new CrmVisitPlanCountShow();
        if (!empty($param['start']) && !empty($param['end']) && !empty($param['staffCode'])) {
            $model = $model->PlanCountSearch($param['start'],$param['end'],$param['staffCode'],$param['isSupper'])->getModels();
        }
        return $model;
    }

    /**
     * @return mixed
     * 新增客户下拉列表
     */
    public function actionCreateDownList($code){
        //公司规模
        $downList['companyScale'] = BsPubdata::getList(BsPubdata::CRM_COMPANY_SCALE);
        //客户等级
        $downList['custLevel'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_LEVEL);
        //客户经理人
        $downList['manager'] = CrmEmployee::getManagerInfo();
        //默认客户经理人
        $downList['managerDefault'] = CrmEmployee::getManagetRelation($code);
        //需求类目
        $downList['productType'] = BsCategory::getLevelOne();
        //所在地区
        $downList['district'] = BsDistrict::getDisProvince();
        //所在军区
        $downList['salearea'] = CrmSalearea::getSalearea();
        //客户类型
        $downList['customerType'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_TYPE);
        //会员类别
//        $downList['memberType'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_CLASS);
        //会员等级
        $downList['memberLevel'] = BsPubdata::getList(BsPubdata::CRM_MEMBER_LEVEL);
        //注册网站
//        $downList['regWeb'] = BsPubdata::getList(BsPubdata::CRM_REGISTER_WEB);
        //客户来源
        $downList['customerSource'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_SOURCE);
        //经营模式
//        $downList['managementType'] = BsPubdata::getList(BsPubdata::CRM_MANAGEMENT_TYPE);
        //公司类型
//        $downList['property'] = BsPubdata::getList(BsPubdata::CRM_COMPANY_PROPERTY);
        //币别
        $downList['tradeCurrency'] = BsPubdata::getList(BsPubdata::SUPPLIER_TRADE_CURRENCY);
        //潜在需求
        $downList['latentDemand'] = BsPubdata::getList(BsPubdata::CRM_LATENT_DEMAND);
        //需求类目
        $downList['productType']= BsCategory::getLevelOne();
        $downList['country'] = BsDistrict::getDisLeveOne();//所在国家
        $downList['visitType'] = BsPubdata::getData(BsPubdata::CRM_VISIT_TYPE);//拜访类型
        return $downList;
    }
}
