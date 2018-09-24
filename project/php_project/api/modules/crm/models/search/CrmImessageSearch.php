<?php

namespace app\modules\crm\models\search;

use app\modules\crm\models\show\CrmImessageShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmImessage;

/**
 * CrmImessageSearch represents the model behind the search form about `app\modules\crm\models\CrmImessage`.
 */
class CrmImessageSearch extends CrmImessage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imesg_id', 'obj_id', 'cust_id', 'imesg_receiver'], 'integer'],
            [['imesg_type', 'imesg_sentman', 'imesg_sentdate', 'imesg_notes', 'imesg_btime', 'imesg_etime', 'imesg_status', 'imesg_remark'], 'safe'],
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
        $query = CrmImessageShow::find()->where(['cust_id'=>$params['id']])->andWhere(['!=','imesg_status',self::STATUS_DEL])->orderBy('imesg_sentdate DESC');

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
    public function searchReminder($params)
    {
        $query = CrmImessageShow::find()->where(['obj_id'=>$params['id']])->andWhere(['!=','imesg_status',self::STATUS_DEL]);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }
        return $dataProvider;
    }
    public function searchInform($params)
    {
        $query = CrmImessageShow::find()
            ->select([
                "*",
                "date_format(imesg_btime,'%Y-%m-%d') imesg_btime",
                "date_format(imesg_etime,'%Y-%m-%d') imesg_etime"
            ])
            ->where(['imesg_receiver'=>$params['id']])->andWhere(['and',['<=','imesg_btime',date('Y-m-d H:i:s',time())],['>=','imesg_etime',date('Y-m-d H:i:s',time())]])->andWhere(['=','imesg_status',CrmImessage::STATUS_DEFAULT])->orderBy('imesg_btime DESC');
        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            $pageSize =6;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }
        return $dataProvider;
    }
}
