<?php
/**
 * 招商客户开发
 * User: F3859386
 * Date: 2017/2/13
 * Time: 9:12
 */
namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsCurrency;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\crm\Crm;
use app\modules\crm\models\CrmActImessage;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustomerPersion;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmCustShop;
use app\modules\crm\models\CrmImessage;
use app\modules\crm\models\CrmMchpdtype;
use app\modules\crm\models\search\CrmMchpdtypeSearch;
use app\modules\crm\models\CrmVisitRecord;
use app\modules\crm\models\CrmVisitRecordChild;
use app\modules\crm\models\search\CrmCustomerInfoSearch;
use app\modules\crm\models\search\CrmVisitRecordChildSearch;
use app\modules\crm\models\search\CrmImessageSearch;
use app\modules\crm\models\search\CrmActImessageSearch;
use app\modules\crm\models\search\CrmCustomerPersionSearch;
use app\modules\crm\models\show\CrmActImessageShow;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\ptdt\models\BsCategory;
use app\modules\crm\models\show\CrmCustomerPersionShow;
use app\modules\crm\models\show\CrmImessageShow;
use app\modules\crm\models\show\CrmVisitRecordChildShow;
use app\modules\crm\models\show\CrmVisitRecordShow;
use app\modules\hr\models\HrStaff;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

class CrmInvestmentDvelopmentController extends BaseActiveController
{
//    public $modelClass = 'app\modules\crm\models\CrmInvestmentDvelopmentController';
    public $modelClass = 'app\modules\crm\models\CrmCustomerInfo';

    public function actionIndex()
    {
        $searchModel = new CrmCustomerInfoSearch();
        $dataProvider = $searchModel->searchInvestmentDvelopment(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 创建
     * @return array
     */
    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $info = CrmCustomerInfo::findOne(['cust_sname' => $post['CrmCustomerInfo']['cust_sname']]);
        if (!empty($info)) {
            return $this->error("客户已存在,新增失败");
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = new CrmCustomerInfo();
            //设置招商客户类型编码规则
            $model->codeType = CrmCustomerInfo::CODE_TYPE_INVESTMENT;
            $model->load($post);
            if ($post["CrmCustomerInfo"]["member_regweb"] != "") {
                $model->cust_ismember = CrmCustomerInfo::MEMBER_YES;
                $model->member_type = '100070';
            }
            if (!$model->save()) {
                throw new \Exception("新增客户信息失败");
            }
            /*状态*/
            $status = new CrmCustomerStatus();
            $status->customer_id = $model->cust_id;
//            if($model->cust_ismember){
            if ($post["CrmCustomerInfo"]["member_regweb"] != "") {
                $status->member_status = CrmCustomerStatus::STATUS_DEFAULT;
            }
            $status->investment_status = CrmCustomerStatus::STATUS_DEFAULT;
//            }
            if (!$status->save()) {
                throw new \Exception("新增客户信息失败");
            }
            if (isset($post["CrmCustomerPersion"])) {
                for ($x = 0; $x < count($post["CrmCustomerPersion"]["ccper_name"]); $x++) {
                    $personModel = new CrmCustomerPersion();
                    $attrs = array_combine(array_keys($post["CrmCustomerPersion"]), array_column($post["CrmCustomerPersion"], $x));
                    $attrs["cust_id"] = $model->primaryKey;
                    $personModel->setAttributes($attrs);
                    $personModel->ccper_ismain = '0';
                    if (!($personModel->validate() && $personModel->save())) {
                        throw new \Exception("联系人信息保存失败");
                        break;
                    }
                }
            }
            $transaction->commit();
            return $this->success(['name' => $model->cust_sname, 'id' => $model->cust_id]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($model->getErrors());
        }
    }

    /**
     * 更新
     * @param $id
     * @return array
     */
    public function actionUpdate($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $model = CrmCustomerInfo::findOne(['cust_id' => $id]);
            $model->load($post);
            $status = CrmCustomerStatus::findOne(['customer_id' => $id]);
            $status->customer_id = $model->cust_id;
//            if($model->cust_ismember){
//                $status->member_status = CrmCustomerStatus::STATUS_DEFAULT;
//            }else{
//                $model->cust_ismember = CrmCustomerInfo::MEMBER_YES;
//                $status->member_status = CrmCustomerStatus::STATUS_DEL;
//                $model->member_name = null;
//                $model->member_type = null;
//                $model->member_regtime = null;
//            }
            if ($model->cust_ismember == 0) {
                if (!empty($post["CrmCustomerInfo"]["member_regweb"])) {
                    $model->cust_ismember = CrmCustomerInfo::MEMBER_YES;
                    $model->member_type = '100070';
                    $status->member_status = CrmCustomerStatus::STATUS_DEFAULT;
                }
            }
            if (!$status->save()) {
                throw new \Exception("修改客户信息失败");
            }
            if (!$model->save()) {
                throw new \Exception("修改客户信息失败");
            }
            if (isset($post["CrmCustomerPersion"])) {
                CrmCustomerPersion::deleteAll(["cust_id" => $model->primaryKey]);
                for ($x = 0; $x < count($post["CrmCustomerPersion"]["ccper_name"]); $x++) {
                    $personModel = new CrmCustomerPersion();
                    $attrs = array_combine(array_keys($post["CrmCustomerPersion"]), array_column($post["CrmCustomerPersion"], $x));
                    $attrs["cust_id"] = $model->primaryKey;
                    $personModel->setAttributes($attrs);
                    $personModel->ccper_ismain = '0';
                    if (!($personModel->validate() && $personModel->save())) {
                        throw new \Exception("联系人信息保存失败");
                        break;
                    }
                }
            }

            $transaction->commit();
            return $this->success($model->cust_sname);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function actionDelete($id)
    {
        $model = CrmCustomerStatus::findOne($id);
        $model->investment_status = 0;
        $customer = CrmCustomerInfo::findOne($id);
        if ($model->save()) {
            return $this->success($customer->cust_sname);
        }
        return $this->error($customer->cust_sname);
    }

    /**
     * 开店信息
     * @return array
     */
    public function actionShopInfo()
    {
        if ($post = Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model = new CrmCustShop();
                $model->load(['CrmCustShop' => $post]);
                if (!$model->save()) {
                    throw new \Exception("新增开店信息失败");
                }
                /*状态*/
                $status = CrmCustomerStatus::find()->where(['customer_id' => $post['cust_id']])->andWhere(['!=', 'investment_status', CrmCustomerStatus::INVESTMENT_SUCC])->one();
                if (!empty($status)) {
                    $status->investment_status = CrmCustomerStatus::INVESTMENT_SUCC;
                    if (!$status->save()) {
                        throw new \Exception("新增开店信息失败");
                    }
                }
                $transaction->commit();
                return $this->success($model->shop_name);
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        //币别
        $downList['tradeCurrency'] = BsPubdata::getList(BsPubdata::SUPPLIER_TRADE_CURRENCY);
        return $downList;
    }

    public function actionShop($id)
    {
        $info = CrmCustShop::find()
            ->select([
                CrmCustShop::tableName() . ".*",
                CrmCustomerInfo::tableName() . ".cust_sname",
                CrmCustomerInfo::tableName() . ".cust_shortname",
                BsPubdata::tableName() . ".bsp_svalue member_type",
                CrmCustomerInfo::tableName() . ".member_name",
                CrmCustomerInfo::tableName() . ".member_regtime",
            ])
            ->leftJoin(CrmCustomerInfo::tableName(), CrmCustomerInfo::tableName() . ".cust_id=" . CrmCustShop::tableName() . ".cust_id")
            ->leftJoin(BsPubdata::tableName(), BsPubdata::tableName() . ".bsp_id=" . CrmCustomerInfo::tableName() . ".member_type")
            ->where(["shop_id" => $id])->asArray()->one();
        $info['tradeCurrency'] = BsPubdata::getList(BsPubdata::SUPPLIER_TRADE_CURRENCY);;
        return $info;
    }

    public function actionShopEdit($id)
    {
        try {
            $params = \Yii::$app->request->post();
            $model = CrmCustShop::findOne($id);
            $model->load($params);
            if (!($model->validate() && $model->save())) {
                throw new \Exception("修改失败");
            }
            return $this->success("修改成功");
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

    }

    /**
     * @param $id
     * @return array
     * 删除店铺信息
     */
    public function actionDeleteShop($id)
    {
        $model = CrmCustShop::findOne($id);
        $model->shop_status = CrmCustShop::STATUS_DELETE;
        $result = $model->save();
        $shop = CrmCustShop::find()->where(['and', ['cust_id' => $model['cust_id']], ['!=', 'shop_status', CrmCustShop::STATUS_DELETE]])->count();
        if ($shop == 0) {
            $cust = CrmCustomerStatus::findOne(['customer_id' => $model['cust_id']]);
            $cust->investment_status = CrmCustomerStatus::STATUS_DEFAULT;
            $cust->save();
        }
        if ($result) {
            return $this->success($model->shop_name);
        }
        return $this->error($model->shop_name);
    }
//    //选择客户信息
//    public function actionSelectCustomer()
//    {
//        $model=new CrmCustomerInfo();
//        $dataProvider=$model->searchCustomerInfo(Yii::$app->request->queryParams);
//        return [
//            'rows'=>$dataProvider->getModels(),
//            'total'=>$dataProvider->totalCount,
//        ];
//    }

    /**
     * 导入客户
     * @param $companyId
     * @param $createBy
     * @return array
     */
//    public function actionImportCustomer($companyId,$createBy){
//        $post = Yii::$app->request->post();
//        static $succ = 0;
//        static $err = 0;
//        foreach ($post as $k => $v) {
//            if ($k >= 0) {
//                $trans=Yii::$app->db->beginTransaction();
//                try{
//                    $model=new CrmCustomerInfo();
//                    // 根据工号查询数据,存在不插入
//                    if ($v['B'] != '' && !$model::find()->where(['cust_sname' => $v['B']])->one()) {
//                        $model->cust_sname = trim($v['B']);
//                        $model->cust_shortname = trim($v['C']);
//                        $model->cust_contacts = trim($v['D']);
//                        $model->cust_position = trim($v['E']);
//                        $model->cust_tel2 = trim($v['F']);
//                        $model->cust_email = trim($v['G']);
//                        $model->cust_tel1 = trim($v['H']);
//                        $model->cust_district_2=BsDistrict::find()->select("district_id")->where(["district_name"=>$v["J"],"district_level"=>3])->scalar();
//                        $model->cust_tel1 = $v['I'];
//                        $model->cust_tel1 = $v['J'];
//                        $model->cust_adress = trim($v['K']);
//                        $lType = BsPubdata::getExcelData(trim($v['L']));
//                        $model->cust_businesstype = !empty($lType["bsp_id"])?$lType["bsp_id"]:null;
//                        $mType = BsPubdata::getExcelData(trim($v['M']));
//                        $model->member_regweb = !empty($mType["bsp_id"])?$mType["bsp_id"]:null;
//                        $nType = BsPubdata::getExcelData(trim($v['N']));
//                        $model->member_reqflag = !empty($nType["bsp_id"])?$nType["bsp_id"]:null;
//                        $oType = BsPubdata::getExcelData(trim($v['O']));
//                        $model->member_reqitemclass = !empty($oType['bsp_id'])?$oType['bsp_id']:null;
//
//                        // 插入數據
//                        $model->company_id = $companyId;
//                        $model->create_by = $createBy;
//                        if(!$model->save()){
//                            throw new \Exception("");
//                        }
//                        $custId = $model->primaryKey;
//                        $status = new CrmCustomerStatus();
//                        $status->customer_id = $custId;
//                        $status->investment_status = CrmCustomerStatus::STATUS_DEFAULT;
//                        if (!$status->save()) {
//                            $err++;
//                            throw new \Exception($status->getErrors());
//                        }
//                    } else {
//                        $err++;
//                    }
//                    $succ++;
//                    $trans->commit();
//                }catch (\Exception $e){
//                    $err++;
//                    $trans->rollBack();
//                }
//            }
//        }
//        return ["succ"=>$succ,"error"=>$err];
//    }

    /**
     * 点击加载
     */
    public function actionLoadInfo($id)
    {
        $condition = ['cust_id' => $id];
        $data['record'] = $this->loadRecord($condition);
        $data['shop'] = $this->loadShop($condition);
        $data['messages'] = $this->LoadMessage($condition);
        $data['reminder'] = $this->LoadReminder($condition);
        $data['contacts'] = $this->LoadContacts($condition);
        return $data;
    }

    /**
     * @return mixed
     * 拜访记录列表
     */
    public function actionLoadRecord()
    {
        $searchModel = new CrmVisitRecordChildSearch();
        $dataProvider = $searchModel->searchInvestmentInfo(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @return mixed
     * 开店信息
     */
    public function actionLoadShop()
    {
        $searchModel = new CrmCustShop();
        $dataProvider = $searchModel->searchShopInfo(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @return mixed
     * 提醒事项
     */
    public function actionLoadReminders()
    {
        $searchModel = new CrmImessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @return mixed
     * 通讯记录
     */
    public function actionLoadMessage()
    {
        $searchModel = new CrmActImessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @return mixed
     * 其他联系人
     */
    public function actionLoadContacts()
    {
        $searchModel = new CrmCustomerPersionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->request->queryParams["id"]);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 分配员工
     */
    public function actionAssignStaff($id, $class)
    {
        $custInfo = CrmCustomerInfo::findOne($id);
        if ($postData = Yii::$app->request->post()) {

            $transaction = Yii::$app->db->beginTransaction();
            try {
                //判断是否认领,已认领则取消
                $personinch = CrmCustPersoninch::getByOne($postData['CrmCustPersoninch']['cust_id']);
                if (!empty($personinch)) {
                    $result = $personinch->delete();
                    $custInfo->assign_status = CrmCustomerInfo::ASSIGN_STATUS_NO;
                    $results = $custInfo->save();
                    if ($result && $results) {
                        $transaction->commit();
                        return $this->success('', '招商客户[' . $custInfo['cust_shortname'] . ']取消分配员工');
                    } else {
                        $transaction->rollBack();
                        return $this->error();
                    }
                }
                //负责人信息
                $crmMchpdtype = CrmMchpdtype::find()->where(['staff_code' => $postData['assignStaff']])->andWhere(['and', ['like', 'category_id', $postData['mainType']], ['=', 'mpdt_status', CrmMchpdtype::STATUS_DEFAULT]])->one();
                $model = new CrmCustPersoninch();
                $model->ccpich_stype = CrmCustPersoninch::PERSONINCH_INVEST;
                $model->ccpich_personid = $crmMchpdtype->id;
                $model->load($postData);
                $custInfo->assign_status = CrmCustomerInfo::ASSIGN_STATUS_YES;
                $custInfo->member_reqitemclass = $postData['mainType'];
//            if(empty($custInfo['member_reqitemclass'])){
//                $custInfo->member_reqitemclass = $postData['mainType'];
//            }
                $custInfo->save();
                if (!$model->save()) {
                    return $this->error();
                }
                $transaction->commit();
                return $this->success('', '招商客户[' . $custInfo['cust_shortname'] . ']分配员工');
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        $personinch = CrmCustPersoninch::getByOne($id);
        if (!empty($personinch)) {
            $model = CrmMchpdtype::find()->where(['id' => $personinch->ccpich_personid])->select('staff_code,category_id')->one();
            $data['model'] = $model;
        }
        $data['mchpdtype'] = BsCategory::getLevelOne();
        return $data;
    }

    //抛至公海
    public function actionThrowSea($arrId)
    {
        $arrId = explode(',', $arrId);
        foreach ($arrId as $id) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //客户表
                $customerModel = CrmCustomerStatus::findOne($id);
                $customerModel->investment_status = CrmCustomerStatus::STATUS_DEL;
                if ($customerModel->member_status == CrmCustomerStatus::STATUS_DEFAULT) {
                    $customerModel->member_status = CrmCustomerStatus::STATUS_DEFAULT;
                } else {
                    $customerModel->potential_status = CrmCustomerStatus::STATUS_DEFAULT;
                }
                if (!$customerModel->save()) {
                    throw new \Exception('操作失败！');
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }
        return $this->success();
    }

    public function actionGetAssignStaff($type)
    {
        return CrmMchpdtypeSearch::find()->select("staff_code")->where(['mpdt_status' => CrmMchpdtype::STATUS_DEFAULT])->groupBy('staff_code')->andFilterWhere(['like', 'category_id', $type])->all();
    }

    public function isArrEmpty($arr)
    {
        $result = true;
        foreach ($arr as $k => $v) {
            $result = $result && empty($v);
        }
        return $result;
    }

    /**
     * 导入客户资料
     * @param $companyId
     * @param $createBy
     * @return array
     */
    public function actionImport($companyId, $createBy)
    {
        $post = Yii::$app->request->post();
        static $succ = 0;
        static $err = 0;
        $log = [];
        foreach ($post as $k => $v) {
            $result = true;
            //去除空行
            foreach ($v as $key => $value) {
                $result = $result && empty($value);
            }
            if (!$result) {
                $modelInfo = CrmCustomerInfo::find()->where(['cust_sname' => $v['B']])->one();
                if (empty($v['B']) && !empty($modelInfo)) {
                    $err += 1;
                    continue;
                }
                $trans = Yii::$app->db->beginTransaction();
                try {
                    $model = new CrmCustomerInfo();
                    $model->cust_sname = trim($v['B']);
                    $model->cust_shortname = trim($v['C']);
                    $model->cust_contacts = trim($v['D']);
                    $model->cust_position = trim($v['E']);
                    $model->cust_tel2 = trim($v['F']);
                    $model->cust_email = trim($v['G']);
                    $model->cust_tel1 = trim($v['H']);
                    $model->cust_adress = trim($v['I']) . trim($v['J']) . trim($v['K']);
                    if (!empty($v["L"])) {
                        $lType = BsPubdata::getExcelData(trim($v['L']));
                        $model->cust_businesstype = !empty($lType["bsp_id"]) ? $lType["bsp_id"] : null;
                    }
                    if (!empty($v["M"])) {
                        $mType = BsPubdata::getExcelData(trim($v['M']));
                        $model->member_source = !empty($mType["bsp_id"]) ? $mType["bsp_id"] : null;
                    }
                    if (!empty($v["N"])) {
                        $nType = BsPubdata::getExcelData(trim($v['N']));
                        $model->member_reqflag = !empty($nType["bsp_id"]) ? $nType["bsp_id"] : null;
                    }
                    if (!empty($v["O"])) {
                        $oType = BsCategory::getExcelData(trim($v['O']));
                        $model->member_reqitemclass = !empty($oType['catg_id']) ? $oType['catg_id'] : null;
                    }
                    $model->company_id = $companyId;
                    $model->create_by = $createBy;
                    $model->save();
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                    $status = new CrmCustomerStatus();
                    $status->customer_id = $model->primaryKey;
                    $status->investment_status = CrmCustomerStatus::STATUS_DEFAULT;
                    if (!$status->save()) {
                        throw new \Exception(json_encode($status->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                    $succ += 1;
                    $trans->commit();
                } catch (\Exception $e) {
                    $log[] = [
                        'file' => basename(get_class()) . ":" . $e->getLine(),
                        'message' => $e->getMessage()
                    ];
                    $err += 1;
                    $trans->rollBack();
                }
            }
        }
        return ["succ" => $succ, "error" => $err, 'log' => $log];
    }

    public function actionDownList($search = null)
    {
        if ($search !== null) {
            //客户来源
            $downList['customerSource'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_SOURCE);
            //招商状态
            $downList['investmentStatus'] = [
                CrmCustomerStatus::INVESTMENT_UN => '未开发',
                CrmCustomerStatus::INVESTMENT_IN => '开发中',
//                CrmCustomerStatus::INVESTMENT_SUCC => '开发成功',
//                CrmCustomerStatus::INVESTMENT_FAILURE=> '开发失败'
            ];
            $downList['personinchStatus'] = [
                CrmCustomerInfo::PERSONINCH_NO => '未分配',
                CrmCustomerInfo::PERSONINCH_YES => '已分配',
            ];
            //会员类别
            $downList['memberType'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_CLASS);
        } else {
            //会员类别
            $downList['memberType'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_CLASS);
            //注册网站
            $downList['regWeb'] = BsPubdata::getList(BsPubdata::CRM_REGISTER_WEB);
            //客户来源
            $downList['customerSource'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_SOURCE);

            //公司类型
            $downList['property'] = BsPubdata::getList(BsPubdata::CRM_COMPANY_PROPERTY);
            //币别
            $downList['tradeCurrency'] = BsPubdata::getList(BsPubdata::SUPPLIER_TRADE_CURRENCY);

            //国家
            $downList['country'] = BsDistrict::getDisLeveOne();//所在国家
        }
        //需求类目
        $downList['productType'] = BsCategory::getLevelOne();
        //经营模式
        $downList['businessModel'] = BsPubdata::getList(BsPubdata::CRM_MANAGEMENT_TYPE);
        //潜在需求
        $downList['latentDemand'] = BsPubdata::getList(BsPubdata::CRM_LATENT_DEMAND);
        $downList['invoice'] = BsPubdata::getList(BsPubdata::CRM_INVOICE_NEEDS);       //发票需求
        $downList['custFunction'] = BsPubdata::getList(BsPubdata::CUST_FUNCTION);//职位职能
        return $downList;
    }

    /*
     * 拜访信息
     */
    protected function loadRecord($condition)
    {
        $visit = CrmVisitRecord::find()->where($condition)->one();
        $recordList = CrmVisitRecordChildShow::find()->where(['!=', 'sil_status', CrmVisitRecord::STATUS_DELETE])->andWhere(['sih_id' => $visit['sih_id']])->orderBy(['create_at' => SORT_DESC])->all();

        $list["rows"] = $recordList;
        $list["total"] = count($recordList);
        return $list;


    }


    /**
     * 开店信息
     * @param $condition
     * @return mixed
     */
    protected function loadShop($condition)
    {
        $shop = CrmCustShop::getByAll($condition);
        $list["rows"] = $shop;
        $list["total"] = count($shop);
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * 通讯记录
     */
    protected function LoadMessage($condition)
    {
        $model = CrmActImessageShow::getActImessages($condition);
        $list["rows"] = $model;
        $list["total"] = count($model);
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * 提醒事项列表
     */
    protected function LoadReminder($condition)
    {
        $model = CrmImessageShow::getIMessages($condition);
        $list["rows"] = $model;
        $list["total"] = count($model);
        return $list;
    }

    public function actionModels($id)
    {
        return CrmCustomerInfoShow::findOne(['cust_id' => $id]);
    }

    protected function LoadContacts($condition)
    {
        $model = CrmCustomerPersionShow::getContacts($condition);
        $list["rows"] = $model;
        $list["total"] = count($model);
        return $list;
    }

    public function actionUpdateContacts($id)
    {
        $model = CrmCustomerPersion::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function actionDeleteContacts($id)
    {
        $model = CrmCustomerPersion::findOne($id);
        $model->ccper_status = CrmCustomerPersion::STATUS_DELETE;
        if ($model->save()) {
            return $this->success($model->ccper_name);
        }
        return $this->error($model->ccper_name);
    }
}
