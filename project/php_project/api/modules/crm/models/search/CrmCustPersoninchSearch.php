<?php

namespace app\modules\crm\models\search;

use app\modules\crm\models\show\CrmCustPersoninchShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmCustPersoninch;

/**
 * CrmCustPersoninchSearch represents the model behind the search form about `app\modules\crm\models\CrmCustPersoninch`.
 */
class CrmCustPersoninchSearch extends CrmCustPersoninch
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ccpich_id', 'cust_id', 'csarea_id', 'sts_id'], 'integer'],
            [['ccpich_personid', 'ccpich_personid2', 'ccpich_date', 'ccpich_status', 'ccpich_remark'], 'safe'],
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

        $query = CrmCustPersoninchShow::find()->where(['cust_id'=>$id,'ccpich_stype'=>1]);
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

            return $dataProvider;
        }


        return $dataProvider;
    }
}
