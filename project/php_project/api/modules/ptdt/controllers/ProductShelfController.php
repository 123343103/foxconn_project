<?php
/**
 * Created by PhpStorm.
 * User: F3860942
 * Date: 2017/9/21
 * Time: 上午 09:33
 */

namespace app\modules\ptdt\controllers;

use app\modules\ptdt\models\BsProduct;
use app\controllers\BaseActiveController;
use Yii;

/*商品上架审核详情*/

class ProductShelfController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\BsPartno';

    //获取商品信息(商品編輯)
    public function actionGetPdtInfo($vco_busid)//$vco_busid:送审单号
    {
        $result = Yii::$app->db->createCommand("select pdt.l_product.pdt_name,
       pdt.l_product.pdt_title,
       pdt.l_product.pdt_keyword,
       pdt.l_product.pdt_label,
       pdt.bs_brand.BRAND_NAME_CN,
       pdt.l_product.pdt_attribute,
       pdt.l_product.pdt_form,
       pdt.l_product.unit,
       t1.bsp_svalue bsp_svalue1,
       t2.bsp_svalue bsp_svalue2,
       t3.bsp_svalue bsp_svalue3,
CONCAT_WS('->',cat_3.catg_name,
    cat_2.catg_name,
    cat_1.catg_name) cat_three_level from pdt.l_product
        left join pdt.bs_category cat_1 on cat_1.catg_id=l_product.category_id
        left join pdt.bs_category cat_2 on cat_2.catg_id=cat_1.p_catg_id
        left join pdt.bs_category cat_3 on cat_3.catg_id=cat_2.p_catg_id
        left join pdt.bs_brand
        on pdt.l_product.brand_id=pdt.bs_brand.BRAND_ID
        left join erp.bs_pubdata t1
         on pdt.l_product.unit=t1.bsp_id
        left join erp.bs_pubdata t2
         on pdt.l_product.pdt_attribute=t2.bsp_id
        left join erp.bs_pubdata t3
         on pdt.l_product.pdt_form=t3.bsp_id
 where l_pdt_pkid in(select l_pdt_pkid from pdt.l_partno where l_prt_pkid=:id )", [":id" => $vco_busid]
        )->queryOne();

        return $result;
    }

    //获取商品信息(商品上下架)
    public function actionGetPdtInfoShelves($vco_busid)//$vco_busid:送审单号
    {
        $result = Yii::$app->db->createCommand("select pdt.bs_product.pdt_name,
       pdt.bs_product.pdt_title,
       pdt.bs_product.pdt_keyword,
       pdt.bs_product.pdt_label,
       pdt.bs_brand.BRAND_NAME_CN,
       pdt.bs_product.pdt_attribute,
       pdt.bs_product.pdt_form,
       pdt.bs_product.unit,
       t1.bsp_svalue bsp_svalue1,
       t2.bsp_svalue bsp_svalue2,
       t3.bsp_svalue bsp_svalue3,
CONCAT_WS('->',cat_3.catg_name,
    cat_2.catg_name,
    cat_1.catg_name) cat_three_level from pdt.bs_product
        left join pdt.bs_category cat_1 on cat_1.catg_id=bs_product.catg_id
        left join pdt.bs_category cat_2 on cat_2.catg_id=cat_1.p_catg_id
        left join pdt.bs_category cat_3 on cat_3.catg_id=cat_2.p_catg_id
        left join pdt.bs_brand
        on pdt.bs_product.brand_id=pdt.bs_brand.BRAND_ID
        left join erp.bs_pubdata t1
         on pdt.bs_product.unit=t1.bsp_id
        left join erp.bs_pubdata t2
         on pdt.bs_product.pdt_attribute=t2.bsp_id
        left join erp.bs_pubdata t3
         on pdt.bs_product.pdt_form=t3.bsp_id
 where pdt_pkid in (select pdt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id))", [":id" => $vco_busid]
        )->queryOne();

        return $result;
    }

    //查询商品关联商品(商品編輯)
    public function actionGetRelPdt($vco_busid)
    {
        $result = Yii::$app->db->createCommand("
        select c.pdt_name FROM pdt.l_product c where l_pdt_pkid  in (select a.r_pdt_PKID from pdt.r_pdt_pdt a
where a.pdt_PKID in(select b.l_pdt_pkid from pdt.l_partno b where b.l_prt_pkid=:id ))", [":id" => $vco_busid])->queryall();
        return $result;
    }

    //查询商品关联商品(商品上下架)
    public function actionGetRelPdtShelves($vco_busid)
    {
        $result = Yii::$app->db->createCommand("
        select c.pdt_name FROM pdt.bs_product c where pdt_pkid  in (select a.r_pdt_PKID from pdt.r_pdt_pdt a
where a.pdt_PKID in(select pdt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id)))", [":id" => $vco_busid])->queryall();
        return $result;
    }

    //查询商品图片(商品編輯)
    public function actionGetPdtImg($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.fl_new from pdt.l_images a where a.l_pdt_pkid in (select l_pdt_pkid from pdt.l_partno where l_prt_pkid=:id and a.img_type=0 order by a.orderby) 
and a.img_type=0 ORDER BY a.orderby ", [":id" => $vco_busid])->queryAll();

        return $result;
    }

    //查询商品图片(商品上下架)
    public function actionGetPdtImgShelves($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.fl_new from pdt.bs_images a where a.pdt_pkid in (select pdt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id)) and a.img_type=0 order by a.orderby ", [":id" => $vco_busid])->queryAll();

        return $result;
    }

    //查询单笔料号信息(商品編輯)
    public function actionGetPartNoInfo($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.*,truncate(a.min_order,2) min_order,truncate(b.pdt_qty,2) pdt_qty,GROUP_CONCAT(DISTINCT(c.supplier_name)) spp_sname from pdt.l_partno a
left join pdt.l_pack b
on a.l_prt_pkid=b.l_prt_pkid
left join (select * from pdt.pdtprice_pas f where f.effective_date<NOW() and f.expiration_date>NOW() and f.supplier_name is not NULL) c
on a.part_no=c.part_no
where a.l_prt_pkid=:id
and b.pck_type=2
and b.yn is NULL 
group by a.l_prt_pkid", [":id" => $vco_busid])->queryOne();
        return $result;
    }

    //查询单笔料号信息(商品上下架)
    public function actionGetPartNoInfoShelves($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.*,truncate(a.min_order,2) min_order, truncate(b.pdt_qty,2) pdt_qty,GROUP_CONCAT(DISTINCT(c.supplier_name)) spp_sname,d.rs_mark from pdt.bs_partno a
left join pdt.bs_pack b
on a.prt_pkid=b.prt_pkid
left join (select * from pdt.pdtprice_pas f where f.effective_date<NOW() and f.expiration_date>NOW() and f.supplier_name is not NULL) c
on a.part_no=c.part_no
left join pdt.off_reason d
on a.rs_id=d.rs_id
where  a.prt_pkid in(select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id))
and b.pck_type=2
GROUP BY a.prt_pkid", [":id" => $vco_busid])->queryOne();
        return $result;
    }

    //查询料号自提仓库(編輯商品)
    public function actionGetPrtWm($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.wh_name,f.staff_name,f.staff_mobile,CONCAT(d.district_name,c.district_name,b.district_name,a.wh_addr)wh_address from wms.bs_wh a
left join erp.bs_district b
on a.DISTRICT_ID=b.district_id
left join erp.bs_district c
on b.district_pid=c.district_id
left join erp.bs_district d
on c.district_pid=d.district_id
left join wms.wh_adm e
on a.wh_code=e.wh_code
left join erp.hr_staff f
on e.emp_no=f.staff_code
where wh_id in (select wh_id from pdt.l_prt_wh where l_prt_pkid=:id)", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //查询料号自提仓库(商品上下架)
    public function actionGetPrtWmShelves($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.wh_name,f.staff_name,f.staff_mobile,CONCAT(d.district_name,c.district_name,b.district_name,a.wh_addr)wh_address from wms.bs_wh a
left join erp.bs_district b
on a.DISTRICT_ID=b.district_id
left join erp.bs_district c
on b.district_pid=c.district_id
left join erp.bs_district d
on c.district_pid=d.district_id
left join wms.wh_adm e
on a.wh_code=e.wh_code
left join erp.hr_staff f
on e.emp_no=f.staff_code
where wh_id in (select wh_id from pdt.r_prt_wh where prt_pkid in(select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id)))", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //查询料号价格信息(編輯商品)
    public function actionGetPrtPrice($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select truncate(a.minqty,2) minqty,truncate(a.maxqty,2) maxqty,truncate(a.price,2) price,a.currency from pdt.bs_price a where a.prt_pkid=:id order BY(a.item)", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //查询料号价格信息(商品上下架)
    public function actionGetPrtPriceShelves($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select truncate(a.minqty,2) minqty,IF(a.maxqty,truncate(a.maxqty,2),'') maxqty,truncate(a.price,5) price,a.currency,b.bsp_svalue from pdt.bs_price a 
left join erp.bs_pubdata b
on a.currency=b.bsp_id
where a.prt_pkid IN (select prt_pkid from pdt.bs_partno 
where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id)) order BY(a.item)", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //查询料号备货区间(編輯商品)
    public function actionGetPrtStock($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select truncate(a.min_qty,2) min_qty,truncate(a.max_qty,2) max_qty,a.stock_time,a.stock_Unit from pdt.l_stock a where a.l_prt_pkid=:id  and a.yn is null order BY(a.item) ", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //查询料号备货区间(商品上下架)
    public function actionGetPrtStockShelves($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select truncate(a.min_qty,2) min_qty,truncate(a.max_qty,2) max_qty,a.stock_time,a.stock_Unit from pdt.bs_stock a where a.prt_pkid in(select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id)) order BY(a.item) ", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //查询料号发货地址(編輯商品)
    public function actionGetPrtShip($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.country_name,a.province_name,a.city_name from pdt.l_ship a where a.l_prt_pkid=:id and a.yn is null", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //查询料号发货地址(商品上下架)
    public function actionGetPrtShipShelves($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.country_name,a.province_name,a.city_name from pdt.bs_ship a where a.prt_pkid in (select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id))", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //料号包装信息(編輯商品)
    public function actionGetPrtPack($vco_busid)
    {
        $result["pck_type1"] = Yii::$app->db->createCommand("select a.*,truncate(a.pdt_weight,2) pdt_weight,truncate(a.pdt_qty,2) pdt_qty, b.yn_pa_fjj,b.yn_pallet from pdt.l_pack a 
join pdt.l_partno b
on a.l_prt_pkid=b.l_prt_pkid
where a.l_prt_pkid=:id and a.pck_type=1 and a.yn is null ", [":id" => $vco_busid])->queryOne();//基本信息
        $result["pck_type2"] = Yii::$app->db->createCommand("select a.*,truncate(a.pdt_weight,2) pdt_weight,truncate(a.pdt_qty,2) pdt_qty,truncate(a.net_weight,2) net_weight from pdt.l_pack a where  a.l_prt_pkid=:id and a.pck_type=2 and a.yn is null", [":id" => $vco_busid])->queryOne();//销售包装
        $result["pck_type3"] = Yii::$app->db->createCommand("select a.*,truncate(a.pdt_weight,2) pdt_weight,truncate(a.pdt_qty,2) pdt_qty from pdt.l_pack a where  a.l_prt_pkid=:id and a.pck_type=3 and a.yn is null", [":id" => $vco_busid])->queryOne();//外包装
        $result["pck_type4"] = Yii::$app->db->createCommand("select a.*,truncate(a.pdt_weight,2) pdt_weight,truncate(a.pdt_qty,2) pdt_qty from pdt.l_pack a where  a.l_prt_pkid=:id and a.pck_type=4 and a.yn is null", [":id" => $vco_busid])->queryOne();//散货包装
        $result["pck_type5"] = Yii::$app->db->createCommand("select a.*,truncate(a.pdt_weight,2) pdt_weight,truncate(a.pdt_qty,2) pdt_qty from pdt.l_pack a where  a.l_prt_pkid=:id and a.pck_type=5 and a.yn is null", [":id" => $vco_busid])->queryOne();//栈板包装
        return $result;
    }

    //料号包装信息(商品上下架)
    public function actionGetPrtPackShelves($vco_busid)
    {
        $result["pck_type1"] = Yii::$app->db->createCommand("select a.*,truncate(a.pdt_weight,2) pdt_weight,truncate(a.pdt_qty,2) pdt_qty, b.yn_pa_fjj,b.yn_pallet from pdt.bs_pack a 
join pdt.bs_partno b
on a.prt_pkid=b.prt_pkid
where a.prt_pkid in (select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id)) 
and pck_type=1", [":id" => $vco_busid])->queryOne();//基本信息
        $result["pck_type2"] = Yii::$app->db->createCommand("select a.*,truncate(a.pdt_weight,2) pdt_weight,truncate(a.pdt_qty,2) pdt_qty,truncate(a.net_weight,2) net_weight  from pdt.bs_pack a where  a.prt_pkid in (select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id)) and a.pck_type=2", [":id" => $vco_busid])->queryOne();//销售包装
        $result["pck_type3"] = Yii::$app->db->createCommand("select a.*,truncate(a.pdt_weight,2) pdt_weight,truncate(a.pdt_qty,2) pdt_qty from pdt.bs_pack a where  a.prt_pkid in (select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id)) and a.pck_type=3", [":id" => $vco_busid])->queryOne();//外包装
        $result["pck_type4"] = Yii::$app->db->createCommand("select a.*,truncate(a.pdt_weight,2) pdt_weight,truncate(a.pdt_qty,2) pdt_qty from pdt.bs_pack a where  a.prt_pkid in (select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id)) and a.pck_type=4", [":id" => $vco_busid])->queryOne();//散货包装
        $result["pck_type5"] = Yii::$app->db->createCommand("select a.*,truncate(a.pdt_weight,2) pdt_weight,truncate(a.pdt_qty,2) pdt_qty from pdt.bs_pack a where  a.prt_pkid in (select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id)) and a.pck_type=5", [":id" => $vco_busid])->queryOne();//栈板包装
        return $result;
    }

    //料号运费信息(編輯商品)
    public function actionGetPrtDeliv($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.country_name,a.province_name,a.city_name from pdt.l_deliv a where l_prt_pkid=:id and a.yn is null", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //料号运费信息(商品上下架)
    public function actionGetPrtDelivShelves($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.country_name,a.province_name,a.city_name from pdt.bs_deliv a where prt_pkid in (select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id))", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //设备料号相关信息(編輯商品)
    public function actionGetPrtMachine($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select * from pdt.l_machine where l_prt_pkid=:id", [":id" => $vco_busid])->queryOne();
        return $result;
    }

    //设备料号相关信息(商品上下架)
    public function actionGetPrtMachineShelves($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select * from pdt.bs_machine where prt_pkid in (select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id))", [":id" => $vco_busid])->queryOne();
        return $result;
    }

    //设备料号延保方案(編輯商品)
    public function actionGetPrtWarr($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.wrr_prd,a.wrr_fee,b.bsp_svalue from pdt.l_warr a 
left join erp.bs_pubdata b
on a.cry=b.bsp_id
where l_prt_pkid=:id order BY(a.item)", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //设备料号延保方案(商品上下架)
    public function actionGetPrtWarrShelves($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.wrr_prd,a.wrr_fee,b.bsp_svalue from pdt.bs_warr a 
left join erp.bs_pubdata b
on a.cry=b.bsp_id
where a.prt_pkid in (select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id)) order BY(a.item)", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //商品详情信息(編輯商品)
    public function actionGetPdtDetails($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.details,MAX(a.l_dt_pkid) from pdt.l_details a where a.l_pdt_pkid in (select l_pdt_pkid from pdt.l_partno where l_prt_pkid=:id)
and a.l_prt_pkid is NULL", [":id" => $vco_busid])->queryOne();
        return $result;
    }

    //商品详情信息(商品上下架)
    public function actionGetPdtDetailsShelves($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.details  from pdt.bs_details a where a.pdt_pkid in (select pdt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id))
and a.prt_pkid is NULL", [":id" => $vco_busid])->queryOne();
        return $result;
    }

    //查询是否为设备料号(編輯商品)
    public function actionIsYnMachine($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select b.yn_machine from pdt.bs_product a
LEFT JOIN pdt.bs_category b
on a.catg_id=b.catg_id
where a.pdt_pkid in (select c.pdt_pkid from pdt.bs_partno c where c.part_no in (select b.part_no from pdt.l_partno b where b.l_prt_pkid=:id))", [":id" => $vco_busid])->queryOne();
        return $result;
    }

    //查询是否为设备料号(商品上下架)
    public function actionIsYnMachineShelves($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select b.yn_machine from pdt.bs_product a
LEFT JOIN pdt.bs_category b
on a.catg_id=b.catg_id
where a.pdt_pkid in (select pdt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id))", [":id" => $vco_busid])->queryOne();
        return $result;
    }

    //查询设备料号詳情(編輯商品)
    public function actionGetMachineDetails($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.details,MAX(a.l_dt_pkid) from pdt.l_details a where a.l_pdt_pkid in (select l_pdt_pkid from pdt.l_partno where l_prt_pkid=:id)
and a.l_prt_pkid=:id", [':id' => $vco_busid])->queryOne();
        return $result;
    }

    //查询设备料号詳情(商品上下架)
    public function actionGetMachineDetailsShelves($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.details from pdt.bs_details a where a.pdt_pkid in (select pdt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id))
and a.prt_pkid in (select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id))", [':id' => $vco_busid])->queryOne();
        return $result;
    }

    //查询料号的类别属性(編輯商品)
    public function actionGetPrtAttr($prt_pkid)
    {
        $result = Yii::$app->db->createCommand("select c.attr_name,c.attr_type,c.catg_attr_id from pdt.bs_product a
LEFT JOIN pdt.bs_partno b
on a.pdt_pkid=b.pdt_PKID
left join pdt.bs_catg_attr c
on a.catg_id=c.catg_id
where b.prt_pkid=:id", [":id" => $prt_pkid])->queryAll();
        return $result;
    }

    //查询料号的类别属性(商品上下架)
    public function actionGetPrtAttrShelves($prt_pkid)
    {
        $result = Yii::$app->db->createCommand("select c.attr_name,c.attr_type,c.catg_attr_id from pdt.bs_product a
LEFT JOIN pdt.bs_partno b
on a.pdt_pkid=b.pdt_PKID
left join pdt.bs_catg_attr c
on a.catg_id=c.catg_id
where b.prt_pkid=:id", [":id" => $prt_pkid])->queryAll();
        return $result;
    }

    //查询料号的属性值
    public function actionGetPrtAttrVal($catgattrid)
    {
        $result = Yii::$app->db->createCommand("select a.attr_value,a.attr_val_id from pdt.r_attr_value a where a.catg_attr_id=:catgattrid", [":catgattrid" => $catgattrid])->queryAll();
        return $result;
    }

    //查询料号对应的规格参数
    public function actionGetPrtAttrName($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select a.attr_name,a.catg_attr_id ,'' attr_value from pdt.r_prt_attr a where  a.prt_pkid=:id  group by a.catg_attr_id", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //查询料号对应的参数值
    public function actionGetPrtValue($vco_busid, $catgattrid)
    {
        $result = Yii::$app->db->createCommand("select a.attr_value from pdt.r_prt_attr a where a.catg_attr_id=:catgid and a.prt_pkid=:prtid", [":catgid" => $catgattrid, ":prtid" => $vco_busid])->queryAll();
        return $result;
    }


    //查詢料號的規格參數(編輯商品)
    public function actionNewGetPrtAttr($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select d.attr_name,GROUP_CONCAT(d.attr_value) attr_value from (select a.attr_name,b.attr_value,a.catg_attr_id from  pdt.l_prt_attr a
left join pdt.r_attr_value b
on a.attr_val_id=b.attr_val_id
left join pdt.bs_catg_attr c
on a.catg_attr_id=c.catg_attr_id
where c.attr_type!=3
and a.prt_pkid in (select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id))
union 
select a.attr_name,a.attr_value,a.catg_attr_id from pdt.l_prt_attr a
left join pdt.bs_catg_attr b
on a.catg_attr_id=b.catg_attr_id
where a.prt_pkid in (select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id))
and b.attr_type=3) d
group by d.attr_name order by d.catg_attr_id", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //查詢料號的規格參數(商品上下架)
    public function actionNewGetPrtAttrShelves($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select d.attr_name,GROUP_CONCAT(d.attr_value) attr_value from (select a.attr_name,b.attr_value,a.catg_attr_id from  pdt.r_prt_attr a
left join pdt.r_attr_value b
on a.attr_val_id=b.attr_val_id
left join pdt.bs_catg_attr c
on a.catg_attr_id=c.catg_attr_id
where c.attr_type!=3
and a.prt_pkid in (select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id))
union 
select a.attr_name,a.attr_value,a.catg_attr_id from pdt.r_prt_attr a
left join pdt.bs_catg_attr b
on a.catg_attr_id=b.catg_attr_id
where a.prt_pkid in (select prt_pkid from pdt.bs_partno where part_no in(select part_no from pdt.l_partno where l_prt_pkid=:id))
and b.attr_type=3) d
group by d.attr_name order by d.catg_attr_id", [":id" => $vco_busid])->queryAll();
        return $result;
    }

    //查询料号下架原因及附件
    public function actionGetPrtRes($vco_busid)
    {
        $result = Yii::$app->db->createCommand("select c.rs_mark,b.other_reason,d.file_old,d.file_new from pdt.off_apply_dt a
left join pdt.off_apply b
on a.off_app_id=b.off_app_id
left join pdt.off_reason c
on b.rs_id=c.rs_id
left join pdt.off_file d
on a.off_app_id=d.off_app_id
where a.l_pdt_pkid=:id", [":id" => $vco_busid])->queryAll();
        return $result;
    }
}