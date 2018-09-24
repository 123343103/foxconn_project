<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use yii;
use yii\helpers\Url;
use yii\helpers\Json;

class AreaSettingController extends BaseController
{
    private $_url = "crm/area-setting/";

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {
            $list = Json::decode($this->findCurl()->get($url)); // 店铺列表数据列表
            foreach ($list['rows'] as $key => $val){
                $list['rows'][$key]['csarea_code']='<a href="'. Url::to(['view','id'=>$val['csarea_id']]).'">'.$val['csarea_code'].'</a>';
            }
            return Json::encode($list);
        }
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->exportFiled(Json::decode($this->findCurl()->get($url))['rows']);
        }
        $columns = $this->getField('/crm/area-setting/index');
        $Status = [20 => "启用",10 => "禁用"];
        //dumpE($queryParam);
        return $this->render('index',[
                'columns'=>$columns,
                'Status' => $Status,
                'queryParam' => $queryParam,
            ]
        );
    }

    /*创建销售区域*/
    public function actionCreate()
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['CrmSalearea']['create_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog('新增'.$post['CrmSalearea']['csarea_name'].'成功');
                return Json::encode(['msg' => "创建成功", "flag" => 1, "url" => Url::to(['view','id'=>$data["data"]])]);
            } else {
                return Json::encode(['msg' => "创建失败", "flag" => 0]);
            }
        } else {
            $districtAll = Json::decode($this->getAreaChildren());
            $Status = [20 => "启用",10 => "禁用"];
            return $this->render('create', [
                'Status' => $Status,
                'districtAll' => $districtAll
            ]);
        }
    }

    /*查看销售区域*/
    public function actionView($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'view?id=' . $id;
        $model = Json::decode($this->findCurl()->get($url));
        $arr = explode(' ',$model['dis']);
        return $this->render('view', [
            'model' => $model,
            'arr'   => $arr
        ]);
    }
//批量设置状态
    public function actionSetstatus($id=''){
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
    /*修改销售区域*/
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['CrmSalearea']['update_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                return Json::encode(['msg' => "修改成功！", "flag" => 1, "url" => Url::to(['view','id'=>$id])]);
            } else {
                return Json::encode(['msg' => "修改失败！", "flag" => 0]);
            }
        } else {
            $url = $this->findApiUrl() . $this->_url . 'view?id=' . $id;
            $model = Json::decode($this->findCurl()->get($url));
            $urld = $this->findApiUrl() . $this->_url . 'show-district?id=' . $id;
            $dis = Json::decode($this->findCurl()->get($urld));
            $Status = [10 => "禁用", 20 => "启用"];
            $districtAll = Json::decode($this->getAreaChildren());
            foreach($dis as $key => $val){
                $city[$key] = Json::decode($this->getCity($val['district_id']));
            }
//            dumpE($city);
            return $this->render('update', [
                'model' => $model,
                'Status' => $Status,
                'districtAll' => $districtAll,
                'dis'=>$dis,
                'city'=>$city
            ]);
        }
    }
    //启用状态
    public function actionEnableAttr($id){
        $url = $this->findApiUrl() . $this->_url . "enable-attr?id=" . $id;
        $result=json_decode($this->findCurl()->get($url),true);
        if($result['status']==1){
            return json_encode(['msg'=>$result['msg'],'flag'=>1]);
        }
        return json_encode(['msg'=>$result['msg'],'flag'=>0]);
    }
    //禁用状态
    public function actionDisableAttr($id){
        $url = $this->findApiUrl() . $this->_url . "disable-attr?id=" . $id;
        $result=json_decode($this->findCurl()->get($url),true);
        if($result['status']==1){
            return json_encode(['msg'=>$result['msg'],'flag'=>1]);
        }
        return json_encode(['msg'=>$result['msg'],'flag'=>0]);
    }

    /*删除销售区域*/
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'delete?id=' . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']['msg']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => $result['msg'].' 删除失败', "flag" => 0]);
        }
    }

//    /*导出数据*/
//    private function getExcelData($data)
//    {
//        $objPHPExcel = new \PHPExcel();
//        $objActSheet = $objPHPExcel->getActiveSheet();
//        $objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue('A1', '序号')
//            ->setCellValue('B1', '区域代码')
//            ->setCellValue('C1', '区域名称')
//            ->setCellValue('D1', '包含地区')
//            ->setCellValue('E1', '创建日期')
//            ->setCellValue('F1', '创建人')
//            ->setCellValue('G1', '更新日期')
//            ->setCellValue('H1', '更新人')
//            ->setCellValue('I1', '备注');
//        foreach ($data as $key => $val) {
//            $num = $key + 2;
//            $objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A' . $num, $num-1)
//                ->setCellValue('B' . $num, $val['csarea_code'])
//                ->setCellValue('C' . $num, $val['csarea_name'])
//                ->setCellValue('D' . $num, $val['dis'])
//                ->setCellValue('E' . $num, $val['create_at'])
//                ->setCellValue('F' . $num, $val['creator'])
//                ->setCellValue('G' . $num, $val['update_at'])
//                ->setCellValue('H' . $num, $val['updateBy'])
//                ->setCellValue('I' . $num, $val['csarea_remark']);
//            for($i= A;$i <= I; $i++){
//                $objActSheet->getColumnDimension($i)->setWidth(22);
//                $objActSheet->getDefaultRowDimension()->setRowHeight(18);
//                $objActSheet->getStyle($i . $num)->getAlignment()->setHorizontal("center");
//                $objActSheet->getStyle($i . "1")->getAlignment()->setHorizontal("center");
//                $objActSheet->getStyle($i . '1')->getFont()->setName('宋体')->setSize(16);
//                $objActSheet->getColumnDimension('A')->setWidth(8);
//                $objActSheet->getColumnDimension('D')->setWidth(50);
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

    /*获取所有身份*/
    private function getAreaChildren()
    {
        $url = $this->findApiUrl() . $this->_url . 'area-children';
        return $this->findCurl()->get($url);
    }

    /*获取所有市区*/
    private function getCity($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'city?id='.$id;
        return $this->findCurl()->get($url);
    }

    /*获取对应市区*/
    private function getDisCity($id,$pid){
        $url = $this->findApiUrl() . $this->_url . 'dis-city?id='.$id.'&pid='.$pid;
        return Json::decode($this->findCurl()->get($url));
    }
}