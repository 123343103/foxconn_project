<?php

namespace app\modules\system\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\SysCustomDesktop;
use yii;

class DesktopController extends BaseActiveController
{
    public $modelClass = 'app\modules\system\models\SysCustomDesktop';
    public function actionGetCustomSelectDesktop($uid){
        $result = SysCustomDesktop::find()->where(['uid'=>$uid])->all();
        return $result;
    }

    public function actionSaveCustomDesktop()
    {
        static $err = 0;
        $res['flag'] = 1;
        $res['mesg'] = "设置成功!";
        $post = Yii::$app->request->post();
        $uid = $post['uid'];
        SysCustomDesktop::deleteAll(['uid'=>$uid]);
        if (!empty($post['desktop'])) {
            foreach ($post['desktop'] as $k => $v) {
                $model = SysCustomDesktop::find()->where(['action_url'=>$v,'uid'=>$uid])->one();
                if (!$model) {
                    $model = new SysCustomDesktop();
                }
                $model->action_url = $v;
                $model->uid = $uid;
                $result = $model->save();
                if (!$result) {
                    $err++;
                }
                if ($err) {
                    $res['flag'] = 0;
                    $res['mesg'] = "有 $err 条设置项保存失败！";
                } else {
                    $res['flag'] = 1;
                    $res['mesg'] = "设置成功!";
                }
            }
        }
        return $res;
    }
}


