<?php

namespace app\modules\sale\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sale\models\OrdRefundDt;
use yii\db\Query;

/**
 * OrdRefundDtSearch represents the model behind the search form about `app\modules\sale\models\OrdRefundDt`.
 */
class OrdRefundDtSearch extends OrdRefundDt
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rfnd_dt_id', 'refund_id', 'sol_id', 'rfnd_type'], 'integer'],
            [['rfnd_nums', 'rfnd_amount'], 'number'],
            [['remarks'], 'safe'],
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
        $query = (new Query())->select([
            'ROUND(dt.rfnd_nums,2) AS rfnd_nums',             //退货数量
            'ROUND(dt.rfnd_amount,2) as rfnd_amount',             //退货金额
            'dt.remarks',             //退货原因
            'pdt.pdt_name',            // 商品品名
            'bpn.part_no',            // 料号
            'bpn.tp_spec',              //规格/型号
            'ROUND(ordt.uprice_tax_o,5) AS uprice_tax_o',        //单价
            'ROUND(ordt.sapl_quantity,2) AS sapl_quantity',        //订单数量
            'bp_1.bsp_svalue unit_name',        //单位
            'bp_2.bsp_svalue rfndType',        //退款类型
        ])->from('oms.ord_refund_dt dt')
//            ->leftJoin('oms.ord_refund refund'.'refund.refund_id = dt.refund_id')
            ->leftJoin('oms.ord_dt ordt','ordt.ord_dt_id = dt.sol_id')
            ->leftJoin('pdt.bs_partno bpn','bpn.prt_pkid = ordt.prt_pkid')
            ->leftJoin('pdt.bs_product pdt', 'pdt.pdt_pkid = bpn.pdt_pkid')
            ->leftJoin('erp.bs_pubdata bp_1', 'bp_1.bsp_id = pdt.unit')
            ->leftJoin('erp.bs_pubdata bp_2', 'bp_2.bsp_id = dt.rfnd_type')
            ->where(['dt.refund_id'=>$params['id']])
        ;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => !empty($params['rows'])?$params['rows']:'10',

            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }


        return $dataProvider;
    }
}
