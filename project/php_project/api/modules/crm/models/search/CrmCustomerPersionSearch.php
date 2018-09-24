<?php

namespace app\modules\crm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmCustomerPersion;
use app\modules\crm\models\show\CrmCustomerPersionShow;

/**
 * CrmCustconetionPersionSearch represents the model behind the search form about `app\modules\crm\models\CrmCustconetionPersion`.
 */
class CrmCustomerPersionSearch extends CrmCustomerPersion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ccper_id', 'cust_id', 'ccper_age'], 'integer'],
            [['ccper_name', 'ccper_sex', 'ccper_birthday', 'ccper_birthplace', 'ccper_deparment', 'ccper_post', 'ccper_tel', 'ccper_fax', 'ccper_mobile', 'ccper_mail', 'ccper_wechat', 'ccper_qq', 'ccper_hobby', 'ccper_relationship', 'ccper_isshareholder', 'ccper_ispost', 'ccper_ismain', 'ccper_status', 'ccper_remark'], 'safe'],
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

        $query = CrmCustomerPersionShow::find()->where(['and',['cust_id'=>$id],['=','ccper_status',CrmCustomerPersion::STATUS_DEFAULT]]);

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
            'ccper_id' => $this->ccper_id,
            'cust_id' => $this->cust_id,
            'ccper_birthday' => $this->ccper_birthday,
            'ccper_age' => $this->ccper_age,
        ]);

        $query->andFilterWhere(['like', 'ccper_name', $this->ccper_name])
            ->andFilterWhere(['like', 'ccper_sex', $this->ccper_sex])
            ->andFilterWhere(['like', 'ccper_birthplace', $this->ccper_birthplace])
            ->andFilterWhere(['like', 'ccper_deparment', $this->ccper_deparment])
            ->andFilterWhere(['like', 'ccper_post', $this->ccper_post])
            ->andFilterWhere(['like', 'ccper_tel', $this->ccper_tel])
            ->andFilterWhere(['like', 'ccper_fax', $this->ccper_fax])
            ->andFilterWhere(['like', 'ccper_mobile', $this->ccper_mobile])
            ->andFilterWhere(['like', 'ccper_mail', $this->ccper_mail])
            ->andFilterWhere(['like', 'ccper_wechat', $this->ccper_wechat])
            ->andFilterWhere(['like', 'ccper_qq', $this->ccper_qq])
            ->andFilterWhere(['like', 'ccper_hobby', $this->ccper_hobby])
            ->andFilterWhere(['like', 'ccper_relationship', $this->ccper_relationship])
            ->andFilterWhere(['like', 'ccper_isshareholder', $this->ccper_isshareholder])
            ->andFilterWhere(['like', 'ccper_ispost', $this->ccper_ispost])
            ->andFilterWhere(['like', 'ccper_ismain', $this->ccper_ismain])
            ->andFilterWhere(['like', 'ccper_status', $this->ccper_status])
            ->andFilterWhere(['like', 'ccper_remark', $this->ccper_remark]);

        return $dataProvider;
    }
}
