<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models\search;
use app\classes\Trans;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmActiveType;
use app\modules\crm\models\show\CrmActiveTypeShow;
use app\modules\hr\models\HrStaff;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use app\modules\common\models\BsCompany;
//活动类型搜索模型
class CrmActiveTypeSearch extends CrmActiveType
{
    //搜索活动类型
    public function searchActiveType($params)
    {
        $query=(new Query())->select([
            CrmActiveType::tableName().'.*',//活动类型表
            'bp1.bsp_svalue AS activeWay',//活动方式
            "(CASE ".CrmActiveType::tableName().".acttype_status WHEN ".CrmActiveType::VALID_STATUS." THEN '启用' WHEN ".CrmActiveType::INVALID_STATUS." THEN '禁用' ELSE '删除' END) AS activeTypeStatus",//活动类型状态
            'hs1.staff_name AS createBy',//创建人
            'hs2.staff_name AS updateBy',//修改人
            'bp2.bsp_svalue AS acttype_name',//活动类型
        ])->from(CrmActiveType::tableName())
            ->leftJoin(BsPubdata::tableName().' bp1','bp1.bsp_id='.CrmActiveType::tableName().'.acttype_way')
            ->leftJoin(HrStaff::tableName().' hs1','hs1.staff_id='.CrmActiveType::tableName().'.create_by')
            ->leftJoin(HrStaff::tableName().' hs2','hs2.staff_id='.CrmActiveType::tableName().'.update_by')
            ->leftJoin(BsPubdata::tableName().' bp2','bp2.bsp_id='.CrmActiveType::tableName().'.acttype_name')
            ->where(['!=',CrmActiveType::tableName().'.acttype_status',CrmActiveType::DELETE_STATUS])
            ->andWhere(['in',CrmActiveType::tableName().'.company_id',BsCompany::getIdsArr($params['companyId'])])
            ->orderBy([CrmActiveType::tableName().'.acttype_id'=>SORT_DESC]);
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
                ['like',CrmActiveType::tableName().'.acttype_code',$params['keyword']],
                //活动类型
                ['like','bp2.bsp_svalue',$params['keyword']],
                ['like','bp2.bsp_svalue',$trans->c2t($params['keyword'])],
                ['like','bp2.bsp_svalue',$trans->t2c($params['keyword'])],
                //活动方式
                ['like','bp1.bsp_svalue',$params['keyword']],
                ['like','bp1.bsp_svalue',$trans->c2t($params['keyword'])],
                ['like','bp1.bsp_svalue',$trans->t2c($params['keyword'])],
                //描述
                ['like',CrmActiveType::tableName().'.acttype_description',$params['keyword']],
                ['like',CrmActiveType::tableName().'.acttype_description',$trans->c2t($params['keyword'])],
                ['like',CrmActiveType::tableName().'.acttype_description',$trans->t2c($params['keyword'])],
                //状态
                ['like',"(CASE ".CrmActiveType::tableName().".acttype_status WHEN ".CrmActiveType::VALID_STATUS." THEN '启用' WHEN ".CrmActiveType::INVALID_STATUS." THEN '禁用' ELSE '删除' END)",$params['keyword']],
                ['like',"(CASE ".CrmActiveType::tableName().".acttype_status WHEN ".CrmActiveType::VALID_STATUS." THEN '启用' WHEN ".CrmActiveType::INVALID_STATUS." THEN '禁用' ELSE '删除' END)",$trans->c2t($params['keyword'])],
                ['like',"(CASE ".CrmActiveType::tableName().".acttype_status WHEN ".CrmActiveType::VALID_STATUS." THEN '启用' WHEN ".CrmActiveType::INVALID_STATUS." THEN '禁用' ELSE '删除' END)",$trans->t2c($params['keyword'])],
                //档案建立人
                ['like','hs1.staff_name',$params['keyword']],
                ['like','hs1.staff_name',$trans->c2t($params['keyword'])],
                ['like','hs1.staff_name',$trans->t2c($params['keyword'])],
                //建档日期
                ['like',"DATE_FORMAT(".CrmActiveType::tableName().".create_at,'%Y-%m-%d %H:%i:%s')",$params['keyword']],
                //最后修改人
                ['like','hs2.staff_name',$params['keyword']],
                ['like','hs2.staff_name',$trans->c2t($params['keyword'])],
                ['like','hs2.staff_name',$trans->t2c($params['keyword'])],
                //修改日期
                ['like',"DATE_FORMAT(".CrmActiveType::tableName().".update_at,'%Y-%m-%d %H:%i:%s')",$params['keyword']]
            ]);
        }
        return $dataProvider;
    }
}