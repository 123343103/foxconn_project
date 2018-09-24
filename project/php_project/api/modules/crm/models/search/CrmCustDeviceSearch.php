<?php

namespace app\modules\crm\models\search;

use app\modules\crm\models\show\CrmCustDeviceShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmCustDevice;

/**
 * CrmCustDeviceSearch represents the model behind the search form about `app\modules\crm\models\CrmCustDevice`.
 */
class CrmCustDeviceSearch extends CrmCustDevice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['custd_id', 'cust_id', 'nqty', 'status', 'create_by', 'update_by'], 'integer'],
            [['type', 'code', 'sname', 'brand', 'description', 'create_at', 'update_at'], 'safe'],
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
    public function search($params, $id)
    {

        $query = CrmCustDeviceShow::find()->where(['!=', 'status', self::STATUS_DELETE])->andWhere(['cust_id' => $id]);

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
            'custd_id' => $this->custd_id,
            'cust_id' => $this->cust_id,
            'nqty' => $this->nqty,
            'status' => $this->status,
            'create_by' => $this->create_by,
            'create_at' => $this->create_at,
            'update_by' => $this->update_by,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'sname', $this->sname])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
