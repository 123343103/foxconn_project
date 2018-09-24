<?php
/**
 * 客户拜访计划
 */
namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use mPDF;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use app\classes\Menu;
use yii\web\NotFoundHttpException;

/**
 * CrmVisitPlanController implements the CRUD actions for CrmVisitPlan model.
 */
class CrmVisitPlanController extends BaseController
{
    private $_url = "crm/crm-visit-plan/";

    //过滤action
    public function beforeAction($action)
    {
        $this->ignorelist=array_merge($this->ignorelist,[
            "/crm/crm-visit-record/select-customer",
            "/crm/crm-visit-plan/cause"
        ]);
        if($this->getRoute()=='crm/crm-visit-plan/create' && Menu::isAction('/crm/crm-customer-info/plan-create')==true){
            $this->ignorelist=array_merge($this->ignorelist,['/crm/crm-visit-plan/create']);
        }
        return parent::beforeAction($action);
    }

    //拜访计划列表
    public function actionIndex()
    {
        $url=$url=$this->findApiUrl().'crm/crm-visit-plan/index';
        //导出
        if(Yii::$app->request->get('export')==1){
            $url.='?companyId='.Yii::$app->user->identity->company_id;
            //排除超级管理员
            if(empty(Yii::$app->user->identity->is_supper)){
//                $url .= '&staffId=' . Yii::$app->user->identity->staff_id;
                $url .= '&uid=' . Yii::$app->user->identity->user_id;
            }
            $url.='&'.http_build_query(Yii::$app->request->queryParams);
            $dataProvider=Json::decode($this->findCurl()->get($url));
            $this->exportFiled($dataProvider['rows']);
        }
        if (Yii::$app->request->isAjax) {
            $url .= '?companyId=' . Yii::$app->user->identity->company_id;
            //排除超级管理员
            if(empty(Yii::$app->user->identity->is_supper)){
//                $url .= '&staffId=' . Yii::$app->user->identity->staff_id;
                $url .= '&uid=' . Yii::$app->user->identity->user_id;
            }
            $url .= '&' . http_build_query(Yii::$app->request->queryParams);
            $dataProvider=$this->findCurl()->get($url);
            if(Menu::isAction('/crm/crm-visit-plan/view')){
                $dataProvider=Json::decode($dataProvider);
                if (!empty($dataProvider['rows'])) {
                    foreach ($dataProvider['rows'] as &$val) {
                        $val['svp_code'] = "<a onclick='window.location.href=\"" . Url::to(['view', 'id' => $val['svp_id']]) . "\";event.stopPropagation();'>" . $val['svp_code'] . "</a>";
                        $val['start']=date('Y-m-d H:i',strtotime($val['start']));
                        $val['end']=date('Y-m-d H:i',strtotime($val['end']));
                        $val['create_at']=date('Y-m-d',strtotime($val['create_at']));
                    }
                }
                return Json::encode($dataProvider);
            }
            return $dataProvider;
        }
        $fields = $this->getField("/crm/crm-visit-plan/index");
        $downList = $this->getDownList();
        return $this->render('index', [
            'downList' => $downList,
            'fields' => $fields
        ]);
    }

    //导出
    public function actionExport()
    {
        $url = $this->findApiUrl() . "crm/crm-visit-plan/index?companyId=" . Yii::$app->user->identity->company_id . "&staff=" . Yii::$app->user->identity->staff_id . "&user=" . Yii::$app->user->identity->user_account;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = json::decode($this->findCurl()->get($url));
        $data = [];
        foreach ($dataProvider['rows'] as $key => $val) {
            $data[$key][1] = Html::decode($val['svp_code']);
            $data[$key][2] = Html::decode($val['customerInfo']['customerName']);
            $data[$key][3] = Html::decode($val['start']);
            $data[$key][4] = Html::decode($val['end']);
            $data[$key][5] = Html::decode($val['visitPerson']);
            $data[$key][6] = Html::decode($val['visitType']);
            $data[$key][7] = Html::decode($val['status']);
            $data[$key][8] = Html::decode($val['createPerson']);
            $data[$key][9] = Html::decode($val['create_at']);
        }
        $this->getExcels($data);
    }

    //详情导出
    public function actionViewExport($planId = "")
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-plan/view?id=' . $planId;
        $data = Json::decode($this->findCurl()->get($url));
        $this->viewExport($data);
    }

    /**
     * 新增
     * @return string
     */
    public function actionCreate($customerId = null)
    {
        if ($post = Yii::$app->request->post()) {
            $post['CrmVisitPlan']['create_by'] = Yii::$app->user->identity->staff_id;
            $post['CrmVisitPlan']['company_id'] = Yii::$app->user->identity->company_id;
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog('新增编号为' . $data['data']['code'] . '的拜访计划');
                return Json::encode(['msg' => $data['msg'], "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $downList = $this->getDownList();
        $model = $this->getCust($customerId);
        return $this->render('create', [
            'downList' => $downList,
            'userInfo' => $this->getUserInfo(),
            'model' => $model
        ]);
    }


    //编辑拜访计划
    public function actionUpdate($id)
    {
        if ($post = Yii::$app->request->post()) {
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog('修改编号为' . $data['data']['code'] . '的拜访计划');
                return Json::encode(['msg' => $data['msg'], "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $dataProvider = $this->actionGetOnePlan($id);
        if (empty($dataProvider['planData'])) {
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('update', [
            'model' => $dataProvider['planData'],
            'accompanyData' => $dataProvider['accompanyData'],
            'userInfo' => $this->getUserInfo(),
            'downList' => $this->getDownList()
        ]);
    }

    //查看拜访计划
    public function actionView($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'view?id=' . $id;
        $model = Json::decode($this->findCurl()->get($url));
        return $this->render('view', [
            'model' => $model['planData'],
            'accompanyData' => $model['accompanyData']
        ]);
    }

    public function actionPdf($id)
    {
        $this->layout = false;

        $url = $this->findApiUrl() . $this->_url . 'view?id=' . $id;
        $model = Json::decode($this->findCurl()->get($url));

        $mpdf = new mPDF('+aCJK', 'A4', '', '', 10, 10, 10, 10, 10, 10);
//        $mpdf =new mPDF('+aCJK','A4','','',32,25,27,25,16,13);
//        $pdf = new mPDF('zh-CN');
        $mpdf->useAdobeCJK = true;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetAutoFont(AUTOFONT_ALL);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->showWatermarkText = true;
        $stylesheet = file_get_contents(YII::$app->basePath . '/web/css/main.css');

        $strContent = $this->render('pdfview', [
            'model' => $model,
        ]);
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($strContent, 2);
        $mpdf->Output("拜访计划" . $model['svp_code'] . ".pdf", 'D');
    }

    /**
     * 获取所有计划
     * @return mixed
     */
    public function actionPlanData()
    {
        $url = $this->findApiUrl() . $this->_url . "plan-data";
        $dataProvider = $this->findCurl()->get($url);
        return $dataProvider;
    }

    //type=1 取消计划  type=2 终止计划
    public function actionCause($svp_id, $type, $cause=null)
    {

        return $this->renderAjax("cause", ['svp_id' => $svp_id, 'type' => $type, 'cause' => $cause]);
    }

    /**
     * 获取一条计划
     * @return mixed
     */
    public function actionGetOnePlan($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-one-plan?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * 登陆人信息
     * @return mixed
     */
    private function getUserInfo()
    {
        $url = $this->findApiUrl() . "/hr/staff/return-info?id=" . Yii::$app->user->identity->staff_id;
        $result['staff'] = Json::decode($this->findCurl()->get($url));
//        $result['supper']=User::isSupper(Yii::$app->user->identity->user_id);
        return $result;
    }

    /**
     * 下拉列
     * @return mixed
     */
    private function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    private function getCust($id)
    {
        $url = $this->findApiUrl() . $this->_url . "cust?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * 取消拜访计划
     */
    public function actionCancel($svp_id='',$type='')
    {
        if($post=Yii::$app->request->post()){
            $url = $this->findApiUrl() . $this->_url . 'cancel';
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog('取消拜访计划');
                return Json::encode(['msg' => "取消成功", "flag" => 1]);
            }
            return Json::encode(['msg' => $data['msg'], "flag" => 0]);
        }
        return $this->renderAjax('cause',['svp_id'=>$svp_id,'type'=>$type]);
    }

    /**
     * 终止拜访计划
     */
    public function actionStop($svp_id='',$type='')
    {
        if($post=Yii::$app->request->post()){
            $url = $this->findApiUrl() . $this->_url . 'stop';
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog('终止拜访计划');
                return Json::encode(['msg' => "终止成功", "flag" => 1]);
            }
            return Json::encode(['msg' => $data['msg'], "flag" => 0]);
        }
        return $this->renderAjax('cause',['svp_id'=>$svp_id,'type'=>$type]);
    }

    /**
     *
     * 获取excel数据
     * @param $headArr
     * @param $data
     */
    private function getExcels($data)
    {
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xls";
        // 创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        // 设置表头
        $key = "A";
        $headArr = [
            '序号',
            '档案编号',
            '客户名称',
            '开始时间',
            '结束时间',
            '拜访人',
            '拜访类型',
            '状态',
            '档案建立人',
            '建档日期',
        ];

        foreach ($headArr as $v) {
            $colum = $key;
//            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            if ($key == "Z") {
                $key = "AA";
            } elseif ($key == "AZ") {
                $key = "BA";
            } else {
                $key++;
            }
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();


        foreach ($data as $key => $rows) { // 行写入
            $span = "B";
            foreach ($rows as $keyName => $value) { // 列写入

                $j = $span;
                $objActSheet->setCellValue('A' . $column, $column - 1);
                $objActSheet->setCellValue($j . $column, $value);

                if ($span == "Z") {
                    $span = "AA";
                } elseif ($span == "AZ") {
                    $span = "BA";
                } else {
                    $span++;
                }
                $objActSheet->getColumnDimension($j)->setWidth(22);
                $objActSheet->getDefaultRowDimension()->setRowHeight(18);
                $objActSheet->getColumnDimension($j)->setCollapsed(true);
                $objActSheet->getStyle($j . '1')->getAlignment()->setHorizontal("center");
                $objActSheet->getStyle($j . '1')->getFont()->setName('黑体')->setSize(14);
                $objActSheet->getStyle($j . $column)->getAlignment()->setHorizontal("center");
                $objActSheet->getStyle('A' . $column)->getAlignment()->setHorizontal("center");
            }
            $column++;
        }

        $fileName = iconv("utf-8", "gb2312", $fileName);
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
        SystemLog::addLog('导出拜访计划');
        exit();
    }

    //数据导出
    private function viewExport($data)
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '档案编号')
            ->setCellValue('C1', '客户名称')
            ->setCellValue('D1', '开始时间')
            ->setCellValue('E1', '结束时间')
            ->setCellValue('F1', '拜访人')
            ->setCellValue('G1', '拜访类型')
            ->setCellValue('H1', '状态')
            ->setCellValue('I1', '档案建立人')
            ->setCellValue('J1', '建档日期');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . 2, 1)
            ->setCellValue('B' . 2, $data['svp_code'])
            ->setCellValue('C' . 2, $data['customerInfo']['customerName'])
            ->setCellValue('D' . 2, $data['start'])
            ->setCellValue('E' . 2, $data['end'])
            ->setCellValue('F' . 2, $data['visitPerson'])
            ->setCellValue('G' . 2, $data['visitType'])
            ->setCellValue('H' . 2, $data['status'])
            ->setCellValue('I' . 2, $data['create_by'])
            ->setCellValue('J' . 2, $data['create_at']);
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

    //删除计划
    public function actionDeletePlan($id)
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-plan/delete-plan';
        $url .= '?id=' . $id;
        $result = Json::decode($this->findCurl()->get($url));
        if ($result['status'] == 1) {
            SystemLog::addLog('删除拜访计划' . $result['data']);
            return Json::encode(['msg' => $result['msg'], 'flag' => 1]);
        }
        return Json::encode(['msg' => $result['msg'], 'flag' => 0]);
    }
}
