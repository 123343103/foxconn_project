<?php
/**
 * @TODO
 * 单据审核流控制器
 * User: F3859386
 * Date: 2017/1/12
 * Time: 15:43
 */
namespace app\modules\system\controllers;

use app\commands\GetCustomerCode;
use app\controllers\BaseController;
use app\models\Upload;
use app\models\User;
use app\modules\crm\models\CrmCustomerApply;
use app\modules\system\models\SystemLog;
use app\modules\warehouse\controllers\OtherOutStockController;
use app\widgets\upload\Ftp;
use yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;

class VerifyRecordController extends BaseController
{
    public function beforeAction($action)
    {
        $this->ignorelist = array_merge($this->ignorelist, [
            "/system/verify-record/index",
            "/system/verify-record/verify",
            "/system/verify-record/opinion",
            "/system/verify-record/verify-one",
            "/system/verify-record/verify-all",
            "/system/verify-record/pass-opinion",
            "/system/verify-record/upload",
        ]);
        $token = Yii::$app->request->get('token');
        if (in_array($this->action->id, ['verify', 'audit-pass', 'audit-reject', 'load-record']) && !empty($token)) {
            // 令牌登陆不成功 跳转提示链接失效
            // 示例链接 http://localhost/php_project/fjj_erp/web/system/verify-record/verify?id=204&token=8bb132138bb0c3a4011155e18e39858c&urlToken=9c83ac1b43027f1032724806dc160331
//            if (empty(Yii::$app->user->loginByAccessToken('8bb132138bb0c3a4011155e18e39858c'))) {
            if (empty(Yii::$app->user->loginByAccessToken($token))) {
//            dumpE((Url::to([Yii::$app->get])));
                return $this->redirect(['/index/invalid1'])->send(); // 跳转无效链接提示
            }
            $this->layout = '@app/views/layouts/ajax';
        }
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

//    public function afterAction($action)
//    {
//        if (1==1) {
//            Yii::$app->user->logout();
//        }
//        return parent::afterAction($action);
//    }

    private $_url = "system/verify-record/";  //对应api控制器URL

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index?id=" . Yii::$app->user->identity->id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $type = $this->getBusinessType();
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        return $this->render('index', ['type' => $type, 'queryParam' => $queryParam]);
    }

    /**
     * 审核人
     * @param $id
     * @return mixed
     */
    public function actionReviewer($type, $id, $url = null)
    {
        if ($post = Yii::$app->request->post()) {
            $post['id'] = $id;    //单据ID
            $post['type'] = $type;  //审核流类型
            $post['staff'] = Yii::$app->user->identity->staff_id;//送审人ID
            $verifyUrl = $this->findApiUrl() . $this->_url . "verify-record";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($verifyUrl));
            if ($data['status']) {
                if (!empty($url)) {
                    return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1, "url" => $url]);
                } else {
                    return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1]);
                }
            } else {
                return Json::encode(['msg' => $data['msg'] . ' 送审失败！', "flag" => 0]);
            }
        }
        $urls = $this->findApiUrl() . $this->_url . "reviewer?type=" . $type . '&staff_id=' . Yii::$app->user->identity->staff_id;
        $review = Json::decode($this->findCurl()->get($urls));
        return $this->renderAjax('reviewer', [
            'review' => $review,
        ]);
    }

    /**
     * 加载审核顺序
     * @param $id
     * @return mixed
     */
    public function actionLoadRecord($id)
    {
        $url = $this->findApiUrl() . $this->_url . "load-record?id=" . $id;
        $queryParam = Yii::$app->request->queryParams;
        $token = Yii::$app->request->get('token');
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (!empty($token)) {
            Yii::$app->user->logout();
        }
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        return $dataProvider;
    }

    /**
     * @param $id
     * @return string
     * 审核详情
     */
    public function actionVerify($id)
    {

        $uid = Yii::$app->user->identity->user_id;
        $result = $this->getModels($id);
        $status = $this->getModelsStatus($id, $uid);
        $token = Yii::$app->request->get('token'); // 登陆口令
        $urlToken = Yii::$app->request->get('url-token'); // 防止篡改链接
//        dumpE($result);
        if (!empty($token)) {
//            dumpE(md5((string)$id . $token));
            if (($urlToken != md5((string)$id . $token)) || !$status) {
                $this->redirect(['/index/invalid2']); // 跳转无效链接提示
            }
        }
        switch ($result['bus_code']) {
            case 'cscbsh':
                $url = $this->findApiUrl() . "/ptdt/firm-report/models?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $firmCompared = $model['firmCompared'];
                $curl = $this->findApiUrl() . "/ptdt/firm-report/check-child?id=" . $model['pfr_id'];
                $childModel = Json::decode($this->findCurl()->get($curl));
                if ($firmCompared) {
                    $i = 0;
                    foreach ($firmCompared as $key => $val) {
                        $lists[$i] = $this->getCompared($val['firm_id'], $i);
                        $i++;
                    }
                } else {
                    $lists = $this->getCompared($model['firm_id'], $i = null);
                }
                if (!empty($token)) {
                    Yii::$app->user->logout();
                }
                return $this->render('_report', [
                    'model' => $model,
                    'childModel' => $childModel,
                    'firmCompared' => $firmCompared,
                    "lists" => $lists,
                    'result' => $result,
                    'id' => $id,
                    'token' => $token
                ]);
                break;
            case 'spkfxqsh':
                $url = $this->findApiUrl() . "/ptdt/product-dvlp/model?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url), false);
                return $this->render('_product', [
                    'model' => $model,
                    'result' => $result,
                    'id' => $id
                ]);
                break;
            case 'khbmsh':
                $url = $this->findApiUrl() . "/crm/crm-customer-apply/get-apply?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $cerf = $this->findApiUrl() . "/crm/crm-customer-apply/crm-certf?id=" . $model['cust_id'];
                $crmcertf = Json::decode($this->findCurl()->get($cerf));
                $down = $this->findApiUrl() . "/crm/crm-customer-apply/down-list";
                $downList = Json::decode($this->findCurl()->get($down));
//                dumpE($downList);
                return $this->render('_customer', [
                    'model' => $model,
                    'result' => $result,
                    'id' => $id,
                    'crmcertf' => $crmcertf,
                    'downList' => $downList
                ]);
                break;
            case 'credit':
                $url = $this->findApiUrl() . "/crm/crm-credit-apply/models?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $crmcertf = $this->getCrmCertf($model['cust_id']);
                $newnName1 = $crmcertf['bs_license'];
                $newnName1 = substr($newnName1, 2, 6);
//        $newnName1 = str_replace('-', '', $newnName1);
                $newnName2 = $crmcertf['tx_reg'];
                $newnName2 = substr($newnName2, 2, 6);
//        $newnName2 = str_replace('-', '', $newnName2);
                $newnName3 = $crmcertf['qlf_certf'];
                $newnName3 = substr($newnName3, 2, 6);
                $child = $this->getLlimit($result['vco_busid']);
//                dumpE($child);
                return $this->render('_credit', [
                    'model' => $model,
                    'result' => $result,
                    'id' => $id,
                    'status' => $status,
                    'crmcertf' => $crmcertf,
                    'newnName1' => $newnName1,
                    'newnName2' => $newnName2,
                    'newnName3' => $newnName3,
                    'child'=>$child
                ]);
                break;
//            case 'saqut':
//                $url = $this->findApiUrl() . "/sale/sale-cust-order/order-detail?id=" . $result['vco_busid'];
//                $data = Json::decode($this->findCurl()->get($url));
////                dumpE($data);
////                dumpE(Yii::$app->request->queryParams);
//                if (!empty($token)) {
//                    Yii::$app->user->logout();
//                }
////                Yii::$app->user->logout();
////                dumpE($result);
//                return $this->render('_quoted', [
//                    'data' => $data,
//                    'result' => $result,
//                    'id' => $id,
//                    'style' => 'auto',
//                    'token' => $token
//                ]);
//                break;
            case 'qtckd':
                $url = $this->findApiUrl() . "/warehouse/other-out-stock/view?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/warehouse/other-out-stock/child-data?id=" . $result['vco_busid'];
                $childs = Json::decode($this->findCurl()->get($url));
                $urls=$this->findApiUrl()."/warehouse/other-out-stock/options?id={$result['vco_busid']}";
                $url_wh=$this->findApiUrl()."/warehouse/other-out-stock/get-wh-jurisdiction?staff_id=".\Yii::$app->user->identity->staff->staff_id;
                $options=Json::decode($this->findCurl()->get($urls));
                $options['warehouse']=Json::decode($this->findCurl()->get($url_wh));
//                $model['all_address']=OtherOutStockController::actionGetAddress($model['district_id']).$model['address'];
                $businessType = $this->findCurl()->get($this->findApiUrl() . "/warehouse/other-out-stock/business-type");
//                print_r($model);
                return $this->render('_outstock_other', [
                    'model' => $model,
                    'childs' => $childs,
                    'result' => $result,
                    'id' => $id,
                    "options"=>$options,
                    'businessType' => $businessType
                ]);
                break;
            case 'wm03':
                $url = $this->findApiUrl() . "/warehouse/allocation/models?id=" . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
                return $this->render('_allocation', [
                    'data' => $data,
                    'result' => $result,
                    'id' => $id
                ]);
                break;
            case 'wm01':
                $url = $this->findApiUrl() . "/warehouse/other-stock-in/view?id=" . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
                return $this->render('@app/modules/warehouse/views/other-stock-in/view.php', ['viewData' => $data, 'vco_id' => $id]);
                break;
            case 'gysbm':
                $url = $this->findApiUrl() . "/spp/supplier/view?id=" . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
//                return $this->render('_supplier', [
//                    'data' => $data,
//                    'result' => $result,
//                    'id' => $id
//                ]);
                return $this->render('@app/modules/spp/views/supplier/view.php', ['viewData' => $data, 'vco_id' => $id]);
                break;
            case 'reqer':
                $url = $this->findApiUrl() . "/purchase/purchase-apply/models?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $verify = $this->getVerify($result['vco_busid'], 54);
                return $this->render('_reqer', [
                    'model' => $model[0],
                    'pdtmodel' => $model[1],
                    'verify' => $verify,
                    'id' => $id
                ]);
                break;
            case 'notify':
                $url = $this->findApiUrl() . "/purchase/purchase-notify/models?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $verify = $this->getVerify($result['vco_busid'], 55);
                return $this->render('_notify', [
                    'model' => $model[0],
                    'products' => $model[1],
                    'verify' => $verify,
                    'id' => $id
                ]);
                break;
            case 'wm04':
                $url = $this->findApiUrl() . "/warehouse/inv-changeh/view?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                return $this->render('_inv-change', [
                    'model' => $model,
                    'result' => $result,
                    'id' => $id
                ]);
                break;
            case 'wm09':
                $url = $this->findApiUrl() . "/warehouse/set-inventory-warning/models?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                return $this->render('_inventory-warning', [
                    'hrstaffinfo' => $model[0],
                    'whinfo' => $model[1],
                    'id' => $id
                ]);
                break;
            case 'wm07':
                $url = $this->findApiUrl() . "/warehouse/waring-info/view?biw_h_pkid=" . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
//                var_dump($data["rows"]);
                return $this->render('_wh_waring', [
                    'model' => $data["rows"],
                    'result' => $result,
                    'id' => $id
                ]);
                break;
            case 'wm05':
                $urlH = $this->findApiUrl() . 'warehouse/warehouse-change/' . "model?id=" . $result['vco_busid'];
                $modelH = Json::decode($this->findCurl()->get($urlH));
                $url = $this->findApiUrl() . 'warehouse/warehouse-change/' . "models?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
//                dumpE($result['vco_busid']);

                return $this->render('_warehouse-change', [
                    "model" => $model,
                    'modelH' => $modelH,
                    'result' => $result,
                    'id' => $id
                ]);
                break;
            case 'uptepdtsel':
                $isUpOrDown = 2;
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-pdt-info?vco_busid=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-rel-pdt?vco_busid=" . $result['vco_busid'];
                $model2 = Json::decode($this->findCurl()->get($url));
                $mdl = "";
                if (isset($model2)) {

                    foreach ($model2 as $value) {
                        $mdl .= $value['pdt_name'] . ';';
                    }
                }
                $mdl = substr($mdl, 0, strlen($mdl) - 1);
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-pdt-img?vco_busid=" . $result['vco_busid'];
                $model3 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-part-no-info?vco_busid=" . $result['vco_busid'];
                $model4 = Json::decode($this->findCurl()->get($url));
                $img = [];
                foreach ($model3 as $value) {
                    $img[] = Html::img(\Yii::$app->params["FtpConfig"]["httpIP"] . $value["fl_new"], ["style" => "width:60px;height:60px;margin:10px 0px;", "class" => "imgGroup"]);
                }
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-price?vco_busid=" . $result['vco_busid'];
                $model5 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-stock?vco_busid=" . $result['vco_busid'];
                $model6 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-ship?vco_busid=" . $result['vco_busid'];
                $model7 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-pack?vco_busid=" . $result['vco_busid'];
                $model8 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-deliv?vco_busid=" . $result['vco_busid'];
                $model9 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-machine?vco_busid=" . $result['vco_busid'];
                $model10 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-warr?vco_busid=" . $result['vco_busid'];
                $model11 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-pdt-details?vco_busid=" . $result['vco_busid'];
                $model12 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/is-yn-machine?vco_busid=" . $result['vco_busid'];
                $model13 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-machine-details?vco_busid=" . $result['vco_busid'];
                $model14 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-attr-name?vco_busid=" . $result['vco_busid'];
                $model15 = Json::decode($this->findCurl()->get($url));
                for ($i = 0; $i < count($model15, 0); $i++) {
                    $prtValue = "";
                    $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-value?vco_busid=" . $result['vco_busid'] . "&catgattrid=" . $model15[$i]["catg_attr_id"];
                    $model16 = Json::decode($this->findCurl()->get($url));
                    foreach ($model16 as $value) {
                        $prtValue .= $value["attr_value"] . ",";
                    }
                    $prtValue = substr($prtValue, 0, strlen($prtValue) - 1);
                    $model15[$i]["attr_value"] = $prtValue;
                }
                $url = $this->findApiUrl() . "/ptdt/product-shelf/new-get-prt-attr?vco_busid=" . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
                return $this->render('_pdtselves', [
                    'data' => $data,
                    'isUpOrDown' => $isUpOrDown,
                    'model' => $model,
                    'mdl' => $mdl,
                    'img' => $img,
                    'model3' => $model3,
                    'model4' => $model4,
                    'model5' => $model5,
                    'model6' => $model6,
                    'model7' => $model7,
                    'model8' => $model8,
                    'model9' => $model9,
                    'model10' => $model10,
                    'model11' => $model11,
                    'model12' => $model12,
                    'model13' => $model13,
                    'model14' => $model14,
                    'model15' => $model15,
                    'id' => $id
                ]);
                break;
            case 'pdtsel':
            case 'pdtdowmsel':
            case 'pdtreupshelf':
                $isUpOrDown = 0;
                if ($result['bus_code'] == 'pdtsel') {
                    $isUpOrDown = 0;
                } else if ($result['bus_code'] == 'pdtdowmsel') {
                    $isUpOrDown = 1;
                } else {
                    $isUpOrDown = 3;
                }
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-pdt-info-shelves?vco_busid=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-rel-pdt-shelves?vco_busid=" . $result['vco_busid'];
                $model2 = Json::decode($this->findCurl()->get($url));
                $mdl = "";
                if (isset($model2)) {

                    foreach ($model2 as $value) {
                        $mdl .= $value['pdt_name'] . ';';
                    }
                }
                $mdl = substr($mdl, 0, strlen($mdl) - 1);
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-pdt-img-shelves?vco_busid=" . $result['vco_busid'];
                $model3 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-part-no-info-shelves?vco_busid=" . $result['vco_busid'];
                $model4 = Json::decode($this->findCurl()->get($url));
                $img = [];
                foreach ($model3 as $value) {
                    $img[] = Html::img(\Yii::$app->params["FtpConfig"]["httpIP"] . $value["fl_new"], ["style" => "width:60px;height:60px;margin:10px 0px;", "class" => "imgGroup"]);
                }
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-price-shelves?vco_busid=" . $result['vco_busid'];
                $model5 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-stock-shelves?vco_busid=" . $result['vco_busid'];
                $model6 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-ship-shelves?vco_busid=" . $result['vco_busid'];
                $model7 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-pack-shelves?vco_busid=" . $result['vco_busid'];
                $model8 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-deliv-shelves?vco_busid=" . $result['vco_busid'];
                $model9 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-machine-shelves?vco_busid=" . $result['vco_busid'];
                $model10 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-warr-shelves?vco_busid=" . $result['vco_busid'];
                $model11 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-pdt-details-shelves?vco_busid=" . $result['vco_busid'];
                $model12 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/is-yn-machine-shelves?vco_busid=" . $result['vco_busid'];
                $model13 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-machine-details-shelves?vco_busid=" . $result['vco_busid'];
                $model14 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-attr-name?vco_busid=" . $result['vco_busid'];
                $model15 = Json::decode($this->findCurl()->get($url));
                for ($i = 0; $i < count($model15, 0); $i++) {
                    $prtValue = "";
                    $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-value?vco_busid=" . $result['vco_busid'] . "&catgattrid=" . $model15[$i]["catg_attr_id"];
                    $model16 = Json::decode($this->findCurl()->get($url));
                    foreach ($model16 as $value) {
                        $prtValue .= $value["attr_value"] . ",";
                    }
                    $prtValue = substr($prtValue, 0, strlen($prtValue) - 1);
                    $model15[$i]["attr_value"] = $prtValue;
                }
                $url = $this->findApiUrl() . "/ptdt/product-shelf/new-get-prt-attr-shelves?vco_busid=" . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-res?vco_busid=" . $result['vco_busid'];
                $downreason = Json::decode($this->findCurl()->get($url));
                $newFileName = substr($model4['file_new'], 2, 6);
                return $this->render('_pdtselves', [
                    'newFileName' => $newFileName,
                    'downreason' => $downreason,
                    'data' => $data,
                    'isUpOrDown' => $isUpOrDown,
                    'model' => $model,
                    'mdl' => $mdl,
                    'img' => $img,
                    'model3' => $model3,
                    'model4' => $model4,
                    'model5' => $model5,
                    'model6' => $model6,
                    'model7' => $model7,
                    'model8' => $model8,
                    'model9' => $model9,
                    'model10' => $model10,
                    'model11' => $model11,
                    'model12' => $model12,
                    'model13' => $model13,
                    'model14' => $model14,
                    'model15' => $model15,
                    'id' => $id
                ]);
                break;
            case 'order':
                $url = $this->findApiUrl() . "sale/sale-trade-order/log-order-detail?id=" . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
//                dumpE($data);
                if ($data["seller"]["isrule"] == 1) { //如果自己是客户经理人
                    $data["seller"]["leader"] = $data["seller"]["staff_code"] . " " . $data["seller"]["staff"]["name"];
                }
                return $this->render('_reprice', [
                    'data' => $data,
                    'id' => $id
                ]);
                break;
            case 'saqut':
                $url = $this->findApiUrl() . '/sale/sale-quoted-order/view?id=' . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
                $verify = $this->getVerify($id, $data['info']['price_type']);//審核信息
                $seller = $this->getSeller($data['info']['seller']);
                $credits = $this->getCreditLimit($data['info']['customer']['cust_id'], $data['info']['cur_id']);
//        dumpE($verify);
                if (!empty($token)) {
                    Yii::$app->user->logout();
                }
                return $this->render('_quoteorder', [
                    'data' => $data['info'],
                    'dt' => $data['dt'],
                    'seller' => $seller,
                    'credits' => $credits,
                    'id' => $id,
                    'token' => $token
                ]);
                break;
            case 'refund':
                $url = $this->findApiUrl() . '/sale/ord-refund/view?id=' . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
                if (!empty($token)) {
                    Yii::$app->user->logout();
                }
                return $this->render('_orderefund', [
                    'data' => $data['refund'],
                    'dt' => $data['dt'],
                    'id' => $id,
                    'token' => $token
                ]);
                break;
            case 'checklist':
                $code = $this->getCode($result['vco_busid']);
                $url = $this->findApiUrl() . "/warehouse/check-list/models?id=" . $result['vco_busid'] . '&code=' . $code;
                $model = Json::decode($this->findCurl()->get($url));
                $types = $this->getcltype();
                $type = $types['type'][0]['business_type_id'];
                $verify = $this->getVerify($result['vco_busid'], $type);
                return $this->render('_checklist', [
                    'model' => $model[0],
                    'pdtmodel' => $model[1],
                    'verify' => $verify,
                    'id' => $id,
                ]);
                break;
            case 'whprice':
                $url = $this->findApiUrl() . "/warehouse/wh-cost-confirm/model?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url), false);
                return $this->render('_whprice', [
                    'model' => $model,
                    'result' => $result,
                    'id' => $id
                ]);
                break;
            case 'ordlgst':
                $url = $this->findApiUrl() . "/warehouse/logisticsorder/log-order-info?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $verify = $this->getVerify($result['vco_busid'], 61);
                return $this->render('_ordlgst', [
                    'model' => $model,
                    'verify' => $verify,
                    'id' => $id
                ]);
                break;
            case 'ordrecives':
                $url = $this->findApiUrl()."sale/sale-bank-sta/get-data-by-rbo-id?rbo_id=" . $result['vco_busid'];//获取rbo_id的所有审核记录
                $rboData = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "sale/sale-bank-sta/get-trans-info?transid=" . $rboData[0]['TRANSID'];//单笔流水信息
                $oneTransInfo = Json::decode($this->findCurl()->get($url));
                $orderList = [];
                $orderlist = "";
                $orderSum = 0;//订单总金额
                $money = 0;//订单金额
                foreach ($rboData as $value) {
                    array_push($orderList, $value['ord_no']);
                    if ($value['pay_type'] == 1) {
                        $money = $value['stag_cost'];
                    } else {
                        $money = $value['req_tax_amount'];
                    }
                    $orderSum = ($orderSum * 1000 + $money * 1000) / 1000;
                }
                $orderSum = sprintf("%.3f", $orderSum);
                $orderList = array_unique($orderList);
                if (count($orderList) == 1) {
                    $orderlist .= $orderList[0];
                } else {
                    for ($i = 0; $i < count($orderList); $i++) {
                        if ($i == count($orderList) - 1) {
                            $orderlist .= $orderList[$i];
                        } else {
                            $orderlist .= $orderList[$i] . ";";
                        }
                    }
                }
                $remarks = $rboData[0]['remark'];
                if ($remarks == "null") {
                    $remarks = "";
                }
                return $this->render('_ordrecives',['oneTransInfo' => $oneTransInfo, 'orderSum' => $orderSum, 'orderlist' => $orderlist, 'remarks' => $remarks, 'vco_id' =>$id]);
                break;
            case 'batchreceipts':
                $url=$this->findApiUrl()."sale/sale-bank-sta/get-trans-batch?rbo_id=".$result['vco_busid'];
                $data=Json::decode($this->findCurl()->get($url));
                for($j=0;$j<count($data);$j++)
                {
                    $url=$this->findApiUrl()."sale/sale-bank-sta/get-order-no?transid=".$data[$j]['TRANSID']."&rboid=".$result['vco_busid'];
                    $orderno=Json::decode($this->findCurl()->get($url));
                    $order_no="";
                    for($i=0;$i<count($orderno);$i++)
                    {
                        if($i==count($orderno)-1)
                        {
                            $order_no.=$orderno[$i]['ord_no'];
                        }
                        else{
                            $order_no.=$orderno[$i]['ord_no'].",";
                        }
                    }
                    $data[$j]['ord_no']=$order_no;
                    $url=$this->findApiUrl()."sale/sale-bank-sta/get-batch-money?transid=".$data[$j]['TRANSID']."&rboid=".$result['vco_busid'];
                    $anyMoney=Json::decode($this->findCurl()->get($url));
                    $data[$j]['stag_cost']=$anyMoney[0]['stag_cost'];
                    $url=$this->findApiUrl()."sale/sale-bank-sta/get-batch-remark?transid=".$data[$j]['TRANSID']."&rboid=".$result['vco_busid'];
                    $remark=Json::decode($this->findCurl()->get($url));
                    $data[$j]['remark']=$remark[0]['remark'];
                }
                return $this->render('batch-revices',['data'=>$data,'vco_id'=>$id]);
                break;
        }
    }

    public function getLlimit($id){
        $url = $this->findApiUrl() . $this->_url."llimit?id=" .$id;
        $codes = Json::decode($this->findCurl()->get($url));
        return $codes;
    }

    public function getCode($code)
    {
        $url = $this->findApiUrl() . "/warehouse/check-list/code?code=" . $code;
        $codes = Json::decode($this->findCurl()->get($url));
        if ($codes) {
            return $codes;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    /**
     * @return mixed
     * 获取销售人员等信息
     */
    public function getSeller($code)
    {
        $url = $this->findApiUrl() . '/sale/sale-quoted-order/seller?code=' . $code;
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * @return mixed
     * 获取账信额度
     */
    public function getCreditLimit($id, $cur)
    {
        $url = $this->findApiUrl() . '/sale/sale-quoted-order/get-cust-credit?id=' . $id . '&cur=' . $cur;
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * 审核一个
     * @param $id
     * @return string
     */
    public function actionVerifyOne($id)
    {
        $this->layout = '@app/views/layouts/ajax';
        $result = $this->getModels($id);
        $status = 10;
        switch ($result['bus_code']) {
            case 'cscbsh':
                $url = $this->findApiUrl() . "/ptdt/firm-report/models?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $firmCompared = $model['firmCompared'];
                $curl = $this->findApiUrl() . "/ptdt/firm-report/check-child?id=" . $model['pfr_id'];
                $childModel = Json::decode($this->findCurl()->get($curl));
                if ($firmCompared) {
                    $i = 0;
                    foreach ($firmCompared as $key => $val) {
                        $lists[$i] = $this->getCompared($val['firm_id'], $i);
                        $i++;
                    }
                } else {
                    $lists = $this->getCompared($model['firm_id'], $i = null);
                }
                return $this->render('check-report', [
                    'model' => $model,
                    'childModel' => $childModel,
                    'firmCompared' => $firmCompared,
                    "lists" => $lists,
                    'result' => $result,
                    'id' => $id
                ]);
                break;
            case 'spkfxqsh':
                $url = $this->findApiUrl() . "/ptdt/product-dvlp/model?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url), false);
                return $this->render('check-product', [
                    'model' => $model,
                    'result' => $result,
                    'id' => $id
                ]);
                break;
            case 'khbmsh':
                $url = $this->findApiUrl() . "/crm/crm-customer-apply/get-apply?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $cerf = $this->findApiUrl() . "/crm/crm-customer-apply/crm-certf?id=" . $model['cust_id'];
                $crmcertf = Json::decode($this->findCurl()->get($cerf));
                $down = $this->findApiUrl() . "/crm/crm-customer-apply/down-list";
                $downList = Json::decode($this->findCurl()->get($down));
//                dumpE($result);
                return $this->render('_customer', [
                    'model' => $model,
                    'result' => $result,
                    'id' => $id,
                    'crmcertf' => $crmcertf,
                    'downList' => $downList
                ]);
                break;
            case 'credit':
                $url = $this->findApiUrl() . "/crm/crm-credit-apply/models?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $crmcertf = $this->getCrmCertf($model['cust_id']);
                $newnName1 = $crmcertf['bs_license'];
                $newnName1 = substr($newnName1, 2, 6);
//        $newnName1 = str_replace('-', '', $newnName1);
                $newnName2 = $crmcertf['tx_reg'];
                $newnName2 = substr($newnName2, 2, 6);
//        $newnName2 = str_replace('-', '', $newnName2);
                $newnName3 = $crmcertf['qlf_certf'];
                $newnName3 = substr($newnName3, 2, 6);
                $child = $this->getLlimit($result['vco_busid']);
//                dumpE($child);
                return $this->render('_credit', [
                    'model' => $model,
                    'result' => $result,
                    'id' => $id,
                    'status' => $status,
                    'crmcertf' => $crmcertf,
                    'newnName1' => $newnName1,
                    'newnName2' => $newnName2,
                    'newnName3' => $newnName3,
                    'child'=>$child
                ]);
                break;
//            case 'saqut':
//                $url = $this->findApiUrl() . "/sale/sale-cust-order/order-detail?id=" . $result['vco_busid'];
//                $data = Json::decode($this->findCurl()->get($url));
////                dumpE($data);
//                return $this->render('_quoted', [
//                    'data' => $data,
//                    'result' => $result,
//                    'id' => $id,
//                    'status' => $status
//                ]);
//                break;
            case 'qtckd':
                $url = $this->findApiUrl() . "/warehouse/other-out-stock/view?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/warehouse/other-out-stock/child-data?id=" . $result['vco_busid'];
                $childs = Json::decode($this->findCurl()->get($url));
                $urls=$this->findApiUrl()."/warehouse/other-out-stock/options?id={$result['vco_busid']}";
                $url_wh=$this->findApiUrl()."/warehouse/other-out-stock/get-wh-jurisdiction?staff_id=".\Yii::$app->user->identity->staff->staff_id;
                $options=Json::decode($this->findCurl()->get($urls));
                $options['warehouse']=Json::decode($this->findCurl()->get($url_wh));
//                $model['all_address']=OtherOutStockController::actionGetAddress($model['district_id']).$model['address'];
                $businessType = $this->findCurl()->get($this->findApiUrl() . "/warehouse/other-out-stock/business-type");
//                print_r($model);
                return $this->render('_outstock_other', [
                    'model' => $model,
                    'childs' => $childs,
                    'result' => $result,
                    'id' => $id,
                    "options"=>$options,
                    'businessType' => $businessType
                ]);
                break;
            case 'wm03':
                $url = $this->findApiUrl() . "/warehouse/allocation/models?id=" . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
                return $this->render('_allocation', [
                    'data' => $data,
                    'result' => $result,
                    'id' => $id
                ]);
                break;
            case 'wm01':
                $url = $this->findApiUrl() . "/warehouse/other-stock-in/view?id=" . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
                return $this->render('@app/modules/warehouse/views/other-stock-in/view.php', ['viewData' => $data, 'vco_id' => $id]);
                break;
            case 'gysbm':
                $url = $this->findApiUrl() . "/spp/supplier/view?id=" . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
                return $this->render('_supplier', [
                    'data' => $data,
                    'result' => $result,
                    'id' => $id
                ]);
                break;
            case 'reqer':
                $url = $this->findApiUrl() . "/purchase/purchase-apply/models?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $verify = $this->getVerify($result['vco_busid'], 54);
                return $this->render('_reqer', [
                    'model' => $model[0],
                    'pdtmodel' => $model[1],
                    'verify' => $verify,
                    'id' => $id
                ]);
                break;
            case 'notify':
                $url = $this->findApiUrl() . "/purchase/purchase-notify/models?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $verify = $this->getVerify($result['vco_busid'], 55);
                return $this->render('_notify', [
                    'model' => $model[0],
                    'products' => $model[1],
                    'verify' => $verify,
                    'id' => $id
                ]);
                break;
            case 'wm04':
                $urlH = $this->findApiUrl() . 'warehouse/inv-changeh/' . "model?id=" . $result['vco_busid'];
                $modelH = Json::decode($this->findCurl()->get($urlH));
                $url = $this->findApiUrl() . 'warehouse/inv-changeh/' . "models?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
//                dumpE($result['vco_busid']);
                return $this->render('_warehouse-change', [
                    "model" => $model,
                    'modelH' => $modelH,
                    'result' => $result,
                    'id' => $id
                ]);
                break;
            case 'wm05':
                $urlH = $this->findApiUrl() . 'warehouse/warehouse-change/' . "model?id=" . $result['vco_busid'];
                $modelH = Json::decode($this->findCurl()->get($urlH));
                $url = $this->findApiUrl() . 'warehouse/warehouse-change/' . "models?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
//                dumpE($result['vco_busid']);
                return $this->render('_warehouse-change', [
                    "model" => $model,
                    'modelH' => $modelH,
                    'result' => $result,
                    'id' => $id
                ]);
                break;
            case 'wm09':
                $url = $this->findApiUrl() . "/warehouse/set-inventory-warning/models?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                return $this->render('_inventory-warning', [
                    'hrstaffinfo' => $model[0],
                    'whinfo' => $model[1],
                    'id' => $id
                ]);
                break;
            case 'wm07':
                $url = $this->findApiUrl() . "/warehouse/waring-info/view?biw_h_pkid=" . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
//                var_dump($data["rows"]);
                return $this->render('_wh_waring', [
                    'model' => $data["rows"],
                    'result' => $result,
                    'id' => $id
                ]);
                break;
            case 'uptepdtsel':
                $isUpOrDown = 2;
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-pdt-info?vco_busid=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-rel-pdt?vco_busid=" . $result['vco_busid'];
                $model2 = Json::decode($this->findCurl()->get($url));
                $mdl = "";
                if (isset($model2)) {

                    foreach ($model2 as $value) {
                        $mdl .= $value['pdt_name'] . ';';
                    }
                }
                $mdl = substr($mdl, 0, strlen($mdl) - 1);
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-pdt-img?vco_busid=" . $result['vco_busid'];
                $model3 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-part-no-info?vco_busid=" . $result['vco_busid'];
                $model4 = Json::decode($this->findCurl()->get($url));
                $img = [];
                foreach ($model3 as $value) {
                    $img[] = Html::img(\Yii::$app->params["FtpConfig"]["httpIP"] . $value["fl_new"], ["style" => "width:60px;height:60px;margin:10px 0px;", "class" => "imgGroup"]);
                }
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-price?vco_busid=" . $result['vco_busid'];
                $model5 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-stock?vco_busid=" . $result['vco_busid'];
                $model6 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-ship?vco_busid=" . $result['vco_busid'];
                $model7 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-pack?vco_busid=" . $result['vco_busid'];
                $model8 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-deliv?vco_busid=" . $result['vco_busid'];
                $model9 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-machine?vco_busid=" . $result['vco_busid'];
                $model10 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-warr?vco_busid=" . $result['vco_busid'];
                $model11 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-pdt-details?vco_busid=" . $result['vco_busid'];
                $model12 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/is-yn-machine?vco_busid=" . $result['vco_busid'];
                $model13 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-machine-details?vco_busid=" . $result['vco_busid'];
                $model14 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-attr-name?vco_busid=" . $result['vco_busid'];
                $model15 = Json::decode($this->findCurl()->get($url));
                for ($i = 0; $i < count($model15, 0); $i++) {
                    $prtValue = "";
                    $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-value?vco_busid=" . $result['vco_busid'] . "&catgattrid=" . $model15[$i]["catg_attr_id"];
                    $model16 = Json::decode($this->findCurl()->get($url));
                    foreach ($model16 as $value) {
                        $prtValue .= $value["attr_value"] . ",";
                    }
                    $prtValue = substr($prtValue, 0, strlen($prtValue) - 1);
                    $model15[$i]["attr_value"] = $prtValue;
                }
                $url = $this->findApiUrl() . "/ptdt/product-shelf/new-get-prt-attr?vco_busid=" . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
                return $this->render('_pdtseleves-one', [
                    'data' => $data,
                    'isUpOrDown' => $isUpOrDown,
                    'model' => $model,
                    'mdl' => $mdl,
                    'img' => $img,
                    'model3' => $model3,
                    'model4' => $model4,
                    'model5' => $model5,
                    'model6' => $model6,
                    'model7' => $model7,
                    'model8' => $model8,
                    'model9' => $model9,
                    'model10' => $model10,
                    'model11' => $model11,
                    'model12' => $model12,
                    'model13' => $model13,
                    'model14' => $model14,
                    'model15' => $model15,
                    'id' => $id
                ]);
                break;
            case 'pdtsel':
            case 'pdtdowmsel':
            case 'pdtreupshelf':
                $isUpOrDown = 0;
                if ($result['bus_code'] == 'pdtsel') {
                    $isUpOrDown = 0;
                } else if ($result['bus_code'] == 'pdtdowmsel') {
                    $isUpOrDown = 1;
                } else {
                    $isUpOrDown = 3;
                }
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-pdt-info-shelves?vco_busid=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-rel-pdt-shelves?vco_busid=" . $result['vco_busid'];
                $model2 = Json::decode($this->findCurl()->get($url));
                $mdl = "";
                if (isset($model2)) {

                    foreach ($model2 as $value) {
                        $mdl .= $value['pdt_name'] . ';';
                    }
                }
                $mdl = substr($mdl, 0, strlen($mdl) - 1);
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-pdt-img-shelves?vco_busid=" . $result['vco_busid'];
                $model3 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-part-no-info-shelves?vco_busid=" . $result['vco_busid'];
                $model4 = Json::decode($this->findCurl()->get($url));
                $img = [];
                foreach ($model3 as $value) {
                    $img[] = Html::img(\Yii::$app->params["FtpConfig"]["httpIP"] . $value["fl_new"], ["style" => "width:60px;height:60px;margin:10px 0px;", "class" => "imgGroup"]);
                }
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-price-shelves?vco_busid=" . $result['vco_busid'];
                $model5 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-stock-shelves?vco_busid=" . $result['vco_busid'];
                $model6 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-ship-shelves?vco_busid=" . $result['vco_busid'];
                $model7 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-pack-shelves?vco_busid=" . $result['vco_busid'];
                $model8 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-deliv-shelves?vco_busid=" . $result['vco_busid'];
                $model9 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-machine-shelves?vco_busid=" . $result['vco_busid'];
                $model10 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-warr-shelves?vco_busid=" . $result['vco_busid'];
                $model11 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-pdt-details-shelves?vco_busid=" . $result['vco_busid'];
                $model12 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/is-yn-machine-shelves?vco_busid=" . $result['vco_busid'];
                $model13 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-machine-details-shelves?vco_busid=" . $result['vco_busid'];
                $model14 = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-attr-name?vco_busid=" . $result['vco_busid'];
                $model15 = Json::decode($this->findCurl()->get($url));
                for ($i = 0; $i < count($model15, 0); $i++) {
                    $prtValue = "";
                    $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-value?vco_busid=" . $result['vco_busid'] . "&catgattrid=" . $model15[$i]["catg_attr_id"];
                    $model16 = Json::decode($this->findCurl()->get($url));
                    foreach ($model16 as $value) {
                        $prtValue .= $value["attr_value"] . ",";
                    }
                    $prtValue = substr($prtValue, 0, strlen($prtValue) - 1);
                    $model15[$i]["attr_value"] = $prtValue;
                }
                $url = $this->findApiUrl() . "/ptdt/product-shelf/new-get-prt-attr?vco_busid=" . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-res?vco_busid=" . $result['vco_busid'];
                $downreason = Json::decode($this->findCurl()->get($url));
                $newFileName = substr($model4['file_new'], 2, 6);
                return $this->render('_pdtseleves-one', [
                    'newFileName' => $newFileName,
                    'downreason' => $downreason,
                    'data' => $data,
                    'isUpOrDown' => $isUpOrDown,
                    'model' => $model,
                    'mdl' => $mdl,
                    'img' => $img,
                    'model3' => $model3,
                    'model4' => $model4,
                    'model5' => $model5,
                    'model6' => $model6,
                    'model7' => $model7,
                    'model8' => $model8,
                    'model9' => $model9,
                    'model10' => $model10,
                    'model11' => $model11,
                    'model12' => $model12,
                    'model13' => $model13,
                    'model14' => $model14,
                    'model15' => $model15,
                    'id' => $id
                ]);
                break;
            case 'order':
                $url = $this->findApiUrl() . "sale/sale-trade-order/log-order-detail?id=" . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
                return $this->render('_reprice', [
                    'data' => $data,
                    'id' => $id
                ]);
                break;
            case 'saqut':
                $url = $this->findApiUrl() . '/sale/sale-quoted-order/view?id=' . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
                $verify = $this->getVerify($id, $data['info']['price_type']);//審核信息
                $seller = $this->getSeller($data['info']['seller']);
                $credits = $this->getCreditLimit($data['info']['customer']['cust_id'], $data['info']['cur_id']);
//        dumpE($verify);
                if (!empty($token)) {
                    Yii::$app->user->logout();
                }
                return $this->render('_quoteorder', [
                    'data' => $data['info'],
                    'dt' => $data['dt'],
                    'seller' => $seller,
                    'credits' => $credits,
                    'id' => $id,
                    'token' => $token
                ]);
                break;
            case 'refund':
                $url = $this->findApiUrl() . '/sale/ord-refund/view?id=' . $result['vco_busid'];
                $data = Json::decode($this->findCurl()->get($url));
                if (!empty($token)) {
                    Yii::$app->user->logout();
                }
                return $this->render('_orderefund', [
                    'data' => $data['refund'],
                    'dt' => $data['dt'],
                    'id' => $id,
                    'token' => $token
                ]);
                break;
            case 'ordlgst':
                $url = $this->findApiUrl() . "/warehouse/logisticsorder/log-order-info?id=" . $result['vco_busid'];
                $model = Json::decode($this->findCurl()->get($url));
                $verify = $this->getVerify($result['vco_busid'], 61);
                return $this->render('_ordlgst', [
                    'model' => $model,
                    'verify' => $verify,
                    'id' => $id
                ]);
                break;
            case 'ordrecives':
                $url = $this->findApiUrl()."sale/sale-bank-sta/get-data-by-rbo-id?rbo_id=" . $result['vco_busid'];//获取rbo_id的所有审核记录
                $rboData = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "sale/sale-bank-sta/get-trans-info?transid=" . $rboData[0]['TRANSID'];//单笔流水信息
                $oneTransInfo = Json::decode($this->findCurl()->get($url));
                $orderList = [];
                $orderlist = "";
                $orderSum = 0;//订单总金额
                $money = 0;//订单金额
                foreach ($rboData as $value) {
                    array_push($orderList, $value['ord_no']);
                    if ($value['pay_type'] == 1) {
                        $money = $value['stag_cost'];
                    } else {
                        $money = $value['req_tax_amount'];
                    }
                    $orderSum = ($orderSum * 1000 + $money * 1000) / 1000;
                }
                $orderSum = sprintf("%.3f", $orderSum);
                $orderList = array_unique($orderList);
                if (count($orderList) == 1) {
                    $orderlist .= $orderList[0];
                } else {
                    for ($i = 0; $i < count($orderList); $i++) {
                        if ($i == count($orderList) - 1) {
                            $orderlist .= $orderList[$i];
                        } else {
                            $orderlist .= $orderList[$i] . ";";
                        }
                    }
                }
                $remarks = $rboData[0]['remark'];
                if ($remarks == "null") {
                    $remarks = "";
                }
                return $this->render('_ordrecives',['oneTransInfo' => $oneTransInfo, 'orderSum' => $orderSum, 'orderlist' => $orderlist, 'remarks' => $remarks, 'vco_id' =>$id]);
                break;
            case 'ordrecives':
                $url = $this->findApiUrl()."sale/sale-bank-sta/get-data-by-rbo-id?rbo_id=" . $result['vco_busid'];//获取rbo_id的所有审核记录
                $rboData = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . "sale/sale-bank-sta/get-trans-info?transid=" . $rboData[0]['TRANSID'];//单笔流水信息
                $oneTransInfo = Json::decode($this->findCurl()->get($url));
                $orderList = [];
                $orderlist = "";
                $orderSum = 0;//订单总金额
                $money = 0;//订单金额
                foreach ($rboData as $value) {
                    array_push($orderList, $value['ord_no']);
                    if ($value['pay_type'] == 1) {
                        $money = $value['stag_cost'];
                    } else {
                        $money = $value['req_tax_amount'];
                    }
                    $orderSum = ($orderSum * 1000 + $money * 1000) / 1000;
                }
                $orderSum = sprintf("%.3f", $orderSum);
                $orderList = array_unique($orderList);
                if (count($orderList) == 1) {
                    $orderlist .= $orderList[0];
                } else {
                    for ($i = 0; $i < count($orderList); $i++) {
                        if ($i == count($orderList) - 1) {
                            $orderlist .= $orderList[$i];
                        } else {
                            $orderlist .= $orderList[$i] . ";";
                        }
                    }
                }
                $remarks = $rboData[0]['remark'];
                if ($remarks == "null") {
                    $remarks = "";
                }
                return $this->render('_ordrecives',['oneTransInfo' => $oneTransInfo, 'orderSum' => $orderSum, 'orderlist' => $orderlist, 'remarks' => $remarks, 'vco_id' =>$id]);
                break;
            case 'batchreceipts':
                $url=$this->findApiUrl()."sale/sale-bank-sta/get-trans-batch?rbo_id=".$result['vco_busid'];
                $data=Json::decode($this->findCurl()->get($url));
                for($j=0;$j<count($data);$j++)
                {
                    $url=$this->findApiUrl()."sale/sale-bank-sta/get-order-no?transid=".$data[$j]['TRANSID']."&rboid=".$result['vco_busid'];
                    $orderno=Json::decode($this->findCurl()->get($url));
                    $order_no="";
                    for($i=0;$i<count($orderno);$i++)
                    {
                        if($i==count($orderno)-1)
                        {
                            $order_no.=$orderno[$i]['ord_no'];
                        }
                        else{
                            $order_no.=$orderno[$i]['ord_no'].",";
                        }
                    }
                    $data[$j]['ord_no']=$order_no;
                    $url=$this->findApiUrl()."sale/sale-bank-sta/get-batch-money?transid=".$data[$j]['TRANSID']."&rboid=".$result['vco_busid'];
                    $anyMoney=Json::decode($this->findCurl()->get($url));
                    $data[$j]['stag_cost']=$anyMoney[0]['stag_cost'];
                    $url=$this->findApiUrl()."sale/sale-bank-sta/get-batch-remark?transid=".$data[$j]['TRANSID']."&rboid=".$result['vco_busid'];
                    $remark=Json::decode($this->findCurl()->get($url));
                    $data[$j]['remark']=$remark[0]['remark'];
                }
                return $this->render('batch-revices',['data'=>$data,'vco_id'=>$id]);
                break;
        }
    }

    public function actionVerifyAll($str)
    {
        $this->layout = '@app/views/layouts/ajax';
        $arr = explode(',', $str);
        foreach ($arr as $key => $val) {
            $result = $this->getModels($val);
            $results[] = $result;
        }
        return $this->render('verify-all', [
            'results' => Json::encode($results)
        ]);
    }

    /**
     * 送审
     * @return string
     */
    public function actionVerifyRecord()
    {
        $post = Yii::$app->request->post();
        $url = $this->findApiUrl() . $this->_url . "verify-record";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $data = Json::decode($curl->post($url));
        if ($data['status']) {
            return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "發生未知錯誤，新增失敗", "flag" => 0]);
        }
    }
    //采购信息确认页面的提交送审(用于采购前置作业的采购)
    public function actionPrchVerify($type, $id, $url = null)
    {
        if ($post = Yii::$app->request->post()) {
            $id = explode(',', $id);
            foreach ($id as $item) {
                $post['id'] = $item;    //单据ID
                $post['type'] = $type;  //审核流类型
                $post['staff'] = Yii::$app->user->identity->staff_id;//送审人ID
                $verifyUrl = $this->findApiUrl() . $this->_url . "verify-record";
                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
                $data = Json::decode($curl->post($verifyUrl));
                if (!$data['status']) {
                    throw  new \Exception('单据ID:' . $item . '送审失败!');
                }
            }
            if (!empty($url)) {
                return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1, "url" => $url]);
            } else {
                return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1]);
            }
        }
        $urls = $this->findApiUrl() . $this->_url . "reviewer?type=" . $type . '&staff_id=' . Yii::$app->user->identity->staff_id;
        $review = Json::decode($this->findCurl()->get($urls));
        return $this->renderAjax('reviewer', [
            'review' => $review,
        ]);
    }


    /*
     * 通过审核
     */
    public function actionAuditPass()
    {
//        $token = Yii::$app->request->get('token');
//        $post = Yii::$app->request->post();
//        $model = new Upload();
//        $model->file = yii\web\UploadedFile::getInstances($model, 'file');
//        foreach ($model->file as $key => $value) {
//            if ($value && $model->validate(['file' . [$key]])) {
//                $value->saveAs('uploads/creditApply/' . base64_encode($value->baseName) . '.' . $value->extension);
//                $a = $value->baseName . '.' . $value->extension;
//            }
//            $post['CrmCreditLimit'][$key]['accessory'] = serialize($a);
//        }
//        $url = $this->findApiUrl() . $this->_url . "audit-pass";
//        $post['reviewer']['ip'] = Yii::$app->request->getUserIP();
//        $post['url'] = Yii::$app->request->getHostInfo() . Yii::$app->request->getBaseUrl() . '/system/verify-record/verify?id=';
//        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
//        $data = Json::decode($curl->post($url));

        $token = Yii::$app->request->get('token');
        $post = Yii::$app->request->post();
        $url = $this->findApiUrl() . $this->_url . "audit-pass";
        $post['ip'] = Yii::$app->request->getUserIP();
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $data = Json::decode($curl->post($url));
//        dumpE($data);
        if (!empty($token)) {
            Yii::$app->user->logout();
            if ($data['status']) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "单据审核通过", "flag" => 2]);
            } else {
                return Json::encode(['msg' => $data['msg'] . "发生未知错误，审核失敗", "flag" => 0]);
            }
        } else {
            if ($data['status']) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "单据审核通过", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $data['msg'] . "发生未知错误，审核失敗", "flag" => 0]);
            }
        }

    }

    /*
    * 审核驳回
    */
    public function actionAuditReject()
    {
        $token = Yii::$app->request->get('token');
        $post = Yii::$app->request->post();
        $url = $this->findApiUrl() . $this->_url . "audit-reject";
        $post['ip'] = Yii::$app->request->getUserIP();
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $data = Json::decode($curl->post($url));
        if (!empty($token)) {
            Yii::$app->user->logout();
            if ($data['status']) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "单据驳回成功", "flag" => 2]);
            } else {
                return Json::encode(['msg' => "发生未知错误，审核失敗", "flag" => 0]);
            }
        } else {
            if ($data['status']) {
                SystemLog::addLog($data['data']);
                return Json::encode(['msg' => "单据驳回成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，审核失敗" . $data['msg'], "flag" => 0]);
            }
        }
    }


    //通过弹窗
    public function actionPassOpinion()
    {
        $params = Yii::$app->request->queryParams;
        $ids = $params['id'];
//        dumpE($params);
        $this->layout = '@app/views/layouts/ajax';
        return $this->render('pass-opinion', [
            'id' => $ids,
            'type' => $params['hvinvtype'],
            'limit' => $params['LCrmCreditLimit'],
            'apply' => $params['LCrmCreditApply'],
        ]);
    }

    public function actionOpinion()
    {
        $params = Yii::$app->request->queryParams;
        $ids = $_GET['id'];
        $this->layout = '@app/views/layouts/ajax';
        return $this->render('opinion', [
            'id' => $ids,
            'type' => $params['hvinvtype'],
            'limit' => $params['LCrmCreditLimit'],
        ]);
    }


    public function actionInformCount()
    {
        $url = $this->findApiUrl() . "/system/verify-record/inform-count?id=" . Yii::$app->user->identity->staff_id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }


    /*
     * 单据类别
     */
    public function getBusinessType()
    {
        $url = $this->findApiUrl() . $this->_url . "business-type";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function getModels($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    public function getModelsStatus($id, $uid)
    {
        $url = $this->findApiUrl() . $this->_url . "verify-child-status?id=" . $id . '&uid=' . $uid;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
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

    private function getCompared($id, $i)
    {
        if ($id != null && $i === null) {
            $url = $this->findApiUrl() . "/ptdt/firm-report/firm-compared?id=" . $id;
            $lists = Json::decode($this->findCurl()->get($url));
        } else {
            $url = $this->findApiUrl() . "/ptdt/firm-report/firm-compared?id=" . $id . "&i=" . $i;
            $lists = Json::decode($this->findCurl()->get($url));
        }
        return $lists;
    }

    // 需求改变 没有取消审核功能了
    public function actionCancelCheck()
    {
        $url = $this->findApiUrl() . $this->_url . "cancel-check";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $data = json::decode($this->findCurl()->get($url), true);
        if ($data['status']) {
            return Json::encode(['msg' => "取消送审成功", "flag" => 1]);
        } else {
            return Json::encode(['msg' => $data['msg'], "flag" => 0]);
        }
    }

    //料号自提仓库
    public function actionGetPrtWm($id, $isUpOrDown)
    {
        $result = $this->getModels($id);
        if ($isUpOrDown == 2) {
            $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-wm?vco_busid=" . $result['vco_busid'];
        } else {
            $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-wm-shelves?vco_busid=" . $result['vco_busid'];
        }
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $model = $this->findCurl()->get($url);
        return $model;
    }

    //料号价格信息
    public function actionGetPrtPrice($id)
    {
        $result = $this->getModels($id);
        $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-price?vco_busid=" . $result['vco_busid'];
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $model = $this->findCurl()->get($url);
        return $model;
    }

    //生成料号的规格参数
    public function actionBuildPrtAttr($id)
    {
        $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-attr?prt_pkid=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        $selBox = "<table class='no-border' style='width: 400px;border-collapse:separate; border-spacing:0px 15px;'>";
        foreach ($model as $value) {
            if ($value["attr_type"] == 0) {
                $selBox .= "<tr class='no-border'>";
                $selBox .= "<td class='no-border' style='width: 39px;text-align: right;'>" . $value["attr_name"] . "：</td>";
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-attr-val?catgattrid=" . $value["catg_attr_id"];
                $attrValue = Json::decode($this->findCurl()->get($url));
                $selBox .= "<td class='no-border' style='width: 30px;;text-align: left;'>";
                foreach ($attrValue as $value) {
                    $selBox .= "<input type='checkbox' style='margin-left: 10px;' disabled='disabled'/>" . $value["attr_value"];

                }
                $selBox .= "</td>";
                $selBox .= "</tr>";
            } else if ($value["attr_type"] == 1) {
                $selBox .= "<tr class='no-border''>";
                $selBox .= "<td class='no-border' style='width: 39px;text-align: right;'>" . $value["attr_name"] . "：</td>";
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-attr-val?catgattrid=" . $value["catg_attr_id"];
                $attrValue = Json::decode($this->findCurl()->get($url));
                $selBox .= "<td class='no-border' style='width: 30px;;text-align: left;'>";
                foreach ($attrValue as $value) {
                    $selBox .= "<input type='radio' style='margin-left: 10px;' disabled='disabled'/>" . $value["attr_value"];

                }
                $selBox .= "</td>";
                $selBox .= "</tr>";
            } else if ($value["attr_type"] == 2) {
                $selBox .= "<tr class='no-border'>";
                $selBox .= "<td class='no-border' style='width: 39px;text-align: right;'>" . $value["attr_name"] . "：</td>";
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-attr-val?catgattrid=" . $value["catg_attr_id"];
                $attrValue = Json::decode($this->findCurl()->get($url));
                $selBox .= "<td class='no-border' style='width: 30px;'><select style='width: 120px;' disabled='disabled'>";
                foreach ($attrValue as $value) {
                    $selBox .= "<option>" . $value["attr_value"] . "</option>";
                }
                $selBox .= "</select></td>";
                $selBox .= "</tr>";
            } else {
                $selBox .= "<tr class='no-border'>";
                $selBox .= "<td class='no-border' style='width: 39px;text-align: right;'>" . $value["attr_name"] . "：</td>";
                $url = $this->findApiUrl() . "/ptdt/product-shelf/get-prt-attr-val?catgattrid=" . $value["catg_attr_id"];
                $attrValue = Json::decode($this->findCurl()->get($url));
                $selBox .= "<td class='no-border' style='width:30px'><input type='text' style='width: 120px;' disabled='disabled'/></td>";
                $selBox .= "</tr>";
            }
        }
        return $selBox;
    }

    //批量送审(客户代码申请)
    public function actionNewReviewer($type, $id, $url = null)
    {
        if ($post = Yii::$app->request->post()) {
            $id = explode('-', $id);
            unset($id[count($id) - 1]);
            foreach ($id as $item) {
                $post['id'] = $item;    //单据ID
                $post['type'] = $type;  //审核流类型
                $post['staff'] = Yii::$app->user->identity->staff_id;//送审人ID
                $verifyUrl = $this->findApiUrl() . $this->_url . "verify-record";
                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
                $data = Json::decode($curl->post($verifyUrl));
                if (!$data['status']) {
                    throw  new \Exception('单据ID:' . $item . '送审失败!');
                }
            }
            if (!empty($url)) {
                return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1, "url" => $url]);
            } else {
                return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1]);
            }
        }
        $urls = $this->findApiUrl() . $this->_url . "reviewer?type=" . $type . '&staff_id=' . Yii::$app->user->identity->staff_id;
        $review = Json::decode($this->findCurl()->get($urls));
        return $this->renderAjax('reviewer', [
            'review' => $review,
        ]);
    }

    //客户代码申请提交时送审
    public function actionReviewerOne($type, $id, $url = null)
    {
        if ($post = Yii::$app->request->post()) {
            $post['id'] = $id;    //单据ID
            $post['type'] = $type;  //审核流类型
            $post['staff'] = Yii::$app->user->identity->staff_id;//送审人ID
            $verifyUrl = $this->findApiUrl() . $this->_url . "verify-record";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($verifyUrl));
            if ($data['status']) {
                if (!empty($url)) {
                    return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1, "url" => $url . "&iss=1"]);
                } else {
                    return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1]);
                }
            } else {
                return Json::encode(['msg' => $data['msg'] . ' 送审失败！', "flag" => 0]);
            }
        }
        $urls = $this->findApiUrl() . $this->_url . "reviewer?type=" . $type . '&staff_id=' . Yii::$app->user->identity->staff_id;
        $review = Json::decode($this->findCurl()->get($urls));
        return $this->renderAjax('new-reviewer', [
            'review' => $review,
        ]);
    }

    //查看审核状态
    public function getVerify($id, $type)
    {
        $url = $this->findApiUrl() . $this->_url . "find-verify?id=" . $id . "&type=" . $type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    //盘点审核id
    function getcltype()
    {
        $_url = $this->findApiUrl() . $this->_url . "checklist-type";
        return Json::decode($this->findCurl()->get($_url));
    }
}


