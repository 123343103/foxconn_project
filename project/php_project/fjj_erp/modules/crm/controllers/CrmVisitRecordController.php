<?php
/**
 * User: F1677929
 * Date: 2017/3/29
 */
namespace app\modules\crm\controllers;

use app\classes\Menu;
use app\controllers\BaseController;
use app\models\User;
use app\modules\system\models\SystemLog;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use Yii;
use yii\helpers\Html;

//客户拜访记录控制器
class CrmVisitRecordController extends BaseController
{
    //所有操作执行之前执行
    public function beforeAction($action)
    {
        $this->ignorelist=array_merge($this->ignorelist,[
            "/crm/crm-visit-record/select-customer",
            "/crm/crm-visit-record/select-plan",
            "/crm/crm-visit-record/record-new-judge",
            "/crm/crm-visit-record/claim-customer",
            "/crm/crm-visit-record/view-record",
            "/crm/crm-visit-record/view-records",
        ]);
        if($this->getRoute()=='crm/crm-visit-record/add' && Menu::isAction('/crm/crm-customer-info/record-add')==true){
            $this->ignorelist=array_merge($this->ignorelist,['/crm/crm-visit-record/add']);
        }
        return parent::beforeAction($action);
    }

    //客户拜访记录列表
    public function actionIndex()
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-record/index';
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
            if(Menu::isAction('/crm/crm-visit-record/view')){
                $dataProvider=Json::decode($dataProvider);
                if(!empty($dataProvider['rows'])){
                    foreach($dataProvider['rows'] as &$val){
                        $val['sih_code']="<a onclick='window.location.href=\"".Url::to(['view-records','mainId'=>$val['sih_id']])."\";event.stopPropagation();'>".$val['sih_code']."</a>";
                    }
                }
                return Json::encode($dataProvider);
            }
            return $dataProvider;
        }
        $data = Json::decode($this->findCurl()->get($url));
        $data['mainTable']=$this->getField('/crm/crm-visit-record/index');
        $data['childTable']=$this->getField('/crm/crm-visit-record/load-record');
        return $this->render('index', ['data' => $data]);
    }

    //加载客户拜访记录
    public function actionLoadRecord()
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-record/load-record';
        $url .= '?' . http_build_query(Yii::$app->request->queryParams);
        if(empty(Yii::$app->user->identity->is_supper)){
            $url .= '&staffId=' . Yii::$app->user->identity->staff_id;
        }
        $dataProvider=Json::decode($this->findCurl()->get($url));
        if(!empty($dataProvider['rows'])){
            foreach($dataProvider['rows'] as &$val){
                if(Menu::isAction('/crm/crm-visit-record/view')){
                    $val['sil_code']="<a onclick='window.location.href=\"".Url::to(['view-record','childId'=>$val['sil_id']])."\";event.stopPropagation();'>".$val['sil_code']."</a>";
                }
                if(Menu::isAction('/crm/crm-visit-plan/view')){
                    $val['svp_code']="<a onclick='window.location.href=\"".Url::to(['/crm/crm-visit-plan/view','id'=>$val['svp_id']])."\";event.stopPropagation();'>".$val['svp_code']."</a>";
                }
                $val['create_at']=date("Y-m-d",strtotime($val['create_at']));
            }
        }
        return Json::encode($dataProvider);
    }

    //新增拜访记录 由客户或者计划添加
    public function actionAdd($customerId = '', $planId = '')
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-record/add?visitPersonId=' . Yii::$app->user->identity->staff_id . '&customerId=' . $customerId . '&planId=' . $planId;
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $data['CrmVisitRecord']['create_by'] = Yii::$app->user->identity->staff_id;
            $data['CrmVisitRecord']['company_id'] = Yii::$app->user->identity->company_id;
            $data['CrmVisitRecordChild']['create_by'] = Yii::$app->user->identity->staff_id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
            $result = Json::decode($curl->post($url));
            if ($result['status'] == 1) {
                SystemLog::addLog('新增客户拜访记录' . $result['msg']);
                return Json::encode(['msg' => '新增成功', 'flag' => 1, 'url' => Url::to(['view-record', 'childId' => $result['data']])]);
            }
            return Json::encode(['msg' => $result['msg'], 'flag' => 0]);
        }
        $data = Json::decode($this->findCurl()->get($url));
        if (!empty($customerId) && !$data['customerInfo']) {
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('add', ['data' => $data, 'planId' => $planId]);
    }

    //判断拜访记录是否是当前用户下最新的记录
    public function actionRecordNewJudge($childId)
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-record/record-new-judge';
        $url .= '?childId=' . $childId;
        $url .= '&staffId=' . Yii::$app->user->identity->staff_id;
        $data = Json::decode($this->findCurl()->get($url));
        if($data['status']==0){
            return Json::encode(['msg'=>$data['msg'],'flag'=>0]);
        }
        return Json::encode(['flag'=>1]);
    }

    //修改拜访记录
    public function actionEdit($childId)
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-record/edit';
        $url .= '?childId=' . $childId;
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $data['CrmVisitRecord']['update_by'] = Yii::$app->user->identity->staff_id;
            $data['CrmVisitRecord']['company_id'] = Yii::$app->user->identity->company_id;
            $data['CrmVisitRecordChild']['update_by'] = Yii::$app->user->identity->staff_id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
            $result = Json::decode($curl->post($url));
            if ($result['status'] == 1) {
                SystemLog::addLog('修改客户拜访记录' . $result['msg']);
                return Json::encode(['msg' => '修改成功', 'flag' => 1, 'url' => Url::to(['view-record', 'childId' => $childId])]);
            }
            return Json::encode(['msg' => $result['msg'], 'flag' => 0]);
        }
        $data = Json::decode($this->findCurl()->get($url));
        if (empty($data['recordChild'])) {
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('edit', ['data' => $data]);
    }

    /**
     * author:F3859386
     * 临时拜访记录
     * @return string
     */
//    public function actionCreateTemp()
//    {
//        $url = $this->findApiUrl() . 'crm/crm-visit-record/create-temp';
//        if ($post = Yii::$app->request->post()) {
//            $post['CrmVisitRecordChild']['title'] = '拜访' . $post['cust_name'];
//            $post['CrmVisitRecordChild']['start'] = $post['arriveDate'];
//            $post['CrmVisitRecordChild']['end'] = $post['leaveDate'];
//            $post['CrmVisitRecordChild']['color'] = '#C0C0C0';
//            $post['CrmVisitRecordChild']['create_by'] = $post['CrmVisitRecord']['create_by'] = Yii::$app->user->identity->staff_id;
//            $post['CrmVisitRecordChild']['company_id'] = $post['CrmVisitRecord']['company_id'] = Yii::$app->user->identity->company_id;
//            $post['CrmVisitRecordChild']['sil_time'] = serialize([$post['day'], $post['hour'], $post['minute']]);
//            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
//            $data = Json::decode($curl->post($url));
//            if ($data['status']) {
//                SystemLog::addLog('新增临时拜访');
//                return Json::encode(['msg' => "新增临时拜访成功", "flag" => 1, "url" => Url::to(['/crm/crm-visit-record/index'])]);
//            } else {
//                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
//            }
//        }
//
//        $data = Json::decode($this->findCurl()->get($url));
//        return $this->render('create-temp', [
//            'downList' => $data,
//            'userInfo' =>  $this->getUserInfo()
//        ]);
//    }

//    /**
//     * author:F3859386
//     *  新增
//     * @return string
//     */
//    public function actionCreate()
//    {
//        $url = $this->findApiUrl() . 'crm/crm-visit-record/create';
//        $data = Yii::$app->request->post();
//        $data['CrmVisitRecordChild']['start'] = $data['arriveDate'];
//        $data['CrmVisitRecordChild']['end'] = $data['leaveDate'];
//        $data['CrmVisitRecordChild']['title'] = '拜访' . $data['cust_name'];
////        $data['CrmVisitRecordChild']['color'] = '#FEE188';
//        $data['CrmVisitRecord']['create_by'] = Yii::$app->user->identity->staff_id;
//        $data['CrmVisitRecord']['company_id'] = Yii::$app->user->identity->company_id;
//        $data['CrmVisitRecordChild']['create_by'] = Yii::$app->user->identity->staff_id;
//        $data['CrmVisitRecordChild']['company_id'] = Yii::$app->user->identity->company_id;
//        $data['CrmVisitRecordChild']['sil_time'] = serialize([$data['day-1'], $data['hour-1'], $data['minute-1']]);
//        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
//        $result = Json::decode($curl->post($url));
//        if ($result['status']) {
//            return Json::encode(['msg' => '新增成功！', 'flag' => 1, 'url' => Url::to(['/crm/crm-plan-manage/index'])]);
//        }
//        return Json::encode(['msg' => $result['msg'], 'flag' => 0]);
//    }

    //选择客户
    public function actionSelectCustomer()
    {
        $params = Yii::$app->request->queryParams;
        if (Yii::$app->request->isAjax) {
            $url = $this->findApiUrl() . 'crm/crm-visit-record/select-customer';
            $url .= '?companyId=' . Yii::$app->user->identity->company_id;
            if(empty(Yii::$app->user->identity->is_supper)){
                $url .= '&staffId=' . Yii::$app->user->identity->staff_id;
            }
            $url .= '&' . http_build_query($params);
            return $this->findCurl()->get($url);
        }
        return $this->renderAjax('select-customer', ['params' => $params]);
    }

    //认领客户
    public function actionClaimCustomer($customerId)
    {
        $url=$this->findApiUrl().'crm/crm-visit-record/claim-customer';
        $url.='?customerId='.$customerId;
        $url.='&staffId='.Yii::$app->user->identity->staff_id;
        $result=Json::decode($this->findCurl()->get($url));
        if ($result['status'] == 1) {
            SystemLog::addLog('认领客户：' . $result['msg']);
            return Json::encode(['msg' => '认领成功！', 'flag' => 1]);
        }
        return Json::encode(['msg' => $result['msg'], 'flag' => 0]);
    }

    //选择拜访计划
    public function actionSelectPlan()
    {
        $params = Yii::$app->request->queryParams;
        if (Yii::$app->request->isAjax) {
            $url = $this->findApiUrl() . 'crm/crm-visit-record/select-plan';
            $url .= '?companyId=' . Yii::$app->user->identity->company_id;
            if(Yii::$app->user->identity->is_supper!=1){
                $url .= '&staff_id='.Yii::$app->user->identity->staff_id;
            }
            $url .= '&' . http_build_query($params);
            return $this->findCurl()->get($url);
        }
        return $this->renderAjax('select-plan', ['params' => $params]);
    }

    //查看一条拜访记录
    public function actionViewRecord($childId)
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-record/view-record';
        $url .= '?childId=' . $childId;
        if(empty(Yii::$app->user->identity->is_supper)){
            $url .= '&staffId=' . Yii::$app->user->identity->staff_id;
        }
        $data = Json::decode($this->findCurl()->get($url));
        if (empty($data['recordChild']) || empty($data['customerInfo'])) {
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('view-record', ['data' => $data]);
    }

    //查看所有拜访记录
    public function actionViewRecords($mainId)
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-record/view-records';
        $url .= '?mainId=' . $mainId;
        if(empty(Yii::$app->user->identity->is_supper)){
            $url .= '&staffId=' . Yii::$app->user->identity->staff_id;
        }
        $data = Json::decode($this->findCurl()->get($url));
        if (empty($data['customerInfo'])) {
            throw new NotFoundHttpException('页面不存在！');
        }
        return $this->render('view-records', ['data' => $data]);
    }

    //删除
    public function actionDeleteChild($childId)
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-record/delete-child';
        $url .= '?childId=' . $childId;
        $url .= '&staffId=' . Yii::$app->user->identity->staff_id;
        $url .= '&super=' . Yii::$app->user->identity->is_supper;
        $result = Json::decode($this->findCurl()->get($url));
        if ($result['status'] == 1) {
            SystemLog::addLog('客户拜访记录子表删除编号：' . $result['msg']);
            return Json::encode(['msg' => '删除成功！', 'flag' => 1, 'total' => $result['data']]);
        }
        return Json::encode(['msg' => $result['msg'], 'flag' => 0, 'total' => 0]);
    }

    //列表导出
    public function actionExport()
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-record/export?companyId=' . Yii::$app->user->identity->company_id;
        $url .= '&staffId=' . Yii::$app->user->identity->staff_id;
        $url .= '&' . http_build_query(Yii::$app->request->queryParams);
        $data = Json::decode($this->findCurl()->get($url));
        $objPHPExcel = new \PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '编号')
            ->setCellValue('C1', '客户名称')
            ->setCellValue('D1', '客户类型')
            ->setCellValue('E1', '客户经理人')
            ->setCellValue('F1', '营销区域')
            ->setCellValue('G1', '联系人')
            ->setCellValue('H1', '联系电话')
            ->setCellValue('I1', '客户地址');
        foreach ($data as $key => $val) {
            $num = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $num - 1)
                ->setCellValue('B' . $num, Html::decode($val['sih_code']))
                ->setCellValue('C' . $num, Html::decode($val['cust_sname']))
                ->setCellValue('D' . $num, Html::decode($val['customerType']))
                ->setCellValue('E' . $num, Html::decode($val['customerManager']))
                ->setCellValue('F' . $num, Html::decode($val['csarea_name']))
                ->setCellValue('G' . $num, Html::decode($val['cust_contacts']))
                ->setCellValue('H' . $num, Html::decode($val['cust_tel2']))
                ->setCellValue('I' . $num, Html::decode($val['customerAddress']));
            for ($i = A; $i !== J; $i++) {
                $sheet->getColumnDimension("A")->setWidth(10);
                $sheet->getColumnDimension("B")->setWidth(24);
                $sheet->getColumnDimension($i)->setWidth(20);
                $sheet->getColumnDimension("C")->setWidth(30);
                $sheet->getColumnDimension("I")->setWidth(50);
                $sheet->getDefaultRowDimension()->setRowHeight(18);
                $sheet->getColumnDimension($i)->setCollapsed(false);
                $sheet->getStyle($i . '1')->getAlignment()->setHorizontal("center");
                $sheet->getStyle($i . $num)->getAlignment()->setHorizontal("center");
                $sheet->getStyle("C" . $num)->getAlignment()->setHorizontal("left");
                $sheet->getStyle("I" . $num)->getAlignment()->setHorizontal("left");
                $sheet->getStyle($i . '1')->getFont()->setName('黑体')->setSize(14);
            }
        }
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

    //所有详情导出
    public function actionRecordsExport($mainId)
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-record/view-records?mainId=' . $mainId;
        $data = Json::decode($this->findCurl()->get($url));
        $objPHPExcel = new \PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '客户名称')
            ->setCellValue('C1', '客户类型')
            ->setCellValue('D1', '客户经理人')
            ->setCellValue('E1', '营销区域')
            ->setCellValue('F1', '联系人')
            ->setCellValue('G1', '联系电话')
            ->setCellValue('H1', '客户地址')
            ->setCellValue('I1', '拜访人')
            ->setCellValue('J1', '拜访类型')
            ->setCellValue('K1', '拜访开始时间')
            ->setCellValue('L1', '拜访结束时间')
            ->setCellValue('M1', '拜访用时')
            ->setCellValue('N1', '拜访总结')
            ->setCellValue('O1', '关联拜访计划');
        foreach ($data['allRecord'] as $key => $val) {
            $num = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $num - 1)
                ->setCellValue('B' . $num, Html::decode($data['customerInfo']['cust_sname']))
                ->setCellValue('C' . $num, Html::decode($data['customerInfo']['customerType']))
                ->setCellValue('D' . $num, Html::decode($data['customerInfo']['customerManager']))
                ->setCellValue('E' . $num, Html::decode($data['customerInfo']['csarea_name']))
                ->setCellValue('F' . $num, Html::decode($data['customerInfo']['cust_contacts']))
                ->setCellValue('G' . $num, Html::decode($data['customerInfo']['cust_tel2']))
                ->setCellValue('H' . $num, Html::decode($data['customerInfo']['customerAddress']))
                ->setCellValue('I' . $num, Html::decode($val['visitPersonName']))
                ->setCellValue('J' . $num, Html::decode($val['visitType']))
                ->setCellValue('K' . $num, Html::decode($val['start']))
                ->setCellValue('L' . $num, Html::decode($val['end']))
                ->setCellValue('M' . $num, Html::decode($val['sil_time']))
                ->setCellValue('N' . $num, Html::decode($val['sil_interview_conclus']))
                ->setCellValue('O' . $num, Html::decode($val['svp_code']));
            for ($i = A; $i !== P; $i++) {
                $sheet->getColumnDimension("A")->setWidth(10);
                $sheet->getColumnDimension($i)->setWidth(20);
                $sheet->getColumnDimension("H")->setWidth(50);
                $sheet->getColumnDimension("N")->setWidth(50);
                $sheet->getDefaultRowDimension()->setRowHeight(18);
                $sheet->getColumnDimension($i)->setCollapsed(false);
                $sheet->getStyle($i . '1')->getAlignment()->setHorizontal("center");
                $sheet->getStyle($i . $num)->getAlignment()->setHorizontal("center");
                $sheet->getStyle($i . '1')->getFont()->setName('黑体')->setSize(14);
            }
        }
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

    //明细表
    public function actionList()
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-record/list';
        //导出
        if(Yii::$app->request->get('export')==1){
            $url.='?companyId='.Yii::$app->user->identity->company_id;
            //排除超级管理员
            if(empty(Yii::$app->user->identity->is_supper)){
                $url .= '&staffId=' . Yii::$app->user->identity->staff_id;
            }
            $url.='&'.http_build_query(Yii::$app->request->queryParams);
            $dataProvider=Json::decode($this->findCurl()->get($url));
            $this->exportFiled($dataProvider['rows']);
        }
        if (Yii::$app->request->isAjax) {
            $url.='?companyId='.Yii::$app->user->identity->company_id;
            //排除超级管理员
            if(empty(Yii::$app->user->identity->is_supper)){
                $url .= '&staffId=' . Yii::$app->user->identity->staff_id;
            }
            $url .= '&' . http_build_query(Yii::$app->request->queryParams);
            $dataProvider=Json::decode($this->findCurl()->get($url));
            if(!empty($dataProvider['rows'])){
                foreach($dataProvider['rows'] as &$val){
                    if(Menu::isAction('/crm/crm-visit-record/view')){
                        $val['sil_code']="<a onclick='window.location.href=\"".Url::to(['view-record','childId'=>$val['sil_id']])."\";event.stopPropagation();'>".$val['sil_code']."</a>";
                    }
                    if(Menu::isAction('/crm/crm-visit-plan/view')){
                        $val['svp_code']="<a onclick='window.location.href=\"".Url::to(['/crm/crm-visit-plan/view','id'=>$val['svp_id']])."\";event.stopPropagation();'>".$val['svp_code']."</a>";
                    }
                    $val['create_at']=date("Y-m-d",strtotime($val['create_at']));
                }
            }
            return Json::encode($dataProvider);
        }
        $data = Json::decode($this->findCurl()->get($url));
        $data['listTable']=$this->getField('/crm/crm-visit-record/list');
        return $this->render('list', ['data' => $data]);
    }

    //明细表导出
    public function actionListExport()
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-record/list-export?' . http_build_query(Yii::$app->request->queryParams);
        $data = Json::decode($this->findCurl()->get($url));
        $objPHPExcel = new \PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '档案编号')
            ->setCellValue('C1', '客户名称')
            ->setCellValue('D1', '客户类型')
            ->setCellValue('E1', '客户经理人')
            ->setCellValue('F1', '营销区域')
            ->setCellValue('G1', '联系人')
            ->setCellValue('H1', '联系电话')
            ->setCellValue('I1', '客户地址')
            ->setCellValue('J1', '拜访人')
            ->setCellValue('K1', '拜访类型')
            ->setCellValue('L1', '拜访开始时间')
            ->setCellValue('M1', '拜访结束时间')
            ->setCellValue('N1', '拜访用时')
            ->setCellValue('O1', '拜访总结')
            ->setCellValue('P1', '关联拜访计划');
        foreach ($data as $key => $val) {
            $arr = unserialize(Html::decode($val['sil_time']));
            $num = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $num - 1)
                ->setCellValue('B' . $num, Html::decode($val['sil_code']))
                ->setCellValue('C' . $num, Html::decode($val['cust_sname']))
                ->setCellValue('D' . $num, Html::decode($val['customerType']))
                ->setCellValue('E' . $num, Html::decode($val['customerManager']))
                ->setCellValue('F' . $num, Html::decode($val['csarea_name']))
                ->setCellValue('G' . $num, Html::decode($val['cust_contacts']))
                ->setCellValue('H' . $num, Html::decode($val['cust_tel2']))
                ->setCellValue('I' . $num, Html::decode($val['customerAddress']))
                ->setCellValue('J' . $num, Html::decode($val['visitPerson']))
                ->setCellValue('K' . $num, Html::decode($val['visitType']))
                ->setCellValue('L' . $num, Html::decode($val['start']))
                ->setCellValue('M' . $num, Html::decode($val['end']))
                ->setCellValue('N' . $num, $arr[0] . '天' . $arr[1] . '时' . $arr[2] . '分')
                ->setCellValue('O' . $num, Html::decode($val['sil_interview_conclus']))
                ->setCellValue('P' . $num, Html::decode($val['svp_code']));
            for ($i = A; $i !== Q; $i++) {
                $sheet->getColumnDimension($i)->setWidth(20);
                $sheet->getColumnDimension("A")->setWidth(10);
                $sheet->getColumnDimension("C")->setWidth(30);
                $sheet->getColumnDimension("I")->setWidth(40);
                $sheet->getColumnDimension("O")->setWidth(40);
                $sheet->getColumnDimension("P")->setWidth(25);
                $sheet->getDefaultRowDimension()->setRowHeight(20);
                $sheet->getColumnDimension($i)->setCollapsed(false);
                $sheet->getStyle($i . '1')->getAlignment()->setHorizontal("center");
                $sheet->getStyle($i . '1')->getFont()->setName('黑体')->setSize(14);
                $sheet->getStyle($i . $num)->getAlignment()->setHorizontal("center");
                $sheet->getStyle("C" . $num)->getAlignment()->setHorizontal("left");
                $sheet->getStyle("I" . $num)->getAlignment()->setHorizontal("left");
                $sheet->getStyle("O" . $num)->getAlignment()->setHorizontal("left");
                $sheet->getStyle($i . $num)->getAlignment()->setWrapText(true);
            }
        }
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

    /**
     * 获取登录人信息
     * @return mixed
     */
    private function getStaffInfo()
    {
        $url = $this->findApiUrl() . 'hr/staff/return-info?id=' . Yii::$app->user->identity->staff_id;
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * 登陆人信息
     * @return mixed
     */
    private function getUserInfo(){
        $url = $this->findApiUrl() . "/hr/staff/return-info?id=".Yii::$app->user->identity->staff_id;
        $result['staff'] = Json::decode($this->findCurl()->get($url));
        $result['supper'] = Yii::$app->user->identity->is_supper;
        return $result;
    }

    //获取客户经理人
    public function actionGetCustomerManager($id)
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-record/get-customer-manager';
        $url .= '?id=' . $id;
        return $this->findCurl()->get($url);
    }

    //获取拜访计划标识
    public function actionGetPlanFlag($id,$code)
    {
        $url = $this->findApiUrl() . 'crm/crm-visit-record/get-plan-flag';
        $url .= '?id=' . $id;
        $url .= '&code=' . $code;
        return $this->findCurl()->get($url);
    }
}