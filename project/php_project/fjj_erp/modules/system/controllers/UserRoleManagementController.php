<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/11/20
 * Time: 下午 02:38
 */

namespace app\modules\system\controllers;

use app\controllers\BaseController;
use yii;
use yii\helpers\Json;
use yii\helpers\Url;

class UserRoleManagementController extends BaseController
{
    private $_url = "system/user-role-management/";

    //用户角色列表
    public function actionUserIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "user-index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
//        dumpE($dataProvider);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
//        dumpE($queryParam);

        return $this->render('user-index', [
            'search' => $queryParam]);
    }

    //新增角色
    public function actionAddEdit($type)
    {

        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $postData['BsRole']['opper'] = Yii::$app->user->identity->staff->staff_id;//操作人
            $postData['BsRole']['opp_date'] = date('Y-m-d H:i:s', time());//操作时间
            $postData['BsRole']['opp_ip'] = Yii::$app->request->getUserIP();//'//获取ip地址
            $url = $this->findApiUrl() . $this->_url . "add-edit?type=" . $type;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->put($url));
            if ($data->status == 1) {
                return Json::encode(['msg' => "操作成功", "flag" => 1, "url" => Url::to(['user-index'])]);
            } else {
                return Json::encode(['msg' => $data->msg, 'flag' => 0]);
            }

        }
        if ($type == 'add') {

        } else {
            //根据role_pkid查询信息
            $url1 = $this->findApiUrl() . $this->_url . "get-role?role_pkid=" . $type;
            $dataProvider = Json::decode($this->findCurl()->get($url1));
            $this->layout = "@app/views/layouts/ajax.php";
            return $this->render('add-edit', ['model' => $dataProvider, 'type' => $type]);
        }

        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render('add-edit', ['type' => $type]);
    }

    //菜单权限设置
    public function actionMenuAuth($role_pkid)
    {
        return $this->render('menu-auth', ['role_pkid' => $role_pkid]);
    }

    //获取菜单的树状图
    public function actionTree($role_pkid, $menu_pkid)
    {
        $url = $this->findApiUrl() . $this->_url . "get-tree?role_pkid=" . $role_pkid . '&menu_pkid=' . $menu_pkid;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $dataProvider;
    }

    //保存角色的菜单权限
    public function actionSave()
    {
        $role_pkid = $_POST['role_pkid'];
        $dt_pkid = $_POST['dt_pkid'];
        $url = $this->findApiUrl() . $this->_url . "save";
        $postData = Yii::$app->request->post();
        $postData['role_pkid'] = $role_pkid;
        $postData['dt_pkid'] = $dt_pkid;
        $postData['opper'] = Yii::$app->user->identity->staff_id;//操作人
        $postData['opp_date'] = date('Y-m-d H:i:s', time());//操作时间
        $postData['opp_ip'] = Yii::$app->request->getUserIP();
        $cul = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = Json::decode($cul->post($url));
        return $data["status"];
    }

    //根据菜单名称查询菜单pkid
    public function actionGetMenuid($menu_name)
    {
        $url = $this->findApiUrl() . $this->_url . 'get-menuid?menu_name=' . $menu_name;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return empty(!$dataProvider['menu_pkid']) ? $dataProvider['menu_pkid'] : "0";
    }

    //禁用启用状态的更新
    public function actionUpdateState($role_pkid)
    {
        $staff_id = Yii::$app->user->identity->staff_id;//操作人
        $ip = Yii::$app->request->getUserIP();
        $url = $this->findApiUrl() . $this->_url . "update-state?role_pkid=" . $role_pkid . "&userid=" . $staff_id . "&ip=" . $ip;
        $data = $this->findCurl()->delete($url);
        if (json_decode($data)->status == 1) {
            return Json::encode(['msg' => json_decode($data)->msg . "成功!", "flag" => 1, "url" => Url::to(['user-index'])]);
        } else {
            return Json::encode(['msg' => json_decode($data)->msg . "失败!", 'flag' => 0]);
        }
    }
}