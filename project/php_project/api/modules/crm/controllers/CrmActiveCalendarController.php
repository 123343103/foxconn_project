<?php
/**
 * User: F1677929
 * Date: 2017/6/23
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmActiveName;

/**
 * 活动行事历控制器
 */
class CrmActiveCalendarController extends BaseActiveController
{
    public $modelClass='app\modules\crm\models\CrmActiveName';

    /**
     * 活动行事历列表
     */
    public function actionIndex()
    {
        $params=\Yii::$app->request->queryParams;
        if(empty($params['companyId'])){
            return BsPubdata::getData(BsPubdata::CRM_ACTIVE_WAY);
        }
        //查询参数
        $queryParams=[
            ':start_time'=>$params['start'],
            ':end_time'=>$params['end'],
            ':add_status'=>CrmActiveName::ADD_STATUS,
            ':already_start'=>CrmActiveName::ALREADY_START,
            ':already_end'=>CrmActiveName::ALREADY_END
        ];
        //查询sql
        $querySql="select a.actbs_id,
                          a.actbs_name,
                          a.actbs_start_time,
                          a.actbs_end_time,
                          h.bsp_svalue acttype_name,
                          (case a.actbs_status when :add_status then '未开始' when :already_start then '进行中' when :already_end then '已结束' else '删除' end) activeStatus,
                          concat(f.district_name,e.district_name,d.district_name,c.district_name,a.actbs_address) activeAddress,
                          g.bsp_svalue activeWay
                   from erp.crm_bs_act a 
                   left join erp.crm_bs_acttype b on b.acttype_id = a.acttype_id
                   left join erp.bs_district c on c.district_id = a.actbs_address_id
                   left join erp.bs_district d on d.district_id = c.district_pid
                   left join erp.bs_district e on e.district_id = d.district_pid
                   left join erp.bs_district f on f.district_id = e.district_pid
                   left join erp.bs_pubdata g on g.bsp_id = b.acttype_way
                   left join erp.bs_pubdata h on h.bsp_id = b.acttype_name
                   where (a.actbs_status = :add_status or a.actbs_status = :already_start or a.actbs_status = :already_end)
                   and UNIX_TIMESTAMP(a.actbs_start_time) >= :start_time
                   and UNIX_TIMESTAMP(a.actbs_start_time) < :end_time
                   and a.company_id in (";
        //遍历公司id
        foreach(BsCompany::getIdsArr($params['companyId']) as $key=>$val){
            $queryParams[":company_id_{$key}"]=$val;
            $querySql.=":company_id_{$key},";
        }
        $querySql=trim($querySql,',').')';
        if(!empty($params['wayId'])){
            $queryParams[':wayId']=$params['wayId'];
            $querySql.=" and b.acttype_way = :wayId";
        }
        //返回数据
        return \Yii::$app->db->createCommand($querySql,$queryParams)->queryAll();
    }
}