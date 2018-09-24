<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/23
 * Time: 上午 10:59
 */
namespace app\modules\warehouse\models\search;
use app\models\User;
use app\modules\common\models\BsProduct;
use app\modules\hr\models\HrStaff;
use app\modules\warehouse\models\IcInvh;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\modules\common\models\BsCompany;
use app\classes\Trans;

class IcInvhSearch extends IcInvh
{
    public function fields(){
        $fields=parent::fields();
        $fields["product_property"]=function(){
            $data=self::getProductProperty();
            return isset($data[$this->product_property])?$data[$this->product_property]:"";
        };
        $fields["applicant"]=function(){
            $data=HrStaff::find()->select("staff_name")->where(["staff_id"=>$this->applicant])->scalar();
            return $data?$data:"";
        };
        $fields["invh_status"]=function(){
            $data=self::getStatus();
            $status=isset($data[$this->invh_status])?$data[$this->invh_status]:"/";
            if($this->invh_status==self::OUTSTOCK_CANCEL){
                return "<a class='tip' style='color:#cd0a0a'>{$status}</a>";
            }
            return $status;
        };
        $fields["inout_type"]=function(){
            $data=self::getInOutType();
            return isset($data[$this->inout_type])?$data[$this->inout_type]:"/";
        };
        $fields["organization_id"]=function(){
            $data=self::getOrganization();
            return isset($data[$this->organization_id])?$data[$this->organization_id]:"/";
        };
        $fields["whs_id"]=function(){
            $data=self::getWareHouse();
            return isset($data[$this->whs_id])?$data[$this->whs_id]:"/";
        };
        $fields["trans_type"]=function(){
            $data=self::getTransType();
            return isset($data[$this->trans_type])?$data[$this->trans_type]:"/";
        };
        $fields["delivery_type"]=function(){
            $data=self::getDelveryType();
            return isset($data[$this->delivery_type])?$data[$this->delivery_type]:"/";
        };
        $fields["create_by"]=function(){
            $staff=HrStaff::findOne($this->create_by);
            return isset($staff->staff_name)?$staff->staff_name:"/";
        };
        return $fields;
    }

    //搜索其他入库单
    public function searchOtherStockIn($params)
    {
        //查询参数
        $queryParams=[
            ':delete_status'=>IcInvh::DELETE_STATUS,
            ':stock_in_flag'=>IcInvh::STOCK_IN_FLAG
        ];
        //统计总数sql
        $countSql="select count(*)
                   from wms.ic_invh a
                   left join erp.bs_business_type b on b.business_type_id = a.inout_type
                   left join wms.bs_wh c on c.wh_id = a.whs_id
                   left join erp.hr_staff d on d.staff_id = a.invh_reperson
                   left join erp.hr_staff e on e.staff_id = a.create_by
                   where a.invh_status != :delete_status
                   and a.inout_flag = :stock_in_flag
                   and b.business_code = 'wm01'";
        $countSql.=" and a.comp_id in (";
        //查询sql
        $querySql="select a.invh_id,
                          a.invh_code,
                          (case a.invh_status when :wait_commit then '待提交' when :check_ing then '审核中' when :check_complete then '审核完成' when :wait_warehouse then '待入仓' when :already_warehouse then '已入仓' when :reject_status then '驳回' when :outstock_cancel then '作废' else '删除' end) stockInStatus,
                          a.invh_aboutno,
                          a.inout_type,
                          b.business_type_desc stockInType,
                          c.wh_name warehouseName,
                          a.invh_sendperson,
                          d.staff_name receptionPerson,
                          a.invh_date,
                          e.staff_name createBy,
                          a.cdate
                   from wms.ic_invh a
                   left join erp.bs_business_type b on b.business_type_id = a.inout_type
                   left join wms.bs_wh c on c.wh_id = a.whs_id
                   left join erp.hr_staff d on d.staff_id = a.invh_reperson
                   left join erp.hr_staff e on e.staff_id = a.create_by
                   where a.invh_status != :delete_status
                   and a.inout_flag = :stock_in_flag
                   and b.business_code = 'wm01'";
        $querySql.=" and a.comp_id in (";
        //数组查询参数
        foreach(BsCompany::getIdsArr($params['companyId']) as $key=>$val){
            $queryParams[':company_id'.$key]=$val;
            $countSql.=':company_id'.$key.',';
            $querySql.=':company_id'.$key.',';
        }
        $countSql=trim($countSql,',').')';
        $querySql=trim($querySql,',').')';
        //列表搜索
        if(!empty($params['invh_code'])){
            $params['invh_code']=str_replace(['%','_'],['\%','\_'],$params['invh_code']);
            $queryParams[':invh_code']='%'.$params['invh_code'].'%';
            $countSql.=" and a.invh_code like :invh_code";
            $querySql.=" and a.invh_code like :invh_code";
        }
        if(!empty($params['invh_status'])){
            $queryParams[':invh_status']=$params['invh_status'];
            $countSql.=" and a.invh_status = :invh_status";
            $querySql.=" and a.invh_status = :invh_status";
        }
        if(!empty($params['inout_type'])){
            $queryParams[':inout_type']=$params['inout_type'];
            $countSql.=" and a.inout_type = :inout_type";
            $querySql.=" and a.inout_type = :inout_type";
        }
        if(!empty($params['whs_id'])){
            $queryParams[':whs_id']=$params['whs_id'];
            $countSql.=" and a.whs_id = :whs_id";
            $querySql.=" and a.whs_id = :whs_id";
        }
        $trans=new Trans();
        if(!empty($params['invh_sendperson'])){
            $params['invh_sendperson']=str_replace(['%','_'],['\%','\_'],$params['invh_sendperson']);
            $queryParams[':invh_sendperson1']='%'.$params['invh_sendperson'].'%';
            $queryParams[':invh_sendperson2']='%'.$trans->c2t($params['invh_sendperson']).'%';
            $queryParams[':invh_sendperson3']='%'.$trans->t2c($params['invh_sendperson']).'%';
            $countSql.=" and (a.invh_sendperson like :invh_sendperson1 or a.invh_sendperson like :invh_sendperson2 or a.invh_sendperson like :invh_sendperson3)";
            $querySql.=" and (a.invh_sendperson like :invh_sendperson1 or a.invh_sendperson like :invh_sendperson2 or a.invh_sendperson like :invh_sendperson3)";
        }
        if(!empty($params['invh_reperson'])){
            $params['invh_reperson']=str_replace(['%','_'],['\%','\_'],$params['invh_reperson']);
            $queryParams[':invh_reperson1']='%'.$params['invh_reperson'].'%';
            $queryParams[':invh_reperson2']='%'.$trans->c2t($params['invh_reperson']).'%';
            $queryParams[':invh_reperson3']='%'.$trans->t2c($params['invh_reperson']).'%';
            $countSql.=" and (d.staff_name like :invh_reperson1 or d.staff_name like :invh_reperson2 or d.staff_name like :invh_reperson3)";
            $querySql.=" and (d.staff_name like :invh_reperson1 or d.staff_name like :invh_reperson2 or d.staff_name like :invh_reperson3)";
        }
        if(!empty($params['create_by'])){
            $params['create_by']=str_replace(['%','_'],['\%','\_'],$params['create_by']);
            $queryParams[':create_by1']='%'.$params['create_by'].'%';
            $queryParams[':create_by2']='%'.$trans->c2t($params['create_by']).'%';
            $queryParams[':create_by3']='%'.$trans->t2c($params['create_by']).'%';
            $countSql.=" and (e.staff_name like :create_by1 or e.staff_name like :create_by2 or e.staff_name like :create_by3)";
            $querySql.=" and (e.staff_name like :create_by1 or e.staff_name like :create_by2 or e.staff_name like :create_by3)";
        }
        //总条数
        $totalCount=\Yii::$app->db->createCommand($countSql,$queryParams)->queryScalar();
        //查询参数
        $queryParams[':wait_commit']=IcInvh::WAIT_COMMIT;
        $queryParams[':check_ing']=IcInvh::CHECK_ING;
        $queryParams[':check_complete']=IcInvh::CHECK_COMPLETE;
        $queryParams[':wait_warehouse']=IcInvh::WAIT_WAREHOUSE;
        $queryParams[':already_warehouse']=IcInvh::ALREADY_WAREHOUSE;
        $queryParams[':reject_status']=IcInvh::REJECT_STATUS;
        $queryParams[':outstock_cancel']=IcInvh::OUTSTOCK_CANCEL;
        //查询sql排序
        $querySql.=" order by a.invh_status asc, a.invh_id desc";
        //SQL数据提供者
        $provider=new SqlDataProvider([
            'sql'=>$querySql,
            'params'=>$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>empty($params['rows'])?false:$params['rows']
            ]
        ]);
        return $provider;
    }

    //其他出库单搜索
    public function searchOtherOutStock($params=""){
        $query=self::find()->where([">","invh_status",0])->andWhere(["inout_flag"=>"O"]);
        $dataProvider=new ActiveDataProvider([
            "query"=>$query,
            "pagination"=>[
                "pageSize"=>isset($params["rows"])?$params["rows"]:10
            ]
        ]);
        if(isset($params["invh_code"])){
            $query->andFilterWhere(["like","invh_code",$params["invh_code"]]);
        }
        if(isset($params["invh_status"])){
            $query->andFilterWhere(["invh_status"=>$params["invh_status"]]);
        }
        if(isset($params["inout_type"])){
            $query->andFilterWhere(["inout_type"=>$params["inout_type"]]);
        }
        if(isset($params["organization"])){
                    $query->andFilterWhere(["organization_id"=>$params["organization"]]);
        }
        if(isset($params["whs_id"])){
            $query->andFilterWhere(["whs_id"=>$params["whs_id"]]);
        }
        if(isset($params["invh_aboutno"])){
            $query->andFilterWhere(["invh_aboutno"=>$params["invh_aboutno"]]);
        }
        if(isset($params["applicant"])){
            $query->andFilterWhere(["applicant"=>$params["applicant"]]);
        }
        return $dataProvider;
    }

    //搜索商品
    public function searchProduct($params)
    {
        //查询参数
        $queryParams=[
            ':product_status'=>1
        ];
        //统计总数sql
        $countSql="select count(*)
                   from erp.bs_product a
                   left join erp.bs_brand b on b.BRAND_ID = a.brand_id
                   left join erp.category_attr c on c.CATEGORY_ATTR_ID = a.CATEGORY_ATTR_ID
                   left join erp.bs_category_unit d on d.id = a.unit
                   left join erp.bs_category e on e.category_id = a.bs_category_id
                   where a.status = :product_status";
        $countSql.=" and a.company_id in (";
        //查询sql
        $querySql="select a.pdt_id,
                          a.pdt_no,
                          a.pdt_name,
                          b.BRAND_NAME_CN,
                          c.ATTR_NAME,
                          d.unit_name,
                          e.category_sname
                   from erp.bs_product a
                   left join erp.bs_brand b on b.BRAND_ID = a.brand_id
                   left join erp.category_attr c on c.CATEGORY_ATTR_ID = a.tp_spec
                   left join erp.bs_category_unit d on d.id = a.unit
                   left join erp.bs_category e on e.category_id = a.bs_category_id
                   where a.status = :product_status";
        $querySql.=" and a.company_id in (";
        //数组查询参数
        foreach(BsCompany::getIdsArr($params['companyId']) as $key=>$val){
            $queryParams[':company_id'.$key]=$val;
            $countSql.=':company_id'.$key.',';
            $querySql.=':company_id'.$key.',';
        }
        $countSql=trim($countSql,',').')';
        $querySql=trim($querySql,',').')';
        //排除已选中的商品
        if(!empty($params['product_id'])){
            $arr=explode('-',$params['product_id']);
            $countSql.=" and a.pdt_id not in (";
            $querySql.=" and a.pdt_id not in (";
            foreach($arr as $key=>$val){
                $queryParams[':product_id'.$key]=(int)$val;
                $countSql.=':product_id'.$key.',';
                $querySql.=':product_id'.$key.',';
            }
            $countSql=trim($countSql,',').')';
            $querySql=trim($querySql,',').')';
        }
        //搜索
        if(!empty($params['keyword'])){
            $trans=new Trans();
            $queryParams[':keyword1']='%'.$params['keyword'].'%';
            $queryParams[':keyword2']='%'.$trans->c2t($params['keyword']).'%';
            $queryParams[':keyword3']='%'.$trans->t2c($params['keyword']).'%';
            $countSql.=" and (e.category_sname like :keyword1
                              or e.category_sname like :keyword2
                              or e.category_sname like :keyword3
                              or a.pdt_no like :keyword1
                              or a.pdt_name like :keyword1
                              or a.pdt_name like :keyword2
                              or a.pdt_name like :keyword3
                              or b.BRAND_NAME_CN like :keyword1
                              or b.BRAND_NAME_CN like :keyword2
                              or b.BRAND_NAME_CN like :keyword3
                              )";
            $querySql.=" and (e.category_sname like :keyword1
                              or e.category_sname like :keyword2
                              or e.category_sname like :keyword3
                              or a.pdt_no like :keyword1
                              or a.pdt_name like :keyword1
                              or a.pdt_name like :keyword2
                              or a.pdt_name like :keyword3
                              or b.BRAND_NAME_CN like :keyword1
                              or b.BRAND_NAME_CN like :keyword2
                              or b.BRAND_NAME_CN like :keyword3
                              )";
        }
        //总条数
        $totalCount=\Yii::$app->db->createCommand($countSql,$queryParams)->queryScalar();
        //查询sql排序
        $querySql.=" order by a.pdt_id desc";
        //SQL数据提供者
        $provider=new SqlDataProvider([
            'sql'=>$querySql,
            'params'=>$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ]
        ]);
        return $provider;
    }
}