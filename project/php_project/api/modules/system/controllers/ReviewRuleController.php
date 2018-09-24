<?php
namespace app\modules\system\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsReviewCondition;
use app\modules\common\models\BsReviewRule;
use app\modules\common\models\BsReviewRuleChild;
use app\modules\hr\models\HrOrganization;
use app\modules\system\models\search\ReviewSelectUserSearch;
use app\modules\system\models\search\UserSearch;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * F3859386
 * 2016/12/22
 * 业务审核规则控制器
 */
class ReviewRuleController extends BaseActiveController{

    public $modelClass = 'app\modules\ptdt\models\BsReviewRule';

    public function actionIndex(){
        $data = BsReviewRule::getAll();
        return $data;
    }

    /**
     * @param $id
     * @return string
     */
    public function actionEdit(){
        if(Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (empty($post['org'])) {
                    $ruleModels=BsReviewRule::find()->where(['and',['business_type_id'=>$post['id']],['is','org',null]])->one();
                } else {
                    $ruleModels=BsReviewRule::find()->where(['and',['business_type_id'=>$post['id']],['=','org',$post['org']]])->one();
                }
                if (!$ruleModels) {
                    $ruleModels = new BsReviewRule();
                }
                $ruleModels->business_code = $post['business_code'];
                $ruleModels->business_type_id = $post['id'];
                $ruleModels->org = empty($post['org']) ? null : $post['org'];
                if(!empty($post['business_status'])){
                    $ruleModels->business_status=BsReviewRule::STATUS_EDITABLE;
                }else{
                    $ruleModels->business_status=BsReviewRule::STATUS_DEFAULT;
                }
                $ruleModels->save();
                //删除
                $del=array_filter(explode(",",$post['del']));
                if(!empty($del)){
                    foreach($del as $val){
                        $childModels=BsReviewRuleChild::find()->where(['rule_child_id'=>$val])->one();
                        $childModels->delete();
                        BsReviewCondition::deleteAll(['rule_child_id'=>$val]);
                    }
                }
                $index=1;
                $rules = (!empty($post['rule']) && is_array($post['rule'])) ? $post['rule'] : [];
                foreach ($rules as $val){
                    $childModel=BsReviewRuleChild::find()->where(['rule_child_id'=>$val['id']])->one();
                    $childModel=!empty($childModel)?$childModel: new BsReviewRuleChild();
                    $childModel->review_rule_id=$ruleModels->review_rule_id;
                    $childModel->rule_child_index=$index;
                    $index++;
                    if($val['review_type'] == 'user' && !empty($val['user'])){
                        $childModel->review_user_id = $val['user'];
                        $childModel->agent_one_id   = $val['agentOne'];
                        $childModel->agent_two_id   = $val['agentTwo'];
                        $childModel->review_role_id = null;
                        $childModel->save();
                    }else if ($val['review_type'] == 'role' && !empty($val['role'])) {
                        $childModel->review_user_id = null;
                        $childModel->agent_one_id   = null;
                        $childModel->agent_two_id   = null;
                        $childModel->review_role_id=$val['role'];
                        $childModel->save();
                    }
                    $cid=$childModel->rule_child_id;
                    //条件
                    foreach ($val['conditions'] as $keys=>$value){
                        $conditionModel=new BsReviewCondition();
                        if(!empty(array_filter($value))){
                            if (!empty($value['id'])){
                                $conditionModel=BsReviewCondition::find()->where(['condition_id'=>$value['id']])->one();
                                //若条件为空,删除
                                if(empty($value['name']) || empty($value['logic']) || empty($value['para'])){
                                    $conditionModel->delete();
                                    continue;
                                }
                            }
                            $conditionModel->rule_child_id=$cid;
                            $conditionModel->column=$value['name'];
                            $conditionModel->condition_logic=$value['logic'];
                            $conditionModel->condition_value=$value['para'];
                            $conditionModel->rule_id=$ruleModels->review_rule_id;
                            $conditionModel->save();
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
        $typeModels = new BsBusinessType();
        $businessTypeId = Yii::$app->request->get('business_type_id');
        $data['business_code'] = $typeModels->find()->select('business_code')->where(['business_type_id'=>$businessTypeId])->one();
        $data['org'] = HrOrganization::getOrgAllLevel(0);
        return $data;
    }


    /**
     * 创建审核类型
     */
    public function actionCreateType()
    {
        $model=new BsReviewRule();
        $post = Yii::$app->request->post();
        $model->load($post);
        $result=$model->save();
        if($result){
            return $this->success();
        }
        return $this->error();
    }
    
    public function actionDeleteRule($id)
    {
        $model=BsReviewRule::find()->where(['review_rule_id'=>$id])->one();
        $model->business_status=BsReviewRule::STATUS_DELETE;
        $result=$model->save();
        if($result){
            return $this->success();
        }
        return $this->error();
    }
    /**
     * 获取审核数据
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetData($id){
        $childModel = new UserSearch();
        $data = $childModel->selectReviewData($id)->getModels();
//        return $childModel=BsReviewRuleChild::find()->where(['review_rule_id'=>$id])->select('rule_child_id,review_rule_id,rule_child_index,review_user_id,review_role_id,agent_one_id,agent_two_id')->asArray()->all();
        foreach ($data as $key=>$val){
            $data[$key]['conditionModel']=BsReviewCondition::find()->where(['rule_child_id'=>$val['rule_child_id']])->andWhere(['rule_id'=>$id])->asArray()->all();
        }
        return $data;
    }


    public function actionRuleList(){
        return BsBusinessType::getTree();


    }
    public function actionModel($id,$orgId){
        return $this->getModel($id,$orgId);
    }


    private function getModel($id,$orgId){
        return BsReviewRule::getRule($id,$orgId);
    }

    // 选择用户 http://localhost/php_project/api/web/system/review-rule/select-user?UserSearch[searchKeyword]=root
    public function actionSelectUser()
    {
        $searchModel = new UserSearch();
        $queryParams=Yii::$app->request->queryParams;
        $dataProvider = $searchModel->selectUser($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }
}