<?php
/**
 * User: F1677929
 * Date: 2017/6/5
 */
namespace app\modules\crm\models\search;
use app\classes\Trans;
use app\modules\crm\models\CrmMediaType;
use app\modules\hr\models\HrStaff;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use app\modules\common\models\BsCompany;

/**
 * 媒体类型搜索模型
 */
class CrmMediaTypeSearch extends CrmMediaType
{
    /**
     * 搜索媒体类型
     */
    public function searchMediaType($params)
    {
        $query=(new Query())->select([
            CrmMediaType::tableName().'.cmt_id',//媒体id
            CrmMediaType::tableName().'.cmt_code',//媒体编码
            CrmMediaType::tableName().'.cmt_type',//媒体类型
            CrmMediaType::tableName().'.cmt_intro',//媒体简介
            "(CASE ".CrmMediaType::tableName().".cmt_status WHEN ".CrmMediaType::ENABLED_STATUS." THEN '启用' WHEN ".CrmMediaType::DISABLED_STATUS." THEN '禁用' ELSE '删除' END) AS mediaStatus",//媒体状态
            'hs1.staff_name AS createBy',//创建人
            CrmMediaType::tableName().'.create_at',//创建时间
            'hs2.staff_name AS updateBy',//修改人
            CrmMediaType::tableName().'.update_at',//修改时间
            'IFNULL('.CrmMediaType::tableName().'.update_at,'.CrmMediaType::tableName().'.create_at) AS sort_at',//排序
        ])->from(CrmMediaType::tableName())
            ->leftJoin(HrStaff::tableName().' hs1','hs1.staff_id='.CrmMediaType::tableName().'.create_by')
            ->leftJoin(HrStaff::tableName().' hs2','hs2.staff_id='.CrmMediaType::tableName().'.update_by')
            ->where(['!=',CrmMediaType::tableName().'.cmt_status',CrmMediaType::DELETE_STATUS])
            ->andWhere(['in',CrmMediaType::tableName().'.company_id',BsCompany::getIdsArr($params['companyId'])])
            ->orderBy(['sort_at'=>SORT_DESC]);
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
                //媒体编码
                ['like',CrmMediaType::tableName().'.cmt_code',$params['keyword']],
                //媒体类型
                ['like',CrmMediaType::tableName().'.cmt_type',$params['keyword']],
                ['like',CrmMediaType::tableName().'.cmt_type',$trans->c2t($params['keyword'])],
                ['like',CrmMediaType::tableName().'.cmt_type',$trans->t2c($params['keyword'])],
                //简介
                ['like',CrmMediaType::tableName().'.cmt_intro',$params['keyword']],
                ['like',CrmMediaType::tableName().'.cmt_intro',$trans->c2t($params['keyword'])],
                ['like',CrmMediaType::tableName().'.cmt_intro',$trans->t2c($params['keyword'])],
                //状态
                ['like',"(CASE ".CrmMediaType::tableName().".cmt_status WHEN ".CrmMediaType::ENABLED_STATUS." THEN '启用' WHEN ".CrmMediaType::DISABLED_STATUS." THEN '禁用' ELSE '删除' END)",$params['keyword']],
                ['like',"(CASE ".CrmMediaType::tableName().".cmt_status WHEN ".CrmMediaType::ENABLED_STATUS." THEN '启用' WHEN ".CrmMediaType::DISABLED_STATUS." THEN '禁用' ELSE '删除' END)",$trans->c2t($params['keyword'])],
                ['like',"(CASE ".CrmMediaType::tableName().".cmt_status WHEN ".CrmMediaType::ENABLED_STATUS." THEN '启用' WHEN ".CrmMediaType::DISABLED_STATUS." THEN '禁用' ELSE '删除' END)",$trans->t2c($params['keyword'])],
                //档案建立人
                ['like','hs1.staff_name',$params['keyword']],
                ['like','hs1.staff_name',$trans->c2t($params['keyword'])],
                ['like','hs1.staff_name',$trans->t2c($params['keyword'])],
                //建档日期
                ['like',"DATE_FORMAT(".CrmMediaType::tableName().".create_at,'%Y-%m-%d %H:%i:%s')",$params['keyword']],
                //最后修改人
                ['like','hs2.staff_name',$params['keyword']],
                ['like','hs2.staff_name',$trans->c2t($params['keyword'])],
                ['like','hs2.staff_name',$trans->t2c($params['keyword'])],
                //修改日期
                ['like',"DATE_FORMAT(".CrmMediaType::tableName().".update_at,'%Y-%m-%d %H:%i:%s')",$params['keyword']]
            ]);
        }
        return $dataProvider;
    }
}