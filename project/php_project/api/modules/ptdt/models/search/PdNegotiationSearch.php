<?php

namespace app\modules\ptdt\models\search;

use app\classes\Trans;
use app\modules\common\models\BsCompany;
use app\modules\ptdt\models\PdVisitPlan;
use app\modules\ptdt\models\show\PdFirmNegotiationShow;
use app\modules\ptdt\models\show\PdVisitPlanShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\PdNegotiation;

/**
 * NegotiationSearch represents the model behind the search form about `app\modules\ptdt\models\PdNegotiation`.
 */
class PdNegotiationSearch extends PdNegotiation
{
    /**
     * @inheritdoc
     */
    public $name;
    public $type;
    public $isSupplier;
    public $level;
    public $status;
    public function rules()
    {
        return [
            [['pdn_id', 'firm_id', 'vil_id', 'vil_plan_id', 'pdn_verifyter', 'create_by', 'update_by'], 'integer'],
            [['name','type','isSupplier','level','status','pdn_code', 'pdn_date', 'vil_location', 'pdn_status', 'pdn_senddate', 'pdn_verifydate', 'pdn_remark', 'create_at', 'update_at'], 'safe'],
        ];
    }

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
        $query = PdFirmNegotiationShow::find();

        // add conditions that should always apply here
        if(isset($params['rows'])){
            $pageSize=$params['rows'];
        }else{
            $pageSize=5;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>$pageSize,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        //默認排序
        if (!\Yii::$app->request->get('sort')) {
            $query->orderBy("create_at desc");
        }
        // grid filtering conditions
        $query->joinWith('firm');
        $query->andFilterWhere([
            'pdn_id' => $this->pdn_id,
            'pdn_date' => $this->pdn_date,
            'firm_id' => $this->firm_id,
            'vil_id' => $this->vil_id,
            'vil_plan_id' => $this->vil_plan_id,
            'pdn_senddate' => $this->pdn_senddate,
            'pdn_verifydate' => $this->pdn_verifydate,
            'pdn_verifyter' => $this->pdn_verifyter,
            'create_by' => $this->create_by,
            'create_at' => $this->create_at,
            'update_by' => $this->update_by,
            'update_at' => $this->update_at,
        ]);
        $trans = new Trans();
        $query->andFilterWhere([
            'or',
            ['like','pd_firm.firm_sname', $this->name],
            ['like','pd_firm.firm_sname', $trans->c2t($this->name)],
            ['like','pd_firm.firm_sname', $trans->t2c($this->name)],
        ])
        ->andFilterWhere([
            '=','pd_firm.firm_type', $this->type,
        ])
        ->andFilterWhere([
            '=','pd_firm.firm_issupplier', $this->isSupplier,
        ])
        ->andFilterWhere([
            '<>','pdn_status', PdNegotiation::STATUS_DELETE,
        ])
        ->andFilterWhere(['like', 'vil_location', $this->vil_location])
        ->andFilterWhere(['=', 'pdn_status', $this->pdn_status])
        ->andFilterWhere(['like', 'pdn_remark', $this->pdn_remark]);

        return $dataProvider;
    }
    public function searchPlan($params)
    {
        $query=PdVisitPlanShow::find()->where(['and',['pd_visit_plan.firm_id'=>$params['firmId']],['plan_status'=>PdVisitPlan::STATUS_DEFAULT],['pd_visit_plan.purpose'=>100014],['in','pd_visit_plan.company_id',BsCompany::getIdsArr($params['companyId'])]]);
        if(isset($params['rows'])){
            $pageSize=$params['rows'];
        }else{
            $pageSize=10;
        }
        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>$pageSize,
            ],
            'sort'=>[
                'defaultOrder'=>[
                    'create_at'=>SORT_DESC,
                ]
            ],
        ]);
        if(isset($params['searchKeyword'])){
            $query->andFilterWhere(['like','pd_visit_plan.pvp_plancode',$params['searchKeyword']])
                ->orFilterWhere(['like binary','pd_visit_plan.plan_date',$params['searchKeyword']]);//搜索日期时要加上binary
        }
        return $dataProvider;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {

        return [
            'name' => '廠商全称/简称',
            'type' => '类型',
            'isSupplier' => '是否为集团供应商',
            'level' => '分级分类',
            'pdn_status' => '厂商状态',
        ];
    }
}
