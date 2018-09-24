<?php

namespace app\modules\hr\models\Search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\BsQstInvst;

/**
 * StaffSearch represents the model behind the search form about `app\modules\hr\models\Staff`.
 */
class QuestionSurveySearch extends BsQstInvst
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invst_id', 'invst_nums'], 'integer'],
            [[  'invst_dpt', 'invst_state', 'invst_subj','invst_type', 'OPPER'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = BsQstInvst::find();

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
            'invst_dpt' => $this->invst_dpt,
            'invst_subj' => $this->invst_subj,
            'invst_type' => $this->invst_type,
        ]);

        $query->andFilterWhere(['like', 'invst_dpt', $this->invst_dpt])
            ->andFilterWhere(['like', 'invst_subj', $this->invst_subj])
            ->andFilterWhere(['like', 'invst_type', $this->invst_type]);
        return $dataProvider;
    }

    public function export($params)
    {
        $query = BsQstInvst::find();
        $this->load($params);
        if (!$this->validate()) {
            return $query;
        }

        $query
            ->andFilterWhere(['like', 'invst_dpt', $this->invst_dpt])
            ->andFilterWhere(['like', 'invst_subj', $this->invst_subj])
            ->andFilterWhere(['like', 'invst_type', $this->invst_type]);
        return $query;
    }
}
