<?php

namespace app\modules\warehouse\models\search;

use app\classes\Trans;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmEmployee;
use app\modules\hr\models\HrStaff;
use app\modules\sale\models\SaleInoutnoteh;
use app\modules\sale\models\SaleOrderh;
use app\modules\sale\models\SaleOrderl;
use app\modules\warehouse\models\ShpNt;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\data\SqlDataProvider;

/**
 * SaleOrderhSearch represents the model behind the search form about `app\modules\crm\models\SaleOrderh`.
 */
class ShipmentNotifySearch extends ShpNt
{
    public $note_no;
    public $status;
    public $wh_id;
    public $ord_no;
    public $cust_sname;
    public $n_time;
    public $corporate;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['note_no', 'status', 'wh_id', 'ord_no', 'cust_sname', 'n_time'], 'safe'],
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
//        $query=(new Query())
//            ->select([
//                // 通知单id
//                'ioh.sonh_id',
//                // 通知单号
//                'ioh.notify_no',
//                // 通知单状态
////                'ioh.notfy_status',
//                '(CASE ioh.notfy_status '
//                .'WHEN ' .SaleInoutnoteh::STATUS_CANCEL . ' THEN "已取消" '
//                .'WHEN ' .SaleInoutnoteh::STATUS_DEFAULT. ' THEN "待处理" '
//                .'WHEN ' .SaleInoutnoteh::STATUS_PICKING. ' THEN "拣货中" '
//                .'WHEN ' .SaleInoutnoteh::STATUS_PICKED . ' THEN "已拣货" '
//                .'ELSE "" END) as notfy_status',
//                // 下单人
//                //'ioh.notify_from',
//                'hs1.staff_name as notify_from',
//                // 通知日期
//                'ioh.notity_date',
//                // 接单人
//                //'ioh.notify_to',
//                'hs2.staff_name as notify_to',
//                // 商品性质
////                'pdt.pdt_attribute',
//                // 客户名称
//                'cust.cust_sname',
//                // 交易法人
//                'bc.company_name',
//                // 关联订单号
//                'odh.saph_code',
//                // 通知描述 备注 取消原因
//                'ioh.notify_descr',
//                // 订单类型
//                'bt.business_value',
//            ])
//            ->from(['ioh'=>'oms.sale_inoutnoteh'])
//            ->leftJoin('oms.sale_orderh odh', 'ioh.bill_id=odh.soh_id')
//            ->leftJoin('erp.bs_business_type bt','bt.business_type_id=odh.saph_type')
//            ->leftJoin('erp.bs_company bc','bc.company_id=odh.corporate')
//            ->leftJoin('erp.crm_bs_customer_info cust','cust.cust_id=odh.cust_id AND cust_status!=' . CrmCustomerInfo::CUSTOMER_INVALID)
////            ->leftJoin('oms.sale_inoutnotel iol', 'iol.sonh_id=ioh.sonh_id')
////            ->leftJoin('oms.sale_orderl odl','odl.sol_id=iol.lbill_id')
////            ->leftJoin('erp.bs_product pdt','pdt.pdt_id=odl.pdt_id')
//            ->leftJoin('erp.hr_staff hs1','hs1.staff_id=ioh.notify_from') // 下单人名
//            ->leftJoin('erp.hr_staff hs2','hs2.staff_id=ioh.create_by') // 接单人名
//            ->where(['!=','odh.saph_status',SaleOrderh::STATUS_DELETE]);
//        if(isset($params['rows'])){
//            $pageSize = $params['rows'];
//        }else{
//            $pageSize =10;
//        }
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => [
//                'pageSize' => $pageSize,
//            ],
//        ]);
//        $this->load($params);
//        if (!$this->validate()) {
//            return $dataProvider;
//        }
//        $trans = new Trans();
////        return $this->notfy_status;
//        $query->andFilterWhere(['like', 'odh.saph_code', $this->saph_code]) // 订单编号
//        ->andFilterWhere(['like', 'ioh.notify_no', $this->notify_no]) // 通知单号
//        ->andFilterWhere(['ioh.notfy_status' => $this->notfy_status]) // 订单状态
//        ->andFilterWhere(['odh.corporate' => $this->corporate]) // 交易法人
//        ->andFilterWhere([
//            'or',
//            ['like', 'cust.cust_sname', $trans->c2t($this->cust_sname)],
//            ['like', 'cust.cust_sname', $trans->t2c($this->cust_sname)]
//        ]) // 客户名称
//        ->andFilterWhere([
//            'or',
//            ['like', 'hs1.staff_name', $trans->c2t($this->notify_from)],
//            ['like', 'hs1.staff_name', $trans->t2c($this->notify_from)]
//        ]); // 下单人
$queryParam=[];
        $sql="select ss.* from(
SELECT
    s.note_pkid,
	s.note_no,
	s.n_time,
	s.ord_no,
	s.`status`,
	s.urg,
	s.wh_name,
	t.business_value AS ord_type,
	s.corporate,
	s.wh_id,
	(
		SELECT
			cust_sname
		FROM
			erp.crm_bs_customer_info
		WHERE
			cu.cust_id = cust_id
	) cust_sname,
	c.company_name,
	f.staff_name AS pickor,
	f1.staff_name AS operator,
s.soh_id
FROM
	(
		SELECT
			s.note_no,
			p.bsp_svalue AS STATUS,
			o.ord_no,
			s.n_time,
			h.wh_name,
			s.urg,
			o.corporate,
			o.ord_type,
			o.ord_id,
			s.operator,
			s.pickor,
			s.wh_id,
			s.note_pkid,
			s.soh_id
		FROM
			wms.shp_nt s
		LEFT JOIN oms.ord_info o ON s.soh_id = o.ord_id
		LEFT JOIN erp.bs_pubdata p ON p.bsp_id = s. STATUS
		LEFT JOIN wms.bs_wh h ON h.wh_id = s.wh_id
	) s
LEFT JOIN erp.bs_company c ON c.company_id = s.corporate
LEFT JOIN erp.bs_business_type t ON s.ord_type = t.business_type_id
LEFT JOIN oms.ord_cust cu ON cu.ord_id = s.ord_id
LEFT JOIN erp.hr_staff f ON f.staff_id = s.pickor
LEFT JOIN erp.hr_staff f1 ON f.staff_code = s.operator)ss
WHERE 1 = 1 ";
        $this->load($params);
        if (!$this->validate()) {
            return $sql;
        }
        if(!empty($params["note_no"])){
            $note_no=str_replace(' ', '', $params["note_no"]);
            $sql=$sql."and ss.note_no like '%$note_no%' ";
        }
        if(!empty($params["corporate"])){
            $queryParam[':corporate']=$params['corporate'];
            $sql=$sql."and ss.corporate=:corporate ";
        }
        if(!empty($params["status"])){
           //$queryParam[':status']=$params['status'];
            $sql=$sql."and ss.status='{$params['status']}' ";
        }
        if(!empty($params["wh_id"])){
            $queryParam[':wh_id']=$params['wh_id'];
            $sql=$sql."and ss.wh_id=:wh_id ";
        }
        if(!empty($params["ord_no"])){
            $ord_no=str_replace(' ', '', $params["ord_no"]);
            $sql=$sql."and ss.ord_no='$ord_no' ";
        }
        if(!empty($params["cust_sname"])){
            $cust_sname=str_replace(' ', '', $params["cust_sname"]);
            $sql=$sql."and ss.ord_no='$cust_sname' ";
        }
        if (!empty($params['start_date'])) {
            $queryParam[':start_date'] = date('Y-m-d H:i:s', strtotime($params['start_date']));
            $sql .= " and ss.n_time >= :start_date";
        }
        if (!empty($params['end_date'])) {
            $queryParam[':end_date'] = date('Y-m-d H:i:s', strtotime($params['end_date'] . '+1 day'));
            $sql .= " and  ss.n_time < :end_date";
        }
        $sql.="order by ss.n_time desc";
        $totalCount = Yii::$app->getDb('wms')->createCommand("select count(*) from ( {$sql} ) a ", $queryParam)->queryScalar();
        $dataProvider = new SqlDataProvider([
            'db' => 'db',
            'sql' => $sql,
            'params' => $queryParam,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => $params['rows']
            ]
        ]);
        return $dataProvider;
    }

    /**
     * 点击主表获取子表商品信息
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchOrderProducts($params)
    {
//    $sql="select ss.*,t.pdt_name,(select brand_name_cn  FROM pdt.bs_brand where t.brand_id=brand_id) brand_name_cn
//            ,(select bsp_svalue from erp.bs_pubdata where t.unit=bsp_id)unit from (SELECT s.*,o.pdt_pkid,d.bdm_sname,o.tp_spec,h.wh_name from
//            (SELECT FORMAT(dt.nums,2)nums,dt.part_no,FORMAT(o.sapl_quantity,2)sapl_quantity,DATE_FORMAT(o.request_date,'%Y/%m/%d')request_date,FORMAT(o.uprice_tax_o,5)uprice_tax_o,FORMAT(o.tprice_tax_o,2)tprice_tax_o,o.distribution,o.sapl_remark,dt.wh_id FROM wms.shp_nt t
//            LEFT JOIN wms.shp_nt_dt dt on t.note_pkid=dt.note_pkid
//            LEFT JOIN oms.ord_dt o on t.soh_id=o.ord_id
//            where t.note_pkid={$params["id"]} GROUP BY dt.part_no)s
//            LEFT JOIN pdt.bs_partno o on s.part_no=o.part_no
//            LEFT JOIN wms.bs_deliverymethod d  on d.bdm_id=s.distribution
//            LEFT JOIN wms.bs_wh h on h.wh_id=s.wh_id)ss
//            LEFT JOIN pdt.bs_product t on t.pdt_pkid=ss.pdt_pkid";
        $sql="select ss.*,t.pdt_name,(select brand_name_cn  FROM pdt.bs_brand where t.brand_id=brand_id) brand_name_cn
            ,(select bsp_svalue from erp.bs_pubdata where t.unit=bsp_id)unit from 
        (select s.*,o.pdt_pkid,o.tp_spec 
        from (SELECT 
            o.ord_id,
            o.ord_dt_id,
            FORMAT(snt.nums, 2) nums,
            o.sapl_remark,
            snt.part_no,
            FORMAT(o.sapl_quantity, 2) sapl_quantity,
            DATE_FORMAT(o.request_date, '%Y/%m/%d') request_date,
            FORMAT(o.uprice_tax_o, 5) uprice_tax_o,
            FORMAT(o.tprice_tax_o, 2) tprice_tax_o,
            (select d.bdm_sname from wms.bs_deliverymethod d where d.bdm_id=o.distribution)bdm_sname,
        (select h.wh_name from wms.bs_wh h where h.wh_id=snt.wh_id)wh_name,
          snt.wh_id,
          snt.marks
        FROM(
        select DISTINCT t.soh_id from wms.shp_nt t, wms.shp_nt_dt dt 
        where t.note_pkid=dt.note_pkid
         AND t.note_pkid={$params["id"]}) a,oms.ord_dt o,wms.shp_nt_dt snt
        where a.soh_id=o.ord_id
        AND o.ord_dt_id=snt.sol_id
        and snt.note_pkid={$params["id"]})s LEFT JOIN pdt.bs_partno o on s.part_no=o.part_no)ss
        LEFT JOIN pdt.bs_product t on t.pdt_pkid=ss.pdt_pkid";
//        $totalCount = Yii::$app->getDb('wms')->createCommand("select count(*) from ( {$sql} ) a ", $params["id"])->queryScalar();
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => $params,
        ]);
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
                'ioh.sonh_id',
                // 通知单号
                'ioh.notify_no',
                // 通知单状态
//                'ioh.notfy_status',
                '(CASE ioh.notfy_status '
                .'WHEN ' .SaleInoutnoteh::STATUS_DEFAULT. ' THEN "待处理" '
                .'WHEN ' .SaleInoutnoteh::STATUS_PICKING. ' THEN "拣货中" '
                .'WHEN ' .SaleInoutnoteh::STATUS_PICKED . ' THEN "已拣货" '
                .'ELSE "" END) as notfy_status',
                // 下单人
                //'ioh.notify_from',
                'hs1.staff_name as notify_from',
                // 通知日期
                'ioh.notity_date',
                // 通知优先级（紧急程度）
                '(CASE ioh.pri '
                .'WHEN ' .SaleInoutnoteh::PRI_GENERAL. ' THEN "一般" '
                .'WHEN ' .SaleInoutnoteh::PRI_URGENT. ' THEN "紧急" '
                .'WHEN ' .SaleInoutnoteh::PRI_EXTRA_URGENT . ' THEN "特急" '
                .'ELSE "" END) as pri',
                // 接单人
                //'ioh.notify_to',
                'hs2.staff_name as notify_to',
                // 商品性质
                'pdt.pdt_attribute',
                // 客户名称
                'cust.cust_sname',
                // 交易法人
                'bc.company_name',
                // 关联订单号
                'odh.saph_code',
                // 订单类型
                'bt.business_value',
                // 出仓仓库
                'bw.wh_name',
                // 收货人
                'odh.receiver',
                // 收货人联系方式
                'odh.receiver_tel',
                // 收货地址
                'odh.delivery_addr'
            ])
            ->from(['ioh'=>'oms.sale_inoutnoteh'])
            ->leftJoin('oms.sale_orderh odh', 'ioh.bill_id=odh.soh_id')
            ->leftJoin('erp.bs_company bc','bc.company_id=odh.corporate')
            ->leftJoin('erp.bs_business_type bt','bt.business_type_id=odh.saph_type')
            ->leftJoin('erp.crm_bs_customer_info cust','cust.cust_id=odh.cust_id AND cust_status!=' . CrmCustomerInfo::CUSTOMER_INVALID)
            ->leftJoin('oms.sale_inoutnotel iol', 'iol.sonh_id=ioh.sonh_id')
            ->leftJoin('oms.sale_orderl odl','odl.sol_id=iol.lbill_id')
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('erp.bs_product pdt','pdt.pdt_id=odl.pdt_id')
            ->leftJoin('erp.hr_staff hs1','hs1.staff_id=ioh.notify_from') // 下单人名
            ->leftJoin('erp.hr_staff hs2','hs2.staff_id=ioh.create_by') // 接单人名
            ->where(['and', ['ioh.sonh_id' => $id], ['!=','odh.saph_status',SaleOrderh::STATUS_DELETE]]);
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
                // 出仓仓库
                'bw.wh_name',
                // 商品品名
                'pdt.pdt_name',
                // 料号
                'pdt.pdt_no',
                // 品牌
                'bb.BRAND_NAME_CN',
                // 类别
//                'ctg.category_sname',
                'erp.func_get_pcategory(ctg.category_id) as ctg_pname',
                // 规格
                'ca.ATTR_NAME',
                // 下单数量
                'odl.sapl_quantity',
                // 通知出货数量
                'iol.outnoti_qty',
                // 商品库存
                'bi.invt_num',
                // 单位
//                'pdt.unit',
                'cu.unit_name',
                // 商品单价（未税）
//                'odl.uprice_ntax_o',
                // 商品单价（含税）
                'odl.uprice_tax_o',
                // 商品总价（未税）
//                'odl.tprice_ntax_o',
                // 商品总价（含税）
                'odl.tprice_tax_o',
                // 折扣
//                'odl.discount',
                // 体积？？
//                'pdt.pdt_vol',
                // 重量
//                'odl.suttle',
                // 运输方式
//                'btr.tran_sname',
                // 配送方式
                'bd.bdm_sname',
                // 运费
//                'odl.freight',
                // 需求交期
//                'odl.request_date',
                // 交期
                'odl.consignment_date',
                // 备注
                //'odl.sapl_remark',
                'iol.sonl_remark',
            ])
            ->from(['iol'=>'oms.sale_inoutnotel'])
            ->leftJoin('oms.sale_orderl odl', 'iol.lbill_id=odl.sol_id')
            ->leftJoin('wms.bs_wh bw', 'bw.wh_id=odl.whs_id')
            ->leftJoin('bs_product pdt', 'pdt.pdt_id=odl.pdt_id')
            ->LeftJoin('bs_brand bb',"bb.brand_id=pdt.brand_id")
            ->leftJoin('bs_category ctg','ctg.category_id=pdt.bs_category_id')
            ->leftJoin('category_attr ca','ca.CATEGORY_ATTR_ID=pdt.tp_spec')
            ->leftJoin('wms.l_bs_invt bi','bi.pdt_id=odl.pdt_id') // 库存表
            ->leftJoin('bs_category_unit cu','cu.id=pdt.unit')
            ->leftJoin('wms.bs_transport btr','btr.tran_id=odl.transport')
            ->leftJoin('wms.bs_deliverymethod bd', 'bd.bdm_id=odl.distribution')
            ->where([
                'and',
                ['!=','sapl_status',SaleOrderl::STATUS_DELETE],
                ['=','iol.sonh_id', $id]
            ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    //导出
    public function searchApply($params){
        $queryParam=[];
        $sql="select ss.* from(
SELECT
    s.note_pkid,
	s.note_no,
	s.n_time,
	s.ord_no,
	s.`status`,
	s.urg,
	s.wh_name,
	t.business_value AS ord_type,
	s.corporate,
	s.wh_id,
	(
		SELECT
			cust_sname
		FROM
			erp.crm_bs_customer_info
		WHERE
			cu.cust_id = cust_id
	) cust_sname,
	c.company_name,
	f.staff_name AS pickor,
	f1.staff_name AS operator,
s.soh_id
FROM
	(
		SELECT
			s.note_no,
			p.bsp_svalue AS STATUS,
			o.ord_no,
			s.n_time,
			h.wh_name,
			s.urg,
			o.corporate,
			o.ord_type,
			o.ord_id,
			s.operator,
			s.pickor,
			s.wh_id,
			s.note_pkid,
			s.soh_id
		FROM
			wms.shp_nt s
		LEFT JOIN oms.ord_info o ON s.soh_id = o.ord_id
		LEFT JOIN erp.bs_pubdata p ON p.bsp_id = s. STATUS
		LEFT JOIN wms.bs_wh h ON h.wh_id = s.wh_id
	) s
LEFT JOIN erp.bs_company c ON c.company_id = s.corporate
LEFT JOIN erp.bs_business_type t ON s.ord_type = t.business_type_id
LEFT JOIN oms.ord_cust cu ON cu.ord_id = s.ord_id
LEFT JOIN erp.hr_staff f ON f.staff_id = s.pickor
LEFT JOIN erp.hr_staff f1 ON f.staff_code = s.operator)ss
WHERE 1 = 1 ";
        $this->load($params);
        if (!$this->validate()) {
            return $sql;
        }
        if(!empty($params["note_no"])){
            $note_no=str_replace(' ', '', $params["note_no"]);
            $sql=$sql."and ss.note_no like '%$note_no%' ";
        }
        if(!empty($params["corporate"])){
            $queryParam[':corporate']=$params['corporate'];
            $sql=$sql."and ss.corporate=:corporate ";
        }
        if(!empty($params["status"])){
            //$queryParam[':status']=$params['status'];
            $sql=$sql."and ss.status='{$params['status']}' ";
        }
        if(!empty($params["wh_id"])){
            $queryParam[':wh_id']=$params['wh_id'];
            $sql=$sql."and ss.wh_id=:wh_id ";
        }
        if(!empty($params["ord_no"])){
            $ord_no=str_replace(' ', '', $params["ord_no"]);
            $sql=$sql."and ss.ord_no='$ord_no' ";
        }
        if(!empty($params["cust_sname"])){
            $cust_sname=str_replace(' ', '', $params["cust_sname"]);
            $sql=$sql."and ss.ord_no='$cust_sname' ";
        }
        if (!empty($params['start_date'])) {
            $queryParam[':start_date'] = date('Y-m-d H:i:s', strtotime($params['start_date']));
            $sql .= " and ss.n_time >= :start_date";
        }
        if (!empty($params['end_date'])) {
            $queryParam[':end_date'] = date('Y-m-d H:i:s', strtotime($params['end_date'] . '+1 day'));
            $sql .= " and  ss.n_time < :end_date";
        }
        $sql.="order by ss.n_time desc";
        $dataProvider=new SqlDataProvider([
            'sql' => $sql,
            'params'=>$queryParam,
            'pagination'=>[
                'pageSize'=>empty($params['rows'])?'':$params['rows']
            ],
        ]);
        return $dataProvider;
    }

}
