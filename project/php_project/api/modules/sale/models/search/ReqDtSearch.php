<?php

namespace app\modules\sale\models\search;

use app\classes\Trans;
use app\modules\crm\models\CrmEmployee;
use app\modules\hr\models\HrStaff;
use app\modules\sale\models\ReqDt;
use app\modules\sale\models\ReqInfo;
use app\modules\sale\models\SaleCustrequireL;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * This is the ActiveQuery class for [[\app\modules\sale\models\SaleCostType]].
 *
 * @see \app\modules\sale\models\SaleCostType
 */
class ReqDtSearch extends ReqDt
{
    public $saph_code;
    public $saph_status;
    public $saph_type;
    public $pdt_name;
    public $pdt_no;
    public $corporate;
    public $cust_sname;
    public $applyno;
    public $start_date;
    public $end_date;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['saph_type', 'saph_code', 'pdt_name', 'pdt_no', 'corporate', 'applyno', 'start_date', 'end_date', 'cust_sname', 'applyno', 'start_date', 'end_date', 'saph_status'], 'safe'],
        ];
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
                'partno.part_no pdt_no',
                // 商品品名
                'pdt.pdt_name',
                // 规格
//                'ca.ATTR_NAME as specification',
                // 下单数量
                'odl.sapl_quantity',
                // 单位
//                'pdt.unit',
                'bp.bsp_svalue unit_name',
                //  商品定价
                'price.price fixed_price',
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
                // 折扣后金额
                // 运费
                'odl.tax_freight',
                // 需求交期
                'odl.request_date',
                // 交期
                'odl.consignment_date',
                // 备注
                'odl.sapl_remark',
                'partno.tp_spec',
            ])
            ->from(['odl' => 'oms.req_dt'])
            ->leftJoin('oms.req_info odh', 'odh.req_id=odl.req_id')
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
//            ->leftJoin('wms.l_bs_invt bi', 'bi.pdt_id=odl.prt_pkid')// 库存表
            ->leftJoin('pdt.bs_partno partno', 'partno.prt_pkid=odl.prt_pkid')// 商品
            ->leftJoin('pdt.bs_product pdt', 'pdt.pdt_pkid=partno.pdt_pkid')// 商品
            ->leftJoin('pdt.bs_price price', 'odl.prt_pkid=price.prt_pkid and odh.cur_id=price.currency')//价格
//            ->leftJoin('category_attr ca', 'ca.CATEGORY_ATTR_ID=pdt.tp_spec') // 规格
            ->leftJoin('pdt.bs_category ctg', 'ctg.catg_id=pdt.catg_id')
            ->leftJoin('erp.bs_pubdata bp', 'bp.bsp_id=pdt.unit')
            ->leftJoin('wms.bs_transport btr', 'btr.tran_code=odl.transport')
            ->leftJoin('wms.bs_deliverymethod bd', 'bd.bdm_id=odl.distribution')
            ->where((
                (
                    'ISNULL(price.maxqty)'
                    AND 'odl.sapl_quantity >= price.minqty'
                )
                OR (
                    '! ISNULL(price.maxqty)'
                    AND 'odl.sapl_quantity >= price.minqty'
                    AND 'odl.sapl_quantity <= price.maxqty'
                )
            ))->groupBy(['req_dt_id']);
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            if (isset($params['export']) || isset($params['view'])) {
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
        if (!empty($params['id'])) {
            $query->andWhere(['odl.req_id' => $params['id']]);
        }

//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    /**
     * 需求单明细列表
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchList($params)
    {
        $query = (new Query())
            ->select([
                // 料号
                'product.pdt_no',
                'partno.part_no',
                'info.saph_code',
                'info.req_id saph_id',
                'info.nw_date',
                'info.cust_contacts',
                'info.cust_tel',
                '(CASE info.saph_status '
                . 'WHEN ' . ReqInfo::STATUS_CREATE . ' THEN "待报价" '
                . 'WHEN ' . ReqInfo::STATUS_QUOTED . ' THEN "已转报价" '
                . 'WHEN ' . ReqInfo::STATUS_CANCEL . ' THEN "已取消" '
                . 'ELSE "" END) as saph_status',
                // 商品品名
                'product.pdt_name',
                // 规格
//                'ca.ATTR_NAME as specification',
                // 下单数量
                'FORMAT(odl.sapl_quantity,2) sapl_quantity',
                'FORMAT(odl.discount,2) price_off',
                'FORMAT((odl.sapl_quantity*odl.uprice_tax_o),2) total_off',
                'info.receipter receipter_name',
                'info.receipter_Tel receipter_tel',
                // 单位
//                'pdt.unit',
                'bp.bsp_svalue unit_name',
                //交易法人
                'company.company_name',
                //客户名称
                'customer.cust_sname',
                //客户代码
                'customer.cust_code',
                //  商品定价
                'price.price fixed_price',
                // 商品单价（未税）
                '(CASE price.price '
                . 'WHEN 0 THEN "无定价" '
                . 'WHEN -1 THEN "面议" '
                . 'ELSE FORMAT((price.price*0.83),5) END) as uprice_ntax_o',
//                'FORMAT((price.price*0.83),5) uprice_ntax_o',
                // 商品单价（含税）
                '(CASE price.price '
                . 'WHEN 0 THEN "无定价" '
                . 'WHEN -1 THEN "面议" '
                . 'ELSE FORMAT((price.price),5) END) as uprice_tax_o',
//                'FORMAT(price.price,5) uprice_tax_o',
                'FORMAT(odl.uprice_tax_o,5) uprice_tax_o2',
                // 配送方式
                'bd.bdm_sname',
                // 出仓仓库
                'bw.wh_name',
                // 运输方式
                'btr.tran_sname',
                // 商品总价（未税）
                'FORMAT(odl.tprice_ntax_o,2) tprice_ntax_o',
                // 商品总价（含税）
                'FORMAT(odl.tprice_tax_o,2) tprice_tax_o',
                // 税率
                'FORMAT(odl.cess,2) cess',
                // 折扣率
                'FORMAT(odl.discount,2) discount',
                // todo 折扣后金额
                // 运费
                'FORMAT(odl.tax_freight,2) tax_freight',
                'odl.req_dt_id',
                // 需求交期
                'odl.request_date',
                // 交期
                'odl.consignment_date',
                // 备注
                'odl.sapl_remark',
                // 订单类型
                'bbt.business_type_desc saph_type',
                // 币别
                'curr.bsp_svalue curr_code',
                'pub2.bsp_svalue origin_from',
                // 发票类型
                'invoice.bsp_svalue',
                // 币别
//                'payment.pac_sname',
                '(CASE WHEN pub3.bsp_svalue is not null THEN pub3.bsp_svalue ELSE payment.pac_sname END) as pac_sname',
                // 币别
                'hr.staff_name sell_delegate',
                '(CASE epl1.isrule '
                . 'WHEN 0 THEN hs2.staff_name '
                . 'ELSE hr.staff_name END) as pdtManager',
                // 币别
                'pdt.func_get_pcategory(ctg.catg_id) ctg_pname',
            ])
            ->from(['odl' => 'oms.req_dt'])
            ->leftJoin('oms.req_info info', 'info.req_id=odl.req_id')
            ->leftJoin('pdt.bs_partno partno', 'partno.prt_pkid=odl.prt_pkid')
            ->leftJoin('pdt.bs_product product', 'partno.pdt_pkid=product.pdt_pkid')
            ->leftJoin('erp.bs_payment payment', 'payment.pac_id=info.pac_id')
            ->leftJoin('erp.bs_pubdata pub3', 'pub3.bsp_id=info.pay_type')//支付方式
            ->leftJoin('erp.bs_company company', 'company.company_id=info.corporate and company_status=10')//交易法人
            ->leftJoin('erp.crm_bs_customer_info customer', 'customer.cust_id=info.cust_id')//交易法人
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
//            ->leftJoin('wms.l_bs_invt bi', 'bi.pdt_id=odl.prt_pkid')// 库存表
            ->leftJoin('pdt.bs_price price', 'odl.prt_pkid=price.prt_pkid and info.cur_id=price.currency')//价格
//            ->leftJoin('category_attr ca', 'ca.CATEGORY_ATTR_ID=pdt.tp_spec') // 规格
            ->leftJoin('pdt.bs_category ctg', 'ctg.catg_id=product.catg_id')
            ->leftJoin('erp.bs_pubdata bp', 'bp.bsp_id=product.unit')
            ->leftJoin('erp.bs_pubdata curr', 'curr.bsp_id=info.cur_id')
            ->leftJoin('erp.bs_pubdata invoice', 'invoice.bsp_id=info.invoice_type')
            ->leftJoin('erp.bs_pubdata pub2', 'pub2.bsp_id=info.origin_hid')// 订单来源
//            ->leftJoin('erp.crm_employee staff', 'staff.staff_id=info.sell_delegate')
            ->leftJoin('erp.hr_staff hr', 'hr.staff_id=info.nwer AND hr.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 销售代表名称
            ->leftJoin('erp.crm_employee epl1', 'epl1.staff_code=hr.staff_code AND sale_status!=' . CrmEmployee::SALE_STATUS_DEL)// 销售代表
            ->leftJoin('erp.crm_employee epl2', 'epl2.staff_id=epl1.leader_id')// 商品经理人
            ->leftJoin('erp.hr_staff hs2', 'hs2.staff_code=epl2.staff_code AND hs2.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 商品经理人名称
            ->leftJoin('erp.bs_business_type bbt', 'bbt.business_type_id=info.saph_type')
            ->leftJoin('wms.bs_transport btr', 'btr.tran_code=odl.transport')
            ->leftJoin('wms.bs_deliverymethod bd', 'bd.bdm_id=odl.distribution')
            ->where((
                (
                    'ISNULL(price.maxqty)'
                    AND 'odl.sapl_quantity >= price.minqty'
                )
                OR (
                    '! ISNULL(price.maxqty)'
                    AND 'odl.sapl_quantity >= price.minqty'
                    AND 'odl.sapl_quantity <= price.maxqty'
                )
            ))
            ->orderBy(['info.nw_date' => SORT_DESC])->groupBy(['odl.req_dt_id']);
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
        $trans = new Trans();
        $query->andFilterWhere(['like', 'info.saph_code', trim($this->saph_code)])// 订单编号
        ->andFilterWhere(['info.saph_status' => trim($this->saph_status)])// 订单状态
        ->andFilterWhere(['info.saph_type' => trim($this->saph_type)])// 订单类型
        ->andFilterWhere(['like', 'customer.cust_code', trim($this->applyno)])// 客户代码

        ->andFilterWhere(['like', 'product.pdt_name', trim($this->pdt_name)])//
        ->andFilterWhere(['like', 'partno.part_no', trim($this->pdt_no)])//
        ->andFilterWhere(['like', 'info.corporate', trim($this->corporate)])//

        ->andFilterWhere([
            'or',
            ['like', 'customer.cust_sname', $trans->c2t(trim($this->cust_sname))],
            ['like', 'customer.cust_sname', $trans->t2c(trim($this->cust_sname))]
        ]);
//        return $query->createCommand()->getRawSql();
        if(!empty($this->start_date)){
            $query->andFilterWhere(['>=', 'info.nw_date', date('Y-m-d H:i:s',strtotime($this->start_date))]);
        }
        if(!empty($this->end_date)){
            $query->andFilterWhere(['<=', 'info.nw_date', date('Y-m-d H:i:s',strtotime("+1 day",strtotime($this->end_date)))]);
        }
        return $dataProvider;
    }
}
