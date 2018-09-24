<?php

namespace app\modules\sale\models\search;

use app\classes\Trans;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmEmployee;
use app\modules\hr\models\HrStaff;
use app\modules\sale\models\OrdInfo;
use app\modules\sale\models\OrdDt;
use app\modules\sale\models\OrdRefund;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * OrdInfoSearch represents the model behind the search form about `app\modules\crm\models\OrdInfo`.
 */
class OrdInfoSearch extends OrdInfo
{
    public $ord_no;
    public $ord_status;
    public $ord_type;
    public $origin_hid;
    public $corporate;
    public $cust_sname;
    public $cust_code;
    public $pdt_no;
    public $pdt_name;
    public $pac_id;
    public $start_date;
    public $end_date;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['ord_id', 'comp_id', 'cust_id', 'pat_id', 'pac_id', 'dec_id', 'cur_id', 'bill_from', 'sell_delegate', 'district_id', 'sell_manager', 'create_by', 'review_by', 'update_by', 'whs_id'], 'integer'],
            [['ord_no', 'ord_status', 'ord_type', 'origin_hid', 'corporate', 'cust_sname', 'cust_code', 'pac_id', 'start_date', 'end_date', 'pdt_name', 'pdt_no'], 'safe'],
//            [['bill_oamount', 'bill_camount'], 'number'],
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
     * 订单查询明细列表
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = (new Query())
            ->select([
                'odh.ord_id',
                // 订单编号
                'odh.ord_no',
                // 订单类型
                'bt.business_value',
                // 订单状态
                '(CASE odh.audit_id '
                . 'WHEN 12 THEN "否" '
                . 'ELSE "否" END) as is_invoice',  //暂借用字段默认否
                // 下单时间
                'odh.ord_date',
                'hr1.staff_name createBy',
                'hr2.staff_name updateBy',
                // 交易法人
                'bc.company_name',
                // 客户名称
                'cust.cust_sname',
                'odh.cust_code',
                // 客户经理人
                'hs.staff_name as custManager',
                'hr1.staff_name seller',
                // 出仓仓库
//                'bw.wh_name',
                // 付款方式
//                'bp.pac_sname',
                '(CASE WHEN pub3.bsp_svalue is not null THEN pub3.bsp_svalue ELSE bp.pac_sname END) as pac_sname',
                // 状态
                'status.os_name saph_status',
                // 发票类型
                'pub.bsp_svalue invoice_type',
                // 运费
                'pub1.bsp_svalue order_from',
                'odh.prd_org_amount bill_oamount',
                'odh.tax_freight bill_freight',
                'pay.yn_pay',
                '(CASE refund.rfnd_status '
                . 'WHEN '.OrdRefund::STATUS_REFUND.' THEN "已退款" '
                . 'WHEN '.OrdRefund::STATUS_IN_REVIEW.' THEN "退款审核中" '
                . 'WHEN '.OrdRefund::STATUS_PASS_REVIEW.' THEN "审核通过" '
                . 'WHEN '.OrdRefund::STATUS_CANCLE_REFUND.' THEN "取消退款" '
                . 'WHEN '.OrdRefund::STATUS_REJECT_REVIEW.' THEN "驳回" '
                . 'ELSE "否" END) as is_rfnd',  //暂借用字段默认否
            ])
            ->from(['odh' => 'oms.ord_info'])
            ->leftJoin('oms.ord_status status', 'status.os_id=odh.os_id')
            ->leftJoin('oms.ord_refund refund', 'refund.ord_no=odh.ord_no')
            ->leftJoin('oms.ord_pay pay', 'pay.ord_id=odh.ord_id')
            ->leftJoin('erp.crm_bs_customer_info cust', 'cust.cust_code=odh.cust_code')
            ->leftJoin('crm_bs_customer_personinch cp', 'cp.cust_id=cust.cust_id')// 客户经理人
            ->leftJoin('erp.hr_staff hs', 'hs.staff_id=cp.ccpich_personid')// 客户经理人名称
            ->leftJoin('erp.bs_business_type bt', 'bt.business_type_id=odh.ord_type')
            ->leftJoin('erp.bs_payment bp', 'bp.pac_id=odh.pac_id')// 付款方式
            ->leftJoin('erp.hr_staff hr1', 'hr1.staff_id=odh.nwer')// 创建人
            ->leftJoin('erp.hr_staff hr2', 'hr2.staff_id=odh.opper')// 修改人
            ->leftJoin('erp.bs_pubdata pub', 'pub.bsp_id=odh.invoice_type')//发票类型
            ->leftJoin('erp.bs_pubdata pub1', 'pub1.bsp_id=odh.origin_hid')//订单来源
            ->leftJoin('erp.bs_pubdata pub3', 'pub3.bsp_id=odh.pay_type')// 订单来源
            ->leftJoin('erp.bs_company bc', 'bc.company_id=odh.corporate')
            ->groupBy("odh.ord_id")
            ->orderBy(['odh.ord_id' => SORT_DESC]);
//            ->where(['!=','saph_status',OrdInfo::STATUS_DELETE]);
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
        $trans = new Trans();
//        return $params['OrdInfoSearch']['business_value'];
        $query->andFilterWhere(['like', 'odh.ord_no', trim($this->ord_no)])// 订单编号
        ->andFilterWhere(['odh.os_id' => $this->ord_status])// 订单状态
        ->andFilterWhere(['odh.origin_hid' => $this->origin_hid])// 订单来源
        ->andFilterWhere(['bt.business_type_id' => $this->ord_type])// 订单类型
        ->andFilterWhere(['like', 'cust.cust_code', trim($this->cust_code)])// 客户代码
        ->andFilterWhere([
            'or',
            ['like', 'cust.cust_sname', $trans->c2t(trim($this->cust_sname))],
            ['like', 'cust.cust_sname', $trans->t2c(trim($this->cust_sname))]
        ]);
//        return $query->createCommand()->queryAll();
//        return $query->createCommand()->getRawSql();
        if (!empty($this->start_date)) {
            $query->andFilterWhere(['>=', 'odh.ord_date', date('Y-m-d H:i:s', strtotime($this->start_date))]);
        }
        if (!empty($this->end_date)) {
            $query->andFilterWhere(['<=', 'odh.ord_date', date('Y-m-d H:i:s', strtotime("+1 day", strtotime($this->end_date)))]);
        }
        return $dataProvider;
    }

    /**
     * 订单查询明细列表
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchOrderList($params)
    {
        $query = (new Query())
            ->select([
                'odh.ord_id',
                // 订单编号
                'odh.ord_no saph_code',
                'odh.receipter receipter_name',
                'odh.receipter_Tel receipter_tel',
                // 订单类型
                'bt.business_value',
                // 状态
                'status.os_name saph_status',
                // 下单时间
                'odh.ord_date saph_date',
                // 交易法人
                'bc.company_name',
                // 客户名称
                'cust.cust_sname',
                // 客户代码
                'cust.cust_code',
                // 联系人
                'cust.cust_contacts',
                // 联系方式
                'cust.cust_tel2',
                // 出仓仓库
                'bw.wh_name',
                // 付款方式
//                'bp.pac_sname',
                '(CASE WHEN pub3.bsp_svalue is not null THEN pub3.bsp_svalue ELSE bp.pac_sname END) as pac_sname',
                // 支付状态 即状态
//                'odh.saph_status',
                // 币别
                'cur.bsp_svalue cur_code',
//                // 收款条件
//                'cdt.pat_sname',
                // 发票类型
                'pub2.bsp_svalue origin_from',
                'invoice.bsp_svalue',
                // 商品经理人
//                'hs2.staff_name as pdtManager',
                // 销售代表
                'hs1.staff_name sell_delegate',
                // 商品品名
                'pdt.pdt_name',
                'partno.part_no',
                '(CASE odh.audit_id '
                . 'WHEN 12 THEN "否" '
                . 'ELSE "否" END) as is_invoice',  //暂借用字段默认否
                // 类别
                'unit.bsp_svalue unit_name',
                // 下单数量
                'FORMAT(odl.sapl_quantity,2) sapl_quantity',
                // 商品库存
//                'bi.invt_num',
                // 单位
                'pdt.unit',
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
                // 商品总价（未税）
                'FORMAT(odl.tprice_ntax_o,2) tprice_ntax_o',
                // 商品总价（含税）
                'FORMAT(odl.tprice_tax_o,2) tprice_tax_o',
                'FORMAT(odl.cess,2) cess',
                'FORMAT(odl.discount,2) price_off',
                'FORMAT((odl.sapl_quantity*odl.uprice_tax_o),2) total_off',
                'FORMAT(odl.tax_freight,2) freight',
                // 运输方式
                'btr.tran_sname',
                // 配送方式
                'bd.bdm_sname',
                // 需求交期
                'odl.request_date',
                // 交期
                'odl.consignment_date',
                // 备注
                'odl.sapl_remark',
            ])
            ->from(['odh' => 'oms.ord_info'])
            ->leftJoin('oms.ord_dt odl', 'odl.ord_id=odh.ord_id')
            ->leftJoin('oms.ord_status status', 'status.os_id=odh.os_id')
            ->leftJoin('erp.crm_bs_customer_info cust', 'cust.cust_code=odh.cust_code')
            ->leftJoin('erp.bs_business_type bt', 'bt.business_type_id=odh.ord_type')
            ->leftJoin('erp.bs_company bc', 'bc.company_id=odh.corporate')
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('erp.bs_payment bp', 'bp.pac_id=odh.pac_id')// 付款方式
            ->leftJoin('erp.bs_pubdata pub3', 'pub3.bsp_id=odh.pay_type')//支付方式
//            ->leftJoin('erp.bs_pay_condition cdt', 'cdt.pat_id=odh.pat_id')// 付款条件
            ->leftJoin('pdt.bs_price price', 'odl.prt_pkid=price.prt_pkid and odh.cur_id=price.currency')//价格
            ->leftJoin('erp.bs_pubdata cur', 'cur.bsp_id=odh.cur_id')
            ->leftJoin('erp.bs_pubdata invoice', 'invoice.bsp_id=odh.invoice_type')
            ->leftJoin('erp.bs_pubdata pub2', 'pub2.bsp_id=odh.origin_hid')// 订单来源
            ->leftJoin('erp.hr_staff hs1', 'hs1.staff_id=odh.nwer AND hs1.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 销售代表名称
//            ->leftJoin('wms.l_bs_invt bi', 'bi.pdt_id=odl.prt_pkid')// 库存表
            ->leftJoin('pdt.bs_partno partno', 'partno.prt_pkid=odl.prt_pkid')
            ->leftJoin('pdt.bs_product pdt', 'pdt.pdt_pkid=partno.pdt_pkid')
            ->leftJoin('erp.bs_pubdata unit', 'unit.bsp_id=pdt.unit')
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
            ))->orderBy(['odh.nw_date' => SORT_DESC])->groupBy(['ord_dt_id']);
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
//        return $params['OrdInfoSearch']['business_value'];
        $query->andFilterWhere(['like', 'odh.ord_no', trim($this->ord_no)])// 订单编号
        ->andFilterWhere(['status.os_id' => $this->ord_status])// 订单状态
        ->andFilterWhere(['bt.business_type_id' => $this->ord_type])// 订单类型
        ->andFilterWhere(['like', 'cust.cust_code', trim($this->cust_code)])// 客户代码
        ->andFilterWhere(['like', 'partno.part_no', trim($this->pdt_no)])// 料号
        ->andFilterWhere([
            'or',
            ['like', 'cust.cust_sname', $trans->c2t(trim($this->cust_sname))],
            ['like', 'cust.cust_sname', $trans->t2c(trim($this->cust_sname))]
        ])// 客户名称
        ->andFilterWhere([
            'or',
            ['like', 'pdt.pdt_name', $trans->c2t(trim($this->pdt_name))],
            ['like', 'pdt.pdt_name', $trans->t2c(trim($this->pdt_name))]
        ])// 品名
        ->andFilterWhere(['odh.corporate' => $this->corporate]);
        if (!empty($this->start_date)) {
            $query->andFilterWhere(['>=', 'odh.ord_date', date('Y-m-d H:i:s', strtotime($this->start_date))]);
        }
        if (!empty($this->end_date)) {
            $query->andFilterWhere(['<=', 'odh.ord_date', date('Y-m-d H:i:s', strtotime("+1 day", strtotime($this->end_date)))]);
        }
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    /**
     * 点击主表获取子表商品信息
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchOrderProducts($params)
    {
        $query = (new Query())
            ->select([
                // 出仓仓库
                'bw.wh_name',
                // 商品品名
                'pdt.pdt_name',
                // 料号
                'pdt.pdt_no',
                // 类别
//                'ctg.category_sname',
                'pdt.func_get_pcategory(ctg.catg_id) as ctg_pname',
                // 下单数量
                'odl.sapl_quantity',
                // 下单数量
                'odl.delivery_num out_num',
                // 商品库存
//                'bi.invt_num',
                // 单位
//                'pdt.unit',
                'bp.bsp_svalue unit_name',
                //折扣后金额
                '(odl.sapl_quantity * odl.uprice_tax_o) as price_off',
                // 商品单价（未税）
                'odl.uprice_ntax_o',
                // 商品单价（含税）
                'odl.uprice_tax_o',
                // 商品总价（未税）
                'odl.tprice_ntax_o',
                // 商品总价（含税）
                'odl.tprice_tax_o',
                // 折扣
                'odl.discount',
                // 税率
                'odl.cess',
                // 体积？？
//                'pdt.pdt_vol',
                // 重量
                'odl.suttle',
                // 运输方式
                'btr.tran_sname',
                // 配送方式
                'btr2.tran_sname distrustion',
                // 运费
                'odl.tax_freight',
                // 需求交期
                'odl.request_date',
                // 交期
                'odl.consignment_date',
                // 备注
                'odl.sapl_remark',
                // 定价
                'price.price tax_price',
                'partno.tp_spec',
                'partno.part_no',
            ])
            ->from(['odl' => 'oms.ord_dt'])
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('pdt.bs_partno partno', 'partno.prt_pkid=odl.prt_pkid')
            ->leftJoin('pdt.bs_product pdt', 'partno.pdt_pkid=pdt.pdt_pkid')
            ->leftJoin('pdt.bs_category ctg', 'ctg.catg_id=pdt.catg_id')
//            ->leftJoin('wms.l_bs_invt bi', 'bi.pdt_id=odl.prt_pkid')// 库存表
            ->leftJoin('bs_pubdata bp', 'bp.bsp_id=pdt.unit')// 产品单位
            ->leftJoin('wms.bs_transport btr', 'btr.tran_code=odl.transport')
            ->leftJoin('wms.bs_transport btr2', 'btr2.tran_code=odl.distribution')
            ->leftJoin('oms.ord_info odh', 'odh.ord_id=odl.ord_id')
            ->leftJoin('pdt.bs_price price', 'odl.prt_pkid=price.prt_pkid and odh.cur_id=price.currency')
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
            ->andWhere([
                'and',
                ['!=', 'sapl_status', OrdDt::STATUS_DELETE],
                ['=', 'odl.ord_id', $params['id']]
            ])->groupBy(['ord_dt_id']);
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            if (isset($params['view'])) {
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
//        return $query->createCommand()->getRawSql();
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
                'odh.ord_id',
//                'odh.saph_status',
//                '(CASE odh.saph_status '
//                . 'WHEN ' . OrdInfo::STATUS_CREATE . ' THEN "新增" '
//                . 'WHEN ' . OrdInfo::STATUS_WAIT . ' THEN "待审核" '
//                . 'WHEN ' . OrdInfo::STATUS_CHECKING . ' THEN "审核中" '
//                . 'WHEN ' . OrdInfo::STATUS_FINISH . ' THEN "审核完成" '
//                . 'WHEN ' . OrdInfo::STATUS_PREPARE . ' THEN "驳回" '
//                . 'ELSE "" END) as saph_status',
                // 下单时间
                'odh.saph_date',
                // 订单来源
                'pub3.bsp_svalue older_from',
                // 订单类型
                'bt.business_value',
                // 预计交货日期
                'odh.delivery_date',
                // 客户名称
                'cust.cust_sname',
                // 客户代码
                'cust.cust_code',
                // 联系人
                'cust.cust_contacts',
                // 联系电话
                'cust.cust_tel2',
                // 客户地址  取营业地址
                'cust.cust_adress',
                // 公司电话
                'cust.cust_tel1',
                // 交易法人
                'bc.company_name',
                // 交易模式
                //'pub2.bsp_svalue trad_mode',
                'tr.tac_sname',
                // 币别
                'cur.cur_code',
                // 合同编号
                'odh.contract_no',
                // 发票类型
                'pub1.bsp_svalue invoice_type',
                // 发票抬头
                'odh.invoice_title',
                // 发票抬头地址
                'odh.title_addr',
                // 发票寄送地址
                'odh.send_addr',
                // 收货地址
                'odh.delivery_addr',
                // 客户下单附件
                'odh.cust_attachment',
                // 销售员下单附件
                'odh.seller_attachment',
                // 订单备注
                'odh.saph_remark',
                // 剩余信用额度
                // 付款方式
                'bp.pac_sname',
                // 收款条件
                'cdt.pat_sname',
                // 部门
//                'ho.organization_name',
                // 客户经理人
                'hs3.staff_name as custManager',
                // 商品经理人
                'hs2.staff_name as pdtManager',
                // 销售地区
                'area.csarea_name',
                // 销售点
                'store.sts_sname',
                // 销售代表
                'hs1.staff_name as sell_delegate',
                // 制单人
                'hs4.staff_name',
                // 制单日期
                'odh.cdate'
            ])
            ->from(['odh' => 'oms.ord_info'])
//            ->leftJoin('oms.ord_dt odl', 'odl.ord_id=odh.ord_id AND sapl_status!=' . OrdDt::STATUS_DELETE)
            ->leftJoin('crm_bs_customer_info cust', 'cust.cust_id=odh.cust_id AND cust_status!=' . CrmCustomerInfo::CUSTOMER_INVALID)
            ->leftJoin('bs_business_type bt', 'bt.business_type_id=odh.saph_type')
            ->leftJoin('bs_company bc', 'bc.company_id=odh.corporate')
//            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('bs_payment bp', 'bp.pac_id=odh.pac_id')// 付款方式
            ->leftJoin('bs_pay_condition cdt', 'cdt.pat_id=odh.pat_id')// 付款条件
            ->leftJoin('bs_currency cur', 'cur.cur_id=odh.cur_id')
            ->leftJoin('bs_pubdata pub1', 'pub1.bsp_id=odh.invoice_type')// 发票类型
//            ->leftJoin('bs_pubdata pub2', 'pub2.bsp_id=odh.trade_mode')// 交易模式
            ->leftJoin('bs_transaction tr', 'tr.tac_id=odh.trade_mode')// 交易模式
            ->leftJoin('bs_pubdata pub3', 'pub3.bsp_id=odh.origin_hid')// 订单来源
            ->leftJoin('crm_employee epl1', 'epl1.staff_id=odh.sell_delegate AND sale_status!=' . CrmEmployee::SALE_STATUS_DEL)// 销售代表
            ->leftJoin('crm_employee epl2', 'epl2.staff_id=epl1.leader_id')// 商品经理人
            ->leftJoin('crm_bs_customer_personinch cp', 'cp.cust_id=cust.cust_id')// 客户经理人
            ->leftJoin('hr_staff hs1', 'hs1.staff_code=epl1.staff_code AND hs1.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 销售代表名称
            ->leftJoin('hr_staff hs2', 'hs2.staff_code=epl2.staff_code AND hs2.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 商品经理人名称
            ->leftJoin('hr_staff hs3', 'hs3.staff_id=cp.ccpich_personid AND hs3.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 客户经理人名称
            ->leftJoin('hr_staff hs4', 'hs4.staff_id=odh.create_by AND hs3.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 制单人
//            ->leftJoin('bs_product pdt', 'pdt.pdt_id=odl.pdt_id')
            ->leftJoin('crm_bs_salearea area', 'area.csarea_id=odh.district_id2')
            ->leftJoin('crm_bs_storesinfo store', 'store.sts_id=odh.sts_id')
            ->where(['and', ['odh.ord_id' => $id], ['!=', 'saph_status', OrdInfo::STATUS_DELETE]]);
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
                'odl.prt_pkid',
                'odl.ord_dt_id',
                'odl.cess',
                // 交易单位
                'pdt.unit',
//                'cu.unit_name',
                // 商品单价（含税）
                'odl.uprice_tax_o',
                // 商品总价（含税）
                'odl.tprice_tax_o',
                // 折扣
                'odl.discount',
                'odl.distribution',
                'odl.transport',
                'odl.whs_id',
                'partno.part_no',
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
                'odl.tax_freight',
                '(CASE price.price WHEN -1.000000 THEN "面议" ELSE price.price END) AS price',
                'odh.pac_id',
                'partno.isselftake self_take',


            ])
            ->from(['odl' => 'oms.ord_dt'])
            ->leftJoin('oms.ord_info odh', 'odl.ord_id=odh.ord_id AND odl.sapl_status!=' . OrdDt::STATUS_DELETE)
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('pdt.bs_partno partno', 'partno.prt_pkid=odl.prt_pkid')
            ->leftJoin('pdt.bs_product pdt', 'pdt.pdt_pkid=partno.pdt_pkid')
            ->leftJoin('pdt.bs_price price', 'odl.prt_pkid=price.prt_pkid and odh.cur_id=price.currency')
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
            ->andWhere(['odl.ord_id' => $id])
            ->groupBy("ord_dt_id");
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }

    /**
     * 采购通知
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchPurchaseH($id)
    {
        $query = (new Query())
            ->select([
                'odh.ord_id',
                'odh.comp_id',
                'odh.cust_id',
                // 订单编号
                'odh.saph_code',
//                'odh.saph_status',
                '(CASE odh.saph_status '
                . 'WHEN ' . OrdInfo::STATUS_CREATE . ' THEN "新增" '
                . 'WHEN ' . OrdInfo::STATUS_WAIT . ' THEN "待审核" '
                . 'WHEN ' . OrdInfo::STATUS_CHECKING . ' THEN "审核中" '
                . 'WHEN ' . OrdInfo::STATUS_FINISH . ' THEN "审核完成" '
                . 'WHEN ' . OrdInfo::STATUS_PREPARE . ' THEN "驳回" '
                . 'ELSE "" END) as saph_status',
                // 下单时间
                'odh.saph_date',
                // 订单来源
                'pub3.bsp_svalue older_from',
                // 订单类型
                'bt.business_value',
                // 预计交货日期
                'odh.delivery_date',
                // 客户名称
                'cust.cust_sname',
                // 客户代码
                'cust.cust_code',
                // 联系人
                'cust.cust_contacts',
                // 联系电话
                'cust.cust_tel2',
                // 客户地址  取营业地址
                'cust.cust_adress',
                // 公司电话
                'cust.cust_tel1',
                // 交易法人
                'bc.company_name',
                // 交易模式
                //'pub2.bsp_svalue trad_mode',
                'tr.tac_sname',
                // 币别
//                'cur.cur_code',
                'odh.cur_id',
                // 合同编号
                'odh.contract_no',
                // 发票类型
                'pub1.bsp_svalue invoice_type',
                // 发票抬头
                'odh.invoice_title',
                // 发票抬头地址
                'odh.title_addr',
                // 发票寄送地址
                'odh.send_addr',
                // 收货地址
                'odh.delivery_addr',
                // 客户下单附件
                'odh.cust_attachment',
                // 销售员下单附件
                'odh.seller_attachment',
                // 订单备注
                'odh.saph_remark',


                // 剩余信用额度
                // 付款方式
                'bp.pac_sname',
                // 收款条件
                'cdt.pat_sname',
                // 部门
//                'ho.organization_name',
                // 客户经理人
                'hs3.staff_name as custManager',
                // 商品经理人
                'hs2.staff_name as pdtManager',
                // 销售地区
                'area.csarea_name',
                // 销售点
                'store.sts_sname',
                // 销售代表
                'hs1.staff_name as sell_delegate',
                // 制单人
                'hs4.staff_name',
                // 制单日期
                'odh.cdate'
            ])
            ->from(['odh' => 'oms.ord_info'])
//            ->leftJoin('oms.ord_dt odl', 'odl.ord_id=odh.ord_id AND sapl_status!=' . OrdDt::STATUS_DELETE)
            ->leftJoin('crm_bs_customer_info cust', 'cust.cust_id=odh.cust_id AND cust_status!=' . CrmCustomerInfo::CUSTOMER_INVALID)
            ->leftJoin('bs_business_type bt', 'bt.business_type_id=odh.saph_type')
            ->leftJoin('bs_company bc', 'bc.company_id=odh.corporate')
//            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('bs_payment bp', 'bp.pac_id=odh.pac_id')// 付款方式
            ->leftJoin('bs_pay_condition cdt', 'cdt.pat_id=odh.pat_id')// 付款条件
            ->leftJoin('bs_currency cur', 'cur.cur_id=odh.cur_id')
            ->leftJoin('bs_pubdata pub1', 'pub1.bsp_id=odh.invoice_type')// 发票类型
//            ->leftJoin('bs_pubdata pub2', 'pub2.bsp_id=odh.trade_mode')// 交易模式
            ->leftJoin('bs_transaction tr', 'tr.tac_id=odh.trade_mode')// 交易模式
            ->leftJoin('bs_pubdata pub3', 'pub3.bsp_id=odh.origin_hid')// 订单来源
            ->leftJoin('crm_employee epl1', 'epl1.staff_id=odh.sell_delegate AND sale_status!=' . CrmEmployee::SALE_STATUS_DEL)// 销售代表
            ->leftJoin('crm_employee epl2', 'epl2.staff_id=epl1.leader_id')// 商品经理人
            ->leftJoin('crm_bs_customer_personinch cp', 'cp.cust_id=cust.cust_id')// 客户经理人
            ->leftJoin('hr_staff hs1', 'hs1.staff_code=epl1.staff_code AND hs1.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 销售代表名称
            ->leftJoin('hr_staff hs2', 'hs2.staff_code=epl2.staff_code AND hs2.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 商品经理人名称
            ->leftJoin('hr_staff hs3', 'hs3.staff_id=cp.ccpich_personid AND hs3.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 客户经理人名称
            ->leftJoin('hr_staff hs4', 'hs4.staff_id=odh.create_by AND hs3.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 制单人
//            ->leftJoin('bs_product pdt', 'pdt.pdt_id=odl.pdt_id')
            ->leftJoin('crm_bs_salearea area', 'area.csarea_id=odh.district_id2')
            ->leftJoin('crm_bs_storesinfo store', 'store.sts_id=odh.sts_id')
            ->where(['odh.ord_id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    public function searchPurchaseL($id)
    {
        $query = (new Query())
            ->select([

                // 商品品名
                'odl.sol_id',
                'pdt.pdt_name',
                'pdt.pdt_no',
                'pdt.pdt_id',
                // 下单数量
                'odl.sapl_quantity',
                // 通知数量
                'odl.pur_note_qty',
                // 通知状态
                'odl.pur_note_status',
                // 交易单位
//                'pdt.unit',
                'cu.unit_name',
                // 商品单价（含税）
//                'odl.uprice_tax_o',
                // 商品总价（含税）
//                'odl.tprice_tax_o',
                // 折扣
//                'odl.discount',
                // 配送方式
                'bd.bdm_sname',
                // 出仓仓库
                'bw.wh_name',
                // 需求交期
                'odl.request_date',
                // 交期
                'odl.consignment_date',
                // 备注
//                'odl.sapl_remark',
                // 商品库存
                'ifnull(bi.invt_num,0) as invt_num',
                // 还需采购
                'ifnull(bi.invt_num,0)-odl.sapl_quantity+odl.pur_note_qty as require_note_qty',
                'ifnull(bi.invt_num,0)-odl.sapl_quantity as require_qty'

            ])
            ->from(['odl' => 'oms.ord_dt'])
            ->leftJoin('oms.ord_info odh', 'odl.ord_id=odh.ord_id AND odl.sapl_status!=' . OrdDt::STATUS_DELETE)
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('bs_product pdt', 'pdt.pdt_id=odl.pdt_id')
            ->leftJoin('erp.bs_category_unit cu', 'cu.id=pdt.unit')
            ->leftJoin('wms.bs_deliverymethod bd', 'bd.bdm_id=odl.distribution')
            ->leftJoin('wms.l_bs_invt bi', 'bi.pdt_id=odl.pdt_id')// 库存表
            ->where([
                'and',
                ['odh.ord_id' => $id],
                ['!=', 'odl.pur_note_status', OrdDt::NOTE_ALL],
                ['is not', 'bi.invt_num', null],
                ['<', '(bi.invt_num)-odl.sapl_quantity+odl.pur_note_qty', 0],
            ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }

    /**
     * 发货通知
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchOutH($id)
    {
        $query = (new Query())
            ->select([
                'odh.ord_id',
                'odh.corporate',
                'cust.cust_id',
                // 订单编号
                'odh.ord_no',
                // 下单时间
                'odh.nw_date',
                // 订单来源
                'pub3.bsp_svalue older_from',
                // 订单类型
                'bt.business_value',
                // 客户名称
                'cust.cust_sname',
                // 客户代码
                'cust.cust_code',
                // 联系人
                'cust.cust_contacts',
                // 联系电话
                'cust.cust_tel2',
                // 客户地址  取营业地址
                'cust.cust_adress',
                // 公司电话
                'cust.cust_tel1',
                // 交易法人
                'bc.company_name',
                // 交易模式
                //'pub2.bsp_svalue trad_mode',
                'tac_sname',
                // 币别
//                'cur.cur_code',
                'odh.cur_id',
                // 合同编号
                'odh.contract_no',
                // 发票类型
                'pub1.bsp_svalue invoice_type',
                // 发票抬头
//                'odh.invoice_title',
//                // 发票抬头地址
//                'addr.detail_addr',
//                // 发票寄送地址
//                'odh.send_addr',
//                // 收货地址
//                'odh.delivery_addr',
                // 订单备注
                'odh.remark',


                // 剩余信用额度
                // 付款方式
                'bp.pac_sname',
            ])
            ->from(['odh' => 'oms.ord_info'])
            ->leftJoin('crm_bs_customer_info cust', 'cust.cust_code=odh.cust_code AND cust_status!=' . CrmCustomerInfo::CUSTOMER_INVALID)
            ->leftJoin('bs_business_type bt', 'bt.business_type_id=odh.ord_type')
            ->leftJoin('bs_company bc', 'bc.company_id=odh.corporate')
//            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('bs_payment bp', 'bp.pac_id=odh.pac_id')// 付款方式
            ->leftJoin('bs_pubdata cur', 'cur.bsp_id=odh.cur_id')
            ->leftJoin('bs_pubdata pub1', 'pub1.bsp_id=odh.invoice_type')// 发票类型
            ->leftJoin('bs_transaction tr', 'tr.tac_id=odh.trade_mode')// 交易模式
            ->leftJoin('bs_pubdata pub3', 'pub3.bsp_id=odh.origin_hid')// 订单来源
            ->where(['odh.ord_id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    public function searchOutL($id)
    {
        $query = (new Query())
            ->select([

                // 商品品名
                'odl.ord_dt_id',
                'pdt.pdt_name',
                'partno.part_no',
                'pdt.pdt_pkid',
                // 下单数量
                'odl.sapl_quantity',
                // 已通知数量
                'odl.delivery_num',
                // 通知状态
//                'odl.out_note_status',
                // 交易单位
                'pub1.bsp_svalue unit',
                'pub1.bsp_svalue unit_name',
                // 配送方式
                'bd.bdm_sname',
                // 运输方式
                'btr.tran_sname',
                // 配送方式
                'btr2.tran_sname distrustion',
                // 出仓仓库
                'bw.wh_id',
                'bw.wh_name',
                // 需求交期
                // 交期
                'odl.consignment_date',
                // 备注
                'odl.sapl_remark',
                'odh.receipter addr_man',

            ])
            ->from(['odl' => 'oms.ord_dt'])
            ->leftJoin('oms.ord_info odh', 'odl.ord_id=odh.ord_id')
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('pdt.bs_partno partno', 'partno.prt_pkid=odl.prt_pkid')
            ->leftJoin('pdt.bs_product pdt', 'pdt.pdt_pkid=partno.pdt_pkid')
            ->leftJoin('erp.bs_pubdata pub1', 'pub1.bsp_id=pdt.unit')
            ->leftJoin('wms.bs_transport btr', 'btr.tran_code=odl.transport')
            ->leftJoin('wms.bs_transport btr2', 'btr2.tran_code=odl.distribution')
            ->leftJoin('wms.bs_deliverymethod bd', 'bd.bdm_id=odl.distribution')
//            ->leftJoin('wms.l_bs_invt bi', 'bi.pdt_id=odl.prt_pkid')// 库存表
            ->where(['odh.ord_id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}
