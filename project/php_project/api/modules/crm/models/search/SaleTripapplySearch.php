<?php

namespace app\modules\crm\models\search;

use app\modules\crm\models\show\SaleTripapplyShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\SaleTripapply;

/**
 * SaleTripapplySearch represents the model behind the search form about `app\modules\crm\models\SaleTripapply`.
 */
class SaleTripapplySearch extends SaleTripapply
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stah_id', 'scost_id', 'stah_applyer', 'position_id', 'organization_id', 'stah_mil', 'cust_id', 'stah_partner1', 'stah_partner2', 'stah_partner3', 'stah_agenter', 'stah_nocarprove', 'stah_sender', 'creater', 'editor','stah_district'], 'integer'],
            [['stah_code', 'stah_description', 'stah_date', 'stah_costcode', 'stah_place', 'stah_btime', 'stah_etime', 'stah_workdescription', 'stah_isflag', 'stah_flag', 'stah_iscar', 'stah_status', 'stah_remark', 'stah_senddate', 'stah_checkdate', 'cdate', 'edate'], 'safe'],
            [['stah_costcount'], 'number'],
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
    public function search($id)
    {
        if($id==null){
            $query = SaleTripapplyShow::find();
        }else{
            $query = SaleTripapplyShow::find()->where(['cust_id'=>$id]);
        }

//        if (isset($params['rows'])) {
//            $pageSize = $params['rows'];
//        } else {
//            $pageSize = 5;
//        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'pagination' => [
//                'pageSize' => $pageSize,
//            ],
        ]);
//        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        return $dataProvider;
    }
}
