<?php
namespace app\modules\ptdt\models\search;
use app\classes\Trans;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\PdFirm;
use app\modules\ptdt\models\PdVisitPlan;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\PdVisitResume;
use yii\db\Query;
//拜访履历主表搜索模型
class PdVisitResumeSearch extends PdVisitResume
{
    //列表页搜索
    public function searchResumeMain($params)
    {
        $query=(new Query())->select([
            PdVisitResume::tableName().'.vih_id',
            PdVisitResume::tableName().'.vih_code',
            PdVisitResume::tableName().'.vih_status',
            "(CASE ".PdVisitResume::tableName().".vih_status WHEN ".PdVisitResume::VISIT_ING." THEN '拜访中' WHEN ".PdVisitResume::VISIT_COMPLETE." THEN '拜访完成' ELSE '删除' END) AS visitStatus",
            'IFNULL('.PdVisitResume::tableName().'.update_at,'.PdVisitResume::tableName().'.create_at) AS sort_at',
            PdFirm::tableName().'.firm_id',
            PdFirm::tableName().'.firm_sname',
            PdFirm::tableName().'.firm_shortname',
            PdFirm::tableName().'.firm_brand',
            PdFirm::tableName().'.firm_category_id',
            PdFirm::tableName().'.firm_issupplier',
            "(CASE ".PdFirm::tableName().".firm_issupplier WHEN '0' THEN '否' WHEN '1' THEN '是' ELSE '不知' END) AS groupSupplier",
            'bp1.bsp_svalue AS firmType',
            'bp2.bsp_svalue AS firmSource',
        ])->from(PdVisitResume::tableName())
            ->leftJoin(PdFirm::tableName(),PdFirm::tableName().'.firm_id='.PdVisitResume::tableName().'.firm_id')
            ->leftJoin(BsPubdata::tableName().' bp1','bp1.bsp_id='.PdFirm::tableName().'.firm_type')
            ->leftJoin(BsPubdata::tableName().' bp2','bp2.bsp_id='.PdFirm::tableName().'.firm_source')
            ->where(['!=',PdVisitResume::tableName().'.vih_status',PdVisitResume::VISIT_DELETE])
            ->andWhere(['in',PdVisitResume::tableName().'.company_id',BsCompany::getIdsArr($params['companyId'])])
            ->orderBy(['sort_at'=>SORT_DESC]);
        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>$params['rows'],
            ],
        ]);
        $query->andFilterWhere([
            PdFirm::tableName().'.firm_type'=>isset($params['firmType'])?$params['firmType']:'',
            PdFirm::tableName().'.firm_issupplier'=>isset($params['groupSupplier'])?$params['groupSupplier']:'',
            PdVisitResume::tableName().'.vih_status'=>isset($params['visitStatus'])?$params['visitStatus']:'',
        ]);
        $trans=new Trans();
        if(isset($params['firmName'])){
            $query->andFilterWhere([
                'or',
                ['like',PdFirm::tableName().'.firm_sname',$params['firmName']],
                ['like',PdFirm::tableName().'.firm_sname',$trans->c2t($params['firmName'])],
                ['like',PdFirm::tableName().'.firm_sname',$trans->t2c($params['firmName'])],
                ['like',PdFirm::tableName().'.firm_shortname',$params['firmName']],
                ['like',PdFirm::tableName().'.firm_shortname',$trans->c2t($params['firmName'])],
                ['like',PdFirm::tableName().'.firm_shortname',$trans->t2c($params['firmName'])],
            ]);
        }
        if(isset($params['oneCategory'])){
            $query->andFilterWhere([
                'or',
                ['like',PdFirm::tableName().'.firm_category_id',$params['oneCategory']],
                ['like',PdFirm::tableName().'.firm_category_id',$trans->c2t($params['oneCategory'])],
                ['like',PdFirm::tableName().'.firm_category_id',$trans->t2c($params['oneCategory'])],
            ]);
        }
        return $dataProvider;
    }

    //搜索厂商
    public function searchFirm($params)
    {
        $query=(new Query())->select([
            PdFirm::tableName().'.firm_id',//厂商id
            PdFirm::tableName().'.firm_sname',//注册公司名称
            PdFirm::tableName().'.firm_shortname',//公司简称
            PdFirm::tableName().'.firm_ename',//英文全称
            PdFirm::tableName().'.firm_eshortname',//英文简称
            PdFirm::tableName().'.firm_brand',//品牌
            PdFirm::tableName().'.firm_brand_english',//商標英文名
            PdFirm::tableName().'.firm_compaddress',//公司地址
            PdFirm::tableName().'.firm_issupplier',//是否集团供应商
            PdFirm::tableName().'.firm_category_id',//是否集团供应商
            'bp1.bsp_svalue firmSource',//厂商来源
            'bp2.bsp_svalue firmType',//厂商类型
            'CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,'.PdFirm::tableName().'.firm_compaddress) AS firmAddress',//厂商地址
            'IFNULL('.PdFirm::tableName().'.update_at,'.PdFirm::tableName().'.create_at) AS sort_at',
        ])->from(PdFirm::tableName())
            ->leftJoin(BsDistrict::tableName().' bd1','bd1.district_id='.PdFirm::tableName().'.firm_district_id')
            ->leftJoin(BsDistrict::tableName().' bd2','bd1.district_pid=bd2.district_id')
            ->leftJoin(BsDistrict::tableName().' bd3','bd2.district_pid=bd3.district_id')
            ->leftJoin(BsDistrict::tableName().' bd4','bd3.district_pid=bd4.district_id')
            ->leftJoin(BsPubdata::tableName().' bp1','bp1.bsp_id='.PdFirm::tableName().'.firm_source')
            ->leftJoin(BsPubdata::tableName().' bp2','bp2.bsp_id='.PdFirm::tableName().'.firm_type')
            ->where(['!=',PdFirm::tableName().'.firm_status',PdFirm::STATUS_DELETE])
            ->andWhere(['in',PdFirm::tableName().'.company_id',BsCompany::getIdsArr($params['companyId'])])
            ->orderBy(['sort_at'=>SORT_DESC]);
        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>$params['rows'],
            ],
        ]);
        if(isset($params['searchKeyword'])){
            $trans=new Trans();//处理简体繁体
            $query->andFilterWhere([
                'or',
                ['like',PdFirm::tableName().'.firm_sname',$params['searchKeyword']],
                ['like',PdFirm::tableName().'.firm_sname',$trans->t2c($params['searchKeyword'])],
                ['like',PdFirm::tableName().'.firm_sname',$trans->c2t($params['searchKeyword'])],
                ['like',PdFirm::tableName().'.firm_shortname',$params['searchKeyword']],
                ['like',PdFirm::tableName().'.firm_shortname',$trans->t2c($params['searchKeyword'])],
                ['like',PdFirm::tableName().'.firm_shortname',$trans->c2t($params['searchKeyword'])],
                ['like','CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,'.PdFirm::tableName().'.firm_compaddress)',$params['searchKeyword']],
                ['like','CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,'.PdFirm::tableName().'.firm_compaddress)',$trans->t2c($params['searchKeyword'])],
                ['like','CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,'.PdFirm::tableName().'.firm_compaddress)',$trans->c2t($params['searchKeyword'])],
                ['like','bp2.bsp_svalue',$params['searchKeyword']],
                ['like','bp2.bsp_svalue',$trans->t2c($params['searchKeyword'])],
                ['like','bp2.bsp_svalue',$trans->c2t($params['searchKeyword'])],
            ]);
        }
        return $dataProvider;
    }

    //搜索拜访计划
    public function searchPlan($params)
    {
        $query=(new Query())->select([
            PdVisitPlan::tableName().'.pvp_planID',//拜访计划id
            PdVisitPlan::tableName().'.pvp_plancode',//拜访计划编号
            PdVisitPlan::tableName().'.plan_date',//计划日期
            PdFirm::tableName().'.firm_sname',//注册公司名称
            'IFNULL('.PdVisitPlan::tableName().'.update_at,'.PdVisitPlan::tableName().'.create_at) AS sort_at',
        ])->from(PdVisitPlan::tableName())
            ->leftJoin(PdFirm::tableName(),PdFirm::tableName().'.firm_id='.PdVisitPlan::tableName().'.firm_id')
            ->where([PdVisitPlan::tableName().'.firm_id'=>$params['firmId']])
            ->andWhere([PdVisitPlan::tableName().'.plan_status'=>PdVisitPlan::STATUS_DEFAULT])
            ->andWhere([PdVisitPlan::tableName().'.purpose'=>100013])
            ->andWhere(['in',PdVisitPlan::tableName().'.company_id',BsCompany::getIdsArr($params['companyId'])])
            ->orderBy(['sort_at'=>SORT_DESC]);
        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>$params['rows'],
            ],
        ]);
        if(isset($params['searchKeyword'])){
            $trans=new Trans();//处理简体繁体
            $query->andFilterWhere([
                'or',
                ['like',PdVisitPlan::tableName().'.pvp_plancode',$params['searchKeyword']],
                ['like',"DATE_FORMAT(".PdVisitPlan::tableName().".plan_date,'%Y-%m-%d')",$params['searchKeyword']],
                ['like',PdFirm::tableName().'.firm_sname',$params['searchKeyword']],
                ['like',PdFirm::tableName().'.firm_sname',$trans->t2c($params['searchKeyword'])],
                ['like',PdFirm::tableName().'.firm_sname',$trans->c2t($params['searchKeyword'])],
            ]);
        }
        return $dataProvider;
    }
}
