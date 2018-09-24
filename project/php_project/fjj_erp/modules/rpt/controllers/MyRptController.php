<?php

namespace app\modules\rpt\controllers;

use app\controllers\BaseController;
use yii;
use yii\helpers\Json;

class MyRptController extends BaseController
{
    private $_url = "rpt/my-rpt/";

    public function actionIndex()
    {
        $post = Yii::$app->request->queryParams;
        $post['uid'] = Yii::$app->user->id;
        $roles = Yii::$app->authManager->getRolesByUser($post['uid']);
        $url = $this->findApiUrl().$this->_url."index";
        if (!empty($post)) {
            $url .= "?" . http_build_query($post);
        }
        if (!empty($roles)) {
            foreach ($roles as $key=>$role) {
                $roleArr[] = $key;
            }
            $post['roles'] = $roleArr;
        }
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $myRpt = $curl->get($url);
        if (Yii::$app->request->isAjax) {
            return $myRpt;
        }
        return $this->render('index', [
        ]);
    }
}

