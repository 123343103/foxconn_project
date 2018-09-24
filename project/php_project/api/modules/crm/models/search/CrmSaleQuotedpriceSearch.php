<?php

namespace app\modules\crm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmSaleQuotedprice;

/**
 * CrmSaleQuotedpriceSearch represents the model behind the search form about `app\modules\crm\models\CrmSaleQuotedprice`.
 */
class CrmSaleQuotedpriceSearch extends CrmSaleQuotedprice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['saph_id', 'corp_id', 'cust_id', 'pos_id', 'applicant', 'branch_district', 'currency', 'pay_type', 'checker', 'status', 'creator', 'modified_by'], 'integer'],
            [['bs_id', 'saph_category', 'saph_no', 'saph_date', 'branch_sale_area', 'payment_terms', 'trading_terms', 'valid_date', 'description', 'remark', 'check_date', 'creatdate', 'modified_date'], 'safe'],
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
        $query = CrmSaleQuotedprice::find();

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
        $query->andFilterWhere([
            'saph_id' => $this->saph_id,
            'corp_id' => $this->corp_id,
            'saph_date' => $this->saph_date,
            'cust_id' => $this->cust_id,
            'pos_id' => $this->pos_id,
            'applicant' => $this->applicant,
            'branch_district' => $this->branch_district,
            'currency' => $this->currency,
            'pay_type' => $this->pay_type,
            'valid_date' => $this->valid_date,
            'checker' => $this->checker,
            'check_date' => $this->check_date,
            'status' => $this->status,
            'creator' => $this->creator,
            'creatdate' => $this->creatdate,
            'modified_by' => $this->modified_by,
            'modified_date' => $this->modified_date,
        ]);

        $query->andFilterWhere(['like', 'bs_id', $this->bs_id])
            ->andFilterWhere(['like', 'saph_category', $this->saph_category])
            ->andFilterWhere(['like', 'saph_no', $this->saph_no])
            ->andFilterWhere(['like', 'branch_sale_area', $this->branch_sale_area])
            ->andFilterWhere(['like', 'payment_terms', $this->payment_terms])
            ->andFilterWhere(['like', 'trading_terms', $this->trading_terms])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
