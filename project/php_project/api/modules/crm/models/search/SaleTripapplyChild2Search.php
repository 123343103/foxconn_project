<?php

namespace app\modules\crm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\SaleTripapplyChild2;

/**
 * SaleTripapplyChild2Search represents the model behind the search form about `app\modules\crm\models\SaleTripapplyChild2`.
 */
class SaleTripapplyChild2Search extends SaleTripapplyChild2
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stal_id', 'stah_id'], 'integer'],
            [['stah_bdt', 'stah_place', 'stah_edt', 'stah_arrpalce', 'stah_transportation', 'stah_ocdescription', 'stah_remark'], 'safe'],
            [['stah_transcost', 'stah_foodcost', 'stah_staycost', 'stah_othercost', 'stah_notmeatcost', 'stah_cost1', 'stah_cost2', 'stah_cost3', 'stah_cost4'], 'number'],
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
        $query = SaleTripapplyChild2::find();

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
            'stah_bdt' => $this->stah_bdt,
            'stah_edt' => $this->stah_edt,
            'stah_transcost' => $this->stah_transcost,
            'stah_foodcost' => $this->stah_foodcost,
            'stah_staycost' => $this->stah_staycost,
            'stah_othercost' => $this->stah_othercost,
            'stah_notmeatcost' => $this->stah_notmeatcost,
            'stah_cost1' => $this->stah_cost1,
            'stah_cost2' => $this->stah_cost2,
            'stah_cost3' => $this->stah_cost3,
            'stah_cost4' => $this->stah_cost4,
        ]);

        $query->andFilterWhere(['like', 'stah_place', $this->stah_place])
            ->andFilterWhere(['like', 'stah_arrpalce', $this->stah_arrpalce])
            ->andFilterWhere(['like', 'stah_transportation', $this->stah_transportation])
            ->andFilterWhere(['like', 'stah_ocdescription', $this->stah_ocdescription])
            ->andFilterWhere(['like', 'stah_remark', $this->stah_remark]);

        return $dataProvider;
    }
}
