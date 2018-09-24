<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\models\Upload;
use app\modules\system\models\SystemLog;
use app\widgets\upload\Ftp;
use Yii;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;


/**
 * CrmCreditApplyController implements the CRUD actions for CrmCreditApply model.
 */
class CrmCreditApplyController extends BaseController
{
    private $_url = 'crm/crm-credit-apply/'; //对应api

    public function beforeAction($action)
    {
        $this->ignorelist = array_merge($this->ignorelist, [
            "/crm/crm-credit-apply/upload",
        ]);
        return parent::beforeAction($action);
    }
    public function actions(){
        return array_merge(parent::actions(),[
            'upload' => [
                'class' => \app\widgets\upload\UploadAction::className(),
                'scene'=>trim(\Yii::$app->ftpPath["CCA"]["father"],"/")."/".trim(\Yii::$app->ftpPath["CCA"]["Credit"],"/")
            ],
        ]);
    }
    /**
     * @return mixed|string
     * 账信申请列表
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index?companyId=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if(empty(Yii::$app->user->identity->is_supper)){
            $queryParam['staff_id']=Yii::$app->user->identity->staff_id;
        }
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
//        dumpE(Json::decode($this->findCurl()->get($url)));
        if (Yii::$app->request->isAjax) {
            $dataProvider = Json::decode($this->findCurl()->get($url));
            foreach ($dataProvider['rows'] as $key => $val){
                $dataProvider['rows'][$key]['credit_code']='<a href="'. Url::to(['view','id'=>$val['l_credit_id']]).'">'.$val['credit_code'].'</a>';
                $dataProvider['rows'][$key]['file']='<a target="_blank" href="'. Yii::$app->ftpPath['httpIP'].$val['file_new'].'">'.$val['file_old'].'</a>';
            }
            return Json::encode($dataProvider);
        }
        $downList = $this->getDownList();
        $columns = $this->getField('/crm/crm-credit-apply/index');
        return $this->render('index', [
            'downList' => $downList,
            'queryParam'=>$queryParam,
            'columns'=>$columns
        ]);
    }

    public function actionExport()
    {
        $url = $this->findApiUrl() . $this->_url . "index?export=1&companyId=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if(empty(Yii::$app->user->identity->is_supper)){
            $queryParam['staff_id']=Yii::$app->user->identity->staff_id;
        }
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        \Yii::$app->controller->action->id = 'index';
        return $this->exportFiled($dataProvider['rows']);
    }

    public function actionUpdate($id){
        $uploadModel = new Upload();
        if(Yii::$app->request->getIsPost()){
            $post = Yii::$app->request->post();
            $isApply = Yii::$app->request->get('is_apply');
            $status = Yii::$app->request->get('status');
//            $result = $ftp->ftp_dir_exists($name);
//            if($result == false){
//                $ftp->mkdirs($name);
//            }
//            $licnameA = date('Y-m-d');//获取当前时间
//            $licnameA = str_replace('-', '', $licnameA);
//            $postfix = pathinfo($_FILES['Upload']['name']['message'],PATHINFO_EXTENSION);
//            $desiga = '/cmp/bslcns/'.$licnameA.'/'.$licnameA.rand(1000,9999).'.'.$postfix;
//            $tmp = $_FILES['Upload']['tmp_name']['message'];
////            $ftp->mkdirs('/cmp/bslcns/'.$licnameA);
//            $res = $ftp->put($desiga,$tmp);
//            dumpE($res);


            /*文件上传*/
//            $uploadModel->message = UploadedFile::getInstances($uploadModel, 'message');
//            $uploadModel->file = UploadedFile::getInstances($uploadModel, 'file');
//            if (!empty($uploadModel->message)) {
//                if (!$uploadModel->validate(['message'])) {
//                    return Json::encode(['msg' => current($uploadModel->getFirstErrors()), "flag" => 0]);
//                }
//                $father = Yii::$app->ftpPath['CCA']['father'];
//                $pathlcn = Yii::$app->ftpPath['CCA']['Credit'];
//                $uploadaddress = date("ymd");
//                $ftp = new Ftp();
//                $fullDir = trim($father, "/") . "/" . trim($pathlcn, "/") . "/" . $uploadaddress;
//                if (!$ftp->ftp_dir_exists($fullDir)) {
//                    $ftp->mkdirs($fullDir);
//                }
//                foreach ($uploadModel->message as $k => $attach) {
//                    $fileName = $attach->name;
//                    $newfileName = $uploadaddress . (time() + $k) . "." . pathinfo($fileName, PATHINFO_EXTENSION);
//                    $tempName = $attach->tempName;
//                    $tmpfile = \Yii::$app->getRuntimePath() . "\\tmpfile" . time();
//                    $tmpfile = str_replace("\\", "/", $tmpfile);
//                    $dest = "/".$fullDir . "/" . trim($newfileName, "/");
//                    if (move_uploaded_file($tempName, $tmpfile) && $ftp->put($dest, $tmpfile) && @unlink($tmpfile)) {
//                        $post['message'][$k]["file_old"] = $fileName;
//                        $post['message'][$k]["file_new"] = $newfileName;
//                    } else {
//                        return Json::encode(['msg' => "上传文件失败", "flag" => 0]);
//                    }
//                }
//            }
//
//            if (!empty($uploadModel->file)) {
//                if (!$uploadModel->validate(['file'])) {
//                    return Json::encode(['msg' => current($uploadModel->getFirstErrors()), "flag" => 0]);
//                }
//                $father = Yii::$app->ftpPath['CCA']['father'];
//                $pathlcn = Yii::$app->ftpPath['CCA']['Credit'];
//                $uploadaddress = date("ymd");
//
//                $ftp = new Ftp();
//                $fullDir = trim($father, "/") . "/" . trim($pathlcn, "/") . "/" . $uploadaddress;
//                if (!$ftp->ftp_dir_exists($fullDir)) {
//                    $ftp->mkdirs($fullDir);
//                }
//                foreach ($uploadModel->file as $k => $attach) {
//                    $fileName = $attach->name;
//                    $newfileName = $uploadaddress . (time() + $k) . "." . pathinfo($fileName, PATHINFO_EXTENSION);
//                    $tempName = $attach->tempName;
//                    $tmpfile = \Yii::$app->getRuntimePath() . "\\tmpfile" . time();
//                    $tmpfile = str_replace("\\", "/", $tmpfile);
//                    $dest = $fullDir . "/" . trim($newfileName, "/");
//                    if (move_uploaded_file($tempName, $tmpfile) && $ftp->put($dest, $tmpfile) && @unlink($tmpfile)) {
//                        $post['file'][$k]["file_old"] = $fileName;
//                        $post['file'][$k]["file_new"] = $newfileName;
//                    } else {
//                        return Json::encode(['msg' => "上传文件失败", "flag" => 0]);
//                    }
//                }
//            }

            $post['LCrmCreditApply']['update_by'] = Yii::$app->user->identity->staff_id;
            $post['LCrmCreditApply']['company_id'] = Yii::$app->user->identity->company_id;
            if($isApply){
                $post['LCrmCreditApply']['credit_status'] = $status;
            }
            $url = $this->findApiUrl() . "/crm/crm-credit-apply/update?id=".$id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog('修改'.$post['cust_name'].'客户账信申请');
                if (!empty($isApply)) {
                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view','id'=>$data['data'],'is_apply'=>1])]);
                } else {
                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']])]);
                }
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        }
        $apply = $this->getModel($id);
        $model = $this->getCustomer($apply['cust_id']);
//        dumpE($apply['volume_trade']);
        $downList = $this->getDownList();
        return $this->render('update',[
            'downList'=>$downList,
            'model'=>$model,
            'apply'=>$apply,
        ]);

    }

    /**
     * @param $id
     * @return string
     * 详情
     */
    public function actionView($id){
        $isApply = Yii::$app->request->get('is_apply');
        $model = $this->getModel($id);
        $verify = $this->getVerify($id,$model['credit_type']);//審核信息
        $crmcertf = $this->getCrmCertf($model['cust_id']);
        $newnName1 = $crmcertf['bs_license'];
        $newnName1 = substr($newnName1, 2, 6);
//        $newnName1 = str_replace('-', '', $newnName1);
        $newnName2 = $crmcertf['tx_reg'];
        $newnName2 = substr($newnName2, 2, 6);
//        $newnName2 = str_replace('-', '', $newnName2);
        $newnName3 = $crmcertf['qlf_certf'];
        $newnName3 = substr($newnName3, 2, 6);
        return $this->render('view',[
            'id'=>$id,
            'model'=>$model,
            'isApply'=>$isApply,
            'verify'=>$verify,
            'crmcertf' => $crmcertf,
            'newnName1' => $newnName1,
            'newnName2' => $newnName2,
            'newnName3' => $newnName3,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * 查询三证
     */
    public function getCrmCertf($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-apply/crm-certf?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        }
    }

    /**
     *
     */
    public function actionCancleApply($id){
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "cancle-apply?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg'] . '取消申请');
                return Json::encode(['msg' => "取消成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $this->layout = '@app/views/layouts/ajax';
        return $this->render('_cancleapply', [
            'id' => $id,
        ]);
    }

    /**
     * @param $id
     * @return string
     * 冻结客户帐信额度
     */
    public function actionFreeze($id){
        $url = $this->findApiUrl() . $this->_url . "freeze?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "冻结成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => $result['msg'].' 冻结失败', "flag" => 0]);
        }
    }

    public function actionVerify($id){
        $url = $this->findApiUrl() . $this->_url . "verify?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        if ($result['msg'] == 'true') {
            return 'true';
        } else {
            return 'false';
        }
    }


    /**
     * 获取下拉菜单
     */
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * 获取客户信息
     */
    public function getCustomer($custId)
    {
        $url = $this->findApiUrl() . $this->_url . "customer?id=".$custId;
        $result = Json::decode($this->findCurl()->get($url));
//        $result['csarea']['csarea_id'] = $result['csarea_id'];
//        $result['cust_type']['bsp_id'] = $result['cust_type'];
//        $result['cust_level']['bsp_id'] = $result['cust_level'];
//        $result['cust_compvirtue']['bsp_id'] = $result['cust_compvirtue'];
//        $result['manager']['staff_name'] = $result['staff_name'];
//        $result['manager']['staff_mobile'] = $result['staff_mobile'];
        return $result;
    }

    /**
     * @return mixed|string
     * 选择客户
     */
    public function actionSelectCustomer()
    {
        $this->layout = '@app/views/layouts/ajax';
        $url = $this->findApiUrl() . $this->_url."select-customer?companyId=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        foreach ($dataProvider['rows'] as $key => $val){
            $dataProvider['rows'][$key]['total_investment'] = unserialize($dataProvider['rows'][$key]['total_investment']);
            $dataProvider['rows'][$key]['official_receipts'] = unserialize($dataProvider['rows'][$key]['official_receipts']);
        }
        $dataProvider = Json::encode($dataProvider);

//        dumpE(Json::decode($dataProvider));
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        return $this->render('_customer',['queryParam'=>$queryParam]);
    }

    /*检测客户是否已申请*/
    public function actionCheckCust($id)
    {
        $url = $this->findApiUrl() . $this->_url . "check-cust?id=".$id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function actionFileDownload($file)
    {
//        dumpE($file);
        header("Content-type:text/html;charset=utf-8");
// $file_name="cookie.jpg";
        //解决中文不能显示的问题
        $file_name = iconv("utf-8","gb2312",$file);
        $ext = substr(strrchr($file, '.'), 0);
        $name = basename($file,$ext);
        $file_path = 'uploads/creditApply/' . base64_encode($name) . $ext;
//        dumpE($file_path);
        if(!file_exists($file_path)){
            echo "<script>alert('找不到文件！');history.go(-1);</script>";exit();
        }
        $fp=fopen($file_path,"r");
        $file_size=filesize($file_path);
        // 下载文件需要用到的头
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length:".$file_size);
        header("Content-Disposition: attachment; filename=".$file_name);
        $buffer=1024;
        $file_count=0;
        // 向浏览器返回数据
        while(!feof($fp) && $file_count<$file_size){
            $file_con=fread($fp,$buffer);
            $file_count+=$buffer;
            echo $file_con;
        }
        fclose($fp);
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
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 获取账信申请信息
     */
    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    /*检测客户是否已申请*/
    public function actionSettlement($id)
    {
        $url = $this->findApiUrl() . $this->_url . "settlement?id=".$id;
        $result = $this->findCurl()->get($url);
        return $result;
    }
}
