<?php

namespace app\modules\purchase\controllers;

use app\classes\Trans;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsForm;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrOrganization;
use app\modules\purchase\models\BsPrch;
use app\modules\purchase\models\BsPrchDt;
use app\modules\purchase\models\RReqPrch;
use app\modules\purchase\models\search\PurchaseNotifySearch;
use app\modules\sale\models\SaleInoutnoteh;
use app\modules\sale\models\SaleInoutnotel;
use app\modules\sale\models\SalePurchasenoteh;
use app\modules\warehouse\models\RcpNotice;
use app\modules\warehouse\models\RcpNoticeDt;
use app\modules\warehouse\models\SalePickingh;
use app\modules\warehouse\models\SalePickingl;
use Yii;
use yii\base\Exception;
use yii\data\SqlDataProvider;


class PurchaseNotifyController extends BaseActiveController
{
    public $modelClass = 'app\modules\purchase\models\BsPrch';

    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        if (empty($params['rows'])) {
            return [
//                'req_dct' => BsPubdata::getData(BsPubdata::REQ_DCT),
                'req_dct' => Yii::$app->db->createCommand("select bsp_id, bsp_svalue from erp.bs_pubdata where bsp_id=109016 or bsp_id=109017")->queryAll(),
//                'req_rqf'=>BsPubdata::getData(BsPubdata::REQ_RQF),
                'spp_fname' => Yii::$app->db->createCommand("select spp_id,spp_fname from spp.bs_supplier where company_id = 3213")->queryAll(),
                'wh_addr' => Yii::$app->db->createCommand("select wh_id,wh_addr from wms.bs_wh where wh_id != 0")->queryAll(),
                'leg_id' => Yii::$app->db->createCommand("select company_id,company_name from erp.bs_company where company_status = 10")->queryAll(),
                'prch_status' => Yii::$app->db->createCommand("select prch_id,(CASE prch_name when '採購未提交' THEN '未提交' when '採購審核中' then '审核中' when '採購已駁回' then '驳回
' when '採購已取消' then '已取消' when '採購審核完成' then '审核完成' WHEN '已採購' then '已采购' when '采购未提交' THEN '未提交' when '采购审核中' then '审核中' when '采购已驳回' then '驳回
' when '采购已取消' then '已取消' when '采购审核完成' then '审核完成' WHEN '已采购' then '已采购' else prch_name END)prch_name from prch.prch_status  where prch_type = 1")->queryAll(),
            ];
        }
        $params = Yii::$app->request->queryParams;
        $querySql = "select a.prch_id,
                          a.prch_no,
                          a.prch_status,
                          (case c.prch_name when '採購未提交' THEN '未提交' when '採購審核中' then '审核中' when '採購已駁回' then '驳回
' when '採購已取消' then '已取消' when '採購審核完成' then '审核完成' WHEN '已採購' then '已采购' when '采购未提交' THEN '未提交' when '采购审核中' then '审核中' when '采购已驳回' then '驳回
' when '采购已取消' then '已取消' when '采购审核完成' then '审核完成' WHEN '已采购' then '已采购' ELSE c.prch_name end)prch_name,
                          LEFT (a.app_date,10)app_date,
                          b.bsp_svalue,                
                          i.staff_name,
                          d.organization_name,
                          a.contact_info,
                          e.company_name,
--                        f.wh_addr, 
 --                       g.cur_sname,
      --                  a.total_amount,
        --                h.yn_three,
--                        k.spp_fname,
                          l.cur_id
                          from prch.bs_prch a 
                          LEFT JOIN erp.bs_pubdata b on a.req_dct=b.bsp_id
                          LEFT JOIN prch.prch_status c ON a.prch_status=c.prch_id
                          LEFT JOIN erp.hr_organization d on a.dpt_id=d.organization_id
                          LEFT JOIN erp.bs_company e on a.leg_id=e.company_id                                            
                          LEFT JOIN prch.bs_prch_dt l on a.prch_id=l.prch_id
 --                         LEFT JOIN wms.bs_wh f on l.addr_id=f.wh_id
                          LEFT JOIN erp. bs_currency g on l.cur_id=g.cur_id
                          LEFT JOIN prch.bs_req h on h.req_id=a.prch_id
                          LEFT JOIN erp.hr_staff i ON a.apper=i.staff_id
                          LEFT JOIN prch.bs_prch_dt j ON j.prch_id=a.prch_id
                          LEFT JOIN spp.bs_supplier k on j.spp_id=k.spp_id
                          WHERE a.prch_id IS NOT NULL
                           AND a.apper={$params['ids']}
        ";
//        dumpE($querySql);
        $queryParams = [];
        if (!empty($params['req_dct'])) {
            $trans = new Trans();
            $params['req_dct'] = str_replace(['%', '_'], ['\%', '\_'], $params['req_dct']);
            $queryParams[':req_dct1'] = '%' . $params['req_dct'] . '%';
            $queryParams[':req_dct2'] = '%' . $trans->c2t($params['req_dct']) . '%';
            $queryParams[':req_dct3'] = '%' . $trans->t2c($params['req_dct']) . '%';
            $querySql .= " and (b.bsp_id like :req_dct1 or b.bsp_id like :req_dct2 or b.bsp_id like :req_dct3)";
        }
        if (!empty($params['leg_id'])) {
            $trans = new Trans();
            $params['leg_id'] = str_replace(['%', '_'], ['\%', '\_'], $params['leg_id']);
            $queryParams[':leg_id1'] = '%' . $params['leg_id'] . '%';
            $queryParams[':leg_id2'] = '%' . $trans->c2t($params['leg_id']) . '%';
            $queryParams[':leg_id3'] = '%' . $trans->t2c($params['leg_id']) . '%';
            $querySql .= " and (e.company_id like :leg_id1 or e.company_id like :leg_id2 or e.company_id like :leg_id3)";
        }
//        if (isset($params['yn_three']) && $params['yn_three'] != '') {
//            $trans=new Trans();
//            $params['yn_three']=str_replace(['%','_'],['\%','\_'],$params['yn_three']);
//            $queryParams[':yn_three1'] = $params['yn_three'];
//            $queryParams[':yn_three2']='%'.$trans->c2t($params['yn_three']).'%';
//            $queryParams[':yn_three3']='%'.$trans->t2c($params['yn_three']).'%';
//            $querySql .= " and yn_three = :yn_three1";
//        }
//        if (!empty($params['wh_addr'])) {
//            $trans = new Trans();
//            $params['wh_addr'] = str_replace(['%', '_'], ['\%', '\_'], $params['wh_addr']);
//            $queryParams[':wh_addr1'] = '%' . $params['wh_addr'] . '%';
//            $queryParams[':wh_addr2'] = '%' . $trans->c2t($params['wh_addr']) . '%';
//            $queryParams[':wh_addr3'] = '%' . $trans->t2c($params['wh_addr']) . '%';
//            $querySql .= " and (f.wh_id like :wh_addr1 or f.wh_id like :wh_addr2 or f.wh_id like :wh_addr3)";
//        }
        if (!empty($params['prch_no'])) {
            $trans = new Trans();
            $params['prch_no'] = str_replace(['%', '_'], ['\%', '\_'], $params['prch_no']);
            $queryParams[':prch_no1'] = '%' . $params['prch_no'] . '%';
            $queryParams[':prch_no2'] = '%' . $trans->c2t($params['prch_no']) . '%';
            $queryParams[':prch_no3'] = '%' . $trans->t2c($params['prch_no']) . '%';
            $querySql .= " and (a.prch_no like :prch_no1 or a.prch_no like :prch_no2 or a.prch_no like :prch_no3)";
        }
//        if (!empty($params['spp_fname'])) {
//            $trans = new Trans();
//            $params['spp_fname'] = str_replace(['%', '_'], ['\%', '\_'], $params['spp_fname']);
//            $queryParams[':spp_fname1'] = '%' . $params['spp_fname'] . '%';
//            $queryParams[':spp_fname2'] = '%' . $trans->c2t($params['spp_fname']) . '%';
//            $queryParams[':spp_fname3'] = '%' . $trans->t2c($params['spp_fname']) . '%';
//            $querySql .= " and (k.spp_id like :spp_fname1 or k.spp_id like :spp_fname2 or k.spp_id like :spp_fname3)";
//        }
        if (!empty($params['prch_status'])) {
            $trans = new Trans();
            $params['prch_status'] = str_replace(['%', '_'], ['\%', '\_'], $params['prch_status']);
            $queryParams[':prch_status1'] = '%' . $params['prch_status'] . '%';
            $queryParams[':prch_status2'] = '%' . $trans->c2t($params['prch_status']) . '%';
            $queryParams[':prch_status3'] = '%' . $trans->t2c($params['prch_status']) . '%';
            $querySql .= " and (c.prch_id like :prch_status1 or c.prch_id like :prch_status2 or c.prch_id like :prch_status3)";
        }
        if (!empty($params['start_date'])) {
            $queryParams[':start_date'] = date('Y-m-d H:i:s', strtotime($params['start_date']));
            $querySql .= " and a.app_date >= :start_date";
        }
        if (!empty($params['end_date'])) {
            $queryParams[':end_date'] = date('Y-m-d H:i:s', strtotime($params['end_date'] . '+1 day'));
            $querySql .= " and  a.app_date < :end_date";
        }
        $querySql .= " GROUP BY a.prch_id order by a.prch_id desc";
        $totalCount = Yii::$app->get('db')->createCommand("select count(*) from ( {$querySql} ) a", $queryParams)->queryScalar();
        $provider = new SqlDataProvider([
            'db' => 'db',
            'sql' => $querySql,
            'params' => $queryParams,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => $params['rows']
            ]
        ]);
        return [
            'rows' => $provider->getModels(),
            'total' => $provider->totalCount
        ];
    }

    //商品详情
    public function actionCommodity()
    {
        $params = Yii::$app->request->queryParams;
        $queryParams = [':id' => $params['id']];
//        $attrModel = new BsQstAnsw();
//        $attrModel->answ_id = $id;
        if (empty($params['rows'])) {
            return Yii::$app->db->createCommand("select a.prch_id from erp.bs_prch a where a.prch_id = :id", $queryParams)->queryOne();
        }
        $querySql = "select a.prch_dt_id,
                          b.part_no,
                          b.pdt_name,
                          b.tp_spec,
                          b.brand,
                          b.unit,
                          a.pay_condition,
                          a.goods_condition,
                          round(a.prch_num,2)prch_num,
                          round(a.price,5)price,
                          round(a.total_amount,2)total_amount,
 --                         f.bsp_svalue,
                          concat(ta.tax_no,'/',round(ta.tax_value*100,0),'%')tax,
                          a.deliv_date,
  --                        h.wh_addr,
                          GROUP_concat(k.req_no)req_no,
                          group_concat(k.req_id)req_id,
                          sp.spp_code,
                          sp.spp_fname,
                          (g.bsp_svalue)cur_code
                          from prch.bs_prch_dt a 
                          LEFT JOIN pdt.bs_material b ON a.part_no=b.part_no
    --                      LEFT JOIN pdt.bs_product c ON b.pdt_PKID=c.pdt_PKID
     --                     LEFT JOIN pdt.bs_brand d on c.brand_id=d.brand_id
 --                         LEFT JOIN pdt.r_prt_spp_dt e ON a.prt_spp_dt_pkid=e.prt_spp_dt_pkid
   --                       LEFT JOIN erp. bs_pubdata f ON a.pay_type=f.bsp_id
                          LEFT JOIN erp.bs_pubdata g ON a.cur_id=g.bsp_id
 --                         LEFT JOIN wms.bs_wh h ON h.wh_id=a.addr_id
                          LEFT join prch.r_req_prch i ON i.prch_dt_id=a.prch_dt_id
                          LEFT JOIN prch.bs_req_dt j on j.req_dt_id=i.req_dt_id
                          LEFT JOIN prch.bs_req k on k.req_id=j.req_id
                          LEFT JOIN spp.bs_supplier sp ON sp.spp_id=a.spp_id
                          left join erp.bs_tax ta ON ta.tax_pkid=a.tax
  --                        LEFT JOIN pdt.r_prt_spp spp ON spp.prt_spp_pkid=e.prt_spp_pkid
                         where a.prch_id=" . $queryParams[':id'] . " ";
        $queryParams = [];
        $querySql .= " group by a.prch_dt_id order BY a.prch_dt_id";
        $totalCount = Yii::$app->get('db')->createCommand("select count(*) from ( {$querySql} ) a", $queryParams)->queryScalar();
        $provider = new SqlDataProvider([
            'db' => 'db',
            'sql' => $querySql,
            'params' => $queryParams,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        return [
            'rows' => $provider->getModels(),
            'total' => $provider->totalCount
        ];
    }

    //生成收货通知单
    public function actionNotice()
    {
        $params = Yii::$app->request->queryParams;
        $queryParams = [':id' => $params['id']];
        if (empty($params['rows'])) {
            return Yii::$app->db->createCommand("select  a.prch_no,i.staff_name,d.organization_name from prch.bs_prch a LEFT JOIN erp.hr_organization d on a.dpt_id=d.organization_id   LEFT JOIN erp.hr_staff i ON a.apper=i.staff_id where a.prch_id = :id", $queryParams)->queryAll();
        }
        $querySql = "select a.prch_dt_id,
                          b.part_no,
                          b.pdt_name,
                          b.tp_spec,
                          b.brand,
                          b.unit,
                          a.spp_id,
                          e.spp_fname,
                          a.prch_num,
                          a.price,
                          a.total_amount,
                          a.pay_condition,
                          a.tax,
                          g.cur_code,
                          a.deliv_date,
                          h.wh_addr,
                          k.req_no,
                          k.req_id
                          from prch.bs_prch_dt a 
                          LEFT JOIN pdt.bs_material b ON a.part_no=b.part_no
                          LEFT JOIN spp.bs_supplier e ON a.spp_id=e.spp_id
                          LEFT JOIN prch.bs_prch c ON c.prch_id=a.prch_id
                          LEFT JOIN erp.bs_currency g ON a.cur_id=g.cur_id
                          LEFT JOIN wms.bs_wh h ON h.wh_id=c.rcp_id
                          LEFT join prch.r_req_prch i ON i.prch_dt_id=a.prch_dt_id
                          LEFT JOIN prch.bs_req_dt j on j.req_dt_id=i.req_dt_id
                          LEFT JOIN prch.bs_req k on k.req_id=j.req_id
                         where a.prch_id=" . $queryParams[':id'] . " ";
        $queryParams = [];
        $querySql .= " group by a.prch_dt_id order BY a.prch_dt_id";
        $totalCount = Yii::$app->get('db')->createCommand("select count(*) from ( {$querySql} ) a", $queryParams)->queryScalar();
        $provider = new SqlDataProvider([
            'db' => 'db',
            'sql' => $querySql,
            'params' => $queryParams,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        return [
            'rows' => $provider->getModels(),
            'total' => $provider->totalCount
        ];
    }

//获取采购部门和采购人
    public function actionHrmes($id)
    {
        $sql = [];
        $sql['hr'] = Yii::$app->db->createCommand("select  a.prch_no,i.staff_name,d.organization_name 
                                           from prch.bs_prch a 
                                           LEFT JOIN erp.hr_organization d on a.dpt_id=d.organization_id   
                                           LEFT JOIN erp.hr_staff i ON a.apper=i.staff_id 
                                           where a.prch_id =$id")->queryAll();
        return $sql;
    }

    //生成收货通知单页面数据新版 by hf
    public function actionNoticeNew()
    {
        $params = Yii::$app->request->queryParams;
        $sql = "select l.* FROM(
SELECT
	t.part_no,
	bm.pkmt_id,
	bm.pdt_name,
	bm.tp_spec,
	bm.brand,
	bm.unit,
	round(t.prch_num,2)prch_num,
	t.prch_dt_id,
	pr.rcp_id,
	pr.prch_id,
    pr.prch_no,
	pr.dpt_id,
	pr.area_id,
	pr.apper,
	pr.leg_id,
	br.rcp_name,
	br.rcp_no,
	ho.organization_code,
	ho.organization_name,
	hr.staff_name,
bs.spp_id,
bs.spp_fname,
bs.group_code spp_code,
	(
		-- 查询收货通知单中已经收货的数量
			SELECT
				IFNULL(SUM(gd.rcpg_num),0)
		FROM
			wms.rcp_notice n
		LEFT JOIN wms.rcp_notice_dt rt ON n.rcpnt_no = rt.rcpnt_no
    LEFT JOIN wms.rcp_goods_dt gd ON rt.rcpdt_id=gd.rcpdt_id
		WHERE
			rt.prch_dt_id=t.prch_dt_id
		AND rt.part_no = t.part_no AND n.rcpnt_status=2
) delivery_num,
(
select MAX(bpr.app_date) FROM prch.bs_prch bpr where bpr.prch_id in(19,20)
) prch_date
FROM
	prch.bs_prch_dt t
LEFT JOIN prch.bs_prch pr ON t.prch_id = pr.prch_id
LEFT JOIN pdt.bs_material bm ON t.part_no = bm.part_no
LEFT JOIN wms.bs_receipt br ON pr.rcp_id = br.rcp_id
LEFT JOIN erp.hr_organization ho ON pr.dpt_id = ho.organization_id
LEFT JOIN erp.hr_staff hr ON pr.apper=hr.staff_id
LEFT JOIN spp.bs_supplier bs ON t.spp_id=bs.spp_id
WHERE
	pr.prch_id IN (".$params['id'].")
)l
-- 采购数量大于收货数量
where l.prch_num>l.delivery_num";
       // file_put_contents('log.txt', Yii::$app->get('prch')->createCommand($sql)->getRawSql());
        return Yii::$app->getDb('prch')->createCommand($sql)->queryAll();
    }

    //保存收货通知单
    public function actionCreateNotice()
    {
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->wms->beginTransaction();
        $tr = new Trans();
        $count=0;
        try {
            $notices = array();
            //根据收货中心ID和法人相同的分组(收货中心和法人相同的为一个通知单)
            foreach ($post['RcpNoticeDt'] as $k => $v) {
                $notices[$v['rcp_id']]['NoticeDt'][] = $v; //根据收货中心ID相同的分组
            }
            $notices = array_values($notices);//将新数组重新排序
            //return $result;
            //$notices = array();
//            for ($i = 0; $i < count($result); $i++) {
//                foreach ($result[$i]['Notice'] as $ke => $va) {
//                    $notices[$va['leg_id']]['NoticeDt'][] = $va; //根据法人相同的分组
//                }
//                $notices = array_values($notices);
//            }

           // $notices = array_values($notices);//将新数组重新排序(收货中心和法人相同的为一个通知单)
            //return $notices;
            //保存数据生成通知单
            //此循环表示有几个通知单
            for ($j = 0; $j < count($notices); $j++) {
                $count++;
                $noticedt = $notices[$j]['NoticeDt'];//收货通知单详情
                $notice = $post['RcpNotice'];
                //生成收货通知单
                $rcpnotice = new RcpNotice();
//                $rcpnotice->rcpnt_no = BsForm::getCode("rcp_notice", $rcpnotice);//收货通知单号
                $rcpnotice->rcpnt_type = 1;//单据类型
                $prchno = "";
                //获取采购单号
                if (count($noticedt) == 1) {
                    $prchno = $noticedt[0]['prch_no'];//只有一个采购单时
                }
                ////将多个采购单号用逗号隔开
                if (count($noticedt) > 1) {
                    $arr = [];
                    for ($m = 0; $m < count($noticedt); $m++) {
                        array_push($arr, $noticedt[$m]['prch_no']);////将多个采购单号转成数组
                    }
                    $arr=array_unique($arr);//去掉重复的采购单号
                    $prchno = join($arr, ',');//将多个采购单号用逗号隔开
                }
                $rcpnotice->rcpnt_status = 1;//通知单状态
                $rcpnotice->prch_no = $prchno;//采购单号
                $rcpnotice->prch_depno = $notice['prch_depno'];//采购部门
                $rcpnotice->prch_area = $noticedt[0]['prch_area'];//采购区域
                $rcpnotice->rcp_no=$noticedt[0]['rcp_no'];//收货中心编码
                $rcpnotice->creator = $post['staff'];//创建人
                $rcpnotice->creat_date = date('Y-m-d');
                $rcpnotice->prch_date = $notice['prch_date'];//采购时间
                if (!$rcpnotice->save()) {
                    throw new Exception(json_encode($rcpnotice->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                //收货通知单详情
                //此循环表示每个通知单有几条详情记录
                for ($n = 0; $n < count($noticedt); $n++) {
                    $rcpnoticedt = new RcpNoticeDt();
                    $rcpnoticedt->rcpnt_no = $rcpnotice->rcpnt_no;//通知单号
                   // $ordid = Yii::$app->getDb('wms')->createCommand("select max(t.ord_id) ord_id from wms.rcp_notice_dt t where t.part_no='{$noticedt[$n]['part_no']}'")->queryAll();
                    //$rcpnoticedt->ord_id = empty($ordid[0]['ord_id']) ? 1 : ((int)$ordid[0]['ord_id']) + 1;//项次/批次(料号的批次)
                   $rcpnoticedt->prch_dt_id=$noticedt[$n]['prch_dt_id'];//采购单明细ID
                    $rcpnoticedt->part_no = $noticedt[$n]['part_no'];//料号
                    $rcpnoticedt->ord_num = $noticedt[$n]['ord_num'];//采购量
                    $rcpnoticedt->delivery_num = $noticedt[$n]['delivery_num'];//送货数量
                    $rcpnoticedt->plan_date = $noticedt[$n]['plan_date'];//预计到货时间
                    $rcpnoticedt->spp_code=$noticedt[$n]['spp_code'];//供应商代码
                    $rcpnoticedt->remarks =  $tr->t2c($noticedt[$n]['remark']);//备注
                    if (!$rcpnoticedt->save()) {
                        throw new Exception(json_encode($rcpnoticedt->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            //改变采购单状态
            //此循环为了改变采购单状态
            for ($h = 0; $h < count($notices); $h++) {
                $noticedt1 = $notices[$h]['NoticeDt'];//收货通知单详情
                $prchnos = [];
                foreach ($noticedt1 as $ke1 => $va1) {
                    $prchnos[$va1['prch_no']]['NoticeDt'][] = $va1; //将采购单相同的放到一个数组
                }
                $prchnos = array_values($prchnos);//将数组重新排序
                //对应的采购单单信息
                for ($p = 0; $p < count($prchnos); $p++) {
                   // $deliverynums = 0;//采购单已到货数量
                   // $prchnums = 0;//采购单总数量
                    $prchs = $prchnos[$p]['NoticeDt'];//具体的采购单所对应的料号数据
                    $id = "";//采购单号
                    //每个采购单所对应的料号信息
                    for ($k = 0; $k < count($prchs); $k++) {
                        //获取采购单ID
                        $prchid = Yii::$app->getDb('prch')->createCommand("select bp.prch_id from prch.bs_prch bp where bp.prch_no='{$prchs[0]['prch_no']}'")->queryOne();
                        $id = $prchid['prch_id'];

                        //采购单采购的所有料号的数量
//                        $nums = Yii::$app->getDb('prch')->createCommand("SELECT
//                                                                    	SUM(prt.prch_num) as prch_num
//                                                                     FROM
//	                                                                    prch.bs_prch_dt prt
//                                                                    LEFT JOIN prch.bs_prch pr ON prt.prch_id=pr.prch_id
//                                                                    WHERE
//	                                                                    pr.prch_no ='{$prchs[0]['prch_no']}'")->queryAll();
//                        $prchnums = $nums[0]['prch_num'];//当前采购单所有的采购数量
//                        $deliverynums += (double)$prchs[$k]['delivery_num'];//当前送货的数量
//                        //采购单已到货的数量
//                        $denums = Yii::$app->getDb('wms')->createCommand("SELECT IFNULL(SUM(rt.delivery_num),0) deliverynum FROM wms.rcp_notice n
//    LEFT JOIN wms.rcp_notice_dt rt ON n.rcpnt_no = rt.rcpnt_no
//		WHERE rt.prch_dt_id={$prchs[$k]['prch_dt_id']}
//		AND rt.part_no = '{$prchs[$k]['part_no']}' AND n.rcpnt_status=2")->queryAll();
//                        $deliverynums += (double)$denums[0]['deliverynum'];//当前送货的数量+已到货的数量
                    }
                    //出货总数量大于或等于采购数量
                    //if ($deliverynums >= $prchnums) {
                      //  $bsprch = BsPrch::findOne($id);
                      //  $bsprch->prch_status = 48;//已采购
                       // if (!$bsprch->save()) {
                       //     throw new Exception(json_encode($bsprch->getErrors(), JSON_UNESCAPED_UNICODE));
                    //    }
                   // } else {
                    //将采购单状态更改为采购中
                        $bsprch = BsPrch::findOne($id);
                        $bsprch->prch_status = 49;//采购中
                        if (!$bsprch->save()) {
                            throw new Exception(json_encode($bsprch->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                   // }
                }
            }
            $transaction->commit();
            return $this->success("新增成功",['count'=>$count]);
        } catch (Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    // 通知单详情
    public function actionView($id)
    {
        $model = new PurchaseNotifySearch();
//        $model->searchOrderH($id);
        $dataProviderH = current($model->searchNotifyH($id)->getModels());
        $dataProviderL = $model->searchNotifyL($id)->getModels();
        $data['products'] = $dataProviderL;
        $data = array_merge($dataProviderH, $data);
        return $data;
    }

    // 下拉列表
    public function actionGetDownList()
    {
        $downList = [];
        // 单据类型 对应business中的采购订单
        $downList['billType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'puord'])->all();
        $downList['orgList'] = HrOrganization::getOrgAllLevel(0);
        // 订单类型
//        $downList['orderType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'order'])->all();
        // 交易法人
//        $downList['corporate'] = BsCompany::find()->select(['company_id', 'company_name'])->all();
        // 通知单状态
        $downList['notfy_status'] = [
            SalePurchasenoteh::STATUS_DEFAULT => '待处理',
            SalePurchasenoteh::STATUS_PURCHASING => '采购中',
            SalePurchasenoteh::STATUS_PURCHASED => '已采购',
        ];
        // 付款方式
//        $downList['payment'] = BsPayment::find()->select(['pac_id', 'pac_code', 'pac_sname'])->all();
        // 支付类型
//        $downList['payType'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::PAY_TYPE])->all();
        // 付款条件
//        $downList['payCondition'] = BsPayCondition::find()->select(['pat_id', 'pat_sname'])->all();
        // 交易方式（交易模式）
//        $downList['pattern'] = BsTransaction::find()->select(['tac_id','tac_sname'])->all();
        // 订单来源
        $downList['orderFrom'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::ORDER_FROM])->all();
        // 发票类型
//        $downList['invoiceType'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::CRM_INVOICE_TYPE])->all();
        // 交易币别
//        $downList['currency'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::SUPPLIER_TRADE_CURRENCY])->all();
        // 运输方式
//        $downList['transport'] = BsTransport::find()->select(['tran_id', 'tran_code', 'tran_sname'])->all();
        // 配送方式
//        $downList['dispatching'] = BsDeliverymethod::find()->select(['bdm_id', 'bdm_code', 'bdm_sname'])->all();
        // 仓库信息
//        $downList['warehouse'] = BsWh::find()->select(['wh_id', 'wh_code', 'wh_name'])->all();
        return $downList;
    }

    // 点击主表获取子表商品信息
    public function actionGetProducts()
    {
        $params = Yii::$app->request->queryParams;
        $model = new PurchaseNotifySearch();
        $model = $model->searchOrderProducts($params);
        return $model;
    }

    // 生成采购单
    public function actionCreatePick()
    {
        $post = Yii::$app->request->post();
        // 查找信息
        $transaction = Yii::$app->oms->beginTransaction();
        try {
            $notifyH = SaleInoutnoteh::findOne($post['id']);
            if ($notifyH->notfy_status == 1) {
                $notifyH->notfy_status = $notifyH::STATUS_PICKING;
                $pickH = new SalePickingh();
                $pickH->p_bill_id = $notifyH->sonh_id;
                $pickH->bill_type = $pickH::SALE_OUT;
                $pickH->whs_id = $notifyH->whs_id;
                $pickH->create_by = $post['staff_id'];
                $pickH->update_by = $post['staff_id'];
                if (!$notifyH->save()) {
                    throw new \Exception(current($notifyH->getFirstErrors()));
                }
                if (!$pickH->save()) {
                    throw new \Exception(current($pickH->getFirstErrors()));
                }
                $notifyL = SaleInoutnotel::find()->where(['sonh_id' => $notifyH->sonh_id])->all();
                foreach ($notifyL as $k => $v) {
                    $pickL = new SalePickingl();
                    $pickL->soph_id = $pickH->soph_id;
                    $pickL->poh_id = $notifyH->bill_id;
                    $pickL->pol_id = $v->lbill_id;
                    $pickL->pdt_id = $v->pdt_id;
                    $pickL->p_bill_hid = $notifyH->sonh_id;
                    $pickL->p_bill_lid = $v->sonl_id;
                    if (!$pickL->save()) {
                        throw new \Exception(current($pickL->getFirstErrors()));
                    }
                }
            } else {
                throw new \Exception('只有待处理状态才能生成拣货单');
            }
//            throw new \Exception(current($notifyH->getFirstErrors()));
            $transaction->commit();
            return $this->success('生成拣货单成功！');
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    // 取消通知
    public function actionCancelNotify($id)
    {
        $post = Yii::$app->request->post();

        $notifyH = SalePurchasenoteh::findOne($id);
        if ($notifyH->notfy_status == '1') {
            $notifyH->notfy_status = $notifyH::STATUS_CANCEL;
            $notifyH->notify_descr .= '  取消原因：' . $post['reason'];
            if (!$notifyH->save()) {
                return $this->error(current($notifyH->getFirstErrors()));
            } else {
                return $this->success('取消通知成功！');
            }
        } else {
            return $this->error('只有待处理状态可以取消通知！');
        }
    }

    public function actionModels($id)
    {
        //采购单信息
        $sql = "SELECT s.prch_id,s.prch_no,DATE_FORMAT(s.app_date,'%Y/%m/%d')app_date ,s.yn_can,s.can_rsn,s.contact_info,s.prch_status,
        ss.prch_name,d.bsp_svalue as req_dct,c.company_name as leg_id,a.factory_name as area_id,
        o.organization_name as dpt_id,f.staff_name as apper,f.staff_code,s.remarks,t.rcp_name
        from (SELECT * FROM prch.bs_prch where prch_id=:prch_id)s
        LEFT JOIN prch.prch_status ss on ss.prch_id=s.prch_status
        LEFT JOIN erp.bs_pubdata d on d.bsp_id=s.req_dct
        LEFT JOIN erp.bs_company c on c.company_id=s.leg_id
        LEFT JOIN erp.bs_factory a on a.factory_id=s.area_id 
        LEFT JOIN erp.hr_organization o on o.organization_id=s.dpt_id
        LEFT JOIN erp.hr_staff f on f.staff_id=s.apper
				LEFT JOIN wms.bs_receipt t on t.rcp_id=s.rcp_id";
        $basicinfo = Yii::$app->db->createCommand($sql)->bindValue(':prch_id', $id)->queryOne();
        //关联单号
//        $sql1="select (select req_no from prch.bs_req where req_id=b.req_id)req_no,b.req_id from
//        (select r.req_dt_id from (select t.prch_dt_id from prch.bs_prch h
//        LEFT JOIN prch.bs_prch_dt t on h.prch_id=t.prch_id where h.prch_id=:prch_id)s
//        LEFT JOIN prch.r_req_prch r on r.prch_dt_id=s.prch_dt_id)ss
//        LEFT JOIN prch.bs_req_dt b on b.req_dt_id=ss.req_dt_id";
//        $prtinfo = Yii::$app->db->createCommand($sql1)->bindValue(':prch_id', $id)->queryAll();
        //商品信息
//        $sql2 = 'select ss.brand_id,ss.unit,ss.pdt_name,ss.prch_num,ss.price,ss.tp_spec ,ss.pdt_PKID,ss.part_no,
//				p.bsp_svalue as unit,b.brand_name_cn,ss.deliv_date,
//				ss.spp_code,ss.spp_fname,ss.bs_prch,ss.cur_code,ss.tax_no,ss.tax_value,ss.wh_code,
//				(select req_no from prch.bs_req where req_id=t.req_id)req_no,t.req_id,ss.total_amount
//        from (select p.brand_id,p.unit,p.pdt_name,s.prch_num,s.price,s.tp_spec ,s.pdt_PKID,
//				s.part_no,DATE_FORMAT(s.deliv_date,\'%Y/%m/%d\')deliv_date,s.tax_no,s.tax_value,
//				r.spp_code,r.spp_fname,s.bs_prch,s.cur_code,s.wh_code,h.req_dt_id,s.total_amount
//        from (SELECT b.total_amount,b.prch_dt_id,b.prch_num,b.price,o.tp_spec,o.pdt_PKID,o.part_no,b.deliv_date,b.spp_id,
//				a.bsp_svalue as bs_prch,t.tax_no,t.tax_value ,c.cur_code,bs.wh_code FROM prch.bs_prch_dt b
//        left join pdt.bs_partno o on b.prt_pkid=o.prt_pkid
//				left join prch.bs_prch p on p.prch_id=b.prch_id
//				LEFT JOIN erp.bs_pubdata a on a.bsp_id=b.pay_type
//				LEFT JOIN erp.bs_tax t on t.tax_pkid=b.tax
//				LEFT JOIN erp.bs_currency c on c.cur_id=b.cur_id
//				LEFT JOIN wms.bs_wh bs on bs.wh_id=b.addr_id
//				where b.prch_id=:prch_id ORDER BY o.part_no)s
//        left join pdt.bs_product p on  p.pdt_PKID=s.pdt_PKID
//				LEFT JOIN spp.bs_supplier r on r.spp_id=s.spp_id
//				LEFT JOIN prch.r_req_prch h on h.prch_dt_id=s.prch_dt_id)ss
//        left join erp.bs_pubdata p on p.bsp_id=ss.unit
//        left join pdt.bs_brand b on b.brand_id=ss.brand_id
//				LEFT JOIN prch.bs_req_dt t  on t.req_dt_id=ss.req_dt_id';
        $sql2="select ss.*,(select req_no from prch.bs_req where req_id=t.req_id)req_no,t.req_id
        from (select s.*,r.group_code,r.spp_fname,h.req_dt_id
        from (SELECT b.total_amount,b.prch_dt_id,b.prch_num,b.price,o.tp_spec,o.part_no,o.brand,o.pdt_name,o.unit,DATE_FORMAT(b.deliv_date,'%Y/%m/%d')deliv_date,b.spp_id,
				t.tax_no,t.tax_value ,c.bsp_svalue as cur_id,b.pay_condition,b.goods_condition FROM prch.bs_prch_dt b
        left join pdt.bs_material o on b.part_no=o.part_no
				left join prch.bs_prch p on p.prch_id=b.prch_id 
				LEFT JOIN erp.bs_tax t on t.tax_pkid=b.tax
				LEFT JOIN erp.bs_pubdata c on c.bsp_id=b.cur_id
				where b.prch_id=:prch_id ORDER BY o.part_no)s
				LEFT JOIN spp.bs_supplier r on r.spp_id=s.spp_id
				LEFT JOIN prch.r_req_prch h on h.prch_dt_id=s.prch_dt_id)ss
				LEFT JOIN prch.bs_req_dt t  on t.req_dt_id=ss.req_dt_id";
        $products = Yii::$app->db->createCommand($sql2)->bindValue(':prch_id', $id)->queryAll();
        $infoall = [$basicinfo, $products];
        if ($infoall !== null) {
            return $infoall;

        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //取消采购
    public function actionCanRsn($id)
    {
        $_id = explode(',', $id);
        $_succ = "操作失败";
        foreach ($_id as $vid) {
            $data = Yii::$app->request->post();
            $model = BsPrch::findOne($vid);
            $model->prch_status = BsPrch::REQUEST_STATUS_STATUS;
            $model->yn_can = BsPrch::REQUEST_STATUS_CLOSE;
            if ($model->load($data) && $model->save()) {
                $_succ = $this->success('操作成功');
            }
        }
        return $_succ;
    }

    //修改采购单 by hf
    public function actionUpdate($id)
    {
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $bsprch = BsPrch::findOne($id);
            if ($bsprch != null) {
                $bsprch->load($post);
                $tatal = 0;//总税额
                $totaltax = 0;//未税总金额
                if ($post['BsPrchDt'] != null) {
                    foreach ($post['BsPrchDt'] as $key => $val) {
                        $taxs = Yii::$app->db->createCommand("select t.tax_value from erp.bs_tax t where t.yn=1 and t.tax_pkid={$val['tax']}")->queryOne();
                        $totaltax += (double)$val['total_amount'];
                        $tatal += (double)$taxs['tax_value'];
                    }
                }
                $bsprch->total_amount = $totaltax * (1 + $tatal);//含税总金额=未税总金额*(1+总税率)
                $bsprch->tax_fee = $totaltax * $tatal;//总税额=未税总金额*总税率
                $bsprch->app_ip = Yii::$app->request->getUserIP();
                if (!$bsprch->save()) {
                    throw new Exception('修改采购单失败', json_encode($bsprch->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }
            if ($post['BsPrchDt'] != null) {
                $rreqprch = Yii::$app->getDb('prch')->createCommand("SELECT DISTINCT prch_dt_id from prch.r_req_prch where prch_dt_id in (select prch_dt_id from prch.bs_prch_dt where prch_id={$id})")->queryAll();
                $rreqprchcount = Yii::$app->getDb('prch')->createCommand("SELECT  count(DISTINCT prch_dt_id) num from prch.r_req_prch where prch_dt_id in (select prch_dt_id from prch.bs_prch_dt where prch_id={$id})")->queryAll();
                //return $rreqprchcount[0]['num'];
                if ($rreqprch != null) {
                    $decount = 0;
                    foreach ($rreqprch as $ke => $va) {
                        RReqPrch::deleteAll(['prch_dt_id' => $va]);
                        $decount += 1;
                    }
                    if ((int)$decount < (int)$rreqprchcount) {
                        throw  new \Exception("更新请购采购关联表失败" . json_encode($bsprch->getErrors()));
                    }
                }
                $bsprchdtcount = BsPrchDt::find()->where(['prch_id' => $id])->count();
                if (BsPrchDt::deleteAll(['prch_id' => $id]) < $bsprchdtcount) {
                    throw  new \Exception("更新采购详情失败" . json_encode($bsprch->getErrors()));
                }
                foreach ($post['BsPrchDt'] as $key => $val) {
                    $bsprchdt = new BsPrchDt();
                    $tax = Yii::$app->db->createCommand("select t.tax_value from erp.bs_tax t where t.yn=1 and t.tax_pkid={$val['tax']}")->queryOne();
                    $price = round(((double)$val['price']) * (1 + (double)$tax['tax_value']), 3);
                    $bsprchdt->prch_id = $id;
                    $bsprchdt->part_no = $val['part_no'];
                    $bsprchdt->deliv_date = $val['deliv_date'];
                    $bsprchdt->spp_id = $val['spp_id'];
                    $bsprchdt->prch_num = $val['prch_num'];
                    $bsprchdt->price = $val['price'];//未税单价
                    $bsprchdt->total_amount = $val['total_amount'];//未税总金额
                    $bsprchdt->price_tax = $price;//含税单价
                    $bsprchdt->total_am_tax = round(((double)$price * (double)$val['prch_num']), 3);//含税总金额;
                    $bsprchdt->goods_condition = $val['goods_condition'];
                    $bsprchdt->pay_condition = $val['pay_condition'];
                    $bsprchdt->tax = $val['tax'];
                    $bsprchdt->cur_id = $val['cur_id'];
                    if (!$bsprchdt->save()) {
                        throw new Exception('修改采购单详情失败', json_encode($bsprchdt->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                    $lstdtid = explode(",", $val['dt_id']);
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
            $transaction->commit();
            return $this->success("修改成功");
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    //获取采购单信息 by hf
    public function actionBuyInfo($id)
    {
        $sql = "SELECT
	d.prch_dt_id,
	d.prch_id,
	d.part_no,
	d.spp_id,
	d.prch_num,
	d.price,
	d.total_amount,
	d.deliv_date,
	d.tax,
	d.cur_id,
  d.pay_condition,
  d.goods_condition,
	bp.req_dct,
	bp.leg_id,
	bp.dpt_id,
	bp.area_id,
	bp.contact_info,
	bp.remarks,
	bp.apper,
	bp.app_date,
	bp.prch_no,
  bp.rcp_id,
  re.rcp_name,
  p.part_no,
 	p.tp_spec,
	p.pdt_name,
	p.brand,
	p.unit,
	s.group_code spp_code,
	s.spp_fname,
	ta.tax_pkid,
	ta.tax_no,
	ta.tax_name,
	ta.tax_value,
    pu.bsp_svalue currency
FROM
	prch.bs_prch_dt d
LEFT JOIN prch.bs_prch bp ON d.prch_id = bp.prch_id
LEFT JOIN pdt.bs_material p ON d.part_no = p.part_no
LEFT JOIN spp.bs_supplier s ON d.spp_id = s.spp_id
LEFT JOIN erp.bs_tax ta ON d.tax = ta.tax_pkid
LEFT JOIN wms.bs_receipt re ON bp.rcp_id=re.rcp_id
LEFT JOIN erp.bs_pubdata pu ON d.cur_id=pu.bsp_id
WHERE
	d.prch_id ={$id}
ORDER BY d.part_no ASC ";
        return Yii::$app->getDb('prch')->createCommand($sql)->queryAll();
    }

    //获取料号所对应的请购单号 by hf
    public function actionReqNo($id, $partno)
    {
        $sql = "SELECT 
DISTINCT
	br.req_no,
	br.req_id,
r.req_dt_id
-- re.req_nums
FROM
	prch.r_req_prch r
RIGHT JOIN prch.bs_req_dt re ON r.req_dt_id=re.req_dt_id
RIGHT JOIN prch.bs_req br ON re.req_id=br.req_id
WHERE
	r.prch_dt_id IN (
		SELECT
			d.prch_dt_id
		FROM
			prch.bs_prch_dt d
		LEFT JOIN prch.bs_prch p ON d.prch_id = p.prch_id
		WHERE
			d.prch_id = {$id}
		AND d.part_no = '{$partno}'
	)";
        return Yii::$app->getDb('prch')->createCommand($sql)->queryAll();
    }


    //导出
    public function actionExport()
    {

        $params = Yii::$app->request->queryParams;
//        $queryParams=[':id'=>$params['id']];
        $sql = "select      a.prch_id,
                          a.prch_no,
                          (CASE c.prch_name when '採購未提交' THEN '未提交' when '採購審核中' then '审核中' when '採購已駁回' then '驳回
' when '採購已取消' then '已取消' when '採購審核完成' then '审核完成' WHEN '已採購' then '已采购' WHEN '采购未提交' THEN '未提交' when '采购审核中' then '审核中' when '采购已驳回' then '驳回
' when '采购已取消' then '已取消' when '采购审核完成' then '审核完成' WHEN '已采购' then '已采购' ELSE c.prch_name end)prch_name,
                          a.prch_status,
                           LEFT (a.app_date,10)app_date,                           
                          b.bsp_svalue,
                          d.organization_name,
                          i.staff_name,                        
                          a.contact_info,
                          e.company_name
  --                        f.wh_addr,
 --                         k.spp_fname,
--                          g.cur_sname,
     --                     a.tax_fee,
    --                      (CASE h.yn_three WHEN 1 THEN '是' WHEN 0 THEN '否' ELSE h.yn_three END)yn_three,                
 --                         l.cur_id
                          from prch.bs_prch a 
                          LEFT JOIN erp.bs_pubdata b on a.req_dct=b.bsp_id
                          LEFT JOIN prch.prch_status c ON a.prch_status=c.prch_id
                          LEFT JOIN erp.hr_organization d on a.dpt_id=d.organization_id
                          LEFT JOIN erp.bs_company e on a.leg_id=e.company_id                                            
                          LEFT JOIN prch.bs_prch_dt l on a.prch_id=l.prch_id
   --                       LEFT JOIN wms.bs_wh f on l.addr_id=f.wh_id
 --                         LEFT JOIN erp. bs_currency g on l.cur_id=g.cur_id
                          LEFT JOIN prch.bs_req h on h.req_id=a.prch_id
                          LEFT JOIN erp.hr_staff i ON a.apper=i.staff_id
                          LEFT JOIN prch.bs_prch_dt j ON j.prch_id=a.prch_id
                          LEFT JOIN spp.bs_supplier k on j.spp_id=k.spp_id
                          WHERE a.prch_id IS NOT NULL
                           AND a.apper={$params['ids']}
                          ";
        $queryParams = [];
        if (!empty($params['req_dct'])) {
            $sql .= " and b.bsp_id ='" . $params['req_dct'] . "'";
        }
        if (!empty($params['leg_id'])) {
            $sql .= " and e.company_id ='" . $params['leg_id'] . "'";
        }
//        if (isset($params['yn_three']) && $params['yn_three'] != '') {
//            $sql .= " and yn_three ='".$params['yn_three']."'";
//        }
//        if (!empty($params['wh_addr'])) {
//            $sql .= " and f.wh_id='".$params['wh_addr']."'";
//        }
//        if (!empty($params['prch_no'])) {
//            $sql .= " and a.prch_no ='".$params['prch_no']."'";
//        }
        if (!empty($params['prch_no'])) {
            $trans = new Trans();
            $params['prch_no'] = str_replace(['%', '_'], ['\%', '\_'], $params['prch_no']);
            $queryParams[':prch_no1'] = '%' . $params['prch_no'] . '%';
            $queryParams[':prch_no2'] = '%' . $trans->c2t($params['prch_no']) . '%';
            $queryParams[':prch_no3'] = '%' . $trans->t2c($params['prch_no']) . '%';
            $sql .= " and (a.prch_no like :prch_no1 or a.prch_no like :prch_no2 or a.prch_no like :prch_no3)";
        }
//        if (!empty($params['spp_fname'])) {
//            $sql .= " and k.spp_id='".$params['spp_fname']."'";
//        }
        if (!empty($params['prch_status'])) {
            $sql .= " and c.prch_id='" . $params['prch_status'] . "'";
        }
        if (!empty($params['start_date'])) {
            $sql .= " and a.app_date >='" . $params['start_date'] . "'";
        }
        if (!empty($params['end_date'])) {
            $sql .= " and a.app_date <='" . $params['end_date'] . "'";
        }
        $sql .= " GROUP BY a.prch_id order by a.prch_id desc";
        $data = Yii::$app->db->createCommand($sql, $queryParams)->queryAll();
//        $index = 1;
        $date['tr'] = [];
        $date['tr'] = $data;
//        if (!empty($date)) {
//            foreach ($date['tr'] as $key => $val) {
//                $date['tr'][$key]['prch_id'] = $index;
//                $index++;
//            }
//        }
//        $date['th'] = ['采购单号', '采购单状态', '采购日期', '单据类型', '采购部門', '采购员', '联系方式', '法人', '收货中心', '供应商', '币别', '总金额', '是否三方交易'];

        return $date;
    }

}
