<?php
/**
 * User: F1677929
 * Date: 2017/9/30
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsCompany;
use Yii;
/**
 * 销售拜访行事历(行程日历)
 */
class SaleVisitCalendarController extends BaseActiveController
{
    public $modelClass='';

    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        $queryParams=[
            ':start_time'=>date('Y-m-d H:i:s',(int)$params['start']-8*3600),
            ':end_time'=>date('Y-m-d H:i:s',(int)$params['end']-8*3600),
        ];
        //拜访记录
//        $sql1="select a.sil_id,
//                      a.start,
//                      a.end,
//                      c.cust_sname,
//                      c.cust_shortname
//               from erp.crm_visit_info_child a
//               left join erp.crm_visit_info b on b.sih_id = a.sih_id
//               left join erp.crm_bs_customer_info c on c.cust_id = b.cust_id
//               left join erp.hr_staff d on d.staff_code = a.sil_staff_code
//               where (a.type = '20' or a.type = '30')
//               and a.start >= :start_time
//               and a.end < :end_time
//               and b.company_id in (";
        //拜访计划
        $sql2="select a.svp_id,
                      a.start,
                      a.end,
                      b.cust_sname,
                      b.cust_shortname
               from erp.crm_visit_plan a
               left join erp.crm_bs_customer_info b on b.cust_id = a.cust_id
               left join erp.hr_staff c on c.staff_code = a.svp_staff_code
               where a.start >= :start_time
               and a.end < :end_time
               and a.company_id in (";
        foreach(BsCompany::getIdsArr($params['companyId']) as $key=>$val){
//            $sql1.=$val.',';
            $sql2.=$val.',';
        }
//        $sql1=trim($sql1,',').')';
        $sql2=trim($sql2,',').')';
        if(!empty($params['staffId'])){
//            $sql1.=" and d.staff_id = {$params['staffId']}";
            $sql2.=" and c.staff_id = {$params['staffId']}";
        }
//        $arr1=Yii::$app->db->createCommand($sql1,$queryParams)->queryAll();
        $arr2=Yii::$app->db->createCommand($sql2,$queryParams)->queryAll();
//        $data=array_merge($arr1,$arr2);
        return $arr2;
    }
}