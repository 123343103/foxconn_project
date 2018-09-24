<?php

namespace app\modules\crm\models\search;

use app\modules\crm\models\show\CrmCustProjectsShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmCustProjects;

/**
 * CrmCustProjectsSearch represents the model behind the search form about `app\modules\crm\models\CrmCustProjects`.
 */
class CrmCustProjectsSearch extends CrmCustProjects
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pro_id', 'cust_id'], 'integer'],
            [['pro_code', 'pro_sname', 'pro_child', 'pro_issue', 'pro_schedule', 'pro_close', 'create_by', 'create_at', 'update_by', 'update_at'], 'safe'],
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

        $query = CrmCustProjectsShow::find()->where(['cust_id'=>$id]);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageParam'=>"page",
                'pageSizeParam'=>'rows'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        return $dataProvider;
    }
}
