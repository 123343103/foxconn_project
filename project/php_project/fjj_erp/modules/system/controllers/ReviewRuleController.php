<?php
/**
 * @TODO
 * 业务审核规则控制器
 * User:F3859386
 */
namespace app\modules\system\controllers;
use app\models\User;
use app\controllers\BaseController;
use app\modules\app\app;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;


class ReviewRuleController extends BaseController{
    
    private $_url = "system/review-rule/";
    /**
     * 审核流列表页
     * @return string
     */
    public function actionIndex(){
        $url = $this->findApiUrl() . $this->_url . "rule-list";
        $model = Json::decode($this->findCurl()->get($url));
        return $this->render("rule",[
            'str'=>$model
        ]);
    }

    /**
     * 编辑
     * @param $id
     * @return string
     */
    public function actionEdit($id){
//        $url = $this->findApiUrl() . $this->_url . "edit";
        $orgId = Yii::$app->request->get('orgId');
        $model = $this->findModel($id,$orgId);
        $dataProvider  = Json::encode($this->findDataModel($model->review_rule_id));
        $data['conditions']=$this->findConditions($model->conditions);
        $data['logic']=$this->findLogic();

        $data['users']=$this->findReviewUsers($model->review_url);
        $data['roles']=$this->findReviewRoles($model->review_url);
        $url = $this->findApiUrl() . $this->_url . "edit?id=" . $model->review_rule_id . '&business_type_id=' . $id;
        $res = json::decode($this->findCurl()->get($url));
//        dumpE(json_decode($data));
        if($post = Yii::$app->request->post()){
            $post['id']=$id;
            $post['org'] = $orgId;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                return Json::encode(['msg' => "编辑审核需求成功", "flag" => 1]);
            } else {
                return Json::encode(['msg' => "發生未知錯誤，新增失敗", "flag" => 0]);
            }
        }
        return $this->renderAjax("edit",[
            'model'=>$model,
            'dataProvider'  =>$dataProvider,
            'data'  =>Json::encode($data),
            'orgList' => $res['org'],
            'business_code' => $res['business_code'],
            'orgId' => $orgId
        ]);
    }

    /**
     * 审核类型
     */
    public function actionCreateType()
    {
        $post = Yii::$app->request->post();
        $url = $this->findApiUrl() . $this->_url . "create-type";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $data = Json::decode($curl->post($url));
        if ($data['status'] == 1) {
            return Json::encode(['msg' => "添加成功", "flag" => 1, "url" => Url::to(['rule'])]);
        } else {
            return Json::encode(['msg' => "發生未知錯誤，新增失敗", "flag" => 0]);
        }
    }

//    public function actionDeleteRule()
//    {
//        $get=Yii::$app->request->get();
//        $url =$this->findApiUrl().$this->_url."delete-rule?id=".$get['id'];
//        $data = Json::decode($this->findCurl()->get($url));
//        if ($data['status'] == 1) {
//            return Json::encode(['msg' => "删除成功", "flag" => 1, "url" => Url::to(['rule'])]);
//        } else {
//            return Json::encode(['msg' => "發生未知錯誤，新增失敗", "flag" => 0]);
//        }
//    }

    /**
     * 审批条件选择
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    private function findConditions($conditions){
        $selects = [];
        if($conditions){
            foreach ($conditions as $key => $val ){
                $selects[]= [
                    "id"=> $val->condition_id,
                    "text"=>$val->form_column_desc
                ];
            }
        }
        $select[]=[
            "id"=> "",
            "text"=>"无"
        ];
        $selects=array_merge($select,$selects);
        return $selects;
    }
    /**
     * 审批条件选择
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    private function findLogic(){
        $selects= [
//            [
//                "id"=> "0",
//                "text"=>"wu"
//            ],
            [
                "id"=> "eq",
                "text"=>"=(等于)"
            ],
            [
                "id"=> "egt",
                "text"=>"≥(大于等于)"
            ],
            [
                "id"=> "elt",
                "text"=>"≤(小于等于)"
            ],
            [
                "id"=> "gt",
                "text"=>">(大于)"
            ],
            [
                "id"=> "lt",
                "text"=>"<(小于)"
            ],
        ];
        $select[]=[
            "id"=> "",
            "text"=>"无"
        ];
        $selects=array_merge($select,$selects);
        return $selects;
    }



    /**
     * 获取有审批权限的用户
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    private function findReviewUsers($url){
//        $model = $this->findModel($id);
//        $auth = Yii::$app->authManager;

        $users = User::findAll(["user_status"=>User::STATUS_ACTIVE]);
//        foreach ($users as $key => $val){
//            if (Yii::$app->user->identity->is_supper != 1) {
//                if(!$auth->checkAccess($val->user_id, $url)){
//                    unset($users[$key]);
//                }
//            }
//        }
        $selects = [];
        foreach ($users as $key => $val ){
            $selects[] =['id'=>$val->user_id,"text"=>Html::encode($val->staff->staff_name)];
        }
        $select[]=[
            "id"=> "",
            "text"=>"无"
        ];
        $selects=array_merge($select,$selects);
        return $selects;
    }
    /**
     * 获取有审核该单据权限的角色
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    private function findReviewRoles($url){
        $auth = Yii::$app->authManager;
//        $permission = $auth->getPermission($url);
        $roles = $auth->getRoles();
//        foreach($roles as $key => $val){
//            if( !$auth->hasChild($val,$permission)){
//                unset($roles[$key]);
//            }
//        };
        $selects =[];
        foreach ($roles as $key => $val){
            $selects[] = ['id'=>$val->name,"text"=>Html::encode($val->title)];

        }
        $select[]=[
            "id"=> "",
            "text"=>"无"
        ];
        $selects=array_merge($select,$selects);
        return $selects;
    }

//    /**
//     *
//     */
//    public function actionColumn($id){
//        $url = $this->findApiUrl().$this->_url."column?id=".$id;
//        $data = $this->findCurl()->get($url);
//        dump($data);
//    }
    /**
     * 获取模型
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    private function findModel($id,$orgId){
        $url = $this->findApiUrl().$this->_url."model?id=".$id."&orgId=".$orgId;
        $model = $this->findCurl()->get($url);
        if(!$model){
//            throw new NotFoundHttpException("页面未找到");
        }
        return json_decode($model);
    }
    private function findDataModel($id){
        $url = $this->findApiUrl().$this->_url."get-data?id=".$id;
        $model = $this->findCurl()->get($url);
        if(!$model){
            throw new NotFoundHttpException("页面未找到");
        }
        return json_decode($model);
    }

    public function actionEdit2($id){
//        $url = $this->findApiUrl() . $this->_url . "edit";
        $orgId = Yii::$app->request->get('orgId');
        $model = $this->findModel($id,$orgId);
        $dataProvider  = Json::encode($this->findDataModel($model->review_rule_id));
        $data['conditions']=$this->findConditions($model->conditions);
        $data['logic']=$this->findLogic();

        $data['users']=$this->findReviewUsers($model->review_url);
        $data['roles']=$this->findReviewRoles($model->review_url);
        $url = $this->findApiUrl() . $this->_url . "edit?id=" . $model->review_rule_id . '&business_type_id=' . $id;
        $res = json::decode($this->findCurl()->get($url));
//        dumpE(json_decode($data));
        if($post = Yii::$app->request->post()){
            $post['id']=$id;
            $post['org'] = $orgId;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                return Json::encode(['msg' => "编辑审核需求成功", "flag" => 1]);
            } else {
                return Json::encode(['msg' => "發生未知錯誤，新增失敗", "flag" => 0]);
            }
        }
//        dumpE(json::decode($dataProvider));
        return $this->renderAjax("edit2",[
            'model'=>$model,
            'dataProvider'  =>$dataProvider,
            'data'  =>Json::encode($data),
            'orgList' => $res['org'],
            'business_code' => $res['business_code'],
            'orgId' => $orgId
        ]);
    }

    public function actionSelectUser()
    {
        $parans = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "select-user?" . http_build_query($parans);
        $user = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $user;
        }
//        dumpE(Json::decode($model));
        return $this->renderAjax('select-user', ['user'=>$user]);
    }
}