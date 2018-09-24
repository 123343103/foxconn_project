<?php
/**
 * User: F1677929
 * Date: 2017/6/23
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseController;
/**
 * 活动行事历控制器
 */
class CrmActiveCalendarController extends BaseController
{
    /**
     * 活动行事历列表
     */
    public function actionIndex()
    {
        $url=$this->findApiUrl().'crm/crm-active-calendar/index';
        if(\Yii::$app->request->isAjax){
            $url.='?companyId='.\Yii::$app->user->identity->company_id;
            $params=\Yii::$app->request->queryParams;
            $url.='&'.http_build_query($params);
            return $this->findCurl()->get($url);
        }
        $data=json_decode($this->findCurl()->get($url),true);
        return $this->render('index',['data'=>$data]);
    }
}