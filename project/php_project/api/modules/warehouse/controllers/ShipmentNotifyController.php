<?php

namespace app\modules\warehouse\controllers;

use app\modules\common\models\BsAddress;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsCategoryUnit;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsForm;
use app\modules\common\models\BsPayCondition;
use app\modules\common\models\BsPayment;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsTransaction;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use app\modules\sale\models\OrdInfo;
use app\modules\sale\models\SaleInoutnoteh;
use app\modules\sale\models\SaleInoutnotel;
use app\modules\sale\models\SaleOrderh;
use app\modules\sale\models\SaleOrderl;
use app\modules\sale\models\SalePurchasenoteh;
use app\modules\sale\models\SalePurchasenotel;
use app\modules\sale\models\SaleStaging;
use app\modules\sale\models\SaleCustrequireH;
use app\modules\sale\models\SaleCustrequireL;
use app\modules\sale\models\search\SaleOrderhSearch;
use app\modules\sale\models\search\SaleCustrequireHSearch;
use app\modules\sale\models\search\SaleCustrequireLLSearch;
use app\modules\system\models\SysParameter;
use app\modules\warehouse\models\BsDeliverymethod;
use app\modules\warehouse\models\BsPck;
use app\modules\warehouse\models\BsPckDt;
use app\modules\warehouse\models\BsTransport;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\SalePickingh;
use app\modules\warehouse\models\SalePickingl;
use app\modules\warehouse\models\search\ShipmentNotifySearch;
use app\modules\warehouse\models\ShpNt;
use app\modules\warehouse\models\ShpNtDt;
use Yii;
use  app\controllers\BaseActiveController;
use yii\base\Exception;
use yii\data\SqlDataProvider;
use yii\helpers\Json;


class ShipmentNotifyController extends BaseActiveController
{
    public $modelClass = 'app\modules\sale\models\OrdDt';

    public function actionIndex()
    {
        $params = \Yii::$app->request->queryParams;
        $queryParam=[];
        $where = " WHERE 1=1 ";
//        $where .= " and ss.wh_id in(";
//        $i = 0;
//        foreach ($this->actionGetWhJurisdiction($params['staff_id']) as $key => $val) {
//            $queryParam[':wh_id' . $key] = $val['wh_id'];
//            $i++;
//            if ($i == count($this->actionGetWhJurisdiction($params['staff_id']))) {
//                $where .= ':wh_id' . $key;
//            } else {
//                $where .= ':wh_id' . $key . ',';
//            }
//        }
//        $where = $where . ")";
        if(!empty($params["note_no"])){
            $note_no=str_replace(' ', '', $params["note_no"]);
            $where=$where."and ss.note_no like '%$note_no%' ";
        }
        if(!empty($params["corporate"])){
            $queryParam[':corporate']=$params['corporate'];
            $where=$where."and ss.corporate=:corporate ";
        }
        if(!empty($params["status"])){
            //$queryParam[':status']=$params['status'];
            $where=$where."and ss.status='{$params['status']}' ";
        }
//        if(!empty($params["wh_id"])){
//            $queryParam[':wh_id']=$params['wh_id'];
//            $where=$where."and ss.wh_id=:wh_id ";
//        }
        if(!empty($params["ord_no"])){
            $ord_no=str_replace(' ', '', $params["ord_no"]);
            $where=$where."and ss.ord_no like '%$ord_no%' ";
        }
        if(!empty($params["cust_sname"])){
            $cust_sname=str_replace(' ', '', $params["cust_sname"]);
            $where=$where."and ss.cust_sname='$cust_sname' ";
        }
        if (!empty($params['starttime'])) {
            $queryParam[':starttime'] = date('Y/m/d', strtotime($params['starttime']));
            $where .= " and ss.n_time >= :starttime  ";
        }
        if (!empty($params['endtime'])) {
            $queryParam[':endtime'] = date('Y/m/d', strtotime($params['endtime'] . '+1 day'));
            $where .= " and  ss.n_time < :endtime ";
        }
        $sql="select ss.* from(
SELECT
    s.note_pkid,
	s.note_no,
	s.n_time,
	s.ord_no,
	s.`status`,
	s.urg,
	t.business_value AS ord_type,
	s.corporate,
	o.cust_sname,
	c.company_name,
	f.staff_name AS pickor,
	f1.staff_name AS operator,
s.soh_id,
s.tran_sname,
s.tran_stauts
FROM
	(
		SELECT
			s.note_no,
			p.bsp_svalue AS status,
			o.ord_no,
			DATE_FORMAT(s.n_time,'%Y/%m/%d')n_time,
			s.urg,
			o.corporate,
			o.ord_type,
			o.ord_id,
			s.operator,
			s.pickor,
			s.note_pkid,
			s.soh_id,
			o.cust_code,
			t.tran_sname,
			t.tran_stauts
		FROM
			wms.shp_nt s
		LEFT JOIN oms.ord_info o ON s.soh_id = o.ord_id
		LEFT JOIN erp.bs_pubdata p ON p.bsp_id = s.STATUS
		LEFT JOIN wms.bs_transport t on t.tran_code=s.trans_mode and t.tran_stauts = '1'
	) s
LEFT JOIN erp.bs_company c ON c.company_id = s.corporate
LEFT JOIN erp.bs_business_type t ON s.ord_type = t.business_type_id
LEFT JOIN erp.crm_bs_customer_info o ON o.cust_code = s.cust_code
LEFT JOIN erp.hr_staff f ON f.staff_id = s.pickor
LEFT JOIN erp.hr_staff f1 ON f1.staff_id = s.operator)ss
 {$where}  ";
        $sql.="order by ss.n_time desc,ss.note_no DESC";
        $totalCount =Yii::$app->getDb('wms')->createCommand("select count(*) from ( {$sql} ) a ", $queryParam)->queryScalar();
        $provider = new SqlDataProvider([
            "sql" => $sql,
            "totalCount" => $totalCount,
            "params" => $queryParam,
            "pagination" => [
                "page" => isset($params["page"]) ? $params["page"] - 1 : 0,
                "pageSize" => isset($params["rows"]) ? $params["rows"] : 10,
            ]
        ]);
//        return $provider->sql;
        return [
            "rows" => $provider->models,
            "total" => $provider->totalCount
        ];
//        $search = new ShipmentNotifySearch();
//        $dataProvider = $search->search(Yii::$app->request->queryParams);
////       return $dataProvider;
//        $model = $dataProvider->getModels();
//        $list['rows'] = $model;
//        $list["total"] = $dataProvider->totalCount;
//        return $list;
    }

//    // 订单详情
//    public function actionView($id)
//    {
//        $sql="";
//        $model = new ShipmentNotifySearch();
////        $model->searchOrderH($id);
//        $dataProviderH = current($model->searchNotifyH($id)->getModels());
//        $dataProviderL = $model->searchNotifyL($id)->getModels();
//        $data['products'] = $dataProviderL;
//        $data = array_merge($dataProviderH, $data);
//        return $data;
//    }
    public function actionModels($id)
    {
        return $this->findModel($id);
    }
    protected  function findModel($id){
        //出货通知单信息
        $sql="select s.*,e.business_value,y.company_name ,o.cust_sname
        from( SELECT t.note_pkid,t.note_no,a.bsp_svalue as `status`,f.staff_name,
        (select organization_name from erp.hr_organization where f.organization_code=organization_code)organization_name,
        t.n_time,o.ord_no,o.corporate, o.receipt_Address,o.receipt_areaid,o.cust_code,o.receipter_Tel,receipter,
        t.urg,t.yn_cancel,t.cancel_rs,o.ord_type,t1.tran_sname,
				t1.tran_stauts from wms.shp_nt t 
        LEFT JOIN erp.bs_pubdata a on t.`status`=a.bsp_id 
        LEFT JOIN erp.hr_staff f on f.staff_id=t.operator 
        LEFT JOIN oms.ord_info o on o.ord_id=t.soh_id 
        LEFT JOIN wms.bs_transport t1 on t1.tran_code=t.trans_mode and t1.tran_stauts = '1'
        )s 
        LEFT JOIN erp.crm_bs_customer_info o ON o.cust_code = s.cust_code
        LEFT JOIN erp.bs_business_type e on e.business_type_id=s.ord_type 
        LEFT JOIN erp.bs_company y on y.company_id=s.corporate 
        where s.note_pkid=:id";
        $basicinfo = Yii::$app->db->createCommand($sql)->bindValue(':id', $id)->queryOne();
        //商品信息
        $sql2="select ss.*,t.pdt_name,(select brand_name_cn  FROM pdt.bs_brand where t.brand_id=brand_id) brand_name_cn
            ,(select bsp_svalue from erp.bs_pubdata where t.unit=bsp_id)unit from 
        (select s.*,o.pdt_pkid,o.tp_spec 
        from (SELECT 
            o.ord_id,
            o.ord_dt_id,
            FORMAT(snt.nums, 2) nums,
            o.sapl_remark,
            snt.part_no,
            FORMAT(o.sapl_quantity, 2) sapl_quantity,
            DATE_FORMAT(o.request_date, '%Y/%m/%d') request_date,
            FORMAT(o.uprice_tax_o, 5) uprice_tax_o,
            FORMAT(o.tprice_tax_o, 2) tprice_tax_o,
            (select d.bdm_sname from wms.bs_deliverymethod d where d.bdm_id=o.distribution)bdm_sname,
        (select h.wh_name from wms.bs_wh h where h.wh_id=snt.wh_id)wh_name,
          snt.wh_id,
          snt.marks
        FROM(
        select DISTINCT t.soh_id from wms.shp_nt t, wms.shp_nt_dt dt 
        where t.note_pkid=dt.note_pkid
         AND t.note_pkid=:id) a,oms.ord_dt o,wms.shp_nt_dt snt
        where a.soh_id=o.ord_id
        AND o.ord_dt_id=snt.sol_id
        and snt.note_pkid=:id)s LEFT JOIN pdt.bs_partno o on s.part_no=o.part_no)ss
        LEFT JOIN pdt.bs_product t on t.pdt_pkid=ss.pdt_pkid";
        $productinfo =Yii::$app->db->createCommand($sql2)->bindValue(':id', $id)->queryAll();
        $address_id = $basicinfo["receipt_areaid"];
        $addr[] = $basicinfo["receipt_Address"];
        while ($address_id>0){
            $addr_info=BsDistrict::findOne($address_id);
            $address_id = $addr_info['district_pid'];
            $addr[] = $addr_info['district_name'];
        }
        $addr = array_reverse($addr);
        $str = implode('',$addr);
        $basicinfo["receipt_Address"]=$str;
        $infoall = [$basicinfo, $productinfo];
        if($infoall!==null){
            return $infoall;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
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
    // 下拉列表
    public function actionGetDownList()
    {
        $downList = [];
        // 订单类型
        $downList['orderType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'order'])->all();
        // 交易法人
        $downList['corporate'] = BsCompany::find()->select(['company_id', 'company_name'])->all();
        // t通知单状态
        $downList['status'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::SHP_TYPE])->all();
        // 付款方式
        $downList['payment'] = BsPayment::find()->select(['pac_id', 'pac_code', 'pac_sname'])->all();
        // 支付类型
        $downList['payType'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::PAY_TYPE])->all();
        // 付款条件
        $downList['payCondition'] = BsPayCondition::find()->select(['pat_id', 'pat_sname'])->all();
        // 交易方式（交易模式）
        $downList['pattern'] = BsTransaction::find()->select(['tac_id','tac_sname'])->all();
        // 订单来源
        $downList['orderFrom'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::ORDER_FROM])->all();
        // 发票类型
        $downList['invoiceType'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::CRM_INVOICE_TYPE])->all();
        // 交易币别
        $downList['currency'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::SUPPLIER_TRADE_CURRENCY])->all();
        // 运输方式
        $downList['transport'] = BsTransport::find()->select(['tran_id', 'tran_code', 'tran_sname'])->all();
        // 配送方式
        $downList['dispatching'] = BsDeliverymethod::find()->select(['bdm_id', 'bdm_code', 'bdm_sname'])->all();
        // 仓库信息
        $downList['warehouse'] = BsWh::find()->select(['wh_id', 'wh_code', 'wh_name'])->all();
        return $downList;
    }

    // 点击主表获取子表商品信息
    public function actionGetProducts()
    {
        $params = Yii::$app->request->queryParams;
        $model = new ShipmentNotifySearch();
        $model = $model->searchOrderProducts($params);
        return $model;
    }

    // 生成拣货单
    public function actionCreatePick()
    {
        $post = Yii::$app->request->post();
        // 查找信息
        $transaction = Yii::$app->wms->beginTransaction();
        try {
            $result = array();
            foreach ($post['BsPckDt'] as $k => $v) {
                $result[$v['wh_id']]['PckDt'][] = $v;
                $shpntdt=ShpNtDt::findOne($v["shpn_pkid"]);
                $shpntdt->wh_id=$v["wh_id"];
                if (!$shpntdt->save()) {
                    throw new Exception(json_encode($shpntdt->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }
            $result = array_values($result);//将新数组重新排序
            $shpnt=ShpNt::findOne($post['id']);
            $bspid = Yii::$app->db->createCommand("SELECT bsp_id FROM erp.bs_pubdata where bsp_stype='TZDZT' and bsp_svalue='已处理' ")->queryOne();
            $shpnt->status=$bspid['bsp_id'];
            $shpnt->pic_ip= Yii::$app->request->getUserIP();
            $shpnt->pic_date=date('Y-m-d H:i:s', time());
            $shpnt->pickor=$post["ShpNt"]["pickor"];
            if (!$shpnt->save()) {
                throw new Exception(json_encode($shpnt->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            for ($i = 0; $i < count($result); $i++) {
                $bspckdtt = $result[$i]['PckDt'];//采购单详情数组
                $bspck=new BsPck();
                $bspck->note_pkid=$post['id'];
                $bspck->pck_no= BsForm::getCode("bs_pck", $bspck);
                $bspck->status=4;
                $bspck->wh_id=$bspckdtt[0]['wh_id'];
                $bspck->urg=$shpnt->urg;
                if (!$bspck->save()) {
                    throw new Exception(json_encode($bspck->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                for ($j = 0; $j < count($bspckdtt); $j++) {
                    $quantityinterval = Yii::$app->db->createCommand("select o.upp_num,o.low_num from (SELECT * FROM erp.bs_pubdata a where a.bsp_stype='blsz_djlx' and a.bsp_svalue='拣货单' and a.bsp_status=10)s,erp.bs_ratio o where o.ratio_type=s.bsp_id and o.yn=1")->queryOne();
                    $maxnum=$bspckdtt[$j]["nums"]*(1+$quantityinterval["upp_num"]);//最大值
                    $minnum=$bspckdtt[$j]["nums"]*(1-$quantityinterval["low_num"]);//最小值
                    if ($bspckdtt[$j]["pck_nums"]>$maxnum||$bspckdtt[$j]["pck_nums"]<$minnum)
                    {
                        throw new \Exception(json_encode('料号'.$bspckdtt[$j]["part_no"].'的拣货数量不能大于'.$maxnum.'并且不能小于'.$minnum.'！', JSON_UNESCAPED_UNICODE));
                    }
                    $bspckdt=new BsPckDt();
                    $bspckdt->shpn_pkid=$bspckdtt[$j]["shpn_pkid"];
                    $bspckdt->pck_pkid=$bspck->pck_pkid;
                    $bspckdt->pck_nums=$bspckdtt[$j]["pck_nums"];
                    $bspckdt->marks=$bspckdtt[$j]["marks"];
                    $bspckdt->sol_id=$bspckdtt[$j]["sol_id"];
                    $bspckdt->part_no=$bspckdtt[$j]["part_no"];
                    $bspckdt->req_num=$bspckdtt[$j]["req_num"];
                    if(!$bspckdt->save()){
                        throw new Exception(json_encode($bspckdt->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            $transaction->commit();
            return $this->success('生成拣货单成功！');
//            if($post["BsPckDt"]!=null){
//
//                foreach ($post['BsPckDt'] as $key => $val) {
//                    $quantityinterval = Yii::$app->db->createCommand("SELECT upp_num,low_num FROM erp.bs_ratio where ratio_type=109121 and yn=1")->queryOne();
////                    $maxnum=$quantityinterval["upp_num"]*$val["nums"];//最大值
//                    $maxnum=$val["nums"]*(1+$quantityinterval["upp_num"]);//最大值
////                    $minnum=$quantityinterval["low_num"]*$val["nums"];//最小值
//                    $minnum=$val["nums"]*(1-$quantityinterval["low_num"]);//最小值
//                    if ($val["pck_nums"]>$maxnum||$val["pck_nums"]<$minnum)
//                    {
//                        throw new \Exception(json_encode('料号'.$val["part_no"].'的拣货数量不能大于'.$maxnum.'并且不能小于'.$minnum.'！', JSON_UNESCAPED_UNICODE));
//                    }
//                    $bspckdt=new BsPckDt();
//                    $bspckdt->shpn_pkid=$val["shpn_pkid"];
//                    $bspckdt->pck_pkid=$bspck->pck_pkid;
//                    $bspckdt->pck_nums=$val["pck_nums"];
//                    $bspckdt->marks=$val["marks"];
//                    $bspckdt->sol_id=$val["sol_id"];
//                    $bspckdt->part_no=$val["part_no"];
//                    $bspckdt->req_num=$val["req_num"];
//                    if(!$bspckdt->save()){
//                        throw new Exception(json_encode($bspckdt->getErrors(), JSON_UNESCAPED_UNICODE));
//                    }
//                }
//            }
//            $transaction->commit();
//            return $this->success('生成拣货单成功！');
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    // 取消通知
    public function actionCancelNotify($id)
    {
        $_succ="操作失败";
        $data = Yii::$app->request->post();
        $shpnt = ShpNt::findOne($id);
        $shpnt->yn_cancel=1;
        $bspid = Yii::$app->db->createCommand("SELECT bsp_id FROM erp.bs_pubdata where bsp_stype='TZDZT' and bsp_svalue='已取消' ")->queryOne();
        $shpnt->status = $bspid["bsp_id"];
        $shpnt->cancel_date=date('Y-m-d H:i:s', time());//取消時間
        $shpnt->load($data);
        if (!$shpnt->validate()) {
            return $this->error(json_encode($shpnt->getErrors(),JSON_UNESCAPED_UNICODE));
        }
        $ordinfo=OrdInfo::findOne($shpnt->soh_id);
        $ordinfo->os_id=2;
        if (!$ordinfo->validate()) {
//            return $this->success('操作成功');
            return $this->error(json_encode($ordinfo->getErrors(),JSON_UNESCAPED_UNICODE));
        }
        $shpnt->save(false);
        $ordinfo->save(false);
        return $this->success('操作成功');
//        return $_succ;
//        return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
    }
    public function actionPickinfo($id){
        return $this->findPickinfo($id);
    }
    //生成拣货单
    protected function findPickinfo($id){
//        $sql="SELECT s.*,a.bsp_svalue  FROM (SELECT t.wh_id,h.wh_name,h.wh_code,h.wh_attr,t.urg FROM wms.shp_nt t
//        LEFT JOIN wms.bs_wh h on t.wh_id=h.wh_id WHERE t.note_pkid=:id)s
//        LEFT JOIN erp.bs_pubdata a on a.bsp_id=s.wh_attr";
//        $whinfo = Yii::$app->db->createCommand($sql)->bindValue(':id', $id)->queryOne();
        $sql1="
       select ss.*,t.pdt_name
            ,(select bsp_svalue from erp.bs_pubdata where t.unit=bsp_id)unit from 
(select s.*,o.pdt_pkid
from (SELECT 
snt.sol_id,
snt.shpn_pkid,
o.prt_pkid,
	FORMAT(snt.nums, 2) nums,
	snt.part_no,
(select h.wh_name from wms.bs_wh h where h.wh_id=snt.wh_id)wh_name,
  snt.wh_id
FROM(
select DISTINCT t.soh_id from wms.shp_nt t, wms.shp_nt_dt dt 
where t.note_pkid=dt.note_pkid
 AND t.note_pkid=:id) a,oms.ord_dt o,wms.shp_nt_dt snt
where a.soh_id=o.ord_id
AND o.ord_dt_id=snt.sol_id
and snt.note_pkid=:id)s LEFT JOIN pdt.bs_partno o on s.part_no=o.part_no)ss
LEFT JOIN pdt.bs_product t on t.pdt_pkid=ss.pdt_pkid";
        $productinfo= Yii::$app->db->createCommand($sql1)->bindValue(':id', $id)->queryAll();
//        $infoall = [$whinfo, $productinfo];
        if($productinfo!==null){
            return $productinfo;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    //导出
    public function actionExport()
    {
        $params = \Yii::$app->request->queryParams;
        $queryParam=[];
        $where = " WHERE 1=1 ";
//        $where .= " and ss.wh_id in(";
//        $i = 0;
//        foreach ($this->actionGetWhJurisdiction($params['staff_id']) as $key => $val) {
//            $queryParam[':wh_id' . $key] = $val['wh_id'];
//            $i++;
//            if ($i == count($this->actionGetWhJurisdiction($params['staff_id']))) {
//                $where .= ':wh_id' . $key;
//            } else {
//                $where .= ':wh_id' . $key . ',';
//            }
//        }
//        $where = $where . ")";
        if(!empty($params["note_no"])){
            $note_no=str_replace(' ', '', $params["note_no"]);
            $where=$where."and ss.note_no like '%$note_no%' ";
        }
        if(!empty($params["corporate"])){
            $queryParam[':corporate']=$params['corporate'];
            $where=$where."and ss.corporate=:corporate ";
        }
        if(!empty($params["status"])){
            //$queryParam[':status']=$params['status'];
            $where=$where."and ss.status='{$params['status']}' ";
        }
//        if(!empty($params["wh_id"])){
//            $queryParam[':wh_id']=$params['wh_id'];
//            $where=$where."and ss.wh_id=:wh_id ";
//        }
        if(!empty($params["ord_no"])){
            $ord_no=str_replace(' ', '', $params["ord_no"]);
            $where=$where."and ss.ord_no='$ord_no' ";
        }
        if(!empty($params["cust_sname"])){
            $cust_sname=str_replace(' ', '', $params["cust_sname"]);
            $where=$where."and ss.cust_sname='$cust_sname' ";
        }
        if (!empty($params['starttime'])) {
            $queryParam[':starttime'] = date('Y/m/d', strtotime($params['starttime']));
            $where .= " and ss.n_time >= :starttime ";
        }
        if (!empty($params['endtime'])) {
            $queryParam[':endtime'] = date('Y/m/d', strtotime($params['endtime'] . '+1 day'));
            $where .= " and  ss.n_time < :endtime ";
        }
        $sql="select ss.* from(
SELECT
    s.note_pkid,
	s.note_no,
	s.n_time,
	s.ord_no,
	s.`status`,
	s.urg,
	t.business_value AS ord_type,
	s.corporate,
	o.cust_sname,
	c.company_name,
	f.staff_name AS pickor,
	f1.staff_name AS operator,
s.soh_id,
s.tran_sname,
s.tran_stauts
FROM
	(
		SELECT
			s.note_no,
			p.bsp_svalue AS status,
			o.ord_no,
			DATE_FORMAT(s.n_time,'%Y/%m/%d')n_time,
			s.urg,
			o.corporate,
			o.ord_type,
			o.ord_id,
			s.operator,
			s.pickor,
			s.note_pkid,
			s.soh_id,
			o.cust_code,
			t.tran_sname,
			t.tran_stauts
		FROM
			wms.shp_nt s
		LEFT JOIN oms.ord_info o ON s.soh_id = o.ord_id
		LEFT JOIN erp.bs_pubdata p ON p.bsp_id = s. STATUS
		LEFT JOIN wms.bs_transport t on t.tran_code=s.trans_mode and t.tran_stauts = '1'
	) s
LEFT JOIN erp.bs_company c ON c.company_id = s.corporate
LEFT JOIN erp.bs_business_type t ON s.ord_type = t.business_type_id
LEFT JOIN erp.crm_bs_customer_info o ON o.cust_code = s.cust_code
LEFT JOIN erp.hr_staff f ON f.staff_id = s.pickor
LEFT JOIN erp.hr_staff f1 ON f1.staff_id = s.operator)ss
{$where} ";
        $sql.="order by ss.n_time desc,ss.note_no DESC";
        $dataProvider=new SqlDataProvider([
            'sql' => $sql,
            'params'=>$queryParam,
            'pagination'=>[
                'pageSize'=>empty($params['rows'])?'':$params['rows']
            ],
        ]);
//        return [
//            "rows" => $dataProvider->models,
//        ];
        return $dataProvider->getModels();
//        $model=new ShipmentNotifySearch();
//        $params = \Yii::$app->request->queryParams;
//        $dataProvider=$model->searchApply($staff_id,$params);
//        return $dataProvider->getModels();
    }

    //弹出框中的仓库信息
    public function actionSelectWhid($staff_id,$partno){
//        $sql="select s.* from(SELECT
//	                bw.wh_id,
//	                bw.wh_code,
//	                bw.wh_name,
//	                bp.part_id,
//	                bp.part_code,
//	                bp.part_name
//              FROM
//	                erp.r_user_wh_dt uwd
//              LEFT JOIN erp.`user` u ON u.user_id = uwd.user_id
//              LEFT JOIN wms.bs_wh bw ON bw.wh_id = uwd.wh_pkid
//              LEFT JOIN wms.bs_part bp ON bp.part_id = uwd.part_id where u.staff_id=:staff_id)s
//							left join pdt.bs_partno o on o.wh_id=s.wh_id
//              WHERE  o.part_no=:partno GROUP BY s.wh_id";
        $sql='select s.*,o.part_no from (SELECT 
	                bw.wh_id,
	                bw.wh_code,
	                bw.wh_name
              FROM
	                erp.r_user_wh_dt uwd
              LEFT JOIN erp.`user` u ON u.user_id = uwd.user_id
              LEFT JOIN wms.bs_wh bw ON bw.wh_id = uwd.wh_pkid
              where u.staff_id=:staff_id)s
							left join wms.bs_wh_invt o on o.wh_code=s.wh_code
              WHERE  o.part_no=:partno GROUP BY s.wh_code';
        $queryParam = [
            ':partno' => $partno,
            ':staff_id'=>$staff_id
        ];
        $model = \Yii::$app->get('db')->createCommand($sql, $queryParam)->queryAll();
        return $model;
    }
}
