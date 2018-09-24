<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use yii;
use yii\helpers\Url;
use yii\helpers\Json;

class RoleSettingController extends BaseController
{
    private $_url = "crm/role-setting/";

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }else{
            $url .= "?CrmSaleRolesSearch[sarole_status]=20";
        }
        if (Yii::$app->request->isAjax) {
            $list = Json::decode($this->findCurl()->get($url)); // 店铺列表数据列表
            foreach ($list['rows'] as $key => $val) {
                $list['rows'][$key]['sarole_code'] = '<a onclick="view('.$val['sarole_id'].')">' . $val['sarole_code'] . '</a>';
            }
            return Json::encode($list);
        }
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->exportFiled(Json::decode($this->findCurl()->get($url))['rows']);
        }
        $employeeType = $this->getEmployeeType();
        $roleStatus = [10 => "禁用", 20 => "启用"];
        $columns = $this->getField('/crm/role-setting/index');
        return $this->render('index', [
            'queryParam' => $queryParam,
            "roleStatus" => $roleStatus,
            "employeeType" => $employeeType,
            'columns' => $columns
        ]);
    }

    /*创建销售角色*/
    public function actionCreate()
    {
        $this->layout = '@app/views/layouts/ajax';
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['CrmSaleRoles']['create_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog('创建角色'.$post['CrmSaleRoles']['sarole_sname'].'成功');
                return Json::encode(['msg' => "创建成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "创建失败", "flag" => 0]);
            }
        } else {
            $employeeType = $this->getEmployeeType();
            $roleStatus = [20 => "启用", 10 => "禁用"];
            return $this->render('create', ["employeeType" => $employeeType, "roleStatus" => $roleStatus]);
        }
    }

    /*查看销售角色*/
    public function actionView($id)
    {
        $this->layout = '@app/views/layouts/ajax';
        $url = $this->findApiUrl() . $this->_url . 'view?id=' . $id;
        $model = Json::decode($this->findCurl()->get($url));
//        dumpE($model);die();
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /*修改销售角色*/
    public function actionUpdate($id)
    {
        $this->layout = '@app/views/layouts/ajax';
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['CrmSaleRoles']['update_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog('修改角色'.$postData['CrmSaleRoles']['sarole_sname'].'成功');
                return Json::encode(['msg' => "修改成功！", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "修改失败！", "flag" => 0]);
            }
        } else {
            $url = $this->findApiUrl() . $this->_url . 'view?id=' . $id;
            $model = Json::decode($this->findCurl()->get($url));
            $employeeType = $this->getEmployeeType();
            $roleStatus = [20 => "启用", 10 => "禁用"];
            return $this->render('update', ['model' => $model, "employeeType" => $employeeType, "roleStatus" => $roleStatus]);
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
    //批量设置状态
    public function actionSetstatus($id='',$sarole_status=''){
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

    /*删除销售角色*/
    public function actionDeleteRole($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'delete-role?id=' . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']['msg']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => $result['msg'].' 删除失败', "flag" => 0]);
        }
    }

    /*导出数据*/
//    private function getExcelData($data)
//    {
//        $objPHPExcel = new \PHPExcel();
//        $objActSheet = $objPHPExcel->getActiveSheet();
//        $date = date("Y_m_d", time()) . rand(0, 99);
//        $fileName = "_{$date}.xls";
//        $objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue('A1', '序号')
//            ->setCellValue('B1', '销售角色编码')
//            ->setCellValue('C1', '销售角色名称')
//            ->setCellValue('D1', '提成系数(%)')
//            ->setCellValue('E1', '销售人力类型')
//            ->setCellValue('F1', '状态')
//            ->setCellValue('G1', '档案建立人')
//            ->setCellValue('H1', '建档日期')
//            ->setCellValue('I1', '最后修改人')
//            ->setCellValue('J1', '修改日期');
//        foreach ($data as $key => $val) {
//            $num = $key + 2;
//            $objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A' . $num, $num - 1)
//                ->setCellValue('B' . $num, $val['sarole_code'])
//                ->setCellValue('C' . $num, $val['sarole_sname'])
//                ->setCellValue('D' . $num, $val['sarole_qty'])
//                ->setCellValue('E' . $num, $val['roleType'])
//                ->setCellValue('F' . $num, $val['statuas'])
//                ->setCellValue('G' . $num, $val['createBy'])
//                ->setCellValue('H' . $num, $val['cdate'])
//                ->setCellValue('I' . $num, $val['updateBy'])
//                ->setCellValue('J' . $num, $val['udate']);
//            for($i=A;$i<=J;$i++){
//                $objActSheet->getColumnDimension($i)->setWidth(22);
//                $objActSheet->getDefaultRowDimension()->setRowHeight(18);
//                $objActSheet->getStyle($i . $num)->getAlignment()->setHorizontal("center");
//                $objActSheet->getStyle($i . "1")->getAlignment()->setHorizontal("center");
//                $objActSheet->getStyle($i . '1')->getFont()->setName('宋体')->setSize(16);
//                $objActSheet->getColumnDimension('A')->setWidth(8);
//            }
//        }
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


    /*销售人力类型*/
    public function getEmployeeType()
    {
        $url = $this->findApiUrl() . $this->_url . 'employee-type';
        $employeeType = Json::decode($this->findCurl()->get($url));
        return $employeeType;
    }

}