<?php

namespace app\modules\common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\common\models\BsPubdata;

/**
 * BsPubdataSearch represents the model behind the search form about `app\modules\common\models\BsPubdata`.
 */
class BsPubdataSearch extends BsPubdata
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bsp_id', 'bsp_sname', 'bsp_stype', 'bsp_svalue', 'create_at', 'update_at'], 'safe'],
            [['bsp_status', 'create_by', 'update_by'], 'integer'],
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
        $query = BsPubdata::find()->groupBy('bsp_stype')->orderBy('bsp_id');
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

        //模糊查询
        $query->andFilterWhere(['like', 'bsp_sname', $this->bsp_sname]);

        return $dataProvider;
    }
}
