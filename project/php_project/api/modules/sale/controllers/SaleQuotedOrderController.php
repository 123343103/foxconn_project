<?php

namespace app\modules\sale\controllers;

use app\classes\Transportation;
use app\modules\common\models\AuditState;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmCreditMaintain;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\show\CrmEmployeeShow;
use app\modules\sale\models\PriceDt;
use app\modules\sale\models\PricePay;
use app\modules\sale\models\PriceValue;
use app\modules\sale\models\SaleCustrequireL;
use app\modules\sale\models\search\PriceDtSearch;
use app\modules\sale\models\show\PriceInfoShow;
use app\modules\warehouse\models\BsTransport;
use app\modules\warehouse\models\BsWh;
use Yii;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsPayment;
use app\modules\sale\models\PriceInfo;
use app\modules\sale\models\ReqInfo;
use app\modules\sale\models\search\PriceInfoSearch;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;
use yii\widgets\ActiveForm;

class SaleQuotedOrderController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\PriceInfo';

    /**
     * @return mixed
     * 报价单列表api
     */
    public function actionIndex()
    {
        $search = new PriceInfoSearch();
        $dataProvider = $search->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return array
     * 订单报价
     */
    public function actionCreate($id){
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $transaction = Yii::$app->oms->beginTransaction();
            try{
                /*主表*/
                $quotedHModel = PriceInfo::findOne($id);
                if($quotedHModel['audit_id'] == PriceInfo::STATUS_REVIEW_REJECT){
                    $quotedHModel->audit_id = PriceInfo::STATUS_DEFAULT;
                }
//                //价格信息表
//                $valueModel = PriceValue::findOne($id);
                if (!$quotedHModel->load($post) || !$quotedHModel->save()) {
                    throw new \Exception(current($quotedHModel->getFirstErrors()));
                }
                /*订单子表更新*/
                if(!empty($post['PriceDt'])){
                    foreach ($post['PriceDt'] as $key => $val){
                        $dt = PriceDt::findOne($val['price_dt_id']);
                        $products['PriceDt'] = $val;
                        $dt->uprice_ntax_o = $val["uprice_tax_o"] / 1.17;  //未税销售单价
                        $dt->tprice_ntax_o = $val["tprice_tax_o"] / 1.17;  //未税总价
                        if (!$dt->load($products) || !$dt->save()) {
                            throw new \Exception(current($dt->getFirstErrors()));
                        }
                    }
                }
                //付款记录
                $count = PricePay::find()->where(['price_id' => $quotedHModel->price_id])->count();
                if ($count) {
                    if (PricePay::deleteAll(['price_id' => $quotedHModel->price_id]) < $count) {
                        throw  new \Exception("付款记录表更新失败!");
                    }
                }
                if (!empty($post['PricePay'])) {
                    foreach ($post['PricePay'] as $k => $v) {
                        $ReqPayModel = new PricePay();
                        $v["price_id"] = $quotedHModel->price_id;
                        $value["PricePay"] = $v;
                        if (!$ReqPayModel->load($value) || !$ReqPayModel->save()) {
                            throw new \Exception(current($ReqPayModel->getFirstErrors()));
                        }
                    }
                } else {
                    $ReqPayModel = new PricePay();
                    $ReqPayModel->price_id = $quotedHModel->price_id;
                    $ReqPayModel->stag_cost = $quotedHModel->req_tax_amount;
                    if (!$ReqPayModel->save()) {
                        throw new \Exception(current($ReqPayModel->getFirstErrors()));
                    }
                }

            }catch (\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
            $transaction->commit();
            $msg = array('id' => $id,'msg'=>$quotedHModel['price_no']);
            return $this->success('',$msg);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 转报价页面信息
     */
    public function actionView($id){
        $model['info'] = PriceInfoShow::findOne($id);
        $model['dt'] = $this->getPriceDt($id);
        $tran = new Transportation();
        foreach ($model['dt'] as $key => &$val) {
            $val["transport"] = $tran->getAllTansType($val["prt_pkid"]);    //运输方式
            $quotedLModel[$key] = $val;
        }
        return $model;
    }

    /**
     * @param $code
     * @return null|static
     * 销售人员信息
     */
    public function actionSeller($code){
        return CrmEmployeeShow::find()->where(['and',["staff_code" => $code],['sale_status'=>CrmEmployee::SALE_STATUS_DEFAULT]])->one();
    }

    /**
     * @param $id
     * @return array
     * 取消报价
     */
    public function actionCancleQuote($id){
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $arr = explode(',',$id);
            $name = '';
            foreach ($arr as $key => $val){
                $priceInfo = PriceInfo::findOne($val);
                $priceInfo->audit_id = AuditState::STATUS_CANCLE_OFFER;
                $priceInfo->load($post);
                $name = $name.$priceInfo['price_no'].',';
                $res = $priceInfo->save();
            }
            $price_no = trim($name,',');
            if ($res) {
                $msg = array('id' => $id, 'msg' => '取消报价"' . $price_no . '"');
                return $this->success('',$msg);
            } else {
                return $this->error();
            }
        }

    }

    public function actionDetailList(){
        $model = new PriceDtSearch();
        $dataProvider = $model->searchList(Yii::$app->request->queryParams);
        $result = $dataProvider->getModels();
        foreach ($result as $k => $v) {
            $v["dis_count_price"] = sprintf('%.2f',$v["fixed_price"] * $v["sapl_quantity"]);
            if($v['uprice_tax_o'] == '面议'){
                $v['tprice_ntax_o'] = '面议';
                $v['tprice_tax_o'] = '面议';
                $v['uprice_ntax_o'] = '面议';
            }
            $result[$k] = $v;
        }
        return [
            'rows' => $result,
            'total' => $dataProvider->totalCount,
        ];
//        $search = new PriceDtSearch();
//        $dataProvider = $search->searchList(Yii::$app->request->queryParams);
//        $model = $dataProvider->getModels();
//        $list['rows'] = $model;
//        $list["total"] = $dataProvider->totalCount;
//        return $list;
    }

    /**
     * @return mixed
     * 下拉列表
     */
    public function actionDownList(){
        //订单类型
        $downList['orderType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'saqut'])->all();
        // 交易法人
        $downList['corporate'] = BsCompany::find()->select(['company_id', 'company_name'])->where(['company_status' => BsCompany::STATUS_DEFAULT])->all();
        // 报价单状态
        $downList['quoted_status'] = AuditState::find()->select(['audit_id','audit_name'])->all();
        // 付款方式
        $downList['payment'] = BsPayment::find()->select(['pac_id', 'pac_code', 'pac_sname'])->all();
        // 配送方式
        $downList['dispatching'] = BsTransport::find()->select(['tran_id', 'tran_code', 'tran_sname'])->where(['grade' => 0])->all();
        // 运输方式
        $downList['transport'] = BsTransport::find()->select(['tran_id', 'tran_code', 'tran_sname'])->where(['grade' => 1])->all();
        // 仓库信息
        $downList['warehouse'] = BsWh::find()->select(['wh_id', 'wh_code', 'wh_name'])->all();
        // 交易币别
        $downList['currency'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['and',['bsp_stype' => BsPubdata::SUPPLIER_TRADE_CURRENCY],['bsp_status'=>BsPubdata::STATUS_DEFAULT]])->all();
        // 支付类型
        $downList['payType'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['and',['bsp_stype' => BsPubdata::PAY_TYPE],['bsp_status'=>BsPubdata::STATUS_DEFAULT]])->all();
        //账信类型
        $downList['creditType'] = CrmCreditMaintain::find()->select(['credit_name', 'id'])->where(['=', 'credit_status', CrmCreditMaintain::STATUS_DEFAULT])->all();
        return $downList;
    }

    /**
     * @param $id
     * @return ActiveDataProvider
     * 报价子表商品信息
     */
    public function getPriceDt($id)
    {
        $query = (new Query())
            ->select([
                'bpn.prt_pkid',                      // 商品id
                'bpn.part_no pdt_no',                // 商品料号
                'bpn.min_order',                     // 最小起訂量
                'bpn.isselftake self_take',          // 是否可以自提
                'price.price',                       // 價格(含稅)
                'bpt.pdt_name',                      // 商品名稱
                'odl.sapl_quantity',                 // 下单数量
                'odl.price_dt_id',                   // 報價明細pkid
                'odl.price_id',                      // 報價單pkid
                'bp.pdt_qty',                        // 包裝內商品數量
                'odl.uprice_ntax_o',                 // 單價(未稅)-原幣
                'odl.cess',                          // 税率
                'odl.discount',                      // 折扣
                'odl.transport transport_id',        // 运输方式code
                'btp_2.tran_sname transport_name',   // 运输方式
                'odl.distribution',                  // 配送方式id
                'btp_1.tran_sname distribution_name',// 配送方式
                'odl.freight',                       // 未稅物流費用
                'odl.tax_freight',                   // 含稅物流費用
                'odl.whs_id',                        // 出仓仓库id
                'wh.wh_name wh_name',                // 出仓仓库
                'odl.request_date',                  // 需求交期
                'odl.consignment_date',              // 交期
                'odl.sapl_remark',                   // 备注
                'odl.uprice_tax_o',                  // 商品单价（含税）
                'odl.tprice_ntax_o',                 // 商品总价（未税）
                'odl.tprice_tax_o',                  // 商品总价（含税）
                'odl.suttle',                        // 重量
            ])
            ->from(['odl' => 'oms.price_dt'])
            ->leftJoin('oms.price_info odh', 'odl.price_id=odh.price_id')
            ->leftJoin('pdt.bs_partno bpn', 'bpn.prt_pkid=odl.prt_pkid')
            ->leftJoin('pdt.bs_price price', 'price.prt_pkid = bpn.prt_pkid')
            ->leftJoin('pdt.bs_product bpt', 'bpt.pdt_pkid=bpn.pdt_pkid')
            ->leftJoin('pdt.bs_pack bp', 'bp.prt_pkid=bpn.prt_pkid')
            ->leftJoin('wms.bs_transport btp_1','btp_1.tran_id = odl.distribution')
            ->leftJoin('wms.bs_transport btp_2','btp_2.tran_code = odl.transport')
            ->leftJoin('wms.bs_wh wh','wh.wh_id = odl.whs_id')
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
            ->andwhere(['odl.price_id' => $id])
            ->groupBy("price_dt_id")
            ->all()
        ;

        return $query;
    }

    //获取客户帐信额度
    public function actionGetCustCredit($id, $cur)
    {
        $sql = "SELECT
	                c.cust_id,
	                c.currency,
	                li.credit_type,
	                li.credit_limit,
	                li.surplus_limit,
	                ifnull(li.approval_limit,0) approval_limit,
	                type.business_value credit_name
                FROM
	                erp.crm_credit_apply c
                LEFT JOIN erp.crm_credit_limit li ON li.credit_id = c.credit_id
                LEFT JOIN erp.bs_business_type type ON li.credit_type = type.business_type_id WHERE
		        c.cust_id = " . $id . " AND c.currency=" . $cur;
        $totalCount = Yii::$app->db->createCommand("select count(*) from ({$sql}) A")->queryScalar();
        $provider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $totalCount,
        ]);
        return [
            'rows' => $provider->getModels(),
            'total' => $provider->totalCount
        ];
    }

    /**
     * @return PriceDtSearch|array
     * 通过id获取子表信息
     */
    public function actionGetProduct()
    {

        $params = Yii::$app->request->queryParams;
        $model = new PriceDtSearch();
        $model = $model->search($params)->getModels();
        foreach ($model as $k => $v) {
            $v["dis_count_price"] = sprintf('%.2f',$v["uprice_tax_o"] * $v["sapl_quantity"]);
            $v['product'] = "<div class='text-left'><span class='text-left'>料号: {$v['part_no']}</span>"
                . "</br><span>品名: {$v['pdt_name']}</span>"
                . "</br><span class='text-left'>规格: {$v['tp_spec']}</span></div>";
//                . "</br><span class='text-left'>规格: {$v['specification']}</span></div>";
            $v['fixed_price'] =  ($v["fixed_price"]!='-1'?number_format($v["fixed_price"], '5', '.', ''):"面议");
            if($v['fixed_price'] == '面议'){
                $v['tprice_tax_o'] = '面议';
                $v['tprice_ntax_o'] = '面议';
            }
            $model[$k] = $v;
        }
        return $model;
    }

    /**
     * @param $pdt
     * @param $num
     * @param $addr
     * @param $TransType
     * @return mixed
     * 获取运输方式和运费
     */
    public function actionGetFreight($pdt, $num, $addr, $TransType)
    {
        $town = BsDistrict::findOne(['district_id' => $addr]);
        $city = BsDistrict::findOne(['district_id' => $town->district_pid]);
        $transportation = new Transportation();
        return $transportation->getLogisticsCost($pdt, $city->district_pid, $town->district_pid, $num, $TransType);
    }
}
