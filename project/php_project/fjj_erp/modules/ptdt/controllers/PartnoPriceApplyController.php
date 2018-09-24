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
use yii\helpers\Html;
//商品定价申请控制器
class PartnoPriceApplyController extends BaseController
{
    private $_url = "ptdt/partno-price-apply/";  //对应api控制器URL

    //定价申请列表
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
            $data=Json::decode($dataProvider);
            foreach ($data["rows"] as &$item){
                $item["price_no"]=Html::a($item["price_no"],['view','id'=>$item['id']]);
            }
            return Json::encode($data);
        }
        $columns=$this->getField("/ptdt/partno-price-apply/index");
        return $this->render('index',[
            'model'=>Json::decode($dataProvider),
            'downlist'=>$this->getDownList(),
            'columns'=>$columns
        ]);
    }

    //定价申请详情
    public function actionView($id)
    {
        $model=$this->getModel($id);
        $downlist=$this->getDownList();
        foreach($downlist as $k=>$v){
            if(isset($model[$k])){
                $model[$k]=$v[$model[$k]];
            }
        }
        return $this->render('view',[
            "model"=>$model,
        ]);
    }

    //修改定价申请
    public function actionEdit($id)
    {
        if(Yii::$app->request->isPost){
            $params = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "edit?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                return Json::encode(['msg' => "修改定价成功", "flag" => 1,"url"=>Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }else{
            $model = $this->getModel($id);
            return $this->render('edit',[
                "model"=>$model,
                "downlist"=>$this->getDownList()
            ]);
        }
    }


    //新增定价申请
    public function actionCreate(){
        if(Yii::$app->request->isPost){
            $url=$this->findApiUrl().$this->_url."create";
            $params=\Yii::$app->request->post();
            $curl=$this->findCurl();
            $curl->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $res=Json::decode($curl->post($url),false);
//            echo "<pre>";print_r($res);die();
            if($res->status==1){
                return Json::encode(['msg'=>'新增定价申请成功','flag'=>1,"url"=>Url::to(['index'])]);
            }else{
                return Json::encode(['msg'=>'发生未知错误','flag'=>0]);
            }

        }else{
            return $this->render("create",["downlist"=>$this->getDownList()]);
        }
    }

    //选择料号
    public function actionPartnoSelect(){
        $params=Yii::$app->request->queryParams;
        $url=$this->findApiUrl().$this->_url."partno-select";
        $url.="?".http_build_query($params);
        return $this->findCurl()->get($url);
    }

    //删除定价审请
    public function actionDelete($id){
        $url=$this->findApiUrl().$this->_url."delete?id=".$id;
        $res=Json::decode($this->findCurl()->delete($url),false);
        if($res->status==1){
            return Json::encode(['msg'=>"删除成功","flag"=>1]);
        }else{
            return Json::encode(['msg'=>"删除失败","flag"=>0]);
        }
    }







    //获取定价申请详情信息
    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if (!$model) {
            throw new yii\web\NotFoundHttpException("页面未找到");
        }
        return $model;
    }

    //导出数据
    private function getExcelData($data)
    {
        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xls";
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '料号')
            ->setCellValue('B1', '品名')
            ->setCellValue('C1', '商品经理人')
            ->setCellValue('D1', '申请单号')
            ->setCellValue('E1', '定价发起来源')
            ->setCellValue('F1', '定价类型')
            ->setCellValue('G1', '商品定位')
            ->setCellValue('H1', '状态');
        $num = 2;
        foreach ($data as $key => $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, $val['part_no'])
                ->setCellValue('B'.$num, $val['pdt_name'])
                ->setCellValue('C'.$num, $val['pdt_manager'])
                ->setCellValue('D'.$num, $val['price_no'])
                ->setCellValue('E'.$num, $val['price_from'])
                ->setCellValue('F'.$num, $val['price_type'])
                ->setCellValue('G'.$num, $val['pdt_level'])
                ->setCellValue('H'.$num, $val['status'])
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

    //筛选项下拉列表数据
    public function getDownList(){
        $url=$this->findApiUrl().$this->_url."get-down-list";
        $res=$this->findCurl()->get($url);
        return Json::decode($res,true);
    }


}