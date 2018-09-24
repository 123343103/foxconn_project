<?php

namespace app\modules\ptdt\models\search;

use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\show\PdSupplierShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\PdSupplier;

/**
 * PdSupplierSearch represents the model behind the search form about `app\modules\ptdt\models\PdSupplier`.
 */
class PdSupplierSearch extends PdSupplier
{
    /**
     * @inheritdoc
     */
    public  $startDate;
    public  $endDate;
    public  $createBy;
    public  $firmType;


//    public function rules()
//    {
//        return [
//            [['supplier_id', 'firm_id', 'supplier_status', 'supplier_source', 'supplier_type', 'supplier_position', 'supplier_issupplier', 'supplier_pddepid', 'supplier_productperson', 'supplier_report_id', 'supplier_agentstype', 'supplier_pdtype', 'supplier_transacttype', 'supplier_agentstype2', 'supplier_agentslevel', 'supplier_agents_position', 'supplier_authorize_area', 'supplier_salarea', 'supplier_annual_turnover', 'supplier_trade_currency', 'supplier_trade_condition', 'supplier_pay_condition', 'supplier_pre_annual_sales', 'supplier_pre_annual_profit', 'supplier_patent_num', 'supplier_experimence', 'create_by', 'update_by'], 'safe'],
//            [['supplier_code', 'supplier_sname', 'supplier_shortname', 'supplier_group_sname', 'supplier_ename', 'supplier_eshortname', 'supplier_brand', 'supplier_brand_english', 'supplier_category_id', 'supplier_comptype', 'supplier_scale', 'supplier_compaddress', 'supplier_compprincipal', 'supplier_comptel', 'supplier_compfax', 'supplier_compmail', 'supplier_contaperson', 'supplier_authorize_bdate', 'supplier_authorize_edate', 'supplier_main_product', 'outer_cus_object', 'cus_quality_require', 'supplier_nature', 'supplier_create_date', 'supplier_web_site', 'supplier_factory_area', 'supplier_remark1', 'supplier_remark2', 'create_at', 'update_at', 'vdef1', 'vdef2', 'vdef3', 'vdef4', 'vdef5'], 'safe'],
//            [['startDate','endDate','firmType','createBy'],'safe']
//        ];
//    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PdSupplierShow::find()->where(["!=", "supplier_status", self::STATUS_DELETE]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
//        $query->joinWith('staff');
        $query->andFilterWhere([
//            'supplier_id' => $this->supplier_id,
//            'firm_id' => $this->firm_id,
            'supplier_status' => $this->supplier_status,
            'supplier_source' => $this->supplier_source,
            'supplier_type' => $this->supplier_type,
            'supplier_position' => $this->supplier_position,
            'supplier_issupplier' => $this->supplier_issupplier,
            'supplier_pddepid' => $this->supplier_pddepid,
            'supplier_productperson' => $this->supplier_productperson,
            'supplier_report_id' => $this->supplier_report_id,
            'supplier_agentstype' => $this->supplier_agentstype,
            'supplier_pdtype' => $this->supplier_pdtype,
            'supplier_transacttype' => $this->supplier_transacttype,
            'supplier_agentstype2' => $this->supplier_agentstype2,
            'supplier_agentslevel' => $this->supplier_agentslevel,
            'supplier_agents_position' => $this->supplier_agents_position,
            'supplier_authorize_area' => $this->supplier_authorize_area,
            'supplier_salarea' => $this->supplier_salarea,
            'supplier_authorize_bdate' => $this->supplier_authorize_bdate,
            'supplier_authorize_edate' => $this->supplier_authorize_edate,
            'supplier_annual_turnover' => $this->supplier_annual_turnover,
            'supplier_trade_currency' => $this->supplier_trade_currency,
            'supplier_trade_condition' => $this->supplier_trade_condition,
            'supplier_pay_condition' => $this->supplier_pay_condition,
            'supplier_pre_annual_sales' => $this->supplier_pre_annual_sales,
            'supplier_pre_annual_profit' => $this->supplier_pre_annual_profit,
            'supplier_create_date' => $this->supplier_create_date,
            'supplier_patent_num' => $this->supplier_patent_num,
            'supplier_experimence' => $this->supplier_experimence,
            'create_by' => $this->create_by,
//            'staff' => $this->create_at,
//            'update_by' => $this->update_by,
//            'update_at' => $this->update_at,
        ]);

        $query->joinWith('staff');
        if(isset($params['PdSupplierSearch']['createBy'])){
            $searchPara = $params['PdSupplierSearch']['createBy'];
            $query->orFilterWhere(['like',HrStaff::tableName().".staff_code",$searchPara]);
            $query->orFilterWhere(['like',HrStaff::tableName().".staff_name",$searchPara]);
        };
        $query->andFilterWhere(['like', 'supplier_sname', $this->supplier_sname]);
//            ->andFilterWhere(['like', 'supplier_code', $this->supplier_code])
//            ->andFilterWhere(['like','staff_code',$this->create_by])
//            ->orFilterWhere(['like','staff_name',$this->create_by])
//        $query->andFilterWhere(['<>', 'supplier_status', PdSupplier::STATUS_DELETE]);
        $query->andFilterWhere(['like','company_id',$params['PdSupplierSearch']['companyId']])
            ->orFilterWhere(['like', 'supplier_shortname', $this->supplier_sname])
            ->andFilterWhere(['<>', 'supplier_status', PdSupplier::STATUS_DELETE])
//            ->andFilterWhere(['like', 'supplier_group_sname', $this->supplier_group_sname])
//            ->andFilterWhere(['like', 'supplier_ename', $this->supplier_ename])
//            ->andFilterWhere(['like', 'supplier_eshortname', $this->supplier_eshortname])
//            ->andFilterWhere(['like', 'supplier_brand', $this->supplier_brand])
//            ->andFilterWhere(['like', 'supplier_brand_english', $this->supplier_brand_english])
            ->andFilterWhere(['like', 'supplier_category_id', $this->supplier_category_id])
            ->andFilterWhere(['like', 'supplier_comptype', $this->supplier_comptype])
            ->andFilterWhere(['like', 'supplier_scale', $this->supplier_scale])
//            ->andFilterWhere(['like', 'supplier_compaddress', $this->supplier_compaddress])
//            ->andFilterWhere(['like', 'supplier_compprincipal', $this->supplier_compprincipal])
//            ->andFilterWhere(['like', 'supplier_comptel', $this->supplier_comptel])
//            ->andFilterWhere(['like', 'supplier_compfax', $this->supplier_compfax])
//            ->andFilterWhere(['like', 'supplier_compmail', $this->supplier_compmail])
//            ->andFilterWhere(['like', 'supplier_contaperson', $this->supplier_contaperson])
//            ->andFilterWhere(['like', 'supplier_main_product', $this->supplier_main_product])
//            ->andFilterWhere(['like', 'outer_cus_object', $this->outer_cus_object])
//            ->andFilterWhere(['like', 'cus_quality_require', $this->cus_quality_require])
//            ->andFilterWhere(['like', 'supplier_nature', $this->supplier_nature])
//            ->andFilterWhere(['like', 'supplier_web_site', $this->supplier_web_site])
//            ->andFilterWhere(['like', 'supplier_factory_area', $this->supplier_factory_area])
//            ->andFilterWhere(['like', 'supplier_remark1', $this->supplier_remark1])
//            ->andFilterWhere(['like', 'supplier_remark2', $this->supplier_remark2])
//            ->andFilterWhere(['like', 'vdef1', $this->vdef1])
//            ->andFilterWhere(['like', 'vdef2', $this->vdef2])
//            ->andFilterWhere(['like', 'vdef3', $this->vdef3])
//            ->andFilterWhere(['like', 'vdef4', $this->vdef4])
//            ->andFilterWhere(['like', 'vdef5', $this->vdef5]);
        ;
        if( $this->startDate && !$this->endDate){
            $query->andFilterWhere([">=","create_at",$this->startDate]);
        }
        if( $this->endDate && !$this->startDate){
            $query->andFilterWhere(["<=","create_at",date("Y-m-d H:i:s",strtotime($this->endDate.'+1 day'))]);
        }
        if( $this->endDate && $this->startDate){
            $query->andFilterWhere(["between","create_at",$this->startDate,date("Y-m-d H:i:s",strtotime($this->endDate.'+1 day'))]);
        }

        return $dataProvider;
    }
}
