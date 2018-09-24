<?php
/**
 * User: F1677929
 * Date: 2017/9/6
 */
namespace app\modules\ptdt\controllers;
use app\controllers\BaseController;
use Yii;
use app\modules\system\models\SystemLog;
use yii\helpers\Json;
use yii\helpers\Url;

use yii\web\NotFoundHttpException;

/**
 * 商品分类管理控制器
 */
class CategoryManageController extends BaseController
{
    public function beforeAction($action)
    {
        $this->ignorelist=array_merge($this->ignorelist,[
            "/ptdt/category-manage/attr-list",
            "/ptdt/category-manage/add-attr",
            "/ptdt/category-manage/edit-attr",
            "/ptdt/category-manage/view-attr",
            "/ptdt/category-manage/enable-attr",
            "/ptdt/category-manage/disable-attr",
        ]);
        return parent::beforeAction($action);
    }

    private $_url = "ptdt/category-manage/";  //对应api控制器URL

    public function actionIndex(){
        $downlis= $this->downList();
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }else{
            $url .= "?BsCategorySearch[catg_id]=".$downlis['catgname'][0]['catg_id'];
        }
//        dumpE($dataProvider);
        if (Yii::$app->request->isAjax) {

            $dataProvider = $this->findCurl()->get($url);
            return $dataProvider;
        }
        return $this->render('index', [
            'categoryname'=>$downlis,
            'params'=>$queryParam,
        ]);
    }

    public function actionCreate($catg_no){
        if (Yii::$app->request->getIsPost()) {
            $url = $this->findApiUrl() . $this->_url . "create";
            $postData = Yii::$app->request->post();
            $postData["BsCategory"]["crt_date"]=date('Y-m-d H:i:s', time());//創建時間
            $postData["BsCategory"]["crter"]=Yii::$app->user->identity->staff->staff_id;//创建人
            $postData["BsCategory"]["crt_ip"]= Yii::$app->request->getUserIP();//'//获取ip地址
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            //dumpE($data);
            if ($data['status']) {
                return Json::encode(['msg' => "新增类别信息成功", "flag" => 1, "url" =>Url::to(['index','BsCategorySearch[catg_id]'=>$catg_no])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $this->layout = '@app/views/layouts/ajax';
        $catgno=$catg_no;
        return $this->render("create",[
            "catgno"=>$catgno,
        ]);
    }
    public function actionUpdate($id,$no){
        if(Yii::$app->request->isPost){
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $postData["BsCategory"]["opp_date"]=date('Y-m-d H:i:s', time());//修改時間
            $postData["BsCategory"]["opper"]=Yii::$app->user->identity->staff->staff_id;//修改人
            $postData["BsCategory"]["opp_ip"]= Yii::$app->request->getUserIP();//操作人IP
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                return Json::encode(['msg' => "修改类别信息成功", "flag" => 1, "url" =>Url::to(['index','BsCategorySearch[catg_id]'=>$no])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }else{
            $model = $this->getModel($id);
            $this->layout = '@app/views/layouts/ajax';
            return $this->render("update",[
                'bscategoryInfo'=>$model[0]
            ]);
        }
    }

    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        //dumpE($model[0]["catg_no"]);
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }
//    //根据用户输入的类别编码判断是否存在相同的类别
//    public function actionGetCatgnoInfo($no){
//        $url = $this->findApiUrl().'ptdt/category-manage/get-catgno-info?no='.$no;
//        $info=$this->findCurl()->get($url);
//        if(!empty($info)){
//            return $info;
//        }
//        return "";
//    }
    //判断是否已经关联类别属性
    public function actionGetCheckedattr($id){
        $url = $this->findApiUrl().'ptdt/category-manage/get-checkedattr?id='.$id;
        $info=$this->findCurl()->get($url);
        if(!empty($info)){
            return $info;
        }
        return "";
    }


    /*加载下拉列表*/
    private function downList(){
        $url = $this->findApiUrl() . $this->_url . "down-list";
        return Json::decode($this->findCurl()->get($url));
    }
    //获取上级类别
    public function actionGetPcatgname($catglevel){
        $url = $this->findApiUrl() . $this->_url . "get-pcatgname?catg_level=".$catglevel;
        return Json::decode($this->findCurl()->get($url));
    }
    //获取排序编号
    public function actionGetOrderbyno($pcatgid){
        $url = $this->findApiUrl() . $this->_url . "get-orderbyno?p_catg_id=".$pcatgid;
        return Json::decode($this->findCurl()->get($url));
    }
    //商品分类属性列表
    public function actionAttrList($id)
    {
        $url=$this->findApiUrl().'ptdt/category-manage/attr-list';
        $url.='?id='.$id;
        if(Yii::$app->request->isAjax){
            $url.='&'.http_build_query(Yii::$app->request->queryParams);
            return $this->findCurl()->get($url);
        }
        $data=json_decode($this->findCurl()->get($url),true);
        if(empty($data)){
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('attr-list',['data'=>$data]);
    }

    //新增商品分类属性
    public function actionAddAttr($id)
    {
        if($data=Yii::$app->request->post()){
            $url=$this->findApiUrl().'ptdt/category-manage/add-attr';
            $url.='?id='.$id;
            $data['BsCatgAttr']['opp_date']=date('Y-m-d H:i:s');
            $data['BsCatgAttr']['opper']=Yii::$app->user->identity->staff_id;
            $data['BsCatgAttr']['opp_ip']=Yii::$app->request->getUserIP();
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('新增'.$data['attr_name'].'属性');
                return json_encode(['msg'=>$result['msg'],'flag'=>1]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        return $this->renderAjax('add-attr');
    }

    //修改商品分类属性
    public function actionEditAttr($id)
    {
        $url=$this->findApiUrl().'ptdt/category-manage/edit-attr';
        $url.='?id='.$id;
        if($data=Yii::$app->request->post()){
            $data['BsCatgAttr']['opp_date']=date('Y-m-d H:i:s');
            $data['BsCatgAttr']['opper']=Yii::$app->user->identity->staff_id;
            $data['BsCatgAttr']['opp_ip']=Yii::$app->request->getUserIP();
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('修改'.$data['attr_name'].'属性');
                return json_encode(['msg'=>$result['msg'],'flag'=>1]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=json_decode($this->findCurl()->get($url),true);
        return $this->renderAjax('edit-attr',['data'=>$data]);
    }

    //查看商品分类属性
    public function actionViewAttr($id)
    {
        $url=$this->findApiUrl().'ptdt/category-manage/edit-attr';
        $url.='?id='.$id;
        $data=json_decode($this->findCurl()->get($url),true);
        return $this->renderAjax('view-attr',['data'=>$data]);
    }

    //启用商品分类属性
    public function actionEnableAttr($id)
    {
        $url=$this->findApiUrl().'ptdt/category-manage/enable-attr';
        $url.='?id='.$id;
        $result=json_decode($this->findCurl()->get($url),true);
        if($result['status']==1){
            SystemLog::addLog('启用'.$result['data'].'属性');
            return json_encode(['msg'=>$result['msg'],'flag'=>1]);
        }
        return json_encode(['msg'=>$result['msg'],'flag'=>0]);
    }

    //禁用商品分类属性
    public function actionDisableAttr($id)
    {
        $url=$this->findApiUrl().'ptdt/category-manage/disable-attr';
        $url.='?id='.$id;
        $result=json_decode($this->findCurl()->get($url),true);
        if($result['status']==1){
            SystemLog::addLog('禁用'.$result['data'].'属性');
            return json_encode(['msg'=>$result['msg'],'flag'=>1]);
        }
        return json_encode(['msg'=>$result['msg'],'flag'=>0]);
    }


    //关联类别保存,
    public function actionSave()
    {
        $id = $_POST['id'];
        $rid = $_POST['rid'];
//        $this->check($id, $rid);
        $url = $this->findApiUrl() . 'ptdt/category-manage/save';
        $postData = Yii::$app->request->post();
        $postData['catg_id'] = $id;
        $postData['catg_r_id'] = $rid;
        $postData['opper'] = Yii::$app->user->identity->staff_id;//操作人
        $postData['op_date'] = date('Y-m-d H:i:s', time());
        $postData['opp_ip'] = Yii::$app->request->getUserIP();
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = Json::decode($curl->post($url));
        return $data["status"];


    }

    //类别关联
    public function actionGetCateTree($catgid)
    {
        $url = $this->findApiUrl() . 'ptdt/category-manage/cate-name?id=' . $catgid;
        $title = Json::decode($this->findCurl()->get($url));
        //获取已关联的类别名称
        $url1 = $this->findApiUrl() . 'ptdt/category-manage/category-relating?id=' . $catgid;
        $list = Json::decode($this->findCurl()->get($url1));
        return $this->render('get-cate-tree', [
            'title' => $title,
            'list' => $list,
            'catgid' => $catgid
        ]);


    }

    public function actionTree($catgid)
    {
        $tree = $this->getTree($catgid);
//var_dump($tree);
        return $tree;

    }


    private function getTree($catgid)
    {
        $url = $this->findApiUrl() . $this->_url . "get-tree?catgid=" . $catgid;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $dataProvider;
    }

    public function actionCheck($id, $rid)
    {

        $url = $this->findApiUrl() . $this->_url . "check?id=" . $id;
        $list = Json::decode($this->findCurl()->get($url));
        $ridArr = explode(",", $rid);
        $name = "1";
        foreach ($list as $key => $val) {
            $a = 0;
            foreach ($ridArr as $key1 => $val1) {
                if ($val['catg_id'] == $val1) {
                    $a = $val1;
                }
            }
            if ($a == 0) {
                $url = $this->findApiUrl() . 'ptdt/category-manage/cate-name?id=' . $val['catg_id'];
                $model = Json::decode($this->findCurl()->get($url));
                $name = $model["catg_name"];
            }
        }
        return $name;
    }


}