<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsPubdata;
use app\modules\common\tools\CheckUsed;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\crm\models\show\CrmEmployeeShow;
use app\modules\crm\models\show\CrmStoresinfoShow;
use app\modules\hr\models\HrStaff;
use app\modules\hr\models\show\HrStaffShow;
use app\modules\crm\models\search\CrmEmployeeSearch;
use yii;
use app\modules\ptdt\models\BsCategory;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmSaleRoles;
use app\modules\crm\models\CrmEmployee;

class EmployeeSettingController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmEmployee';
    public function actionIndex()
    {
        $model = new CrmEmployeeSearch();
        $dataProvider = $model->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        if(!empty($model)){
            foreach($model as $key =>$lists){
                $lists['category_id']=unserialize($lists['category_id']);
                if(!empty($lists['category_id'])){
                    $list['category_id']=BsCategory::find()->select('catg_name')->where(['in','catg_no',$lists['category_id']])->all();//注意此处的查询语句
                    $categoryName='';
                    foreach($list['category_id'] as $row){
                        $categoryName.=$row['catg_name'].',';
                    }
                    $model[$key]['category_id']=rtrim($categoryName,',');
                }
            }
        }
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    //创建销售点
    public function actionCreate()
    {
        $employee = new CrmEmployee();
        $post = Yii::$app->request->post();
        if ($post) {
            $employee->load($post);
            $employee->create_by = $post['CrmEmployee']['create_by'];
            $id=$employee->staff_code;
            $employee->save();
            $msg = array('id'=>$id);
            return $this->success('创建成功',$msg);
        } else {
            return $this->error($employee->errors);
        }
    }

    // 更新销售员
    public function actionUpdate($id){
        $model = CrmEmployee::find()->where(['and',["staff_code"=>$id],['!=','sale_status',CrmEmployee::SALE_STATUS_DEL]])->one();
        $post = Yii::$app->request->post();
        if (!empty($model)) {
            $model->load($post);
            $model->update_by = $post['CrmEmployee']['update_by'];
            $model->save();
            $msg = array('id'=>$id);
            return $this->success('创建成功',$msg);
        } else {
            return $this->error($model->errors);
        }
    }
    //启用状态
    public function actionEnableAttr($id)
    {
        $model = CrmEmployee::findOne($id);
        $model->sale_status =CrmEmployee::SALE_STATUS_DEFAULT;
        if ($model->save()) {
            return $this->success('已启用！');
        }
        return $this->error('未知错误！');
    }
    //禁用状态
    public function actionDisableAttr($id)
    {
        $model = CrmEmployee::findOne($id);
        $model->sale_status = CrmEmployee::SALE_STATUS_OUT;
        if ($model->save()) {
            return $this->success('已禁用！');
        }
        return $this->error('未知错误！');
    }
    public function actionSaleInfo($id){
        $arr = explode(',',$id);
        $name="";
        foreach ($arr as $key => $val){
            $sql='select e.staff_code,s.staff_name from erp.crm_employee e left join erp.hr_staff s on e.staff_code=s.staff_code where e.staff_id=:id';
            $saleInfo = Yii::$app->db->createCommand($sql)->bindValue(':id', $val)->queryOne();
            $name = $name.$saleInfo["staff_code"].'('.$saleInfo["staff_name"].')'.';';
        }
        return $name;
    }
    //批量设置状态
    public function actionSetstatus(){
        $post = Yii::$app->request->post();
        $arr=explode(",", $post['id']);
        $status=$post["sale_status"];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($arr as $key => $val) {
                $model = CrmEmployee::findOne($val);
                if($status==1){
                    $model->sale_status = CrmEmployee::SALE_STATUS_DEFAULT;
                }else{
                    $model->sale_status = CrmEmployee::SALE_STATUS_OUT;
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
    //删除销售员
    public function actionDelete($id)
    {
//        $checkUsed = new CheckUsed();
//        $used = $checkUsed->check($id,'sale_status');
//        if ($used['status'] == 0) {
//            return $this->error($used['msg']);
//        }
        $arr = explode(',',$id);
        $name = '';
        foreach ($arr as $key => $val){
            $model = $model = CrmEmployee::find()->where(['and',["staff_code"=>$val],['!=','sale_status',CrmEmployee::SALE_STATUS_DEL]])->one();
            $model->sale_status = CrmEmployee::SALE_STATUS_DEL;
            $name = $name.$model["staff_code"].',';
            $result = $model->save();
        }
        $staff_code = trim($name,',');
        if ($result) {
            $msg = array('id' => $id, 'msg' => '删除销售员"' . $staff_code . '"');
            return $this->success('',$msg);
        } else {
            return $this->error();
        }
//        $model = CrmEmployee::find()->where(['and',["staff_code"=>$id],['!=','sale_status',CrmEmployee::SALE_STATUS_DEL]])->one();
//        $model->sale_status = CrmEmployee::SALE_STATUS_DEL;
//        if ($model->save()) {
//            $msg = array('id'=>$id,'msg'=>'删除销售员"'.$model["staff_code"].'"');
//            return $this->success('',$msg);
//        } else {
//            return $this->error();
//        }
    }

    public function actionDownList(){
        $downList['roles'] = CrmSaleRoles::find()->select(['sarole_id', 'sarole_sname'])->where(['=','sarole_status',CrmSaleRoles::STATUS_DEFAULT])->asArray()->all();//销售角色
        $downList['saleArea'] = CrmSalearea::getSalearea();//营销区域
        $downList['store'] = CrmStoresinfo::find()->select(['sts_id','sts_sname'])->where(['!=','sts_status',CrmStoresinfo::STATUS_DELETE])->asArray()->all();//销售点
        $downList['leader'] = CrmEmployee::find()->joinWith('staffName')->select(['crm_employee.staff_id','crm_employee.staff_code','hr_staff.staff_name'])->where(['!=','sale_status',CrmEmployee::SALE_STATUS_DEL])->andWhere(['!=','sale_status',CrmEmployee::SALE_STATUS_OUT])->asArray()->all();//对应上司
        $downList['sarole_type'] = BsPubdata::getList(BsPubdata::CRM_EMPLOYEE_TYPE);
        return $downList;
    }
    /*获取销售点状态*/
    public function actionStore($id)
    {
        $list = CrmStoresinfo::find()->select("sts_id,sts_sname")->where(['csarea_id'=>$id ])->andWhere(['or',['=','sts_status',CrmStoresinfo::STATUS_PREPARE],['=','sts_status',CrmStoresinfo::STATUS_NORMAL]])->asArray()->all();
        return $list;
    }
    /*获取销售点*/
    public function actionStoreById($id)
    {
        return CrmStoresinfo::findOne($id);
    }
    /*销售角色带出人力类型*/
    public function actionSaleRole($id)
    {
        $sql='SELECT b.bsp_svalue FROM erp.crm_sale_roles c left join erp.bs_pubdata b on c.sarole_type=b.bsp_id where c.sarole_id=:id';
        $saleroleInfo = Yii::$app->db->createCommand($sql)->bindValue(':id', $id)->queryOne();
        if($saleroleInfo!=null){
            return $saleroleInfo;
        }else {
            return "";
        }
        //return CrmSaleRoles::findOne($id);
    }
    /*获取军区列表*/
    public function actionSalearea()
    {
        return CrmSalearea::getSalearea();
    }
    /*获取角色列表*/
    public function actionRoles()
    {
        return CrmSaleRoles::find()->select(['sarole_id', 'sarole_sname'])->asArray()->all();
    }
    /*销售点带出上司省长*/
    public function actionStoreInfo($id){
        return CrmStoresinfoShow::find()->where(['and',['sts_id'=>$id],['!=','sts_status',CrmStoresinfo::STATUS_DELETE]])->one();
    }
    /*上司角色*/
    public function actionLeaderRole($id){
        $a = CrmEmployee::find()->where(['staff_id'=>$id])->select(['sarole_id'])->one();
        return CrmSaleRoles::find()->where(['sarole_id'=>$a['sarole_id']])->select(['sarole_sname','sarole_id'])->one();
    }

    /*工号获取员工信息*/
    public function actionGetStaffInfo($code)
    {
        $model = HrStaffShow::getStaffByCode($code);
        return $model;
    }
    /*获取上司*/
    public function actionLeader()
    {
        $model = new CrmEmployeeSearch();
        $dataProvider = $model->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        return $model;
    }
    /**
     * 獲取分級分類信息(一级分类)
     * @return array|yii\db\ActiveRecord[]
     */
    public function actionCategory(){
//        return PdProductType::getLevelOne();
        return BsCategory::getLevelOne();
    }

    public function actionModels($id)
    {
        return CrmEmployeeShow::find()->where(['and',["staff_code"=>$id],['!=','sale_status',CrmEmployee::SALE_STATUS_DEL]])->one();
    }
    public function actionOrgname($id){
        $info = HrStaff::getStaffByIdCode($id);
        return $info;
    }

}
