<?php
/**
 * User: F1677929
 * Date: 2016/11/23
 */
namespace app\modules\ptdt\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrStaff;
use app\modules\hr\models\show\HrStaffShow;
use app\modules\ptdt\models\PdFirm;
use app\modules\ptdt\models\PdFirmEvaluate;
use app\modules\ptdt\models\PdFirmEvaluateApply;
use app\modules\ptdt\models\PdFirmEvaluateChild;
use app\modules\ptdt\models\PdFirmEvaluateProduct;
use app\modules\ptdt\models\PdFirmEvaluateResult;
use app\modules\ptdt\models\PdFirmReportProduct;
use app\modules\ptdt\models\PdProductType;
use app\modules\ptdt\models\search\PdFirmEvaluateChildSearch;
use app\modules\ptdt\models\search\PdFirmEvaluateSearch;
use app\modules\ptdt\models\show\PdFirmEvaluateApplyShow;
use app\modules\ptdt\models\show\PdFirmEvaluateChildShow;
use app\modules\ptdt\models\show\PdFirmEvaluateResultShow;
use app\modules\ptdt\models\show\PdFirmShow;
use Yii;
/**
 * 厂商评鉴控制器
 */
class FirmEvaluateController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\PdFirmEvaluate';

    /**
     * 厂商评鉴列表
     */
    public function actionIndex()
    {
        $model = new PdFirmEvaluateSearch();
        $dataProvider = $model->searchEvaluateMain(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 获取评鉴信息
     */
    public function actionLoadEvaluate()
    {
        $model = new PdFirmEvaluateChildSearch();
        $dataProvider = $model->searchEvaluateChild(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 新增厂商评鉴
     */
    public function actionAdd()
    {
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //厂商评鉴主表
            $evaluateModel = PdFirmEvaluate::find()->where(['and',['firm_id'=>$post['PdFirmEvaluate']['firm_id']],['!=','evaluate_status',PdFirmEvaluate::EVALUATE_DELETE]])->one();
            if (!empty($evaluateModel)) {
                $evaluateModel->evaluate_status = PdFirmEvaluate::EVALUATE_ING;
            } else {
                $evaluateModel = new PdFirmEvaluate();
                if (!$evaluateModel->load($post)) {
                    throw new \Exception("厂商评鉴主表数据加载失败！");
                }
            }
            if (!$evaluateModel->save()) {
                throw new \Exception("厂商评鉴主表保存失败！");
            }
            //厂商评鉴子表
            $evaluateChildModel = new PdFirmEvaluateChild();
            if ($evaluateChildModel->load($post)) {
                $evaluateChildModel->evaluate_id = $evaluateModel->evaluate_id;
                $evaluateChildModel->create_by = $evaluateModel->create_by;
                if (!$evaluateChildModel->save()) {
                    throw new \Exception("厂商评鉴子表保存失败");
                }
            } else {
                throw new \Exception("厂商评鉴子表加载失败");
            }
            //厂商评鉴结果表
            $evaluateResultModel = new PdFirmEvaluateResult();
            if ($evaluateResultModel->load($post)) {
                $evaluateResultModel->evaluate_child_id = $evaluateChildModel->evaluate_child_id;
                $evaluateResultModel->create_by = $evaluateChildModel->create_by;
                $evaluateResultModel->evaluate_department = PdFirmEvaluateResult::FIRM;
                if ($evaluateResultModel->save()) {
                    if ($evaluateResultModel->evaluate_result == PdFirmEvaluateResult::NO_PASS) {
                        $evaluateModel->evaluate_status = PdFirmEvaluate::EVALUATE_NO_PASS;
                        if (!$evaluateModel->save()) {
                            throw new \Exception("评鉴不通过保存失败");
                        }
                    }
                } else {
                    throw new \Exception("厂商评鉴结果表保存失败");
                }
            } else {
                throw new \Exception("厂商评鉴结果表加载失败");
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 获取列表页数据
     */
    public function actionIndexData()
    {
        return [
            //商品类别
            'productType' => PdProductType::getLevelOneValue(),
            //厂商类型
            'firmType' => BsPubdata::getData(BsPubdata::FIRM_TYPE),
            //集团供应商
            'groupSupplier' => BsPubdata::getData(BsPubdata::GROUP_SUPPLIER),
            //评鉴状态
            'evaluateStatus' => PdFirmEvaluate::evaluateStatus(),
        ];
    }

    /**
     * 获取新增数据
     */
    public function actionAddData($evaluatePersonId,$firmId=null)
    {
        //处理厂商信息
        $firmInfo = PdFirmShow::findOne($firmId);
        if (!empty($firmId) && empty($firmInfo)) {
            return null;
        }
        return [
            //综合等级
            'synthesisLevel' => BsPubdata::getData(BsPubdata::SUPPLIER_SYNTHESIS_LEVEL),
            //厂商评鉴结果
            'firmEvaluateResultList' => PdFirmEvaluateResult::firmEvaluateResultList(),
            //评鉴人信息
            'evaluatePersonInfo' => HrStaffShow::findOne($evaluatePersonId),
            //厂商信息
            'firmInfo' => $firmInfo,
        ];
    }

    /**
     * 获取评鉴申请通过的厂商
     */
    public function actionSelectFirm()
    {
        $model = new PdFirmEvaluateSearch();
        $dataProvider = $model->searchEvaluateApply(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 判断评鉴不通过厂商
     */
    public function actionEvaluateNoPassFirm($id)
    {
        $evaluateModel = PdFirmEvaluate::findOne($id);
        if ($evaluateModel->evaluate_status == PdFirmEvaluate::EVALUATE_NO_PASS) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 采购评鉴判断
     */
    public function actionPurchaseEvaluateJudge($id)
    {
        $evaluateChildModel = PdFirmEvaluateChild::findOne($id);
        $evaluateModel = PdFirmEvaluate::findOne($evaluateChildModel->evaluate_id);
        $firmEvaluateInfo = $evaluateChildModel->firmEvaluate;
        $purchaseEvaluateInfo = $evaluateChildModel->purchaseEvaluate;
        $manageEvaluateInfo = $evaluateChildModel->manageEvaluate;
        if ($evaluateModel->evaluate_status == PdFirmEvaluate::EVALUATE_ING) {
            if (empty($purchaseEvaluateInfo)) {
                if ($firmEvaluateInfo->evaluate_result == PdFirmEvaluateResult::PASS) {
                    if (empty($manageEvaluateInfo)) {
                        return true;
                    } else {
                        if ($manageEvaluateInfo->evaluate_result == PdFirmEvaluateResult::EVALUATE_PASS) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 工管评鉴判断
     */
    public function actionManageEvaluateJudge($id)
    {
        $evaluateChildModel = PdFirmEvaluateChild::findOne($id);
        $evaluateModel = PdFirmEvaluate::findOne($evaluateChildModel->evaluate_id);
        $firmEvaluateInfo = $evaluateChildModel->firmEvaluate;
        $purchaseEvaluateInfo = $evaluateChildModel->purchaseEvaluate;
        $manageEvaluateInfo = $evaluateChildModel->manageEvaluate;
        if ($evaluateModel->evaluate_status == PdFirmEvaluate::EVALUATE_ING) {
            if (empty($manageEvaluateInfo)) {
                if ($firmEvaluateInfo->evaluate_result == PdFirmEvaluateResult::PASS) {
                    if (empty($purchaseEvaluateInfo)) {
                        return true;
                    } else {
                        if ($purchaseEvaluateInfo->evaluate_result == PdFirmEvaluateResult::EVALUATE_PASS) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 获取评鉴数据
     */
    public function actionEvaluateData($id)
    {
        $evaluateChildData = PdFirmEvaluateChild::findOne($id);
        if (empty($evaluateChildData)) {
            return null;
        }
        //处理评鉴主表数据
        $evaluateData = $evaluateChildData->pdFirmEvaluate;
        $firmData = PdFirmShow::findOne($evaluateData->firm_id);
        //处理评鉴子表数据
        $evaluateChildData->evaluate_level = $evaluateChildData->synthesisLevel->bsp_svalue;
        //处理评鉴结果表数据
        $firmEvaluateData = PdFirmEvaluateResultShow::find()->where(['evaluate_department'=>PdFirmEvaluateResult::FIRM,'evaluate_child_id'=>$id])->one();
        $purchaseEvaluateData = PdFirmEvaluateResultShow::find()->where(['evaluate_department'=>PdFirmEvaluateResult::PROCUREMENT,'evaluate_child_id'=>$id])->one();
        $manageEvaluateData = PdFirmEvaluateResultShow::find()->where(['evaluate_department'=>PdFirmEvaluateResult::MANAGEMENT,'evaluate_child_id'=>$id])->one();
        return array(
            'firmData'=>$firmData,
            'evaluateChildData'=>$evaluateChildData,
            'firmEvaluateData'=>$firmEvaluateData,
            'purchaseManageEvaluateResultList'=>PdFirmEvaluateResult::purchaseManageEvaluateResultList(),
            'purchaseEvaluateData'=>!empty($purchaseEvaluateData)?$purchaseEvaluateData:null,
            'manageEvaluateData'=>!empty($manageEvaluateData)?$manageEvaluateData:null,

        );
    }

    /**
     * 采购评鉴
     */
    public function actionPurchaseEvaluate($id)
    {
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $evaluateChildModel = PdFirmEvaluateChild::findOne($id);
            $evaluateModel = PdFirmEvaluate::findOne($evaluateChildModel->evaluate_id);
            $purchaseEvaluateResult = $evaluateChildModel->purchaseEvaluate;
            if (empty($purchaseEvaluateResult)) {
                $evaluateResultModel = new PdFirmEvaluateResult();
            } else {
                $evaluateResultModel = $purchaseEvaluateResult;
            }
            if ($evaluateResultModel->load($post)) {
                $evaluateResultModel->evaluate_child_id = $evaluateChildModel->evaluate_child_id;
                $evaluateResultModel->evaluate_department = PdFirmEvaluateResult::PROCUREMENT;
                if ($evaluateResultModel->save()) {
                    if ($evaluateResultModel->evaluate_result == PdFirmEvaluateResult::CANCEL_ADD) {
                        $evaluateModel->evaluate_status = PdFirmEvaluate::EVALUATE_NO_PASS;
                        if (!$evaluateModel->save()) {
                            throw new \Exception("评鉴不通过保存失败！");
                        }
                    }
                    $manageEvaluateResult = $evaluateChildModel->manageEvaluate;
                    if (isset($manageEvaluateResult) && $manageEvaluateResult->evaluate_result==PdFirmEvaluateResult::EVALUATE_PASS) {
                        if ($evaluateResultModel->evaluate_result == PdFirmEvaluateResult::EVALUATE_PASS) {
                            $evaluateModel->evaluate_status = PdFirmEvaluate::EVALUATE_PASS;
                            if (!$evaluateModel->save()) {
                                throw new \Exception("评鉴通过保存失败！");
                            }
                        }
                    }
                } else {
                    throw new \Exception("采购评鉴保存失败！");
                }
            } else {
                throw new \Exception("采购评鉴数据加载失败！");
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 工管评鉴
     */
    public function actionManageEvaluate($id)
    {
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $evaluateChildModel = PdFirmEvaluateChild::findOne($id);
            $evaluateModel = PdFirmEvaluate::findOne($evaluateChildModel->evaluate_id);
            $manageEvaluateResult = $evaluateChildModel->manageEvaluate;
            if (empty($manageEvaluateResult)) {
                $evaluateResultModel = new PdFirmEvaluateResult();
            } else {
                $evaluateResultModel = $manageEvaluateResult;
            }
            if ($evaluateResultModel->load($post)) {
                $evaluateResultModel->evaluate_child_id = $evaluateChildModel->evaluate_child_id;
                $evaluateResultModel->evaluate_department = PdFirmEvaluateResult::MANAGEMENT;
                if ($evaluateResultModel->save()) {
                    if ($evaluateResultModel->evaluate_result == PdFirmEvaluateResult::CANCEL_ADD) {
                        $evaluateModel->evaluate_status = PdFirmEvaluate::EVALUATE_NO_PASS;
                        if (!$evaluateModel->save()) {
                            throw new \Exception("评鉴不通过保存失败！");
                        }
                    }
                    $purchaseEvaluateResult = $evaluateChildModel->purchaseEvaluate;
                    if (isset($purchaseEvaluateResult) && $purchaseEvaluateResult->evaluate_result==PdFirmEvaluateResult::EVALUATE_PASS) {
                        if ($evaluateResultModel->evaluate_result == PdFirmEvaluateResult::EVALUATE_PASS) {
                            $evaluateModel->evaluate_status = PdFirmEvaluate::EVALUATE_PASS;
                            if (!$evaluateModel->save()) {
                                throw new \Exception("评鉴通过保存失败！");
                            }
                        }
                    }
                } else {
                    throw new \Exception("工管评鉴保存失败！");
                }
            } else {
                throw new \Exception("工管评鉴数据加载失败！");
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 修改判断
     */
    public function actionEditJudge($id)
    {
        $evaluateChildModel = PdFirmEvaluateChild::findOne($id);
        $evaluateResult = $evaluateChildModel->evaluateResult;
        if (($evaluateResult->evaluate_result == PdFirmEvaluateResult::PLAN_TUTOR) || ($evaluateResult->evaluate_result == PdFirmEvaluateResult::IMPROVE_REVIEW)) {
            if ($evaluateResult->evaluate_department == PdFirmEvaluateResult::PROCUREMENT) {
                return 20;
            } elseif ($evaluateResult->evaluate_department == PdFirmEvaluateResult::MANAGEMENT) {
                return 30;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 获取评鉴申请数据
     */
    public function actionEvaluateApplyData($id)
    {
        $evaluateChildData = PdFirmEvaluateChild::findOne($id);
        $evaluateData = PdFirmEvaluate::findOne($evaluateChildData->evaluate_id);
        $evaluateApplyData = PdFirmEvaluateApplyShow::find()->where(['firm_id'=>$evaluateData->firm_id])->one();
        return $evaluateApplyData;
    }

    /**
     * 删除主表判断
     */
    public function actionDeleteMainJudge($id)
    {
        $evaluateModel = PdFirmEvaluate::findOne($id);
        $evaluateChildModel = PdFirmEvaluateChild::find()->where(['and',['evaluate_id'=>$evaluateModel->evaluate_id],['!=','evaluate_child_status',PdFirmEvaluateChild::STATUS_DELETE]])->all();
        $a = true;
        if (!empty($evaluateChildModel)) {
            foreach ($evaluateChildModel as $val) {
                $purchaseEvaluateResult = $val->purchaseEvaluate;
                $manageEvaluateResult = $val->manageEvaluate;
                if (!empty($purchaseEvaluateResult) || !empty($manageEvaluateResult)) {
                    $a = false;
                    break;
                }
            }
        }
        if ($a) {
            return true;
        } else{
            return false;
        }
    }

    /**
     * 删除主表数据
     */
    public function actionDeleteMain($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $evaluateModel = PdFirmEvaluate::findOne($id);
            $evaluateChildModel = PdFirmEvaluateChild::find()->where(['and',['evaluate_id'=>$evaluateModel->evaluate_id],['!=','evaluate_child_status',PdFirmEvaluateChild::STATUS_DELETE]])->all();
            $evaluateModel->evaluate_status = PdFirmEvaluate::EVALUATE_DELETE;
            if (!$evaluateModel->save()) {
                throw new \Exception("厂商评鉴主表数据删除失败！");
            }
            if (!empty($evaluateChildModel)) {
                foreach($evaluateChildModel as $val) {
                    $val->evaluate_child_status = PdFirmEvaluateChild::STATUS_DELETE;
                    if (!$val->save()) {
                        throw new \Exception("厂商评鉴子表数据删除失败！");
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 删除子表判断
     */
    public function actionDeleteChildJudge($id)
    {
        $evaluateChildModel = PdFirmEvaluateChild::findOne($id);
        $purchaseEvaluateResult = $evaluateChildModel->purchaseEvaluate;
        $manageEvaluateResult = $evaluateChildModel->manageEvaluate;
        if (!empty($purchaseEvaluateResult) || !empty($manageEvaluateResult)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 删除子表数据
     */
    public function actionDeleteChild($id)
    {
        $evaluateChildModel = PdFirmEvaluateChild::findOne($id);
        $evaluateChildModel->evaluate_child_status = PdFirmEvaluateChild::STATUS_DELETE;
        if ($evaluateChildModel->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }
}