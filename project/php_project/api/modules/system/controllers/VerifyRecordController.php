<?php
/**
 * @todo 审核
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2017/1/12
 * Time: 15:43
 */

namespace app\modules\system\controllers;

use app\modules\common\tools\SplitOrder;
use app\commands\GetCustomerCode;
use app\controllers\BaseActiveController;
use app\models\User;
use app\modules\common\models\AuditState;
use app\modules\common\models\BsBusiness;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsForm;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsReviewColumn;
use app\modules\common\models\BsReviewCondition;
use app\modules\common\models\BsReviewRule;
use app\modules\common\models\BsReviewRuleChild;
use app\modules\crm\models\BsCredit;
use app\modules\crm\models\CrmCreditApply;
use app\modules\crm\models\CrmCreditFile;
use app\modules\crm\models\CrmCreditLimit;
use app\modules\crm\models\CrmCustomerApply;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmImessage;
use app\modules\crm\models\LCreditFile;
use app\modules\crm\models\LCrmCreditApply;
use app\modules\crm\models\LCrmCreditFile;
use app\modules\crm\models\LCrmCreditLimit;
use app\modules\crm\models\LLimitType;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\BsMaterial;
use app\modules\ptdt\models\BsPartno;
use app\modules\ptdt\models\BsProduct;
use app\modules\ptdt\models\LPartno;
use app\modules\ptdt\models\PdFirm;
use app\modules\ptdt\models\PdFirmReport;
use app\modules\ptdt\models\PdRequirement;
use app\modules\ptdt\models\show\PdFirmReportShow;
use app\modules\purchase\models\BsPrch;
use app\modules\purchase\models\BsReq;
use app\modules\sale\models\BsBankCheck;
use app\modules\sale\models\LOrdDt;
use app\modules\sale\models\LOrdInfo;
use app\modules\sale\models\LOrdPay;
use app\modules\sale\models\LPriceDt;
use app\modules\sale\models\LPriceFile;
use app\modules\sale\models\LPriceInfo;
use app\modules\sale\models\LPricePay;
use app\modules\sale\models\OrdDt;
use app\modules\sale\models\OrdInfo;
use app\modules\sale\models\OrdPay;
use app\modules\sale\models\OrdRefund;
use app\modules\sale\models\OrdStatus;
use app\modules\sale\models\PriceDt;
use app\modules\sale\models\PriceFile;
use app\modules\sale\models\PriceInfo;
use app\modules\sale\models\PricePay;
use app\modules\sale\models\RBankOrder;
use app\modules\sale\models\RepayCredit;
use app\modules\sale\models\SaleOrderh;
use app\modules\sale\models\SaleOrderl;
use app\modules\sale\models\SaleQuotedpriceH;
use app\modules\spp\models\BsSupplier;
use app\modules\system\models\AuthAssignment;
use app\modules\system\models\AuthItem;
use app\modules\system\models\search\VerifyrecordChildSearch;
use app\modules\system\models\search\VerifyrecordSearch;
use app\modules\system\models\show\VerifyrecordChildShow;
use app\modules\system\models\show\VerifyrecordShow;
use app\modules\system\models\SysParameter;
use app\modules\system\models\Verifyrecord;
use app\modules\system\models\VerifyrecordChild;
use app\modules\warehouse\models\BsInvWarn;
use app\modules\warehouse\models\BsInvWarnH;
use app\modules\warehouse\models\BsSitInvt;
use app\modules\warehouse\models\BsSt;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\IcInvCosth;
use app\modules\warehouse\models\IcInvh;
use app\modules\warehouse\models\IcInvl;
use app\modules\warehouse\models\InvChangeh;
use app\modules\warehouse\models\InvChangel;
use app\modules\warehouse\models\InvWarner;
use app\modules\warehouse\models\InWhpdt;
use app\modules\warehouse\models\LInvtRe;
use app\modules\warehouse\models\OrdLgst;
use app\modules\warehouse\models\OWhpdt;
use app\modules\warehouse\models\OWhpdtDt;
use app\modules\warehouse\models\PdtInventory;
use app\modules\warehouse\models\LInvWarn;
use app\modules\warehouse\models\LInvWarnH;
use app\modules\warehouse\models\PdtInventoryDt;
use app\modules\sale\models\BsBankInfo;
use yii;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\Html;

class VerifyRecordController extends BaseActiveController
{
    public $modelClass = 'app\modules\system\models\Verifyrecord';
    const _REJECT = 0; //驳回
    const _PUSH = 1; //送审
    const _PASS = 2; //通过
    const _OTHER = 3; //其他

    public function actionIndex($id)
    {
        $searchModel = new VerifyrecordSearch();
        $user = User::findOne($id);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id, $user->is_supper);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
//        $list['aaa'] = $this->crm_customer_apply('209', '2', null, null);
//        $custcode = new GetCustomerCode();
//        $list['ddd'] = $custcode->getCode("0", "fm123654987", "0");
        return $list;
    }

    /*
     * 设置审核人
     */
    public function actionReviewer($type)
    {
        $data['flag'] = 1;
        $data['msg'] = '';
        $isOrgRule = $this->isOrgRule($type);
        if ($isOrgRule == 1) { // 如果开启部门规则
            // 获取本人部门
            $orgCode = HrStaff::getOrgCode(Yii::$app->request->get('staff_id'));
            $orgModel = new HrOrganization();
            $orgId = $orgModel->find()->select('organization_id')->where(['organization_code' => $orgCode])->one();
            if (!empty($orgId)) {
                // 查找部门规则
                $ruleModel = BsReviewRule::find()->where(['business_type_id' => $type])->andWhere(['<>', 'business_status', BsReviewRule::STATUS_DELETE])->andWhere(['org' => $orgId['organization_id']])->one();
                if (empty($ruleModel)) {
                    $data['flag'] = 0;
                    $data['msg'] = '没有找到该用户所在部门规则';
                    return $data;
                }
            } else {
                $data['flag'] = 0;
                $data['msg'] = '没有找到该用户所在部门';
                return $data;
            }
        } else {
            // 查找系统默认规则
            $ruleModel = BsReviewRule::find()->where(['business_type_id' => $type])->andWhere(['<>', 'business_status', BsReviewRule::STATUS_DELETE])->andWhere(['is', 'org', null])->one();
            if (empty($ruleModel)) {
                $data['flag'] = 0;
                $data['msg'] = '没有设置任何规则';
                return $data;
            }
        }

        //审核流主表ID
        $ruleId = $ruleModel->review_rule_id;
        $data['status'] = $ruleModel->business_status == BsReviewRule::STATUS_EDITABLE ? true : false; // 是否自定义顺序
        //业务代码
//        $ruleCode  = $ruleModel->business_code;

        $data['reviewer'] = (new yii\db\Query())->select([
            'info.rule_child_id',          //id
            'hs.staff_name review_user',          //审核人
            'auth.title review_role',          //审核角色
        ])->from(BsReviewRuleChild::tableName() . ' info')
            //关联档案建立人
            ->leftJoin(User::tableName() . ' user', 'user.user_id=' . 'info.review_user_id')
            ->leftJoin(HrStaff::tableName() . ' hs', 'hs.staff_id=' . 'user.staff_id')
            ->leftJoin(AuthItem::tableName() . ' auth', 'auth.name=' . 'info.review_role_id')
            ->where(['info.review_rule_id' => $ruleId])
            ->orderBy(['rule_child_index' => SORT_ASC])->all();
        return $data;
    }


    public function actionLoadRecord($id)
    {
        $searchModel = new VerifyrecordChildSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 送审
     */
    public function actionVerifyRecord()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $post = Yii::$app->request->post();
        try {
            $busId = $post['id'];    //单据ID
            $type = $post['type'];  //审核流类型ID
            $staff = $post['staff'];//送审人
            $reviewer = explode(',', $post['reviewer']);//审核人
            // 查找记录阻止重复送审
            $recordH = Verifyrecord::find()->where(['vco_busid' => $busId, 'but_code' => $type])->andFilterWhere(['!=', 'vco_status', Verifyrecord::STATUS_REJECT])->one();
            if (!empty($recordH)) {
                return $this->error('不允许重复送审!');
            }
            //获取业务审批流程
            $isOrgRule = $this->isOrgRule($type);
            $ruleModel = $this->getRule($type, $isOrgRule, $staff);
            //审核流主表ID
            $ruleId = $ruleModel->review_rule_id;
            //业务代码
            $ruleCode = $ruleModel->business_code;
            //获取审核人单位代码
            $orgCode = HrStaff::getOrgCode($staff);

            $model = Verifyrecord::find()->where(['vco_busid' => $busId, 'but_code' => $type])->andFilterWhere(['vco_status' => Verifyrecord::STATUS_REJECT])->one();
            $oldChildCount = 0; //原有的审核记录数
            //驳回后再送审进入
            if (!empty($model)) {
                $model->vco_status = Verifyrecord::STATUS_DEFAULT;
                if (!$model->save()) {
                    throw new \Exception(current($model->getFirstErrors()));
                }
//                $childModel = VerifyrecordChild::find()->where(['vco_id' => $model->vco_id])->all();
//                foreach ($childModel as $key => $val){
//                    $val->vcoc_status = VerifyrecordChild::STATUS_CHECKIND;
//                    $val->vcoc_datetime = '';
//                    $val->vcoc_remark = '';
//                    $val->vcoc_computeip = '';
//                    if (!$val->save()) {
//                        throw new \Exception(current($val->getFirstErrors()));
//                    }
//                }
                $oldChild = VerifyrecordChild::find()->where(['vco_id' => $model->vco_id])->asArray()->all();
                $oldChildCount = count($oldChild);
//                $transaction->commit();
//                return $this->success();
            } else {
                //审核,顺序
                $model = new Verifyrecord();
                $model->ver_id = $ruleId;                             //审核流ID
                $model->bus_code = $ruleCode;
                $model->but_code = $type;
                $model->vco_busid = $busId;                           //单据ID
                $model->vco_send_acc = $staff;                        //送审人
                $model->vco_send_dept = $orgCode->organization_code;  //送审部门
                if (!$model->save()) {
                    throw new \Exception(current($model->getFirstErrors()));
                }
            }
            $ruleChild = '';
            //自选审核人进入
            if (!empty($reviewer)) {
                foreach ($reviewer as $key => $value) {
                    $ruleChild[$key] = BsReviewRuleChild::find()->where(['rule_child_id' => $value])->one();
                    $ruleChild[$key]['rule_child_index'] = $key + 1;
                }
            } else {
                $ruleChild = BsReviewRuleChild::find()->where(['review_rule_id' => $ruleId])->all();
            }
            foreach ($ruleChild as $key => $val) {
                $childModel = new VerifyrecordChild();
                $childId = $val['rule_child_id'];
                //条件不通过退出
                $isCondition = $this->getCondition($childId, $busId);
                if ($isCondition === false) {
                    continue;
                }
                $childModel->vco_id = $model->vco_id;
                $childModel->ver_acc_id = $val['review_user_id'];     //当前审核人
                $childModel->ver_acc_rule = $val['review_role_id'];   //当前审核角色
                $childModel->ver_scode = $val['rule_child_index'] + $oldChildCount;
                if ($val['rule_child_index'] == 1) {
                    $childModel->vcoc_status = VerifyrecordChild::STATUS_CHECKIND;
                }
                $childModel->acc_code_agent = $val['agent_one_id'];       //代理人
                $childModel->rule_code_agent = $val['review_role_id'];    //代理角色
                //            $model->acc_code_agents=$val['agent_two_id'];         //代理人
                if (!$childModel->save()) {
                    throw new \Exception(current($childModel->getFirstErrors()));
                }
            }
            $firstChild = reset($ruleChild);
            if (!empty($firstChild['review_user_id'])) {
                $token = $this->getToken($firstChild['review_user_id'], $model->vco_id);
                if (!empty($token['email'])) {
                    $res = $this->sendMail($token['email'], $token['url']);
                    if ($res->status == 1) {
                        // 发送邮件成功改变token 使原有token失效
//                        $this->changeToken($firstChild['review_user_id']);
                    }
                }
            }
            if (!$this->newModel($ruleCode, $busId, self::_PUSH, null, $staff)) {
                throw new \Exception('实例化出错---');
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     *  审核条件
     * @param $id
     * @param $busId
     */
    private function getCondition($id, $busId)
    {
        $condition = BsReviewCondition::find()->where(['rule_child_id' => $id])->all();
        foreach ($condition as $val) {
            $conVal = $val->condition_value;
            $logVal = $val->condition_logic;
            $column = BsReviewColumn::getColumnById($val->column);
            $conditionVal = $this->newModel($column->business_code, $busId, null, $column->form_column_name);
            switch ($logVal) {
                case 'egt':
                    return $conditionVal >= $conVal;
                    break;
                case 'elt':
                    return $conditionVal <= $conVal;
                    break;
                case 'eq':
                    return $conditionVal == $conVal;
                    break;
                case 'gt':
                    return $conditionVal > $conVal;
                    break;
                case 'lt':
                    return $conditionVal < $conVal;
                    break;
                default:
                    return true;
            }
        }
    }

    /**
     * 通过审核
     */
    public function actionAuditPass()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $idArr = explode(',', $post['id']);
            $str = '';
            foreach ($idArr as $val) {
//                $result = $this->actionModels($val);//根据审核ID获取审核信息
                $model = Verifyrecord::find()->where(['vco_id' => $val])->one();
//                $cust = CrmCustomerApply::findOne($model['vco_busid']);//根据被送审的单据ID获取客户id
                $str .= '单号:' . $model['vco_code'] . ' ';
                //当前审核人
                $child = VerifyrecordChild::find()->where(['vco_id' => $val])->andWhere(['vcoc_status' => VerifyrecordChild::STATUS_CHECKIND])->one();
//                $child->vcoc_computeip = $post['reviewer']['ip'];
                $child->vcoc_computeip = $post['ip'];
//                $child->vcoc_remark = $post['remark'];
                $child->vcoc_remark = !empty($post['vcoc_remark']) ? $post['vcoc_remark'] : null;
                $child->load(Yii::$app->request->post());
                $child->vcoc_status = VerifyrecordChild::STATUS_PASS;
                $child->vcoc_datetime = date("Y-m-d H:i:s");
                $child->save();
                //下一个审核人
                $scode = $child['ver_scode'] + 1;
                $nextChild = VerifyrecordChild::find()->where(['vco_id' => $val])
                    //                    ->andWhere(['vcoc_status'=>VerifyrecordChild::STATUS_DEFAULT])
                    ->andWhere(['ver_scode' => $scode])->one();
                //如果是最后一个审核人则单据审核通过
                if (!empty($nextChild)) {
                    $nextChild->load(Yii::$app->request->post());
                    $nextChild->vcoc_status = VerifyrecordChild::STATUS_CHECKIND;
                    $nextChild->save();
                    if (!empty($nextChild->ver_acc_id)) {
                        $token = $this->getToken($nextChild->ver_acc_id, $val);
                        if (!empty($token['email'])) {
                            $this->sendMail($token['email'], $token['url']);
                        }
                    }
                    if($model->bus_code == 'credit'){
//                        return $this->newModel($model->bus_code, $model->vco_busid, self::_PASS, null, null, $post);
//                        if (!$this->newModel($model->bus_code, $model->vco_busid, self::_PASS, null, null, $post)) {
//                            throw new \Exception('实例化保存出错');
//                        }
                        $lim = LLimitType::find()->where(['l_credit_id'=>$model->vco_busid])->all();
                        if(empty($lim)) {
                            foreach ($post['LCrmCreditLimit'] as $key => $val) {
                                $limit = LCrmCreditLimit::find()->where(['and', ['l_limit_id' => $val['l_limit_id']], ['is_approval' => LCrmCreditLimit::DEFAULT_APPROVAL]])->one();
                                $l_credit_id = $limit->l_credit_id;
                                $credit_type = $limit->credit_type;
                                $credit_limit = $limit->credit_limit;
                                $Llimit = new LLimitType();
                                $Llimit->l_limit_id = $val['l_limit_id'];
                                $Llimit->l_credit_id = $l_credit_id;
                                $Llimit->credit_type = $credit_type;
                                $Llimit->credit_limit = $credit_limit;
                                $Llimit->approval_limit = $val['approval_limit'];
                                $Llimit->validity_date = $val['validity_date'];
                                if (!$Llimit->save()) {
                                    throw  new \Exception("批复额度失败");
                                }
                            }
                            if (!empty($post['LCrmCreditApply'])) {
                                $Lfile = new LCreditFile();
                                $Lfile->l_credit_id = $model->vco_busid;
                                $Lfile->file_old = $post['LCrmCreditApply']['file_old'];
                                $Lfile->file_new = $post['LCrmCreditApply']['file_new'];
                                if (!$Lfile->save()) {
                                    throw  new \Exception("文件保存失败");
                                }
                            }
                        }
                    }
                } else {
                    $model->vco_status = Verifyrecord::STATUS_PASS;
                    $model->save();
                    if (!$this->newModel($model->bus_code, $model->vco_busid, self::_PASS, null, null, $post)) {
                        throw new \Exception('实例化保存出错');
                    }
                }
//                if ($post['batchaudit'] == 0) //单个审核通过时修改发票类型
//                {
//                    $CustomerInfo = CrmCustomerInfo::find()->where(['cust_id' => $cust['cust_id']])->one();//根据客户ID获取客户信息
//                    if ($CustomerInfo != null) {
//                        $CustomerInfo->load($post);
//                        $CustomerInfo->HV_INV_TYPE = !empty($post['hv_inv_type']) ? $post['hv_inv_type'] : null;
//                        if (!$CustomerInfo->save()) {
//                            throw new \Exception("修改发票类型失败");
//                        }
//                    }
//                }
            }
            $transaction->commit();
            return $this->success('', $str . '审核通过');
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 驳回审核
     * @return array
     */
    public function actionAuditReject()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $idArr = explode(',', $post['id']);
            $str = '';
            foreach ($idArr as $val) {
                $model = Verifyrecord::find()->where(['vco_id' => $val])->one();
                $model->vco_status = Verifyrecord::STATUS_REJECT;
                $model->save();
                VerifyrecordChild::updateAll(['vcoc_status' => VerifyrecordChild::STATUS_OVER], ["and", ['vco_id' => $val], ['vcoc_status' => VerifyrecordChild::STATUS_DEFAULT]]);
                //当前审核人
                $child = VerifyrecordChild::getCurrentAuditor($val);
                $child->vcoc_computeip = $post['ip'];
                $child->vcoc_datetime = date('Y-m-d H:i:s', time());
                //        $post = Yii::$app->request->post();
                $child->vcoc_remark = !empty($post['remark']) ? $post['remark'] : null;
                //        $child->load($post);
                $child->vcoc_status = VerifyrecordChild::STATUS_REJECT;
                $child->vcoc_remark = !empty($post['vcoc_remark']) ? $post['vcoc_remark'] : null;
                $child->save();
//                $this->newModel($model->bus_code,$model->vco_busid,self::_REJECT);

                if (!$this->newModel($model->bus_code, $model->vco_busid, self::_REJECT, null, null, $post)) {
                    throw new \Exception('驳回 实例化保存出错');
                }
                $str .= '单号:' . $model['vco_code'] . ' ';
            }
            $transaction->commit();
            return $this->success('', $str . '审核被驳回');
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

//    /**
//     * 审核驳回操作
//     * @param $val
//     */
//    private function auditAction($val, $post)
//    {
//        $model = Verifyrecord::find()->where(['vco_id' => $val])->one();
//        $model->vco_status = Verifyrecord::STATUS_REJECT;
//        $model->save();
//        //当前审核人
//        $child = VerifyrecordChild::getCurrentAuditor($val);
//        $child->vcoc_computeip = $post['ip'];
//        $child->vcoc_datetime = date('Y-m-d H:i:s', time());
//    //        $post = Yii::$app->request->post();
//        $child->vcoc_remark = $post['remark'];
//    //        $child->load($post);
//        $child->vcoc_status = VerifyrecordChild::STATUS_REJECT;
//        $child->save();
//        $this->newModel($model->bus_code,$model->vco_busid,self::_REJECT);
//    }


    public function actionFirmReport($id)
    {
        $model = PdFirmReportShow::getReportOne($id);
        return $model;
    }


    /**
     * 实例化模型
     * @param $code  审核单据类型
     * @param $busId 单据ID
     * @const $type  操作类型
     * @const $staff  操作人工号
     * $param $select
     */
    private function newModel($code, $busId, $type, $select = null, $staff = null, $post = null)
    {
        switch ($code) {
            case 'cscbsh':
                $result = $this->firm_report($busId, $type, $select);
                break;
            case 'spkfxqsh':
                $result = $this->product_dvlp($busId, $type, $select, $staff);
                break;
            case 'khbmsh':
                $result = $this->crm_customer_apply($busId, $type, $select, $staff, $post);
                break;
            case 'credit':
                $result = $this->credit_apply($busId, $type, $select, $staff, $post);
                break;
//            case 'saqut':
//                $result = $this->quotation_apply($busId, $type, $select, $staff);
//                break;
            case "wm02":
                $result = $this->other_outstock($busId, $type, $select, $staff);
                break;
            case 'wm01':
                $result = $this->other_stock_in($busId, $type, $select, $staff);
                break;
            case 'wm07':
                $result = $this->wh_waring($busId, $type, $select, $staff);
                break;
            case 'wm04':
                $result = $this->inv_change($busId, $type, $select, $staff);
                break;
            case 'wm05':
                $result = $this->inv_change($busId, $type, $select, $staff);
                break;
            case 'wm09':
                $result = $this->set_invt_warning($busId, $type, $select, $staff);
                break;
            case 'gysbm':
                $result = $this->supplier($busId, $type, $select, $staff);
                break;
            case 'pdtsel'://商品上架
                $result = $this->set_pdt_upshelf($busId, $type, $select, $staff);
                break;
            case 'pdtreupshelf'://商品重新上架
                $result = $this->set_pdt_reupshelf($busId, $type, $select, $staff);
                break;
            case 'pdtdowmsel'://商品下架
                $result = $this->set_pdt_downshelf($busId, $type, $select, $staff);
                break;
            case 'reqer'://请购
                $result = $this->BsReq($busId, $type, $select, $staff);
                break;
            case 'wm03'://调拨单
                $result = $this->InvChangeh($busId, $type, $select, $staff);
                break;
            case 'uptepdtsel'://商品修改
                $result = $this->update_pdt_verify($busId, $type, $select, $staff);
                break;
            case 'notify'://采购
                $result = $this->Notify($busId, $type, $select, $staff);
                break;
            case 'order'://交易订单改价
                $result = $this->OrdReprice($busId, $type, $select, $staff);
                break;
            case 'saqut'://报价单
                $result = $this->quoteOrder($busId, $type, $select, $staff);
                break;
            case 'refund'://退款
                $result = $this->ordeRefund($busId, $type, $select, $staff);
                break;
            case 'checklist'://盘点
                $result = $this->PdtInventory($busId, $type, $select, $staff);
                break;
            case 'whprice'://仓库出仓费用
                $result = $this->whPrice($busId, $type, $select, $staff);
                break;
            case 'ordlgst'://物流订单
                $result = $this->Ordlgst($busId, $type, $select, $staff);
                break;
            case 'qtckd'://其它出库单
                $result = $this->OWhpdt($busId, $type, $select, $staff);
                break;
            case 'ordrecives'://单笔收款
                $result = $this->OneReviceVerify($busId, $type);
                break;
            case 'batchreceipts'://单笔收款
                $result = $this->BatchRecevieOne($busId, $type);
                break;
        }
        return $result;
    }

    /**
     * 厂商呈报
     * @param $id
     * @param $isPass
     */
    private function firm_report($id, $type, $select = null)
    {
        $model = PdFirmReport::getFirmReport($id, $select);
        switch ($type) {
            case self::_PUSH:
                $status = PdFirmReport::CHECK_PENDING;
                break;
            case self::_PASS:
                $status = PdFirmReport::REPORT_COMPLETE;
                break;
            case self::_REJECT:
                $status = PdFirmReport::REPORT_PREPARE;
                break;
            default:
                return $model;
        }
        $model->report_status = $status;
        $model->save();
        $firm = PdFirm::findOne($model['firm_id']);
        $firm->firm_status = PdFirm::DEVELOP_OVER;
        return $firm->save();
    }

    /**
     * 商品开发
     * @param $id
     * @param $isPass
     */
    private function product_dvlp($id, $type, $select = null, $staff)
    {
        $model = PdRequirement::getRequirementOne($id, $select);
        switch ($type) {
            case self::_PUSH:
                $status = PdRequirement::STATUS_REVIEW;
                $model->offer_staff = $staff;
                $model->offer_date = date('Y-m-d H:i:s', time());
                break;
            case self::_PASS:
                $status = PdRequirement::STATUS_FINISH;
                break;
            case self::_REJECT:
                $status = PdRequirement::STATUS_REJECT;
                break;
            default:
                return $model;
        }
        $model->pdq_status = $status;

        return $model->save();
    }

    /**
     * @param $id
     * @param $type
     * @param null $select
     * @return bool
     * 账信申请
     */
    private function credit_apply($id, $type, $select = null, $staff, $post)
    {
        $model = LCrmCreditApply::getCreditApply($id, $select);
        switch ($type) {
            case self::_PUSH:
                $status = LCrmCreditApply::STATUS_REVIEW;
                break;
            case self::_PASS:
                $transactions = Yii::$app->db->beginTransaction();
                try {
                    $status = LCrmCreditApply::STATUS_OVER;
                    $credit_amount = '';
                    foreach ($post['LCrmCreditLimit'] as $key => $val) {
                        $limit = LCrmCreditLimit::find()->where(['and', ['l_limit_id' => $val['l_limit_id']], ['is_approval' => LCrmCreditLimit::DEFAULT_APPROVAL]])->one();
                        $limit->approval_limit = $val['approval_limit'];
                        $limit->validity_date = $val['validity_date'];
                        $limit->is_approval = LCrmCreditLimit::YES_APPROVAL;
                        if (!$limit->save()) {
                            throw  new \Exception("批复额度失败");
                        }
                        $credit_amount += $val['approval_limit'];
                    }
                    if (!empty($post['LCrmCreditApply'])) {
                        $model->file_old = $post['LCrmCreditApply']['file_old'];
                        $model->file_new = $post['LCrmCreditApply']['file_new'];
                    }
                    $model->credit_amount = $credit_amount;
                    $model->credit_status = $status;
                    if (!$model->save()) {
                        throw  new \Exception("审核失败");
                    }
                    $apply = CrmCreditApply::findOne(['cust_id' => $model['cust_id']]);
                    if (empty($apply)) {
                        $apply = new CrmCreditApply();
                    }
                    $arr = Json::decode(Json::encode($model), true);
                    $apply->credit_code = BsForm::getCode(CrmCreditApply::tableName(), $apply);//申請通過時生成編碼
                    if (!empty($post['LCrmCreditApply'])) {
                        $apply->file_old = $post['LCrmCreditApply']['file_old'];
                        $apply->file_new = $post['LCrmCreditApply']['file_new'];
                    }
                    $apply->setAttributes($arr);
                    $apply->allow_amount = $arr['credit_amount'];
                    $apply->surplus_limit = $arr['credit_amount'];
                    if (!$apply->save()) {
                        throw new \Exception(current($apply->getFirstErrors()));
                    }
                    $pub = BsPubdata::find()->where(['and',['bsp_stype'=>'khlb'],['bsp_svalue'=>'账信会员']])->one();
                    $cust = CrmCustomerInfo::findOne($model['cust_id']);
                    $cust->member_type = $pub['bsp_id'];
                    if (!$cust->save()) {
                        throw new \Exception(current($cust->getFirstErrors()));
                    }
                    $status = CrmCustomerStatus::findOne($model['cust_id']);
                    $status->member_status = CrmCustomerStatus::STATUS_DEFAULT;
                    if (!$status->save()) {
                        throw new \Exception(current($status->getFirstErrors()));
                    }
                    $credit_id = $apply->credit_id;
                    $countLimit = LCrmCreditLimit::find()->where(['and', ['l_credit_id' => $id], ['is_approval' => LCrmCreditLimit::YES_APPROVAL]])->all();
                    if ($countLimit) {
                        if (CrmCreditLimit::deleteAll(['credit_id' => $apply['credit_id']]) > count($countLimit)) {
                            throw  new \Exception("删除额度失败");
                        };
                        $countLimits = Json::decode(Json::encode($countLimit), true);
                        foreach ($countLimits as $key => $value) {
                            $crmCreditLimit = new CrmCreditLimit();
                            $crmCreditLimit->setAttributes($value);
                            $crmCreditLimit->surplus_limit = $value['approval_limit'];
                            $crmCreditLimit->credit_id = $credit_id;
                            if (!$crmCreditLimit->save()) {
                                throw  new \Exception("更新额度失败");
                            };
                        }
                    }
                    $fileArrMessage = LCrmCreditFile::find()->where(['and', ['l_credit_id' => $id], ['file_type' => '10']])->all();
                    if ($fileArrMessage) {
                        if (CrmCreditFile::deleteAll(['and', ['credit_id' => $apply['credit_id']], ['file_type' => '10']]) > count($fileArrMessage)) {
                            throw  new \Exception("签字档上传失败");
                        };
                        $fileArrMessages = Json::decode(Json::encode($fileArrMessage), true);
                        foreach ($fileArrMessages as $key => $val) {
                            $fileMessage = new CrmCreditFile();
                            $fileMessage->credit_id = $credit_id;
                            $fileMessage->setAttributes($val);
                            if (!$fileMessage->save()) {
                                throw  new \Exception("签字档上传失败");
                            };
                        }
                    }
                    $fileArr = LCrmCreditFile::find()->where(['and', ['l_credit_id' => $id], ['file_type' => '11']])->all();
                    if ($fileArr) {
                        if (CrmCreditFile::deleteAll(['and', ['credit_id' => $apply['credit_id']], ['file_type' => '11']]) > count($fileArr)) {
                            throw  new \Exception("附件上传失败");
                        };
                        $fileArrs = Json::decode(Json::encode($fileArr), true);
                        foreach ($fileArrs as $key => $val) {
                            $file = new CrmCreditFile();
                            $file->credit_id = $credit_id;
                            $file->setAttributes($val);
                            if (!$file->save()) {
                                throw  new \Exception("附件上传失败");
                            };
                        }
                    }
                    $transactions->commit();
                    return $this->success();
                } catch (\Exception $e) {
                    $transactions->rollBack();
                    return $this->error($e->getMessage());
                }

                break;
            case self::_REJECT:
                $transactions = Yii::$app->db->beginTransaction();
                try {
                    $status = LCrmCreditApply::STATUS_REJECT;
                    $credit_amount = '';
                    foreach ($post['LCrmCreditLimit'] as $key => $val) {
                        $limit = LCrmCreditLimit::find()->where(['and', ['l_limit_id' => $val['l_limit_id']], ['is_approval' => LCrmCreditLimit::DEFAULT_APPROVAL]])->one();
                        $limit->is_approval = LCrmCreditLimit::NO_APPROVAL;
                        if (!$limit->save()) {
                            throw  new \Exception("批复额度失败");
                        }
//                        $credit_amount += $val['approval_limit'];
                    }
//                    $model->credit_amount = $credit_amount;
                    $model->credit_status = $status;
                    if (!$model->save()) {
                        throw  new \Exception("审核失败");
                    }
                    $lt = LLimitType::find()->where(['l_credit_id' => $id])->count();
                    if (LLimitType::deleteAll(['l_credit_id' => $id]) < $lt) {
                        throw  new \Exception("删除临时额度失败");
                    };
                    $cf = LCreditFile::find()->where(['l_credit_id' => $id])->count();
                    if (LCreditFile::deleteAll(['l_credit_id' => $id]) < $cf) {
                        throw  new \Exception("删除临时额度失败");
                    };
                    $transactions->commit();
                    return $this->success();
                } catch (\Exception $e) {
                    $transactions->rollBack();
                    return $this->error($e->getMessage());
                }
                break;
            default:
                return $model;
        }
        $model->credit_status = $status;
        return $model->save();
    }

    /**
     * @param $id
     * @param $type
     * @param null $select
     * @return bool
     * 报价单申请
     */
    private function quotation_apply($id, $type, $select = null, $staff)
    {
//        $model = SaleCustrequireH::getQuotedOne($id, $select);
//        $model = SaleCustrequireH::find()->where(['saph_id'=>$id])->one();
//        return false;
        $model = SaleQuotedpriceH::findOne($id);
        switch ($type) {
            case self::_PUSH:
                $status = SaleQuotedpriceH::STATUS_CHECKING;
                break;
            case self::_PASS:
                $transaction2 = Yii::$app->oms->beginTransaction();
                try {
                    $status = SaleQuotedpriceH::STATUS_FINISH;
                    $model = SaleQuotedpriceH::findOne($id);
                    $model->saph_status = $status;
                    $model->saph_flag = SaleQuotedpriceH::TO_QUOTED;
                    $arr = Json::decode(json::encode($model), true);
                    $orderHModel = new SaleOrderh();
                    $orderHModel->setAttributes($arr);
                    if (empty($model->saph_code)) {
                        $orderHModel->saph_code = BsForm::getCode(SaleOrderh::tableName(), $model);
                    }
                    $orderHModel->origin_id = $model->saph_id; // 源单ID
                    $orderHModel->p_bill_id = $model->saph_id; // 上级单ID
//                    $orderHModel->OS_ID = $model->saph_status; // 訂單狀態，來源ord_status
//                    $orderHModel->OS_OPPER = '110'; // 訂單狀態更改人，狀態修改，必更新
//                    $orderHModel->OS_DATE = date('Y-m-d H:i:s'); // 訂單狀態更改時間，狀態更改必更新
//                    $orderHModel->OS_IP = 'ip'; // 操作人IP
                    // 转移成交易订单
                    if (!$orderHModel->save()) {
//                        return false;
                        throw new \Exception(current($orderHModel->getFirstErrors()));
                    }
                    // 改变客户订单状态saph_status和报价标志saph_flag
                    if (!$model->save()) {
                        throw new \Exception(current($model->getFirstErrors()));
                    }
                    $quotedChildren = SaleQuotedpriceH::find()->where(['saph_id' => $model->saph_id])->all();
                    $children = json::decode(json::encode($quotedChildren), true);
                    foreach ($children as $k => $v) {
                        $childModel = new SaleOrderl();
                        $childModel->setAttributes($v);
                        $childModel->soh_id = $orderHModel->soh_id; // 交易订单主表ID
                        $childModel->origin_id = $v['sapl_id']; // 源单ID
                        $childModel->p_bill_id = $v['sapl_id']; // 上级单ID
                        if (!$childModel->save()) {
                            throw new \Exception(current($childModel->getFirstErrors()));
                        }
                    }
                    $transaction2->commit();
                    return true;
                } catch (\Exception $e) {
                    $transaction2->rollBack();
                    return $e->getMessage();
                    return false;
                }
            case self::_REJECT:
                $status = SaleQuotedpriceH::STATUS_PREPARE;
                break;
            default:
                return $model;
        }
        $model->saph_status = $status;
        return $model->save();
    }

    /**
     * @param $id
     * @param $type
     * @param null $select
     * @param $staff
     * @return bool
     * 客戶編碼審核
     */
    private function crm_customer_apply($id, $type, $select = null, $staff, $post = null)
    {
        $model = CrmCustomerApply::getCustomerOne($id, $select);
        $crminfo = CrmCustomerInfo::getCustomerInfoOne($model['cust_id']);
        //return $crminfo['three_to_one'];
        $threetoone = $crminfo['three_to_one'];//是否三证合一 1是，0否
        $custtaxcode = $crminfo['cust_tax_code'];//税籍编码
        $currency = $crminfo['member_curr'];//交易币别代码
        $currencys = BsPubdata::getBsPubdataOne($currency);//获取币别名
        switch ($type) {
            case self::_PUSH:
                $status = CrmCustomerApply::STATUS_CHECKING; //審核中
                $model->toverify = $staff;

                $model->verifydate = date('Y-m-d H:i:s', time());
                break;
            case self::_PASS:
                $status = CrmCustomerApply::STATUS_FINISH;//通過
                //$model->applyno = BsForm::getCode(CrmCustomerApply::tableName(), $model);//申請通過時生成編碼
                $custcode = new GetCustomerCode();
                $custModel = CrmCustomerInfo::findOne($model->cust_id);
                $custModel->cust_code = $custcode->getCustCode($threetoone, $custtaxcode, $currencys['bsp_svalue']);
                $custModel->member_type = '100071';
                if ($post['batchaudit'] == 0) //单个审核通过时修改发票类型
                {
                    $CustomerInfo = CrmCustomerInfo::find()->where(['cust_id' => $model['cust_id']])->one();//根据客户ID获取客户信息
                    if ($CustomerInfo != null) {
                        $CustomerInfo->load($post);
                        $CustomerInfo->HV_INV_TYPE = !empty($post['hv_inv_type']) ? $post['hv_inv_type'] : null;
                        if (!$CustomerInfo->save()) {
                            throw new \Exception("修改发票类型失败");
                        }
                    }
                }
                $custModel->save();
                break;
            case self::_REJECT:
                $status = CrmCustomerApply::STATUS_PREPARE;//駁回
                break;
            default:
                return $model;
        }
        $model->status = $status;

        return $model->save();
    }

    //其他出库单
    private function other_outstock($id, $type, $select = null, $staff)
    {
        $model = IcInvh::findOne(["invh_id" => $id]);
        switch ($type) {
            case self::_PUSH:
                $status = IcInvh::CHECK_ING;
                break;
            case self::_PASS:
                $status = IcInvh::CHECK_COMPLETE;
                break;
            case self::_REJECT:
                $status = IcInvh::REJECT_STATUS;
                break;
            default:
                return $model;
                break;
        }
        $model->invh_status = $status;
        return $model->save();
    }

    /**
     * @param $id
     * @param $type
     * @param null $select
     * @param $staff
     * @return bool
     * 客戶編碼審核
     */
    private function other_stock_in($id, $type, $select = null, $staff)
    {
        $model = InWhpdt::findOne($id);
        switch ($type) {
            case self::_PUSH:
                $status = 2; //審核中
                break;
            case self::_PASS:
                $status = 5;//通過
                break;
            case self::_REJECT:
                $status = 3;//駁回
                break;
            default:
                return $model;
        }
        $model->invh_status = $status;

        return $model->save();
    }

    /**
     * @param $id
     * @param $type
     * @param null $select
     * @param $staff
     * 供应商
     */
    private function supplier($id, $type, $select = null, $staff)
    {
        $model = BsSupplier::findOne($id);
        switch ($type) {
            case self::_PUSH:
                $status = 2; //審核中
                break;
            case self::_PASS:
                $status = 3;//通過
                $model->codeType = 20;
                $model->spp_code = BsForm::getCode('bs_supplier', $model);
                break;
            case self::_REJECT:
                $status = 4;//駁回
                break;
            default:
                return $model;
        }
        $model->spp_status = $status;

        return $model->save(false);
    }


    /**
     * @param $id
     * @param $type
     * @param null $select
     * @param $staff
     * @return bool
     * 请购
     */
    private function BsReq($id, $type, $select = null, $staff)
    {
        $model = BsReq::findOne($id);
        switch ($type) {
            case self::_PUSH:
                $status = 31; //審核中
                break;
            case self::_PASS:
                $status = 38;//通過
//                $model->codeType = 20;
//                $model->req_no = BsForm::getCode('bs_req', $model);
                break;
            case self::_REJECT:
                $status = 34;//駁回
                break;
            default:
                return $model;
        }
        $model->req_status = $status;

        return $model->save();
    }

    /**
     * @param $id
     * @param $type
     * @param null $select
     * @param $staff
     * @return bool
     * 仓库调拨单
     */
    private function InvChangeh($id, $type, $select = null, $staff)
    {
        $model = InvChangeh::findOne($id);
        switch ($type) {
            case self::_PUSH:
                $status = 20; //審核中
                break;
            case self::_PASS:
                $status = 30;//通過
                $model->o_status = 1;
                if ($model->chh_type == 36) {
                    $transaction = InvChangeh::getDb()->beginTransaction();
                    try {
                        //todo  数据插入
                        $children = InvChangel::find()->where(['chh_id' => $model->chh_id])->all();
                        foreach ($children as $k => $v) {
                            $pdtun = BsMaterial::getBsMaun($v['pdt_no']);
                            $ret = BsWh::getBsWhcn($model->wh_id);
                            if ($ret['wh_code'] == "" || $ret['wh_name'] == "") {
                                return "查询仓库信息失败";
                            }
                            $ret2 = BsSt::getBsStcns($v['st_id']);
                            $models = new LInvtRe();
                            $models->l_types = 8;
                            $models->wh_code = $ret['wh_code'];
                            $models->wh_name = $ret['wh_name'];
                            $models->st_code = $ret2['st_code'];
                            $models->l_r_no = $model['chh_code'];
                            $models->batch_no = $v['chl_bach'];
                            $models->part_no = $v['pdt_no'];
                            $models->unit_name = $pdtun['unit'];
                            $models->pdt_name = $pdtun['pdt_name'];
                            $models->invt_nums = "-" . $v['chl_num'];
                            $models->opp_date = date('Y-m-d H:i:s');
                            $models->yn = 0;
                            // $models->opper=$staff['staff_code'].'-'.$staff['staff_name'];
                            if (!$models->save()) {
                                throw new \Exception("新增流水表出库信息失败");
                            }
                            $models = new LInvtRe();
                            $ret = BsWh::getBsWhcn($model->wh_id2);
                            //$ret2=BsSt::getBsStcns($v['st_id2']);
                            $models->l_types = 7;
                            $models->wh_code = $ret['wh_code'];
                            $models->wh_name = $ret['wh_name'];
                            //$models->st_code=$ret2['st_code'];
                            $models->l_r_no = $model['chh_code'];
                            $models->batch_no = $v['chl_bach'];
                            $models->part_no = $v['pdt_no'];
                            $models->unit_name = $pdtun['unit'];
                            $models->pdt_name = $pdtun['pdt_name'];
                            $models->invt_nums = $v['chl_num'];
                            $models->opp_date = date('Y-m-d H:i:s');
                            $models->yn = 0;
                            $models->opper = $staff['staff_code'] . '-' . $staff['staff_name'];
                            if (!$models->save()) {
                                throw new \Exception("新增流水表入库信息失败");
                            }
                        }
                        //获取業務對象編碼
                        $sqls = "SELECT t.business_value FROM erp.bs_business_type t WHERE t.business_type_id=:type_id";
                        $excu = Yii::$app->db->createCommand($sqls, ['type_id' => $model['chh_type']])->queryAll();
                        if ($excu == "") {
                            return "查询业务代码失败";
                        }
                        //插入库存表ic_invh（主表）
//                      //获取审核人
                        $person = $this->LastAu($model['chh_id'], $model['chh_type']);
                        /*出*/
                        $icmodel = new IcInvh();
                        $icmodel->invh_code=$model['chh_code'];
//                        $icmodel->invh_code="de11111123";
                        $icmodel->comp_id = $model['comp_id'];
                        $icmodel->invh_date = date('Y-m-d');
                        $icmodel->invh_status = 1;
                        $icmodel->organization_id = $model['depart_id'];
                        $icmodel->inout_type = $model['chh_type'];
                        $icmodel->bus_code = $excu[0]['business_value'];
                        $icmodel->inout_flag = 'O';
                        $icmodel->whs_type = "";
                        $icmodel->whs_id = $model['wh_id'];
                        $icmodel->review_by = $person[0]['ver_acc_id'];
                        $icmodel->rdate = date('Y-m-d');
                        $icmodel->create_by = $model['create_by'];
                        $icmodel->cdate = $model['create_at'];
                        if (!$icmodel->save()) {
                            throw new \Exception(current($icmodel->getFirstErrors()));
                        }
                        foreach ($children as $k => $v) {
                            $icchild = new IcInvl();
                            $icchild->invh_id = $icmodel['invh_id'];
                            $icchild->invl_status = 1;
                            $icchild->origin_type = $excu[0]['business_value'];
                            $icchild->origin_id = $model['chh_id'];
                            $icchild->origin_code = $model['chh_code'];
                            $icchild->p_bill_id = $model['chh_id'];
                            $icchild->p_bill_code = $model['chh_code'];
                            $icchild->p_origin_type = $excu[0]['business_value'];
                            $icchild->lor_id = $v['st_id'];
//                            $icchild->category_id='111';
                            $icchild->part_no = $v['pdt_no'];
                            $icchild->out_quantity = $v['chl_num'];
                            $icchild->real_oquantity = $v['chl_num'];
                            $icchild->batch_no = $v['chl_bach'];
                            if (!$icchild->save()) {
                                throw new \Exception("新增仓库流水子表出库信息失败");
                            }
                        }
                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        return false;
                    }
                }

                break;
            case self::_REJECT:
                $status = 40;//駁回
                break;
            default:
                return $model;
        }
        $model->chh_status = $status;

        return $model->save();
    }

    /**
     * @param $id
     * @param $type
     * @param null $select
     * @param $staff
     * @return bool
     * 采购
     */
    private function Notify($id, $type, $select = null, $staff)
    {
        $model = BsPrch::findOne($id);
        switch ($type) {
            case self::_PUSH:
                $status = 41; //審核中
                break;
            case self::_PASS:
                $status = 47;//通過
                break;
            case self::_REJECT:
                $status = 44;//駁回
                break;
            default:
                return $model;
        }
        $model->prch_status = $status;
        return $model->save();
    }

    //物流订单
    private function Ordlgst($id, $type, $select = null, $staff)
    {
        $model = OrdLgst::findOne($id);
        switch ($type) {
            case self::_PUSH:
                $status = 0; //審核中
                break;
            case self::_PASS:
                $status = 1;//通過
                break;
            case self::_REJECT:
                $status = -1;//駁回
                break;
            default:
                return $model;
        }
        $model->check_status = $status;
        return $model->save();
    }

    //其他出库单
    private function OWhpdt($id, $type, $select = null, $staff)
    {
        $model = OWhpdt::findOne($id);
        switch ($type) {
            case self::_PUSH:
                $status = 6; //審核中
                break;
            case self::_PASS:
                $status = 7;//通過
                $model->o_whstatus = $status;
                $transaction = Yii::$app->wms->beginTransaction();
                $OwhpdtDt=OWhpdtDt::find()->where(['o_whpkid'=>$id])->all();
                try {
                    if(!empty($OwhpdtDt)) {
                        foreach ($OwhpdtDt as $key => $value) {
                            $st_invt = BsSitInvt::findOne($value['invt_id']);
                            $models = new LInvtRe();
                            $models->l_types = 12;
                            $models->l_r_no = $model['o_whcode'];
                            $models->part_no = $value['part_no'];
                            $models->pdt_name = $st_invt['pdt_name'];
                            $models->wh_code = $st_invt['wh_code'];
                            $models->wh_name = $st_invt['wh_name'];
                            $models->st_code = $st_invt['st_code'];
                            $models->unit_name = $st_invt['unit_name'];
                            $models->lock_nums = -$value['o_whnum'];
                            $models->batch_no = $st_invt['batch_no'];
                            $models->opp_date = date('Y-m-d H:i:s');
                            $models->yn = 0;

                            if (!$models->save()) {
                                throw new \Exception(current($models->getFirstErrors()));
                            }
                        }
                        $transaction->commit();
                        return true;
                    }
                    else{
                        return true;
                    }
                }
                catch
                (\Exception $e) {
                    $transaction->rollBack();
                    return false;
                }
                break;
            case self::_REJECT:
                $status = 8;//駁回
                break;
            default:
                return $model;
        }
        $model->o_whstatus = $status;
        return $model->save();
    }

    private function PdtInventory($id, $type, $select = null, $staff)
    {
        $model = PdtInventory::findOne($id);
        switch ($type) {
            case self::_PUSH:
                $status = 0; //審核中
                break;
            case self::_PASS:
                $status = 3;//通過
                $model->ivt_status = $status;
                $transaction = Yii::$app->wms->beginTransaction();
                $sql="SELECT
                            t.ivt_code,
                            t.part_no,
                            t.lose_num,
                            ma.pdt_name,
                            ma.unit
                      FROM
                           wms.pdt_inventory_dt t
                       LEFT JOIN pdt.bs_material ma ON ma.part_no=t.part_no
                       WHERE t.ivt_code='{$model->ivt_code}'";
                $model2=\Yii::$app->db->createCommand($sql)->queryAll();
                $sql2="select a.wh_name from wms.bs_wh a where a.wh_code='{$model['wh_code']}'";
                $model1=Yii::$app->db->createCommand($sql2)->queryAll();
                try {
                    foreach ($model2 as $key => $value) {
                        $models = new LInvtRe();
                        $models->l_types = 11;
                        $models->l_r_no = $model['ivt_code'];
                        $models->part_no = $value['part_no'];
                        $models->pdt_name = $value['pdt_name'];
                        $models->wh_code = $model['wh_code'];
                        $models->wh_name = $model1[0]['wh_name'];
                        $models->unit_name = $value['unit'];
                        $models->invt_nums = $value['lose_num'];
                        $models->opp_date = date('Y-m-d H:i:s');
                        $models->yn = 0;
                        $model->save();
                        if (!$models->save()) {
                            throw new \Exception(current($models->getFirstErrors()));
                        }
                    }
                        $transaction->commit();
                        return true;
                }
                catch
                    (\Exception $e) {
                        $transaction->rollBack();
                        return false;
                    }
                break;
            case self::_REJECT:
                $status = 4;//駁回
                break;
            default:
                return $model;

        }
        $model->ivt_status = $status;
        return $model->save();
    }

    private function OrdReprice($id, $type, $select = null, $staff)
    {
        $model = OrdInfo::findOne($id);
        switch ($type) {
            case self::_PUSH:
                $status = OrdStatus::findOne(['os_name' => "订单改价中"]);
                $status = $status->os_id; //订单改价中
                break;
            case self::_PASS:
                $status = OrdStatus::findOne(['os_name' => "订单改价完成"]);
                $status = $status->os_id; //订单改价完成
                //todo   替换数据
                $transaction = Yii::$app->oms->beginTransaction();
                try {
                    //主表
                    $logHModel = LOrdInfo::find()->where(['and', ['ord_id' => $id], ['yn' => 1]])->one();
                    $arr = Json::decode(json::encode($logHModel), true);
                    foreach ($arr as $key => $value) {
                        $arr[$key] = Html::decode($value);
                    }
                    $model->setAttributes($arr);
                    $model->os_id = $status;
                    if (!$model->save()) {
                        throw new \Exception(current($model->getFirstErrors()));
                    }
                    //订单子表更新
                    $count = OrdDt::find()->where(['ord_id' => $model->ord_id])->count();
                    if (OrdDt::deleteAll(['ord_id' => $model->ord_id]) < $count) {
                        throw  new \Exception("订单子表更新失败!");
                    };
                    $logLModels = LOrdDt::find()->where(['l_ord_id' => $logHModel->l_ord_id])->asArray()->all();
                    foreach ($logLModels as $k => $v) {
                        foreach ($v as $key => $value) {
                            $v[$key] = Html::decode($value);
                        }
                        $ordLModel = new OrdDt();
                        $arr = Json::decode(json::encode($v), true);
                        $ordLModel->setAttributes($arr);
//                        $value["OrdDt"] = $v;
                        $ordLModel->ord_id = $model->ord_id;
                        $ordLModel->uprice_ntax_o = $v["uprice_tax_o"] / 1.17;  //未税销售单价
                        $ordLModel->tprice_ntax_o = $v["tprice_tax_o"] / 1.17;  //未税总价
                        if (!$ordLModel->save()) {
                            throw new \Exception(current($ordLModel->getFirstErrors()));
                        }
                    }
                    //付款记录
                    $count = OrdPay::find()->where(['ord_id' => $model->ord_id])->count();
                    if (OrdPay::deleteAll(['ord_id' => $model->ord_id]) < $count) {
                        throw  new \Exception("付款记录表更新失败!");
                    };
                    $logPModels = LOrdPay::find()->where(['l_ord_id' => $logHModel->l_ord_id])->all();
                    foreach ($logPModels as $k => $v) {
                        foreach ($v as $key => $value) {
                            $v[$key] = Html::decode($value);
                        }
                        $ordPModel = new OrdPay();
                        $ordPModel->ord_id = $model->ord_id;
                        $ordPModel->yn_pay = 0;
                        $ordPModel->credit_id = $v["credit_id"];
                        $ordPModel->stag_cost = $v["stag_cost"];
                        if (!$ordPModel->save()) {
                            throw new \Exception(current($ordPModel->getFirstErrors()));
                        }
                    }
                    $transaction->commit();
                    return true;
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return false;
                }
                break;
            case self::_REJECT:
                $status = OrdStatus::findOne(['os_name' => "订单改价驳回"]);
                $status = $status->os_id; //订单改价驳回
                break;
            default:
                return $model;
        }
        $model->os_id = $status;
        return $model->save();
    }

    /**
     * @param $id
     * @param $type
     * @param null $select
     * @param $staff
     * @return bool
     * 仓库预警审核
     */
    private function wh_waring($id, $type, $select = null, $staff)
    {
        $model = BsInvWarnH::findOne($id);
        $model1 = LInvWarnH::findOne($id);
        switch ($type) {
            case self::_PUSH:
                $transaction3 = Yii::$app->wms->beginTransaction();
                try {
                    $status = BsInvWarnH::STATUS_CHECKING; //審核中
                    //添加
                    //添加LInvWarnH表
                    if ($model1) {
                        $model1->biw_h_pkid = $model->biw_h_pkid;
                        $model1->inv_id = $model->inv_id;
                        $model1->wh_id = $model->wh_id;
                        $model1->yn = $model->YN;
                        $model1->OPPER = $model->OPPER;
                        $model1->OPP_DATE = $model->OPP_DATE;
                        $model1->OPP_IP = $model->OPP_IP;
                        $model1->so_type = $status;
                        $model->so_type = $status;
                        if (!($model1->save())) {
                            throw new \Exception(json_encode($model1->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                        if (!($model->save())) {
                            throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                        $BsInvWarnall = BsInvWarn::findAll(['biw_h_pkid' => $id]);
                        if (LInvWarn::deleteAll(["biw_h_pkid" => $id])) {
                            foreach ($BsInvWarnall as $BsInvWarn) {
                                $LInvWarn = new LInvWarn();
                                $LInvWarn->biw_h_pkid = $BsInvWarn->biw_h_pkid;
                                $LInvWarn->up_nums = $BsInvWarn->up_nums;
                                $LInvWarn->part_no = $BsInvWarn->part_no;
                                $LInvWarn->down_nums = $BsInvWarn->down_nums;
                                $LInvWarn->save_num = $BsInvWarn->save_num;
                                $LInvWarn->remarks = $BsInvWarn->remarks;
                                if (!($LInvWarn->save())) {
                                    throw new \Exception(json_encode($LInvWarn->getErrors(), JSON_UNESCAPED_UNICODE));
                                }
                            }
                        }
                    } else {
                        $LInvWarnH = new LInvWarnH();
                        $LInvWarnH->biw_h_pkid = $model->biw_h_pkid;
                        $LInvWarnH->inv_id = $model->inv_id;
                        $LInvWarnH->wh_id = $model->wh_id;
                        $LInvWarnH->yn = $model->YN;
                        $LInvWarnH->OPPER = $model->OPPER;
                        $LInvWarnH->OPP_DATE = $model->OPP_DATE;
                        $LInvWarnH->OPP_IP = $model->OPP_IP;
                        $LInvWarnH->so_type = $status;
                        $model->so_type = $status;
                        if (!($LInvWarnH->save())) {
                            throw new \Exception(json_encode($LInvWarnH->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                        if (!($model->save())) {
                            throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                        $BsInvWarnall = BsInvWarn::findAll(['biw_h_pkid' => $id]);
                        foreach ($BsInvWarnall as $BsInvWarn) {
                            $LInvWarn = new LInvWarn();
                            $LInvWarn->biw_h_pkid = $BsInvWarn->biw_h_pkid;
                            $LInvWarn->up_nums = $BsInvWarn->up_nums;
                            $LInvWarn->part_no = $BsInvWarn->part_no;
                            $LInvWarn->down_nums = $BsInvWarn->down_nums;
                            $LInvWarn->save_num = $BsInvWarn->save_num;
                            $LInvWarn->remarks = $BsInvWarn->remarks;
                            if (!($LInvWarn->save())) {
                                throw new \Exception(json_encode($LInvWarn->getErrors(), JSON_UNESCAPED_UNICODE));
                            }
                        }
                    }
                    $transaction3->commit();
                    return true;
                } catch (\Exception $e) {
                    $transaction3->rollBack();
                    return false;
                }

                break;
            case self::_PASS:
                $transaction2 = Yii::$app->wms->beginTransaction();
                try {
                    $status = BsInvWarnH::STATUS_FINISH;//通過
                    $model->so_type = $status;
                    if ($model1) {
                        $model1->so_type = $status;
                        if (!$model1->save()) {
                            throw new \Exception(current($model1->getFirstErrors()));
                        }
                    }
                    if (!$model->save()) {
                        throw new \Exception(current($model->getFirstErrors()));
                    }
                    $transaction2->commit();
                    return true;
                } catch (\Exception $e) {
                    $transaction2->rollBack();
                    return false;
                }

                break;
            case self::_REJECT:
                $status = BsInvWarnH::STATUS_PREPARE;//駁回
                break;
            default:
                return $model;
        }
        $model->so_type = $status;
        if ($model1) {
            $model1->so_type = $status;
            $model1->save();
        }
        return $model->save();
    }

    /**
     * @param $id
     * @param $type
     * @param null $select
     * @param $staff
     * @return bool
     * 报废单申请审核
     */
    private function inv_change($id, $type, $select = null, $staff)
    {
        $model = InvChangeh::findOne($id);
        $staffs = HrStaff::findOne($model->create_at);
        switch ($type) {
            case self::_PUSH:
                $status = InvChangeh::STATUS_WAIT;
                break;
            case self::_PASS:
                $status = InvChangeh::STATUS_PENDING;
                //储位异动,料号异动,
                if ($model->chh_type == 41 || $model->chh_type == 42) {
                    $transaction = InvChangeh::getDb()->beginTransaction();
                    try {
                        //todo  数据插入
                        $children = InvChangel::find()->where(['chh_id' => $model->chh_id])->all();
                        foreach ($children as $k => $v) {
                            $pdtun = BsMaterial::getBsMaun($v['pdt_no']);
                            $ret = BsWh::getBsWhcn($model->wh_id);
                            if ($ret['wh_code'] == "" || $ret['wh_name'] == "") {
                                return "查询仓库信息失败";
                            }
                            $ret2 = BsSt::getBsStcns($v['st_id']);
                            $models = new LInvtRe();
                            $models->l_types = 4;
                            $models->wh_code = $ret['wh_code'];
                            $models->wh_name = $ret['wh_name'];
                            $models->st_code = $ret2['st_code'];
                            $models->l_r_no = $model['chh_code'];
                            $models->batch_no = $v['chl_bach'];
                            $models->part_no = $v['pdt_no'];
                            $models->unit_name = $pdtun['unit'];
                            $models->pdt_name = $pdtun['pdt_name'];
                            $models->invt_nums = "-" . $v['chl_num'];
                            $models->opp_date = date('Y-m-d H:i:s');
                            $models->yn = 0;
                            // $models->opper=$staff['staff_code'].'-'.$staff['staff_name'];
                            if (!$models->save()) {
                                throw new \Exception("新增流水表出库信息失败");
                            }
                            $models = new LInvtRe();
                            $ret2 = BsSt::getBsStcns($v['st_id2']);
                            $models->l_types = 3;
                            $models->wh_code = $ret['wh_code'];
                            $models->wh_name = $ret['wh_name'];
                            $models->st_code = $ret2['st_code'];
                            $models->l_r_no = $model['chh_code'];
                            $models->batch_no = $v['chl_bach'];
                            $models->part_no = $v['pdt_no'];
                            $models->unit_name = $pdtun['unit'];
                            $models->pdt_name = $pdtun['pdt_name'];
                            $models->invt_nums = $v['chl_num'];
                            $models->opp_date = date('Y-m-d H:i:s');
                            $models->yn = 0;
                            $models->opper = $staffs['staff_code'] . '-' . $staffs['staff_name'];
                            if (!$models->save()) {
                                throw new \Exception("新增流水表入库信息失败");
                            }
                        }
                        //获取業務對象編碼
                        $sqls = "SELECT t.business_value FROM erp.bs_business_type t WHERE t.business_type_id=:type_id";
                        $excu = Yii::$app->db->createCommand($sqls, ['type_id' => $model['chh_type']])->queryAll();
                        if ($excu == "") {
                            return "查询业务代码失败";
                        }
                        //插入库存表ic_invh（主表）
//                      //获取审核人
                        $person = $this->LastAu($model['chh_id'], $model['chh_type']);
                        /*出*/
                        $icmodel = new IcInvh();
                        $icmodel->invh_code=$model['chh_code'];
//                        $icmodel->invh_code="de11111123";
                        $icmodel->comp_id = $model['comp_id'];
                        $icmodel->invh_date = date('Y-m-d');
                        $icmodel->invh_status = 1;
                        $icmodel->organization_id = $model['depart_id'];
                        $icmodel->inout_type = $model['chh_type'];
                        $icmodel->bus_code = $excu[0]['business_value'];
                        $icmodel->inout_flag = 'O';
                        $icmodel->whs_type = "";
                        $icmodel->whs_id = $model['wh_id'];
                        $icmodel->review_by = $person[0]['ver_acc_id'];
                        $icmodel->rdate = date('Y-m-d');
                        $icmodel->create_by = $model['create_by'];
                        $icmodel->cdate = $model['create_at'];
                        if (!$icmodel->save()) {
                            throw new \Exception(current($icmodel->getFirstErrors()));
                        }
                        foreach ($children as $k => $v) {
                            $icchild = new IcInvl();
                            $icchild->invh_id = $icmodel['invh_id'];
                            $icchild->invl_status = 1;
                            $icchild->origin_type = $excu[0]['business_value'];
                            $icchild->origin_id = $model['chh_id'];
                            $icchild->origin_code = $model['chh_code'];
                            $icchild->p_bill_id = $model['chh_id'];
                            $icchild->p_bill_code = $model['chh_code'];
                            $icchild->p_origin_type = $excu[0]['business_value'];
                            $icchild->lor_id = $v['st_id'];
                            $icchild->category_id = '111';
                            $icchild->part_no = $v['pdt_no'];
                            $icchild->out_quantity = $v['chl_num'];
                            $icchild->real_oquantity = $v['chl_num'];
                            $icchild->batch_no = $v['chl_bach'];
                            if (!$icchild->save()) {
                                throw new \Exception("新增仓库流水子表出库信息失败");
                            }
                        }
                        /*入*/
                        $icmodel = new IcInvh();
                        $icmodel->invh_code=$model['chh_code'];
                        $icmodel->comp_id = $model['comp_id'];
                        $icmodel->invh_date = date('Y-m-d');
                        $icmodel->invh_status = 1;
                        $icmodel->organization_id = $model['depart_id'];
                        $icmodel->inout_type = $model['chh_type'];
                        $icmodel->bus_code = $excu[0]['business_value'];
                        $icmodel->inout_flag = 'I';
                        $icmodel->whs_type = "";
                        $icmodel->whs_id = $model['wh_id'];
                        $icmodel->review_by = $person[0]['ver_acc_id'];
                        $icmodel->rdate = date('Y-m-d');
                        $icmodel->create_by = $model['create_by'];
                        $icmodel->cdate = $model['create_at'];
                        if (!$icmodel->save()) {
                            throw new \Exception("新增仓库流水表入库信息失败");
                        }
                        foreach ($children as $k => $v) {
                            $icchild = new IcInvl();
                            $icchild->invh_id = $icmodel['invh_id'];
                            $icchild->invl_status = 1;
                            $icchild->origin_type = $excu[0]['business_value'];
                            $icchild->origin_id = $model['chh_id'];
                            $icchild->origin_code = $model['chh_code'];
                            $icchild->p_bill_id = $model['chh_id'];
                            $icchild->p_bill_code = $model['chh_code'];
                            $icchild->p_origin_type = $excu[0]['business_value'];
                            $icchild->lor_id = $v['st_id2'];
                            $icchild->category_id = '';
                            $icchild->part_no = $v['pdt_no'];
                            $icchild->in_quantity = $v['chl_num'];
                            $icchild->real_quantity = $v['chl_num'];
                            $icchild->brand = "";
                            $icchild->batch_no = $v['chl_bach'];
                            if (!$icchild->save()) {
                                throw new \Exception("新增仓库流水子表入库信息失败");
                            }
                        }
                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        return $this->error($e->getMessage());
//                        return false;
                    }

                } else if ($model->chh_type == 43) { //移仓
                    try {
                        //todo  数据插入
                        $children = InvChangel::find()->where(['chh_id' => $model->chh_id])->all();
                        foreach ($children as $k => $v) {
                            $pdtun = BsMaterial::getBsMaun($v['pdt_no']);
                            $ret = BsWh::getBsWhcn($model->wh_id);
                            if ($ret['wh_code'] == "" || $ret['wh_name'] == "") {
                                return "查询仓库信息失败";
                            }
                            $ret2 = BsSt::getBsStcns($v['st_id']);
                            $models = new LInvtRe();
                            $models->l_types = 4;
                            $models->wh_code = $ret['wh_code'];
                            $models->wh_name = $ret['wh_name'];
                            $models->st_code = $ret2['st_code'];
                            $models->l_r_no = $model['chh_code'];
                            $models->batch_no = $v['chl_bach'];
                            $models->part_no = $v['pdt_no'];
                            $models->unit_name = $pdtun['unit'];
                            $models->pdt_name = $pdtun['pdt_name'];
                            $models->invt_nums = "-" . $v['chl_num'];
                            $models->opp_date = date('Y-m-d H:i:s');
                            $models->yn = 0;
                            // $models->opper=$staff['staff_code'].'-'.$staff['staff_name'];
                            if (!$models->save()) {
                                throw new \Exception("新增流水表出库信息失败");
                            }
                            $models = new LInvtRe();
                            $retss = BsWh::getBsWhcn($model->wh_id2);
                            $ret2 = BsSt::getBsStcns($v['st_id2']);
                            $models->l_types = 3;
                            $models->wh_code = $retss['wh_code'];
                            $models->wh_name = $retss['wh_name'];
                            $models->st_code = $ret2['st_code'];
                            $models->l_r_no = $model['chh_code'];
                            $models->batch_no = $v['chl_bach'];
                            $models->part_no = $v['pdt_no'];
                            $models->unit_name = $pdtun['unit'];
                            $models->pdt_name = $pdtun['pdt_name'];
                            $models->invt_nums = $v['chl_num'];
                            $models->opp_date = date('Y-m-d');
                            $models->opper = $staff['staff_code'] . '-' . $staff['staff_name'];
                            if (!$models->save()) {
                                throw new \Exception("新增流水表入库信息失败");
                            }
                        }
                        //获取業務對象編碼
                        $sqls = "SELECT t.business_value FROM erp.bs_business_type t WHERE t.business_type_id=:type_id";
                        $excu = Yii::$app->db->createCommand($sqls, ['type_id' => $model['chh_type']])->queryAll();
                        if ($excu == "") {
                            return "查询业务代码失败";
                        }
                        //插入库存表ic_invh（主表）
//
                        //获取审核人
                        $person = $this->LastAu($model['chh_id'], $model['chh_type']);
                        /*出*/
                        $icmodel = new IcInvh();
                        $icmodel->invh_code=$model['chh_code'];
                        $icmodel->comp_id = $model['comp_id'];
                        $icmodel->invh_date = date('Y-m-d');
                        $icmodel->invh_status = 1;
                        $icmodel->organization_id = $model['depart_id'];
                        $icmodel->inout_type = $model['chh_type'];
                        $icmodel->bus_code = $excu[0]['business_value'];
                        $icmodel->inout_flag = 'O';
                        $icmodel->whs_type = "";
                        $icmodel->whs_id = $model['wh_id'];
                        $icmodel->review_by = $person[0]['ver_acc_id'];
                        $icmodel->rdate = date('Y-m-d');
                        $icmodel->create_by = $model['create_by'];
                        $icmodel->cdate = $model['create_at'];
                        if (!$icmodel->save()) {
                            throw new \Exception(current($icmodel->getFirstErrors()));
                        }
                        foreach ($children as $k => $v) {
                            $icchild = new IcInvl();
                            $icchild->invh_id = $icmodel['invh_id'];
                            $icchild->invl_status = 1;
                            $icchild->origin_type = $excu[0]['business_value'];
                            $icchild->origin_id = $model['chh_id'];
                            $icchild->origin_code = $model['chh_code'];
                            $icchild->p_bill_id = $model['chh_id'];
                            $icchild->p_bill_code = $model['chh_code'];
                            $icchild->p_origin_type = $excu[0]['business_value'];
                            $icchild->lor_id = $v['st_id'];
                            $icchild->category_id = '111';
                            $icchild->part_no = $v['pdt_no'];
                            $icchild->out_quantity = $v['chl_num'];
                            $icchild->real_oquantity = $v['chl_num'];
                            $icchild->batch_no = $v['chl_bach'];
                            if (!$icchild->save()) {
                                throw new \Exception("新增仓库流水子表出库信息失败");
                            }
                        }
                    } catch (\Exception $e) {
//                        $transaction->rollBack();
                        return false;
                    }

                } else if ($model->chh_type == 27 || $model->chh_type == 28 || $model->chh_type == 29 || $model->chh_type == 30) {

                    $children = InvChangel::find()->where(['chh_id' => $model->chh_id])->all();
                    foreach ($children as $k => $v) {
                        $pdtun = BsMaterial::getBsMaun($v['pdt_no']);
                        $ret = BsWh::getBsWhcn($model->wh_id);
                        if ($ret['wh_code'] == "" || $ret['wh_name'] == "") {
                            return "查询仓库信息失败";
                        }
                        $ret2 = BsSt::getBsStcns($v['st_id']);
                        $models = new LInvtRe();
                        $models->l_types = 6;
                        $models->wh_code = $ret['wh_code'];
                        $models->wh_name = $ret['wh_name'];
                        $models->st_code = $ret2['st_code'];
                        $models->l_r_no = $model['chh_code'];
                        $models->batch_no = $v['chl_bach'];
                        $models->part_no = $v['pdt_no'];
                        $models->unit_name = $pdtun['unit'];
                        $models->pdt_name = $pdtun['pdt_name'];
                        $models->invt_nums = "-" . $v['chl_num'];
                        $models->opp_date = date('Y-m-d H:i:s');
                        $models->yn = 0;
                        // $models->opper=$staff['staff_code'].'-'.$staff['staff_name'];
                        if (!$models->save()) {
                            throw new \Exception("新增流水表出库信息失败");
                        }
                        $models = new LInvtRe();
                        $rets = BsWh::getBsWhcn($v->wh_id2);
                        $ret2 = BsSt::getBsStcns($v['st_id2']);
                        $models->l_types = 5;
                        $models->wh_code = $rets['wh_code'];
                        $models->wh_name = $rets['wh_name'];
                        $models->st_code = $ret2['st_code'];
                        $models->l_r_no = $model['chh_code'];
                        $models->batch_no = $v['chl_bach'];
                        $models->part_no = $v['pdt_no'];
                        $models->unit_name = $pdtun['unit'];
                        $models->pdt_name = $pdtun['pdt_name'];
                        $models->invt_nums = $v['chl_num'];
                        $models->opp_date = date('Y-m-d H:i:s');
                        $models->yn = 0;
                        $models->opper = $staff['staff_code'] . '-' . $staff['staff_name'];
                        if (!$models->save()) {
                            throw new \Exception("新增流水表入库信息失败");
                        }
                        //获取業務對象編碼
                        $sqls = "SELECT t.business_value FROM erp.bs_business_type t WHERE t.business_type_id=:type_id";
                        $excu = Yii::$app->db->createCommand($sqls, ['type_id' => $model['chh_type']])->queryAll();
                        if ($excu == "") {
                            return "查询业务代码失败";
                        }
                        //插入库存表ic_invh（主表）
//                      //获取审核人
                        $person = $this->LastAu($model['chh_id'], $model['chh_type']);

                        /*出*/
                        $icmodel = new IcInvh();
                        $icmodel->invh_code=$model['chh_code'];
//                        $icmodel->invh_code="de11111123";
                        $icmodel->comp_id = $model['comp_id'];
                        $icmodel->invh_date = date('Y-m-d');
                        $icmodel->invh_status = 1;
                        $icmodel->organization_id = $model['depart_id'];
                        $icmodel->inout_type = $model['chh_type'];
                        $icmodel->bus_code = $excu[0]['business_value'];
                        $icmodel->inout_flag = 'O';
                        $icmodel->whs_type = "";
                        $icmodel->whs_id = $model['wh_id'];
                        $icmodel->review_by = $person[0]['ver_acc_id'];
                        $icmodel->rdate = date('Y-m-d');
                        $icmodel->create_by = $model['create_by'];
                        $icmodel->cdate = $model['create_at'];
                        if (!$icmodel->save()) {
                            throw new \Exception(current($icmodel->getFirstErrors()));
                        }
                        foreach ($children as $k => $v) {
                            $icchild = new IcInvl();
                            $icchild->invh_id = $icmodel['invh_id'];
                            $icchild->invl_status = 1;
                            $icchild->origin_type = $excu[0]['business_value'];
                            $icchild->origin_id = $model['chh_id'];
                            $icchild->origin_code = $model['chh_code'];
                            $icchild->p_bill_id = $model['chh_id'];
                            $icchild->p_bill_code = $model['chh_code'];
                            $icchild->p_origin_type = $excu[0]['business_value'];
                            $icchild->lor_id = $v['st_id'];
                            $icchild->category_id = '111';
                            $icchild->part_no = $v['pdt_no'];
                            $icchild->out_quantity = $v['chl_num'];
                            $icchild->real_oquantity = $v['chl_num'];
                            $icchild->batch_no = $v['chl_bach'];
                            if (!$icchild->save()) {
                                throw new \Exception("新增仓库流水子表出库信息失败");
                            }
                        }
                        /*入*/
                        $icmodel = new IcInvh();
                        $icmodel->invh_code=$model['chh_code'];
                        $icmodel->comp_id = $model['comp_id'];
                        $icmodel->invh_date = date('Y-m-d');
                        $icmodel->invh_status = 1;
                        $icmodel->organization_id = $model['depart_id'];
                        $icmodel->inout_type = $model['chh_type'];
                        $icmodel->bus_code = $excu[0]['business_value'];
                        $icmodel->inout_flag = 'I';
                        $icmodel->whs_type = "";
                        $icmodel->whs_id = $model['wh_id'];
                        $icmodel->review_by = $person[0]['ver_acc_id'];
                        $icmodel->rdate = date('Y-m-d');
                        $icmodel->create_by = $model['create_by'];
                        $icmodel->cdate = $model['create_at'];
                        if (!$icmodel->save()) {
                            throw new \Exception("新增仓库流水表入库信息失败");
                        }
                        foreach ($children as $k => $v) {
                            $icchild = new IcInvl();
                            $icchild->invh_id = $icmodel['invh_id'];
                            $icchild->invl_status = 1;
                            $icchild->origin_type = $excu[0]['business_value'];
                            $icchild->origin_id = $model['chh_id'];
                            $icchild->origin_code = $model['chh_code'];
                            $icchild->p_bill_id = $model['chh_id'];
                            $icchild->p_bill_code = $model['chh_code'];
                            $icchild->p_origin_type = $excu[0]['business_value'];
                            $icchild->wh_id2 = $v['wh_id2'];
                            $icchild->lor_id = $v['st_id2'];
                            $icchild->category_id = '';
                            $icchild->part_no = $v['pdt_no'];
                            $icchild->in_quantity = $v['chl_num'];
                            $icchild->real_quantity = $v['chl_num'];
                            $icchild->brand = "";
                            $icchild->batch_no = $v['chl_bach'];
                            if (!$icchild->save()) {
                                throw new \Exception("新增仓库流水子表入库信息失败");
                            }
                        }

                    }
                }
                break;
            case self::_REJECT:
                $status = InvChangeh::STATUS_PREPARE;
                break;
            default;
                return $model;
        }
        $model->chh_status = $status;
        return $model->save();
    }

    //获取仓库预警人员设置审核
    private function set_invt_warning($id, $type, $select = null, $staff_code)
    {
        $model = InvWarner::findOne($id);
//        $YNs=InvWarner::UNVALID;
//        $sql="select staff_code from wms.inv_warner where LIW_PKID=:id";
//        $info=Yii::$app->db->createCommand($sql)->bindValue(':id', $id)->queryOne();
//        $models=InvWarner::find()->where(['staff_code' => $info["staff_code"]])->all();

        switch ($type) {
            case self::_PUSH://送审
                $status = InvWarner::STATUS_WAIT;
                $Yn = InvWarner::UNVALID;
                $model->OPP_DATE = date('Y-m-d H:i:s', time());
                break;
            case self::_PASS://通过
                $status = InvWarner::STATUS_PENDING;
                $sql = "SELECT staff_code FROM wms.inv_warner WHERE LIW_PKID=:id";
                $info = Yii::$app->db->createCommand($sql)->bindValue(':id', $id)->queryOne();
                InvWarner::updateAll(['YN' => 0], ['staff_code' => $info["staff_code"]]);
                //$YNs=InvWarner::UNVALID;
                $Yn = InvWarner::VALID;
                break;
            case self::_REJECT://驳回
                $status = InvWarner::STATUS_PREPARE;
                $Yn = InvWarner::UNVALID;
                break;
            default;
                return $model;
        }
        $model->so_type = $status;
        $model->YN = $Yn;
        return $model->save();
    }


    //商品上架审核
    private function set_pdt_upshelf($id, $type, $select = null, $staff_id)
    {
        $logPrtModel = LPartno::findOne($id);
        $prtModel = BsPartno::findOne(["part_no" => $logPrtModel->part_no]);
        $logPrtModel->opp_date = $prtModel->opp_date = date("Y-m-d H:i:s");
        switch ($type) {
            case self::_PUSH:
                $logPrtModel->part_status = BsPartno::STATUS_UPSHELF;
                $logPrtModel->check_status = BsPartno::CHECK_CHECKING;
                $logPrtModel->yn = 0;
                $prtModel->part_status = BsPartno::STATUS_UPSHELF;
                $prtModel->check_status = BsPartno::CHECK_CHECKING;
                $logPrtModel->opper = $prtModel->opper = $staff_id;
                $pdtModel = BsProduct::findOne($prtModel->pdt_pkid);
                $pdtModel->opper = $prtModel->opper;
                $pdtModel->opp_ip = $prtModel->opp_ip;
                $pdtModel->save();
                break;
            case self::_REJECT:
                $logPrtModel->part_status = BsPartno::STATUS_UPSHELF;
                $logPrtModel->check_status = BsPartno::CHECK_REJECT;
                $logPrtModel->yn = 0;
                $prtModel->part_status = BsPartno::STATUS_UPSHELF;
                $prtModel->check_status = BsPartno::CHECK_REJECT;
                break;
            case self::_PASS:
                $logPrtModel->part_status = BsPartno::STATUS_UPSHELF;
                $logPrtModel->check_status = BsPartno::CHECK_PASS;
                $logPrtModel->yn = 1;
                $prtModel->part_status = BsPartno::STATUS_UPSHELF;
                $prtModel->check_status = BsPartno::CHECK_PASS;
                $prtModel->publish_date = date("Y-m-d H:i:s");
                $pdtModel = BsProduct::findOne($prtModel->pdt_pkid);
                $pdtModel->pdt_status = 1;
                $pdtModel->opp_date = date("Y-m-d H:i:s");
                $pdtModel->opper = $prtModel->opper;
                $pdtModel->opp_ip = $prtModel->opp_ip;
                $pdtModel->publish_date = date("Y-m-d H:i:s");
                $pdtModel->save();
                break;
            default:
                return $logPrtModel;
        }
        return $prtModel->save() && $logPrtModel->save();
    }


    //商品重新上架审核
    private function set_pdt_reupshelf($id, $type, $select = null, $staff_id)
    {
        $logPrtModel = LPartno::findOne($id);
        $prtModel = BsPartno::findOne(["part_no" => $logPrtModel->part_no]);
        $logPrtModel->opp_date = $prtModel->opp_date = date("Y-m-d H:i:s");
        switch ($type) {
            case self::_PUSH:
                $logPrtModel->part_status = BsPartno::STATUS_REUPSHELF;
                $logPrtModel->check_status = BsPartno::CHECK_CHECKING;
                $logPrtModel->yn = 0;
                $prtModel->part_status = BsPartno::STATUS_REUPSHELF;
                $prtModel->check_status = BsPartno::CHECK_CHECKING;
                $logPrtModel->opper = $prtModel->opper = $staff_id;
                break;
            case self::_REJECT:
                $logPrtModel->part_status = BsPartno::STATUS_REUPSHELF;
                $logPrtModel->check_status = BsPartno::CHECK_REJECT;
                $logPrtModel->yn = 0;
                $prtModel->part_status = BsPartno::STATUS_REUPSHELF;
                $prtModel->check_status = BsPartno::CHECK_REJECT;
                break;
            case self::_PASS:
                $logPrtModel->part_status = BsPartno::STATUS_UPSHELF;
                $logPrtModel->check_status = BsPartno::CHECK_PASS;
                $logPrtModel->yn = 1;
                $prtModel->part_status = BsPartno::STATUS_UPSHELF;
                $prtModel->check_status = BsPartno::CHECK_PASS;
                $prtModel->publish_date = date("Y-m-d H:i:s");
                $pdtModel = BsProduct::findOne($prtModel->pdt_pkid);
                $pdtModel->pdt_status = 1;
                $pdtModel->publish_date = date("Y-m-d H:i:s");
                $pdtModel->save();
                break;
            default:
                return $logPrtModel;
        }
        return $prtModel->save() && $logPrtModel->save();
    }


    //商品下架审核
    private function set_pdt_downshelf($id, $type, $select = null, $staff_id)
    {
        $logPrtModel = LPartno::findOne($id);
        $prtModel = BsPartno::findOne(["part_no" => $logPrtModel->part_no]);
        $logPrtModel->opp_date = $prtModel->opp_date = date("Y-m-d H:i:s");
        switch ($type) {
            case self::_PUSH:
                $logPrtModel->part_status = BsPartno::STATUS_DOWNSHELF;
                $logPrtModel->check_status = BsPartno::CHECK_CHECKING;
                $logPrtModel->yn = 0;
                $prtModel->part_status = BsPartno::STATUS_DOWNSHELF;
                $prtModel->check_status = BsPartno::CHECK_CHECKING;
                $logPrtModel->opper = $prtModel->opper = $staff_id;
                break;
            case self::_REJECT:
                $logPrtModel->part_status = BsPartno::STATUS_DOWNSHELF;
                $logPrtModel->check_status = BsPartno::CHECK_REJECT;
                $logPrtModel->yn = 0;
                $prtModel->part_status = BsPartno::STATUS_DOWNSHELF;
                $prtModel->check_status = BsPartno::CHECK_REJECT;
                break;
            case self::_PASS:
                $logPrtModel->part_status = BsPartno::STATUS_DOWNSHELF;
                $logPrtModel->check_status = BsPartno::CHECK_PASS;
                $logPrtModel->yn = 1;
                $prtModel->part_status = BsPartno::STATUS_DOWNSHELF;
                $prtModel->check_status = BsPartno::CHECK_PASS;
                $prtModel->off_opper = $prtModel->opper;
                $prtModel->off_ip = $prtModel->opp_ip;
                $prtModel->off_date = date("Y-m-d H:i:s");
                break;
            default:
                return $logPrtModel;
        }
        return $prtModel->save() && $logPrtModel->save();

    }


    //商品修改审核
    private function update_pdt_verify($id, $type, $select = null, $staff_id)
    {
        $logPrtModel = LPartno::findOne($id);
        $prtModel = BsPartno::findOne(["part_no" => $logPrtModel->part_no]);
        $logPrtModel->opp_date = $prtModel->opp_date = date("Y-m-d H:i:s");
        switch ($type) {
            case self::_PUSH:
                $logPrtModel->part_status = BsPartno::STATUS_MODIFY;
                $logPrtModel->check_status = BsPartno::CHECK_CHECKING;
                $logPrtModel->yn = 0;
                $prtModel->part_status = BsPartno::STATUS_MODIFY;
                $prtModel->check_status = BsPartno::CHECK_CHECKING;
                break;
            case self::_REJECT:
                $logPrtModel->part_status = BsPartno::STATUS_MODIFY;
                $logPrtModel->check_status = BsPartno::CHECK_REJECT;
                $logPrtModel->yn = 0;
                $prtModel->part_status = BsPartno::STATUS_MODIFY;
                $prtModel->check_status = BsPartno::CHECK_REJECT;
                break;
            case self::_PASS:
                $logPrtModel->part_status = BsPartno::STATUS_UPSHELF;
                $logPrtModel->check_status = BsPartno::CHECK_PASS;
                $logPrtModel->yn = 1;
                $prtModel->part_status = BsPartno::STATUS_UPSHELF;
                $prtModel->check_status = BsPartno::CHECK_PASS;
                $pdtModel = BsProduct::findOne($prtModel->pdt_pkid);
                $pdtModel->opp_date = date("Y-m-d H:i:s");
                $pdtModel->save();
                break;
            default:
                return $prtModel;
        }
        if ($prtModel->save() && $logPrtModel->save()) {
            if ($logPrtModel->yn == 1) {
                \Yii::$app->pdt->createCommand("call  pdt.p_log_sync_pdt(:l_prt_pkid)", [":l_prt_pkid" => $logPrtModel->primaryKey])->execute();
            }
            return true;
        }
    }

    /**
     * @param $select
     * @param $id 单据ID
     * @const $type  操作类型
     * @const $staff  操作人工号
     * @return bool|null|static
     * 销售管理报价单审核
     */
    private function quoteOrder($id, $type, $select = null, $staff)
    {
        $model = PriceInfo::findOne($id);
        switch ($type) {
            case self::_PUSH:
                $transaction = Yii::$app->oms->beginTransaction();
                try {
                    $model->audit_id = PriceInfo::STATUS_UNDER_REVIEW;
                    if (!$model->save()) {
                        throw new \Exception(current($model->getFirstErrors()));
                    }
                    /*报价单主表=>报价单日志主表*/
                    $arr = Json::decode(Json::encode($model), true);
                    $LPriceInfo = new LPriceInfo();
                    foreach ($arr as $key => $value) {
                        $arr[$key] = Html::decode($value);
                    }
                    $LPriceInfo->setAttributes($arr);
                    $LPriceInfo->yn = '1';
                    if (!$LPriceInfo->save()) {
                        throw new \Exception('报价单日志生成失败！');
                    }
                    $l_price_id = $LPriceInfo->l_price_id;
                    /*报价单子表=>报价单日志子表*/
                    $PriceDt = PriceDt::find()->where(['price_id' => $id])->all();
                    $children = Json::decode(Json::encode($PriceDt), true);
                    foreach ($children as $k => $v) {
                        $childModel = new LPriceDt();
                        foreach ($v as $key => $value) {
                            $v[$key] = Html::decode($value);
                        }
                        $childModel->setAttributes($v);
                        $childModel->l_price_id = $l_price_id; // 报价单单主表ID
                        if (!$childModel->save()) {
                            throw new \Exception(current($childModel->getFirstErrors()));
                        }
                    }
                    //付款记录
                    $reqPay = PricePay::find()->where(['price_id' => $id])->all();
                    $reqPays = Json::decode(Json::encode($reqPay), true);
                    foreach ($reqPays as $k => $v) {
                        $pPayModel = new LPricePay();
                        foreach ($v as $key => $value) {
                            $v[$key] = Html::decode($value);
                        }
                        $v["l_price_id"] = $l_price_id;
                        $pPayModel->setAttributes($v);
                        if (!$pPayModel->save()) {
                            throw new \Exception(Json::encode(current($pPayModel->getFirstErrors())));
                        }
                    }
                    //附件信息
                    $reqFile = PriceFile::find()->where(['price_id' => $id])->all();
                    $reqFiles = Json::decode(Json::encode($reqFile), true);
                    foreach ($reqFiles as $k => $v) {
                        foreach ($v as $key => $value) {
                            $v[$key] = Html::decode($value);
                        }
                        $pFileModel = new LPriceFile();
                        $v["l_price_id"] = $l_price_id;
                        $pFileModel->setAttributes($v);
                        if (!$pFileModel->save()) {
                            throw new \Exception(Json::encode(current($pFileModel->getFirstErrors())));
                        }
                    }
                    $transaction->commit();
                    return $this->success();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return $this->error($e->getMessage());
                }
                break;
            case self::_PASS:
                $transaction = Yii::$app->oms->beginTransaction();
                try {
                    $sp = new SplitOrder();
                    $sp->getSplitOrder($id);
                    $transaction->commit();
                    return $this->success();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return $this->error($e->getMessage());
                }
                break;
            case self::_REJECT:
                $status = PriceInfo::STATUS_REVIEW_REJECT;//駁回
                break;
            default:
                return $model;
        }
        $model->audit_id = $status;
        return $model->save();
    }


    private function whPrice($id, $type, $select = null, $staff)
    {
        $model = IcInvCosth::findOne($id);
        switch ($type) {
            case self::_PUSH:
                $status = IcInvCosth::STATUS_WAIT; //審核中
                break;
            case self::_PASS:
                $status = IcInvCosth::STATUS_PENDING;//通過
//                $model->codeType = 20;
//                $model->req_no = BsForm::getCode('bs_req', $model);
                break;
            case self::_REJECT:
                $status = IcInvCosth::STATUS_PREPARE;//駁回
                break;
            default:
                return $model;
        }
        $model->audit_status = $status;

        return $model->save();
    }


    /**
     * @param $select
     * @param $id 单据ID
     * @const $type  操作类型
     * @const $staff  操作人工号
     * @return bool|null|static
     * 退款审核
     */
    private function ordeRefund($id, $type, $select = null, $staff)
    {
        $model = OrdRefund::findOne($id);
        switch ($type) {
            case self::_PUSH:
                $model->rfnd_status = OrdRefund::STATUS_IN_REVIEW;
                break;
            case self::_PASS:
                $model->rfnd_status = OrdRefund::STATUS_PASS_REVIEW;
                break;
            case self::_REJECT:
                $model->rfnd_status = OrdRefund::STATUS_REJECT_REVIEW;
                break;
            default:
                return $model;
        }
        return $model->save();
    }

    //获取业务类别
    public function actionBusinessType()
    {
        return $query = (new Query())
            ->select(['type.business_type_id', 'type.business_type_desc'])
            ->from(['business' => BsBusiness::tableName(), 'type' => BsBusinessType::tableName()])
            ->where('business.flag=' . BsBusiness::REVIEW_ENABLED . ' and business.business_code=type.business_code')->createCommand()->queryAll();
    }

    //获取当前审核人待审核记录数
    public function actionCount($id)
    {
        $res = AuthAssignment::find()->select('item_name')->where(['user_id' => $id])->all();
        $resArr = [];
        foreach ($res as $k => $v) {
            $resArr[] = $v['item_name'];
        }
        $user = User::findOne($id);
        if ($user->is_supper == 1) {
            $result = VerifyrecordShow::find()->joinWith('verifyChild')->where(['ver_acc_id' => $id])->andWhere(['vco_status' => Verifyrecord::STATUS_DEFAULT, 'vcoc_status' => VerifyrecordChild::STATUS_CHECKIND])->count();
            return $result;
        }
        $result = VerifyrecordShow::find()->joinWith('verifyChild')->where(['ver_acc_id' => $id])->orWhere(['in', 'ver_acc_rule', $resArr])->orWhere(['acc_code_agent' => $id])->orWhere(['in', 'rule_code_agent', $resArr])->andWhere(['vco_status' => Verifyrecord::STATUS_DEFAULT, 'vcoc_status' => VerifyrecordChild::STATUS_CHECKIND])->count();
        return $result;
    }

    //获取当前审核人通知记录数
    public function actionInformCount($id)
    {
        //        $res = AuthAssignment::find()->where(['user_id'=>$id])->one();
        $result = CrmImessage::find()->where(['imesg_receiver' => $id])->andWhere(['and', ['<=', 'imesg_btime', date('Y-m-d H:i:s', time())], ['>=', 'imesg_etime', date('Y-m-d H:i:s', time())]])->andWhere(['=', 'imesg_status', CrmImessage::STATUS_DEFAULT])->count();
        return $result;
    }

    //获取待审核记录
    public function actionModels($id)
    {
        $result = VerifyrecordShow::getVerifyOne($id);
        return $result;
    }

    // 获取审核记录子表状态信息
    public function actionVerifyChildStatus($id, $uid)
    {
        $result = VerifyrecordChild::find()->select(['vcoc_status'])->where(['vco_id' => $id, 'ver_acc_id' => $uid])->orderBy("vcoc_id desc")->one();
        if ($result != null) {
            return ($result->vcoc_status == VerifyrecordChild::STATUS_DEFAULT || $result->vcoc_status == VerifyrecordChild::STATUS_CHECKIND) ? true : false;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @param $type
     * @return array|yii\db\ActiveRecord[]
     * 签核记录
     */
    public function actionFindVerify($id, $type)
    {
        $vocId = Verifyrecord::find()->select('vco_id')->where(['but_code' => $type])->andWhere(['vco_busid' => $id])->one();
        $vcoc = VerifyrecordChildShow::find()->where(['and', ['vco_id' => $vocId], ['!=', 'vcoc_status', VerifyrecordChild::STATUS_OVER]])->all();
        return $vcoc;
    }

    /**
     * @param $typeId 单据业务审核类型id
     * 判断是否开启部门规则 $isOrgRule等于1开启
     */
    public function isOrgRule($typeId)
    {
        $businessModel = new BsBusinessType();
        $businessCode = $businessModel->find()->select('business_code')->where(['business_type_id' => $typeId])->one();
        $sysParamModel = new SysParameter();
        $isOrgRule = $sysParamModel->find()->select('par_value')->where(['par_syscode' => $businessCode])->one();
        $isOrgRule = $isOrgRule['par_value'] ? $isOrgRule['par_value'] : 0;
        return $isOrgRule;
    }

    /**
     * @param $isOrgRule 是否部门规则
     * @param $typeId 单据审核类型ID
     * @param $staffId 员工ID
     * 获取规则
     */
    public function getRule($typeId, $isOrgRule = 0, $staffId = null)
    {
        if ($isOrgRule == 1) { // 如果开启部门规则
            // 获取本人部门
            $orgCode = HrStaff::getOrgCode($staffId);
            $orgModel = new HrOrganization();
            $orgId = $orgModel->find()->select('organization_id')->where(['organization_code' => $orgCode])->one();
            if (!empty($orgId)) {
                // 查找部门规则
                $ruleModel = BsReviewRule::find()->where(['business_type_id' => $typeId])->andWhere(['<>', 'business_status', BsReviewRule::STATUS_DELETE])->andWhere(['org' => $orgId['organization_id']])->one();
            } else {
                return '没有找到该用户所在部门';
            }
        } else {
            // 查找系统默认规则
            $ruleModel = BsReviewRule::find()->where(['business_type_id' => $typeId])->andWhere(['<>', 'business_status', BsReviewRule::STATUS_DELETE])->andWhere(['is', 'org', null])->one();
        }
        return $ruleModel;
    }

    /**
     * @param $id 被审核单据ID
     * @param $type 审核类型ID
     * @return array|yii\db\ActiveRecord[] 没找到返回null
     * 查找第一位审核人的审核状态
     */
    public function findFirstVerify($id, $type)
    {
        $vocId = Verifyrecord::find()->select('vco_id')->where(['but_code' => $type])->andWhere(['vco_busid' => $id])->one();
        if (empty($vocId)) {
            return $vocId;
        }
        $vcoc = VerifyrecordChildShow::find()->where(['vco_id' => $vocId, 'ver_scode' => 1])->one();
        return $vcoc;
    }

    public function changeStatus($id, $type)
    {
        $businessCode = BsBusinessType::find()->select('business_code')->where(['business_type_id' => $type])->one();
        switch ($businessCode['business_code']) {
            case 'saqut':
                $model = PriceInfo::find()->where(['price_id' => $id])->one();
                $model->audit_id = AuditState::STATUS_DEFAULT;
                return $model->save();
                break;
            default :
                return false;
        }
    }

    /**
     * 取消审核  (需求改变 没有取消审核功能了)
     * @param $id 被审核单据ID
     * @param $type 审核类型ID
     * @return array|yii\db\ActiveRecord[]
     */
    public function actionCancelCheck($id, $type)
    {
        // 获取第一审核人审核状态
        $first = $this->findFirstVerify($id, $type);
        if (!empty($first)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($first['vcoc_status'] == 30) {
                    throw new \Exception('已在审核中！不允许取消');
                }
                $affect1 = Verifyrecord::deleteAll(['but_code' => $type, 'vco_busid' => $id]);
                $affect2 = VerifyrecordChild::deleteAll(['vco_id' => $first->vco_id]);
                if ($affect1 + $affect2 < 2) {
                    throw new \Exception('取消送审失败');
                }
                if (!($this->changeStatus($id, $type))) {
                    throw new \Exception('改变状态错误！取消送审失败');
                };
                $transaction->commit();
                return $this->success();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        } else {
            return $this->error('还未送审，无需取消');
        }
    }

    public function sendMail($email, $url)
    {
        $client = new \SoapClient('http://imes.foxconn.com/mailintoface.asmx?WSDL');
        $params = [
            'from' => 'service@foxconnmall.com',
            'toLst' => array($email),
            'subject' => '审核通知邮件',
            'body' => '<div>
                        <p>您好！</p>
                            <div style="text-indent: 1cm;">
                            <p>您有一笔来自ERP系统的单据需要审核，请您及时处理</p>
                            <p>点击此链接直接进入审核页面： <a href="' . $url . '">审核页面</a></p> 
                            如果以上鏈接失效，请<a href="http://localhost/php_project/fjj_erp/web/login/login">登录</a>富金机企业资源管理系统
                            </div>
                        </div>',
            'MessageType' => '邮件',
            'isHtml' => true,
            'strEncoding' => 'utf-8',
        ];
        $data = $client->send($params);
        $result = $data->SendResult;
        return $result;
    }

    /**
     * @param $uid 用户ID 当前审核人ID
     * @param $id 审核记录主表ID
     * @return array
     * 获取token  生成外链url
     */
    public function getToken($uid, $id)
    {
        $url = Yii::$app->params['erpUrl'] . 'system/verify-record/verify?id=' . $id;
        $userModel = User::find()->select(['access_token', 'staff_id'])->where(['user_id' => $uid])->one();
        if (!empty($userModel->staff_id)) {
            $staffModel = HrStaff::find()->select(['staff_email'])->where(['staff_id' => $userModel->staff_id])->one();
            $email = $staffModel->staff_email;
        } else {
            $email = '';
        }
        $token = $userModel->access_token;
        if (empty($token)) {
            $token = Yii::$app->security->generateRandomString();
            $userModel->access_token = $token;
            $userModel->save();
        }
        $verifyRecord = Verifyrecord::findOne($id);
        if ($verifyRecord->vco_status == Verifyrecord::STATUS_DEFAULT) {
            $urlToken = md5((string)$id . $token);
        } else {
            $urlToken = '';
        }
        $url .= "&token=$token&url-token=$urlToken";
        return [
            'email' => $email,
            'url' => $url
        ];
    }

    //获取盘点审核id
    public function actionChecklistType()
    {
        $downList['type'] = Yii::$app->db->createCommand("SELECT a.business_type_id FROM erp.bs_business_type a WHERE a.business_code='checklist' ")->queryAll();
//        return $downList['type'][0]['business_type_id'];
        return $downList;
    }
    /**
     * @param $uid 用户ID
     * @return boolean
     * 原有token失效、无效  更改$token
     */
//    public function changeToken($uid)
//    {
//        $userModel = User::findOne(intval($uid));
//        $token = Yii::$app->security->generateRandomString();
//        $userModel->access_token = $token;
//        return @$userModel->save();
//    }


    /**
     * 获取最后审核人
     */
    public function LastAu($vcoid, $butcode)
    {
        $sqll = "SELECT t.vco_id,t.ver_scode,t.ver_acc_id,u1.username_describe,t.vcoc_datetime,t.vcoc_remark
                FROM erp.system_verifyrecord s 
                LEFT JOIN erp.system_verifyrecord_child t ON s.vco_id=t.vco_id
                LEFT JOIN erp.`user` u1 ON t.ver_acc_id=u1.user_id
                WHERE s.vco_busid=:vco_busid AND s.but_code=:but_code
                ORDER BY t.ver_scode DESC";
        $re = Yii::$app->db->createCommand($sqll, ['vco_busid' => $vcoid, 'but_code' => $butcode])->queryAll();
        return $re;
    }


//    public function actionCeshi()
//    {
//        $aa=$this->LastAu(145,41);
//        return $aa[0]['ver_acc_id'];
//    }
   //单笔收款送审
    public function actionReciveVerify()
    {
        $post = Yii::$app->request->post();
        $transid = $post['transid'];    //流水号
        $type = $post['type'];  //审核流类型ID
        $staff = $post['staff'];//送审人
        $remark = $post['remark'];//备注说明
        $ordPayId = explode(';', $post['ord_pay_id']);//待付款id
        $reviewer = explode(',', $post['reviewer']);//审核人
        $Id = null;
        if (isset($post['rbo_id'])) {
            $Id = $post['rbo_id'];
        }
        $result = $this->OneReviceVerify($Id, self::_PUSH, $staff, $transid, $ordPayId, $remark);
        if ($result['status'] == 0) {
            throw new \Exception($result['msg']);
        }
        $busId = $result['msg'];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 查找记录阻止重复送审
            $recordH = Verifyrecord::find()->where(['vco_busid' => $busId, 'but_code' => $type])->andFilterWhere(['!=', 'vco_status', Verifyrecord::STATUS_REJECT])->one();
            if (!empty($recordH)) {
                return $this->error('不允许重复送审!');
            }
            //获取业务审批流程
            $isOrgRule = $this->isOrgRule($type);
            $ruleModel = $this->getRule($type, $isOrgRule, $staff);
            //审核流主表ID
            $ruleId = $ruleModel->review_rule_id;
            //业务代码
            $ruleCode = $ruleModel->business_code;
            //获取审核人单位代码
            $orgCode = HrStaff::getOrgCode($staff);

            $model = Verifyrecord::find()->where(['vco_busid' => $busId, 'but_code' => $type])->andFilterWhere(['vco_status' => Verifyrecord::STATUS_REJECT])->one();
            $oldChildCount = 0; //原有的审核记录数
            //驳回后再送审进入
            if (!empty($model)) {
                $model->vco_status = Verifyrecord::STATUS_DEFAULT;
                if (!$model->save()) {
                    throw new \Exception(current($model->getFirstErrors()));
                }
                $oldChild = VerifyrecordChild::find()->where(['vco_id' => $model->vco_id])->asArray()->all();
                $oldChildCount = count($oldChild);
            } else {
                //审核,顺序
                $model = new Verifyrecord();
                $model->ver_id = $ruleId;                             //审核流ID
                $model->bus_code = $ruleCode;
                $model->but_code = $type;
                $model->vco_busid = $busId;                           //单据ID
                $model->vco_send_acc = $staff;                        //送审人
                $model->vco_send_dept = $orgCode->organization_code;  //送审部门
                if (!$model->save()) {
                    throw new \Exception(current($model->getFirstErrors()));
                }
            }
            $ruleChild = '';
            //自选审核人进入
            if (!empty($reviewer)) {
                foreach ($reviewer as $key => $value) {
                    $ruleChild[$key] = BsReviewRuleChild::find()->where(['rule_child_id' => $value])->one();
                    $ruleChild[$key]['rule_child_index'] = $key + 1;
                }
            } else {
                $ruleChild = BsReviewRuleChild::find()->where(['review_rule_id' => $ruleId])->all();
            }
            foreach ($ruleChild as $key => $val) {
                $childModel = new VerifyrecordChild();
                $childId = $val['rule_child_id'];
                //条件不通过退出
                $isCondition = $this->getCondition($childId, $busId);
                if ($isCondition === false) {
                    continue;
                }
                $childModel->vco_id = $model->vco_id;
                $childModel->ver_acc_id = $val['review_user_id'];     //当前审核人
                $childModel->ver_acc_rule = $val['review_role_id'];   //当前审核角色
                $childModel->ver_scode = $val['rule_child_index'] + $oldChildCount;
                if ($val['rule_child_index'] == 1) {
                    $childModel->vcoc_status = VerifyrecordChild::STATUS_CHECKIND;
                }
                $childModel->acc_code_agent = $val['agent_one_id'];       //代理人
                $childModel->rule_code_agent = $val['review_role_id'];    //代理角色
                //            $model->acc_code_agents=$val['agent_two_id'];         //代理人
                if (!$childModel->save()) {
                    throw new \Exception(current($childModel->getFirstErrors()));
                }
            }
            $firstChild = reset($ruleChild);
            if (!empty($firstChild['review_user_id'])) {
                $token = $this->getToken($firstChild['review_user_id'], $model->vco_id);
                if (!empty($token['email'])) {
                    $res = $this->sendMail($token['email'], $token['url']);
                    if ($res->status == 1) {
                        // 发送邮件成功改变token 使原有token失效
//                        $this->changeToken($firstChild['review_user_id']);
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            BsBankCheck::deleteAll(['rbo_id' => $busId]);
            RBankOrder::deleteAll(['rbo_id' => $busId]);
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    private function OneReviceVerify($id, $type, $staff = null, $transid = null, $ordPayId = null, $remark = null)
    {
            switch ($type) {
                case self::_PUSH:
                    try {
                        $transaction = Yii::$app->db->beginTransaction();
                        if (isset($id)) {
                            RBankOrder::deleteAll(['rbo_id' => $id]);
                            $bsbankcheck = BsBankCheck::find()->where(['rbo_id' => $id])->one();
                        } else {
                            $bsbankcheck = new BsBankCheck();
                        }
                        $bsbankcheck->state = 10;
                        $bsbankcheck->opper = $staff;
                        $bsbankcheck->opp_date = date('Y-m-d H:i:s');
                        $bsbankcheck->opp_ip = Yii::$app->request->getUserIP();
                        if (!$bsbankcheck->save()) {
                            throw new \Exception(Json::encode($bsbankcheck->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                        for ($i = 0; $i < count($ordPayId); $i++) {
                            $rbankModel = new RBankOrder();
                            $rbankModel->TRANSID = $transid;
                            $rbankModel->ord_pay_id = $ordPayId[$i];
                            $rbankModel->remark = $remark;
                            $rbankModel->rbo_id = $bsbankcheck->attributes['rbo_id'];
                            if (!$rbankModel->save()) {
                                throw new \Exception(Json::encode($rbankModel->getErrors(), JSON_UNESCAPED_UNICODE));
                            }
                        }
                        $transaction->commit();
                        return $this->success($bsbankcheck->attributes['rbo_id']);
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        return $this->error($e->getMessage());
                    }
                    break;
                case self::_PASS:
                    try
                    {
                        $trans=Yii::$app->db->beginTransaction();
                        $bsbankcheck = BsBankCheck::findOne(['rbo_id' => $id]);
                        $bsbankcheck->state = 20;
                        if(!$bsbankcheck->save())
                        {
                            throw new yii\base\Exception(json_encode($bsbankcheck->getErrors(),JSON_UNESCAPED_UNICODE));
                        }
                        $ord_pay_id = RBankOrder::find()->select('ord_pay_id')->where(['rbo_id' => $id])->asArray()->all();
                        foreach ($ord_pay_id as $value) {
                            $payinfo = Yii::$app->db->createCommand("select a.*,b.pac_sname from oms.ord_pay a left join erp.bs_payment b on a.pac_id=b.pac_id where a.ord_pay_id=:ord_pay_id", ['ord_pay_id' => $value['ord_pay_id']])->queryOne();
                            if ($payinfo['yn_pay'] == 0 && $payinfo['pac_sname'] == "预付款") {
                                $payModel = OrdPay::findOne(['ord_pay_id' => $value['ord_pay_id']]);
                                $payModel->yn_pay = 1;
                                if(!$payModel->save())
                                {
                                    throw new yii\base\Exception(json_encode($payModel->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            } else if ($payinfo['yn_pay'] == 1 && $payinfo['pac_sname'] == "信用额度支付") {
                                $repayModel = RepayCredit::findOne(['ord_pay_id' => $value['ord_pay_id']]);
                                $repayModel->is_repay = 1;
                                $repayModel->confirm_date = date('Y-m-d H:i:s', time());
                                if(!$repayModel->save())
                                {
                                    throw new yii\base\Exception(json_encode($repayModel->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                                $custId=Yii::$app->db->createCommand("select c.cust_id from erp.crm_bs_customer_info c where c.cust_code in (select b.cust_code from oms.ord_info b where b.ord_id IN (select a.ord_id from oms.ord_pay a where a.ord_pay_id=:ord_pay_id))",['ord_pay_id'=>$value['ord_pay_id']])->queryOne();
                                $Credit=BsCredit::findOne(['cust_id'=>$custId['cust_id']]);
                                $ordpay=OrdPay::findOne(['ord_pay_id'=>$value['ord_pay_id']]);
                                $Credit->aval_amount=((float)$Credit->aval_amount*1000+(float)$ordpay['stag_cost']*1000)/1000;
                                $Credit->sum_amount=((float)$Credit->sum_amount*1000-(float)$ordpay['stag_cost']*1000)/1000;
                                if(!$Credit->save())
                                {
                                    throw new yii\base\Exception(json_encode($Credit->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            }
                        }
                        $trans->commit();
                        return true;
                    }
                    catch(yii\base\Exception $e)
                    {
                        $trans->rollBack();
                        return false;
                    }
                    break;
                case self::_REJECT:
                    $bsbankcheck = BsBankCheck::findOne(['rbo_id' => $id]);
                    $bsbankcheck->state = 30;
                    if(!$bsbankcheck->save())
                    {
                        throw new yii\base\Exception(json_encode($bsbankcheck->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                    return true;
                    break;
                default:
                    return '';
            }
    }
    //批量收款送审(多笔订单共用一笔流水)
    public function actionBatchReviewOne()
    {
        $post = Yii::$app->request->post();
        $type = $post['type'];  //审核流类型ID
        $staff = $post['staff'];//送审人
        $reviewer = explode(',', $post['reviewer']);//审核人
        $importList=$post['importList'];
        $Id = null;
        if (!empty($post['rbo_id'])) {
            $Id = $post['rbo_id'];
        }
        $result = $this->BatchRecevieOne($Id, self::_PUSH, $staff,$importList);
        if ($result['status'] == 0) {
            throw new \Exception($result['msg']);
        }
        $busId = $result['msg'];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 查找记录阻止重复送审
            $recordH = Verifyrecord::find()->where(['vco_busid' => $busId, 'but_code' => $type])->andFilterWhere(['!=', 'vco_status', Verifyrecord::STATUS_REJECT])->one();
            if (!empty($recordH)) {
                return $this->error('不允许重复送审!');
            }
            //获取业务审批流程
            $isOrgRule = $this->isOrgRule($type);
            $ruleModel = $this->getRule($type, $isOrgRule, $staff);
            //审核流主表ID
            $ruleId = $ruleModel->review_rule_id;
            //业务代码
            $ruleCode = $ruleModel->business_code;
            //获取审核人单位代码
            $orgCode = HrStaff::getOrgCode($staff);

            $model = Verifyrecord::find()->where(['vco_busid' => $busId, 'but_code' => $type])->andFilterWhere(['vco_status' => Verifyrecord::STATUS_REJECT])->one();
            $oldChildCount = 0; //原有的审核记录数
            //驳回后再送审进入
            if (!empty($model)) {
                $model->vco_status = Verifyrecord::STATUS_DEFAULT;
                if (!$model->save()) {
                    throw new \Exception(current($model->getFirstErrors()));
                }
                $oldChild = VerifyrecordChild::find()->where(['vco_id' => $model->vco_id])->asArray()->all();
                $oldChildCount = count($oldChild);
            } else {
                //审核,顺序
                $model = new Verifyrecord();
                $model->ver_id = $ruleId;                             //审核流ID
                $model->bus_code = $ruleCode;
                $model->but_code = $type;
                $model->vco_busid = $busId;                           //单据ID
                $model->vco_send_acc = $staff;                        //送审人
                $model->vco_send_dept = $orgCode->organization_code;  //送审部门
                if (!$model->save()) {
                    throw new \Exception(current($model->getFirstErrors()));
                }
            }
            $ruleChild = '';
            //自选审核人进入
            if (!empty($reviewer)) {
                foreach ($reviewer as $key => $value) {
                    $ruleChild[$key] = BsReviewRuleChild::find()->where(['rule_child_id' => $value])->one();
                    $ruleChild[$key]['rule_child_index'] = $key + 1;
                }
            } else {
                $ruleChild = BsReviewRuleChild::find()->where(['review_rule_id' => $ruleId])->all();
            }
            foreach ($ruleChild as $key => $val) {
                $childModel = new VerifyrecordChild();
                $childId = $val['rule_child_id'];
                //条件不通过退出
                $isCondition = $this->getCondition($childId, $busId);
                if ($isCondition === false) {
                    continue;
                }
                $childModel->vco_id = $model->vco_id;
                $childModel->ver_acc_id = $val['review_user_id'];     //当前审核人
                $childModel->ver_acc_rule = $val['review_role_id'];   //当前审核角色
                $childModel->ver_scode = $val['rule_child_index'] + $oldChildCount;
                if ($val['rule_child_index'] == 1) {
                    $childModel->vcoc_status = VerifyrecordChild::STATUS_CHECKIND;
                }
                $childModel->acc_code_agent = $val['agent_one_id'];       //代理人
                $childModel->rule_code_agent = $val['review_role_id'];    //代理角色
                //            $model->acc_code_agents=$val['agent_two_id'];         //代理人
                if (!$childModel->save()) {
                    throw new \Exception(current($childModel->getFirstErrors()));
                }
            }
            $firstChild = reset($ruleChild);
            if (!empty($firstChild['review_user_id'])) {
                $token = $this->getToken($firstChild['review_user_id'], $model->vco_id);
                if (!empty($token['email'])) {
                    $res = $this->sendMail($token['email'], $token['url']);
                    if ($res->status == 1) {
                        // 发送邮件成功改变token 使原有token失效
//                        $this->changeToken($firstChild['review_user_id']);
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            BsBankCheck::deleteAll(['rbo_id' => $busId]);
            RBankOrder::deleteAll(['rbo_id' => $busId]);
            $model=Yii::$app->db->createCommand("select a.ord_pay_id from oms.r_bank_order a left join oms.ord_pay b on a.ord_pay_id=b.ord_pay_id left join erp.bs_payment c on b.pac_id=c.pac_id where a.rbo_id=:rbo_id and c.pac_sname='信用额度支付'",['rbo_id'=>$busId])->queryAll();
            foreach($model as $value)
            {
                $repaycidt=RepayCredit::findOne(['ord_pay_id'=>$value['ord_pay_id']]);
                $repaycidt->is_repay=0;
                $repaycidt->apper='';
                $repaycidt->app_date='';
                $repaycidt->save();
            }
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }
    //批量审核实例化(多笔订单共用一笔流水)
    private function BatchRecevieOne($id, $type, $staff=null,$importList=null)
    {
        switch ($type) {
            case self::_PUSH:
                try {
                    $transaction = Yii::$app->db->beginTransaction();
                    if (isset($id)) {
                        RBankOrder::deleteAll(['rbo_id' => $id]);
                        $bsbankcheck = BsBankCheck::find()->where(['rbo_id' => $id])->one();
                    } else {
                        $bsbankcheck = new BsBankCheck();
                    }
                    $bsbankcheck->state = 10;
                    $bsbankcheck->opper = $staff;
                    $bsbankcheck->opp_date = date('Y-m-d H:i:s',time());
                    $bsbankcheck->opp_ip = Yii::$app->request->getUserIP();
                    if (!$bsbankcheck->save()) {
                        throw new \Exception(Json::encode($bsbankcheck->getErrors(), JSON_UNESCAPED_UNICODE));
                     }
                    foreach ($importList as $value)
                    {
                        $order_no = explode(";", $value['order_no']);
                        if (count($order_no)==1)
                        {
                            $model=\Yii::$app->db->createCommand("select b.*,c.pac_sname from oms.ord_pay b left join erp.bs_payment c on b.pac_id=c.pac_id left join oms.repay_credit d on b.ord_pay_id=d.ord_pay_id where b.ord_id in (select a.ord_id from oms.ord_info a where a.ord_no=:ord_no) AND
((c.pac_sname='预付款' and b.yn_pay=0) or (c.pac_sname='信用额度支付' and b.yn_pay=1 and d.is_repay=0))",['ord_no'=>$order_no[0]])->queryAll();
                            $useModel=\Yii::$app->db->createCommand("select SUM(b.stag_cost) totalmoney from oms.r_bank_order a left join oms.ord_pay b on a.ord_pay_id=b.ord_pay_id left join oms.bs_bank_check c on a.rbo_id=c.rbo_id where TRANSID=:TRANSID and 
        c.state=20", ['TRANSID' => $value['transid']])->queryAll();//流水使用金额
                            $transinfo=BsBankInfo::findOne(['TRANSID' => $value['transid']]);
                            $surplus=((float)$transinfo['TXNAMT']*1000-(float)$useModel[0]['totalmoney']*1000)/1000;//流水剩余金额
                            $ordpayId=[];
                            $sum=0.000;
                            if(!empty($model))
                            {
                                if($model[0]['pac_sname']=="预付款")
                                {
                                    $optorder=\Yii::$app->db->createCommand("select b.* from oms.ord_pay b left join erp.bs_payment c on b.pac_id=c.pac_id where b.ord_id in (select a.ord_id from oms.ord_info a where a.ord_no=:ord_no) AND
(c.pac_sname='预付款' and b.yn_pay=0) order by b.stag_times ASC ",['ord_no'=>$order_no[0]])->queryAll();
                                }
                                else if($model[0]['pac_sname']=="信用额度支付")
                                {
                                    $optorder=\Yii::$app->db->createCommand("select b.* from oms.ord_pay b left join erp.bs_payment c on b.pac_id=c.pac_id left join oms.repay_credit d on b.ord_pay_id=d.ord_pay_id where b.ord_id in (select a.ord_id from oms.ord_info a where a.ord_no=:ord_no) AND
    (c.pac_sname='信用额度支付' and b.yn_pay=1 and d.is_repay=0) order by b.stag_cost ASC ",['ord_no'=>$order_no[0]])->queryAll();
                                }
                                for ($k=0;$k<count($optorder);$k++)
                                {
                                    $sum=($sum*1000+(float)$optorder[$k]['stag_cost']*1000)/1000;
                                    if($sum>$surplus)
                                    {
                                        break;
                                    }
                                    else{
                                        array_push($ordpayId,$optorder[$k]['ord_pay_id']);
                                    }
                                }
                                for ($j=0;$j<count($ordpayId);$j++)
                                {
                                    if ($model[0]['pac_sname']=="信用额度支付")
                                    {
                                        $creditTable=RepayCredit::findOne(['ord_pay_id'=>$ordpayId[$j]]);
                                        $creditTable->is_repay=2;
                                        $creditTable->apper=$staff;
                                        $creditTable->app_date=date('Y-m-d H:i:s',time());
                                        if (!$creditTable->save()) {
                                            throw new \Exception(Json::encode($creditTable->getErrors(), JSON_UNESCAPED_UNICODE));
                                        }
                                    }
                                    $rbankModel=new RBankOrder();
                                    $rbankModel->TRANSID=$value['transid'];
                                    $rbankModel->rbo_id=$bsbankcheck->attributes['rbo_id'];
                                    $rbankModel->ord_pay_id=$ordpayId[$j];
                                    $rbankModel->remark=$value['remark'];
                                    if (!$rbankModel->save()) {
                                        throw new \Exception(Json::encode($rbankModel->getErrors(), JSON_UNESCAPED_UNICODE));
                                    }
                                }
                            }
                        }
                        else{
                            for($t=0;$t<count($order_no);$t++)
                            {
                                $model=\Yii::$app->db->createCommand("select b.ord_pay_id,c.pac_sname from oms.ord_pay b left join erp.bs_payment c on b.pac_id=c.pac_id left join oms.repay_credit d on b.ord_pay_id=d.ord_pay_id where b.ord_id in (select a.ord_id from oms.ord_info a where a.ord_no=:ord_no) AND
((c.pac_sname='预付款' and b.yn_pay=0) or (c.pac_sname='信用额度支付' and b.yn_pay=1 and d.is_repay=0))",['ord_no'=>$order_no[$t]])->queryAll();
                                foreach($model as $val)
                                {
                                    if ($model[0]['pac_sname']=="信用额度支付")
                                    {
                                        $creditTable=RepayCredit::findOne(['ord_pay_id'=>$val['ord_pay_id']]);
                                        $creditTable->is_repay=2;
                                        $creditTable->apper=$staff;
                                        $creditTable->app_date=date('Y-m-d H:i:s',time());
                                        if (!$creditTable->save()) {
                                            throw new \Exception(Json::encode($creditTable->getErrors(), JSON_UNESCAPED_UNICODE));
                                        }
                                    }
                                    $rbankModel=new RBankOrder();
                                    $rbankModel->TRANSID=$value['transid'];
                                    $rbankModel->rbo_id=$bsbankcheck->attributes['rbo_id'];
                                    $rbankModel->ord_pay_id=$val['ord_pay_id'];
                                    $rbankModel->remark=$value['remark'];
                                    if (!$rbankModel->save()) {
                                        throw new \Exception(Json::encode($rbankModel->getErrors(), JSON_UNESCAPED_UNICODE));
                                    }
                                }
                            }
                        }
                    }
                    $transaction->commit();
                    return $this->success($bsbankcheck->attributes['rbo_id']);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return $this->error($e->getMessage());
                }
                break;
            case self::_PASS:
                try
                {
                    $trans=Yii::$app->db->beginTransaction();
                    $bsbankcheck = BsBankCheck::findOne(['rbo_id' => $id]);
                    $bsbankcheck->state = 20;
                    if(!$bsbankcheck->save())
                    {
                        throw new yii\base\Exception(json_encode($bsbankcheck->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                    $ord_pay_id = RBankOrder::find()->select('ord_pay_id')->where(['rbo_id' => $id])->asArray()->all();
                    foreach ($ord_pay_id as $value) {
                        $cust_id = Yii::$app->db->createCommand("select c.cust_id from erp.crm_bs_customer_info c where 
c.cust_code in (select b.cust_code from oms.ord_pay a left join oms.ord_info b on a.ord_id=b.ord_id where a.ord_pay_id=:ord_pay_id)",['ord_pay_id'=>$value['ord_pay_id']])->queryAll();
                        if (!empty($cust_id))
                        {
                            $creditModel=BsCredit::findOne(['cust_id'=>$cust_id[0]['cust_id']]);
                            $ordpay=OrdPay::findOne(['ord_pay_id'=>$value['ord_pay_id']]);
                            $creditModel->aval_amount=((float)$creditModel->aval_amount*1000+(float)$ordpay['stag_cost']*1000)/1000;
                            $creditModel->sum_amount=((float)$creditModel->sum_amount*1000-(float)$ordpay['stag_cost']*1000)/1000;
                            if(!$creditModel->save())
                            {
                                throw new yii\base\Exception(json_encode($creditModel->getErrors(),JSON_UNESCAPED_UNICODE));
                            }
                        }
                        $payinfo = Yii::$app->db->createCommand("select a.*,b.pac_sname from oms.ord_pay a left join erp.bs_payment b on a.pac_id=b.pac_id where a.ord_pay_id=:ord_pay_id", ['ord_pay_id' => $value['ord_pay_id']])->queryOne();
                        if ($payinfo['yn_pay'] == 0 && $payinfo['pac_sname'] == "预付款") {
                            $payModel = OrdPay::findOne(['ord_pay_id' => $value['ord_pay_id']]);
                            $payModel->yn_pay = 1;
                            if(!$payModel->save())
                            {
                                throw new yii\base\Exception(json_encode($payModel->getErrors(),JSON_UNESCAPED_UNICODE));
                            }
                        } else if ($payinfo['yn_pay'] == 1 && $payinfo['pac_sname'] == "信用额度支付") {
                            $repayModel = RepayCredit::findOne(['ord_pay_id' => $value['ord_pay_id']]);
                            $repayModel->is_repay = 1;
                            $repayModel->confirm_date = date('Y-m-d H:i:s', time());
                            if(!$repayModel->save())
                            {
                                throw new yii\base\Exception(json_encode($repayModel->getErrors(),JSON_UNESCAPED_UNICODE));
                            }
                        }
                    }
                    $trans->commit();
                    return true;
                }
                catch(yii\base\Exception $e)
                {
                    $trans->rollBack();
                    return false;
                }
                break;
            case self::_REJECT:
                $bsbankcheck = BsBankCheck::findOne(['rbo_id' => $id]);
                $bsbankcheck->state = 30;
                if(!$bsbankcheck->save())
                {
                    throw new yii\base\Exception(json_encode($bsbankcheck->getFirstError(),JSON_UNESCAPED_UNICODE));
                }
                //恢复成信用额度表之前的状态
                $repayid=Yii::$app->db->createCommand("select a.ord_pay_id from oms.r_bank_order a left join oms.ord_pay b on a.ord_pay_id=b.ord_pay_id left join erp.bs_payment c on b.pac_id=c.pac_id where a.rbo_id=:rbo_id and c.pac_sname='信用额度支付'",['rbo_id'=>$id])->queryAll();
                for($i=0;$i<count($repayid);$i++)
                {
                    $repayData=RepayCredit::findOne(['ord_pay_id'=>$repayid[$i]['ord_pay_id']]);
                    $repayData->is_repay=0;
                    $repayData->apper='';
                    $repayData->app_date='';
                    if (!$repayData->save()) {
                        throw new \Exception(Json::encode($repayData->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
                return true;
                break;
            default:
                return '';
        }
    }
    //批量收款送审(同公司多流水对多订单)
    public function actionBatchReviewTwo()
    {
        $post = Yii::$app->request->post();
        $type = $post['type'];  //审核流类型ID
        $staff = $post['staff'];//送审人
        $reviewer = explode(',', $post['reviewer']);//审核人
        $importList=$post['importList'];
        $Id = null;
        if (!empty($post['rbo_id'])) {
            $Id = $post['rbo_id'];
        }
        $result = $this->BatchRecevieTwo($Id, self::_PUSH, $staff,$importList);
        if ($result['status'] == 0) {
            throw new \Exception($result['msg']);
        }
        $busId = $result['msg'];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 查找记录阻止重复送审
            $recordH = Verifyrecord::find()->where(['vco_busid' => $busId, 'but_code' => $type])->andFilterWhere(['!=', 'vco_status', Verifyrecord::STATUS_REJECT])->one();
            if (!empty($recordH)) {
                return $this->error('不允许重复送审!');
            }
            //获取业务审批流程
            $isOrgRule = $this->isOrgRule($type);
            $ruleModel = $this->getRule($type, $isOrgRule, $staff);
            //审核流主表ID
            $ruleId = $ruleModel->review_rule_id;
            //业务代码
            $ruleCode = $ruleModel->business_code;
            //获取审核人单位代码
            $orgCode = HrStaff::getOrgCode($staff);

            $model = Verifyrecord::find()->where(['vco_busid' => $busId, 'but_code' => $type])->andFilterWhere(['vco_status' => Verifyrecord::STATUS_REJECT])->one();
            $oldChildCount = 0; //原有的审核记录数
            //驳回后再送审进入
            if (!empty($model)) {
                $model->vco_status = Verifyrecord::STATUS_DEFAULT;
                if (!$model->save()) {
                    throw new \Exception(current($model->getFirstErrors()));
                }
                $oldChild = VerifyrecordChild::find()->where(['vco_id' => $model->vco_id])->asArray()->all();
                $oldChildCount = count($oldChild);
            } else {
                //审核,顺序
                $model = new Verifyrecord();
                $model->ver_id = $ruleId;                             //审核流ID
                $model->bus_code = $ruleCode;
                $model->but_code = $type;
                $model->vco_busid = $busId;                           //单据ID
                $model->vco_send_acc = $staff;                        //送审人
                $model->vco_send_dept = $orgCode->organization_code;  //送审部门
                if (!$model->save()) {
                    throw new \Exception(current($model->getFirstErrors()));
                }
            }
            $ruleChild = '';
            //自选审核人进入
            if (!empty($reviewer)) {
                foreach ($reviewer as $key => $value) {
                    $ruleChild[$key] = BsReviewRuleChild::find()->where(['rule_child_id' => $value])->one();
                    $ruleChild[$key]['rule_child_index'] = $key + 1;
                }
            } else {
                $ruleChild = BsReviewRuleChild::find()->where(['review_rule_id' => $ruleId])->all();
            }
            foreach ($ruleChild as $key => $val) {
                $childModel = new VerifyrecordChild();
                $childId = $val['rule_child_id'];
                //条件不通过退出
                $isCondition = $this->getCondition($childId, $busId);
                if ($isCondition === false) {
                    continue;
                }
                $childModel->vco_id = $model->vco_id;
                $childModel->ver_acc_id = $val['review_user_id'];     //当前审核人
                $childModel->ver_acc_rule = $val['review_role_id'];   //当前审核角色
                $childModel->ver_scode = $val['rule_child_index'] + $oldChildCount;
                if ($val['rule_child_index'] == 1) {
                    $childModel->vcoc_status = VerifyrecordChild::STATUS_CHECKIND;
                }
                $childModel->acc_code_agent = $val['agent_one_id'];       //代理人
                $childModel->rule_code_agent = $val['review_role_id'];    //代理角色
                //            $model->acc_code_agents=$val['agent_two_id'];         //代理人
                if (!$childModel->save()) {
                    throw new \Exception(current($childModel->getFirstErrors()));
                }
            }
            $firstChild = reset($ruleChild);
            if (!empty($firstChild['review_user_id'])) {
                $token = $this->getToken($firstChild['review_user_id'], $model->vco_id);
                if (!empty($token['email'])) {
                    $res = $this->sendMail($token['email'], $token['url']);
                    if ($res->status == 1) {
                        // 发送邮件成功改变token 使原有token失效
//                        $this->changeToken($firstChild['review_user_id']);
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            BsBankCheck::deleteAll(['rbo_id' => $busId]);
            RBankOrder::deleteAll(['rbo_id' => $busId]);
            $model=Yii::$app->db->createCommand("select a.ord_pay_id from oms.r_bank_order a left join oms.ord_pay b on a.ord_pay_id=b.ord_pay_id left join erp.bs_payment c on b.pac_id=c.pac_id where a.rbo_id=:rbo_id and c.pac_sname='信用额度支付'",['rbo_id'=>$busId])->queryAll();
            foreach($model as $value)
            {
                $repaycidt=RepayCredit::findOne(['ord_pay_id'=>$value['ord_pay_id']]);
                $repaycidt->is_repay=0;
                $repaycidt->apper='';
                $repaycidt->app_date='';
                $repaycidt->save();
            }
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }
    //批量收款送审(同公司多流水对多订单)
    private function BatchRecevieTwo($id, $type, $staff=null,$importList=null)
    {
        switch ($type) {
            case self::_PUSH:
                try {
                    $transaction = Yii::$app->db->beginTransaction();
                    if (isset($id)) {
                        RBankOrder::deleteAll(['rbo_id' => $id]);
                        $bsbankcheck = BsBankCheck::find()->where(['rbo_id' => $id])->one();
                    } else {
                        $bsbankcheck = new BsBankCheck();
                    }
                    $bsbankcheck->state = 10;
                    $bsbankcheck->opper = $staff;
                    $bsbankcheck->opp_date = date('Y-m-d H:i:s',time());
                    $bsbankcheck->opp_ip = Yii::$app->request->getUserIP();
                    if (!$bsbankcheck->save()) {
                        throw new \Exception(Json::encode($bsbankcheck->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                    foreach ($importList as $value)
                    {
                            $order_no = explode(";", $value['order_no']);
                            for($t=0;$t<count($order_no);$t++)
                            {
                                $model=\Yii::$app->db->createCommand("select b.ord_pay_id,c.pac_sname from oms.ord_pay b left join erp.bs_payment c on b.pac_id=c.pac_id left join oms.repay_credit d on b.ord_pay_id=d.ord_pay_id where b.ord_id in (select a.ord_id from oms.ord_info a where a.ord_no=:ord_no) AND
((c.pac_sname='预付款' and b.yn_pay=0) or (c.pac_sname='信用额度支付' and b.yn_pay=1 and d.is_repay=0))",['ord_no'=>$order_no[$t]])->queryAll();
                                foreach($model as $val)
                                {
                                    $model2=\Yii::$app->db->createCommand("select a.* from oms.r_bank_order a where a.ord_pay_id=:ord_pay_id and a.rbo_id=:rbo_id and a.TRANSID=:TRANSID",['ord_pay_id'=>$val['ord_pay_id'],'rbo_id'=>$bsbankcheck->attributes['rbo_id'],'TRANSID'=>$value['transid']])->queryAll();
                                    if(!$model2)
                                    {
                                        if ($model[0]['pac_sname']=="信用额度支付")
                                        {
                                            $creditTable=RepayCredit::findOne(['ord_pay_id'=>$val['ord_pay_id']]);
                                            $creditTable->is_repay=2;
                                            $creditTable->apper=$staff;
                                            $creditTable->app_date=date('Y-m-d H:i:s',time());
                                            if (!$creditTable->save()) {
                                                throw new \Exception(Json::encode($creditTable->getErrors(), JSON_UNESCAPED_UNICODE));
                                            }
                                        }
                                        $rbankModel=new RBankOrder();
                                        $rbankModel->TRANSID=$value['transid'];
                                        $rbankModel->rbo_id=$bsbankcheck->attributes['rbo_id'];
                                        $rbankModel->ord_pay_id=$val['ord_pay_id'];
                                        $rbankModel->remark=$value['remark'];
                                        if (!$rbankModel->save()) {
                                            throw new \Exception(Json::encode($rbankModel->getErrors(), JSON_UNESCAPED_UNICODE));
                                        }
                                    }
                                }
                            }
                    }
                    $transaction->commit();
                    return $this->success($bsbankcheck->attributes['rbo_id']);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return $this->error($e->getMessage());
                }
                break;
            case self::_PASS:
                try
                {
                    $trans=Yii::$app->db->beginTransaction();
                    $bsbankcheck = BsBankCheck::findOne(['rbo_id' => $id]);
                    $bsbankcheck->state = 20;
                    if(!$bsbankcheck->save())
                    {
                        throw new yii\base\Exception(json_encode($bsbankcheck->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                    $ordPayId = RBankOrder::find()->select('ord_pay_id')->where(['rbo_id' => $id])->asArray()->all();
                    $ord_pay_id=array_unique($ordPayId);
                    foreach ($ord_pay_id as $value) {
                        $cust_id = Yii::$app->db->createCommand("select c.cust_id from erp.crm_bs_customer_info c where 
c.cust_code in (select b.cust_code from oms.ord_pay a left join oms.ord_info b on a.ord_id=b.ord_id where a.ord_pay_id=:ord_pay_id)",['ord_pay_id'=>$value['ord_pay_id']])->queryAll();
                        if (!empty($cust_id))
                        {
                            $creditModel=BsCredit::findOne(['cust_id'=>$cust_id[0]['cust_id']]);
                            $ordpay=OrdPay::findOne(['ord_pay_id'=>$value['ord_pay_id']]);
                            $creditModel->aval_amount=((float)$creditModel->aval_amount*1000+(float)$ordpay['stag_cost']*1000)/1000;
                            $creditModel->sum_amount=((float)$creditModel->sum_amount*1000-(float)$ordpay['stag_cost']*1000)/1000;
                            if(!$creditModel->save())
                            {
                                throw new yii\base\Exception(json_encode($creditModel->getErrors(),JSON_UNESCAPED_UNICODE));
                            }
                        }
                        $payinfo = Yii::$app->db->createCommand("select a.*,b.pac_sname from oms.ord_pay a left join erp.bs_payment b on a.pac_id=b.pac_id where a.ord_pay_id=:ord_pay_id", ['ord_pay_id' => $value['ord_pay_id']])->queryOne();
                        if ($payinfo['yn_pay'] == 0 && $payinfo['pac_sname'] == "预付款") {
                            $payModel = OrdPay::findOne(['ord_pay_id' => $value['ord_pay_id']]);
                            $payModel->yn_pay = 1;
                            if(!$payModel->save())
                            {
                                throw new yii\base\Exception(json_encode($payModel->getErrors(),JSON_UNESCAPED_UNICODE));
                            }
                        } else if ($payinfo['yn_pay'] == 1 && $payinfo['pac_sname'] == "信用额度支付") {
                            $repayModel = RepayCredit::findOne(['ord_pay_id' => $value['ord_pay_id']]);
                            $repayModel->is_repay = 1;
                            $repayModel->confirm_date = date('Y-m-d H:i:s', time());
                            if(!$repayModel->save())
                            {
                                throw new yii\base\Exception(json_encode($repayModel->getErrors(),JSON_UNESCAPED_UNICODE));
                            }
                        }
                    }
                    $trans->commit();
                    return true;
                }
                catch(yii\base\Exception $e)
                {
                    $trans->rollBack();
                    return false;
                }
                break;
            case self::_REJECT:
                $bsbankcheck = BsBankCheck::findOne(['rbo_id' => $id]);
                $bsbankcheck->state = 30;
                if(!$bsbankcheck->save())
                {
                    throw new yii\base\Exception(json_encode($bsbankcheck->getFirstError(),JSON_UNESCAPED_UNICODE));
                }
                //恢复成信用额度表之前的状态
                $repayid=Yii::$app->db->createCommand("select a.ord_pay_id from oms.r_bank_order a left join oms.ord_pay b on a.ord_pay_id=b.ord_pay_id left join erp.bs_payment c on b.pac_id=c.pac_id where a.rbo_id=:rbo_id and c.pac_sname='信用额度支付'",['rbo_id'=>$id])->queryAll();
                $repayId=array_unique($repayid);
                for($i=0;$i<count($repayId);$i++)
                {
                    $repayData=RepayCredit::findOne(['ord_pay_id'=>$repayId[$i]['ord_pay_id']]);
                    $repayData->is_repay=0;
                    $repayData->apper='';
                    $repayData->app_date='';
                    if (!$repayData->save()) {
                        throw new \Exception(Json::encode($repayData->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
                return true;
                break;
            default:
                return '';
        }
    }

    public function actionLlimit($id){
        $model['file'] = LCreditFile::find()->where(['l_credit_id'=>$id])->one();
        $model['limit'] = (new Query())->select([
            'l_limit_id',
            'l_credit_id',
            'credit_limit',
            'approval_limit',
            "date_format(validity_date,'%Y-%m-%d') validity_date",
            "(CASE credit_type WHEN 65 THEN '无担保' WHEN  64 THEN 'AP担保' WHEN 63 THEN '自保' ELSE '默认' END) as creditType"
        ])->from(LLimitType::tableName().' limit')
            ->where(['l_credit_id'=>$id])
            ->all();
        return $model;
    }
}


