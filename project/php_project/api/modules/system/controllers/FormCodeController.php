<?php

namespace app\modules\system\controllers;

use app\modules\common\models\BsForm;
use app\modules\common\models\BsFormCodeMax;
use app\modules\common\models\BsFormCodeRule;
use Yii;
use app\controllers\BaseActiveController;
use app\modules\system\models\SysDisplayList;
use app\modules\system\models\SysDisplayListChild;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * TradConditionController implements the CRUD actions for BsTradConditions model.
 */
class FormCodeController extends BaseActiveController
{
    public $modelClass = 'app\modules\common\models\FormCode';

    public function actionEdit($code,$type){
        $model=$this->codeRule($code,$type);
        if(empty($model)){
            $model=New BsFormCodeRule();
        }else{
            $codeMax = BsFormCodeMax::findOne(['code_rule_id'=>$model->rule_id]);
        }
        if(empty($codeMax)){
            $codeMax = New BsFormCodeMax();
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->form_id=$code;
            $model->code_type=$type;
            $model->load(Yii::$app->request->post());
            if(!$model->save()) {
                throw new \Exception("保存失败".current($model->getFirstErrors()));
            }
            $codeMax->code_rule_id = $model->rule_id;
            $codeMax->current_number = $model->serial_number_start;
            if(!$codeMax->save()){
                throw new \Exception("保存失败".current($codeMax->getFirstErrors()));
            }
            $form=BsForm::find()->where(['form_id'=>$code])->andWhere(['code_type'=>$type])->one();
            $transaction->commit();
            return $this->success($form->form_name);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }
    /**
     * Lists all BsTradConditions models.
     * @return mixed
     */
    public function actionGetTree()
    {
        return BsForm::getTree();
    }

    public function actionFormCodeInfo($code,$type=10){
        return $this->codeRule($code,$type);
    }

    private function codeRule($code,$type=10){
        return BsFormCodeRule::find()->where(['form_id'=>$code])->andWhere(['code_type'=>$type])->one();
    }


}