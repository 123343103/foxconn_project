<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/13
 * Time: 上午 10:10
 */
namespace app\modules\warehouse\controllers;
use app\controllers\BaseActiveController;
use app\models\User;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsForm;
use app\modules\system\models\search\VerifyrecordChildSearch;
use app\modules\system\models\Verifyrecord;
use app\modules\warehouse\models\OWhpdt;
use app\modules\warehouse\models\OWhpdtDt;
use yii\data\SqlDataProvider;

class OtherOutStockController extends BaseActiveController
{
    public $modelClass = '\app\modules\warehouse\models\OWhpdt';

    //首页
    public function actionIndex($staff_id)
    {
        $params = \Yii::$app->request->queryParams;
        $bindParams = [];
        $where = "WHERE ow.buss_type='2'";
        $where .= " and ow.o_whid in(";
        $i = 0;
        foreach ($this->actionGetWhJurisdiction($staff_id) as $key => $val) {
            $bindParams[':o_whid' . $key] = $val['wh_id'];
            $i++;
            if ($i == count($this->actionGetWhJurisdiction($staff_id))) {
                $where .= ':o_whid' . $key;
//                $where=trim($where,',');
            } else {
                $where .= ':o_whid' . $key . ',';
//                $where=trim($where,',').',';
            }
        }
        $where = $where . ")";
        if (!empty($params["o_whcode"])) {
            $where .= " and ow.o_whcode like '%" . $params["o_whcode"] . "%'";
        }
        if (!empty($params["o_whstatus"])) {
            $where .= "and ow.o_whstatus='" . $params["o_whstatus"] . "'";
        }
        if (!empty($params["o_whtype"])) {
            $where .= "and ow.o_whtype='" . $params["o_whtype"] . "'";
        }
        if (!empty($params["organization_name"])) {
            $where .= "and ho.organization_name='" . $params["organization_name"] . "'";
        }
        if (!empty($params["o_whid"])) {
            $where .= "and ow.o_whid='" . $params["o_whid"] . "'";
        }
        if(!empty($params["bs_cmp"]))
        {
            $where .=" and bw.bs_cmp='".$params["bs_cmp"]."'";
        }
        if(!empty($params["o_start_date"]))
        {
            $where.=" and ow.plan_odate>='".date('Y-m-d H:i:s',strtotime($params["o_start_date"]))."'";
        }
        if(!empty($params["o_end_date"]))
        {
            $where.=" and ow.plan_odate<='".date('Y-m-d H:i:s',strtotime($params["o_end_date"]))."'";
        }
        $fields = "ow.o_whpkid,bp.bsp_svalue,ow.o_whcode,bc.company_name,bw.wh_name o_wh_name,bws.wh_name i_wh_name,ow.o_whstatus,ow.relate_packno,ow.ord_id,ow.logistics_no,ow.o_date,ow.pdt_attr,ow.plan_odate,ow.delivery_type,ow.logistics_type,ow.use_for,ow.reciver,ow.reciver_tel,ow.district_id,ow.address,ow.remarks,hs.staff_name opp_name,ow.opp_date,ow.opp_ip,hsc.staff_name create_name,ow.creat_date,ow.creat_ip,ho.organization_name";
        $sql = "SELECT {$fields} FROM wms.o_whpdt ow 
                LEFT JOIN erp.bs_pubdata bp ON bp.bsp_id = ow.o_whtype
                LEFT JOIN wms.bs_wh bw ON bw.wh_id = ow.o_whid
                LEFT JOIN wms.bs_wh bws ON bws.wh_id = ow.i_whid
                LEFT JOIN erp.bs_company bc ON bc.company_id=bw.bs_cmp 
                LEFT JOIN erp.hr_staff hs ON hs.staff_id = ow.opp_id
                LEFT JOIN erp.hr_staff hsc ON hsc.staff_id = ow.creator
                LEFT JOIN erp.hr_organization ho ON ho.organization_code = hsc.organization_code {$where} order by ow.creat_date DESC ";
        $count = \Yii::$app->db->createCommand("select count(*) FROM wms.o_whpdt ow
                LEFT JOIN erp.bs_pubdata bp ON bp.bsp_id = ow.o_whtype
                LEFT JOIN wms.bs_wh bw ON bw.wh_id = ow.o_whid
                LEFT JOIN erp.hr_staff hs ON hs.staff_id = ow.opp_id
                LEFT JOIN erp.hr_staff hsc ON hsc.staff_id = ow.creator
                LEFT JOIN erp.hr_organization ho ON ho.organization_code = hsc.organization_code {$where} group by ow.o_whpkid", $bindParams)->query()->count();
        $provider = new SqlDataProvider([
            "sql" => $sql,
            "totalCount" => $count,
            "params" => $bindParams,
            "pagination" => [
                "page" => isset($params["page"]) ? $params["page"] - 1 : 0,
                "pageSize" => isset($params["rows"]) ? $params["rows"] : 10,
            ]
        ]);
        return [
            "rows" => $provider->models,
            "total" => $provider->totalCount
        ];
    }

    //新增
    public function actionCreate()
    {
        $trans = OWhpdt::getDb()->beginTransaction();
        date_default_timezone_set("Asia/Shanghai");
        $post = \Yii::$app->request->post();
        try {
            $model = new OWhpdt();
            $model->load($post);
            $model->o_whcode = BsForm::getCode("o_whpdt_other", $model);
            $model->o_whstatus = OWhpdt::WAIT_COMMIT;
            $model->buss_type = 2;
            $model->creat_date = date("Y-m-d H:i:s");
            $model->creat_ip = \Yii::$app->request->getUserIP();
            if (!($model->validate() && $model->save())) {
                throw new \Exception("新增失败");
            }
            if (isset($post["OWhpdtDt"])) {
                $childModel = new OWhpdtDt();
                $data = $post["OWhpdtDt"];
                $rows = [];
                for ($x = 0; $x < count($data["part_no"]); $x++) {
                    $_childModel = clone $childModel;
                    $row["o_whpkid"] = $model->o_whpkid;
                    $row = array_merge($row, array_combine(array_keys($data), array_column($data, $x)));
                    $_childModel->setAttributes($row);
                    if (!$_childModel->validate()) {
                        throw new \Exception("子表数据验证失败");
                        break;
                    }
                    $rows[] = $row;
                }
                $batch = OWhpdtDt::getDb()->createCommand()->batchInsert(OWhpdtDt::tableName(), ['o_whpkid','invt_id','part_no', 'o_whnum', 'remarks'], $rows);
                if (!$batch->execute()) {
                    throw new \Exception("子表数据保存失败");
                }
            }
            $trans->commit();
            return $this->success([$model->primaryKey, "新增成功"]);
        } catch (\Exception $e) {
            $trans->rollBack();
            return $this->error([$model->primaryKey, $e->getMessage()]);
        }
    }

    //修改
    public function actionEdit($id)
    {
        try {
            $post = \Yii::$app->request->post();
            $model = OWhpdt::findOne($id);
            $model->load($post);
            if (!($model->validate() && $model->save())) {
                throw new \Exception("修改失败");
            }
            if (isset($post["OWhpdtDt"])) {
                OWhpdtDt::deleteAll(["o_whpkid" => $model->o_whpkid]);
                $childModel = new OWhpdtDt();
                $data = $post["OWhpdtDt"];
                $rows = [];
                for ($x = 0; $x < count($data["part_no"]); $x++) {
                    $_childModel = clone $childModel;
                    $row["o_whpkid"] = $model->o_whpkid;
                    $row = array_merge($row, array_combine(array_keys($data), array_column($data, $x)));
                    $_childModel->setAttributes($row);
                    if (!$_childModel->validate()) {
                        throw new \Exception("子表数据验证失败");
                        break;
                    }
                    $rows[] = $row;
                }
                $batch = OWhpdtDt::getDb()->createCommand()->batchInsert(OWhpdtDt::tableName(), ['o_whpkid','invt_id', 'part_no', 'o_whnum', 'remarks'], $rows);
                if (!$batch->execute()) {
                    throw new \Exception("子表数据保存失败");
                }
            }
            return $this->success("修改成功");
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    //详情
    public function actionView($id)
    {
        $model = OWhpdt::findOne($id);
        $data = $model->toArray();
        $record = Verifyrecord::findOne(["bus_code" => "qtckd", "vco_busid" => $model->o_whpkid]);
        if ($record) {
            $searchModel = new VerifyrecordChildSearch();
            $dataProvider = $searchModel->search(null, $record->vco_id);
            $data["checks"] = $dataProvider->getModels();
        }
        return $data;
    }

    //取消出库
    public function actionCancel($id)
    {
        try {
            $params = \Yii::$app->request->post();
            if (!(OWhpdt::updateAll(["o_whstatus" => OWhpdt::OUTSTOCK_CANCEL, "can_reason" => $params["reason"]], ["o_whpkid" => $id]))) {
                throw new \Exception("取消失败");
            }
            return $this->success("取消成功");
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function actionToCheck($id)
    {

    }

    //每笔出库单关联的商品信息
    public function actionChildData($id)
    {
        $where = "where owd.o_whpkid=".$id;
        $fields = "owd.o_whdtid,owd.remarks,owd.o_whpkid,owd.part_no,owd.remarks,owd.invt_id,owd.req_num,owd.o_whnum,bsi.pdt_name,bsi.brand_name,bsi.tp_spec,bsi.unit_name,bsi.invt_num,bsi.st_code,bsi.batch_no";
        $sql = "SELECT {$fields} FROM wms.o_whpdt_dt owd
                LEFT JOIN wms.bs_sit_invt bsi ON bsi.invt_id = owd.invt_id {$where}";
        $count = \Yii::$app->db->createCommand("select count(*) FROM wms.o_whpdt_dt owd
                                                LEFT JOIN wms.bs_wh_invt bsi ON bsi.invt_id = owd.invt_id
                                                {$where} group by owd.o_whdtid", null)->query()->count();
        $provider = new SqlDataProvider([
            "sql" => $sql,
            "totalCount" => $count,
//            "params"=>$bindParams,
            "pagination" => [
                "page" => isset($params["page"]) ? $params["page"] - 1 : 0,
                "pageSize" => isset($params["rows"]) ? $params["rows"] : 10,
            ]
        ]);
        return [
            "rows" => $provider->models,
            "total" => $provider->totalCount
        ];
    }

    //库存商品
    public function actionProductData($staff_id,$id)
    {
        $params = \Yii::$app->request->queryParams;
//        return $params;
        $where = " where 1=1 ";
        $bindParams = [];
        $where .= " and bw.wh_id in(";
        $i = 0;
        foreach ($this->actionGetWhJurisdiction($staff_id,$id) as $key => $val) {
            $bindParams[':whs_id' . $key] = $val['wh_id'];
            $i++;
            if ($i == count($this->actionGetWhJurisdiction($staff_id,$id))) {
                $where .= ':whs_id' . $key;
//                $where=trim($where,',');
            } else {
                $where .= ':whs_id' . $key . ',';
//                $where=trim($where,',').',';
            }
        }
        $where = $where . ") and bw.wh_state='Y' ";
        if (!empty($params["st_code"])) {
            $bindParams[":st_code"] = $params["st_code"];
            $where .= " and bsi.st_code=:st_code ";
        }
        if (!empty($params["kwd"])) {
            $params["kwd"] = str_replace(['%', '_'], ['\%', '\_'], $params["kwd"]);
            $bindParams[":pdt_no"] = "%{$params["kwd"]}%";
            $bindParams[":pdt_name"] = "%{$params["kwd"]}%";
            $where .= " and (bsi.part_no like :pdt_no or bsi.pdt_name like :pdt_name) ";
        }
        if(!empty($params['part_no']))
        {
            $bindParams[":part_no"] =$params['part_no'];
            $where.=" and bsi.part_no=:part_no";
        }
        $fields = "bsi.invt_id,bsi.invt_num,bsi.part_no,bsi.pdt_name,bsi.tp_spec,bsi.brand_name,bsi.unit_name,bsi.wh_name,bsi.wh_code,bw.wh_id,bsi.st_code,bsi.batch_no";
        $sql = "select {$fields} FROM
    		            wms.bs_sit_invt bsi
    		            LEFT JOIN wms.bs_wh bw ON bw.wh_code=bsi.wh_code 
        {$where}";
        $count = \Yii::$app->db->createCommand("select count(*) FROM 	wms.bs_sit_invt bsi
          LEFT JOIN wms.bs_wh bw ON bw.wh_code=bsi.wh_code
        {$where} group by bsi.invt_id", $bindParams)->query()->count();
        $provider = new SqlDataProvider([
            "sql" => $sql,
            "totalCount" => $count,
            "params" => $bindParams,
            "pagination" => [
                "page" => isset($params["page"]) ? $params["page"] - 1 : 0,
                "pageSize" => isset($params["rows"]) ? $params["rows"] : 10,
            ]
        ]);
        return [
            "rows" => $provider->models,
            "total" => $provider->totalCount
        ];
    }

    public function actionModel($id)
    {
        return OWhpdt::findOne($id);
    }

    //下拉列表
    public function actionOptions()
    {
        return [
            "o_whstatus" => OWhpdt::getStatus(),    //状态
            "o_whtype" => OWhpdt::getOutType(),                      //单据类型
            "organization" => OWhpdt::getOrganization(),                                  //申请部门
//            "warehouse"=>OWhpdt::getWareHouse(),                                       //出仓仓库,
            "trans_type" => OWhpdt::getTransType(),                                     //物流方式,
            "delivery_type" => OWhpdt::getDelveryType(),                                 //配送方式
            "storage_position" => OWhpdt::getSt(),
            "product_property" => ["1" => "样品", "0" => "非样品"],
            "staff" => OWhpdt::getStaff(),
            "staff_code" => OWhpdt::getStaffCode(),
            "company"=>OWhpdt::getBsCompany()   //法人

        ];
    }

    //根据仓库id获取仓库管理员
    public function actionGetWhCode($wh_id)
    {
        $sql = "select hs.staff_code,hs.staff_name from erp.hr_staff hs,wms.wh_adm wa,wms.bs_wh bw where hs.staff_code=wa.emp_no and wa.wh_code=bw.wh_code and bw.wh_id=:wh_id";
        $queryParam = [
            ':wh_id' => $wh_id,
        ];
        $model = \Yii::$app->get('db')->createCommand($sql, $queryParam)->queryAll();
        return $model;
    }

    //获取当前用的user中是否是超级管理员
    public function actionGetUser($staff_id)
    {
        $user=User::find()->select("is_supper")->where(['staff_id'=>$staff_id])->one();
        return $user;
    }

    //根据料号获取库存商品详情
    public function actionGetProduct($part_no,$id)
    {
        $sql = "SELECT
        	        bsi.invt_num,
                	bsi.part_no,
                	bsi.batch_no,
                	bsi.pdt_name,                   
                	bsi.tp_spec,
                	bsi.brand_name,
                	bsi.unit_name,
                	bsi.wh_name,
                	bsi.wh_code,
                	bsi.st_code,
                	bw.wh_id,
                	bs.st_id
                FROM
                	wms.bs_sit_invt bsi
                LEFT JOIN wms.bs_wh bw ON bw.wh_code = bsi.wh_code
                LEFT JOIN wms.bs_st bs ON bs.st_code = bsi.st_code
                WHERE
                	bsi.part_no =:part_no AND bw.wh_id=:id";
        $queryParam = [
            ':part_no' => $part_no,
            ':id'=>$id
        ];
        $model = \Yii::$app->get('db')->createCommand($sql, $queryParam)->queryAll();
        return $model;
    }

    //仓库权限管控
    public function actionGetWhJurisdiction($staff_id,$id=null)
    {
        if(!empty($id))
        {
            $sql = "SELECT
	                bw.wh_id,
	                bw.wh_code,
	                bw.wh_name,
	                bp.part_id,
	                bp.part_code,
	                bp.part_name,
	                bw.bs_cmp,
	                bc.company_name
              FROM
	                erp.r_user_wh_dt uwd
              LEFT JOIN erp.`user` u ON u.user_id = uwd.user_id
              LEFT JOIN wms.bs_wh bw ON bw.wh_id = uwd.wh_pkid
              LEFT JOIN wms.bs_part bp ON bp.part_id = uwd.part_id
              LEFT JOIN erp.bs_company bc ON bc.company_id=bw.bs_cmp
              WHERE u.staff_id =:staff_id AND bw.wh_state='Y' AND bw.wh_id=:id GROUP BY bw.wh_id";
            $queryParam = [
                ':staff_id' => $staff_id,
                ':id'=>$id
            ];
        }
        else {
            $sql = "SELECT
	                bw.wh_id,
	                bw.wh_code,
	                bw.wh_name,
	                bp.part_id,
	                bp.part_code,
	                bp.part_name,
	                bw.bs_cmp,
	                bc.company_name
              FROM
	                erp.r_user_wh_dt uwd
              LEFT JOIN erp.`user` u ON u.user_id = uwd.user_id
              LEFT JOIN wms.bs_wh bw ON bw.wh_id = uwd.wh_pkid
              LEFT JOIN wms.bs_part bp ON bp.part_id = uwd.part_id
              LEFT JOIN erp.bs_company bc ON bc.company_id=bw.bs_cmp
              WHERE u.staff_id =:staff_id AND bw.wh_state='Y' GROUP BY bw.wh_id";
            $queryParam = [
                ':staff_id' => $staff_id,
            ];
        }
        $model = \Yii::$app->get('db')->createCommand($sql, $queryParam)->queryAll();
        return $model;
    }

    //商品权限管控
    public function actionGetCtgJurisdiction($staff_id)
    {
        $sql="SELECT
      	        bc.catg_id,
              	bc.catg_level,
              	bc.catg_no,
              	bc.catg_name,
              	bc.p_catg_id,
              	bc.yn_machine
              FROM
              	erp.r_user_ctg_dt rucd
              LEFT JOIN erp.`user` u ON u.user_id = rucd.user_id
              LEFT JOIN pdt.bs_category bc ON bc.catg_id = rucd.ctg_pkid
              WHERE
              	bc.isvalid = 1 AND u.staff_id =:staff_id";
        $queryParam = [
            ':staff_id' => $staff_id,
        ];
        $model = \Yii::$app->get('db')->createCommand($sql, $queryParam)->queryAll();
        return $model;
    }

    //用户角色权限管控
    public function actionGetRoleJurisdiction($staff_id)
    {
        $sql="SELECT
              	br.role_pkid,
              	br.role_no,
              	br.role_name,
              	br.role_des
              FROM
              	erp.r_user_role_dt rurd
              LEFT JOIN erp.`user` u ON u.user_id = rurd.user_id
              LEFT JOIN erp.bs_role br ON br.role_pkid = rurd.role_pkid
              WHERE
              	br.role_state = 1 AND u.staff_id =:staff_id";
        $queryParam = [
            ':staff_id' => $staff_id,
        ];
        $model = \Yii::$app->get('db')->createCommand($sql, $queryParam)->queryAll();
        return $model;
    }

    //厂区权限管控
    public function actionGetAreaJurisdiction($staff_id)
    {
        $sql="SELECT
      	        bf.factory_id,
              	bf.factory_code,
              	bf.factory_name
              FROM
              	erp.r_user_area_dt ruad
              LEFT JOIN erp.`user` u ON u.user_id = ruad.user_id
              LEFT JOIN erp.bs_factory bf ON bf.factory_id = ruad.area_pkid
              WHERE
              	bf.fact_status = 1 AND u.staff_id =:staff_id";
        $queryParam = [
            ':staff_id' => $staff_id,
        ];
        $model = \Yii::$app->get('db')->createCommand($sql, $queryParam)->queryAll();
        return $model;
    }

    //根据最后一阶id获取完整地址
    public function actionGetAddress($district_id)
    {
        $address_id = $district_id;//最后一阶的地址代码
        $addr[] = "";
        while ($address_id > 0) {
            $addr_info = BsDistrict::findOne($address_id);
            $address_id = $addr_info->district_pid;
            $addr[] = $addr_info->district_name;
        }
        return implode(" ", array_reverse($addr));
    }

    public function actionBusinessType()
    {
        $businessType = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'qtckd'])->all();
        foreach ($businessType as $k=>$v) {
            $data[$v['business_type_id']] = $v['business_value'];
        }
        return $data;
    }

    //根据仓库id获取储位信息
    public function actionGetSt($wh_id)
    {
        $sql="select bs.st_id,bs.st_code from wms.bs_st bs
              LEFT JOIN wms.bs_part bp ON bp.part_code=bs.part_code
              LEFT JOIN wms.bs_wh bw ON bw.wh_code=bp.wh_code
              where bw.wh_id=:wh_id
              and bs.YN='Y'";
        $queryParam = [
            ':wh_id' => $wh_id,
        ];
        $model = \Yii::$app->get('db')->createCommand($sql, $queryParam)->queryAll();
        return $model;
    }

}
?>