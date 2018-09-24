<?php
namespace app\modules\warehouse\models\Search;

use app\classes\Trans;
use app\modules\common\models\BsProduct;
use app\modules\warehouse\models\BsInvt;
use app\modules\warehouse\models\show\ProductInfoShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

class ProductInfoSearch extends BsInvt{
    public $part_no;
    public $partno;
    public $wh_id;
    public $category_sname;
    public $pdt_name;
    public $type_1;
    public $type_2;
    public $type_3;
    public function rules()
    {
        return [
            [['wh_id'], 'safe'],
            [['pdt_name'], 'safe'],/*, 'category_sname'*/
            [['part_no'],'safe'],
            [['type_1'],'safe'],
            [['type_2'],'safe'],[['type_3'],'safe'],
        ];
    }
     public function scenarios()
     {
         // bypass scenarios() implementation in the parent class
         return Model::scenarios();
     }

     //批量添加数据搜索
     public function search($params)
     {
         //$query = ProductInfoShow::find();/*'inv_id,OPP_DATE,wh_code,wh_code,so_nbr,remarks',staff_code,staff_name,staff_mobile,staff_email,category_sname,*/
         //$querParams = [];
         //$sql1="select bh.inv_id from(select n.biw_h_pkid from(select h.inv_warn_PKID from wms.inv_warner r left join wms.inv_warner_h h on r.LIW_PKID=h.LIW_PKID where r.YN<>0)s left join
 //wms.bs_inv_warn n on n.inv_warn_PKID=s.inv_warn_PKID)ss left join wms.bs_inv_warn_h bh on ss.biw_h_pkid=bh.biw_h_pkid";

        $count='select 
count(*)
from (SELECT
	wa.part_no,
	h.biw_h_pkid,
	wa.down_nums,
	wa.up_nums,
	h.inv_id,
wh.wh_name,
h.wh_id
FROM
	wms.bs_inv_warn_h h
LEFT JOIN wms.bs_inv_warn wa ON h.biw_h_pkid = wa.biw_h_pkid
LEFT JOIN wms.bs_wh wh on wh.wh_id=h.wh_id where h.so_type=20 and h.YN=1
)s LEFT JOIN erp.bs_product p ON s.part_no = p.pdt_no LEFT JOIN wms.bs_invt vt on vt.part_no=s.part_no and vt.wh_id=s.wh_id where s.inv_id not in (select bh.inv_id from(select n.biw_h_pkid from(select h.inv_warn_PKID from wms.inv_warner r left join wms.inv_warner_h h on r.LIW_PKID=h.LIW_PKID where r.YN<>0)s left join
 wms.bs_inv_warn n on n.inv_warn_PKID=s.inv_warn_PKID)ss left join wms.bs_inv_warn_h bh on ss.biw_h_pkid=bh.biw_h_pkid where bh.YN=1)  ';
         $sql='select 
s.part_no,
s.down_nums,
s.inv_id,
s.up_nums,
s.wh_name,
(select b.BRAND_NAME_CN from erp.bs_brand b where p.brand_id=b.brand_id)BRAND_NAME_CN,
(select c.category_sname from erp.bs_category c where p.bs_category_id=c.category_id)category_sname,
s.wh_id,
p.pdt_name
,vt.invt_num
from (SELECT
	wa.part_no,
	h.biw_h_pkid,
	wa.down_nums,
	wa.up_nums,
	h.inv_id,
wh.wh_name,
h.wh_id
FROM
	wms.bs_inv_warn_h h
LEFT JOIN wms.bs_inv_warn wa ON h.biw_h_pkid = wa.biw_h_pkid
LEFT JOIN wms.bs_wh wh on wh.wh_id=h.wh_id where h.so_type=20 and h.YN=1
)s LEFT JOIN erp.bs_product p ON s.part_no = p.pdt_no 
LEFT JOIN wms.bs_invt vt on vt.part_no=s.part_no and vt.wh_id=s.wh_id
where  s.inv_id not in (select bh.inv_id from(select n.biw_h_pkid from(select h.inv_warn_PKID from wms.inv_warner r left join wms.inv_warner_h h on r.LIW_PKID=h.LIW_PKID where r.YN<>0)s left join
 wms.bs_inv_warn n on n.inv_warn_PKID=s.inv_warn_PKID)ss left join wms.bs_inv_warn_h bh on ss.biw_h_pkid=bh.biw_h_pkid where bh.YN=1)  ';
         $this->load($params);
         if(!$this->validate()){
             return $sql;
         }
         if(!empty($this->wh_id)){
             $sql=$sql."  and s.wh_id='$this->wh_id'";
             $count=$count."  and s.wh_id='$this->wh_id'";
         }
         $types=[];
         $types[]=isset($params['type_1'])?$params['type_1']:"";
         $types[]=isset($params['type_2'])?$params['type_2']:"";
         $types[]=isset($params['type_3'])?$params['type_3']:"";
         $types=array_filter($types);
         $type=count($types)>0?$types[count($types)-1]:"";
         if(!empty($type)){
             $sql=$sql.' and s.part_no like \''.$type.'%\'';
             $count=$count.'  and s.part_no like \''.$type.'%\'';
         }
         if(!empty($this->part_no)){
             $sql=$sql.' and s.part_no like \'%'.$this->part_no.'%\'';
             $count=$count.' and s.part_no like \'%'.$this->part_no.'%\'';
         }
         if(!empty($this->pdt_name)){
             $sql=$sql.' and p.pdt_name like \'%'.$this->pdt_name.'%\'';
             $count=$count.' and p.pdt_name like \'%'.$this->pdt_name.'%\'';
         }
         $totalCount=\Yii::$app->db->createCommand($count,null)->queryScalar();
         $dataProvider = new SqlDataProvider([
             'sql' => $sql,
             'totalCount'=>$totalCount,
             'pagination' => [
                 'pageSize' => $params['rows'],
             ]
         ]);
         return $dataProvider;

//         if(isset($params['rows'])){
//             $pageSize = $params['rows'];
//         }else{
//             if(isset($params['export'])){
//                 $pageSize =false;
//             }else{
//                 $pageSize =10;
//             }
//         }
//         $dataProvider = new ActiveDataProvider([
//             'query' => $query,
//             'pagination' => [
//                 'pageSize' => $pageSize,
//             ]
//         ]);
//         $this->load($params);
//         if (!$this->validate()) {
//             return $dataProvider;
//         }
//         $query->andFilterWhere(['=', 'invt_code',$this->invt_code]);
//         $types=[];
//         $types[]=isset($params['type_1'])?$params['type_1']:"";
//         $types[]=isset($params['type_2'])?$params['type_2']:"";
//         $types[]=isset($params['type_3'])?$params['type_3']:"";
//         $types=array_filter($types);
//         $type=count($types)>0?$types[count($types)-1]:"";
//         $query->andFilterWhere(["like","part_no",$type]);
//
////                                   $commandQuery = clone $query;
////                         echo $commandQuery->createCommand()->getRawSql();
////                         exit;
//         $query->andFilterWhere(['like', 'part_no',$this->part_no]);
     }

//     //按商品分类搜索
//     public function searchcategory($params){
//        // $query = ProductInfoShow::find();
//         //$trans=new Trans();
//         $part_no=$params["partno"];
//         $count="select count(*)  from wms.bs_inv_warn w  LEFT JOIN wms.bs_invt i ON i.part_no = w.part_no
//left join erp.bs_product p on w.part_no=p.pdt_no
//left join wms.bs_wh h on w.wh_code=h.wh_code where (w.part_no like '$part_no%') ";
//         $sql="SELECT
//	w.part_no,
//	w.down_nums,
//	w.up_nums,
//	w.save_num,
//	i.invt_num,
//p.pdt_name,
//p.pdt_model,
//(select b.BRAND_NAME_CN from erp.bs_brand b where b.brand_id=p.brand_id)BRAND_NAME_CN,
//h.wh_name
//FROM
//	wms.bs_inv_warn w
//LEFT JOIN wms.bs_invt i ON i.part_no = w.part_no
//left join erp.bs_product p on w.part_no=p.pdt_no
//left join wms.bs_wh h on w.wh_code=h.wh_code where (w.part_no like '$part_no%') ";
//         $this->load($params);
//         if(!$this->validate()){
//             return $sql;
//         }
//         if(!empty($this->part_no)){
//             $sql=$sql.'  and (w.part_no like  \''.$this->part_no.'%\')';
//             $count=$count.' and (w.part_no like  \''.$this->part_no.'%\')';
//         }
//         if(!empty($this->pdt_name)){
//             $sql=$sql. '  and  (p.pdt_name lik  \''.$this->pdt_name.'%\')';
//             $count=$count. ' and  (p.pdt_name like  \''.$this->pdt_name.'%\')';
//         }
//         $totalCount=\Yii::$app->db->createCommand($count,null)->queryScalar();
//         $dataProvider = new SqlDataProvider([
//             'sql' => $sql,
//             'totalCount'=>$totalCount,
//             'pagination' => [
//                 'pageSize' => 10,
//             ]
//         ]);
//        // $query->joinWith('bsProduct');
////         $query->andFilterWhere(['like', 'part_no',$params['partno']]);
////         $query->andFilterWhere(['like', 'part_no',$params['part_no']])
//
////         $pdtname=null;
////         if(!empty($params['part_name'])){
////             $info=BsProduct::getPdtname($params['part_name'],$params['partno']);
////             $pdtname=$info;
////         }
////         $query->andFilterWhere(['like', 'part_no',empty($params['partno'])?'':$params['partno']])
////            ->andFilterWhere(['like', 'part_no',$this->part_no]);
////             if(!empty($pdtname))
////             {
////                 foreach ($pdtname as $item)
////                 {
////                     $query->andFilterWhere(['=','part_no', $item["pdt_no"]]);
////                 }
////             }
////             $commandQuery = clone $query;
////             echo $commandQuery->createCommand()->getRawSql();
////             exit;
//         return $dataProvider;
//     }
 }