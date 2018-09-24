<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models\search;
use app\classes\Trans;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmActiveName;
use app\modules\crm\models\CrmActiveType;
use app\modules\crm\models\show\CrmActiveNameShow;
use app\modules\hr\models\HrStaff;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use app\modules\common\models\BsCompany;
/**
 * 活动名称搜索模型
 */
class CrmActiveNameSearch extends CrmActiveName
{
    //搜索活动名称
    public function searchActiveName($params)
    {
        $query=(new Query())->select([
            CrmActiveName::tableName().'.*',//活动名称表
            "(CASE ".CrmActiveName::tableName().".actbs_status WHEN ".CrmActiveName::ADD_STATUS." THEN '未开始' WHEN ".CrmActiveName::ALREADY_START." THEN '进行中' WHEN ".CrmActiveName::ALREADY_END." THEN '已结束' WHEN ".CrmActiveName::ALREADY_CANCEL." THEN '已取消' WHEN ".CrmActiveName::ALREADY_STOP." THEN '已终止' ELSE '删除' END) AS activeNameStatus",//活动状态
            'bp2.bsp_svalue acttype_name',//类型名
            'bp1.bsp_svalue activeWay',//活动方式
            'hs1.staff_name createBy',//创建人
            'hs2.staff_name updateBy',//修改人
        ])->from(CrmActiveName::tableName())
            ->leftJoin(CrmActiveType::tableName(),CrmActiveType::tableName().'.acttype_id='.CrmActiveName::tableName().'.acttype_id')
            ->leftJoin(BsPubdata::tableName().' bp1','bp1.bsp_id='.CrmActiveType::tableName().'.acttype_way')
            ->leftJoin(BsPubdata::tableName().' bp2','bp2.bsp_id='.CrmActiveType::tableName().'.acttype_name')
            ->leftJoin(HrStaff::tableName().' hs1','hs1.staff_id='.CrmActiveName::tableName().'.create_by')
            ->leftJoin(HrStaff::tableName().' hs2','hs2.staff_id='.CrmActiveName::tableName().'.update_by')
            ->where(['!=',CrmActiveName::tableName().'.actbs_status',CrmActiveName::DELETE_STATUS])
            ->andWhere(['in',CrmActiveName::tableName().'.company_id',BsCompany::getIdsArr($params['companyId'])])
            ->orderBy(['actbs_id'=>SORT_DESC]);
        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ],
        ]);
        if(!empty($params['keyword'])){
            $trans=new Trans();
            $query->andFilterWhere([
                'or',
                //编码
                ['like',CrmActiveName::tableName().'.actbs_code',$params['keyword']],
                //活动名称
                ['like',CrmActiveName::tableName().'.actbs_name',$params['keyword']],
                ['like',CrmActiveName::tableName().'.actbs_name',$trans->c2t($params['keyword'])],
                ['like',CrmActiveName::tableName().'.actbs_name',$trans->t2c($params['keyword'])],
                //活动类型
                ['like','bp2.bsp_svalue',$params['keyword']],
                ['like','bp2.bsp_svalue',$trans->c2t($params['keyword'])],
                ['like','bp2.bsp_svalue',$trans->t2c($params['keyword'])],
                //活动方式
                ['like','bp1.bsp_svalue',$params['keyword']],
                ['like','bp1.bsp_svalue',$trans->c2t($params['keyword'])],
                ['like','bp1.bsp_svalue',$trans->t2c($params['keyword'])],
                //开始时间
                ['like',"DATE_FORMAT(".CrmActiveName::tableName().".actbs_start_time,'%Y-%m-%d %H:%i:%s')",$params['keyword']],
                //结束时间
                ['like',"DATE_FORMAT(".CrmActiveName::tableName().".actbs_end_time,'%Y-%m-%d %H:%i:%s')",$params['keyword']],
                //活动状态
                ['like',"(CASE ".CrmActiveName::tableName().".actbs_status WHEN ".CrmActiveName::ADD_STATUS." THEN '未开始' WHEN ".CrmActiveName::ALREADY_START." THEN '进行中' WHEN ".CrmActiveName::ALREADY_END." THEN '已结束' WHEN ".CrmActiveName::ALREADY_CANCEL." THEN '已取消' ELSE '删除' END)",$params['keyword']],
                ['like',"(CASE ".CrmActiveName::tableName().".actbs_status WHEN ".CrmActiveName::ADD_STATUS." THEN '未开始' WHEN ".CrmActiveName::ALREADY_START." THEN '进行中' WHEN ".CrmActiveName::ALREADY_END." THEN '已结束' WHEN ".CrmActiveName::ALREADY_CANCEL." THEN '已取消' ELSE '删除' END)",$trans->c2t($params['keyword'])],
                ['like',"(CASE ".CrmActiveName::tableName().".actbs_status WHEN ".CrmActiveName::ADD_STATUS." THEN '未开始' WHEN ".CrmActiveName::ALREADY_START." THEN '进行中' WHEN ".CrmActiveName::ALREADY_END." THEN '已结束' WHEN ".CrmActiveName::ALREADY_CANCEL." THEN '已取消' ELSE '删除' END)",$trans->t2c($params['keyword'])],
                //档案建立人
                ['like','hs1.staff_name',$params['keyword']],
                ['like','hs1.staff_name',$trans->c2t($params['keyword'])],
                ['like','hs1.staff_name',$trans->t2c($params['keyword'])],
                //建档日期
                ['like',"DATE_FORMAT(".CrmActiveName::tableName().".create_at,'%Y-%m-%d %H:%i:%s')",$params['keyword']],
                //最后修改人
                ['like','hs2.staff_name',$params['keyword']],
                ['like','hs2.staff_name',$trans->c2t($params['keyword'])],
                ['like','hs2.staff_name',$trans->t2c($params['keyword'])],
                //修改日期
                ['like',"DATE_FORMAT(".CrmActiveName::tableName().".update_at,'%Y-%m-%d %H:%i:%s')",$params['keyword']]
            ]);
        }
//        file_put_contents('log.txt',$query->createCommand()->getRawSql());
        return $dataProvider;
    }
}