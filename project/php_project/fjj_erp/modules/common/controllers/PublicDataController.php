<?php
/**
 * User: F1677929
 * Date: 2017/3/10
 */
namespace app\modules\common\controllers;
use app\controllers\BaseController;
use app\modules\common\models\BsPubdata;
use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
//公共数据控制器
class PublicDataController extends BaseController
{
    //公共参数列表
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        if(Yii::$app->request->isAjax){
            $url=$this->findApiUrl().'common/public-data/index';
            $url.='?'.http_build_query($params);
            return $this->findCurl()->get($url);
        }
        return $this->render('index',['params'=>$params]);
    }

    //公共参数详情
    public function actionView($val)
    {
        if(Yii::$app->request->isAjax){
            $url=$this->findApiUrl().'common/public-data/view';
            $params=Yii::$app->request->queryParams;
            $url.='?'.http_build_query($params);
            return $this->findCurl()->get($url);
        }
        return $this->render('view',['val'=>$val]);
    }

    //新增
    public function actionAdd($val)
    {
        $url=$this->findApiUrl().'common/public-data/add?val='.$val;
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['BsPubdata']['create_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                return Json::encode(['msg'=>'新增成功！','flag'=>1]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        return $this->renderAjax('add',['data'=>$data]);
    }

    //修改
    public function actionEdit($id)
    {
        $url=$this->findApiUrl().'common/public-data/edit?id='.$id;
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['BsPubdata']['update_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                return Json::encode(['msg'=>'修改成功！','flag'=>1]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        return $this->renderAjax('edit',['data'=>$data]);
    }

    //删除
    public function actionDeleteName($id)
    {
        $url=$this->findApiUrl().'common/public-data/delete-name?id='.$id;
        $result=Json::decode($this->findCurl()->get($url));
        if($result['status']==1){
            return Json::encode(['msg'=>'删除成功！','flag'=>1]);
        }
        else{
            return Json::encode(['msg'=>$result['msg']['bsp_svalue'][0],'flag'=>0]);
        }
    }
}