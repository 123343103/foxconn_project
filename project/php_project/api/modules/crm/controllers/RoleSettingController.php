<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\tools\CheckUsed;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\hr\models\show\HrStaffShow;
use app\modules\sale\models\search\SellerSearch;
use app\modules\crm\models\search\CrmSaleRolesSearch;
use app\modules\crm\models\CrmSaleRoles;
use app\modules\crm\models\show\CrmSaleRolesShow;
use app\modules\sale\models\show\StoreSettingShow;
use yii;
use app\modules\crm\models\CrmSalearea;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use yii\helpers\Html;
class RoleSettingController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmSaleRoles';
    public function actionIndex()
    {
        $model = new CrmSaleRolesSearch();
        $dataProvider = $model->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    //创建销售点
    public function actionCreate()
    {
        $role = new CrmSaleRoles();
        if ($role->load(Yii::$app->request->post()) && $role->save()) {
            return $this->success('创建成功');
        } else {
            return $this->error($role->errors);
        }
    }

    // 更新店铺
    public function actionUpdate($id){
        $model = CrmSaleRoles::findOne($id);
        $post = Yii::$app->request->post();
        try{
            $model->load($post);
            if(!$model->save()){
                throw new \Exception("修改信息失败");
            }
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
        return $this->success();
    }
    //启用状态
    public function actionEnableAttr($id)
    {
        $model = CrmSaleRoles::findOne($id);
        $model->sarole_status =CrmSaleRoles::STATUS_DEFAULT;
        if ($model->save()) {
            return $this->success('已启用！');
        }
        return $this->error('未知错误！');
    }
    //禁用状态
    public function actionDisableAttr($id)
    {
        $model = CrmSaleRoles::findOne($id);
        $model->sarole_status = CrmSaleRoles::STATUS_FORBIDDEN;
        if ($model->save()) {
            return $this->success('已禁用！');
        }
        return $this->error('未知错误！');
    }
    //批量设置状态
    public function actionSetstatus(){
        $post = Yii::$app->request->post();
        $arr=explode(",", $post['id']);
        $status=$post["sarole_status"];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($arr as $key => $val) {
                $model = CrmSaleRoles::findOne($val);
                if($status==1){
                    $model->sarole_status = CrmSaleRoles::STATUS_DEFAULT;
                }else{
                    $model->sarole_status = CrmSaleRoles::STATUS_FORBIDDEN;
                }
                if (!($model->save())) {
                    throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }

    }
    public function actionSaleInfo($id){
        $arr = explode(',',$id);
        $name="";
        foreach ($arr as $key => $val){
            //$sql='select e.staff_code,s.staff_name from erp.crm_employee e left join erp.hr_staff s on e.staff_code=s.staff_code where e.staff_id=:id';
            $saleInfo = CrmSaleRoles::findOne($val);
            $name = $name.$saleInfo["sarole_code"].'('.$saleInfo["sarole_sname"].')'.';';
        }
        return $name;
    }
    // 删除销售点（店铺）
    public function actionDeleteRole($id)
    {
        $arr = explode(',',$id);
        $name = '';
        foreach ($arr as $key => $val){
            $checkUsed = new CheckUsed();
            $used = $checkUsed->check($val,'sarole_id');
            if ($used['status'] == 0) {
                return $this->error($used['msg']);
            }
            $model = CrmSaleRoles::findOne($val);
            $model->sarole_status = CrmSaleRoles::STATUS_DEL;
            $model->sarole_code = Html::decode($model['sarole_code']);
            $model->sarole_sname = Html::decode($model['sarole_sname']);
            $model->sarole_remark = Html::decode($model['sarole_remark']);
            $model->sarole_desription = Html::decode($model['sarole_desription']);
            $name = $name.$model["sarole_sname"].',';
            $result = $model->save();
        }
        $sarole_sname = trim($name,',');
        if ($result) {
            $msg = array('id' => $id, 'msg' => '删除销售角色"' . $sarole_sname . '"');
            return $this->success('',$msg);
        } else {
            return $this->error();
        }
//        $model = CrmSaleRoles::findOne($id);
//        $model->sarole_status = CrmSaleRoles::STATUS_DEL;
//        $model->sarole_code = Html::decode($model['sarole_code']);
//        $model->sarole_sname = Html::decode($model['sarole_sname']);
//        $model->sarole_remark = Html::decode($model['sarole_remark']);
//        $model->sarole_desription = Html::decode($model['sarole_desription']);
//        if ($model->save()) {
//            $msg = array('id'=>$id,'msg'=>'删除销售角色"'.$model["sarole_sname"].'"');
//            return $this->success('',$msg);
//        } else {
//            return $this->error();
//        }
    }

    //查看销售点
    public function actionView($id)
    {
        return CrmSaleRolesShow::findOne($id);
    }

    /*获取销售人力类型*/
    public function actionEmployeeType()
    {
        $employeeType = BsPubdata::getList(BsPubdata::CRM_EMPLOYEE_TYPE);  //销售点状态
        return $employeeType;
    }

    /*获取军区列表*/
    public function actionSalearea()
    {
        return CrmSalearea::getSalearea();
    }

    public function actionSelectSeller(){
        $seller = new SellerSearch();
        $dataProvider = $seller->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /*工号获取员工信息*/
    public function actionGetSeller($code)
    {
        $model = HrStaffShow::getStaffByIdCode($code);
        return $model;
    }

    public function actionGetCountry()
    {
        return BsDistrict::getDisLeveOne();
    }

    public function actionGetParentDistricts($id)
    {
        $model = new BsDistrict();
        return $model->getParentsById($id);
    }

}
