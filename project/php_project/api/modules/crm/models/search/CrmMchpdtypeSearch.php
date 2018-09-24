<?php

namespace app\modules\crm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmMchpdtype;

/**
 * CrmMchpdtypeSearch represents the model behind the search form about `app\modules\crm\models\CrmMchpdtype`.
 */
class CrmMchpdtypeSearch extends CrmMchpdtype
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id'], 'safe'],
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
        $query = self::find()->where(['<>', 'mpdt_status', self::STATUS_DELETE])->select(['*, if(update_at,update_at,create_at) order_time'])->orderBy('order_time DESC, id DESC');
        // add conditions that should always apply here

        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere(['like', 'category_id', $this->category_id]);

        return $dataProvider;
    }

}
