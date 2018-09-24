<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/6/29
 * Time: 下午 02:45
 */
namespace app\modules\warehouse\controllers;

use app\controllers\BaseActiveController;
use app\modules\warehouse\models\BsLogCmp;
use app\modules\warehouse\models\BsVeh;
use app\modules\warehouse\models\search\BsVehSearch;
use app\modules\hr\models\HrStaff;
use yii;

class VehicleInformationController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\BsVeh';

    public function actionIndex()
    {
        $searsh=new BsVehSearch();
        $post=Yii::$app->request->queryParams;
        $dataProvider =  $searsh->search($post);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    //公司名称
    public function actionCompanyNameList()
    {
        return BsLogCmp::getData();
    }

    //根据id获取数据
    public function actionGetBsvehByid($id)
    {
        return BsVeh::getBsVehById($id);
    }

    //新增
    public function actionAdd()
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $model = new BsVeh();
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        $hr_staff=new HrStaff();
        try{
            $model->load($post);
            $hr_staff->load($post);
            $username=$hr_staff->getStaffById($hr_staff['staff_code']);//获取用户名

            $model['OPP_DATE']=date("Y-m-d H:i:s");
            $model['OPPER']=$username['staff_name'];
            $model['veh_ip']=$_SERVER["REMOTE_ADDR"];
            if (!$model->save()) {
                $transaction->rollBack();
                throw new \Exception("新增车辆信息失败");
            }
            $transaction->commit();
        }
        catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $msg = array('新增成功！！');
        return $this->success('',$msg);

    }

    //修改
    public function actionUpdate($id)
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $model = BsVeh::getBsVehById($id);
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        $hr_staff=new HrStaff();
//        return $model;
        try{
            $model->load($post);
            $hr_staff->load($post);
            $username=$hr_staff->getStaffById($hr_staff['staff_code']);//获取用户名

            //bs_part表
            $model['OPP_DATE']=date("Y-m-d H:i:s");
            $model['OPPER']=$username['staff_name'];
            $model['veh_ip']=$_SERVER["REMOTE_ADDR"];
            if (!$model->save()) {
                $transaction->rollBack();
                throw new \Exception("修改车辆信息失败");
            }
            $transaction->commit();
        }
        catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $msg = array('修改成功！！');
        return $this->success('',$msg);
    }

    //删除
    public function actionDeletes($id,$staff_id){
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $model = BsVeh::getBsVehById($id);
//        $post = Yii::$app->request->post();
        $hr_staff=new HrStaff();
//        return $model;
        if($model){
//            $hr_staff->load($post);
            $username=$hr_staff->getStaffById($staff_id);//获取用户名
            $model['OPPER']=$username['staff_name'];
            $model['OPP_DATE']=date("Y-m-d H:i:s");
            $model->veh_status = BsVehSearch::STATUS_DELETE;
            if ($result = $model->save()) {
                return $this->success();
            } else {
                return $this->error();
            }
        }else{
            return $this->error();
        }

    }
}