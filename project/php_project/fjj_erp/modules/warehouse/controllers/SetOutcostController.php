<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/12/9
 * Time: 下午 01:55
 */
namespace app\modules\warehouse\controllers;
use app\controllers\BaseController;
use yii\helpers\Url;

class SetOutcostController extends BaseController{
    private $_url = "warehouse/set-outcost/";  //对应api控制器URL

    public function actionIndex(){
        return $this->render("index");
    }
}