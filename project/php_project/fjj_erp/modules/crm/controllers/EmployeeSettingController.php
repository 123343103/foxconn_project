<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\modules\hr\models\HrStaff;
use app\modules\system\models\SystemLog;
use yii;
use yii\helpers\Url;
use yii\helpers\Json;

class EmployeeSettingController extends BaseController
{
    private $_url = "crm/employee-setting/";

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $list = Json::decode($this->findCurl()->get($url));
        if (Yii::$app->request->isAjax) {
            foreach ($list['rows'] as $key => $val) {
                $list['rows'][$key]['create_at'] = date("Y-m-d", strtotime($val['create_at']));
                $list['rows'][$key]['update_at'] = $val['update_at'] ? date("Y-m-d", strtotime($val['update_at'])) : '';
                $list['rows'][$key]['staff_code']='<a href="'. yii\helpers\Url::to(['view','id'=>$val['staff_code']]).'">'.$val['staff_code'].'</a>';
            }
            return Json::encode($list);
        }
        $downList = $this->getDownList(); // 军区列表
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->exportFiled($list['rows']);
        }
        $columns = $this->getField("/crm/employee-setting/index");
        return $this->render('index', [
            'downList' => $downList,
            'queryParam' => $queryParam,
            'columns' => $columns,
        ]);
    }

    /*创建销售员*/
    public function actionCreate()
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['CrmEmployee']['create_by'] = Yii::$app->user->identity->staff_id;
            $post['CrmEmployee']['category_id'] = !empty($post['CrmEmployee']['category_id']) ? serialize($post['CrmEmployee']['category_id']) : null;
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog('新增' . $post['CrmEmployee']['staff_code'] . '成功');
                return Json::encode(['msg' => "创建成功", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']['id']])]);
            } else {
                return Json::encode(['msg' => "创建失败", "flag" => 0]);
            }
        } else {
            $downList = $this->getDownList();
            $category = $this->getCategory(); // 获取类别
            $status = [20 => "有效", 10 => "无效"];
            return $this->render('create', [
                'category' => $category,
                'status' => $status,
                'downList' => $downList,
            ]);
        }
    }

    /*查看销售员*/
    public function actionView($id)
    {
        $model = $this->getModel($id);
        $info=$this->getOrgname($id);
        return $this->render('view', [
            'model' => $model,
            'info'=>$info
        ]);
    }

    /*修改销售员*/
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['CrmEmployee']['update_by'] = Yii::$app->user->identity->staff_id;
            $postData['CrmEmployee']['category_id'] = !empty($postData['CrmEmployee']['category_id']) ? serialize($postData['CrmEmployee']['category_id']) : null;
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog('修改' . $postData['CrmEmployee']['staff_code'] . '成功');
                return Json::encode(['msg' => "修改成功！", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']['id']])]);
            } else {
                return Json::encode(['msg' => "修改失败！", "flag" => 0]);
            }
        } else {
            $model = $this->getModel($id);
            $store = Json::decode($this->actionStore($model['csarea_id']));
            $downList = $this->getDownList();
            $category = $this->getCategory(); // 获取类别
//            dumpE($model);
            $status = [20 => "启用", 10 => "禁用"];
            return $this->render('update', [
                'model' => $model,
                'category' => $category,
                'status' => $status,
                'downList' => $downList,
                'store' => $store
            ]);
        }
    }

    /*删除销售员*/
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']['msg']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => $result['msg'].' 删除失败', "flag" => 0]);
        }
    }
    //批量设置状态
    public function actionSetstatus($id='',$sale_status=''){
        if ($post=Yii::$app->request->post()) {
            $url = $this->findApiUrl() . $this->_url . "setstatus";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                return json_encode(['msg'=>'设置成功！','flag'=>1]);
            } else {
                return json_encode(['msg'=>'设置失败！','flag'=>0]);
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


    /*导出数据*/
//    private function getExcelData($data)
//    {
//        $objPHPExcel = new \PHPExcel();
//        $date = date("Y_m_d", time()) . rand(0, 99);
//        $fileName = "_{$date}.xls";
//        $objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue('A1', '序号')
//            ->setCellValue('B1', '姓名')
//            ->setCellValue('C1', '工号')
//            ->setCellValue('D1', '销售角色')
//            ->setCellValue('E1', '所在销售点')
//            ->setCellValue('F1', '营销区域')
//            ->setCellValue('G1', '允许销售商品类别')
//            ->setCellValue('H1', '个人销售提成系数(%)')
//            ->setCellValue('I1', '个人销售目标指数')
//            ->setCellValue('J1', '建档人')
//            ->setCellValue('K1', '建档时间')
//            ->setCellValue('L1', '最后修改人')
//            ->setCellValue('M1', '修改时间');
////        $num = 2;
//
//        foreach ($data as $key => $val) {
//            $num = $key+2;
//            $objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A' . $num, $num-1)
//                ->setCellValue('B' . $num, $val['staff_name'])
//                ->setCellValue('C' . $num, $val['staff_code'])
//                ->setCellValue('D' . $num, $val['sarole_sname'])
//                ->setCellValue('E' . $num, $val['sts_sname'])
//                ->setCellValue('F' . $num, $val['csarea_name'])
//                ->setCellValue('G' . $num, $val['category_id'])
//                ->setCellValue('H' . $num, $val['sale_qty'])
//                ->setCellValue('I' . $num, $val['sale_quota'])
//                ->setCellValue('J' . $num, $val['create_by'])
//                ->setCellValue('K' . $num, $val['create_at'])
//                ->setCellValue('L' . $num, $val['update_by'])
//                ->setCellValue('M' . $num, $val['update_at']);
//            for ($i = A; $i <= M; $i++) {
//                $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setWidth(22);
//                $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(18);
//                $objPHPExcel->getActiveSheet()->getStyle($i . $num)->getAlignment()->setHorizontal("center");
//                $objPHPExcel->getActiveSheet()->getStyle($i . "1")->getAlignment()->setHorizontal("center");
//                $objPHPExcel->getActiveSheet()->getStyle($i . '1')->getFont()->setName('宋体')->setSize(16);
//                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
//                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50);
//                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
//                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
//            }
////            $num++;
//        }
//
//
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


    public function actionGetStaffInfo($code)
    {
        $url = $this->findApiUrl() . $this->_url . "get-staff-info?code=" . $code;
        return $this->findCurl()->get($url);
    }


    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*通过销售区域获取销售点列表*/
    public function actionStore($id)
    {
        $url = $this->findApiUrl() . $this->_url . "store?id=" . $id;
        $result = $this->findCurl()->get($url);
        return $result;
    }

    /*获取销售点*/
    public function actionStoreById($id)
    {
        $url = $this->findApiUrl() . $this->_url . "store-by-id?id=" . $id;
        $result = $this->findCurl()->get($url);
        return $result;
    }

    //销售角色带出人力类型
    public function actionSaleRole($id)
    {
        $url = $this->findApiUrl() . $this->_url . "sale-role?id=" . $id;
        $result = $this->findCurl()->get($url);
        return $result;
    }

    //销售点带出上司省长
    public function actionStoreInfo($id)
    {
        $url = $this->findApiUrl() . $this->_url . "store-info?id=" . $id;
        $result = $this->findCurl()->get($url);
        return $result;
    }

    //上司角色
    public function actionLeaderRole($id)
    {
        $url = $this->findApiUrl() . $this->_url . "leader-role?id=" . $id;
        $result = $this->findCurl()->get($url);
        return $result;
    }

    /**
     * 獲取分級分類信息
     * @return array|yii\db\ActiveRecord[]
     */
    public function getCategory()
    {
        $url = $this->findApiUrl() . $this->_url . "category";
        return Json::decode($this->findCurl()->get($url));
    }

    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new yii\web\NotFoundHttpException('页面未找到');
        }
    }
    private function getOrgname($id){
        $url = $this->findApiUrl() . $this->_url . "orgname?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new yii\web\NotFoundHttpException('页面未找到');
        }
    }
}