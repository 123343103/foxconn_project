<?php
/**
 * User: F1677929
 * Date: 2017/6/5
 */
namespace app\modules\crm\models\search;
use app\classes\Trans;
use app\modules\crm\Crm;
use app\modules\crm\models\CrmCarrier;
use app\modules\hr\models\HrStaff;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;
use app\modules\common\models\BsCompany;

/**
 * 载体搜索模型
 */
class CrmCarrierSearch extends CrmCarrier
{
    /**
     * 搜索载体
     */
    public function searchCarrier($params)
    {
        $queryParams=[
            ':enabled_status'=>CrmCarrier::ENABLED_STATUS,
            ':disabled_status'=>CrmCarrier::DISABLED_STATUS
        ];
        $countSql="select count(a.cc_id)
                   from erp.crm_bs_carrier a
                   left join (select a1.cc_id, 
                                     group_concat(b1.bsp_svalue) carrierType
                              from erp.crm_bs_carrier a1
                              left join erp.bs_pubdata b1 on find_in_set(b1.bsp_id, a1.cc_carrier)
                              group by a1.cc_id) b on b.cc_id = a.cc_id
                   left join (select a2.cc_id,
                                     group_concat(b2.bsp_svalue) saleWay
                              from erp.crm_bs_carrier a2
                              left join erp.bs_pubdata b2 on find_in_set(b2.bsp_id, a2.cc_sale_way)
                              group by a2.cc_id) c on c.cc_id = a.cc_id
                   left join erp.hr_staff d on d.staff_id = a.create_by
                   left join erp.hr_staff e on e.staff_id = a.update_by
                   where (a.cc_status = :enabled_status or a.cc_status = :disabled_status)
                   and a.company_id in (";
        $querySql="select a.cc_id,
                          a.cc_code,
                          a.cc_name,
                          b.carrierType,
                          c.saleWay,
                          (case a.cc_status when :enabled_status then '启用' when :disabled_status then '禁用' else '删除' end) carrierStatus,
                          d.staff_name createBy,
                          a.create_at,
                          e.staff_name updateBy,
                          a.update_at
                   from erp.crm_bs_carrier a
                   left join (select a1.cc_id, 
                                     group_concat(b1.bsp_svalue) carrierType
                              from erp.crm_bs_carrier a1
                              left join erp.bs_pubdata b1 on find_in_set(b1.bsp_id, a1.cc_carrier)
                              group by a1.cc_id) b on b.cc_id = a.cc_id
                   left join (select a2.cc_id,
                                     group_concat(b2.bsp_svalue) saleWay
                              from erp.crm_bs_carrier a2
                              left join erp.bs_pubdata b2 on find_in_set(b2.bsp_id, a2.cc_sale_way)
                              group by a2.cc_id) c on c.cc_id = a.cc_id
                   left join erp.hr_staff d on d.staff_id = a.create_by
                   left join erp.hr_staff e on e.staff_id = a.update_by
                   where (a.cc_status = :enabled_status or a.cc_status = :disabled_status)
                   and a.company_id in (";
        foreach(BsCompany::getIdsArr($params['companyId']) as $key=>$val){
            $queryParams[':company_id_'.$key]=$val;
            $countSql.=':company_id_'.$key.',';
            $querySql.=':company_id_'.$key.',';
        }
        $countSql=trim($countSql,',').')';
        $querySql=trim($querySql,',').')';
        if(!empty($params['keyword'])){
            $trans=new Trans();
            $queryParams[':keyword1']='%'.$params['keyword'].'%';
            $queryParams[':keyword2']='%'.$trans->c2t($params['keyword']).'%';
            $queryParams[':keyword3']='%'.$trans->t2c($params['keyword']).'%';
            $countSql.=" and (a.cc_code like :keyword1 
                              or a.cc_name like :keyword1 
                              or a.cc_name like :keyword2 
                              or a.cc_name like :keyword3
                              or b.carrierType like :keyword1 
                              or b.carrierType like :keyword2 
                              or b.carrierType like :keyword3
                              or c.saleWay like :keyword1 
                              or c.saleWay like :keyword2 
                              or c.saleWay like :keyword3
                              or (case a.cc_status when :enabled_status then '启用' when :disabled_status then '禁用' else '删除' end) like :keyword1 
                              or (case a.cc_status when :enabled_status then '启用' when :disabled_status then '禁用' else '删除' end) like :keyword2 
                              or (case a.cc_status when :enabled_status then '启用' when :disabled_status then '禁用' else '删除' end) like :keyword3
                              or d.staff_name like :keyword1 
                              or d.staff_name like :keyword2 
                              or d.staff_name like :keyword3
                              or date_format(a.create_at, '%Y-%m-%d %H:%i:%s') like :keyword1
                              or e.staff_name like :keyword1 
                              or e.staff_name like :keyword2 
                              or e.staff_name like :keyword3
                              or date_format(a.update_at, '%Y-%m-%d %H:%i:%s') like :keyword1
                              )";
            $querySql.=" and (a.cc_code like :keyword1 
                              or a.cc_name like :keyword1 
                              or a.cc_name like :keyword2 
                              or a.cc_name like :keyword3
                              or b.carrierType like :keyword1 
                              or b.carrierType like :keyword2 
                              or b.carrierType like :keyword3
                              or c.saleWay like :keyword1 
                              or c.saleWay like :keyword2 
                              or c.saleWay like :keyword3
                              or (case a.cc_status when :enabled_status then '启用' when :disabled_status then '禁用' else '删除' end) like :keyword1 
                              or (case a.cc_status when :enabled_status then '启用' when :disabled_status then '禁用' else '删除' end) like :keyword2 
                              or (case a.cc_status when :enabled_status then '启用' when :disabled_status then '禁用' else '删除' end) like :keyword3
                              or d.staff_name like :keyword1 
                              or d.staff_name like :keyword2 
                              or d.staff_name like :keyword3
                              or date_format(a.create_at, '%Y-%m-%d %H:%i:%s') like :keyword1
                              or e.staff_name like :keyword1 
                              or e.staff_name like :keyword2 
                              or e.staff_name like :keyword3
                              or date_format(a.update_at, '%Y-%m-%d %H:%i:%s') like :keyword1
                              )";
        }
        $totalCount=\Yii::$app->db->createCommand($countSql,$queryParams)->queryScalar();
        $querySql.=" order by a.cc_id desc";
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