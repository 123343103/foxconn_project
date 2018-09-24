<?php

namespace app\modules\crm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmCustOddsitem;
use app\modules\crm\models\show\CrmCustOddsitemShow;

/**
 * CrmCustOddsitemSearch represents the model behind the search form about `app\modules\crm\models\CrmCustOddsitem`.
 */
class CrmCustOddsitemSearch extends CrmCustOddsitem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['odds_id', 'cust_id', 'category_id', 'pdt_id'], 'integer'],
            [['odds_sname', 'odds_model', 'brand', 'status'], 'safe'],
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
    public function search($params,$id)
    {
        $query = CrmCustOddsitemShow::find()->where(['cust_id'=>$id])->andWhere(['!=','status',self::STATUS_DELETE]);

        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 5;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,

            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'odds_id' => $this->odds_id,
            'cust_id' => $this->cust_id,
            'category_id' => $this->category_id,
            'pdt_id' => $this->pdt_id,
        ]);

        $query->andFilterWhere(['like', 'odds_sname', $this->odds_sname])
            ->andFilterWhere(['like', 'odds_model', $this->odds_model])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
