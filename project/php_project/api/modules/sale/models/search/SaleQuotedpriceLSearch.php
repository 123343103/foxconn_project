<?php

namespace app\modules\sale\models\search;

use app\modules\sale\models\show\SaleQuotedpriceLShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the ActiveQuery class for [[\app\modules\sale\models\SaleCostType]].
 *
 * @see \app\modules\sale\models\SaleCostType
 */
class SaleQuotedpriceLSearch extends SaleQuotedpriceLShow
{
    public function rules()
    {
        return [
//            [['scost_id','create_by','update_by'], 'integer'],
//            [['scost_code', 'scost_sname'], 'string', 'max' => 20],
//            [['scost_status'], 'string', 'max' => 2],
//            [['scost_remark','scost_description', 'scost_vdef1', 'scost_vdef2'], 'string', 'max' => 120],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)   //搜索方法
    {
        $query = SaleQuotedpriceLShow::find()->select([
            'sapl_id',
            'pdt_id',
//            'whs_id',
            // 下单数量
            'sapl_quantity',
            'uprice_ntax_o',
            'uprice_tax_o',
            'tprice_ntax_o',
            'tprice_tax_o',
            // 运费
            'freight',
            'request_date',
            'consignment_date',
            'sapl_remark'
        ])->where(['saph_id' => $params['id']]);
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);

        return $dataProvider;
    }
}
