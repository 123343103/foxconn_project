<?php

namespace app\modules\sale\controllers;

use app\classes\Transportation;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPayCondition;
use app\modules\common\models\BsPayment;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsSettlement;
use app\modules\common\models\BsTransaction;
use app\modules\crm\models\CrmCreditMaintain;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\show\CrmEmployeeShow;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\RPrtWh;
use app\modules\sale\models\CreditApply;
use app\modules\sale\models\CreditLimit;
use app\modules\sale\models\LOrdDt;
use app\modules\sale\models\LOrdFile;
use app\modules\sale\models\LOrdInfo;
use app\modules\sale\models\LOrdPay;
use app\modules\sale\models\OmsShpNt;
use app\modules\sale\models\OmsShpNtDt;
use app\modules\sale\models\OrdDt;
use app\modules\sale\models\OrdFile;
use app\modules\sale\models\OrdInfo;
use app\modules\sale\models\OrdPay;
use app\modules\sale\models\OrdStatus;
use app\modules\sale\models\RepayCredit;
use app\modules\sale\models\SaleOrderl;
use app\modules\sale\models\SalePurchasenoteh;
use app\modules\sale\models\SalePurchasenotel;
use app\modules\sale\models\search\LOrdInfoSearch;
use app\modules\sale\models\search\OrdInfoSearch;
use app\modules\sale\models\search\SaleCustrequireHSearch;
use app\modules\sale\models\search\SaleOrderhSearch;
use app\modules\sale\models\show\LOrdInfoShow;
use app\modules\sale\models\show\OrdInfoShow;
use app\modules\system\models\SysParameter;
use app\modules\warehouse\models\BsTransport;
use app\modules\warehouse\models\BsWh;
use Yii;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Json;


class SaleTradeOrderController extends BaseActiveController
{
    public $modelClass = 'app\modules\sale\models\OrdInfo';

    public function actionIndex()
    {
        $search = new OrdInfoSearch();
        $dataProvider = $search->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

//    public function actionList()
//    {
//        $search = new SaleCustrequireLSearch();
//        //todo 待改
//        $queryParams = Yii::$app->request->queryParams;
//        $queryParams["id"] = 2;
//        $dataProvider = $search->search($queryParams);
//        $model = $dataProvider->getModels();
//        $list['rows'] = $model;
//        $list["total"] = $dataProvider->totalCount;
//        return $list;
//    }


    // 订单详情
    public function actionOrderDetail($id)
    {
        $model = OrdInfoShow::findOne($id);
        $search = new OrdInfoSearch();
        $dataProviderL = $search->searchOrderL($id)->getModels();
        $tran = new Transportation();
        foreach ($dataProviderL as $key => &$val) {
            $val["transport"] = $tran->getAllTansType($val["prt_pkid"]);    //运输方式
            $val["wh"] = RPrtWh::find()->where(["prt_pkid" => $val["prt_pkid"]])->select('wh_id')->asArray()->all();  //自提仓库
            foreach ($val["wh"] as &$v) {
                $v["wh_name"] = BsWh::findOne($v["wh_id"])->wh_name;
            }
            $quotedLModel[$key] = $val;
        }
        $staffCode = HrStaff::findone($model->nwer)->staff_code;
        $data['seller'] = CrmEmployeeShow::find()->where(['and', ['staff_code' => $staffCode], ['sale_status' => CrmEmployee::SALE_STATUS_DEFAULT]])->one();    //销售员信息
        // 付款方式
        $payment = BsPayment::find()->select(['pac_id', 'pac_code', 'pac_sname'])->all();
        // 支付类型
        $payType = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::PAY_TYPE])->all();
        foreach ($payType as $v) {
            if ($v["bsp_id"] == $model["pay_type"]) {
                $data["pay_type_name"] = $v["bsp_svalue"];
            }
        }
        foreach ($payment as $v) {
            if ($v["pac_id"] == $model["pac_id"]) {
                $data["pac_name"] = $v["pac_sname"];
            }
        }
        $downList = BsBusinessType::find()->select(['business_value', 'business_type_id'])->where(['business_code' => 'credit'])->all();//帐信类型
        $data["pay"] = OrdPay::find()->where(["ord_id" => $id])->all();
        foreach ($data['pay'] as &$value) {
            foreach ($downList as $val) {
                if ($value["credit_id"] == $val["business_type_id"]) {
                    $value["credit_id"] = $val["business_value"];
                }
            }
        }
        $data["model"] = $model;
        $data['products'] = $dataProviderL;
        return $data;
    }

    //订单改价详情
    public function actionLogOrderDetail($id)
    {
        $id = LOrdInfo::find()->where(['and', ['ord_id' => $id], ['yn' => 1]])->one()->l_ord_id;  //审核内容为当前日志最新
        $model = LOrdInfoShow::findOne($id);
        $search = new LOrdInfoSearch();
        $dataProviderL = $search->searchOrderL($id)->getModels();
        $tran = new Transportation();
        foreach ($dataProviderL as $key => &$val) {
            $val["transport"] = $tran->getAllTansType($val["prt_pkid"]);    //运输方式
            $val["wh"] = RPrtWh::find()->where(["prt_pkid" => $val["prt_pkid"]])->select('wh_id')->asArray()->all();  //自提仓库
            foreach ($val["wh"] as &$v) {
                $v["wh_name"] = BsWh::findOne($v["wh_id"])->wh_name;
            }
            $quotedLModel[$key] = $val;
        }
        $staffCode = HrStaff::findone($model->nwer)->staff_code;
        $data['seller'] = CrmEmployeeShow::find()->where(['and', ['staff_code' => $staffCode], ['sale_status' => CrmEmployee::SALE_STATUS_DEFAULT]])->one();    //销售员信息
        // 付款方式
        $payment = BsPayment::find()->select(['pac_id', 'pac_code', 'pac_sname'])->all();
        // 支付类型
        $payType = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::PAY_TYPE])->all();
        foreach ($payType as $v) {
            if ($v["bsp_id"] == $model["pay_type"]) {
                $data["pay_type_name"] = $v["bsp_svalue"];
            }
        }
        foreach ($payment as $v) {
            if ($v["pac_id"] == $model["pac_id"]) {
                $data["pac_name"] = $v["pac_sname"];
            }
        }
        $downList = BsBusinessType::find()->select(['business_value', 'business_type_id'])->where(['business_code' => 'credit'])->all();//帐信类型
        $data["pay"] = LOrdPay::find()->where(["l_ord_id" => $id])->all();
        foreach ($data['pay'] as &$value) {
            foreach ($downList as $val) {
                if ($value["credit_id"] == $val["business_type_id"]) {
                    $value["credit_id"] = $val["business_value"];
                }
            }
        }
        $data["model"] = $model;
        $data['products'] = $dataProviderL;
        return $data;
    }

    // 退款处理
    public function actionRefund($id)
    {
        $model = new SaleCustrequireHSearch();
        $dataProviderH = current($model->searchOrderH($id)->getModels());
        $dataProviderL = $model->searchOrderL($id)->getModels();
        $data['products'] = $dataProviderL;
        $data = array_merge($dataProviderH, $data);
        return $data;
    }

    // 获取审核类型
    public function actionGetCheckType($type)
    {
        return BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['and', ['business_code' => 'order'], ['business_value' => $type]])->asArray()->one()["business_type_id"];
    }

    // 订单改价
    public function actionReprice($id)
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $transaction = Yii::$app->oms->beginTransaction();
            try {
                //主表
                $quotedHModel = OrdInfo::findOne($id);
                LOrdInfo::updateAll(['yn' => 0], ['ord_id' => $quotedHModel->ord_id]);
                $arr = Json::decode(json::encode($quotedHModel), true);
                $logHModel = new LOrdInfo();
                $logHModel->ord_id = $id;
                foreach ($arr as $key => $val) {
                    $arr[$key] = Html::decode($val);
                }
                $logHModel->setAttributes($arr);
                $logHModel->yn = 1;
                $logHModel->tax_freight = $post['OrdInfo']["tax_freight"];
                $logHModel->prd_org_amount = $post['OrdInfo']["prd_org_amount"];
                $logHModel->req_tax_amount = $post['OrdInfo']["req_tax_amount"];
                $logHModel->pac_id = $post['OrdInfo']["pac_id"];
                $logHModel->pay_type = $post['OrdInfo']["pay_type"];
                if (!$logHModel->save()) {
                    throw new \Exception(Json::encode($logHModel->getFirstErrors()));
                }
                //附件信息表
                $fModels = OrdFile::find()->where(['ord_id' => $id])->all();
                $fModels = json::decode(json::encode($fModels), true);
                foreach ($fModels as $k => $v) {
                    $logFModel = new LOrdFile();
                    $logFModel->setAttributes($v);
                    $logFModel->l_ord_id = $logHModel->l_ord_id;
                    if (!$logFModel->save()) {
                        throw new \Exception(current($logFModel->getFirstErrors()));
                    }
                }
                //订单子表更新
                if (!empty($post['orderL'])) {
                    foreach ($post['orderL'] as $k => $v) {
                        foreach ($v as $key => $val) {
                            $v[$key] = Html::decode($val);
                        }
                        $logLModel = new LOrdDt();
                        $value["LOrdDt"] = $v;
                        $logLModel->l_ord_id = $logHModel->l_ord_id;
                        $logLModel->uprice_ntax_o = $v["uprice_tax_o"] / 1.17;  //未税销售单价
                        $logLModel->tprice_ntax_o = $v["tprice_tax_o"] / 1.17;  //未税总价
                        if (!$logLModel->load($value) || !$logLModel->save()) {
                            throw new \Exception(current($logLModel->getFirstErrors()));
                        }
                    }
                }
                //付款记录
                if (!empty($post['OrdPay'])) {
                    foreach ($post['OrdPay'] as $k => $v) {
                        foreach ($v as $key => $val) {
                            $v[$key] = Html::decode($val);
                        }
                        $logPayModel = new LOrdPay();
                        $v["l_ord_id"] = $logHModel->l_ord_id;
                        $value["LOrdPay"] = $v;
                        if (!$logPayModel->load($value) || !$logPayModel->save()) {
                            throw new \Exception(current($logPayModel->getFirstErrors()));
                        }
                    }
                } else {
                    $logPayModel = new LOrdPay();
                    $logPayModel->l_ord_id = $logHModel->l_ord_id;
                    $logPayModel->stag_cost = $logHModel->req_tax_amount;
                    if (!$logPayModel->save()) {
                        throw new \Exception(current($logPayModel->getFirstErrors()));
                    }
                }
                $transaction->commit();
                return $this->success('改价单保存成功', $quotedHModel->ord_id);
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        $model = OrdInfoShow::findOne($id);
        $search = new OrdInfoSearch();
        $dataProviderL = $search->searchOrderL($id)->getModels();
        $tran = new Transportation();
        foreach ($dataProviderL as $key => &$val) {
            $val["transport_code"] = $val["transport"];    //运输方式
            $val["transport"] = $tran->getAllTansType($val["prt_pkid"]);    //运输方式
            $val["wh"] = RPrtWh::find()->where(["prt_pkid" => $val["prt_pkid"]])->select('wh_id')->asArray()->all();  //自提仓库
            foreach ($val["wh"] as &$v) {
                $v["wh_name"] = BsWh::findOne($v["wh_id"])->wh_name;
            }
            $quotedLModel[$key] = $val;
        }
        $staffCode = HrStaff::findone($model->nwer)->staff_code;
        $data['seller'] = CrmEmployeeShow::find()->where(['and', ['staff_code' => $staffCode], ['sale_status' => CrmEmployee::SALE_STATUS_DEFAULT]])->one();    //销售员信息
//        $data["pay"] = OrdPay::find()->where(["ord_id" => $id])->all();
        $data["pay"] = $this->getCreditPay($id);
        $data["model"] = $model;
        $data['products'] = $dataProviderL;
        return $data;
    }

    // 订单明细列表
    public function actionOrderDetailList()
    {
        $model = new OrdInfoSearch();
//        return Yii::$app->request->queryParams;
        $dataProvider = $model->searchOrderList(Yii::$app->request->queryParams);
        return ['rows' => $dataProvider->getModels(),
            'total' => $dataProvider->totalCount,

        ];
    }

    //获取运输方式和运费
    public function actionGetFreight($pdt, $num, $addr, $TransType)
    {
        $town = BsDistrict::findOne(['district_id' => $addr]);
        $city = BsDistrict::findOne(['district_id' => $town->district_pid]);
        $transportation = new Transportation();
        return $transportation->getLogisticsCost($pdt, $city->district_pid, $town->district_pid, $num, $TransType);
    }

    // 采购通知
    public function actionPurchaseNote($id)
    {
        if (Yii::$app->request->isPost) {
            $transaction = Yii::$app->oms->beginTransaction();
            try {
                $post = Yii::$app->request->post();
                if (empty($post['choose'])) {
                    return $this->error('没有选择产品');
                } else {
                    $noteH = new SalePurchasenoteh();
                    if (!$noteH->load($post) || !$noteH->save()) {
                        throw new \Exception(current($noteH->getFirstErrors()));
                    }
                    $children = [];
                    foreach ($post['choose'] as $k => $v) {
                        $children[]['SalePurchasenotel'] = $post['SalePurchasenotel'][$v];
                    }
                    foreach ($children as $kk => $vv) {
                        $noteL = new SalePurchasenotel();
                        $noteL->ponh_id = $noteH->ponh_id;
                        if (!$noteL->load($vv) || !$noteL->save()) {
                            throw new \Exception(current($noteL->getFirstErrors()));
                        }
                        $orderL = SaleOrderl::find()->where(['sol_id' => $noteL->lbill_id])->one();
                        // 查找系统设置 通知数量是否可大于下单数量 回写订单通知状态和通知数量
                        $orderL->pur_note_qty += $noteL->apply_qty;
                        if ($orderL->pur_note_qty >= $noteL->require_qty) {
                            $orderL->pur_note_status = SaleOrderl::NOTE_ALL;
                        } else if ($orderL->pur_note_qty > 0 && $orderL->pur_note_qty <= $orderL->sapl_quantity) {
                            $orderL->pur_note_status = SaleOrderl::NOTE_PART;
                        }
//                        throw new \Exception(json_encode($orderL->save()));
                        if (empty($orderL) || !$orderL->save()) {
                            throw new \Exception('订单通知状态更新失败！');
                        }
                    }
                }
                $transaction->commit();
                return $this->success('成功');
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        } else {
            $staffId = Yii::$app->request->get('staff_id');
            $staff = HrStaff::find()->select('staff_id, staff_name, organization_code')->where(['staff_id' => $staffId])->asArray()->one();
//        $orgCode = HrStaff::getOrgCode($staff);
            $orgModel = new HrOrganization();
            $org = $orgModel->find()->select('organization_id, organization_name')->where(['organization_code' => $staff['organization_code']])->asArray()->one();
//            $orgList['orgList'] = $orgModel->find()->select('organization_id, organization_name')->asArray()->all();
            $orgList['orgList'] = HrOrganization::getOrgAllLevel(0);
//        $model->searchOrderH($id);
            $model = new SaleOrderhSearch();
            $dataProviderL = $model->searchPurchaseL($id)->getModels();
            if (empty($dataProviderL)) {
                // 不缺货,没有可发送的采购通知
                return [];
            }
            $dataProviderH = current($model->searchPurchaseH($id)->getModels());
            $data['products'] = $dataProviderL;
            // 通知数上限
            $sysParamModel = new SysParameter();
            $isGt = $sysParamModel->find()->select('par_value, par_value_decimal')->where(['par_syscode' => 'purchase_gt'])->one();
            $data['gt'] = 1;
            if (!empty($isGt['par_value'])) {
                $data['gt'] = !empty($isGt['par_value_decimal']) ? $isGt['par_value_decimal'] : 1;
            }
            // 单据类型 对应business中的采购订单
            $data['billType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'puord'])->all();
            $data = array_merge($dataProviderH, $data, $staff, $org, $orgList);
//            $data = array_merge($dataProviderH, $data, $staff, $org);
            return $data;
        }
    }

    // 出货通知
    public function actionOutNote($id)
    {
        if (Yii::$app->request->isPost) {
            $transaction = Yii::$app->oms->beginTransaction();
            $post = Yii::$app->request->post();
            $status_id = OrdStatus::find()->where(['and', ['os_name' => '部分已通知出货'], ['yn' => 1]])->one()["os_id"];
            if ($post["IsAll"] == 10) {  //10 表示全部出货
                $status_id = OrdStatus::find()->where(['and', ['os_name' => '订单备货中'], ['yn' => 1]])->one()["os_id"];
            }
            $transport = null;
            $ord_dt = OrdDt::find()->where(['ord_dt_id' => $post['OmsShpNtDt'][0]["sol_id"]])->one();
            if (!empty($ord_dt->transport)) {
                $transport = $ord_dt->transport;
            }
            try {
                $statusModel = BsPubdata::find()->where(['and', ['bsp_stype' => 'TZDZT'], ['bsp_svalue' => '待处理']])->one();
                $post['OmsShpNt']['status'] = $statusModel->bsp_id;
                $post['OmsShpNt']['trans_mode'] = $transport;
                $post['OmsShpNt']['operator'] = Yii::$app->request->get('staff_id');;
                $noteH = new OmsShpNt();
                if (!$noteH->load($post) || !$noteH->save()) {
                    throw new \Exception(current($noteH->getFirstErrors()));
                }
                $order = OrdInfo::findOne($id);
                $order->os_id = $status_id;
                if (!$order->save()) {
                    throw new \Exception(current($order->getFirstErrors()));
                }
                foreach ($post['OmsShpNtDt'] as $kk => $vv) {
                    if (isset($vv['checkbox']) && $vv['checkbox'] == 'on') {
                        $noteDt = new OmsShpNtDt();
                        $value["OmsShpNtDt"] = $vv;
                        if(!empty( $post['OmsShpNt']["wh_id"])){
                            $value["OmsShpNtDt"]["wh_id"] =  $post['OmsShpNt']["wh_id"];
                        }
                        $noteDt->note_pkid = $noteH->note_pkid;
                        if (!$noteDt->load($value) || !$noteDt->save()) {
                            throw new \Exception(current($noteDt->getFirstErrors()));
                        }
                        $orderL = OrdDt::find()->where(['ord_dt_id' => $vv['sol_id']])->one();
                        // 查找系统设置 通知数量是否可大于下单数量 回写订单通知状态和通知数量
                        $orderL->delivery_num = $orderL->delivery_num + $vv['nums'];
                        if (empty($orderL) || !$orderL->save()) {
                            throw new \Exception('订单通知状态更新失败！');
                        }
                    }
                }
                $transaction->commit();
                return $this->success('成功');
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        } else {
            $staffId = Yii::$app->request->get('staff_id');
            $staff = HrStaff::find()->select('staff_id, staff_name, organization_code')->where(['staff_id' => $staffId])->asArray()->one();
//        $orgCode = HrStaff::getOrgCode($staff);
            $orgModel = new HrOrganization();
            $org = $orgModel->find()->select('organization_id, organization_name')->where(['organization_code' => $staff['organization_code']])->asArray()->one();
//            $orgList['orgList'] = $orgModel->find()->select('organization_id, organization_name')->asArray()->all();
//            $orgList['orgList'] = HrOrganization::getOrgAllLevel(0);
//        $model->searchOrderH($id);
            $model = new OrdInfoSearch();
            $dataProviderL = $model->searchOutL($id)->getModels();
            if (empty($dataProviderL)) {
                // 没有可发送的通知
                return [];
            }
            $dataProviderH = current($model->searchOutH($id)->getModels());
            $data['products'] = $dataProviderL;
            // 通知数上限
//            $sysParamModel = new SysParameter();
//            $isGt = $sysParamModel->find()->select('par_value, par_value_decimal')->where(['par_syscode' => 'out_gt'])->one();
//            $data['gt'] = 1;
//            if (!empty($isGt['par_value'])) {
//                $data['gt'] = !empty($isGt['par_value_decimal']) ? $isGt['par_value_decimal'] : 1;
//            }
            $data = array_merge($dataProviderH, $data, $staff, $org);
//            $data = array_merge($dataProviderH, $data, $staff, $org, $orgList);
//            $data = array_merge($dataProviderH, $data, $staff, $org);
            return $data;
        }
    }

    // 下拉列表
    public function actionGetDownList()
    {
        $downList = [];
        // 订单类型
        $downList['orderType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'saqut'])->all();
        // 交易法人
        $downList['corporate'] = BsCompany::find()->select(['company_id', 'company_name'])->all();
        // 订单状态
        $downList['status'] = OrdStatus::find()->select(['os_id', 'os_name'])->where(['yn' => 1])->all();
        // 付款方式
        $downList['payment'] = BsPayment::find()->select(['pac_id', 'pac_code', 'pac_sname'])->all();
        // 支付类型
        $downList['payType'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['and', ['bsp_stype' => BsPubdata::PAY_TYPE], ['bsp_status' => BsPubdata::STATUS_DEFAULT]])->all();
        // 付款条件
        $downList['payCondition'] = BsPayCondition::find()->select(['pat_id', 'pat_sname'])->all();
        // 交易模式
        $downList['pattern'] = BsTransaction::find()->select(['tac_id', 'tac_sname'])->all();
//        $downList['pattern'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::TRANSACT_PATTERN])->all();
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
        // 仓库信息
        $downList['warehouse'] = BsWh::find()->select(['wh_id', 'wh_code', 'wh_name'])->all();
        $downList['creditType'] = CrmCreditMaintain::find()->select(['credit_name', 'id'])->where(['=', 'credit_status', CrmCreditMaintain::STATUS_DEFAULT])->all();//所在国家
        return $downList;
    }

    // 点击主表获取子表商品信息
    public function actionGetProducts()
    {
        $params = Yii::$app->request->queryParams;
        $model = new OrdInfoSearch();
        $models = $model->searchOrderProducts($params)->getModels();
        foreach ($models as $k => $v) {
            if ($v["tax_price"] == -1) {
                $v["tax_price"] = "面议";
            } else {
                $v["tax_price"] = bcsub($v["tax_price"], 0, 5);
            }
            $v["sapl_quantity"] = bcsub($v["sapl_quantity"], 0, 2);
            if ($v["out_num"] < $v["sapl_quantity"]) {
                $v["out_num"] = bcsub($v["out_num"], 0, 2);
                $v["out_num"] = "<span class='red'>{$v['out_num']}</span>";
            } else {
                $v["out_num"] = bcsub($v["out_num"], 0, 2);
            }
            $v["uprice_ntax_o"] = bcsub($v["uprice_ntax_o"], 0, 5);
            $v["uprice_tax_o"] = bcsub($v["uprice_tax_o"], 0, 5);
            $v["tprice_tax_o"] = bcsub($v["tprice_tax_o"], 0, 2);
            $v["tprice_ntax_o"] = bcsub($v["tprice_ntax_o"], 0, 2);
            $v["price_off"] = bcsub($v["price_off"], 0, 2);
            $v["cess"] = bcsub($v["cess"], 0, 2);
            $v["discount"] = bcsub($v["discount"], 0, 2);
            $v["dis_count_price"] = bcmul($v["uprice_tax_o"], $v["sapl_quantity"], 2);
            $v["tax_freight"] = bcsub($v["tax_freight"], 0, 2);
            $v['pdt_name'] = "<div class='text-left text-no-next'><span class='text-left'>料号: {$v['part_no']}</span>"
                . "</br><span class='text-no-next'>品名: {$v['pdt_name']}</span>"
                . "</br><span class='text-left text-no-next'>规格: {$v['tp_spec']}</span></div>";
            $models[$k] = $v;
        }
//        dumpE($models);
        return $models;
    }

    // 点击主表获取子表商品信息
    public function actionGetLogProducts()
    {
        $params = Yii::$app->request->queryParams;
        $model = new LOrdInfoSearch();
        $models = $model->searchOrderProducts($params)->getModels();
        foreach ($models as $k => $v) {
            if ($v["tax_price"] == -1) {
                $v["tax_price"] = "面议";
            } else {
                $v["tax_price"] = bcsub($v["tax_price"], 0, 5);
            }
            $v["sapl_quantity"] = bcsub($v["sapl_quantity"], 0, 2);
            $v["uprice_ntax_o"] = bcsub($v["uprice_ntax_o"], 0, 5);
            $v["uprice_tax_o"] = bcsub($v["uprice_tax_o"], 0, 5);
            $v["tprice_tax_o"] = bcsub($v["tprice_tax_o"], 0, 2);
            $v["tprice_ntax_o"] = bcsub($v["tprice_ntax_o"], 0, 2);
            $v["price_off"] = bcsub($v["price_off"], 0, 2);
            $v["cess"] = bcsub($v["cess"], 0, 2);
            $v["discount"] = bcsub($v["discount"], 0, 2);
            $v["dis_count_price"] = bcmul($v["uprice_tax_o"], $v["sapl_quantity"], 2);
            $v["tax_freight"] = bcsub($v["tax_freight"], 0, 2);
            $v['pdt_name'] = "<div class='text-left text-no-next'><span class='text-left'>料号: {$v['part_no']}</span>"
                . "</br><span class='text-no-next'>品名: {$v['pdt_name']}</span>"
                . "</br><span class='text-left text-no-next'>规格: {$v['tp_spec']}</span></div>";
            $models[$k] = $v;
        }
//        dumpE($models);
        return $models;
    }
    // 帐信支付确认
    public function actionOrderPay($id)
    {
        //判断币别
        $credit = BsPayment::find()->select(['pac_id', 'pac_code'])->where(['pac_code' => 'credit-amount'])->one()['pac_id'];
        $status_id = OrdStatus::find()->where(['and', ['os_name' => '订单已付款'], ['yn' => 1]])->one()["os_id"];
        $order = OrdInfo::findOne($id);
        $order_status = OrdStatus::find()->where(['and', ['os_id' => $order->os_id], ['yn' => 1]])->one()['os_name'];
        if ($order->pac_id == $credit && ($order_status == "订单已提交" || $order_status == "订单已确认" || $order_status == "订单改价完成" || $order_status == "订单改价取消")) {
            $cust = CrmCustomerInfo::findOne(["cust_code" => $order->cust_code]);
            $cust_id = $cust->cust_id;
            $apply = CreditApply::findOne(["cust_id" => $cust_id]);
            $condition = BsSettlement::findOne(["bnt_code" => $apply->payment_clause]);
            $creditPays = $order->getOrdPays()->all();
            $transaction = Yii::$app->oms->beginTransaction();
            try {
                if (empty($apply)) {
                    throw new \Exception("支付确认失败！");
                } else {
                    $limit_id = $apply->credit_id;
                }
                foreach ($creditPays as $k => $v) {
                    if (!empty($v["stag_cost"]) && $v["stag_cost"] != '0.000') {
                    $credit_id = $v["credit_id"];
                    $limit = CreditLimit::find()->where(['and', ['credit_id' => $limit_id], ['credit_type' => $credit_id], ['is_approval' => 2], ['limit_status' => 2]])->one();

                    if($limit->validity_date >= date("Y-m-d")){
                    if (!empty($limit) && $limit->surplus_limit >= $v["stag_cost"] ) {
                        // 减去使用额度 改为已支付 插入支付表
                        //大数值 加减法
                        $limit->surplus_limit = bcsub($limit->surplus_limit, $v["stag_cost"]);//减法
                        $limit->used_limit = bcadd($limit->used_limit, $v["stag_cost"]);//加法
                        if (!$limit->save()) {
                            throw new \Exception(current($limit->getFirstErrors()));
                        }
                        $repqy = new RepayCredit();
                        $repqy->ord_pay_id = $v["ord_pay_id"];
                        $repqy->repay_fee = $v["stag_cost"];
                        $repqy->pat_sname = $condition->bnt_sname;
                        $day = $this->getPayDay($apply->payment_clause);
                        $repqy->repay_date = date("Y-m-d H:i:s", strtotime("$order->nw_date   +$day day"));
                        $repqy->pat_code = $apply->payment_clause;
                        $repqy->user_date = date("Y-m-d H:i:s");
                        $repqy->user_id = $cust_id;
                        $repqy->is_repay = 0;
                        if (!$repqy->save()) {
                            throw new \Exception(current($repqy->getFirstErrors()));
                        }
                        $ordPay = OrdPay::findOne($v["ord_pay_id"]);
                        $ordPay->yn_pay = 1;
                        if (!$ordPay->save()) {
                            throw new \Exception(current($ordPay->getFirstErrors()));
                        }
                        $apply->surplus_limit = bcsub($apply->surplus_limit, $v["stag_cost"]);
                        $apply->allow_amount = bcsub($apply->allow_amount, $v["stag_cost"]);
                        $apply->used_limit = bcadd($apply->used_limit, $v["stag_cost"]);
                        $apply->grand_total_limit = bcadd($apply->grand_total_limit, $v["stag_cost"]);
                    } else {
                        throw new \Exception('余额不足！');
                    }
                        }else {
                        throw new \Exception('帐信额度不在有效期内！');
                        }
                }
            }
                $order->os_id = $status_id;
                if (!$order->save()) {
                    throw new \Exception(current($order->getFirstErrors()));
                }
                if (!$apply->save()) {
                    throw new \Exception(current($apply->getFirstErrors()));
                }
                $transaction->commit();
                return $this->success("支付确认成功！");
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error("支付确认失败！" . $e->getMessage());
            }
        } else {
            return $this->error("订单状态不能支付确认！");
        }
    }

    // 取消订单
    public function actionCancel()
    {
        $transaction = Yii::$app->oms->beginTransaction();
        $get = Yii::$app->request->get();
        $status = OrdStatus::find()->where(["and", ['os_name' => '订单已取消'], ['yn' => 1]])->one();
        $reason = !empty($get['reason']) ? $get['reason'] : '';
        $num = 0;
        try {
            if (!empty($get['cancelId'])) {
                $ids = explode(',', trim($get['cancelId'], ','));
                foreach ($ids as $val) {
                    $order = OrdInfo::findOne($val);
                    $st = OrdStatus::findOne($order->os_id);
                    if ($st->os_name != "订单已收货" && $st->os_name != "订单取消支付" && $st->os_name != "交易完成" && $st->os_name != "订单已出货" && $st->os_name != "订单已取消") {
                        $order->os_id = $status->os_id;
                        $order->caner = $get['update_by'];
                        $order->can_reason = $reason;
                        if ($order->save()) {
                            $num++;
                        }
                    }
                }
                if ($num <= 0) {
                    throw new \Exception('取消失败！');
                }
            } else {
                throw new \Exception('没有选中任何数据');
            }
            $transaction->commit();
            if ($num == count($ids)) {
                return $this->success('取消成功！');
            } else {
                return $this->success($num . '条取消成功！' . (count($ids) - $num) . "条订单状态不能取消！");
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    // 取消订单
    public function actionRepriceCancel()
    {
        $transaction = Yii::$app->oms->beginTransaction();
        $get = Yii::$app->request->get();
        try {
            if (!empty($get['id'])) {
                $ids = explode(',', trim($get['id'], ','));
                $status = OrdStatus::find()->where(['os_name' => '订单改价取消'])->one();
                $status2 = OrdStatus::find()->where(['os_name' => '订单改价驳回'])->one();
                $where = [
                    'and',
                    ['in', "ord_id", $ids]
                    , ['os_id' => $status2->os_id]
                ];
                $reason = !empty($get['reason']) ? '取消原因: ' . $get['reason'] : '';
                $num = OrdInfo::updateAll(["os_id" => $status->os_id], $where);
                if ($num <= 0) {
                    throw new \Exception('取消改价失败！');
                }
            } else {
                throw new \Exception('没有选中任何数据');
            }
            $transaction->commit();
            if ($num == count($ids)) {
                return $this->success('取消改价成功！');
            } else {
                return $this->success($num . '条取消改价成功！' . (count($ids) - $num) . "改价未驳回，不能取消改价！");
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    function getCreditPay($id)
    {
        $stag = OrdPay::find()->where(["ord_id" => $id])->one();
        if (!empty($stag["stag_times"])) { //分期
            $query = (new Query())
                ->select([
                    // 商品id
                    'pay.stag_cost',
                    'pay.stag_times',
                    'pay.stag_date',

                ])
                ->from(['pay' => 'oms.ord_pay'])
                ->andwhere(['pay.ord_id' => $id])
                ->groupBy("stag_times");
        } else if (!empty($stag["credit_id"])) {
            $query = (new Query())
                ->select([
                    // 商品id
                    'pay.stag_cost',
                    'pay.credit_id',
                    'pay.stag_times',
                    'pay.stag_date',
                    'type.business_value credit_name',
                    'limit.surplus_limit',
                    'limit.approval_limit',

                ])
                ->from(['pay' => 'oms.ord_pay'])
                ->leftJoin('oms.ord_info odh', 'pay.ord_id=odh.ord_id')
                ->leftJoin('erp.crm_bs_customer_info cust', 'cust.cust_code=odh.cust_code')
                ->leftJoin('erp.crm_credit_apply apply', 'apply.cust_id=cust.cust_id')
                ->leftJoin('erp.crm_credit_limit limit', 'apply.credit_id=limit.credit_id and limit.credit_type=pay.credit_id')
                ->leftJoin('erp.bs_business_type type', 'type.business_type_id=limit.credit_type')
//            ->andWhere('bp.pck_type = 2')
                ->andwhere(['pay.ord_id' => $id])
                ->groupBy("credit_id");
        } else {
            return null;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider->getModels();
    }

    function getPayDay($code)
    {
        switch ($code) {
            case 'M0015':
                return 22;
            case 'M0200':
                return 27;
            case 'M0312':
                return 37;
            case 'M0411':
                return 52;
            case 'M0611':
                return 67;
            case 'M0911':
                return 97;
            case 'ME015':
                return 22;
            case 'M0030':
                return 37;
            case 'M0060':
                return 67;
            default:
                return 0;
        }
    }
}
