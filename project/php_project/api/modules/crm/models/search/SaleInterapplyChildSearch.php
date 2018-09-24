<?php

namespace app\modules\crm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\SaleInterapplyChild;

/**
 * SaleInterapplyChildSearch represents the model behind the search form about `app\modules\crm\models\SaleInterapplyChild`.
 */
class SaleInterapplyChildSearch extends SaleInterapplyChild
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sial_id', 'siah_id'], 'integer'],
            [['sial_date', 'sial_address', 'sial_item', 'sial_description', 'sial_custpeople1', 'sial_custpeople2', 'sial_custpeople3', 'sial_sial_custpeople1post', 'sial_custpeople2post', 'sial_custpeople3post', 'sial_comppeole1', 'sial_comppeole2', 'sial_remark'], 'safe'],
            [['sial_appcost', 'sial_cost'], 'number'],
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
        $query = SaleInterapplyChild::find();

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
            'sial_id' => $this->sial_id,
            'siah_id' => $this->siah_id,
            'sial_date' => $this->sial_date,
            'sial_appcost' => $this->sial_appcost,
            'sial_cost' => $this->sial_cost,
        ]);

        $query->andFilterWhere(['like', 'sial_address', $this->sial_address])
            ->andFilterWhere(['like', 'sial_item', $this->sial_item])
            ->andFilterWhere(['like', 'sial_description', $this->sial_description])
            ->andFilterWhere(['like', 'sial_custpeople1', $this->sial_custpeople1])
            ->andFilterWhere(['like', 'sial_custpeople2', $this->sial_custpeople2])
            ->andFilterWhere(['like', 'sial_custpeople3', $this->sial_custpeople3])
            ->andFilterWhere(['like', 'sial_sial_custpeople1post', $this->sial_sial_custpeople1post])
            ->andFilterWhere(['like', 'sial_custpeople2post', $this->sial_custpeople2post])
            ->andFilterWhere(['like', 'sial_custpeople3post', $this->sial_custpeople3post])
            ->andFilterWhere(['like', 'sial_comppeole1', $this->sial_comppeole1])
            ->andFilterWhere(['like', 'sial_comppeole2', $this->sial_comppeole2])
            ->andFilterWhere(['like', 'sial_remark', $this->sial_remark]);

        return $dataProvider;
    }
}
