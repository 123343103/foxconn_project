<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/11/21
 * Time: 上午 10:08
 */

namespace app\modules\system\controllers;

use app\controllers\BaseController;
use app\modules\system\models\RMenuBtnDt;
use yii\helpers\Url;
use yii\helpers\Json;

class MenuPowerController extends BaseController
{
    private $_url = "system/menu-power/";//对应api控制器

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $param = \Yii::$app->request->queryParams;
        if (!empty($param)) {
            $url .= "?" . http_build_query($param);
        }
        $dataProvider = json_decode($this->findCurl()->get($url), true);
//        dumpE($dataProvider);
        if (\Yii::$app->request->isAjax) {
            return $dataProvider;
        }

        return $this->render('index', ['search' => $param['MenuSearch'], 'dataProvider' => $dataProvider]);
    }

    //修改 增加
    public function actionUpdateAdd()
    {
        if (\Yii::$app->request->getIsPost()) {
            $opper=\Yii::$app->user->identity->staff_id;
            $opp_date=date('Y-m-d H:i:s',time());
            $opp_ip=\Yii::$app->request->getUserIP();
            $postData = \Yii::$app->request->post();
            $postData['opper']=$opper;
            $postData['opp_date']=$opp_date;
            $postData['opp_ip']=$opp_ip;
            $url = $this->findApiUrl() . $this->_url . "add-menu";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                return Json::encode(['msg' => '保存成功', 'flag' => 1, 'url' => Url::to(['index'])]);
            }
        }
        $menuPkid = \Yii::$app->request->get('menuPkid');
        $type = \Yii::$app->request->get('type');
        $i=\Yii::$app->request->get('i');
        $menuData = null;
        if (isset($menuPkid)) {
            $menuPkid=trim($menuPkid);
            $url = $this->findApiUrl() . $this->_url . "query-info?menuPkid=" . $menuPkid;
            $menuData = Json::decode($this->findCurl()->get($url));
        }
        if ($type == 0) {
            $url = $this->findApiUrl() . $this->_url . "one-menu";
            $data = json_decode($this->findCurl()->get($url), true);
        } else {
            $url = $this->findApiUrl() . $this->_url . "one-menu?menuPkid=" . $menuPkid;
            $data = json_decode($this->findCurl()->get($url), true);
        }
        $this->layout = '@app/views/layouts/ajax';
        return $this->render('update_add', ['data' => $data, 'type' => $type, 'menuData' => $menuData,'i'=>$i]);
    }

    //操作设置
    public function actionOperaSet($menu_pkid = null, $menu_name = null)
    {
        if (\Yii::$app->request->getIsPost()) {
            $opper=\Yii::$app->user->identity->staff_id;
            $opp_date=date('Y-m-d H:i:s',time());
            $opp_ip=\Yii::$app->request->getUserIP();
            $post = \Yii::$app->request->post();
            $post['opper']=$opper;
            $post['opp_date']=$opp_date;
            $post['opp_ip']=$opp_ip;
            $url = $this->findApiUrl() . $this->_url . "insert-btn";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                return Json::encode(['msg' => '操作成功', 'flag' => 1, 'url' => Url::to(['index'])]);
            }
        }
        $url = $this->findApiUrl() . $this->_url . "opera-set";
        $data = json_decode($this->findCurl()->get($url), true);
        $url = $this->findApiUrl() . $this->_url . "query-menu-btn?menu_pkid=" . trim($menu_pkid);
        $allBtn = json_decode($this->findCurl()->get($url), true);
        return $this->render('opera_set', ['data' => $data, 'menu_pkid' => $menu_pkid, 'allBtn' => $allBtn, 'menu_name' => $menu_name]);
    }
}