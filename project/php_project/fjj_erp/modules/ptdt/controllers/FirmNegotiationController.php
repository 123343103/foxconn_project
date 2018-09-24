<?php
/**
 * User: F3859386
 * Date: 2016/10/13
 * Time: 17:13
 */
namespace app\modules\ptdt\controllers;

use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use app\controllers\BaseController;

/**
 * 厂商谈判履历表.
 */
class FirmNegotiationController extends BaseController
{

    private $_url = "ptdt/firm-negotiation/";
    /**
     * 主页
     * @return mixed
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl().$this->_url."index?PdfirmNegotiationSearch%5BcompanyId%5D=".Yii::$app->user->identity->company_id."&";
        if (!empty($queryParam=Yii::$app->request->queryParams)) {
            $url .=http_build_query($queryParam);
        }
        if(Yii::$app->request->isAjax){
            $dataProvider = Json::decode($this->findCurl()->get($url));
            foreach ($dataProvider['rows'] as $key => $val){
                $dataProvider['rows'][$key]['pdn_code']='<a href="'. Url::to(['view','pid'=>$val['pdn_id']]).'">'.$val['pdn_code'].'</a>';
            }
            return Json::encode($dataProvider);
        }
        //获取动态列
        return $this->render('index',[
            'downList'=>$this->getSearchDownList(),
            'columns'=>$this->getField(),
            'queryParam'=>$queryParam
        ]);
    }

    /**
     * 加载履历列表
     * @return string
     */
    public function actionLoadInfo($id)
    {
        $url = $this->findApiUrl().$this->_url."load-info?id=".$id;
        $dataProvider = $this->findCurl()->get($url);
        return $dataProvider;
    }


    /**
     * 查看
     * @param integer $id
     * @return mixed
     */
    public function actionView($pid=null,$cid=null)
    {
        if($pid != null){
            $url = $this->findApiUrl() . $this->_url . "view?pid=" . $pid;
            $dataProvider = Json::decode($this->findCurl()->get($url));
            return $this->render('views', [
                'negotiation' => $dataProvider['negotiation'],
                'firmInfo' => $dataProvider['firmInfo'],
                'child' => $dataProvider['child'],
                'reception' => $dataProvider['reception'],
            ]);
        }else{
            $url = $this->findApiUrl() . $this->_url . "view?cid=" . $cid;
            $dataProvider = Json::decode($this->findCurl()->get($url));
            return $this->render('view', [
                'negotiation' => $dataProvider['negotiation'],
                'analysis' => $dataProvider['analysis'],
                'firmInfo' => $dataProvider['formInfo'],
                'child' => $dataProvider['child'],
                'authorize' => $dataProvider['authorize'],
                'accompany' => $dataProvider['accompany'],
                'reception' => $dataProvider['reception'],
                'productInfo' => $dataProvider['productInfo'],
            ]);
        }
    }

    /**
     * 创建
     * @return mixed
     */
    public function actionCreate($pdnId = null,$firmId = null,$planId = null)
    {
        //选中谈判新增进入
        $firmModel=null;
        if ($post=Yii::$app->request->post()) {
            if ($_FILES['attachment']['error']==false) {
                $file=$_FILES['attachment'];
                $temp = explode(".", $file["name"]);
                $extension = end($temp);     // 获取文件后缀名
                $path = 'uploads/pdn-attachment/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $name = uniqid() . '.' . $extension;
                move_uploaded_file($file["tmp_name"], $path . $name);
                $post['PdNegotiationChild']['attachment'] = $path.$name;
                $post['PdNegotiationChild']['attachment_name'] = $file["name"];
            }
            $post['PdNegotiation']['create_by'] = Yii::$app->user->identity->staff_id;
            $post['PdNegotiation']['company_id'] = Yii::$app->user->identity->company_id;
            $post['PdVisitPlan']['planId'] = $planId;
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog('厂商谈判新增,编号:'.$data['msg']);
                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" =>Url::to(['view','cid'=>$data['data']])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失敗", "flag" => 0]);
            }
        }
        $plan = $this->getVisitPlan($planId)?$this->getVisitPlan($planId):'';
        return $this->render('create',[
            'downList'=>$this->getDownList(),
            'firmInfo'=>$this->getFirmInfo($pdnId,$firmId),
            'plan'=>$plan
        ]);
    }



    /**
     * 更新
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($cid)
    {
        $url = $this->findApiUrl() . $this->_url . "update?cid=".$cid;

        if ($post=Yii::$app->request->post())
        {
            $file=$_FILES['attachment'];
            //文件不为空且无错误时进入
            if ($file!==null && $file['error']==false) {
                    $temp = explode(".", $file["name"]);
                    $extension = end($temp);     // 获取文件后缀名
                    $path = 'uploads/pdn-attachment/';
                    if (!file_exists($path))
                    {
                        mkdir($path, 0777, true);
                    }
                    $name = uniqid() . '.' . $extension;
                    move_uploaded_file($file["tmp_name"], $path . $name);
                    $post['PdNegotiationChild']['attachment'] = $path.$name;
                    $post['PdNegotiationChild']['attachment_name'] = $file["name"];
            }else if($file['error']==4){
                $post['PdNegotiationChild']['attachment'] = '';
                $post['PdNegotiationChild']['attachment_name'] = '';
            }
            $post['PdNegotiation']['update_by'] = Yii::$app->user->identity->staff_id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $info = Json::decode($curl->post($url));
            if ($info['status']) {
                SystemLog::addLog('厂商谈判修改,编号:'.$info['msg']);
                return Json::encode(['msg' => "修改成功", "flag" => 1, "url" =>Url::to(['view','cid'=>$cid])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失敗", "flag" => 0]);
            }
        }
        $data=Json::decode($this->findCurl()->put($url));
        $firmInfo=$this->getFirmInfo('',$data['negotiation']['firm_id']);
        $data['downList']=$this->getDownList();
        $plan = $this->getVisitPlan($data['child']['visit_planID']);
        return $this->render('update',[
            'data'=>$data,
            'firmInfo'=>$firmInfo,
            'plan'=>$plan
        ]);
    }

    /**
     * @param $pdnId
     * @return string
     * 谈判完成
     */
    public function actionNegotiate($pdnId){
        $url = $this->findApiUrl() . $this->_url . "finish-negotiate?id=" . $pdnId;
        $result = Json::decode($this->findCurl()->put($url));
        if ($result['status'] == 1) {
            return Json::encode(["msg" => "谈判完成", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "发生未知错误", "flag" => 0]);
        }
    }

    //获取Firm信息
    protected  function getFirmInfo($pdnId,$firmId){
        $firmInfo = null;
        if(!empty($pdnId)){
            $url =$this->findApiUrl().$this->_url."get-info?pdnId=".$pdnId;
            $negotiation=Json::decode($this->findCurl()->get($url));
            $firmId =$negotiation['firm_id'];
        }
        if($firmId){
            $url =$this->findApiUrl().$this->_url."get-firm-info?firmId=".$firmId;
            $firmInfo=Json::decode($this->findCurl()->get($url));
        }
        return $firmInfo;
    }

    /**
     * @param $planId
     * @return mixed
     * 拜访计划
     */
    protected function getVisitPlan($planId){
        $url =$this->findApiUrl().$this->_url."get-visit-plan?planId=".$planId;
        $plan=Json::decode($this->findCurl()->get($url));
        return $plan;
    }

    /**
     * 删除
     */
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $result = $this->findCurl()->delete($url);
        if (json_decode($result)->status) {
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失敗", "flag" => 0]);
        }
    }

    /**
     * 谈判分析表
     */
    public function actionAnalysis($selects)
    {
        $url =$this->findApiUrl().$this->_url."analysis?selects=".$selects;
        $list=Json::decode($this->findCurl()->get($url));
        return $this->render('analysis',[
            'list'=>$list
        ]);
    }
    public function actionSelectPlan($firmId)
    {
        $params=Yii::$app->request->queryParams;
        if(Yii::$app->request->isAjax){
            $url=$this->findApiUrl().'ptdt/firm-negotiation/select-plan?companyId='.Yii::$app->user->identity->company_id.'&'.http_build_query($params);
            return $this->findCurl()->get($url);
        }
        return $this->renderAjax('select-plan',['firmId'=>$firmId,'params'=>$params]);
    }
    /**
     * 加载商品开发计划
     * @return string
     */
    public function actionLoadDvlp(){

        $url = $this->findApiUrl() . "ptdt/firm-negotiation/load-dvlp?PdRequirementSearch%5BcompanyId%5D=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
        return $this->renderAjax('load-dvlp', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 加载商品信息
     * @param $id
     * @return string
     */
    public function actionDvlpInfo($id)
    {
        $url = $this->findApiUrl() . $this->_url."dvlp-info?id=".$id;
        $dataProvider = $this->findCurl()->get($url);
        return $dataProvider;
    }

//    /**
//     * 一阶分类选择列表
//     */
//    protected function getProductTypeList()
//    {
//        return PdProductType::getLevelOne();
//    }

    protected function getSearchDownList(){
        $url = $this->findApiUrl().$this->_url."search-down-list";
        $dataProvider = json::decode($this->findCurl()->get($url));
        $productType = $dataProvider['productTypes'];
        $productTypeIdToValue = [];
        foreach ($productType as $key => $val) {
            $productTypeIdToValue[$val['category_id']] = $val['category_sname'];
        }
        $dataProvider['productTypes']=$productTypeIdToValue;
        return $dataProvider;
    }

    protected function getDownList(){
        $url = $this->findApiUrl().$this->_url."form-down-list";
        $dataProvider = json::decode($this->findCurl()->get($url));
        $productType = $dataProvider['productTypes'];
        $productTypeIdToValue = [];
        foreach ($productType as $key => $val) {
            $productTypeIdToValue[$val['category_id']] = $val['category_sname'];
        }
        $dataProvider['productTypes']=$productTypeIdToValue;
        return $dataProvider;
//        //厂商地位
//        $downList['firmLevel']=BsPubdata::getList(BsPubdata::FIRM_LEVEL);
//        //厂商配合度
//        $downList['firmCooperate']=BsPubdata::getList(BsPubdata::PD_NEGOTIATION_COOPERATE);
//        //商品类别
//        $downList['productType']=$this->getProductTypeList();
//        //商品定位
//        $downList['productLevel']=BsPubdata::getList(BsPubdata::DP_PRODUCT_LEVEL);
//        //谈判结论
//        $downList['negotiationResult']=BsPubdata::getList(BsPubdata::PD_NEGOTIATION_RESULT);
//        //授权区域范围
//        $downList['authorizeArea']=BsPubdata::getList(BsPubdata::PD_AUTHORIZE_AREA);
//        //销售范围
//        $downList['saleArea']=BsPubdata::getList(BsPubdata::PD_SALE_AREA);
//        //代理等级
//        $downList['agentsLevel']=BsPubdata::getList(BsPubdata::PD_AGENTS_LEVEL);
//        //物流配送
//        $downList['deliveryWay']=BsPubdata::getList(BsPubdata::PD_DELIVERY_WAY);
//        //售后服务
//        $downList['service']=BsPubdata::getList(BsPubdata::PD_SERVICE);
////        //厂商类型
////        $downList['firmType']=BsPubdata::getList(BsPubdata::FIRM_TYPE);
//        return $downList;
    }





}
