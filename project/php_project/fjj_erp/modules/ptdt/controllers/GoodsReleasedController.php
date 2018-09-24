<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/9/11
 * Time: 14:44
 */
namespace app\modules\ptdt\controllers;

use app\modules\system\models\SystemLog;
use yii;
use app\controllers\BaseController;
use yii\helpers\Json;

class GoodsReleasedController extends BaseController{
    private $_url = 'ptdt/goods-released/';



    public function actions(){
        return array_merge(parent::actions(),[
            'upload' => [
                'class' => \app\widgets\upload\UploadAction::className(),
                'allowRatio'=>'800*800',
                'scene'=>trim(\Yii::$app->ftpPath["PDT"]["father"],"/")."/".trim(\Yii::$app->ftpPath["PDT"]["PdtImg"],"/")
            ],
            'upload3d' => [
                'class' => \app\widgets\upload\UploadAction::className(),
                'scene'=>trim(\Yii::$app->ftpPath["PDT"]["father"],"/")."/".trim(\Yii::$app->ftpPath["PDT"]["Pdt3D"],"/")
            ],
            'uploadmrk' => [
                'class' => \app\widgets\upload\UploadAction::className(),
                'scene'=>trim(\Yii::$app->ftpPath["PDT"]["father"],"/")."/".trim(\Yii::$app->ftpPath["PDT"]["PdtMrk"],"/")
            ]
        ]);
    }

    /**
     * @return string
     * 发布新商品列表页
     */
    public function actionIndex(){
        $url = $this->findApiUrl() . "ptdt/goods-released/index";
        $queryParam = Yii::$app->request->queryParams;
        if(!empty($queryParam['FpPriceSearch']['levelOne'])){
            if(empty($queryParam['FpPriceSearch']['levelTwo']) && empty($queryParam['FpPriceSearch']['levelThree'])){
                $queryParam['FpPriceSearch']['category'] = $queryParam['FpPriceSearch']['levelOne'];
            }
            $levelTwo = $this->getLevel($queryParam['FpPriceSearch']['levelOne']);
        }
        if(!empty($queryParam['FpPriceSearch']['levelTwo'])){
            $queryParam['FpPriceSearch']['category'] = $queryParam['FpPriceSearch']['levelTwo'];
            $levelThree = $this->getLevel($queryParam['FpPriceSearch']['levelTwo']);
        }
        if(!empty($queryParam['FpPriceSearch']['levelThree'])){
            $queryParam['FpPriceSearch']['category'] = $queryParam['FpPriceSearch']['levelThree'];
        }
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
//        echo $this->findCurl()->get($url);exit;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
//            foreach ($dataProvider['rows'] as $key => $value){
//                if(!empty($value['category_id'])){
//                    $arr = $this->getCategory($value['category_id']);
//                    $dataProvider['rows'][$key]['category'] = $arr;
//                }
//
//            }
            return Json::encode($dataProvider);
        }
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            foreach ($dataProvider['rows'] as $key => $value){
                $arr = $this->getCategory($value['category_id']);
                $dataProvider['rows'][$key]['category'] = $arr;
            }
            $this->exportFiled($dataProvider['rows']);
        }
        $downList = $this->getDownList();
//        dumpE($downList);
        $columns = $this->getField('/ptdt/goods-released/index');
        return $this->render('index',[
            'downList' => $downList,
            'queryParam' => $queryParam,
            'levelTwo' => isset($levelTwo)?$levelTwo:[],
            'levelThree' => isset($levelThree)?$levelThree:[],
            'columns' => $columns
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * 核价档
     */
    public function actionLoadContent($id){
        $url = $this->findApiUrl() . $this->_url . "load-content?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
//        dumpE($dataProvider);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
    }

    /**
     * @param $id
     * @return string
     * 删除
     */
    public function actionDelete($id){
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失敗", "flag" => 0]);
        }
    }

    /*
     *
     * 商品上架-商品信息填写
    */
    public function actionUpShelf($partno){
        if(\Yii::$app->request->isPost){
            $url=$this->findApiUrl().$this->_url."up-shelf?partno={$partno}";
            $params=\Yii::$app->request->post();
            $params["opper"]=\Yii::$app->user->identity->staff_id;
            $params["opp_date"]=date("Y-m-d H:i:s");
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $data=Json::decode($curl->post($url));
            if($data["status"]==1){
                return Json::encode(['msg' => "修改成功", "flag" => 1,'url'=>yii\helpers\Url::to(['up-shelf2','id'=>$data['msg']['pdt_id']])]);
            }
            return Json::encode(['msg' =>$data["msg"], "flag" => 0]);
        }else{
            $url=$this->findApiUrl().$this->_url."up-shelf?partno={$partno}";
            $data=Json::decode($this->findCurl()->get($url));
//            echo "<pre>";print_r($data);die();
            if($data["isUpshelf"]){
                return $this->redirect(["up-shelf2","id"=>$data["pdt_pkid"]]);
            }
            $url=$this->findApiUrl()."ptdt/product-list/options";
            $options=Json::decode($this->findCurl()->get($url));
            return $this->render("up-shelf",[
                "model"=>$data,
                "options"=>$options
            ]);
        }
    }


    //商品上架-料号信息维护
    public function actionUpShelf2($id,$type=""){
        if(\Yii::$app->request->isPost){
            $data=\Yii::$app->request->post();
            $data["BsPartno"]["opp_date"]=date("Y-m-d H:i:s");
            $data["BsPartno"]["opper"]=\Yii::$app->user->identity->staff_id;
            $data["BsPartno"]["opp_ip"]=\Yii::$app->request->userIP;
            $url=$this->findApiUrl().$this->_url."up-shelf2?id={$id}&type={$type}";
            $res=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data))->post($url);
            $res=Json::decode($res);
            if($res["status"]==1){
                return Json::encode(['msg' => "保存成功",'l_prt_pkid'=>$res['msg']['l_prt_pkid'], "flag" => 1,'url'=>yii\helpers\Url::to(['index'])]);
            }
            return Json::encode(['msg' =>"保存失败", "flag" =>0]);
        }
        $url=$this->findApiUrl().$this->_url."up-shelf2?id={$id}";
        $data=$this->findCurl()->get($url);
        $model=Json::decode($data);
        $url=$this->findApiUrl()."ptdt/product-list/options";
        $options=Json::decode($this->findCurl()->get($url));
        $url=$this->findApiUrl()."ptdt/product-list/get-bus-type?code=pdtsel";
        $busTypeId=Json::decode($this->findCurl()->get($url));
        return $this->render("up-shelf2",[
            "model"=>$model,
            "options"=>$options,
            "busTypeId"=>$busTypeId
        ]);
    }



    //送审
    public function actionReviewer($type, $id, $url = null)
    {
        if ($post = \Yii::$app->request->post()) {
            $post['id'] = $id;    //单据ID
            $post['type'] = $type;  //审核流类型
            $post['staff'] = \Yii::$app->user->identity->staff_id;//送审人ID
            $verifyUrl = $this->findApiUrl() . "system/verify-record/verify-record";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($verifyUrl));
            if ($data['status']) {
                if (!empty($url)) {
                    return Json::encode(['msg' => "提交审核成功！", "flag" => 1, "url" => $url]);
                } else {
                    return Json::encode(['msg' => "提交审核成功！", "flag" => 1]);
                }
            } else {
                return Json::encode(['msg' => $data['msg'] . ' 送审失败！', "flag" => 0]);
            }
        }
        $urls = $this->findApiUrl() ."system/verify-record/reviewer?type=" . $type . '&staff_id=' . \Yii::$app->user->identity->staff_id;
        $review = Json::decode($this->findCurl()->get($urls));
        return $this->renderAjax('reviewer', [
            'review' => $review,
        ]);
    }


    //商品的料号列表
    public function actionPartnoList($id){
        if(\Yii::$app->request->isAjax){
            $params=\Yii::$app->request->queryParams;
            $url=$this->findApiUrl().$this->_url."partno-list?".http_build_query($params);
            return $this->findCurl()->get($url);
        }
    }



    //料号信息修改ajax切换
    public function actionPartnoAjaxForm($id,$partno=""){
        $params=\Yii::$app->request->queryParams;
        $url=$this->findApiUrl().$this->_url."partno-info?id={$id}&partno={$partno}";
        $data=Json::decode($this->findCurl()->get($url));
//        echo "<pre>";print_r($data);die();
        $url=$this->findApiUrl()."ptdt/product-list/options";
        $options=Json::decode($this->findCurl()->get($url));
//        echo "<pre>";print_r($data);die();
        return $this->renderAjax('_partno_form',[
            "data"=>$data,
            "options"=>$options
        ]);
    }


    public function actionCount($id){
        $url = $this->findApiUrl() . $this->_url . "count?id=".$id;
        return $this->findCurl()->get($url);
    }

    /**
     * @param $id
     * @return mixed
     * 表格中(类别)拼凑字符串
     */
    public function getCategory($id){
        $url = $this->findApiUrl() . $this->_url . "category?id=".$id;
        return Json::decode($this->findCurl()->get($url));
    }

    public function getLevel($id){
        $url = $this->findApiUrl() . $this->_url . 'get-category-type?id=' . $id;
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * @param $id
     * @return mixed
     * 级联查询类别
     */
    public function actionGetCategoryType($id){
        $url = $this->findApiUrl() . $this->_url . 'get-category-type?id=' . $id;
        return $this->findCurl()->get($url);
    }

    /**
     * @return mixed
     * 下拉菜单
     */
    public function getDownList(){
        $url = $this->findApiUrl() . $this->_url . "down-list";
        return Json::decode($this->findCurl()->get($url));
    }
}