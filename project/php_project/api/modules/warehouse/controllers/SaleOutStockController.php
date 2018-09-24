<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/12/23
 * Time: 上午 11:01
 */
namespace app\modules\warehouse\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsCurrency;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsForm;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\BsPack;
use app\modules\sale\models\OrdInfo;
use app\modules\warehouse\models\BsInvt;
use app\modules\warehouse\models\BsPck;
use app\modules\warehouse\models\BsSt;
use app\modules\warehouse\models\LInvtRe;
use app\modules\warehouse\models\OrdLgst;
use app\modules\warehouse\models\OrdLgstDt;
use app\modules\warehouse\models\OWhpdt;
use yii\data\SqlDataProvider;
use yii;

class SaleOutStockController extends BaseActiveController
{
    public $modelClass = '\app\modules\warehouse\models\OWhpdt';

    //首页
    public function actionIndex()
    {
        $params =Yii::$app->request->queryParams;
        $bindParams = [];
       $whid= $this->actionGetWhJurisdiction($params['staff_id']);
        $sql="SELECT 
 ow.delivery_type,
 ow.o_whpkid,
 ow.o_whcode,
 CASE ow.o_whstatus
 WHEN 0 THEN '待出库'
 WHEN 1 THEN '待收货'
WHEN 2 THEN '已收货'
WHEN 3 THEN '已出库'
WHEN 4 THEN '已取消'
WHEN 5 THEN '待提交'
WHEN 6 THEN '审核中'
WHEN 7 THEN '审核完成'
WHEN 8 THEN '驳回'
ELSE '已上架'
END AS o_whstatus,
 ow.relate_packno,
 ow.o_whid,
 bw.wh_name,
 ow.logistics_no,
 ow.address,
 ow.district_id,
 ow.reciver,
 ow.reciver_tel,
 ow.o_date,
 oi.ord_no,
 bc.company_name,
 oi.cust_contacts,
cbc.cust_sname,
 hs.staff_name,
 bbt.business_type_desc
FROM wms.o_whpdt ow
              LEFT JOIN oms.ord_info oi ON oi.ord_id = ow.ord_id
              LEFT JOIN erp.crm_bs_customer_info cbc ON cbc.cust_code=oi.cust_code
              LEFT JOIN erp.bs_business_type bbt ON bbt.business_type_id = oi.ord_type
              LEFT JOIN erp.bs_company bc ON bc.company_id = oi.corporate
              LEFT JOIN wms.bs_wh bw ON bw.wh_id = ow.o_whid
              LEFT JOIN erp.hr_staff hs ON hs.staff_id = ow.creator 
WHERE  ow.buss_type = '1'";
        if(count($whid)>0)
        {
            $sql .= " and ow.o_whid in(";
            foreach ($whid as $key => $val){
                $sql .= "'{$val['wh_id']}'" . ',';
            }
            $sql = trim($sql, ',') . ')';
        }
        else{
            $sql .= " and ow.o_whid in('0')";
        }
//        $sql .= " and ow.o_whid in(";
//        $i = 0;
//        foreach ($this->actionGetWhJurisdiction($params['staff_id']) as $key => $val) {
//            $bindParams[':o_whid' . $key] = $val['wh_id'];
//            $i++;
//            if ($i == count($this->actionGetWhJurisdiction($params['staff_id']))) {
//                $sql .= ':o_whid' . $key;
////                $where=trim($where,',');
//            } else {
//                $sql .= ':o_whid' . $key . ',';
////                $where=trim($where,',').',';
//            }
//        }
//        $sql = $sql . ")";
        if (!empty($params["o_whcode"])) {
            $bindParams[':o_whcode']='%' . trim($params["o_whcode"]) . '%';
            $sql .= " and ow.o_whcode like :o_whcode";
        }
        if (!empty($params["o_whstatus"])) {
            //为了查询待出库数据将其查询状态更改为10 当查询状态为10时实际状态更改为0
            if($params["o_whstatus"]==10){
                $params["o_whstatus"]=0;
            }
            $bindParams[':o_whstatus']=trim($params["o_whstatus"]);
            $sql .= " and ow.o_whstatus=:o_whstatus";
        }
        if (!empty($params["company"])) {
            $bindParams[':company']=trim($params["company"]);
            $sql .= " and bc.company_id=:company";
        }
        if (!empty($params["o_whid"])) {
            $bindParams[':o_whid']=trim($params["o_whid"]);
            $sql .= " and ow.o_whid=:o_whid";
        }
        if (!empty($params["cust_contacts"])) {
            $bindParams[':cust_contacts']='%'.trim($params["cust_contacts"]).'%';
            $sql .= " and oi.cust_contacts like :cust_contacts";
        }
        if (!empty($params["ord_no"])) {
            $bindParams[':ord_no']='%'.trim($params["ord_no"]).'%';
            $sql .= " and oi.ord_no like :ord_no";
        }
        if(!empty($params["o_start_date"]))
        {
//            $bindParams[':o_start_date'] = $params['o_start_date'].' 00:00:00';//为了兼容IE
            $bindParams[':o_start_date'] = date('Y/m/d', strtotime($params['o_start_date']));
            $sql.=" and ow.o_date>=:o_start_date";
        }
        if(!empty($params["o_end_date"]))
        {
//            $bindParams[':o_end_date'] =  $params['o_end_date'].' 23:59:59';//为了兼容IE
            $bindParams[':o_end_date'] =  date('Y/m/d', strtotime($params['o_end_date'] . '+1 day'));
            $sql.=" and ow.o_date<=:o_end_date";
        }
        $sql.=" order by ow.o_date DESC";
        file_put_contents('log.txt', Yii::$app->get('wms')->createCommand($sql,$bindParams)->getRawSql());
        $totalCount=Yii::$app->get('db')->createCommand("select count(a.o_whpkid) from ({$sql})a",$bindParams)->queryScalar();
        $provider = new SqlDataProvider([
            'db' => 'db',
            "sql" => $sql,
            "totalCount" => $totalCount,
            "params" => $bindParams,
            "pagination" => [
                'pageSize' => empty($params['rows']) ? '' : $params['rows']
            ]
        ]);
        return [
            "rows" => $provider->getModels(),
            "total" => $provider->totalCount
        ];
    }
    //订单详情
    public function actionView($id)
    {
        $where = " WHERE  ow.buss_type = '1' AND ow.o_whpkid=:o_whpkid";
        $bindParams = [
            ':o_whpkid' => $id
        ];
        $fields = "	ow.o_whpkid,ow.can_reason,cbc.cust_sname,ow.ord_id,ow.o_whcode,ow.o_whstatus,ow.relate_packno,ow.o_whid,ow.remarks,hs1.staff_name create_name,bw.wh_name,bw.wh_code,bpub.bsp_svalue,ow.logistics_no,ow.address owaddress,ow.district_id owdistrict_id,ow.reciver,ow.reciver_tel,ow.o_date,oi.ord_no,bc.company_name,bc.company_code,oi.cust_contacts,oi.cust_code,hs.staff_name opp_name ,bbt.business_type_desc,hor.organization_name,bt.tran_sname, bw.district_id bwdistrict_id,bw.wh_addr bwwh_addr,ow.logistics_type";
        $sql = "SELECT {$fields} FROM wms.o_whpdt ow
              LEFT JOIN oms.ord_info oi ON oi.ord_id = ow.ord_id
              LEFT JOIN erp.bs_business_type bbt ON bbt.business_type_id = oi.ord_type
              LEFT JOIN erp.crm_bs_customer_info cbc ON cbc.cust_code=oi.cust_code
              LEFT JOIN erp.bs_company bc ON bc.company_id = oi.corporate
              LEFT JOIN wms.bs_wh bw ON bw.wh_id = ow.o_whid
              LEFT JOIN erp.bs_pubdata bpub ON bpub.bsp_id = bw.wh_attr
              LEFT JOIN erp.hr_staff hs ON hs.staff_id = ow.opp_id
              LEFT JOIN erp.hr_staff hs1 ON hs1.staff_id = ow.creator
              LEFT JOIN wms.bs_transport bt ON bt.tran_id = ow.logistics_type
              LEFT JOIN erp.hr_organization hor ON hor.organization_id = ow.app_depart {$where}";
        $count = \Yii::$app->db->createCommand("select count(*) FROM wms.o_whpdt ow
              LEFT JOIN oms.ord_info oi ON oi.ord_id = ow.ord_id
              LEFT JOIN erp.bs_business_type bbt ON bbt.business_type_id = oi.ord_type
              LEFT JOIN erp.bs_company bc ON bc.company_id = oi.corporate
              LEFT JOIN wms.bs_wh bw ON bw.wh_id = ow.o_whid
              LEFT JOIN erp.bs_pubdata bpub ON bpub.bsp_id = bw.wh_attr
              LEFT JOIN erp.hr_staff hs ON hs.staff_id = ow.opp_id
              LEFT JOIN erp.hr_staff hs1 ON hs1.staff_id = ow.creator
              LEFT JOIN erp.hr_organization hor ON hor.organization_id = ow.app_depart
              LEFT JOIN wms.bs_transport bt ON bt.tran_id = ow.logistics_type
 {$where} group by ow.o_whpkid", $bindParams)->query()->count();
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

    //每笔出库单关联的商品信息
    public function actionChildData($id)
    {
        $where = "where owd.o_whpkid='" . $id . "'";
        $fields = "owd.o_whdtid,
        owd.part_no,
        owd.o_whnum,
        bbd.brand_name_cn,
        ow.o_date,
        owd.remarks,
        owd.req_num,
        bd.bdm_sname,
        IFNULL(convert(bpd.pck_nums,decimal(20,2)),0)pck_nums,
        bpd.L_invt_bach,
        bpd.st_id,
        bpdc.pdt_name,
        bp.tp_spec,
        bpc.price,
        pu.bsp_svalue unit_name,
        od.consignment_date";
        $sql = "SELECT DISTINCT {$fields} FROM wms.o_whpdt_dt owd
                LEFT JOIN wms.o_whpdt ow ON ow.o_whpkid = owd.o_whpkid
                LEFT JOIN oms.ord_dt od ON od.ord_id = ow.ord_id
                LEFT JOIN wms.bs_deliverymethod bd ON bd.bdm_id = ow.delivery_type
                LEFT JOIN wms.bs_pck_dt bpd ON bpd.pck_dt_pkid = owd.pck_dt_pkid
                LEFT JOIN pdt.bs_partno bp ON bp.part_no = owd.part_no
                LEFT JOIN pdt.bs_product bpdc ON bpdc.pdt_pkid = bp.pdt_pkid
                LEFT JOIN pdt.bs_brand bbd ON bbd.brand_id=bpdc.brand_id
                LEFT JOIN erp.bs_category_unit bcu ON bcu.id = bpdc.unit
                LEFT JOIN pdt.bs_price bpc ON bpc.prt_pkid = bp.prt_pkid
                LEFT JOIN erp.bs_pubdata pu ON bpdc.unit = pu.bsp_id
                LEFT JOIN pdt.bs_material bm ON bm.part_no = owd.part_no {$where}";

        $count = \Yii::$app->db->createCommand("select count(*) FROM wms.o_whpdt_dt owd
                                                LEFT JOIN wms.o_whpdt ow ON ow.o_whpkid = owd.o_whpkid
                                                LEFT JOIN oms.ord_dt od ON od.ord_id = ow.ord_id
                                                LEFT JOIN wms.bs_deliverymethod bd ON bd.bdm_id = ow.delivery_type
                                                LEFT JOIN wms.bs_pck_dt bpd ON bpd.pck_dt_pkid = owd.pck_dt_pkid
                                                LEFT JOIN pdt.bs_partno bp ON bp.part_no = owd.part_no
                                                LEFT JOIN pdt.bs_product bpdc ON bpdc.pdt_pkid = bp.pdt_pkid
                                                LEFT JOIN pdt.bs_brand bbd ON bbd.brand_id=bpdc.brand_id
                                                LEFT JOIN erp.bs_category_unit bcu ON bcu.id = bpdc.unit
                                                LEFT JOIN pdt.bs_price bpc ON bpc.prt_pkid = bp.prt_pkid
                                                LEFT JOIN erp.bs_pubdata pu ON bpdc.unit = pu.bsp_id
                                                LEFT JOIN pdt.bs_material bm ON bm.part_no = owd.part_no {$where} group by owd.o_whdtid", null)->query()->count();
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

    //取消发货
    public function actionCancel($id,$staff_id)
    {
        date_default_timezone_set("Asia/Shanghai");
        try {
            $params = \Yii::$app->request->post();
            $sql="select t.relate_packno from wms.o_whpdt t where t.o_whpkid={$id}";
            $pckno=Yii::$app->get('wms')->createCommand($sql)->queryOne();
            if (!(OWhpdt::updateAll(["o_whstatus" => OWhpdt::OUTSTOCK_CANCEL,
                "can_reason" => $params["reason"], 'opp_id' => $staff_id,
                'opp_date' => date("Y-m-d H:i:s"), 'opp_ip' => \Yii::$app->request->getUserIP()],
                ["o_whpkid" => $id]))&&!(BsPck::updateAll(["status"=>4,"pck_IP"=>\Yii::$app->request->getUserIP()],
                    ["pck_no"=>$pckno['relate_packno']]))) {
                throw new \Exception("取消失败");
            }
            return $this->success("取消成功");
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    //自提
    public function actionSince($id, $staff_id)
    {
        date_default_timezone_set("Asia/Shanghai");
        try {
            if (!(OWhpdt::updateAll(["o_whstatus" => OWhpdt::ALREADY_WAREHOUSE, 'opp_id' => $staff_id, 'opp_date' => date("Y-m-d H:i:s"), 'opp_ip' => \Yii::$app->request->getUserIP()], ["o_whpkid" => $id]))) {
                throw new \Exception("自提收货失败");
            }
            $sql="SELECT
	sum(owd.o_whnum) o_whnum
FROM
	wms.o_whpdt ow,
	wms.o_whpdt_dt owd,
	oms.ord_info oi,
	oms.ord_dt od,
	pdt.bs_partno pa
WHERE
	ow.o_whpkid = owd.o_whpkid
AND ow.ord_id = oi.ord_id
AND oi.ord_id = od.ord_id
AND od.prt_pkid = pa.prt_pkid
AND owd.part_no = pa.part_no
AND ow.o_whpkid = {$id}";
            $whnum=Yii::$app->getDb('wms')->createCommand($sql)->queryAll();//出货的数量
            $sql1="SELECT
	sum(od.sapl_quantity) sapl_quantity
FROM
	wms.o_whpdt ow,
	oms.ord_info oi,
	oms.ord_dt od
where ow.ord_id=oi.ord_id
AND oi.ord_id=od.ord_id
AND ow.o_whpkid={$id}";
            $quantity=Yii::$app->getDb('wms')->createCommand($sql1)->queryAll();//订单的数量
            $sql2="SELECT ow.ord_id from wms.o_whpdt ow where ow.o_whpkid={$id}";
            $ord=Yii::$app->getDb('wms')->createCommand($sql2)->queryOne();
            if((double)$whnum[0]['o_whnum']==(double)$quantity[0]['sapl_quantity'])
            {
                $modelord = OrdInfo::findOne($ord['ord_id']);
                $modelord->os_id=7;//订单已收货
                $modelord->opp_date=date("Y-m-d H:i:s");
                $modelord->opper=$staff_id;
                $modelord->opp_id = \Yii::$app->request->getUserIP();
                if (!$modelord->save()) {
                    throw new \Exception('修改订单状态失败', json_encode($modelord->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }
            return $this->success("自提收货成功");
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    //重新上架
    public function actionPutAway($id, $staff_id = '')
    {
        date_default_timezone_set("Asia/Shanghai");
        if ($data = Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $staff = HrStaff::findOne($data['update_by']);
            try {
                if (!empty($data['arr'])) {
                    foreach ($data['arr'] as $key => $val) {
                        $st_id = explode(",", $val['st_codes']);
                        $store_num = explode(",", $val['store_num']);
                        for ($i = 0; $i < count($st_id); $i++) {
                            $st_code=BsSt::findOne($st_id[$i]);
                            $list = new LInvtRe();
                            $list->st_code = $st_code['st_code'];
                            $list->part_no = $val['part_no'];
                            $list->wh_code = $data['wh_code'];
                            $list->wh_name = $data['wh_name'];
                            $list->batch_no = $val['L_invt_bach'];
                            $list->l_r_no = $data['o_whcode'];
                            $list->pdt_name = $val['pdt_name'];
                            $list->unit_name = $val['unit_name'];
                            $list->opp_date = date('Y-m-d h:i:s');
                            $list->lock_nums = $store_num[$i];
                            $list->l_types = 10;
                            $list->opper = $staff['staff_code'] . '-' . $staff['staff_name'];
                            $list->yn = 0;
                            if (!$list->save()) {
                                throw new \Exception(json_encode($list->getErrors(), JSON_UNESCAPED_UNICODE));
                            }
                        }
                    }
                    $owhpdt = OWhpdt::findOne($id);
                    $owhpdt->o_whstatus = OWhpdt::HAVE_BEEN_ABANDONED;
                    $owhpdt->opp_id = $data['update_by'];
                    $owhpdt->opp_date = date('Y-m-d');
                    $owhpdt->opp_ip = $data['op_ip'];
                    if (!$owhpdt->save()) {
                        throw new \Exception(json_encode($owhpdt->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
                $transaction->commit();
                return $this->success('操作成功');
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getFile() . "--" . $e->getLine() . "--" . $e->getMessage());
            }
        }
        $sql = "select a.o_whpkid,
                      a.o_whcode,
                     b.wh_code,
                     b.wh_id,
                     b.wh_name,
                     c.bsp_svalue wh_attr
              from wms.o_whpdt a
              left join wms.bs_wh b on b.wh_id = a.o_whid
              left join erp.bs_pubdata c on c.bsp_id = b.wh_attr
              where a.o_whpkid = :id 
              and a.o_whstatus = 4";
        $data['arr1'] = Yii::$app->db->createCommand($sql, [':id' => $id])->queryOne();
        if (!empty($data['arr1'])) {
            $sql = "select a.staff_id,
                         a.staff_name
                  from erp.hr_staff a
                  where a.staff_id = :id";
            $staffInfo = Yii::$app->db->createCommand($sql, [':id' => $staff_id])->queryOne();
            if (!empty($staffInfo)) {
                $data['arr1'] = array_merge($data['arr1'], $staffInfo);
            }
            $sql = "SELECT
	a.o_whdtid,
	a.part_no,
	c.pdt_name,
	c.unit,
	a.req_num,
	a.pck_dt_pkid,
	a.o_whnum,
	a.remarks,
	pu.bsp_svalue unit_name,
	bpd.st_id,
	bpd.pck_nums,
	bpd.L_invt_bach
FROM
	wms.o_whpdt_dt a
LEFT JOIN pdt.bs_partno b ON b.part_no = a.part_no
LEFT JOIN wms.bs_pck_dt bpd ON bpd.pck_dt_pkid = a.pck_dt_pkid
LEFT JOIN pdt.bs_product c ON c.pdt_pkid = b.pdt_pkid
JOIN erp.bs_pubdata pu ON c.unit = pu.bsp_id
WHERE a.o_whpkid = :id";
            $data['arr2'] = Yii::$app->db->createCommand($sql, [':id' => $data['arr1']['o_whpkid']])->queryAll();
        }
        return $data;
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
            "company" => OWhpdt::getBsCompany()

        ];
    }

    //生成物流订单
    public function actionGeneratingLogistics($id)
    {
        date_default_timezone_set("Asia/Shanghai");
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->wms->beginTransaction();
        try {
            $ordlgst = new OrdLgst;
            $ordlgst->load($post);
            $staff = HrStaff::findOne($post['update_by']);
            $ordlgst->lg_no = BsForm::getCode("ord_lgst", $ordlgst);
            $ordlgst->o_whpkid = $id;
            if (!$ordlgst->save()) {
                throw new \Exception('新增物流订单失败', json_encode($ordlgst->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            else {
                $ordlgid=$ordlgst->ord_lg_id;
                $orddt=$post['OrdLgstDt'];
                $owhnum=0;
                //出库单的出库总数量
                foreach ($orddt as $ke=>$va)
                {
                    $owhnum+=$va['o_whnum'];
                }
                foreach ($orddt as $k=>$v)
                {
                    $ordlgstdt=new OrdLgstDt();
                    $ordlgstdt->ord_lg_id=$ordlgid;
                    $ordlgstdt->ord_dt_id=$v['ord_dt_id'];//订单详细ID
                    $ordlgstdt->o_whdtid=$v['o_whdtid'];//出库单详细ID
                    $ordlgstdt->nums=$owhnum;//物流订单数量
                    if (!$ordlgstdt->save()) {
                        throw new \Exception(json_encode($ordlgstdt->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
                $view = $this->actionView($id);
                $child = $this->actionChildData($id);
                if (!empty($child['rows'])) {
                    foreach ($child['rows'] as $key => $val) {
                        $st_id = explode(",", $val['st_id']);
                        $lock_num = explode(",", $val['pck_nums']);
                        for ($i = 0; $i < count($st_id); $i++) {
                            $bs_st = BsSt::findOne($st_id[$i]);
                            if(!empty($val['pck_nums']))
                            {
                                $lock_nums=$lock_num[$i];
                            }
                            else
                            {
                                $lock_nums=0;
                            }
                            $list = new LInvtRe();
                            $list->l_types = 2;
                            $list->wh_code = $view['rows'][0]['wh_code'];
                            $list->wh_name = $view['rows'][0]['wh_name'];
                            $list->st_code = $bs_st['st_code'];
                            $list->l_r_no = $view['rows'][0]['o_whcode'];
                            $list->pdt_name = $val['pdt_name'];
                            $list->batch_no = $val['L_invt_bach'];
                            $list->part_no = $val['part_no'];
                            $list->unit_name = $val['unit_name'];
                            $list->lock_nums = '-' . $lock_nums;
                            $list->opp_date = date('Y-m-d h:i:s');
                            $list->opper = $staff['staff_code'] . '-' . $staff['staff_name'];
                            $list->yn = 0;
                            if (!$list->save()) {
                                throw new \Exception(json_encode($list->getErrors(), JSON_UNESCAPED_UNICODE));
                            }
                        }
                    }
                }
                $model = OWhpdt::findOne($id);
                $model->load($post);
                $model->o_whstatus = '3';
                $model->logistics_no = $ordlgst->lg_no;
                $model->opp_date = date("Y-m-d H:i:s");
                $model->opp_ip = \Yii::$app->request->getUserIP();
                if (!$model->save()) {
                    throw new \Exception('修改出库单失败', json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                $sql="SELECT
	sum(owd.o_whnum) o_whnum
FROM
	wms.o_whpdt ow,
	wms.o_whpdt_dt owd,
	oms.ord_info oi,
	oms.ord_dt od,
	pdt.bs_partno pa
WHERE
	ow.o_whpkid = owd.o_whpkid
AND ow.ord_id = oi.ord_id
AND oi.ord_id = od.ord_id
AND od.prt_pkid = pa.prt_pkid
AND owd.part_no = pa.part_no
AND ow.o_whpkid = {$id}";
                $whnum=Yii::$app->getDb('wms')->createCommand($sql)->queryAll();
                $sql1="SELECT
	sum(od.sapl_quantity) sapl_quantity
FROM
	wms.o_whpdt ow,
	oms.ord_info oi,
	oms.ord_dt od
where ow.ord_id=oi.ord_id
AND oi.ord_id=od.ord_id
AND ow.o_whpkid={$id}";
                $quantity=Yii::$app->getDb('wms')->createCommand($sql1)->queryAll();
                $sql2="SELECT ow.ord_id from wms.o_whpdt ow where ow.o_whpkid={$id}";
                $ord=Yii::$app->getDb('wms')->createCommand($sql2)->queryOne();
                if((double)$whnum[0]['o_whnum']==(double)$quantity[0]['sapl_quantity'])
                {
                    $modelord = OrdInfo::findOne($ord['ord_id']);
                    $modelord->os_id=6;//订单已出货
                    $modelord->opp_date=date("Y-m-d H:i:s");
                    $modelord->opper=$post['update_by'];
                    $modelord->opp_id = \Yii::$app->request->getUserIP();
                    if (!$modelord->save()) {
                        throw new \Exception('修改订单状态失败', json_encode($modelord->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
                $transaction->commit();
                return $this->success("新增成功",['ordlgid'=>$ordlgid]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //仓库权限管控
    public function actionGetWhJurisdiction($staff_id)
    {
        $sql = "SELECT
	                bw.wh_id,
	                bw.wh_code,
	                bw.wh_name,
	                bp.part_id,
	                bp.part_code,
	                bp.part_name
              FROM
	                erp.r_user_wh_dt uwd
              LEFT JOIN erp.`user` u ON u.user_id = uwd.user_id
              LEFT JOIN wms.bs_wh bw ON bw.wh_id = uwd.wh_pkid
              LEFT JOIN wms.bs_part bp ON bp.part_id = uwd.part_id
              WHERE u.staff_id =:staff_id GROUP BY bw.wh_id";
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
        return implode(array_reverse($addr));
    }

    //获取车种
    public function actionGetCar()
    {
        $data = BsPubdata::find()->where(['bsp_stype' => 'scwldcz'])->all();
        return $data;
    }

    //生成物流订单页面单身列表数据
    public function actionChildOrdData($id)
    {
        $sql = "SELECT  
  a.ord_id,
  a.ord_no,
  a.part_no,
  a.o_whdtid,
  bpa.tp_spec,
	bpd.pdt_name,
	bpa.pdt_origin,
	pu.bsp_svalue unit,
	od.sapl_quantity,
	od.ord_dt_id,
	od.tax_freight,
	od.freight,
	od.suttle,
	od.gross_weight,
	bp.plate_num,
	bp.pdt_length,
	bp.pdt_width,
	bp.pdt_height,
	bp.pdt_weight,
	bp.pdt_qty,
	bp.pck_type
FROM(SELECT
DISTINCT
	 ow.ord_id ord_id,
   oi.ord_no,
   owd.part_no,
   owd.o_whdtid
FROM
	wms.o_whpdt ow,
  wms.o_whpdt_dt owd,
  oms.ord_info oi
where owd.o_whpkid = ow.o_whpkid
AND  oi.ord_id = ow.ord_id
AND ow.o_whpkid={$id}
)a,
oms.ord_dt od,
pdt.bs_partno bpa,
pdt.bs_product bpd,
erp.bs_pubdata pu,
pdt.bs_pack bp
where od.ord_id=a.ord_id
AND bpa.prt_pkid = od.prt_pkid
AND bpd.pdt_pkid = bpa.pdt_pkid
AND bpd.unit = pu.bsp_id
AND bp.prt_pkid = od.prt_pkid
AND bpa.part_no=a.part_no";
        $sql .= " GROUP BY bpa.part_no";
        //file_put_contents('log.txt', Yii::$app->get('wms')->createCommand($sql)->getRawSql());
        return Yii::$app->getDb('wms')->createCommand($sql)->queryAll();
    }

    //币种
    public function actionGetCur()
    {
        $data = BsCurrency::find()->select('cur_id,cur_code,cur_sname,cur_shortname,remarks')->all();
        return $data;
    }

    //获取生成物流订单的信息
    public function actionLogInfo($id){
        $queryParams=[];
        $sql="SELECT
    ow.o_whpkid,
	ow.district_id owdistrict_id,
	ow.address owaddress,
	ow.reciver,
	ow.reciver_tel,
	bw.district_id bwdistrict_id,
	bw.wh_addr bwwh_addr,
	ow.reciver,
	ow.reciver_tel,
	ow.logistics_type,
	ow.ord_id,
	bw.wh_name,
	bw.wh_code,
	cbc.cust_sname company_name,
	cbc.cust_code cust_contacts,
	bt.tran_sname
FROM
	wms.o_whpdt ow,
	oms.ord_info oi,
	wms.bs_wh bw,
	erp.crm_bs_customer_info cbc,
	wms.bs_transport bt
WHERE
	ow.ord_id = oi.ord_id
AND bw.wh_id = ow.o_whid
AND cbc.cust_code = oi.cust_code
AND ow.logistics_type=bt.tran_code
AND ow.buss_type = '1'
AND ow.o_whpkid = :id";
        $queryParams[':id'] = $id;
          $list['rows']=Yii::$app->getDb('db')->createCommand($sql,$queryParams)->queryAll();
        return $list;
//        $totalCount= Yii::$app->getDb('db')->createCommand("select count(*) from ({$sql})a",$queryParams)->queryAll();
//        $provider = new SqlDataProvider([
//            'db' => 'db',
//            'sql' => $sql,
//            'params' => $queryParams,
//            'totalCount' => $totalCount,
//            'pagination' => [
//                'pageSize' => empty($post['rows']) ? false : $post['rows']
//            ]
//        ]);
//        $list['rows'] = $provider->getModels();
//        $list['total'] = $provider->totalCount;
//        return $list;
    }

    //获取仓库资料和出仓费用
    public function actionPriceList($id)
    {
        $sql = "SELECT
	ow.o_whcode,
	ow.o_date,
	bw.wh_name,
	ow.o_whid,
	bw.wh_code,
	bc.company_name
FROM
	wms.o_whpdt ow
LEFT JOIN wms.bs_wh bw ON bw.wh_id = ow.o_whid
LEFT JOIN oms.ord_info oi ON oi.ord_id = ow.ord_id
LEFT JOIN erp.bs_company bc ON bc.company_id = oi.corporate
where ow.o_whpkid={$id}";
        $data = Yii::$app->getDb('wms')->createCommand($sql)->queryOne();

        $sql2 = "SELECT
	a.*, b.whpb_code,
	b.whpb_sname,
	b.stcl_description,
	c.cur_code,
	(
		SELECT
			COUNT(*)
		FROM
			wms.ic_inv_costlist
		WHERE
			whpl_id = a.whpl_id
	) AS count
FROM
	wms.wh_pricel a
LEFT JOIN wms.bs_wh_price b ON a.whpb_id = b.whpb_id
LEFT JOIN erp.bs_currency c ON a.whpb_curr = c.cur_id
LEFT JOIN wms.wh_price d ON d.whp_id = a.whp_id
WHERE
	d.wh_id = {$data['o_whid']}";
        $datas = Yii::$app->getDb('wms')->createCommand($sql2)->queryAll();
        $sql3 = "SELECT
	a.*, b.whpb_code,
	b.whpb_sname,
	b.stcl_description,
	c.cur_code,
	(
		SELECT
			COUNT(*)
		FROM
			wms.ic_inv_costlist
		WHERE
			whpl_id = a.whpl_id
	) AS count
FROM
	wms.wh_pricel a
LEFT JOIN wms.bs_wh_price b ON a.whpb_id = b.whpb_id
LEFT JOIN erp.bs_currency c ON a.whpb_curr = c.cur_id
LEFT JOIN wms.wh_price d ON d.whp_id = a.whp_id ";
        $datass = Yii::$app->getDb('wms')->createCommand($sql3)->queryAll();
        return [
            'owhpdt' => $data,
            'cost' => $datas,
            'costs' => $datass
        ];
    }

    //获取物流信息
    public function actionGetLogList($id)
    {
        $sql = "SELECT
blc.log_cmp_name,
blc.log_cont_pho,
oll.FORWARDCODE,
oll.EXPRESSNO,
oll.STATION,
oll.ONWAYSTATUS,
oll.TRANSACTIONID,
oll.ONWAYSTATUS_DATE,
oll.DELIVERY_MAN,
oll.REMARK, 
oll.CUSTOMERID,
oll.CARRIERNO
FROM
	wms.ord_logistic_log oll
LEFT JOIN wms.bs_log_cmp blc ON blc.log_code=oll.FORWARDCODE
LEFT JOIN wms.o_whpdt ow ON ow.logistics_no = oll.orderno
where ow.o_whpkid={$id} ORDER BY oll.ONWAYSTATUS_DATE";
        $data = Yii::$app->getDb('wms')->createCommand($sql)->queryAll();
        return $data;
    }
}