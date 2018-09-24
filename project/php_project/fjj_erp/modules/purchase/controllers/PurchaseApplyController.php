<?php

namespace app\modules\purchase\controllers;

use app\modules\system\models\SystemLog;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use app\classes\Menu;
use yii\web\NotFoundHttpException;


class PurchaseApplyController extends \app\controllers\BaseController
{
    private $_url = 'purchase/purchase-apply/'; //对应api
    public function actionIndex()
    {
        $url = $this->findApiUrl() . 'purchase/purchase-apply/index';
        $url2 = $this->findApiUrl() . 'purchase/purchase-apply/export';
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
            if (Menu::isAction('/purchase/purchase-apply/view')) {
                //给请购单号添加单击事件
                $dataProvider = Json::decode($dataProvider);
                if (!empty($dataProvider['rows'])) {
                    foreach ($dataProvider['rows'] as &$val) {
                            $val['req_no'] = "<a onclick='window.location.href=\"" . Url::to(['view', 'id' => $val['req_id']]) . "\";event.stopPropagation();'>" . $val['req_no'] . "</a>";
                    }
                }
                return Json::encode($dataProvider);
            }
            return $dataProvider;
        }
        $fields = $this->getField("/purchase/purchase-apply/index");
        $dataProvider = $this->findCurl()->get($url);
        $data = Json::decode($dataProvider);
        $data['table2']=$this->getField('/purchase/purchase-apply/index');
        $data['table3']=$this->getField('/purchase/purchase-apply/commodity');
        return $this->render('index', ['data' => $data, 'fields' => $fields]);
    }

    //商品信息
    public function actionCommodity($id)
    {
        $url = $this->findApiUrl() . 'purchase/purchase-apply/commodity';
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
        $fields = $this->getField("/purchase/purchase-apply/commodity");
        $dataProvider = $this->findCurl()->get($url);
        $data = Json::decode($dataProvider);

        return $this->render('index', ['data' => $data, 'fields' => $fields]);
    }
    // 新增(old作废)
//    public function actionCreates()
//    {
//        if (Yii::$app->request->getIsPost()) {
//            $post = Yii::$app->request->post();
//            $post['']['req_id'] = Yii::$app->user->identity->req_id;
//            $post['']['create_by'] = Yii::$app->user->identity->staff_id;
//            $url = $this->findApiUrl() . $this->_url . "create";
//            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
//            $data = Json::decode($curl->post($url));
//            if ($data['status'] == 1) {
//                SystemLog::addLog($data['data']['msg']);
//                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']['id']])]);
//            } else {
//                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
//            }
//        } else {
////            $downList = $this->getDownList();
//            return $this->render('create', [
////                'downList' => $downList,
//            ]);
//        }
//    }

    //修改(作废)
//    public function actionUpdate($id)
//    {
//        if($data=Yii::$app->request->post()){
//            $url=$this->findApiUrl().'purchase/purchase-apply/update';
//            $url.='?id='.$id;
//            return $this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data))->post($url);
//        }
//        return $this->render('_update');
//    }

    //取消
    public function actionCanRsn($id)
    {
        if($data=Yii::$app->request->post()){
            $url=$this->findApiUrl().'purchase/purchase-apply/can-rsn';
            $url.='?id='.$id;
            return $this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data))->post($url);
        }
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render('can-rsn');
    }

//    取消按钮
    public function actionCancels()
    {
        $post=Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "cancels" ;
        if(!empty($post)){
            $url .= "?" . http_build_query($post);
        }
        $data = Json::decode($this->findCurl()->get($url));
        if ($data['status']) {
            return Json::encode(["msg" => "取消成功", "flag" => 1, "url" => Url::to(['index'])]);
        } else {
            return Json::encode(["msg" => "取消失败", "flag" => 0]);
        }
    }

//     选择商品
    public function actionSelectProduct()
    {
        $params = Yii::$app->request->queryParams;
        $urls = $this->findApiUrl() . "system/display-list/get-url-field?url=/sale/sale-cust-order/create&user=" . Yii::$app->user->identity->user_id . "&type=";
        $columns = Json::decode($this->findCurl()->get($urls));
        $url = $this->findApiUrl() . $this->_url . 'select-product';
        $downList = $this->getDownList();
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        if (Yii::$app->request->isAjax) {
            return $this->findCurl()->get($url);
        }
//        dumpE($columns);
        return $this->renderAjax('select-product', ['params' => $params, 'columns' => $columns, 'downList' => $downList]);
    }

    //料号获取信息
    public function actionGetPdt($pdt_no)
    {
        $url = $this->findApiUrl() . $this->_url . "get-pdt?pdt_no=" . $pdt_no;
        return $this->findCurl()->get($url);
    }

    //详情
    public function actionView($id)
    {
        $model = $this->getModel($id);
//        dumpE($model);
        $verify=$this->getVerify($id,54);//查看審核狀態
        return $this->render('view', [
            'model' => $model[0],
            'pdtmodel'=>$model[1],
            'verify'=>$verify,
            'id'=>$id
        ]);
    }
    //查看审核状态
    public function getVerify($id,$type){
        $url = $this->findApiUrl() . "/system/verify-record/find-verify?id=" . $id."&type=".$type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }
    public function  getModel($id){
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }
    //打印
    public function actionPrview($id){
        $this->layout = '@app/views/layouts/ajax';
        $model = $this->getModel($id);
//        dumpE($model);
        $verify=$this->getVerify($id,54);//查看審核狀態
        return $this->render('prview', [
            'model' => $model[0],
            'pdtmodel'=>$model[1],
            'verify'=>$verify,
            'id'=>$id
        ]);
    }

    // 获取子表商品信息
    public function actionGetProduct($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-products?id=" . $id;
        return $this->findCurl()->get($url);
    }

    // 下拉列表
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "get-down-list";
        return json::decode($this->findCurl()->get($url));
    }

    // 生成采购单
    public function actionCreatePurchase() {
        $post = Yii::$app->request->queryParams;
        $post['staff_id'] = Yii::$app->user->identity->staff_id;
        $url = $this->findApiUrl() . $this->_url . "create-purchase";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        return $curl->post($url);
    }

    // 取消通知
    public function actionCancelNotify($id)
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "cancel-notify?id=" . $id;
            return $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post))->post($url);
        }
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render("cancel-notify",['id'=>$id]);
    }


    //新增请购需求
    public function actionCreate()
    {
        $url=$this->findApiUrl().$this->_url."create";
       // date_default_timezone_set("PRC");
        if($data=Yii::$app->request->post())
        {
            $pam=Yii::$app->user->identity->staff_id;
            $pam2=Yii::$app->user->identity->company_id;
            $data['BsReq']['leg_id']=$pam2;
            $data['BsReq']['app_id']=$pam;
            $sqll="SELECT t.organization_id FROM hr_organization t LEFT JOIN hr_staff a ON       t.organization_code=a.organization_code WHERE a.staff_id=".$pam." ";
            $ret=Yii::$app->db->createCommand($sqll)->queryScalar();
            $data['BsReq']['spp_dpt_id']=$ret;
           // $data['BsReq']['app_date']=date('Y-m-d H:i:s',time());
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('新增请购需求'.$result['data']['code']);
                return json_encode([
                    'msg'=>$result['msg'],
                    'flag'=>1,
                    'url'=>Url::to(['view','id'=>$result['data']['id']]),
                    'billId'=>$result['data']['id'],
                    'billTypeId'=>$result['data']['typeId']
                ]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);

        }
        $staff_id = Yii::$app->user->identity->staff_id;
        $downList=$this->getDownLists($staff_id);
//        dumpE($downList);
        return $this->render('create',[
           'downList'=>$downList,
           'u'=>$this->getUserInfo(),
           'fr'=>$this->getCompanyLi()
        ]);
    }

    //选择采购部门
    public function actionSelectDepart()
    {
        $url=$this->findApiUrl().$this->_url."select-depart";
        $queryParam=Yii::$app->request->queryParams;
        if(!empty($queryParam)){
            $url .= "?".http_build_query($queryParam);
        }
        $dataProvider=$this->findCurl()->get($url);
        if(Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        return $this->renderAjax('_depart',[
            'dataProvider'=>$dataProvider,
            'queryParam' => $queryParam
        ]);
    }
    //选择配送部门
    public function actionSelectPlace()
    {
        $url=$this->findApiUrl().$this->_url."select-place";
        $queryParam=Yii::$app->request->queryParams;
        if(!empty($queryParam)){
            $url .= "?".http_build_query($queryParam);
        }
        $dataProvider=$this->findCurl()->get($url);
        if(Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        return $this->renderAjax('_place',[
            'dataProvider'=>$dataProvider,
            'queryParam' => $queryParam
        ]);
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

    //获取单笔料号信息
    public function actionGetPurchaseProducts($id)
    {
        $url=$this->findApiUrl().$this->_url."get-purchase-products?id=".$id;
        $result=$this->findCurl()->get($url);
        return $result;
    }


    //新增请购需求单下拉列表
    function getDownLists($id)
    {
        $_url=$this->findApiUrl().$this->_url."dawn-list-by-create?id=".$id;
        return Json::decode($this->findCurl()->get($_url));
    }

    //获取用户登录信息
    function getUserInfo()
    {
        $url=$this->findApiUrl()."/hr/staff/return-info?id=".Yii::$app->user->identity->staff_id;
        $result=Json::decode($this->findCurl()->get($url));
        return $result;
    }

    //获取公司法人
    public function getCompanyLi()
    {
        $pam2=Yii::$app->user->identity->company_id;
        $sqll2="SELECT t.company_name FROM erp.bs_company t WHERE t.company_id=".$pam2." ";
        $ret2=Yii::$app->db->createCommand($sqll2)->queryScalar();
        return $ret2;
    }

    //根据工号查询联系人和电话
    public function actionGetStaffInfo($code)
    {
        $sqll2="SELECT t.staff_id,t.staff_code, t.staff_name,t.staff_mobile,t.staff_email FROM erp.hr_staff t WHERE t.staff_code = '".$code."' ";
        $ret2=Yii::$app->db->createCommand($sqll2)->queryOne();
        return Json::encode($ret2);
    }

    //请购修改
    public function actionEdit($id)
    {
        $url=$this->findApiUrl().$this->_url .'edit';
        $url.='?id='.$id;
        if($data=Yii::$app->request->post())
        {
            $pam=Yii::$app->user->identity->staff_id;
            $pam2=Yii::$app->user->identity->company_id;
            $data['BsReq']['leg_id']=$pam2;
            $data['BsReq']['app_id']=$pam;
            $sqll="SELECT t.organization_id FROM hr_organization t LEFT JOIN hr_staff a ON       t.organization_code=a.organization_code WHERE a.staff_id=".$pam." ";
            $ret=Yii::$app->db->createCommand($sqll)->queryScalar();
            $data['BsReq']['spp_dpt_id']=$ret;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('修改请购单'.$result['data']['code']);
                return json_encode([
                    'msg'=>$result['msg'],
                    'flag'=>1,
                    'url'=>Url::to(['view','id'=>$id]),
                    'billId'=>$id,
                    'billTypeId'=>54
                ]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $staff_id = Yii::$app->user->identity->staff_id;
        $downList=$this->getDownLists($staff_id);
        $model = $this->getEditModel($id);
//        dumpE($model);
        $verify=$this->getVerify($id,54);//查看審核狀態
        return $this->render('edit', [
            'model' => $model[0],
            'pdtmodel'=>$model[1],
            'verify'=>$verify,
            'downList'=>$downList
        ]);
    }

    //获取编辑model
    public function  getEditModel($id){
        $url = $this->findApiUrl() . $this->_url . "edit-models?id=" . $id;
//        dumpE($url);
        $model = Json::decode($this->findCurl()->get($url));
//        dumpE($url);
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }


    //导出
    public function actionExport()
    {
        $url = $this->findApiUrl() . $this->_url . "export";
//        dumpE($url);
//        $url.='?req_no='.$id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        $dataProvider = Json::decode($dataProvider);
//        dumpE($dataProvider);
        return $this->getExcelData($dataProvider);
    }


    private function getExcelData($dataProvider)
    {
        $data = $dataProvider['tr'];
       $i=1;
        foreach ($data as $key => $val) {
            $data[$key]['req_id']=$i;
            $i++;
            unset($data[$key]['yn_can']);
            unset($data[$key]['can_rsn']);
            unset($data[$key]['req_status']);
            unset($data[$key]['bsp_id']);
        }
        $headArr=['序号','请购单号','请购单状态','法人','请购部门','申请人','采购部门','币别','单据类型','请购形式','采购方式','采购区域','申请日期'];
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
            $objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(17);
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
        SystemLog::addLog('导出请购单结果');
        exit();
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



    //交易订单请购
    public function actionSpecificForm($id)
    {
        $url=$this->findApiUrl().$this->_url."create";
        if($data=Yii::$app->request->post())
        {
            $pam=Yii::$app->user->identity->staff_id;
            $pam2=Yii::$app->user->identity->company_id;
            $data['BsReq']['leg_id']=$pam2;
            $data['BsReq']['app_id']=$pam;
            $sqll="SELECT t.organization_id FROM hr_organization t LEFT JOIN hr_staff a ON       t.organization_code=a.organization_code WHERE a.staff_id=".$pam." ";
            $ret=Yii::$app->db->createCommand($sqll)->queryScalar();
            $data['BsReq']['spp_dpt_id']=$ret;
            $data['BsReq']['app_date']=date('Y-m-d H:i:s');
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('新增请购需求'.$result['data']['code']);
                return json_encode([
                    'msg'=>$result['msg'],
                    'flag'=>1,
                    'url'=>Url::to(['view','id'=>$result['data']['id']]),
                    'billId'=>$result['data']['id'],
                    'billTypeId'=>$result['data']['typeId']
                ]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);

        }
        $staff_id = Yii::$app->user->identity->staff_id;
        $downList=$this->getDownLists($staff_id);
//        dumpE($downList);
        $result=$this->specificpart($id);
        return $this->render('specific-form',[
            'downList'=>$downList,
            'u'=>$this->getUserInfo(),
            'fr'=>$this->getCompanyLi(),
            'result' => $result
        ]);
    }

    //获取订单商品信息
    public function specificpart($id)
    {
        $url=$this->findApiUrl().$this->_url."specific-form?id=".$id;
        $result=Json::decode($this->findCurl()->get($url));
        return $result;
    }

}
