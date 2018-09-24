<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\tools\CheckUsed;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\crm\models\show\CrmEmployeeShow;
use app\modules\hr\models\HrStaff;
use app\modules\hr\models\show\HrStaffShow;
use app\modules\sale\models\search\SellerSearch;
use app\modules\sale\models\search\StoreSettingSearch;
use app\modules\sale\models\show\StoreSettingShow;
use yii;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmEmployee;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;

class StoreSettingController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmStoresinfo';
    public function actionIndex()
    {
        $model = new StoreSettingSearch();
        $dataProvider = $model->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    //创建销售点
    public function actionCreate()
    {
        $storeSave = new CrmStoresinfo();
        if ($storeSave->load(Yii::$app->request->post()) && $storeSave->save()) {
            $id=$storeSave->sts_id;
            return $this->success('创建成功',$id);
        } else {
            return $this->error('创建失败');
        }
    }

    // 更新店铺
    public function actionUpdate($id){
        $model = CrmStoresinfo::findOne($id);
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
    //批量设置状态
    public function actionSetstatus(){
        $post = Yii::$app->request->post();
        $arr=explode(",", $post['id']);
        $status=$post["sts_status"];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($arr as $key => $val) {
                $model = CrmStoresinfo::findOne($val);
                $model->sts_status = $status;
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
//        $result = array();
//        $info=array();
        foreach ($arr as $key => $val){
            $saleInfo = CrmStoresinfo::findOne($val);
            $name = $name.$saleInfo["sts_code"].'('.$saleInfo["sts_sname"].')'.';';
        }
//        $info=explode(';',$name);
//        foreach ($info as $key => $value) {
//            if ('' != ($value = trim($value))) {
//                $result[] = $value;
//            }
//        }
//        return $result;
//        //  $info=str_split($name);
//      //  return $info;
        return $name;
    }
//    // 删除销售点（店铺）
//    public function actionDeleteStore($id)
//    {
//
//        $arr = explode(',',$id);
//        $name = '';
//        foreach ($arr as $key => $val){
//            $checkUsed = new CheckUsed();
//            $used = $checkUsed->check($val,'sts_id');
//            if ($used['status'] == 0) {
//                return $this->error($used['msg']);
//            }
//            $model = CrmStoresinfo::findOne($val);
//            $model->sts_status = CrmStoresinfo::STATUS_DELETE;
//            $name = $name.$model["sts_sname"].',';
//            $result = $model->save();
//        }
//        $staff_code = trim($name,',');
//        if ($result) {
//            $msg = array('id' => $id, 'msg' => '删除销售点"' . $staff_code . '"');
//            return $this->success('',$msg);
//        } else {
//            return $this->error();
//        }

//    }

    //查看销售点
    public function actionView($id)
    {
        return StoreSettingShow::findOne($id);
    }

    /*获取销售点状态*/
    public function actionStoreStatus()
    {
        $storeStatus['storeStatus'] = [
            CrmStoresinfo::STATUS_PREPARE => '筹备中',
            CrmStoresinfo::STATUS_NORMAL => '已营业',

//            CrmStoresinfo::STATUS_SHUTOUT => '已停业',
            CrmStoresinfo::STATUS_SHUTUPSTORE => '已歇业',
            CrmStoresinfo::STATUS_PAUSE => '已暂停',
            CrmStoresinfo::STATUS_CLOSE => '已关闭',
//            CrmStoresinfo::STATUS_DELETE => '已删除',
        ];  //销售点状态
        return $storeStatus;
    }

    /*获取军区列表*/
    public function actionSalearea()
    {
        return CrmSalearea::getSalearea();
    }


    /*工号获取员工信息*/
    public function actionGetSeller()
    {
        $model = (new yii\db\Query())->select([
            HrStaff::tableName().'.staff_code',
            HrStaff::tableName().'.staff_id',
            HrStaff::tableName().'.staff_name'
        ])->from(CrmEmployee::tableName())
            ->leftJoin(HrStaff::tableName(),HrStaff::tableName().'.staff_code='.CrmEmployee::tableName().'.staff_code')
            ->where(['=',CrmEmployee::tableName().'.sale_status',CrmEmployee::SALE_STATUS_DEFAULT])->all();
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
