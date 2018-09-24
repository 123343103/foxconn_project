<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsCurrency;
use app\modules\common\models\BsPayCondition;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsSettlement;
use app\modules\crm\models\CrmCreditLimit;
use app\modules\crm\models\CrmCreditMaintain;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\LCrmCreditApply;
use app\modules\crm\models\LCrmCreditLimit;
use app\modules\crm\models\search\CrmCreditLimitSearch;
use app\modules\common\models\BsCompany;
use app\modules\crm\models\show\CrmCreditApplyShow;
use app\modules\crm\models\show\CrmCreditLimitShow;
use app\modules\hr\models\HrStaff;
use app\modules\sale\models\BsBankInfo;
use Yii;
use app\modules\crm\models\CrmCreditApply;
use app\modules\crm\models\search\CrmCreditApplySearch;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CrmCreditLimitController implements the CRUD actions for CrmCreditApply model.
 */
class CrmCreditLimitController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmCreditApply';

    /**
     * Lists all CrmCreditApply models.
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new CrmCreditLimitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
//        foreach ($model as $key=>$val){
//            $arr = explode(',',$val['credit_limit_id']);
//            $credit_limit = '';
//            $approval_limit = '';
//            $used_limit = '';
//            $surplus_limit = '';
//            $validity_date = '';
//            foreach ($arr as $k=>$v){
//                $limit = (new Query())->select(['type.business_value credit_type','limit.credit_limit','limit.approval_limit','limit.used_limit','limit.surplus_limit','date_format(limit.validity_date,"%Y-%m-%d") validity_date'])->from(CrmCreditLimit::tableName().' limit')->leftJoin(BsBusinessType::tableName().' type','type.business_type_id=limit.credit_type')->where(['limit_id'=>$v])->one();
//                if($k == count($arr)-1){
//                    $credit_limit .= '<span>'. $limit['credit_type'] .'：'.$limit['credit_limit'].'</span></br>';
//                    $approval_limit .= '<span>'.$limit['approval_limit'].'</span></br>';
//                    $used_limit .= '<span>'.$limit['used_limit'].'</span></br>';
//                    $surplus_limit .= '<span>'.$limit['surplus_limit'].'</span></br>';
//                    $validity_date .= '<span>'.(empty($limit['validity_date'])?"无":$limit['validity_date']).'</span></br>';
//                }else{
//                    $credit_limit .= '<span class="border_cell">'. $limit['credit_type'] .'：'.$limit['credit_limit'].'</span></br>';
//                    $approval_limit .= '<span class="border_cell">'.$limit['approval_limit'].'</span></br>';
//                    $used_limit .= '<span class="border_cell">'.$limit['used_limit'].'</span></br>';
//                    $surplus_limit .= '<span class="border_cell">'.$limit['surplus_limit'].'</span></br>';
//                    $validity_date .= '<span class="border_cell">'.(empty($limit['validity_date'])?"无":$limit['validity_date']).'</span></br>';
//                }
//                $val['credit_limit'] = !empty($limit['credit_limit'])?$credit_limit:'';
//                $val['approval_limit'] = !empty($limit['approval_limit'])?$approval_limit:'';
//                $val['used_limit'] = !empty($limit['used_limit'])?$used_limit:'';
//                $val['surplus_limit'] = !empty($limit['surplus_limit'])?$surplus_limit:'';
//                $val['validity_date'] = !empty($limit['validity_date'])?$validity_date:'';
//                $model[$key] = $val;
//            }
//        }
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }
    /**
     * @param $id
     * @return mixed
     * 查询额度明细
     */
    public function actionList(){
        $searchModel = new CrmCreditLimitSearch();
        $dataProvider = $searchModel->searchList(Yii::$app->request->queryParams);
        $list['rows'] = $dataProvider->getModels();
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * Displays a single CrmCreditApply model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CrmCreditApply model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CrmCreditApply();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->aid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CrmCreditApply model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->aid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CrmCreditApply model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return array
     * 额度查询冻结额度
     */
    public function actionFreeze($id)
    {
        $model = $this->findModel($id);
        $cust = CrmCustomerInfo::getCustomerInfoOne($model['cust_id']);
        $model->credit_status = CrmCreditApply::STATUS_FREEZE;
        if ($result = $model->save()) {
            $msg = array('id' => $id, 'msg' => '冻结客户"' . $cust["cust_sname"] . '"账信额度');
            return $this->success('', $msg);
        } else {
            return $this->error();
        }
    }

    /**
     * @param $id
     * @return mixed
     * 查询额度明细
     */
    public function actionLoadInfo($id){
        $query = (new Query())->select([
            'limit.laid',
            'limit.credit_limit',           //申请总额度
            'limit.approval_limit',         //批复总额度
            'limit.used_limit',             //已使用额度
            'limit.surplus_limit',         //剩余额度
            'limit.remark',                 //备注
            'limit.accessory',              //附件
            'limit.validity',               //有效期
            'cm.credit_name',               //信用额度类型
            'limit.credit_type',
            'bs_1.bnt_sname payment_clause',//付款条件
            'bp_2.bsp_svalue payment_method',//付款方式
            'bp_3.bsp_svalue initial_day',  //起算日
            'bp_4.bsp_svalue pay_day',      //付款日
            "(CASE limit.is_approval WHEN ".CrmCreditLimit::Y_APPROVAL." THEN '是' WHEN  ".CrmCreditLimit::N_APPROVAL." THEN '否' ELSE '默认' END) as approval",        //是否授权
            'hr.staff_name staff_name',      //申请人
            "date_format(limit.credit_date,'%Y-%m-%d') credit_date",//申请时间
        ])->from(CrmCreditLimit::tableName().' limit')
            ->leftJoin(CrmCreditMaintain::tableName().' cm','cm.id = limit.credit_type')
            ->leftJoin(BsSettlement::tableName().' bs_1','bs_1.bnt_id = limit.payment_clause')
            ->leftJoin(BsPubdata::tableName().' bp_2','bp_2.bsp_id = limit.payment_method')
            ->leftJoin(BsPubdata::tableName().' bp_3','bp_3.bsp_id = limit.initial_day')
            ->leftJoin(BsPubdata::tableName().' bp_4','bp_4.bsp_id = limit.pay_day')
            ->leftJoin(HrStaff::tableName().' hr','hr.staff_id = limit.credit_people')
//            ->where(['and',['aid'=>$id],['=','is_approval',CrmCreditLimit::Y_APPROVAL]])
            ->where(['aid'=>$id])
            ->andWhere(['>=','validity',date('Y-m-d',time())])
            ->all()
        ;
        $list['rows'] = $query;
        $list['total'] = count($query);
        return $list;
    }

    /**
     * Finds the CrmCreditApply model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CrmCreditApply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CrmCreditApply::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return mixed
     * 下拉菜单
     */
    public function actionDownList(){
        $downList['verifyType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'credit'])->all();    //账信类型
        $downList['company'] = BsCompany::find()->select(['company_id','company_name'])->where(['=','company_status',BsCompany::STATUS_DEFAULT])->all();
        return $downList;
    }

    /*public function actionMaintain()
    {
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $limit = isset($post['limit']) ? $post['limit'] : false;
            if ($limit) {
                foreach ($limit as $key => $val) {
                    $info = CrmCustomerInfo::findOne(['cust_code' => $val[2]]);
                    $apply = CrmCreditApply::findOne(['cust_id' => $info['cust_id']]);
                    if (!empty($apply)) {
                        $limitY = CrmCreditLimit::find()->where(['and',['=','is_approval',CrmCreditLimit::Y_APPROVAL],['aid'=>$apply['aid']]])->all();
                        foreach ($limitY as $val){
                            $crmLimitY = CrmCreditLimit::find()->where(['and',['=','is_approval',CrmCreditLimit::Y_APPROVAL],['laid'=>$val['laid']]])->one();
                            $crmLimitY->is_approval = CrmCreditLimit::N_APPROVAL;
                            $crmLimitY->save();
                        }
                    }
                }
                foreach ($limit as $key => $val) {
                    $info = CrmCustomerInfo::findOne(['cust_code' => $val[2]]);
                    $apply = CrmCreditApply::findOne(['cust_id' => $info['cust_id']]);
                    if (!empty($apply)) {
//                        $model = CrmCreditLimit::find()->where(['and', ['aid' => $apply['aid']], ['credit_type' => $val[10]]])->one();
                        $model = new CrmCreditLimit();
                        $model->aid = $apply['aid'];
                        $model->credit_type = $val[10];
                        $model->credit_limit = $val[5];
                        $model->approval_limit = $val[7];
                        $model->validity = $val[8];
                        $model->remark = $val[9];
                        $model->is_approval = CrmCreditLimit::Y_APPROVAL;
                        $model->surplus_limit = $val[7]-$model['used_limit'];
                        $currency = BsPubdata::find()->where(['bsp_svalue' => $val[4]])->one();
                        $apply->currency = $currency['bsp_id'];
//                        return $currency;
                        $result = $model->save();
                        $apply->save();
                    }
                }
                $credit_limit = CrmCreditLimit::find()->where(['and',['>=','validity',date('Y-m-d',time())],['=','is_approval',CrmCreditLimit::Y_APPROVAL]])->sum('credit_limit');
                $approval_limit = CrmCreditLimit::find()->where(['and',['>=','validity',date('Y-m-d',time())],['=','is_approval',CrmCreditLimit::Y_APPROVAL]])->sum('approval_limit');
                $used_limit = CrmCreditLimit::find()->where(['and',['>=','validity',date('Y-m-d',time())],['=','is_approval',CrmCreditLimit::Y_APPROVAL]])->sum('used_limit');
                $surplus_limit = CrmCreditLimit::find()->where(['and',['>=','validity',date('Y-m-d',time())],['=','is_approval',CrmCreditLimit::Y_APPROVAL]])->sum('surplus_limit');
                $apply->total_amount = $credit_limit;
                $apply->credit_amount = $approval_limit;
                $apply->used_limit = $used_limit;
                $apply->allow_amount = $approval_limit-$apply['used_limit'];
                $apply->surplus_limit = $surplus_limit;
                $apply->grand_total_limit = $apply['grand_total_limit']+$apply['used_limit'];
                $apply->save();
                if ($result) {
                    $transaction->commit();
                    return $this->success('');
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }*/
    public function actionMaintain()
    {
        $post = Yii::$app->request->post();
        $limit = isset($post['limit']) ? $post['limit'] : false;
        if (empty($limit)) {
            return $this->error('没有数据！');
        }
        $num = 0;
        $total = count($limit);
        foreach ($limit as $key => $val) {
            $info = CrmCustomerInfo::findOne(['cust_code' => $val[2]]);
            $apply = CrmCreditApply::findOne(['cust_id' => $info['cust_id']]);
            if (empty($info)) {
                return $this->error("共{$total}条数据，成功设置{$num}条,失败原因：客户编码为{$val[2]}的客户不存在！");
            }
            if (empty($val[6])) {
                return $this->error("共{$total}条数据，成功设置{$num}条,失败原因：客户编码为{$val[2]}的额度类型错误！");
            }
            $model = CrmCreditLimit::find()->where(['and', ['cust_id' => $info['cust_id']], ['credit_type' => $val[6]]])->one();
            if (!$model) {
                $model = new CrmCreditLimit();
            }
            $model->aid = !empty($apply['aid']) ? $apply['aid'] : '';
            $model->cust_id = $info['cust_id'];
            $model->credit_type = $val[6];
            $model->credit_limit = $val[5]; // 申请额度
            $model->approval_limit = $val[7]; // 批复额度
            $model->validity = $val[8]; // 有效期
            $model->remark = $val[9];
            $model->is_approval = CrmCreditLimit::Y_APPROVAL;
            if (!$model->save()) {
                throw new \Exception('申请表存储数据失败！');
            }
            $num ++;
        }
        return $this->success('设置成功！');
    }

    /**
     * @param $id
     * @return null|static
     * 详情页获取额度申请信息
     */
    public function actionModels($id)
    {
        $result = CrmCreditLimitShow::findOne($id);
        return $result;
    }


    // 额度使用明细的信用客户基本信息
    public function actionGetCustomerInfo($cust_id,$laid){
        $query = (new Query())->select([
            'cust.cust_id',
            'cust.cust_sname', // 客户名称
            'cust.cust_code', // 客户代码
            'bc.company_name', // 交易法人
            'hr1.staff_name customerManager', // 客户经理人
            'cm.credit_name', // 信用额度类型
            'limit.credit_limit', // 申请额度
            'limit.approval_limit', // 授信额度
            'limit.used_limit', // 已使用额度
            'limit.surplus_limit', // 剩余额度
            'limit.validity', // 有效期至
        ])->from(['cust' => CrmCustomerInfo::tableName()])
            ->leftJoin(CrmCreditLimit::tableName().' limit','cust.cust_id = limit.cust_id')
            ->leftJoin(BsCompany::tableName().' bc','bc.company_id = cust.company_id')
            ->leftJoin(CrmCustPersoninch::tableName().' inch','inch.cust_id = cust.cust_id')
            ->leftJoin(HrStaff::tableName().' hr1','hr1.staff_id = inch.ccpich_personid')
            ->leftJoin(CrmCreditMaintain::tableName().' cm','cm.id = limit.credit_type')
            ->where(['cust.cust_id'=>$cust_id,'limit.laid'=>$laid])
            ->one()
        ;
        $list = $query;
        return $list;
    }

    // 还款记录的信用客户基本信息
    public function actionGetCustomerInfo2($cust_id,$laid){
        $query = (new Query())->select([
            'cust.cust_id',
            'cust.cust_sname', // 客户名称
            'cust.cust_code', // 客户代码
            'bc.company_name', // 交易法人
            'hr1.staff_name customerManager', // 客户经理人
            'cm.credit_name', // 信用额度类型
            'bsc.cur_code', // 币别
//            'limit.credit_limit', // 申请额度
            'limit.approval_limit', // 授信额度
            'limit.used_limit', // 已使用额度
            'limit.surplus_limit', // 剩余额度
            'limit.validity', // 有效期至
        ])->from(['cust' => CrmCustomerInfo::tableName()])
            ->leftJoin(CrmCreditLimit::tableName().' limit','cust.cust_id = limit.cust_id')
            ->leftJoin(CrmCreditApply::tableName().' ap','ap.laid = limit.laid')
            ->leftJoin(BsCurrency::tableName().' bsc','bsc.cur_id = ap.currency')
            ->leftJoin(BsCompany::tableName().' bc','bc.company_id = cust.company_id')
            ->leftJoin(CrmCustPersoninch::tableName().' inch','inch.cust_id = cust.cust_id')
            ->leftJoin(HrStaff::tableName().' hr1','hr1.staff_id = inch.ccpich_personid')
            ->leftJoin(CrmCreditMaintain::tableName().' cm','cm.id = limit.credit_type')
            ->where(['cust.cust_id'=>$cust_id,'limit.laid'=>$laid])
            ->one()
        ;
        $list = $query;
        return $list;
    }

    // 额度使用明细
    public function actionOrderLimitDetails($laid){
//        return $laid;
        $query = (new Query())->select([
            'oh.saph_code', // 订单编号
            'oh.bill_oamount', // 订单总金额（含税）
            'oh.bill_oamount this_amount', // 当次使用信用额度
            'oh.saph_date', // 下单时间
//            '', // 开票时间
            'bpc.pat_sname', // 账期
//            '', // 应还款日期
            'oh.yn_pay', // 还款状态
        ])->from(['limit' => CrmCreditLimit::tableName()])
            ->leftJoin('oms.sale_orderh oh','limit.cust_id = oh.cust_id')
            ->leftJoin(BsPayCondition::tableName().' bpc','bpc.pat_id = limit.payment_clause')
            //            ->where(['cust.cust_id' => $cust_id, 'limit.credit_type' => $type])
            ->where(['limit.laid' => $laid])
            ->all()
        ;
        $list['rows'] = $query;
        $list['total'] = count($query);
        return $list;
    }

    // 额度使用明细
    public function actionOrderLimitRecord($laid){
//        return $laid;
        $query = (new Query())->select([
            'oh.saph_code', // 订单编号
            'oh.bill_oamount', // 订单总金额（含税）
            'oh.bill_oamount this_amount', // 当次使用信用额度
            'oh.saph_date', // 下单时间
//            '', // 开票时间
            'bpc.pat_sname', // 账期
//            'bpc.pat_sname', // 还款金额
//            'bpc.pat_sname', // 还款方式
            'oh.yn_pay', // 还款状态
//            'oh.yn_pay', // 还款时间
//            'oh.yn_pay', // 还款确认时间
        ])->from(['limit' => CrmCreditLimit::tableName()])
            ->leftJoin('oms.sale_orderh oh','limit.cust_id = oh.cust_id')
            ->leftJoin(BsPayCondition::tableName().' bpc','bpc.pat_id = limit.payment_clause')
//            ->leftJoin(BsBankInfo::tableName().' bbi','')
            ->where(['limit.laid' => $laid])
            ->all()
        ;
        $list['rows'] = $query;
        $list['total'] = count($query);
        return $list;
    }
}
