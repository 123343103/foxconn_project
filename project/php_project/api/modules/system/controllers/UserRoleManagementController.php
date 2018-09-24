<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/11/20
 * Time: 下午 02:50
 */

namespace app\modules\system\controllers;


use app\controllers\BaseActiveController;
use app\modules\system\models\BsMenu;
use app\modules\system\models\BsRole;
use app\modules\system\models\RRoleMnBtn;
use app\modules\system\models\RRoleMnBtnDt;
use app\modules\system\models\search\BsRoleSearch;
use Yii;
use yii\data\SqlDataProvider;

class UserRoleManagementController extends BaseActiveController
{
    public $modelClass = 'app\modules\system\models\BsRole';

    public function actionUserIndex()
    {
        $searchModel = new BsRoleSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    //新增和修改
    public function actionAddEdit($type)
    {
        $post = Yii::$app->request->post();
        if ($type == 'add') {
            try {
                $BsRole = new BsRole();
                $BsRole->load($post);
                if (!$BsRole->save()) {
                    throw new \Exception(json_encode($BsRole->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                return $this->success();
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        } else {
            try {
                $BsPart = BsRole::findOne($type);
                $BsPart->load($post);
                if (!$BsPart->save()) {
                    throw new \Exception(json_encode($BsPart->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                return $this->success();
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }


    }

    //获取菜单树的方法
    public function actionGetTree($role_pkid, $menu_pkid = 0)
    {
        //根据$menu_pkid查询是否有数据
        $model = BsMenu::find()->where(['menu_pkid' => $menu_pkid])->one();
        $data = "";
        if ($model) {
            $data .= "[{ \"id\" :\"" . $model->menu_level . "\",\"text\" :\"" . $model->menu_name . "<div style='display:none' class='catgid'>" . $model->menu_pkid . "</div><div style='display:none' class='level'>" . $model->menu_level . "</div>\", \"level\":\"" . $model->menu_level . "\", \"value\" :\"" . $model->menu_pkid . "\"";
            $chile = $this->getTree($menu_pkid, $role_pkid);
            if ($chile != "") {
                $data .= ",\"children\":" . $chile;
            }
            $data .= "}]";

        } else {
            $data = $this->getTree($menu_pkid, $role_pkid);
        }
        return $data;
    }

    //保存数据的方法
    public function actionSave()
    {
        $para = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //根据role_pkid查询是否有数据有数据
            $RRoleMnBtn = RRoleMnBtn::find()->where(['role_pkid' => $para['role_pkid']])->all();
            $RRoleMnBtnDt = RRoleMnBtnDt::find()->where(['role_pkid' => $para['role_pkid']])->all();

            if (!empty($RRoleMnBtnDt)) {
                //删除r_role_mn_btn_dt
                RRoleMnBtnDt::deleteAll(['role_pkid' => $para['role_pkid']]);
            }
            if (!empty($RRoleMnBtn)) {
                //删除r_role_mn_btn
                RRoleMnBtn::deleteAll(['role_pkid' => $para['role_pkid']]);
            }
            //添加数据
            $rrmbModel = new RRoleMnBtn();

            $rrmbModel->role_pkid = $para['role_pkid'];
            $rrmbModel->opper = $para['opper'];
            $rrmbModel->opp_date = $para['opp_date'];
            $rrmbModel->opp_ip = $para['opp_ip'];
            if (!$rrmbModel->save()) {
                throw new \Exception(json_encode($rrmbModel->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            $dtidArr = explode(",", $para['dt_pkid']);
            foreach ($dtidArr as $key => $val) {
                $int = (int)$val;
                $rrmbdMode = new RRoleMnBtnDt();
                $rrmbdMode->role_pkid = $para['role_pkid'];
                $rrmbdMode->dt_pkid = $int;
                if (!$rrmbdMode->save()) {
                    throw new \Exception(json_encode($rrmbdMode->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }

            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //根据菜单名获取菜单id
    public function actionGetMenuid($menu_name)
    {
        $model = BsMenu::find()->where(['menu_name' => $menu_name])->select('menu_pkid')->one();
        return $model;
    }

    //根据role_pkid查询要修改的角色详情
    public function actionGetRole($role_pkid)
    {
        $model = BsRole::find()->where(['role_pkid' => $role_pkid])->one();
        return $model;
    }

    //状态的启用和禁用的更改
    public function actionUpdateState($role_pkid, $userid, $ip)
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $model = BsRole::findOne($role_pkid);
        if (!empty($model)) {
            if ($model->role_state == 0) {
                $model->role_state = 1;
            } else if ($model->role_state == 1) {
                $model->role_state = 0;
            }
            $model->opper = $userid;
            $model->opp_date = date("Y-m-d H:i:s");;
            $model->opp_ip = $ip;
            if ($model->save()) {
                return $this->success();
            } else {
                return $this->error();
            }
        } else {
            return $this->error();
        }
    }

    //查询菜单树
    public function getTree($menu_pkid = 0, $role_pkid)
    {
        $RRoleMnBtnDt = RRoleMnBtnDt::find()->andWhere(['role_pkid' => $role_pkid])->all();
        $tree = BsMenu::find()->andWhere(['p_menu_pkid' => $menu_pkid])->andWhere(['yn' => 1])
            ->select('menu_pkid,menu_name,p_menu_pkid,menu_level')->all();
//        dumpE($tree);

        $i = 0;
        static $str = "";
        if ($tree) {
            $str = $str . "[";
            foreach ($tree as $key => $val) {
                if ($i == 0) {
                    $i++;
                } else {
                    $str = $str . ",";
                }
                $childs = BsMenu::find()->andWhere(['p_menu_pkid' => $val['menu_pkid']])->andWhere(['yn' => 1])->one();
                $str .= "
                                       {  
                                        \"id\" :\"" . $val['menu_level'] . "\",
                                        \"text\" :\"" . $val['menu_name'] . "<div style='display:none' class='catgid'>" . $val['menu_pkid'] . "</div><div style='display:none' class='level'>" . $val['menu_level'] . "</div>\",
                                        \"level\":\"" . $val['menu_level'] . "\",
                                        \"value\" :\"" . $val['menu_pkid'] . "\"";

                if ($childs) {
                    $str .= "
                           , \"children\":";
                    self::getTree($val['menu_pkid'], $role_pkid);
                    $str .= "  }";
                } else {
                    //查询菜单页面关联的按钮
                    $queryParams = [':menu_pkid' => $val['menu_pkid']];
                    $sql = "SELECT
                            rmbd.dt_pkid,
                            rmbd.menu_pkid,
                            rmbd.btn_pkid,
                            b.btn_no,
                            b.btn_name
                        FROM
                            r_menu_btn_dt rmbd
                        LEFT JOIN bs_btn b ON rmbd.btn_pkid = b.btn_pkid
                        WHERE
                            rmbd.menu_pkid = :menu_pkid";
                    $provider = new SqlDataProvider([
                        'sql' => $sql,
                        'params' => $queryParams,
                        'pagination' => [
                            'pageSize' => ''
                        ]
                    ]);
                    $model = $provider->getModels();
                    $count = count($model);
                    $j = 0;
                    foreach ($model as $key1 => $val1) {

                        if ($count == 1) {
                            $level = $val['menu_level'] + 1;
                            if ($j == 0) {
                                $str .= "   , \"children\":";
                                $str .= "[";
                                $j++;
                            } else {

                                $str .= ",";
                            }
                            $str .= " {
                                                        \"id\" :\"" . $level . "\",
                                                        \"text\" :\"" . "隐藏" . "<div style='display:none' class='display134 dt_pkid'>" . $val1['dt_pkid'] . "</div><div style='display:none' class='level'>" . $level . "</div>\",
                                                        \"level\":\"" . $level . "\",
                                                        \"value\" :\"" . $val1['menu_pkid'] . "\"";

                        } else {
                            if (!empty($val1['btn_pkid'])) {
                                $level = $val['menu_level'] + 1;
                                if ($j == 0) {
                                    $str .= "   , \"children\":";
                                    $str .= "[";
                                    $j++;
                                } else {

                                    $str .= ",";
                                }
                                $str .= " {
                                                        \"id\" :\"" . $level . "\",
                                                        \"text\" :\"" . $val1['btn_name'] . "<div style='display:none' class='dt_pkid'>" . $val1['dt_pkid'] . "</div><div style='display:none' class='level'>" . $level . "</div>\",
                                                        \"level\":\"" . $level . "\",
                                                        \"value\" :\"" . $val1['menu_pkid'] . "\"";
                            }else{
                                continue;
                            }

                        }
                        //判断是否设置过该角色的菜单
                        foreach ($RRoleMnBtnDt as $key2 => $val2) {
                            if ($val1['dt_pkid'] == $val2['dt_pkid']) {
                                $str .= " ,\"checked\" :true";
                            }
                        }
                        $str .= "  } ";

                    }
                    if ($j != 0) {
                        $str .= "  ] ";
                    }
                    $str .= "  } ";
                }
            }
            $str .= "]";
        } else {
            //查询菜单页面关联的按钮
            $queryParams = [':menu_pkid' => $menu_pkid];
            $sql = "SELECT
                            rmbd.dt_pkid,
                            rmbd.menu_pkid,
                            mbd.btn_pkid,
                            b.btn_no,
                            b.btn_name
                        FROM
                            r_menu_btn_dt rmbd
                        LEFT JOIN bs_btn b ON rmbd.btn_pkid = b.btn_pkid
                        WHERE
                            rmbd.menu_pkid = :menu_pkid";
            $provider = new SqlDataProvider([
                'sql' => $sql,
                'params' => $queryParams,
                'pagination' => [
                    'pageSize' => ''
                ]
            ]);
            $model = $provider->getModels();
            $j = 0;
            foreach ($model as $key1 => $val1) {
                $level = 3;
                if ($j == 0) {
                    $str .= "[";
                    $j++;
                } else {

                    $str .= ",";
                }
                if ($val1["btn_pkid"] == 0) {
                    $str .= " {
                                                        \"id\" :\"" . $level . "\",
                                                        \"text\" :\"" . "隐藏" . "<div style='display:none' class='display134 dt_pkid'>" . $val1['dt_pkid'] . "</div><div style='display:none' class='level'>" . $level . "</div>\",
                                                        \"level\":\"" . $level . "\",
                                                        \"value\" :\"" . $val1['menu_pkid'] . "\"";
                } else {
                    $str .= " {
                                                        \"id\" :\"" . $level . "\",
                                                        \"text\" :\"" . $val1['btn_name'] . "<div style='display:none' class='dt_pkid'>" . $val1['dt_pkid'] . "</div><div style='display:none' class='level'>" . $level . "</div>\",
                                                        \"level\":\"" . $level . "\",
                                                        \"value\" :\"" . $val1['menu_pkid'] . "\"";
                }
                //判断是否设置过该角色的菜单
                foreach ($RRoleMnBtnDt as $key2 => $val2) {
                    if ($val1['dt_pkid'] == $val2['dt_pkid']) {
                        $str .= " ,\"checked\" :true";
                    }
                }
                $str .= "  } ";

            }
            if ($j != 0) {
                $str .= "  ] ";
            }
        }
        return $str;
    }

}