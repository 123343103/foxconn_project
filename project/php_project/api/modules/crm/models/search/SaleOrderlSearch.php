<?php

namespace app\modules\crm\models\search;

use app\modules\crm\models\show\SaleOrderlShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sale\models\SaleOrderl;

/**
 * SaleOrderlSearch represents the model behind the search form about `app\modules\crm\models\SaleOrderl`.
 */
class SaleOrderlSearch extends SaleOrderl
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sol_id', 'soh_id', 'pdt_id', 'comp_pdtid', 'category_id', 'brandid', 'whs_id', 'origin_hid', 'origin_lid'], 'integer'],
            [['quantity', 'ntax_uprice', 'tax_uprice', 'cess', 'uprice_ntax_o', 'uprice_tax_o', 'uprice_ntax_c', 'uprice_tax_c', 'tprice_ntax_o', 'tprice_tax_o', 'tprice_ntax_c', 'tprice_tax_c', 'freight', 'discount', 'out_quantity', 'invoice_quantity', 'recede_quantity', 'pur_quantity', 'suttle', 'gross_weight'], 'number'],
            [['is_largess', 'consignment_date', 'pack_type', 'sol_status', 'sol_remark'], 'safe'],
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

        $query = SaleOrderlShow::find()->joinWith('saleOrderh')->andWhere(['sale_orderh.cust_id'=>$id]);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query
        ]);
        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }


        return $dataProvider;
    }
}
