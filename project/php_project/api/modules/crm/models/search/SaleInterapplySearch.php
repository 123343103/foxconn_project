<?php

namespace app\modules\crm\models\search;

use app\modules\crm\models\show\SaleInterapplyShow;
use app\modules\crm\models\show\SaleTripapplyShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\SaleInterapply;

/**
 * SaleInterapplySearch represents the model behind the search form about `app\modules\crm\models\SaleInterapply`.
 */
class SaleInterapplySearch extends SaleInterapply
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['siah_id', 'scost_id', 'siah_dirstrict', 'siah_appdepid', 'siah_appman', 'siah_objid', 'cur_id', 'siah_peopleqyt', 'siah_sender', 'siah_creator', 'siah_editor'], 'integer'],
            [['siah_code', 'siah_description', 'siah_address', 'siah_appdate', 'siah_objtype', 'siah_shape', 'siah_standard', 'siah_remark', 'siah_status', 'siah_senddate', 'siah_cdate', 'siah_edate'], 'safe'],
            [['siah_cost'], 'number'],
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
        $query = SaleInterapplyShow::find()->where(['siah_objid'=>$id]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageParam'=>"page",
                'pageSizeParam'=>'rows'
            ]
        ]);

//        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }


        return $dataProvider;
    }
}
