<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/11/21
 * Time: 上午 11:04
 */

namespace app\modules\system\controllers;


use app\classes\Trans;
use app\controllers\BaseActiveController;
use app\modules\system\models\BsBtn;
use app\modules\system\models\RMenuBtn;
use app\modules\system\models\RMenuBtnDt;
use app\modules\system\models\RRoleMnBtn;
use app\modules\system\models\RRoleMnBtnDt;
use app\modules\system\models\search\MenuPowerSearch;
use app\modules\system\models\BsMenu;
use yii\base\Exception;

class MenuPowerController extends BaseActiveController
{
    public $modelClass = 'app\modules\system\models\BsMenu';

    public function actionIndex()
    {
        $param = \Yii::$app->request->queryParams;
        $model = new MenuPowerSearch();
//        $search = $model->Search($param);
        $model1=$model->TreeTable($param);
//        $search=$model->NewSearch();
        return $model1;
    }

    //一级菜单
    public function actionOneMenu($menuPkid = null)
    {
        if (isset($menuPkid)) {
            $data = BsMenu::find()->andWhere(['!=', 'menu_pkid', $menuPkid])->all();
        } else {
            $data = BsMenu::find()->all();
        }
        return $data;
    }

    //根据主键查询信息
    public function actionQueryInfo($menuPkid)
    {
        $data = BsMenu::findOne(['menu_pkid' => $menuPkid]);
        return $data;
    }

    //新增或者修改一笔数据
    public function actionAddMenu()
    {
        $trans=new Trans();
        $post = \Yii::$app->request->post();
        if ($post['Menu']['type'] == 0) {
            $model = new BsMenu();
        } else if ($post['Menu']['type'] == 1) {

            $model = BsMenu::findOne(['menu_pkid' => $post['Menu']['menu_pkid']]);
        }
        if($post['Menu']['type'] == 0 || $post['Menu']['updatype']==1)
        {
        if ($post['Menu']['p_menu_pkid'] == -1) {
            $model->p_menu_pkid = 0;
        } else {
            $model->p_menu_pkid = $post['Menu']['p_menu_pkid'];
        }
        }
        $model->menu_level = $post['Menu']['menu_level'];
        $model->menu_name = $trans->t2c($post['Menu']['menu_name']);
        $model->yn = $post['Menu']['yn'];
        $model->menu_url = $post['Menu']['menu_url'];
        $model->opper = $post['opper'];
        $model->opp_date = $post['opp_date'];
        $model->opp_ip = $post['opp_ip'];
        if (!$model->save()) {
            throw new \Exception(json_encode($model->getFirstError(), JSON_UNESCAPED_UNICODE));
        }
        if ($model->menu_url!="#" && $model->menu_url!="" && $post['Menu']['type'] == 0)
        {
            $r_menu= new RMenuBtnDt();
            $r_menu->menu_pkid=$model->menu_pkid;
            if (!$r_menu->save()) {
                throw new \Exception(json_encode($r_menu->getFirstError(), JSON_UNESCAPED_UNICODE));
            }
        }
        if ($model->menu_url!="#" && $post['Menu']['type'] == 1 && $model->menu_url!="")
        {
            $menu= RMenuBtnDt::find()->where(['and',['menu_pkid'=>$model->menu_pkid,'btn_pkid'=>null]])->one();
            if (empty($menu))
            {
                $r_menu= new RMenuBtnDt();
                $r_menu->menu_pkid=$model->menu_pkid;
                if (!$r_menu->save()) {
                    throw new \Exception(json_encode($r_menu->getFirstError(), JSON_UNESCAPED_UNICODE));
                }
            }
        }
        return $this->success();
    }

    //查询所有按钮
    public function actionOperaSet()
    {
        $btnData = BsBtn::find()->andWhere(['btn_yn' => 1])->all();
        return $btnData;
    }

    //插入菜单对应的按钮
    public function actionInsertBtn()
    {
        $post = \Yii::$app->request->post();
        $menu_pkid = $post['menu_pkid'];
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (empty($post['Check'])) {
                $modelData = RMenuBtnDt::find()->andWhere(['and',['menu_pkid' => $menu_pkid],['<>','btn_pkid','null']])->select('dt_pkid')->asArray()->all();
                foreach ($modelData as $value)
                {
                    $model1=RRoleMnBtnDt::findOne(['dt_pkid'=>$value['dt_pkid']]);
                    if($model1)
                    {
                        RRoleMnBtnDt::deleteAll(['dt_pkid'=>$value['dt_pkid']]);
                    }
                }
               $model2=RMenuBtnDt::deleteAll(['and','menu_pkid=:menu_pkid',['<>','btn_pkid','null']],[':menu_pkid'=>$menu_pkid]);
            } else {
                $data = RMenuBtnDt::find()->andWhere(['and',['menu_pkid' => $menu_pkid],['<>','btn_pkid','null']])->select('btn_pkid')->asArray()->all();
                $ttt = [];//从表单传递过来的btn_pkid
                $aaa = [];//从数据库获取到的btn_pkid
                foreach ($data as $value) {
                    $aaa[] = $value['btn_pkid'];
                }
                foreach ($post['Check'] as $key => $value) {
                    $ttt[] = $value;
                }
                $bbb = array_merge(array_diff($ttt, array_intersect($ttt, $aaa)), array_diff($aaa, array_intersect($ttt, $aaa)));//取两个数组中不相交的部分
                $i=0;
                $mn_btn_pkid="";
                foreach ($bbb as $value) {
                    if (in_array($value, $ttt)) {
                        if($i==0)
                        {
                            $rmodel = new RMenuBtn();
                            $rmodel->opp_date = $post['opp_date'];
                            $rmodel->opp_ip = $post['opp_ip'];
                            $rmodel->opper = $post['opper'];
                            if(!$rmodel->save()){
                                throw new Exception(json_encode($rmodel->getFirstError(),JSON_UNESCAPED_UNICODE));
                            }
                            $mn_btn_pkid=$rmodel->attributes['mn_btn_pkid'];
                            $i++;
                        }
                        $model = new RMenuBtnDt();
                        $model->menu_pkid = $menu_pkid;
                        $model->btn_pkid = $value;
                        $model->mn_btn_pkid=$mn_btn_pkid;
                        if (!$model->save()) {
                            throw new Exception(json_encode($model->getFirstError(), JSON_UNESCAPED_UNICODE));
                        }
                    } else {
                        $dt_pkid = RMenuBtnDt::find()->andWhere(['menu_pkid' => $menu_pkid, 'btn_pkid' => $value])->select('dt_pkid')->one();
                        RRoleMnBtnDt::deleteAll(['dt_pkid' => $dt_pkid]);
                        RMenuBtnDt::deleteAll(['dt_pkid' => $dt_pkid]);
                    }
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            return $e->getMessage();
        }
        $transaction->commit();
        return $this->success();
    }

    //查询菜单所对应的按钮
    public function actionQueryMenuBtn($menu_pkid)
    {
        $data = RMenuBtnDt::find()->andWhere(['menu_pkid' => $menu_pkid])->select('btn_pkid')->distinct()->all();
        return $data;
    }
}