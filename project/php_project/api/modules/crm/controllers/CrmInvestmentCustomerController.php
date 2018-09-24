<?php
/**
 * 招商客户列表
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2017/2/13
 * Time: 9:16
 */

namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmActImessage;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustomerPersion;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmCustShop;
use app\modules\crm\models\CrmImessage;
use app\modules\crm\models\CrmMchpdtype;
use app\modules\crm\models\CrmVisitRecord;
use app\modules\crm\models\search\CrmCustomerInfoSearch;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\ptdt\models\BsCategory;
use app\modules\crm\models\show\CrmVisitRecordShow;
use Yii;

class CrmInvestmentCustomerController extends BaseActiveController
{

    public $modelClass = 'app\modules\crm\models\CrmInvestmentCustomer';

    /**
     * 列表
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new CrmCustomerInfoSearch();
        $dataProvider = $searchModel->searchInvestmentCustomer(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 创建
     * @return array
     */
    public function actionCreate(){
        $post=Yii::$app->request->post();
        if(CrmCustomerInfo::findOne(['cust_sname'=>$post['CrmCustomerInfo']['cust_sname']])){
            return $this->error("客户已存在");
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model=new CrmCustomerInfo();
            $model->load($post);
            if (!$model->save()) {
                throw new \Exception("新增客户信息失败");
            }
            /*状态*/
            $status = new CrmCustomerStatus();
            $status->customer_id = $model->cust_id;;
            if($model->cust_ismember){
                $status->member_status = CrmCustomerStatus::STATUS_DEFAULT;
            }
            $status->investment_status = CrmCustomerStatus::STATUS_DEFAULT;
            if (!$status->save()) {
                throw new \Exception("新增客户信息失败");
            }
            $transaction->commit();
            return $this->success($model->cust_sname);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 更新
     * @param $id
     * @return array
     */
    public function actionUpdate($id){
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post=Yii::$app->request->post();
            $model=CrmCustomerInfo::findOne(['cust_id'=>$id]);
            if(!$model->cust_ismember){
                $status = CrmCustomerStatus::findOne(['customer_id'=>$id]);
                $status->customer_id = $model->cust_id;
                $status->member_status = CrmCustomerStatus::STATUS_DEL;
                if (!$status->save()) {
                    throw new \Exception("修改客户信息失败");
                }
            }
            if(isset($post["CrmCustomerPersion"])){
                CrmCustomerPersion::deleteAll(["cust_id"=>$model->primaryKey]);
                for($x=0;$x<count($post["CrmCustomerPersion"]["ccper_name"]);$x++){
                    $personModel=new CrmCustomerPersion();
                    $attrs=array_combine(array_keys($post["CrmCustomerPersion"]),array_column($post["CrmCustomerPersion"],$x));
                    $attrs["cust_id"]=$model->primaryKey;
                    $personModel->setAttributes($attrs);
                    $personModel->ccper_ismain = '0';
                    if(!($personModel->validate() && $personModel->save())){
                        throw new \Exception("联系人信息保存失败");
                        break;
                    }
                }
            }
            $model->load($post);
            if (!$model->save()) {
                throw new \Exception("修改客户信息失败");
            }
                $transaction->commit();
                return $this->success($model->cust_sname);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error();
        }
    }

    public function actionDelete($id){
        $model=CrmCustomerStatus::findOne($id);
        $model->investment_status=CrmCustomerStatus::STATUS_DEL;
        $customer=CrmCustomerInfo::findOne($id);
        if($model->save()){
            return $this->success($customer->cust_sname);
        }
        return $this->error($customer->cust_sname);
    }


    /**
     * 点击加载
     */
    public function actionLoadInfo($id){
        $condition=['cust_id'=>$id];
        $data['record']   = $this->loadRecord($condition);
        $data['shop']     = $this->loadShop($condition);
        $data['messages'] = $this->LoadMessage($condition);
        $data['reminder'] = $this->LoadReminder($condition);
        return $data;
    }


    public function actionDownList(){
        //会员类别
        $downList['memberType'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_CLASS);
        //注册网站
        $downList['regWeb'] = BsPubdata::getList(BsPubdata::CRM_REGISTER_WEB);
        //客户来源
        $downList['customerSource'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_SOURCE);
        //经营模式
        $downList['businessModel'] = BsPubdata::getList(BsPubdata::CRM_MANAGEMENT_TYPE);
        //公司类型
        $downList['property'] = BsPubdata::getList(BsPubdata::CRM_COMPANY_PROPERTY);
        //币别
        $downList['tradeCurrency'] = BsPubdata::getList(BsPubdata::SUPPLIER_TRADE_CURRENCY);
        //潜在需求
        $downList['latentDemand'] = BsPubdata::getList(BsPubdata::CRM_LATENT_DEMAND);
        //需求类目
        $downList['productType']=BsCategory::getLevelOne();
        //国家
        $downList['country'] = BsDistrict::getDisLeveOne();//所在国家
        return $downList;
    }

    /*
     * 拜访信息
     */
    protected function loadRecord($condition){
        $visit=CrmVisitRecord::find()->where($condition)->one();
        $recordList=CrmVisitRecordShow::find()->where(['sih_id'=>$visit['sih_id']])->orderBy("create_at DESC")->all();
        $list["rows"] = $recordList;
        $list["total"] = count($recordList);
        return $list;
    }

    /**
     * 开店信息
     * @param $condition
     * @return mixed
     */
    protected function loadShop($condition){
        $shop=CrmCustShop::getByAll($condition);
        $list["rows"] = $shop;
        $list["total"] = count($shop);
        return $list;
    }
    /**
     * @param $id
     * @return mixed
     * 通讯记录
     */
    protected function LoadMessage($condition){
        $model=CrmActImessage::getActImessages($condition);
        $list["rows"] = $model;
        $list["total"] = count($model);
        return $list;
    }
    /**
     * @param $id
     * @return mixed
     * 提醒事项列表
     */
    protected function LoadReminder($condition){
        $model=CrmImessage::getIMessages($condition);
        $list["rows"] = $model;
        $list["total"] = count($model);
        return $list;
    }

    public function actionModels($id){
        $info=CrmCustomerInfoShow::findOne(['cust_id'=>$id]);
        return $info;
    }

    protected function model($id){
        return CrmCustomerInfo::findOne(['cust_id'=>$id]);
    }
}