<?php
/**
 * User: F1677929
 * Date: 2016/9/12
 */
namespace app\modules\ptdt\controllers;
use app\classes\Menu;
use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
//厂商拜访履历控制器
class VisitResumeController extends BaseController
{
    //厂商拜访履历列表
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        $url=$this->findApiUrl().'ptdt/visit-resume/index?userId='.Yii::$app->user->identity->user_id;
        if(Yii::$app->request->isAjax){
            $url.='&companyId='.Yii::$app->user->identity->company_id;
            $url.='&'.http_build_query($params);
            $dataProvider=$this->findCurl()->get($url);
            if(Menu::isAction('/ptdt/visit-resume/view')){
                $dataProvider=Json::decode($dataProvider);
                if(!empty($dataProvider['rows'])){
                    foreach($dataProvider['rows'] as &$val){
                        $val['vih_code']="<a onclick='window.location.href=\"".Url::to(['view-resumes','mainId'=>$val['vih_id']])."\";event.stopPropagation();'>".$val['vih_code']."</a>";
                    }
                }
                return Json::encode($dataProvider);
            }
            return $dataProvider;
        }
        $data=Json::decode($this->findCurl()->get($url));
        $data['mainTable']=$this->getField('/ptdt/visit-resume/index');
        $data['childTable']=$this->getField('/ptdt/visit-resume/load-resume');
        return $this->render('index',['params'=>$params,'data'=>$data]);
    }

    //加载履历子表
    public function actionLoadResume()
    {
        $params=Yii::$app->request->queryParams;
        $url=$this->findApiUrl().'ptdt/visit-resume/load-resume';
        $url.='?'.http_build_query($params);
        $dataProvider=$this->findCurl()->get($url);
        if(Menu::isAction('/ptdt/visit-resume/view')){
            $dataProvider=Json::decode($dataProvider);
            if(!empty($dataProvider['rows'])){
                foreach($dataProvider['rows'] as &$val){
                    $val['vil_code']="<a onclick='window.location.href=\"".Url::to(['view-resume','childId'=>$val['vil_id']])."\";event.stopPropagation();'>".$val['vil_code']."</a>";
                }
            }
            return Json::encode($dataProvider);
        }
        return $dataProvider;
    }

    //新增拜访履历
    public function actionAdd($firmId='',$planId='')
    {
        $url=$this->findApiUrl().'ptdt/visit-resume/add?visitPersonId='.Yii::$app->user->identity->staff_id.'&firmId='.$firmId.'&planId='.$planId;
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['PdVisitResume']['create_by']=Yii::$app->user->identity->staff_id;
            $data['PdVisitResume']['company_id']=Yii::$app->user->identity->company_id;
            $data['PdVisitResumeChild']['create_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                if(empty($result['msg']['mainCode'])){
                    SystemLog::addLog('厂商拜访履历子表新增,编号：'.$result['msg']['childCode']);
                }else{
                    SystemLog::addLog('厂商拜访履历主表新增,编号：'.$result['msg']['mainCode'].';厂商拜访履历子表新增,编号：'.$result['msg']['childCode']);
                }
                return Json::encode(['msg'=>'新增成功！','flag'=>1,'url'=>Url::to(['view-resume','childId'=>$result['data']])]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        if(!empty($firmId)&&empty($data['firmInfo'])){
            throw new NotFoundHttpException('页面未找到！');
        }
        if(!empty($planId)&&empty($data['visitPlan'])){
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('add',['data'=>$data]);
    }

    //修改拜访履历
    public function actionEdit($childId)
    {
        $url=$this->findApiUrl().'ptdt/visit-resume/edit?visitPersonId='.Yii::$app->user->identity->staff_id.'&childId='.$childId;
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['PdVisitResume']['update_by']=Yii::$app->user->identity->staff_id;
            $data['PdVisitResumeChild']['update_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                SystemLog::addLog('厂商拜访履历子表修改,编号：'.$result['msg']);
                return Json::encode(['msg'=>'修改成功！','flag'=>1,'url'=>Url::to(['view-resume','childId'=>$childId])]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        if(empty($data['resumeChild'])){
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('edit',['data'=>$data]);
    }

    //选择厂商
    public function actionSelectFirm()
    {
        $params=Yii::$app->request->queryParams;
        if(Yii::$app->request->isAjax){
            $url=$this->findApiUrl().'ptdt/visit-resume/select-firm?companyId='.Yii::$app->user->identity->company_id.'&'.http_build_query($params);
            return $this->findCurl()->get($url);
        }
        return $this->renderAjax('select-firm',['params'=>$params]);
    }

    //选择拜访计划
    public function actionSelectPlan($firmId)
    {
        $params=Yii::$app->request->queryParams;
        if(Yii::$app->request->isAjax){
            $url=$this->findApiUrl().'ptdt/visit-resume/select-plan?companyId='.Yii::$app->user->identity->company_id.'&'.http_build_query($params);
            return $this->findCurl()->get($url);
        }
        return $this->renderAjax('select-plan',['firmId'=>$firmId,'params'=>$params]);
    }

    //查看一条拜访履历
    public function actionViewResume($childId)
    {
        $url=$this->findApiUrl().'ptdt/visit-resume/view-resume?childId='.$childId;
        $data=Json::decode($this->findCurl()->get($url));
        if(empty($data['resumeChild'])){
            throw new NotFoundHttpException('页面不存在！');
        }
        return $this->render('view-resume',['data'=>$data]);
    }

    //查看所有拜访履历
    public function actionViewResumes($mainId)
    {
        $url=$this->findApiUrl().'ptdt/visit-resume/view-resumes?mainId='.$mainId;
        $data=Json::decode($this->findCurl()->get($url));
        if(empty($data['resumeMain'])){
            throw new NotFoundHttpException('页面不存在！');
        }
        return $this->render('view-resumes',['data'=>$data]);
    }

    //刪除子表
    public function actionDeleteChild($childId)
    {
        $url=$this->findApiUrl().'ptdt/visit-resume/delete-child?childId='.$childId;
        $result=Json::decode($this->findCurl()->get($url));
        if($result['status']==1){
            if($result['data']==1){
                SystemLog::addLog('厂商拜访履历主表删除,编号：'.$result['msg']['mainCode'].';厂商拜访履历子表删除,编号：'.$result['msg']['childCode']);
            }else{
                SystemLog::addLog('厂商拜访履历子表删除编号：'.$result['msg']['childCode']);
            }
            return Json::encode(['msg'=>'删除成功！','flag'=>1,'total'=>$result['data']]);
        }
        return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
    }

    //拜访完成
    public function actionVisitComplete($mainId)
    {
        $url=$this->findApiUrl().'ptdt/visit-resume/visit-complete?mainId='.$mainId;
        $result=Json::decode($this->findCurl()->get($url));
        if($result['status']==1){
            SystemLog::addLog('厂商拜访履历主表拜访完成,编号：'.$result['msg']);
            return Json::encode(['msg'=>'拜访完成！','flag'=>1]);
        }
        return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
    }
}