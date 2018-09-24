<?php

namespace app\modules\ptdt\models\search;

use app\modules\ptdt\models\show\PdFirmReportProductShow;
use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\PdFirmReportProduct;

/**
 * PdFirmReportProductSearch represents the model behind the search form about `app\modules\ptdt\models\PdFirmReportProduct`.
 */
class PdFirmReportProductSearch extends PdFirmReportProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pfrd_id', 'pfrc_id', 'pfr_id', 'pdh_id', 'firm_id', 'pdt_no'], 'integer'],
            [['product_unit', 'product_name', 'product_size', 'product_brand', 'delivery_terms', 'payment_terms', 'currency_type', 'product_price', 'price_max', 'price_min', 'price_range', 'price_average', 'profit_margin', 'product_type_1', 'product_type_2', 'product_type_3', 'product_type_4', 'product_type_5', 'product_type_6', 'demand_id', 'product_requirement', 'product_process_requirement', 'product_quality_requirement', 'enviroment_requirement', 'use_device', 'finishing_process', 'process_requirements', 'pfrc_quantity', 'pfrc_price', 'pfrc_remark', 'product_level'], 'safe'],
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
            $query = PdFirmReportProductShow::find();
        }else{
            $query = PdFirmReportProductShow::find()->joinWith('product')->where(['pd_firm_report_child.pfrc_id'=>$params['id']]);
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

        return $dataProvider;
    }
}
