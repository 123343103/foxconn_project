<?php

namespace app\modules\rpt\controllers;
use app\controllers\BaseController;
use yii;
use yii\helpers\Json;

class StockController extends BaseController
{
    private $_url = "rpt/stock/";

    public function actionIndex()
    {
      $curl=$this->findCurl();
      if(\Yii::$app->request->isAjax){
          $params=\Yii::$app->request->queryParams;
          $url=$this->findApiUrl().$this->_url."index?".http_build_query($params);
          return $this->findCurl()->get($url);
      }
      return $this->render("index");
    }
    //导出库存报表
    public function actionExport()
    {
        $url = $this->findApiUrl() . 'rpt/stock/index';

//        $url = $this->findApiUrl() . 'warehouse/receipt-center-set/index';
        $url .= '?' . http_build_query(\Yii::$app->request->queryParams);
        $data = json_decode($this->findCurl()->get($url), true);
        dumpE($data);
        $columnWidth[] = [7, 25, 20, 10, 40, 12, 15, 25, 50];
        $headArr[] = ["序号", "法人", "创业公司", "仓库名称", "鸿海料号", "商品名称", "规格", "仓库代码", "储位","最后异动日期","单位","库存量","库存金额","币别","备注"];
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
                    $v = yii\helpers\Html::decode($v);//解码Command.php中的编码
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
}