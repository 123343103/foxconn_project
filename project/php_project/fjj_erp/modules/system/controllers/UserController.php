<?php

namespace app\modules\system\controllers;

use app\controllers\BaseController;
use app\models\User;
use app\modules\common\models\BsCompany;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;


/**
 * User控制器
 */
class UserController extends BaseController
{
    /**
     * index控制器
     * @return mixed
     */

    private $_url = "system/user/";  //对应api控制器URL

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
//        dumpE($dataProvider);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        return $this->render('index', [
            'search' => $queryParam['UserSearch']]);
    }


    /**
     * 新增控制器
     */
    public function actionCreate()
    {
        $model = new User();
        if ($model->load($post = Yii::$app->request->post())) {
            $pwd = "123456";//默认密码
            //如果爲空则默认123456
            //如果爲空则不修改密码
            $model->user_pwd = empty($post['User']['user_pwd']) ? Yii::$app->security->generatePasswordHash($pwd) : Yii::$app->security->generatePasswordHash($post['User']['user_pwd']);
            $roles = isset($post['roles']) ? $post['roles'] : [];
            if ($model->saveUser($roles)) {
                SystemLog::addLog('用户管理修改用户:' . $model->user_account);
                return Json::encode(['msg' => "新增用户成功,默认密码为：" . $pwd . "，请及时修改密码！", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        } else {
            $roles = Yii::$app->authManager->getRoles();
            $company = BsCompany::find()->where(['company_status' => BsCompany::STATUS_DEFAULT])->all();
            $model->user_status = User::STATUS_ACTIVE;
            return $this->render('create', [
                'model' => $model,
                'roles' => $roles,
                'company' => $company,
            ]);
        }
    }

    //Ajax验证账号是否重複
    public function actionAjaxValidation()
    {
        $model = new User();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->getRequest()->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\bootstrap\ActiveForm::validate($model);
        }
    }

    /**
     * 更新控制器
     */
    public function actionUpdate($id)
    {
        $post = Yii::$app->request->post();
        $model = $this->findModel($id);
        if ($model->load($post)) {
            //如果爲空则不修改密码
            $model->user_pwd = empty($post['User']['user_pwd']) ? $model->user_pwd : Yii::$app->security->generatePasswordHash($post['User']['user_pwd']);
            $model->is_supper = $post['User']['is_supper'];
            $roles = isset($post['roles']) ? $post['roles'] : [];
            if ($model->saveUser($roles)) {
                SystemLog::addLog('用户管理修改用户:' . $model->user_account);
                return Json::encode(['msg' => "更新用户成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，更新用户失败", "flag" => 0]);
            }
        } else {
            $roles = Yii::$app->authManager->getRoles();
            $permission = Yii::$app->authManager->getRolesByUser($model->user_id);
            $company = BsCompany::find()->where(['company_status' => BsCompany::STATUS_DEFAULT])->all();
            $url = $this->findApiUrl() . $this->_url . "get-staff?staff_id=" . $model->staff_id;
            $staff = json_decode($this->findCurl()->get($url), true);
            return $this->render('update', [
                'model' => $model,
                'roles' => $roles,
                'staff' => $staff,
                'permission' => $permission,
                'company' => $company,
            ]);
        }
    }

    /**
     * 密码重置控制器
     */
    public function actionResetPassword($id)
    {
        $pwd = "123456";
        $model = $this->findModel($id);
        //如果爲空则不修改密码
        $model->user_pwd = Yii::$app->security->generatePasswordHash($pwd);
        $model->first_login = '0';
        if ($model->save()) {
            SystemLog::addLog('用户管理密码重置:' . $model->user_account);
            return Json::encode(['msg' => "重置密码成功为：" . $pwd, "flag" => 1]);
        } else {
            return Json::encode(['msg' => "发生未知错误，更新用户失败", "flag" => 0]);
        }
    }

    /**
     * 删除控制器
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->is_supper == 1) {
            return Json::encode(["msg" => "删除失败,超级管理员无法删除", "flag" => 0]);
        }
        $model->user_status = User::STATUS_DELETE;
        if ($model->save()) {
            SystemLog::addLog('用户管理删除用户:' . $model->user_account);
            return Json::encode(["msg" => "删除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "删除失败", "flag" => 0]);
        }
    }

    public function actionDataAuthorization($id, $tid)
    {
        return $this->render('data-authorization', ['user_id' => $id, 'tid' => $tid]);
    }

    public function actionTree($ass_id, $org_id)
    {
        $url = $this->findApiUrl() . "hr/organization/get-tree?ass_id=" . $ass_id . '&org_id=' . $org_id;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $dataProvider;

    }

    public function actionSave()
    {
        $ass_id = $_POST['ass_id'];
        $org_id = $_POST['rid'];
        $typeid = $_POST['tid'];
        $url = $this->findApiUrl() . $this->_url . "save";
        $postData = Yii::$app->request->post();
        $postData['type_id'] = $typeid;
        $postData['ass_id'] = $ass_id;
        $postData['org_id'] = $org_id;
        $postData['opper'] = Yii::$app->user->identity->staff_id;//操作人
        $postData['opp_date'] = date('Y-m-d H:i:s', time());
        $postData['opp_ip'] = Yii::$app->request->getUserIP();
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = Json::decode($curl->post($url));
        return $data["status"];
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
