<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/7/10
 * Time: 上午 09:07
 */
namespace app\modules\warehouse\controllers;

use app\controllers\BaseActiveController;
use app\modules\hr\models\HrStaff;
use app\modules\warehouse\models\BsLogCmp;
use app\modules\warehouse\models\search\LogCmpSearch;
use app\modules\warehouse\models\show\LogCmpShow;
use app\modules\warehouse\models\WmsParam;
use yii;

class LogisticsCompanyController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\BsLogCmp';
    public function actionIndex()
    {
        $searsh=new LogCmpSearch();
        $post=Yii::$app->request->queryParams;
        $dataProvider =  $searsh->search($post);
//        return $dataProvider;
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    //新增
    public function actionAdd()
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $model = new BsLogCmp();
        $post = Yii::$app->request->post();
        $hr_staff=new HrStaff();
        try{
            $model->load($post);
            $hr_staff->load($post);
            $username=$hr_staff->getStaffById($hr_staff['staff_code']);//获取用户名

            $model['OPP_DATE']=date("Y-m-d H:i:s");
            $model['OPPER']=$username['staff_name'];
            $model['OPP_IP']=$_SERVER["REMOTE_ADDR"];
            if (!$model->save()) {
                throw new \Exception("新增物流公司信息失败");
            }
        }
        catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        $msg = array('新增成功！！');
        return $this->success('',$msg);
    }

    //详情
    public function actionViews($id)
    {
        $searsh=new LogCmpSearch();
        $dataProvider =  $searsh->getById($id);
        return $dataProvider;
    }
    //修改
    public function actionUpdate($id)
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $model = BsLogCmp::findOne($id);
//        return $model;
        $post = Yii::$app->request->post();
        $hr_staff=new HrStaff();
        try{
            $model->load($post);
            $hr_staff->load($post);
            $username=$hr_staff->getStaffById($hr_staff['staff_code']);//获取用户名

            $model['OPP_DATE']=date("Y-m-d H:i:s");
            $model['OPPER']=$username['staff_name'];
            $model['OPP_IP']=$_SERVER["REMOTE_ADDR"];
            if (!$model->save()) {
                throw new \Exception("修改物流公司信息失败");
            }
        }
        catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        $msg = array('修改成功！！');
        return $this->success('',$msg);
    }

    //删除
    public function actionDeletes($id,$staff_id){
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $model = BsLogCmp::findOne($id);
        $hr_staff=new HrStaff();
        if($model){
            $username=$hr_staff->getStaffById($staff_id);//获取用户名
            $model['OPPER']=$username['staff_name'];
            $model['OPP_DATE']=date("Y-m-d H:i:s");
            $model->log_status = LogCmpSearch::STATUS_DELETE;
            if ($result = $model->save()) {
                return $this->success();
            } else {
                return $this->error();
            }
        }else{
            return $this->error();
        }

    }
    //公司名称
    public function actionCompanyNameList()
    {
        return BsLogCmp::getData();
    }

    //公司类型
    public function actionCompanyTypeList()
    {
        return BsLogCmp::getDatas();
    }

    //运输方式
    public function actionParaCompanyNameList()
    {
        return WmsParam::getData();
    }

    //公司类型
    public function actionParaCompanyTypeList()
    {
        return WmsParam::getDatas();
    }
}