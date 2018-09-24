<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/11/20
 * Time: 下午 02:59
 */
namespace app\modules\system\controllers;

use app\controllers\BaseActiveController;
use app\models\User;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\BsCategory;
use app\modules\system\models\BsFactory;
use app\modules\system\models\BsRole;
use app\modules\system\models\RPwrDpt;
use app\modules\system\models\RUserArea;
use app\modules\system\models\RUserAreaDt;
use app\modules\system\models\RUserCtg;
use app\modules\system\models\RUserCtgDt;
use app\modules\system\models\RUserDpt;
use app\modules\system\models\RUserDptDt;
use app\modules\system\models\RUserRole;
use app\modules\system\models\RUserWh;
use app\modules\system\models\RUserWhDt;
use app\modules\system\models\search\UserSearch;
use app\modules\system\models\RUserRoleDt;
use app\modules\warehouse\models\BsPart;
use app\modules\warehouse\models\BsWh;
use Yii;
use yii\data\SqlDataProvider;
use yii\web\NotFoundHttpException;


/**
 * User控制器
 */
class UserManagementController extends BaseActiveController
{
    public $modelClass = true;

    //首页
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->searchs($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
        //http://localhost/php_project/api/web/system/user-management/index
    }

    //通过id查询三阶商品类别名称
    public function actionCateName($id)
    {
        $model = BsCategory::find()->where(['catg_id' => $id])->one();
        return $model;
    }

    //保存类别关联数据
    public function actionSave()
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $param = Yii::$app->request->post();
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!empty($param['user_id'])) {
                $users = User::findOne($param['user_id']);
                $users->load($param);
                $users->update_by = $param['opper'];
                $users->update_at = date("Y-m-d H:i:s");
            } else {
                $users = new User();
                $users->load($param);
                $users->create_by = $param['opper'];
                $users->user_pwd = $param['pwd'];
                $users->create_at = date("Y-m-d H:i:s");
            }
            $users->user_account = $param['User']['user_account'];
            $users->staff_id = $param['User']['staff_id'];
            $users->user_type = $param['User']['user_type'];
            $users->user_status = $param['User']['user_status'];
            $users->user_mobile = $param['User']['user_mobile'];
            $users->user_email = $param['User']['user_email'];
            $users->other_tel = $param['User']['other_tel'];
            $users->remarks = $param['User']['remarks'];
            if ($param['type_name'] == '超级管理员') {
                $users['is_supper'] = 1;
            } else {
                $users['is_supper'] = 0;
            }
            if (!$users->save()) {
                throw new \Exception(json_encode($users->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            if (!empty($param['user_id'])) {
                $userid = $param['user_id'];
            } else {
                $userid = $users->user_id;
            }

            //用户角色设置
            $RoleridArr = explode(",", $param['Role_rid']);//再添加
            if (!empty($RoleridArr[0])) {
                $Roles = RUserRoleDt::find()->where(["user_id" => $userid])->all();//判断删除的关联料号有没有被删除
                if (!empty($Roles)) {
                    if (RUserRoleDt::deleteAll(["user_id" => $userid])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $Role = RUserRole::find()->where(["user_id" => $userid])->all();//判断删除的关联料号有没有被删除
                if (!empty($Role)) {
                    if (RUserRole::deleteAll(["user_id" => $userid])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $role = new RUserRole();
//            $role->load($param);
                $role['user_id'] = $userid;
                $role['opper'] = $param['opper'];
                $role['opp_date'] = date("Y-m-d H:i:s");
                $role['opp_ip'] = $param['opp_ip'];
                if (!$role->save()) {
                    throw new \Exception(json_encode($role->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                foreach ($RoleridArr as $key => $val) {
                    $model = new RUserRoleDt();
//                $model->load($param);
                    $model->user_id = $userid;
                    $model->role_pkid = $val;
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }

            //用户权限（部门）设置
            $DptridArr = explode(",", $param['data_rid']);//再添加
            if (!empty($DptridArr[0])) {
                $Dptdpt = RUserDptDt::find()->where(["user_id" => $userid])->all();//判断删除的关联料号有没有被删除
                if (!empty($Dptdpt)) {
                    if (RUserDptDt::deleteAll(["user_id" => $userid])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $Dptdpts = RUserDpt::find()->where(["user_id" => $userid])->all();//判断删除的关联料号有没有被删除
                if (!empty($Dptdpts)) {
                    if (RUserDpt::deleteAll(["user_id" => $userid])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $dpt = new RUserDpt();
//            $dpt->load($param);
                $dpt['user_id'] = $userid;
                $dpt['opper'] = $param['opper'];
                $dpt['opp_date'] = date("Y-m-d H:i:s");
                $dpt['opp_ip'] = $param['opp_ip'];
                if (!$dpt->save()) {
                    throw new \Exception(json_encode($dpt->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                foreach ($DptridArr as $key => $val) {
                    $model = new RUserDptDt();
//                $model->load($param);
                    $model->user_id = $userid;
                    $model->dpt_pkid = $val;
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }

            //商品类别设置
            $CtgridArr = explode(",", $param['Commodity_rid']);//再添加
            if (!empty($CtgridArr[0])) {
                $CtgDt = RUserCtgDt::find()->where(["user_id" => $userid])->all();//判断删除的关联料号有没有被删除
                if (!empty($CtgDt)) {
                    if (RUserCtgDt::deleteAll(["user_id" => $userid])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $CtgDts = RUserCtg::find()->where(["user_id" => $userid])->all();//判断删除的关联料号有没有被删除
                if (!empty($CtgDts)) {
                    if (RUserCtg::deleteAll(["user_id" => $userid])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $ctg = new RUserCtg();
//            $ctg->load($param);
                $ctg['user_id'] = $userid;
                $ctg['opper'] = $param['opper'];
                $ctg['opp_date'] = date("Y-m-d H:i:s");
                $ctg['opp_ip'] = $param['opp_ip'];
                if (!$ctg->save()) {
                    throw new \Exception("新增失败");
                }
                foreach ($CtgridArr as $key => $val) {
                    $model = new RUserCtgDt();
//                $model->load($param);
                    $model->user_id = $userid;
                    $model->ctg_pkid = $val;
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }

            //仓库权限设置
            $whidArr = explode(",", $param['wh_id']);//再添加
            $partidArr = explode(",", $param['part_id']);
            $whpkidArr = explode(",", $param['whpk_id']);
            if (!empty($whidArr[0])||!empty($partidArr[0])||!empty($whpkidArr[0])) {
                $whDt = RUserWhDt::find()->where(["user_id" => $userid])->all();//判断删除的关联料号有没有被删除
                if (!empty($whDt)) {
                    if (RUserWhDt::deleteAll(["user_id" => $userid])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $whDts = RUserWh::find()->where(["user_id" => $userid])->all();//判断删除的关联料号有没有被删除
                if (!empty($whDts)) {
                    if (RUserWh::deleteAll(["user_id" => $userid])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $wh = new RUserWh();
//            $ctg->load($param);
                $wh['user_id'] = $userid;
                $wh['opper'] = $param['opper'];
                $wh['opp_date'] = date("Y-m-d H:i:s");
                $wh['opp_ip'] = $param['opp_ip'];
                if (!$wh->save()) {
                    throw new \Exception("新增失败");
                }
                foreach ($whidArr as $key => $val) {
                    $model = new RUserWhDt();
//                $model->load($param);
                    $model->user_id = $userid;
                    $model->wh_pkid = $val;
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
                for($i=0;$i<count($partidArr);$i++)
                {
                    $model = new RUserWhDt();
//                $model->load($param);
                    $model->user_id = $userid;
                    $model->wh_pkid = $whpkidArr[$i];
                    $model->part_id=$partidArr[$i];
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }

            //厂区设置
            $areaArr = explode(",", $param['factory_id']);//再添加
            if (!empty($areaArr[0])) {
                $area = RUserAreaDt::find()->where(["user_id" => $userid])->all();//判断删除的关联user_id有没有被删除
                if (!empty($area)) {
                    if (RUserAreaDt::deleteAll(["user_id" => $userid])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $areas = RUserArea::find()->where(["user_id" => $userid])->all();//判断删除的关联User_id有没有被删除
                if (!empty($areas)) {
                    if (RUserArea::deleteAll(["user_id" => $userid])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $factory = new RUserArea();
//            $ctg->load($param);
                $factory['user_id'] = $userid;
                $factory['opper'] = $param['opper'];
                $factory['opp_date'] = date("Y-m-d H:i:s");
                $factory['opp_ip'] = $param['opp_ip'];
                if (!$factory->save()) {
                    throw new \Exception("新增失败");
                }
                foreach ($areaArr as $key => $val) {
                    $model = new RUserAreaDt();
//                $model->load($param);
                    $model->user_id = $userid;
                    $model->area_pkid = $val;
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getFile() . "::::" . $e->getLine() . "::::" . $e->getMessage());
        }
    }

    //用户角色设置保存
    public function actionSaver()
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $param = Yii::$app->request->post();
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //用户角色设置
            $RoleridArr = explode(",", $param['Role_rid']);//再添加
            if (!empty($RoleridArr[0])) {
                $Roles = RUserRoleDt::find()->where(["user_id" => $param['user_id']])->all();//判断删除的关联料号有没有被删除
                if (!empty($Roles)) {
                    if (RUserRoleDt::deleteAll(["user_id" => $param['user_id']])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $Role = RUserRole::find()->where(["user_id" => $param['user_id']])->all();//判断删除的关联料号有没有被删除
                if (!empty($Role)) {
                    if (RUserRole::deleteAll(["user_id" => $param['user_id']])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $role = new RUserRole();
//            $role->load($param);
                $role['user_id'] = $param['user_id'];
                $role['opper'] = $param['opper'];
                $role['opp_date'] = date("Y-m-d H:i:s");
                $role['opp_ip'] = $param['opp_ip'];
                if (!$role->save()) {
                    throw new \Exception(json_encode($role->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                foreach ($RoleridArr as $key => $val) {
                    $model = new RUserRoleDt();
//                $model->load($param);
                    $model->user_id = $param['user_id'];
                    $model->role_pkid = $val;
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getFile() . "::::" . $e->getLine() . "::::" . $e->getMessage());
        }
    }

    //仓库权限设置保存
    public function actionSavew()
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $param = Yii::$app->request->post();
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //仓库权限设置
            $whidArr = explode(",", $param['wh_id']);//再添加
            $partidArr = explode(",", $param['part_id']);
            $whpkidArr = explode(",", $param['whpk_id']);
            if (!empty($whidArr[0])||!empty($partidArr[0])||!empty($whpkidArr[0])) {
                $whDt = RUserWhDt::find()->where(["user_id" => $param['user_id']])->all();//判断删除的关联料号有没有被删除
                if (!empty($whDt)) {
                    if (RUserWhDt::deleteAll(["user_id" => $param['user_id']])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $whDts = RUserWh::find()->where(["user_id" => $param['user_id']])->all();//判断删除的关联料号有没有被删除
                if (!empty($whDts)) {
                    if (RUserWh::deleteAll(["user_id" => $param['user_id']])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $wh = new RUserWh();
//            $ctg->load($param);
                $wh['user_id'] = $param['user_id'];
                $wh['opper'] = $param['opper'];
                $wh['opp_date'] = date("Y-m-d H:i:s");
                $wh['opp_ip'] = $param['opp_ip'];
                if (!$wh->save()) {
                    throw new \Exception("新增失败");
                }
                foreach ($whidArr as $key => $val) {
                    $model = new RUserWhDt();
//                $model->load($param);
                    $model->user_id = $param['user_id'];
                    $model->wh_pkid = $val;
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
                for($i=0;$i<count($partidArr);$i++)
                {
                    $model = new RUserWhDt();
//                $model->load($param);
                    $model->user_id = $param['user_id'];
                    $model->wh_pkid = $whpkidArr[$i];
                    $model->part_id=$partidArr[$i];
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getFile() . "::::" . $e->getLine() . "::::" . $e->getMessage());
        }
    }

    //厂区设置保存
    public function actionSavef()
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $param = Yii::$app->request->post();
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $areaArr = explode(",", $param['factory_id']);//再添加
            if (!empty($areaArr[0])) {
                $area = RUserAreaDt::find()->where(["user_id" => $param['user_id']])->all();//判断删除的关联user_id有没有被删除
                if (!empty($area)) {
                    if (RUserAreaDt::deleteAll(["user_id" => $param['user_id']])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $areas = RUserArea::find()->where(["user_id" => $param['user_id']])->all();//判断删除的关联User_id有没有被删除
                if (!empty($areas)) {
                    if (RUserArea::deleteAll(["user_id" => $param['user_id']])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $factory = new RUserArea();
//            $ctg->load($param);
                $factory['user_id'] = $param['user_id'];
                $factory['opper'] = $param['opper'];
                $factory['opp_date'] = date("Y-m-d H:i:s");
                $factory['opp_ip'] = $param['opp_ip'];
                if (!$factory->save()) {
                    throw new \Exception("新增失败");
                }
                foreach ($areaArr as $key => $val) {
                    $model = new RUserAreaDt();
//                $model->load($param);
                    $model->user_id = $param['user_id'];
                    $model->area_pkid = $val;
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getFile() . "::::" . $e->getLine() . "::::" . $e->getMessage());
        }
    }

    //重置密码
    public function actionResetPassword($id, $update_by)
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $pwd = "123456";
        $model = User::findOne($id);
        try {
            $model->user_pwd = Yii::$app->security->generatePasswordHash($pwd);
            $model->first_login = '0';
            $model->update_by = $update_by;
            $model->update_at = date("Y-m-d H:i:s");
            if (!$model->save()) {
                throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getFile() . "::::" . $e->getLine() . "::::" . $e->getMessage());
        }

    }

    //数据权限设置保存
    public function actionSaved()
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $param = Yii::$app->request->post();
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //用户权限（部门）设置
            $DptridArr = explode(",", $param['data_rid']);//再添加
            if (!empty($DptridArr[0])) {
                $Dptdpt = RUserDptDt::find()->where(["user_id" => $param['user_id']])->all();//判断删除的关联料号有没有被删除
                if (!empty($Dptdpt)) {
                    if (RUserDptDt::deleteAll(["user_id" => $param['user_id']])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $Dptdpts = RUserDpt::find()->where(["user_id" => $param['user_id']])->all();//判断删除的关联料号有没有被删除
                if (!empty($Dptdpts)) {
                    if (RUserDpt::deleteAll(["user_id" => $param['user_id']])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $dpt = new RUserDpt();
//            $dpt->load($param);
                $dpt['user_id'] = $param['user_id'];
                $dpt['opper'] = $param['opper'];
                $dpt['opp_date'] = date("Y-m-d H:i:s");
                $dpt['opp_ip'] = $param['opp_ip'];
                if (!$dpt->save()) {
                    throw new \Exception(json_encode($dpt->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                foreach ($DptridArr as $key => $val) {
                    $model = new RUserDptDt();
//                $model->load($param);
                    $model->user_id = $param['user_id'];
                    $model->dpt_pkid = $val;
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getFile() . "::::" . $e->getLine() . "::::" . $e->getMessage());
        }

    }

    //商品类别设置保存
    public function actionSavec()
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $param = Yii::$app->request->post();
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //商品类别设置
            $CtgridArr = explode(",", $param['Commodity_rid']);//再添加
            if (!empty($CtgridArr[0])) {
                $CtgDt = RUserCtgDt::find()->where(["user_id" => $param['user_id']])->all();//判断删除的关联料号有没有被删除
                if (!empty($CtgDt)) {
                    if (RUserCtgDt::deleteAll(["user_id" => $param['user_id']])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $CtgDts = RUserCtg::find()->where(["user_id" => $param['user_id']])->all();//判断删除的关联料号有没有被删除
                if (!empty($CtgDts)) {
                    if (RUserCtg::deleteAll(["user_id" => $param['user_id']])) {
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                $ctg = new RUserCtg();
//            $ctg->load($param);
                $ctg['user_id'] = $param['user_id'];
                $ctg['opper'] = $param['opper'];
                $ctg['opp_date'] = date("Y-m-d H:i:s");
                $ctg['opp_ip'] = $param['opp_ip'];
                if (!$ctg->save()) {
                    throw new \Exception("新增失败");
                }
                foreach ($CtgridArr as $key => $val) {
                    $model = new RUserCtgDt();
//                $model->load($param);
                    $model->user_id = $param['user_id'];
                    $model->ctg_pkid = $val;
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getFile() . "::::" . $e->getLine() . "::::" . $e->getMessage());
        }
    }

    //根据staff_id查询user
    public function actionGetByOne($staff_id)
    {
        $sql = "select *from erp.user t WHERE t.staff_id=:staff_id";
        $queryParams = [':staff_id' => $staff_id];
        return Yii::$app->db->createCommand($sql, $queryParams)->queryOne();
    }

    //获取用户类型
    public function actionBspNameList()
    {
        $sql = "select *from erp.bs_pubdata where bsp_status=10 AND bsp_stype='YHLX'";
        return Yii::$app->db->createCommand($sql, null)->queryAll();
    }

    //获取用户角色
    public function actionRoles()
    {
        $roles = BsRole::find()->andWhere(['role_state' => '1'])->all();
        return $roles;
    }

    //获取厂区列表
    public function actionBsFactoryList()
    {
        $factory=BsFactory::find()->andWhere(['fact_status'=>'1'])->all();
        return $factory;
    }

    //根据user_id获取厂区权限内容
    public function actionFactoryDt($id)
    {
        $factory=RUserAreaDt::find()->andWhere(['user_id'=>$id])->all();
        return $factory;
    }
    //工号id获取信息
    public function actionGetStaff($staff_id)
    {
//        $param = Yii::$app->request->get();
//        $info = HrStaff::getStaffInfoById($staff_id);
        $info = HrStaff::getStaffInfoById($staff_id);
        return $info;
    }

    //user_id获取用户角色权限内容
    public function actionRolesDt($id)
    {
        $roles = RUserRoleDt::find()->andWhere(['user_id' => $id])->all();
        return $roles;
    }

    //部门权限设置的树状列表
    public function actionGetTree($pid=100,$userid)
    {
        $model = new HrOrganization();
        $data = $model->getTrees($pid,$userid);
        return $data;
    }

    //商品类别查询树
    public function actionGetTrees($p_catg_id=0,$userid)
    {
        return BsCategory::getTrees($p_catg_id, $userid);
    }


    //仓库权限查询树
    public function actionGetWhTree($wh_id=0,$user_id)
    {
        return BsWh::getTree($wh_id,$user_id);
    }

    public function actionGetPartTree($part_name,$user_id)
    {
        $model=$this->actionGetPartId($part_name);
        return BsWh::getTrees($model['wh_code'],$user_id,$model['wh_id'],$part_name);
    }
    //根据菜单名获取菜单id
    public function actionGetCtgid($ctg_name)
    {
        $model = BsCategory::find()->where(['catg_name' => $ctg_name])->select('catg_id')->one();
        return $model;
    }

    //根据部门名获取部门id
    public function actionGetOrganizationId($organization_name)
    {
        $model = HrOrganization::find()->where(['organization_name' => $organization_name])->select('organization_id')->one();
        return $model;
    }

    //根据名称获取仓库ID
    public function actionGetWhId($wh_name)
    {
        $model=BsWh::find()->andWhere(['wh_name'=>$wh_name])->select('wh_id')->one();
        return $model;
    }

    //根据名称获取数据
    public function actionGetPartId($part_name)
    {
        $sql="select bw.wh_id ,bp.wh_code FROM wms.bs_wh bw,wms.bs_part bp where bw.wh_code=bp.wh_code AND bp.part_name=:part_name";
        $queryParam = [
            ':part_name' => $part_name,
        ];
        $model = Yii::$app->get('db')->createCommand($sql, $queryParam)->queryOne();
        return $model;
    }
}