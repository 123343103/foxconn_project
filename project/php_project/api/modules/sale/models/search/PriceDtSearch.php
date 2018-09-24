<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/12/6
 * Time: 16:00
 */
namespace app\modules\sale\models\search;

use app\classes\Trans;
use app\modules\sale\models\PriceDt;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class PriceDtSearch extends PriceDt
{

    public $audit_id;       //报价单状态
    public $price_type;     //报价单类型
    public $corporate;      //交易法人
    public $saph_code;      //需求单号
    public $price_no;      //报价单号
    public $cust_sname;      //客户名称/代码
    public $part_no;      //料号
    public $pdt_name;      //品名
    public $start_date;      //开始时间
    public $end_date;      //结束时间

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_date', 'consignment_date', 'sapl_quantity', 'uprice_ntax_o', 'uprice_tax_o', 'uprice_ntax_c', 'uprice_tax_c', 'tprice_ntax_o', 'tprice_tax_o', 'tprice_ntax_c', 'tprice_tax_c', 'cess', 'discount', 'tax_freight', 'freight', 'suttle', 'gross_weight', 'pack_type', 'sapl_remark', 'price_id', 'prt_pkid', 'is_gift', 'whs_id', 'distribution', 'transport', 'sapl_status', 'saph_code', 'audit_id', 'price_type', 'corporate', 'price_no', 'cust_sname', 'part_no', 'pdt_name', 'start_date', 'end_date'], 'safe'],

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
                'partno.part_no',
                'pdt.pdt_no',
                // 商品品名
                'pdt.pdt_name',
                // 规格
//                'ca.ATTR_NAME as specification',
                // 下单数量
                'ROUND(odl.sapl_quantity,2) sapl_quantity',
                // 单位
//                'pdt.unit',
                'bp.bsp_svalue unit_name',
                //  商品定价
                'ROUND(price.price,5) fixed_price',
                // 商品单价（未税）
                'ROUND(odl.uprice_ntax_o,5) uprice_ntax_o',
                // 商品单价（含税）
                'ROUND(odl.uprice_tax_o,5) uprice_tax_o',
                // 配送方式
                'bd.bdm_sname',
                // 出仓仓库
                'bw.wh_name',
                // 运输方式
                'btr.tran_sname',
                // 商品总价（未税）
                'ROUND(odl.tprice_ntax_o,2) tprice_ntax_o',
                // 商品总价（含税）
                'ROUND(odl.tprice_tax_o,2) tprice_tax_o',
                // 税率
                'ROUND(odl.cess,2) cess',
                // 折扣率
                'ROUND(odl.discount,2) discount',
                // todo 折扣后金额
                // 运费
                'ROUND(odl.tax_freight,2) tax_freight',
                // 需求交期
                'odl.request_date',
                // 交期
                'odl.consignment_date',
                // 备注
                'odl.sapl_remark',
                'partno.tp_spec',
            ])
            ->from(['odl' => 'oms.price_dt'])
            ->leftJoin('oms.price_info odh', 'odh.price_id=odl.price_id')
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('pdt.bs_partno partno', 'partno.prt_pkid=odl.prt_pkid')// 商品
            ->leftJoin('pdt.bs_product pdt', 'pdt.pdt_pkid=partno.pdt_pkid')// 商品
            ->leftJoin('pdt.bs_price price', 'odl.prt_pkid=price.prt_pkid and odh.cur_id = price.currency')//价格
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
            ))->groupBy(['price_dt_id']);
        if (!empty($params['id'])) {
            $query->andWhere(['odl.price_id' => $params['id']]);
        }
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
        return $dataProvider;
    }


    /**
     * 报价单明细列表
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchList($params)
    {
        $query = (new Query())
            ->select([
                'info.price_id',                    //报价单ID
                'info.price_no',                    //报价单号
                'info.saph_code',                   //需求单号
                'info.audit_id',                    //报价单状态
                'ast.audit_name price_status',      //报价单状态(列表)
                'info.price_date saph_date',        //下单时间
                'cust.cust_sname',                  //客户名称
                'cust.cust_code',                   //客户代码
                'cust.cust_contacts',               //联系人
                'cust.cust_tel2',                   //联系方式
                'bp_3.bsp_svalue cur_code',                     //交易币别
                'cp.company_name corporate',        //交易法人
                'bp_1.bsp_svalue origin',           //订单来源
                'bbt.business_value saph_type',     //订单类型
                'bp_2.bsp_svalue invoice',          //发票类型
//                'bpm.pac_sname pac_sname',          //付款方式
                '(CASE WHEN pub3.bsp_svalue is not null THEN pub3.bsp_svalue ELSE bpm.pac_sname END) as pac_sname',
                'info.receipter addr_man',        //收货人
                'info.receipter_Tel addr_tel',        //联系电话
                'hs.staff_name manager',              //销售员
                'pdt.pdt_name',                     //商品
                'dt.sapl_quantity',                 //下单数量
                'bp_4.bsp_svalue unit_name',        //交易单位
                'ROUND(dt.uprice_ntax_o,2) uprice_ntax_o',                 //商品单价(未税)
                '(CASE price.price WHEN "-1" THEN "面议" ELSE price.price END) AS uprice_tax_o',                  //商品定价（含税）
                'ROUND(dt.uprice_tax_o,5) fixed_price',          //商品单价（含税）
                'ROUND(dt.tprice_ntax_o,2) tprice_ntax_o',                 //商品总价(未税)
                'ROUND(dt.tprice_tax_o,2) tprice_tax_o',                  //商品总价（含税）
                'ROUND(dt.cess,2) cess',                          // 税率
                'ROUND(dt.discount,2) discount',                      // 折扣率
//                'sum(dt.sapl_quantity * dt.uprice_tax_o) as dis_count_price',
                'bd.bdm_sname',                     // 配送方式
                'bw.wh_name',                       // 出仓仓库
                'btr.tran_sname',                   //运输方式
                'ROUND(dt.tax_freight,2) tax_freight',                   // 运费
                'dt.request_date',                  // 需求交期
                'dt.consignment_date',              // 交期
                'dt.sapl_remark',                   // 备注
                'partno.part_no',                   //料号
//                'partno.tp_spec',
            ])
            ->from(['dt' => 'oms.price_dt'])
            ->leftJoin('oms.price_info info', 'info.price_id = dt.price_id')
            ->leftJoin('erp.crm_bs_customer_info cust', 'cust.cust_code= info.cust_code')
            ->leftJoin('erp.bs_company cp', 'cp.company_id = info.corporate')
            ->leftJoin('erp.bs_pubdata bp_1', 'bp_1.bsp_id = info.origin_hid')
            ->leftJoin('erp.bs_pubdata bp_2', 'bp_2.bsp_id = info.invoice_type')
            ->leftJoin('erp.bs_business_type bbt', 'bbt.business_type_id = info.price_type')
            ->leftJoin('erp.bs_pubdata bp_3', 'bp_3.bsp_id = info.cur_id')
            ->leftJoin('erp.bs_payment bpm', 'bpm.pac_id = info.pac_id')
            ->leftJoin('erp.bs_pubdata pub3', 'pub3.bsp_id=info.pay_type')//支付方式
            ->leftJoin('erp.hr_staff hs', 'hs.staff_id = info.nwer')
            ->leftJoin('erp.audit_state ast', 'ast.audit_id = info.audit_id')
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=dt.whs_id')
            ->leftJoin('pdt.bs_partno partno', 'partno.prt_pkid=dt.prt_pkid')
            ->leftJoin('pdt.bs_product pdt', 'pdt.pdt_pkid=partno.pdt_pkid')
            ->leftJoin('pdt.bs_price price', 'price.prt_pkid = dt.prt_pkid and info.cur_id=price.currency')
            ->leftJoin('erp.bs_pubdata bp_4', 'bp_4.bsp_id=pdt.unit')
            ->leftJoin('wms.bs_transport btr', 'btr.tran_code=dt.transport')
            ->leftJoin('wms.bs_deliverymethod bd', 'bd.bdm_id=dt.distribution')
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
            ->orderBy('info.nw_date desc')
            ->groupBy(['price_dt_id'])
        ;
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
        $query->andFilterWhere([
            'info.audit_id' => $this->audit_id,     //报价单状态
            'info.price_type' => $this->price_type, //订单类型
            'info.corporate' => $this->corporate,   //交易法人
        ]);
        $query->andFilterWhere(['like', 'info.saph_code', $this->saph_code])// 关联需求单号
        ->andFilterWhere(['like', 'info.price_no', $this->price_no])//报价单号
        ->andFilterWhere([
            'or',
            ['like', 'cust.cust_sname', $trans->c2t($this->cust_sname)],
            ['like', 'cust.cust_sname', $trans->t2c($this->cust_sname)],
            ['like', 'cust.cust_code', $trans->t2c($this->cust_sname)],
            ['like', 'cust.cust_code', $trans->t2c($this->cust_sname)],
        ])// 客户名称
        ->andFilterWhere([
            'or',
            ['like', 'partno.part_no', $trans->c2t($this->part_no)],
            ['like', 'partno.part_no', $trans->t2c($this->part_no)],
        ])// 料号
        ->andFilterWhere([
            'or',
            ['like', 'pdt.pdt_name', $trans->c2t($this->pdt_name)],
            ['like', 'pdt.pdt_name', $trans->t2c($this->pdt_name)],
        ]);  //品名
        if(!empty($this->start_date)){
            $query->andFilterWhere(['>=', 'info.price_date', date('Y-m-d H:i:s',strtotime($this->start_date))]);
        }
        if(!empty($this->end_date)){
            $query->andFilterWhere(['<=', 'info.price_date', date('Y-m-d H:i:s',strtotime("+1 day",strtotime($this->end_date)))]);
        }
        return $dataProvider;
    }
}