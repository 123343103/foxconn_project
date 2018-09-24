<?php
/**
 * User: F1677929
 * Date: 2017/6/1
 */
namespace app\modules\crm\controllers;
use app\classes\Menu;
use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * 活动统计控制器
 */
class CrmActiveCountController extends BaseController
{
    /**
     * 所有操作执行之前执行
     */
    public function beforeAction($action)
    {
        $this->ignorelist=array_merge($this->ignorelist,[
            "/crm/crm-active-count/select-active",
            "/crm/crm-active-count/view-count",
            "/crm/crm-active-count/view-counts"
        ]);
        return parent::beforeAction($action);
    }

    /**
     * 活动统计列表
     */
    public function actionIndex()
    {
        $url=$this->findApiUrl().'crm/crm-active-count/index';
        if(Yii::$app->request->isAjax){
            $url.='?companyId='.Yii::$app->user->identity->company_id;
            $url.='&'.http_build_query(Yii::$app->request->queryParams);
            $dataProvider=Json::decode($this->findCurl()->get($url));
            if(!empty($dataProvider['rows'])){
                foreach($dataProvider['rows'] as &$val){
                    if(Menu::isAction('/crm/crm-active-count/view')){
                        $val['actch_code']="<a onclick='window.location.href=\"".Url::to(['view-counts','mainId'=>$val['actch_id']])."\";event.stopPropagation();'>".$val['actch_code']."</a>";
                    }
                    $val['actbs_start_time']=substr($val['actbs_start_time'],0,16);
                    $val['actbs_end_time']=substr($val['actbs_end_time'],0,16);
                }
            }
            return Json::encode($dataProvider);
        }
        $data=json_decode($this->findCurl()->get($url),true);
        $data['mainTable']=$this->getField('/crm/crm-active-count/index');
        return $this->render('index',['data'=>$data]);
    }

    /**
     * 加载活动统计信息
     */
    public function actionLoadCount()
    {
        $url=$this->findApiUrl().'crm/crm-active-count/load-count';
        $url.='?'.http_build_query(Yii::$app->request->queryParams);
        return $this->findCurl()->get($url);
    }

    /**
     * 新增活动统计
     */
    public function actionAdd($nameId='')
    {
        $url=$this->findApiUrl().'crm/crm-active-count/add';
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['CrmActiveCount']['company_id']=Yii::$app->user->identity->company_id;
            $data['CrmActiveCount']['create_by']=Yii::$app->user->identity->staff_id;
            $data['CrmActiveCountChild']['create_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                SystemLog::addLog('新增活动统计'.$result['msg']);
                return Json::encode(['msg'=>'新增成功！','flag'=>1,'url'=>Url::to(['view-count','childId'=>$result['data']])]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $url.='?nameId='.$nameId;
        $data=Json::decode($this->findCurl()->get($url));
        if((!empty($nameId))&&empty($data['activeData'])){
            throw new NotFoundHttpException('页面不存在！');
        }
        return $this->render('add',['data'=>$data]);
    }

    /**
     * 修改活动统计
     */
    public function actionEdit($childId)
    {
        $url=$this->findApiUrl().'crm/crm-active-count/edit?childId='.$childId;
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['CrmActiveCount']['update_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                SystemLog::addLog('修改活动统计'.$result['msg']);
                return Json::encode(['msg'=>'修改成功！','flag'=>1,'url'=>Url::to(['view-count','childId'=>$result['data']])]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        if(empty($data['editData'])){
            throw new NotFoundHttpException('页面不存在！');
        }
        return $this->render('edit',['data'=>$data]);
    }

    /**
     * 选择活动
     */
    public function actionSelectActive()
    {
        if(Yii::$app->request->isAjax){
            $url=$this->findApiUrl().'crm/crm-active-count/select-active';
            $url.='?companyId='.Yii::$app->user->identity->company_id;
            $url.='&'.http_build_query(Yii::$app->request->queryParams);
            return $this->findCurl()->get($url);
        }
        return $this->renderAjax('select-active');
    }

    /**
     * 查看活动统计
     */
//    public function actionView($mainId)
//    {
//        $url=$this->findApiUrl().'crm/crm-active-count/view';
//        $url.='?mainId='.$mainId;
//        $data=json_decode($this->findCurl()->get($url),true);
//        if(empty($data)||empty($data['activeData'])){
//            throw new NotFoundHttpException('页面不存在！');
//        }
//        return $this->render('view',['data'=>$data]);
//    }

    /**
     * 查看一条活动统计
     */
    public function actionViewCount($childId)
    {
        $url=$this->findApiUrl().'crm/crm-active-count/view-count';
        $url.='?childId='.$childId;
        $data=json_decode($this->findCurl()->get($url),true);
        if(empty($data['countInfo'])){
            throw new NotFoundHttpException('页面不存在！');
        }
        return $this->render('view-count',['data'=>$data]);
    }

    /**
     * 查看所有活动统计
     */
    public function actionViewCounts($mainId)
    {
        $url=$this->findApiUrl().'crm/crm-active-count/view-counts';
        $url.='?mainId='.$mainId;
        $data=json_decode($this->findCurl()->get($url),true);
        if(empty($data['activeInfo'])){
            throw new NotFoundHttpException('页面不存在！');
        }
        return $this->render('view-counts',['data'=>$data]);
    }

    /**
     * 删除一条活动统计
     */
    public function actionDeleteChild($childId)
    {
        $url=$this->findApiUrl().'crm/crm-active-count/delete-child';
        $url.='?childId='.$childId;
        $isSupper=Yii::$app->user->identity->is_supper;
        if($isSupper==1){
            $url.='&isSupper='.$isSupper;
        }
        $result=Json::decode($this->findCurl()->get($url));
        if($result['status']==1){
            SystemLog::addLog('删除活动统计'.$result['msg']);
            return Json::encode(['msg'=>'删除成功！','flag'=>1,'last'=>$result['data']]);
        }
        return Json::encode(['msg'=>'删除失败！','flag'=>0]);
    }

    /**
     * 删除所有活动统计
     */
    public function actionDeleteMain($mainId)
    {
        $url=$this->findApiUrl().'crm/crm-active-count/delete-main';
        $url.='?mainId='.$mainId;
        $result=Json::decode($this->findCurl()->get($url));
        if($result['status']==1){
            SystemLog::addLog('删除活动统计'.$result['msg']);
            return Json::encode(['msg'=>'删除成功！','flag'=>1]);
        }
        return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
    }
}