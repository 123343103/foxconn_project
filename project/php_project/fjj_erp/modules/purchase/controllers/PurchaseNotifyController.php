<?php

namespace app\modules\purchase\controllers;

use app\classes\Menu;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class PurchaseNotifyController extends \app\controllers\BaseController
{
    private $_url = 'purchase/purchase-notify/'; //对应api
    private $_puurl = "purchase/purchase-before-work/";//对应采购前置api

    public function actionIndex()
    {
        $ids = Yii::$app->user->identity->staff_id;
        $url = $this->findApiUrl() . 'purchase/purchase-notify/index';
        $url2 = $this->findApiUrl() . 'purchase/purchase-notify/export';
        //导出
        if(Yii::$app->request->get('export')==1){
            $queryParam = Yii::$app->request->queryParams;
            if (!empty($queryParam)) {
                $url2 .= "?" . http_build_query($queryParam);
                $url2 .= "&" . http_build_query($queryParam);
                $url2 .= "&ids=" .$ids;
            }
            $dataProvider = $this->findCurl()->get($url2);
            $dataProvider = Json::decode($dataProvider);
            $this->exportFiled($dataProvider['tr']);
        }
        if (Yii::$app->request->isAjax) {
            $url .= '?' . http_build_query(Yii::$app->request->queryParams);
            $url .= '&' . http_build_query(Yii::$app->request->queryParams);
            $url .= '&ids=' . $ids;
            $dataProvider = $this->findCurl()->get($url);
            if (Menu::isAction('/purchase/purchase-notify/view')) {
                //给采购单号添加单击事件
                $dataProvider = Json::decode($dataProvider);
                if (!empty($dataProvider['rows'])) {
                    foreach ($dataProvider['rows'] as &$val) {
                        $val['prch_no'] = "<a onclick='window.location.href=\"" . Url::to(['view', 'id' => $val['prch_id']]) . "\";event.stopPropagation();'>" . $val['prch_no'] . "</a>";
                    }
                }
                return Json::encode($dataProvider);
            }
            return $dataProvider;
        }
        $fields = $this->getField("/purchase/purchase-notify/index");
        $dataProvider = $this->findCurl()->get($url);
        $data = Json::decode($dataProvider);
        $data['table']=$this->getField('/purchase/purchase-notify/index');
        $data['table1']=$this->getField('/purchase/purchase-notify/commodity');
        return $this->render('index', ['data' => $data, 'fields' => $fields]);
    }

    //生成收货通知单
    public function actionNotice($id)
    {
        $url = $this->findApiUrl() . 'purchase/purchase-notify/notice';
        if (Yii::$app->request->isAjax) {
            $url .= '?' . http_build_query(Yii::$app->request->queryParams);
            $url .= '&' . http_build_query(Yii::$app->request->queryParams);
            $url .= '?id=' . $id;
            $dataProvider = $this->findCurl()->get($url);
            return $dataProvider;
        }
        $fields = $this->getField("/purchase/purchase-notify/notice");
        $hr = $this->gethr($id);
        $dataProvider = $this->findCurl()->get($url);
        $data = Json::decode($dataProvider);
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render('notice', ['data' => $data, 'fields' => $fields, 'hr' => $hr]);
    }

    //获取采购部门和采购人
    public function gethr($id)
    {
        $_url = $this->findApiUrl() . $this->_url . "hrmes?id=" . $id;
        return Json::decode($this->findCurl()->get($_url));
    }

    //生成收货通知单页面数据新版 by hf
    public function actionNoticeNew()
    {
        $queryParam = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "notice-new";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        if (Yii::$app->request->isAjax) {
            return Json::encode($dataProvider);
        }
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render('_noticenew', ['data' => $dataProvider]);
    }

    //生成收货通知单 by hf
    public function actionCreateNotice()
    {
        $postData = Yii::$app->request->post();
        $postData['staff'] = Yii::$app->user->identity->staff_id;
        if (Yii::$app->request->getIsPost()) {
            $url = $this->findApiUrl() . $this->_url . "create-notice";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
           $data = Json::decode($curl->post($url), true);
            $count=$data['data']['count'];
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg']);
                return json_encode(['msg' => "您选择的资料将生成 <span style='color:red;'>".$count."</span> 条通知单！", 'flag' => 1, "url" => Url::to(['/purchase/purchase-notify/index'])
                ]);
            } else {
                return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
            }
        }
    }

    //商品信息
    public function actionCommodity($id)
    {
        $url = $this->findApiUrl() . 'purchase/purchase-notify/commodity';
        if (Yii::$app->request->isAjax) {
            $url .= '?' . http_build_query(Yii::$app->request->queryParams);
            $url .= '&' . http_build_query(Yii::$app->request->queryParams);
            $url .= '?id=' . $id;
            $dataProvider = $this->findCurl()->get($url);
            if (Menu::isAction('/purchase/purchase-apply/view')) {
                //给关联单号添加单击事件
                $dataProvider = Json::decode($dataProvider);

                foreach ($dataProvider['rows'] as &$val) {
                    $req_no_n = null;
                    $val['req_no'] = explode(',', $val['req_no']);
                    $req_id = explode(',', $val['req_id']);
                    foreach ($val['req_no'] as $key => $value) {
                        if (!empty($val['req_no'])) {
                            $req_no_n .= "<a onclick='window.location.href=\"" . Url::to(['/purchase/purchase-apply/view', 'id' => $req_id[$key]]) . "\";event.stopPropagation();'>" . $value . "</a></br>";

                        }
                    }
                    if(!empty($req_no_n)){
                        $val["req_no"] =  $req_no_n;
                    }
                }
                return Json::encode($dataProvider);
            }
            return $dataProvider;
        }
        $fields = $this->getField("/purchase/purchase-notify/commodity");
        $dataProvider = $this->findCurl()->get($url);
        $data = Json::decode($dataProvider);
        return $this->render('index', ['data' => $data, 'fields' => $fields]);
    }

    //详情
    public function actionView($id)
    {
        $model = $this->getModel($id);
        $verify = $this->getVerify($id, 55);//查看審核狀態
//        dumpE($data);
        return $this->render('view', [
            'model' => $model[0],
            'products' => $model[1],
            'verify' => $verify,
            'id' => $id
        ]);
    }

    //打印
    public function actionPrview($id)
    {
        $this->layout = '@app/views/layouts/ajax';
        $model = $this->getModel($id);
        $verify = $this->getVerify($id, 55);//查看審核狀態
        return $this->render('prview', [
            'model' => $model[0],
            'products' => $model[1],
            'verify' => $verify,
            'id' => $id
        ]);
    }

    //查看审核状态
    public function getVerify($id, $type)
    {
        $url = $this->findApiUrl() . "/system/verify-record/find-verify?id=" . $id . "&type=" . $type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    public function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
//        dumpE($url);
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    // 获取子表商品信息
    public function actionGetProduct($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-products?id=" . $id;
        return $this->findCurl()->get($url);
    }

    // 下拉列表
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "get-down-list";
        return json::decode($this->findCurl()->get($url));
    }

    // 生成采购单
    public function actionCreatePurchase()
    {
        $post = Yii::$app->request->queryParams;
        $post['staff_id'] = Yii::$app->user->identity->staff_id;
        $url = $this->findApiUrl() . $this->_url . "create-purchase";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        return $curl->post($url);
    }

    // 取消通知
    public function actionCancelNotify($id)
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "cancel-notify?id=" . $id;
            return $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post))->post($url);
        }
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render("cancel-notify", ['id' => $id]);
    }

    //取消
    public function actionCanRsn($id)
    {
        if ($data = Yii::$app->request->post()) {
            $url = $this->findApiUrl() . 'purchase/purchase-notify/can-rsn';
            $url .= '?id=' . $id;
            return $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data))->post($url);
        }
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render('can-rsn');
    }

    //修改采购单 by hf
    public function actionUpdate($id)
    {
        if ($post = Yii::$app->request->post()) {
            $url = $this->findApiUrl() . 'purchase/purchase-notify/update?id=' . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            // dump($post);
            $data = Json::decode($curl->put($url));
            // dump($data);
            // $data['status']=0;
            //$isApply = Yii::$app->request->get('is_apply');
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg']);
                //if (!empty($isApply)) {
                return json_encode([
                    'msg' => "保存成功，点击确定完成申请",
                    'flag' => 1,
                    'billId' => $id,//单据id
                    'billTypeId' => 55,//单据类型
                    "url" => Url::to(['view', 'id' => $id])
                ]);
//                } else {
//                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $id])]);
//                }
            } else {
                return Json::encode(['msg' => '发生未知错误', "flag" => 0]);
            }
        } else {
            $model = $this->getBuyInfo($id);   //获取采购单信息
            $buyerinfo = $this->getBuyerInfo($model[0]['apper']);//获取采购人员信息
            $reqdct = $this->getReqDct($model[0]['req_dct']);//单据类型
            $areaid = $this->getBuyArea($model[0]['area_id']);//采购区域
            $legid = $this->getComname($model[0]['leg_id']);//法人信息
            $DownList = $this->getDownLists();//获取付款方式
            $Currency = $this->getCurrency();//获取币别
            return $this->render('_update', [
                'model' => $model,
                'buyerinfo' => $buyerinfo,
                'reqdct' => $reqdct,
                'areaid' => $areaid,
                'legid' => $legid,
                'downList' => $DownList,
                'currency' => $Currency
            ]);
        }
    }

//获取指定的采购区域
    public function getBuyArea($id)
    {
        $url = $this->findApiUrl() . $this->_puurl . 'buy-area?id=' . $id;
        return Json::decode($this->findCurl()->get($url));
    }

    public function actionReqNo($id, $partno)
    {
        $url = $this->findApiUrl() . $this->_url . "req-no?id=" . $id . "&partno=" . $partno;
        return $this->findCurl()->get($url);
    }

    //获取采购单信息
    public function getBuyInfo($id)
    {
        $url = $this->findApiUrl() . $this->_url . "buy-info?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    //获取交货条件
    public function actionGoodsCondition()
    {
        $url = $this->findApiUrl() . $this->_url . 'goods-condition';
        $url .= "?" . http_build_query(Yii::$app->request->queryParams);
        $data = $this->findCurl()->get($url);
        return $data;
    }

    //获取单据类型与请购形式、付款方式
    public function getDownLists()
    {
        $url = $this->findApiUrl() . $this->_puurl . "down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    //获取请购部门
    public function getSppDpt()
    {
        $url = $this->findApiUrl() . $this->_puurl . "spp-dpt";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    //获取法人信息
    public function getComMan()
    {
        $url = $this->findApiUrl() . $this->_puurl . 'com-man';
        return Json::decode($this->findCurl()->get($url));
    }

//获取采购人员信息
    public function getBuyerInfo($id)
    {
        $url = $this->findApiUrl() . $this->_puurl . 'buyer-info?id=' . $id;
        return Json::decode($this->findCurl()->get($url));
    }

    //获取具体单据类型与采购区域
    public function getReqDct($id)
    {
        $url = $this->findApiUrl() . $this->_puurl . 'req-dct?id=' . $id;
        $data = $this->findCurl()->get($url);
        return Json::decode($data);
    }

    //获取法人的具体信息
    public function getComname($id)
    {
        $url = $this->findApiUrl() . $this->_puurl . 'get-comname?id=' . $id;
        $data = $this->findCurl()->get($url);
        return Json::decode($data);
    }

//获取币别
    public function getCurrency()
    {
        $url = $this->findApiUrl() . $this->_puurl . 'currency';
        $data = Json::decode($this->findCurl()->get($url));
        return $data;
    }


    //导出
    public function actionExport()
    {
        $ids=Yii::$app->user->identity->staff_id;
        $url = $this->findApiUrl() . $this->_url . "export";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
            $url .= "&" . http_build_query($queryParam);
            $url.="&ids=".$ids;
        }
        $dataProvider = $this->findCurl()->get($url);
        $dataProvider = Json::decode($dataProvider);
        return $this->getExcelData($dataProvider);
    }

    private function getExcelData($dataProvider)
    {
        $data = $dataProvider['tr'];
        foreach ($data as $key => $val) {
//            unset($data[$key]['prch_id']);
            unset($data[$key]['prch_status']);
            unset($data[$key]['cur_id']);;
        }
        $headArr = ['序号', '采购单号', '采购单状态', '采购日期', '单据类型', '采购部門', '采购员', '联系方式', '法人',];
        $this->getExcels($headArr, $data);
    }

    private function getExcels($headArr, $data)
    {

        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xls";
        // 创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        // 设置表头
        $key = "A";
        foreach ($headArr as $v) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(18);
            $colum = $key;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            if ($key == "Z") {
                $key = "AA";
            } elseif ($key == "AZ") {
                $key = "BA";
            } else {
                $key++;
            }
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach ($data as $key => $rows) { // 行写入
            $span = "A";
            foreach ($rows as $keyName => $value) { // 列写入
                $j = $span;
                $objActSheet->setCellValue($j . $column, $value);

                if ($span == "Z") {
                    $span = "AA";
                } elseif ($span == "AZ") {
                    $span = "BA";
                } else {
                    $span++;
                }
            }
            $column++;
        }
        $fileName = iconv("utf-8", "gb2312", $fileName);
        // 重命名表
        // $objPHPExcel->getActiveSheet()->setTitle('test');
        // 设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean(); // 清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=" . $fileName);
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); // 文件通过浏览器下载
        SystemLog::addLog('导出采购单结果');
        exit();
    }
}
