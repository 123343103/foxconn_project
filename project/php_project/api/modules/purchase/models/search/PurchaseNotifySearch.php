<?php

namespace app\modules\purchase\models\search;

use app\classes\Trans;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\sale\models\SaleInoutnoteh;
use app\modules\sale\models\SaleOrderh;
use app\modules\sale\models\SaleOrderl;
use app\modules\sale\models\SalePurchasenoteh;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * SaleOrderhSearch represents the model behind the search form about `app\modules\crm\models\SaleOrderh`.
 */
class PurchaseNotifySearch extends SalePurchasenoteh
{
    public $saph_type;
    public $saph_code;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notify_no', 'notfy_status', 'bill_type', 'notify_foganid', 'notify_from', 'saph_code', 'notify_toganid'], 'safe'],
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
     * 通知列表
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query=(new Query())
            ->select([
                // 通知单id
                'pch.ponh_id',
                // 通知单号
                'pch.notify_no',
                // 通知单状态
//                'pch.notfy_status',
                '(CASE pch.notfy_status '
                .'WHEN ' .SalePurchasenoteh::STATUS_CANCEL      . ' THEN "已取消" '
                .'WHEN ' .SalePurchasenoteh::STATUS_DEFAULT     . ' THEN "待处理" '
                .'WHEN ' .SalePurchasenoteh::STATUS_PURCHASING  . ' THEN "采购中" '
                .'WHEN ' .SalePurchasenoteh::STATUS_PURCHASED   . ' THEN "已采购" '
                .'ELSE "" END) as notfy_status',
                // 单据类型
                'pch.bill_type',
                'bt2.business_value as bill_type_name',
                // 申请部门
                'pch.notify_foganid',
                'org1.organization_name as apply_dpt',
                // 下单人 申请人
                //'pch.notify_from',
                'hs1.staff_name as notify_from',
                // 联系方式 （申请人联系方式  关联hr_staff表查手机号码）
                'hs.staff_mobile',
                // 交易法人
                'bc.company_name',
                // 关联订单号
                'odh.saph_code',
                // 通知日期 申请日期
                'pch.notity_date',
                // 被通知部门
                'pch.notify_toganid',
                'org2.organization_name as notifyto_dpt',
                // 接单人
                //'pch.notify_to',
                'hs2.staff_name as notify_to',
                // 操作时间
                'pch.udate',

                // 通知描述 备注 取消原因
                'pch.notify_descr',
                // 订单类型
//                'bt.business_value',
            ])
            ->from(['pch'=>'oms.sale_purchasenoteh'])
            ->leftJoin('oms.sale_orderh odh', 'pch.bill_id=odh.soh_id')
            ->leftJoin('erp.hr_staff hs', 'hs.staff_id=pch.notify_from')
            ->leftJoin('erp.hr_organization org1', 'org1.organization_id=pch.notify_foganid')  // 申请部门
            ->leftJoin('erp.hr_organization org2', 'org2.organization_id=pch.notify_toganid')  // 被通知部门
//            ->leftJoin('erp.bs_business_type bt','bt.business_type_id=odh.saph_type') // 订单类型
            ->leftJoin('erp.bs_business_type bt2','bt2.business_type_id=pch.bill_type') // 单据类型
            ->leftJoin('erp.bs_company bc','bc.company_id=odh.corporate')
            ->leftJoin('erp.crm_bs_customer_info cust','cust.cust_id=odh.cust_id AND cust_status!=' . CrmCustomerInfo::CUSTOMER_INVALID)
//            ->leftJoin('oms.sale_purchasenotel pcl', 'pcl.ponh_id=pch.ponh_id')
//            ->leftJoin('oms.sale_orderl odl','odl.sol_id=pcl.lbill_id')
//            ->leftJoin('erp.bs_product pdt','pdt.pdt_id=odl.pdt_id')
            ->leftJoin('erp.hr_staff hs1','hs1.staff_id=pch.notify_from') // 下单人名
            ->leftJoin('erp.hr_staff hs2','hs2.staff_id=pch.create_by') // 接单人名
            ->where(['!=','odh.saph_status',SaleOrderh::STATUS_DELETE]);
        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            $pageSize =10;
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
//        return $this->notfy_status;
        $query->andFilterWhere(['like', 'pch.notify_no', $this->notify_no]) // 通知单号
        ->andFilterWhere(['pch.notfy_status' => $this->notfy_status]) // 订单状态
        ->andFilterWhere(['pch.bill_type' => $this->bill_type]) // 单据类型
        ->andFilterWhere(['pch.notify_foganid' => $this->notify_foganid]) // 申请部门
        ->andFilterWhere([
            'or',
            ['like', 'hs1.staff_name', $trans->c2t($this->notify_from)],
            ['like', 'hs1.staff_name', $trans->t2c($this->notify_from)]
        ]) // 下单人
        ->andFilterWhere(['like', 'odh.saph_code', $this->saph_code]) // 关联单号
        ->andFilterWhere(['pch.notify_toganid' => $this->notify_toganid]); // 被通知部门

        return $dataProvider;
    }

    /**
     * 点击主表获取子表商品信息
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchOrderProducts($params)
    {
        $query=(new Query())
            ->select([
                // 料号
                'pdt.pdt_no',
                // 商品品名
                'pdt.pdt_name',
                // 规格
                'ca.ATTR_NAME',
                // 品牌
                'bb.BRAND_NAME_CN',
                // 类别
//                'ctg.category_sname',
                'erp.func_get_pcategory(ctg.category_id) as ctg_pname',
                // 单位
//                'pdt.unit',
                'cu.unit_name',
                // 商品库存
                'bi.invt_num',
                // 需求量
                'pcl.require_qty',
                // 通知采购数量
                'pcl.apply_qty',
                // 预计单价 商品单价（未税）
                'odl.uprice_tax_o',
                // 预计总价
                '(odl.uprice_tax_o*pcl.require_qty) as t_price',
                // 商品单价（含税）
//                'odl.uprice_tax_o',
//                // 商品总价（未税）
//                'odl.tprice_ntax_o',
//                // 商品总价（含税）
//                'odl.tprice_tax_o',
//                // 下单数量
//                'odl.sapl_quantity',
//                // 折扣
//                'odl.discount',
//                // 体积？？
//                'pdt.pdt_vol',
//                // 重量
//                'odl.suttle',
//                // 运输方式
//                'btr.tran_sname',
//                // 配送方式
//                'bd.bdm_sname',
//                // 运费
//                'odl.freight',
                // 需求日期
                'pcl.require_date',
                // 交期
                'odl.consignment_date',
                // 备注
                'pcl.sonl_remark',
            ])
            ->from(['pcl'=>'oms.sale_purchasenotel'])
            ->leftJoin('oms.sale_orderl odl', 'pcl.lbill_id=odl.sol_id')
            ->leftJoin('bs_product pdt', 'pdt.pdt_id=odl.pdt_id')
            ->leftJoin('category_attr ca','ca.CATEGORY_ATTR_ID=pdt.tp_spec')
            ->LeftJoin('bs_brand bb',"bb.brand_id=pdt.brand_id")
            ->leftJoin('bs_category ctg','ctg.category_id=pdt.bs_category_id')
            ->leftJoin('wms.l_bs_invt bi','bi.pdt_id=odl.pdt_id') // 库存表
            ->leftJoin('bs_category_unit cu','cu.id=pdt.unit')
            ->leftJoin('wms.bs_transport btr','btr.tran_id=odl.transport')
            ->leftJoin('wms.bs_deliverymethod bd', 'bd.bdm_id=odl.distribution')
            ->where([
                'and',
                ['!=','sapl_status',SaleOrderl::STATUS_DELETE],
                ['=','pcl.ponh_id', $params['id']]
            ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    /**
     * 发货通知单详情
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchNotifyH($id)
    {
        $query=(new Query())
            ->select([
                // 通知单id
                'pch.ponh_id',
                // 通知单号
                'pch.notify_no',
                // 通知单状态
//                'pch.notfy_status',
                '(CASE pch.notfy_status '
                .'WHEN ' .SalePurchasenoteh::STATUS_CANCEL      . ' THEN "已取消" '
                .'WHEN ' .SalePurchasenoteh::STATUS_DEFAULT     . ' THEN "待处理" '
                .'WHEN ' .SalePurchasenoteh::STATUS_PURCHASING  . ' THEN "采购中" '
                .'WHEN ' .SalePurchasenoteh::STATUS_PURCHASED   . ' THEN "已采购" '
                .'ELSE "" END) as notfy_status',
                // 单据类型
                'pch.bill_type',
                'bt2.business_value as bill_type_name',
                // 申请部门
                'pch.notify_foganid',
                'org1.organization_name as apply_dpt',
                // 下单人 申请人
                //'pch.notify_from',
                'hs1.staff_name as notify_from',
                // 联系方式 （申请人联系方式  关联hr_staff表查手机号码）
                'hs.staff_mobile',
                // 交易法人
                'bc.company_name',
                // 关联订单号
                'odh.saph_code',
                // 通知日期 申请日期
                'pch.notity_date',
                // 被通知部门
                'pch.notify_toganid',
                'org2.organization_name as notifyto_dpt',
                // 接单人
                //'pch.notify_to',
                'hs2.staff_name as notify_to',
                // 操作时间
                'pch.udate',

                // 通知描述 备注 取消原因
                'pch.notify_descr',
                // 订单类型
//                'bt.business_value',
            ])
            ->from(['pch'=>'oms.sale_purchasenoteh'])
            ->leftJoin('oms.sale_orderh odh', 'pch.bill_id=odh.soh_id')
            ->leftJoin('erp.hr_staff hs', 'hs.staff_id=pch.notify_from')
            ->leftJoin('erp.hr_organization org1', 'org1.organization_id=pch.notify_foganid')  // 申请部门
            ->leftJoin('erp.hr_organization org2', 'org2.organization_id=pch.notify_toganid')  // 被通知部门
//            ->leftJoin('erp.bs_business_type bt','bt.business_type_id=odh.saph_type') // 订单类型
            ->leftJoin('erp.bs_business_type bt2','bt2.business_type_id=pch.bill_type') // 单据类型
            ->leftJoin('erp.bs_company bc','bc.company_id=odh.corporate')
            ->leftJoin('erp.crm_bs_customer_info cust','cust.cust_id=odh.cust_id AND cust_status!=' . CrmCustomerInfo::CUSTOMER_INVALID)
//            ->leftJoin('oms.sale_purchasenotel pcl', 'pcl.ponh_id=pch.ponh_id')
//            ->leftJoin('oms.sale_orderl odl','odl.sol_id=pcl.lbill_id')
//            ->leftJoin('erp.bs_product pdt','pdt.pdt_id=odl.pdt_id')
            ->leftJoin('erp.hr_staff hs1','hs1.staff_id=pch.notify_from') // 下单人名
            ->leftJoin('erp.hr_staff hs2','hs2.staff_id=pch.create_by') // 接单人名
            ->where(['and', ['pch.ponh_id' => $id], ['!=','odh.saph_status',SaleOrderh::STATUS_DELETE]]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }
    public function searchNotifyL($id)
    {
        $query=(new Query())
            ->select([
                // 料号
                'pdt.pdt_no',
                // 商品品名
                'pdt.pdt_name',
                // 规格
                'ca.ATTR_NAME',
                // 品牌
                'bb.BRAND_NAME_CN',
                // 类别
//                'ctg.category_sname',
                'erp.func_get_pcategory(ctg.category_id) as ctg_pname',
                // 单位
//                'pdt.unit',
                'cu.unit_name',
                // 商品库存
                'bi.invt_num',
                // 需求量
                'pcl.require_qty',
                // 通知采购数量
                'pcl.apply_qty',
                // 预计单价 商品单价（未税）
                'odl.uprice_tax_o',
                // 预计总价
                '(odl.uprice_tax_o*pcl.require_qty) as t_price',
                // 商品单价（含税）
//                'odl.uprice_tax_o',
//                // 商品总价（未税）
//                'odl.tprice_ntax_o',
//                // 商品总价（含税）
//                'odl.tprice_tax_o',
//                // 下单数量
//                'odl.sapl_quantity',
//                // 折扣
//                'odl.discount',
//                // 体积？？
//                'pdt.pdt_vol',
//                // 重量
//                'odl.suttle',
//                // 运输方式
//                'btr.tran_sname',
//                // 配送方式
//                'bd.bdm_sname',
//                // 运费
//                'odl.freight',
                // 需求日期
                'pcl.require_date',
                // 交期
                'odl.consignment_date',
                // 备注
                'pcl.sonl_remark',
            ])
            ->from(['pcl'=>'oms.sale_purchasenotel'])
            ->leftJoin('oms.sale_orderl odl', 'pcl.lbill_id=odl.sol_id')
            ->leftJoin('bs_product pdt', 'pdt.pdt_id=odl.pdt_id')
            ->leftJoin('category_attr ca','ca.CATEGORY_ATTR_ID=pdt.tp_spec')
            ->LeftJoin('bs_brand bb',"bb.brand_id=pdt.brand_id")
            ->leftJoin('bs_category ctg','ctg.category_id=pdt.bs_category_id')
            ->leftJoin('wms.l_bs_invt bi','bi.pdt_id=odl.pdt_id') // 库存表
            ->leftJoin('bs_category_unit cu','cu.id=pdt.unit')
            ->leftJoin('wms.bs_transport btr','btr.tran_id=odl.transport')
            ->leftJoin('wms.bs_deliverymethod bd', 'bd.bdm_id=odl.distribution')
            ->where([
                'and',
                ['!=','sapl_status',SaleOrderl::STATUS_DELETE],
                ['=','pcl.ponh_id', $id]
            ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

}
