<?php
/**
 * User: F1677929
 * Date: 2017/12/13
 */
namespace app\modules\warehouse\controllers;

use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * 收货通知控制器
 */
class ReceiptNoticeController extends BaseController
{
    //权限过滤
    public function beforeAction($action)
    {
        $this->ignorelist = array_merge($this->ignorelist, [
            "/warehouse/receipt-notice/view",
        ]);
        return parent::beforeAction($action);
    }

    //列表
    public function actionList()
    {
        if (Yii::$app->request->isAjax) {
            $url = $this->findApiUrl() . 'warehouse/receipt-notice/list';
            $url .= '?' . http_build_query(Yii::$app->request->queryParams);
            $data = $this->findCurl()->get($url);
            $data = json_decode($data, true);
            foreach ($data['rows'] as &$val) {
                $val['rcpnt_no_val']=$val['rcpnt_no'];
                $val['rcpnt_no']="<a onclick='window.location.href=\"".Url::to(['view','id'=>$val['rcpnt_id']])."\";event.stopPropagation();'>".$val['rcpnt_no']."</a>";
            }
            return json_encode($data);
        }

        $fields = $this->getField("/warehouse/receipt-notice/list");
        return $this->render('list', ['fields' => $fields]);
    }

    //获取料号信息-列表、详情共用
    public function actionGetPno()
    {
        $url = $this->findApiUrl() . 'warehouse/receipt-notice/get-pno';
        $url .= '?' . http_build_query(Yii::$app->request->queryParams);
        return $this->findCurl()->get($url);
    }

    //生成收货单
    public function actionGenerateReceiptBill($id)
    {
        $url = $this->findApiUrl() . 'warehouse/receipt-notice/generate-receipt-bill';
        $url .= '?id=' . $id;
        if ($data = Yii::$app->request->post()) {
            $data['RcpNotice']['operator'] = Yii::$app->user->identity->staff_id;
            $data['RcpNotice']['operate_date'] = date('Y-m-d');
            $data['RcpNotice']['operate_ip'] = Yii::$app->request->getUserIP();
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
            $result = json_decode($curl->post($url), true);
            if ($result['status'] == 1) {
                SystemLog::addLog('生成收货单');
                return json_encode(['msg' => $result['msg'], 'flag' => 1]);
            }
            return json_encode(['msg' => $result['msg'], 'flag' => 0]);
        }
        $this->layout = "@app/views/layouts/ajax.php";
        $url .= "&staff_id=" . Yii::$app->user->identity->staff_id;
        $data = json_decode($this->findCurl()->get($url), true);
        return $this->render('generate-receipt-bill', ['data' => $data]);
    }

    //取消收货
    public function actionCancelReceipt($id)
    {
        if ($data = Yii::$app->request->post()) {
            $url = $this->findApiUrl() . 'warehouse/receipt-notice/cancel-receipt';
            $url .= '?id=' . $id;
            $data['RcpNotice']['operator'] = Yii::$app->user->identity->staff_id;
            $data['RcpNotice']['operate_date'] = date('Y-m-d');
            $data['RcpNotice']['operate_ip'] = Yii::$app->request->getUserIP();
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
            $result = json_decode($curl->post($url), true);
            if ($result['status'] == 1) {
                SystemLog::addLog('取消收货');
                return json_encode(['msg' => $result['msg'], 'flag' => 1]);
            }
            return json_encode(['msg' => $result['msg'], 'flag' => 0]);
        }
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render('cancel-receipt');
    }

    //导出
    public function actionExport()
    {
        $headArr[] = [
            "序号" => 7,
            "收货通知单号" => 25,
            "单据状态" => 15,
            "单据类型" => 15,
            "出仓仓库" => 15,
            "采购部门" => 15,
            "收货中心" => 15,
            "关联单号" => 30,
            "通知人" => 15,
            "通知日期" => 15,
            "操作人" => 15,
            "操作日期" => 15
        ];
        $url = $this->findApiUrl() . 'warehouse/receipt-notice/list';
        $url .= '?' . http_build_query(Yii::$app->request->queryParams);
        $data = json_decode($this->findCurl()->get($url), true);
        $rows = array_merge($headArr, $data['rows']);
        $objPHPExcel = new \PHPExcel();
        $column = "A";
        foreach ($rows as $key => $val) {
            foreach ($val as $k => $v) {
                if ($key == 0) {
                    $objPHPExcel->getActiveSheet()->getStyle($column)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth($v);
                    $v = $k;
                }
                if ($key > 0 && $k == key($val)) {
                    $v = $key;
                }
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . ($key + 1), $v);
                $column++;
            }
            $column = "A";
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="receipt_notice.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    //详情
    public function actionView($id)
    {
        $url = $this->findApiUrl() . 'warehouse/receipt-notice/view';
        $url .= '?id=' . $id;
        $data = json_decode($this->findCurl()->get($url), true);
        if (empty($data)) {
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('view', ['viewData' => $data]);
    }
}