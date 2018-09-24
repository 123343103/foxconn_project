<?php

namespace app\modules\system\controllers;

use app\controllers\BaseActiveController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;


/**
 * log控制器
 */
class SystemLogController extends BaseActiveController
{
    public $modelClass=true;

    public function actionCreate()
    {
        $model= New SystemLog();
        $post=Yii::$app->request->post();
        $model->load($post);
        $model->save();

    }



}
