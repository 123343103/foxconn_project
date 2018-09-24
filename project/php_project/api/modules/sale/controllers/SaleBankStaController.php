<?php
/**
 * Created by PhpStorm.
 * User: F3860942
 * Date: 2017/8/4
 * Time: 上午 11:36
 */

namespace app\modules\sale\controllers;


use app\controllers\BaseActiveController;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\sale\models\BsBankCheck;
use app\modules\sale\models\BsBankInfo;
use app\modules\sale\models\OrdInfo;
use app\modules\sale\models\OrdPay;
use app\modules\sale\models\RepayCredit;
use app\modules\system\models\Verifyrecord;
use app\modules\system\System;
use Yii;
use app\modules\sale\models\search\BsBankInfoSearch;
use app\commands\BankInfo;


class SaleBankStaController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmEmployee';

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $search = new BsBankInfoSearch();
        $dataProvider = $search->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    public function actionGetDownList()
    {
        $downList = [];
        // 收款法人
        $downList['corpDesc'] = BsBankInfo::find()->select(['CORP_DESC'])->distinct()->all();
        $downList['accounts'] = BsBankInfo::find()->select(['ACCOUNTS'])->distinct()->all();
        return $downList ;
    }

    public function actionGrabData($date)
    {
        $bankinfo = new BankInfo();
        $date = date('Ymd', strtotime($date));
        $bkc = $bankinfo->AddBKCHBankInfo($date);
        $abo = $bankinfo->AddABOCBankInfo($date);
        $icb = $bankinfo->AddICBKBankInfo($date);
        if ($bkc == "1" || $abo == "1" || $icb == "1") {
            return ['msg' => "抓取成功", 'flag' => 1];
        } else {
            return ['msg' => "抓取失败", 'flag' => 0];
        }
    }

    //查询单笔流水号信息
    public function actionGetTransInfo($transid)
    {
        $search = new BsBankInfoSearch();
        $model = $search->GetTransInfo($transid);
        return $model;
    }

    //获取未支付的订单
    public function actionGetUnpaidOrder()
    {
        $params=Yii::$app->request->queryParams;
        $search = new BsBankInfoSearch();
        $dataProvider = $search->GetUnpaidOrder($params);
        $model=$dataProvider->getModels();
        $list['rows']=$model;
        $list['total']=$dataProvider->totalCount;
        return $list;
    }

    //獲取所有已綁定過的流水()
    public function actionCheckReOrder()
    {
        $translist = new BsBankInfoSearch();
        $model = $translist->GetTransList();;
        return $model;
    }

    //獲取所有訂單的客戶代碼
    public function actionGetCmpInfo($order_no)
    {
        $model = OrdInfo::findOne(['ord_no' => $order_no]);
        return $model->cust_code;
    }

    //获取订单交易币别
    public function actionGetCurId($order_no)
    {
        $search = new BsBankInfoSearch();
        $model = $search->GetCurCode($order_no);
        return $model;
    }

    //获取单笔订单信息
    public function actionGetOneOrderInfo($order_no)
    {
        $search = OrdInfo::findOne(['ord_no' => $order_no]);
        return $search;
    }

    //查询单笔退款总金额
    public function actionGetRefMoney($order_no)
    {
        $search = new BsBankInfoSearch();
        $model = $search->GetRefMoney($order_no);
        return $model;
    }

    //获取订单的交易法人
    public function actionGetCorporate($order_no)
    {
        $search = new BsBankInfoSearch();
        $model = $search->GetCorporate($order_no);
        return $model;
    }

    //获取订单的付款类型
    public function actionGetPacName($order_no)
    {
        $search = new BsBankInfoSearch();
        $model = $search->GetPocName($order_no);
        return $model;
    }

    //获取订单是否支付
    public function actionGetYnPay($order_no)
    {
        $search = new BsBankInfoSearch();
        $model = $search->GetYnPay($order_no);
        return $model;
    }
    //查询客户信用额度支付订单还款时间
    public function actionGetRepayDate($cust_code)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetRepayDate($cust_code);
        return $model;
    }
    //查询是否有信用额度使用记录
    public function actionGetCreditRecord($ord_pay_id)
    {
        $cust_id = Yii::$app->db->createCommand("select c.cust_id from erp.crm_bs_customer_info c where 
c.cust_code in (select b.cust_code from oms.ord_pay a left join oms.ord_info b on a.ord_id=b.ord_id where a.ord_pay_id=:ord_pay_id)",['ord_pay_id'=>$ord_pay_id])->queryAll();
        print_r($cust_id);
        exit();
        $search=new BsBankInfoSearch();
        $model=$search->GetCreditRecord($ord_pay_id);
        return $model;
    }
    //修改还款订单的还款状态
    public function actionUpdateIsPay($ord_pay_id)
    {
        $credit=RepayCredit::findOne(['ord_pay_id'=>$ord_pay_id]);
        $credit->is_repay=2;
        $credit->app_date=date('Y-m-d H:i:s',time());
        $credit->repay_type=1;
        if(!$credit->save())
        {
            return $this->error(Json_encode($credit->getFirstErrors(),JSON_UNESCAPED_UNICODE));
        }
        return $this->success();
    }
    //查询system_verify主键
    public function actionGetPrimary($transid)
    {
        $primary=Verifyrecord::find()->select('vco_id')->where(['bus_code'=>'ordrecives','vco_busid'=>$transid])->one();
        return $primary;
    }
    //查询流水是否已有在审核中的数据
    public function actionGetVerifyTrans($transid)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetVerifyTrans($transid);
        return $model;
    }
    //查询ord_pay表
    public function actionGetOrdPay($ord_pay_id)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetOrdPay($ord_pay_id);
        return $model;
    }
    //查询ord_pay_id是否在r_bank_order存在
    public function actionGetRboInfo($ord_pay_id)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetRboinfo($ord_pay_id);
        return $model;
    }
    //判断是单笔还是多笔申请
    public function actionGetIsSign($rbo_id)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetIsSign($rbo_id);
        return $model;
    }
    //根据rbo_id获取数据
    public function actionGetDataByRboId($rbo_id)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetDataByRboId($rbo_id);
        return $model;
    }
    //获取vco_id(单笔)
    public function actionGetVcoId($rbo_id)
    {
        $vco_id=Verifyrecord::find()->select('vco_id')->where(['bus_code'=>'ordrecives','vco_busid'=>$rbo_id])->one();
        return $vco_id;
    }
    //获取vco_id(批量)
    public function actionGetBatchVcoId($rbo_id)
    {
        $vco_id=Verifyrecord::find()->select('vco_id')->where(['bus_code'=>'batchreceipts','vco_busid'=>$rbo_id])->one();
        return $vco_id;
    }
    //查询订单号是否有审核中的数组
    public function actionGetVerifOrder($order_no)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetVerifOrder($order_no);
        return $model;
    }
    //根据流水号查询所有已完成的审核记录
    public function actionGetPassTrans($transid)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetPassTrans($transid);
        return $model;
    }
    //查询订单信息
    public function actionGetOrderInfo($order_no)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetOrderInfo($order_no);
        return $model;
    }
    //判断订单是否是收还款订单
    public function actionIsRecvOrRepay($order_no)
    {
        $search=new BsBankInfoSearch();
        $model=$search->IsRecvOrRepay($order_no);
        return $model;
    }
    //获取所有可操作订单
    public function actionGetOperaOrder($order_no)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetOperaOrder($order_no);
        return $model;
    }
    //查询流水是否第一次使用
    public function actionGetIsFirstUse($transid)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetIsFirstUse($transid);
        return $model;
    }
    //通过订单号查询该笔订单时全额订单还是分期订单(包括帐信)
    public function actionGetIsFullAmount($order_no)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetIsFullAmount($order_no);
        return $model;
    }
    //多分期订单排序
    public function actionGetOrderbyStage($order_no)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetOrderbyStage($order_no);
        return $model;
    }
    //多信用额度分期
    public function actionGetOrderbyCredit($order_no){
        $search=new BsBankInfoSearch();
        $model=$search->GetOrderbyCredit($order_no);
        return $model;
    }
    //判断是单个还是批量驳回
    public function actionGetIsBatchOrSign($rbo_id)
    {
        $model=Verifyrecord::find()->where(['vco_busid'=>$rbo_id,'bus_code'=>'ordrecives'])->all();
        $model1=Verifyrecord::find()->where(['vco_busid'=>$rbo_id,'bus_code'=>'batchreceipts'])->all();
        if(!empty($model))
        {
            return 1;
        }
        else if(!empty($model1)){
            return 2;
        }
        else{
            return '';
        }
    }
    //根据流水号和审核id查询流水号上面绑定的订单
    public function actionGetOrderNo($transid,$rboid)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetOrderNo($transid,$rboid);
        return $model;
    }
    //获取批量收款流水信息
    public function actionGetTransBatch($rbo_id){
        $search=new BsBankInfoSearch();
        $model=$search->GetTransBatch($rbo_id);
        return $model;
    }
    //获取批量订单金额
    public function actionGetBatchMoney($transid,$rboid)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetBatchMoney($transid,$rboid);
        return $model;
    }
    //获取批量流水说明
    public function actionGetBatchRemark($transid,$rboid)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetBatchRemark($transid,$rboid);
        return $model;
    }
    //查询流水绑定过的ord_pay_id
    public function actionGetBinded($transid)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetBinded($transid);
        return $model;
    }
    //查询单笔支付信息
    public function actionOrdPayInfo($ord_pay_id)
    {
        $search=new BsBankInfoSearch();
        $model=$search->OrdPayInfo($ord_pay_id);
        return $model;
    }
    //根据流水号查询所有已完成记录订单的客户id
    public function actionGetCustCode($transid)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetCustCode($transid);
        return $model;
    }
    //根据订单号查询客户id
    public function actionGetCustCodeByOrder($order_no)
    {
        $search=new BsBankInfoSearch();
        $model=$search->GetCustCodeByOrder($order_no);
        return $model;
    }
}