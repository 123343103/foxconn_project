<?php
namespace app\modules\system\controllers;

use app\controllers\BaseController;

use app\modules\system\models\AuthItem;
use app\modules\system\models\AuthItemChild;
use app\modules\system\models\AuthTitle;
use app\modules\system\models\search\AuthoritySearch;
use app\modules\system\models\SystemLog;
use yii;
use yii\helpers\Json;
use yii\helpers\Url;


/*
 * 权限控制器
 * F3859386
 * 2016/10/7
 */
class AuthorityController extends BaseController
{
    /**
     * role列表页
     * @return string
     */
    public function actionRoleIndex()
    {
        $searchModel = new AuthoritySearch();
        $queryParam=Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParam);
        $model = $dataProvider->getModels();
        foreach ($model as $key => &$val){
            if(!empty(AuthItemChild::findOne(['parent'=>$val['name']]))){
                $isEnable=true;
            }else{
                $isEnable=false;
            }
            $model[$key]['isEnable']  =$isEnable;
            $val["code"]= '<a href="'.Url::to(['view','name'=>$val['name']]).'">'. yii\helpers\Html::encode($val["code"]) .'</a>';
            $val["title"]= yii\helpers\Html::encode($val["title"]);
            $val["description"]= yii\helpers\Html::encode($val["description"]);
            $val["rule_name"]= yii\helpers\Html::encode($val["rule_name"]);
            $val["data"]= yii\helpers\Html::encode($val["data"]);
        }
        $list['rows']=$model;
        $list['total'] = $dataProvider->totalCount;
        if (Yii::$app->request->isAjax) {
            return Json::encode($list);
        }
        return $this->render('role-index',[
            'search'=>$queryParam['AuthoritySearch'
            ]]);
    }

    public function actionView($name)
    {
        $model = AuthItem::find()->where(["name" => $name])->one();
        $authority = $this->getFunctions();
        $auth = Yii::$app->authManager;
        $permissions = $auth->getPermissionsByRole($model->name);
        return $this->render("view", ['model' => $model, 'authority' => $authority, "permissions" => $permissions]);
    }

    /**
     * 修改
     * @param $name
     * @return string
     */
    public function actionEdit($name)
    {
        $model = AuthItem::find()->where(["name" => $name])->one();
        if ($model->load($post = Yii::$app->request->post())) {
            return $this->saveRole($model);
        }
        $authority = $this->getFunctions();
        $auth = Yii::$app->authManager;
        $permissions = $auth->getPermissionsByRole($model->name);
        return $this->render("edit", ['model' => $model, 'authority' => $authority, "permissions" => $permissions]);
    }

    /**
     * 新增
     * @return string
     */
    public function actionAdd()
    {
        $model = new AuthItem();
        if ($model->load($post = Yii::$app->request->post())) {
            return $this->saveRole($model);
        } else {
            $authority = $this->getFunctions();
            return $this->render('add', ['model' => $model, 'authority' => $authority]);
        }


    }

    /**
     * 获取权限功能
     * @return array
     */
    private function getFunctions()
    {
        $functions = AuthTitle::find()->asArray()->all();
        $authority = [];
        foreach ($functions as $key => $val) {
            $authority[$val['action_parent']][] = $val;
        }
        return $authority;
    }

    /**
     * 为role赋权
     * @param $model
     * @return string
     * @throws \yii\db\Exception
     */
    private function saveRole($model)
    {
        $transaction = Yii::$app->db->beginTransaction();
        if(!$model->name){
            $model->name = strval(time());
        }

        if ($model->save() !== false) {
            $post = Yii::$app->request->post();
            $authItemChildren = isset($post['AuthItem']['AuthItemChildren']) ? $post['AuthItem']['AuthItemChildren'] : [];
            $result = AuthItem::addPerForRole($model, $authItemChildren);

            if (!$result) {
                $transaction->rollBack();
                return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
            }
            $transaction->commit();
            SystemLog::addLog('操作角色管理:'.$model->title);
            return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['role-index'])]);
        } else {
            $transaction->rollBack();
            return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
        }
    }

    public function actionDelete($id)
    {
        $model = AuthItem::findOne($id)->delete();
        if ($model) {
            return Json::encode(['msg' => "删除成功", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "删除失败", "flag" => 0]);
        }
    }

    public function actionAjaxValidation()
    {
        $model = new AuthItem();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->getRequest()->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\bootstrap\ActiveForm::validate($model);
        }
    }
}