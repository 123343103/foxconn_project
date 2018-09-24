<?php
/**
 * User: F1677929
 * Date: 2017/4/29
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseController;
use Yii;
/**
 * 活动设置控制器
 */
class CrmActiveSetController extends BaseController
{
    //活动设置列表
    public function actionIndex()
    {
        return $this->render('index');
    }
}