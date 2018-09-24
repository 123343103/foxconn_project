<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/12/23
 * Time: 上午 11:00
 */
namespace app\modules\warehouse\controllers;

use app\controllers\BaseController;
use app\modules\system\controllers\DataUpdate;
use app\modules\system\models\SystemLog;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii;

class SaleOutStockController extends BaseController
{
    public $_url="warehouse/sale-out-stock/";

    //首页
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        $staff=Yii::$app->user->identity->staff_id;
        $queryParam['staff_id'] = $staff;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        if(Yii::$app->request->isAjax){
            $data = Json::decode($this->findCurl()->get($url));
            //$data=Json::decode($this->findCurl()->get($url1));
            foreach($data["rows"] as $key=>$value){
                $data["rows"][$key]['o_whcode']=Html::a($data["rows"][$key]['o_whcode'],['view', 'id' => $data["rows"][$key]['o_whpkid']],['class'=>'viewitem']);
                $data["rows"][$key]["o_date"] = date("Y/m/d", strtotime($data["rows"][$key]["o_date"]));
                $data["rows"][$key]["plan_odate"] = date("Y/m/d", strtotime($data["rows"][$key]["plan_odate"]));
                $data["rows"][$key]["opp_date"] = date("Y/m/d", strtotime($data["rows"][$key]["opp_date"]));
                $data["rows"][$key]["creat_date"] = date("Y/m/d", strtotime($data["rows"][$key]["creat_date"]));
                $data["rows"][$key]['all_address']=$this->actionGetAddress($data["rows"][$key]['district_id']).$data["rows"][$key]['address'];
            }
            return Json::encode($data);
        }
        $urls=$this->findApiUrl().$this->_url."options";
        $url_wh=$this->findApiUrl().$this->_url."get-wh-jurisdiction?staff_id=".\Yii::$app->user->identity->staff->staff_id;
        $options=Json::decode($this->findCurl()->get($urls));
        $options['warehouse']=Json::decode($this->findCurl()->get($url_wh));
        return $this->render("index",["options"=>$options,'Param'=>$queryParam]);
    }
    //导出
    public function actionExport()
    {
        $queryParam = Yii::$app->request->queryParams;
        $queryParam['staff_id']=Yii::$app->user->identity->staff->staff_id;
        $url = $this->findApiUrl() . $this->_url ."index";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $data=Json::decode($this->findCurl()->get($url));
        $objPHPExcel = new \PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '出库单号')
            ->setCellValue('C1', '出库单状态')
            ->setCellValue('D1', '关联拣货单')
            ->setCellValue('E1', '交易法人')
            ->setCellValue('F1', '客户名称')
            ->setCellValue('G1', '出仓仓库')
            ->setCellValue('H1', '客户订单号')
            ->setCellValue('I1', '订单类型')
            ->setCellValue('J1', '物流订单号')
            ->setCellValue('K1', '收货地址')
            ->setCellValue('L1', '收货人')
            ->setCellValue('M1', '收货人联系方式')
            ->setCellValue('N1', '出库单日期')
            ->setCellValue('O1', '操作员')
            ->setCellValue('P1', '预出库时间')
            ->setCellValue('Q1', '操作时间')
            ->setCellValue('R1', '申请时间');
        foreach($data['rows'] as $key=>$val){
            $num = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $num - 1)
                ->setCellValue('B' . $num, Html::decode($val['o_whcode']))
                ->setCellValue('C' . $num, Html::decode($val['o_whstatus'] == "1" ? "待收货" :
                    ($val["o_whstatus"] == "0" ? "待出库" : ($val["o_whstatus"] == "2" ? "已收货" :
                        ($val["o_whstatus"]=="3"?"已出库":($val["o_whstatus"]=="4"?"已取消":($val["o_whstatus"]=="5"?"待提交" :
                            ($val["o_whstatus"]=="6"?"审核中":($val["o_whstatus"]=="7"?"审核完成":($val["o_whstatus"]=="8"?"驳回":
                                "已作废"))))))))))
                ->setCellValue('D' . $num, Html::decode($val['relate_packno']))
                ->setCellValue('E' . $num, Html::decode($val['company_name']))
                ->setCellValue('F' . $num, Html::decode($val['cust_contacts']))//$val['so_type']=="10"?"待提交":($val['so_type']=="20"?"审核中":($val['so_type']=="40"?"审核完成":"驳回"))
                ->setCellValue('G' . $num, Html::decode($val['wh_name']))
                ->setCellValue('H' . $num, Html::decode($val['ord_no']))
                ->setCellValue('I' . $num, Html::decode($val['business_type_desc']))
                ->setCellValue('J' . $num, Html::decode($val['logistics_no']))
                ->setCellValue('K' . $num, Html::decode($val['all_address']))
                ->setCellValue('L' . $num, Html::decode($val['reciver']))
                ->setCellValue('M' . $num, Html::decode($val['reciver_tel']))
                ->setCellValue('N' . $num, Html::decode(date("Y/m/d", strtotime($data["rows"][$key]["o_date"]))))
                ->setCellValue('O' . $num, Html::decode($val['staff_name']))
                ->setCellValue('P' . $num, Html::decode( date("Y/m/d", strtotime($data["rows"][$key]["plan_odate"]))))
                ->setCellValue('Q' . $num, Html::decode(date("Y/m/d", strtotime($data["rows"][$key]["opp_date"]))))
                ->setCellValue('R' . $num, Html::decode(date("Y/m/d", strtotime($data["rows"][$key]["creat_date"]))));

            for ($i = A; $i !== R; $i++) {
                $sheet->getColumnDimension($i)->setWidth(25);
                $sheet->getDefaultRowDimension()->setRowHeight(18);
                $sheet->getColumnDimension($i)->setCollapsed(false);
                $sheet->getStyle($i . '1')->getAlignment()->setHorizontal("center");
                $sheet->getStyle($i . $num)->getAlignment()->setHorizontal("center");
                $sheet->getStyle($i . '1')->getFont()->setName('黑体')->setSize(14);
            }
        }
        $sheet->getColumnDimension("A")->setWidth(10);
        $sheet->getColumnDimension("E")->setWidth(50);
        $sheet->getColumnDimension("K")->setWidth(50);
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
    //详情页
    public function actionView($id)
    {
        $url=$this->findApiUrl().$this->_url."view?id={$id}";
        $model=Json::decode($this->findCurl()->get($url));
        $url=$this->findApiUrl().$this->_url."child-data?id={$id}";
        $childs=Json::decode($this->findCurl()->get($url));
        foreach($childs["rows"] as $key=>$value){
            if(!empty($childs["rows"][$key]["consignment_date"]))
            {
                $childs["rows"][$key]["consignment_date"] = date("Y/m/d", strtotime($childs["rows"][$key]["consignment_date"]));
            }
            if(!empty($childs["rows"][$key]["o_date"])) {
                $childs["rows"][$key]["o_date"] = date("Y/m/d", strtotime($childs["rows"][$key]["o_date"]));
            }
            if(!empty($childs["rows"][$key]["o_whnum"])&&!empty($childs["rows"][$key]["price"]))
            {
                $childs["rows"][$key]["sum_price"]=sprintf("%.2f", (float)$childs["rows"][$key]["o_whnum"]*(float)$childs["rows"][$key]["price"]);
            }
        }
        $model['rows'][0]['all_address']=$this->actionGetAddress($model['rows'][0]['owdistrict_id']).$model['rows'][0]['owaddress'];
        $urls=$this->findApiUrl().$this->_url."options?id={$id}";
        $options=Json::decode($this->findCurl()->get($urls));
        return $this->render('view',["model"=>$model['rows'][0],"childs"=>$childs,"options"=>$options]);
    }

    //取消发货
    public function actionCancel($id){
        if(\Yii::$app->request->isPost){
            $post=\Yii::$app->request->post();
            $url=$this->findApiUrl().$this->_url."cancel?id={$id}&staff_id=".\Yii::$app->user->identity->staff->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($post));
           $data=Json::decode($curl->post($url));
            if($data["status"]==1){
                return Json::encode(["msg"=>$data["msg"],"flag"=>1]);
            }
            return Json::encode(["msg"=>$data["msg"],"flag"=>0]);
        }
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render("cancel",["id"=>$id]);
    }

    //自提
    public function actionSince($id)
    {
        $url=$this->findApiUrl().$this->_url."since?id={$id}&staff_id=".\Yii::$app->user->identity->staff->staff_id;
        $data=Json::decode($this->findCurl()->get($url));
        if($data["status"]==1){
            return Json::encode(["msg"=>$data["msg"],"flag"=>1]);
        }
        return Json::encode(["msg"=>$data["msg"],"flag"=>0]);
    }

    //重新上架
    public function actionPutAway($id)
    {
        $url=$this->findApiUrl().$this->_url.'put-away';
        $url.='?id='.$id;
        if($data=Yii::$app->request->post()){
            $data['update_by']=Yii::$app->user->identity->staff_id;
            $data['udate']=date('Y-m-d');
            $data['op_ip']=Yii::$app->request->getUserIP();
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
//            dumpE($result);
            if($result['status']==1){
                SystemLog::addLog('销售出库重新上架');
                return json_encode(['msg'=>$result['msg'],'flag'=>1]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $this->layout="@app/views/layouts/ajax.php";
        $url.="&staff_id=".Yii::$app->user->identity->staff_id;
        $data=json_decode($this->findCurl()->get($url),true);
//        print_r($data);
        return $this->render('put-away',['data'=>$data]);
    }

    //查看物流信息
    public function actionViewLogisticsInformation($id)
    {
        $url=$this->findApiUrl().$this->_url."get-log-list?id={$id}";
        $model=Json::decode($this->findCurl()->get($url));
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render('view-logistics-information',
            [
                'model'=>$model
            ]);
    }

    //生成物流单号
    public function actionGeneratingLogistics($id)
    {
        date_default_timezone_set("Asia/Shanghai");
        if($post=Yii::$app->request->post()){
            $url=$this->findApiUrl().'warehouse/sale-out-stock/generating-logistics?id='.$id;
            $post['OrdLgst']['crter']=\Yii::$app->user->identity->staff_id;
            $post['OWhpdt']['opp_id']=\Yii::$app->user->identity->staff_id;
            $post['OrdLgst']['crt_date']=time();
            $post['OrdLgst']['stt_date']=time();
            $post['update_by']=\Yii::$app->user->identity->staff_id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                return json_encode([
                    'msg' => "保存成功",
                    'flag'=>1,
                    'billId'=>$data['data']['ordlgid'],//单据id
                    'id'=>$id,
                    'billTypeId'=>61,//单据类型
                    "url" => Url::to(['view','id'=>$id])
                ]);
            }
            else{
                return Json::encode(['msg' =>'发生未知错误', "flag" => 0]);
            }
        }
        else {
            $url = $this->findApiUrl() . $this->_url . "log-info?id={$id}";
            $sum = 0;//含税价物流总价
            $sums = 0;//未含税价物流总价
            $model = Json::decode($this->findCurl()->get($url));
            $urls = $this->findApiUrl() . $this->_url . "child-ord-data?id={$id}";//单身列表
            $orddata = Json::decode($this->findCurl()->get($urls));
            if (!empty($orddata)) {
                foreach ($orddata as $key => $val) {
                    //含税价物流
                    if (!empty($val['tax_freight'])) {
                        $sum = $sum + (float)$val['tax_freight'];
                    }
                    //未含税价物流
                    if (!empty($val['freight'])) {
                        $sums = $sums + (float)$val['freight'];
                    }
                    if ($orddata[$key]['pck_type'] == '2') {
                        $orddata[$key]['pck_type'] = '銷售包裝';
                    }
                     if ($orddata[$key]['pck_type'] == '1') {
                        $orddata[$key]['pck_type'] = '基本包裝';
                    }
                    if ($orddata[$key]['pck_type'] == '3') {
                        $orddata[$key]['pck_type'] = '外包裝';
                    }
                    if ($orddata[$key]['pck_type'] == '4') {
                        $orddata[$key]['pck_type'] = '散貨包裝';
                    }
                    if ($orddata[$key]['pck_type'] == '5') {
                        $orddata[$key]['pck_type'] = '栈板包装';
                    }
                }
            }
            $model['rows'][0]['ow_all_address'] = $this->actionGetAddress($model['rows'][0]['owdistrict_id']) . $model['rows'][0]['owaddress'];
            $model['rows'][0]['bw_all_address'] = $this->actionGetAddress($model['rows'][0]['bwdistrict_id']) . $model['rows'][0]['bwwh_addr'];
            $model['rows'][0]['tax_freight'] = $sum;//含税价物流总价
            $model['rows'][0]['freight'] = $sums;//未含税价物流总价
            $url = $this->findApiUrl() . $this->_url . "get-car";
            $data = Json::decode($this->findCurl()->get($url));//车种
            return $this->render('generating-logistics', [
                "model" => $model['rows'][0],
                'Car' => $data,
                'orddata' => $orddata
            ]);
        }
    }

    //出仓费用申请
    public function actionApplicationApply($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-cur";
        $data = Json::decode($this->findCurl()->get($url));//币种
        $urls = $this->findApiUrl() . $this->_url . "price-list?id=".$id;
        $datas = Json::decode($this->findCurl()->get($urls));//基本信息
        return $this->render('application-apply',
            [
                'cur'=>$data,
                'model'=>$datas
            ]);
    }

    //加载关联商品信息
    public function actionLoadProduct($id)
    {
        $url=$this->findApiUrl().$this->_url.'child-data?id='.$id;
        $url.='?'.http_build_query(\Yii::$app->request->queryParams);
        $data=Json::decode($this->findCurl()->get($url));
        foreach($data["rows"] as $key=>$value){
            if(!empty($data["rows"][$key]["consignment_date"]))
            {
                $data["rows"][$key]["consignment_date"] = date("Y/m/d", strtotime($data["rows"][$key]["consignment_date"]));
            }
            if(!empty($data["rows"][$key]["o_date"])) {
                $data["rows"][$key]["o_date"] = date("Y/m/d", strtotime($data["rows"][$key]["o_date"]));
            }
            if(!empty($data["rows"][$key]["o_whnum"])&&!empty($data["rows"][$key]["price"]))
            {
                $data["rows"][$key]["sum_price"]=sprintf("%.5f",(float)$data["rows"][$key]["o_whnum"]*(float)$data["rows"][$key]["price"]);
            }
        }
//        dumpE($data);
        return json_encode($data);
    }

    //根据最后一阶id获取完整地址
    public function actionGetAddress($district_id)
    {
        $url=$this->findApiUrl().$this->_url."get-address?district_id=".$district_id;
        $data=Json::decode($this->findCurl()->get($url));
        return $data;
    }

}