<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/11/21
 * Time: 上午 10:18
 */
namespace app\modules\system\controllers;

use app\controllers\BaseController;
use app\models\User;
use app\modules\common\models\BsCompany;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class UserManagementController extends BaseController
{
    private $_url = "system/user-management/";  //对应api控制器URL

    //首页
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
        $bspnamelist = $this->actionNamelist();
//        print_r($bspnamelist);
        return $this->render('index', [
            'search' => $queryParam['UserSearch'],
            'data' => $bspnamelist
        ]);
    }

    //获取用户类型
    public function actionNamelist()
    {
        $urls = $this->findApiUrl() . $this->_url . "bsp-name-list";
        return Json::decode($this->findCurl()->get($urls));
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

    //新增
    public function actionAdd()
    {
        $model = new User();
        $url = $this->findApiUrl() . $this->_url . "roles";
        $roles = Json::decode($this->findCurl()->get($url));
        $urls_f = $this->findApiUrl() . $this->_url . "bs-factory-list";
        $factory = Json::decode($this->findCurl()->get($urls_f));
//        $company = BsCompany::find()->where(['company_status' => BsCompany::STATUS_DEFAULT])->all();
//        $model->user_status = User::STATUS_ACTIVE;
        $bspnamelist = $this->actionNamelist();
//            print_r($roles);
        return $this->render('add', [
            'model' => $model,
            'roles' => $roles,
//            'company' => $company,
            'bspnamelist' => $bspnamelist,
            'factory' => $factory
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $urls = $this->findApiUrl() . $this->_url . "roles";
        $roles = Json::decode($this->findCurl()->get($urls));

        $urls_f = $this->findApiUrl() . $this->_url . "bs-factory-list";
        $factory = Json::decode($this->findCurl()->get($urls_f));
//        $permission = Yii::$app->authManager->getRolesByUser($model->user_id);
        $url = $this->findApiUrl() . $this->_url . "get-staff?staff_id=" . $model->staff_id;
        $staff = Json::decode($this->findCurl()->get($url));

        $urlrole = $this->findApiUrl() . $this->_url . "roles-dt?id=" . $id;
        $role = Json::decode($this->findCurl()->get($urlrole));

        $urlfactory = $this->findApiUrl() . $this->_url . "factory-dt?id=" . $id;
        $area = Json::decode($this->findCurl()->get($urlfactory));
        $bspnamelist = $this->actionNamelist();
//        print_r($role);
        return $this->render('update', [
            'model' => $model,
            'roles' => $roles,
            'staff' => $staff,
            'bspnamelist' => $bspnamelist,
            'role' => $role,
            'user_id' => $id,
            'factory' => $factory,
            'area' => $area
        ]);
    }

    /**
     * 密码重置控制器
     */
    public function actionResetPassword($id)
    {
        $url = $this->findApiUrl() . $this->_url . "reset-password?id=" . $id . "&update_by=" . Yii::$app->user->identity->staff_id;
        $data = Json::decode($this->findCurl()->get($url));
//        dumpE($data['status']);
        if ($data['status'] == 1) {
            return Json::encode(['msg' => "以还原，密码为：123456", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "发生未知错误，更新用户失败", "flag" => 0]);
        }
    }

    //关联类别保存,
    public function actionSave()
    {
//        return Json::encode(['msg' => "预览成功!", "flag" => 2, 'datas' => serialize($_POST), "url" => Url::to(['test'])]);
        $postData = Yii::$app->request->post();
        $postData['User']['user_account'] = $_POST['data'][1]['value'];
        $postData['User']['user_type'] = $_POST['data'][2]['value'];
        $postData['User']['staff_id'] = $_POST['data'][3]['value'];
        $postData['User']['user_mobile'] = $_POST['data'][4]['value'];
        $postData['User']['other_tel'] = $_POST['data'][5]['value'];
        $postData['User']['user_email'] = $_POST['data'][6]['value'];
        $postData['User']['user_status'] = $_POST['data'][7]['value'];
        $postData['User']['remarks'] = $_POST['data'][8]['value'];
        $postData['opper'] = Yii::$app->user->identity->staff_id;//操作人
        $postData['pwd'] = Yii::$app->security->generatePasswordHash("123456");
        $postData['opp_ip'] = Yii::$app->request->getUserIP();//操作人IP
        $postData['Role_rid'] = $_POST['Role_rid'];
        $postData['data_rid'] = $_POST['data_rid'];
        $postData['Commodity_rid'] = $_POST['Commodity_rid'];
        $postData['wh_id'] = $_POST['wh_id'];
        $postData['part_id'] = $_POST['part_id'];
        $postData['whpk_id'] = $_POST['whpk_id'];
        $postData['factory_id'] = $_POST['factory_id'];
        $postData['user_id'] = $_POST['user_id'];
        $postData['type_name'] = $_POST['type_name'];
//        $postData = serialize($postData);
//        return Json::encode(['msg' => "预览成功!", "flag" => 2, 'datas' => $postData, "url" => Url::to(['test'])]);
        $url = $this->findApiUrl() . $this->_url . "save";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = Json::decode($curl->post($url));
//        dumpE($data);
        return $data['status'];
    }

    //用户角色设置保存
    public function actionSaver()
    {
        $postData = Yii::$app->request->post();
        $postData['user_id'] = $_POST['user_id'];
        $postData['Role_rid'] = $_POST['Role_rid'];
        $postData['opper'] = Yii::$app->user->identity->staff_id;//操作人
        $postData['opp_ip'] = Yii::$app->request->getUserIP();//操作人IP
        $url = $this->findApiUrl() . $this->_url . "saver";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = Json::decode($curl->post($url));
//        dumpE($data);
        return $data['status'];
    }

    //数据权限设置保存
    public function actionSaved()
    {
        $postData = Yii::$app->request->post();
        $postData['user_id'] = $_POST['user_id'];
        $postData['data_rid'] = $_POST['data_rid'];
        $postData['opper'] = Yii::$app->user->identity->staff_id;//操作人
        $postData['opp_ip'] = Yii::$app->request->getUserIP();//操作人IP
        $url = $this->findApiUrl() . $this->_url . "saved";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = Json::decode($curl->post($url));
//        dumpE($data);
        return $data['status'];
    }

    //商品类别设置保存
    public function actionSavec()
    {
        $postData = Yii::$app->request->post();
        $postData['user_id'] = $_POST['user_id'];
        $postData['Commodity_rid'] = $_POST['Commodity_rid'];
        $postData['opper'] = Yii::$app->user->identity->staff_id;//操作人
        $postData['opp_ip'] = Yii::$app->request->getUserIP();//操作人IP
        $url = $this->findApiUrl() . $this->_url . "savec";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = Json::decode($curl->post($url));
//        dumpE($data);
        return $data['status'];
    }

    //仓库权限设置保存
    public function actionSavew()
    {
        $postData = Yii::$app->request->post();
        $postData['user_id'] = $_POST['user_id'];
        $postData['part_id'] = $_POST['part_id'];
        $postData['wh_id'] = $_POST['wh_id'];
        $postData['whpk_id'] = $_POST['whpk_id'];
        $postData['opper'] = Yii::$app->user->identity->staff_id;//操作人
        $postData['opp_ip'] = Yii::$app->request->getUserIP();//操作人IP
        $url = $this->findApiUrl() . $this->_url . "savew";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = Json::decode($curl->post($url));
//        dumpE($data);
        return $data['status'];
    }

    //厂区设置保存
    public function actionSavef()
    {
        $postData = Yii::$app->request->post();
        $postData['user_id'] = $_POST['user_id'];
        $postData['factory_id'] = $_POST['factory_id'];
        $postData['opper'] = Yii::$app->user->identity->staff_id;//操作人
        $postData['opp_ip'] = Yii::$app->request->getUserIP();//操作人IP
        $url = $this->findApiUrl() . $this->_url . "savef";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = Json::decode($curl->post($url));
//        dumpE($data);
        return $data['status'];
    }

    public function actionTest($data)
    {
        print_r(Yii::$app->user->identity->staff->staff_id);
        $datas = unserialize($data);
        print_r($datas);
        return $this->render('test');
    }


    //根据工号查询信息
    public function actionGetStaff($staff_code, $code)
    {
        if ($staff_code == $code) {
            return 0;
        } else {
            $url = $this->findApiUrl() . "hr/staff/get-staff-info?code=" . $staff_code;
            $staff = $this->findCurl()->get($url);
            $staffs = Json::decode($staff);
            if (!empty($staffs)) {
                $urls = $this->findApiUrl() . $this->_url . "get-by-one?staff_id=" . $staffs['staff_id'];
                $user = $this->findCurl()->get($urls);
                $users = Json::decode($user);
                if (!empty($users)) {
                    return 2;
                } else {
                    return $staff;
                }
            } else {
                return 1;
            }
        }
    }

    //部门权限设置的树状列表
    public function actionTree($userid)
    {
        $url = $this->findApiUrl() . $this->_url . "get-tree?userid=" . $userid;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $dataProvider;
    }

    //商品类别查询树
    public function actionTrees($userid)
    {
        $url = $this->findApiUrl() . $this->_url . "get-trees?userid=" . $userid;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $dataProvider;
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //用户角色设置
    public function actionUpdateRole($id)
    {
        $urls = $this->findApiUrl() . $this->_url . "roles";
        $roles = Json::decode($this->findCurl()->get($urls));
        $this->layout = '@app/views/layouts/ajax';

        $urlrole = $this->findApiUrl() . $this->_url . "roles-dt?id=" . $id;
        $role = Json::decode($this->findCurl()->get($urlrole));
        return $this->render("update-role",
            [
                'user_id' => $id,
                'roles' => $roles,
                'role' => $role
            ]);
    }

    //修改部门权限
    public function actionUpdateDepartment($id)
    {
        return $this->render("update-department",
            [
                'user_id' => $id
            ]);
    }

    //修改商品权限
    public function actionUpdateCommodity($id)
    {
        return $this->render("update-commodity",
            [
                'user_id' => $id
            ]);
    }

    //修改商品权限
    public function actionUpdateWh($id)
    {
        return $this->render("update-wh",
            [
                'user_id' => $id
            ]);
    }

    //修改厂区设置权限
    public function actionUpdateFactory($id)
    {
        $model = $this->findModel($id);

        $urlfactory = $this->findApiUrl() . $this->_url . "factory-dt?id=" . $id;
        $area = Json::decode($this->findCurl()->get($urlfactory));

        $urls_f = $this->findApiUrl() . $this->_url . "bs-factory-list";
        $factory = Json::decode($this->findCurl()->get($urls_f));

        $url = $this->findApiUrl() . $this->_url . "get-staff?staff_id=" . $model->staff_id;
        $staff = Json::decode($this->findCurl()->get($url));

        return $this->render('update-factory', [
            'area' => $area,
            'factory' => $factory,
            'staff' => $staff,
            'user_id' => $id
        ]);
    }

    //部门权限设置的树状列表(两个参数)
    public function actionGetTree($pid, $userid)
    {
        $url = $this->findApiUrl() . $this->_url . "get-tree?pid=" . $pid . "&userid=" . $userid;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $dataProvider;
    }

    //商品类别查询树（两个参数）
    public function actionGetTrees($p_catg_id, $userid)
    {
        $url = $this->findApiUrl() . $this->_url . "get-trees?userid=" . $userid . "&p_catg_id=" . $p_catg_id;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $dataProvider;
    }


    //仓库权限查询树
    public function actionGetWhTree($wh_id='', $user_id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-wh-tree?wh_id=" . $wh_id . "&user_id=" . $user_id;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $dataProvider;
    }

    public function actionGetPartTree($part_name,$user_id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-part-tree?part_name=" . $part_name . "&user_id=" . $user_id;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $dataProvider;
    }

    //根据菜单名称查询菜单pkid
    public function actionGetCtgid($ctg_name)
    {
        $url = $this->findApiUrl() . $this->_url . 'get-ctgid?ctg_name=' . $ctg_name;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return empty(!$dataProvider['catg_id']) ? $dataProvider['catg_id'] : "0";
    }

    //根据部门名称查询部门pkid
    public function actionGetOrganizationId($organization_name)
    {
        $url = $this->findApiUrl() . $this->_url . 'get-organization-id?organization_name=' . $organization_name;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return empty(!$dataProvider['organization_id']) ? $dataProvider['organization_id'] : "0";
    }

    public function actionGetWhId($wh_name)
    {
        $url = $this->findApiUrl() . $this->_url . 'get-wh-id?wh_name=' . $wh_name;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        $url = $this->findApiUrl() . $this->_url . 'get-part-id?part_name=' . $wh_name;
        $dataProviders = Json::decode($this->findCurl()->get($url));
        if (empty(!$dataProvider['wh_id'])) {
            return $dataProvider['wh_id'];
        } else if (!empty($dataProviders['wh_id'])) {
            return "0";
        } else {
            return "-1";
        }
    }
}