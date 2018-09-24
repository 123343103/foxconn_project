<?php
/**
 * User: F1677929
 * Date: 2017/11/16
 */
namespace app\modules\spp\controllers;
use app\controllers\BaseController;
use Yii;

/**
 * 供应商弹窗模板控制器
 */
class SupplierPopTplController extends BaseController
{
    //权限过滤
    public function beforeAction($action)
    {
        $this->ignorelist=array_merge($this->ignorelist,[
            "/spp/supplier-pop-tpl/select-supplier",
        ]);
        return parent::beforeAction($action);
    }

    //获取供应商
    public function actionSelectSupplier()
    {
        if(Yii::$app->request->isAjax){
            $url=$this->findApiUrl().'spp/supplier-pop-tpl/select-supplier';
            $url.='?companyId='.Yii::$app->user->identity->company_id;
            $url.='&'.http_build_query(Yii::$app->request->queryParams);
            return $this->findCurl()->get($url);
        }
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render('select-supplier');
    }
}