<?php

namespace app\modules\sale\models\search;

use app\classes\Trans;
use app\modules\common\models\BsAddress;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsCurrency;
use app\modules\common\models\BsDistrict;
use app\modules\ptdt\models\BsPartno;
use app\modules\ptdt\models\BsProduct;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmCustomerApply;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmEmployee;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\FpPrice;
use app\modules\sale\models\SaleCustrequireH;
use app\modules\sale\models\SaleCustrequireL;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;

/**
 * This is the ActiveQuery class for [[\app\modules\sale\models\SaleCostType]].
 *
 * @see \app\modules\sale\models\SaleCostType
 */
class SaleCustrequireHSearch extends SaleCustrequireH
{
    public $cust_sname;
    public $applyno;
    public $ccpich_personid;
    public $start_date;
    public $end_date;
    public $pdt_name;
    public $pdt_no;

    public function rules()
    {
        return [
            [['saph_type', 'saph_code', 'cust_sname', 'applyno', 'ccpich_personid', 'start_date', 'end_date', 'saph_status', 'corporate', 'pdt_name', 'pdt_no', 'pac_id'], 'safe'],
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
     * index首页客户需求单条件搜索
     * @param $params
     * @return ActiveDataProvider
     */
    public function requireSearch($params)
    {
        $query = (new Query())
            ->select([
                'odh.saph_id',
                // 订单编号
                'odh.saph_code',
                // 订单状态
                // 'odh.saph_status',
                '(CASE odh.saph_status '
                . 'WHEN ' . SaleCustrequireH::STATUS_CREATE . ' THEN "待报价" '
                . 'WHEN ' . SaleCustrequireH::STATUS_QUOTING . ' THEN "报价中" '
//                . 'WHEN ' . SaleCustrequireH::STATUS_CHECKING . ' THEN "报价中" '
                . 'WHEN ' . SaleCustrequireH::STATUS_FINISH . ' THEN "已报价" '
                . 'WHEN ' . SaleCustrequireH::STATUS_PREPARE . ' THEN "报价驳回" '
                . 'ELSE "" END) as saph_status',
                // 下单时间
                'odh.saph_date',
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
                'bp.pac_sname',
                // 币别
                'cur.cur_code',
                // 客户经理人
                'hs1.staff_name as cust_manager',
            ])
            ->from(['odh' => 'oms.sale_custrequire_h'])
            ->leftJoin('erp.crm_bs_customer_info cust', 'cust.cust_id=odh.cust_id AND cust_status!=' . CrmCustomerInfo::CUSTOMER_INVALID)
            ->leftJoin('crm_bs_customer_personinch cp', 'cp.cust_id=cust.cust_id')// 客户经理人
            ->leftJoin('hr_staff hs1', 'hs1.staff_id=cp.ccpich_personid AND hs1.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 客户经理人名称
            ->leftJoin('erp.bs_business_type bt', 'bt.business_type_id=odh.saph_type')
            ->leftJoin('erp.bs_company bc', 'bc.company_id=odh.corporate')
            ->leftJoin('erp.bs_payment bp', 'bp.pac_id=odh.pac_id')// 付款方式
            ->leftJoin('erp.bs_currency cur', 'cur.cur_id=odh.cur_id')
            ->leftJoin('erp.bs_pubdata pub', 'pub.bsp_id=cust.invoice_type')
            ->leftJoin('erp.bs_pubdata pub2', 'pub2.bsp_id=odh.origin_hid')// 订单来源
            ->where(['!=', 'saph_status', SaleCustrequireH::STATUS_DELETE]);
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
        $query->andFilterWhere(['like', 'odh.saph_code', $this->saph_code])// 订单编号
        ->andFilterWhere(['odh.saph_status' => $this->saph_status])// 订单状态
        ->andFilterWhere(['bt.business_type_id' => $this->saph_type])// 订单类型
        ->andFilterWhere(['bp.pac_id' => $this->pac_id])// 付款方式
        ->andFilterWhere(['like', 'cust.cust_code', $this->applyno])// 客户代码
        ->andFilterWhere([
            'or',
            ['like', 'cust.cust_sname', $trans->c2t($this->cust_sname)],
            ['like', 'cust.cust_sname', $trans->t2c($this->cust_sname)]
        ])// 客户名称
        ->andFilterWhere(['>=', 'odh.saph_date', $this->start_date])
            ->andFilterWhere(['<=', 'odh.saph_date', $this->end_date]);
//        return $query->createCommand()->getRawSql();
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
                'apply.applyno',                    // 客户代码
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
                'ba3.ba_address delivery_address',  // 收货地址
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
            ->leftJoin(BsAddress::tableName() . ' ba3', 'ba3.cust_id=cust.cust_id and ba3.ba_type=' . BsAddress::TYPE_DELIVERY . ' and ba3.ba_status=' . BsAddress::STATUS_DEFAULT)
            ->where(['status.sale_status' => CrmCustomerStatus::STATUS_DEFAULT])
            ->andWhere(['or', ['!=', 'cust.cust_status', 0], ['is', 'cust.cust_status', null]])
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
                ['like', 'cust_sname', $trans->t2c($params['searchKeyword'])],
                ['like', 'cust_sname', $trans->c2t($params['searchKeyword'])],
                ['like', 'cust_sname', $params['searchKeyword']],
                ['like', 'applyno', ($params['searchKeyword'])],
            ]);
        } else {
            $query->andWhere(['is not', 'apply.applyno', null]);
        }
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    /**
     * 选择商品
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchProducts($params)
    {
        $query = (new Query())
            ->select([
//                'pdt.pdt_id',
                // 料号
                'partno.part_no pdt_no',
                // 品名
                'pdt.pdt_name',
                // 仓库
//                'wh.wh_name',
                // 商品库存
//                'bi.invt_num',
                // 类别
                //'ctg.category_id',
                'ctg.catg_name',
                'ctg.catg_id',
                // 单位
//                'cu.unit_name',
                //'pdt.unit',
                // 重量
//                'pdt.pdt_weight',
                // 品牌
//                'bb.BRAND_NAME_CN',
                // 规格
                'fpprice.tp_spec specification',
//                'ca.ATTR_NAME specification',
                // 材积
//                'pdt.pdt_vol',
                // 折扣率
            ])
            ->from(['fpprice' => FpPrice::tableName()])
            ->LeftJoin(BsPartno::tableName() . ' partno', "partno.part_no=fpprice.part_no")
            ->LeftJoin(BsProduct::tableName() . ' pdt', "pdt.pdt_PKID=partno.pdt_PKID")
            ->LeftJoin(BsCategory::tableName() . ' ctg', "pdt.catg_id=ctg.catg_id")
//            ->leftJoin('category_attr ca', 'ca.CATEGORY_ATTR_ID=pdt.tp_spec')
//            ->leftJoin('category_attr ca', 'ca.CATEGORY_ATTR_ID=pdt.tp_spec')
//            ->LeftJoin('wms.bs_wh wh', "wh.wh_id=pdt.pdt_whsid")
//            ->LeftJoin('wms.l_bs_invt bi', "bi.pdt_id=pdt.pdt_id")
//            ->leftJoin('bs_category_unit cu', 'cu.id=pdt.unit')
//            ->LeftJoin('bs_brand bb', "bb.brand_id=pdt.brand_id")
//            ->where('pdt.pdt_id is not null and ctg.catg_id  is not null')
        ;

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
            $trans = new Trans();
            $params['searchKeyword'] = trim($params['searchKeyword']);
            $query->andFilterWhere(['or',
                ['like', 'pdt_no', $params['searchKeyword']],
                ['like', 'pdt_name', $trans->t2c($params['searchKeyword'])],
                ['like', 'pdt_name', $trans->c2t($params['searchKeyword'])],
                ['like', 'pdt_name', $params['searchKeyword']],
            ]);
        }
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    //选择商品重写（new）
    public function  searchProduct($params)
    {
        if($params['whId'] !=""){
            $where=" WHERE s.wh_code="."'".$params['whId']."'";
        }else{
            $where=" WHERE 1=1";
        }

        $bindParams=[];
        if(!empty($params["kwd"])){
            $bindParams[":pdt_no"]="%{$params["kwd"]}%";
            $bindParams[":pdt_name"]="%{$params["kwd"]}%";
            $bindParams[":tp_spec"]="%{$params["kwd"]}%";
            $bindParams[":st_code"]="%{$params["kwd"]}%";
            $where.=" and (pdt.bs_material.part_no like :pdt_no or pdt.bs_material.pdt_name like :pdt_name or pdt.bs_material.tp_spec like :tp_spec  or s.st_code like :st_code)";}

        $sql="SELECT s.wh_code,bw.wh_name,s.part_no,s.batch_no,s.invt_num,wms.s.st_code,
			pdt.bs_material.pdt_name,pdt.bs_material.brand,pdt.bs_material.unit,bs.st_id,bw.wh_id,
			pdt.bs_material.tp_spec,c1.catg_name,c2.catg_name AS catg_name2,c3.catg_name AS catg_name3
            from wms.bs_sit_invt s 
            LEFT JOIN  pdt.bs_material  ON pdt.bs_material.part_no= s.part_no
            LEFT JOIN wms.bs_wh bw ON s.wh_code= bw.wh_code
            LEFT JOIN wms.bs_st bs ON s.st_code= bs.st_code
            LEFT JOIN pdt.bs_category c1 ON pdt.bs_material.category_no=c1.catg_no 
            LEFT JOIN pdt.bs_category c2 ON c1.p_catg_id=c2.catg_id
            LEFT JOIN pdt.bs_category c3 ON c2.p_catg_id=c3.catg_id
            {$where}";

        $count=\Yii::$app->db->createCommand("select count(*) from wms.bs_sit_invt s
            LEFT JOIN  pdt.bs_material  ON pdt.bs_material.part_no= s.part_no
            LEFT JOIN wms.bs_wh bw ON s.wh_code= bw.wh_code
            LEFT JOIN pdt.bs_category c1 ON pdt.bs_material.category_no=c1.catg_no 
            LEFT JOIN pdt.bs_category c2 ON c1.p_catg_id=c2.catg_id
            LEFT JOIN pdt.bs_category c3 ON c2.p_catg_id=c3.catg_id
            {$where} ",$bindParams)->query()->count();

        $provider=new SqlDataProvider([
            "sql"=>$sql,
            "totalCount"=>$count,
            "params"=>$bindParams,
            "pagination"=>[
                "page"=>isset($params["page"])?$params["page"]-1:0,
                "pageSize"=>isset($params["rows"])?$params["rows"]:10,
            ]
        ]);
        return $provider;

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
     * 客户订单查询明细列表
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchOrderList($params)
    {
        $query = (new Query())
            ->select([
                'odh.saph_id',
                // 订单编号
                'odh.saph_code',
                // 订单类型
                'bt.business_value',
                // 订单状态
//                'odh.saph_status',
                '(CASE odh.saph_status '
                . 'WHEN ' . SaleCustrequireH::STATUS_CREATE . ' THEN "新增" '
//                . 'WHEN ' . SaleCustrequireH::STATUS_WAIT . ' THEN "待审核" '
//                . 'WHEN ' . SaleCustrequireH::STATUS_CHECKING . ' THEN "审核中" '
                . 'WHEN ' . SaleCustrequireH::STATUS_FINISH . ' THEN "已报价" '
                . 'WHEN ' . SaleCustrequireH::STATUS_PREPARE . ' THEN "驳回" '
                . 'ELSE "" END) as saph_status',
                // 下单时间
                'odh.saph_date',
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
                'bp.pac_sname',
                // 支付状态 即状态
//                'odh.saph_status',
                // 币别
                'cur.cur_code',
                // 收款条件
                'cdt.pat_sname',
                // 发票类型
                'pub.bsp_svalue',
                // 商品经理人
                'hs2.staff_name as pdtManager',
                // 销售代表
                'hs1.staff_name sell_delegate',
                // 商品品名
                'pdt.pdt_name',
                // 料号
//                'pdt.pdt_no',
                // 类别
                'ctg.category_sname',
                'erp.func_get_pcategory(ctg.category_id) as ctg_pname',
                // 下单数量
                'odl.sapl_quantity',
                // 商品库存
                'bi.invt_num',
                // 单位
//                'pdt.unit',
                'cu.unit_name',
                // 商品单价（未税）
                'odl.uprice_ntax_o',
                // 商品单价（含税）
                'odl.uprice_tax_o',
                // 商品总价（未税）
                'odl.tprice_ntax_o',
                // 商品总价（含税）
                'odl.tprice_tax_o',
                // 体积？？
                'pdt.pdt_vol',
                // 重量
                'odl.suttle',
                // 运输方式
                'btr.tran_sname',
                // 配送方式
                'bd.bdm_sname',
                // 运费
                'odl.freight',
                // 需求交期
                'odl.request_date',
                // 交期
                'odl.consignment_date',
                // 备注
                'odl.sapl_remark',
            ])
            ->from(['odh' => 'oms.sale_custrequire_h'])
            ->leftJoin('oms.sale_custrequire_l odl', 'odl.saph_id=odh.saph_id AND sapl_status!=' . SaleCustrequireL::STATUS_DELETE)
            ->leftJoin('erp.crm_bs_customer_info cust', 'cust.cust_id=odh.cust_id AND cust_status!=' . CrmCustomerInfo::CUSTOMER_INVALID)
            ->leftJoin('erp.bs_business_type bt', 'bt.business_type_id=odh.saph_type')
            ->leftJoin('erp.bs_company bc', 'bc.company_id=odh.corporate')
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('erp.bs_payment bp', 'bp.pac_id=odh.pac_id')// 付款方式
            ->leftJoin('erp.bs_pay_condition cdt', 'cdt.pat_id=odh.pat_id')// 付款条件
            ->leftJoin('erp.bs_currency cur', 'cur.cur_id=odh.cur_id')
            ->leftJoin('erp.bs_pubdata pub', 'pub.bsp_id=cust.invoice_type')
            ->leftJoin('erp.crm_employee epl1', 'epl1.staff_id=odh.sell_delegate AND sale_status!=' . CrmEmployee::SALE_STATUS_DEL)// 销售代表
            ->leftJoin('erp.crm_employee epl2', 'epl2.staff_id=epl1.leader_id')// 商品经理人
            ->leftJoin('erp.hr_staff hs1', 'hs1.staff_code=epl1.staff_code AND hs1.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 销售代表名称
            ->leftJoin('erp.hr_staff hs2', 'hs2.staff_code=epl2.staff_code AND hs2.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 商品经理人名称
            ->leftJoin('wms.l_bs_invt bi', 'bi.pdt_id=odl.pdt_id')// 库存表
            ->leftJoin('erp.bs_product pdt', 'pdt.pdt_id=odl.pdt_id')
            ->leftJoin('erp.bs_category ctg', 'ctg.category_id=pdt.bs_category_id')
            ->leftJoin('erp.bs_category_unit cu', 'cu.id=pdt.unit')
            ->leftJoin('wms.bs_transport btr', 'btr.tran_id=odl.transport')
            ->leftJoin('wms.bs_deliverymethod bd', 'bd.bdm_id=odl.distribution')
            ->where(['!=', 'saph_status', SaleCustrequireH::STATUS_DELETE]);
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
//        return $params['SaleQuotedpriceHSearch']['business_value'];
        $query->andFilterWhere(['like', 'odh.saph_code', $this->saph_code])// 订单编号
        ->andFilterWhere(['odh.saph_status' => $this->saph_status])// 订单状态
        ->andFilterWhere(['bt.business_type_id' => $this->saph_type])// 订单类型
        ->andFilterWhere(['like', 'cust.cust_code', $this->applyno])// 客户代码
        ->andFilterWhere(['like', 'pdt.pdt_no', $this->pdt_no])// 料号
        ->andFilterWhere([
            'or',
            ['like', 'cust.cust_sname', $trans->c2t($this->cust_sname)],
            ['like', 'cust.cust_sname', $trans->t2c($this->cust_sname)]
        ])// 客户名称
        ->andFilterWhere([
            'or',
            ['like', 'pdt.pdt_name', $trans->c2t($this->pdt_name)],
            ['like', 'pdt.pdt_name', $trans->t2c($this->pdt_name)]
        ])// 品名
        ->andFilterWhere(['odh.corporate' => $this->corporate])// 交易法人
        ->andFilterWhere([
            'between',
            'odh.saph_date',
            $this->start_date,
            $this->end_date
        ]); // 下单时间 开始时间和结束时间必须同时存在 否则查询不对
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    /**
     * 报价单查询明细列表
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchQuotedDetailList($params)
    {
        $query = (new Query())
            ->select([
                'odh.saph_id',
                // 订单编号
                'odh.saph_code',
                // 订单类型
                'bt.business_value',
                // 订单状态
//                'odh.saph_status',
                '(CASE odh.saph_status '
                . 'WHEN ' . SaleCustrequireH::STATUS_CREATE . ' THEN "新增" '
//                . 'WHEN ' . SaleCustrequireH::STATUS_WAIT . ' THEN "待审核" '
//                . 'WHEN ' . SaleCustrequireH::STATUS_CHECKING . ' THEN "审核中" '
                . 'WHEN ' . SaleCustrequireH::STATUS_FINISH . ' THEN "已报价" '
                . 'WHEN ' . SaleCustrequireH::STATUS_PREPARE . ' THEN "驳回" '
                . 'ELSE "" END) as saph_status',
                // 下单时间
                'odh.saph_date',
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
                'bp.pac_sname',
                // 支付状态 即状态
//                'odh.saph_status',
                // 币别
                'cur.cur_code',
                // 收款条件
                'cdt.pat_sname',
                // 发票类型
                'pub.bsp_svalue',
                // 商品经理人
                'hs2.staff_name as pdtManager',
                // 销售代表
                'hs1.staff_name sell_delegate',
                // 商品品名
                'pdt.pdt_name',
                // 料号
//                'pdt.pdt_no',
                // 类别
                'ctg.category_sname',
                'erp.func_get_pcategory(ctg.category_id) as ctg_pname',
                // 下单数量
                'odl.sapl_quantity',
                // 商品库存
                'bi.invt_num',
                // 单位
//                'pdt.unit',
                'cu.unit_name',
                // 商品单价（未税）
                'odl.uprice_ntax_o',
                // 商品单价（含税）
                'odl.uprice_tax_o',
                // 商品总价（未税）
                'odl.tprice_ntax_o',
                // 商品总价（含税）
                'odl.tprice_tax_o',
                // 体积？？
                'pdt.pdt_vol',
                // 重量
                'odl.suttle',
                // 运输方式
                'btr.tran_sname',
                // 配送方式
                'bd.bdm_sname',
                // 运费
                'odl.freight',
                // 需求交期
                'odl.request_date',
                // 交期
                'odl.consignment_date',
                // 备注
                'odl.sapl_remark',
            ])
            ->from(['odh' => 'oms.sale_custrequire_h'])
            ->leftJoin('oms.sale_custrequire_l odl', 'odl.saph_id=odh.saph_id AND sapl_status!=' . SaleCustrequireL::STATUS_DELETE)
            ->leftJoin('erp.crm_bs_customer_info cust', 'cust.cust_id=odh.cust_id AND cust_status!=' . CrmCustomerInfo::CUSTOMER_INVALID)
            ->leftJoin('erp.bs_business_type bt', 'bt.business_type_id=odh.saph_type')
            ->leftJoin('erp.bs_company bc', 'bc.company_id=odh.corporate')
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('erp.bs_payment bp', 'bp.pac_id=odh.pac_id')// 付款方式
            ->leftJoin('erp.bs_pay_condition cdt', 'cdt.pat_id=odh.pat_id')// 付款条件
            ->leftJoin('erp.bs_currency cur', 'cur.cur_id=odh.cur_id')
            ->leftJoin('erp.bs_pubdata pub', 'pub.bsp_id=cust.invoice_type')
            ->leftJoin('erp.crm_employee epl1', 'epl1.staff_id=odh.sell_delegate AND sale_status!=' . CrmEmployee::SALE_STATUS_DEL)// 销售代表
            ->leftJoin('erp.crm_employee epl2', 'epl2.staff_id=epl1.leader_id')// 商品经理人
            ->leftJoin('erp.hr_staff hs1', 'hs1.staff_code=epl1.staff_code AND hs1.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 销售代表名称
            ->leftJoin('erp.hr_staff hs2', 'hs2.staff_code=epl2.staff_code AND hs2.staff_status!=' . HrStaff::STAFF_STATUS_DEL)// 商品经理人名称
            ->leftJoin('wms.l_bs_invt bi', 'bi.pdt_id=odl.pdt_id')// 库存表
            ->leftJoin('erp.bs_product pdt', 'pdt.pdt_id=odl.pdt_id')
            ->leftJoin('erp.bs_category ctg', 'ctg.category_id=pdt.bs_category_id')
            ->leftJoin('erp.bs_category_unit cu', 'cu.id=pdt.unit')
            ->leftJoin('wms.bs_transport btr', 'btr.tran_id=odl.transport')
            ->leftJoin('wms.bs_deliverymethod bd', 'bd.bdm_id=odl.distribution')
            ->where(['and', ['!=', 'saph_status', SaleCustrequireH::STATUS_DELETE], ['saph_flag' => SaleCustrequireH::TO_QUOTED]]);
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
//        return $params['SaleCustrequireHSearch']['business_value'];
        $query->andFilterWhere(['like', 'odh.saph_code', $this->saph_code])// 订单编号
        ->andFilterWhere(['odh.saph_status' => $this->saph_status])// 订单状态
        ->andFilterWhere(['bt.business_type_id' => $this->saph_type])// 订单类型
        ->andFilterWhere(['like', 'cust.cust_code', $this->applyno])// 客户代码
        ->andFilterWhere(['like', 'pdt.pdt_no', $this->pdt_no])// 料号
        ->andFilterWhere([
            'or',
            ['like', 'cust.cust_sname', $trans->c2t($this->cust_sname)],
            ['like', 'cust.cust_sname', $trans->t2c($this->cust_sname)]
        ])// 客户名称
        ->andFilterWhere([
            'or',
            ['like', 'pdt.pdt_name', $trans->c2t($this->pdt_name)],
            ['like', 'pdt.pdt_name', $trans->t2c($this->pdt_name)]
        ])// 品名
        ->andFilterWhere(['odh.corporate' => $this->corporate])// 交易法人
        ->andFilterWhere([
            'between',
            'odh.saph_date',
            $this->start_date,
            $this->end_date
        ]); // 下单时间 开始时间和结束时间必须同时存在 否则查询不对
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
                'odh.saph_id',
//                'odh.saph_status',
                '(CASE odh.saph_status '
                . 'WHEN ' . SaleCustrequireH::STATUS_CREATE . ' THEN "新增" '
//                . 'WHEN ' . SaleCustrequireH::STATUS_WAIT . ' THEN "待审核" '
//                . 'WHEN ' . SaleCustrequireH::STATUS_CHECKING . ' THEN "审核中" '
                . 'WHEN ' . SaleCustrequireH::STATUS_FINISH . ' THEN "审核完成" '
                . 'WHEN ' . SaleCustrequireH::STATUS_PREPARE . ' THEN "驳回" '
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
//                'pub2.bsp_svalue trad_mode',
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
            ->from(['odh' => 'oms.sale_custrequire_h'])
//            ->leftJoin('oms.sale_custrequire_l odl', 'odl.saph_id=odh.saph_id AND sapl_status!=' . SaleCustrequireL::STATUS_DELETE)
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
            ->where(['and', ['odh.saph_id' => $id], ['!=', 'saph_status', SaleCustrequireH::STATUS_DELETE]]);
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
            ->from(['odl' => 'oms.sale_custrequire_l'])
            ->leftJoin('oms.sale_custrequire_h odh', 'odl.saph_id=odh.saph_id AND odl.sapl_status!=' . SaleCustrequireL::STATUS_DELETE)
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('bs_product pdt', 'pdt.pdt_id=odl.pdt_id')
            ->leftJoin('wms.bs_deliverymethod bd', 'bd.bdm_id=odl.distribution')
            ->where(['and', ['odh.saph_id' => $id], ['!=', 'saph_status', SaleCustrequireH::STATUS_DELETE]]);
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
                'bpn.prt_pkid pdt_id',
                // 商品料号
                'bpn.part_no pdt_no',
                // 商品料号
                'bpn.min_order',
                // 商品料号
                'price.price',
                // 商品名称
                'bpt.pdt_name',
                // 下单数量
                'odl.sapl_quantity',
                //包装数量
                'bp.pdt_qty',
                // 商品单价（未税）
                'odl.uprice_ntax_o',
                // 税率
                'odl.cess',
                // 折扣
                'odl.discount',
                // 运输方式
                'odl.transport',
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
            ->from(['odl' => 'oms.sale_custrequire_l'])
            ->leftJoin('oms.sale_custrequire_h odh', 'odl.saph_id=odh.saph_id AND odl.sapl_status!=' . SaleCustrequireL::STATUS_DELETE)
            ->leftJoin('pdt.bs_partno bpn', 'bpn.prt_pkid=odl.pdt_id')
            ->leftJoin('pdt.bs_price price', 'odl.pdt_id=price.prt_pkid')
            ->leftJoin('pdt.bs_product bpt', 'bpt.pdt_PKID=bpn.pdt_PKID')
            ->leftJoin('pdt.bs_pack bp', 'bp.prt_pkid=bpn.prt_pkid')
            ->where(
                "`odl`.`sapl_quantity` BETWEEN price.minqty
    AND price.maxqty"
	)
            ->andWhere('bp.pck_type = 2')
            ->andwhere(['and', ['odh.saph_id' => $id], ['!=', 'saph_status', SaleCustrequireH::STATUS_DELETE]]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }

    /**
     * 报价单列表
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchQuotedList($params)   //搜索方法
    {
        $query = SaleCustrequireH::find()->select([
            'saph_id',
            'saph_code',
            'saph_status',
            'saph_type',
            'saph_date',
            'sale_custrequire_h.cust_id',
            'corporate',
            'sale_custrequire_h.create_by',
            'sale_custrequire_h.update_by',
            'bill_freight',
            'bill_oamount',

        ])->where([
            'and',
            ['<>', 'saph_status', self::STATUS_DELETE],
            ['=', 'saph_flag', SaleCustrequireH::TO_QUOTED]
        ]);
//        $query->joinWith("orderType");
//        $query->joinWith("customerInfo");
//        $query->joinWith("corporateCompany");
//        $query->joinWith("customerManager");
//        $query->joinWith("customerManagerName");
//        $query->joinWith("customerApply");
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
            ]
        ]);

        // 从参数的数据中加载过滤条件，并验证
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'saph_code', $this->saph_code])// 订单编码
        ->andFilterWhere(['saph_type' => $this->saph_type])// 订单类型
        ->andFilterWhere(['like', 'erp.crm_bs_customer_info.cust_sname', $this->cust_sname])// 客户名称
        ->andFilterWhere(['like', 'erp.crm_customer_apply.applyno', $this->applyno])// 客户编码
        ->andFilterWhere(['like', 'corporate', $this->corporate])// 交易法人
        ->andFilterWhere(['saph_status' => $this->saph_status])// 订单状态
        ->andFilterWhere(['like', 'staff_name', $this->ccpich_personid])// 客户经理人
        ->andFilterWhere(['pac_id' => $this->pac_id])// 付款方式
        ->andFilterWhere(['between', 'saph_date', $this->start_date, $this->end_date]); // 下单时间
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }
}
