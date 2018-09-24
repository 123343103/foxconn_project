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
 * 媒体类型控制器
 */
class CrmMediaTypeController extends BaseController
{
    /**
     * 媒体类型列表
     */
    public function actionIndex()
    {
        if(Yii::$app->request->isAjax){
            $url=$this->findApiUrl().'crm/crm-media-type/index';
            $url.='?companyId='.Yii::$app->user->identity->company_id;
            $url.='&'.http_build_query(Yii::$app->request->queryParams);
            return $this->findCurl()->get($url);
        }
        return $this->render('index');
    }

    /**
     * 新增媒体类型
     */
    public function actionAdd()
    {
        $url=$this->findApiUrl().'crm/crm-media-type/add';
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['CrmMediaType']['company_id']=Yii::$app->user->identity->company_id;
            $data['CrmMediaType']['create_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                SystemLog::addLog('新增媒体类型'.$result['data']);
                return Json::encode(['msg'=>$result['msg'],'flag'=>1]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        return $this->renderAjax('add',['data'=>$data]);
    }

    /**
     * 修改媒体类型
     */
    public function actionEdit($id)
    {
        $url=$this->findApiUrl().'crm/crm-media-type/edit?id='.$id;
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['CrmMediaType']['update_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                SystemLog::addLog('修改媒体类型'.$result['data']);
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
     * 删除媒体类型
     */
    public function actionDeleteMedia($id)
    {
        $url=$this->findApiUrl().'crm/crm-media-type/delete-media?id='.$id;
        $result=Json::decode($this->findCurl()->get($url));
        if($result['status']==1){
            SystemLog::addLog('删除媒体类型'.$result['data']);
            return Json::encode(['msg'=>$result['msg'],'flag'=>1]);
        }
        return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
    }
}