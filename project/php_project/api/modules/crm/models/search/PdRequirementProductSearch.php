<?php

namespace app\modules\crm\models\search;

use app\modules\crm\models\show\PdRequirementProductShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\PdRequirementProduct;

/**
 * PdRequirementProductSearch represents the model behind the search form about `app\modules\ptdt\models\PdRequirementProduct`.
 */
class PdRequirementProductSearch extends PdRequirementProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'requirement_id', 'product_index', 'product_level_id', 'product_main_type_id', 'product_type_id', 'product_status', 'quantity'], 'integer'],
            [['product_name', 'product_size', 'product_requirement', 'product_process_requirement', 'product_quality_requirement', 'other_des', 'product_brand', 'material', 'product_unit', 'enviroment_requirement', 'use_performance_requirement', 'use_machine', 'craft_requirement', 'work_process', 'vdef1', 'vdef2', 'vdef3', 'vdef4', 'vdef5'], 'safe'],
            [['price'], 'number'],
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
        if($id==null){
            $query = PdRequirementProductShow::find();
        }else{
            $query = PdRequirementProductShow::find()->joinWith('requirement')->andWhere(['cust_id'=>$id]);
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
            return $dataProvider;
        }

        return $dataProvider;
    }
}
