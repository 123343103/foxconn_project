<?php

namespace app\modules\warehouse\controllers;

use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class PickingListController extends \app\controllers\BaseController
{
    private $_url = 'warehouse/picking-list/'; //对应api

    public function actionIndex()
    {
        $queryParam = Yii::$app->request->queryParams;
        $queryParam['staff_id']=Yii::$app->user->identity->staff->staff_id;
        $url = $this->findApiUrl() . $this->_url ."index";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = json::decode($this->findCurl()->get($url), true);

            foreach ($dataProvider['rows'] as $key => $val) {
                $dataProvider['rows'][$key]['pck_no'] = '<a href="' . Url::to(['view', 'id' => $val['pck_pkid']]) . '">' . $val['pck_no'] . '</a>';
                $dataProvider['rows'][$key]['status'] = $dataProvider['rows'][$key]['status'] == "4" ? "待拣货" : ($dataProvider['rows'][$key]['status'] == "1" ? "已拣货" : ($dataProvider['rows'][$key]['status'] == "2" ? "已转出库单" : "已取消"));
            }
            return Json::encode($dataProvider);
        }

//        $url_wh=$this->findApiUrl().$this->_url."get-wh-jurisdiction?staff_id=".\Yii::$app->user->identity->staff->staff_id;
//        $options=Json::decode($this->findCurl()->get($url));
//        $options['warehouse']=Json::decode($this->findCurl()->get($url_wh));
        $columns = $this->getField("/warehouse/picking-list/index");
        $child_columns = $this->getField("/warehouse/picking-list/get-product");
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->exportFiled(Json::decode($this->findCurl()->get($url))['rows']);
        }
//        dumpE(json::decode($this->findCurl()->get($url),true));
        $downList = $this->getDownList();
        return $this->render('index', [
            'whattrinfo'=>$downList[1],
            'options' => $downList[0],
            'columns' => $columns,
            'child_columns' => $child_columns,
            'queryParam' => $queryParam
        ]);
    }

    public function actionView($id)
    {
        $model = $this->getModel($id);
//        $url = $this->findApiUrl() . $this->_url . "view?id=" . $id;
//        $data = Json::decode($this->findCurl()->get($url),true);
//        dumpE($data);
        return $this->render('view', [
            'data' => $model[0],
            'productinfo' => $model[1],
            'id'=>$id
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
        $data= Json::decode($this->findCurl()->get($url));
        $newmodel = array();//合并拣货数量后的新数据
        foreach ($data as $key=>$val) {
            $newmodel[$key] = $val;
//            $lstdtid = explode(",", number_format($val['pck_nums'],2));
            $lstdtid = explode(",", $val['pck_nums']);

            $nums = 0;
            if (count($lstdtid) > 1) {
                foreach ($lstdtid as $k => $v) {
//                    $nums+=(int)$v;
                    $nums += number_format($v, 2);
                    $lstdtid[$k]=number_format($v,2);
                }
                $newmodel[$key]['count_pck_nums'] = number_format($nums, 2);
                $newmodel[$key]["pck_nums"] = implode(',', $lstdtid);
            } else {
             $newmodel[$key]['count_pck_nums'] = number_format($val['pck_nums'], 2);
            }

        }
        if (Yii::$app->request->isAjax) {
            return Json::encode($newmodel);
        }
        return Json::encode($newmodel);
    }

    // 下拉列表
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "get-wh-jurisdiction?staff_id=".\Yii::$app->user->identity->staff->staff_id;
        return json::decode($this->findCurl()->get($url));
       // $options['warehouse']=Json::decode($this->findCurl()->get($url));
    }

    // 取消通知
    public function actionCancelPick($id)
    {
        if ($data = Yii::$app->request->post()) {
//            $data["ShpNt"]["canclor"] = Yii::$app->user->identity->staff->staff_id;//操作人
            $url = $this->findApiUrl() . $this->_url . "cancel-pick?id=" . $id;
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
        return $this->render("cancel-pick", ['id' => $id]);
    }

    //数量维护
    public function actionMaintenancePick($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $postData=Yii::$app->request->post();
            $postData["BsPck"]["pck_man"]= Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "maintenance-pick";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url),true);
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg']);
                return json_encode([
                    'msg' => "拣货数量维护成功！",
                    'flag'=>1,
                    "url" => Url::to(['/warehouse/picking-list/index'])
                ]);
            }else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        } else {
            $this->layout = "@app/views/layouts/ajax.php";
            $model = $this->getModel($id);
            return $this->render("maintenance-pick", [
//                "whinfo" => $model[2],
//                "pickinfo" => $model[3],
                "whinfo" => $model[2],
                "pickinfo" => $model[3],
                'id'=>$id
            ]);
        }
    }

    //导出
    public function actionExport()
    {
        $queryParam = Yii::$app->request->queryParams;
        $queryParam['staff_id']=Yii::$app->user->identity->staff->staff_id;
        $url = $this->findApiUrl() . $this->_url ."export";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $data = Json::decode($this->findCurl()->get($url));
        $objPHPExcel = new \PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '拣货单号')
            ->setCellValue('C1', '拣货单状态')
            ->setCellValue('D1', '仓库名称')
            ->setCellValue('E1', '仓库代码')
            ->setCellValue('F1', '仓库属性')
            ->setCellValue('G1', '出货通知单号')
            ->setCellValue('H1', '厂商出库单号')
            ->setCellValue('I1', '拣货单日期')
            ->setCellValue('J1', '操作员');
        foreach ($data as $key => $val) {
            $num = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $num - 1)
                ->setCellValue('B' . $num, Html::decode($val['pck_no']))
                ->setCellValue('C' . $num, Html::decode($val['status'] == "4" ? "待拣货" : ($val["status"] == "1" ? "已拣货" : ($val["status"] == "2" ? "已转出库单" : "已取消"))))
                ->setCellValue('D' . $num, Html::decode($val['wh_name']))
                ->setCellValue('E' . $num, Html::decode($val['wh_code']))
                ->setCellValue('F' . $num, Html::decode($val['cksx']))//$val['so_type']=="10"?"待提交":($val['so_type']=="20"?"审核中":($val['so_type']=="40"?"审核完成":"驳回"))
                ->setCellValue('G' . $num, Html::decode($val['note_no']))
                ->setCellValue('H' . $num, Html::decode($val['operator']))//厂商出库单号
                ->setCellValue('I' . $num, Html::decode($val['pck_time']))
                ->setCellValue('J' . $num, Html::decode($val['staff_name']));
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

    //查找仓库对应的储位信息
    public function actionSelectStinfo()
    {
        $this->layout = "@app/views/layouts/ajax.php";
        $url = $this->findApiUrl() . $this->_url . "select-stinfo";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
//        dumpE($queryParam);
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        $downList = $this->getDownList();
        return $this->render('select-stinfo', [
            'downList' => $downList[0],
            'params' => $queryParam,
        ]);
    }
    //出库
    public function actionOutPick($pck_pkid){
        $url = $this->findApiUrl() . $this->_url . 'out-pick?pck_pkid=' . $pck_pkid;
        $postData=Yii::$app->request->post();
        $postData["o_whpdt"]["creator"]= Yii::$app->user->identity->staff_id;
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = Json::decode($curl->post($url),true);
        if ($data['status'] == 1) {
            SystemLog::addLog($data['data']['msg']);
            return Json::encode(["msg" => $data['msg'], "flag" => 1]);
        }else {
            return Json::encode(['msg' => $data['msg'], "flag" => 0]);
        }
    }
    //弹出框中的批次信息
    public function actionLinvtBach($wh_code,$partno){
        $url=$this->findApiUrl() . $this->_url . "linvt-bach?wh_code=".$wh_code."&partno=".$partno;
        $data = $this->findCurl()->get($url);
        return $data;
    }
}
