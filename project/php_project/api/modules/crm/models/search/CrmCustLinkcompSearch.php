<?php

namespace app\modules\crm\models\search;

use app\modules\crm\models\show\CrmCustLinkcompShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmCustLinkcomp;

/**
 * CrmCustLinkcompSearch represents the model behind the search form about `app\modules\crm\models\CrmCustLinkcomp`.
 */
class CrmCustLinkcompSearch extends CrmCustLinkcomp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['linc_id', 'cust_id', 'linc_district'], 'integer'],
            [['linc_isp', 'linc_code', 'linc_name', 'linc_shortname', 'linc_type', 'linc_date', 'linc_incpeople', 'linc_tel', 'linc_address', 'linc_remark'], 'safe'],
            [['linc_status'], 'number'],
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

        $query = CrmCustLinkcompShow::find()->joinWith('district')->where(['!=','linc_status',self::STATUS_DELETE])->andWhere(['cust_id'=>$id]);

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
            'linc_id' => $this->linc_id,
            'cust_id' => $this->cust_id,
            'linc_date' => $this->linc_date,
            'linc_district' => $this->linc_district,
            'linc_status' => $this->linc_status,
        ]);

        $query->andFilterWhere(['like', 'linc_isp', $this->linc_isp])
            ->andFilterWhere(['like', 'linc_code', $this->linc_code])
            ->andFilterWhere(['like', 'linc_name', $this->linc_name])
            ->andFilterWhere(['like', 'linc_shortname', $this->linc_shortname])
            ->andFilterWhere(['like', 'linc_type', $this->linc_type])
            ->andFilterWhere(['like', 'linc_incpeople', $this->linc_incpeople])
            ->andFilterWhere(['like', 'linc_tel', $this->linc_tel])
            ->andFilterWhere(['like', 'linc_address', $this->linc_address])
            ->andFilterWhere(['like', 'linc_remark', $this->linc_remark]);

        return $dataProvider;
    }
}
