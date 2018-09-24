<?php
/**
 * User: F1677929
 * Date: 2017/9/23
 */
namespace app\modules\spp\controllers;
use app\classes\Menu;
use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * 供应商控制器
 */
class SupplierController extends BaseController
{
    //供应商列表
    public function actionIndex()
    {
        $url=$this->findApiUrl().'spp/supplier/index';
        if(Yii::$app->request->isAjax){
            $url.='?companyId='.Yii::$app->user->identity->company_id;
            $url.='&'.http_build_query(Yii::$app->request->queryParams);
//            return $this->findCurl()->get($url);
            $dataProvider=$this->findCurl()->get($url);
            if(Menu::isAction('/spp/supplier/view')){
                $dataProvider=Json::decode($dataProvider);
                if(!empty($dataProvider['rows'])){
                    foreach($dataProvider['rows'] as &$val){
                        $val['spp_fname']="<a onclick='window.location.href=\"".Url::to(['view','id'=>$val['spp_id']])."\";event.stopPropagation();'>".$val['spp_fname']."</a>";
                    }
                }
                return Json::encode($dataProvider);
            }
            return $dataProvider;
        }
        $data=json_decode($this->findCurl()->get($url),true);
        $data['table1']=$this->getField('/spp/supplier/index');
        return $this->render('index',['data'=>$data]);
    }

    //新增供应商
    public function actionAdd()
    {
        $url=$this->findApiUrl().'spp/supplier/add';
        if($data=Yii::$app->request->post()){
            $data['BsSupplier']['company_id']=Yii::$app->user->identity->company_id;
            $data['BsSupplier']['oper_id']=Yii::$app->user->identity->staff_id;
            $data['BsSupplier']['oper_time']=date('Y-m-d H:i:s');
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('新增供应商'.$result['data']['code']);
                return json_encode([
                    'msg'=>$result['msg'],
                    'flag'=>1,
                    'url'=>Url::to(['view','id'=>$result['data']['id']]),
                    'billId'=>$result['data']['id'],
                    'billTypeId'=>$result['data']['typeId']
                ]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=json_decode($this->findCurl()->get($url),true);
        return $this->render('add',['data'=>$data]);
    }

    //修改供应商
    public function actionEdit($id)
    {
        $url=$this->findApiUrl().'spp/supplier/edit';
        $url.='?id='.$id;
        if($data=Yii::$app->request->post()){
            $data['BsSupplier']['oper_id']=Yii::$app->user->identity->staff_id;
            $data['BsSupplier']['oper_time']=date('Y-m-d H:i:s');
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('修改供应商'.$result['data']['code']);
                return json_encode([
                    'msg'=>$result['msg'],
                    'flag'=>1,
                    'url'=>Url::to(['view','id'=>$result['data']['id']]),
                    'billId'=>$result['data']['id'],
                    'billTypeId'=>$result['data']['typeId']
                ]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=json_decode($this->findCurl()->get($url),true);
        if(empty($data['editData'])){
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('edit',['data'=>$data]);
    }

    //查看供应商
    public function actionView($id)
    {
        $url=$this->findApiUrl().'spp/supplier/view';
        $url.='?id='.$id;
        $data=json_decode($this->findCurl()->get($url),true);
        if(empty($data)){
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('view',['viewData'=>$data]);
    }

    //删除供应商
    public function actionDeleteSupplier($id)
    {
        $url=$this->findApiUrl().'spp/supplier/delete-supplier';
        $url.='?id='.$id;
        $result=json_decode($this->findCurl()->get($url),true);
        SystemLog::addLog('删除供应商');
        return json_encode(['msg'=>$result['msg'],'flag'=>1]);
    }

    //地址联动
    public function actionGetDistrict()
    {
        $url=$this->findApiUrl().'spp/supplier/get-district';
        $url.='?'.http_build_query(Yii::$app->request->queryParams);
        return $this->findCurl()->get($url);
    }

    //获取联系人
    public function actionGetContacts()
    {
        $url=$this->findApiUrl().'spp/supplier/get-contacts';
        $url.='?'.http_build_query(Yii::$app->request->queryParams);
        return $this->findCurl()->get($url);
    }

    //获取供应商主营商品
    public function actionGetMainProduct()
    {
        $url=$this->findApiUrl().'spp/supplier/get-main-product';
        $url.='?'.http_build_query(Yii::$app->request->queryParams);
        return $this->findCurl()->get($url);
    }

    //获取拟采购商品
    public function actionGetPurchaseProduct()
    {
        $url=$this->findApiUrl().'spp/supplier/get-purchase-product';
        $url.='?'.http_build_query(Yii::$app->request->queryParams);
        return $this->findCurl()->get($url);
    }

    //获取签核记录
    public function actionGetCheckRecord($billId,$billTypeId)
    {
        $url=$this->findApiUrl().'spp/supplier/get-check-record';
        $url.='?billId='.$billId;
        $url.='&billTypeId='.$billTypeId;
        return $this->findCurl()->get($url);
    }

    //抓取供应商数据
    public function actionGetSupplier()
    {
        if($data=Yii::$app->request->post()){
            ini_set('max_execution_time',0);
            $url=$this->findApiUrl().'spp/supplier/get-supplier';
            $data['BsSupplier']['company_id']=Yii::$app->user->identity->company_id;
            $data['BsSupplier']['oper_id']=Yii::$app->user->identity->staff_id;
            $data['BsSupplier']['oper_time']=date('Y-m-d H:i:s');
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('抓取供应商');
                return json_encode(['msg'=>$result['msg'],'flag'=>1]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render('_get-sup');
    }

    //选择料号
    public function actionSelectPno()
    {
        $url=$this->findApiUrl().'spp/supplier/select-pno';
        $url.='?'.http_build_query(Yii::$app->request->queryParams);
        return $this->findCurl()->get($url);
    }
}