<?php

namespace app\modules\sale\controllers;

use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

class SaleTradeOrderController extends \app\controllers\BaseController
{
    private $_url = 'sale/sale-trade-order/'; //对应api
    public $p_url = '/sale/sale-trade-order/index'; //父级菜单列表

    public function beforeAction($action)
    {
        $this->list = array_merge($this->list, [
            "/purchase/purchase-apply/create" => "btn_purch",
            "/sale/sale-trade-order/reprice" => "btn_chang_price",
            "/sale/sale-trade-order/reprice-cancel" => "btn_chang_price-cancel",
            "/sale/sale-trade-order/out-note" => "btn_notice",
            "/sale/sale-trade-order/out" => "btn_invoice",
            "/sale/sale-trade-order/order-pay" => "btn_pay",
            "/sale/sale-trade-order/cancel" => "btn_cancle",
            "/sale/sale-trade-order/detail-list" => "btn_detail",
//        "/sale/sale-trade-order/index" => "btn_export",
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
//            $dataProvider = $this->findCurl()->get($url);
//            return $dataProvider;
            $dataProvider = json::decode($this->findCurl()->get($url), true);
            foreach ($dataProvider['rows'] as $key => $val) {
                $dataProvider['rows'][$key]['ord_no'] = '<a href="' . Url::to(['view', 'id' => $val['ord_id']]) . '">' . $val['ord_no'] . '</a>';
            }
            return Json::encode($dataProvider);
        }
        $downList = $this->getDownList();
        $columns = $this->getField("/sale/sale-trade-order/index");
        $child_columns = $this->getField("/sale/sale-trade-order/get-product");
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->exportFiled(Json::decode($this->findCurl()->get($url))['rows']);
        }
//        dumpE($type);
        return $this->render('index', [
            'downList' => $downList,
            'columns' => $columns,
            'child_columns' => $child_columns,
        ]);
    }

    public function actionDetailList()
    {
        $url = $this->findApiUrl() . $this->_url . "order-detail-list";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
//            $dataProvider = $this->findCurl()->get($url);
//            return $dataProvider;
            $dataProvider = json::decode($this->findCurl()->get($url), true);
            foreach ($dataProvider['rows'] as $key => $val) {
                $dataProvider['rows'][$key]['saph_code'] = '<a href="' . Url::to(['view', 'id' => $val['ord_id']]) . '">' . $val['saph_code'] . '</a>';
            }
            return Json::encode($dataProvider);
        }
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->exportFiled(Json::decode($this->findCurl()->get($url))['rows']);
        }
        $columns = $this->getField("/sale/sale-trade-order/detail-list");
        $downList = $this->getDownList();
//        dumpE($columns);
        return $this->render('detail-list', [
            'downList' => $downList,
            'columns' => $columns,
        ]);
    }

    public function actionView($id)
    {
        $url = $this->findApiUrl() . $this->_url . "order-detail?id=" . $id;
        $data = Json::decode($this->findCurl()->get($url));
//        $child_columns = $this->getField("/sale/sale-trade-order/get-product");
        if ($data["seller"]["isrule"] == 1) { //如果自己是客户经理人
            $data["seller"]["leader"] = $data["seller"]["staff_code"] . " " . $data["seller"]["staff"]["name"];
        }
        $type = json_decode($this->actionGetCheckType($data["model"]["ordType"]));
        $verify = $this->getVerify($id, $type);//審核信息
        return $this->render('view', [
            'data' => $data,
            'verify' => $verify,
        ]);
    }

    //取消订单
    public function actionCancel()
    {
        $getData = Yii::$app->request->get();
        $getData['update_by'] = Yii::$app->user->identity->staff_id;
//        dumpE($getData);
        $url = $this->findApiUrl() . $this->_url . "cancel?" . http_build_query($getData);
        return $data = $this->findCurl()->get($url);
    }

    //改价取消
    public function actionRepriceCancel()
    {
        $getData = Yii::$app->request->get();
        $getData['update_by'] = Yii::$app->user->identity->staff_id;
//        dumpE($getData);
        $url = $this->findApiUrl() . $this->_url . "reprice-cancel?" . http_build_query($getData);
        return $data = $this->findCurl()->get($url);
    }

    public function actionCancelBox()
    {
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render('cancel');
    }
    public function actionPayBox($id)
    {
        $url = $this->findApiUrl() . $this->_url . "order-detail?id=" . $id;
        $data = Json::decode($this->findCurl()->get($url));
//        dumpE($data);
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render('pay', [
            'data' => $data,
        ]);
    }

    //获取客户帐信额度
    public function actionGetCustCredit($id, $currency)
    {
        $url = $this->findApiUrl() . "sale/sale-cust-order/get-cust-credit?id=" . $id . '&currency=' . $currency;
        return $this->findCurl()->get($url);
    }

    // 退款处理
    public function actionRefund($id)
    {
        $url = $this->findApiUrl() . $this->_url . "refund?id=" . $id;
        if (yii::$app->request->isPost) {
            $params = Yii::$app->request->post();
            $curl = $this->findCurl();
            $curl->setOption(CURLOPT_POSTFIELDS, http_build_query($params));
            $res = Json::decode($curl->post($url));
            if ($res['status'] == 1) {
                return Json::encode(['msg' => "新增成功！", "flag" => 1, 'url' => \yii\helpers\Url::to(['view', 'id' => $id])]);
            } else {
                return Json::encode(['msg' => $res['msg'], "flag" => 0]);
            }
        } else {
            $data = Json::decode($this->findCurl()->get($url));
//            dumpE($data);
            return $this->render('refund', [
                'data' => $data,
            ]);
        }
    }

    // 退款处理
    public function actionReprice($id)
    {
        $url = $this->findApiUrl() . $this->_url . "reprice?id=" . $id;
        if (yii::$app->request->getIsPost()) {
            $post = yii::$app->request->post();
//            dumpE($post);
            $curl = $this->findCurl();
            $curl->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $res = Json::decode($curl->post($url));
            if ($res['status'] == 1) {
                return Json::encode(['msg' => "订单改价成功！", "flag" => 1]);
            } else {
                return Json::encode(['msg' => $res['msg'], "flag" => 0]);
            }
        } else {
            $data = Json::decode($this->findCurl()->get($url));
            $downList = $this->getDownList();
            $type = $this->actionGetCheckType($data["model"]["ordType"]);
            if ($data["seller"]["isrule"] == 1) { //如果自己是客户经理人
                $data["seller"]["leader"] = $data["seller"]["staff_code"] . " " . $data["seller"]["staff"]["name"];
            }
//            dumpE($data);
            return $this->render('reprice', [
                'type' => $type,
                'data' => $data,
                'downList' => $downList,
            ]);
        }
    }

    // 获取子表商品信息
    public function actionGetProduct($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-products?id=" . $id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        return $this->findCurl()->get($url);
    }
    // 获取改價子表商品信息
    public function actionGetLogProduct($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-log-products?id=" . $id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        return $this->findCurl()->get($url);
    }

    // 获取子表商品信息
    public function actionGetCheckType($type)
    {
        $url = $this->findApiUrl() . $this->_url . "get-check-type?type=" . $type;
        return $this->findCurl()->get($url);
    }

    //获取运输方式和运费
    public function actionGetFreight($pdt, $num, $addr, $TransType)
    {
        $url = $this->findApiUrl() . $this->_url . "get-freight?pdt=" . $pdt . "&num=" . $num . "&addr=" . $addr . "&TransType=" . $TransType;
        return $this->findCurl()->get($url);
    }

    // 下拉列表
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "get-down-list";
        return json::decode($this->findCurl()->get($url));
    }

    // 采购通知
    public function actionPurchaseNote($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'purchase-note?id=' . $id;
        $params = Yii::$app->request->post();
        $params['staff_id'] = Yii::$app->user->identity->staff_id;
        if (!empty($params)) {
            $url .= '&' . http_build_query($params);
        }
        if (Yii::$app->request->isPost) {
            return $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params))->post($url);
        } else {
            $res = json::decode($this->findCurl()->get($url), true);
//            dumpE($res);
            return $this->renderAjax('purchase-note', ['res' => $res]);
        }
    }

    // 出货通知
    public function actionOutNote($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'out-note?id=' . $id;
        $params = Yii::$app->request->post();
        // 发送通知人
        $params['staff_id'] = Yii::$app->user->identity->staff_id;
        if (!empty($params)) {
            $url .= '&' . http_build_query($params);
        }
        if (Yii::$app->request->isPost) {
//            dumpE($params);
            return $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params))->post($url);
        } else {
            $res = json::decode($this->findCurl()->get($url), true);
//            dumpE($res);
            return $this->renderAjax('out-note', ['res' => $res]);
        }
    }

    // 帐信支付确认
    public function actionOrderPay($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'order-pay?id=' . $id;

        $res = json::decode($this->findCurl()->get($url), true);
        if ($res['status'] == 1) {
            return Json::encode(['msg' => "支付确认成功！", "flag" => 1]);
        } else {
            return Json::encode(['msg' => '发生错误'.$res['msg'], "flag" => 0]);
        }
    }

    /**
     * 获取审核记录
     * @param $id
     * @param $type
     * @return mixed
     */
    public function getVerify($id, $type)
    {
        $url = $this->findApiUrl() . "system/verify-record/find-verify?id=" . $id . "&type=" . $type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }
}
