<?php
/**
 * Created by PhpStorm.
 * User: F3860959
 * Date: 2017/6/7
 * Time: 下午 05:23
 */

namespace app\modules\warehouse\controllers;

use app\classes\Trans;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrStaff;
use app\modules\warehouse\models\show\BsWhSearch;
use app\modules\warehouse\models\show\WhAdmShow;
use Yii;
use yii\base\Exception;
use yii\caching\DummyCache;
use yii\data\SqlDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\controllers\BaseActiveController;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\WhAdm;
use app\modules\warehouse\models\search\SetWarehouseSearch;
use app\modules\warehouse\models\show\SetWarehouseShow;
use app\modules\common\models\BsDistrict;
use yii\data\ActiveDataProvider;

/*
 * 仓库设置控制器
 */

class SetWarehouseController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\BsWh';

    /**
     * Lists all BsWh models.
     * @return mixed
     */
    public function actionIndex()
    {
//        $searchModel = new \app\modules\warehouse\models\search\BsWhSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        $model = $dataProvider->getModels();
//        $list['rows'] = $model;
//        $list['total'] = $dataProvider->totalCount;
//        return $list;


        $params=Yii::$app->request->queryParams;
        $sql="select s.wh_id,
                     s.wh_code,
                     s.wh_name,
                     (case s.people when '0' then '' else s.people end)people,
                     (case s.company when '0' then '' else s.company end)company,
                     concat(func_get_paddress(s.district_id),s.wh_addr) wh_addr,
                     (case s.wh_state when 'Y' then '启用' else '禁用' end)wh_state,
                     (a.bsp_svalue)wh_type,
                     (b.bsp_svalue)wh_lev,
                     (c.bsp_svalue)wh_attr,
                     (d.bsp_svalue) wh_nature,
                     (case s.wh_yn when 'N' then '否' else '是' end)wh_yn,
                     s.remarks,
                     (case s.yn_deliv when '0' then '否' else '是' end)yn_deliv,
                     s.nwer,
                     s.nw_date,
                     s.opper,
                     s.opp_date
              from wms.bs_wh s
              left join erp.bs_pubdata a on a.bsp_id=s.wh_type
              left join erp.bs_pubdata b on b.bsp_id=s.wh_lev
              left join erp.bs_pubdata c on c.bsp_id=s.wh_attr
              left join erp.bs_pubdata d on d.bsp_id=s.wh_nature
              where 1=1 ";
        $queryParams=[];
        if(!empty($params['wh_code'])){
            $params['wh_code']=str_replace(['%','_'],['\%','\_'],$params['wh_code']);
            $sql.=" and (s.wh_code = '".$params['wh_code']."')";
        }
        if(!empty($params['wh_lev'])){
            $params['wh_lev']=str_replace(['%','_'],['\%','\_'],$params['wh_lev']);
            $sql.=" and (s.wh_lev = '".$params['wh_lev']."')";
        }
        if(!empty($params['wh_attr'])){
            $params['wh_attr']=str_replace(['%','_'],['\%','\_'],$params['wh_attr']);
            $sql.=" and (s.wh_attr = '".$params['wh_attr']."')";
        }
        if(!empty($params['wh_yn'])){
            $params['wh_yn']=str_replace(['%','_'],['\%','\_'],$params['wh_yn']);
            $sql.=" and (s.wh_yn = '".$params['wh_yn']."')";
        }
        if(!empty($params['yn_deliv'])){
            $params['wh_yn']=str_replace(['%','_'],['\%','\_'],$params['yn_deliv']);
            $sql.=" and (s.yn_deliv = '".$params['yn_deliv']."')";
        }
        if(!empty($params['wh_type'])){
            $params['wh_type']=str_replace(['%','_'],['\%','\_'],$params['wh_type']);
            $sql.=" and (s.wh_type = '".$params['wh_type']."')";
        }
        if(!empty($params['wh_state'])){
            $params['wh_state']=str_replace(['%','_'],['\%','\_'],$params['wh_state']);
            if($params['wh_state']!='1')
            {
                $sql.=" and (s.wh_state = '".$params['wh_state']."')";
            }
        }
        if(!empty($params['wh_name'])){
            $trans=new Trans();
            $params['part_code']=str_replace(['%','_'],['\%','\_'],$params['wh_name']);
            $sql.=" and (s.wh_name like '%".$params['wh_name']."%')";
        }
        if(empty($params['wh_state']))
        {
            $sql.=" and (s.wh_state = 'Y')";
        }
        $sql.="ORDER BY s.opp_date DESC";
        $totalCount=Yii::$app->get('db')->createCommand("select count(*) from ( {$sql} ) a",$queryParams)->queryScalar();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ]
        ]);
        $model = $provider->getModels();
        $list['rows'] = $model;
        $list['total'] = $provider->totalCount;
        return $list;

    }

    public function actionLoadInfor()
    {
        $queryParams = Yii::$app->request->queryParams;
        $_sql="SELECT a.wh_code,a.emp_no,
        a.adm_phone,(SELECT s.staff_name FROM erp.hr_staff s WHERE s.staff_code=a.emp_no)staffname,
              a.adm_email from wms.wh_adm a WHERE a.wh_code='".$queryParams['id']."'";
        $total="select count(*) from wms.wh_adm a WHERE a.wh_code= '".$queryParams['id']."'";
        $totalCount=Yii::$app->db->createCommand($total)->queryScalar();
        $dataProvider = new SqlDataProvider([
            'sql' => $_sql,
            'totalCount'=>$totalCount,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
        $model = $dataProvider->getModels();
        return $model;
    }

    /*
     * 新增仓库物流的操作数据的方法
     */
    /**
     * @return array
     */
    public function actionCreateWarehouse()
    {
        if (Yii::$app->request->isPost) {
//            $db = Yii::$app->get('db2');//指向数据库2
            $data = Yii::$app->request->post();
//            return $data;
            $transaction = Yii::$app->wms->beginTransaction();
            try {
                $BsWhInfo = new BsWh();
//                $WhAdmInfo = new WhAdm();
                if (!($BsWhInfo->load($data) && $BsWhInfo->save())) {
                    throw new \Exception(json_encode($BsWhInfo->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                if(!empty($data['sta'])){
                    foreach($data['sta'] as $key=>$val){
                        if(!empty($val['WhAdm']['emp_no'])){
                            $bsreqdt=new WhAdm();
                            $bsreqdt->wh_code=$BsWhInfo->wh_code;
                            if($bsreqdt->load($val)){
                                if(!$bsreqdt->save()){
                                    throw new Exception(json_encode($bsreqdt->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            }else{
                                throw new Exception('新增仓库管理异常');
                            }
                        }
                    }
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return $this->success();
    }

    //获取新增页面的下拉页面
    public function actionDownListWh()
    {
        $downList['country'] = BsDistrict::getDisLeveOne();//所在国家
        $downList['wh_nature']=BsPubdata::getList(BsPubdata::WH_NATURE); //仓库性质
        $downList['wh_type']=BsPubdata::getList(BsPubdata::WH_TYPE);  //仓库类别
        $downList['wh_lev']=BsPubdata::getList(BsPubdata::WH_LEV);   //仓库级别
        $downList['wh_attr']=BsPubdata::getList(BsPubdata::WH_ATTR);   //仓库属性
//        $downList['cur_id']=BsPubdata::getList(BsPubdata::SUPPLIER_TRADE_CURRENCY); //币别

        $downList['people']=BsCompany::find()->select(['company_id', 'company_name'])->where(['company_status' => BsCompany::STATUS_DEFAULT])->all();  //公司名称
        return $downList;
    }

    /*
     * 修改仓储信息 add by jh
     */
    public function actionUpdateWarehouse($id)
    {
        if (Yii::$app->request->isPost) {
            $db = Yii::$app->get('wms');//指向数据库wms
            $post = Yii::$app->request->post();
            $transaction = $db->beginTransaction();
            try {
                $bsReq=BsWh::findOne($id);
                if($bsReq->load($post)){
                    if(!$bsReq->save())
                    {
                        throw new Exception(json_encode($bsReq->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }else{
                    throw new Exception('主表更新失败');
                }
                WhAdm::deleteAll(['wh_code'=>$bsReq->wh_code]);
                if(!empty($post['sta'])){
                    foreach($post['sta'] as $key=>$val){
                        if(!empty($val['WhAdm']['emp_no'])){
                            $bsreqdt=new WhAdm();
                            $bsreqdt->wh_code=$bsReq->wh_code;
                            if($bsreqdt->load($val)){
                                if(!$bsreqdt->save()){
                                    throw new Exception(json_encode($bsreqdt->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            }else{
                                throw new Exception('修改仓库管理异常');
                            }
                        }
                    }
                }
                $transaction->commit();
                return $this->success();
            } catch (\Exception $e) {
//                file_put_contents('log.txt', $e->getMessage());
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
    }

    //开关仓库
    public function actionUpdateWhState($id)
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $models = BsWh::getBsWhInfoOne($id);//BsWh::findOne($id);
       // $hr_staff = new HrStaff();
        $msg = '操作';
       // $username = $hr_staff->getStaffById($staff_id);//获取用户名
        if (!empty($models)) {
            if ($models->wh_state == 'Y') {
                $models->wh_state = 'N';
                $msg = '已禁用';
            } else if ($models->wh_state == 'N') {
                $models->wh_state = 'Y';
                $msg = '已启用';
            }
//            $models->OPPER = $username['staff_name'];
            //$models->OPP_DATE = date("Y-m-d H:i:s");

            if ($models->save()) {
                return $this->success($msg = $msg);
//            throw new \Exception(json_encode($models->getErrors(), JSON_UNESCAPED_UNICODE));
            } else {
//                throw new \Exception(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
                return $this->error($msg = $msg);
            }
        } else {
            return $this->error($msg = $msg);
        }
    }

    //批量启用仓库
    public function actionOpenss()
    {
        $param = Yii::$app->request->queryParams;
        try{
            $array = $param['id'];
            for ($i=0;$i<count($array);$i++) {
                $model = BsWh::findOne($array[$i]);
                $model->wh_state = "Y";
                if(!$model->save()){
                    throw  new \Exception("启用失败");
                };
            }
            return $this->success();
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
    //批量禁用
    public function actionClosess()
    {
        $param = Yii::$app->request->queryParams;
        try{
            $array = $param['id'];
            for ($i=0;$i<count($array);$i++) {
                $model = BsWh::findOne($array[$i]);
                $model->wh_state = "N";
                if(!$model->save()){
                    throw  new \Exception("禁用失败");
                };
            }
            return $this->success();
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }



    /**
     * Finds the BsWh model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return BsWh the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BsWh::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
     * 获取wh_adm表中主键id
     */
    public function findAdmid()
    {
        $query = WhAdm::find()->select('MAX(adm_id) adm_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query]);
        $modelWhAdm = $dataProvider->getModels();
        return $modelWhAdm[0]['adm_id'];
    }

    /**
     * 获取一级地址
     * @return array|yii\db\ActiveRecord[]
     */
    public function actionDistrictLevelOne()
    {
        return BsDistrict::getDisLeveOne();
    }

//下拉列表数据
    public function actionGetDownList()
    {
        return BsWh::options(["" => "全部"]);
    }

    /*
     * 新增仓储页面的下拉数据
     */
    public function actionDownList()
    {
        return BsWh::options();
    }

    /*
     * 获取一条数据
     */
    public function actionModels($id)
    {
        $sql="SELECT
                (q.bsp_svalue) wh_natures,
                (w.bsp_svalue) wh_attrs,
                (e.bsp_svalue) wh_types,
                (r.bsp_svalue) wh_levs,
                t.*,
                concat(func_get_paddress(t.district_id),t.wh_addr) wh_addrss,
                a.adm_id,
                a.wh_code,
                a.emp_no,
				s.staff_name,
                a.adm_phone,
                a.adm_email
                FROM
                    wms.bs_wh t
                LEFT JOIN wms.wh_adm a ON t.wh_code = a.wh_code
				LEFT JOIN erp.hr_staff s ON a.emp_no=s.staff_code
				left join erp.bs_pubdata q on q.bsp_id=t.wh_nature
				left join erp.bs_pubdata w on w.bsp_id=t.wh_attr
				left join erp.bs_pubdata e on e.bsp_id=t.wh_type
				left join erp.bs_pubdata r on r.bsp_id=t.wh_lev
                WHERE
                    t.wh_id =".$id;
        //$ret=Yii::$app->db->createCommand($sql)->queryAll();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
        ]);
        $result = $provider->getModels();
        return $result;
    }

    /**
     * 通过输入的仓库代码判断是否已经存在
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function actionGetWarehouseInfo($id)
    {
        return BsWh::getBsWhInfoOne($id);
    }

    /*
     * 根据仓库名称获取仓库信息
     */
    public function actionGetWarehouseInfoByName($name)
    {
        return BsWh::getBsWhInfoByName($name);
    }

    //获取仓库信息，用于请购转采购
    public function actionWarehouseInfo()
    {
        $params = Yii::$app->request->queryParams;
        if (!empty($params['wh_code'])) {
            $sql = "select * from wms.bs_wh w where w.wh_state='Y' and w.wh_code='{$params['wh_code']}' or w.wh_name='{$params['wh_code']}'";
        } else {
            $sql = "select w.wh_id,w.wh_code,w.wh_name from wms.bs_wh w where w.wh_state='Y'";
        }
        $db = Yii::$app->getDb('wms');
        $result = $db->createCommand($sql)->queryAll();
        return $result;
    }
}