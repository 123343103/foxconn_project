<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use yii;
use yii\helpers\Html;
use yii\helpers\Json;

class CrmAllCustomerController extends BaseController
{
    private $_url = "crm/crm-all-customer/";  //对应api控制器URL

    public function beforeAction($action)
    {
        $this->ignorelist = array_merge($this->ignorelist, [
            "/crm/crm-all-customer/select-columns",
        ]);
        return parent::beforeAction($action);
    }

    /**
     * @return mixed|string
     * 客户资料列表
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        $export = Yii::$app->request->get('export');
        if (!empty($queryParam) ) {
            $url .= "?" . http_build_query($queryParam);
        }

        $data = $this->findCurl()->get($url);

        if (isset($export)) {
            $this->getExcelData(Json::decode($data)['rows']);
        }
//        var_dump($data);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $this->findCurl()->get($url);
        }
        $downList = $this->getDownList();
//        dumpE($this->findCurl()->get($url));
        return $this->render('index', [
            'downList' => $downList,
            'queryParam' => $queryParam,
        ]);
    }

    public function actionSelectColumns()
    {
        if ($post = Yii::$app->request->post()) {
            $url = $this->findApiUrl() . $this->_url . "index";
            $queryParam = Yii::$app->request->queryParams;
            if (!empty($queryParam)) {
                $url .= "?" . http_build_query($queryParam);
            }
            $columns = [];
            if (!empty($post['columns'])) {
                foreach ($post['columns'] as $k => $v) {
                    $kv = explode('-', $v);
                    $columns[$kv[0]] = $kv[1];
                }

//                dumpE($columns);
                $this->export(Json::decode($this->findCurl()->get($url))['rows'], $columns);
            }
        }
        return $this->renderAjax('select-columns', []);
    }

    /*客户类型等下拉菜单*/
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    //数据导出
//    private function export($data, $columns)
//    {
////        $columns=
//        $objPHPExcel = new \PHPExcel();
//        $index = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH'];
//        $i = 0;
//        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '序号');
//        foreach ($columns as $tk => $tv) {
//            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($index[$i + 1] . '1', $tv);
//            $i++;
//        }
//        foreach ($data as $key => $val) {
//            $num = $key + 2;
//            $i = 1;
//            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $num, $num - 1);
//            foreach ($columns as $k => $v) {
//                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($index[$i] . $num, $val[$k]);
//                $i++;
//            }
//        }
//        $date = date("Y_m_d", time()) . rand(0, 99);
//        $fileName = "_{$date}.xls";
//        // 创建PHPExcel对象，注意，不能少了\
//        $fileName = iconv("utf-8", "gb2312", $fileName);
//        $objPHPExcel->setActiveSheetIndex(0);
//        ob_end_clean(); // 清除缓冲区,避免乱码
//        header('Content-Type: application/vnd.ms-excel');
//        header("Content-Disposition: attachment;filename=" . $fileName);
//        header('Cache-Control: max-age=0');
//
//        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//        $objWriter->save('php://output'); // 文件通过浏览器下载
//        exit();
//    }

    public function getExcelData($data)
    {
        $objPHPExcel = new \PHPExcel();
        $date = date('Y-m-d-H-i-s', time()) . '_' . rand(0, 99);//加随机数，防止重名
        $fileName = '客户资料' . "_{$date}.xls";
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '系统编号')
            ->setCellValue('C1', '客户代码')
            ->setCellValue('D1', '客户名称')
            ->setCellValue('E1', '客户简称')
            ->setCellValue('F1', '客户属性')
            ->setCellValue('G1', '客户类型')
            ->setCellValue('H1', '等级')
            ->setCellValue('I1', '公司电话')
            ->setCellValue('J1', '传真')
            ->setCellValue('K1', '公司法人')
            ->setCellValue('L1', '公司网址')
            ->setCellValue('M1', '联系人')
            ->setCellValue('N1', '联系电话')
            ->setCellValue('O1', '邮箱')
            ->setCellValue('P1', '客户经理人')
            ->setCellValue('Q1', '所在地区')
            ->setCellValue('R1', '所在军区')
            ->setCellValue('S1', '公司地址')
            ->setCellValue('T1', '公司规模')
            ->setCellValue('U1', '需求类目')
            ->setCellValue('V1', '经营类型')
            ->setCellValue('W1', '行业类型')
            ->setCellValue('X1', '公司属性')
            ->setCellValue('Y1', '员工数')
            ->setCellValue('Z1', '注册时间')
            ->setCellValue('AA1', '注册资金')
            ->setCellValue('AB1', '是否上市')
            ->setCellValue('AC1', '年营业额')
            ->setCellValue('AD1', '税籍编码')
            ->setCellValue('AE1', '是否会员')
            ->setCellValue('AF1', '会员类型')
            ->setCellValue('AG1', '认证状态')
            ->setCellValue('AH1', '会员名')
            ->setCellValue('AI1', '注册邮箱')
            ->setCellValue('AJ1', '注册手机');
        $num = 2;
        foreach ($data as $key => $val) {
           $name= Json::encode($val['cust_sname']);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $num - 1)
                ->setCellValue('B' . $num, Html::decode($val['cust_filernumber']))
                ->setCellValue('C' . $num, Html::decode($val['cust_code']))
                ->setCellValue('D' . $num, $name)
                ->setCellValue('E' . $num, Html::decode($val['cust_shortname']))
                ->setCellValue('F' . $num, Html::decode($val['cust_t']))
                ->setCellValue('G' . $num, Html::decode($val['cust_type']))
                ->setCellValue('H' . $num, Html::decode($val['cust_level']))
                ->setCellValue('I' . $num, Html::decode($val['cust_tel1']))
                ->setCellValue('J' . $num, Html::decode($val['cust_fax']))
                ->setCellValue('K' . $num, Html::decode($val['cust_inchargeperson']))
                ->setCellValue('L' . $num, Html::decode($val['member_regweb']))
                ->setCellValue('M' . $num, Html::decode($val['cust_contacts']))
                ->setCellValue('N' . $num, Html::decode($val['cust_tel1']))
                ->setCellValue('O' . $num, Html::decode($val['staff_email']))
                ->setCellValue('P' . $num, Html::decode($val['custManager']))
                ->setCellValue('Q' . $num, Html::decode($val['district_name']))
                ->setCellValue('R' . $num, Html::decode($val['cust_salearea']))
                ->setCellValue('S' . $num, Html::decode($val['customerAddress']))
                ->setCellValue('T' . $num, Html::decode($val['cust_compscale']))
                ->setCellValue('U' . $num, Html::decode($val['member_reqitemclass']))
                ->setCellValue('V' . $num, Html::decode($val['cust_businesstype']))
                ->setCellValue('W' . $num, Html::decode($val['cust_industrytype']))
                ->setCellValue('X' . $num, Html::decode($val['cust_compvirtue']))
                ->setCellValue('Y' . $num, Html::decode($val['cust_personqty']))
                ->setCellValue('Z' . $num, Html::decode($val['cust_regdate']))
                ->setCellValue('AA' . $num, Html::decode($val['cust_regfunds']))
                ->setCellValue('AB' . $num, Html::decode($val['cust_islisted']))
                ->setCellValue('AC' . $num, Html::decode($val['member_compsum']))
                ->setCellValue('AD' . $num, Html::decode($val['cust_tax_code']))
                ->setCellValue('AE' . $num, Html::decode($val['cust_ismember']))
                ->setCellValue('AF' . $num, Html::decode($val['bsp_svalue']))
                ->setCellValue('AG' . $num, Html::decode($val['YN']))
                ->setCellValue('AH' . $num, Html::decode($val['member_name']))
                ->setCellValue('AI' . $num, Html::decode($val['cust_email']))
                ->setCellValue('AJ' . $num, Html::decode($val['cust_tel2']));
            $num++;
        }

//        dumpE($data);
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


}
