<?php

namespace app\modules\sale\models\search;

use app\modules\sale\models\SaleCustrequireL;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * This is the ActiveQuery class for [[\app\modules\sale\models\SaleCostType]].
 *
 * @see \app\modules\sale\models\SaleCostType
 */
class SaleCustrequireLSearch extends SaleCustrequireL
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
     * 通过id获取商品信息
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = (new Query())
            ->select([
                // 料号
                'pdt.pdt_no',
                // 商品品名
                'pdt.pdt_name',
                // 规格
//                'ca.ATTR_NAME as specification',
                // 下单数量
                'odl.sapl_quantity',
                // 单位
//                'pdt.unit',
                'bp.bsp_svalue',
                // todo 商品定价
                // 商品单价（未税）
                'odl.uprice_ntax_o',
                // 商品单价（含税）
                'odl.uprice_tax_o',
                // 配送方式
                'bd.bdm_sname',
                // 出仓仓库
                'bw.wh_name',
                // 运输方式
                'btr.tran_sname',
                // 商品总价（未税）
                'odl.tprice_ntax_o',
                // 商品总价（含税）
                'odl.tprice_tax_o',
                // 税率
                'odl.cess',
                // 折扣率
                'odl.discount',
                // todo 折扣后金额
                // 运费
                'odl.freight',
                // 需求交期
                'odl.request_date',
                // 交期
                'odl.consignment_date',
                // 备注
                'odl.sapl_remark',
            ])
            ->from(['odl' => 'oms.sale_custrequire_l'])
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('wms.l_bs_invt bi', 'bi.pdt_id=odl.pdt_id')// 库存表
            ->leftJoin('pdt.bs_product pdt', 'pdt.pdt_PKID=odl.pdt_id') // 商品
//            ->leftJoin('category_attr ca', 'ca.CATEGORY_ATTR_ID=pdt.tp_spec') // 规格
            ->leftJoin('pdt.bs_category ctg', 'ctg.catg_id=pdt.catg_id')
            ->leftJoin('erp.bs_pubdata bp', 'bp.bsp_id=pdt.unit')
            ->leftJoin('wms.bs_transport btr', 'btr.tran_id=odl.transport')
            ->leftJoin('wms.bs_deliverymethod bd', 'bd.bdm_id=odl.distribution')
            ->where(['odl.saph_id' => $params['id']]);
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            if (isset($params['export'])) {
                $pageSize = false;
            } else {
                $pageSize = 10;
            }
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

//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }
}
