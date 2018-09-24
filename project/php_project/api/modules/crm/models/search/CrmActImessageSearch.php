<?php

namespace app\modules\crm\models\search;

use app\modules\crm\models\show\CrmActImessageShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmActImessage;

/**
 * CrmActImessageSearch represents the model behind the search form about `app\modules\crm\models\CrmActImessage`.
 */
class CrmActImessageSearch extends CrmActImessage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imesg_id', 'cust_id'], 'integer'],
            [['imesg_type', 'imesg_sentman', 'imesg_sentdate', 'imesg_subject', 'imesg_notes', 'imesg_status', 'imesg_remark'], 'safe'],
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
        $query = CrmActImessageShow::find()->where(['cust_id'=>$params['id']])->andWhere(['!=','imesg_status',self::STATUS_DEL]);

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
        $query->orderBy("imesg_sentdate desc");
//        $dataProvider = new ActiveDataProvider([
//            'query'=>$query
//        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
