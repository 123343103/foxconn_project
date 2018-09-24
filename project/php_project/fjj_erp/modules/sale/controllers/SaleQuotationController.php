<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\models\User;
use app\modules\system\models\SystemLog;
use yii\helpers\Json;
use yii;
use yii\web\NotFoundHttpException;

class SaleQuotationController extends BaseController
{
    private $_url = "sale/sale-quotation/";  //对应api控制器URL
//    public function beforeAction($action)
//    {
//        $this->ignorelist=array_merge($this->ignorelist,[
//            "/crm/crm-all-customer/select-columns",
//        ]);
//        return parent::beforeAction($action);
//    }
    /**
     * @return mixed|string
     * 报价单列表
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
        return $this->findCurl()->get($url);
        }
        $downList = $this->getDownList();
//        dumpE($this->findCurl()->get($url));
        return $this->render('index', [
            'downList' => $downList,
            'queryParam'=>$queryParam,
        ]);
    }

    public function actionSelectColumns()
    {
        if($post = Yii::$app->request->post()){
            $url = $this->findApiUrl() . $this->_url . "index";
            $queryParam = Yii::$app->request->queryParams;
            if (!empty($queryParam)) {
                $url .= "?" . http_build_query($queryParam);
            }
            $columns = [];
            if (!empty($post['columns'])) {
                foreach ($post['columns'] as $k => $v) {
                    $kv = explode('-',$v);
                    $columns[$kv[0]] = $kv[1];
                }
//                dumpE($columns);
                $this->export(Json::decode($this->findCurl()->get($url))['rows'],$columns);
            }
        }
        return $this->renderAjax('select-columns',[]);
    }

    /*客户类型等下拉菜单*/
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    //数据导出
    private function export($data,$columns)
    {
        $objPHPExcel = new \PHPExcel();
        $index = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH'];
        $i = 0;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '序号');
        foreach ($columns as $tk => $tv) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($index[$i+1].'1', $tv);
            $i++;
        }
        foreach ($data as $key => $val) {
            $num = $key + 2;
            $i = 1;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $num-1);
            foreach ($columns as $k => $v) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($index[$i].$num,$val[$k]);
                $i++;
            }
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
