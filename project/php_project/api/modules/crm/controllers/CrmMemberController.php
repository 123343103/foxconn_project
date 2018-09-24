<?php

namespace app\modules\crm\controllers;

use app\classes\Trans;
use app\controllers\BaseActiveController;
use app\modules\ptdt\models\BsCategory;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\common\tools\CheckUsed;
use app\modules\crm\models\CrmActImessage;
use app\modules\crm\models\CrmActiveApply;
use app\modules\crm\models\CrmC;
use app\modules\crm\models\CrmCreditApply;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmImessage;
use app\modules\crm\models\CrmMember;
use app\modules\crm\models\CrmVisitRecordChild;
use app\modules\crm\models\search\CrmActImessageSearch;
use app\modules\crm\models\search\CrmActiveApplySearch;
use app\modules\crm\models\search\CrmCustomerInfoSearch;
use app\modules\crm\models\search\CrmImessageSearch;
use app\modules\crm\models\search\CrmVisitRecordChildSearch;
use app\modules\crm\models\show\CrmImessageShow;
use app\modules\crm\models\show\CrmMemberShow;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

/**
 * CrmMemberController implements the CRUD actions for CrmMember model.
 */
class CrmMemberController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmMember';

    public function actionIndex()
    {
        $searchModel = new CrmCustomerInfoSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->searchMember($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = CrmCustomerInfo::findOne($post['CrmCustomerInfo']['cust_id']);
            if (empty($model)) {
                $model = new CrmCustomerInfo();
            }
            $model->load($post);
            $model->cust_ismember = CrmCustomerInfo::MEMBER_YES;
            $model->codeType = CrmCustomerInfo::CODE_TYPE_MEMBER;
            if (!$model->save()) {
                throw new \Exception("新增会员失败");
            }
            $custId = $model->cust_id;
            $member = $model->member_name;
            /*状态*/
            $status = CrmCustomerStatus::findOne($custId);
            if (!empty($status)) {
                if ($status['potential_status'] == CrmCustomerStatus::STATUS_DEFAULT) {
                    $status->potential_status = CrmCustomerStatus::STATUS_DEL;
                }
            } else {
                $status = new CrmCustomerStatus();
            }
            $status->customer_id = $custId;
            $status->member_status = CrmCustomerStatus::STATUS_DEFAULT;
            if (!$status->save()) {
                throw new \Exception("新增会员失败");
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        $data = array('id' => $custId, 'msg' => '新增会员[ ' . $member . ' ]信息');
        return $this->success($msg = null, $data);
    }

    /**
     * @param $id
     * @return array
     * 更新
     */
    public function actionUpdate($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $contact = CrmCustomerInfo::findOne($id);
        $post = Yii::$app->request->post();
        try {
            $contact->load($post);
            if (!$contact->save()) {
                throw new \Exception("修改信息失败");
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success('', '修改会员[' . $contact["member_name"] . ']信息');
    }

    /**
     * @param $id
     * @return array
     * 删除客户信息
     */
    public function actionDeleteCustomer($id)
    {
        $data = explode(',', $id);
        $err = 0;
        $suc = 0;
        $name = '';
        foreach ($data as $key => $val) {
            $checkUsed = new CheckUsed();
            $used = $checkUsed->check($val, 'cust_id');
            if ($used['status'] == 0) {
//                return $this->error($used['msg']);
                $err++;
                continue;
            }else{
                $suc++;
            }
            $member = CrmCustomerInfo::getCustomerInfoOne($val);
            $model = $this->getModel($val);
            $model->member_status = CrmCustomerStatus::STATUS_DEL;
            $name = $name . $member['member_name'] . ',';
            $result = $model->save();
        }
        $cust_sname = trim($name, ',');
        if ($result) {
            return $this->success('成功删除'.$suc.'条,失败'.$err.'条', '删除会员[' . $cust_sname . ']信息');
        } else {
            return $this->error();
        }
    }

    /**
     * @param $id
     * @return array
     * 删除回访记录等信息
     */
//    public function actionDelete($id)
//    {
//        $member = CrmCustomerInfo::getCustomerInfoOne($id);
//        $checkUsed = new CheckUsed();
//        $used = $checkUsed->check($id, 'cust_id');
//        if ($used['status'] == 0) {
//            return $this->error($used['msg']);
//        }
//        $model = $this->getModel($id);
//        $model->member_status = CrmCustomerStatus::STATUS_DEL;
//        if ($result = $model->save()) {
//            return $this->success('', '删除会员[' . $member["member_name"] . ']信息');
//        } else {
//            return $this->error();
//        }
//    }

    /**
     * @return array
     * 邮件发送
     */
    public function actionSendEmail()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $postData = Yii::$app->request->post();
        try {
            $arr1 = explode(',', $postData['CrmActImessage']['cust_id']);
            foreach ($arr1 as $key => $val) {
                if ($key != count($arr1) - 1) {
                    $model = new CrmActImessage();
                    $model->load($postData);
                    $model->cust_id = $val;
                    $model->imesg_type = CrmActImessage::TYPE_EMAIL;
                    if (!$model->save()) {
                        throw new \Exception("修改信息失败");
                    }
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success('', '发送邮件');
    }

    /**
     * @return array
     * 新增提醒事项
     */
    public function actionCreateReminders()
    {
        $model = new CrmImessage();
        $post = Yii::$app->request->post();
        $model->load($post);
        $model->cust_id = $post['CrmImessage']['cust_id'];
        $cust = CrmCustomerInfo::getCustomerInfoOne($post['CrmImessage']['cust_id']);
        if ($result = $model->save()) {
            return $this->success('', '新增客户[' . $cust["cust_sname"] . ']提醒信息');
        } else {
            return $this->error();
        }
    }

    /**
     * @return array
     * 修改提醒事项
     */
    public function actionUpdateReminders($id)
    {
        $model = CrmImessage::findOne($id);
        $post = Yii::$app->request->post();
        $model->load($post);
        $model->cust_id = $post['CrmImessage']['cust_id'];
        $cust = CrmCustomerInfo::getCustomerInfoOne($post['CrmImessage']['cust_id']);
        if ($result = $model->save()) {
            return $this->success('', '修改客户[' . $cust["cust_sname"] . ']提醒信息');
        } else {
            return $this->error();
        }
    }


    /**
     * @param $id
     * @return mixed
     * 列表
     */
//    public function actionLoadInformation()
//    {
//        $result['reminder'] = $this->actionLoadReminder();
//        $result['record'] = $this->actionLoadRecord();
//        $result['active'] = $this->actionLoadActive();
//        $result['message'] = $this->actionLoadMessage();
//        return $result;
//    }

    /**
     * @return mixed
     * 回访记录列表
     */
    public function actionLoadRecord()
    {
        $searchModel = new CrmVisitRecordChildSearch();
        $dataProvider = $searchModel->searchMemberInfo(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * 提醒事项列表
     */
    public function actionLoadReminder()
    {
        $searchModel = new CrmImessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * 活动信息列表
     */
    public function actionLoadActive()
    {
        $searchModel = new CrmActiveApplySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
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
     * 新增会员选择客户
     */
    public function actionSelectCustomer()
    {
        $searchModel = new CrmCustomerInfoSearch();
        $dataProvider = $searchModel->searchCheckMember(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;

    }

    /**
     * @param $id
     * @return array
     * 转招商
     */
    public function actionTurnInvestment($str)
    {
        $arr = explode(',', $str);
        foreach ($arr as $key => $val) {
            $model = $this->getStatusModel($val);
            $model->potential_status = CrmCustomerStatus::STATUS_DEL;
            $model->investment_status = CrmCustomerStatus::STATUS_DEFAULT;
            $cust = CrmCustomerInfo::getCustomerInfoOne($val);
            $result = $model->save();
        }
        if ($result) {
            return $this->success('', '客户[' . $cust["cust_sname"] . ']转招商');
        } else {
            return $this->error();
        }
    }


    /**
     * @param $id
     * @return array
     * 转销售
     */
    public function actionTurnSales($str)
    {
        $arr = explode(',', $str);
        foreach ($arr as $key => $val) {
            $model = $this->getStatusModel($val);
            $model->investment_status = CrmCustomerStatus::STATUS_DEL;
            $model->potential_status = CrmCustomerStatus::STATUS_DEL;
            $model->sale_status = CrmCustomerStatus::STATUS_DEFAULT;
            $inch = CrmCustPersoninch::find()->where(['and', ['cust_id' => $val], ['ccpich_stype' => CrmCustPersoninch::PERSONINCH_DEFAULT]])->one();
            if (!empty($inch)) {
                $inch->delete();
            }
            $cust = CrmCustomerInfo::getCustomerInfoOne($val);
            $result = $model->save();
        }
        if ($result) {
            return $this->success('', '客户[' . $cust["cust_sname"] . ']转销售');
        } else {
            return $this->error();
        }
    }

    /**
     * @param $id
     * @return array
     * 关闭提醒事项
     */
    public function actionCloseReminders($id)
    {
        $reminder = CrmImessage::find()->where(['imesg_id' => $id])->one();
        $cust = CrmCustomerInfo::getCustomerInfoOne($reminder['cust_id']);
        $reminder->imesg_status = CrmImessage::STATUS_NONE;
        if ($reminder->save()) {
            return $this->success('', '关闭客户[' . $cust["cust_sname"] . ']提醒事项');
        } else {
            return $this->error();
        }
    }

    /**
     * @param $companyId
     * @param $createBy
     * @return array
     * 导入
     */
    public function actionImport($companyId, $createBy)
    {
        $post = Yii::$app->request->post();
        static $succ = 0;
        static $err = 0;
        $log = [];
        foreach ($post as $k => $v) {
            //跳过第一列标题
            if ($k >= 0) {
                $trans = Yii::$app->db->beginTransaction();
                try {
                    $time = 25569;
                    $tran = new Trans();
                    // 根據工號查詢數據，如存在則不插入數據
                    $model = new CrmMember();
                    $model->member_regtime = !empty($v["B"]) ? date('Y-m-d',($v['B']-$time)*3600*24) : null;     //"注册时间"
                    $model->member_name = $v['C'];            //"会员名称"
                    $model->cust_sname = !empty($v["D"]) ? $v["D"] : null;     //"公司名称"
                    $model->cust_regfunds = !empty($v["E"]) ? $v["E"] : null;     //"注册资金"
                    $model->cust_regnumber = !empty($v["F"]) ? $v["F"] : null;     //"营业执照注册号"
                    $model->cust_contacts = !empty($v["G"]) ? $v["G"] : null;     //"用户姓名"
                    $model->cust_tel1 = !empty($v["H"]) ? $v["H"] : null;     //"公司电话"
                    $model->cust_email = !empty($v["I"]) ? $v["I"] : null;     //"邮箱"
                    $model->cust_tel2 = !empty($v["J"]) ? $v["J"] : null;     //"手机号码"
                    $b = !empty($v["K"]) ? $tran->t2c($v["K"]) : null;
                    $type = BsPubdata::getExcelData($b);
                    $model->member_type = !empty($type['bsp_id']) ? $type['bsp_id'] : null;     //"会员类型"
                    $model->member_points = !empty($v["L"]) ? $v["L"] : null;     //"会员积分"
                    $c = !empty($v["M"]) ? $tran->t2c($v["M"]) : null;
                    $level = BsPubdata::getExcelData([$c]);
                    $model->member_level = !empty($level['bsp_id']) ? $level['bsp_id'] : null;     //"会员等级"
                    $model->member_certification = !empty($v["N"]) ? date('Y-m-d',($v['N']-$time)*3600*24) : null;     //"认证完成时间"
                    $d = !empty($v["O"]) ? $tran->t2c($v["O"]) : null;
                    $reg = BsPubdata::getExcelData($d);
                    $model->member_regweb = !empty($reg['bsp_id']) ? $reg['bsp_id'] : null;     //"注册网站"
                    // 插入數據
                    $model->company_id = $companyId;
                    $model->create_by = $createBy;
                    $model->cust_ismember = CrmCustomerInfo::MEMBER_YES;
                    if (!($model->validate() && $model->save())) {
                        throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                    $custId = $model->cust_id;
                    $status = new CrmCustomerStatus();
                    $status->customer_id = $custId;
                    $status->member_status = CrmCustomerStatus::STATUS_DEFAULT;
                    $status->save();
                    $succ++;
                    $trans->commit();
                } catch (\Exception $e) {
                    $log[] = [
                        'file' => basename(get_class()) . ":" . $e->getLine(),
                        'message' => $e->getMessage()
                    ];
                    $err++;
                    $trans->rollBack();
                }
            }
        }
        return ["succ" => $succ, "error" => $err, 'log' => $log];
    }


    public function actionDownList()
    {
        //会员类别
        $downList['memberType'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_CLASS);
        //会员等级
        $downList['memberLevel'] = BsPubdata::getList(BsPubdata::CRM_MEMBER_LEVEL);
        //注册网站
        $downList['regWeb'] = BsPubdata::getList(BsPubdata::CRM_REGISTER_WEB);
        //客户来源
        $downList['customerSource'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_SOURCE);
        //经营模式
        $downList['managementType'] = BsPubdata::getList(BsPubdata::CRM_MANAGEMENT_TYPE);
        //公司类型
        $downList['property'] = BsPubdata::getList(BsPubdata::CRM_COMPANY_PROPERTY);
        //币别
        $downList['tradeCurrency'] = BsPubdata::getList(BsPubdata::SUPPLIER_TRADE_CURRENCY);
        //潜在需求
        $downList['latentDemand'] = BsPubdata::getList(BsPubdata::CRM_LATENT_DEMAND);
        //发票需求
        $downList['InvoiceNeeds'] = BsPubdata::getList(BsPubdata::CRM_INVOICE_NEEDS);
        //需求类目
        $downList['productType'] = BsCategory::getLevelOne();
        $downList['country'] = BsDistrict::getDisLeveOne();//所在国家
        $downList['visitType'] = BsPubdata::getData(BsPubdata::CRM_VISIT_TYPE);//拜访类型
        $downList['custFunction'] = BsPubdata::getList(BsPubdata::CUST_FUNCTION);//职位职能
        return $downList;
    }

    /**
     * @param $id
     * @return null|static
     * 查询状态
     */
    public function actionGetStatus($id){
        $model = CrmCustomerStatus::findOne($id);
        return $model;
    }

    /**
     * @param $id
     * @return null|static
     * 查询提醒事项
     */
    public function actionGetReminder($id){
        return CrmImessageShow::findOne($id);
    }

    public function actionModels($id)
    {
        $result = CrmMemberShow::getCustomerInfoOne($id);
        return $result;
    }

    protected function getStatusModel($id)
    {
        if (($model = CrmCustomerStatus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 查询主表
     */
    protected function getModel($id)
    {
        if (($model = CrmCustomerStatus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 查询子表
     */
    protected function getChildModel($id)
    {
        if (($model = CrmVisitRecordChild::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 查询活动信息
     */
    protected function getActiveModel($id)
    {
        if (($model = CrmActiveApply::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 查询提醒事项
     */
    protected function getReminderModel($id)
    {
        if (($model = CrmImessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 查询通讯记录
     */
    protected function getMessageModel($id)
    {
        if (($model = CrmActImessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $data
     * @return mixed
     * 客户名称搜索(全部)
     */
    public function actionSelectCom($data)
    {
        $query = (new Query())->select([
            'info.cust_id',                  //id
            'cust_sname',               //公司名称
            'cust_shortname',           //公司简称
            'member_name',              //用户名
            'member_regweb',            //注册网站
            'cust_tel1',                //公司电话
            'member_compzipcode',       //邮编
            'cust_contacts',            //联系人
            'cust_position',            //联系人职位
            'cust_tel2',                //手机号码
            'cust_email',               //邮箱
            'cust_district_2',          //地址
            'cust_adress',              //详细地址
            'cust_inchargeperson',      //法人代表
            'cust_regdate',             //注册时间
            'cust_regfunds',            //注册资金
            'member_regcurr',           //注册货币
            'cust_compvirtue',          //公司类型
            'member_source',            //客户来源
            'cust_businesstype',        //经营模式
            'member_curr',              //交易币种
            'member_compsum',           //年营业额
            'compsum_cur',              //年营业额币别
            'cust_pruchaseqty',         //年采购额
            'pruchaseqty_cur',          //年采购额币别
            'cust_personqty',           //员工人数
            'member_compreq',           //发票需求
            'member_reqflag',           //潜在需求
            'member_reqitemclass',      //需求类目
            'member_reqdesription',     //需求类别
            'member_compcust',          //主要客户
            'member_marketing',         //主要市场
            'member_compwebside',       //主页
            'member_businessarea',      //经营范围
            'member_remark',            //备注
            'district1.district_name area_name',    //地区名称
            'district2.district_id city_id',        //城市ID
            'district2.district_name city_name',    //城市名称
            'district3.district_id provice_id',     //省份ID
            'district3.district_name provice_name', //省份名称
            'district4.district_id country_id',     //国家ID
            'district4.district_name country_name', //国家名称
            'crmc.crtf_pkid',                       //会员认证ID
            'crmc.yn',                              //会员认证状态
            'apply.aid',                            //账信申请ID
            'bp_1.bsp_svalue memberType'            //会员类型名称
        ])
            ->from(CrmCustomerInfo::tableName() . ' info')
            ->leftJoin(CrmCustomerStatus::tableName() . ' status', 'status.customer_id=info.cust_id')
            ->leftJoin(CrmC::tableName().' crmc', 'crmc.cust_id = info.cust_id')
            ->leftJoin(BsPubdata::tableName().' bp_1','bp_1.bsp_id = info.member_type')
            ->leftJoin(CrmCreditApply::tableName().' apply', 'apply.cust_id = info.cust_id')
            ->leftJoin(BsDistrict::tableName() . ' district1', 'district1.district_id=info.cust_district_2')
            ->leftJoin(BsDistrict::tableName() . ' district2', 'district2.district_id=district1.district_pid')
            ->leftJoin(BsDistrict::tableName() . ' district3', 'district3.district_id=district2.district_pid')
            ->leftJoin(BsDistrict::tableName() . ' district4', 'district4.district_id=district3.district_pid')
            ->where(['like', 'cust_sname', $data])
            ->andWhere(['or', ['!=', 'status.member_status', CrmCustomerStatus::STATUS_DEFAULT], ['is', 'status.member_status', null]])
            ->all();
        return $query;
    }

    /**
     * @param $data
     * @return mixed
     * 客户名称搜索(单个),带出详细信息
     */
    public function actionSelectComOne($data)
    {
        $query = (new Query())->select([
            'info.cust_id',                  //id
            'info.cust_code',                  //客户申请代码
            'apply.credit_code',                  //申请单号
            'cust_sname',               //公司名称
            'cust_shortname',           //公司简称
            'member_name',              //用户名
            'member_regweb',            //注册网站
            'cust_tel1',                //公司电话
            'member_compzipcode',       //邮编
            'cust_contacts',            //联系人
            'cust_position',            //联系人职位
            'cust_tel2',                //手机号码
            'cust_email',               //邮箱
            'cust_district_2',          //地址
            'cust_adress',              //详细地址
            'cust_inchargeperson',      //法人代表
            'cust_regdate',             //注册时间
            'cust_regfunds',            //注册资金
            'member_regcurr',           //注册货币
            'cust_compvirtue',          //公司类型
            'member_source',            //客户来源
            'cust_businesstype',        //经营模式
            'member_curr',              //交易币种
            'member_compsum',           //年营业额
            'compsum_cur',              //年营业额币别
            'cust_pruchaseqty',         //年采购额
            'pruchaseqty_cur',          //年采购额币别
            'cust_personqty',           //员工人数
            'member_compreq',           //发票需求
            'member_reqflag',           //潜在需求
            'member_reqitemclass',      //需求类目
            'member_reqdesription',     //需求类别
            'member_compcust',          //主要客户
            'member_marketing',         //主要市场
            'member_compwebside',       //主页
            'member_businessarea',      //经营范围
            'member_remark',            //备注
            'district1.district_name area_name',    //地区名称
            'district2.district_id city_id',        //城市ID
            'district2.district_name city_name',    //城市名称
            'district3.district_id provice_id',     //省份ID
            'district3.district_name provice_name', //省份名称
            'district4.district_id country_id',     //国家ID
            'district4.district_name country_name', //国家名称
            'crmc.crtf_pkid',                       //会员认证ID
            'crmc.yn',                              //会员认证状态
            'apply.aid',                            //账信申请ID
            'bp_1.bsp_svalue memberType'            //会员类型名称
        ])
            ->from(CrmCustomerInfo::tableName() . ' info')
            ->leftJoin(CrmCustomerStatus::tableName() . ' status', 'status.customer_id=info.cust_id')
            ->leftJoin(CrmC::tableName().' crmc', 'crmc.cust_id = info.cust_id')
            ->leftJoin(CrmCreditApply::tableName().' apply', 'apply.cust_id = info.cust_id')
            ->leftJoin(BsPubdata::tableName().' bp_1','bp_1.bsp_id = info.member_type')
            ->leftJoin(BsDistrict::tableName() . ' district1', 'district1.district_id=info.cust_district_2')
            ->leftJoin(BsDistrict::tableName() . ' district2', 'district2.district_id=district1.district_pid')
            ->leftJoin(BsDistrict::tableName() . ' district3', 'district3.district_id=district2.district_pid')
            ->leftJoin(BsDistrict::tableName() . ' district4', 'district4.district_id=district3.district_pid')
            ->where(['cust_sname' => $data])
            ->andWhere(['or', ['!=', 'status.member_status', CrmCustomerStatus::STATUS_DEFAULT], ['is', 'status.member_status', null]])
            ->one();
        return $query;
    }

    /**
     * @param $id
     * @param $attr
     * @param $val
     * @return string
     *验证客户名称唯一性
     */
    public function actionValidateMember($id, $attr, $val)
    {
        $class = $this->modelClass;//默认使用moduleClass作为验证类
//        if($id){
        $model = $class::findOne($id);
//            $model->$attr=urldecode($val);
//            $model->validate($attr);
//            return $model->getFirstError($attr)?$model->getFirstError($attr):"";
        if ($val === $model['cust_sname']) {
            return '';
        } else {
            $model = $class::find()->joinWith('status')->where(['and', ['cust_sname' => $val], ['member_status' => CrmCustomerStatus::STATUS_DEFAULT]])->one();
            if (!empty($model)) {
                return '客户已存在';
            } else {
                return '';
            }
        }
//        }else{
//            $model = $class::find()->joinWith('status')->where(['and',['cust_sname'=>$val],['member_status'=>CrmCustomerStatus::STATUS_DEFAULT]])->one();
//            if(!empty($model)){
//                return '客户已存在';
//            }else{
//                return '';
//            }
//        }

        //返回字段的验证结果
    }

    /**
     * @param $id
     * @param $attr
     * @param $val
     * @return string
     *验证客户用户名唯一性
     */
    public function actionValidateName($id, $val)
    {
        $class = $this->modelClass;//默认使用moduleClass作为验证类
        $model = $class::findOne($id);
        if ($val === $model['member_name']) {
            return '';
        } else {
            $model = $class::find()->joinWith('status')->where(['and', ['member_name' => $val], ['member_status' => CrmCustomerStatus::STATUS_DEFAULT]])->one();
            if (!empty($model)) {
                return '用户名已存在';
            } else {
                return '';
            }
        }
    }
}
