<?php
/**
 * User: F1677929
 * Date: 2017/6/1
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/**
 * 载体控制器
 */
class CrmCarrierController extends BaseController
{
    /**
     * 载体列表
     */
    public function actionIndex()
    {
        if(Yii::$app->request->isAjax){
            $url=$this->findApiUrl().'crm/crm-carrier/index';
            $url.='?companyId='.Yii::$app->user->identity->company_id;
            $params=Yii::$app->request->queryParams;
            $url.='&'.http_build_query($params);
            return $this->findCurl()->get($url);
        }
        return $this->render('index');
    }

    /**
     * 新增载体
     */
    public function actionAdd()
    {
        $url=$this->findApiUrl().'crm/crm-carrier/add';
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['CrmCarrier']['company_id']=Yii::$app->user->identity->company_id;
            $data['CrmCarrier']['create_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                SystemLog::addLog('新增载体'.$result['data']);
                return Json::encode(['msg'=>$result['msg'],'flag'=>1]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        return $this->renderAjax('add',['data'=>$data]);
    }

    /**
     * 修改载体
     */
    public function actionEdit($id)
    {
        $url=$this->findApiUrl().'crm/crm-carrier/edit?id='.$id;
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['CrmCarrier']['update_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                SystemLog::addLog('修改载体'.$result['data']);
                return Json::encode(['msg'=>$result['msg'],'flag'=>1]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        if(empty($data['editData'])){
            throw new NotFoundHttpException('页面不存在！');
        }
        return $this->renderAjax('edit',['data'=>$data]);
    }

    /**
     * 删除载体
     */
    public function actionDeleteCarrier($id)
    {
        $url=$this->findApiUrl().'crm/crm-carrier/delete-carrier?id='.$id;
        $result=Json::decode($this->findCurl()->get($url));
        if($result['status']==1){
            SystemLog::addLog('删除载体'.$result['data']);
            return Json::encode(['msg'=>$result['msg'],'flag'=>1]);
        }
        return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
    }
}