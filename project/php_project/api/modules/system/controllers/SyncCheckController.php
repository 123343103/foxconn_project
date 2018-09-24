<?php

namespace app\modules\system\controllers;

use app\controllers\BaseActiveController;

use Yii;
use yii\web\NotFoundHttpException;


/**
 * SyncCheck控制器
 */
class SyncCheckController extends BaseActiveController
{
    /**
     * index控制器
     */
    public $modelClass = true;

    public function actionCheck()
    {
        $post = Yii::$app->request->post();
        $sql = str_replace(PHP_EOL,'',$post['sql']);
        $arr = explode(';',$sql);
        foreach (array_filter($arr) as $key => $val){
            $res = Yii::$app->$post['database']->createCommand(trim($val))->queryAll();
            $list[$key]['rows'] = $res;
            $list[$key]["total"] = count($res);
        }

        return $list;
    }

}
