<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/12/20
 * Time: 上午 11:12
 */
namespace app\modules\warehouse\models\search;

use app\modules\warehouse\models\BsPck;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\data\SqlDataProvider;

class PickingListSearch extends BsPck{


    public $pck_no;
    public $status;
    public $wh_id;
    public $wh_code;
    public $wh_attr;
    public $pck_time;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pck_no', 'status', 'wh_id', 'wh_code', 'wh_attr', 'pck_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    //拣货单列表
    public function search($params){
        $queryParam=[];
        $sql="select s.*,a.bsp_svalue,t.o_whcode,f.staff_name from 
        (SELECT k.pck_no,k.pck_pkid,k.`status`,k.wh_id,k.pck_time,k.pck_man,h.wh_name,h.wh_code,h.wh_attr,t.note_no FROM wms.bs_pck k 
        LEFT JOIN wms.bs_wh h on k.wh_id=h.wh_id
				LEFT JOIN wms.shp_nt t on k.note_pkid=t.note_pkid)s
        LEFT JOIN erp.bs_pubdata a on a.bsp_id=s.wh_attr 
        LEFT JOIN wms.o_whpdt t on t.relate_packno=s.pck_no
        LEFT JOIN erp.hr_staff f on f.staff_id=s.pck_man where 1=1  ";
        $this->load($params);
        if (!$this->validate()) {
            return $sql;
        }
        if(!empty($params["pck_no"])){
            $pck_no=str_replace(' ', '', $params["pck_no"]);
            $sql=$sql."and s.pck_no like '%$pck_no%'  ";
        }
        if(!empty($params["status"])){
//            $queryParam[':status']=$params['status'];
            $sql=$sql."and s.status='{$params['status']}' ";
        }
        if(!empty($params["wh_id"])){
            $queryParam[':wh_id']=$params['wh_id'];
            $sql=$sql."and s.wh_id=:wh_id ";
        }
        if(!empty($params["wh_code"])){
            $wh_code=str_replace(' ', '', $params["wh_code"]);
            $sql=$sql."and s.wh_code  like '%$wh_code%'  ";
        }
        if(!empty($params["wh_attr"])){
            $queryParam[':wh_attr']=$params['wh_attr'];
            $sql=$sql."and s.wh_attr=:wh_attr  ";
        }
        if (!empty($params['start_date'])) {
            $queryParam[':start_date'] = date('Y-m-d H:i:s', strtotime($params['start_date']));
            $sql .= " and s.pck_time >= :start_date ";
        }
        if (!empty($params['end_date'])) {
            $queryParam[':end_date'] = date('Y-m-d H:i:s', strtotime($params['end_date'] . '+1 day'));
            $sql .= " and  s.pck_time < :end_date" ;
        }
        $sql.="order by s.pck_time desc";
        $totalCount =Yii::$app->getDb('wms')->createCommand("select count(*) from ( {$sql} ) a ", $queryParam)->queryScalar();
        $dataProvider = new SqlDataProvider([
            'db' => 'db',
            'sql' => $sql,
            'params' => $queryParam,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => $params['rows']
            ]
        ]);
        return $dataProvider;
    }

    /**
     * 点击主表获取子表商品信息
     * @param $params
     * @return ActiveDataProvider
     */
    public function  searchOrderProducts($params)
    {
//        $sql="SELECT ss.*,t.pdt_name,(SELECT bsp_svalue from erp.bs_pubdata where bsp_id=t.unit)unit FROM (SELECT s.*,o.pdt_pkid,o.tp_spec,
//(select part_name from wms.bs_part where part_code=t.part_code)part_name,t.rack_code,t.st_code,l.L_invt_bach,l.L_invt_num
//from (select t.part_no,t.st_id,t.pck_nums,t.marks,t.pack_date,dt.nums from wms.bs_pck k
//LEFT JOIN wms.bs_pck_dt t on k.pck_pkid=t.pck_pkid
//LEFT JOIN wms.shp_nt_dt dt on k.note_pkid=dt.note_pkid
//where k.pck_pkid={$params["id"]})s
//LEFT JOIN pdt.bs_partno o on o.part_no=s.part_no
//LEFT JOIN wms.bs_st t on t.st_id=s.st_id
//LEFT JOIN wms.l_bs_invt_list l on l.st_id=s.st_id)ss
//LEFT JOIN pdt.bs_product t on ss.pdt_pkid=t.pdt_pkid";
//        $sql="SELECT ss.*,t.pdt_name,(SELECT bsp_svalue from erp.bs_pubdata where bsp_id=t.unit)unit,(select brand_name_cn from pdt.bs_brand where t.brand_id=brand_id)brand_name_cn,ss.pck_nums count_pck_nums
//FROM (SELECT s.*,o.pdt_pkid,o.tp_spec
//from (select t.part_no,t.st_id,t.pck_nums,t.marks,DATE_FORMAT(t.pack_date,'%Y/%m/%d')pack_date,t.part_name,t.rack_code,t.L_invt_bach,FORMAT(dt.nums,2)nums from wms.bs_pck k
//LEFT JOIN wms.bs_pck_dt t on k.pck_pkid=t.pck_pkid
//LEFT JOIN wms.shp_nt_dt dt on k.note_pkid=dt.note_pkid
//where k.pck_pkid={$params['id']} GROUP BY dt.shpn_pkid)s
//LEFT JOIN pdt.bs_partno o on o.part_no=s.part_no
//)ss
//LEFT JOIN pdt.bs_product t on ss.pdt_pkid=t.pdt_pkid";
        $sql="select sss.*,t.pdt_name,(SELECT bsp_svalue from erp.bs_pubdata where bsp_id=t.unit)unit,
        (select brand_name_cn from pdt.bs_brand where t.brand_id=brand_id)brand_name_cn,sss.pck_nums count_pck_nums 
        FROM (select ss.*,o.pdt_pkid,o.tp_spec from (select s.*,t.pck_pkid,t.part_no,t.st_id,t.pck_nums,t.marks,DATE_FORMAT(t.pack_date,'%Y/%m/%d')pack_date,t.part_name,t.rack_code,t.L_invt_bach ,FORMAT(dt.nums,2)nums
        FROM(select DISTINCT k.note_pkid from wms.bs_pck k ,wms.bs_pck_dt t 
        where k.pck_pkid=t.pck_pkid and  k.pck_pkid={$params['id']})s ,wms.shp_nt_dt dt ,wms.bs_pck_dt t
        where dt.note_pkid=s.note_pkid
        and t.shpn_pkid=dt.shpn_pkid and t.pck_pkid={$params['id']})ss 
        LEFT JOIN pdt.bs_partno o on o.part_no=ss.part_no )sss 
        LEFT JOIN pdt.bs_product t on sss.pdt_pkid=t.pdt_pkid";
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => $params,
        ]);
        return $dataProvider;
    }

    //导出
    public function searchApply($params){
        $queryParam=[];
        $sql="select s.*,a.bsp_svalue,t.o_whcode,f.staff_name from 
        (SELECT k.pck_no,k.pck_pkid,k.`status`,k.wh_id,k.pck_time,k.pck_man,h.wh_name,h.wh_code,h.wh_attr,t.note_no FROM wms.bs_pck k 
        LEFT JOIN wms.bs_wh h on k.wh_id=h.wh_id
				LEFT JOIN wms.shp_nt t on k.note_pkid=t.note_pkid)s
        LEFT JOIN erp.bs_pubdata a on a.bsp_id=s.wh_attr 
        LEFT JOIN wms.o_whpdt t on t.relate_packno=s.pck_no
        LEFT JOIN erp.hr_staff f on f.staff_id=s.pck_man where 1=1 ";
        $this->load($params);
        if (!$this->validate()) {
            return $sql;
        }
        if(!empty($params["pck_no"])){
            $pck_no=str_replace(' ', '', $params["pck_no"]);
            $sql=$sql."and s.pck_no like '%$pck_no%'  ";
        }
        if(!empty($params["status"])){
//            $queryParam[':status']=$params['status'];
            $sql=$sql."and s.status='{$params['status']}' ";
        }
        if(!empty($params["wh_id"])){
            $queryParam[':wh_id']=$params['wh_id'];
            $sql=$sql."and s.wh_id=:wh_id ";
        }
        if(!empty($params["wh_code"])){
            $wh_code=str_replace(' ', '', $params["wh_code"]);
            $sql=$sql."and s.wh_code  like '%$wh_code%'  ";
        }
        if(!empty($params["wh_attr"])){
            $queryParam[':wh_attr']=$params['wh_attr'];
            $sql=$sql."and s.wh_attr=:wh_attr  ";
        }
        if (!empty($params['start_date'])) {
            $queryParam[':start_date'] = date('Y-m-d H:i:s', strtotime($params['start_date']));
            $sql .= " and s.pck_time >= :start_date ";
        }
        if (!empty($params['end_date'])) {
            $queryParam[':end_date'] = date('Y-m-d H:i:s', strtotime($params['end_date'] . '+1 day'));
            $sql .= " and  s.pck_time < :end_date" ;
        }
        $sql.="order by s.pck_time desc";
        $dataProvider=new SqlDataProvider([
            'sql' => $sql,
            'params'=>$queryParam,
            'pagination'=>[
                'pageSize'=>empty($params['rows'])?'':$params['rows']
            ],
        ]);
        return $dataProvider;
    }
    //查询弹出框的储位信息
    public function searchstinfo($params){
        $queryParam=[];
//        $sql="SELECT b.wh_id,t.part_no,t.st_id,t.L_invt_bach,t.L_invt_num,b.st_code,b.rack_code,
//        (SELECT part_name from wms.bs_part where b.part_code=part_code)part_name,b.part_code,
//        (select wh_name from wms.bs_wh where b.wh_id=wh_id)wh_name from wms.l_bs_invt_list t
//        LEFT JOIN wms.bs_st b on t.st_id=b.st_id
//        where b.YN='Y'  " ;

//        $sql="select b.wh_id,t.part_no,t.st_id,t.L_invt_bach,t.L_invt_num,b.st_code,b.rack_code,
//(SELECT part_name from wms.bs_part where b.part_code=part_code)part_name,b.part_code,
//(select wh_name from wms.bs_wh where b.wh_id=wh_id)wh_name from wms.l_bs_invt_list t,
//wms.bs_st b  where t.whs_id=b.wh_id and t.st_id=b.st_id  ";
        $sql="SELECT s.*,b.rack_code,(SELECT part_name from wms.bs_part where b.part_code=part_code)part_name,b.part_code,b.st_id from 
(select t.batch_no,t.invt_num,h.wh_id,t.st_code,t.part_no,h.wh_code,h.wh_name from wms.bs_sit_invt t LEFT JOIN wms.bs_wh h on t.wh_code=h.wh_code)s ,
wms.bs_st b where b.wh_id=s.wh_id and s.st_code=b.st_code  ";
//        and io_type=0
        $this->load($params);
        if (!$this->validate()) {
            return $sql;
        }
        if(!empty($params['st_code'])){
            $sql .= " and b.st_code not in(";
            $j= 0;
            $stidarry = array_values(array_filter(explode(",", $params['st_code'])));//拣货数量
//            return count($stidarry);
            for ($i=0;$i<count($stidarry);$i++){
                $queryParam[':st_code'.$i]=$stidarry[$i];
                $j++;
                if ($j == count($stidarry)) {
                    $sql .= ':st_code' . $i;
                } else {
                    $sql .= ':st_code' . $i . ',';
                }
            }
            $sql = $sql . ")   ";
        }
        if(!empty($params["part_no"])){
            $queryParam[':part_no']=$params['part_no'];
            $sql=$sql."and s.part_no=:part_no  ";
        }
        if(!empty($params["wh_code"])){
            $queryParam[':wh_code']=$params['wh_code'];
            $sql=$sql."and s.wh_code=:wh_code ";
        }
        if(!empty($params["lbach"])){
            $queryParam[':lbach']=$params['lbach'];
            $sql=$sql."and s.batch_no=:lbach ";
        }
        if(!empty($params["part_code"])) {
            $part_code = str_replace(' ', '', $params["part_code"]);
            $sql = $sql . "and b.part_code like '%$part_code%'  ";
        }
        if(!empty($params["rack_code"])){
            $rack_code=str_replace(' ', '', $params["rack_code"]);
            $sql=$sql."and b.rack_code like '%$rack_code%'  ";
        }
        if (!empty($params['stcode'])) {
            $st_code=str_replace(' ', '', $params["stcode"]);
            $sql=$sql."and s.st_code like '%$st_code%'  ";
        }
        $totalCount =Yii::$app->getDb('wms')->createCommand("select count(*) from ( {$sql} ) a ", $queryParam)->queryScalar();
        $dataProvider = new SqlDataProvider([
            'db' => 'db',
            'sql' => $sql,
            'params' => $queryParam,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => $params['rows']
            ]
        ]);
        return $dataProvider;
    }

}