<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/6/9
 * Time: 上午 09:50
 */
namespace app\modules\warehouse\controllers;

use app\controllers\BaseActiveController;
use app\modules\hr\models\HrStaff;
use app\modules\warehouse\models\BsSt;
use app\modules\warehouse\models\BsPart;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\search\PartSearch;
use app\modules\hr\models\show\HrStaffShow;
use yii;
use yii\gii\generators\crud\Generator;


class StorageController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\BsSt';

    public function actionIndex()
    {
        $searsh = new PartSearch();
        $post = Yii::$app->request->queryParams;
        $dataProvider = $searsh->search($post);
//        return $dataProvider;
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        foreach ($list['rows'] as $key => $val) {
            if ($list['rows'][$key]['YN'] == 'Y') {
                $list['rows'][$key]['YN'] = '启用';
            } else if ($list['rows'][$key]['YN'] == 'N') {
                $list['rows'][$key]['YN'] = '禁用';
            }
        }
        return $list;
    }

    //导出
    public function actionExport()
    {
        $searchModel = new PartSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->export($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        foreach ($list['rows'] as $key => $val) {
            if ($list['rows'][$key]['YN'] == 'Y') {
                $list['rows'][$key]['YN'] = '启用';
            } else if ($list['rows'][$key]['YN'] == 'N') {
                $list['rows'][$key]['YN'] = '禁用';
            }
        }
        return $list;
    }
    //获取操作人名称
    public function actionName($id)
    {
        $hr_staff = new HrStaff();
        $username = $hr_staff->getStaffById($id);//获取用户名
        return $username["staff_name"];
    }

    //获取仓库名称
    public function actionWhname()
    {
        $searsh = new BsWh();
        return $searsh->getWarehouseAll();
    }

    //获取仓库信息
    public function actionGetWarehouseInfo($id='',$code='')
    {
        $queryParams=[];
        $querySql="select a.wh_id,
                          a.wh_name,
                          a.wh_code,
                          (case a.wh_attr when 'Y' then '自有' when 'N' then '外租' else '未知' end) warehouseAttr 
                   from wms.bs_wh a
                   where a.wh_state = 'Y'";
        if(!empty($id)){
            $queryParams[':id']=$id;
            $querySql.=" and a.wh_id = :id";
        }
        if(!empty($code)){
            $queryParams[':code']=$code;
            $querySql.=" and a.wh_code = :code";
        }
        $warehouseInfo=\Yii::$app->db->createCommand($querySql,$queryParams)->queryOne();
        if(!empty($warehouseInfo['wh_id'])){
            $queryParams=[
                ':warehouse_id'=>$warehouseInfo['wh_id']
            ];
            $querySql="select b.part_code,b.part_name
                       from wms.bs_part b
                       left join wms.bs_wh c on c.wh_code = b.wh_code
                       where c.wh_id = :warehouse_id
                       and b.YN = '1'";
            $warehouseInfo['locationInfo']=\Yii::$app->db->createCommand($querySql,$queryParams)->queryAll();
        }
        return $warehouseInfo;
    }

    //根据st_id获取数据
    public function actionViewsbyid($st_id)
    {
        $sql = "select 
              bs.st_id,
              bs.st_code,
              bs.rack_code,
              bs.YN,
              bs.NW_DATE,
              bs.remarks,
              bs.NWER,
              bs.part_code,
              bs.wh_id,
              bp.part_name,
              bw.wh_name,
              bs.OPPER,
              DATE_FORMAT(bs.OPP_DATE,'%Y/%m/%d %h:%i')OPP_DATE
              from wms.bs_st bs left join wms.bs_part bp on bs.part_code=bp.part_code 
							left join wms.bs_wh bw on bs.wh_id=bw.wh_id
              where bs.part_code=bp.part_code 
              and bs.st_id=:id ";
        return Yii::$app->wms->createCommand($sql, [':id' => $st_id])->queryOne();
    }

    //修改
    public function actionUpdate($st_id)
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        $post = Yii::$app->request->post();
        $models = BsSt::findOne($st_id);
        $hr_staff = new HrStaff();
        try {
//            $model->load($post);
            $models->load($post);
            $hr_staff->load($post);
            $username = $hr_staff->getStaffById($hr_staff['staff_code']);//获取用户名

            //bs_st表
            if ($models['YN'] == '启用') {
                $models['YN'] = 'Y';
            } else if ($models['YN'] == '禁用') {
                $models['YN'] = 'N';
            }
            $models['OPPER'] = $username['staff_name'];;
            $models['OPP_DATE'] = date("Y-m-d H:i:s");

            if (!$models->save()) {
                $transaction->rollBack();
                throw new \Exception(json_encode($models->getErrors(), JSON_UNESCAPED_UNICODE));
                // throw new \Exception("修改儲位信息失败");
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $msg = array('修改成功！！');
        return $this->success('', $msg);
    }

    //关闭（开启）儲位
    public function actionUpdateyn($st_id,$staff_id)
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")

        $models = BsSt::findOne($st_id);
        $hr_staff = new HrStaff();
        $msg = '操作';
        $username = $hr_staff->getStaffById($staff_id);//获取用户名
//        return $models;
        //bs_st表
        if(!empty($models)) {
            if ($models->YN == 'Y') {
                $models->YN = 'N';
                $msg = '已禁用';
            } else if ($models->YN == 'N') {
                $models->YN = 'Y';
                $msg = '已启用';
            }
            $models->OPPER = $username['staff_name'];
            $models->OPP_DATE = date("Y-m-d H:i:s");

            if ($models->save()) {
                return $this->success($msg=$msg);
//            throw new \Exception(json_encode($models->getErrors(), JSON_UNESCAPED_UNICODE));
            } else {
//                throw new \Exception(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
                return $this->error($msg=$msg);
            }
        } else {
            return $this->error($msg=$msg);
        }
    }
    //批量设置状态
    public function actionBulkEnable($id,$yn,$opper){
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $arr=explode(",", $id);
        $hr_staff = new HrStaff();
        $username = $hr_staff->getStaffById($opper);//获取用户名
        $transaction = Yii::$app->db->beginTransaction();
        $msg="";
        if($yn=='Y'){
            $msg='已启用!';
        }else{
            $msg='已禁用!';
        }
        try{
            foreach ($arr as $key => $val) {
                $model=BsSt::findOne($val);
                $model->YN=$yn;
                $model->OPPER=$username['staff_name'];;
                $model->OPP_DATE = date("Y-m-d H:i:s");
                if (!($model->save())) {
                    throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }
            $transaction->commit();
            return $this->success($msg);
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //获取bs_st表中最后一条数据
    public function actionCounts()
    {
        $sql="select * from bs_st order by st_id desc limit 1";
        return Yii::$app->wms->createCommand($sql, null)->queryOne();
    }

    //新增
    public function actionAdd()
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $model = new BsSt();
        $hr_staff = new HrStaff();
        $post = Yii::$app->request->post();
        $list=$this->actionCounts();
        try {
            $model->load($post);
            $hr_staff->load($post);

            $username = $hr_staff->getStaffById($hr_staff['staff_code']);//获取用户名
//            $bsst['st_id']=strval(count($list)+1);
//            $bsst['part_code'] = $bsst['part_name'];
            $model['st_id']=strval(intval($list['st_id'])+1);
            $model['OPPER'] = $username['staff_name'];
            $model['OPP_DATE'] = date("Y-m-d H:i:s");
            $model['NWER'] = $username['staff_name'];
            $model['NW_DATE'] = date("Y-m-d H:i:s");
//            return $model;
            if ($model['YN'] == '启用') {
                $model['YN'] = 'Y';
            } else if ($model['YN'] == '禁用') {
                $model['YN'] = 'N';
            }
            $model['opp_ip'] = $_SERVER["REMOTE_ADDR"];

            if (!$model->save()) {
               throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                //return $this->error($msg="新增儲位信息失败");
            }
        } catch (\Exception $e) {
            return $this->error($msg=$e->getMessage());
        }
        $msg = array('新增成功！！');
        return $this->success('', $msg);

    }

}