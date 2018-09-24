<?php

namespace app\modules\system\models\search;

use app\modules\system\models\show\VerifyrecordChildShow;
use app\modules\system\models\Verifyrecord;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\system\models\VerifyrecordChild;

/**
 * VerifyrecordChildSearch represents the model behind the search form about `app\modules\system\models\VerifyrecordChild`.
 */
class VerifyrecordChildSearch extends VerifyrecordChild
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vcoc_id', 'vco_id', 'ver_scode', 'ver_acc_id', 'ver_acc_rule', 'acc_code_agent', 'rule_code_agent', 'vcoc_status'], 'integer'],
            [['vcoc_datetime', 'vcoc_remark', 'vcoc_computeip'], 'safe'],
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
        $query = VerifyrecordChildShow::find()->where(['vco_id'=>$id]);

        // add conditions that should always apply here

        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            $pageSize =10;
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
        $query->andFilterWhere([
            'vcoc_id' => $this->vcoc_id,
            'vco_id' => $this->vco_id,
            'ver_scode' => $this->ver_scode,
            'ver_acc_id' => $this->ver_acc_id,
            'ver_acc_rule' => $this->ver_acc_rule,
            'acc_code_agent' => $this->acc_code_agent,
            'rule_code_agent' => $this->rule_code_agent,
            'vcoc_status' => $this->vcoc_status,
            'vcoc_datetime' => $this->vcoc_datetime,
        ]);

        $query->andFilterWhere(['like', 'vcoc_remark', $this->vcoc_remark])
            ->andFilterWhere(['!=', 'vcoc_status', VerifyrecordChild::STATUS_DEFAULT])
            ->andFilterWhere(['!=', 'vcoc_status', VerifyrecordChild::STATUS_OVER])
        ->andFilterWhere(['like', 'vcoc_computeip', $this->vcoc_computeip]);

        return $dataProvider;
    }
}
