<?php

namespace app\modules\crm\models\search;

use app\modules\crm\models\show\CrmCustCustomerShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmCustCustomer;

/**
 * CrmCustCustomerSearch represents the model behind the search form about `app\modules\crm\models\CrmCustCustomer`.
 */
class CrmCustCustomerSearch extends CrmCustCustomer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cc_customerid', 'cust_id', 'create_by', 'update_by'], 'integer'],
            [['cc_customer_name', 'cc_customer_type', 'cc_customer_person', 'cc_customer_mobile', 'cc_customer_tel', 'cc_customer_ratio', 'cc_customer_remark', 'create_at', 'update_at'], 'safe'],
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
        $query = CrmCustCustomerShow::find()->where(['and',['cust_id'=>$id],['=','status',CrmCustCustomer::STATUS_DEFAULT]]);
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            if (isset($params['export'])) {
                $pageSize = false;
            } else {
                $pageSize = 5;
            }
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
            'cc_customerid' => $this->cc_customerid,
            'cust_id' => $this->cust_id,
            'create_by' => $this->create_by,
            'create_at' => $this->create_at,
            'update_by' => $this->update_by,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'cc_customer_name', $this->cc_customer_name])
            ->andFilterWhere(['like', 'cc_customer_type', $this->cc_customer_type])
            ->andFilterWhere(['like', 'cc_customer_person', $this->cc_customer_person])
            ->andFilterWhere(['like', 'cc_customer_mobile', $this->cc_customer_mobile])
            ->andFilterWhere(['like', 'cc_customer_tel', $this->cc_customer_tel])
            ->andFilterWhere(['like', 'cc_customer_ratio', $this->cc_customer_ratio])
            ->andFilterWhere(['like', 'cc_customer_remark', $this->cc_customer_remark]);

        return $dataProvider;
    }
}
