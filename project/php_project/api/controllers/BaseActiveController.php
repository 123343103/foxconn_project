<?php
namespace app\controllers;

use app\models\User;
use app\modules\common\models\BsDistrict;
use app\modules\crm\models\CrmActImessage;
use app\modules\system\models\SystemLog;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\rest\ActiveController;

/**
 * 基类
 * F3858995
 * 2016/11/11
 */
class BaseActiveController extends ActiveController
{

    public function error($msg = null, $data = null)
    {
        return ['status' => 0, "msg" => $msg, "data" => $data];
    }

    public function success($msg = null, $data = null)
    {
        return ['status' => 1, "msg" => $msg, "data" => $data];
    }

    protected function addLog($msg)
    {
        SystemLog::addLog($msg);
    }

    public function actions()
    {
        return [];
    }

    /**
     * 2017-4-18
     * F1678688 李方波
     * 统一字段验证类
     * @param $id   字段惟一标识
     * @param $attr 验证属性
     * @param $val  验证属性值
     */
    public function actionValidate($id, $attr, $val, $scenario)
    {
        $class = $this->modelClass;//默认使用moduleClass作为验证类
        $model = new $class();
        if ($id) {
            $model = $class::findOne($id);
        } else {
            $model->setScenario($scenario);
        }
        $model->$attr = urldecode($val);
        $model->validate($attr);
        return $model->getFirstError($attr) ? $model->getFirstError($attr) : "";
        //返回字段的验证结果
    }

    public function actionSendMessage()
    {
        $params = \Yii::$app->request->post();
        $model = new CrmActImessage();
        $model->load($params);
        $model->imesg_sentdate = date("Y-m-d H:i:s");
        if (!($model->validate() && $model->save())) {
            return $this->error();
        }
        return $this->success();
    }

}