<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/7/20
 * Time: 上午 09:47
 */
namespace app\modules\warehouse\controllers;

use app\controllers\BaseController;
use yii;
use yii\helpers\Json;
use yii\bootstrap\Html;

class AllocationController extends BaseController
{
    private $_url = 'warehouse/allocation/';
    public function actionIndex()
    {
        $url = $this->findApiUrl() . "/warehouse/allocation/index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        $get = Yii::$app->request->get();
        if (!isset($get['InvChangehSearch'])) {
            $get['InvChangehSearch'] = null;
        }
        if (Yii::$app->request->isAjax) {
            foreach($dataProvider['rows'] as $key=>$val)
            {
                $dataProvider['rows'][$key]['chh_code']= Html::a($dataProvider['rows'][$key]['chh_code'],['views', 'id' => $dataProvider['rows'][$key]['chh_id']],['class'=>'viewitem']);
            }
            return Json::encode($dataProvider);
        }
        $whname=$this->actionWhname();//仓库
        $_usertype = $this->getUserType();  //获取用户权限
        $businessname=$this->actionBusinessTypeName();//调拨类型
        if (\Yii::$app->request->get('export')) {
            $this->getField(Json::decode($dataProvider)['rows']);
        }
//        print_r($dataProvider);
        return $this->render('index', [
            'whname'=>$whname,
            'businessname'=>$businessname,
            'usertype'=>$_usertype
        ]);
    }
    //加载商品信息
    public function actionLoadProduct($id)
    {
        $url=$this->findApiUrl().'warehouse/allocation/relation-commodity?id='.$id;
//        $url.='?'.http_build_query(Yii::$app->request->queryParams);
        return $this->findCurl()->get($url);
    }

    //获取商品信息
    public function actionGetProductInfo($id,$pdt_no)
    {
        $url=$this->findApiUrl().'warehouse/allocation/relation-commodity?id='.$id.'&pdt_no='.$pdt_no;
//        $url.='?'.http_build_query(Yii::$app->request->queryParams);
//        print_r($this->findCurl()->get($url));
        return $this->findCurl()->get($url);
    }

    //删除
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "deletes?id=" . $id;
        $result = $this->findCurl()->delete($url);
        if (json_decode($result)->status == 1) {
//            SystemLog::addLog("刪除了ID=".$id.'的信息');
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失败", "flag" => 0]);
        }
    }

    //生成入库单
    public function actionInWare($id)
    {
        $sta=Yii::$app->user->identity->staff_id;
        $url = $this->findApiUrl() . $this->_url . "in-ware?id=" . $id;
        $url.='&staff='.$sta;
        $result=json_decode($this->findCurl()->get($url),true);
//        dumpE($result);
        if ($result['status'] == 1) {
            return Json::encode(["msg" => "生成入库通知成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "插入失败", "flag" => 0]);
        }
    }

    //新增
    public function actionAdd()
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "add";
            $postData['InvChangeh']['comp_id']=Yii::$app->user->identity->company_id;
            $postData['InvChangeh']['create_by']=Yii::$app->user->identity->staff_id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
//            print_r($data);
            if ($data['status'] == 1) {
//                SystemLog::addLog($data['data']['msg']);
                return Json::encode(['msg' => "新增成功!", "flag" => 1, "id"=>$data['data']['id'],"chh_type"=>$data['data']['chh_type'],"url" => yii\helpers\Url::to(['views','id'=>$data['data']['id']])]);
            } else {
                return Json::encode(['msg' => "新增失败!", "flag" => 0]);
            }
        }
        else {
            $whname = $this->actionWhname();//仓库
            $dpt=$this->getorgniozation(); //获取所存在权限的调拨单位
            $_usertype = $this->getUserType();  //获取用户权限
            $businessname = $this->actionBusinessTypeName();//调拨类型
            $StCode = $this->actionStCode();//仓储码
            $url = $this->findApiUrl() . "hr/staff/view?id=" . Yii::$app->user->identity->staff_id;
            $dataProvider = Json::decode($this->findCurl()->get($url));
//            dumpE($dataProvider);
            return $this->render('add',
                [
                    'whname' => $whname,
                    'businessname' => $businessname,
                    'StCode' => $StCode,
                    'model' => $dataProvider,
                    'dpt' => $dpt,
                    'usertype'=>$_usertype,
                ]);
        }
    }

    //查看详情
    public function actionViews($id)
    {
        $data = $this->getModel($id);
//        dumpE($model);
        $verify=$this->getVerify($id,36);
        return $this->render('views', [
                'data'=>$data,
                'verify'=>$verify,
                'id'=>$id
            ]);
    }

    //修改
    public function actionEdit($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "update?id=".$id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
//                SystemLog::addLog($data['data']['msg']);
                return Json::encode(['msg' => "修改成功!", "flag" => 1,'id'=>$data['data']['id'],'type'=>$data['data']['chh_type'], "url" => yii\helpers\Url::to(['views','id'=>$data['data']['id']])]);
            } else {
                return Json::encode(['msg' => "修改失败!", "flag" => 0]);
            }
        }
        else {
            $data = $this->getModel($id);
//            dumpE($data);
            $whname = $this->actionWhname();//仓库
            $dpt=$this->getorgniozation(); //获取所存在权限的调拨单位
//            dumpE($dpt);
            $_usertype = $this->getUserType();  //获取用户权限
            $StCode = $this->actionStCode();//仓储码
//        print_r($data);
            return $this->render('edit', [
                'whname' => $whname,
                'data' => $data,
                'StCode' => $StCode,
                'dpt' => $dpt,
                'usertype'=>$_usertype,
            ]);
        }
    }

    //获取商品
    public function actionSelectProduct($wh_id){
        $url=$this->findApiUrl().$this->_url."product-data";
        $params=\Yii::$app->request->queryParams;
        if(\Yii::$app->request->isAjax){
            $url.='?'.http_build_query($params);
            return $this->findCurl()->get($url);
        }
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render("select-product",['params' => $params]);
    }

    //获取仓库信息-$id和$code不会同时有值(入)
    public function actionGetWarehouseInfo($id='',$code='')
    {
        $url=$this->findApiUrl().'warehouse/other-stock-in/get-warehouse-info';
        if(!empty($id)){
            $url.='?id='.$id;
        }
        if(!empty($code)){
            $url.='?code='.$code;
        }
        return $this->findCurl()->get($url);
    }

    //获取仓库信息-$id和$code不会同时有值(出)
    public function actionGetWarehouseInfos($Oid='',$Iid='',$code='')
    {
        $url=$this->findApiUrl().$this->_url.'get-warehouse-info';
        if(!empty($Oid)){
            $url.='?Oid='.$Oid;
        }
        if(!empty($Iid)){
            $url.='?Iid='.$Iid;
        }
        if(!empty($code)){
            $url.='?code='.$code;
        }
        return $this->findCurl()->get($url);
    }

    public function actionGetPdtno($pdt_no,$wh_id)
    {
        $url=$this->findApiUrl().$this->_url."product-data?pdt_no=".$pdt_no."&wh_id=".$wh_id;
//        print_r(Json::decode($this->findCurl()->get($url)));
        return $this->findCurl()->get($url);
    }
    //获取仓库名称
    public function actionWhname()
    {
        $url = $this->findApiUrl() ."warehouse/other-out-stock/get-wh-jurisdiction?staff_id=".Yii::$app->user->identity->staff_id;
        return Json::decode($this->findCurl()->get($url));
    }
    //获取仓库名称
    public function actionBusinessTypeName()
    {
        $url = $this->findApiUrl() . $this->_url . "business-type-name";
        return Json::decode($this->findCurl()->get($url));
    }

    //调拨单位
    public function actionOrganization()
    {
        $url = $this->findApiUrl() . $this->_url . "organization";
        return Json::decode($this->findCurl()->get($url));
    }

    //仓储码
    public function actionStCode()
    {
        $url = $this->findApiUrl() . $this->_url . "st-code";
        return Json::decode($this->findCurl()->get($url));
    }

    //获取调拨单位
    public  function getorgniozation()
    {
        $url = $this->findApiUrl() . $this->_url . "get-orgniozation?_id=".Yii::$app->user->identity->staff_id;
//        dumpE($url);
        return Json::decode($this->findCurl()->get($url));
    }

    //获取登录用户的权限
    public function getUserType()
    {
        $url = $this->findApiUrl() . $this->_url . "get-user-type?_id=".Yii::$app->user->identity->staff_id;
//        dumpE($url);
        return Json::decode($this->findCurl()->get($url));
    }

    //查看审核状态
    public function getVerify($id,$type){
        $url = $this->findApiUrl() . "/system/verify-record/find-verify?id=" . $id."&type=".$type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    //获取详情
    public function  getModel($id){
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new yii\web\NotFoundHttpException('页面未找到');
        }
    }
}