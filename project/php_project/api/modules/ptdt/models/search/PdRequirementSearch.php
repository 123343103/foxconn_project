<?php
namespace app\modules\ptdt\models\search;

use app\modules\common\models\BsCompany;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use app\modules\common\models\BsCategory;
use app\modules\ptdt\models\PdRequirement;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\show\PdRequirementShow;
use yii\db\Query;

/**
 * 商品开发需求搜索模型
 *  F3858995
 *  2016/9/22
 */
class PdRequirementSearch extends PdRequirement
{

    public $startDate;
    public $endDate;

    public function rules()
    {
        return [
            [['startDate', 'endDate', 'pdq_code', 'pdq_source_type', 'develop_center', 'develop_department', 'pdq_status'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {

        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query=(new Query())
        ->select([
            'info.pdq_id',
            'info.pdq_code',                                        //需求编号
            'bp1.bsp_svalue sourceType',                            //需求来源
            'bp2.bsp_svalue developType',                           //开发类型
            'organization.organization_name developCenter',         //开发中心
            'organization1.organization_name developDepartment',   //开发部
            'commodity.category_sname commodity',        //商品大类
            'staff.staff_name productManager',                      //商品经理人
            'staff.staff_code productCode',                      //商品经理人
            'staff1.staff_name offer_name',                         //提报人
            'info.offer_date',                                      //提报日期
            'info.pdq_status',
            "(CASE info.pdq_status WHEN ".PdRequirement::STATUS_DELETE." THEN '删除' WHEN  ".PdRequirement::STATUS_DEFAULT." THEN '新增' WHEN  ".PdRequirement::STATUS_REVIEW." THEN '审核中' WHEN  ".PdRequirement::STATUS_FINISH." THEN '已通过' WHEN  ".PdRequirement::STATUS_REJECT." THEN '被驳回' ELSE '其他' END) as status",//状态
            'info.remark',                                               //备注
            'IFNULL('.'info.update_at,'.'info.create_at) AS sort_at'
        ])
//            ->select("CASE 1 WHEN condition1满足条件 THEN 1 ELSE 0 END")
            ->from(PdRequirement::tableName().' info')
            //需求来源
            ->leftJoin(BsPubdata::tableName().' bp1','bp1.bsp_id='.'info.pdq_source_type')
            //开发类型
            ->leftJoin(BsPubdata::tableName().' bp2','bp2.bsp_id='.'info.develop_type')
            //开发中心
            ->leftJoin(HrOrganization::tableName().' organization','organization.organization_code='.'info.develop_center')
            //开发部门
            ->leftJoin(HrOrganization::tableName().' organization1','organization1.organization_code='.'info.develop_department')
            //商品大类
            ->leftJoin(BsCategory::tableName().' commodity','commodity.category_id='.'info.commodity')
            //商品经理人
            ->leftJoin(HrStaff::tableName().' staff','staff.staff_id='.'info.product_manager')
            //提报人
            ->leftJoin(HrStaff::tableName().' staff1','staff1.staff_id='.'info.offer_staff')

            ->where(['and',['in','info.company_id',BsCompany::getIdsArr($params['PdRequirementSearch']['companyId'])]])
            ->andWhere(["!=", "pdq_status", self::STATUS_DELETE])
            ->groupBy('info.pdq_id')
            ->orderBy(['sort_at'=>SORT_DESC]);


        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'pdq_source_type' => $this->pdq_source_type,
            'develop_center' => $this->develop_center,
            'develop_department' => $this->develop_department,
            'pdq_status' => $this->pdq_status,
        ]);
        $query->andFilterWhere(['like', "pdq_code", $this->pdq_code]);
        if ($this->startDate && !$this->endDate) {
            $query->andFilterWhere([">=", "offer_date", $this->startDate]);
        }
        if ($this->endDate && !$this->startDate) {
            $query->andFilterWhere(["<=", "offer_date", date("Y-m-d H:i:s", strtotime($this->endDate . '+1 day'))]);
        }
        if ($this->endDate && $this->startDate) {
            $query->andFilterWhere(["between", "offer_date", $this->startDate, date("Y-m-d H:i:s", strtotime($this->endDate . '+1 day'))]);
        }

        return $dataProvider;
    }
}