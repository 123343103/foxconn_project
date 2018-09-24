<?php

namespace app\modules\system\controllers;

use app\modules\system\models\SysDisplayRule;
use Yii;
use app\controllers\BaseActiveController;
use app\modules\system\models\SysDisplayList;
use app\modules\system\models\SysDisplayListChild;

/**
 * TradConditionController implements the CRUD actions for BsTradConditions model.
 */
class DisplayListController extends BaseActiveController
{
    public $modelClass = 'app\modules\common\models\SysDisplay';


    /**
     * 编辑动态列
     * @return array
     */
    public function actionEdit(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            foreach($post['field'] as $val){
                //角色不为空进入
                if(!empty($post['rule'])){
                    $child = SysDisplayRule::find()->where(['rule_code'=>$post['rule']])->andWhere(['field_id' => $val['field_id']])->one();
                    if(empty($child)){
                        $child=new SysDisplayRule();
                    }
                    $child->rule_code=$post['rule'];
                    $child->ddi_sid=$post['ddi_sid'];
                    $child->field_field=$val['field_field'];
                    $child->field_display=isset($val['field_display'])?SysDisplayRule::STATUS_DEFAULT:SysDisplayRule::STATUS_DELETE;
                }else{
                    $child=SysDisplayListChild::findOne(['field_id'=> $val['field_id']]);
                    $child->field_display= isset($val['field_display'])?SysDisplayListChild::STATUS_DEFAULT:SysDisplayListChild::STATUS_DELETE;
                }
                    $child->field_title  = $val['field_title'];
                    $child->field_width  = $val['field_width'];
                    $child->field_index  = $val['field_index'];
                if (!$child->save()) {
                    throw new \Exception("发生错误");
                }
            }
            $transaction->commit();
            $ddiName=SysDisplayList::findOne(['ddi_sid'=>$child->ddi_sid]);
            return $this->success($ddiName->ddi_sname);
        }catch (\Exception $e){
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
        return SysDisplayList::getTree();
    }

    /**
     * 通过ID获取字段
     * @param $url
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetField($id,$rule)
    {
        if(!empty($rule)){
            $model=SysDisplayRule::find()->where(['ddi_sid'=>$id])->andWhere(['rule_code'=>$rule])->orderBy('field_index ASC')->all();
        }
        if(empty($model) || empty($rule)){
            $model=SysDisplayListChild::find()->where(['ddi_sid'=>$id])->orderBy('field_index ASC')->all();
        }
        return $model;
    }

    /**
     * 通过URl获取字段
     * @param $url
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetUrlField($url,$user,$type)
    {
        return SysDisplayList::getColumns($url,$user,$type);
    }
}