<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/12/6
 * Time: 上午 10:01
 */

namespace app\modules\ptdt\controllers;


use app\controllers\BaseController;
use yii;
use yii\helpers\Json;
use yii\helpers\Url;

class PartnoPriceReviewController extends BaseController
{
    private $_url = "ptdt/partno-price-review/";  //对应api控制器URL

    /*
     * 生成表格页数据
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;

        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);

        if(\Yii::$app->request->get('export')){
            $this->getExcelData(Json::decode($dataProvider)['rows']);
        }

        if (Yii::$app->request->isAjax) {          //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
        $statusType=array("未定价","发起定价","商品开发维护","审核中","已定价","被驳回","已逾期","重新定价");
        return $this->render('index',[
            'model'=>Json::decode($dataProvider),
            'statusType'=>$statusType
        ]);
    }

    public function actionView($id)
    {
        $model=$this->getModel($id);
        return $this->render('view',[
            "model"=>$model
        ]);
    }

    public function actionEdit($id)
    {
        if(Yii::$app->request->isPost){
            $params = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "edit?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params));
            $data = Json::decode($curl->put($url));
            echo "<pre>";print_r($data);die();
            if ($data['status'] == 1) {
                return Json::encode(['msg' => "修改定价成功", "flag" => 1,'url'=>Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }else{
            $model = $this->getModel($id);
            return $this->render('edit',[
                "model"=>$model
            ]);
        }
    }


    public function actionDelete($id){
        $url=$this->findApiUrl().$this->_url."delete?id=".$id;
        $res=Json::decode($this->findCurl()->delete($url),false);
        if($res->status==1){
            return Json::encode(['msg'=>"删除成功","flag"=>1]);
        }else{
            return Json::encode(['msg'=>"删除失败","flag"=>0]);
        }
    }








    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if (!$model) {
            throw new yii\web\NotFoundHttpException("页面未找到");
        }
        return $model;
    }


    private function getExcelData($data)
    {
        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xls";
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '料号')
            ->setCellValue('B1', '商品经理人')
            ->setCellValue('C1', '商品定位')
            ->setCellValue('D1', '申请单号')
            ->setCellValue('E1', '供应商简称')
            ->setCellValue('F1', '数量区间')
            ->setCellValue('G1', '价格')
            ->setCellValue('H1', '有效期')
            ->setCellValue('I1', '状态');
        $num = 2;
        $statusArr=["未定价","发起定价","商品开发维护","审核中","已定价","被驳回","已逾期","重新定价"];
        $levelArr=["","高","中","低"];
        foreach ($data as $key => $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, $val['part_no'])
                ->setCellValue('B'.$num, $val['pdt_manager'])
                ->setCellValue('C'.$num, $levelArr[$val['pdt_level']])
                ->setCellValue('D'.$num, $val['price_no'])
                ->setCellValue('E'.$num, $val['supplier_name_shot'])
                ->setCellValue('F'.$num,$val['num_area'])
                ->setCellValue('G'.$num, $val['buy_price'])
                ->setCellValue('H'.$num,$val['valid_date'])
                ->setCellValue('I'.$num,$statusArr[$val['status']])
            ;
            $num++;
        }
        $fileName = iconv("utf-8", "gb2312", $fileName);
//        dump($column);exit();
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
        exit();
    }

}