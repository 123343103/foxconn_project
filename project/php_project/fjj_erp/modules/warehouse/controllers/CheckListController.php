<?php

namespace app\modules\warehouse\controllers;

use app\modules\system\models\SystemLog;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use app\classes\Menu;
use yii\web\NotFoundHttpException;


class CheckListController extends \app\controllers\BaseController
{
    private $_url = 'warehouse/check-list/'; //对应api
    public function actionIndex()
    {
        $url = $this->findApiUrl() . 'warehouse/check-list/index';
        $url2=$this->findApiUrl() . 'warehouse/check-list/export';
        //导出
        if(Yii::$app->request->get('export')==1){
            $queryParam = Yii::$app->request->queryParams;
            if (!empty($queryParam)) {
                $url2 .= "?" . http_build_query($queryParam);
                $url2 .= "&" . http_build_query($queryParam);
            }
            $dataProvider = $this->findCurl()->get($url2);
            $dataProvider = Json::decode($dataProvider);
            $this->exportFiled($dataProvider['tr']);
        }
        if (Yii::$app->request->isAjax) {
            $url .= '?' . http_build_query(Yii::$app->request->queryParams);
            $url .= '&' . http_build_query(Yii::$app->request->queryParams);
            $dataProvider = $this->findCurl()->get($url);
            if (Menu::isAction('/warehouse/check-list/view')) {
                //给请购单号添加单击事件
                $dataProvider = Json::decode($dataProvider);
                if (!empty($dataProvider['rows'])) {
                    foreach ($dataProvider['rows'] as &$val) {
                        $val['ivt_code'] = "<a onclick='window.location.href=\"" . Url::to(['view','id'=>$val['ivt_id'],'code'=> $val['ivt_code']]) . "\";event.stopPropagation();'>" . $val['ivt_code'] . "</a>";
                    }
                }
                return Json::encode($dataProvider);
            }
            return $dataProvider;
        }
        $downlist=$this->getDownLists();
        $dataProvider = $this->findCurl()->get($url);
        $data = Json::decode($dataProvider);
        $data['table']=$this->getField('/warehouse/check-list/index');
        return $this->render('index', ['data' => $data,'downlist'=>$downlist]);
    }

    //商品信息
    public function actionCommodity($id)
    {
        $url = $this->findApiUrl() . 'warehouse/check-list/commodity';
        if (Yii::$app->request->isAjax) {
            $url .= '?' . http_build_query(Yii::$app->request->queryParams);
            $url .= '&' . http_build_query(Yii::$app->request->queryParams);
            $url.='?id='.$id;
            $dataProvider = $this->findCurl()->get($url);

            if (Menu::isAction('/purchase/purchase-notify/view')) {
                //给请购单号添加单击事件
                $dataProvider = Json::decode($dataProvider);
                foreach ($dataProvider['rows'] as &$val) {
                    if (!empty($val['prch_no'])) {
                        $val['prch_no'] = "<a onclick='window.location.href=\"" . Url::to(['/purchase/purchase-notify/view', 'id' => $val['prch_id']]) . "\";event.stopPropagation();'>" . $val['prch_no'] . "</a>";
                    }
                }
                return Json::encode($dataProvider);
            }
            return $dataProvider;
        }
        $fields = $this->getField("/warehouse/check-list/commodity");
        $dataProvider = $this->findCurl()->get($url);
        $data = Json::decode($dataProvider);

        return $this->render('index', ['data' => $data, 'fields' => $fields]);
    }
    //修改
    public function actionUpdate($id)
    {
        if($data=Yii::$app->request->post()){
            $url=$this->findApiUrl().'purchase/purchase-apply/update';
            $url.='?id='.$id;
            return $this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data))->post($url);
        }
        return $this->render('_update');
    }

    //作废
    public function actionCanRsn($id)
    {
        if($data=Yii::$app->request->post()){
            $url=$this->findApiUrl().'warehouse/check-list/can-rsn';
            $url.='?id='.$id;
            return $this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data))->post($url);
        }
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render('can-rsn');
    }
    //盘点单明细
    public function actionDetail()
    {
        $url = $this->findApiUrl() . 'warehouse/check-list/detail';
        if (Yii::$app->request->isAjax) {
            $url .= '?' . http_build_query(Yii::$app->request->queryParams);
            $url .= '&' . http_build_query(Yii::$app->request->queryParams);
            $dataProvider = $this->findCurl()->get($url);
//            if (Menu::isAction('/purchase/purchase-apply/view')) {
//                //给请购单号添加单击事件
//                $dataProvider = Json::decode($dataProvider);
//                if (!empty($dataProvider['rows'])) {
//                    foreach ($dataProvider['rows'] as &$val) {
//                        $val['req_no'] = "<a onclick='window.location.href=\"" . Url::to(['view', 'id' => $val['req_id']]) . "\";event.stopPropagation();'>" . $val['req_no'] . "</a>";
//                    }
//                }
//                return Json::encode($dataProvider);
//            }
            return $dataProvider;
        }
        $fields = $this->getField("/warehouse/check-list/detail");
        $dataProvider = $this->findCurl()->get($url);
        $data = Json::decode($dataProvider);
        return $this->render('detail', ['data' => $data, 'fields' => $fields]);
    }
    //详情
    public function actionView($id,$code)
    {
        $model = $this->getModel($id,$code);
//        $verify=$this->getVerify($id,54);//查看審核狀態
//        dump($model[1]);
        $downlist=$this->getDownLists();
        return $this->render('view', [
            'model' => $model[0],
            'pdtmodel'=>$model[1],
//            'verify'=>$verify,
            'downlist'=>$downlist,
            'code'=>$code,
            'id'=>$id
        ]);
    }
    public function  getModel($id,$code){
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $url.='&code='.$code;
//        dumpE($url);
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }
    // 下拉列表
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "get-down-list";
        return json::decode($this->findCurl()->get($url));
    }
    //新增盘点
    public function actionCreate()
    {
        $url=$this->findApiUrl().$this->_url."create";
        if($data=Yii::$app->request->post())
        {
//            $pam=Yii::$app->user->identity->staff_id;
//            $pam2=Yii::$app->user->identity->company_id;
//            $data['BsReq']['leg_id']=$pam2;
//            $data['BsReq']['app_id']=$pam;
//            $sqll="SELECT t.organization_id FROM hr_organization t LEFT JOIN hr_staff a ON       t.organization_code=a.organization_code WHERE a.staff_id=".$pam." ";
//            $ret=Yii::$app->db->createCommand($sqll)->queryScalar();
//            $data['BsReq']['spp_dpt_id']=$ret;
//            $data['BsReq']['app_date']=date('Y-m-d H:i:s');
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('新增盘点信息');
                return json_encode([
                    'msg'=>$result['msg'],
                    'flag'=>1,
                    'url'=>Url::to(['view','id'=>$result['data']['id'],'code'=>$result['data']['code']]),
//                    'billId'=>$result['data']['id'],
//                    'billTypeId'=>$result['data']['typeId']
                ]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);

        }
//        dumpE(Yii::$app->user->identity->staff_id);
        $id=Yii::$app->user->identity->staff_id;
        $downList=$this->getDownLists($id);
//        dumpE($downList);
        return $this->render('create',[
            'downList'=>$downList,
            'id'=>$id
//            'u'=>$this->getUserInfo(),
//            'fr'=>$this->getCompanyLi()
        ]);
    }

    //选择商品
    public function actionProdSelect(){
        if(\Yii::$app->request->isAjax){
            $params=\Yii::$app->request->queryParams;
            $url = $this->findApiUrl() . $this->_url . "prod-select?".http_build_query($params);
            $ret=$this->findCurl()->get($url);
            return $ret;
        }else{
            return $this->renderAjax("prod-select");
        }
    }
    //获取商品类别
    public function actionGetNextLevel($id="",$prompt=1){
        $url=$this->findApiUrl().$this->_url."get-next-level?id={$id}";
        $data=$this->findCurl()->get($url);
        $data=Json::decode($data);
        if($prompt){
            return Html::renderSelectOptions("",$data,$options=["prompt"=>"请选择"]);
        }
        return Html::renderSelectOptions("",$data);
    }
    //获取请购商品详细信息
    public function actionGetPurchaseProduct()
    {
        $id=Yii::$app->request->queryParams;
        $url=$this->findApiUrl().$this->_url."get-purchase-product";
        $url.='?'.http_build_query($id);
        $result=$this->findCurl()->get($url);
        if(Yii::$app->request->isAjax) {
            return $result;
        }
        return $result;
    }
    //新增请购需求单下拉列表
    function getDownLists($id=null)
    {
        $_url=$this->findApiUrl().$this->_url."dawn-list-by-create?id=".$id;
        return Json::decode($this->findCurl()->get($_url));
    }
    //获取用户登录信息
//    function getUserInfo()
//    {
//        $url=$this->findApiUrl()."/hr/staff/return-info?id=".Yii::$app->user->identity->staff_id;
//        $result=Json::decode($this->findCurl()->get($url));
//        return $result;
//    }
    //获取公司法人
//    public function getCompanyLi()
//    {
//        $pam2=Yii::$app->user->identity->company_id;
//        $sqll2="SELECT t.company_name FROM erp.bs_company t WHERE t.company_id=".$pam2." ";
//        $ret2=Yii::$app->db->createCommand($sqll2)->queryScalar();
//        return $ret2;
//    }
    //根据工号查询联系人和电话
    public function actionGetStaffInfo($code)
    {
        $sqll2="SELECT t.staff_id,t.staff_code, t.staff_name,t.staff_mobile FROM erp.hr_staff t WHERE t.staff_code = '".$code."' ";
        $ret2=Yii::$app->db->createCommand($sqll2)->queryOne();
        return Json::encode($ret2);
    }
    //盘点修改
    public function actionEdit($id,$code)
    {
        $url=$this->findApiUrl().$this->_url .'edit';
        $url.='?id='.$id;
        $url.='&code='.$code;
        if($data=Yii::$app->request->post())
        {
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('修改请购单'.$result['data']['code']);
                return json_encode([
                    'msg'=>$result['msg'],
                    'flag'=>1,
                    'url'=>Url::to(['view','id'=>$id,'code'=>$code]),
                ]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $downList=$this->getDownLists();
        $model = $this->getModel($id,$code);
//        $verify=$this->getVerify($id,54);//查看審核狀態
        return $this->render('edit', [
            'model' => $model[0],
            'pdtmodel'=>$model[1],
//            'verify'=>$verify,
            'downList'=>$downList
        ]);
    }
    //添加复盘信息
    public function actionAddMsg($id,$code)
    {
        $url=$this->findApiUrl().$this->_url .'edit';
        $url.='?id='.$id;
        $url.='&code='.$code;
        if($data=Yii::$app->request->post())
        {
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('添加成功'.$result['data']['code']);
                return json_encode([
                    'msg'=>$result['msg'],
                    'flag'=>1,
                    'url'=>Url::to(['view','id'=>$id,'code'=>$code]),
//                    'billId'=>$id,
//                    'billTypeId'=>54
                ]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $downList=$this->getDownLists();
        $model = $this->getModel($id,$code);
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render('add-msg', [
            'model' => $model[0],
            'pdtmodel'=>$model[1],
            'downList'=>$downList
        ]);
    }
    //盘点明细单导出
    public function actionExportDetail()
    {
        $url = $this->findApiUrl() . $this->_url . "export-detail";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        $dataProvider = Json::decode($dataProvider);
        return $this->getExcelDatas($dataProvider);
    }
    private function getExcelDatas($dataProvider)
    {
        $data = $dataProvider['tr'];
        $i=1;
        foreach ($data as $key => $val) {
            $data[$key]['ivt_id']=$i;
            $i++;
//            unset($data[$key]['ivt_id']);
        }
        $headArr=['序号','盘点单号','法人','期别','仓库名称','仓库代码','库存截止时间',
            '料号','品名','规格型号','单位','库存数量','初盘人','初盘数量',
            '初盘日期','复盘人','复盘数量','复盘日期','盈亏数量','盈亏金额','初盘备注','复盘备注','状态'];
        ;
        $this->getExcel($headArr, $data);
    }

    private function getExcel($headArr, $data)
    {

        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xls";
        // 创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        // 设置表头
        $key = "A";
        foreach ($headArr as $v) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(18);
            $colum = $key;
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
            $span = "A";
            foreach ($rows as $keyName => $value) { // 列写入
                $j = $span;
                $objActSheet->setCellValue($j . $column, $value);

                if ($span == "Z") {
                    $span = "AA";
                } elseif ($span == "AZ") {
                    $span = "BA";
                } else {
                    $span++;
                }
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
        SystemLog::addLog('导出盘点单结果');
        exit();
    }
    //导出
    public function actionExport()
    {
        $url = $this->findApiUrl() . $this->_url . "export";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        $dataProvider = Json::decode($dataProvider);
        return $this->getExcelData($dataProvider);
    }


    private function getExcelData($dataProvider)
    {
        $data = $dataProvider['tr'];
        $i=1;
        foreach ($data as $key => $val) {
            $data[$key]['ivt_id']=$i;
            $i++;
//            unset($data[$key]['ivt_id']);
        }
        $headArr=['序号','盘点单号','法人','期别','仓库名称','仓库代码','库存截止时间','初盘人','初盘日期','复盘人','复盘日期','状态'];
        ;
        $this->getExcels($headArr, $data);
    }

    private function getExcels($headArr, $data)
    {

        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xls";
        // 创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        // 设置表头
        $key = "A";
        foreach ($headArr as $v) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(18);
            $colum = $key;
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
            $span = "A";
            foreach ($rows as $keyName => $value) { // 列写入
                $j = $span;
                $objActSheet->setCellValue($j . $column, $value);

                if ($span == "Z") {
                    $span = "AA";
                } elseif ($span == "AZ") {
                    $span = "BA";
                } else {
                    $span++;
                }
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
        SystemLog::addLog('导出盘点单结果');
        exit();
    }
    public function actionReviewer($type,$code, $id, $url = null)
    {
        if ($post = Yii::$app->request->post()) {
            $url.='&code='.$code;
            $post['id'] = $id;    //单据ID
            $post['type'] = $type;  //审核流类型
            $post['staff'] = Yii::$app->user->identity->staff_id;//送审人ID
            $verifyUrl =$this->findApiUrl()."/system/verify-record/verify-record";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($verifyUrl));
            if ($data['status']) {
                if (!empty($url)) {
                    return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1, "url" => $url]);
                } else {
                    return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1]);
                }
            } else {
                return Json::encode(['msg' => $data['msg'] . ' 送审失败！', "flag" => 0]);
            }
        }
        $urls =$this->findApiUrl(). "/system/verify-record/reviewer?type=" . $type . '&staff_id=' . Yii::$app->user->identity->staff_id;
        $review = Json::decode($this->findCurl()->get($urls));
//        dump($review);
        return $this->renderAjax('reviewer', [
            'review' => $review,
            'code'=>$code
        ]);
    }
}
