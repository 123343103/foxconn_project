<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/29
 * Time: 上午 09:31
 */
namespace app\modules\warehouse\models\search;
use app\modules\warehouse\models\BsInvt;
use yii\data\SqlDataProvider;

class BsInvtSearch extends BsInvt{

    //库存查询
    public static function StockQuery($params=""){
        $bindParams=[];

        $fields="bs_wh.wh_id,
        bs_wh.wh_name,
        bs_wh.wh_code,
        case bs_wh.wh_attr 
          when '100879' then '外租' 
          when '100880' then '自有' 
        end as wh_attr,
        bs_pubdata.bsp_svalue wh_type,
        case bs_wh.wh_lev
          when 100896 then '其他'
          when 100893 then '一级'
          when 100894 then '二级'
          when 100895 then '三级'
          end as wh_lev,
        bs_material.part_no,
        bs_material.pdt_name,
        bs_material.brand,
        cc.catg_name,
        bs_material.tp_spec,
        bs_sit_invt.st_code,
        bs_sit_invt.batch_no batch,
        bs_material.unit";
        $group="";
        $where=' where bs_material.part_no is not null ';
        if(!empty($params['catg_id'])){
            $where.=' and cc.catg_id like :catg_id';
            $bindParams[':catg_id']="%{$params['catg_id']}%";
        }
        if(!empty($params['part_no'])){
            $where.=' and bs_material.part_no like :part_no';
            $bindParams[':part_no']="%{$params['part_no']}%";
        }
        if(!empty($params['pdt_name'])){
            $where.=' and bs_material.pdt_name like :pdt_name';
            $bindParams[':pdt_name']="%{$params['pdt_name']}%";
        }
        if(!empty($params['brand'])){
            $where.=' and bs_material.brand like :brand';
            $bindParams[':brand']="%{$params['brand']}%";
        }
        if(!empty($params['tp_spec'])){
            $where.=' and bs_material.tp_spec like :tp_spec';
            $bindParams[':tp_spec']="%{$params['tp_spec']}%";
        }
        if(!empty($params['wh_name'])){
            $where.=' and bs_sit_invt.wh_name=:wh_name';
            $bindParams[':wh_name']=$params['wh_name'];
        }
        if(!empty($params['wh_code'])){
            $where.=' and bs_sit_invt.wh_code=:wh_code';
            $bindParams[':wh_code']=$params['wh_code'];
        }
        if(!empty($params['wh_attr'])){
            $where.=' and bs_wh.wh_attr=:wh_attr';
            $bindParams[':wh_attr']=$params['wh_attr'];
        }
        if(!empty($params['wh_type'])){
            $where.=' and bs_wh.wh_type=:wh_type';
            $bindParams[':wh_type']=$params['wh_type'];
        }

        if(!empty($params['count_by_wh'])){
            $fields.=",round(sum(bs_sit_invt.invt_num),2) invt_num ";
            $group.=' group by bs_sit_invt.wh_code ';
        }

        if(empty($params['count_by_wh'])){
            $fields.=",round(bs_sit_invt.invt_num,2) invt_num ";
        }

        if(empty($params['show_zero']) || $params['show_zero']!=1){
            $where.=' and invt_num>:invt_num';
            $bindParams[':invt_num']=0;
        }
        if(!empty($params['sssss'])){
//            $_sql="SELECT t.batch_no from wms.bs_sit_invt t WHERE t.batch_no="."'".$params['sssss']."'";
//            $ret=\Yii::$app->db->createCommand($_sql)->queryScalar();
            $where.=' and bs_sit_invt.batch_no like :sssss';
            $bindParams[':sssss']=$params['sssss'];
        }

        $sql="select {$fields} from wms.bs_sit_invt
        LEFT JOIN wms.bs_wh ON bs_wh.wh_code=bs_sit_invt.wh_code
        left join pdt.bs_material on wms.bs_sit_invt.part_no=pdt.bs_material.part_no
        left join pdt.bs_category a on a.catg_no=LEFT (bs_material.category_no,6)
        left join pdt.bs_category b on a.p_catg_id=b.catg_id
        left join pdt.bs_category cc on b.p_catg_id=cc.catg_id
        LEFT JOIN erp.bs_pubdata ON bs_pubdata.bsp_id=bs_wh.wh_type {$where} {$group}
        order by invt_id DESC ";


        $query=\Yii::$app->db->createCommand($sql,$bindParams);
        $dataProvider=new SqlDataProvider([
            "sql"=>$query->getRawSql(),
            "params"=>$bindParams,
            "totalCount"=>$query->query()->count(),
            "pagination"=>[
                "pageSize"=>isset($params["rows"])?$params["rows"]:10,
            ]
        ]);
        return $dataProvider;
    }
}