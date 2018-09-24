<?php
/**
 * User: F1677929
 * Date: 2017/2/18
 */
namespace app\modules\crm\controllers;
use app\classes\Menu;
use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
//活动控制器
class CrmActiveNameController extends BaseController
{
    //活动列表
    public function actionIndex()
    {
        if(Yii::$app->request->isAjax){
            $url=$this->findApiUrl().'crm/crm-active-name/index';
            $url.='?userId='.Yii::$app->user->identity->user_id;
            $url.='&companyId='.Yii::$app->user->identity->company_id;
            $url.='&'.http_build_query(Yii::$app->request->queryParams);
            $dataProvider=$this->findCurl()->get($url);
            if(Menu::isAction('/crm/crm-active-name/view')){
                $dataProvider=Json::decode($dataProvider);
                if(!empty($dataProvider['rows'])){
                    foreach($dataProvider['rows'] as &$val){
                        $val['actbs_code']="<a onclick='window.location.href=\"".Url::to(['view','nameId'=>$val['actbs_id']])."\";event.stopPropagation();'>".$val['actbs_code']."</a>";
                    }
                }
                return Json::encode($dataProvider);
            }
            return $dataProvider;
        }
        $data=$this->getField('/crm/crm-active-name/index');
        return $this->render('index',['data'=>$data]);
    }

    //新增活动
    public function actionAdd($flag='')
    {
        $url=$this->findApiUrl().'crm/crm-active-name/add?staffId='.Yii::$app->user->identity->staff_id;
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['CrmActiveName']['company_id']=Yii::$app->user->identity->company_id;
            $data['CrmActiveName']['create_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                SystemLog::addLog('新增活动'.$result['msg']);
                if($flag=='calendar'){
                    $url=Url::to(['/crm/crm-active-calendar/index']);
                }elseif($flag=='count'){
                    $url=Url::to(['/crm/crm-active-count/add','nameId'=>$result['data']]);
                }else{
                    $url=Url::to(['view','nameId'=>$result['data']]);
                }
                return Json::encode(['msg'=>'新增成功！','flag'=>1,'url'=>$url]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        return $this->render('add',['data'=>$data]);
    }

    //获取活动类型
    public function actionGetActiveType($wayId)
    {
        $url=$this->findApiUrl().'crm/crm-active-name/get-active-type?wayId='.$wayId;
        return $this->findCurl()->get($url);
    }

    //修改活动
    public function actionEdit($nameId,$flag='')
    {
        $url=$this->findApiUrl().'crm/crm-active-name/edit?staffId='.Yii::$app->user->identity->staff_id.'&nameId='.$nameId;
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['CrmActiveName']['update_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                SystemLog::addLog('修改活动'.$result['msg']);
                if($flag=='calendar'){
                    $url=Url::to(['/crm/crm-active-calendar/index']);
                }else{
                    $url=Url::to(['view','nameId'=>$result['data']]);
                }
                return Json::encode(['msg'=>'修改成功！','flag'=>1,'url'=>$url]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        if(empty($data['editData'])){
            throw new NotFoundHttpException('页面不存在！');
        }
        return $this->render('edit',['data'=>$data]);
    }

    //查看活动
    public function actionView($nameId,$from='')
    {
        $url=$this->findApiUrl().'crm/crm-active-name/view?nameId='.$nameId;
        $data=Json::decode($this->findCurl()->get($url));
        if(empty($data)){
            throw new NotFoundHttpException('页面不存在！');
        }
        return $this->render('view',['data'=>$data,'from'=>$from]);
    }

    //删除活动
    public function actionDeleteActiveName($nameId)
    {
        $url=$this->findApiUrl().'crm/crm-active-name/delete-active-name?nameId='.$nameId;
        $result=Json::decode($this->findCurl()->get($url));
        if($result['status']==1){
            SystemLog::addLog('删除活动'.$result['data']);
            return Json::encode(['msg'=>$result['msg'],'flag'=>1]);
        }
        return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
    }

    //取消活动
    public function actionCancelActive($nameId)
    {
        $url=$this->findApiUrl().'crm/crm-active-name/cancel-active';
        $url.='?nameId='.$nameId;
        $url.='&staffId='.Yii::$app->user->identity->staff_id;
        $result=Json::decode($this->findCurl()->get($url));
        if($result['status']==1){
            SystemLog::addLog('取消活动'.$result['data']);
            return Json::encode(['msg'=>$result['msg'],'flag'=>1]);
        }
        return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
    }

    //终止活动
    public function actionStopActive($nameId)
    {
        $url=$this->findApiUrl().'crm/crm-active-name/stop-active';
        $url.='?nameId='.$nameId;
        $url.='&staffId='.Yii::$app->user->identity->staff_id;
        $result=Json::decode($this->findCurl()->get($url));
        if($result['status']==1){
            SystemLog::addLog('终止活动'.$result['data']);
            return Json::encode(['msg'=>$result['msg'],'flag'=>1]);
        }
        return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
    }

    //地址联动
    public function actionGetDistrict($id)
    {
        $url=$this->findApiUrl().'crm/crm-active-name/get-district';
        $url.='?id='.$id;
        return $this->findCurl()->get($url);
    }
}