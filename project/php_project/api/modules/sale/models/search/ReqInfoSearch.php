<?php

namespace app\modules\sale\models\search;

use app\classes\Trans;
use app\modules\common\models\BsAddress;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsCurrency;
use app\modules\common\models\BsDistrict;
use app\modules\crm\models\CrmCreditApply;
use app\modules\ptdt\models\BsPartno;
use app\modules\ptdt\models\BsProduct;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmCustomerApply;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmEmployee;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\FpPrice;
use app\modules\sale\models\ReqInfo;
use app\modules\sale\models\ReqPay;
use app\modules\sale\models\SaleCustrequireH;
use app\modules\sale\models\SaleCustrequireL;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * This is the ActiveQuery class for [[\app\modules\sale\models\SaleCostType]].
 *
 * @see \app\modules\sale\models\SaleCostType
 */
class ReqInfoSearch extends ReqInfo
{
    public $cust_sname;
    public $applyno;
    public $ccpich_personid;
    public $start_date;
    public $end_date;
    public $pdt_name;
    public $pdt_no;
    public $pac_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['saph_type', 'saph_code', 'cust_sname', 'applyno', 'start_date', 'end_date', 'saph_status', 'pac_id'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * index首页客户需求单条件搜索
     * @param $params
     * @return ActiveDataProvider
     */
    public function requireSearch($params)
    {
        $query = (new Query())
            ->select([
                'odh.req_id',
                // 订单编号
                'odh.saph_code',
                // 订单状态
                // 'odh.saph_status',
                '(CASE odh.saph_status '
                . 'WHEN ' . ReqInfo::STATUS_CREATE . ' THEN "待报价" '
                . 'WHEN ' . ReqInfo::STATUS_QUOTED . ' THEN "已转报价" '
                . 'WHEN ' . ReqInfo::STATUS_CANCEL . ' THEN "已取消" '
                . 'ELSE "" END) as status',
                // 下单时间
                'odh.nw_date saph_date',
                // 客户名称
                'cust.cust_sname',
                // 客户代码
                'cust.cust_code',
                // 交易法人
                'bc.company_name',
                // 订单来源
                // 'odh.origin_hid',
                'pub2.bsp_svalue order_from',
                // 订单类型
                // 'odh.saph_type',
                'bt.business_value as order_type',
                // 付款方式
//                'bp.pac_sname',
//                'pub3.bsp_svalue cur_code2',
                '(CASE WHEN pub3.bsp_svalue is not null THEN pub3.bsp_svalue ELSE bp.pac_sname END) as pac_sname',
                'odh.pac_id',
                'odh.tax_freight bill_freight',
                'odh.prd_org_amount bill_oamount',
                // 币别
                'cur.bsp_svalue cur_code',
                // 客户经理人
                'hs1.staff_name as cust_manager',//销售员
            ])
            ->from(['odh' => 'oms.req_info'])
            ->leftJoin('erp.crm_bs_customer_info cust', 'cust.cust_id=odh.cust_id')
//            ->leftJoin('erp.crm_employee employee', 'employee.staff_id=odh.nwer')// 客户经理人
            ->leftJoin('erp.hr_staff hs1', 'hs1.staff_id=odh.nwer')// 客户经理人名称
            ->leftJoin('erp.bs_business_type bt', 'bt.business_type_id=odh.saph_type')
            ->leftJoin('erp.bs_company bc', 'bc.company_id=odh.corporate')
            ->leftJoin('erp.bs_payment bp', 'bp.pac_id=odh.pac_id')// 付款方式
            ->leftJoin('erp.bs_pubdata cur', 'cur.bsp_id=odh.cur_id')
            ->leftJoin('erp.bs_pubdata pub', 'pub.bsp_id=cust.invoice_type')
            ->leftJoin('erp.bs_pubdata pub2', 'pub2.bsp_id=odh.origin_hid')// 订单来源
            ->leftJoin('erp.bs_pubdata pub3', 'pub3.bsp_id=odh.pay_type')// 订单来源
            ->groupBy("odh.req_id")
            ->orderBy(['odh.nw_date' => SORT_DESC]);
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
        if ($this->pac_id == 'credit-amount') {
            $query->andFilterWhere(['bp.pac_code' => $this->pac_id]);// 付款方式
        } else {
            $query->andFilterWhere(['odh.pay_type' => $this->pac_id]);// 付款方式
        }
//        return $this;
        $trans = new Trans();
        $query->andFilterWhere(['like', 'odh.saph_code', trim($this->saph_code)])// 订单编号
        ->andFilterWhere(['odh.saph_status' => $this->saph_status])// 订单状态
        ->andFilterWhere(['bt.business_type_id' => $this->saph_type])// 订单类型
        ->andFilterWhere(['like', 'cust.cust_code', trim($this->applyno)])// 客户代码
        ->andFilterWhere([
            'or',
            ['like', 'cust.cust_sname', $trans->c2t(trim($this->cust_sname))],
            ['like', 'cust.cust_sname', $trans->t2c(trim($this->cust_sname))]
        ]);
//        return $query->createCommand()->getRawSql();
        if(!empty($this->start_date)){
            $query->andFilterWhere(['>=', 'odh.nw_date', date('Y-m-d H:i:s',strtotime($this->start_date))]);
        }
        if(!empty($this->end_date)){
            $query->andFilterWhere(['<=', 'odh.nw_date', date('Y-m-d H:i:s',strtotime("+1 day",strtotime($this->end_date)))]);
        }
        return $dataProvider;
    }


    /**
     * 选择客户
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchCustomerInfo($params)
    {
        $trans = new Trans();
        $query = (new Query())
            ->select([
                'cust.cust_id',                     // id
                'cust.cust_sname',                  // 全名
                'cust.cust_shortname',              // 简称
                'cust.cust_code applyno',                    // 客户代码
                'cust.cust_contacts',               // 联系人
                'cust.cust_tel2',                   // 联系电话
                'cust.cust_adress',                 // 客户地址 取营业地址
                'cust.cust_tel1',                   // 公司电话
                'bc.company_name',                  // 交易法人 默认取登陆者公司名
                'cu.bsp_id',                      // 交易币别
                'cust.invoice_title',               // 发票抬头
                'bp.bsp_id invoice_type',                    // 发票类型
                'cust.invoice_title_district',      // 发票抬头地址
                'cust.invoice_title_address',        // 发票抬头地址
                'cust.invoice_mail_district',      // 发票寄送地址
                'cust.invoice_mail_address',        // 发票寄送地址
                'cust.cust_creditqty',              // 信用额度
                'CONCAT(bd4.district_name," ",bd3.district_name," ",bd2.district_name," ",bd1.district_name," ",if(cust.cust_readress,cust.cust_readress,"")) AS customerAddress',           // 收货地址(客户带出)
                'ba1.ba_address title_address',     // 发票抬头地址
                'ba2.ba_address send_address',      // 发票寄送地址
            ])
            ->from(['bc' => BsCompany::tableName(), 'cust' => 'erp.crm_bs_customer_info'])
            ->leftJoin(CrmCustomerStatus::tableName() . ' status', "status.customer_id=cust.cust_id")
            ->leftJoin(CrmCustomerApply::tableName() . ' apply', "apply.cust_id=cust.cust_id")
            ->leftJoin(BsPubdata::tableName() . ' cu', "cu.bsp_id=cust.member_curr")
            ->leftJoin(BsPubdata::tableName() . ' bp', "bp.bsp_id=cust.invoice_type")
            ->leftJoin(BsDistrict::tableName() . ' bd1', 'bd1.district_id=cust.cust_district_1')
            ->leftJoin(BsDistrict::tableName() . ' bd2', 'bd1.district_pid=bd2.district_id')
            ->leftJoin(BsDistrict::tableName() . ' bd3', 'bd2.district_pid=bd3.district_id')
            ->leftJoin(BsDistrict::tableName() . ' bd4', 'bd3.district_pid=bd4.district_id')
            ->leftJoin(BsAddress::tableName() . ' ba1', 'ba1.cust_id=cust.cust_id and ba1.ba_type=' . BsAddress::TYPE_TITLE . ' and ba1.ba_status=' . BsAddress::STATUS_DEFAULT)
            ->leftJoin(BsAddress::tableName() . ' ba2', 'ba2.cust_id=cust.cust_id and ba2.ba_type=' . BsAddress::TYPE_SEND . ' and ba2.ba_status=' . BsAddress::STATUS_DEFAULT)
            ->where(['status.sale_status' => CrmCustomerStatus::STATUS_DEFAULT])
            ->andWhere(['apply.status' => CrmCustomerApply::STATUS_FINISH])
            ->andWhere(['in', 'cust.company_id', BsCompany::getIdsArr($params['companyId'])])
            ->andWhere(['bc.company_id' => $params['companyId']])
            ->groupBy('cust.cust_id')
            ->orderBy(['cust.create_at' => SORT_DESC]);

        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
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
        if (!empty($params['searchKeyword'])) {
            $params['searchKeyword'] = trim($params['searchKeyword']);
            $query->andFilterWhere(['or',
                ['like', 'cust.cust_sname', $trans->t2c($params['searchKeyword'])],
                ['like', 'cust.cust_sname', $trans->c2t($params['searchKeyword'])],
                ['like', 'cust.cust_shortname', $trans->t2c($params['searchKeyword'])],
                ['like', 'cust.cust_shortname', $trans->c2t($params['searchKeyword'])],
                ['like', 'cust.cust_code', ($params['searchKeyword'])],
            ]);
        } else {
            $query->andWhere(['is not', 'cust.cust_code', null]);
        }
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    /**
     * 根据料号获取信息
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchPdt($pdt_no)
    {
        $query = (new Query())
            ->select([
                'pdt.pdt_id',
                // 料号
                'pdt.pdt_no',
                // 品名
                'pdt.pdt_name',
                // 仓库
                'wh.wh_name',
                // 商品库存
                'bi.invt_num',
                // 类别
                //'ctg.category_id',
                'ctg.catg_name',
                // 单位
                'cu.unit_name',
                //'pdt.unit',
                // 重量
                'pdt.pdt_weight',
                // 商品单价（未税）
                'bb.BRAND_NAME_CN',
                // 商品总价（未税）
                // 规格
                'ca.ATTR_NAME specification',
                // 材积
                'pdt.pdt_vol',
                // 折扣率
            ])
            ->from(['pdt' => BsProduct::tableName()])
            ->LeftJoin(BsCategory::tableName() . ' ctg', "pdt.bs_category_id=ctg.catg_id")
            ->LeftJoin('wms.bs_wh wh', "wh.wh_id=pdt.pdt_whsid")
            ->LeftJoin('wms.l_bs_invt bi', "bi.pdt_id=pdt.pdt_id")
            ->leftJoin('category_attr ca', 'ca.CATEGORY_ATTR_ID=pdt.tp_spec')
            ->leftJoin('bs_category_unit cu', 'cu.id=pdt.unit')
            ->LeftJoin('bs_brand bb', "bb.brand_id=pdt.brand_id")
            ->where('pdt.pdt_id is not null and ctg.catg_id  is not null')
            ->andWhere(['pdt.pdt_no' => $pdt_no])
            ->one();
        return $query;
    }

    /**
     * 选择地址
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchAddress($params)
    {
        $query = (new Query())
            ->select([
                'ba.ba_id',
                'contact_name',
                'contact_tel',
                'ba.ba_address',
                'ba.ba_type',
                'ba.ba_status'
            ])
            ->from(['ba' => BsAddress::tableName()])
            ->where(['and', ['!=', 'ba_status', BsAddress::STATUS_INVALID], ['cust_id' => $params['custId']]]);

        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 8;
        }
        if (!empty($params['type'])) {
            $query->andWhere(['ba_type' => $params['type']]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
        return $dataProvider;
    }

    /**
     * 客户订单详情
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchOrderH($id)
    {
        $query = (new Query())
            ->select([
                'odh.req_id',
                'odh.saph_code',
                '(CASE odh.saph_status '
                . 'WHEN ' . ReqInfo::STATUS_CREATE . ' THEN "待报价" '
//                . 'WHEN ' . SaleCustrequireH::STATUS_WAIT . ' THEN "待审核" '
//                . 'WHEN ' . SaleCustrequireH::STATUS_CHECKING . ' THEN "审核中" '
                . 'WHEN ' . ReqInfo::STATUS_QUOTED . ' THEN "已转报价" '
                . 'WHEN ' . ReqInfo::STATUS_CANCEL . ' THEN "已取消" '
                . 'ELSE "" END) as saph_status',
                // 下单时间
                'odh.saph_date',
                // 订单来源
                'pub3.bsp_svalue older_from',
                // 订单类型
                'bt.business_value req_type',
                // 预计交货日期
//                'odh.delivery_date',
                'odh.req_tax_amount',
                'odh.prd_org_amount',
                'odh.tax_freight',
                'odh.pac_id',
                'odh.cur_id',
                'odh.pay_type',
                //todo  根据状态取地址
                'odh.ba_id',
                'odh.receipter_Tel',
                'odh.receipter',
                'odh.addr_tel',
                'erp.func_get_paddress(odh.receipt_areaid) receipt',
                'odh.receipt_Address',
                'erp.func_get_paddress(odh.invoice_Title_AreaID) title_dis',
                'odh.invoice_Title_Addr title_addr',
                'erp.func_get_paddress(odh.invoice_AreaID) send_dis',
                'odh.invoice_Address send_addr',
                // 客户名称
                'cust.cust_sname',
                'cust.cust_id',
                // 客户代码
                'cust.cust_code',
                // 联系人
                'odh.cust_contacts',
                // 联系电话
                'cust.cust_tel1',
                // 客户地址  取营业地址
                'cust.cust_adress',
                // 公司电话
                'odh.cust_tel',
                // 交易法人
                'bc.company_name',
                // 交易模式
//                'pub2.bsp_svalue trad_mode',
                'tr.tac_sname',
                // 币别
                'cur.bsp_svalue cur_code',
                // 合同编号
                'odh.contract_no',
                // 发票类型
                'pub1.bsp_svalue invoice_type',
                // 支付类型
                'pub2.bsp_svalue pay_type_name',
                // 发票抬头
                'odh.invoice_title',
                // 订单备注
                'odh.saph_remark',
                'odh.can_reason',

                // 剩余信用额度
                // 付款方式
                'bp.pac_sname',
                // 收款条件
//                'cdt.pat_sname',
                // 部门
//                'ho.organization_name',
                // 销售地区
//                'area.csarea_name',
                // 销售点
//                'store.sts_sname',
                // 制单日期
                'odh.nw_date'
            ])
            ->from(['odh' => 'oms.req_info'])
//            ->leftJoin('oms.req_dt odl', 'odl.req_id=odh.req_id AND sapl_status!=' . SaleCustrequireL::STATUS_DELETE)
            ->leftJoin('crm_bs_customer_info cust', 'cust.cust_id=odh.cust_id')
            ->leftJoin('bs_business_type bt', 'bt.business_type_id=odh.saph_type')
            ->leftJoin('bs_company bc', 'bc.company_id=odh.corporate')
//            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('bs_payment bp', 'bp.pac_id=odh.pac_id')// 付款方式
//            ->leftJoin('bs_pay_condition cdt', 'cdt.pat_id=odh.pat_id')// 付款条件
            ->leftJoin('bs_pubdata cur', 'cur.bsp_id=odh.cur_id')
            ->leftJoin('bs_pubdata pub2', 'pub2.bsp_id=odh.pay_type')// 支付类型
            ->leftJoin('bs_pubdata pub1', 'pub1.bsp_id=odh.invoice_type')// 发票类型
//            ->leftJoin('bs_pubdata pub2', 'pub2.bsp_id=odh.trade_mode')// 交易模式
            ->leftJoin('bs_transaction tr', 'tr.tac_id=odh.trade_mode')// 交易模式
            ->leftJoin('bs_pubdata pub3', 'pub3.bsp_id=odh.origin_hid')// 订单来源
//            ->leftJoin('bs_product pdt', 'pdt.pdt_id=odl.prt_pkid')
//            ->leftJoin('crm_bs_salearea area', 'area.csarea_id=odh.district_id2')
//            ->leftJoin('crm_bs_storesinfo store', 'store.sts_id=odh.sts_id')
            ->where(['odh.req_id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    public function searchOrderL($id)
    {
        $query = (new Query())
            ->select([

                // 商品品名
                'pdt.pdt_name',
                // 下单数量
                'odl.sapl_quantity',
                // 交易单位
                'pdt.unit',
//                'cu.unit_name',
                // 商品单价（含税）
                'odl.uprice_tax_o',
                // 商品总价（含税）
                'odl.tprice_tax_o',
                // 折扣
                'odl.discount',
                // 配送方式
                'bd.bdm_sname',
                // 出仓仓库
                'bw.wh_name',
                // 需求交期
                'odl.request_date',
                // 交期
                'odl.consignment_date',
                // 备注
                'odl.sapl_remark',


            ])
            ->from(['odl' => 'oms.req_dt'])
            ->leftJoin('oms.req_info odh', 'odl.req_id=odh.req_id AND')
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('pdt.bs_partno prt', 'prt.prt_pkid=odl.prt_pkid')
            ->leftJoin('pdt.bs_product pdt', 'pdt.pdt_pkid=prt.pdt_pkid')
            ->leftJoin('wms.bs_deliverymethod bd', 'bd.bdm_id=odl.distribution')
            ->where(['odh.req_id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }

    /**
     * 更新带出订单子表商品信息
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchOrderL2($id)
    {
        $query = (new Query())
            ->select([
                // 商品id
                'bpn.prt_pkid',
                // 商品料号
                'bpn.part_no pdt_no',
                // 商品料号
                'bpn.min_order',
                'bpn.isselftake self_take',
                // 商品料号
                'price.price',
                // 商品名称
                'bpt.pdt_name',
                // 下单数量
                'odl.sapl_quantity',
                'odl.req_dt_id',
                'odl.req_id',
                //包装数量
                'bp.pdt_qty',
                // 商品单价（未税）
                'odl.uprice_ntax_o',
                // 税率
                'odl.cess',
                // 折扣
                'odl.discount',
                // 运输方式
                'odl.transport transport_id',
                //'btr.tran_sname',
                // 配送方式
                'odl.distribution',
//                'bd.bdm_id',
                //'bd.bdm_sname',
                // 运费
                'odl.freight',
                // 出仓仓库
                'odl.whs_id',
                //'bw.wh_id',
                //'bw.wh_name',
                // 需求交期
                'odl.request_date',
                // 交期
                'odl.consignment_date',
                // 备注
                'odl.sapl_remark',
                // 商品品名
//                'pdt.pdt_name',
                // 商品单价（含税）
                'odl.uprice_tax_o',
                // 商品总价（未税）
                'odl.tprice_ntax_o',
                // 商品总价（含税）
                'odl.tprice_tax_o',
                'odl.tax_freight',
                // 规格
//                'ca.ATTR_NAME as specification',
                // 类别
//                'ctg.category_sname',
//                'func_get_pcategory(ctg.category_id) as ctg_pname',
                // 单位id
//                'pdt.unit',
                // 单位名称
//                'cu.unit_name',
                //'cu.unit_name',
                // 重量
                'odl.suttle',
                // 库存
//                'bi.invt_num',
            ])
            ->from(['odl' => 'oms.req_dt'])
            ->leftJoin('oms.req_info odh', 'odl.req_id=odh.req_id')
            ->leftJoin('pdt.bs_partno bpn', 'bpn.prt_pkid=odl.prt_pkid')
            ->leftJoin('pdt.bs_price price', 'odl.prt_pkid=price.prt_pkid and odh.cur_id=price.currency')
            ->leftJoin('pdt.bs_product bpt', 'bpt.pdt_pkid=bpn.pdt_pkid')
            ->leftJoin('pdt.bs_pack bp', 'bp.prt_pkid=bpn.prt_pkid')
//            ->leftJoin('select odl.sapl_quantity')
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
//            ->andWhere('bp.pck_type = 2')
            ->andwhere(['odl.req_id' => $id])
            ->groupBy("req_dt_id");
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }

    function getCreditPay($id)
    {
        $stag = ReqPay::find()->where(["req_id" => $id])->one();
        if (!empty($stag["stag_times"])) { //分期
            $query = (new Query())
                ->select([
                    'pay.stag_cost',
                    'pay.stag_times',
                    'pay.stag_date',

                ])
                ->from(['pay' => 'oms.req_pay'])
//            ->andWhere('bp.pck_type = 2')
                ->andwhere(['pay.req_id' => $id])
                ->groupBy("stag_times");
        } else if (!empty($stag["stag_type"])) {
            $query = (new Query())
                ->select([
                    // 商品id
                    'pay.stag_cost',
                    'pay.stag_type',
                    'pay.stag_times',
                    'pay.stag_date',
                    'type.business_value credit_name',
                    'limit.surplus_limit',
                    'limit.approval_limit',

                ])
                ->from(['pay' => 'oms.req_pay'])
                ->leftJoin('oms.req_info odh', 'pay.req_id=odh.req_id')
                ->leftJoin('erp.crm_credit_apply apply', 'apply.cust_id=odh.cust_id')
                ->leftJoin('erp.crm_credit_limit limit', 'apply.credit_id=limit.credit_id and limit.credit_type=pay.stag_type')
                ->leftJoin('erp.bs_business_type type', 'type.business_type_id=limit.credit_type')
//            ->andWhere('bp.pck_type = 2')
                ->andwhere(['pay.req_id' => $id])
                ->groupBy("stag_type,stag_times");
        } else {
            return null;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider->getModels();
    }
}
