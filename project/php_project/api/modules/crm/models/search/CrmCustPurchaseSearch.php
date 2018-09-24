<?php

namespace app\modules\crm\models\search;

use app\modules\crm\models\show\CrmCustPurchaseShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmCustPurchase;

/**
 * CrmCustPurchaseSearch represents the model behind the search form about `app\modules\crm\models\CrmCustPurchase`.
 */
class CrmCustPurchaseSearch extends CrmCustPurchase
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cpurch_id', 'cust_id', 'create_by', 'update_by'], 'integer'],
            [['category_id', 'pdt_no', 'itemname', 'pruchasetype', 'status', 'description', 'create_at', 'update_at'], 'safe'],
            [['purchaseqty', 'pruchasecost'], 'number'],
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

        $query = CrmCustPurchaseShow::find()->where(['!=','status',self::STATUS_DELETE])->andWhere(['cust_id'=>$id]);

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
            'cpurch_id' => $this->cpurch_id,
            'cust_id' => $this->cust_id,
            'purchaseqty' => $this->purchaseqty,
            'pruchasecost' => $this->pruchasecost,
            'create_by' => $this->create_by,
            'create_at' => $this->create_at,
            'update_by' => $this->update_by,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'category_id', $this->category_id])
            ->andFilterWhere(['like', 'pdt_no', $this->pdt_no])
            ->andFilterWhere(['like', 'itemname', $this->itemname])
            ->andFilterWhere(['like', 'pruchasetype', $this->pruchasetype])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
