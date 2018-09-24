<?php

namespace app\modules\sale\controllers;

use app\classes\Transportation;
use app\models\User;
use app\modules\common\models\BsAddress;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsCategoryUnit;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsForm;
use app\modules\common\models\BsPayCondition;
use app\modules\common\models\BsPayment;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsTransaction;
use app\modules\crm\models\CrmCreditApply;
use app\modules\crm\models\CrmCreditLimit;
use app\modules\crm\models\CrmCreditMaintain;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\RPrtWh;
use app\modules\ptdt\models\show\BsPartnoSelectorShow;
use app\modules\sale\models\PriceDt;
use app\modules\sale\models\PriceFile;
use app\modules\sale\models\PriceInfo;
use app\modules\sale\models\PricePay;
use app\modules\sale\models\ReqAddr;
use app\modules\sale\models\ReqDt;
use app\modules\sale\models\ReqFile;
use app\modules\sale\models\ReqInfo;
use app\modules\sale\models\ReqPay;
use app\modules\sale\models\ReqRcvAddr;
use app\modules\sale\models\ReqSale;
use app\modules\sale\models\ReqValue;
use app\modules\sale\models\search\ReqDtSearch;
use app\modules\sale\models\search\ReqInfoSearch;
use app\modules\crm\models\show\CrmEmployeeShow;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\warehouse\models\BsTransport;
use app\modules\warehouse\models\BsWh;
use Yii;
use  app\controllers\BaseActiveController;
use app\modules\common\models\BsDistrict;
use yii\bootstrap\Html;
use yii\data\SqlDataProvider;
use yii\db\Query;
use yii\helpers\Json;


class SaleCustOrderController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmEmployee';

    public function actionIndex()
    {
        $search = new ReqInfoSearch();
        $dataProvider = $search->requireSearch(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    public function actionList()
    {
        $search = new ReqDtSearch();
        //todo 待改
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $search->search($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    // 新增报价单
    public function actionCreate()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            $quotedHModel = new ReqInfo();
            $transaction = Yii::$app->oms->beginTransaction();
            $quotedHModel->ba_id = $post['delivery_addr'];
            $orderFrom = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['and', ['bsp_stype' => BsPubdata::ORDER_FROM], ['bsp_svalue' => '平台新增订单']])->one();
            $quotedHModel->origin_hid = $orderFrom->bsp_id;

            //收货地址信息
            $bsAddress = BsAddress::findOne($post['delivery_addr']);
            $quotedHModel->receipter = Html::decode($bsAddress->contact_name);
            $quotedHModel->receipter_Tel = $bsAddress->contact_tel;
            $quotedHModel->addr_tel = $bsAddress->tel;
            $quotedHModel->ba_id = $post['delivery_addr'];
            $quotedHModel->receipt_areaid = $bsAddress->district;
            $quotedHModel->receipt_Address = Html::decode($bsAddress->address);
            try {
                if (!$quotedHModel->load($post) || !$quotedHModel->save()) {
                    throw new \Exception(current($quotedHModel->getFirstErrors()));
                }
                $req_id = $quotedHModel->req_id;

                if (!empty($post['orderL'])) {
                    foreach ($post['orderL'] as $k => $v) {
                        $quotedLModel = new ReqDt();
                        $value["ReqDt"] = $v;
                        $quotedLModel->req_id = $req_id;
                        $quotedLModel->uprice_ntax_o = $v["uprice_tax_o"] / 1.17;  //未税销售单价
                        $quotedLModel->tprice_ntax_o = $v["tprice_tax_o"] / 1.17;  //未税总价
                        if (!$quotedLModel->load($value) || !$quotedLModel->save()) {
                            throw new \Exception(current($quotedLModel->getFirstErrors()));
                        }
                    }
                }
                //付款记录
                if (!empty($post['ReqPay'])) {
                    foreach ($post['ReqPay'] as $k => $v) {
                        $ReqPayModel = new ReqPay();
                        $v["req_id"] = $req_id;
                        $value["ReqPay"] = $v;
                        if (!$ReqPayModel->load($value) || !$ReqPayModel->save()) {
                            throw new \Exception(current($ReqPayModel->getFirstErrors()));
                        }
                    }
                } else {
                    $ReqPayModel = new ReqPay();
                    $ReqPayModel->req_id = $req_id;
                    $ReqPayModel->stag_cost = $quotedHModel->req_tax_amount;
                    if (!$ReqPayModel->save()) {
                        throw new \Exception(current($ReqPayModel->getFirstErrors()));
                    }
                }
                //附件信息
                if (!empty($post['Files'][0]["file_old"])) {
                    foreach ($post['Files'] as $k => $v) {
                        $FileModel = new ReqFile();
                        $v["req_id"] = $req_id;
                        $value["ReqFile"] = $v;
                        if (!$FileModel->load($value) || !$FileModel->save()) {
                            throw new \Exception(current($FileModel->getFirstErrors()));
                        }
                    }
                }
                $transaction->commit();
                return $this->success('报价订单保存成功', $quotedHModel->req_id);
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return $this->actionGetDownList();
    }

    // 更新报价单
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $transaction = Yii::$app->oms->beginTransaction();

            try {
                //主表
                $quotedHModel = ReqInfo::findOne($id);

                $bsAddress = BsAddress::findOne($post['delivery_addr']);
                $quotedHModel->receipter = Html::decode($bsAddress->contact_name);
                $quotedHModel->receipter_Tel = $bsAddress->contact_tel;
                $quotedHModel->addr_tel = $bsAddress->tel;
                $quotedHModel->ba_id = $post['delivery_addr'];
                $quotedHModel->receipt_areaid = $bsAddress->district;
                $quotedHModel->receipt_Address = Html::decode($bsAddress->address);
                if (!$quotedHModel->load($post) || !$quotedHModel->save()) {
                    throw new \Exception(current($quotedHModel->getFirstErrors()));
                }
                //订单子表更新
                $count = ReqDt::find()->where(['req_id' => $quotedHModel->req_id])->count();
                if (ReqDt::deleteAll(['req_id' => $quotedHModel->req_id]) < $count) {
                    throw  new \Exception("订单子表更新失败!");
                };
                if (!empty($post['orderL'])) {
                    foreach ($post['orderL'] as $k => $v) {
                        $quotedLModel = new ReqDt();
                        $value["ReqDt"] = $v;
                        $quotedLModel->req_id = $quotedHModel->req_id;
                        $quotedLModel->uprice_ntax_o = $v["uprice_tax_o"] / 1.17;  //未税销售单价
                        $quotedLModel->tprice_ntax_o = $v["tprice_tax_o"] / 1.17;  //未税总价
                        if (!$quotedLModel->load($value) || !$quotedLModel->save()) {
                            throw new \Exception(current($quotedLModel->getFirstErrors()));
                        }
                    }
                }
                //付款记录
                $count = ReqPay::find()->where(['req_id' => $quotedHModel->req_id])->count();
                if ($count) {
                    if (ReqPay::deleteAll(['req_id' => $quotedHModel->req_id]) < $count) {
                        throw  new \Exception("付款记录表更新失败!");
                    }
                }
                if (!empty($post['ReqPay'])) {
                    foreach ($post['ReqPay'] as $k => $v) {
                        $ReqPayModel = new ReqPay();
                        $v["req_id"] = $quotedHModel->req_id;
                        $value["ReqPay"] = $v;
                        if (!$ReqPayModel->load($value) || !$ReqPayModel->save()) {
                            throw new \Exception(current($ReqPayModel->getFirstErrors()));
                        }
                    }
                } else {
                    $ReqPayModel = new ReqPay();
                    $ReqPayModel->req_id = $quotedHModel->req_id;
                    $ReqPayModel->stag_cost = $quotedHModel->req_tax_amount;
                    if (!$ReqPayModel->save()) {
                        throw new \Exception(current($ReqPayModel->getFirstErrors()));
                    }
                }
                if (!empty($post['Files'][0]["file_old"])) {
                    //附件信息
                    $count = ReqFile::find()->where(['req_id' => $quotedHModel->req_id])->count();
                    if ($count) {
                        if (ReqFile::deleteAll(['req_id' => $quotedHModel->req_id]) < $count) {
                            throw  new \Exception("附件信息表更新失败!");
                        }
                    }
                    foreach ($post['Files'] as $k => $v) {
                        $FileModel = new ReqFile();
                        $v["req_id"] = $quotedHModel->req_id;;
                        $value["ReqFile"] = $v;
                        if (!$FileModel->load($value) || !$FileModel->save()) {
                            throw new \Exception(current($FileModel->getFirstErrors()));
                        }
                    }
                }
                $transaction->commit();
                return $this->success('报价订单保存成功', $quotedHModel->req_id);
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        $quotedHModel = ReqInfo::findOne($id);
//        $quotedLModel = SaleCustrequireL::find()->where(['saph_id' => $quotedHModel->saph_id])->all();
        $search = new ReqInfoSearch();
        $quotedLModel = "";
        $quotedL = $search->searchOrderL2($id)->getModels();
        $tran = new Transportation();
        foreach ($quotedL as $key => &$val) {
            $val["transport"] = $tran->getAllTansType($val["prt_pkid"]);    //运输方式
            $val["wh"] = RPrtWh::find()->where(["prt_pkid" => $val["prt_pkid"]])->select('wh_id')->asArray()->all();  //自提仓库
            foreach ($val["wh"] as &$v) {
                $v["wh_name"] = BsWh::findOne($v["wh_id"])->wh_name;
            }
            $quotedLModel[$key] = $val;
        }
//        $quotedLModel = $search->searchOrderL2($id)->getModels();
        $list[] = $quotedHModel;    //主订单
        $list[] = $quotedLModel;    //子订单
        $staffCode = HrStaff::findone($quotedHModel->nwer)->staff_code;
        $list[] = CrmEmployeeShow::find()->where(['and', ['staff_code' => $staffCode], ['sale_status' => CrmEmployee::SALE_STATUS_DEFAULT]])->one();    //销售员信息
        $list[] = CrmCustomerInfoShow::findOne($quotedHModel->cust_id);    //客户信息
        $list[] = $search->getCreditPay($quotedHModel->req_id);
//        $list[] = ReqPay::find()->where(["req_id" => $quotedHModel->req_id])->all();    //付款信息
        $list[] = ReqFile::find()->where(["req_id" => $quotedHModel->req_id])->all();    //附件信息
        return $list;
    }


    // 订单详情
    public function actionOrderDetail($id)
    {
        $model = new ReqInfoSearch();
        $quotedHModel = ReqInfo::findOne($id);
        $dataProviderH = current($model->searchOrderH($id)->getModels());
        $data['pay'] = $model->getCreditPay($quotedHModel->req_id);
        $data['attachments'] = ReqFile::find()->where(["req_id" => $id])->all();
        $staffCode = HrStaff::findone($quotedHModel->nwer)->staff_code;
        $data['seller'] = CrmEmployeeShow::find()->where(['and', ['staff_code' => $staffCode], ['sale_status' => CrmEmployee::SALE_STATUS_DEFAULT]])->one();    //销售员信息
        $data = array_merge($dataProviderH, $data);
        return $data;
    }

    // 订单明细列表
    public function actionOrderList($id)
    {
        $search = new ReqInfoSearch();
        $quotedLModel = "";
        $quotedL = $search->searchOrderL2($id)->getModels();
        $tran = new Transportation();
        foreach ($quotedL as $key => &$val) {
            $val["transport"] = $tran->getAllTansType($val["prt_pkid"]);    //运输方式
            $val["wh"] = RPrtWh::find()->where(["prt_pkid" => $val["prt_pkid"]])->select('wh_id')->asArray()->all();  //自提仓库
            foreach ($val["wh"] as &$v) {
                $v["wh_name"] = BsWh::findOne($v["wh_id"])->wh_name;
            }
            $quotedLModel[$key] = $val;
        }
        return $quotedLModel;
    }

    // 订单明细列表
    public function actionOrderDetailList()
    {
        $model = new ReqDtSearch();
//        return Yii::$app->request->queryParams;
        $dataProvider = $model->searchList(Yii::$app->request->queryParams);
        return [
            'rows' => $dataProvider->getModels(),
            'total' => $dataProvider->totalCount,
        ];
    }


    //选择客户信息
    public function actionSelectCustomer()
    {
        $model = new ReqInfoSearch();
        $dataProvider = $model->searchCustomerInfo(Yii::$app->request->queryParams);
        return [
            'rows' => $dataProvider->getModels(),
            'total' => $dataProvider->totalCount,
        ];
    }

    // 下拉列表
    public function actionGetDownList()
    {
        $downList = [];
        // 订单类型
        $downList['orderType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'saqut'])->all();
        // 交易法人
        $downList['corporate'] = BsCompany::find()->select(['company_id', 'company_name'])->where(['company_status' => BsCompany::STATUS_DEFAULT])->all();
        // 订单状态
        $downList['status'] = [
            ReqInfo::STATUS_CREATE => '待报价',
            ReqInfo::STATUS_QUOTED => '已转报价',
            ReqInfo::STATUS_CANCEL => '已取消',
        ];
        // 付款方式
        $downList['payment'] = BsPayment::find()->select(['pac_id', 'pac_code', 'pac_sname'])->all();
        // 支付类型
        $downList['payType'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['and', ['bsp_stype' => BsPubdata::PAY_TYPE], ['bsp_status' => BsPubdata::STATUS_DEFAULT]])->all();
        // 付款条件
        $downList['payCondition'] = BsPayCondition::find()->select(['pat_id', 'pat_sname'])->all();
        // 交易模式
        $downList['pattern'] = BsTransaction::find()->select(['tac_id', 'tac_sname'])->all();
        // 订单来源
        $downList['orderFrom'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['and', ['bsp_stype' => BsPubdata::ORDER_FROM], ['bsp_status' => BsPubdata::STATUS_DEFAULT]])->all();
        // 发票类型
        $downList['invoiceType'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['and', ['bsp_stype' => BsPubdata::CRM_INVOICE_TYPE], ['bsp_status' => BsPubdata::STATUS_DEFAULT]])->all();
        // 交易币别
        $downList['currency'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['and', ['bsp_stype' => BsPubdata::SUPPLIER_TRADE_CURRENCY], ['bsp_status' => BsPubdata::STATUS_DEFAULT]])->all();
        // 运输方式
        $downList['transport'] = BsTransport::find()->select(['tran_id', 'tran_code', 'tran_sname'])->where(['grade' => 1])->all();
        // 配送方式
        $downList['dispatching'] = BsTransport::find()->select(['tran_id', 'tran_code', 'tran_sname'])->where(['grade' => 0])->all();
//        $downList['dispatching'] = BsDeliverymethod::find()->select(['bdm_id', 'bdm_code', 'bdm_sname'])->all();
        // 仓库信息
        $downList['warehouse'] = BsWh::find()->select(['wh_id', 'wh_code', 'wh_name'])->all();
        //国家
        $downList['country'] = BsDistrict::getDisLeveOne();//所在国家
        $downList['creditType'] = CrmCreditMaintain::find()->select(['credit_name', 'id'])->where(['=', 'credit_status', CrmCreditMaintain::STATUS_DEFAULT])->all();//所在国家
        return $downList;
    }

    // 审核类型
    public function actionBusinessType()
    {
        $businessType = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'saqut'])->all();
        foreach ($businessType as $k => $v) {
            $data[$v['business_type_id']] = $v['business_value'];
        }
        return $data;
    }

    // 通过ID获取商品
    public function actionGetProduct($id)
    {

        $params = Yii::$app->request->queryParams;
        $model = new ReqDtSearch();
        $model = $model->search($params)->getModels();
        foreach ($model as $k => $v) {
            if ($v["fixed_price"] == -1) {
                $v["fixed_price"] = "面议";
            } elseif ($v["fixed_price"] == 0) {
                $v["fixed_price"] = "无定价";
            } else {
                $v["fixed_price"] = bcsub($v["fixed_price"], 0, 5);
            }
            $v["sapl_quantity"] = bcsub($v["sapl_quantity"], 0, 2);
            $v["uprice_ntax_o"] = bcsub($v["uprice_ntax_o"], 0, 5);
            $v["uprice_tax_o"] = bcsub($v["uprice_tax_o"], 0, 5);
            if($v["fixed_price"] == -1)
            {
                $v["tprice_tax_o"]="面议";
                $v["tprice_ntax_o"]="面议";
            }
            else{
                $v["tprice_tax_o"] = bcsub($v["tprice_tax_o"], 0, 2);
                $v["tprice_ntax_o"] = bcsub($v["tprice_ntax_o"], 0, 2);
            }
            $v["cess"] = bcsub($v["cess"], 0, 2);
            $v["discount"] = bcsub($v["discount"], 0, 2);
            $v["dis_count_price"] = bcmul($v["uprice_tax_o"], $v["sapl_quantity"], 2);
            $v["tax_freight"] = bcsub($v["tax_freight"], 0, 2);
            $v['product'] = "<div class='text-left text-no-next'><span class='text-left'>料号: {$v['pdt_no']}</span>"
                . "</br><span class='text-no-next'>品名: {$v['pdt_name']}</span>"
//                . "</br><span class='text-left'>规格: 改了数据库 暂时取不到</span></div>";
                . "</br><span class='text-left text-no-next'>规格: {$v['tp_spec']}</span></div>";
            $model[$k] = $v;
        }
        return $model;
    }

    //获取料号定价
    public function actionGetPrice($pdt_no, $num, $curr)
    {
        $query = (new Query())
            ->select([
                'price.price',
                'price.minqty',
                'price.maxqty',
            ])
            ->from(['price' => 'pdt.bs_price'])
            ->leftJoin('pdt.bs_partno bpn', 'bpn.prt_pkid=price.prt_pkid')
            ->where(['bpn.part_no' => $pdt_no])
            ->andWhere(['price.currency' => $curr])->all();
        foreach ($query as $value) {
            if ($value["maxqty"] == 0 && $num >= $value["minqty"]) {
                return $value["price"];
            } else if ($value["maxqty"] >= $num && $num >= $value["minqty"]) {
                return $value["price"];
            }
        }
    }

    //获取客户帐信额度
    public function actionGetCustCredit($id, $currency)
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
                LEFT JOIN erp.bs_business_type type ON li.credit_type = type.business_type_id 	             WHERE
		        c.cust_id = " . $id . " AND c.currency=" . $currency . "";
//        $sql = "SELECT a.id,
//	                    a.credit_name,
//	                    a.code,
//	                    b.cust_id,
//	                    b.credit_type,
//	                    b.ap_surplus_limit surplus_limit,
//	                    ifnull(b.ap_approval_limit,0) approval_limit,
//	                    b.currency
//                  FROM
//	                    erp.crm_credit_maintain a
//                LEFT JOIN (
//	             SELECT
//		           c.cust_id,
//		           a.credit_type,
//		           a.ap_approval_limit,
//		           a.ap_surplus_limit,
//		           c.currency
//	              FROM
//		          erp.crm_credit_limit a
//	         LEFT JOIN erp.crm_credit_apply c ON a.credit_id = c.credit_id
//	             WHERE
//		       c.cust_id = " . $id . " AND c.currency=" . $currency . "
//                ) b ON b.credit_type = a.id WHERE a.credit_status=" . CrmCreditMaintain::STATUS_DEFAULT . " AND ap_approval_limit!=0 ORDER BY b.ap_approval_limit DESC";
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

    //选择商品
    public function actionSelectProduct()
    {
        $model = new ReqInfoSearch();
        $dataProvider = $model->searchProducts(Yii::$app->request->queryParams);
        return [
            'rows' => $dataProvider->getModels(),
            'total' => $dataProvider->totalCount,
        ];
    }

    // 根据料号获取信息
    public function actionGetPdt($pdt_no)
    {
        $model = BsPartnoSelectorShow::findOne(['part_no' => $pdt_no]);
        if (empty($model)) {
            return 0;
        }
        return $model;
    }

    //选择地址
    public function actionSelectAddress($custId, $type)
    {
        $queryParams = Yii::$app->request->queryParams;
        $queryParams["custId"] = $custId;
        $queryParams["type"] = $type;
        $model = new ReqInfoSearch();
        $dataProvider = $model->searchAddress($queryParams);
        return [
            'rows' => $dataProvider->getModels(),
            'total' => $dataProvider->totalCount,
        ];
    }

    //获取地址
    public function actionAddress($id)
    {
        $model = BsAddress::findOne($id);
        return $model;
    }

    // 添加地址
    public function actionAddAddress()
    {
        $post = Yii::$app->request->post();
        $model = new BsAddress();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //如果添加为默认地址
            if ($post["BsAddress"]["ba_status"] == 11) {
                $oldModel = BsAddress::find()->select(['ba_id', 'ba_status'])->where([
                    'ba_status' => BsAddress::STATUS_DEFAULT,
                    'cust_id' => $model->cust_id,
                    'ba_type' => $model->ba_type
                ])->one();
                if (!empty($oldModel)) {
                    $oldModel->ba_status = BsAddress::STATUS_VALID;
                    if (!$oldModel->save()) { // 前默认地址改成非默认
                        throw new \Exception(current($oldModel->getFirstErrors()));
                    };
                }
            }
            if (!$model->load($post) || !$model->save()) {
                throw new \Exception(current($model->getFirstErrors()));
            }
            $transaction->commit();
            return $this->success('新增地址成功！', $model->ba_id);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage() . ' 修改默认地址失败！');
        }
    }

    // 修改地址
    public function actionEditAddress($id)
    {
        $post = Yii::$app->request->post();
        $model = BsAddress::findOne($id);
        $post["BsAddress"]["cust_id"] = $model->cust_id;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //如果添加为默认地址
            if ($post["BsAddress"]["ba_status"] == 11) {
                $oldModel = BsAddress::find()->select(['ba_id', 'ba_status'])->where([
                    'ba_status' => BsAddress::STATUS_DEFAULT,
                    'cust_id' => $model->cust_id,
                    'ba_type' => $model->ba_type
                ])->one();
//                throw new \Exception(Json::encode($oldModel));
                if (!empty($oldModel)) {
                    $oldModel->ba_status = BsAddress::STATUS_VALID;
                    if (!$oldModel->save()) { // 前默认地址改成非默认
                        throw new \Exception(current($oldModel->getFirstErrors()));
                    };
                }
            }
            if (!$model->load($post) || !$model->save()) {
                throw new \Exception(current($model->getFirstErrors()));
            }
            $transaction->commit();
            return $this->success('修改地址成功！', $id);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage() . ' 修改地址失败！');
        }
    }

    // 删除地址
    public function actionDelAddress($id)
    {
        $model = BsAddress::findOne($id)->delete();
        if ($model == false) {
            return $this->error('删除失败');
        }
        return $this->success('删除地址成功！');
    }

    // 修改默认地址
    public function actionDefaultAddress($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = BsAddress::findOne($id);
            if (!empty($model)) {
                if ($model->ba_status == BsAddress::STATUS_DEFAULT) {
                    throw new \Exception('已是默认地址！');
                }
                $oldModel = BsAddress::find()->select(['ba_id', 'ba_status'])->where([
                    'ba_status' => BsAddress::STATUS_DEFAULT,
                    'cust_id' => $model->cust_id,
                    'ba_type' => $model->ba_type
                ])->one();
                if (!empty($oldModel)) {
                    $oldModel->ba_status = BsAddress::STATUS_VALID;
                    if (!$oldModel->save()) { // 前默认地址改成非默认
                        throw new \Exception(current($oldModel->getFirstErrors()));
                    };
                }
                $model->ba_status = BsAddress::STATUS_DEFAULT;
                if (!$model->save()) { // 改为默认地址
                    throw new \Exception(current($model->getFirstErrors()));
                };
            }
            $transaction->commit();
            return $this->success('修改默认地址成功！');
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage() . ' 修改默认地址失败！');
        }
    }

    //获取销售员
    public function actionGetSeller($id)
    {
        return CrmEmployeeShow::find()->where(['and', ['staff_code' => $id], ['sale_status' => CrmEmployee::SALE_STATUS_DEFAULT]])->one();     //销售员信息
    }

    //获取国家
    public function actionGetCountry()
    {
        return BsDistrict::getDisLeveOne();//所在国家
    }

    //获取运输方式和运费
    public function actionGetFreight($pdt, $num, $addr, $TransType)
    {
        $address = BsAddress::findOne($addr);
        $district = $address->district;
        $town = BsDistrict::findOne(['district_id' => $district]);
        $city = BsDistrict::findOne(['district_id' => $town->district_pid]);
        $transportation = new Transportation();
        return $transportation->getLogisticsCost($pdt, $city->district_pid, $town->district_pid, $num, $TransType);
    }

    public function actionGetTranSport($pdt)
    {
//        $transportation = new Transportation();
//        $sports = $transportation->getAllTansType($pdt);
//        $list["transport"] =$sports;
        return BsPartnoSelectorShow::find()->where(["prt_pkid" => $pdt])->one();
    }

    // 转报价
    public function actionToQuoted($id)
    {
        $requireHmodel = ReqInfo::findOne($id);
        if (!$requireHmodel) {
            return $this->error('没有找到该数据！');
        } else if ($requireHmodel->saph_status == ReqInfo::STATUS_QUOTED) {
            return $this->error('已经报价！');
        } else {
            $transaction = Yii::$app->oms->beginTransaction();
            try {
                $requireHmodel->saph_status = ReqInfo::STATUS_QUOTED;
                if (!$requireHmodel->save()) {
                    throw new \Exception(current($requireHmodel->getFirstErrors()));
                }

                // 客户需求单主表转报价单主表
                $arr = Json::decode(json::encode($requireHmodel), true);
                $quotedHModel = new PriceInfo();
                $quotedHModel->setAttributes($arr);
                $quotedHModel->distinct_id = $requireHmodel->invoice_AreaID;
                $quotedHModel->remark = Html::decode($requireHmodel->saph_remark);
                $quotedHModel->price_date = $requireHmodel->nw_date;
                $quotedHModel->price_type = $requireHmodel->saph_type;

                $quotedHModel->cust_contacts = Html::decode($requireHmodel->cust_contacts);
                $quotedHModel->invoice_Title_Addr = Html::decode($requireHmodel->invoice_Title_Addr);
                $quotedHModel->invoice_Address = Html::decode($requireHmodel->invoice_Address);
                $quotedHModel->receipt_Address = Html::decode($requireHmodel->receipt_Address);
                $quotedHModel->cust_tel2 = $requireHmodel->cust_tel;

                //客户信息
                $cust_id = $requireHmodel->cust_id;
                $cust = CrmCustomerInfo::findOne($cust_id);
                $quotedHModel->cust_code = Html::decode($cust->cust_code);
                $quotedHModel->cust_addr = Html::decode($cust->getDistricts());
                $quotedHModel->cust_tel1 = $cust->cust_tel1;

                if (!$quotedHModel->save()) {
                    throw new \Exception('转报价失败！');
                }
                $price_id = $quotedHModel->price_id;
                //子表
                $requireLModel = ReqDt::find()->where(['req_id' => $id])->all();
                $children = json::decode(json::encode($requireLModel), true);
                foreach ($children as $k => $v) {
                    foreach ($v as $key => $value) {
                        $v[$key] = Html::decode($value);
                    }
                    $childModel = new PriceDt();
                    $childModel->setAttributes($v);
                    $childModel->price_id = $price_id; // 报价单单主表ID
                    if (!$childModel->save()) {
                        throw new \Exception(current($childModel->getFirstErrors()));
                    }
                }
                //付款记录
                $reqPay = ReqPay::find()->where(['req_id' => $id])->all();
                $reqPays = json::decode(json::encode($reqPay), true);
                if (!empty($reqPay)) {
                    foreach ($reqPays as $k => $v) {
                        foreach ($v as $key => $value) {
                            $v[$key] = Html::decode($value);
                        }
                        $pPayModel = new PricePay();
                        $v["price_id"] = $price_id;
                        $v["credit_id"] = $v["stag_type"];
                        $pPayModel->setAttributes($v);
                        //todo 表有差异
                        if (!$pPayModel->save()) {
                            throw new \Exception(Json::encode(current($pPayModel->getFirstErrors())));
                        }
                    }
                }
                //附件信息
                $reqFile = ReqFile::find()->where(['req_id' => $id])->all();
                $reqFiles = json::decode(json::encode($reqFile), true);
                if (!empty($reqFile)) {
                    foreach ($reqFiles as $k => $v) {
                        $pFileModel = new PriceFile();
                        $v["price_id"] = $price_id;
                        $pFileModel->setAttributes($v);
                        if (!$pFileModel->save()) {
                            throw new \Exception(Json::encode(current($pFileModel->getFirstErrors())));
                        }
                    }
                }
                $transaction->commit();
                $data['price_id'] = $price_id;
                $data['price_type'] = $quotedHModel->price_type;
                return $this->success('转报价成功！', $data);
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * @param $id
     * @return null|static
     * 查询销售人员姓名及工号
     */
    public function getStaffId($id)
    {
        $user = User::findOne($id);
        $staff = HrStaff::findOne($user['staff_id']);
        return $staff;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 查询客户经理人信息
     */
    public function getManager($id)
    {
        $employee = CrmEmployee::find()->where(['staff_id' => $id])->andWhere(['sale_status' => CrmEmployee::SALE_STATUS_DEFAULT])->one();
        $staff = HrStaff::find()->where(['staff_code' => $employee['staff_code']])->andWhere(['staff_status' => '10'])->one();
        return $staff;
    }


    // 取消订单
    public function actionCancel()
    {
        $transaction = Yii::$app->oms->beginTransaction();
        $get = Yii::$app->request->get();
        try {
            if (!empty($get['cancelId'])) {
                $ids = explode(',', trim($get['cancelId'], ','));
                $where = [
                    'and',
                    ['in', "req_id", $ids],
                    ['saph_status' => ReqInfo::STATUS_CREATE]
                ];
                $reason = !empty($get['reason']) ? $get['reason'] : '';
                $num = ReqInfo::updateAll(["saph_status" => '30', 'opper' => $get['update_by'], 'can_reason' => $reason], $where);
                if ($num <= 0) {
                    throw new \Exception('状态不为待报价，不能取消！');
                }
            } else {
                throw new \Exception('没有选中任何数据');
            }
            $transaction->commit();
            if ($num == count($ids)) {
                return $this->success('取消成功！');
            } else if ($num < count($ids)) {
                return $this->success($num . '条取消成功！' . (count($ids) - $num) . '条状态不为待报价，不能取消！');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }
}
