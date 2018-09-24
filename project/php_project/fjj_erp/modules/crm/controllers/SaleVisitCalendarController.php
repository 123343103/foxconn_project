<?php
/**
 * User: F1677929
 * Date: 2017/9/30
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseController;
use Yii;
/**
 * 销售拜访行事历(行程日历)
 */
class SaleVisitCalendarController extends BaseController
{
    public function actionIndex()
    {
        if(Yii::$app->request->isAjax){
            $url=$this->findApiUrl().'crm/sale-visit-calendar/index';
            $url.='?companyId='.Yii::$app->user->identity->company_id;
            if(!Yii::$app->user->identity->is_supper){
                $url.='&staffId='.Yii::$app->user->identity->staff_id;
            }
            $url.='&'.http_build_query(Yii::$app->request->queryParams);
            return $this->findCurl()->get($url);
        }
        return $this->render('index');
    }
}