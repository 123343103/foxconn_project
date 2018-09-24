<?php

namespace app\modules\crm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\SaleTripapplyChild;

/**
 * SaleTripapplyChildSearch represents the model behind the search form about `app\modules\crm\models\SaleTripapplyChild`.
 */
class SaleTripapplyChildSearch extends SaleTripapplyChild
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stal_id', 'stah_id', 'stcl_id'], 'integer'],
            [['stcl_count', 'stcl_dateqty', 'stcl_plan_tripcostqty', 'stcl_tripcostqty', 'stcl_diffqty', 'stcl_realtripcostqty'], 'number'],
            [['stcl_description', 'stcl_diffdescription', 'stcl_remark'], 'safe'],
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
        $query = SaleTripapplyChild::find();

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
            'stal_id' => $this->stal_id,
            'stah_id' => $this->stah_id,
            'stcl_id' => $this->stcl_id,
            'stcl_count' => $this->stcl_count,
            'stcl_dateqty' => $this->stcl_dateqty,
            'stcl_plan_tripcostqty' => $this->stcl_plan_tripcostqty,
            'stcl_tripcostqty' => $this->stcl_tripcostqty,
            'stcl_diffqty' => $this->stcl_diffqty,
            'stcl_realtripcostqty' => $this->stcl_realtripcostqty,
        ]);

        $query->andFilterWhere(['like', 'stcl_description', $this->stcl_description])
            ->andFilterWhere(['like', 'stcl_diffdescription', $this->stcl_diffdescription])
            ->andFilterWhere(['like', 'stcl_remark', $this->stcl_remark]);

        return $dataProvider;
    }
}
