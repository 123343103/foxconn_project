<?php

namespace app\modules\ptdt\models\search;

use app\modules\ptdt\models\show\PdRequirementProductShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\PdRequirementProduct;

/**
 * PdRequirementProductSearch represents the model behind the search form about `app\modules\ptdt\models\PdRequirementProduct`.
 */
class PdRequirementProductSearch extends PdRequirementProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'requirement_id', 'product_index', 'product_level_id', 'product_main_type_id', 'product_type_id', 'product_status', 'quantity'], 'integer'],
            [['product_name', 'product_size', 'product_requirement', 'product_process_requirement', 'product_quality_requirement', 'other_des', 'product_brand', 'material', 'product_unit', 'enviroment_requirement', 'use_performance_requirement', 'use_machine', 'craft_requirement', 'work_process', 'vdef1', 'vdef2', 'vdef3', 'vdef4', 'vdef5'], 'safe'],
            [['price'], 'number'],
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
        $query = PdRequirementProductShow::find();

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
        $query->joinWith('requirement');
        $query->andFilterWhere([
            'product_id' => $this->product_id,
            'requirement_id' => $this->requirement_id,
            'product_index' => $this->product_index,
            'product_level_id' => $this->product_level_id,
            'product_main_type_id' => $this->product_main_type_id,
            'product_type_id' => $this->product_type_id,
            'product_status' => $this->product_status,
            'quantity' => $this->quantity,
            'price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'product_name', $this->product_name])
              ->andFilterWhere([
                  '=','pd_requirement.pdq_status', 10,
              ])
            ->andFilterWhere(['like', 'product_size', $this->product_size])
            ->andFilterWhere(['like', 'product_requirement', $this->product_requirement])
            ->andFilterWhere(['like', 'product_process_requirement', $this->product_process_requirement])
            ->andFilterWhere(['like', 'product_quality_requirement', $this->product_quality_requirement])
            ->andFilterWhere(['like', 'other_des', $this->other_des])
            ->andFilterWhere(['like', 'product_brand', $this->product_brand])
            ->andFilterWhere(['like', 'material', $this->material])
            ->andFilterWhere(['like', 'product_unit', $this->product_unit])
            ->andFilterWhere(['like', 'enviroment_requirement', $this->enviroment_requirement])
            ->andFilterWhere(['like', 'use_performance_requirement', $this->use_performance_requirement])
            ->andFilterWhere(['like', 'use_machine', $this->use_machine])
            ->andFilterWhere(['like', 'craft_requirement', $this->craft_requirement])
            ->andFilterWhere(['like', 'work_process', $this->work_process])
            ->andFilterWhere(['like', 'vdef1', $this->vdef1])
            ->andFilterWhere(['like', 'vdef2', $this->vdef2])
            ->andFilterWhere(['like', 'vdef3', $this->vdef3])
            ->andFilterWhere(['like', 'vdef4', $this->vdef4])
            ->andFilterWhere(['like', 'vdef5', $this->vdef5]);

        return $dataProvider;
    }
}
