<?php
/**
 * User: F1676624
 * Date: 2016/11/25
 */

namespace app\modules\ptdt\controllers;

use yii\helpers\Json;
use yii\helpers\Html;
use app\controllers\BaseController;
use yii;
//产品控制器
class ProductLibraryController extends BaseController
{
    private $_url = "ptdt/product-library/";  //对应api控制器URL

    //产品列表
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;

        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);

        if(\Yii::$app->request->get('export')){
            $this->export(Json::decode($dataProvider)['rows']);
        }

        if (Yii::$app->request->isAjax) {
            $data=Json::decode($dataProvider);
            foreach ($data["rows"] as &$item){
                $item["product_no"]=Html::a($item["pdt_no"],['view','id'=>$item['pdt_no']]);
            }
            return Json::encode($data);
        }
        //一阶分类
        $types = $this->getProductTypeList();
        $type1=[""=>"请选择"];
        foreach ($types as $key => $val) {
            $type1[$val['category_id']] = $val['category_sname'];
        }
        //页面已经有级联函数  此数据为了查询后能显示查询数据
        $type2 = [""=>"请选择"];
        $type3 = [""=>"请选择"];
        $type4 = [""=>"请选择"];
        $type5 = [""=>"请选择"];
        $type6 = [""=>"请选择"];
        $get=\Yii::$app->request->queryParams;
        if (!empty($get["type_1"])) {
            $type2 = array_merge($type2,$this->getTypeOption($get["type_1"]));
        };
        if (!empty($get["type_2"])) {
            $type3 = array_merge($type3,$this->getTypeOption($get["type_2"]));
        };
        if (!empty($get["type_3"])) {
            $type4 = array_merge($type4,$this->getTypeOption($get["type_3"]));
        };
        if (!empty($get["type_4"])) {
            $type5 = array_merge($type5,$this->getTypeOption($get["type_4"]));
        };
        if (!empty($get["type_5"])) {
            $type6 = array_merge($type6,$this->getTypeOption($get["type_5"]));
        };
        $columns=$this->getField();
        $statusType=array("未定价","发起定价","商品开发维护","审核中","已定价","被驳回","已逾期","重新定价");
        return $this->render('index', [
            'type1' => $type1
            , 'type2' => $type2
            , 'type3' => $type3
            , 'type4' => $type4
            , 'type5' => $type5
            , 'type6' => $type6
            , 'statusType'=>$statusType
            , 'columns'=>$columns
        ]);
    }

    //产品详情
    public function actionView($id)
    {
        $model = $model = $this->getModel($id);
        return $this->render('view',
            ['model' => $model
            ]);
    }

    //产品修改
    public function actionEdit($id)
    {
        if (yii::$app->request->isPost) {
            $url = $this->findApiUrl() . $this->_url . "edit?id=" . $id;
            $params = Yii::$app->request->post();
            $curl=$this->findCurl();
            $curl->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $res=Json::decode($curl->post($url));
//            echo "<pre>";print_r($res);die();
            if($res['status']==1){
                return Json::encode(['msg' => "修改成功", "flag" => 1,'url'=>\yii\helpers\Url::to(['view','id'=>$id])]);
            }else{
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        } else {


            //一阶分类
            $productType = $this->getProductTypeList();
            $productTypeIdToValue = [];
            foreach ($productType as $key => $val) {
                $productTypeIdToValue[$val['category_id']] = $val['category_sname'];
            }
            $model = $this->getModel($id);
            $url=$this->findApiUrl().$this->_url."district-level?id=".$model->sale_area;
            $district=Json::decode($this->findCurl()->get($url));
            return $this->render('edit',
                [
                    'model' => $model,
                    'district'=>$district,
                    'productTypeIdToValue' => $productTypeIdToValue
                ]
            );
        }
    }

    //获取产品定价
    public function actionLoadPrice($id)
    {
        $url = $this->findApiUrl() . $this->_url . "prices?id=" . $id;
        $priceList = json_decode($this->findCurl()->get($url));
        return $this->renderPartial('load-price', ['priceList' => $priceList]);
    }

    //根据ID获取产品下级分类
    public function actionGetProductType($id)
    {

        $url = $this->findApiUrl() . $this->_url . "product-types-children?id=" . $id;
        return $this->findCurl()->get($url);
    }

    //获取产品一级分类
    protected function getProductTypeList()
    {
        $url = $this->findApiUrl() . $this->_url . "product-types";
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * 获取子类
     */
    protected function getTypeOption($id)
    {
        $url = $this->findApiUrl() . $this->_url . "types-option?id=" . $id;
        return json_decode($this->findCurl()->get($url),true);
    }

    //获取详情数据
    protected function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-model?id=" . $id;
        return Json::decode($this->findCurl()->get($url), false);
    }

    //数据导出
    private function export($data)
    {
        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xls";
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '料号')
            ->setCellValue('B1', '商品名称')
            ->setCellValue('C1', '规格型号')
            ->setCellValue('D1', '品牌')
            ->setCellValue('E1', '单位')
            ->setCellValue('F1', '供应商名称')
            ->setCellValue('G1', '商品经理人')
            ->setCellValue('H1', '商品分类')
            ->setCellValue('I1', '商品属性')
            ->setCellValue('J1', '状态')
        ;
        $num = 2;
        $typeArr=["新增","降价","涨价","定价不变","变更利润率","延期"];
        $statusArr=["未定价","发起定价","商品开发维护","审核中","已定价","被驳回","已逾期","重新定价"];
        $fromArr=['自主开发','CRD/PRD'];
        $levelArr=["","高","中","低"];
        foreach ($data as $key => $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, $val['pdt_no'])
                ->setCellValue('B'.$num, $val['pdt_name'])
                ->setCellValue('C'.$num, $val['tp_spec'])
                ->setCellValue('D'.$num, $val['brand_name'])
                ->setCellValue('E'.$num, $val['unit'])
                ->setCellValue('F'.$num, $val['supplier_name'])
                ->setCellValue('G'.$num, $val['pd_manager'])
                ->setCellValue('H'.$num, $val['category'])
                ->setCellValue('I'.$num, $val['pdt_attribute'])
                ->setCellValue('J'.$num, $val['status'])
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