<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/2/13
 * Time: 上午 09:29
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseActiveController;
use app\models\User;
use app\modules\ptdt\models\BsCategory;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\common\tools\CheckUsed;
use app\modules\crm\models\CrmActImessage;
use app\modules\crm\models\CrmActiveApply;
use app\modules\crm\models\CrmActiveCheckIn;
use app\modules\crm\models\CrmActiveName;
use app\modules\crm\models\CrmActiveType;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustomerPersion;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmImessage;
use app\modules\crm\models\CrmMchpdtype;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\crm\models\CrmVisitRecord;
use app\modules\crm\models\CrmVisitRecordChild;
use app\modules\crm\models\search\CrmActiveApplySearch;
use app\modules\crm\models\search\CrmCustomerInfoSearch;
use app\modules\crm\models\show\CrmActImessageShow;
use app\modules\crm\models\show\CrmActiveApplyShow;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\crm\models\show\CrmCustomerPersionShow;
use app\modules\crm\models\show\CrmCustPersoninchShow;
use app\modules\crm\models\show\CrmEmployeeShow;
use app\modules\crm\models\show\CrmImessageShow;
use app\modules\crm\models\show\CrmVisitRecordChildShow;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use app\modules\hr\models\show\HrStaffShow;
use yii\base\Exception;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\Html;
use Yii;

class CrmPotentialCustomerController extends BaseActiveController{
    public $modelClass='app\modules\crm\models\CrmCustomerInfo';
    //潜在客户列表数据
    public function actionIndex(){
        $model=new CrmCustomerInfoSearch();
        $dataProvider=$model->searchPotentialCustomer(\Yii::$app->request->queryParams);
        $list["rows"]=$dataProvider->getModels();
        $list["total"]=$dataProvider->totalCount;
        return $list;
    }
    //创建潜在客户
    public function actionCreate(){
        $post=\Yii::$app->request->post();
        $trans=\Yii::$app->db->beginTransaction();
        try{
            $model=new CrmCustomerInfo();
            $model->load(["CrmCustomerInfo"=>$post]);
            if(!($model->validate() && $model->save())){
//                throw new \Exception(json::encode($model->getErrors()));
                throw new \Exception("新增潜在客户失败");
            }
            $status=new CrmCustomerStatus();
            $status->customer_id=$model->primaryKey;
            $status->potential_status=10;
            if(!($status->validate() && $status->save())){
//                throw new \Exception(json::encode($status->getErrors()));
                throw new \Exception("新增潜在客户失败");
            }
            $trans->commit();
            return $this->success(Json::encode([$model->primaryKey,"新增潜在客户成功"]));
        }catch(\Exception $e){
            $trans->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //修改潜在客户
    public function actionEdit($id){
        $post=\Yii::$app->request->post();
        $model=CrmCustomerInfo::findOne($id);
        $model->load(["CrmCustomerInfo"=>$post]);
        if($model->validate() && $model->save()){
            return $this->success();
        }else{
            return $this->error();
        }
    }


    //提醒
    public function actionRemind(){
        $model=new CrmImessage();
        $post=\Yii::$app->request->post();
        $model->load($post);
        if($model->validate() && $model->save()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    //查询指定部门下的人
    public function actionAllotmanSelect($dep_type){
        if($dep_type=="0"){
            $res=CrmEmployeeShow::find()->all();
        }else if($dep_type=="1"){
            $res=CrmMchpdtype::find()->all();
        }
        return $res;
    }
    //分配
    public function actionAllot(){
        $post=\Yii::$app->request->post();
        $trans=\Yii::$app->db->beginTransaction();
        try{
            $customers=empty($post['customers'])?"":explode(",",$post['customers']);
            if($customers){
                foreach($customers as $customer){
                    $model=CrmCustPersoninch::findOne(["cust_id"=>$customer]);
                    if(!$model){
                        $model=new CrmCustPersoninch();
                    }
                    $model->load($post);
                    $model->cust_id=$customer;
                    $model->ccpich_stype='0';
                    $model->ccpich_status='10';
                    $model->ccpich_date=date("Y-m-d");
                    if(!($model->validate() && $model->save())){
                        throw new \Exception("");
                    }

                    $custModel = CrmCustomerInfo::findOne($customer);
                    // 字符实体转换防止客户信息为脚本等特殊符号时保存失败
                    foreach ($custModel as $k => $v) {
                        $custModel[$k] = html_entity_decode($v);
                    }
                    $custModel->personinch_status=10;
                    if(!($custModel->save())){
                        throw new \Exception("");
                    }
                }
            }
            $trans->commit();
            return $this->success();
        }catch(\Exception $e){
            $trans->rollBack();
            return $this->error();
        }
    }


    public function actionUndoAllot($customers){
        $trans=\Yii::$app->db->beginTransaction();
        try{
            $customers=explode(",",$customers);
            if(!CrmCustPersoninch::updateAll(["ccpich_status"=>0],["ccpich_stype"=>"0","cust_id"=>$customers])){
                throw new \Exception("取消分配失败");
            }
//            if(!CrmCustomerInfo::updateAll(['personinch_status'=>'0'],["cust_id"=>$customers])){
//                throw new \Exception("取消分配失败");
//            }
            $trans->commit();
            return $this->success();
        }catch(\Exception $e){
            $trans->rollBack();
            return $this->error();
        }
    }

    //客户认领
//    public function actionClaim(){
//        $post=\Yii::$app->request->post();
//        $trans=\Yii::$app->db->beginTransaction();
//        try{
//            $customers=isset($post['customers'])?explode(",",$post['customers']):"";
//            if($customers){
//                $allotman=$post['CrmCustPersoninch']['ccpich_personid'];
//                CrmCustomerInfo::updateAll(["personinch_status"=>0,"cust_sales"=>""],["cust_id"=>$customers]);
//                CrmCustPersoninch::updateAll(["ccpich_status"=>0],["cust_id"=>$customers]);
//                foreach($customers as $customer){
//                    $model=new CrmCustPersoninch();
//                    $model->load($post);
//                    $model->cust_id=$customer;
//                    $model->ccpich_status='10';
//                    $model->ccpich_date=date("Y-m-d");
//                    if(!($model->validate() && $model->save())){
//                        throw new \Exception("操作失败");
//                    }
//
//                    $custModel = CrmCustomerInfo::findOne($customer);
//                    $custModel->personinch_status=10;
//                    $custModel->cust_sales=$model->ccpich_personid;
//                    if(!$custModel->save()){
//                        throw new \Exception("操作失败");
//                    }
//                }
//            }
//            $trans->commit();
//            return $this->success();
//        }catch(\Exception $e){
//            $trans->rollBack();
//            return $this->error($e->getMessage());
//        }
//    }

    //取消认领
//    public function actionUndoClaim($customers){
//        $trans=\Yii::$app->db->beginTransaction();
//        try{
//            if(!CrmCustPersoninch::updateAll(["ccpich_status"=>0],["cust_id"=>$customers])){
//                throw new \Exception("取消认领失败");
//            }
//            if(!CrmCustomerInfo::updateAll(['personinch_status'=>0,'cust_sales'=>''],["cust_id"=>$customers])){
//                throw new \Exception("取消认领失败");
//            }
//            $trans->commit();
//            return $this->success();
//        }catch(\Exception $e){
//            $trans->rollBack();
//            return $this->error($e->getMessage());
//        }
//    }

    //认领部门下拉列表数据
    public function actionClaimDropdownList($staff_id,$org_code="",$is_supper=""){
        if($org_code){
            $org=HrOrganization::findOne(["organization_code"=>$org_code]);
            $orgs=HrOrganization::getOrgChild($org->organization_id);
            return HrStaff::find()->select("staff_id,staff_code,staff_name,organization_code")->where(["in","organization_code",array_column($orgs,"organization_code")])->all();
        }else{
            $staff=HrStaff::findOne($staff_id);
            if($is_supper){
                $org=HrOrganization::findOne(["organization_pid"=>0]);
            }else{
                $org=HrOrganization::findOne(["organization_code"=>$staff->organization_code]);
            }
            $data=HrOrganization::getOrgChild($org->organization_id);
//            foreach($data as &$item){
//                $item["organization_name"]=str_repeat("--",$item["level"]*2).$item["organization_name"];
//            }
            return $data;
        }
    }

    public function actionGetUserOrg($staff_id){
        $staff=HrStaff::findOne($staff_id);
        return $staff->organization_code;
    }

    public function actionGetUserOrgStaff($staff_id){
        $staff=HrStaff::findOne($staff_id);
        $org=HrOrganization::findOne(["organization_code"=>$staff->organization_code]);
        $res[]=$org->organization_code;
        $orgs=HrOrganization::getOrgChild($org->organization_id,$res);
        return HrStaff::find()->where(["in","organization_code",$orgs])->asArray()->all();
    }


    //认领人信息
//    public function actionClaimInfo($cust_id){
//        return CrmCustomerInfo::find()
//            ->select([
//                HrStaff::tableName().".staff_id",
//                HrStaff::tableName().".staff_name",
//                HrStaff::tableName().".staff_code",
//                HrStaff::tableName().".organization_code"
//            ])
//            ->leftJoin(CrmCustPersoninch::tableName(),CrmCustPersoninch::tableName().".cust_id=".CrmCustomerInfo::tableName().".cust_id")
//            ->leftJoin(HrStaff::tableName(),HrStaff::tableName().".staff_id=".CrmCustPersoninch::tableName().".ccpich_personid")
//            ->where([
//                CrmCustomerInfo::tableName().".personinch_status"=>10,
//                CrmCustPersoninch::tableName().".ccpich_status"=>10,
//                CrmCustomerInfo::tableName().".cust_id"=>$cust_id
//            ])->asArray()->one();
//    }

    //转销售,转招商
    public function actionSwitchStatus($type,$customers){
        $trans=\Yii::$app->db->beginTransaction();
        try{
            $customersArr=explode(",",$customers);
            foreach($customersArr as $customer){
                $model=CrmCustomerStatus::findOne($customer);
                if(!$model){
                    $model=new CrmCustomerStatus();
                    $model->customer_id=$customer;
                }
                if($type == 'sale_status'){
                    $inch = CrmCustPersoninch::find()->where(['and',['cust_id'=>$customer],['ccpich_stype'=>CrmCustPersoninch::PERSONINCH_DEFAULT]])->one();
                    if(!empty($inch)){
                        $inch->delete();
                    }
                }
                if ($type == 'investment_status') {
                    $sih_id = CrmVisitRecord::find()->select('sih_id')->where(['and',['cust_id'=>$customer], ['!=','sih_status',CrmVisitRecord::STATUS_DELETE]])->one();
                    CrmVisitRecordChild::updateAll(['type'=>'60'],['sih_id'=>$sih_id['sih_id']]);
                }
                $model->sale_status=0;
                $model->investment_status=0;
                $model->potential_status=0;
                $model->$type=10;
                if(!($model->validate() && $model->save())){
                    throw new \Exception("操作失败");
                }
            }
            $trans->commit();
            return $this->success();
        }catch(\Exception $e){
            $trans->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //活动信息
    public function actionActInfo(){
        $params=\Yii::$app->request->queryParams;
        $query=CrmActiveApply::find()
            ->select([
                CrmActiveApply::tableName().".acth_id",
                CrmActiveApply::tableName().".acth_code",
                CrmActiveName::tableName().".actbs_name",
                "date_format(crm_act_h.acth_date,'%Y-%m-%d') acth_date",
                CrmActiveType::tableName().".acttype_name",
                CrmActiveApply::tableName().".acth_name",
                CrmActiveApply::tableName().".acth_phone",
                CrmActiveApply::tableName().".acth_email",
                CrmActiveApply::tableName().".acth_ischeckin",
                CrmActiveApply::tableName().".acth_remark"
            ])
            ->leftJoin(CrmActiveName::tableName(),CrmActiveName::tableName().".actbs_id=".CrmActiveApply::tableName().".actbs_id")
            ->leftJoin(CrmActiveType::tableName(),CrmActiveType::tableName().".acttype_id=".CrmActiveApply::tableName().".acttype_id")
            ->where([
                CrmActiveApply::tableName().".cust_id"=>$params["id"],
                CrmActiveApply::tableName().".acth_status"=>10
            ])
            ->orderBy(CrmActiveApply::tableName().".acth_id desc")
            ->asArray();
        $dataProvider=new ActiveDataProvider([
            "query"=>$query,
            "pagination"=>[
                "pageSize"=>isset($params["size"])?$params["size"]:5
            ]
        ]);
        ;
        return [
            "total"=>$dataProvider->totalCount,
            "rows"=>$dataProvider->getModels()
        ];
    }

    //提醒事项
    public function actionRemindItem(){
        $params=\Yii::$app->request->queryParams;
        $query=CrmImessageShow::find()->where(['cust_id'=>$params["id"]])->andWhere(['!=','imesg_status',CrmImessage::STATUS_DEL]);
        $dataProvider=new ActiveDataProvider([
            "query"=>$query,
            "pagination"=>[
                "pageSize"=>5
            ]
        ]);
        $res["total"]=$dataProvider->totalCount;
        $res["rows"]=$dataProvider->getModels();
        return $res;
    }

    public function actionRemindDo($id,$act){
        switch($act){
            case 1:
                if(CrmImessage::updateAll(["imesg_status"=>2],["imesg_id"=>$id])){
                    return $this->success();
                }
                return $this->error();
                break;
            case 2:
                if(CrmImessage::deleteAll(["imesg_id"=>$id])){
                    return $this->success();
                }
                return $this->error();
                break;
        }
    }

    //消息日志
    public function actionMessageLog($id,$page=1){
        $query=CrmActImessageShow::find()
            ->select([
                "*", "date_format(imesg_sentdate,'%Y-%m-%d') imesg_sentdate"
            ])
            ->where(['cust_id'=>$id])->orderBy(["imesg_sentdate"=>SORT_DESC]);
        $dataProvider=new ActiveDataProvider([
            "query"=>$query,
            "pagination"=>[
                "pageSize"=>5,
                "page"=>$page-1
            ]
        ]);
        $res["total"]=$dataProvider->totalCount;
        $res["rows"]=$dataProvider->getModels();
        return $res;
    }

    //拜访记录
    public function actionVisitLog($id,$page){
        $query=CrmVisitRecordChildShow::find()
            ->select([
                "crm_visit_info_child.*",
                "crm_visit_info_child.start",
                "crm_visit_info_child.end",
            ])
            ->joinWith("visitInfo")
            ->where([
                'and',
                [CrmVisitRecord::tableName().'.cust_id'=>$id],
                ['!=',CrmVisitRecordChildShow::tableName().'.sil_status',CrmVisitRecordChild::STATUS_DELETE]
            ])
            ->orderBy(['create_at'=>SORT_DESC]);
        $dataProvider=new ActiveDataProvider([
            "query"=>$query,
            "pagination"=>[
                "pageSize"=>5,
                "page"=>$page-1
            ]
        ]);
        $res["total"]=$dataProvider->totalCount;
        $res["rows"]=$dataProvider->getModels();
        return $res;
    }

    //删除潜在客户
    public function actionRemove($id){
        $checker=new CheckUsed();
        $data=$checker->check($id,"cust_id");
        if($data["status"]==0){
            return $this->error($data['msg']);
        }
        $model=CrmCustomerStatus::findOne($id);
        $model->potential_status=0;
        $customer=CrmCustomerInfo::findOne($id);
        if($model->save()){
            return $this->success($customer->cust_sname);
        }
        return $this->error($customer->cust_sname);
    }


    //拜访记录信息
    public function actionVisitRecordInfo($id){
        $recordChildData = CrmVisitRecordChild::findOne($id);
        if (!empty($recordChildData)) {
            $recordMainData = $recordChildData->visitInfo;
            if (!empty($recordMainData)) {
                $customerData = CrmCustomerInfoShow::find()->where(['cust_id'=>$recordMainData->cust_id])->one();
                if (!empty($customerData)) {
                    return [
                        'customerData' => $customerData,
                        'recordChildData' => $recordChildData,
                    ];
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }


    public function actionCustVisitRecords($id){
        $custInfo=CrmCustomerInfoShow::findOne($id);
        $sihIds=CrmVisitRecord::find()
            ->select("sih_id")
            ->where(["cust_id"=>$id])
            ->andWhere(["!=","sih_status","0"])
            ->column();
            $records=CrmVisitRecordChildShow::find()
                ->where(["in","sih_id",$sihIds])
                ->andWhere(["!=","sil_status","0"])
                ->limit(3)
                ->orderBy("create_at desc")->all();
            $result["custInfo"]=$custInfo;
            $result["records"]=$records;
        return $result;
    }

    //拜访人
    public function actionVisitPerson($id)
    {
        return HrStaff::find()->where(['staff_id'=>$id])->select('staff_id,staff_name,staff_code')->one();
    }


    //潜在客户信息
    public function actionModels($id){
        return CrmCustomerInfoShow::findOne($id);
    }


    //导入客户
    public function actionImport($companyId,$createBy){
        $post = \Yii::$app->request->post();
        static $succ = 0;
        static $err = 0;
        $log=[];
        foreach ($post as $k => $v) {
            if ($k >= 0) {
                $trans=Yii::$app->db->beginTransaction();
                try{
                    $model=new CrmCustomerInfo();
                        $model->cust_shortname = isset($v['B'])?$v['B']:"";
                        $model->cust_sname = isset($v["C"])?$v["C"]:null;
                        $model->cust_contacts = isset($v["D"])?$v["D"]:null;


                        $model->cust_department=isset($v["E"])?$v["E"]:"";
                        $model->cust_position=isset($v["F"])?$v["F"]:"";
                        if(isset($v["G"])){
                            $func=BsPubdata::findOne(["bsp_stype"=>"khzwzn","bsp_svalue"=>$v["G"]]);
                            $model->cust_function=isset($func->bsp_id)?$func->bsp_id:null;
                        }


                        $model->cust_tel2 = isset($v["H"])?$v["H"]:null;
                        $model->cust_email = isset($v["I"])?$v["I"]:null;
                        if(isset($v["K"])){
                            $district=BsDistrict::findOne(["district_name"=>$v["K"]]);
                            $model->cust_district_2=isset($district->district_id)?$district->district_id:null;
                        }

                        $model->cust_adress=isset($v["L"])?$v["L"]:null;


                        $b = isset($v["M"])?$v["M"]:null;
                        $type = BsPubdata::getExcelData($b);
                        $model->cust_businesstype = isset($type["bsp_id"])?$type["bsp_id"]:null;
//                        $model->member_businessarea =isset($v["M"])?$v["M"]:null;
                        $b = isset($v["N"])?$v["N"]:null;
                        $type = BsPubdata::getExcelData($b);
                        $model->member_source = isset($type['bsp_id'])?$type['bsp_id']:null;
                        $b = isset($v["O"])?$v["O"]:null;
                        $type= BsPubdata::getExcelData($b);
                        $model->member_reqflag = isset($type['bsp_id'])?$type['bsp_id']:null;
                        if(isset($v["P"])){
                            $cat=BsCategory::findOne(["catg_name"=>$v["P"]]); // erp数据库的类别表弃用！！
                            $model->member_reqitemclass=isset($cat->catg_no)?$cat->catg_no:"";
                        }
                        if(isset($v["Q"])){
                            $creator=HrStaff::findOne(["staff_name"=>$v["Q"]]);
                            $model->create_by =isset($creator->staff_id)?$creator->staff_id:$createBy;
                        }
                        $allotman="";
                        if(isset($v["R"])){
			                $allotor=HrStaff::findOne(["staff_name"=>$v["R"]]);
                            $allotman=isset($allotor->staff_id)?$allotor->staff_id:"";
                            if($allotman){
                                $model->personinch_status='10';
                            }
			            }
                        // 插入數據
                        $model->company_id = $companyId;
                        $model->create_at=date("Y-m-d H:i:s");
                        if(!($model->validate() && $model->save())){
                            throw new \Exception(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE)."-".$model->cust_sname);
                        }
                        if($allotman){
                            $custId = $model->primaryKey;
                            $personinchModel=new CrmCustPersoninch();
                            $personinchModel->cust_id=$custId;
                            $personinchModel->ccpich_personid="$allotman";
                            $personinchModel->ccpich_status='10';
                            if(!$personinchModel->save()){
                                throw new \Exception("分配人保存失败");
                            }
                        }

                    CrmCustomerStatus::deleteAll(["customer_id"=>$model->primaryKey]);
                    $status = new CrmCustomerStatus();
                    $status->customer_id = $model->primaryKey;
                    $status->potential_status =10;
                    if (!$status->save()) {
                        throw new \Exception("客户状态保存失败");
                    }
                    $succ++;
                    $trans->commit();
                }catch (\Exception $e){
                    $log[]=[
                        'file'=>basename(get_class()).":".$e->getLine(),
                        'message'=>$e->getMessage()
                    ];
                    $err++;
                    $trans->rollBack();
                }
            }
        }
        return ["succ"=>$succ,"error"=>$err,"log"=>$log];
    }


    //下拉列表数据
    public function actionDownList($id=""){
        $params=\Yii::$app->request->queryParams;
        $data=BsDistrict::find()->where(["district_level"=>2])->asArray()->all();
        $list["province"]=array_combine(array_column($data,"district_id"),array_column($data,"district_name"));
        $data=BsDistrict::find()->where(["district_level"=>3,"district_pid"=>isset($params["province"])?$params["province"]:""])->asArray()->all();
        $list["city"]=array_combine(array_column($data,"district_id"),array_column($data,"district_name"));
        $list["customer_source"]=BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>BsPubdata::CRM_CUSTOMER_SOURCE])->indexBy("bsp_id")->column();//客户来源
        $list["member_reqflag"]=BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>BsPubdata::CRM_LATENT_DEMAND])->indexBy("bsp_id")->column();//潜在需求
        $list["cust_businesstype"]=BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>BsPubdata::CRM_MANAGEMENT_TYPE])->indexBy("bsp_id")->column();//经营模式
        $list["member_businessarea"]=BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>BsPubdata::CRM_MANAGEMENT_TYPE])->indexBy("bsp_id")->column();//经营范围
        $list["cust_regweb"]=BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>BsPubdata::CRM_REGISTER_WEB])->indexBy("bsp_id")->column();//注册网站
        $list["currency"]=BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>BsPubdata::SUPPLIER_TRADE_CURRENCY])->indexBy("bsp_id")->column();;//交易币种
        $list["member_type"]=BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>BsPubdata::CRM_CUSTOMER_CLASS])->indexBy("bsp_id")->column();;//会员类别
        $list["company_type"]=BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>BsPubdata::CRM_COMPANY_PROPERTY])->indexBy("bsp_id")->column();//公司类型
        $list['productType'] = BsCategory::find()->select(['catg_name'])->indexBy('catg_id')->Where(['catg_level' => 1, 'isvalid' => 1])->orderBy('orderby')->column();//需求类目
        $list['salearea'] = CrmSalearea::getSalearea();//所在军区
        $list['storeinfo'] =CrmStoresinfo::getStoreInfo();//销售点
        $list['manager'] =CrmEmployee::getManagerInfo();//客户经理人
        $list['allsales'] = CrmEmployee::getAllSales();//所有销售人员
        $list['visit_type'] = BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>BsPubdata::CRM_VISIT_TYPE])->indexBy("bsp_id")->column();//拜访类型
        $list['cust_function']=BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>"khzwzn"])->indexBy("bsp_id")->column();
        $list["member_compreq"]=BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>"fpxq"])->indexBy("bsp_id")->column();;
        $list["allotman"]=CrmCustPersoninch::find()
            ->select("staff_name")
            ->leftJoin(CrmCustomerStatus::tableName(),CrmCustomerStatus::tableName().".customer_id=".CrmCustPersoninch::tableName().".cust_id")
            ->leftJoin(HrStaff::tableName(),HrStaff::tableName().".staff_id=".CrmCustPersoninch::tableName().".ccpich_personid")
            ->where([
                CrmCustomerStatus::tableName().".potential_status"=>10,
                CrmCustPersoninch::tableName().".ccpich_stype"=>0,
                CrmCustPersoninch::tableName().".ccpich_status"=>10
            ])
            ->groupBy(HrStaff::tableName().".staff_id")
            ->indexBy("staff_id")
            ->asArray()
            ->column();
        return $list;
    }


    //地区多级数据
    function actionDistrictLevel($id){
        $tree=[];
        $path=[];
        $addr_info=BsDistrict::findOne($id);
        if($addr_info->district_level<4){
            $path[]=$addr_info->district_id;
            $tree[]=BsDistrict::findAll(["district_pid"=>$addr_info->district_id]);
        }
        while($id>0){
            $addr_info=BsDistrict::findOne($id);
            $path[]=$addr_info->district_id;
            $id=$addr_info->district_pid;
            $parent=BsDistrict::findAll(["district_pid"=>$addr_info->district_pid]);
            $tree[]=$parent;
        }
        $tree=array_reverse($tree);
        $path=array_reverse($path);
        return [
            'oneLevel'=>isset($tree[0])?$tree[0]:"",
            'oneLevelId'=>isset($path[0])?$path[0]:"",
            'twoLevel'=>isset($tree[1])?$tree[1]:"",
            'twoLevelId'=>isset($path[1])?$path[1]:"",
            'threeLevel'=>isset($tree[2])?$tree[2]:"",
            'threeLevelId'=>isset($path[2])?$path[2]:"",
            'fourLevel'=>isset($tree[3])?$tree[3]:"",
            'fourLevelId'=>isset($path[3])?$path[3]:""
        ];
    }
    //地区路径
    function actionDistrictNamePath($id){
        $addr=[];
        while($id>0){
            $addr_info=BsDistrict::findOne($id);
            $id=$addr_info->district_pid;
            $addr[]=$addr_info->district_name;
        }
        $addr=array_reverse($addr);
        return $addr;
    }
}
