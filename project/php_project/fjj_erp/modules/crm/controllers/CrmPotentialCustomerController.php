<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/2/13
 * Time: 上午 09:29
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseController;
use app\models\UploadForm;
use app\modules\common\tools\SendMail;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\system\models\SystemLog;
use yii\bootstrap\ActiveForm;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\modules\common\tools\ExcelToArr;
use Yii;
//潜在客户控制器
class CrmPotentialCustomerController extends BaseController{
    public $_url="crm/crm-potential-customer/";
    //潜在客户列表
    public function beforeAction($action)
    {
        $this->ignorelist=array_merge($this->ignorelist,[
            "/crm/crm-potential-customer/import",
            "/crm/crm-potential-customer/get-progress"
        ]);
        return parent::beforeAction($action);
    }

    public function actionExport(){
//        $params=\Yii::$app->request->queryParams;
//        $url = $this->findApiUrl() . $this->_url . "index?companyId=" . \Yii::$app->user->identity->company_id."&managerId=".\Yii::$app->user->identity->staff_id;
//        $url.="&".http_build_query($params);
//        $dataProvider=$this->findCurl()->get($url);
//        $data=Json::decode($dataProvider);
//        foreach ($data["rows"] as &$item){
//            $item["cust_filernumber_link"]=$item["cust_filernumber"];
//            $district=explode(",",$item["district"]);
//            $item["province"]=isset($district[0])?$district[0]:"";
//            $item["city"]=isset($district[1])?$district[1]:"";
//        }
//        \Yii::$app->controller->action->id = 'index';
//        return $this->exportFiled($data["rows"]);
        $params=\Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "index?companyId=" . \Yii::$app->user->identity->company_id."&managerId=".\Yii::$app->user->identity->staff_id.'&export=1';
        $url.="&".($params['queryParams']);
        $dataProvider=$this->findCurl()->get($url);
        $data=Json::decode($dataProvider);
        foreach ($data["rows"] as &$item){
            $item["cust_filernumber_link"]=$item["cust_filernumber"];
            $district=explode(",",$item["district"]);
            $item["province"]=isset($district[0])?$district[0]:"";
            $item["city"]=isset($district[1])?$district[1]:"";
        }
        \Yii::$app->controller->action->id = 'index';
        $this->exportFiled($data["rows"]);
    }

    public function actionIndex(){
        $params=\Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "index?companyId=" . \Yii::$app->user->identity->company_id."&managerId=".\Yii::$app->user->identity->staff_id;
        $url.="&".http_build_query($params);
        if(\Yii::$app->request->isAjax){
            $dataProvider=$this->findCurl()->get($url);
            $data=Json::decode($dataProvider);
            foreach ($data["rows"] as &$item){
                $item["cust_filernumber_link"]=Html::a($item["cust_filernumber"],['view','id'=>$item['cust_id']]);
                $district=explode(",",$item["district"]);
                $item["province"]=isset($district[0])?$district[0]:"";
                $item["city"]=isset($district[1])?$district[1]:"";
            }
            return Json::encode($data);
        }
        $export = \Yii::$app->request->get('export');
        if (isset($export)) {
            $dataProvider=$this->findCurl()->get($url);
            $data=Json::decode($dataProvider);
            foreach ($data["rows"] as &$item){
                $item["cust_filernumber_link"]=$item["cust_filernumber"];
                $district=explode(",",$item["district"]);
                $item["province"]=isset($district[0])?$district[0]:"";
                $item["city"]=isset($district[1])?$district[1]:"";
            }
            $this->exportFiled($data["rows"]);
        }
        $columns=[
            "index"=>$this->getField(),
            "visit-log"=>$this->getField("/crm/crm-potential-customer/visit-log"),
            "act-info"=>$this->getField("/crm/crm-potential-customer/act-info"),
            "remind-item"=>$this->getField("/crm/crm-potential-customer/remind-item"),
            "message-log"=>$this->getField("/crm/crm-potential-customer/message-log")
        ];
        return $this->render("index",["columns"=>$columns,"downList"=>$this->getDownList($params)]);
    }

    //创建潜在客户
    public function actionCreate(){
        if(\Yii::$app->request->isPost){
            $url=$this->findApiUrl().$this->_url."create";
            $params=\Yii::$app->request->post();
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $res=Json::decode($curl->post($url));
//            dumpE($res);
            if($res['status']==1){
                SystemLog::addLog("客户".$params["cust_sname"]."创建成功");
                $info=Json::decode($res["msg"]);
                return Json::encode(['msg'=>$info[1],'flag'=>1,'url'=>Url::to(['view','id'=>$info[0]])]);
            }else{
                SystemLog::addLog("客户".$params["cust_sname"]."创建失败");
                return Json::encode(['msg'=>$res["msg"],'flag'=>0]);
            }
        }else{
            return $this->render("create",['downList'=>$this->getDownList()]);
        }
    }

    //修改潜在客户
    public function actionEdit($id){
        if(\Yii::$app->request->isPost){
            $url=$this->findApiUrl().$this->_url."edit?id=".$id;
            $params=\Yii::$app->request->post();
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $res=Json::decode($curl->post($url));
            if($res['status']==1){
                SystemLog::addLog("客户".$params['cust_sname']."修改成功");
                return Json::encode(["msg"=>"修改成功","flag"=>1,'url'=>Url::to(['view','id'=>$id])]);
            }else{
                SystemLog::addLog("客户".$params['cust_sname']."修改失败");
                return Json::encode(["msg"=>"修改失败","flag"=>0]);
            }
        }else{
            $url=$this->findApiUrl().$this->_url."models?id=".$id;
            $model=Json::decode($this->findCurl()->get($url),true);
            $url=$this->findApiUrl().$this->_url."district-level?id=".$model['cust_district_2'];
            $district=Json::decode($this->findCurl()->get($url));
            return $this->render("edit",["model"=>$model,"district"=>$district,"downList"=>$this->getDownList()]);
        }
    }

    //潜在客户详情
    public function actionView($id){
        $url=$this->findApiUrl().$this->_url."models?id=".$id;
        $model=Json::decode($this->findCurl()->get($url),true);
        if(!$model){
            throw new NotFoundHttpException("纪录不存在");
        }

        if(\Yii::$app->request->get("preview")==1){

        }

        return $this->render("view",["model"=>$model,"downList"=>$this->getDownList()]);
    }

    public function actionDelete($id){
        $url=$this->findApiUrl().$this->_url."remove?id=".$id;
        $res=Json::decode($this->findCurl()->get($url));
        if($res['status']==1){
            SystemLog::addLog("客户".$res['msg']."删除成功");
            return Json::encode(["msg"=>"删除客户成功",'flag'=>1]);
        }else{
            SystemLog::addLog($res["msg"]);
            return Json::encode(["msg"=>$res["msg"],'flag'=>0]);
        }
    }

//    新增提醒
//    public function actionRemind(){
//        if(\Yii::$app->request->isPost){
//            $url=$this->findApiUrl().$this->_url."remind";
//            $params=\Yii::$app->request->post();
//            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
//            $res=Json::decode($curl->post($url));
//            if($res['status']==1){
//                SystemLog::addLog("新增提醒成功");
//                return Json::encode(["msg"=>"提醒成功","flag"=>1]);
//            }else{
//                SystemLog::addLog("新增提醒失败");
//                return Json::encode(["msg"=>"提醒失败","flag"=>0]);
//            }
//        }else{
//            $employee = $this->getEmployee();
//            $this->layout="@app/views/layouts/ajax.php";
//            return $this->render("remind",['employee'=>$employee]);
//        }
//    }

    private function getEmployee()
    {
        $url = $this->findApiUrl() . "/crm/crm-return-visit/employee";
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }
    //分配人部门下拉列表
    public function actionAllotmanSelect($org_code){
        $url=$url=$this->findApiUrl().$this->_url."claim-dropdown-list?staff_id=".\Yii::$app->user->identity->staff_id."&org_code=".$org_code;
        return $this->findCurl()->get($url);
    }
    //客户分配
    public function actionAllot(){
        if(\Yii::$app->request->isPost){
            $params=\Yii::$app->request->post();
            $url=$this->findApiUrl().$this->_url."allot";
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $res=Json::decode($curl->post($url));
            if($res['status']==1){
                SystemLog::addLog("客户分配成功");
                return Json::encode(["msg"=>"客户分配成功","flag"=>1]);
            }else{
                SystemLog::addLog("客户分配失败");
                return Json::encode(["msg"=>"客户分配失败","flag"=>0]);
            }
        }else{
            $url=$this->findApiUrl().$this->_url."get-user-org?staff_id=".\Yii::$app->user->identity->staff_id;
            $orgCode=Json::decode($this->findCurl()->get($url));
            $url=$this->findApiUrl().$this->_url."get-user-org-staff?staff_id=".\Yii::$app->user->identity->staff_id;
            $orgStaff=Json::decode($this->findCurl()->get($url));
            $url=$this->findApiUrl().$this->_url."claim-dropdown-list?staff_id=".\Yii::$app->user->identity->staff_id;
            \Yii::$app->user->identity->is_supper?$url.="&is_supper=1":"";
            $res=Json::decode($this->findCurl()->get($url));
            return $this->renderAjax("allot",["department"=>$res,"orgCode"=>$orgCode,"orgStaff"=>$orgStaff,"downList"=>$this->getDownList()]);
        }
    }

    //客户分配
    public function actionUndoAllot($customers){
            $params=\Yii::$app->request->post();
            $url=$this->findApiUrl().$this->_url."undo-allot?uid=".\Yii::$app->user->id."&customers=".$customers;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $res=Json::decode($curl->post($url));
            if($res['status']==1){
                SystemLog::addLog("取消分配成功");
                return Json::encode(["msg"=>"取消分配成功","flag"=>1]);
            }else{
                SystemLog::addLog("取消分配失败");
                return Json::encode(["msg"=>"取消分配失败","flag"=>0]);
            }
    }

    //客户认领
    public function actionClaim($cust_id=""){
        if(\Yii::$app->request->isPost){
            $params=\Yii::$app->request->post();
            $url=$this->findApiUrl().$this->_url."claim";
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $res=Json::decode($curl->post($url));
            if($res['status']==1){
                SystemLog::addLog("客户认领成功");
                return Json::encode(["msg"=>"客户认领成功","flag"=>1]);
            }else{
                SystemLog::addLog("客户认领失败");
                return Json::encode(["msg"=>"客户认领失败","flag"=>0]);
            }
        }else{
            $url=$url=$this->findApiUrl().$this->_url."claim-info?cust_id=".$cust_id;
            $claim_info=Json::decode($this->findCurl()->get($url));
            $url=$url=$this->findApiUrl().$this->_url."claim-dropdown-list";
            $dep_info=Json::decode($this->findCurl()->get($url));
            return $this->renderAjax("claim",["claim_info"=>$claim_info,"department"=>$dep_info]);
        }
    }

    //取消认领
    public function actionUndoClaim($customers){
        $url=$this->findApiUrl().$this->_url."undo-claim?customers=".$customers;
        $res=Json::decode($this->findCurl()->get($url));
        if($res['status']==1){
            SystemLog::addLog("客户取消认领成功");
            return Json::encode(["msg"=>"取消认领成功","flag"=>1]);
        }else{
            SystemLog::addLog("客户取消认领失败");
            return Json::encode(["msg"=>"取消认领失败","flag"=>0]);
        }
    }

    //活动信息
    public function actionActInfo(){
        $params=\Yii::$app->request->queryParams;
        $params["companyId"]=\Yii::$app->user->identity->company_id;
        $url=$this->findApiUrl().$this->_url."act-info?".http_build_query($params);
        $data=Json::decode($this->findCurl()->get($url));
        foreach ($data["rows"] as &$item){
            $item["acth_code"]=Html::a($item["acth_code"],['crm-active-apply/view','id'=>$item['acth_id']]);
            $item["acth_ischeckin"]=$item["acth_ischeckin"]?"已签到":"未签到";
        }
        return Json::encode($data);
    }

    //提醒事项
    public function actionRemindItem(){
        $params=\Yii::$app->request->queryParams;
        $url = $this->findApiUrl() .$this->_url."remind-item?".http_build_query($params);
        $data=$this->findCurl()->get($url);
        return $data;
    }


//    public function actionRemindDo($id,$act){
//        $url=$this->findApiUrl().$this->_url."remind-do?id={$id}&act={$act}";
//        $res=Json::decode($this->findCurl()->get($url));
//        if($res['status']==1){
//            return Json::encode([
//                "msg"=>"操作成功",
//                "flag"=>1
//            ]);
//        }
//        return Json::decode([
//            "msg"=>"操作失败",
//            "flag"=>0
//        ]);
//
//    }

    //通讯记录
    public function actionMessageLog($id,$page){
        $url=$this->findApiUrl().$this->_url."message-log?id={$id}&page={$page}";
        $data=$this->findCurl()->get($url);
        return $data;
    }

    //转招商,转销售
    /*public function actionSwitchStatus($type,$customers){
        $url=$this->findApiUrl().$this->_url."switch-status?type={$type}&customers={$customers}";
        $res=Json::decode($this->findCurl()->get($url));
        $act="";
        switch($type){
            case "investment_status":
                $act="转招商开发";
                break;
            case "sale_status":
                $act="转销售";
                break;
            case "member_status":
                $act="转会员";
                break;
        }
        if($res['status']==1){
            SystemLog::addLog($act."成功");
            return Json::encode(["msg"=>$act."成功",'flag'=>1]);
        }else{
            SystemLog::addLog($act."失败");
            return Json::encode(["msg"=>$act."失败",'flag'=>0]);
        }
    }*/

    //转销售
    public function actionToSale($type,$customers){
        $url=$this->findApiUrl().$this->_url."switch-status?type={$type}&customers={$customers}";
        $res=Json::decode($this->findCurl()->get($url));
//        $act="";
//        switch($type){
//            case "investment_status":
//                $act="转招商开发";
//                break;
//            case "sale_status":
//                $act="转销售";
//                break;
//            case "member_status":
//                $act="转会员";
//                break;
//        }
        if($res['status']==1){
            SystemLog::addLog("转销售成功");
            return Json::encode(["msg"=>"转销售成功",'flag'=>1]);
        }else{
            SystemLog::addLog("转销售失败");
            return Json::encode(["msg"=>"转销售失败",'flag'=>0]);
        }
    }

    //转招商
    public function actionToInvestment($type,$customers){
        $url=$this->findApiUrl().$this->_url."switch-status?type={$type}&customers={$customers}";
        $res=Json::decode($this->findCurl()->get($url));
        /*$act="";
        switch($type){
            case "investment_status":
                $act="转招商开发";
                break;
            case "sale_status":
                $act="转销售";
                break;
            case "member_status":
                $act="转会员";
                break;
        }*/
        if($res['status']==1){
            SystemLog::addLog("转招商开发成功");
            return Json::encode(["msg"=>"转招商开发成功",'flag'=>1]);
        }else{
            SystemLog::addLog("转招商开发失败");
            return Json::encode(["msg"=>"转招商开发失败",'flag'=>0]);
        }
    }

    //潜在客户拜访记录列表
    public function actionVisitLog($id,$page){
        $url=$this->findApiUrl().$this->_url."visit-log?id={$id}&page={$page}";
        $data=Json::decode($this->findCurl()->get($url));
        foreach ($data["rows"] as &$item){
            $item["sil_code"]=Html::a($item["sil_code"],['view-visit','id'=>$id,"childId"=>$item["sil_id"],"ctype"=>4]);
        }
        return Json::encode($data);
    }

    //选择客户
    public function actionSelectCustomer(){
        return $this->renderAjax("select-customer");
    }

    //修改客户资料
    public function actionEditCustomer($id){
        $url=$this->findApiUrl().$this->_url."models?id=".$id;
        $model=Json::decode($this->findCurl()->get($url),true);
        $url=$this->findApiUrl().$this->_url."district-level?id=".$model['cust_district_2'];
        $district=Json::decode($this->findCurl()->get($url));
        return $this->renderAjax("edit-customer",[
            "id"=>$id,
            "district"=>$district,
            "model"=>$model,
            "downList"=>$this->getDownList()
        ]);
    }

//    //新增提醒
//    public function actionAddRemind($id){
//        $url=$this->findApiUrl().$this->_url."models?id=".$id;
//        $model=Json::decode($this->findCurl()->get($url),true);
//        $this->layout="@app/views/layouts/ajax.php";
//        return $this->render("add-remind",["model"=>$model]);
//    }

    //筛选项下拉列表数据
    public function getDownList($params=""){
        $url=$this->findApiUrl().$this->_url."down-list";
        $url.="?".http_build_query($params);
        $res=$this->findCurl()->get($url);
        return Json::decode($res,true);
    }





    /*------------------------------拜访记录start--------------------------------------*/
    /**
     * @param null $id
     * @param null $ctype
     * @return string
     * 新增拜访
     */
    public function actionVisitCreate($id = null, $ctype = null)
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['ctype'] = $ctype;
            $post['CrmVisitRecordChild']['title'] = '拜访' . $post['cust_name'];
            $post['CrmVisitRecordChild']['start'] = $post['arriveDate'];
            $post['CrmVisitRecordChild']['end'] = $post['leaveDate'];
//            $post['CrmVisitInfoChild']['color'] = '#F00';
            $post['CrmVisitRecord']['create_by'] = $post['CrmVisitRecordChild']['create_by'] = Yii::$app->user->identity->staff_id;
            $post['CrmVisitRecord']['company_id'] = $post['CrmVisitRecordChild']['company_id'] = Yii::$app->user->identity->company_id;
            $url = $this->findApiUrl() . "crm/crm-member-develop/visit-create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['message']);
                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" => Url::to(['view-visit', 'id' => $data['data']['id'], 'childId' => $data['data']['childId'], 'ctype' => $ctype])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        } else {
            $downList = $this->getList();
            $url = $this->findApiUrl() . '/crm/crm-visit-info/visit-person?id=' . Yii::$app->user->identity->staff_id;
            $visitPerson = json_decode($this->findCurl()->get($url));
            if (!empty($id)) {
                $member = $this->getCustomerInfo($id);
                $districtId = $member['cust_district_2'];
                $districtAll = $this->getAllDistrict($districtId);
                return $this->render('/crm-member-develop/create', [
                    'downList' => $downList,
                    'member' => $member,
                    'districtAll' => $districtAll,
                    'visitPerson' => $visitPerson,
                    'id' => $id,
                    'ctype' => $ctype
                ]);
            } else {
                return $this->render('/crm-member-develop/create', [
                    'downList' => $downList,
                    'visitPerson' => $visitPerson,
                    'ctype' => $ctype
                ]);
            }
        }
    }

    /**
     * @param $id
     * @param $childId
     * @return string
     * 查看拜访详情
     */
    public function actionViewVisit($id, $childId = null, $ctype = null)
    {
        $type = 0;
        $member = $this->getCustomerInfo($id);
        $record = $this->getCustInfo($id);
        if ($childId == null) {
            $child = $this->getAllVisitChild($member['sih_id']);
            $num = 0;
        } else {
            $allchild = $this->getAllVisitChild($member['sih_id']);
            $child = array($this->getVisitChild($childId));
//            dumpE($allchild);
            if ($child[0]['create_at'] < $allchild[0]['create_at']) {
                $type = 1;
            }
            $num = 1;
        }
        $downList = $this->getDownList();
        return $this->render('/crm-member-develop/view', [
            'downList' => $downList,
            'member' => $member,
            'child' => $child,
            'type' => $type,
            'num' => $num,
            'childid' => $childId,
            'ctype' => $ctype,
            'record' => $record,
            'id' => $id
        ]);

    }

    /**
     * @param $id
     * @return string
     * 更新拜访记录
     */
    public function actionVisitUpdate($id, $childId, $ctype = null)
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['CrmVisitRecordChild']['sil_time'] = serialize([$post['day'], $post['hour'], $post['minute']]);
            $post['CrmVisitRecordChild']['title'] = '拜访' . $post['cust_name'];
            $post['CrmVisitRecordChild']['start'] = $post['arriveDate'];
            $post['CrmVisitRecordChild']['end'] = $post['leaveDate'];
            $post['CrmVisitRecord']['create_by'] = $post['CrmVisitRecordChild']['create_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . "crm/crm-member-develop/visit-update?id=" . $id . '&childId=' . $childId;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['message']);
                return Json::encode(['msg' => "修改成功", "flag" => 1, "url" => Url::to(['view-visit', 'id' => $data['data']['id'], 'childId' => $data['data']['childId'], 'ctype' => $ctype])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }

        } else {
            $downList = $this->getDownList();
            $member = $this->getCustomerInfo($id);
            $districtId = $member['cust_district_2'];
            $districtAll = $this->getAllDistrict($districtId);
            $childCount = $this->getCountChild($member['sih_id']);
            $child = $this->getVisitChild($childId);
            $visit = $this->getCustInfo($id);
            return $this->render('/crm-member-develop/update', [
                'downList' => $downList,
                'member' => $member,
                'districtAll' => $districtAll,
                'childCount' => $childCount,
                'child' => $child,
                'ctype' => $ctype,
                'visit' => $visit,
                'id' => $id,
                'childId' => $childId
            ]);
        }
    }

    /**
     * @param null $id
     * @param null $childId
     * @return string
     * 删除拜访记录
     */
    public function actionDeleteVisit($id, $childId)
    {
        $url = $this->findApiUrl() . "crm/crm-member-develop/delete?id=" . $id . "&childId=" . $childId;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失败", "flag" => 0]);
        }
    }

    public function getCountChild($id)
    {
        $url = $this->findApiUrl() . "crm/crm-member-develop/count-child?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*获取一条子表数据*/
    public function getVisitChild($id)
    {
        $url = $this->findApiUrl() . "crm/crm-member-develop/visit-child?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*获取所有拜访记录信息*/
    public function getAllVisitChild($id)
    {
        $url = $this->findApiUrl() . "crm/crm-member-develop/all-visit-child?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * 获取客户信息
     */
    public function getCustomerInfo($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-member-develop/models?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }


    /*根据客户选择主表*/
    public function getCustInfo($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-return-visit/cust-info?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function getList()
    {
        $url = $this->findApiUrl() . "/crm/crm-member/down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*根据地址五级获取全部信息*/
    public function getAllDistrict($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-info/get-all-district?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

/*------------------------------拜访记录end--------------------------------------*/


    //导入模板
    public function actionDownTemplate()
    {
        $headArr = ['客户编码','公司简称','公司名称','姓名','部门','职位','职位职能','手机号码','邮箱','省份','地级市','详细地址','经营范围','客户来源','潜在需求','需求类别','录入人员','被分配者'];
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "潜在客户导入模板.xlsx";
        $objPHPExcel = new \PHPExcel();
        $key = "A";
        foreach ($headArr as $v) {
            $colum = $key;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(15);
            // $objPHPExcel->getActiveSheet($v)->getStyle($key)->getAlignment($key)->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            if ($key == "Z") {
                $key = "AA";
            } elseif ($key == "AZ") {
                $key = "BA";
            } else {
                $key++;
            }
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . 2, 'crm2017xxxxx')
            ->setCellValue('B' . 2, '富士康')
            ->setCellValue('C' . 2, '富士康科技集团')
            ->setCellValue('D' . 2, '张三')
            ->setCellValue('E' . 2, 'IT部')
            ->setCellValue('F' . 2, '师一')
            ->setCellValue('G' . 2, 'ERP开发')
            ->setCellValue('H' . 2, '13688889999')
            ->setCellValue('I' . 2, 'XXX@XX.COM')
            ->setCellValue('J' . 2, '广东省')
            ->setCellValue('K' . 2, '深圳市')
            ->setCellValue('L' . 2, '龙华新区 ...')
            ->setCellValue('M' . 2, '工业耗材')
            ->setCellValue('N' . 2, '自动化论坛')
            ->setCellValue('O' . 2, '销售')
            ->setCellValue('P' . 2, '车铣制品(NEW)')
            ->setCellValue('Q' . 2, '李方波')
            ->setCellValue('R' . 2, '李方波');
        $fileName = "potential.xlsx";
        $fileName = iconv("utf-8", "gb2312", $fileName);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean(); // 清除缓冲区,避免乱码

        //以下导出-excel2003版本
//        header('Content-Type: application/vnd.ms-excel');
//        header("Content-Disposition: attachment;filename=" . $fileName);
//        header('Cache-Control: max-age=0');
//        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//        $objWriter->save('php://output'); // 文件通过浏览器下载

        //以下导出-excel2007版本
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_clean();    //用于清除缓冲区的内容,兼容
        $objWriter->save('php://output');
        exit();
    }




    //数据导出
    private function export($data)
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '公司名称')
            ->setCellValue('C1', '公司简称')
            ->setCellValue('D1', '姓名')
            ->setCellValue('E1', '部门')
            ->setCellValue('F1', '职位')
            ->setCellValue('G1', '职能职称')
            ->setCellValue('H1', '手机号码')
            ->setCellValue('I1', '邮箱')
            ->setCellValue('J1', '省份')
            ->setCellValue('K1', '地级市')
            ->setCellValue('L1', '经营模式')
            ->setCellValue('M1', '经营范围')
            ->setCellValue('N1', '客户来源')
            ->setCellValue('O1', '潜在需求')
            ->setCellValue('P1', '需求类目')
            ->setCellValue('Q1', '录入人员')
            ->setCellValue('R1', '被分配者');
        foreach ($data as $key => $val) {
            $num = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, $num-1)
                ->setCellValue('B'.$num, $val['cust_sname'])
                ->setCellValue('C'.$num, $val['cust_shortname'])
                ->setCellValue('D'.$num, $val['cust_contcats'])
                ->setCellValue('E'.$num, $val['cust_department'])
                ->setCellValue('F'.$num, $val['cust_position'])
                ->setCellValue('G'.$num, $val['cust_function'])
                ->setCellValue('H'.$num, $val['cust_tel2'])
                ->setCellValue('I'.$num, $val['cust_email'])
                ->setCellValue('J'.$num, $val['province'])
                ->setCellValue('K'.$num, $val['city'])
                ->setCellValue('L'.$num, $val['cust_businesstype'])
                ->setCellValue('M'.$num, $val['member_businessarea'])
                ->setCellValue('N'.$num, $val['member_source'])
                ->setCellValue('O'.$num, $val['member_reqflag'])
                ->setCellValue('P'.$num, $val['member_reqitemclass'])
                ->setCellValue('Q'.$num, $val['create_by'])
                ->setCellValue('R'.$num, $val['allotman']);
        }
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xls";
        // 创建PHPExcel对象，注意，不能少了\
        $fileName = iconv("utf-8", "gb2312", $fileName);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean(); // 清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=" . $fileName);
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); // 文件通过浏览器下载
        exit();
    }

}