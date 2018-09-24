<?php

namespace app\modules\sale\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\AuditState;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmCreditApply;
use app\modules\crm\models\CrmCreditLimit;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\sale\models\OrdInfo;
use app\modules\sale\models\OrdPay;
use app\modules\sale\models\OrdRefundDt;
use app\modules\sale\models\OrdStatus;
use app\modules\sale\models\search\OrdRefundDtSearch;
use app\modules\sale\models\show\OrdInfoShow;
use app\modules\sale\models\show\OrdRefundShow;
use Yii;
use app\modules\sale\models\OrdRefund;
use app\modules\sale\models\search\OrdRefundSearch;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrdRefundController implements the CRUD actions for OrdRefund model.
 */
class OrdRefundController extends BaseActiveController
{
    public $modelClass = 'app\modules\sale\models\OrdRefund';


    /**
     * Lists all OrdRefund models.
     * @return mixed
     */
    public function actionIndex()
    {
        $search = new OrdRefundSearch();
        $dataProvider = $search->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * Displays a single OrdRefund model.
     * @param string $id
     * @return mixed
     * 退款订单的详细信息
     */
    public function actionView($id)
    {
        $model['refund'] = OrdRefundShow::findOne($id);
        $model['dt'] = $this->actionGetProduct($id);
        return $model;
    }

    /**
     * Creates a new OrdRefund model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $transaction = Yii::$app->oms->beginTransaction();
            try{
                /*退款主表*/
                $model = new OrdRefund();
                if (!$model->load($post) || !$model->save()) {
                    throw new \Exception(current($model->getFirstErrors()));
                }
                $refund_id = $model->refund_id;
                /*退款子表*/
                if(!empty($post['OrdRefundDt'])){
                    /*排除未退款的商品*/
                    foreach ($post['OrdRefundDt'] as $key => $val){
                        if(!empty($val['rfnd_type'])){
                            $arr1[$key] = $val;
                        }
                    }
                    /*保存退款商品信息*/
                    if(!empty($arr1)){
                        foreach ($arr1 as $k => $v){
                            $child = new OrdRefundDt();
                            $v['refund_id'] = $refund_id;
                            $products['OrdRefundDt'] = $v;
                            if (!$child->load($products) || !$child->save()) {
                                throw new \Exception(current($child->getFirstErrors()));
                            }
                        }
                    }else{
                        throw new \Exception('没有选择退款商品');
                    }
                }
                $transaction->commit();
                $msg = array('id'=>$refund_id);
                return $this->success('',$msg);
            }catch (\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }

        }
    }

    /**
     * Updates an existing OrdRefund model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * 退款单驳回重新修改送审
     */
    public function actionUpdate($id)
    {
        $model = OrdRefund::findOne($id);
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $transaction = Yii::$app->oms->beginTransaction();
            try{
                /*退款主表*/
                $model->rfnd_status = OrdRefund::STATUS_DEFAULT;
                if (!$model->load($post) || !$model->save()) {
                    throw new \Exception(current($model->getFirstErrors()));
                }
                /*退款子表*/
                if(!empty($post['OrdRefundDt'])){
                    /*排除未退款的商品*/
                    foreach ($post['OrdRefundDt'] as $key => $val){
                        if(!empty($val['rfnd_type'])){
                            $arr1[$key] = $val;
                        }
                    }
                    /*保存退款商品信息*/
                    if(!empty($arr1)){
                        foreach ($arr1 as $k => $v){
                            $child = OrdRefundDt::findOne($v['rfnd_dt_id']);
                            $v['refund_id'] = $id;
                            $products['OrdRefundDt'] = $v;
                            if (!$child->load($products) || !$child->save()) {
                                throw new \Exception(current($child->getFirstErrors()));
                            }
                        }
                    }else{
                        throw new \Exception('没有选择退款商品');
                    }
                }
                $transaction->commit();
                $msg = array('id'=>$id);
                return $this->success('',$msg);
            }catch (\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }

        }
    }

    /**
     * Finds the OrdRefund model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return OrdRefund the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionGetModel($id)
    {
        if (($model = OrdRefundShow::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $id
     * @return array
     * 取消退款
     */
    public function actionCancleQuote($id){
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $arr = explode(',',$id);
            $name = '';
            foreach ($arr as $key => $val){
                $priceInfo = OrdRefund::findOne($val);
                $priceInfo->rfnd_status = OrdRefund::STATUS_CANCLE_REFUND;
                $priceInfo->load($post);
                $name = $name.$priceInfo['refund_no'].',';
                $res = $priceInfo->save();
            }
            $price_no = trim($name,',');
            if ($res) {
                $msg = array('id' => $id, 'msg' => '取消退款"' . $price_no . '"');
                return $this->success('',$msg);
            } else {
                return $this->error();
            }
        }

    }

    /**
     * @param $id
     * @return array
     * 确认退款
     */
    public function actionConfirm($id){
        $arr = explode(',',$id);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach ($arr as $key => $val){
                $rid = OrdRefund::findOne($id);
//                $ord = OrdInfo::find()->select(['payment.pac_id','pac_code','ord_id','cust_code'])->leftJoin('erp.bs_payment payment','payment.pac_id='.OrdInfo::tableName().'.pac_id')->where(['ord_no'=>$rid['ord_no']])->one();
                $ord = (new Query())->select(['payment.pac_id','payment.pac_code','ord_id','cust_code','req_tax_amount'])->from('oms.ord_info info')->leftJoin('erp.bs_payment payment','payment.pac_id=info.pac_id')->where(['ord_no'=>$rid['ord_no']])->one();
                if($ord['pac_code'] == 'credit-amount'){
                    $opd = OrdPay::find()->where(['ord_id'=>$ord['ord_id']])->all();
                    $cust = (new Query())->select(['info.cust_id','apply.credit_type'])->from('erp.crm_bs_customer_info info')->leftJoin('erp.crm_credit_apply apply','apply.cust_id = info.cust_id')->where(['cust_code'=>$ord['cust_code']])->one();
                    foreach ($opd as $k => $v){
                        $per = $v['stag_cost']/$ord['req_tax_amount'];
                        $apply = CrmCreditApply::find()->where(['and',['cust_id'=>$cust['cust_id']],['credit_type'=>$cust['credit_type']]])->one();
//                        if($k < count($opd)-1){
//                            $limit = CrmCreditLimit::find()->where(['and',['credit_id'=>$apply['credit_id']],['credit_type'=>$v['credit_id']]])->one();
//                            $limit->used_limit = $limit['used_limit']-round($rid['tax_fee']*$per);
//                            $limit->surplus_limit = $limit['surplus_limit']+round($rid['tax_fee']*$per);
//                            if(!$limit->save()){
//                                throw new \Exception(current($limit->getFirstErrors()));
//                            }
//                            $count += round($v['stag_cost']*$per);
//                            $apply->credit_limit = $apply['credit_limit']+round($v['stag_cost']*$per);
//                            if(!$apply->save()){
//                                throw new \Exception(current($apply->getFirstErrors()));
//                            }
//                        }
//                        if($k == count($opd)-1){
                            $apply->allow_amount = $apply['allow_amount']+round($rid['tax_fee']*$per);
                            $apply->used_limit = $apply['used_limit']-round($rid['tax_fee']*$per);
                            $apply->surplus_limit = $apply['surplus_limit']+round($rid['tax_fee']*$per);
                            $apply->grand_total_limit = $apply['grand_total_limit']-round($rid['tax_fee']*$per);
                            $limit = CrmCreditLimit::find()->where(['and',['credit_id'=>$apply['credit_id']],['credit_type'=>$v['credit_id']]])->one();
                            $limit->used_limit = $limit['used_limit']-round($rid['tax_fee']*$per);
                            $limit->surplus_limit = $limit['surplus_limit']+round($rid['tax_fee']*$per);
                            if(!$limit->save()){
                                throw new \Exception(current($limit->getFirstErrors()));
                            }
                            if(!$apply->save()){
                                throw new \Exception(current($apply->getFirstErrors()));
                            }
                            $rid->rfnd_status = OrdRefund::STATUS_REFUND;
                            if(!$rid->save()){
                                throw new \Exception(current($rid->getFirstErrors()));
                            }
//                        }
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return \yii\db\ActiveQuery
     * 查询订单的详细信息
     */
    public function actionOrd($id){
        $model['info'] = OrdInfoShow::findOne($id);
        $model['dt'] = $this->getOrdDt($id);
        return $model;
    }

    /**
     * 下拉菜单
     */
    public function actionDownList(){
//        $downList['refund_status'] = AuditState::find()->select(['audit_id','audit_name'])->all();      //单据状态
        $downList['refund_status'] = [
            OrdRefund::STATUS_CANCLE_REFUND => '已取消',
            OrdRefund::STATUS_IN_REVIEW => '审核中',
            OrdRefund::STATUS_PASS_REVIEW => '审核完成',
            OrdRefund::STATUS_REJECT_REVIEW => '驳回',
            OrdRefund::STATUS_REFUND => '已退款'
        ];
//        $downList['order_status'] = OrdStatus::find()->select(['os_id','os_name'])->where(['yn'=>'1'])->all();      //订单状态
        $downList['order_status'] = [
            '2'=>'已付款',
            '5'=>'备货中',
            '6'=>'已出货',
            '7'=>'已收货'
        ];
        $downList['orderType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'saqut'])->all();    //订单类型
        $downList['refundType'] = BsPubdata::getList(BsPubdata::REFUND_TYPE);       //退款类型
        return $downList;
    }

    /**
     * @param $id
     * @return array
     * 根据订单id 查询订单商品信息
     */
    public function getOrdDt($id){
        $query = (new Query())->select([
            'dt.ord_dt_id',         //订单商品明细id
            'dt.ord_id',            //订单id
            'bpn.part_no',          //料号
            'bpt.pdt_name',         //品名
            'bpn.tp_spec',          //规格/型号
            'ROUND(dt.uprice_tax_o,5) AS uprice_tax_o',      //商品单价
            'ROUND(dt.sapl_quantity,2) AS sapl_quantity',     //订单数量
            'bp_1.bsp_svalue unit_name',        //单位
        ])
            ->from('oms.ord_dt dt')
            ->leftJoin('pdt.bs_partno bpn','bpn.prt_pkid = dt.prt_pkid')
            ->leftJoin('pdt.bs_product bpt', 'bpt.pdt_pkid=bpn.pdt_pkid')
            ->leftJoin('erp.bs_pubdata bp_1', 'bp_1.bsp_id=bpt.unit')
            ->where(['dt.ord_id'=>$id])
            ->all();
        return $query;
    }

    public function actionOrdDt($id){
        $query = (new Query())->select([
            'dt.rfnd_dt_id',            //退款子表ID
            'dt.rfnd_type',            //退款子表类型
            'ROUND(dt.rfnd_nums,2) AS rfnd_nums',            //退款子表退货数量
            'ROUND(dt.rfnd_amount,3) AS rfnd_amount',            //退款子表退款总金额
            'dt.remarks',            //退款子表备注
            'ordt.ord_dt_id',         //订单商品明细id
            'ordt.ord_id',            //订单id
            'bpn.part_no',          //料号
            'bpt.pdt_name',         //品名
            'bpn.tp_spec',          //规格/型号
            'ROUND(ordt.uprice_tax_o,3) AS uprice_tax_o',      //商品单价
            'ROUND(ordt.sapl_quantity,2) AS sapl_quantity',     //订单数量
            'bp_1.bsp_svalue unit_name',        //单位
        ])
            ->from('oms.ord_refund_dt dt')
            ->leftJoin('oms.ord_dt ordt','ordt.ord_dt_id = dt.sol_id')
            ->leftJoin('pdt.bs_partno bpn','bpn.prt_pkid = ordt.prt_pkid')
            ->leftJoin('pdt.bs_product bpt', 'bpt.pdt_pkid=bpn.pdt_pkid')
            ->leftJoin('erp.bs_pubdata bp_1', 'bp_1.bsp_id=bpt.unit')
            ->where(['dt.refund_id'=>$id])
            ->all();
        return $query;
    }

    /**
     * @return PriceDtSearch|array
     * 通过id获取子表信息
     */
    public function actionGetProduct()
    {

        $params = Yii::$app->request->queryParams;
        $model = new OrdRefundDtSearch();
        $model = $model->search($params)->getModels();
        return $model;
    }
}
