<?php

namespace app\modules\ptdt\models\search;

use app\modules\ptdt\models\show\PdFirmReportChildShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\PdFirmReportChild;

/**
 * PdFirmReportChildSearch represents the model behind the search form about `app\modules\ptdt\models\PdFirmReportChild`.
 */
class PdFirmReportChildSearch extends PdFirmReportChild
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pfrc_id', 'pfr_id', 'pdna_id', 'pdaa_id', 'rece_id'], 'integer'],
            [['pfrc_code', 'pfrc_date', 'pfrc_time', 'pfrc_receid', 'pfrc_location', 'pfrc_person', 'vacc_id', 'process_descript', 'negotiate_concluse', 'trace_matter', 'next_notice', 'pfrc_status', 'negotiate_others', 'remark', 'attachment', 'attachment_name'], 'safe'],
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
        if($params['id'] == null){
            $query = PdFirmReportChildShow::find();
        }else{
            $query = PdFirmReportChildShow::find()->where(['!=','pfrc_status',self::STATUS_DEL])->andWhere(['pfr_id'=>$params['id']]);
        }
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
            'pfrc_id' => $this->pfrc_id,
            'pfr_id' => $this->pfr_id,
            'pdna_id' => $this->pdna_id,
            'pdaa_id' => $this->pdaa_id,
            'pfrc_date' => $this->pfrc_date,
            'pfrc_time' => $this->pfrc_time,
            'rece_id' => $this->rece_id,
        ]);

        $query->andFilterWhere(['like', 'pfrc_code', $this->pfrc_code])
            ->andFilterWhere(['like', 'pfrc_receid', $this->pfrc_receid])
            ->andFilterWhere(['like', 'pfrc_location', $this->pfrc_location])
            ->andFilterWhere(['like', 'pfrc_person', $this->pfrc_person])
            ->andFilterWhere(['like', 'vacc_id', $this->vacc_id])
            ->andFilterWhere(['like', 'process_descript', $this->process_descript])
            ->andFilterWhere(['like', 'negotiate_concluse', $this->negotiate_concluse])
            ->andFilterWhere(['like', 'trace_matter', $this->trace_matter])
            ->andFilterWhere(['like', 'next_notice', $this->next_notice])
            ->andFilterWhere(['like', 'pfrc_status', $this->pfrc_status])
            ->andFilterWhere(['like', 'negotiate_others', $this->negotiate_others])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'attachment', $this->attachment])
            ->andFilterWhere(['like', 'attachment_name', $this->attachment_name]);

        return $dataProvider;
    }
}
