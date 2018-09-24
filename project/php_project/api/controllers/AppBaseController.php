<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\HttpBasicAuth;
use Yii;
/**
 * 基类
 * F1676624
 * 2017/2/8
 */
class AppBaseController extends ActiveController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBasicAuth::className(),
                HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],];
        return $behaviors;
    }

    protected function error($msg = null, $data = null)
    {
        return ['status' => 0, "msg" => $msg, "data" => $data];
    }

    protected function success($msg = null, $data = null)
    {
        return ['status' => 1, "msg" => $msg, "data" => $data];
    }

    public function actions()
    {
        return [];
    }
    public function init(){
        parent::init();
        Yii::$app->user->enableSession = false;
    }

}