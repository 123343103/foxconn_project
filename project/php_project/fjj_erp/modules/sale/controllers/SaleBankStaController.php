<?php
/**
 * Created by PhpStorm.
 * User: F3860942
 * Date: 2017/8/2
 * Time: 下午 02:19
 */

namespace app\modules\sale\controllers;


use app\controllers\BaseController;
use app\modules\common\models\BsBusinessType;
use Yii;
use yii\base\Exception;
use yii\helpers\Json;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\modules\common\tools\ExcelToArr;
use yii\bootstrap\Html;


class SaleBankStaController extends BaseController
{
    private $_url = 'sale/sale-bank-sta/'; //对应api

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
//        $dataProvider = json::decode($this->findCurl()->get($url), true);
//        dumpE($dataProvider);
        if (Yii::$app->request->isAjax) {

//            dumpE($queryParam);
            $dataProvider = json::decode($this->findCurl()->get($url), true);
            for($j=0;$j<count($dataProvider['rows']);$j++)
            {
                $url=$this->findApiUrl().$this->_url."get-order-no?transid=".$dataProvider['rows'][$j]['TRANSID']."&rboid=".$dataProvider['rows'][$j]['rbo_id'];
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
                $dataProvider['rows'][$j]['order_no']=$order_no;
            }
            return Json::encode($dataProvider);
        }
        $columns=$this->getField("/sale/sale-bank-sta/index");
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $dataProvider = json::decode($this->findCurl()->get($url), true);
            for($j=0;$j<count($dataProvider['rows']);$j++)
            {
                $url=$this->findApiUrl().$this->_url."get-order-no?transid=".$dataProvider['rows'][$j]['TRANSID']."&rboid=".$dataProvider['rows'][$j]['rbo_id'];
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
                $dataProvider['rows'][$j]['order_no']=$order_no;
                switch ($dataProvider['rows'][$j]['state']){
                    case 10:$dataProvider['rows'][$j]['states']="审核中";
                        break;
                    case 20:$dataProvider['rows'][$j]['states']="已审核";
                        break;
                    case 30:$dataProvider['rows'][$j]['states']="已驳回";
                        break;
                }
            };
            $this->export($dataProvider['rows']);
        }
        $downList = $this->getDownList();
        return $this->render('index', [
            'downList' => $downList,
            'columns'=>$columns,
            'search' => $queryParam['BsBankInfoSearch']
        ]);
    }

    // 下拉列表
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "get-down-list";
//        var_dump(json::decode($this->findCurl()->get($url)));
        return json::decode($this->findCurl()->get($url));
    }

    //导出数据
    private function getExcelData($data)
    {
        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();
        $date = date('Y-m-d-H-i-s', time()) . '_' . rand(0, 99);//加随机数，防止重名
        $fileName = '银行流水查询列表' . "_{$date}.xls";
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);//设置J栏位（电话栏）宽度为15//
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);//设置J栏位（电话栏）宽度为15//
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);//设置J栏位（电话栏）宽度为15//
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);//设置J栏位（电话栏）宽度为15//
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);//设置J栏位（电话栏）宽度为15//
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(42);//设置J栏位（电话栏）宽度为15//
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);//设置J栏位（电话栏）宽度为15//
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);//设置J栏位（电话栏）宽度为15//
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '流水号')
            ->setCellValue('B1', '法人')
            ->setCellValue('C1', '银行')
            ->setCellValue('D1', '账户')
            ->setCellValue('E1', '收款金额(RMB)')
            ->setCellValue('F1', '转账日期')
            ->setCellValue('G1', '对方账户')
            ->setCellValue('H1', '订单号')
            ->setCellValue('I1', '收款状态');
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(61);
        $num = 2;
        foreach ($data as $key => $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, ' ' . $val['TRANSID'])
                ->setCellValue('B' . $num, $val['CORP_DESC'])
                ->setCellValue('C' . $num, ' ' . $val['BNK_NME'])
                ->setCellValue('D' . $num, $val['ACCOUNTS'])
                ->setCellValue('E' . $num, $val['TXNAMT'])
                ->setCellValue('F' . $num, $val['TRDATE'])
                ->setCellValue('G' . $num, ' ' . $val['OPPNAME'])
                ->setCellValue('H' . $num, $val['ord_no'])
                ->setCellValue('I' . $num, $val['states']);
            $num++;
        }
//        $fileName = iconv("utf-8", "gb2312", $fileName);
        // 重命名表
        // 设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean(); // 清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=" . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); // 文件通过浏览器下载
        exit();
    }
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
        $number0=[['field_field'=>'states','field_title'=>'状态']];
        $columns=array_merge($columns,$number0);
        $excelIndex = '$objPHPExcel->setActiveSheetIndex(0)';
        //获取列
        foreach ($columns as $key => $value) {
            if ($fieldIndex > 24) {
                $fieldIndex = 1;
            }
            //设置列的宽度
            $objPHPExcel->getActiveSheet()->getColumnDimension($filedTitle)->setAutoSize(true);
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
                $filedVal .= '->setCellValue(\'' . $filedTitle . $num . '\',\' ' . $field_val . '\')';
                $filedTitle++;
            }
            $filedTitle = 'A';
            eval($excelIndex . $filedVal . ';');
            Html::decode($filedVal);
            $filedVal = '';
        }
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "银行流水查询列表"."_{$date}.xls";
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

    //收款弹窗
    public function actionReveiveOrder($transid, $rbo_id)
    {
        $a = 0;
        if ($rbo_id != "null") {
            $url = $this->findApiUrl() . $this->_url . "get-is-sign?rbo_id=" . $rbo_id;
            $data = Json::decode($this->findCurl()->get($url));
            if (count($data) == 1) {
                $transid = $data[0]['TRANSID'];
                $a = 1;
            }
        }

        if (!empty($transid) || $a == 1) {
            if (Yii::$app->request->isAjax) {
                $params = Yii::$app->request->queryParams;
                $url = $this->findApiUrl() . $this->_url . "get-unpaid-order?";
                if (!empty($params)) {
                    $url .= "?" . http_build_query($params);
                }
                $unpaidOrder = $this->findCurl()->get($url);
                return $unpaidOrder;
            }
            $url = $this->findApiUrl() . $this->_url . "get-trans-info?transid=" . $transid;
            $data = Json::decode($this->findCurl()->get($url));
            $this->layout = "@app/views/layouts/ajax";
            return $this->render('receive-apply', ['data' => $data, 'rbo_id' => $rbo_id]);
        }
    }

    //收款绑定
    public function actionBindBankTrans($transid, $orderlist, $address, $remark, $rbo_id, $ord_pay_id, $ordMoney)
    {
        try {
            $typeId = BsBusinessType::find()->select('business_type_id')->where(['business_code' => 'ordrecives'])->asArray()->one();
            $typeId = $typeId['business_type_id'];
            if (Yii::$app->request->getIsPost()) {
                $post = Yii::$app->request->post();
                $post['transid'] = $transid;
                $post['type'] = $typeId;
                $post['staff'] = Yii::$app->user->identity->staff_id;
                $post['remark'] = trim($remark);
                $post['ord_pay_id'] = $ord_pay_id;
                if ($rbo_id != "null") {
                    $post['rbo_id'] = $rbo_id;
                }
                $url = $this->findApiUrl() . "system/verify-record/recive-verify";
                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
                $data = Json::decode($curl->post($url));
                if ($data['status']) {
                    return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1, "url" => $address]);
                } else {
                    return Json::encode(['msg' => $data['msg'] . "送审失败!", 'flag' => 0]);
                }
            }
            $custCode = "";
            $curId = "";
            $refundSum = 0;//退款总金额
            $params = require("../config/params.php");
            $p_dif = $params['p_dif'];//订单差额参数
            $orderArray = explode(';', $orderlist);
            $ordPayId = explode(';', $ord_pay_id);
            $url = $this->findApiUrl() . $this->_url . "get-trans-info?transid=" . $transid;//获取单笔流水信息
            $oneTransInfo = Json::decode($this->findCurl()->get($url));
            $url = $this->findApiUrl() . $this->_url . "get-verify-trans?transid=" . $transid;
            $transcount = Json::decode($this->findCurl()->get($url));
            if (count($transcount) > 0) {
                throw new \Exception("该笔流水已有审核中的记录,禁止操作!");
            }
            //根据收款银行账户判断所计营收科目
            $accountTitle = "";
            switch ($oneTransInfo['ACCOUNTS']) {
                case "41028900040053117":
                    //00160804  深圳精基  41028900040053117 深圳精基精密机械贸易有限公司 中国农业银行深圳龙华支行1002103QB0
                    $accountTitle = "1002103QB0";
                    break;
                case "41028900040049149":
                    //00199172  鄭州精基  41028900040049149 富金机网络科技（河南）有限公司 农业银行深圳龙华支行 1002101UB1
                    $accountTitle = "1002101UB1";
                    break;
                case "4000026619202686718":
                    //鄭州精基  4000026619202686718 郑州精基精密机械贸易有限公司 工行深圳龙华支行 1002101UG0
                    $accountTitle = "1002101UG0";
                    break;
                case "254621880150":
                    //鄭州精基  254621880150 郑州精基精密机械贸易有限公司 中行郑州航空港支行 1002101U50
                    $accountTitle = "1002101U50";
                    break;
                default:
                    throw new \Exception("收款银行没有设定所计营收科目");
                    break;
            }
            for ($i = 0; $i < count($orderArray); $i++) {
                $url = $this->findApiUrl() . $this->_url . "get-cmp-info?order_no=" . $orderArray[$i];//查询订单的客户代码
                $cust_code = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . $this->_url . "get-cur-id?order_no=" . $orderArray[$i];//获取订单的交易币别
                $cur_id = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . $this->_url . "get-corporate?order_no=" . $orderArray[$i];//获取订单交易法人
                $corporate = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . $this->_url . "get-ref-money?order_no=" . $orderArray[$i];//获取单笔订单的退款金额
                $refMoney = Json::decode($this->findCurl()->get($url));
                if ($cur_id[0]['bsp_svalue'] != $oneTransInfo['CUR_CDE']) {
                    throw new \Exception("订单号:" . $orderArray[$i] . "的交易币别必须与银行流水的交易币别保持一致!");
                }
                if ($corporate[0]['company_code'] == "JJJMSZ0MTC" || $corporate[0]['company_code'] == "JJJMZZ0MTC") {
                    // JJJMSZ0MTC深圳金机
                    if ($corporate[0]['company_code'] == "JJJMSZ0MTC" && $corporate[0]['company_code'] != "1002103QB0") {
                        throw new \Exception("订单号:" . $orderArray[$i] . "的交易法人银行与交易流水银行不一致!");
                    }
                    // JJJMZZ0MTC 鄭州精基
                    if ($corporate[0]['company_code'] == "JJJMZZ0MTC" && ($accountTitle != "1002101UB1" && $accountTitle != "1002101UG0" && $accountTitle != "1002101U50")) {
                        throw new \Exception("订单号:" . $orderArray[$i] . "的交易法人银行与交易流水银行不一致!");
                    }
                } else {
                    throw new \Exception("订单号:" . $orderArray[$i] . "的交易法人银行与交易流水银行不一致!");
                }
                foreach ($refMoney as $value) {
                    $refundSum = ((float)$value['tax_fee'] * 1000 + $refundSum * 1000) / 1000;
                }
                if ($i == count($orderArray) - 1) {
                    $custCode .= $cust_code;
                    $curId .= $cur_id[0]['bsp_svalue'];
                } else {
                    $custCode .= $cust_code . ";";
                    $curId .= $cur_id[0]['bsp_svalue'] . ";";
                }
            }
            $custCode = explode(';', $custCode);
            $curId = explode(';', $curId);
            for ($k = 0; $k < count($curId); $k++) {
                if ($curId[0] !== $curId[$k]) {
                    throw new \Exception("所有订单的交易币别必须一致!");
                }
            }
            for ($j = 0; $j < count($custCode); $j++) {
                if ($custCode[0] != $custCode[$j]) {
                    throw new \Exception("同一交易流水只能绑定同一公司下的订单!");
                }
            }
            if (count($ordPayId) == 1) {
                $url = $this->findApiUrl() . $this->_url . "get-ord-pay?ord_pay_id=" . $ordPayId[0];
                $payInfo = Json::decode($this->findCurl()->get($url));
                if ($payInfo[0]['pay_type'] != 1) {
                    if ($payInfo[0]['req_tax_amount'] < 20) {
                        throw new \Exception("当只选择一笔订单时,订单金额不能小于20!");
                    }
                } else {
                    if ($payInfo[0]['stag_cost'] < 20) {
                        throw new \Exception("当只选择一笔订单时,订单金额不能小于20!");
                    }
                }
            }
            $sk = 0;
            for ($j = 0; $j < count($ordPayId); $j++) {
                $url = $this->findApiUrl() . $this->_url . "get-pac-name?order_no=" . $ordPayId[$j];//获取是收款还是还款订单
                $pacName = Json::decode($this->findCurl()->get($url));
                if ($pacName[0]['pac_sname'] == "预付款" && $pacName[0]['yn_pay'] == 0) {
                    $sk++;
                }
                $url = $this->findApiUrl() . $this->_url . "get-ord-pay?ord_pay_id=" . $ordPayId[$j];
                $payInfo = Json::decode($this->findCurl()->get($url));
                $url = $this->findApiUrl() . $this->_url . "get-rbo-info?ord_pay_id=" . $ordPayId[$j];
                $rbankinfo = Json::decode($this->findCurl()->get($url));
                if (count($rbankinfo) > 0) {
                    throw new \Exception("支付id为:" . $payInfo[0]['ord_pay_id'] . "的订单号:" . $payInfo[0]['ord_no'] . "已经被其他流水绑定,无法进行操作~!");
                }
                if ($pacName[0]['pac_sname'] == "信用额度支付" && $pacName[0]['yn_pay'] == 1) {
                    if (empty($oneTransInfo['TRDATE'])) {
                        throw new \Exception("获取不到流水号的交易时间!");
                    }
                    $data = $this->CheckHkOrder($ordPayId[$j], $oneTransInfo['TRDATE']);
                    if ($data['status'] == 0) {
                        throw new \Exception($data['msg']);
                    }
                }
            }
            if ($sk > 0) {
                $url = $this->findApiUrl() . $this->_url . "get-repay-date?cust_code=" . $custCode[0];
                $repayDate = Json::decode($this->findCurl()->get($url));
                $nowTime = date('Y-m-d h:i:s', time());
                foreach ($repayDate as $value) {
                    if ($nowTime > $value['repay_date']) {
                        throw new \Exception("该客户有逾期未还款订单,禁止收款!");
                    }
                }
            }
            if (((float)$ordMoney * 1000 - $refundSum * 1000 - (float)($oneTransInfo['TXNAMT']) * 1000) / 1000 > (float)$p_dif) {
                throw  new \Exception("订单金额-流水金额不能大于20元!");
            }
            $this->layout = "@app/views/layouts/ajax";
            $url = $this->findApiUrl() . "/system/verify-record/reviewer?type=" . $typeId . "&staff_id=" . Yii::$app->user->identity->staff_id;
            $review = Json::decode($this->findCurl()->get($url));
            return $this->renderAjax('@app/modules/system/views/verify-record/reviewer', ['review' => $review]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //判断订单是否已经被绑定过
    public function CheckReOrder($order_no)
    {
        $url = $this->findApiUrl() . $this->_url . "check-re-order";
        $data = Json::decode($this->findCurl()->get($url));
        $orderList = [];
        foreach ($data as $value) {
            $orderNo = $value['order_no'];
            $orderNo = explode(';', $orderNo);
            for ($i = 0; $i < count($orderNo); $i++) {
                array_push($orderList, $orderNo[$i]);
            }
        }
        if (in_array($order_no, $orderList)) {
            return 0;
        } else {
            return 1;
        }
    }

    //判断订单是否能够还款
    public function CheckHkOrder($ord_pay_id, $transdate)
    {
        $url = $this->findApiUrl() . $this->_url . "get-credit-record?ord_pay_id=" . $ord_pay_id;
        $creaditRecord = Json::decode($this->findCurl()->get($url));
        if (count($creaditRecord) != 1) {
            return ['status' => 0, 'msg' => '待付款id:' . $ord_pay_id . '没有信用额度使用信息'];
        }
        if ($creaditRecord[0]['is_repay'] != 0) {
            return ['status' => 0, 'msg' => '待付款id:' . $ord_pay_id . '不是未还款状态!'];
        } else {
            $url = $this->findApiUrl() . $this->_url . "update-is-pay?ord_pay_id=" . $ord_pay_id;
            $data = Json::decode($this->findCurl()->get($url));
            if ($data['status'] == 0) {
                return ['status' => 0, 'msg' => '待付款id:' . $ord_pay_id . '修改还款状态失败,请重新操作!'];
            }
        }
        if (date('Y-m-d', $creaditRecord[0]['user_date']) > date('Y-m-d', $transdate)) {
            return ['status' => 0, 'msg' => '待付款id:' . $ord_pay_id . '还款时间要大于订单支付时间,申请失败!'];
        }
        return ['status' => 1, 'msg' => ''];
    }

    //单笔流水审核详情
    public function actionOneView($rbo_id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-is-sign?rbo_id=" . $rbo_id;
        $data = Json::decode($this->findCurl()->get($url));
        if (count($data) == 1) {
            $url = $this->findApiUrl() . $this->_url . "get-trans-info?transid=" . $data[0]['TRANSID'];//单笔流水信息
            $oneTransInfo = Json::decode($this->findCurl()->get($url));
            $url = $this->findApiUrl() . $this->_url . "get-data-by-rbo-id?rbo_id=" . $rbo_id;//获取rbo_id的所有审核记录
            $rboData = Json::decode($this->findCurl()->get($url));
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
            $url = $this->findApiUrl() . $this->_url . "get-vco-id?rbo_id=" . $rbo_id;
            $vco_id = Json::decode($this->findCurl()->get($url));
            return $this->render('view', ['oneTransInfo' => $oneTransInfo, 'orderSum' => $orderSum, 'orderlist' => $orderlist, 'remarks' => $remarks, 'vco_id' => $vco_id['vco_id']]);
        }
    }

    //抓取数据弹窗
    public function actionGrabData()
    {
        if (Yii::$app->request->getIsPost()) {
            $date = $_POST["date"];
            $url = $this->findApiUrl() . $this->_url . "grab-data?date=" . $date;
            $postData["date"] = $date;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = $curl->post($url);
            return $data;
        }
        $this->layout = "@app/views/layouts/ajax";
        return $this->renderAjax("grab-data");
    }

    //批量申请导入页面
    public function actionBatchImport()
    {
        return $this->render('batchimport');
    }

    //导入模板
    public function actionDownTemplate()
    {
        $headArr = ['订单号', '交易流水号', '说明'];
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "批量收款申请导入模板.xlsx";
        $objPHPExcel = new \PHPExcel();
        $key = "A";
        foreach ($headArr as $v) {
            $colum = $key;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(30);
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
            ->setCellValue('A' . 2, 'ORDINFO2017121500001')
            ->setCellValueExplicit('B' . 2, '060751684999977825999977825', \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('C' . 2, '金额差额原因,其它说明')
            ->setCellValue('A' . 3, 'ORDINFO201801090002')
            ->setCellValueExplicit('B' . 3, '074329204999981327999981327', \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('C' . 3, '金额差额原因,其它说明');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//        $fileName = iconv("utf-8", "gb2312", $fileName);
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

    //导入excel数据
    public function actionImport()
    {
        $model = new UploadForm();
        $model->file = UploadedFile::getInstance($model, 'file');
        $fileName = date('Y-m-d', time()) . '_' . rand(1, 9999);
        $fileExt = $model->file->extension;
        $resultSave = $model->file->saveAs('uploads/' . $fileName . '.' . $fileExt);
        if (!empty($resultSave)) {
            $e2a = new ExcelToArr();
            $arr = $e2a->excel2arry($fileName, $fileExt);
            $tabel = "";
            $tabel .= "<table class='table'><tr><td style='text-align: center'>订单号</td><td style='text-align: center'>流水号</td><td style='text-align: center'>说明</td></tr>";
            for ($i = 2; $i <= count($arr); $i++) {
                $tabel .= "<tr><td><input type='text' style='width: 95%;' value='" . $arr[$i]['A'] . "'></td><td><input type='text' style='width:95%' value='" . $arr[$i]['B'] . "'></td><td><input type='text' style='width:95%' value='" . $arr[$i]['C'] . "'></td></tr>";
            }
            $tabel .= "</table>";
            return $tabel;
        }

    }

    //多笔订单共用一笔流水
    public function actionSaveList()
    {
        try {
            $importList = $_GET['importList'];
            $rbo_id=$_GET['rbo_id'];
            $importList = json_decode($importList, true);
            $transList = [];
            $orderList = [];
            $typeId = BsBusinessType::find()->select('business_type_id')->where(['business_code' => 'batchreceipts'])->asArray()->one();
            $typeId = $typeId['business_type_id'];
            if (Yii::$app->request->getIsPost()) {
                $post = Yii::$app->request->post();
                $post['importList'] = $importList;
                $post['rbo_id'] = $rbo_id;
                $post['type'] = $typeId;
                $post['staff'] = Yii::$app->user->identity->staff_id;
                $url = $this->findApiUrl() . "system/verify-record/batch-review-one";
                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
                $data = Json::decode($curl->post($url));
                if ($data['status']) {
                    return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1, "url" => 'index']);
                } else {
                    return Json::encode(['msg' => $data['msg'] . "送审失败!", 'flag' => 0]);
                }
            }
            for ($i = 0; $i < count($importList); $i++) {
                array_push($transList, $importList[$i]['transid']);
                if (strpos($importList[$i]['order_no'], "；")) {
                    str_replace("；", ";", $importList[$i]['order_no']);

                }
                $order_no = explode(";", $importList[$i]['order_no']);
                for ($j = 0; $j < count($order_no); $j++) {
                    array_push($orderList, $order_no[$j]);
                    $url = $this->findApiUrl() . $this->_url . "get-verif-order?order_no=" . $order_no[$j];//订单是否有审核中的记录
                    $isverifyorder = Json::decode($this->findCurl()->get($url));
                    $url = $this->findApiUrl() . $this->_url . "get-trans-info?transid=" . $importList[$i]['transid'];
                    $transinfo = Json::decode($this->findCurl()->get($url));//单笔流水信息
                    if (count($isverifyorder) > 0) {
                        throw new \Exception("订单号:" . $order_no[$j] . "已被绑定或存在审核中的记录,禁止操作!");
                    }
                    $url = $this->findApiUrl() . $this->_url . "is-recv-or-repay?order_no=" . $order_no[$j];
                    $isOk = Json::decode($this->findCurl()->get($url));
                    if (count($isOk) < 1) {
                        throw new \Exception("订单号:" . $order_no[$j] . "无收还款记录可操作!");
                    }
                    $url=$this->findApiUrl().$this->_url."get-opera-order?order_no=".$order_no[$j];
                    $operad=Json::decode($this->findCurl()->get($url));
                    if ($operad[0]['pac_sname']=="预付款")
                    {
                        $url = $this->findApiUrl() . $this->_url . "get-cmp-info?order_no=" . $order_no[$j];//查询订单的客户代码
                        $cust_code = Json::decode($this->findCurl()->get($url));
                        $url = $this->findApiUrl() . $this->_url . "get-repay-date?cust_code=" . $cust_code;
                        $repayDate = Json::decode($this->findCurl()->get($url));
                        $nowTime = date('Y-m-d h:i:s', time());
                        foreach ($repayDate as $value) {
                            if ($nowTime > $value['repay_date']) {
                                throw new \Exception("该客户有逾期未还款订单,禁止收款!");
                            }
                        }
                    }
                }
                $url = $this->findApiUrl() . $this->_url . "get-verify-trans?transid=" . $importList[$i]['transid'];
                $isverifytrans = Json::decode($this->findCurl()->get($url));
                if (count($isverifytrans) > 0) {
                    throw new \Exception("流水号:" . $importList[$i]['transid'] . "有审核未完成的记录,禁止操作!");
                }
            }
            $transDistinct = array_unique($transList);
            if (count($transList) != count($transDistinct)) {
                throw new \Exception("流水号不能重复!");
            }
            $orderDistinct = array_unique($orderList);
            if (count($orderDistinct) != count($orderList)) {
                throw new \Exception("订单号不能重复!");
            }
            return "{\"status\":"."\"1\"}";
        } catch (\Exception $e) {
            return "{\"msg\":"."\"".$e->getMessage()."\"".",\"status\":"."\"0\"}";
        }

    }
    //同公司多订单对多流水
    public function actionSaveListTwo(){
        try{
            $importList = $_GET['importList'];
            $importList = json_decode($importList, true);
            $orderlist = [];//订单
            $custCode=[];//客户代码
            for ($i = 0; $i < count($importList); $i++)
            {
                $url=$this->findApiUrl().$this->_url."get-cust-code?transid=".$importList[$i]['transid'];
                $CustCode=Json::decode($this->findCurl()->get($url));
                if($CustCode)
                {
                    for ($j=0;$j<count($CustCode);$j++)
                    {
                        array_push($custCode,$CustCode[$j]['cust_code']);
                    }
                }
                $order_no = explode(";", $importList[$i]['order_no']);
                for ($j=0;$j<count($order_no);$j++)
                {
                    array_push($orderlist,$order_no[$j]);
                }
                $url = $this->findApiUrl() . $this->_url . "get-verify-trans?transid=" . $importList[$i]['transid'];
                $isverifytrans = Json::decode($this->findCurl()->get($url));
                if (count($isverifytrans) > 0) {
                    throw new \Exception("流水号:" . $importList[$i]['transid'] . "有审核未完成的记录,禁止操作!");
                }
            }
            $orderList=array_unique($orderlist);//订单去重
            $array_corporate=[];//订单交易法人
            for($i=0;$i<count($orderList);$i++)
            {
                $url=$this->findApiUrl().$this->_url."get-cust-code-by-order?order_no=".$orderList[$i];//获取客户代码
                $code=Json::decode($this->findCurl()->get($url));
                array_push($custCode,$code['cust_code']);
                $url = $this->findApiUrl() . $this->_url . "get-verif-order?order_no=" . $orderList[$i];//订单是否有审核中的记录
                $isverifyorder = Json::decode($this->findCurl()->get($url));
                $url=$this->findApiUrl().$this->_url."get-one-order-info?order_no=".$orderList[$i];//单笔订单信息
                $ordinfo=Json::decode($this->findCurl()->get($url));
                if (count($isverifyorder) > 0) {
                    throw new \Exception("订单号:" . $orderList[$i] . "已被绑定或存在审核中的记录,禁止操作!");
                }
                $url = $this->findApiUrl() . $this->_url . "is-recv-or-repay?order_no=" . $orderList[$i];
                $isOk = Json::decode($this->findCurl()->get($url));
                if (count($isOk) < 1) {
                    throw new \Exception("订单号:" . $orderList[$i]  . "无收还款记录可操作!");
                }
                $url=$this->findApiUrl().$this->_url."get-opera-order?order_no=".$orderList[$i];
                $operad=Json::decode($this->findCurl()->get($url));
                if ($operad[0]['pac_sname']=="预付款")
                {
                    $url = $this->findApiUrl() . $this->_url . "get-cmp-info?order_no=" . $orderList[$i];//查询订单的客户代码
                    $cust_code = Json::decode($this->findCurl()->get($url));
                    $url = $this->findApiUrl() . $this->_url . "get-repay-date?cust_code=" . $cust_code;
                    $repayDate = Json::decode($this->findCurl()->get($url));
                    $nowTime = date('Y-m-d h:i:s', time());
                    foreach ($repayDate as $value) {
                        if ($nowTime > $value['repay_date']) {
                            throw new \Exception("该客户有逾期未还款订单,禁止收款!");
                        }
                    }
                }
                array_push($array_corporate,$ordinfo['corporate']);
            }
            $discorporate=array_unique($array_corporate);
            if(count($discorporate)!=1)
            {
                throw new \Exception("订单号必须为同一交易法人!");
            }
            $custcode=array_unique($custCode);
            if (count($custcode)!=1)
            {
                throw new \Exception("流水号上绑定的的订单都必须是同一公司下的订单!");
            }
            return "{\"status\":"."\"1\"}";

        }
        catch(\Exception $e)
        {
            return "{\"msg\":"."\"".$e->getMessage()."\"".",\"status\":"."\"0\"}";
        }
    }

    //判断订单号和流水号是否填写正确(多笔订单共用一笔流水)
    public function actionCheck()
    {
        $importList = $_POST['importList'];
        $importList = json_decode($importList, true);
        for ($i = 0; $i < count($importList); $i++) {
            $url = $this->findApiUrl() . $this->_url . "get-trans-info?transid=" . $importList[$i]['transid'];
            $transinfo = Json::decode($this->findCurl()->get($url));//单笔流水信息
            if (strpos($importList[$i]['order_no'], "；")) {
                str_replace("；", ";", $importList[$i]['order_no']);

            }
            if (empty($importList[$i]['order_no']))
            {
                return "{\"msg\":" . "\"" . $importList[$i]['order_no'] . "\"" . ",\"status\":\"0\"" . ",\"isok\":" . "\"2\"}";
            }
            if (empty($importList[$i]['transid']))
            {
                return "{\"msg\":" . "\"" . $importList[$i]['order_no'] . "\"" . ",\"status\":\"0\"" . ",\"isok\":" . "\"2\"}";
            }
            $order_no = explode(";", $importList[$i]['order_no']);
            for ($j = 0; $j < count($order_no); $j++) {
                $url = $this->findApiUrl() . $this->_url . "get-order-info?order_no=" . $order_no[$j];//单笔订单信息
                $orderinfo = Json::decode($this->findCurl()->get($url));
                if (count($orderinfo) != 1) {
                    return "{\"msg\":" . "\"" . $orderinfo[$j] . "\"" . ",\"status\":\"0\"" . ",\"isok\":" . "\"0\"}";
                }
            }
            if (empty($transinfo)) {
                return "{\"msg\":" . "\"" . $importList[$i]['transid'] . "\"" . ",\"status\":\"0\"" . ",\"isok\":" . "\"1\"}";
            }
        }
        return $result=$this->IsOkAmount($importList);
    }
   //判断订单号和流水号是否填写正确(同公司多订单对多流水)
    public function actionCheckTwo()
    {
        $importList = $_POST['importList'];
        $importList = json_decode($importList, true);
        $transOrder=[];//把流水号和订单分成新的组
        $transGroup=[];//流水
        for ($i = 0; $i < count($importList); $i++) {
            $url = $this->findApiUrl() . $this->_url . "get-trans-info?transid=" . $importList[$i]['transid'];
            $transinfo = Json::decode($this->findCurl()->get($url));//单笔流水信息
            if (strpos($importList[$i]['order_no'], "；")) {
                str_replace("；", ";", $importList[$i]['order_no']);

            }
            if (empty($importList[$i]['order_no']))
            {
                return "{\"msg\":" . "\"" . $importList[$i]['order_no'] . "\"" . ",\"status\":\"0\"" . ",\"isok\":" . "\"2\"}";
            }
            if (empty($importList[$i]['transid']))
            {
                return "{\"msg\":" . "\"" . $importList[$i]['order_no'] . "\"" . ",\"status\":\"0\"" . ",\"isok\":" . "\"2\"}";
            }
            $order_no = explode(";", $importList[$i]['order_no']);
            for ($j = 0; $j < count($order_no); $j++) {
                $url = $this->findApiUrl() . $this->_url . "get-order-info?order_no=" . $order_no[$j];//单笔订单信息
                $orderinfo = Json::decode($this->findCurl()->get($url));
                if (count($orderinfo) != 1) {
                    return "{\"msg\":" . "\"" . $orderinfo[$j] . "\"" . ",\"status\":\"0\"" . ",\"isok\":" . "\"0\"}";
                }
            }
            if (empty($transinfo)) {
                return "{\"msg\":" . "\"" . $importList[$i]['transid'] . "\"" . ",\"status\":\"0\"" . ",\"isok\":" . "\"1\"}";
            }
            $trsOrder=$importList[$i]['transid'].";".$importList[$i]['order_no'];
            array_push($transOrder,$trsOrder);
            array_push($transGroup,$importList[$i]['transid']);
        }
        $disTransOrder=array_unique($transOrder);
        $distransGroup=array_unique($transGroup);
        if (count($transOrder)!=count($disTransOrder))
        {
            return "{\"msg\":\"请保持流水+订单号的唯一性!\"," . "\"status\":\"0\"}";
        }
        if(count($distransGroup)!=count($transGroup))
        {
            return "{\"msg\":\"流水号请保持唯一性!\"," . "\"status\":\"0\"}";
        }
        return $result=$this->IsOkAmountTwo($importList);
    }
    //判断订单金额与流水金额是否满足申请条件(多笔订单共用一笔流水)
    public function IsOkAmount($importList)
    {
        $params = require("../config/params.php");
        $p_dif = $params['p_dif'];//订单差额参数
        for ($i = 0; $i < count($importList); $i++) {
            $orderAcounmt = 0.000;//所有可操作订单总金额
            $ordReAcounmt = 0.000;//所有订单退款金额
            $url = $this->findApiUrl() . $this->_url . "get-trans-info?transid=" . $importList[$i]['transid'];
            $transinfo = Json::decode($this->findCurl()->get($url));//单笔流水信息
            if (strpos($importList[$i]['order_no'], "；")) {
                str_replace("；", ";", $importList[$i]['order_no']);
            }
            $order_no = explode(";", $importList[$i]['order_no']);
            if (count($order_no) == 1) {
                $url = $this->findApiUrl() . $this->_url . "get-is-first-use?transid=" . $importList[$i]['transid'];
                $isfirst = Json::decode($this->findCurl()->get($url));
                if (count($isfirst) > 0) {//流水已经使用过了
                    $url=$this->findApiUrl().$this->_url."get-is-full-amount?order_no=".$order_no[0];
                    $isfull=Json::decode($this->findCurl()->get($url));
                    $url = $this->findApiUrl() . $this->_url . "get-pass-trans?transid=" . $importList[$i]['transid'];
                    $useMoney = Json::decode($this->findCurl()->get($url));//流水使用金额
                    $surplusMoney = ((float)$transinfo['TXNAMT'] * 1000 - (float)$useMoney[0]['totalmoney'] * 1000) / 1000;//流水剩余金额
                    if (count($isfull)>1)//分期或者帐信自保+担保
                    {

                        $url=$this->findApiUrl().$this->_url."get-opera-order?order_no=".$order_no[0];
                        $operad=Json::decode($this->findCurl()->get($url));
                        if ($operad[0]['pac_sname']=="预付款")
                        {
                            $url=$this->findApiUrl().$this->_url."get-orderby-stage?order_no=".$order_no[0];//排序
                            $orderby=Json::decode($this->findCurl()->get($url));
                            if(((float)$orderby[0]['stag_cost']*1000-$surplusMoney*1000)/1000>$p_dif)
                            {
                                return "{\"msg\":\"订单总金额-流水金额不能大于20元!\"," . "\"status\":\"0\"}";
                            }
                        }
                        else if($operad[0]['pac_sname']=="信用额度支付")
                        {
                            $url=$this->findApiUrl().$this->_url."get-orderby-credit?order_no=".$order_no[0];//排序
                            $orderby=Json::decode($this->findCurl()->get($url));
                            if (((float)$orderby[0]['stag_cost']*1000-$surplusMoney*1000)/1000>$p_dif)
                            {
                                return "{\"msg\":\"订单总金额-流水金额不能大于20元!\"," . "\"status\":\"0\"}";
                            }
                        }
                    }
                    else{
                        $orderAcounmt=(float)$isfull[0]['stag_cost'];
                        $url = $this->findApiUrl() . $this->_url . "get-ref-money?order_no=" . $order_no[0];
                        $getremoney = Json::decode($this->findCurl()->get($url));
                        for ($t = 0; $t < count($getremoney); $t++) {
                            $ordReAcounmt = ($ordReAcounmt * 1000 + (float)$getremoney[$t]['tax_fee'] * 1000) / 1000;
                        }
                        if(($orderAcounmt*1000-$ordReAcounmt*1000-(float)$transinfo['TXNAMT']*1000)/1000>$p_dif)
                        {
                            return "{\"msg\":\"订单总金额-流水金额不能大于20元!\"," . "\"status\":\"0\"}";
                        }
                    }

                } else {//流水从未被使用过
                    $url=$this->findApiUrl().$this->_url."get-is-full-amount?order_no=".$order_no[0];
                    $isfull=Json::decode($this->findCurl()->get($url));
                    if (count($isfull)>1)//分期或者帐信自保+担保
                    {
                        $url=$this->findApiUrl().$this->_url."get-opera-order?order_no=".$order_no[0];
                        $operad=Json::decode($this->findCurl()->get($url));
                        if ($operad[0]['pac_sname']=="预付款")
                        {
                            $url=$this->findApiUrl().$this->_url."get-orderby-stage?order_no=".$order_no[0];//排序
                            $orderby=Json::decode($this->findCurl()->get($url));
                            if(((float)$orderby[0]['stag_cost']*1000-(float)$transinfo['TXNAMT']*1000)/1000>$p_dif)
                            {
                                return "{\"msg\":\"订单总金额-流水金额不能大于20元!\"," . "\"status\":\"0\"}";
                            }
                        }
                        else if($operad[0]['pac_sname']=="信用额度支付")
                        {
                            $url=$this->findApiUrl().$this->_url."get-orderby-credit?order_no=".$order_no[0];//排序
                            $orderby=Json::decode($this->findCurl()->get($url));
                             if (((float)$orderby[0]['stag_cost']*1000-(float)$transinfo['TXNAMT']*1000)/1000>$p_dif)
                             {
                                 return "{\"msg\":\"订单总金额-流水金额不能大于20元!\"," . "\"status\":\"0\"}";
                             }
                        }
                    }
                    else{
                        $orderAcounmt=(float)$isfull[0]['stag_cost'];
                        $url = $this->findApiUrl() . $this->_url . "get-ref-money?order_no=" . $order_no[0];
                        $getremoney = Json::decode($this->findCurl()->get($url));
                        for ($t = 0; $t < count($getremoney); $t++) {
                            $ordReAcounmt = ($ordReAcounmt * 1000 + (float)$getremoney[$t]['tax_fee'] * 1000) / 1000;
                        }
                        if(($orderAcounmt*1000-$ordReAcounmt*1000-(float)$transinfo['TXNAMT']*1000)/1000>$p_dif)
                        {
                            return "{\"msg\":\"订单总金额-流水金额不能大于20元!\"," . "\"status\":\"0\"}";
                        }
                    }
                }
            } else {

                for ($k = 0; $k < count($order_no); $k++) {
                    $url = $this->findApiUrl() . $this->_url . "get-opera-order?order_no=" . $order_no[$k];
                    $data = Json::decode($this->findCurl()->get($url));
                    for ($j = 0; $j < count($data); $j++) {
                        $orderAcounmt = ($orderAcounmt * 1000 + (float)$data[$j]['stag_cost'] * 1000) / 1000;
                    }
                    $url = $this->findApiUrl() . $this->_url . "get-ref-money?order_no=" . $order_no[$k];
                    $getremoney = Json::decode($this->findCurl()->get($url));
                    for ($t = 0; $t < count($getremoney); $t++) {
                        $ordReAcounmt = ($ordReAcounmt * 1000 + (float)$getremoney[$t]['tax_fee'] * 1000) / 1000;
                    }
                }
                if (($orderAcounmt * 1000 - $ordReAcounmt * 1000) / 1000 != (float)$transinfo['TXNAMT']) {
                    if (empty($importList[$i]['remark'])) {
                        return "{\"msg\":\"当订单金额不等于流水金额的时候说明不能为空!\"," . "\"status\":\"0\"}";
                    }
                }
                if (($orderAcounmt * 1000 - $ordReAcounmt * 1000 - (float)$transinfo['TXNAMT'] * 1000) / 1000 > $p_dif) {
                    return "{\"msg\":\"订单总金额-流水金额不能大于20元!\"," . "\"status\":\"0\"}";
                }
            }
        }
        return "{\"status\":\"1\"}";
    }
    //判断订单金额与流水金额是否满足申请条件(同公司多订单对多流水)
    public function IsOkAmountTwo($importList)
    {
        $orderNo=[];
        $ordpayid=[];//存放所有流水绑定的ord_pay_id
        $atm=0.000;//订单金额
        $allRefMoney=0.000;//所有订单退款金额
        $txnamt=0.000;//收款金额
        $params = require("../config/params.php");
        $p_dif = $params['p_dif'];//订单差额参数
        for ($i=0;$i<count($importList);$i++)
        {
            $order_no = explode(";", $importList[$i]['order_no']);
            for ($j=0;$j<count($order_no);$j++)
            {
                array_push($orderNo,$order_no[$j]);
            }
            $url=$this->findApiUrl().$this->_url."get-binded?transid=".$importList[$i]['transid'];
            $ordPay=Json::decode($this->findCurl()->get($url));
                for ($j=0;$j<count($ordPay);$j++)
                {
                    array_push($ordpayid,$ordPay[$j]['ord_pay_id']);
                }
            $url=$this->findApiUrl().$this->_url."get-trans-info?transid=".$importList[$i]['transid'];
            $transinfo=Json::decode($this->findCurl()->get($url));
            $txnamt=($txnamt*1000+(float)$transinfo['TXNAMT']*1000)/1000;
        }
        $order_No=array_unique($orderNo);//订单去重
        if (count($order_No)>1)
        {
            $partAtm=0.000;
            for($i=0;$i<count($order_No);$i++)
            {
                $url=$this->findApiUrl().$this->_url."get-opera-order?order_no=".$order_No[$i];
                $opeorder=Json::decode($this->findCurl()->get($url));
                for ($j=0;$j<count($opeorder);$j++)
                {
                    array_push($ordpayid,$opeorder[$j]['ord_pay_id']);
                }
                $url=$this->findApiUrl().$this->_url."get-ref-money?order_no=".$order_No[$i];
                $fee=Json::decode($this->findCurl()->get($url));
                for ($j=0;$j<count($fee);$j++)
                {
                    $allRefMoney=($allRefMoney*1000+(float)$fee[$j]['tax_fee']*1000)/1000;
                }
            }
            $ordpayId=array_unique($ordpayid);
            for($i=0;$i<count($ordpayId);$i++)
            {
                $url=$this->findApiUrl().$this->_url."ord-pay-info?ord_pay_id=".$ordpayId[$i];
                $payInfo=Json::decode($this->findCurl()->get($url));
                $partAtm=($atm*1000+(float)$payInfo['stag_cost']*1000)/1000;
            }
            $url=$this->findApiUrl().$this->_url."get-orderby-stage?order_no=".$order_No[0];
            $data=Json::decode($this->findCurl()->get($url));
            $atm=((float)$data[0]['stag_cost']*1000+$partAtm*1000)/1000;
            if($atm-$txnamt>$p_dif)
            {
                return "{\"msg\":\"订单总金额-流水金额不能大于20元!\"," . "\"status\":\"0\"}";
            }
        }
        else{
            for($i=0;$i<count($order_No);$i++)
            {
                $url=$this->findApiUrl().$this->_url."get-opera-order?order_no=".$order_No[$i];
                $opeorder=Json::decode($this->findCurl()->get($url));
                for ($j=0;$j<count($opeorder);$j++)
                {
                    array_push($ordpayid,$opeorder[$j]['ord_pay_id']);
                }
                $url=$this->findApiUrl().$this->_url."get-ref-money?order_no=".$order_No[$i];
                $fee=Json::decode($this->findCurl()->get($url));
                for ($j=0;$j<count($fee);$j++)
                {
                    $allRefMoney=($allRefMoney*1000+(float)$fee[$j]['tax_fee']*1000)/1000;
                }
            }
            $ordPayid=array_unique($ordpayid);
            for($i=0;$i<count($ordPayid);$i++)
            {
                $url=$this->findApiUrl().$this->_url."ord-pay-info?ord_pay_id=".$ordPayid[$i];
                $payInfo=Json::decode($this->findCurl()->get($url));
                $atm=($atm*1000+(float)$payInfo['stag_cost']*1000)/1000;
            }
            if ($atm-$txnamt>$p_dif)
            {
                return "{\"msg\":\"订单总金额-流水金额不能大于20元!\"," . "\"status\":\"0\"}";
            }

        }
        return "{\"status\":\"1\"}";
    }
    //判断是批量还是单笔驳回
    public function actionIsBatchOrSign($rbo_id)
    {
        $url=$this->findApiUrl().$this->_url."get-is-batch-or-sign?rbo_id=".$rbo_id;
        $model=$this->findCurl()->get($url);
        return $model;
    }
    //批量送審(多笔订单共用一笔流水)
    public function actionVerifySaveList()
    {
        $importList = $_GET['importList'];
        $rbo_id=$_GET['rbo_id'];
        $importList = json_decode($importList, true);
        $typeId = BsBusinessType::find()->select('business_type_id')->where(['business_code' => 'batchreceipts'])->asArray()->one();
        $typeId = $typeId['business_type_id'];
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['importList'] = $importList;
            $post['rbo_id'] = $rbo_id;
            $post['type'] = $typeId;
            $post['staff'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . "system/verify-record/batch-review-one";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1, "url" =>'index']);
            } else {
                return Json::encode(['msg' => $data['msg'] . "送审失败!", 'flag' => 0]);
            }
        }
        $this->layout = "@app/views/layouts/ajax";
        $url = $this->findApiUrl() . "/system/verify-record/reviewer?type=" . $typeId . "&staff_id=" . Yii::$app->user->identity->staff_id;
        $review = Json::decode($this->findCurl()->get($url));
        return $this->renderAjax('@app/modules/system/views/verify-record/reviewer', ['review' => $review]);
    }
    //批量送審(同公司多流水对多订单)
    public function actionVerifySaveListTwo()
    {
        $importList = $_GET['importList'];
        $rbo_id=$_GET['rbo_id'];
        $importList = json_decode($importList, true);
        $typeId = BsBusinessType::find()->select('business_type_id')->where(['business_code' => 'batchreceipts'])->asArray()->one();
        $typeId = $typeId['business_type_id'];
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['importList'] = $importList;
            $post['rbo_id'] = $rbo_id;
            $post['type'] = $typeId;
            $post['staff'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . "system/verify-record/batch-review-two";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1, "url" =>'index']);
            } else {
                return Json::encode(['msg' => $data['msg'] . "送审失败!", 'flag' => 0]);
            }
        }
        $this->layout = "@app/views/layouts/ajax";
        $url = $this->findApiUrl() . "/system/verify-record/reviewer?type=" . $typeId . "&staff_id=" . Yii::$app->user->identity->staff_id;
        $review = Json::decode($this->findCurl()->get($url));
        return $this->renderAjax('@app/modules/system/views/verify-record/reviewer', ['review' => $review]);
    }
    //批量审核详情
    public function actionBatchView($rbo_id)
    {
        $url=$this->findApiUrl().$this->_url."get-trans-batch?rbo_id=".$rbo_id;
        $data=Json::decode($this->findCurl()->get($url));
        for($j=0;$j<count($data);$j++)
        {
            $url=$this->findApiUrl().$this->_url."get-order-no?transid=".$data[$j]['TRANSID']."&rboid=".$rbo_id;
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
            $url=$this->findApiUrl().$this->_url."get-batch-money?transid=".$data[$j]['TRANSID']."&rboid=".$rbo_id;
            $anyMoney=Json::decode($this->findCurl()->get($url));
            $data[$j]['stag_cost']=$anyMoney[0]['stag_cost'];
            $url=$this->findApiUrl().$this->_url."get-batch-remark?transid=".$data[$j]['TRANSID']."&rboid=".$rbo_id;
            $remark=Json::decode($this->findCurl()->get($url));
            $data[$j]['remark']=$remark[0]['remark'];
        }
        $url = $this->findApiUrl() . $this->_url . "get-batch-vco-id?rbo_id=" . $rbo_id;
        $vco_id = Json::decode($this->findCurl()->get($url));
     return $this->render('batch-view',['data'=>$data,'vco_id'=>$vco_id['vco_id']]);
    }
    //批量驳回重新申请页面
    public function actionBatchRejectView()
    {
        $rbo_id=$_GET['rbo_id'];
        $url=$this->findApiUrl().$this->_url."get-trans-batch?rbo_id=".$rbo_id;
        $data=Json::decode($this->findCurl()->get($url));
        for($j=0;$j<count($data);$j++)
        {
            $url=$this->findApiUrl().$this->_url."get-order-no?transid=".$data[$j]['TRANSID']."&rboid=".$rbo_id;
            $orderno=Json::decode($this->findCurl()->get($url));
            $order_no="";
            for($i=0;$i<count($orderno);$i++)
            {
                if($i==count($orderno)-1)
                {
                    $order_no.=$orderno[$i]['ord_no'];
                }
                else{
                    $order_no.=$orderno[$i]['ord_no'].";";
                }
            }
            $data[$j]['ord_no']=$order_no;
            $url=$this->findApiUrl().$this->_url."get-batch-money?transid=".$data[$j]['TRANSID']."&rboid=".$rbo_id;
            $anyMoney=Json::decode($this->findCurl()->get($url));
            $data[$j]['stag_cost']=$anyMoney[0]['stag_cost'];
            $url=$this->findApiUrl().$this->_url."get-batch-remark?transid=".$data[$j]['TRANSID']."&rboid=".$rbo_id;
            $remark=Json::decode($this->findCurl()->get($url));
            $data[$j]['remark']=$remark[0]['remark'];
        }
        return $this->render('batch-reject',['rbo_id'=>$rbo_id,'data'=>$data]);
    }
}