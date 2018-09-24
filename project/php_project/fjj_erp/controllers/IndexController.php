<?php
namespace app\controllers;

use app\models\EditPwdForm;
use app\modules\system\models\AuthTitle;
use yii;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * 首页控制器和提示页面，该页面操作无需权限验证
 * F3858995
 * 2016.9.14
 */
class IndexController extends BaseController
{
    public function beforeAction($action)
    {
        $this->ignorelist = array_merge($this->ignorelist, [
            "/index/desktop",
            "/index/validate-form"
        ]);
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        if (isset(Yii::$app->user->identity->first_login) && Yii::$app->user->identity->first_login == 0) {// 第一次登陆必须修改密码
            return $this->redirect(['/login/login-out']);
        }
        $size = 11;
        $count = $this->getCount();
        $authority = $this->getFunctions();
        $available = $this->getAvailable();
        $informCount = $this->getInformCount();
        $SurveyCount = $this->getSurveyCount();
        $rptCount=$this->getRptCount();
        $ava = [];
        $j = 0;
        if (!empty($available)) {
            foreach ($available as $k => $v) {
                foreach ($v as $kk => $vv) {
                    $j += 1;
                    $ava[ceil($j / $size)][] = $vv;
                }
            }
        }
        $pageTotal = ceil($j / $size);
        $lastPageCount = $j % $size;
//        dumpE($lastPageCount);
//        dumpE($this->getCustomDisabledDesktop());
        return $this->render("index", [
            'count' => $count,
            'authority' => $authority,
            'available' => $ava,
            'pageTotal' => $pageTotal,
            'informCount' => $informCount,
            'lastPageCount' => $lastPageCount,
            'SurveyCount' => $SurveyCount,
            'rptCount'=>$rptCount
        ]);
    }

    /**
     * 无权限提示页面
     * @return string
     */
    public function actionForbidden()
    {
        return $this->render('forbidden');
    }

    /**
     * 修改密码
     * @return string
     * @throws yii\base\Exception
     */
    public function actionEditPwd()
    {
        $model = new EditPwdForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->editPwd()) {
                return yii\helpers\Json::encode(['msg' => "修改密码成功", "flag" => 1, "url" => yii\helpers\Url::to(["index"])]);
            }
            return yii\helpers\Json::encode(['msg' => "修改密码失败", "flag" => 0]);
        }
        $first = Yii::$app->request->get()['first'];
        if ($first) {
            $this->layout = '@app/views/layouts/ajax';
        }
        return $this->render('edit-pwd', ['model' => $model, 'first' => $first]);
    }

    /**
     * AJAX验证修改密码表单
     * @return array
     */
    public function actionValidateForm()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new EditPwdForm();   //这里要替换成自己的模型类
        $model->load(Yii::$app->request->post());
        return \yii\widgets\ActiveForm::validate($model);
    }

    /**
     * 自定义我的桌面
     *
     */
    public function actionDesktop()
    {
        $authority = $this->getFunctions();
        $select = $this->getCustomSelectDesktop();
        if (Yii::$app->request->isAjax) {
            $url = $this->findApiUrl() . 'system/desktop/save-custom-desktop';
            $postData = Yii::$app->request->post();
            $postData['uid'] = Yii::$app->user->id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $res = json_decode($curl->post($url), true);
            if ($res['flag'] == 1) {
                return Json::encode(['msg' => $res['mesg'], "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $res['mesg'], "flag" => 0]);
            }
        } else {
            return $this->render("desktop", [
                'authority' => $authority,
                'select' => $select,
            ]);
        }
    }

    /**
     * 自定义我的桌面
     *
     */
    public function actionEditIcon()
    {
        $authority = $this->getAllFunctions();
        if (Yii::$app->request->isAjax) {
            $postData = Yii::$app->request->post();
            $transaction = yii::$app->db->beginTransaction();
            try {
                foreach ($postData["AuthTitle"] as $key => $val) {
                    $authtitle = AuthTitle::find()->where(['action_url' => $val['action_url']])->one();
                    if ($authtitle) {
                        $authtitle->action_icon = $val['action_icon'];
                        if (!$authtitle->save()) {
                            throw new \Exception(json::encode($authtitle->errors));
                        }
                    }
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                return Json::encode(['msg' => "发生未知错误，修改失败:<br/>" . $e->getMessage(), "flag" => 0]);
            }
            $transaction->commit();
            return Json::encode(['msg' => "修改成功", "flag" => 1, "url" => Url::to(['index'])]);
        } else {
            return $this->render("edit-icon", [
                'authority' => $authority,
            ]);
        }
    }

    /**
     * 选择图标
     *
     */
    public function actionSelectIcon($id, $titleId)
    {
        $this->layout = '@app/views/layouts/ajax';
        $hostdir = Yii::$app->basePath . "/web/img/desktop-icon";
        $filesnames = scandir($hostdir);
//        dumpE($filesnames);die();
        return $this->render("select-icon", [
            'filesnames' => $filesnames,
            'id' => $id,
            'titleId' => $titleId
        ]);
    }

    //待审核
    public function getCount()
    {
        if (empty(Yii::$app->user->identity->id)) {
            return null;
        }
        $url = $this->findApiUrl() . "/system/verify-record/count?id=" . Yii::$app->user->identity->id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    //我的问卷
    public function getSurveyCount()
    {
        $org = $this->getOrgInfo();
        $staff_code = Yii::$app->user->identity->staff->staff_code;
        $oid = $org["organization_id"];
        $url = $this->findApiUrl() . "/hr/question-survey/survey-count?oid=" . $oid . "&staff_coed=" . $staff_code;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    //报表统计
    public function getRptCount(){
        $uid=Yii::$app->user->identity->user_id;
        $url=$this->findApiUrl()."/rpt/my-rpt/rpt-count?uid=".$uid;
        return $this->findCurl()->get($url);
    }

    // 获取所有有权限的功能项
    private function getFunctions()
    {
        $uid = \Yii::$app->user->identity->id;
        $authority = [];
        $functions = AuthTitle::find()->asArray()->all();
        if (Yii::$app->user->identity->is_supper != 1) {
            $permissions = array_keys(\Yii::$app->authManager->getPermissionsByUser($uid));
            foreach ($functions as $key => $val) {
                if (in_array($val["action_url"], $permissions) && empty(strstr($val['action_title'], '-'))) {
                    $authority[$val['action_parent']][] = $val;
                }
            }
        } else {
            foreach ($functions as $key => $val) {
                if (empty(strstr($val['action_title'], '-'))) {
                    $authority[$val['action_parent']][] = $val;
                }
            }
        }
        unset($authority["移动端菜单"]);//去除移动端菜单栏
        return $authority;
    }

    // 获取所有的功能项
    private function getAllFunctions()
    {
        $functions = AuthTitle::find()->asArray()->all();
        $authority = [];
        foreach ($functions as $key => $val) {
            if (empty(strstr($val['action_title'], '-'))) {
                $authority[$val['action_parent']][] = $val;
            }
        }
        unset($authority["移动端菜单"]);//去除移动端菜单栏
        return $authority;
    }

    // 获取所有被自定义显示的功能项
    public function getCustomSelectDesktop()
    {
        $uid = Yii::$app->user->id;
        $url = $this->findApiUrl() . "/system/desktop/get-custom-select-desktop?uid=$uid";
        $dsb = Json::decode($this->findCurl()->get($url));
        $select = [];
        if (!empty($dsb)) {
            foreach ($dsb as $k => $v) {
                $select[] = $v['action_url'];
            }
        }
        return $select;
    }

    // 获取所有可显示的功能项
    public function getAvailable()
    {
        $selected = $this->getCustomSelectDesktop();
        $permission = $this->getFunctions();
        $available = [];
        if (!empty($selected)) {
            foreach ($permission as $k => $v) {
                foreach ($v as $kk => $vv) {
                    if (in_array($vv['action_url'], $selected) && empty(strstr($vv['action_title'], '-'))) {
                        $available[$k][] = $vv;
                    }
                }
            }
        } else {
            foreach ($permission as $k => $v) {
                foreach ($v as $kk => $vv) {
                    if (empty(strstr($vv['action_title'], '-'))) {
                        $available[$k][] = $vv;
                    }
                }
            }
            $available = array_slice($available, 0, 1, true);
        }
        return $available;
    }

    //我的通知
    public function getInformCount()
    {
        if (empty(Yii::$app->user->identity->id)) {
            return null;
        }
        $url = $this->findApiUrl() . "/system/verify-record/inform-count?id=" . Yii::$app->user->identity->staff_id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function actionTest()
    {
        return $this->render("test");
    }

    //获取登录人的部门信息
    public function getOrgInfo()
    {
        if (empty(Yii::$app->user->identity->staff->organization_code)) {
            return null;
        }
        $url = $this->findApiUrl() . "/hr/organization/get-org-info?organization_code=" . Yii::$app->user->identity->staff->organization_code;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
}
