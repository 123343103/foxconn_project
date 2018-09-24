<?php

namespace app\modules\warehouse\controllers;

use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class ShipmentNotifyController extends \app\controllers\BaseController
{
    private $_url = 'warehouse/shipment-notify/'; //对应api

    public function actionIndex()
    {
        $queryParam = Yii::$app->request->queryParams;
//        $queryParam['staff_id']=Yii::$app->user->identity->staff->staff_id;
        //$url = $this->findApiUrl() . $this->_url ."index?staff_id=".Yii::$app->user->identity->staff->staff_id.http_build_query($queryParam);
        $url = $this->findApiUrl() . $this->_url ."index";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = json::decode($this->findCurl()->get($url), true);
            foreach ($dataProvider['rows'] as $key => $val) {
                $dataProvider['rows'][$key]['note_no'] = '<a href="' . Url::to(['view', 'id' => $val['note_pkid']]) . '">' . $val['note_no'] . '</a>';
                // 取消原因
                $dataProvider['rows'][$key]['urg']=$dataProvider['rows'][$key]['urg']=="1"?"一般":($dataProvider['rows'][$key]['urg']=="2"?"急":"特急");
            }
            return Json::encode($dataProvider);
        }
        $downList = $this->getDownList();
        $url_wh=$this->findApiUrl().$this->_url."get-wh-jurisdiction?staff_id=".\Yii::$app->user->identity->staff->staff_id;
        $options=Json::decode($this->findCurl()->get($url));
        $options['warehouse']=Json::decode($this->findCurl()->get($url_wh));
        $columns = $this->getField("/warehouse/shipment-notify/index");
        $child_columns = $this->getField("/warehouse/shipment-notify/get-product");
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->exportFiled(Json::decode($this->findCurl()->get($url))['rows']);
        }
        //$dataProvider
        //dumpE(json::decode($this->findCurl()->get($url),true));
        return $this->render('index', [
            'options' => $options,
            'downList' => $downList,
            'columns' => $columns,
            'child_columns' => $child_columns,
            'queryParam'=>$queryParam
        ]);
    }

    //詳情
    public function actionView($id)
    {
        $model = $this->getModel($id);
//        $url = $this->findApiUrl() . $this->_url . "view?id=" . $id;
//        $data = Json::decode($this->findCurl()->get($url),true);
//        dumpE($data);
        return $this->render('view', [
            'data' => $model[0],
            'productinfo' => $model[1]
        ]);
    }

    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    // 获取子表商品信息
    public function actionGetProduct($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-products?id=" . $id;
//        dumpE(json::decode($this->findCurl()->get($url),true));
        return $this->findCurl()->get($url);
    }

    // 下拉列表
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "get-down-list";
        return json::decode($this->findCurl()->get($url));
    }

//    // 生成拣货单
//    public function actionCreatePick() {
//        $post = Yii::$app->request->queryParams;
//        $post['staff_id'] = Yii::$app->user->identity->staff_id;
//        $url = $this->findApiUrl() . $this->_url . "create-pick";
//        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
//        return $curl->post($url);
//    }

    // 取消通知
    public function actionCancelNotify($id)
    {
        if ($data = Yii::$app->request->post()) {
            $data["ShpNt"]["canclor"] = Yii::$app->user->identity->staff->staff_id;//操作人
            $url = $this->findApiUrl() . $this->_url . "cancel-notify?id=" . $id;
            return $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data))->post($url);
        }
//        if(Yii::$app->request->isPost){
//            $post = Yii::$app->request->post();
//            $url = $this->findApiUrl() . $this->_url . "cancel-notify?id=" . $id;
//            $data = $this->findCurl()->delete($url);
//            return $data;
//            if (json_decode($data)->status == 1) {
//                return Json::encode(['msg' =>json_decode($data)->msg."成功!", "flag" => 1, "url" => Url::to(['index'])]);
//            } else {
//                return Json::encode(['msg' =>json_decode($data)->msg."失败!", 'flag' => 0]);
//            }
////            return $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post))->post($url);
//        }
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render("cancel-notify", ['id' => $id]);
    }

    //生成拣货单
    public function actionCreatePick($id)
    {
        $Params = Yii::$app->request->queryParams;
        if (Yii::$app->request->getIsPost()) {
            $postData=Yii::$app->request->post();
            $postData["ShpNt"]["pickor"]=Yii::$app->user->identity->staff->staff_id;//操作人
            $url = $this->findApiUrl() . $this->_url . "create-pick";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url),true);
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg']);
                return json_encode([
                    'msg' => "生成拣货单成功！",
                    'flag'=>1,
                    "url" => Url::to(['/warehouse/shipment-notify/index'])
                ]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        } else {
            $this->layout = "@app/views/layouts/ajax.php";
            $pickinfo = $this->getpickinfo($id);
            return $this->render("create-pick", [
//                "model" => $pickinfo[0],
                'param'=>$Params,
                "productinfo" => $pickinfo,
            ]);
        }
    }

    public function getPickinfo($id)
    {
        $url = $this->findApiUrl() . $this->_url . "pickinfo?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    //导出
    public function actionExport(){
        $queryParam = Yii::$app->request->queryParams;
        $queryParam['staff_id']=Yii::$app->user->identity->staff->staff_id;
        $url = $this->findApiUrl() . $this->_url ."export";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
//        $url=$this->findApiUrl().$this->_url.'export?staff_id='.\Yii::$app->user->identity->staff->staff_id.http_build_query(Yii::$app->request->queryParams);
        $data=Json::decode($this->findCurl()->get($url));
//        dumpE($data);
        $objPHPExcel = new \PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '出货通知单号')
            ->setCellValue('C1', '单据状态')
            ->setCellValue('D1', '交易法人')
            ->setCellValue('E1', '客户名称')
            ->setCellValue('F1', '关联订单号')
            ->setCellValue('G1', '订单类型')
            ->setCellValue('H1', '通知人')
            ->setCellValue('I1', '通知单日期')
            ->setCellValue('J1', '运输方式')
            ->setCellValue('K1', '紧急程度')
            ->setCellValue('L1', '接单人');
        foreach ($data as $key => $val) {
            $num = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $num - 1)
                ->setCellValue('B' . $num, Html::decode($val['note_no']))
                ->setCellValue('C' . $num, Html::decode($val['status']))
                ->setCellValue('D' . $num, Html::decode($val['company_name']))
                ->setCellValue('E' . $num, Html::decode($val['cust_sname']))
                ->setCellValue('F' . $num, Html::decode($val['ord_no']))//$val['so_type']=="10"?"待提交":($val['so_type']=="20"?"审核中":($val['so_type']=="40"?"审核完成":"驳回"))
                ->setCellValue('G' . $num, Html::decode($val['ord_type']))
                ->setCellValue('H' . $num, Html::decode($val['operator']))
                ->setCellValue('I' . $num, Html::decode($val['n_time']))
                ->setCellValue('J' . $num, Html::decode($val['tran_sname']))
                ->setCellValue('K' . $num, Html::decode($val['urg']=="1"?"一般":($val['urg']=="2"?"急":"特急")))
                ->setCellValue('L' . $num, Html::decode($val['pickor']));

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
        $sheet->getColumnDimension("F")->setWidth(50);
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

    //弹出框中的仓库信息
    public function actionSelectWhid($staff_id,$partno){
        $url=$this->findApiUrl() . $this->_url . "select-whid?staff_id=".$staff_id."&partno=".$partno;
        $data = $this->findCurl()->get($url);
        return $data;
    }
}
