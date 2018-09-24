<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CrmCreditLimitController implements the CRUD actions for CrmCreditApply model.
 */
class CrmCreditLimitController extends BaseController
{
    public $_url = 'crm/crm-credit-limit/'; //对应api


    /**
     * Lists all CrmCreditApply models.
     * @return mixed
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index?companyId=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        foreach ($dataProvider['rows'] as $key => $val){
            $dataProvider['rows'][$key]['cust_code']='<a href="'. Url::to(['view','id'=>$val['credit_id']]).'">'.$val['cust_code'].'</a>';
            $dataProvider['rows'][$key]['file']='<a target="_blank" href="'. Yii::$app->ftpPath['httpIP'].$val['file_new'].'">'.$val['file_old'].'</a>';
        }
        if (Yii::$app->request->isAjax) {
            return Json::encode($dataProvider);
        }
        $downList = $this->getDownList();
        $columns = $this->getField('/crm/crm-credit-limit/index');
        return $this->render('index', [
            'downList' => $downList,
            'queryParam'=>$queryParam,
            'columns'=>$columns
        ]);
    }

    public function actionList($id){
        $url = $this->findApiUrl() . $this->_url . "list?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
//        echo $this->findCurl()->get($url);exit;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        foreach ($dataProvider['rows'] as $key => $val){
            $dataProvider['rows'][$key]['approval']= ($val['approval'] == '否')?'<span class="red">'.$val['approval'].'</span>':'<span>'.$val['approval'].'</span>';
        }
        if (Yii::$app->request->isAjax) {
            return Json::encode($dataProvider);
        }
        $model = $this->getModel($id);
        $downList = $this->getDownList();
        $columns = $this->getField('/crm/crm-credit-limit/list');
        return $this->render('_record', [
            'queryParam'=>$queryParam,
            'id'=>$id,
            'model'=>$model,
            'downList'=>$downList,
            'columns'=>$columns
        ]);
    }

    /*信用额度导出*/
    public function actionExport()
    {
        $url = $this->findApiUrl() . $this->_url . "index?export=1&companyId=".Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if(empty(Yii::$app->user->identity->is_supper)){
            $queryParam['staff_id']=Yii::$app->user->identity->staff_id;
        }
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        \Yii::$app->controller->action->id = 'index';
        return $this->exportFiled($dataProvider['rows']);
    }

    /*信用额度明细导出*/
    public function actionExportList($id)
    {
        $url = $this->findApiUrl() . $this->_url . "index?id=".$id;
        $queryParam = Yii::$app->request->queryParams;
//        if(empty(Yii::$app->user->identity->is_supper)){
//            $queryParam['staff_id']=Yii::$app->user->identity->staff_id;
//        }
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        \Yii::$app->controller->action->id = 'list';
        return $this->exportFiled($dataProvider['rows']);
    }


    /**
     * Displays a single CrmCreditApply model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->getModel($id);
        $verify = $this->getVerify($id,$model['credit_type']);//審核信息
        $crmcertf = $this->getCrmCertf($model['cust_id']);
        $newnName1 = $crmcertf['bs_license'];
        $newnName1 = substr($newnName1, 2, 6);
//        $newnName1 = str_replace('-', '', $newnName1);
        $newnName2 = $crmcertf['tx_reg'];
        $newnName2 = substr($newnName2, 2, 6);
//        $newnName2 = str_replace('-', '', $newnName2);
        $newnName3 = $crmcertf['qlf_certf'];
        $newnName3 = substr($newnName3, 2, 6);
//        dumpE($crmcertf);
        return $this->render('view',[
            'id'=>$id,
            'model'=>$model,
            'verify'=>$verify,
            'crmcertf' => $crmcertf,
            'newnName1' => $newnName1,
            'newnName2' => $newnName2,
            'newnName3' => $newnName3,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * 查询三证
     */
    public function getCrmCertf($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-apply/crm-certf?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        }
    }

    /**
     * Updates an existing CrmCreditApply model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->aid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CrmCreditApply model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return string
     * 冻结客户帐信额度
     */
    public function actionFreeze($id){
        $url = $this->findApiUrl() . $this->_url . "freeze?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']);
            return Json::encode(["msg" => "冻结成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => $result['msg'].' 冻结失败', "flag" => 0]);
        }
    }

    /**
     * @return string
     * 批量维护信用额度
     */
    public function actionMaintain(){
        if(Yii::$app->request->getIsPost()){
            $post = Yii::$app->request->post();
            $post['CrmCreditLimit']['create_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url."maintain";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
//            dumpE($post);
//            dumpE($data);
            if ($data['status'] == 1) {
                SystemLog::addLog('导入客户信用额度成功');
                return Json::encode(['msg' => $data['msg'], "flag" => 1, "url" => Url::to(['index'])]);

            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $downList = $this->getDownList();
//        dumpE($downList);
        return $this->render('maintain',[
            'downList'=>$downList
        ]);
    }


    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 获取账信申请信息
     */
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
    /**
     * 获取下拉菜单
     */
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * 导出批量维护信用额度模板
     */
    public function actionExportTpl()
    {
        $objPHPExcel = new \PHPExcel();
        $field = ['A','B','C','D','E','F','G','H','I','J'];
        foreach($field as $key => $value){
            //宽度
            $objPHPExcel->getActiveSheet()->getColumnDimension($value)->setWidth(15);
            //标题垂直居中
            $objPHPExcel->getActiveSheet()->getStyle($value)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            // 设置字体
            $objPHPExcel->getActiveSheet()->getStyle( $value)->getFont()->setName('黑体' );
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '法人')
            ->setCellValue('C1', '客户代码')
            ->setCellValue('D1', '客户名称')
            ->setCellValue('E1', '币别')
            ->setCellValue('F1', '申请额度')
            ->setCellValue('G1', '信用额度类型')
            ->setCellValue('H1', '授信额度')
            ->setCellValue('I1', '有效期')
            ->setCellValue('J1', '备注');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . 2, '1')
            ->setCellValue('B' . 2, '富士康科技')
            ->setCellValue('C' . 2, 'C17082900035')
            ->setCellValue('D' . 2, 'qqqqqq')
            ->setCellValue('E' . 2, 'RMB')
            ->setCellValue('F' . 2, '120000')
            ->setCellValue('G' . 2, '保险额度')
            ->setCellValue('H' . 2, '120000')
            ->setCellValue('I' . 2, '2017/10/23')
            ->setCellValue('J' . 2, 'xxx');
        $fileName = "templet.xls";
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

    // 额度使用明细
    public function actionLimitDetails($cust_id,$laid)
    {
        $info = $this->getCustomerInfo($cust_id,$laid);
//        dumpE($p);
        if (Yii::$app->request->isAjax) {
            return $this->getOrderLimitDetails($laid);
        }
        return $this->render('limit-detail',['info' => $info]);
    }

    // 额度使用记录
    public function actionLimitRecord($cust_id,$laid)
    {
        $info = $this->getCustomerInfo($cust_id,$laid);
//        dumpE($this->getOrderLimitDetails($cust_id,$type));
        if (Yii::$app->request->isAjax) {
            return $this->getOrderLimitRecord($laid);
        }
//        dumpE($data);
        return $this->render('limit-record',['info' => $info]);
    }

    // 获取客户信息
    public function getCustomerInfo($cust_id,$laid)
    {
        $url = $this->findApiUrl() . $this->_url . "get-customer-info?cust_id=$cust_id&laid=$laid";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    // 额度使用明细
    public function getOrderLimitDetails($laid)
    {
        $url = $this->findApiUrl() . $this->_url . "order-limit-details?laid=$laid";
        $result = $this->findCurl()->get($url);
        return $result;
    }

    // 额度使用记录
    public function getOrderLimitRecord($laid)
    {
        $url = $this->findApiUrl() . $this->_url . "order-limit-record?laid=$laid";
        $result = $this->findCurl()->get($url);
        return $result;
    }

    public function getVerify($id,$type){
        $url = $this->findApiUrl() . "/system/verify-record/find-verify?id=" . $id."&type=".$type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }
}
