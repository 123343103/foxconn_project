<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/12/20
 * Time: 上午 11:26
 */

namespace app\modules\warehouse\controllers;

use app\modules\common\models\BsForm;
use app\modules\warehouse\models\BsPck;
use app\modules\warehouse\models\BsPckDt;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\LBsInvtList;
use app\modules\warehouse\models\LInvtRe;
use app\modules\warehouse\models\OWhpdt;
use app\modules\warehouse\models\OWhpdtDt;
use app\modules\warehouse\models\ShpNt;
use Yii;

use app\controllers\BaseActiveController;
use app\modules\warehouse\models\search\PickingListSearch;
use yii\base\Exception;
use yii\base\Response;
use yii\data\SqlDataProvider;

class PickingListController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\BsPck';

    public function actionIndex()
    {
        $params = \Yii::$app->request->queryParams;
        $queryParam = [];
        $where = " WHERE 1=1 ";
        $where .= " and s.wh_id in(";
        $i = 0;
        foreach ($this->actionGetWhJurisdiction($params['staff_id'])[0] as $key => $val) {
            $queryParam[':wh_id' . $key] = $val['wh_id'];
            $i++;
            if ($i == count($this->actionGetWhJurisdiction($params['staff_id'])[0])) {
                $where .= ':wh_id' . $key;
            } else {
                $where .= ':wh_id' . $key . ',';
            }
        }
        $where = $where . ")";
        if (!empty($params["pck_no"])) {
            $pck_no = str_replace(' ', '', $params["pck_no"]);
            $where = $where . "  and s.pck_no like '%$pck_no%'  ";
        }
        if (!empty($params["status"])) {
//            $queryParam[':status']=$params['status'];
            $where = $where . "  and s.status='{$params['status']}' ";
        }
        if (!empty($params["wh_id"])) {
            $queryParam[':wh_id'] = $params['wh_id'];
            $where = $where . "  and s.wh_id=:wh_id ";
        }
        if (!empty($params["wh_code"])) {
            $wh_code = str_replace(' ', '', $params["wh_code"]);
            $where = $where . " and s.wh_code  like '%$wh_code%'  ";
        }
        if (!empty($params["wh_attr"])) {
            $queryParam[':wh_attr'] = $params['wh_attr'];
            $where = $where . " and s.wh_attr=:wh_attr  ";
        }
        if (!empty($params['start_date'])) {
            $queryParam[':start_date'] = date('Y/m/d', strtotime($params['start_date']));
            $where .= "  and s.pck_time >= :start_date ";
        }
        if (!empty($params['end_date'])) {
            $queryParam[':end_date'] = date('Y/m/d', strtotime($params['end_date'] . '+1 day'));
            $where .= "  and  s.pck_time < :end_date";
        }
        $sql = "select s.*,a.bsp_svalue,t.o_whcode,f.staff_name from 
        (SELECT k.pck_no,k.pck_pkid,k.`status`,k.wh_id,DATE_FORMAT(k.pck_time,'%Y/%m/%d')pck_time,k.pck_man,h.wh_name,h.wh_code,h.wh_attr,t.note_no,t.pic_date FROM wms.bs_pck k 
        LEFT JOIN wms.bs_wh h on k.wh_id=h.wh_id
				LEFT JOIN wms.shp_nt t on k.note_pkid=t.note_pkid)s
        LEFT JOIN erp.bs_pubdata a on a.bsp_id=s.wh_attr 
        LEFT JOIN wms.o_whpdt t on t.relate_packno=s.pck_no
        LEFT JOIN erp.hr_staff f on f.staff_id=s.pck_man {$where}";
        $sql .= " order by s.pic_date desc,s.pck_no DESC";
        $totalCount = Yii::$app->getDb('wms')->createCommand("select count(*) from ( {$sql} ) a ", $queryParam)->queryScalar();
        $provider = new SqlDataProvider([
            "sql" => $sql,
            "totalCount" => $totalCount,
            "params" => $queryParam,
            "pagination" => [
                "page" => isset($params["page"]) ? $params["page"] - 1 : 0,
                "pageSize" => isset($params["rows"]) ? $params["rows"] : 10,
            ]
        ]);
        return [
            "rows" => $provider->models,
            "total" => $provider->totalCount
        ];
//        $search = new PickingListSearch();
//        $dataProvider = $search->search(Yii::$app->request->queryParams,$staff_id);
//        $model = $dataProvider->getModels();
//        return $model;
//        $list['rows'] = $model;
//        $list["total"] = $dataProvider->totalCount;
//        return $list;
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
              WHERE u.staff_id =:staff_id and bw.wh_id<>'' GROUP BY bw.wh_id";
        $queryParam = [
            ':staff_id' => $staff_id,
        ];
        $model = \Yii::$app->get('db')->createCommand($sql, $queryParam)->queryAll();
        $sql2="select bsp_svalue,bsp_id from erp.bs_pubdata where bsp_stype='CKSX' and bsp_status=10";
        $model2 = \Yii::$app->get('db')->createCommand($sql2)->queryAll();
        $info=[$model,$model2];
        return $info;
    }

    // 点击主表获取子表商品信息
    public function actionGetProducts()
    {
        $params = Yii::$app->request->queryParams;
        $model = new PickingListSearch();
        $model = $model->searchOrderProducts($params);
        return $model;
    }

    // 下拉列表
    public function actionGetDownList()
    {
        $downList = [];
        $downList['warehouse'] = BsWh::find()->select(['wh_id', 'wh_code', 'wh_name'])->all();
        return $downList;
    }

    public function actionModels($id)
    {
        return $this->findModel($id);
    }

    protected function findModel($id)
    {
        //拣货单信息
        $sql = "SELECT ss.*,y.company_name,o.cust_sname,e.business_value from(select s.*,f.staff_name, (select organization_name from erp.hr_organization where f.organization_code=organization_code)organization_name,
        o.corporate ,o.ord_no,o.ord_type,o.cust_code
        from(SELECT k.pck_no,k.`status`,t.pic_date,t.soh_id,h.wh_name,h.wh_code,t.operator,
        (select bsp_svalue from erp.bs_pubdata where bsp_id=h.wh_attr)wh_attr,t.note_no,
        (SELECT staff_name from erp.hr_staff where staff_id=t.pickor)pickor,k.cancle_reason
        FROM wms.bs_pck k
        LEFT JOIN wms.shp_nt t on k.note_pkid=t.note_pkid
        LEFT JOIN wms.bs_wh h on h.wh_id=k.wh_id where k.pck_pkid=:id)s
        LEFT JOIN erp.hr_staff f  on f.staff_id=s.operator
        LEFT JOIN oms.ord_info o on o.ord_id=s.soh_id)ss
        LEFT JOIN erp.bs_company y on y.company_id=ss.corporate
				LEFT JOIN erp.crm_bs_customer_info o on o.cust_code = ss.cust_code
				LEFT JOIN erp.bs_business_type e on e.business_type_id=ss.ord_type";
        $basicinfo = Yii::$app->db->createCommand($sql)->bindValue(':id', $id)->queryOne();
        //商品信息
        $sql2 = "select sss.*,t.pdt_name,(SELECT bsp_svalue from erp.bs_pubdata where bsp_id=t.unit)unit,
        (select brand_name_cn from pdt.bs_brand where t.brand_id=brand_id)brand_name_cn,sss.pck_nums count_pck_nums 
        FROM (select ss.*,o.pdt_pkid,o.tp_spec from (select s.*,t.pck_pkid,t.part_no,t.st_id,t.pck_nums,t.marks,DATE_FORMAT(t.pack_date,'%Y/%m/%d')pack_date,t.part_name,t.rack_code,t.L_invt_bach ,FORMAT(dt.nums,2)nums
        FROM(select DISTINCT k.note_pkid from wms.bs_pck k ,wms.bs_pck_dt t 
        where k.pck_pkid=t.pck_pkid and  k.pck_pkid=:id)s ,wms.shp_nt_dt dt ,wms.bs_pck_dt t
        where dt.note_pkid=s.note_pkid
        and t.shpn_pkid=dt.shpn_pkid and t.pck_pkid=:id)ss 
        LEFT JOIN pdt.bs_partno o on o.part_no=ss.part_no )sss 
        LEFT JOIN pdt.bs_product t on sss.pdt_pkid=t.pdt_pkid";
        $productinfo = Yii::$app->db->createCommand($sql2)->bindValue(':id', $id)->queryAll();
        //拣货数量维护
        $sql3 = "select k.wh_id,h.wh_name,wh_code,(select bsp_svalue from erp.bs_pubdata where h.wh_attr=bsp_id)wh_attr from wms.bs_pck k 
LEFT JOIN wms.bs_wh h on k.wh_id=h.wh_id where k.pck_pkid=:id";
        $whinfo = Yii::$app->db->createCommand($sql3)->bindValue(':id', $id)->queryOne();
        $sql4 = "select sss.*,t.pdt_name,(SELECT bsp_svalue from erp.bs_pubdata where bsp_id=t.unit)unit
        FROM (select ss.*,o.pdt_pkid,FORMAT(i.invt_num,2)invt_num from (select s.*,t.pck_dt_pkid,t.part_no,t.pck_nums,t.marks,FORMAT(t.req_num,2)req_num,
        FORMAT(dt.nums,2)nums,dt.shpn_pkid,dt.sol_id,(select wh_code from wms.bs_wh where wh_id=s.wh_id)wh_code
        FROM(select DISTINCT k.note_pkid,k.wh_id from wms.bs_pck k ,wms.bs_pck_dt t 
        where k.pck_pkid=t.pck_pkid and  k.pck_pkid=:id)s ,wms.shp_nt_dt dt ,wms.bs_pck_dt t
        where dt.note_pkid=s.note_pkid
        and t.shpn_pkid=dt.shpn_pkid and t.pck_pkid=:id)ss 
        LEFT JOIN pdt.bs_partno o on o.part_no=ss.part_no 
        LEFT JOIN wms.bs_wh_invt i on i.wh_code=ss.wh_code and i.part_no=ss.part_no
        )sss 
        LEFT JOIN pdt.bs_product t on sss.pdt_pkid=t.pdt_pkid";
        $pickinfo = Yii::$app->db->createCommand($sql4)->bindValue(':id', $id)->queryAll();
        $newmodel = array();//合并拣货数量后的新数据
        foreach ($productinfo as $key=>$val){
            $newmodel[$key]=$val;
            $lstdtid = explode(",",$val['pck_nums']);
            $newmodel[$key]["pck_nums"]=implode(',',$lstdtid );
            $nums=0;
            if(count($lstdtid)>1)
            {
                foreach ($lstdtid as $k => $v) {
//                    $nums+=(int)$v;
                    $nums+=number_format($v,2);
                    $lstdtid[$k]=number_format($v,2);
                }
                $newmodel[$key]['count_pck_nums']=number_format($nums,2);
                $newmodel[$key]["pck_nums"] = implode(',', $lstdtid);
            }else {
                $newmodel[$key]['count_pck_nums'] = number_format($val['pck_nums'], 2);
            }
        }
        $infoall = [$basicinfo, $newmodel, $whinfo, $pickinfo];
//        $infoall = [$productinfo, $whinfo, $pickinfo];
        if ($infoall !== null) {
            return $infoall;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //取消
    public function actionCancelPick($id)
    {
        //$_succ="操作失败";
        $data = Yii::$app->request->post();
        $bspack = BsPck::findOne($id);
        $bspack->status = 3;
        $bspack->pck_IP = Yii::$app->request->getUserIP();
        $bspack->cancle_date = date('Y-m-d H:i:s', time());
        $bspack->load($data);
        if (!$bspack->validate()) {
            return $this->error(json_encode($bspack->getErrors(),JSON_UNESCAPED_UNICODE));
        }
        $shpnt=ShpNt::findOne($bspack->note_pkid);
        $bspid = Yii::$app->db->createCommand("SELECT bsp_id FROM erp.bs_pubdata where bsp_stype='TZDZT' and bsp_svalue='待处理' ")->queryOne();
        $shpnt->status=$bspid['bsp_id'];
        if (!$shpnt->validate()) {
//            return $this->success('操作成功');
            return $this->error(json_encode($shpnt->getErrors(),JSON_UNESCAPED_UNICODE));
        }
        $bspack->save(false);
        $shpnt->save(false);
        return $this->success('操作成功');
    }

    //导出
    public function actionExport()
    {
        $params = \Yii::$app->request->queryParams;
        $queryParam = [];
        $where = " WHERE 1=1 ";
        $where .= " and s.wh_id in(";
        $i = 0;
        foreach ($this->actionGetWhJurisdiction($params['staff_id'])[0] as $key => $val) {
            $queryParam[':wh_id' . $key] = $val['wh_id'];
            $i++;
            if ($i == count($this->actionGetWhJurisdiction($params['staff_id'])[0])) {
                $where .= ':wh_id' . $key;
            } else {
                $where .= ':wh_id' . $key . ',';
            }
        }
        $where = $where . ")";
        if (!empty($params["pck_no"])) {
            $pck_no = str_replace(' ', '', $params["pck_no"]);
            $where = $where . "  and s.pck_no like '%$pck_no%'  ";
        }
        if (!empty($params["status"])) {
//            $queryParam[':status']=$params['status'];
            $where = $where . "  and s.status='{$params['status']}' ";
        }
        if (!empty($params["wh_id"])) {
            $queryParam[':wh_id'] = $params['wh_id'];
            $where = $where . "  and s.wh_id=:wh_id ";
        }
        if (!empty($params["wh_code"])) {
            $wh_code = str_replace(' ', '', $params["wh_code"]);
            $where = $where . " and s.wh_code  like '%$wh_code%'  ";
        }
        if (!empty($params["wh_attr"])) {
            $queryParam[':wh_attr'] = $params['wh_attr'];
            $where = $where . " and s.wh_attr=:wh_attr  ";
        }
        if (!empty($params['start_date'])) {
            $queryParam[':start_date'] = date('Y/m/d', strtotime($params['start_date']));
            $where .= "  and s.pck_time >= :start_date ";
        }
        if (!empty($params['end_date'])) {
            $queryParam[':end_date'] = date('Y/m/d', strtotime($params['end_date'] . '+1 day'));
            $where .= "  and  s.pck_time < :end_date";
        }
        $sql = "select s.*,a.bsp_svalue as cksx,t.o_whcode,f.staff_name from 
        (SELECT k.pck_no,k.pck_pkid,k.`status`,k.wh_id,DATE_FORMAT(k.pck_time,'%Y/%m/%d')pck_time,k.pck_man,h.wh_name,h.wh_code,h.wh_attr,t.note_no,t.pic_date FROM wms.bs_pck k 
        LEFT JOIN wms.bs_wh h on k.wh_id=h.wh_id
				LEFT JOIN wms.shp_nt t on k.note_pkid=t.note_pkid)s
        LEFT JOIN erp.bs_pubdata a on a.bsp_id=s.wh_attr 
        LEFT JOIN wms.o_whpdt t on t.relate_packno=s.pck_no
        LEFT JOIN erp.hr_staff f on f.staff_id=s.pck_man {$where}";
        $sql .= " order by s.pic_date desc,s.pck_no DESC";
//        $totalCount = Yii::$app->getDb('wms')->createCommand("select count(*) from ( {$sql} ) a ", $queryParam)->queryScalar();
        $provider = new SqlDataProvider([
            "sql" => $sql,
//            "totalCount" => $totalCount,
            "params" => $queryParam,
            'pagination'=>[
                'pageSize'=>empty($params['rows'])?'':$params['rows']
            ],
        ]);
//        return $provider->sql;
        return $provider->getModels();
//        $model = new PickingListSearch();
//        $dataProvider = $model->searchApply(Yii::$app->request->queryParams);
//        return $dataProvider->getModels();
    }

    //加载弹出框的商品信息
    public function actionSelectStinfo()
    {
        $search = new PickingListSearch();
        $dataProvider = $search->searchstinfo(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    //拣货数量维护
    public function actionMaintenancePick()
    {
        $post = Yii::$app->request->post();
        // 查找信息
        $transaction = Yii::$app->wms->beginTransaction();
        try {
            $bspck = BsPck::findOne($post["id"]);
            $bspck->pck_time = date('Y-m-d H:i:s', time());
            $bspck->pck_IP = Yii::$app->request->getUserIP();
            $bspck->status = 1;
            $bspck->pck_man = $post["BsPck"]["pck_man"];
            if (!$bspck->save()) {
                throw new Exception(json_encode($bspck->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            if ($post["BsPckDt"] != null) {
                foreach ($post['BsPckDt'] as $key => $val) {

                    $pcknumarray = array_values(array_filter(explode(",", $val['pck_nums'])));//拣货数量
                    $stidarray = array_values(array_filter(explode(",", $val["st_id"])));//储位
                    $invtnums = explode(",", $val["L_invt_num"]);//储位对应的库存数
                    $pck_nums = implode(',', $pcknumarray);
                    $st_id = implode(',', $stidarray);
                    $t=!(count($pcknumarray)==count($stidarray));
                    if($t){
                        throw new \Exception(json_encode('请将储位与数量一一对应！', JSON_UNESCAPED_UNICODE));
                    }
                    for ($i = 0; $i < count($pcknumarray); $i++) {
                        $pcknum = $pcknumarray[$i];//拆分出来的从每个储位拣货的数量
                        $stnum = $invtnums[$i];//拆分出来的每个储位对应的库存数量
                        if ($pcknum > $stnum) {
                            throw new \Exception(json_encode("料号.{$val["part_no"]}.的储位$stidarray[$i]对应的拣货数量大于该储位剩余的库存数量！", JSON_UNESCAPED_UNICODE));
                        }
                    }
//                    $countpcknum=0;
//                    if (count($pcknumarray) > 1) {
//                        foreach ($pcknumarray as $k => $v) {
//                            $countpcknum += $v;
//                        }
//                    } else {
//                        $countpcknum= $val['pck_nums'];
//                    }
//                    return count($pcknumarray);
                    $quantityinterval = Yii::$app->db->createCommand("select o.upp_num,o.low_num from (SELECT * FROM erp.bs_pubdata a where a.bsp_stype='blsz_djlx' and a.bsp_svalue='拣货单' and a.bsp_status=10)s,erp.bs_ratio o where o.ratio_type=s.bsp_id and o.yn=1")->queryOne();
//                    $maxnum=$quantityinterval["upp_num"]*$val["req_num"];//最大值
                    $maxnum=$val['req_num']*(1+$quantityinterval["upp_num"]);//最大值
//                    $minnum=$quantityinterval["low_num"]*$val["req_num"];//最小值
                    $minnum=$val['req_num']*(1-$quantityinterval["low_num"]);//最小值
                    if($val['countnum']>$maxnum||$val['countnum']<$minnum){
                        throw new \Exception(json_encode("料号{$val["part_no"]}的拣货数量不能大于'$maxnum'并且不能小于'$minnum'！'", JSON_UNESCAPED_UNICODE));
                    }
                    BsPckDt::deleteAll(['pck_dt_pkid'=>$val["pck_dt_pkid"]]);
                    $bspckdt=new BsPckDt();
                    $bspckdt->shpn_pkid=$val["shpn_pkid"];
                    $bspckdt->pck_pkid=$bspck->pck_pkid;
                    $bspckdt->st_id=$st_id;
                    $bspckdt->pck_nums=$pck_nums;
                    $bspckdt->marks=$val["marks"];
                    $bspckdt->sol_id=$val["sol_id"];
                    $bspckdt->pack_date=$val["pack_date"];
                    $bspckdt->part_no=$val["part_no"];
                    $bspckdt->part_name=$val["part_name"];
                    $bspckdt->rack_code=$val["rack_code"];
                    $bspckdt->L_invt_bach=$val["L_invt_bach"];
                    $bspckdt->req_num=$val["req_num"];
//                    $bspckdt = BsPckDt::findOne($val["pck_dt_pkid"]);
//                    $pcknumarray = array_values(array_filter(explode(",", $val['pck_nums'])));//拣货数量
//                    $stidarray = array_values(array_filter(explode(",", $val["st_id"])));//储位
//                    $invtnums = explode(",", $val["L_invt_num"]);//储位对应的库存数
//                    $pck_nums = implode(',', $pcknumarray);
//                    $st_id = implode(',', $stidarray);
////                    return $st_id;
//                    $t=!(count($pcknumarray)==count($stidarray));
//                    if($t){
//                        throw new \Exception(json_encode('请将储位与数量一一对应！', JSON_UNESCAPED_UNICODE));
//                    }
//
//                    for ($i = 0; $i < count($pcknumarray); $i++) {
//                        $pcknum = $pcknumarray[$i];//拆分出来的从每个储位拣货的数量
//                        $stnum = $invtnums[$i];//拆分出来的每个储位对应的库存数量
//                        if ($pcknum > $stnum) {
//                            throw new \Exception(json_encode("料号$bspckdt->part_no.的储位$stidarray[$i]对应的拣货数量大于该储位剩余的库存数量！", JSON_UNESCAPED_UNICODE));
//                        }
//                    }
//                    $bspckdt->st_id = $st_id;
//                    $bspckdt->pck_nums = $pck_nums;
//                    $bspckdt->marks = $val["marks"];
//                    $bspckdt->pack_date = $val["pack_date"];
//                    $bspckdt->part_name = $val["part_name"];
//                    $bspckdt->rack_code = $val["rack_code"];
//                    $bspckdt->L_invt_bach = $val["L_invt_bach"];
                    if (!$bspckdt->save()) {
                        throw new Exception(json_encode($bspckdt->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
//                    foreach ($stidarray as $k=>$v ){
                    for($j=0;$j<count($stidarray);$j++){
                        $linvtre=new LInvtRe();
                        $linvtre->l_types=9;
                        $whcodeone = Yii::$app->wms->createCommand("SELECT wh_code,wh_name from bs_wh where wh_id='$bspck->wh_id'")->queryOne();
                        $linvtre->wh_code=$whcodeone["wh_code"];
                        $linvtre->wh_name=$whcodeone["wh_name"];
//                        $stcode = Yii::$app->wms->createCommand("SELECT * from bs_st where st_code={$stidarray[$j]}")->queryOne();
//                        $linvtre->st_code=$stcode["st_code"];
                        $linvtre->st_code=$stidarray[$j];
                        $linvtre->l_r_no=$bspck->pck_no;
                        $linvtre->pdt_name=$val["pdt_name"];
                        $linvtre->batch_no=$bspckdt->L_invt_bach;
                        $linvtre->part_no=$val["part_no"];
                        $linvtre->unit_name=$val["unit"];
                        $linvtre->lock_nums=$pcknumarray[$j];
                        $linvtre->invt_nums=0;
                        $linvtre->opp_date=date('Y-m-d H:i:s', time());
                        $staffinfo=Yii::$app->db->createCommand("SELECT staff_name,staff_code from erp.hr_staff where staff_id=$bspck->pck_man")->queryOne();
                        $linvtre->opper=$staffinfo["staff_code"].'-'.$staffinfo["staff_name"];
                        $linvtre->yn=0;
                        if (!$linvtre->save()) {
                            throw new Exception(json_encode($linvtre->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
//                }
            }
            $transaction->commit();
            return $this->success('拣货数量维护成功！');
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //生成出库单
    public function actionOutPick($pck_pkid)
    {
        $post = Yii::$app->request->post();
        // 查找信息
        $transaction = Yii::$app->wms->beginTransaction();
        try {
            $bspck = BsPck::findOne($pck_pkid);
            $bspck->status = 2;
            if (!$bspck->save()) {
                throw new Exception(json_encode($bspck->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            $shpnt = ShpNt::findOne($bspck->note_pkid);
            $OWhpdt = new OWhpdt();
            $OWhpdt->buss_type = 1;
            $OWhpdt->o_whcode = BsForm::getCode("o_whpdt", $OWhpdt);
            $OWhpdt->o_whid = $bspck->wh_id;
            $OWhpdt->o_whstatus = 0;//待出库
            $OWhpdt->relate_packno = $bspck->pck_no;
            $OWhpdt->o_date = date('Y-m-d H:i:s', time());
            $OWhpdt->ord_id = $shpnt->soh_id;
            $OWhpdt->creator = $post["o_whpdt"]["creator"];
            $OWhpdt->creat_date = date('Y-m-d H:i:s', time());
            $OWhpdt->creat_ip = Yii::$app->request->getUserIP();
            $OWhpdt->logistics_type=$shpnt->trans_mode;
            $deliverytypet = Yii::$app->oms->createCommand("select t.distribution from oms.ord_dt t where t.ord_id=$shpnt->soh_id group by t.distribution")->queryOne();
            $OWhpdt->delivery_type=$deliverytypet["distribution"];
            $ordinfo = Yii::$app->oms->createCommand("select * from oms.ord_info t where t.ord_id=$shpnt->soh_id")->queryOne();
            $OWhpdt->reciver=$ordinfo["receipter"];
            $OWhpdt->reciver_tel=$ordinfo["receipter_Tel"];
            $OWhpdt->district_id=$ordinfo["receipt_areaid"];
            $OWhpdt->address=$ordinfo["receipt_Address"];
            $appdepart=Yii::$app->db->createCommand("SELECT n.organization_id FROM erp.hr_staff f left join erp.hr_organization n on f.organization_code=n.organization_code where f.staff_id={$post["o_whpdt"]["creator"]}")->queryOne();
            $OWhpdt->app_depart=$appdepart["organization_id"];
            if (!$OWhpdt->save()) {
                throw new Exception(json_encode($OWhpdt->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            $Bspckdt = Yii::$app->wms->createCommand("select * from wms.bs_pck_dt t where t.pck_pkid={$pck_pkid}")->queryAll();
            if ($Bspckdt != null) {
                foreach ($Bspckdt as $key => $val) {
                    $OWhpdtdt = new OWhpdtDt();
                    $OWhpdtdt->o_whpkid = $OWhpdt->o_whpkid;
                    $OWhpdtdt->part_no = $val["part_no"];
                    $OWhpdtdt->req_num = $val["req_num"];
                    $OWhpdtdt->pck_dt_pkid = $val["pck_dt_pkid"];
//                    $OWhpdtdt->invt_bach=$val["L_invt_bach"];
                    $pcknum = explode(",", $val['pck_nums']);
                    $o_whnum = "";
                    if (count($pcknum) > 1) {
                        foreach ($pcknum as $k => $v) {
                            $o_whnum += $v;
                        }
                        $OWhpdtdt->o_whnum = (float)$o_whnum;
                    } else {
                        $OWhpdtdt->o_whnum = $val['pck_nums'];
                    }
                    if (!$OWhpdtdt->save()) {
                        throw new Exception(json_encode($OWhpdtdt->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
//                    $stid = explode(",", $val['st_id']);
//                    for ($i = 0; $i < count($stid); $i++) {
//                        $lbsinvtlistone = Yii::$app->wms->createCommand("SELECT invt_iid,whs_id FROM wms.l_bs_invt_list st where whs_id='$OWhpdt->o_whid '  and st_id='{$stid[$i]}' and L_invt_bach='{$val['L_invt_bach']}' and part_no='$OWhpdtdt->part_no' ")->queryOne();
//                        $lbsinvtlist = LBsInvtList::findOne($lbsinvtlistone["invt_iid"]);
//                        $lbsinvtlist->L_invt_num =$lbsinvtlist->L_invt_num-(float)$pcknum[$i];
//                        $lbsinvtlist->update_date=date('Y-m-d', time());
//                        if (!$lbsinvtlist->save()) {
//                            throw new Exception(json_encode($lbsinvtlist->getErrors(), JSON_UNESCAPED_UNICODE));
//                        }
//                    }
                }
            }
            $transaction->commit();
            return $this->success('出库成功！');
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //弹出框中的批次信息
    public function actionLinvtBach($wh_code,$partno){
        $sql="select batch_no from wms.bs_sit_invt where  part_no=:partno and wh_code=:wh_code GROUP BY batch_no ORDER BY batch_no ";
        $queryParam = [
            ':partno' => $partno,
            ':wh_code'=>$wh_code
        ];
        $model = \Yii::$app->get('wms')->createCommand($sql, $queryParam)->queryAll();
        return $model;
    }

}