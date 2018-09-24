<?php
namespace app\modules\warehouse\models\Search;

use app\classes\Trans;
use app\modules\common\models\BsProduct;
use app\modules\warehouse\models\BsInvWarnH;
use app\modules\warehouse\models\BsWhM;
use app\modules\warehouse\models\InvWarner;
use yii;
use yii\base\Model;
use app\modules\warehouse\models\BsInvWarn;
use app\modules\warehouse\models\show\SetInventoryWarningShow;
use yii\data\ActiveDataProvider;


class SetInventoryWarningSearch extends BsInvWarnH{
    public  $staff_code;
    public  $staff_name;
    public  $wh_id;
    public  $category_id;
    public  $part_no;
    public $so_type;
    public $LIW_PKID;
    public function rules()
    {
        return [
            [['staff_code'], 'safe'],
            [['staff_name'], 'safe'],/*,  'staff_email'*/
         /*   [[ 'staff_mobile'], 'safe'],*/
            [['inv_id'], 'safe'],
            [['OPP_DATE', 'remarks'], 'safe'],
            [['wh_id'], 'safe'],/*, 'wh_name'*/
           // [['wh_name'], 'safe'],
            [['category_id','so_type'], 'safe'],/*, 'category_sname'*/
            [['remarks'],'safe'],
            [['part_no'],'safe'],
            [['LIW_PKID'],'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params){
        $count="select COUNT(*) from(select COUNT(*)counts,ss.LIW_PKID FROM(select  b.biw_h_pkid, s.staff_code,s.staff_email,s.staff_mobile,s.staff_name,s.so_type,s.OPPER,s.OPP_DATE,b.part_no,s.LIW_PKID from (
SELECT 
        w.staff_code,
        h.staff_email,
        h.staff_mobile,
        h.staff_name,
w.so_type,
(select h.staff_name from erp.hr_staff h where h.staff_code=w.OPPER)OPPER,
w.OPP_DATE,
i.inv_warn_PKID,
w.LIW_PKID
FROM wms.inv_warner w
    LEFT JOIN erp.hr_staff h ON w.staff_code = h.staff_code
		LEFT JOIN wms.inv_warner_H i on i.LIW_PKID=w.LIW_PKID 
    )s LEFT JOIN wms.bs_inv_warn b on b.inv_warn_PKID=s.inv_warn_PKID)ss LEFT JOIN  wms.bs_inv_warn_h bh on ss.biw_h_pkid=bh.biw_h_pkid where 1=1  ";
        $sql='select ss.LIW_PKID, bh.wh_id,ss.staff_code,ss.staff_email,ss.staff_mobile,ss.staff_name,ss.so_type,ss.OPPER,date_format(ss.OPP_DATE,\'%Y-%m-%d\')OPP_DATE,ss.part_no FROM(select b.biw_h_pkid, s.staff_code,s.staff_email,s.staff_mobile,s.staff_name,s.so_type,s.OPPER,s.OPP_DATE,b.part_no,s.LIW_PKID from (
SELECT 
        w.staff_code,
        h.staff_email,
        h.staff_mobile,
        h.staff_name,
w.so_type,
(select h.staff_name from erp.hr_staff h where h.staff_code=w.OPPER)OPPER,
w.OPP_DATE,
i.inv_warn_PKID,
w.LIW_PKID
FROM wms.inv_warner w
    LEFT JOIN erp.hr_staff h ON w.staff_code = h.staff_code
		LEFT JOIN wms.inv_warner_H i on i.LIW_PKID=w.LIW_PKID 
    )s LEFT JOIN wms.bs_inv_warn b on b.inv_warn_PKID=s.inv_warn_PKID)ss LEFT JOIN  wms.bs_inv_warn_h bh on ss.biw_h_pkid=bh.biw_h_pkid where 1=1 ';
        $this->load($params);
        if (!$this->validate()) {
            return $sql;
        }
        if(!empty($this->wh_id)){
            $sql=$sql. " and bh.wh_id='$this->wh_id'";
            $count=$count." and bh.wh_id='$this->wh_id'";
        }
        if(!empty($this->staff_code)){
            $sql=$sql. ' and ((ss.staff_code like \'%'.$this->staff_code.'%\') or (ss.staff_name like  \'%'.$this->staff_code.'%\'))';
            $count=$count. ' and ((ss.staff_code like \'%'.$this->staff_code.'%\') or (ss.staff_name like  \'%'.$this->staff_code.'%\'))';
        }
        if(!empty($this->category_id)){
            $sql=$sql.' and ss.part_no like \''.$this->category_id.'%\'';
            $count=$count.'  and ss.part_no like \''.$this->category_id.'%\'';
        }
        if(!empty($this->part_no)){
            $sql=$sql.' and ss.part_no like \'%'.$this->part_no.'%\'';
            $count=$count.' and ss.part_no like \'%'.$this->part_no.'%\'';
        }
        if(!empty($this->so_type)){
            $sql=$sql."  and ss.so_type='$this->so_type'";
            $count=$count."  and ss.so_type='$this->so_type'";
        }
        $sql=$sql." GROUP BY ss.LIW_PKID";
        $count=$count." GROUP BY ss.LIW_PKID)sss";
        $totalCount=\Yii::$app->db->createCommand($count,null)->queryScalar();
        $dataProvider = new yii\data\SqlDataProvider([
            'sql' => $sql,
            'totalCount'=>$totalCount,
            'pagination' => [
                'pageSize' => $params['rows'],
            ]
        ]);
        return $dataProvider;
    }

    public function searchproductInfo($params){
        $this->load($params);
        //$code=$params['code'];
        $count="select count(*) from wms.inv_warner_h w where w.LIW_PKID='$this->LIW_PKID' ";
        $sql="select bh.inv_id, bh.wh_id,(select wh_name from wms.bs_wh where wh_id=bh.wh_id)wh_name,bt.invt_num,b.BRAND_NAME_CN,c.category_sname,s.pdt_model,s.part_no,s.down_nums,s.up_nums from (
           select wa.biw_h_pkid, wa.part_no,wa.down_nums,wa.up_nums,wa.inv_warn_PKID,
(select p.pdt_model from erp.bs_product p where p.pdt_no=wa.part_no)pdt_model,
         (select p.brand_id from erp.bs_product p where p.pdt_no=wa.part_no)brand_id,
        (select p.bs_category_id from erp.bs_product p WHERE p.pdt_no=wa.part_no)bs_category_id,
(select p.pdt_name from erp.bs_product p where p.pdt_no=wa.part_no)pdt_name
from wms.inv_warner_h h left join wms.bs_inv_warn wa on h.inv_warn_PKID=wa.inv_warn_PKID
 where h.LIW_PKID='$this->LIW_PKID')s left join wms.bs_inv_warn_h bh on bh.biw_h_pkid=s.biw_h_pkid
left join wms.bs_invt bt on bt.part_no=s.part_no and bt.wh_id=bh.wh_id
LEFT JOIN erp.bs_brand b ON b.BRAND_ID = s.brand_id
LEFT JOIN erp.bs_category c ON c.category_id = s.bs_category_id ";
        $totalCount=\Yii::$app->db->createCommand($count,null)->queryScalar();
        $dataProvider = new yii\data\SqlDataProvider([
            'sql' => $sql,
            'totalCount'=>$totalCount,
            'pagination' => [
                "pageSize"=>5
            ]
        ]);
        return $dataProvider;
    }
    //导出
    public function searchApply($params){
        $this->load($params);
        $sql="select t.invt_num,sss.staff_code,sss.OPP_DATE,sss.so_type,sss.OPPER,sss.staff_email,sss.staff_mobile,sss.staff_name,sss.BRAND_NAME_CN,
sss.category_sname,sss.pdt_model,sss.part_no,sss.pdt_name,sss.down_nums,sss.up_nums,sss.wh_id,sss.wh_name
 from (select  ss.staff_code,ss.OPP_DATE,ss.so_type,ss.OPPER,ss.staff_email,ss.staff_mobile,ss.staff_name,b.BRAND_NAME_CN,
c.category_sname,ss.pdt_model,ss.part_no,ss.pdt_name,ss.down_nums,ss.up_nums,bh.wh_id,
(select b.wh_name from wms.bs_wh b where b.wh_id=bh.wh_id)wh_name from(select n.biw_h_pkid,n.part_no,(select p.pdt_name from erp.bs_product p where p.pdt_no=n.part_no)pdt_name,s.staff_code,s.staff_email,
s.staff_mobile,s.staff_name,s.so_type,s.OPP_DATE,s.OPPER,n.down_nums,n.up_nums,
(select p.pdt_model from erp.bs_product p where p.pdt_no=n.part_no)pdt_model,  
        (select p.brand_id from erp.bs_product p where p.pdt_no=n.part_no)brand_id,
        (select p.bs_category_id from  erp.bs_product p WHERE p.pdt_no=n.part_no)bs_category_id
from (select h.inv_warn_PKID,w.staff_code,hr.staff_email,
        hr.staff_mobile,
        hr.staff_name,w.so_type,w.OPP_DATE,
(select h.staff_name from erp.hr_staff h where h.staff_code=w.OPPER)OPPER
from wms.inv_warner w LEFT JOIN wms.inv_warner_h h ON w.LIW_PKID=H.LIW_PKID
LEFT JOIN erp.hr_staff hr ON w.staff_code = hr.staff_code)s left join wms.bs_inv_warn n on n.inv_warn_PKID=s.inv_warn_PKID)ss 
left join wms.bs_inv_warn_h bh on bh.biw_h_pkid=ss.biw_h_pkid
LEFT JOIN erp.bs_brand b ON b.BRAND_ID = ss.brand_id
LEFT JOIN erp.bs_category c ON c.category_id = ss.bs_category_id)sss
left join wms.bs_invt t on t.wh_id=sss.wh_id and t.part_no=sss.part_no 
where 1=1 ";
//        if (!$this->validate()) {
//            return $sql;
//        }
        if(!empty($this->wh_id)){
            $sql=$sql. " and sss.wh_id='$this->wh_id'";
        }
        if(!empty($this->staff_code)){
            $sql=$sql. ' and ((sss.staff_code like \'%'.$this->staff_code.'%\') or (sss.staff_name like  \'%'.$this->staff_code.'%\'))';
        }
        if(!empty($this->category_id)){
            $sql=$sql.' and sss.part_no like \''.$this->category_id.'%\'';
        }
        if(!empty($this->part_no)){
            $sql=$sql.' and sss.part_no like \'%'.$this->part_no.'%\'';
        }
        if(!empty($this->so_type)) {
            $sql = $sql . "  and sss.so_type='$this->so_type'";
        }
        $dataProvider=new yii\data\SqlDataProvider([
            'sql' => $sql,
            'pagination'=>[
                'pageSize'=>empty($params['rows'])?'':$params['rows']
            ],
        ]);
        return $dataProvider;
    }

}