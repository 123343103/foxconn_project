<?php
/**
 * User: F1677929
 * Date: 2017/11/28
 */
namespace app\modules\ptdt\controllers;
use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * 料号关联供应商控制器
 */
class PnoBindSppController extends BaseController
{
    public $_url="ptdt/pno-bind-spp/";

    //权限过滤
    public function beforeAction($action)
    {
        $this->ignorelist=array_merge($this->ignorelist,[
            "/ptdt/pno-bind-spp/select-pno",
            "/ptdt/pno-bind-spp/import",
            "/ptdt/pno-bind-spp/get-progress",
        ]);
        return parent::beforeAction($action);
    }

    //料号关联供应商列表
    public function actionIndex()
    {
        if(Yii::$app->request->isAjax){
            $url=$this->findApiUrl().'ptdt/pno-bind-spp/index';
            $url.='?'.http_build_query(Yii::$app->request->queryParams);
            return $this->findCurl()->get($url);
        }
        return $this->render('index');
    }

    //新增料号关联供应商
    public function actionAdd()
    {
        $url=$this->findApiUrl().'ptdt/pno-bind-spp/add';
        if($data=Yii::$app->request->post()){
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('新增核价资料');
                return json_encode(['msg'=>$result['msg'],'flag'=>1]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=json_decode($this->findCurl()->get($url),true);
        return $this->render('add',['data'=>$data]);
    }

    //修改料号关联供应商
    public function actionEdit($id)
    {
        $url=$this->findApiUrl().'ptdt/pno-bind-spp/edit';
        $url.='?id='.$id;
        if($data=Yii::$app->request->post()){
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('修改核价资料');
                return json_encode(['msg'=>$result['msg'],'flag'=>1]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=json_decode($this->findCurl()->get($url),true);
        if(empty($data['editData'])){
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('edit',['data'=>$data]);
    }

    //选择料号
    public function actionSelectPno()
    {
        $url=$this->findApiUrl().'ptdt/pno-bind-spp/select-pno';
        if(Yii::$app->request->isAjax){
            $url.='?'.http_build_query(Yii::$app->request->queryParams);
            return $this->findCurl()->get($url);
        }
        $this->layout="@app/views/layouts/ajax.php";
        $data=json_decode($this->findCurl()->get($url),true);
        return $this->render('select-pno',['data'=>$data]);
    }

    //根据料号获取料号信息
    public function actionGetPnoInfo()
    {
        $url=$this->findApiUrl().'ptdt/pno-bind-spp/get-pno-info';
        $url.='?'.http_build_query(Yii::$app->request->queryParams);
        return $this->findCurl()->get($url);
    }

    //根据料号获取对应供应商
    public function actionGetSupplierByPno()
    {
        $url=$this->findApiUrl().'ptdt/pno-bind-spp/get-supplier-by-pno';
        $url.='?'.http_build_query(Yii::$app->request->queryParams);
        return $this->findCurl()->get($url);
    }

    //导入模板
    public function actionDownTemplate()
    {
        $headArr = ['料号','品名','供应商代码','供应商名称','采购价','有效期至','备注'];
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "料号关联供应商导入模板.xlsx";
        $objPHPExcel = new \PHPExcel();
        $key = "A";
        foreach ($headArr as $v) {
            $colum = $key;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(30);
            $objPHPExcel->getActiveSheet()->getStyle($key.'1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($key.'2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            if ($key == "Z") {
                $key = "AA";
            } elseif ($key == "AZ") {
                $key = "BA";
            } else {
                $key++;
            }
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . 2, 'EQ0505AA23003-0009')
            ->setCellValue('B' . 2, 'PP色母M3583')
            ->setCellValue('C' . 2, 'SUP2017112408152')
            ->setCellValue('D' . 2, '京大冈科技 (淮安) 有限公司')
            ->setCellValue('E' . 2, '150.55')
//            ->setCellValue('F' . 2, '2020/1/1')
            //对日期的特殊处理
            ->setCellValue('F' . 2, 43831)
            ->setCellValue('G' . 2, '导入示例数据');
        //对日期的特殊处理
        $objPHPExcel->getActiveSheet()->getStyle('F2')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
        $fileName = "pnobindspp.xlsx";
        $fileName = iconv("utf-8", "gb2312", $fileName);
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
}