<?php
/**
 * 廠商控制器
 */
namespace app\modules\ptdt\controllers;
use app\modules\ptdt\models\PdFirm;
use app\modules\ptdt\models\Search\PdFirmQuery;
use yii;
use app\controllers\BaseController;
use yii\helpers\Json;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\PdProductType;
use app\modules\system\models\SystemLog;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use app\modules\common\models\BsDistrict;

class FirmController extends BaseController {

    private $_url = 'ptdt/firm/';
    /**
     * @return string
     * 廠商默認頁面
     */
    public function actionIndex(){


        $url = $this->findApiUrl() . "ptdt/firm/index?companyId=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)){
            $url .= "&". http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = Json::decode($this->findCurl()->get($url));
            foreach ($dataProvider['rows'] as $key => $val){
                $dataProvider['rows'][$key]['firm_code']='<a href="'. yii\helpers\Url::to(['view','id'=>$val['firm_id']]).'">'.$val['firm_code'].'</a>';
            }
            return Json::encode($dataProvider);
        }
        $firmType = $this->actionFirmTypeList();
        $firmCategory = $this->getFirmCategory();
        $firmCategoryToValue = [];
        foreach ($firmCategory as $key => $val) {
            $firmCategoryToValue[$val['category_id']] = $val['category_sname'];   //分級分類模糊搜索
        }
        $columns=$this->getField("/ptdt/firm/index");
//        dumpE($columns);
         return $this->render('index', [
            'firmType' => $firmType,
            'firmCategoryToValue' => $firmCategoryToValue,
             'queryParam'=>$queryParam['PdFirmQuery'],
             'columns'=>$columns
        ]);
    }
    /**
     * @return string
     * 廠商詳情頁面
     */
    public function actionView($id)
    {
        $model = $this->findModel($id,Yii::$app->user->identity->company_id);
        $orgInfo = $this->getOrgName($model->createBy->organization_code);
        $orgName = $orgInfo['organization_name'];

        $firmDis = $model->firm_district_id;
        $firmComAddress = $model->firm_compaddress;
        $disId = BsDistrict::find()->where(['district_id'=>$firmDis])->one();
        $dis1 = BsDistrict::find()->where(['district_id'=>$disId['district_pid']])->one();
        $dis2 = BsDistrict::find()->where(['district_id'=>$dis1['district_pid']])->one();
        $dis3 = BsDistrict::find()->where(['district_id'=>$dis2['district_pid']])->one();
        $dis4 = BsDistrict::find()->where(['district_id'=>$dis3['district_pid']])->one();
        $disName = $dis4['district_name'].$dis3['district_name'].$dis2['district_name'].$dis1['district_name'].$disId['district_name'];
//            dumpE($firmType);
        $disComAddress = $disName.$firmComAddress;
        return $this->render("view",
            [
                'model' => $model,
                'orgName'=>$orgName,
                'disComAddress'=>$disComAddress
             ]);
    }

    /**
     * @return string
     * 廠商新增頁面
     */
    public function actionCreate($type=null){
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            if ($postData['PdFirm']['firm_category_id']==''){
                $postData['firm_category_id']='';
            }else{
                $postData['PdFirm']['firm_category_id']= serialize(explode(',',$postData['PdFirm']['firm_category_id']));//序列化分級分類
            }
            $postData['PdFirm']['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['PdFirm']['create_at'] = date("Y-m-d", time());
            $postData['PdFirm']['company_id'] = Yii::$app->user->identity->company_id;
            $postData['PdFirm']['firm_status'] = 10;
            $postData['type']=$type;
            $url = $this->findApiUrl() . $this->_url . "create" ;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->post($url));
            if ($data->status == 1) {
                SystemLog::addLog('新增了' . $postData['firm_sname'] . '厂商信息');
                if(!empty($type)){
                    return Json::encode($data);
                }
                return Json::encode(['msg' => "新增厂商成功", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        }else{
            $code = yii::$app->user->identity->staff->organization_code;
            $name = $this->getOrgName($code);
            $orgName = $name['organization_name']; //获取组织名称
            $firmSource = $this->getFirmSoucrceList();//获取厂商来源列表
            $firmType = $this->actionFirmTypeList();//获取厂商类型
            $firmPosition = $this->getFirmPositionList();//获取厂商地位
            $firmCategory = $this->getFirmCategory();//获取厂商分级分类
            $firmCategoryToValue = [];
            foreach ($firmCategory as $key=>$val){
                $firmCategoryToValue[$val['category_id']] = $val['category_sname'];
            }
            //获取地址联动信息
            $firmDis = $this->getDistrictLevelOne();
            $firmDisName = '';
            foreach ($firmDis as $k=>$v){
                $firmDisName[$v['district_id']] = $v['district_name'];
            }
            return $this->render('create',[
                'firmType' =>$firmType,
                'firmSource'=>$firmSource,
                'firmPosition'=>$firmPosition,
                'firmCategory' => $firmCategory,
                'firmCategoryToValue' =>$firmCategoryToValue,
                'orgName'=>$orgName,
                'firmDisName'=>$firmDisName
            ]);
        }
    }
    /**
     * @return string
     * 廠商修改頁面
     */
    public function actionUpdate($id){
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            if ($postData['PdFirm']['firm_category_id']==''){
                $postData['firm_category_id']='';
            }else{
                $postData['PdFirm']['firm_category_id']= serialize(explode(',',$postData['PdFirm']['firm_category_id']));//序列化分級分類
            }
            $postData['PdFirm']['update_by'] = Yii::$app->user->identity->staff_id;
            $postData['PdFirm']['update_at'] = date("Y-m-d", time());
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->put($url));
            if ($data->status == 1) {
                SystemLog::addLog('修改ID为' . $id . '的厂商');
                return Json::encode(['msg' => "修改成功", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => '修改失败', "flag" => 0]);
            }
        } else {
            $model = $this->findModel($id,Yii::$app->user->identity->company_id);
            $staffId = $model->create_by;
            $staff = HrStaff::find()->where(['staff_id'=>$staffId])->one();
            $codeName = HrOrganization::find()->where(['organization_code'=>$staff['organization_code']])->one();
            $firmSource = $this->getFirmSoucrceList();
            $firmType = $this->actionFirmTypeList();
            $firmPosition = $this->getFirmPositionList();
            $firmCategory = $this->getFirmCategory();//获取厂商分级分类
            $firmCategoryToValue = [];
            foreach ($firmCategory as $key=>$val){
                $firmCategoryToValue[$val['category_id']] = $val['category_sname'];
            }
            //$firmMessage = PdFirm::find()->where(['firm_id'=>$id])->one();
            $firmMessage = $this->findModel($id,Yii::$app->user->identity->company_id);
            $firmCategory2 = $firmMessage->firm_category_id;

            $category = '';
            if ($firmCategory2!='') {
                $category = unserialize($firmCategory2);//反序列化輸出分級分類
            }
            $districtId = $model->firm_district_id;
            $districtAll = $this->getAllDistrict($districtId);
//            dumpE($model);
            $firmIssupplier = array(
                ''=>'请选择',
                '0'=>'否',
                '1'=>'是'
            );
            return $this->render('update', [
                'model' => $model,
                'firmType' =>$firmType,
                'firmSource'=>$firmSource,
                'firmPosition'=>$firmPosition,
                'firmCategory'=>$firmCategory,
                'firmCategoryToValue'=>$firmCategoryToValue,
                'staff'=>$staff,
                'codeName' =>$codeName,
                'upCateValue'=>Json::encode($category),
                'districtAll' => $districtAll,
                'firmIssupplier'=>$firmIssupplier

            ]);
        }
    }
    /*根据地址五级获取全部信息*/
    public function getAllDistrict($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-info/get-all-district?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /**
     * @param $id
     * @return yii\web\Response
     * 厂商删除操作
     */
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $result = $this->findCurl()->delete($url);
        if (json_decode($result)->status == 1) {
            SystemLog::addLog("刪除了ID=".$id.'的厂商');
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失败", "flag" => 0]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 查询该厂商在拜访计划  拜访履历 谈判 呈报中是否引用
     * F1678089 -- 龚浩晋
     */
    public function actionDeleteCount($id){
        $url = $this->findApiUrl() . $this->_url . "delete-count?id=" . $id;
        $result = $this->findCurl()->put($url);
        return $result;
    }

    /**
     * 获取一级地址
     * @return array|yii\db\ActiveRecord[]
     */
    public function getDistrictLevelOne(){
        //return BsDistrict::getDisLeveOne();
        $url = $this->findApiUrl() . $this->_url . "district-level-one";
        //dumpE($url);
        return Json::decode($this->findCurl()->get($url));
    }
    /**
     * AJAX獲取地址子类
     * @param $id
     * @return string
     */
    public function actionGetDistrict($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-district?id=".$id;
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * 链接新增拜访计划
     * @param $id
     * @return string
     */

    public function actionAddVisitPlan($id){
        $model = $this->findModel($id,'');
        return Json::decode(Json::encode($model));
    }


    private function findModel($id,$companyId)
    {
        $url = $this->findApiUrl() . $this->_url . "model?id=" . $id."&companyId=".$companyId;
        $model = Json::decode($this->findCurl()->get($url), false);
        if (!$model) {
            throw new yii\web\NotFoundHttpException("页面未找到");
        }
        return $model;
    }
    /**
     * 獲取廠商類型列表
     * @return mixed
     */
    public function actionFirmTypeList()
    {
        $url = $this->findApiUrl() . $this->_url . "firm-type-list";
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * 獲取廠商來源列表
     * @return mixed
     */
    public function getFirmSoucrceList()
    {
        $url = $this->findApiUrl() . $this->_url . "firm-soucrce-list";
        //dumpE($url);
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * 獲取廠商地位列表
     * @return mixed
     */
    public function getFirmPositionList()
    {
        $url = $this->findApiUrl() . $this->_url . "firm-position-list";
        return Json::decode($this->findCurl()->get($url));

    }

    /**
     * 獲取分級分類信息
     * @return array|yii\db\ActiveRecord[]
     */
    public function getFirmCategory()
    {
        $url = $this->findApiUrl() . $this->_url . "firm-category";
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * 選擇廠商信息
     */
    public function actionSelectCom()
    {
        $url = $this->findApiUrl() . "ptdt/firm-report/select-coms";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
        return $this->renderAjax('select-com', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 獲取組織名稱在新增時顯示
     * @param $code
     * @return array|null|yii\db\ActiveRecord
     */
    public function getOrgName($code){
        $url = $this->findApiUrl() . $this->_url . "org-name?code=" . $code;
        return Json::decode($this->findCurl()->get($url));
    }

    public function actionFirmInfo($id){
        $firmData = $this->findModel($id,Yii::$app->user->identity->company_id);
        $firmSource = $firmData->firmSource;
        $firmType = $firmData->firmType;
        $firmCategory = $firmData->firm_category_id;

        $categoryType = '';
        if ($firmCategory!='') {
            $categoryType = unserialize($firmCategory);//反序列化輸出分級分類
        }
        return Json::encode([$firmData, $firmSource, $firmType ,$categoryType]);
    }

}