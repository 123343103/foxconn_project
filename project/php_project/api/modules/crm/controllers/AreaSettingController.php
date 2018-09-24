<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\tools\CheckUsed;
use app\modules\crm\models\CrmDistrictSalearea;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\hr\models\show\HrStaffShow;
use app\modules\sale\models\search\SellerSearch;
use app\modules\crm\models\search\SaleAreaSearch;
use app\modules\sale\models\show\StoreSettingShow;
use yii;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\show\SaleAreaShow;
use app\modules\common\models\BsDistrict;
use yii\helpers\Html;
class AreaSettingController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmSalearea';

    public function actionIndex()
    {
        $model = new SaleAreaSearch();
        $dataProvider = $model->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    //创建销售区域
    public function actionCreate()
    {
        $area = new CrmSalearea();
        $post=Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $area->load($post);
            if(!$area->save()){
                throw new \Exception("新增销售区域失败");
            }
            $csareaId = $area->csarea_id;
            $district = array_filter($post['CrmDistrictSalearea']['district_id']);
            $city = $post['CrmDistrictSalearea']['city_id'];
            foreach ($district as $key => $item) {
                    $disArea = new CrmDistrictSalearea();
                    $disArea->csarea_id = $csareaId;
                    $disArea->district_id = $item;
                    $disArea->city_id = $city[$key];
                    if(!$disArea->save()){
                        throw  new \Exception("新增失败");
                    };
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success('',$csareaId);
    }

    // 更新销售区域
    public function actionUpdate($id)
    {
        $area = CrmSalearea::findOne($id);
        $post=Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $area->load($post);
            if(!$area->save()){
                throw new \Exception("新增销售区域失败");
            }
            $csareaId = $area->csarea_id;
            $count = CrmDistrictSalearea::find()->where(['csarea_id' => $id])->count();
            if (CrmDistrictSalearea::deleteAll(['csarea_id' => $id]) < $count) {
                throw  new \Exception("删除失败");
            };
            $district = array_filter($post['CrmDistrictSalearea']['district_id']);
            $city = $post['CrmDistrictSalearea']['city_id'];
            foreach ($district as $key => $item) {
                $disArea = new CrmDistrictSalearea();
                $disArea->csarea_id = $csareaId;
                $disArea->district_id = $item;
                $disArea->city_id = $city[$key];
                if(!$disArea->save()){
                    throw  new \Exception("新增失败");
                };
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success();
    }
    //启用状态
    public function actionEnableAttr($id)
    {
        $model = CrmSalearea::findOne($id);
        $model->csarea_status =CrmSalearea::STATUS_DEFAULT;
        if ($model->save()) {
            return $this->success('已启用！');
        }
        return $this->error('未知错误！');
    }
    //禁用状态
    public function actionDisableAttr($id)
    {
        $model = CrmSalearea::findOne($id);
        $model->csarea_status = CrmSalearea::STATUS_STOP;
        if ($model->save()) {
            return $this->success('已禁用！');
        }
        return $this->error('未知错误！');
    }
//批量设置状态
    public function actionSetstatus(){
        $post = Yii::$app->request->post();
        $arr=explode(",", $post['id']);
        $status=$post["csarea_status"];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($arr as $key => $val) {
                $model = CrmSalearea::findOne($val);
                $model->csarea_status = $status;
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
            $saleInfo = CrmSalearea::findOne($val);
            $name = $name.$saleInfo["csarea_code"].'('.$saleInfo["csarea_name"].')'.';';
        }
        return $name;
    }
    // 删除销售区域
    public function actionDelete($id)
    {

        $arr = explode(',',$id);
        $name = '';
        foreach ($arr as $key => $val){
            $checkUsed = new CheckUsed();
            $used = $checkUsed->check($val,'csarea_id');
            if ($used['status'] == 0) {
                return $this->error($used['msg']);
            }
            $model = CrmSalearea::find()->where(['and',['csarea_id'=>$val],['!=','csarea_status',CrmSalearea::STATUS_DELETE]])->one();
            $model->csarea_code = Html::decode($model['csarea_code']);
            $model->csarea_name = Html::decode($model['csarea_name']);
            $model->csarea_status = CrmSalearea::STATUS_DELETE;
            CrmDistrictSalearea::deleteAll(['csarea_id' => $val]);
            $name = $name.$model["csarea_name"].',';
            $result = $model->save();
        }
        $staff_code = trim($name,',');
        if ($result) {
            $msg = array('id' => $id, 'msg' => '删除销售点"' . $staff_code . '"');
            return $this->success('',$msg);
        } else {
            return $this->error();
        }
    }

    //查看销售区域
    public function actionView($id)
    {
        return SaleAreaShow::find()->where(['and',['csarea_id'=>$id],['!=','csarea_status',CrmSalearea::STATUS_DELETE]])->one();
    }
    //查看包含区域
    public function actionShowDistrict($id)
    {
        return CrmDistrictSalearea::find()->where(['csarea_id'=>$id])->asArray()->all();
    }

//获取所有省份
    public function actionAreaChildren()
    {
        return BsDistrict::getDisProvince();
    }
    //获取对应省份所有市区
    public function actionCity($id){
        return BsDistrict::getChildByParentId($id);
    }

    /*获取对应市区*/
    public function actionDisCity($id,$pid){
        $result = CrmDistrictSalearea::getDisCity($id,$pid);
        return $result;
    }
}
