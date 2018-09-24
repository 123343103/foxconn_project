<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/8/7
 * Time: 上午 09:55
 */

namespace app\modules\warehouse\controllers;


use app\classes\Menu;
use app\controllers\BaseController;
use app\modules\warehouse\models\BsWh;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class SetPartController extends BaseController
{
    private $_url = "warehouse/bs-part/";  //对应api控制器URL

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) { //如果是分页获取数据则直接返回数据
            $data = Json::decode($dataProvider);
            if (Menu::isAction('/warehouse/set-part/view')) {
                //给请购单号添加单击事件
                $dataProvider = Json::decode($dataProvider);
                if (!empty($dataProvider['rows'])) {
                    foreach ($dataProvider['rows'] as &$val) {
                        $id=$val['part_id'];
                        $val['part_code'] = "<a class='partcode'  data-id='".$id."'>" . $val['part_code'] . "</a>";
                    }
                }
                return Json::encode($dataProvider);
            }
            return Json::encode($data);
        }
        $downList = $this->downList();
        $fields = $this->getField("/warehouse/set-part/index");
        $data = Json::decode($dataProvider);
//        dumpE($dataProvider);
        $data['table']=$this->getField('/warehouse/set-part/index');
        return $this->render('index', [
            'data' => $data,
            'downList' => $downList,
            'fields'=>$fields
        ]);
    }

    public function actionView($part_id)
    {
        $url = $this->findApiUrl() . $this->_url . "view?part_id=" . $part_id;
        $model = Json::decode($this->findCurl()->get($url));
//        dumpE($model);
        if ($model) {
//            dumpE($model);
            return $this->renderAjax("view", [
                'model' => $model
//                'downList' => $downList
            ]);
        } else {
            throw new NotFoundHttpException('页面未找到');
        }

    }

    public function actionAddEdit($part_id)
    {
        $downList = $this->downList();
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();

            $postData['BsPart']['OPPER'] = Yii::$app->user->identity->staff->staff_code;//操作人
            $postData['BsPart']['OPP_DATE'] = date('Y-m-d H:i:s', time());//操作时间
            $postData['BsPart']['opp_ip'] = Yii::$app->request->getUserIP();//'//获取ip地址
            $url = $this->findApiUrl() . $this->_url . "add-edit?part_id=" . $part_id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->put($url));
            if ($data->status == 1) {
                return Json::encode(['msg' => "操作成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "操作失败", 'flag' => 0]);
            }
        }
        if ($part_id == "add") {
            return $this->renderAjax('add-edit', [
                'downList' => $downList]);
        } else {
            $url = $this->findApiUrl() . $this->_url . "view?part_id=" . $part_id;
            $model = Json::decode($this->findCurl()->get($url));
//            dumpE($model);
            $ret= $this->getBsWhname($model[0]['wh_code']);
//            dumpE($ret);
            return $this->renderAjax('add-edit', [
                'model' => $model,
                'ret' => $ret,
                'downList' => $downList]);
        }
    }
    //设置状态
    public function actionSetCharacter($part_id)
    {
        $downList = $this->downList();
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();

            $postData['BsPart']['OPPER'] = Yii::$app->user->identity->staff->staff_code;//操作人
            $postData['BsPart']['OPP_DATE'] = date('Y-m-d H:i:s', time());//操作时间
            $postData['BsPart']['opp_ip'] = Yii::$app->request->getUserIP();//'//获取ip地址
            $url = $this->findApiUrl() . $this->_url . "set-character?part_id=" . $part_id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->put($url));
            if ($data->status == 1) {
                return Json::encode(['msg' => "操作成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "操作失败", 'flag' => 0]);
            }
        }
        if ($part_id == "setcharacter") {
            return $this->renderAjax('set-character', [
                'downList' => $downList]);
        } else {
            $url = $this->findApiUrl() . $this->_url . "view?part_id=" . $part_id;
            $model = Json::decode($this->findCurl()->get($url));
            return $this->renderAjax('set-character', [
                'model' => $model,
                'downList' => $downList]);
        }
    }

    public function actionDelete($partid)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?partid=" . $partid;
        $res = Json::decode($this->findCurl()->delete($url), false);
        if ($res->status == 1) {
            return Json::encode(['msg' => "删除成功", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "删除失败", "flag" => 0]);
        }
    }

    //启用禁用仓库
    public function actionOpenClose($part_id)
    {
        $url = $this->findApiUrl() . $this->_url . "open-close?part_id=" . $part_id;
        $res = Json::decode($this->findCurl()->delete($url), false);
        if ($res->status == 1) {
            return Json::encode(['msg' => "操作成功", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "操作成功", "flag" => 0]);
        }
    }

    //批量启用禁用仓库ss
    public function actionOpenClosess()
    {
        $queryParam = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "openss";
        $url .= "?" . http_build_query($queryParam);
        $res = Json::decode($this->findCurl()->delete($url), false);
//        dumpE($res);
        if ($res->status == 1) {
            return Json::encode(['msg' => "已启用", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "操作失败", "flag" => 0]);
        }
    }
    //批量禁用仓库
    public function actionClosess()
    {
        $queryParam = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "closess";
        $url .= "?" . http_build_query($queryParam);
        $res = Json::decode($this->findCurl()->delete($url), false);
//        dumpE($res);
        if ($res->status == 1) {
            return Json::encode(['msg' => "已禁用", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "操作失败", "flag" => 0]);
        }
    }


    private function downList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $a = $this->findCurl()->get($url);
        return Json::decode($this->findCurl()->get($url), true);
    }

    public function getBsWhname($wh_code)
    {
        $url = $this->findApiUrl() . $this->_url . "get-bs-whname?wh_code=".$wh_code;
        return Json::decode($this->findCurl()->get($url), true);
    }

}