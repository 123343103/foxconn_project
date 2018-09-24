<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/8/11
 * Time: 下午 02:31
 */

namespace app\modules\warehouse\controllers;


use app\controllers\BaseActiveController;
use app\modules\common\models\BsDistrict;
use app\modules\warehouse\models\OrdLgst;
use app\modules\warehouse\models\search\OrdLgstSearch;
use Yii;
use yii\base\Exception;
use yii\data\SqlDataProvider;

class LogisticsorderController extends BaseActiveController
{
    public $modelClass = true;

    public function actionIndex()
    {
        $searchModel = new OrdLgstSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    //修改物流订单
    public function actionUpdate($id)
    {
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $ordlgst = OrdLgst::findOne($id);
            if($ordlgst!=null)
            {
                $ordlgst->load($post);
                if (!$ordlgst->save()) {
                    throw new Exception('修改物流订单失败', json_encode($ordlgst->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }
            $transaction->commit();
            return $this->success("修改成功");
        }
        catch (Exception $e)
        {
            return $this->error($e->getMessage());
        }
    }

    //获取物流订单信息
    public function actionLogOrderInfo($id)
    {
        $sql = "SELECT
	ol.ord_lg_id,

IF (
	ol.sr_type = 1,
	'客户订单',
	'采购单'
) sr_type,

IF (
	ol.TRANSMODE = 5,
	'陆运',
	'快递'
) TRANSMODE,
 ol.lgst_date,
 ol.check_status,
 CASE ol.trade_type
WHEN 201 THEN
	'标准快递'
WHEN 202 THEN
	'经济快递'
WHEN 203 THEN
	'优速快递'
WHEN 301 THEN
	'普通陆运'
ELSE
	'定日达陆运'
END AS trade_type,

IF (ol.YN_FJJ = 1, '是', '否') YN_FJJ,
 ol.trade_act,

IF (ol.YN_Fee = 1, '是', '否') YN_Fee,

IF (ol.YN_ins = 1, '是', '否') YN_ins,

IF (
	ol.ie_type = 1,
	'进口',
	'出口'
) ie_type,
 ol.kd_car,
 LEFT (ol.crt_date, 10) crt_date,
 ol.cost_no,
 ol.marks,
 ol.shp_cntct,
 ol.shp_tel,
 ol.shp_marks,
 ol.rcv_cntct,
 ol.rcv_tel,
 ol.rcv_marks,
 hr.staff_name,
 oi.ord_no,
 od.sapl_quantity,
 od.suttle,
 wh.wh_code,
 wh.wh_name,
 wh.district_id,
 wh.wh_addr,
 bc.cust_id,
 bc.cust_code,
 bc.cust_sname,
 bc.cust_district_1,
 bc.cust_readress,
 pa.part_no,
 pa.pdt_origin,
 pa.tp_spec,
 pt.pdt_name,
 pu.bsp_svalue unit,
 pk.pdt_qty,
 pk.plate_num,
 pk.pdt_weight,
 pk.pdt_length,
 pk.pdt_width,
 pk.pdt_height,
 pk.pck_type,
 '销售包装' as pck_type
FROM
	wms.ord_lgst ol,
	wms.ord_lgst_dt old,
	wms.o_whpdt ow,
	erp.hr_staff hr,
	wms.bs_wh wh,
	oms.ord_info oi,
	oms.ord_dt od,
	erp.crm_bs_customer_info bc,
	pdt.bs_partno pa,
	pdt.bs_product pt,
	erp.bs_pubdata pu,
	pdt.bs_pack pk
WHERE
	ol.ord_lg_id = old.ord_lg_id
AND ol.o_whpkid = ow.o_whpkid
AND ol.crter = hr.staff_id
AND ow.o_whid = wh.wh_id
AND ol.ord_id = oi.ord_id
AND old.ord_dt_id = od.ord_dt_id
AND oi.cust_code = bc.cust_code
AND od.prt_pkid = pa.prt_pkid
AND pa.pdt_pkid = pt.pdt_pkid
AND pt.unit = pu.bsp_id
AND pa.prt_pkid = pk.prt_pkid
AND pk.pck_type = 2
AND ol.ord_lg_id = {$id}";
           // file_put_contents('log.txt', Yii::$app->get('wms')->createCommand($sql)->getRawSql());
            return Yii::$app->getDb('wms')->createCommand($sql)->queryAll();
    }

    //获取详细地址
    public function actionAddress($id)
    {
        $address_id =$id;//最后一阶的地址代码
        $addr[] = "";
        while ($address_id > 0) {
            $addr_info = BsDistrict::findOne($address_id);
            $address_id = $addr_info->district_pid;
            $addr[] = $addr_info->district_name;
        }
        return implode("", array_reverse($addr));
    }
    //物流进度信息
    public function actionLogInfo($lgno)
    {
        $sql="SELECT
DISTINCT 
lo.orderno,
lo.forwardcode,
lo.expressno,
lo.station,
lo.onwaystatus,
lo.onwaystatus_date,
lo.delivery_man,
lo.remark,
lo.carrierno,
lo.create_by,
lo.createdate,
oi.ord_no
FROM
	wms.ord_logistic_log lo
LEFT JOIN wms.ord_logistics_shipment sh ON lo.ship_id = sh.ship_id
LEFT JOIN wms.o_whpdt_dt od ON sh.o_whdtid = od.o_whdtid
LEFT JOIN oms.ord_info oi ON sh.saleorder_id=oi.ord_id
--  物流进度表的物流订单必须在物流出货表出现，物流出货表中的料号必须在出库详情标准出现
WHERE lo.orderno=sh.orderno and sh.part_no=od.part_no 
and lo.orderno='{$lgno}'";
        $totalCount= Yii::$app->getDb('wms')->createCommand("select count(*) from ( {$sql} ) a")->queryScalar();
        $provider = new SqlDataProvider([
            'db' => 'db',
            'sql' => $sql,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => empty($post['rows']) ? false : $post['rows']
            ]
        ]);
        return [
            'rows' => $provider->getModels(),
            'total' => $provider->totalCount
        ];
    }
}