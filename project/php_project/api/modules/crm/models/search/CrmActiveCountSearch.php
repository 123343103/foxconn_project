<?php
/**
 * User: F1677929
 * Date: 2017/6/5
 */
namespace app\modules\crm\models\search;
use app\classes\Trans;
use app\modules\crm\models\CrmActiveCount;
use app\modules\crm\models\CrmActiveName;
use app\modules\crm\models\CrmCarrier;
use app\modules\hr\models\HrStaff;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;
use app\modules\common\models\BsCompany;

/**
 * 活动统计主表搜索模型
 */
class CrmActiveCountSearch extends CrmActiveCount
{
    /**
     * 搜索活动统计
     */
    public function searchCountMain($params)
    {
        //查询参数
        $queryParams=[
            ':count_status'=>CrmActiveCount::DEFAULT_STATUS
        ];
        //统计总数sql
        $countSql="select count(*) 
                   from erp.crm_act_count a 
                   left join erp.crm_bs_act b on b.actbs_id = a.actbs_id
                   left join erp.bs_pubdata c on c.bsp_id = b.actbs_month
                   left join erp.bs_district d on d.district_id = b.actbs_address_id
                   left join erp.bs_district e on e.district_id = d.district_pid
                   left join erp.bs_district f on f.district_id = e.district_pid
                   left join erp.bs_district g on g.district_id = f.district_pid
                   left join erp.hr_staff h on h.staff_id = b.actbs_duty
                   left join erp.crm_bs_acttype i on i.acttype_id = b.acttype_id
                   left join erp.bs_pubdata j on j.bsp_id = i.acttype_way
                   where a.actch_status = :count_status 
                   and a.company_id in (";
        //查询sql
        $querySql="select a.actch_id,
                          a.actch_code,
                          b.actbs_id,
                          b.actbs_name, 
                          b.actbs_start_time, 
                          b.actbs_end_time,
                          (case b.actbs_status when :add_status then '未开始' when :already_start then '进行中' when :already_end then '已结束' when :already_cancel then '已取消' else '删除' end) activeStatus,
                          c.bsp_svalue activeMonth,
                          concat(g.district_name,f.district_name,e.district_name,d.district_name,b.actbs_address) activeAddress,
                          h.staff_name activeDuty,
                          k.bsp_svalue acttype_name,
                          j.bsp_svalue activeWay
                   from erp.crm_act_count a 
                   left join erp.crm_bs_act b on b.actbs_id = a.actbs_id
                   left join erp.bs_pubdata c on c.bsp_id = b.actbs_month
                   left join erp.bs_district d on d.district_id = b.actbs_address_id
                   left join erp.bs_district e on e.district_id = d.district_pid
                   left join erp.bs_district f on f.district_id = e.district_pid
                   left join erp.bs_district g on g.district_id = f.district_pid
                   left join erp.hr_staff h on h.staff_id = b.actbs_duty
                   left join erp.crm_bs_acttype i on i.acttype_id = b.acttype_id
                   left join erp.bs_pubdata j on j.bsp_id = i.acttype_way
                   left join erp.bs_pubdata k on k.bsp_id = i.acttype_name
                   where a.actch_status = :count_status 
                   and a.company_id in (";
        //遍历公司id
        foreach(BsCompany::getIdsArr($params['companyId']) as $key=>$val){
            $queryParams[":company_id_{$key}"]=$val;
            $countSql.=":company_id_{$key},";
            $querySql.=":company_id_{$key},";
        }
        $countSql=trim($countSql,',').')';
        $querySql=trim($querySql,',').')';
        //搜索框查询
        if(!empty($params['main_code'])){
            $params['main_code']=str_replace(['%','_'],['\%','\_'],$params['main_code']);
            $queryParams[':main_code']='%'.$params['main_code'].'%';
            $countSql.=" and a.actch_code like :main_code";
            $querySql.=" and a.actch_code like :main_code";
        }
        if(!empty($params['active_month'])){
            $queryParams[':active_month']=$params['active_month'];
            $countSql.=" and b.actbs_month = :active_month";
            $querySql.=" and b.actbs_month = :active_month";
        }
        if(!empty($params['active_type'])){
            $queryParams[':active_type']=$params['active_type'];
            $countSql.=" and i.acttype_id = :active_type";
            $querySql.=" and i.acttype_id = :active_type";
        }
        if(!empty($params['active_name'])){
            $queryParams[':active_name']=$params['active_name'];
            $countSql.=" and b.actbs_id = :active_name";
            $querySql.=" and b.actbs_id = :active_name";
        }
        if(!empty($params['active_way'])){
            $queryParams[':active_way']=$params['active_way'];
            $countSql.=" and i.acttype_way = :active_way";
            $querySql.=" and i.acttype_way = :active_way";
        }
        if(!empty($params['active_status'])){
            $queryParams[':active_status']=$params['active_status'];
            $countSql.=" and b.actbs_status = :active_status";
            $querySql.=" and b.actbs_status = :active_status";
        }
        if(!empty($params['active_duty'])){
            $trans=new Trans();
            $params['active_duty']=str_replace(['%','_'],['\%','\_'],$params['active_duty']);
            $queryParams[':active_duty_1']='%'.$params['active_duty'].'%';
            $queryParams[':active_duty_2']='%'.$trans->c2t($params['active_duty']).'%';
            $queryParams[':active_duty_3']='%'.$trans->t2c($params['active_duty']).'%';
            $countSql.=" and (h.staff_name like :active_duty_1 or h.staff_name like :active_duty_2 or h.staff_name like :active_duty_3)";
            $querySql.=" and (h.staff_name like :active_duty_1 or h.staff_name like :active_duty_2 or h.staff_name like :active_duty_3)";
        }
        if(!empty($params['active_start_time'])){
            $queryParams[':active_start_time']=date('Y-m-d H:i:s',strtotime($params['active_start_time']));
            $countSql.=" and b.actbs_start_time >= :active_start_time";
            $querySql.=" and b.actbs_start_time >= :active_start_time";
        }
        if(!empty($params['active_end_time'])){
            $queryParams[':active_end_time']=date('Y-m-d H:i:s',strtotime($params['active_end_time'].'+1 day'));
            $countSql.=" and b.actbs_end_time < :active_end_time";
            $querySql.=" and b.actbs_end_time < :active_end_time";
        }
        //总条数
        $totalCount=\Yii::$app->db->createCommand($countSql,$queryParams)->queryScalar();
        //查询sql追加参数
        $queryParams[':add_status']=CrmActiveName::ADD_STATUS;
        $queryParams[':already_start']=CrmActiveName::ALREADY_START;
        $queryParams[':already_end']=CrmActiveName::ALREADY_END;
        $queryParams[':already_cancel']=CrmActiveName::ALREADY_CANCEL;
        //查询sql排序
        $querySql.=" order by a.actch_id desc";
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

    /**
     * 选择活动
     */
    public function searchActive($params)
    {
        //查询参数
        $queryParams=[
            ':add_status'=>CrmActiveName::ADD_STATUS,
            ':already_start'=>CrmActiveName::ALREADY_START,
            ':already_end'=>CrmActiveName::ALREADY_END
        ];
        //统计总数sql
        $countSql="select count(*) 
                   from erp.crm_bs_act a
                   left join erp.crm_bs_acttype b on b.acttype_id = a.acttype_id
                   left join erp.bs_pubdata c on c.bsp_id = b.acttype_way
                   left join erp.bs_pubdata d on d.bsp_id = a.actbs_industry
                   left join erp.bs_district e on e.district_id = a.actbs_address_id
                   left join erp.bs_district f on f.district_id = e.district_pid
                   left join erp.bs_district g on g.district_id = f.district_pid
                   left join erp.bs_district h on h.district_id = g.district_pid
                   left join erp.bs_pubdata i on i.bsp_id = a.actbs_purpose
                   left join erp.hr_staff j on j.staff_id = a.actbs_maintain
                   left join erp.bs_pubdata k on k.bsp_id = a.actbs_month
                   left join erp.hr_staff l on l.staff_id = a.actbs_duty
                   where (a.actbs_status = :add_status or a.actbs_status = :already_start or a.actbs_status = :already_end)
                   and a.company_id in (";
        //查询sql
        $querySql="select a.*,
                          m.bsp_svalue acttype_name,
                          c.bsp_svalue activeWay,
                          d.bsp_svalue industryType,
                          concat(h.district_name,g.district_name,f.district_name,e.district_name,a.actbs_address) activeAddress,
                          i.bsp_svalue joinPurpose,
                          j.staff_name maintainPerson,
                          k.bsp_svalue activeMonth,
                          (case a.actbs_status when :add_status then '未开始' when :already_start then '进行中' when :already_end then '已结束' when :already_cancel then '已取消' else '删除' end) activeStatus,
                          l.staff_name activeDutyPerson
                   from erp.crm_bs_act a
                   left join erp.crm_bs_acttype b on b.acttype_id = a.acttype_id
                   left join erp.bs_pubdata c on c.bsp_id = b.acttype_way
                   left join erp.bs_pubdata d on d.bsp_id = a.actbs_industry
                   left join erp.bs_district e on e.district_id = a.actbs_address_id
                   left join erp.bs_district f on f.district_id = e.district_pid
                   left join erp.bs_district g on g.district_id = f.district_pid
                   left join erp.bs_district h on h.district_id = g.district_pid
                   left join erp.bs_pubdata i on i.bsp_id = a.actbs_purpose
                   left join erp.hr_staff j on j.staff_id = a.actbs_maintain
                   left join erp.bs_pubdata k on k.bsp_id = a.actbs_month
                   left join erp.hr_staff l on l.staff_id = a.actbs_duty
                   left join erp.bs_pubdata m on m.bsp_id = b.acttype_name
                   where (a.actbs_status = :add_status or a.actbs_status = :already_start or a.actbs_status = :already_end)
                   and a.company_id in (";
        //遍历公司id
        foreach(BsCompany::getIdsArr($params['companyId']) as $key=>$val){
            $queryParams[":company_id_{$key}"]=$val;
            $countSql.=":company_id_{$key},";
            $querySql.=":company_id_{$key},";
        }
        $countSql=trim($countSql,',').')';
        $querySql=trim($querySql,',').')';
        //搜索框查询
        if(!empty($params['keyword'])){
            $trans=new Trans();
            $queryParams[':keyword_1']='%'.$params['keyword'].'%';
            $queryParams[':keyword_2']='%'.$trans->c2t($params['keyword']).'%';
            $queryParams[':keyword_3']='%'.$trans->t2c($params['keyword']).'%';
            $countSql.=" and (a.actbs_name like :keyword_1 
                              or a.actbs_name like :keyword_2 
                              or a.actbs_name like :keyword_3
                              or c.bsp_svalue like :keyword_1
                              or c.bsp_svalue like :keyword_2
                              or c.bsp_svalue like :keyword_3
                              or DATE_FORMAT(a.actbs_start_time,'%Y-%m-%d %H:%i:%s') like :keyword_1
                              or DATE_FORMAT(a.actbs_end_time,'%Y-%m-%d %H:%i:%s') like :keyword_1)";
            $querySql.=" and (a.actbs_name like :keyword_1 
                              or a.actbs_name like :keyword_2 
                              or a.actbs_name like :keyword_3
                              or c.bsp_svalue like :keyword_1
                              or c.bsp_svalue like :keyword_2
                              or c.bsp_svalue like :keyword_3
                              or DATE_FORMAT(a.actbs_start_time,'%Y-%m-%d %H:%i:%s') like :keyword_1
                              or DATE_FORMAT(a.actbs_end_time,'%Y-%m-%d %H:%i:%s') like :keyword_1)";
        }
        //总条数
        $totalCount=\Yii::$app->db->createCommand($countSql,$queryParams)->queryScalar();
        //查询sql追加参数
        $queryParams[':already_cancel']=CrmActiveName::ALREADY_CANCEL;
        //查询sql排序
        $querySql.=" order by a.actbs_id desc";
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