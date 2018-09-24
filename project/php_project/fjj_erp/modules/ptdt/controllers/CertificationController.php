<?php
/**
 * Created by PhpStorm.
 * User: F1677929
 * Date: 2016/9/12
 * Time: 上午 11:38
 */
namespace app\modules\ptdt\controllers;

use app\controllers\BaseController;

/**
 * 認證控制器
 * Class CertificationController
 * @package app\modules\ptdt\controllers
 */
class CertificationController extends BaseController
{
    /**
     * 新增认证
     * @return string
     */
    public function actionAdd()
    {
        return $this->render('add');
    }

    /**
     * 认证详情
     * @return string
     */
    public function actionDetail()
    {
        return $this->render('detail');
    }
}