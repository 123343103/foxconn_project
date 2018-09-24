<?php

namespace app\modules\sale\controllers;

use app\modules\common\models\BsBusinessType;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

class SaleQuotedOrderController extends \app\controllers\BaseController
{
    private $_url = 'sale/sale-quoted-order/'; //对应api
    public $p_url = '/sale/sale-quoted-order/index'; //父级菜单列表

    public function beforeAction($action)
    {
        $this->list = array_merge($this->list, [
            "/sale/sale-quoted-order/create" => "btn_add",
            "/system/verify-record/reviewer" => "btn_trial",
            "/sale/sale-quoted-order/update" => "btn_mody",
            "/sale/sale-quoted-order/cancle-quote" => "btn_cancle",
            "/sale/sale-quoted-order/detail-list" => "btn_detail",
            "/sale/sale-quoted-order/export" => "btn_export",
        ]);
//        $this->ignorelist = array_merge($this->ignorelist, [
//            "/sale/sale-cust-order/get-product",
//        ]);
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
            $dataProvider = Json::decode($this->findCurl()->get($url), true);
            foreach ($dataProvider['rows'] as $key => $val) {
                $dataProvider['rows'][$key]['price_no'] = '<a href="' . Url::to(['view', 'id' => $val['price_id']]) . '">' . $val['price_no'] . '</a>';
            }
            return Json::encode($dataProvider);
        }
        $downList = $this->getDownList();
        $columns = $this->getField("/sale/sale-quoted-order/index");
        $child_columns = $this->getField("/sale/sale-quoted-order/get-product");
        return $this->render('index', [
            'downList' => $downList,
            'columns' => $columns,
            'queryParam' => $queryParam,
            'child_columns' => $child_columns,
        ]);
    }

    /**
     * 导出报价单
     */
    public function actionExport()
    {
        $url = $this->findApiUrl() . $this->_url . "index?export=1";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        \Yii::$app->controller->action->id = 'index';
        SystemLog::addLog('导出报价单');
        return $this->exportFiled($dataProvider['rows']);
    }

    /**
     * @return string
     * 订单报价
     */
    public function actionCreate($id)
    {

        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $isApply = Yii::$app->request->get('is_apply');
            $status = Yii::$app->request->get('status');
            $url = $this->findApiUrl() . $this->_url . "create?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg'] . '保存成功');
                if (!empty($isApply)) {
                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id'], 'is_apply' => 1])]);
                } else {
                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
                }
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $url = $this->findApiUrl() . $this->_url . 'view?id=' . $id;
        $data = Json::decode($this->findCurl()->get($url));
        $seller = $this->getSeller($data['info']['seller']);
//        dumpE($seller);
        $downList = $this->getDownList();
        $credits = $this->getCreditLimit($data['info']['customer']['cust_id'], $data['info']['cur_id']);
        return $this->render('create', [
            'data' => $data['info'],
            'dt' => $data['dt'],
            'credits' => $credits,
            'downList' => $downList,
            'seller' => $seller,
            'id' => $id,
        ]);
    }

    /**
     * @return string
     * 订单报价
     */
    public function actionUpdate($id)
    {

        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $isApply = Yii::$app->request->get('is_apply');
            $status = Yii::$app->request->get('status');
            $url = $this->findApiUrl() . $this->_url . "create?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg'] . '保存成功');
                if (!empty($isApply)) {
                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id'], 'is_apply' => 1])]);
                } else {
                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
                }
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $url = $this->findApiUrl() . $this->_url . 'view?id=' . $id;
        $data = Json::decode($this->findCurl()->get($url));
        $seller = $this->getSeller($data['info']['seller']);
//        dumpE($seller);
        $downList = $this->getDownList();
        $credits = $this->getCreditLimit($data['info']['customer']['cust_id'], $data['info']['cur_id']);
//        dumpE($data['info']['pay']);
        return $this->render('update', [
            'data' => $data['info'],
            'dt' => $data['dt'],
            'credits' => $credits,
            'downList' => $downList,
            'seller' => $seller,
            'id' => $id,
        ]);
    }

    /**
     * @param $id
     * @return string
     * 详情页
     */
    public function actionView($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'view?id=' . $id;
        $data = Json::decode($this->findCurl()->get($url));
        $isApply = Yii::$app->request->get('is_apply');
        $verify = $this->getVerify($id, $data['info']['price_type']);//審核信息
        $seller = $this->getSeller($data['info']['seller']);
        $credits = $this->getCreditLimit($data['info']['customer']['cust_id'], $data['info']['cur_id']);
        return $this->render('view', [
            'data' => $data['info'],
            'dt' => $data['dt'],
            'seller' => $seller,
            'isApply' => $isApply,
            'id' => $id,
            'verify' => $verify,
            'credits' => $credits,
        ]);
    }

    /**
     * @return string
     * 取消报价
     */
    public function actionCancleQuote($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['PriceInfo']['caner'] = Yii::$app->user->identity->staff_id;
            $post['PriceInfo']['can_ip'] = Yii::$app->request->getUserIP();
            $post['PriceInfo']['can_date'] = date('Y-m-d H:i:s', time());
            $url = $this->findApiUrl() . $this->_url . "cancle-quote?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg'] . '取消报价');
                return Json::encode(['msg' => "取消成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $this->layout = '@app/views/layouts/ajax';
        return $this->render('_canclequote', [
            'id' => $id,
        ]);
    }

    /**
     * @return mixed|string
     * 报价单明细列表
     */
    public function actionDetailList()
    {
        $url = $this->findApiUrl() . $this->_url . "detail-list";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = Json::decode($this->findCurl()->get($url), true);
//            foreach ($dataProvider['rows'] as $key => $val) {
//                $dataProvider['rows'][$key]['price_no'] = '<a href="' . Url::to(['view', 'id' => $val['price_id']]) . '">' . $val['price_no'] . '</a>';
//            }
            return Json::encode($dataProvider);
        }
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->exportFiled(Json::decode($this->findCurl()->get($url))['rows']);
        }
        $downList = $this->getDownList();
        $columns = $this->getField("/sale/sale-quoted-order/detail-list");
        return $this->render('list', [
            'downList' => $downList,
            'columns' => $columns,
            'queryParam' => $queryParam,
        ]);
    }

    /**
     * 导出报价单明细
     */
    public function actionDetailListExport()
    {
        $url = $this->findApiUrl() . $this->_url . "detail-list?export=1";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        \Yii::$app->controller->action->id = 'detail-list';
        SystemLog::addLog('导出报价单');
        return $this->exportFiled($dataProvider['rows']);
    }

    /**
     * 获取审核记录
     * @param $id
     * @param $type
     * @return mixed
     */
    public function getVerify($id, $type)
    {
        $url = $this->findApiUrl() . "/system/verify-record/find-verify?id=" . $id . "&type=" . $type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    /**
     * @return mixed
     * 获取销售人员等信息
     */
    public function getSeller($code)
    {
        $url = $this->findApiUrl() . $this->_url . 'seller?code=' . $code;
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * @return mixed
     * 下拉菜单
     */
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . 'down-list';
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * @return mixed
     * 获取账信额度
     */
    public function getCreditLimit($id, $cur)
    {
        $url = $this->findApiUrl() . $this->_url . 'get-cust-credit?id=' . $id . '&cur=' . $cur;
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * @param $id
     * @return mixed
     * 获取子表信息
     */
    public function actionGetProduct($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-product?id=" . $id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        return $this->findCurl()->get($url);
    }

    /**
     * @param $pdt
     * @param $num
     * @param $addr
     * @param $TransType
     * @return mixed
     *获取运输方式和运费
     */
    public function actionGetFreight($pdt, $num, $addr, $TransType)
    {
        $url = $this->findApiUrl() . $this->_url . "get-freight?pdt=" . $pdt . "&num=" . $num . "&addr=" . $addr . "&TransType=" . $TransType;
        return $this->findCurl()->get($url);
    }
}
