<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/13
 * Time: 上午 09:41
 */

namespace app\modules\purchase\controllers;


use app\classes\GetUserPermissions;
use app\classes\Trans;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsCurrency;
use app\modules\common\models\BsForm;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\search\StaffSearch;
use app\modules\purchase\models\BsPrch;
use app\modules\purchase\models\BsPrchDt;
use app\modules\purchase\models\RReqPrch;
use app\modules\warehouse\models\BsReceipt;
use yii;
use yii\base\Exception;
use yii\data\SqlDataProvider;

class PurchaseBeforeWorkController extends BaseActiveController
{
    public $modelClass = 'app\modules\purchase\models\BsReq';

    public function actionIndex()
    {
        $post = Yii::$app->request->queryParams;
        $usercategory = new GetUserPermissions();
        $category = $usercategory->GetUserCategory($post['staff']);
        $queryParams = [];
        // 获取请购单为审核完成未生成采购单的数据和采购单已经取消的数据
        $sql = "SELECT DISTINCT
	        l.req_dt_id,
			l.req_nums,
			TRUNCATE (l.req_price, 5) req_price,
			l.req_no,
			l.req_id,
			LEFT (l.app_date, 10) app_date,
			l.area_id,
			l.req_status,
			l.part_no,
			l.tp_spec,
			l.pdt_name,
			l.unit,
			l.brand,
			l.addr
FROM
	(
		SELECT
			b.req_dt_id,
			b.req_nums,
			b.req_price,
			r.req_no,
			r.req_id,
			r.app_date,
			r.area_id,
			r.req_status,
			p.part_no,
			p.tp_spec,
			p.pdt_name,
			p.unit,
			p.brand,
			re.rcp_name addr
		FROM
			prch.bs_req_dt b,
			prch.bs_req r,
			pdt.bs_material p,
			wms.bs_receipt re
		WHERE
			b.req_id = r.req_id
		AND b.part_no = p.part_no
		AND r.addr = re.rcp_no
		AND r.req_status = 38
		AND (
			b.req_dt_id NOT IN (
				SELECT
					t.req_dt_id
				FROM
					prch.r_req_prch t
				WHERE
					r.req_status = 38
			)
			OR (
				b.req_dt_id IN (
					SELECT DISTINCT
						rt.req_dt_id
					FROM
						prch.r_req_prch rt,
						prch.bs_prch_dt bp,
						prch.bs_prch p
					WHERE
						rt.prch_dt_id = bp.prch_dt_id
					AND bp.prch_id = p.prch_id
					AND p.yn_can = 1
				)
			)
		)";//采购单取消之后可以再进行采购
        //该用户只能查看自己所对应的商品类别的商品信息
        if (count($category) > 0) {
            $sql .= "  and substring(p.category_no,1,6) in(";
            foreach ($category as $key => $val) {
                $sql .= "'{$val['catg_no']}'" . ',';
            }
            $sql = trim($sql, ',') . ')';
        } //该用户没有对应的商品类别权限,不能查看商品信息
        else {
            $sql .= "  and p.category_no in('0')";
        }
        //请购单号
        if (!empty($post['req_no'])) {
            $queryParams[':req_no'] = '%' . trim($post['req_no']) . '%';
            $sql .= " and r.req_no like :req_no";
        }
        //单据类型
        if (!empty($post['req_dct'])) {
            $queryParams[':req_dct'] = trim($post['req_dct']);
            $sql .= " and r.req_dct=:req_dct";
        }
        //收货中心
        if (!empty($post['addr'])) {
            $queryParams[':addr'] = trim($post['addr']);
            $sql .= " and r.addr=:addr";
        }
        //采购区域
        if (!empty($post['area_id'])) {
            $queryParams[':area_id'] = trim($post['area_id']);
            $sql .= " and r.area_id=:area_id";
        }
        //请购形式
        if (!empty($post['req_rqf'])) {
            $queryParams[':req_rqf'] = trim($post['req_rqf']);
            $sql .= " and r.req_rqf=:req_rqf";
        }
        //请购部门
        if (!empty($post['spp_dpt_id'])) {
            $queryParams[':spp_dpt_id'] = trim($post['spp_dpt_id']);
            $sql .= " and r.spp_dpt_id=:spp_dpt_id";
        }
        //所属法人
        if (!empty($post['leg_id'])) {
            $queryParams[':leg_id'] = trim($post['leg_id']);
            $sql .= " and r.leg_id=:leg_id";
        }
        //申请开始时间
        if (!empty($post['starttime'])) {
            $queryParams[':starttime'] = trim($post['starttime']).' 00:00:00';
            $sql .= " and r.app_date>:starttime";
        }
        //申请结束时间
        if (!empty($post['endtime'])) {
            $queryParams[':endtime'] = trim($post['endtime']).' 23:59:59';
            $sql .= " and r.app_date<=:endtime";
        }
        $sql .= " order by r.app_date desc)l
        WHERE
	l.req_dt_id NOT IN (
		SELECT
			rp.req_dt_id
		FROM
			prch.r_req_prch rp,
			prch.bs_prch_dt bp,
			prch.bs_prch pr,
			prch.bs_req rq
		WHERE
			l.req_dt_id = rp.req_dt_id
		AND rp.prch_dt_id = bp.prch_dt_id
		AND bp.prch_id = pr.prch_id
		AND pr.prch_status IN (40, 41, 44, 47, 48, 49) 
	) order by l.app_date desc"; //再次筛选是否再次进行采购过，如果采购过将不能再采购
        //file_put_contents('log.txt', Yii::$app->get('prch')->createCommand($sql, $queryParams)->getRawSql());
        // $result=Yii::$app->getDb('prch')->createCommand($sql,$queryParams)->queryAll();
        $totalCount = Yii::$app->get('db')->createCommand("select count(a.req_dt_id) from ( {$sql} ) a", $queryParams)->queryScalar();
        $provider = new SqlDataProvider([
            'db' => 'db',
            'sql' => $sql,
            'params' => $queryParams,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => empty($post['rows']) ? false : $post['rows']
            ]
        ]);
        $list['rows'] = $provider->getModels();
        $list['total'] = $provider->totalCount;
        return $list;
    }

    public function actionProcurement()
    {
        $params = Yii::$app->request->queryParams;
        //获取采购区域一致的采购料号信息
        $sql = "SELECT
	ls.*
FROM
	(
		SELECT
			d.req_dt_id,
			d.req_id,
			d.part_no,
			d.req_nums,
			d.req_price,
			d.spp_id,
			d.total_amount,
			d.exp_account,
			d.remarks,
			p.pkmt_id,
			p.tp_spec,
			p.pdt_name,
			p.brand,
			p.unit,
			re.req_no,
			re.area_id,
			pt.rcp_id,
			pt.rcp_name,
     f.factory_name
		FROM
			prch.bs_req_dt d
		JOIN prch.bs_req re ON d.req_id = re.req_id
		JOIN pdt.bs_material p ON d.part_no = p.part_no
		JOIN wms.bs_receipt pt ON re.addr = pt.rcp_no
    JOIN erp.bs_factory f ON re.area_id=f.factory_id
		WHERE
			d.req_dt_id IN (" . $params['id'] . ")) ls";
        //当选择的料号有多个时获取采购区域一样的料号信息
        // file_put_contents('log.txt', Yii::$app->get('prch')->createCommand($sql)->getRawSql());
        $db = Yii::$app->getDb('prch');
        $result = $db->createCommand($sql)->queryAll();
        return $result;
    }

    //请购转采购生产采购单
    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->prch->beginTransaction();
        $tr = new Trans();
        try {
            $arr=[];//采购单ID数组(多个采购单)
            $prchid="";//采购单ID (单个采购单)
            $result = array();
            //根据供应商ID相同的分组(供应商相同的为一个采购单)
            foreach ($post['BsPrchDt'] as $k => $v) {
                $result[$v['spp_id']]['PrchDt'][] = $v;
            }
            $result = array_values($result);//将新数组重新排序
            //return $result;
            //按照分组后的新数组进行保存
            for ($i = 0; $i < count($result); $i++) {
                $prchdt = $result[$i]['PrchDt'];//采购单详情数组
                $prch = $post['BsPrch'];//采购单主表数据
                $bsprch = new BsPrch();
                $bsprch->prch_no = BsForm::getCode("bs_prch", $bsprch);//采购单号
                $bsprch->req_dct = $prch['req_dct'];
                $bsprch->leg_id = $prch['leg_id'];
                $bsprch->area_id = $prch['area_id'];
                $bsprch->dpt_id = $prch['dpt_id'];
                $bsprch->apper = $prch['apper'];
                $bsprch->contact_info = $prch['contact_info'];
                $bsprch->rcp_id = $prch['rcp_id'];
                $bsprch->app_date = $prch['app_date'];
                $totaltax = 0;//未税总金额
                $tatal = 0;//总税率
                //获取采购详情的税率与计算金额
                for ($j = 0; $j < count($prchdt); $j++) {
                    $taxs = Yii::$app->db->createCommand("select t.tax_value from erp.bs_tax t where t.yn=1 and t.tax_pkid={$prchdt[$j]['tax']}")->queryOne();
                    $totaltax += (double)$prchdt[$j]['total_amount'];
                    $tatal += (double)$taxs['tax_value'];//税率
                }
                $bsprch->prch_status = 40;
                $bsprch->total_amount = $totaltax * (1 + $tatal);//含税总金额=未税总金额*(1+总税率)
                $bsprch->tax_fee = $totaltax * $tatal;//总税额=未税总金额*总税率
                $bsprch->yn_can = 0;
                $bsprch->remarks = $tr->t2c($prch['remarks']);
                $bsprch->app_ip = Yii::$app->request->getUserIP();
                if (!$bsprch->save()) {
                    throw new Exception(json_encode($bsprch->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                    array_push($arr,$bsprch->prch_id);
                //采购详情表数据保存
                for ($j = 0; $j < count($prchdt); $j++) {
                    $bsprchdt = new BsPrchDt();
                    $tax = Yii::$app->db->createCommand("select t.tax_value from erp.bs_tax t where t.yn=1 and t.tax_pkid={$prchdt[$j]['tax']}")->queryOne();
                    $price = round(((double)$prchdt[$j]['price']) * (1 + (double)$tax['tax_value']), 3);//含税单价
                    $bsprchdt->prch_id = $bsprch->prch_id;
                    $bsprchdt->part_no = $prchdt[$j]['part_no'];
                    $bsprchdt->deliv_date = $prchdt[$j]['deliv_date'];
                    $bsprchdt->spp_id = $prchdt[$j]['spp_id'];
                    $bsprchdt->prch_num = $prchdt[$j]['prch_num'];
                    $bsprchdt->price = $prchdt[$j]['price'];//未税单价
                    $bsprchdt->total_amount = $prchdt[$j]['total_amount'];//未税总金额
                    $bsprchdt->price_tax = $price;//含税单价
                    $bsprchdt->total_am_tax = round(($price * (int)$prchdt[$j]['prch_num']), 3);//含税总金额
                    $bsprchdt->tax = $prchdt[$j]['tax'];
                    $bsprchdt->pay_condition = $prchdt[$j]['pay_condition'];
                    $bsprchdt->cur_id = $prchdt[$j]['cur_id'];
                    $bsprchdt->goods_condition = $prchdt[$j]['goods_condition'];
                    if (!$bsprchdt->save()) {
                        throw new Exception(json_encode($bsprchdt->getErrors(), JSON_UNESCAPED_UNICODE));
                    }

                    $lstdtid = explode(",", $prchdt[$j]['dt_id']);
                    foreach ($lstdtid as $k => $v) {
                        //请购与采购关联(多对多的关系)
                        $rreqprch = new RReqPrch();
                        $rreqprch->req_dt_id = $v;
                        $rreqprch->prch_dt_id = $bsprchdt->prch_dt_id;
                        if (!$rreqprch->save()) {
                            throw new Exception(json_encode($rreqprch->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
            }
            $prchid = join($arr, ',');//将多个采购单号用逗号隔开
            $transaction->commit();
            return $this->success("新增成功", [
                //'id' => $bsprch->prch_id,
                'id'=>$prchid,
            ]);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    //获取单据类型与请购形式、付款方式
    public function actionDownList()
    {
        $downList['req_dct'] = BsPubdata::getList(BsPubdata::REQ_DCT); //单据类型
        $downList['req_rqf'] = BsPubdata::getList(BsPubdata::REQ_RQF); //请购形式
        $downList['pay_type'] = BsPubdata::getList(BsPubdata::PAY_TYPE);//付款方式
        return $downList;
    }

    //获取单据类型(采购前置列表)
    public function actionReqDcts()
    {
        //不需要Hub料号需求单
        $sql = "SELECT
	p.bsp_id,
  p.bsp_svalue
FROM
	erp.bs_pubdata p
WHERE
	p.bsp_stype = 'DJLX'
AND p.bsp_svalue NOT IN (
	'Hub料号需求单',
	'Hub料號需求單',
	'hub料號需求單',
	'hub料号需求单'
)";
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    //获取请购部门信息
    public function actionSppDpt()
    {
        $sppdpt = HrOrganization::getOrgAll();
        return $sppdpt;
    }

    //获取法人信息
    public function actionComMan()
    {
        return BsCompany::find()->select(['company_id', 'company_name'])->where(['company_status' => 10])->all();
    }

    //获取采购人员信息
    public function actionBuyerInfo()
    {
        $search = new StaffSearch();
        $post = Yii::$app->request->queryParams;
        $dataProvider = $search->buyinfosearch($post);
        $model = $dataProvider->getModels();
        $list = $model;
        return $list;
        //return HrStaffShow::find()->where(['staff_id'=>$id])->one();
    }

    //获取料号与供应商所对应的数据
    public function actionSppPartno()
    {
        $post = Yii::$app->request->queryParams;
        $sql = "SELECT
DISTINCT 
	f.part_no,
	f.payment_terms,
	su.spp_code,
	su.spp_fname,
    su.group_code,
    su.spp_id
FROM
	pdt.pdtprice_pas f
LEFT JOIN pdt.bs_material m ON f.part_no=m.part_no
LEFT JOIN spp.bs_supplier su ON f.supplier_code=su.group_code
WHERE
	f.part_no ='{$post['id']}'
AND su.spp_status=3 AND f.effective_date<=CURDATE() AND f.expiration_date>CURDATE()";
        if (!empty($post['keyword'])) {
            $post['keyword'] = str_replace(['%', '_'], ['\%', '\_'], $post['keyword']);
            $queryParams[':spp_fname'] = '%' . $post['keyword'] . '%';
            $sql .= " AND f.supplier_name like :spp_fname";
        }
        $db = Yii::$app->getDb('pdt');
        $result = $db->createCommand($sql, empty($queryParams) ? [] : $queryParams)->queryAll();
        return $result;
    }

    //获取单据类型(采购信息确认页面)
    public function actionReqDct($id)
    {
        return BsPubdata::getBsPubdataOne($id);
    }

    //根据id获取具体的法人
    public function actionGetComname($id)
    {
        return BsCompany::find()->select(['company_name', 'company_id'])->where(['company_status' => 10, 'company_id' => $id])->one();
    }

    //获取币别
    public function actionCurrency()
    {
        return BsCurrency::find()->select(['cur_id', 'cur_code'])->all();
    }

    //获取收货中心
    public function actionReceipt()
    {
        return BsReceipt::find()->select(['rcp_id', 'rcp_no', 'rcp_name'])->where(['rcp_status' => 'Y'])->all();
    }

    //获取采购区域
    public function actionBuyAddr($staff)
    {
        $userpermissions = new GetUserPermissions();
        $area = $userpermissions->GetUserFactory($staff);
        $sql = "select f.factory_id,f.factory_name from bs_factory f";
        if (count($area) > 0) {
            $sql .= "   where f.factory_id in(";
            foreach ($area as $key => $val) {
                $sql .= $val['area_pkid'] . ',';
            }
            $sql = trim($sql, ',') . ')';
        }
        return Yii::$app->getDb('db')->createCommand($sql)->queryAll();
    }

    //获取交货条件
    public function actionDelivery()
    {
        $post = Yii::$app->request->queryParams;
        $sql = "SELECT
                DISTINCT 
	               f.trading_terms
                FROM
	               pdt.pdtprice_pas f
                WHERE
	               f.part_no='{$post['partno']}' 
	               and f.supplier_code='{$post['sppcode']}' 
	               and f.payment_terms='{$post['payterms']}'";
        return Yii::$app->getDb('pdt')->createCommand($sql)->queryAll();
    }

//获取采购单价以及币别
    public function actionPrice()
    {
        $post = Yii::$app->request->queryParams;
        $sql = "SELECT
	f.pk_id
FROM
	pdt.pdtprice_pas f
WHERE
	 f.part_no='{$post['partno']}' 
	               and f.supplier_code='{$post['sppcode']}' 
	               and f.payment_terms='{$post['payterms']}' 
	               and f.trading_terms='{$post['tradterms']}'
                   AND f.flag=1";
        $totalCount = Yii::$app->get('db')->createCommand("select count(a.pk_id) from ( {$sql} ) a")->queryScalar();
        //有数量区间
        if ($totalCount > 0) {
            $sql1 = "SELECT
                   f.rmb_price,
                   f.buy_price quote_price,
                   f.currency quote_currency,
                   p.bsp_id currency
                FROM
	               pdt.pdtprice_pas f
	               LEFT JOIN erp.bs_pubdata p ON f.currency=p.bsp_svalue
                WHERE
	               f.part_no='{$post['partno']}' 
	               and f.supplier_code='{$post['sppcode']}' 
	               and f.payment_terms='{$post['payterms']}' 
	               and f.trading_terms='{$post['tradterms']}'
	               and f.min_num<{$post['num']}
	               and f.max_num>={$post['num']}";
            $result = Yii::$app->getDb('pdt')->createCommand($sql1)->queryOne();
            //没有该区间的数据(获取最小值到无穷大的价格)
            if (count($result) == 0) {
                $sql2 = "SELECT
                   f.rmb_price,
                   f.buy_price quote_price,
                   f.currency quote_currency,
                   p.bsp_id currency
                FROM
	               pdt.pdtprice_pas f
	               LEFT JOIN erp.bs_pubdata p ON f.currency=p.bsp_svalue
                WHERE
	               f.part_no='{$post['partno']}' 
	               and f.supplier_code='{$post['sppcode']}' 
	               and f.payment_terms='{$post['payterms']}' 
	               and f.trading_terms='{$post['tradterms']}'
	               and f.min_num>{$post['num']}";
                return Yii::$app->getDb('pdt')->createCommand($sql2)->queryOne();
            }
            return $result;
        } //没有数量区间(获取没有数量区间的价格)
        else {
            $sql3 = "SELECT
                   f.rmb_price,
                   f.buy_price quote_price,
                   f.currency quote_currency,
                   p.bsp_id currency
                FROM
	               pdt.pdtprice_pas f
	               LEFT JOIN erp.bs_pubdata p ON f.currency=p.bsp_svalue
                WHERE
	               f.part_no='{$post['partno']}' 
	               and f.supplier_code='{$post['sppcode']}' 
	               and f.payment_terms='{$post['payterms']}' 
	               and f.trading_terms='{$post['tradterms']}'";
            return Yii::$app->getDb('pdt')->createCommand($sql3)->queryOne();
        }
    }

    //获取指定的采购区域
    public function actionBuyArea($id)
    {
        $sql = "select f.factory_id,f.factory_name from erp.bs_factory f where f.factory_id=$id AND f.fact_status=1";
        return Yii::$app->getDb('db')->createCommand($sql)->queryOne();
    }

    //获取采购数量区间
    public function actionRatioInfo()
    {
        $sql = "SELECT
	               r.upp_num,
                   r.low_num
                FROM
	               erp.bs_ratio r
                LEFT JOIN erp.bs_pubdata pu ON r.ratio_type = pu.bsp_id
                WHERE
	               	(pu.bsp_svalue ='采购单'
                OR pu.bsp_svalue ='採購單')
                AND r.yn=1";
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
}
