<?php
namespace app\modules\ptdt\controllers;

use app\controllers\BaseController;
use app\modules\common\models\BsCompany;
use yii\helpers\Url;
use app\modules\system\models\SystemLog;
use yii\helpers\Json;

use yii;

/**
 * 商品开发需求控制器
 * F3858995
 * 2016/9/18
 */
class ProductDvlpController extends BaseController
{

    private $_url = "ptdt/product-dvlp/";  //对应api控制器URL


    /**
     * 列表页
     * @return string
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl().$this->_url."index?PdRequirementSearch%5BcompanyId%5D=".Yii::$app->user->identity->company_id."&";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = $this->findCurl()->get($url);
            return $dataProvider;
        }
        $downList = $this->downList();
        $developCenter = $downList['developCenters'];
        $requirementType = $downList['requirementTypes'];
        $pdqStatus = $downList['pdqStatus'];
        $developDep = [];
        if ($developCenterCode = Yii::$app->request->get('PdRequirementSearch')['develop_center']) {
            $developDep = $this->getDvlpOption($developCenterCode);
        };
        //获取动态列
        $columns=$this->getField();
        return $this->render('index', [
            'columns' =>$columns,
            'requirementType' => $requirementType,
            'developDep' => $developDep,
            'developCenter' => $developCenter,
            'pdqStatus' => $pdqStatus
        ]);
    }

    /**
     * 新增
     * @return string
     * @throws yii\db\Exception
     */
    public function actionAdd()
    {
        if ($postData = Yii::$app->request->post()) {
            $postData['PdRequirement']['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['PdRequirement']['company_id'] = Yii::$app->user->identity->company_id;
            $url = $this->findApiUrl() . $this->_url . 'add';
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog('新增商品开发需求,编号:'.$data['msg']);
                if($postData['status'] == 20 && !empty($data['data'])){
                    return Json::encode(['msg' => $data['data'], "flag" => 2]);
                }
                return Json::encode(['msg' => '保存成功', "flag" => 1, "url" => Url::to(['view','id'=>$data['data']])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        }
        $downList =$this->downList();
        //一阶分类
        $productType = $downList['productTypes'];
        $productTypeIdToValue = [];
        foreach ($productType as $key => $val) {
            $productTypeIdToValue[$val['category_id']] = $val['category_sname'];
        }
        $downList['productTypes']=$productTypeIdToValue;
        return $this->render("add", [
            'downList'=>$downList
        ]);
    }



    /**
     * 编辑
     * @param $id
     * @return string
     * @throws yii\db\Exception
     */
    public function actionEdit($id)
    {
        if ($postData = Yii::$app->request->post()) {
            $postData['PdRequirement']['update_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "edit?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status']) {
                SystemLog::addLog('修改商品开发需求,编号:'.$data['msg']);
                if($postData['status'] ==20){
                    return Json::encode(['msg' => $id, "flag" => 2]);
                }
                return Json::encode(['msg' => '保存成功', "flag" => 1, "url" => Url::to(['view','id'=>$id])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        } else {
            $planModel = $this->getModel($id);
            //开发部门
            $developDep = $this->getDvlpOption($planModel->develop_center);
            //开发中心
//            $developCenter = $this->getDevelopCenterList();
//            商品经理人
//            $staffManager = $this->getPms();
//            需求类型
//            $requirementType = $this->getRequirementTypeList();
//            开发类型
//            $developType = $this->getDevelopTypeList();
//            商品定位
//            $productLevel = $this->getProductLevelList();

            $downList =$this->downList();
            //一阶分类
            $productType = $downList['productTypes'];
            $productTypeIdToValue = [];
            foreach ($productType as $key => $val) {
                $productTypeIdToValue[$val['category_id']] = $val['category_sname'];
            }
            $downList['productTypes']=$productTypeIdToValue;

            return $this->render("edit", [
                'planModel'  => $planModel,
                'developDep' => $developDep,
                'downList'   => $downList
            ]);
        }
    }

    /**
     * 送审
     */
    public function actionCheck($id)
    {
        $dataResult=$this->check($id);

        if($dataResult){
            return Json::encode(["msg" => "送审成功", "flag" => 1]);
        }else{
            return Json::encode(["msg" => "送审失敗", "flag" => 0]);
        }
    }


    /**
     * 送审
     * @param $id
     */
    protected function check($id){
        $data['id']=$id;
        $data['type']=11;
        $data['staff']=Yii::$app->user->identity->staff_id;
        //送审,审核流程
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
        $verifyUrl = $this->findApiUrl() . '/system/verify-record/verify-record';
        return Json::decode($curl->post($verifyUrl));
    }


    /**
     * 查看审核详情
     * @param $id
     * @return string
     */
    public function actionCheckView($id){
        $model = $this->getModel($id);
        $verify = $this->getVerify($id,$type=11);
        return $this->render('check',[
            'model'=>$model,
            'verify'=>$verify
        ]);
    }

    /**
     * 获取审核记录
     * @param $id
     * @param $type
     * @return mixed
     */
    public function getVerify($id,$type){
        $url = $this->findApiUrl() . "/system/verify-record/find-verify?id=" . $id."&type=".$type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    /**
     * 详情页
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $model = $this->getProduct($id);
        return $this->render("view", [
            'model' => $model,
        ]);
    }

    /**
     * 删除
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $data = Json::decode($this->findCurl()->delete($url));
        if ($data['status']) {
            SystemLog::addLog('商品开发需求删除,编号:'.$data['msg']);
            return Json::encode(["msg" => "删除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "删除失败", "flag" => 0]);
        }
    }

    public function actionDeleteCount($id){
        $url = $this->findApiUrl().$this->_url.'delete-count?id='.$id;
        $data = Json::decode($this->findCurl()->get($url));
        return $data;
    }

    /**
     * 获取模型
     * @param $id
     * @return null|static
     * @throws \Exception
     */
    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "model?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url), false);
        if (!$model) {
            throw new yii\web\NotFoundHttpException("页面未找到");
        }
        return $model;
    }

    /**
     * load加载商品详情
     * @return string
     */
    public function actionLoadProduct($id)
    {
        $url = $this->findApiUrl() . $this->_url . "products?id=" . $id;
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
        return $this->renderPartial('load-product');
    }

    /**
     * 加载商品详情
     * @return string
     */
    private function getProduct($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-product?id=" . $id;
        $data=  Json::decode($this->findCurl()->get($url));
        return $data['model'];
    }


    /**
     * AJAX获取商品大类
     * @param $id
     * @return string
     */
    public function actionGetProductType($id)
    {
        $url = $this->findApiUrl() . $this->_url . "product-type-children?id=" . $id;
        return $this->findCurl()->get($url);
    }

    /**
     * AJAX获取开发部门
     * @param $code
     * @return string
     */
    public function actionGetDevelopDep($code)
    {
        $url = $this->findApiUrl() . $this->_url . "develop-deps?code=" . $code;
        return $this->findCurl()->get($url);
    }

    /**
     * 下拉列表
     */
    private function downList(){
        $url = $this->findApiUrl() . $this->_url . "down-list";
        return Json::decode($this->findCurl()->get($url));

    }


    /**
     * 获取开发部门
     * @param $code
     * @return mixed
     */
    private function getDvlpOption($code)
    {
        $url = $this->findApiUrl() . $this->_url . "develop-deps?code=" . $code;

        return Json::decode($this->findCurl()->get($url));

    }



}
