<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\controllers;
use app\classes\Menu;
use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use yii\helpers\Json;
use yii\helpers\Url;
use Yii;
use yii\web\NotFoundHttpException;
//活动类型设置控制器
class CrmActiveTypeController extends BaseController
{
    //活动类型列表
    public function actionIndex()
    {
        $url=$this->findApiUrl().'crm/crm-active-type/index';
        if(Yii::$app->request->isAjax){
            $url.='?companyId='.Yii::$app->user->identity->company_id;
            $url.='&'.http_build_query(Yii::$app->request->queryParams);
            $dataProvider=$this->findCurl()->get($url);
            if(Menu::isAction('/crm/crm-active-type/view')){
                $dataProvider=Json::decode($dataProvider);
                if(!empty($dataProvider['rows'])){
                    foreach($dataProvider['rows'] as &$val){
                        $val['acttype_code']="<a class='type_code' data-id='".$val['acttype_id']."'>".$val['acttype_code']."</a>";
                    }
                }
                return Json::encode($dataProvider);
            }
            return $dataProvider;
        }
        $flag=json_decode($this->findCurl()->get($url));
        $data=$this->getField('/crm/crm-active-type/index');
        return $this->render('index',['data'=>$data,'flag'=>$flag]);
    }

    //新增活动类型
    public function actionAdd()
    {
        $url=$this->findApiUrl().'crm/crm-active-type/add';
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['CrmActiveType']['company_id']=Yii::$app->user->identity->company_id;
            $data['CrmActiveType']['create_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                SystemLog::addLog('新增活动类型'.$result['data']);
                return Json::encode(['msg'=>$result['msg'],'flag'=>1]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        return $this->renderAjax('add',['data'=>$data]);
    }

    //修改活动类型
    public function actionEdit($typeId)
    {
        $url=$this->findApiUrl().'crm/crm-active-type/edit?typeId='.$typeId;
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['CrmActiveType']['update_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                SystemLog::addLog('修改活动类型'.$result['data']);
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

    //查看活动类型
    public function actionView($typeId)
    {
        $url=$this->findApiUrl().'crm/crm-active-type/view?typeId='.$typeId;
        $data=Json::decode($this->findCurl()->get($url));
        if(empty($data)){
            throw new NotFoundHttpException('页面不存在！');
        }
        return $this->renderAjax('view',['data'=>$data]);
    }

    //删除活动类型
    public function actionDeleteActiveType($typeId)
    {
        $url=$this->findApiUrl().'crm/crm-active-type/delete-active-type?typeId='.$typeId;
        $result=Json::decode($this->findCurl()->get($url));
        if($result['status']==1){
            SystemLog::addLog('删除活动类型'.$result['data']);
            return Json::encode(['msg'=>$result['msg'],'flag'=>1]);
        }
        return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
    }

    //获取活动类型
    public function actionGetActiveType($id='')
    {
        $url=$this->findApiUrl().'crm/crm-active-type/get-active-type';
        $url.='?companyId='.Yii::$app->user->identity->company_id;
        $url.='&id='.$id;
        return $this->findCurl()->get($url);
    }
}