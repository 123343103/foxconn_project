<?php

namespace app\modules\sale\controllers;

use app\models\Upload;
use app\modules\common\models\BsBusinessType;
use app\widgets\ueditor\Ftp;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Json;
use app\modules\system\models\SystemLog;
use yii\helpers\Url;
use yii\web\UploadedFile;

class SaleCustOrderController extends \app\controllers\BaseController
{
    private $_url = 'sale/sale-cust-order/'; //对应api
    public $p_url = '/sale/sale-cust-order/index'; //父级菜单列表

    public function beforeAction($action)
    {
        $this->list = array_merge($this->list, [
            "/sale/sale-cust-order/create" => "btn_add",
            "/sale/sale-cust-order/to-quoted" => "btn_quote",
            "/sale/sale-cust-order/update" => "btn_mody",
        ]);
        $this->ignorelist = array_merge($this->ignorelist, [
            "/sale/sale-cust-order/get-product",
        ]);
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = json::decode($this->findCurl()->get($url), true);
            foreach ($dataProvider['rows'] as $key => $val) {
                $dataProvider['rows'][$key]['saph_code'] = '<a href="' . Url::to(['view', 'id' => $val['req_id']]) . '">' . $val['saph_code'] . '</a>';
            }
            return Json::encode($dataProvider);
        }
        $downList = $this->getDownList();
        $columns = $this->getField("/sale/sale-cust-order/index");
        $child_columns = $this->getField("/sale/sale-cust-order/get-product");
        $businessType = $this->findCurl()->get($this->findApiUrl() . $this->_url . "business-type");
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->exportFiled(Json::decode($this->findCurl()->get($url))['rows']);
        }
        return $this->render('index', [
            'downList' => $downList,
            'search' => $queryParam['ReqInfoSearch'],
            'columns' => $columns,
            'child_columns' => $child_columns,
            'businessType' => $businessType
        ]);
    }

    public function actionList()
    {
        $url = $this->findApiUrl() . $this->_url . "order-detail-list";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = json::decode($this->findCurl()->get($url), true);
//        dumpE($dataProvider);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
//            return $dataProvider;
            foreach ($dataProvider['rows'] as $key => $val) {
                $dataProvider['rows'][$key]['saph_code'] = '<a href="' . Url::to(['view', 'id' => $val['saph_id']]) . '">' . $val['saph_code'] . '</a>';
            }
            return Json::encode($dataProvider);
        }
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->export(Json::decode($this->findCurl()->get($url))['rows']);
        }
        $downList = $this->getDownList();
        $columns = $this->getField("/sale/sale-cust-order/list");
//        dumpE($columns);
        return $this->render('list', [
            'downList' => $downList,
            'search' => $queryParam['ReqDtSearch'],
            'columns' => $columns,
        ]);
    }

    // 报价单查询明细列表
    public function actionQuotedDetailList()
    {
        $url = $this->findApiUrl() . $this->_url . "quoted-detail-list";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->exportFiled(Json::decode($this->findCurl()->get($url))['rows']);
        }
        $downList = $this->getDownList();
        $columns = $this->getField("/sale/sale-cust-order/quoted-detail-list");
//        dumpE($dataProvider);
        return $this->render('list', [
            'downList' => $downList,
            'columns' => $columns,
            'search' => $queryParam['SaleCustrequireLSearch'],
        ]);
    }

    public function actionCreate()
    {
        $uploadModel = new Upload();
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['ReqInfo']['nwer'] = Yii::$app->user->identity->staff_id;
            $post['ReqInfo']['nw_ip'] = Yii::$app->request->userIP;
            $post['ReqInfo']['user_id'] = Yii::$app->user->identity->user_id;
//            dumpE($post);


            $uploadModel->custAttach = UploadedFile::getInstances($uploadModel, 'custAttach');
//            dumpE($uploadModel->custAttach);
//            return json::encode($uploadModel->validate(['custAttach']));
            if (!empty($uploadModel->custAttach)) {
                if (!$uploadModel->validate(['custAttach'])) {
                    return Json::encode(['msg' => current($uploadModel->getFirstErrors()), "flag" => 0]);
                }
                $randStr = '_' . substr(time(), 5);
                $custAttachName = [];

                $father = Yii::$app->ftpPath['ORD']['father'];
                $pathlcn = Yii::$app->ftpPath['ORD']['Req'];
                $uploadaddress = date("Ymd");

                $ftp = new Ftp();
                $fullDir = trim($father, "/") . "/" . trim($pathlcn, "/") . "/" . $uploadaddress;
                if (!$ftp->ftp_dir_exists($fullDir)) {
                    $ftp->mkdirs($fullDir);
                }
                foreach ($uploadModel->custAttach as $k => $attach) {
                    $fileName = $attach->name;
                    $newfileName = $uploadaddress . "_" . (time() + $k) . "." . pathinfo($fileName, PATHINFO_EXTENSION);
                    $tempName = $attach->tempName;
                    $tmpfile = \Yii::$app->getRuntimePath() . "\\tmpfile" . time();
                    $tmpfile = str_replace("\\", "/", $tmpfile);
                    $dest = $fullDir . "/" . trim($newfileName, "/");
//                    dumpE(pathinfo($fileName, PATHINFO_EXTENSION));
                    if (move_uploaded_file($tempName, $tmpfile) && $ftp->put($dest, $tmpfile) && @unlink($tmpfile)) {
                        $post['Files'][$k]["file_old"] = $fileName;
                        $post['Files'][$k]["file_new"] = $newfileName;
                    } else {
                        return Json::encode(['msg' => "上传文件失败", "flag" => 0]);
                    }
                }
            }

            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
//            dumpE($data);
            if ($data['status'] == 1) {
                return Json::encode(['msg' => "创建成功", "flag" => 1, "url" => Url::to(['view']).'?id='.$data['data']]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        } else {
            $downList = $this->getDownList();
            $seller = Json::decode($this->getSeller(Yii::$app->user->identity->staff->staff_code));
            if ($seller["isrule"] == 1) { //如果自己是客户经理人
                $seller["leader"] = $seller["staff_code"] . " " . $seller["staff"]["name"];
            }
            $seller['company_id'] = Yii::$app->user->identity->company_id;
            $seller['user_id'] = Yii::$app->user->identity->user_id;
//        dumpE($seller);
            return $this->render('create', ['downList' => $downList, 'seller' => $seller
            ]);
        }
    }

    public function actionUpdate($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $uploadModel = new Upload();
//dumpE($post);
            $uploadModel->custAttach = UploadedFile::getInstances($uploadModel, 'custAttach');

//            return json::encode($uploadModel->validate(['custAttach']));
            if (!empty($uploadModel->custAttach)) {
                if (!$uploadModel->validate(['custAttach'])) {
                    return Json::encode(['msg' => current($uploadModel->getFirstErrors()), "flag" => 0]);
                }

                $father = Yii::$app->ftpPath['ORD']['father'];
                $pathlcn = Yii::$app->ftpPath['ORD']['Req'];
                $uploadaddress = date("Ymd");

                $ftp = new Ftp();
                $fullDir = trim($father, "/") . "/" . trim($pathlcn, "/") . "/" . $uploadaddress;
                if (!$ftp->ftp_dir_exists($fullDir)) {
                    $ftp->mkdirs($fullDir);
                }
                foreach ($uploadModel->custAttach as $k => $attach) {
                    $fileName = $attach->name;
                    $newfileName = $uploadaddress . "_" . date('Ymd') . (time() + $k) . "." . pathinfo($fileName, PATHINFO_EXTENSION);
                    $tempName = $attach->tempName;
                    $tmpfile = \Yii::$app->getRuntimePath() . "\\tmpfile" . time();
                    $tmpfile = str_replace("\\", "/", $tmpfile);
                    $dest = $fullDir . "/" . trim($newfileName, "/");
//                    dumpE(pathinfo($fileName, PATHINFO_EXTENSION));
                    if (move_uploaded_file($tempName, $tmpfile) && $ftp->put($dest, $tmpfile) && @unlink($tmpfile)) {
                        $post['Files'][$k]["file_old"] = $fileName;
                        $post['Files'][$k]["file_new"] = $newfileName;
                    } else {
                        return Json::encode(['msg' => "上传文件失败", "flag" => 0]);
                    }
                }
            }

            $post['ReqInfo']['opper'] = Yii::$app->user->identity->staff_id;
            $post['ReqInfo']['opp_id'] = Yii::$app->request->userIP;
            $post['ReqInfo']['user_id'] = Yii::$app->user->identity->user_id;
//            dumpE($post);
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
//                        dumpE($data);
            if ($data['status'] == 1) {
                return Json::encode(['msg' => "修改成功", "flag" => 1, "url" => Url::to(['view']) . "?id=" . $id]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        } else {
            $murl = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $models = Json::decode($this->findCurl()->get($murl));
            $quotedHModel = $models[0];//主订单
            $quotedLModel = $models[1];//子订单
            $seller = $models[2];//销售员信息
            $customer = $models[3];//客户信息
//            $credits = $models[7];//总價格信息
            $ReqPay = $models[4];//总價格信息
            $files = $models[5];//总價格信息
            $quotedHModel["delivery_addr"] = $quotedHModel["ba_id"];//总價格信息

            $downList = $this->getDownList();
            $title_district = $this->getAllDistrict($quotedHModel["invoice_Title_AreaID"]);
            $send_district = $this->getAllDistrict($quotedHModel["invoice_AreaID"]);

            if ($seller["isrule"] == 1) { //如果自己是客户经理人
                $seller["leader"] = $seller["staff_code"] . " " . $seller["staff"]["name"];
            }
            $seller['company_id'] = Yii::$app->user->identity->company_id;
            $seller['user_id'] = Yii::$app->user->identity->user_id;
//            dumpE($quotedHModel);
            return $this->render('update', ['downList' => $downList,
                'quotedHModel' => $quotedHModel, 'quotedLModel' => $quotedLModel, 'ReqPay' => $ReqPay,
                'seller' => $seller,
                'title_district' => $title_district,
                'send_district' => $send_district,
                'files' => $files,
//                'credits' => $credits,
                'customer' => $customer
            ]);
        }
    }

    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $data = Json::decode($this->findCurl()->get($url));
        if ($data['status'] == 1) {
            return Json::encode(['msg' => "删除成功", "flag" => 1, "url" => Url::to(['index'])]);
        } else {
            return Json::encode(['msg' => $data['msg'], "flag" => 0]);
        }
    }

    public function actionView($id)
    {
        $url = $this->findApiUrl() . $this->_url . "order-detail?id=" . $id;
        $data = Json::decode($this->findCurl()->get($url));
        $businessType = $this->findCurl()->get($this->findApiUrl() . $this->_url . "business-type");

        if ($data["seller"]["isrule"] == 1) { //如果自己是客户经理人
            $data["seller"]["leader"] = $data["seller"]["staff_code"] . " " . $data["seller"]["staff"]["name"];
        }
//        dumpE($data);
        return $this->render('view', [
            'data' => $data,
            'businessType' => $businessType,
        ]);
    }

    // 转报价
    public function actionToQuoted($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();

            $post['ReqInfo']['opper'] = Yii::$app->user->identity->staff_id;
            $post['ReqInfo']['user_id'] = Yii::$app->user->identity->user_id;
//            dumpE($post);
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
//                        dumpE($data);
            if ($data['status'] == 1) {
                $url = $this->findApiUrl() . $this->_url . "to-quoted?id=" . $id;
                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
                $data2 = Json::decode($curl->post($url));
                if ($data2['status'] == 1) {
                    return Json::encode(['msg' => "转报价成功", "flag" => 1, "data" => $data2["data"], "url" => Url::to(['index'])]);
                } else {
                    return Json::encode(['msg' => "保存成功,转报价时错误," . $data2['msg'], "flag" => 0]);
                }
            } else {
                return Json::encode(['msg' => "保存时错误," . $data['msg'], "flag" => 0]);
            }
        } else {
            $murl = $this->findApiUrl() . $this->_url . "order-list?id=" . $id;
            $quotedLModel = Json::decode($this->findCurl()->get($murl));//子订单

            $downList = $this->getDownList();

            $url = $this->findApiUrl() . $this->_url . "order-detail?id=" . $id;
            $data = Json::decode($this->findCurl()->get($url));
            if ($data["seller"]["isrule"] == 1) { //如果自己是客户经理人
                $data["seller"]["leader"] = $data["seller"]["staff_code"] . " " . $data["seller"]["staff"]["name"];
            }
            $ReqPay = $data["pay"];
//            dumpE($quotedLModel);
            return $this->render('to-quoted', [
                'downList' => $downList,
                'quotedLModel' => $quotedLModel,
                'ReqPay' => $ReqPay,
                'data' => $data,
//                'credits' => $credits,
            ]);
        }
    }

    //选择客户
    public function actionSelectCustomer()
    {
        $params = Yii::$app->request->queryParams;
        $params['companyId'] = Yii::$app->user->identity->company_id;
        $url = $this->findApiUrl() . $this->_url . 'select-customer';
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        if (Yii::$app->request->isAjax) {
            return $this->findCurl()->get($url);
        }
        return $this->renderAjax('select-customer', ['params' => $params]);
    }

    // 获取子表商品信息
    public function actionGetProduct($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-product?id=" . $id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        return $this->findCurl()->get($url);
    }

    // 选择商品
    public function actionSelectProduct()
    {
        $params = Yii::$app->request->queryParams;
        $urls = $this->findApiUrl() . "system/display-list/get-url-field?url=/sale/sale-cust-order/create&user=" . Yii::$app->user->identity->user_id . "&type=";
        $columns = Json::decode($this->findCurl()->get($urls));
        $url = $this->findApiUrl() . $this->_url . 'select-product';
        $downList = $this->getDownList();
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        if (Yii::$app->request->isAjax) {
            return $this->findCurl()->get($url);
        }
        return $this->renderAjax('select-product', ['params' => $params, 'columns' => $columns, 'downList' => $downList]);
    }

    /**
     * 选择地址
     * @param integer $custId required
     * @param string $type
     */
    public function actionSelectAddress($custId, $type = null)
    {
        $countrys = Json::decode($this->getCountry());
        $params = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . 'select-address';
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        $address = Json::decode($this->findCurl()->get($url))["rows"];
        return Json::encode($address);
//        return $this->renderAjax('select-address', ['address' => $address, 'countrys' => $countrys, 'params' => $params]);
    }

    // 添加地址
    public function actionAddAddress()
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
//            dumpE($post);
            $url = $this->findApiUrl() . $this->_url . "add-address";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
//            dumpE($data);
            if ($data['status'] == 1) {
                return Json::encode(['msg' => "创建成功", "flag" => 1, "data" => $data['data']]);
            } else {
                return Json::encode(['msg' => $data["msg"], "flag" => 0]);
            }
        } else {
            $params = Yii::$app->request->queryParams;
//            dumpE($params);
            $countrys = Json::decode($this->getCountry());
            $this->layout = "@app/views/layouts/ajax.php";
            return $this->render('address', [
                'countrys' => $countrys,
                'params' => $params,
            ]);
        }
    }

    // 添加地址
    public function actionEditAddress($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "edit-address?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                return Json::encode(['msg' => "修改成功", "flag" => 1, "data" => $data['data']]);
            } else {
                return Json::encode(['msg' => "修改失败", "flag" => 0]);
            }
        } else {
            $url = $this->findApiUrl() . $this->_url . "address?id=" . $id;
            $address = Json::decode($this->findCurl()->get($url));
            $district = $this->getAllDistrict($address["district"]);
//            dumpE($district);
            $params = Yii::$app->request->queryParams;
            $countrys = Json::decode($this->getCountry());
            $this->layout = "@app/views/layouts/ajax.php";
            return $this->render('address', [
                'countrys' => $countrys,
                'district' => $district,
                'model' => $address,
                'params' => $params,
            ]);
        }
    }

    // 删除地址
    public function actionDelAddress($id)
    {
        $url = $this->findApiUrl() . $this->_url . "del-address?id=" . $id;
        return $this->findCurl()->get($url);
    }

    // 修改默认地址
    public function actionDefaultAddress($id)
    {
        $url = $this->findApiUrl() . $this->_url . "default-address?id=" . $id;
        return $this->findCurl()->get($url);
    }

    // 下拉列表
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "get-down-list";
        return json::decode($this->findCurl()->get($url));
    }

    //获取销售员信息
    public function getSeller($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-seller?id=" . $id;
        return $this->findCurl()->get($url);
    }

    //获取客户帐信额度
    public function actionGetCustCredit($id, $currency)
    {
        $url = $this->findApiUrl() . $this->_url . "get-cust-credit?id=" . $id . "&currency=" . $currency;
        return $this->findCurl()->get($url);
    }

    //获取料号信息
    public function actionGetPdt($pdt_no)
    {
        $url = $this->findApiUrl() . $this->_url . "get-pdt?pdt_no=" . $pdt_no;
        return $this->findCurl()->get($url);
    }

    //获取料号定价
    public function actionGetPrice($pdt_no, $num, $curr)
    {
        $url = $this->findApiUrl() . $this->_url . "get-price?pdt_no=" . $pdt_no . "&num=" . $num . "&curr=" . $curr;
        return $this->findCurl()->get($url);
    }

    //获取运输方式和运费
    public function actionGetFreight($pdt, $num, $addr, $TransType)
    {
        $url = $this->findApiUrl() . $this->_url . "get-freight?pdt=" . $pdt . "&num=" . $num . "&addr=" . $addr . "&TransType=" . $TransType;
        return $this->findCurl()->get($url);
    }

    public function actionGetTranSport($pdt)
    {
        $url = $this->findApiUrl() . $this->_url . "get-tran-sport?pdt=" . $pdt;
        $sports = $this->findCurl()->get($url);
        return $sports;
    }

    //获取国家
    public function getCountry()
    {
        $url = $this->findApiUrl() . $this->_url . "get-country";
        return $this->findCurl()->get($url);
    }

    //验证料号是否存在
    public function actionPdtValidate($id, $attr, $val, $scenario)
    {
        $val = urlencode($val);
        $url = $this->findApiUrl() . "ptdt/product-library/" . "validate";
        $url = $url . "?id={$id}&attr={$attr}&val={$val}&scenario={$scenario}";
        return $this->findCurl()->get($url);
    }

    // 报价单查询列表
    public function actionQuotedList()
    {
        $url = $this->findApiUrl() . $this->_url . "quoted-list";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = $this->findCurl()->get($url);
            return $dataProvider;
        }
        $downList = $this->getDownList();
        $columns = $this->getField("/sale/sale-cust-order/quoted-list");
        $child_columns = $this->getField("/sale/sale-cust-order/get-products");
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->exportFiled(Json::decode($this->findCurl()->get($url))['rows']);
        }
        $businessType = $this->findCurl()->get($this->findApiUrl() . $this->_url . "business-type");
//        dumpE($businessType);
        return $this->render('index', [
            'downList' => $downList,
            'search' => $queryParam['SaleCustrequireHSearch'],
            'columns' => $columns,
            'businessType' => $businessType,
            'child_columns' => $child_columns
        ]);
    }

    // 取消订单/报价单  传入cancelId字符串 单个或者多个逗号串隔开
    public function actionCancel()
    {
        $getData = Yii::$app->request->get();
        $getData['update_by'] = Yii::$app->user->identity->staff_id;
        $url = $this->findApiUrl() . $this->_url . "cancel?" . http_build_query($getData);
        return $data = $this->findCurl()->get($url);
    }

    public function actionCancelBox()
    {
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render('cancel');
    }

    /*根据地址五级获取全部信息*/
    public function getAllDistrict($id)
    {
        $url = $this->findApiUrl() . "crm/crm-customer-info/get-all-district?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*根据地址五级获取全部信息*/
    public function actionGetAllDistrict($id)
    {
        $url = $this->findApiUrl() . "crm/crm-customer-info/get-all-district?id=" . $id;
        return $this->findCurl()->get($url);
    }
    /**
     * 根据动态列导出客户
     * @param $data
     */
    public function export($data)
    {
        $filed = '';
        $filedVal = '';
        $fieldIndex = 1;
        $filedTitle = 'A';
        $fieldArr = [];
        $objPHPExcel = new \PHPExcel();
        $columns = $this->getField(null, true);
        $number = [['field_field' => true, 'field_title' => '序号']];
        $columns = array_merge($number, $columns);
        $excelIndex = '$objPHPExcel->setActiveSheetIndex(0)';
        //获取列
        foreach ($columns as $key => $value) {
            if ($fieldIndex > 24) {
                $fieldIndex = 1;
            }
            //宽度
            $objPHPExcel->getActiveSheet()->getColumnDimension($filedTitle)->setWidth(30);
            //标题垂直居中
            $objPHPExcel->getActiveSheet()->getStyle($filedTitle)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $filed .= '->setCellValue(\'' . $filedTitle . $fieldIndex . '\',\'' . $value['field_title'] . '\')';
            $filedTitle++;
            $fieldArr[$key] = $value['field_field'];
        }
        $filedTitle = 'A';
        eval($excelIndex . $filed . ';');
        foreach ($data as $key => $val) {
            $num = $key + 2;
            foreach ($fieldArr as $v) {
                $field_val = htmlspecialchars_decode(htmlspecialchars_decode(htmlspecialchars_decode(htmlspecialchars_decode($val[$v]))));
                if ($v === true) {
                    $field_val = $key + 1;
                }
                if($v=='sapl_remark'){
                    $objPHPExcel->getActiveSheet()->getColumnDimension($filedTitle)->setWidth(150);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
                }
                $filedVal .= '->setCellValue(\'' . $filedTitle . $num . '\',\' ' . $field_val . '\')';
                $filedTitle++;
            }
            $filedTitle = 'A';
            eval($excelIndex . $filedVal . ';');
            Html::decode($filedVal);
            $filedVal = '';
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
