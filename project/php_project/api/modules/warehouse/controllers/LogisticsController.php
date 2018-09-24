<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/21
 * Time: 上午 10:08
 */

namespace app\modules\warehouse\controllers;

use app\controllers\BaseActiveController;
use app\modules\sale\models\OrdCust;
use app\modules\sale\models\OrdDt;
use app\modules\warehouse\models\OrdLogisticLog;
use app\modules\warehouse\models\OrdLogisticsShipment;
use app\modules\warehouse\models\OWhpdtDt;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

class LogisticsController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\SaleOrderl';

//首页搜索商品信息
    public function actionIndex()
    {
        $Param = Yii::$app->request->queryParams;
        $queryParam = [];
        if (!empty($Param['soh_code']) || !empty($Param['ORDERNO']) || !empty($Param['invh_code'])) {
            $sql = "SELECT
	oi.ord_no,
pr.pdt_name,
od.sapl_quantity,
wpt.o_whnum,
wpt.part_no,
wpt.o_whdtid,
od.gross_weight,
os.os_name,
CASE od.transport
WHEN 201 THEN
	'标准快递'
WHEN 202 THEN
	'经济快递'
WHEN 203 THEN
	'优速快递'
WHEN 301 THEN
	'普通陆运'
WHEN 302 THEN 
    '定日达陆运'
ELSE
	''
END AS transport,
wp.logistics_no
FROM
	wms.o_whpdt_dt wpt
LEFT JOIN wms.o_whpdt wp ON wpt.o_whpkid = wp.o_whpkid
LEFT JOIN oms.ord_info oi ON wp.ord_id=oi.ord_id
LEFT JOIN oms.ord_status os ON oi.os_id=os.os_id
LEFT JOIN oms.ord_dt od ON oi.ord_id=od.ord_id
LEFT JOIN pdt.bs_partno pa ON od.prt_pkid=pa.prt_pkid
LEFT JOIN pdt.bs_product pr ON pa.pdt_pkid=pr.pdt_pkid
where oi.os_id=6 and wpt.part_no=pa.part_no";// -- 必须要是出货的料号才被查询
            if (!empty($Param['soh_code'])) {
                $queryParam[':ord_no'] = $Param['soh_code'];
                $sql .= " and oi.ord_no=:ord_no";
            } else if (!empty($Param['ORDERNO'])) {
                $queryParam[':ORDERNO'] = $Param['ORDERNO'];
                $sql .= " and wp.logistics_no=:ORDERNO";
            } else if (!empty($Param['invh_code'])) {
                $queryParam[':o_whcode'] = $Param['invh_code'];
                $sql .= " and wp.o_whcode=:o_whcode";
            }
            $totalCount = Yii::$app->get('db')->createCommand("select count(*) from ( {$sql} ) a", $queryParam)->queryScalar();
            $provider = new SqlDataProvider([
                'db' => 'db',
                'sql' => $sql,
                'params' => $queryParam,
                'totalCount' => $totalCount,
                'pagination' => [
                    'pageSize' => empty($post['rows']) ? false : $post['rows']
                ]
            ]);
            $list['rows'] = $provider->getModels();
            $list['total'] = $provider->totalCount;
            return $list;
        } else {
            $list['rows'] = [];
            $list['total'] = 0;
            return $list;
        }
    }

//获取物流进度信息
    public function actionLogInfo()
    {
        $Param = Yii::$app->request->queryParams;
        $queryParam = [];
        $sql = "SELECT
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
WHERE lo.orderno=sh.orderno and sh.part_no=od.part_no";
        if (!empty($Param['partno']) && !empty($Param['o_whdtid'])) {
            $queryParam[':part_no'] = $Param['partno'];
            $queryParam[':o_whdtid'] = $Param['o_whdtid'];
            $sql .= " AND od.part_no =:part_no AND od.o_whdtid =:o_whdtid ";
        }
        $totalCount = Yii::$app->get('db')->createCommand("select count(*) from ( {$sql} ) a", $queryParam)->queryScalar();
        $provider = new SqlDataProvider([
            'db' => 'db',
            'sql' => $sql,
            'params' => $queryParam,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => empty($post['rows']) ? false : $post['rows']
            ]
        ]);
        $shiomodel = new OrdLogisticsShipment();
        $orderno = $shiomodel->getOrderno($Param['partno'], $Param['o_whdtid']);
        return ['orderno' => $orderno,
            'rows' => $provider->getModels(),
            'total' => $provider->totalCount
        ];
//        $model = new OrdLogisticLogSearch();
//        $shiomodel = new OrdLogisticsShipment();
//        $queryParams = Yii::$app->request->queryParams;
//        $orderno = $shiomodel->getOrderno($queryParams['partno'], $queryParams['o_whdtid']);
//        $dataProvider = $model->search($queryParams);
//        return [
//            'orderno' => $orderno,
//            'rows' => $dataProvider->getModels(),
//            'total' => $dataProvider->totalCount
//        ];
    }

//添加物流进度信息
    public function actionAdd()
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->wms->beginTransaction();
            try {
                $OrdLogInfo = new OrdLogisticLog();
                $OrdLogInfo->load($data);
                if ($OrdLogInfo->TRANS_MODE == 0 && empty($OrdLogInfo->CARRIERNO)) {
                    throw new \Exception(json_encode('车牌号不能为空！', JSON_UNESCAPED_UNICODE));
                }                //$OrdShipInfo = new OrdLogisticsShipment();
                else if ($OrdLogInfo->TRANS_MODE == 1 && empty($OrdLogInfo->EXPRESSNO)) {
                    throw new \Exception(json_encode('快递单号不能为空！', JSON_UNESCAPED_UNICODE));
                }
                $OrdLogInfo->ship_iid = (int)($this->findLogid() + 1);//主键
                $OrdLogInfo->ship_id = (int)$data['ship_id'];
                $OrdLogInfo->itemno = (int)($this->findItem($OrdLogInfo['orderno'])) + 1;
                $OrdLogInfo->CREATE_BY = $OrdLogInfo['CREATE_BY'];
                $OrdLogInfo->CREATEDATE = date('Y-m-d H:i:s', time());
                if (!$OrdLogInfo->save()) {
                    throw new \Exception(json_encode($OrdLogInfo->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return $this->success();
    }

//修改物流进度信息
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->wms->beginTransaction();
            try {
                $OrdLogInfo = OrdLogisticLog::findOne($id);
                $OrdLogInfo->load($data);
                if ($OrdLogInfo->TRANS_MODE == 0 && empty($OrdLogInfo->CARRIERNO)) {
                    throw new \Exception(json_encode('车牌号不能为空！', JSON_UNESCAPED_UNICODE));
                }                //$OrdShipInfo = new OrdLogisticsShipment();
                else if ($OrdLogInfo->TRANS_MODE == 1 && empty($OrdLogInfo->EXPRESSNO)) {
                    throw new \Exception(json_encode('快递单号不能为空！', JSON_UNESCAPED_UNICODE));
                }
                $OrdLogInfo->UPDATE_DATE = date('Y-m-d H:i:s', time());
                if (!$OrdLogInfo->save()) {
                    throw new \Exception(json_encode($OrdLogInfo->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return $this->success();
    }

    //添加物流出货信息
    public function actionShipment()
    {
        $owhpdtdt = new OWhpdtDt();
        $orddt = new OrdDt();
        if (Yii::$app->request->isPost) {
            $OrdshipInfo = new OrdLogisticsShipment();
            $data = Yii::$app->request->post();
            $OrdshipInfo->load($data);
            //在物流出货表中根据商品ID和出货详情id获取相应的出货信息
            $invlresult = $owhpdtdt->getOwhpdtdtinfo($OrdshipInfo['part_no'], $OrdshipInfo['o_whdtid']);
            $sql = "select prt_pkid from pdt.bs_partno where part_no='{$invlresult['part_no']}'";
            $prtpkid = Yii::$app->getDb('pdt')->createCommand($sql)->queryOne();
            $orderresult = $orddt->getOrderinfo($prtpkid, $invlresult['ord_id']);
            $transaction = Yii::$app->wms->beginTransaction();
            try {
                $OrdshipInfo->ship_id = (int)($this->findShipmentid()) + 1;//主键
                $OrdshipInfo->orderno_item = (int)($this->findOrderItem($OrdshipInfo['orderno'])) + 1;//项次
                $OrdshipInfo->orderno = $OrdshipInfo['orderno'];
                $OrdshipInfo->o_whpkid = $invlresult['o_whpkid'];
                $OrdshipInfo->o_whdtid = $invlresult['o_whdtid'];
                $OrdshipInfo->qty = $OrdshipInfo['qty'];
                $OrdshipInfo->part_no = $OrdshipInfo['part_no'];
                $OrdshipInfo->update_date = date('Y-m-d H:i:s', time());
                $OrdshipInfo->create_at = date('Y-m-d H:i:s', time());
                $OrdshipInfo->create_by = $OrdshipInfo['create_by'];
                $OrdshipInfo->saleorder_id = $orderresult['ord_id'];
                $OrdshipInfo->saleorder_detailid = $orderresult['ord_dt_id'];
                $OrdshipInfo->freight = $orderresult['freight'];
                if (!$OrdshipInfo->save()) {
                    throw new \Exception(json_encode($OrdshipInfo->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
            return $this->success();
        } else {
            return 0;
        }

    }

    //获取最大的物流进度ID
    public function findLogid()
    {
        $query = OrdLogisticLog::find()->select('MAX(ship_iid) ship_iid');
        $dataProvider = new ActiveDataProvider([
            'query' => $query]);
        $modelWhAdm = $dataProvider->getModels();
        return $modelWhAdm[0]['ship_iid'];
    }

    //获取最大的物流出货ID
    public function findShipmentid()
    {
        $query = OrdLogisticsShipment::find()->select('MAX(ship_id) ship_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query]);
        $modelWhAdm = $dataProvider->getModels();
        return $modelWhAdm[0]['ship_id'];
    }

//判断物流单号是否在物流出货表中存在
    public function actionOrderno($partno, $o_whdtid)
    {
        $shiomodel = new OrdLogisticsShipment();
        $queryParams = Yii::$app->request->queryParams;
        $orderno = $shiomodel->getOrderno($partno, $o_whdtid);
        return [
            'orderno' => $orderno
        ];
    }

//获取最大的物流出货单号项次
    public function findOrderItem($orderno)
    {
        $query = OrdLogisticsShipment::find()->select('MAX(orderno_item) orderno_item');
        $dataProvider = new ActiveDataProvider([
            'query' => $query]);
        $query->andFilterWhere(['orderno' => $orderno]);
        $modelWhAdm = $dataProvider->getModels();
        return $modelWhAdm[0]['orderno_item'];
    }

    //获取最大的物流进度单号项次
    public function findItem($orderno)
    {
        $query = OrdLogisticLog::find()->select('MAX(itemno) itemno');
        $dataProvider = new ActiveDataProvider([
            'query' => $query]);
        $query->andFilterWhere(['orderno' => $orderno]);
        $modelWhAdm = $dataProvider->getModels();
        return $modelWhAdm[0]['itemno'];
    }

    /*
   * 获取一条数据
   */
    public function actionModels($id)
    {
        $result = OrdLogisticLog::getLogInfoOne($id);
        return $result;
    }

    //获取客户信息
    public function actionCrmInfo($orderno, $kdno, $o_whcode)
    {
        if (!empty($orderno) || !empty($kdno) || !empty($o_whcode)) {
            $sql = "SELECT
DISTINCT
	cb.cust_sname,
cb.cust_code,
cb.cust_contacts,
cb.cust_tel2,
CONCAT(bd.district_name,d.district_name,b.district_name,cb.cust_readress) address
FROM
	oms.ord_info oi
LEFT JOIN  erp.crm_bs_customer_info cb ON oi.cust_code=cb.cust_code
LEFT JOIN erp.bs_district b ON cb.cust_district_1=b.district_id
LEFT JOIN erp.bs_district d ON b.district_pid=d.district_id
LEFT JOIN erp.bs_district bd ON d.district_pid=bd.district_id
LEFT JOIN wms.ord_logistics_shipment sh ON sh.saleorder_id=oi.ord_id
LEFT JOIN wms.o_whpdt ow ON ow.ord_id=oi.ord_id";
            if (!empty($orderno)) {
                $sql .= " and oi.ord_no='{$orderno}'";
            }
            if (!empty($kdno)) {
                $sql .= " and sh.orderno='{$kdno}'";
            }
            if (!empty($o_whcode)) {
                $sql .= " and ow.o_whcode='{$o_whcode}'";
            }
            return Yii::$app->getDb('oms')->createCommand($sql)->queryOne();
        }
    }
}