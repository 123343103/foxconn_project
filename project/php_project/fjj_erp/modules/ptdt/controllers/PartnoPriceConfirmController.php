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
//商品定价控制器
class PartnoPriceConfirmController extends BaseController
{
    private $_url = "ptdt/partno-price-confirm/";  //对应api控制器URL

    /*
     * 生成表格页数据
     */

    //商品定价列表
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;

        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);

        if(\Yii::$app->request->get("export")){
            $this->getExcelData(Json::decode($dataProvider)['rows']);
        }

        if (Yii::$app->request->isAjax) {          //如果是分页获取数据则直接返回数据
            $data=Json::decode($dataProvider);
            foreach ($data["rows"] as &$item){
                $item["price_no"]=Html::a($item["price_no"],['view','id'=>$item['id']]);
            }
            return Json::encode($data);
        }
        $productType = $this->getProductTypeList();
        $productTypeIdToValue = [];
        foreach ($productType as $key => $val) {
            $productTypeIdToValue[$val['category_id']] = $val['category_sname'];
        }
        $columns=$this->getField("/ptdt/partno-price-confirm/index");
        $statusType=array("未定价","发起定价","商品开发维护","审核中","已定价","被驳回","已逾期","重新定价");
        return $this->render('index',
            [
                'model'=>Json::decode($dataProvider),
                'productTypeIdToValue' => $productTypeIdToValue,
                'statusType'=>$statusType,
                'columns'=>$columns
            ]
        );
    }

    //商品定价详情
    public function actionView($id)
    {
        $model=$this->getModel($id);
        return $this->render('view',[
            "model"=>$model
        ]);
    }


    //商品新增定价
    public function actionCreate()
    {
        if(\Yii::$app->request->isPost){
            $params = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params));
            $data = Json::decode($curl->post($url));
//            echo "<pre>";print_r($data);die();
            if ($data['status'] == 1) {
                return Json::encode(['msg' => "修改定价成功", "flag" => 1,"url"=>Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }else{
            return $this->render('create',[
                "downlist"=>$this->getDownList()
            ]);
        }
    }

    //修改定价
    public function actionEdit($id)
    {
        if(Yii::$app->request->isPost){
            $params = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "edit?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params));
            $data = Json::decode($curl->put($url));
//            $data=$curl->put($url);
            if ($data['status'] == 1) {
                return Json::encode(['msg' => "修改定价成功", "flag" => 1,"url"=>Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }else{
            $url = $this->findApiUrl() . $this->_url . "view?id=" . $id;
            $model = $this->getModel($id);
            return $this->render('edit',[
                "model"=>$model,
                "downlist"=>$this->getDownList()
            ]);
        }
    }

    //批量定价
    public function actionBatchPrice(){
        if(\Yii::$app->request->isPost){
            $url=$this->findApiUrl().$this->_url."batch-price";
            $params=\Yii::$app->request->post();
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $res=$curl->post($url);
            return $res;
        }
    }

    //关联料号
    public function actionPartnoRelation(){
        if(\Yii::$app->request->isPost){
            $url=$this->findApiUrl().$this->_url."relation-price";
            $params=\Yii::$app->request->post();
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params));
            $dataProvider=$curl->post($url);
            return $dataProvider;
        }else{
            return $this->renderAjax("partno-relation");
        }
    }

    //删除定价
    public function actionDelete($id){
        $url=$this->findApiUrl().$this->_url."delete?id=".$id;
        $res=Json::decode($this->findCurl()->delete($url),false);
        if($res->status==1){
            return Json::encode(['msg'=>"删除成功","flag"=>1]);
        }else{
            return Json::encode(['msg'=>"删除失败","flag"=>0]);
        }
    }


    //获取产品一级分类
    protected function getProductTypeList()
    {
        $url = $this->findApiUrl() . $this->_url . "product-types";
        return Json::decode($this->findCurl()->get($url));
    }











    //获取定价数据
    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if (!$model) {
            throw new yii\web\NotFoundHttpException("页面未找到");
        }
        return $model;
    }


    //数据导出
    private function getExcelData($data)
    {
        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xls";
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '状态')
            ->setCellValue('B1', '品名')
            ->setCellValue('C1', '料号')
            ->setCellValue('D1', '申请单号')
            ->setCellValue('E1', 'PAS单号')
            ->setCellValue('F1', '商品经理人')
            ->setCellValue('G1', '定价类型')
            ->setCellValue('H1', '一阶')
            ->setCellValue('I1', '二阶')
            ->setCellValue('J1', '三阶')
            ->setCellValue('K1', '四阶')
            ->setCellValue('L1', '五阶')
            ->setCellValue('M1', '六阶')
            ->setCellValue('N1', '规格型号')
            ->setCellValue('O1', '品牌')
            ->setCellValue('P1', '交货条件')
            ->setCellValue('Q1', '交货地点')
            ->setCellValue('R1', '付款条件')
            ->setCellValue('S1', '交易单位')
            ->setCellValue('T1', '最小订购量')
            ->setCellValue('U1', '交易币别')
            ->setCellValue('V1', '采购价（未税）')
            ->setCellValue('W1', '低价（未税）')
            ->setCellValue('X1', '商品定价下限（未税）')
            ->setCellValue('Y1', '商品定价上限（未税）')
            ->setCellValue('Z1', '量价区间')
            ->setCellValue('AA1', '市场均价')
            ->setCellValue('AB1', '采购价有效期')
            ->setCellValue('AC1', '定价有效期')
            ->setCellValue('AD1', '是否代理')
            ->setCellValue('AE1', '毛利率')
            ->setCellValue('AF1', '毛利润率（%）')
            ->setCellValue('AG1', '税前利率')
            ->setCellValue('AH1', '税前利润率（%）')
            ->setCellValue('AI1', '税后利率')
            ->setCellValue('AJ1', '税后利润率（%）')
            ->setCellValue('AK1', '商品定位（高/中/底）')
            ->setCellValue('AL1', '原商品定价下线（未税）')
            ->setCellValue('AM1', '价格幅度（%）')
            ->setCellValue('AN1', '原定价日期')
            ->setCellValue('AO1', '是否采用关联料号定价')
            ->setCellValue('AP1', '关联料号')
            ->setCellValue('AQ1', '关联料号')
            ->setCellValue('AR1', '补充说明');
        $num = 2;
        $statusArr=["未定价","发起定价","商品开发维护","审核中","已定价","被驳回","已逾期","重新定价"];
        foreach ($data as $key => $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, $val['status'])
                ->setCellValue('B'.$num, $val['pdt_name'])
                ->setCellValue('C'.$num, $val['part_no'])
                ->setCellValue('D'.$num, $val['price_no'])
                ->setCellValue('E'.$num, $val['pasid'])
                ->setCellValue('F'.$num, $val['pdt_manager'])
                ->setCellValue('G'.$num, $val['price_type'])
                ->setCellValue('H'.$num, $val['price_from'])
                ->setCellValue('I'.$num, $val['type_1'])
                ->setCellValue('J'.$num, $val['type_2'])
                ->setCellValue('K'.$num,$val['type_3'])
                ->setCellValue('L'.$num, $val['type_4'])
                ->setCellValue('M'.$num, $val['type_5'])
                ->setCellValue('N'.$num, $val['type_6'])
                ->setCellValue('O'.$num, $val['tp_spec'])
                ->setCellValue('P'.$num, $val['brand'])
                ->setCellValue('Q'.$num, $val['trading_terms'])
                ->setCellValue('R'.$num, $val['delivery_address'])
                ->setCellValue('S'.$num, $val['payment_terms'])
                ->setCellValue('T'.$num, $val['unit'])
                ->setCellValue('U'.$num, $val['min_order'])
                ->setCellValue('V'.$num, $val['currency'])
                ->setCellValue('W'.$num, $val['buy_price'])
                ->setCellValue('X'.$num, $val['min_price'])
                ->setCellValue('Y'.$num, $val['ws_lower_price'])
                ->setCellValue('Z'.$num, $val['ws_upper_price'])
                ->setCellValue('AA'.$num, $val['num_area'])
                ->setCellValue('AB'.$num, $val['market_price'])
                ->setCellValue('AC'.$num, $val['valid_date'])
                ->setCellValue('AD'.$num, $val['verifydate'])
                ->setCellValue('AE'.$num, $val['isproxy'])
                ->setCellValue('AF'.$num, $val['gross_profit'])
                ->setCellValue('AG'.$num, $val['gross_profit_margin'])
                ->setCellValue('AH'.$num, $val['pre_tax_profit'])
                ->setCellValue('AI'.$num, $val['pre_tax_profit_rate'])
                ->setCellValue('AJ'.$num, $val['after_tax_profit'])
                ->setCellValue('AK'.$num, $val['after_tax_profit_margin'])
                ->setCellValue('AL'.$num, $val['pdt_level'])
                ->setCellValue('AM'.$num, $val['pre_ws_lower_price'])
                ->setCellValue('AN'.$num, $val['price_fd'])
                ->setCellValue('AO'.$num, $val['pre_verifydate'])
                ->setCellValue('AP'.$num, $val['isrelation'])
                ->setCellValue('AQ'.$num, $val['isrelation'])
                ->setCellValue('AR'.$num, $val['remark'])
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
        return Json::decode($res);
    }

}