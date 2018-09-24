<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\Html;

class StoreSettingController extends BaseController
{
    private $_url = "crm/store-setting/";

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $list = Json::decode($this->findCurl()->get($url)); // 店铺列表数据列表
        if (Yii::$app->request->isAjax) {
            foreach ($list['rows'] as $key => $val) {
                $list['rows'][$key]['sts_code'] = '<a href="' . yii\helpers\Url::to(['view', 'id' => $val['sts_id']]) . '">' . $val['sts_code'] . '</a>';
            }
            return Json::encode($list);
        }
        $storeStatus = $this->getStoreStatus(); // 店铺状态列表
        $saleArea = $this->getSalearea(); // 军区列表
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->getExcelData($list['rows']);
        }
        $columns = $this->getField('/crm/store-setting/index');
        return $this->render('index', [
            'storeStatus' => $storeStatus,
            'saleArea' => $saleArea,
            'queryParam' => $queryParam,
            'columns' => $columns
        ]);
    }

    /*创建销售点*/
    public function actionCreate()
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['CrmStoresinfo']['creator'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
           // dumpE($data);
            if ($data['status'] == 1) {
                SystemLog::addLog('创建销售点'.$post['CrmStoresinfo']['sts_sname'].'成功');
                return Json::encode(['msg' => "创建成功", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']])]);
            } else {
                return Json::encode(['msg' => "创建失败", "flag" => 0]);
            }
        } else {
            $storeStatus = $this->getStoreStatus(); // 店铺状态列表
            $saleArea = $this->getSalearea(); // 军区列表
            $seller = $this->getSeller(); // 销售员列表
            $country = Json::decode($this->getCountry(), true);
            return $this->render('create', [
                'storeStatus' => $storeStatus,
                'seller' => $seller,
                'saleArea' => $saleArea,
                'country' => $country,
            ]);
        }
    }

    /*查看销售点*/
    public function actionView($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'view?id=' . $id;
        $model = Json::decode($this->findCurl()->get($url));
        $districtId = $model["district_id"];
        $addressString = $this->getDetailDistrict($districtId);
//        dumpE($addressString);die();
        return $this->render('view', [
            'model' => $model,
            'address' => $addressString
        ]);
    }
    //批量设置状态
    public function actionSetstatus($id='',$sts_status=''){
        if ($post=Yii::$app->request->post()) {
            $url = $this->findApiUrl() . $this->_url . "setstatus";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                return Json::encode(['msg'=>'设置成功！','flag'=>1]);
            } else {
                return Json::encode(['msg'=>'设置失败！','flag'=>0]);
            }
        }else{
            $model=$this->getSaleInfo($id);
            return $this->renderAjax("setstatus",[
                "saleinfo"=>$model,
                "idarr"=>$id
            ]);
        }
    }
    public function getSaleInfo($id){
        $url = $this->findApiUrl() . $this->_url . "sale-info?id=".$id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    /*修改销售点*/
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['CrmStoresinfo']['editor'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog('修改销售点'.$postData['CrmStoresinfo']['sts_sname'].'成功');
                return Json::encode(['msg' => "修改成功！", "flag" => 1, "url" => Url::to(['view','id'=>$id])]);
            } else {
                return Json::encode(['msg' => "修改失败！", "flag" => 0]);
            }
        } else {
            $url = $this->findApiUrl() . $this->_url . 'view?id=' . $id;
            $model = Json::decode($this->findCurl()->get($url));
            $districtId = $model["district_id"];
            $districtAll = $this->getAllDistrict($districtId);
            $storeStatus = $this->getStoreStatus(); // 店铺状态列表
            $saleArea = $this->getSalearea(); // 军区列表
            $district = $this->getDistrict(); // 省份列表
            $seller = $this->getSeller(); // 销售员列表
            $country = Json::decode($this->getCountry(), true);
//            dumpE($seller);die();
            return $this->render('update', [
                'model' => $model,
                'seller' => $seller,
                'districtAll' => $districtAll,
                'saleArea' => $saleArea,
                'storeStatus' => $storeStatus,
                'country' => $country
            ]);
        }
    }

    /*删除销售点*/
    public function actionDeleteStore($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'delete-store?id=' . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']['msg']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => $result['msg'].' 删除失败', "flag" => 0]);
        }
    }

    /*导出数据*/
    private function getExcelData($data)
    {
        $objPHPExcel = new \PHPExcel();
        $objActSheet = $objPHPExcel->getActiveSheet();
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xls";
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '销售点代码')
            ->setCellValue('C1', '销售点名称')
            ->setCellValue('D1', '营销区域')
            ->setCellValue('E1', '省长')
            ->setCellValue('F1', '店长')
            ->setCellValue('G1', '店员数量')
            ->setCellValue('H1', '状态')
            ->setCellValue('I1', 'KPI')
            ->setCellValue('J1', '建档人')
            ->setCellValue('K1', '建档时间')
            ->setCellValue('L1', '最后修改人')
            ->setCellValue('M1', '修改时间');
//        $num = 2;
        foreach ($data as $key => $val) {
            $num = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $num - 1)
                ->setCellValue('B' . $num, Html::decode($val['sts_code']))
                ->setCellValue('C' . $num, Html::decode($val['sts_sname']))
                ->setCellValue('D' . $num, Html::decode($val['saleArea']['csarea_name']))
                ->setCellValue('E' . $num, Html::decode($val['sz']['staff_name']))
                ->setCellValue('F' . $num, Html::decode($val['dz']['staff_name']))
                ->setCellValue('G' . $num, Html::decode($val['sts_count']))
                ->setCellValue('H' . $num, Html::decode($val['status_name']['bsp_svalue']))
                ->setCellValue('I' . $num, Html::decode($val['kpi']))
                ->setCellValue('J' . $num, Html::decode($val['createBy']['staff_name']))
                ->setCellValue('K' . $num, Html::decode($val['cdate']))
                ->setCellValue('L' . $num, Html::decode($val['editBy']['staff_name']))
                ->setCellValue('M' . $num, Html::decode($val['edate']));
            for ($i = A; $i != M; $i++) {
                $objActSheet->getColumnDimension($i)->setWidth(22);
                $objActSheet->getDefaultRowDimension()->setRowHeight(18);
                $objActSheet->getStyle($i . $num)->getAlignment()->setHorizontal("center");
                $objActSheet->getStyle($i . "1")->getAlignment()->setHorizontal("center");
                $objActSheet->getStyle($i . '1')->getFont()->setName('黑体')->setSize(14);
            }
//            $num++;
        }
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

    /*省份信息*/
    public function getDistrict()
    {
        $url = $this->findApiUrl() . 'crm/crm-customer-info/' . "district";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*店铺状态*/
    public function getStoreStatus()
    {
        $url = $this->findApiUrl() . $this->_url . 'store-status';
        $storeStatus = Json::decode($this->findCurl()->get($url));
        return $storeStatus;
    }

    /*军区列表*/
    public function getSalearea()
    {
        $url = $this->findApiUrl() . $this->_url . "salearea";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*国家*/
    public function getCountry()
    {
//        $url = $this->findApiUrl() . "/crm/crm-customer-info/get-country";
        $url = $this->findApiUrl() . $this->_url . "get-country";
        $result = $this->findCurl()->get($url);
        return $result;
    }


    public function getSeller()
    {
        $url = $this->findApiUrl() . $this->_url . "get-seller";
        $dataProvider = Json::decode($this->findCurl()->get($url));
//        dumpE($dataProvider);
        return $dataProvider;
    }


    public function getDetailDistrict($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-parent-districts?id=" . $id;
        $dataProvider = json_decode($this->findCurl()->get($url), true);
//        return $dataProvider;
        $addressStr = '';
        foreach ($dataProvider as $k => $v) {
            $addressStr .= ' ' . $v['district_name'];
        }
        return $addressStr;
    }

    /*根据地址五级获取全部信息*/
    public function getAllDistrict($id)
    {
        $url = $this->findApiUrl() . "crm/crm-customer-info/get-all-district?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
}