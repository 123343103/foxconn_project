<?php
/**
 * User: F1677929
 * Date: 2017/12/4
 */
namespace app\modules\warehouse\controllers;
use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/**
 * 收货中心设置控制器
 */
class ReceiptCenterSetController extends BaseController
{
    //权限过滤
    public function beforeAction($action)
    {
        $this->ignorelist = array_merge($this->ignorelist, [
            "/warehouse/receipt-center-set/view",
        ]);
        return parent::beforeAction($action);
    }

    //收货中心设置列表
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {
            $url = $this->findApiUrl() . 'warehouse/receipt-center-set/index';
            $url .= '?' . http_build_query(Yii::$app->request->queryParams);
            return $this->findCurl()->get($url);
        }
        return $this->render('index');
    }

    //新增收货中心设置
    public function actionAdd()
    {
        $url = $this->findApiUrl() . 'warehouse/receipt-center-set/add';
        if ($data = Yii::$app->request->post()) {
            $data['BsReceipt']['creator'] = Yii::$app->user->identity->staff_id;
            $data['BsReceipt']['creat_date'] = date('Y-m-d H:i:s');
            $data['BsReceipt']['operate_ip'] = Yii::$app->request->getUserIP();
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
            $result = json_decode($curl->post($url), true);
            if ($result['status'] == 1) {
                SystemLog::addLog('新增收货中心设置' . $result['data']['code']);
                return json_encode(['msg' => $result['msg'], 'flag' => 1]);
            }
            return json_encode(['msg' => $result['msg'], 'flag' => 0]);
        }
        $data = json_decode($this->findCurl()->get($url), true);
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render('add', ['data' => $data]);
    }

    //修改收货中心设置
    public function actionEdit($id)
    {
        $url = $this->findApiUrl() . 'warehouse/receipt-center-set/edit';
        $url .= '?id=' . $id;
        if ($data = Yii::$app->request->post()) {
            $data['BsReceipt']['operator'] = Yii::$app->user->identity->staff_id;
            $data['BsReceipt']['operate_date'] = date('Y-m-d H:i:s');
            $data['BsReceipt']['operate_ip'] = Yii::$app->request->getUserIP();
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
            $result = json_decode($curl->post($url), true);
            if ($result['status'] == 1) {
                SystemLog::addLog('修改收货中心设置' . $result['data']['code']);
                return json_encode(['msg' => $result['msg'], 'flag' => 1]);
            }
            return json_encode(['msg' => $result['msg'], 'flag' => 0]);
        }
        $data = json_decode($this->findCurl()->get($url), true);
        if (empty($data['editData'])) {
            throw new NotFoundHttpException('页面未找到！');
        }
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render('edit', ['data' => $data]);
    }

    //查看收货中心设置
    public function actionView($id)
    {
        $url = $this->findApiUrl() . 'warehouse/receipt-center-set/view';
        $url .= '?id=' . $id;
        $data = json_decode($this->findCurl()->get($url), true);
        if (empty($data)) {
            throw new NotFoundHttpException('页面未找到！');
        }
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render('view', ['viewData' => $data]);
    }

    //地址联动
    public function actionGetDistrict($id)
    {
        $url = $this->findApiUrl() . 'warehouse/receipt-center-set/get-district';
        $url .= '?id=' . $id;
        return $this->findCurl()->get($url);
    }

    //启用禁用收货中心设置
    public function actionOperation($id, $flag)
    {
        $url = $this->findApiUrl() . 'warehouse/receipt-center-set/operation';
        $url .= '?id=' . $id;
        $url .= '&flag=' . $flag;
        $result = json_decode($this->findCurl()->get($url), true);
        SystemLog::addLog('启用/禁收货中心设置');
        return json_encode(['msg' => $result['msg'], 'flag' => 1]);
    }

    //导出收货中心设置
    public function actionExport()
    {
        $url = $this->findApiUrl() . 'warehouse/receipt-center-set/index';
        $url .= '?' . http_build_query(Yii::$app->request->queryParams);
        $data = json_decode($this->findCurl()->get($url), true);
        $columnWidth[] = [7, 25, 20, 10, 40, 12, 15, 25, 50];
        $headArr[] = ["序号", "编码", "收货中心名称", "状态", "详细地址", "联系人", "联系方式", "邮箱", "备注"];
        $rows = array_merge($columnWidth, $headArr, $data['rows']);
        $objPHPExcel = new \PHPExcel();
        $column = "A";
        foreach ($rows as $key => $val) {
            foreach ($val as $k => $v) {
                if ($key == 0) {
                    //设置水平居中
                    $objPHPExcel->getActiveSheet()->getStyle($column)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    //设置垂直居中
                    $objPHPExcel->getActiveSheet()->getStyle($column)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    //设置自动换行
                    $objPHPExcel->getActiveSheet()->getStyle($column)->getAlignment()->setWrapText(true);
                    //设置宽度
                    $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth($v);
                }
                if ($key > 0) {
                    if ($key > 1 && $k == key($val)) {
                        $v = $key - 1;
                    }
                    $v = Html::decode($v);//解码Command.php中的编码
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $key, $v);
                }
                $column++;
            }
            $column = "A";
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="receipt_center.xlsx"');
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

    //获取收货中心信息(用于采购)
    public function actionReceiptInfo()
    {
        $url = $this->findApiUrl() . "warehouse/receipt-center-set/receipt-info";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render('receipt-info', [
            'rcpno' => $queryParam
        ]);
    }
}



