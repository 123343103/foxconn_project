<?php
/**
 * User: F1677929
 * Date: 2017/8/2
 */
namespace app\modules\warehouse\controllers;
use app\controllers\BaseActiveController;
use app\modules\warehouse\models\IcInvh;
use app\modules\warehouse\models\IcInvl;
use Yii;
use yii\data\SqlDataProvider;
use app\modules\common\models\BsCompany;
use app\classes\Trans;

/**
 * 出入库查询API控制器
 */
class InoutStockQueryController extends BaseActiveController
{
    public $modelClass='xx';

    //出入库查询列表
//    public function actionIndex()
//    {
//        $params=Yii::$app->request->queryParams;
//        //统计sql
//        $countSql="select count(*)
//                   from wms.ic_invl a
//                   left join wms.ic_invh b on b.invh_id = a.invh_id
//                   left join erp.bs_business_type c on c.business_type_id = b.inout_type
//                   left join erp.bs_business d on d.business_code = c.business_code
//                   left join wms.bs_wh e on e.wh_id = b.whs_id
//                   left join wms.bs_st f on f.st_id = a.lor_id
//                   left join pdt.bs_product g on g.pdt_id = a.pdt_id
//                   left join erp.category_attr h on h.CATEGORY_ATTR_ID = g.tp_spec
//                   left join erp.bs_category i on i.category_id = g.bs_category_id
//                   left join erp.hr_staff j on j.staff_id = b.create_by
//                   left join (select a1.invh_id,
//                                     e1.staff_name checkPerson,
//                                     c1.vcoc_datetime checkDate
//                              from wms.ic_invh a1
//                              left join erp.system_verifyrecord b1 on b1.but_code = a1.inout_type and b1.vco_busid = a1.invh_id
//                              left join erp.system_verifyrecord_child c1 on c1.vco_id = b1.vco_id
//                              left join erp.user d1 on d1.user_id = c1.ver_acc_id
//                              left join erp.hr_staff e1 on e1.staff_id = d1.staff_id
//                              order by c1.ver_scode desc
//                              limit 1
//                             ) k on k.invh_id = b.invh_id
//                   left join erp.hr_staff l on l.staff_id = b.invh_reperson
//                   left join erp.crm_bs_customer_info m on m.cust_id = b.cust_id
//                   left join erp.bs_pubdata n on n.bsp_id = m.cust_type
//                   left join erp.bs_business_type o on o.business_type_id = a.origin_type
//                   left join erp.bs_business p on p.business_code = o.business_code
//                   left join erp.bs_business_type q on q.business_type_id = a.p_origin_type
//                   left join erp.bs_business r on r.business_code = q.business_code
//                   where a.invl_status = 10
// --                  and b.comp_id in (";
//        //查询sql
//        $querySql="select b.invh_code,
//                          b.invh_date,
//                          c.business_type_desc,
//                          d.business_desc,
//                          case b.inout_flag when 'I' then '入库' when 'O' then '出库' else '未知' end inoutFlag,
//                          case e.wh_type when 0 then '其它仓' when 1 then '普通仓' when 2 then '恒温恒湿仓库' when 3 then '危险品仓库' when 4 then '贵重物品仓库' else '未知' end warehouseType,
//                          e.wh_name,
//                          f.st_code,
//                          a.batch_no,
//                          g.pdt_no,
//                          g.pdt_name,
//                          h.ATTR_NAME,
//                          g.pdt_attribute,
//                          i.category_sname,
//                          a.pack_type,
//                          a.in_quantity,
//                          a.real_quantity,
//                          j.staff_name createBy,
//                          case b.invh_status when 10 then '待提交' when 20 then '审核中' when 30 then '审核完成' when 60 then '驳回' when 70 then '单据作废' else '未知' end orderStatus,
//                          k.checkPerson,
//                          k.checkDate,
//                          b.invh_remark,
//                          b.invh_sendperson,
//                          l.staff_name receptionPerson,
//                          m.cust_filernumber,
//                          m.cust_sname,
//                          m.cust_shortname,
//                          n.bsp_svalue customerType,
//                          a.origin_id,
//                          p.form_name originOrder,
//                          o.business_value originType,
//                          a.p_bill_id,
//                          r.form_name prevOrder,
//                          q.business_value prevType
//                   from wms.ic_invl a
//                   left join wms.ic_invh b on b.invh_id = a.invh_id
//                   left join erp.bs_business_type c on c.business_type_id = b.inout_type
//                   left join erp.bs_business d on d.business_code = c.business_code
//                   left join wms.bs_wh e on e.wh_id = b.whs_id
//                   left join wms.bs_st f on f.st_id = a.lor_id
//                   left join pdt.bs_product g on g.pdt_id = a.pdt_id
//                   left join erp.category_attr h on h.CATEGORY_ATTR_ID = g.tp_spec
//                   left join erp.bs_category i on i.category_id = g.bs_category_id
//                   left join erp.hr_staff j on j.staff_id = b.create_by
//                   left join (select a1.invh_id,
//                                     e1.staff_name checkPerson,
//                                     c1.vcoc_datetime checkDate
//                              from wms.ic_invh a1
//                              left join erp.system_verifyrecord b1 on b1.but_code = a1.inout_type and b1.vco_busid = a1.invh_id
//                              left join erp.system_verifyrecord_child c1 on c1.vco_id = b1.vco_id
//                              left join erp.user d1 on d1.user_id = c1.ver_acc_id
//                              left join erp.hr_staff e1 on e1.staff_id = d1.staff_id
//                              order by c1.ver_scode desc
//                              limit 1
//                             ) k on k.invh_id = b.invh_id
//                   left join erp.hr_staff l on l.staff_id = b.invh_reperson
//                   left join erp.crm_bs_customer_info m on m.cust_id = b.cust_id
//                   left join erp.bs_pubdata n on n.bsp_id = m.cust_type
//                   left join erp.bs_business_type o on o.business_type_id = a.origin_type
//                   left join erp.bs_business p on p.business_code = o.business_code
//                   left join erp.bs_business_type q on q.business_type_id = a.p_origin_type
//                   left join erp.bs_business r on r.business_code = q.business_code
//                   where a.invl_status = 10
//--                   and b.comp_id in (";
//        //公司id
////        foreach(BsCompany::getIdsArr($params['companyId']) as $val){
////            $countSql.=$val.',';
////            $querySql.=$val.',';
////        }
////        $countSql=trim($countSql,',').')';
////        $querySql=trim($querySql,',').')';
//        //列表搜索
//        $queryParams=[];
//        $trans=new Trans();
//        //单据号
//        if(!empty($params['invh_code'])){
//            $params['invh_code']=str_replace(['%','_'],['\%','\_'],$params['invh_code']);
//            $queryParams[':invh_code']='%'.$params['invh_code'].'%';
//            $countSql.=" and b.invh_code like :invh_code";
//            $querySql.=" and b.invh_code like :invh_code";
//        }
//        //出入库标志
//        if(!empty($params['inout_flag'])){
//            $queryParams[':inout_flag']=$params['inout_flag'];
//            $countSql.=" and b.inout_flag = :inout_flag";
//            $querySql.=" and b.inout_flag = :inout_flag";
//        }
//        //单据日期
//        if(!empty($params['invh_date_start'])){
//            $queryParams[':invh_date_start']=$params['invh_date_start'];
//            $countSql.=" and b.invh_date >= :invh_date_start";
//            $querySql.=" and b.invh_date >= :invh_date_start";
//        }
//        //至
//        if(!empty($params['invh_date_end'])){
//            $queryParams[':invh_date_end']=date('Y-m-d',strtotime($params['invh_date_end'].'+1 day'));
//            $countSql.=" and b.invh_date < :invh_date_end";
//            $querySql.=" and b.invh_date < :invh_date_end";
//        }
//        //单据业务分类
//        if(!empty($params['business_code'])){
//            $queryParams[':business_code']=$params['business_code'];
//            $countSql.=" and d.business_code = :business_code";
//            $querySql.=" and d.business_code = :business_code";
//        }
//        //单据类型
//        if(!empty($params['inout_type'])){
//            $queryParams[':inout_type']=$params['inout_type'];
//            $countSql.=" and b.inout_type = :inout_type";
//            $querySql.=" and b.inout_type = :inout_type";
//        }
//        //仓库类别
//        if(isset($params['wh_type']) && $params['wh_type']!=''){
//            $queryParams[':wh_type']=$params['wh_type'];
//            $countSql.=" and e.wh_type = :wh_type";
//            $querySql.=" and e.wh_type = :wh_type";
//        }
//        //仓库名称
//        if(!empty($params['whs_id'])){
//            $queryParams[':whs_id']=$params['whs_id'];
//            $countSql.=" and b.whs_id = :whs_id";
//            $querySql.=" and b.whs_id = :whs_id";
//        }
//        //储位码
//        if(!empty($params['lor_id'])){
//            $queryParams[':lor_id']=$params['lor_id'];
//            $countSql.=" and a.lor_id = :lor_id";
//            $querySql.=" and a.lor_id = :lor_id";
//        }
//        //商品信息
//        if(!empty($params['product_info'])){
//            $params['product_info']=str_replace(['%','_'],['\%','\_'],$params['product_info']);
//            $queryParams[':product_info1']='%'.$params['product_info'].'%';
//            $queryParams[':product_info2']='%'.$trans->c2t($params['product_info']).'%';
//            $queryParams[':product_info3']='%'.$trans->t2c($params['product_info']).'%';
//            $countSql.=" and (g.pdt_no like :product_info1
//                              or g.pdt_name like :product_info1
//                              or g.pdt_name like :product_info2
//                              or g.pdt_name like :product_info3
//                             )";
//            $querySql.=" and (g.pdt_no like :product_info1
//                              or g.pdt_name like :product_info1
//                              or g.pdt_name like :product_info2
//                              or g.pdt_name like :product_info3
//                             )";
//        }
//        //商品规格
//        if(!empty($params['ATTR_NAME'])){
//            $params['ATTR_NAME']=str_replace(['%','_'],['\%','\_'],$params['ATTR_NAME']);
//            $queryParams[':ATTR_NAME']='%'.$params['ATTR_NAME'].'%';
//            $countSql.=" and h.ATTR_NAME like :ATTR_NAME";
//            $querySql.=" and h.ATTR_NAME like :ATTR_NAME";
//        }
//        //批次号
//        if(!empty($params['batch_no'])){
//            $params['batch_no']=str_replace(['%','_'],['\%','\_'],$params['batch_no']);
//            $queryParams[':batch_no']='%'.$params['batch_no'].'%';
//            $countSql.=" and a.batch_no like :batch_no";
//            $querySql.=" and a.batch_no like :batch_no";
//        }
//        //制单人
//        if(!empty($params['create_by'])){
//            $params['create_by']=str_replace(['%','_'],['\%','\_'],$params['create_by']);
//            $queryParams[':create_by1']='%'.$params['create_by'].'%';
//            $queryParams[':create_by2']='%'.$trans->c2t($params['create_by']).'%';
//            $queryParams[':create_by3']='%'.$trans->t2c($params['create_by']).'%';
//            $countSql.=" and (j.staff_name like :create_by1
//                              or j.staff_name like :create_by2
//                              or j.staff_name like :create_by3
//                             )";
//            $querySql.=" and (j.staff_name like :create_by1
//                              or j.staff_name like :create_by2
//                              or j.staff_name like :create_by3
//                             )";
//        }
//        //单据状态
//        if(!empty($params['invh_status'])){
//            $queryParams[':invh_status']=$params['invh_status'];
//            $countSql.=" and b.invh_status = :invh_status";
//            $querySql.=" and b.invh_status = :invh_status";
//        }
//        //统计条数
//        $totalCount=\Yii::$app->db->createCommand($countSql,$queryParams)->queryScalar();
//        //查询sql排序
//        $querySql.=" order by a.invl_id desc";
//        //SQL数据提供者
//        $provider=new SqlDataProvider([
//            'sql'=>$querySql,
//            'params'=>$queryParams,
//            'totalCount'=>$totalCount,
//            'pagination'=>[
//                'pageSize'=>empty($params['rows'])?false:$params['rows']
//            ]
//        ]);
//        $rows=$provider->getModels();
//        foreach($rows as &$row){
//            $row['originCode']='';
//            if(!empty($row['originOrder']) && !empty($row['origin_id'])){
//                $arr=explode(',',$row['originOrder']);
//                $sql="select {$arr[2]} from {$arr[0]} where {$arr[1]} = :id";
//                $arr1=Yii::$app->db->createCommand($sql,[':id'=>$row['origin_id']])->queryOne();
//                $row['originCode']=$arr1[$arr[2]];
//            }
//            $row['prevCode']='';
//            if(!empty($row['prevOrder']) && !empty($row['p_bill_id'])){
//                $arr=explode(',',$row['prevOrder']);
//                $sql="select {$arr[2]} from {$arr[0]} where {$arr[1]} = :id";
//                $arr1=Yii::$app->db->createCommand($sql,[':id'=>$row['origin_id']])->queryOne();
//                $row['prevCode']=$arr1[$arr[2]];
//            }
//            unset($row['origin_id']);
//            unset($row['originOrder']);
//            unset($row['p_bill_id']);
//            unset($row['prevOrder']);
//        }
//        return [
//            'rows'=>$rows,
//            'total'=>$provider->totalCount
//        ];
//    }
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        $querySql="SELECT
                            a.invh_id,
                            a.invh_code,
                            a.invh_date,
                            CASE a.inout_type WHEN 1 then '采购'  WHEN 2 THEN '调拨' when 3 THEN '移仓' WHEN 'wm01' THEN '新增' ELSE n.business_type_desc END business_type_desc,
                            a.bus_code business_desc,
                            CASE a.inout_flag WHEN 'I' THEN '入库' when'O' THEN '出库' ELSE a.inout_flag END inoutFlag,
                            a.whs_type warehouseType,
                            c.wh_name,
                            d.lor_id st_code,
                            d.batch_no,
                            d.part_no pdt_no,
                            ma.pdt_name,
                            ma.tp_spec ATTR_NAME,
                            f.pdt_attribute,
                            i.catg_name category_sname,
                            d.pack_type,
                            d.in_quantity,
                            d.real_quantity,
                            j.staff_name createBy,
                            a.invh_status orderStatus,
                            k.checkPerson,
                            k.checkDate,
                            a.invh_remark,
                            d.origin_id originCode,
                            d.origin_type originType,
                            a.invh_sendperson,
                            a.invh_reperson receptionPerson,
                            l.cust_filernumber,
                            l.cust_sname,
                            l.cust_shortname,
                            m.bsp_svalue customerType,
                            r.form_name prevOrder,
                            q.business_value prevType
                FROM
                    wms.ic_invh a
                LEFT JOIN erp.bs_business b ON b.business_code = a.bus_code
                LEFT JOIN wms.bs_wh c ON c.wh_id = a.whs_id
                LEFT JOIN wms.ic_invl d ON d.invh_id = a.invh_id
                LEFT JOIN pdt.bs_material ma ON d.part_no=ma.part_no
                LEFT JOIN wms.bs_st e ON e.st_id = d.lor_id
                LEFT JOIN pdt.bs_product f ON d.comp_pdtid = f.pdt_pkid
                LEFT JOIN pdt.bs_material g ON g.part_no = d.part_no
                LEFT JOIN pdt.bs_catg_attr h ON h.catg_attr_id = g.tp_spec
                LEFT JOIN pdt.bs_category i ON i.catg_id = f.catg_id
                LEFT JOIN erp.hr_staff j ON j.staff_id = a.create_by
                LEFT JOIN (
                    SELECT
                        a1.invh_id,
                        e1.staff_name checkPerson,
                        c1.vcoc_datetime checkDate
                    FROM
                        wms.ic_invh a1
                    LEFT JOIN erp.system_verifyrecord b1 ON b1.but_code = a1.inout_type
                    AND b1.vco_busid = a1.invh_id
                    LEFT JOIN erp.system_verifyrecord_child c1 ON c1.vco_id = b1.vco_id
                    LEFT JOIN erp.user d1 ON d1.user_id = c1.ver_acc_id
                    LEFT JOIN erp.hr_staff e1 ON e1.staff_id = d1.staff_id
                    ORDER BY
                        c1.ver_scode DESC
                    LIMIT 1
                ) k ON k.invh_id = a.invh_id
                LEFT JOIN erp.crm_bs_customer_info l ON l.cust_id = a.cust_id
                LEFT JOIN erp.bs_pubdata m ON m.bsp_id = l.cust_type
                LEFT JOIN erp.bs_business_type n on n.business_type_id = a.inout_type
                LEFT JOIN erp.bs_business_type q ON q.business_type_id = d.p_origin_type
                LEFT JOIN erp.bs_business r ON r.business_code = q.business_code
                WHERE
                    a.invh_id IS NOT NULL";
        $queryParams=[];
        //列表查询

        //单据号
        if(!empty($params['invh_code'])){
            $params['invh_code']=str_replace(['%','_'],['\%','\_'],$params['invh_code']);
            $queryParams[':invh_code']='%'.$params['invh_code'].'%';
            $querySql.=" and a.invh_code like :invh_code";
        }
        //出入库标志
        if(!empty($params['inout_flag'])){
            $querySql.=" and a.inout_flag ='{$params['inout_flag']}'";
         }
         //商品信息
        if(!empty($params['product_info'])){
            $params['product_info']=str_replace(['%','_'],['\%','\_'],$params['product_info']);
            $queryParams[':product_info']='%'.$params['product_info'].'%';
            $querySql.=" and d.part_no like :product_info or f.pdt_name like :product_info";
        }
        //商品规格
        if(!empty($params['ATTR_NAME'])){
            $params['ATTR_NAME']=str_replace(['%','_'],['\%','\_'],$params['ATTR_NAME']);
            $queryParams[':ATTR_NAME']='%'.$params['ATTR_NAME'].'%';
            $querySql.=" and h.attr_name like :ATTR_NAME";
        }
        //批次号
        if(!empty($params['batch_no'])){
            $params['batch_no']=str_replace(['%','_'],['\%','\_'],$params['batch_no']);
            $queryParams[':batch_no']='%'.$params['batch_no'].'%';
            $querySql.=" and d.batch_no like :batch_no";
        }
        //制单人
        if(!empty($params['create_by'])){
            $params['create_by']=str_replace(['%','_'],['\%','\_'],$params['create_by']);
            $queryParams[':create_by']='%'.$params['create_by'].'%';
            $querySql.=" and a.create_by like :create_by";
        }
        //单据状态1
        if(isset($params['invh_status'])&&$params['invh_status']!=""){
            $querySql.=" and a.invh_status ='{$params['invh_status']}'";
        }
        //单据状态2
        if(isset($params['invh_statuss'])&&$params['invh_statuss']!=""){
            $querySql.=" and a.invh_status ='{$params['invh_statuss']}'";
        }
        //单据日期
        if(!empty($params['start_date'])){
            $queryParams[':start_date']=date('Y-m-d H:i:s',strtotime($params['start_date']));
            $querySql.=" and a.invh_date >= :start_date";
        }
        if(!empty($params['end_date'])){
            $queryParams[':end_date']=date('Y-m-d H:i:s',strtotime($params['end_date'].'+1 day'));
            $querySql.=" and  a.invh_date < :end_date";
        }
        $querySql.=" order by a.invh_id desc";
        $totalCount=Yii::$app->get('db')->createCommand("select count(*) from ( {$querySql} ) a",$queryParams)->queryScalar();
        $provider=new SqlDataProvider([
            'db'=>'db',
            'sql'=>$querySql,
            'params'=>$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>10
            ]
        ]);
        $row=$provider->getModels();
//        return $row;
        return [
            'sql'=>$provider->sql,
            'rows'=>$row,
            'total'=>$provider->totalCount
        ];
    }

    //获取单据类型
    public function actionGetOrderType($code)
    {
        $sql="select business_type_id,business_value from erp.bs_business_type where business_code = :business_code";
        $arr=Yii::$app->db->createCommand($sql,[':business_code'=>$code])->queryAll();
        if(!empty($arr)){
            $arr=array_column($arr,'business_value','business_type_id');
        }
        return $arr;
    }
    //获取下拉框
    public function actionDownList()
    {
        $downlist['wh_type']="select bsp_svalue,
                                     bsp_id
                             from erp.bs_pubdata
                             WHERE bsp_stype='CKLB'
        ";
        $downlist['business_code']="SELECT
                                            business_code,
                                            business_desc
                                    FROM
                                            erp.bs_business
                                    WHERE
                                            business_code IN ('wm01','wm02','wm03','wm04','wm05','wm06','wm07','wm08')
        ";
        $downlist['wh_name']="select wh_id,wh_name from wms.bs_wh";
        $downlist['business_code']=Yii::$app->db->createCommand($downlist['business_code'])->queryAll();
        $downlist['wh_type']=Yii::$app->db->createCommand($downlist['wh_type'])->queryAll();
        $downlist['wh_name']=Yii::$app->db->createCommand($downlist['wh_name'])->queryAll();
        return $downlist;
}
    //获取仓库名称
    public function actionGetWarehouse($id)
    {
        $sql="select wh_id,wh_name from wms.bs_wh where wh_type = :id";
        $arr=Yii::$app->db->createCommand($sql,[':id'=>$id])->queryAll();
        if(!empty($arr)){
            $arr=array_column($arr,'wh_name','wh_id');
        }
        return $arr;
    }

    //获取储位信息
    public function actionGetStorageLocation($id)
    {
        $sql="select c.st_id,
                     c.st_code 
              from wms.bs_wh a 
              left join wms.bs_part b on b.wh_code = a.wh_code
              left join wms.bs_st c on c.part_code = b.part_code
              where a.wh_id = :id";
        $arr=Yii::$app->db->createCommand($sql,[':id'=>$id])->queryAll();
        $newArr=[];
        if(!empty($arr)){
            foreach($arr as $val){
                if(!empty($val['st_id'])){
                    $newArr[$val['st_id']]=$val['st_code'];
                }
            }
        }
        return $newArr;
    }
}