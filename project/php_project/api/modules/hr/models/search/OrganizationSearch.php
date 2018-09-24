<?php

namespace app\modules\hr\models\Search;

use app\classes\Trans;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrOrganization;

/**
 * OrganizationSearch represents the model behind the search form about `app\modules\organization\models\Organization`.
 */
class OrganizationSearch extends HrOrganization
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['organization_id', 'organization_pid', 'is_abandoned'], 'integer'],
            [['organization_code', 'organization_name', 'organization_description', 'organization_leader', 'create_date', 'creator', 'organization_level'], 'safe'],
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
        $query = HrOrganization::find();

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
            'organization_id' => $this->organization_id,
            'organization_pid' => $this->organization_pid,
            'is_abandoned' => $this->is_abandoned,
            'create_date' => $this->create_date,
        ]);

        $query->andFilterWhere(['like', 'organization_code', $this->organization_code])
            ->andFilterWhere(['like', 'organization_name', $this->organization_name])
            ->andFilterWhere(['like', 'organization_description', $this->organization_description])
            ->andFilterWhere(['like', 'organization_leader', $this->organization_leader])
            ->andFilterWhere(['like', 'creator', $this->creator])
            ->andFilterWhere(['like', 'organization_level', $this->organization_level]);

        return $dataProvider;
    }
    public function searchDepart($params)
    {
        $query = HrOrganization::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($params['rows'])?$params['rows']:'10',
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $trans=new Trans();
        if(!empty($params['keyWord'])) {
            $query->andFilterWhere(['or',
                ['like',HrOrganization::tableName().'.organization_code',trim($params['keyWord'])],
                ['like',HrOrganization::tableName().'.organization_name',trim($params['keyWord'])],
                ['like',HrOrganization::tableName().'.organization_name',$trans->c2t(trim($params['keyWord']))],
                ['like',HrOrganization::tableName().'.organization_name',$trans->t2c(trim($params['keyWord']))],
            ]);
        }
        return $dataProvider;
    }
}
