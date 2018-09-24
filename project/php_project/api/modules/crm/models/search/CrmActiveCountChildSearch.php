<?php
/**
 * User: F1677929
 * Date: 2017/6/5
 */
namespace app\modules\crm\models\search;
use app\modules\crm\models\CrmActiveCountChild;
use yii\data\SqlDataProvider;

/**
 * 活动统计子表搜索模型
 */
class CrmActiveCountChildSearch extends CrmActiveCountChild
{
    /**
     * 搜索活动统计
     */
    public function searchCountChild($params)
    {
        //查询参数
        $queryParams=[
            ':count_status'=>CrmActiveCountChild::DEFAULT_STATUS,
            ':main_id'=>(int)$params['mainId']
        ];
        //统计总数sql
//        $countSql="select count(*)
//                   from erp.crm_act_count_child a
//                   where a.actch_status=:count_status
//                   and a.actch_id=:main_id";
        //查询sql
        $querySql="select a.*,b.cmt_type mediaType
                   from erp.crm_act_count_child a
                   left join erp.crm_bs_media_type b on b.cmt_id = a.cc_id
                   where a.actch_status=:count_status
                   and a.actch_id=:main_id";
        //总条数
//        $totalCount=\Yii::$app->db->createCommand($countSql,$queryParams)->queryScalar();
        //SQL数据提供者
        $provider=new SqlDataProvider([
            'sql'=>$querySql,
            'params'=>$queryParams,
//            'totalCount'=>$totalCount,
            'pagination'=>[
//                'pageSize'=>$params['rows']
                'pageSize'=>''
            ]
        ]);
        return $provider;
    }
}