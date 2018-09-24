<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\models\User;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsSettlement;
use app\modules\crm\models\CrmCorrespondentBank;
use app\modules\crm\models\CrmCreditLimit;
use app\modules\crm\models\CrmCreditMaintain;
use app\modules\crm\models\CrmCustCustomer;
use app\modules\crm\models\CrmCustLinkcomp;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustomerPersion;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmTurnover;
use app\modules\crm\models\LCrmCreditApply;
use app\modules\crm\models\LCrmCreditFile;
use app\modules\crm\models\LCrmCreditLimit;
use app\modules\crm\models\search\CrmCustomerInfoSearch;
use app\modules\crm\models\show\CrmCreditApplyShow;
use app\modules\hr\models\HrStaff;
use app\modules\common\models\BsCompany;
use Yii;
use app\modules\crm\models\CrmCreditApply;
use app\modules\crm\models\search\CrmCreditApplySearch;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

/**
 * CrmCreditApplyController implements the CRUD actions for CrmCreditApply model.
 */
class CrmCreditApplyController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\LCrmCreditApply';

    /**
     * Lists all CrmCreditApply models.
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new CrmCreditApplySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
//        foreach ($model as $key=>$val){
//            $arr = explode(',',$val['credit_limit_id']);
//            $credit_limit = '';
//            $approval_limit = '';
//            $validity_date = '';
//            foreach ($arr as $k=>$v){
//                $limit = (new Query())->select(['type.business_value credit_type','limit.credit_limit','limit.approval_limit','date_format(limit.validity_date,"%Y-%m-%d") validity_date'])->from(LCrmCreditLimit::tableName().' limit')->leftJoin(BsBusinessType::tableName().' type','type.business_type_id=limit.credit_type')->where(['and',['l_limit_id'=>$v],['limit_status'=>LCrmCreditLimit::YES_NEW]])->one();
//                if($k == count($arr)-1){
//                    $credit_limit .= '<span>'. $limit['credit_type'] .'：'.$limit['credit_limit'].'</span></br>';
//                    $approval_limit .= '<span>'.$limit['approval_limit'].'</span></br>';
//                    $validity_date .= '<span>'.$limit['validity_date'].'</span></br>';
//                }else{
//                    $credit_limit .= '<span class="border_cell">'. $limit['credit_type'] .'：'.$limit['credit_limit'].'</span></br>';
//                    $approval_limit .= '<span class="border_cell">'.$limit['approval_limit'].'</span></br>';
//                    $validity_date .= '<span class="border_cell">'.$limit['validity_date'].'</span></br>';
//                }
//                $val['credit_limit'] = !empty($limit['credit_limit'])?$credit_limit:'';
//                $val['approval_limit'] = !empty($limit['approval_limit'])?$approval_limit:'';
//                $val['validity_date'] = !empty($limit['approval_limit'])?$validity_date:'';
//                $model[$key] = $val;
//            }
//        }
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
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
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
//            $model = LCrmCreditApply::find()->where(['cust_id'=>$post['LCrmCreditApply']['cust_id']])->one();
            $model = LCrmCreditApply::find()->where(['and',['cust_id'=>$post['LCrmCreditApply']['cust_id']],['=','credit_status',LCrmCreditApply::STATUS_OVER]])->one();
            if(empty($model)){
                $model = new LCrmCreditApply();
//                $model->codeType=CrmCustomerInfo::CODE_TYPE_CUSTOMER;
            }
            $total_amount='';
            $limitArr = isset($post['LCrmCreditLimit']) ? $post['LCrmCreditLimit'] : false;
            if($limitArr){
                foreach($limitArr as $k=>$v){
                    $total_amount += $v['credit_limit'];
                }
            }
            $model->load($post);
            $model->total_amount = $total_amount;
            $model->credit_status = LCrmCreditApply::STATUS_PENDING;
            if (!$model->save()) {
                throw new \Exception("新增账信申请失败");
            }
            $aid = $model->l_credit_id;
            $custId = $model->cust_id;
            $createBy = $model->create_by;

            if ($limitArr) {
                foreach ($limitArr as $value) {
                    $crmLimit = new LCrmCreditLimit();
                    $limits['LCrmCreditLimit'] = $value;
                    $crmLimit->load($limits);
                    $crmLimit->l_credit_id = $aid;
                    if (!$crmLimit->save()) {
                        throw  new \Exception("新增申请额度失败");
                    };
                }
            }
            /*申请额度信息*/

            $info = CrmCustomerInfo::findOne($custId);
            $info->load($post);
            if (!$info->save()) {
                throw new \Exception("修改客户失败");
            }
            $fileArrMessage = isset($post['message']) ? $post['message'] : false;
            if($fileArrMessage){
                foreach ($fileArrMessage as $key => $val){
                    $fileMessage = new LCrmCreditFile();
                    $fileMessage->l_credit_id = $aid;
                    $fileMessage->file_type = '10';
                    $fileMessage->file_old = $val['file_old'];
                    $fileMessage->file_new = $val['file_new'];
                    if (!$fileMessage->save()) {
                        throw  new \Exception("签字档上传失败");
                    };
                }
            }
            $fileArr = isset($post['upload']) ? $post['upload'] : false;
            if($fileArr){
                foreach ($fileArr as $key => $val){
                    $file = new LCrmCreditFile();
                    $file->l_credit_id = $aid;
                    $file->file_type = '11';
                    $file->file_old = $val['file_old'];
                    $file->file_new = $val['file_new'];
                    if (!$file->save()) {
                        throw  new \Exception("附件上传失败");
                    };
                }
            }
            /*营业额*/
            $countTurnover = CrmTurnover::find()->where(['cust_id' => $custId])->count();
            if (CrmTurnover::deleteAll(['cust_id' => $custId]) < $countTurnover) {
                throw  new \Exception("更新营业额失败");
            };
            $turnoverArr = !empty($post['CrmTurnover']['ct']) ? $post['CrmTurnover']['ct'] : false;
            $turnoveryear = !empty($post['CrmTurnover']['year']) ? $post['CrmTurnover']['year'] : false;
            if ($turnoverArr) {
                foreach ($turnoverArr as $k => $v){
                    foreach ($turnoveryear as $ks => $a){
                        $cc['currency'] = $v['currency'];
                        $cc['turnover'] = $v['turnover'][$ks];
                        $cc['year'] = $a;
                        $b[] = $cc;
                    }
                }
                foreach ($b as $key => $value){
                    $crmTurnover = new CrmTurnover();
                    $crmTurnover->cust_id = $custId;
                    $crmTurnover->currency = $value['currency'];
                    $crmTurnover->turnover = $value['turnover'];
                    $crmTurnover->year = $value['year'];
                    if (!$crmTurnover->save()) {
                        throw  new \Exception("新增营业额失败");
                    };
                }
            }
            /*主要联系人*/
            $countPerson = CrmCustomerPersion::find()->where(['cust_id' => $custId])->count();
            if (CrmCustomerPersion::deleteAll(['cust_id' => $custId]) < $countPerson) {
                throw  new \Exception("更新联系人失败");
            };
            $persionArr = isset($post['CrmCustomerPersion']) ? $post['CrmCustomerPersion'] : false;
            if ($persionArr) {
                foreach ($persionArr as $value) {
                    if ($value['ccper_name'] != null) {
                        $crmCustPersion = new CrmCustomerPersion();
                        $persions['CrmCustomerPersion'] = $value;
                        $crmCustPersion->load($persions);
                        $crmCustPersion->cust_id = $custId;
                        if (!$crmCustPersion->save()) {
                            throw  new \Exception("新增联系人失败");
                        };
                    }
                }
            }
            /*子公司及关联公司*/
            $countComp = CrmCustLinkcomp::find()->where(['cust_id' => $custId])->count();
            if (CrmCustLinkcomp::deleteAll(['cust_id' => $custId]) < $countComp) {
                throw  new \Exception("更新子公司失败");
            };
            $compArr = isset($post['CrmCustLinkcomp']) ? $post['CrmCustLinkcomp'] : false;
            if ($compArr) {
                foreach ($compArr as $value) {
                    if ($value['linc_name'] != null) {
                        $crmCustLinkcomp = new CrmCustLinkcomp();
                        $comps['CrmCustLinkcomp'] = $value;
                        $crmCustLinkcomp->load($comps);
                        $crmCustLinkcomp->cust_id = $custId;
                        if (!$crmCustLinkcomp->save()) {
                            throw  new \Exception("新增子公司失败");
                        };
                    }
                }
            }
            /*主要客户*/
            $countCustomer = CrmCustCustomer::find()->where(['and',['cust_id' => $custId],['=','cust_type',CrmCustCustomer::TYPE_CUSTOMER]])->count();
            if (CrmCustCustomer::deleteAll(['and',['cust_id' => $custId],['=','cust_type',CrmCustCustomer::TYPE_CUSTOMER]]) < $countCustomer) {
                throw  new \Exception("更新主要客户失败");
            };
            $customerArr = isset($post['CrmCustCustomer']) ? $post['CrmCustCustomer'] : false;
            if ($customerArr) {
                foreach ($customerArr as $value) {
                    if ($value['cc_customer_name'] != null) {
                        $crmCustCustomer = new CrmCustCustomer();
                        $products['CrmCustCustomer'] = $value;
                        $crmCustCustomer->load($products);
                        $crmCustCustomer->cust_id = $custId;
                        $crmCustCustomer->create_by = $createBy;
                        $crmCustCustomer->cust_type = CrmCustCustomer::TYPE_CUSTOMER;
                        if (!$crmCustCustomer->save()) {
                            throw  new \Exception("新增主要客户失败");
                        };
                    }
                }
            }
            /*主要供应商*/
            $countSupplier = CrmCustCustomer::find()->where(['and',['cust_id' => $custId],['=','cust_type',CrmCustCustomer::TYPE_SUPPLIER]])->count();
            if (CrmCustCustomer::deleteAll(['and',['cust_id' => $custId],['=','cust_type',CrmCustCustomer::TYPE_SUPPLIER]]) < $countSupplier) {
                throw  new \Exception("更新主要供应商失败");
            };
            $supplierArr = isset($post['CrmCustSupplier']) ? $post['CrmCustSupplier'] : false;
            if ($supplierArr) {
                foreach ($supplierArr as $value) {
                    if ($value['cc_customer_name'] != null) {
                        $supplier = new CrmCustCustomer();
                        $suppliers['CrmCustSupplier'] = $value;
                        $supplier->cc_customer_name = $suppliers['CrmCustSupplier']['cc_customer_name'];
                        $supplier->payment_clause = $suppliers['CrmCustSupplier']['payment_clause'];
                        $supplier->cc_customer_tel = $suppliers['CrmCustSupplier']['cc_customer_tel'];
                        $supplier->cc_customer_remark = $suppliers['CrmCustSupplier']['cc_customer_remark'];
                        $supplier->cust_id = $custId;
                        $supplier->create_by = $createBy;
                        $supplier->cust_type = CrmCustCustomer::TYPE_SUPPLIER;
                        if (!$supplier->save()) {
                            throw  new \Exception("新增主要供应商失败");
                        };
                    }
                }
            }
            /*主要往来银行*/
            $countBank = CrmCorrespondentBank::find()->where(['cust_id' => $custId])->count();
            if (CrmCorrespondentBank::deleteAll(['cust_id' => $custId]) < $countBank) {
                throw  new \Exception("更新银行失败");
            };
            $bankArr = isset($post['CrmCorrespondentBank']) ? $post['CrmCorrespondentBank'] : false;
            if ($bankArr) {
                foreach ($bankArr as $value) {
                    if ($value['bank_name'] != null) {
                        $crmBank = new CrmCorrespondentBank();
                        $products['CrmCorrespondentBank'] = $value;
                        $crmBank->load($products);
                        $crmBank->cust_id = $custId;
                        if (!$crmBank->save()) {
                            throw  new \Exception("新增主要往来银行失败");
                        };
                    }
                }
            }
//            return $model->getErrors();
            $transaction->commit();
            return $this->success('',$aid);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
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
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $updateBy = $post['LCrmCreditApply']['update_by'];
            $custId = $post['LCrmCreditApply']['cust_id'];

            $total_amount='';
            $limit = isset($post['LCrmCreditLimit']) ? $post['LCrmCreditLimit'] : false;
            if($limit){
                foreach($limit as $k=>$v){
                    $total_amount += $v['credit_limit'];
                }
            }
            $model->load($post);
            $model->total_amount = $total_amount;
            if (!$model->save()) {
                throw new \Exception("修改账信申请失败");
            }
            if($model['credit_status'] == '14'){
            if ($limit) {
                $newLimit = LCrmCreditLimit::find()->where(['and',['l_credit_id'=>$id],['limit_status'=>LCrmCreditLimit::YES_NEW],['is_approval'=>LCrmCreditLimit::NO_APPROVAL]])->all();
                foreach ($newLimit as $v){
                    $limitmodel = LCrmCreditLimit::find()->where(['and',['l_limit_id'=>$v['l_limit_id']],['limit_status'=>LCrmCreditLimit::YES_NEW],['is_approval'=>LCrmCreditLimit::NO_APPROVAL]])->one();
                    $limitmodel->limit_status = LCrmCreditLimit::NO_NEW;
                    if (!$limitmodel->save()) {
                        throw  new \Exception("更新额度失败");
                    };
                }
                foreach ($limit as $value) {
                    $crmCreditLimit = new LCrmCreditLimit();
//                    $limits['LCrmCreditLimit'] = $value;
                    $limits['LCrmCreditLimit']['credit_type'] = $value['credit_type'];
                    $limits['LCrmCreditLimit']['credit_limit'] = $value['credit_limit'];
                    $crmCreditLimit->load($limits);
                    $crmCreditLimit->l_credit_id = $id;
                    if (!$crmCreditLimit->save()) {
                        throw  new \Exception("更新额度失败");
                    };
                }
            }
            }else{
                if ($limit) {
//                    foreach ($limit as $value) {
//                        $crmLimit = LCrmCreditLimit::findOne($value['l_limit_id']);
//                        $crmLimits['LCrmCreditLimit'] = $value;
//                        $crmLimit->load($crmLimits);
//                        if (!$crmLimit->save()) {
//                            throw  new \Exception("更新申请额度失败");
//                        };
//                    }
                    $countLimit = LCrmCreditLimit::find()->where(['and',['l_credit_id'=>$id],['limit_status'=>LCrmCreditLimit::YES_NEW],['is_approval'=>LCrmCreditLimit::DEFAULT_APPROVAL]])->count();
                    if (LCrmCreditLimit::deleteAll(['l_credit_id' => $id]) < $countLimit) {
                        throw  new \Exception("更新额度失败");
                    };
                    if ($limit) {
                        foreach ($limit as $value) {
                            $crmCreditLimit = new LCrmCreditLimit();
                            $limits['LCrmCreditLimit'] = $value;
                            $crmCreditLimit->load($limits);
                            $crmCreditLimit->l_credit_id = $id;
                            if (!$crmCreditLimit->save()) {
                                throw  new \Exception("更新额度失败");
                            };
                        }
                    }
                }
            }

            $fileArrMessage = isset($post['message']) ? $post['message'] : false;
            if($fileArrMessage){
                $countMessage = LCrmCreditFile::find()->where(['and',['l_credit_id' => $id],['file_type' => '10']])->count();
                if (LCrmCreditFile::deleteAll(['and',['l_credit_id' => $id],['file_type' => '10']]) < $countMessage) {
                    throw  new \Exception("签字档上传失败");
                };
                foreach ($fileArrMessage as $key => $val){
                    $fileMessage = new LCrmCreditFile();
                    $fileMessage->l_credit_id = $id;
                    $fileMessage->file_type = '10';
                    $fileMessage->file_old = $val['file_old'];
                    $fileMessage->file_new = $val['file_new'];
                    if (!$fileMessage->save()) {
                        throw  new \Exception("签字档上传失败");
                    };
                }
            }
            $fileArr = isset($post['upload']) ? $post['upload'] : false;
            if($fileArr){
                $countFile = LCrmCreditFile::find()->where(['and',['l_credit_id' => $id],['file_type' => '11']])->count();
                if (LCrmCreditFile::deleteAll(['and',['l_credit_id' => $id],['file_type' => '11']]) < $countFile) {
                    throw  new \Exception("附件上传失败");
                };
                foreach ($fileArr as $key => $val){
                    $file = new LCrmCreditFile();
                    $file->l_credit_id = $id;
                    $file->file_type = '11';
                    $file->file_old = $val['file_old'];
                    $file->file_new = $val['file_new'];
                    if (!$file->save()) {
                        throw  new \Exception("附件上传失败");
                    };
                }
            }
            /*申请额度信息*/

            $model->load($post);
            $model->codeType=CrmCustomerInfo::CODE_TYPE_CUSTOMER;
            $model->credit_status = LCrmCreditApply::STATUS_PENDING;
            if (!$model->save()) {
                throw new \Exception("修改账信申请失败");
            }
            $info = CrmCustomerInfo::findOne($custId);
            $info->load($post);
            if (!$info->save()) {
                throw new \Exception("修改客户失败");
            }
            /*营业额*/
            $countTurnover = CrmTurnover::find()->where(['cust_id' => $custId])->count();
            if (CrmTurnover::deleteAll(['cust_id' => $custId]) < $countTurnover) {
                throw  new \Exception("更新营业额失败");
            };
            $turnoverArr = !empty($post['CrmTurnover']['ct']) ? $post['CrmTurnover']['ct'] : false;
            $turnoveryear = $post['CrmTurnover']['year'] ? $post['CrmTurnover']['year'] : false;
            if ($turnoverArr) {
                foreach ($turnoverArr as $k => $v){
                    foreach ($turnoveryear as $ks => $a){
                        $cc['currency'] = $v['currency'];
                        $cc['turnover'] = $v['turnover'][$ks];
                        $cc['year'] = $a;
                        $b[] = $cc;
                    }
                }
                foreach ($b as $key => $value){
                    $crmTurnover = new CrmTurnover();
                    $crmTurnover->cust_id = $custId;
                    $crmTurnover->currency = $value['currency'];
                    $crmTurnover->turnover = $value['turnover'];
                    $crmTurnover->year = $value['year'];
                    if (!$crmTurnover->save()) {
                        throw  new \Exception("更新营业额失败");
                    };
                }
            }
            /*主要联系人*/
            $countPerson = CrmCustomerPersion::find()->where(['cust_id' => $custId])->count();
            if (CrmCustomerPersion::deleteAll(['cust_id' => $custId]) < $countPerson) {
                throw  new \Exception("更新联系人失败");
            };
            $persionArr = isset($post['CrmCustomerPersion']) ? $post['CrmCustomerPersion'] : false;
            if ($persionArr) {
                foreach ($persionArr as $value) {
                    if ($value['ccper_name'] != null) {
                        $crmCustPersion = new CrmCustomerPersion();
                        $persions['CrmCustomerPersion'] = $value;
                        $crmCustPersion->load($persions);
                        $crmCustPersion->cust_id = $custId;
                        if (!$crmCustPersion->save()) {
                            throw  new \Exception("更新联系人失败");
                        };
                    }
                }
            }
            /*子公司及关联公司*/
            $countComp = CrmCustLinkcomp::find()->where(['cust_id' => $custId])->count();
            if (CrmCustLinkcomp::deleteAll(['cust_id' => $custId]) < $countComp) {
                throw  new \Exception("更新子公司失败");
            };
            $compArr = isset($post['CrmCustLinkcomp']) ? $post['CrmCustLinkcomp'] : false;
            if ($compArr) {
                foreach ($compArr as $value) {
                    if ($value['linc_name'] != null) {
                        $crmCustLinkcomp = new CrmCustLinkcomp();
                        $comps['CrmCustLinkcomp'] = $value;
                        $crmCustLinkcomp->load($comps);
                        $crmCustLinkcomp->cust_id = $custId;
                        if (!$crmCustLinkcomp->save()) {
                            throw  new \Exception("更新子公司失败");
                        };
                    }
                }
            }
            /*主要客户*/
            $countCustomer = CrmCustCustomer::find()->where(['and',['cust_id' => $custId],['=','cust_type',CrmCustCustomer::TYPE_CUSTOMER]])->count();
            if (CrmCustCustomer::deleteAll(['and',['cust_id' => $custId],['=','cust_type',CrmCustCustomer::TYPE_CUSTOMER]]) < $countCustomer) {
                throw  new \Exception("更新主要客户失败");
            };
            $customerArr = isset($post['CrmCustCustomer']) ? $post['CrmCustCustomer'] : false;
            if ($customerArr) {
                foreach ($customerArr as $value) {
                    if ($value['cc_customer_name'] != null) {
                        $crmCustCustomer = new CrmCustCustomer();
                        $products['CrmCustCustomer'] = $value;
                        $crmCustCustomer->load($products);
                        $crmCustCustomer->cust_id = $custId;
                        $crmCustCustomer->update_by = $updateBy;
                        $crmCustCustomer->cust_type = CrmCustCustomer::TYPE_CUSTOMER;
                        if (!$crmCustCustomer->save()) {
                            throw  new \Exception("更新主要客户失败");
                        };
                    }
                }
            }
            /*主要供应商*/
            $countSupplier = CrmCustCustomer::find()->where(['and',['cust_id' => $custId],['=','cust_type',CrmCustCustomer::TYPE_SUPPLIER]])->count();
            if (CrmCustCustomer::deleteAll(['and',['cust_id' => $custId],['=','cust_type',CrmCustCustomer::TYPE_SUPPLIER]]) < $countSupplier) {
                throw  new \Exception("更新主要供应商失败");
            };
            $supplierArr = isset($post['CrmCustSupplier']) ? $post['CrmCustSupplier'] : false;
            if ($supplierArr) {
                foreach ($supplierArr as $value) {
                    if ($value['cc_customer_name'] != null) {
                        $supplier = new CrmCustCustomer();
                        $suppliers['CrmCustSupplier'] = $value;
                        $supplier->cc_customer_name = $suppliers['CrmCustSupplier']['cc_customer_name'];
                        $supplier->payment_clause = $suppliers['CrmCustSupplier']['payment_clause'];
                        $supplier->cc_customer_tel = $suppliers['CrmCustSupplier']['cc_customer_tel'];
                        $supplier->cc_customer_remark = $suppliers['CrmCustSupplier']['cc_customer_remark'];
                        $supplier->cust_id = $custId;
                        $supplier->update_by = $updateBy;
                        $supplier->cust_type = CrmCustCustomer::TYPE_SUPPLIER;
                        if (!$supplier->save()) {
                            throw  new \Exception("更新主要供应商失败");
                        };
                    }
                }
            }
            /*主要往来银行*/
            $countBank = CrmCorrespondentBank::find()->where(['cust_id' => $custId])->count();
            if (CrmCorrespondentBank::deleteAll(['cust_id' => $custId]) < $countBank) {
                throw  new \Exception("更新银行失败");
            };
            $bankArr = isset($post['CrmCorrespondentBank']) ? $post['CrmCorrespondentBank'] : false;
            if ($bankArr) {
                foreach ($bankArr as $value) {
                    if ($value['bank_name'] != null) {
                        $crmBank = new CrmCorrespondentBank();
                        $products['CrmCorrespondentBank'] = $value;
                        $crmBank->load($products);
                        $crmBank->cust_id = $custId;
                        if (!$crmBank->save()) {
                            throw  new \Exception("更新主要往来银行失败");
                        };
                    }
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success('',$id);
    }

    /**
     * Deletes an existing CrmCreditApply model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCancleApply($id)
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $arr = explode(',',$id);
            $name = '';
            foreach ($arr as $key => $val){
                $priceInfo = LCrmCreditApply::findOne($val);
                $priceInfo->credit_status = LCrmCreditApply::STATUS_DELETE;
                $priceInfo->load($post);
                $name = $name.$priceInfo['credit_code'].',';
                $res = $priceInfo->save();
            }
            $price_no = trim($name,',');
            if ($res) {
                $msg = array('id' => $id, 'msg' => '取消申请"' . $price_no . '"');
                return $this->success('',$msg);
            } else {
                return $this->error();
            }
        }
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
     * Finds the CrmCreditApply model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CrmCreditApply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LCrmCreditApply::findOne($id)) !== null) {
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
        $downList['settlement'] = BsSettlement::getSettlement(); //付款条件
        $downList['settlement_type'] = [
            '0'=>'发票日',
            '1'=>'月结日'
        ];
        $downList['pay_method'] = BsPubdata::getList(BsPubdata::CRM_PAY_METHOD);//付款方式
        $downList['initial_day'] = BsPubdata::getList(BsPubdata::CRM_INITIAL_DAY);//起算日
        $downList['pay_day'] = BsPubdata::getList(BsPubdata::CRM_PAY_DAY);//付款日
        $downList['customerType'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_TYPE);//客户类型
        $downList['custLevel'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_LEVEL);  //客户等级
        $downList['salearea'] = CrmSalearea::getSalearea();//所在军区
        $downList['companyProperty'] = BsPubdata::getList(BsPubdata::CRM_COMPANY_PROPERTY);  //公司属性
        $downList['status'] = [
            LCrmCreditApply::STATUS_DELETE => '已取消',
            LCrmCreditApply::STATUS_PENDING => '待提交',
            LCrmCreditApply::STATUS_REVIEW => '审核中',
            LCrmCreditApply::STATUS_OVER => '审核完成',
            LCrmCreditApply::STATUS_REJECT => '驳回',
//            LCrmCreditApply::STATUS_FREEZE => '冻结',
        ];
        $downList['currency'] = BsPubdata::getList(BsPubdata::SUPPLIER_TRADE_CURRENCY); //币别

        $downList['verifyType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'credit'])->all();    //账信类型
        $downList['company'] = BsCompany::find()->select(['company_id','company_name'])->where(['=','company_status',BsCompany::STATUS_DEFAULT])->all();
        return $downList;
    }


    /**
     * @return mixed
     * 选择客户
     */
    public function actionSelectCustomer()
    {
        $searchModel = new CrmCustomerInfoSearch();
        $dataProvider = $searchModel->searchCreditCust(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }


    /**
     * @param $id
     * @return null|static
     * 详情页获取额度申请信息
     */
    public function actionModels($id)
    {
        $result = CrmCreditApplyShow::findOne($id);
        return $result;
    }

    /**
     * @param $id
     * 查询客户带出信息
     */
    public function actionCustomer($id){
        $query = (new Query())->select([
            'info.cust_id',
            'info.cust_sname',
            'info.cust_shortname',
            'info.cust_tel1',
            'info.cust_regfunds',
            'info.member_businessarea',
            'info.cust_parentcomp',
            'info.total_investment',
            'info.total_investment_cur',
            'info.official_receipts',
            'info.official_receipts_cur',
            'info.shareholding_ratio',
            'info.cust_contacts',
            'info.cust_tel2',
            'info.cust_tax_code',
            'info.cust_code apply_code',
            'cs.csarea_name',
            'bp_2.bsp_svalue cust_type',
            'bp_3.bsp_svalue cust_level',
            'bp_4.bsp_svalue cust_compvirtue',
            'hr_1.staff_name',
            'hr_1.staff_mobile',
            'bc_1.company_name',
            'bp_1.bsp_svalue regcurr',
        ])->from(CrmCustomerInfo::tableName().' info')
            ->leftJoin(CrmSalearea::tableName().' cs','cs.csarea_id = info.cust_salearea and cs.csarea_status !='.CrmSalearea::STATUS_DELETE)
            ->leftJoin(BsPubdata::tableName().' bp_1','bp_1.bsp_id=info.member_regcurr')
            ->leftJoin(BsPubdata::tableName().' bp_2','bp_2.bsp_id=info.cust_type')
            ->leftJoin(BsPubdata::tableName().' bp_3','bp_3.bsp_id=info.cust_level')
            ->leftJoin(BsPubdata::tableName().' bp_4','bp_4.bsp_id=info.cust_compvirtue')
            ->leftJoin(CrmCustPersoninch::tableName().' ccp','ccp.cust_id=info.cust_id')
            ->leftJoin(HrStaff::tableName().' hr_1','hr_1.staff_id=ccp.ccpich_personid')
            ->leftJoin(User::tableName().' us_1','us_1.staff_id=hr_1.staff_id')
            ->leftJoin(BsCompany::tableName().' bc_1','bc_1.company_id=us_1.company_id')
            ->where(['info.cust_id'=>$id])->one();
        return $query;
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     * 结算方式
     * 修改人：F1640717 2018-03-01
     * 修改內容：帳信申請時，UE要求付款條件之發票日與具體天數分開顯示，增加一個欄位 new_name 區分
     */
    public function actionSettlement($id){
      //  $model = BsSettlement::find()->select(['bnt_code','bnt_sname'])->where(['and',['bnt_status'=>'1'],['bnt_othername'=>$id]])->all();
        //帳信申請時，UE要求付款條件之發票日與具體天數分開顯示，增加一個欄位 new_name 區分
        $model=Yii::$app->db->createCommand('SELECT CASE bnt_othername WHEN "1" THEN	REPLACE (bnt_sname, "月结日", "") WHEN "0" THEN REPLACE (bnt_sname, "发票日", "") ELSE 	bnt_sname END AS new_name, bnt_code,bnt_sname FROM bs_settlement WHERE bnt_status = :v1  and bnt_othername=:v2',["v1"=>1,":v2"=>$id])->queryAll();
         return $model;
    }

    /**
     * @param $id
     * 驳回送审
     */
    public function actionVerify($id){
        $model = LCrmCreditApply::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if($model['credit_status'] == LCrmCreditApply::STATUS_REJECT){
                $limit = LCrmCreditLimit::find()->where(['and',['l_credit_id'=>$id],['is_approval'=>LCrmCreditLimit::NO_APPROVAL],['limit_status'=>LCrmCreditLimit::YES_NEW]])->all();
                foreach ($limit as $v){
                    $limitmodel1 = LCrmCreditLimit::find()->where(['and',['l_limit_id'=>$v['l_limit_id']],['limit_status'=>LCrmCreditLimit::YES_NEW],['is_approval'=>LCrmCreditLimit::NO_APPROVAL]])->one();
                    $limitmodel1->limit_status = LCrmCreditLimit::NO_NEW;
                    if (!$limitmodel1->save()) {
                        throw  new \Exception("更新额度失败");
                    };
                }
                foreach ($limit as $value){
                    $limitmodel = new LCrmCreditLimit();
                    $limitmodel->l_credit_id = $id;
                    $limitmodel->credit_type = $value['credit_type'];
                    $limitmodel->credit_limit = $value['credit_limit'];
                    $limitmodel->is_approval = LCrmCreditLimit::DEFAULT_APPROVAL;
                    $limitmodel->limit_status = LCrmCreditLimit::YES_NEW;
                    $limitmodel->remark = $value['remark'];
                    if (!$limitmodel->save()) {
                        throw  new \Exception("更新额度失败");
                    };
                }

            }else{
                $transaction->commit();
                return $this->success('true');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success('true');
    }
}
