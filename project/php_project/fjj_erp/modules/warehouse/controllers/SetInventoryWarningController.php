<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/6/9
 * Time: 上午 11:02
 */
namespace app\modules\warehouse\controllers;

use app\modules\common\tools\SendMail;
use Yii;
use app\controllers\BaseController;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\helpers\Html;

class SetInventoryWarningController extends BaseController{

    private $_url = "warehouse/set-inventory-warning/";  //对应api控制器URL
    public  function  actionIndex(){
        $code=Yii::$app->user->identity->staff->staff_code;
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        $productType = $this->downList();
        $productTypeIdToValue = [];
        foreach ($productType as $key => $val) {
            $productTypeIdToValue[$val['category_id']] = $val['category_sname'];
        }
        return $this->render('index', [
            'search'=>$queryParam['SetInventoryWarningSearch'],
            'StaffCode'=>$this->downList(),
            'params'=>$queryParam,
            'opper'=>$code
        ]);
    }

    //根据选择的预警人员加载对应的预警信息
    public  function actionProductinfo(){
        $url=$this->findApiUrl().$this->_url."productinfo";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        return $this->render('productinfo');
    }

    //根据商品分类搜索商品信息$partno
//    public function actionSearchcategory(){
//        $this->layout = '@app/views/layouts/ajax';
//        $url = $this->findApiUrl() . $this->_url . "searchcategory";
//        $queryParam = Yii::$app->request->queryParams;
//        //dumpE($queryParam['partno']);
//        if (!empty($queryParam)) {
//            $url .= "?" . http_build_query($queryParam);
//        }
//        //dump($url);
//        $dataProvider = $this->findCurl()->get($url);
//        if (Yii::$app->request->isAjax) {
//            $data=Json::decode($dataProvider);
//            return Json::encode($data);
//        }
//        return $this->render("searchcategory", [
//            'no'=>$queryParam['partno']
//        ]);
//    }

    //批量添加
    public function  actionNumadd(){
        $this->layout = '@app/views/layouts/ajax';
        $url = $this->findApiUrl() . $this->_url . "numadd";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            $data=Json::decode($dataProvider);
            return Json::encode($data);
        }
        return $this->render("numadd",[
            'search'=>$queryParam,
            'downList'=>$this->getDownList(),
            'StaffCode'=>$this->downList(),
        ]);
    }

    /*详情*/
    public function actionView($id)
    {
        $model = $this->getModel($id);
        $verify=$this->getVerify($id,46);//查看審核狀態
        $isApply = Yii::$app->request->get('is_apply');
        return $this->render('view', [
            'hrstaffinfo' => $model[0],
            'whinfo'=>$model[1],
            'isApply'=>$isApply,
            'id'=>$id,
            'verify'=>$verify
        ]);
    }

    public function getVerify($id,$type){
        $url = $this->findApiUrl() . "/system/verify-record/find-verify?id=" . $id."&type=".$type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }
    private function downList(){
        $url = $this->findApiUrl() . $this->_url . "down-list";
        return Json::decode($this->findCurl()->get($url));
    }

    /*新增*/
    public  function actionCreate(){
        $code=Yii::$app->user->identity->staff->staff_code;
        if (Yii::$app->request->getIsPost()) {

            $url = $this->findApiUrl() . $this->_url . "create";
            $postData = Yii::$app->request->post();
            $postData['InvWarner']['OPPER'] = Yii::$app->user->identity->staff->staff_code;//操作人
            $postData['InvWarner']['so_type'] = 10;
            $isApply = $postData["is_apply"];
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                if ($isApply=="1") {
                    return Json::encode(['msg' => "新增库存预警通知人员信息成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']['id'],'is_apply'=>1])]);
                }
                else{
                    return Json::encode(['msg' => "新增库存预警通知人员信息成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
                }
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $model=$this->actionGetStaffInfo($code);
        return $this->render("create",[
            'StaffCode'=>$this->downList(),
            'downList'=>$this->getDownList(),
            'opper'=>$model
        ]);
    }


    //修改
    public function actionUpdate($id){
        $code=Yii::$app->user->identity->staff->staff_code;//操作人

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $postData['InvWarner']['OPPER'] = Yii::$app->user->identity->staff->staff_code;//操作人
            $postData['InvWarner']['so_type'] = 10;
            $isApply = $postData["is_apply"];
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                //return Json::encode(['msg' => "修改预警人员信息成功", "flag" => 1, "url" => Url::to(['index'])]);
                if ($isApply=="1") {
                    return Json::encode(['msg' => "修改库存预警通知人员信息成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']['id'],'is_apply'=>1])]);
                }
                else{
                    return Json::encode(['msg' => "修改库存预警通知人员信息成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
                }
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }else{
            $model = $this->getModel($id);
            $opper=$this->actionGetStaffInfo($code);
            return $this->render("update",[
                'model'=>$model[0],
                'whinfo'=>$model[1],
                'Opper'=>$opper
            ]);
        }

    }

    /*
     * 根据预警ID获取仓库预警信息
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


    protected function getDownList()
    {
        $url = $this->findApiUrl() . "ptdt/firm-negotiation/form-down-list";
        $dataProvider = json::decode($this->findCurl()->get($url));
        $productType = $dataProvider['productTypes'];
        $productTypeIdToValue = [];
        foreach ($productType as $key => $val) {
            $productTypeIdToValue[$val['category_id']] = $val['category_sname'];
        }
        $dataProvider['productTypes'] = $productTypeIdToValue;
        return $dataProvider;
    }

    //根据仓库名称查找仓库编号
    public function actionGetWhnameInfo($code){
        $url = $this->findApiUrl().$this->_url. "get-whname-info?code=".$code;
        $info=$this->findCurl()->get($url);
        if(!empty($info)){
            return $info;
        }
        return "";
    }

    //验证是否有该员工
    public function actionGetCheckInfo($code){
        $url = $this->findApiUrl().$this->_url. "get-check-info?code=".$code;
        $info=$this->findCurl()->get($url);
        if(!empty($info)){
            return $info;
        }
        return "";
    }
    //验证当前人员是否有已送审的信息
    public function actionGetStaffCheck($code){
        $url = $this->findApiUrl().$this->_url. "get-staff-check?code=".$code;
        $info=$this->findCurl()->get($url);
        if(!empty($info)){
            return $info;
        }
        return "";
    }


    public function actionGetStaffInfo($code){
        $url=$this->findApiUrl() . "hr/staff/get-staff-info?code=".$code;
       // dumpE($url);
        $info=Json::decode($this->findCurl()->get($url));
       // dumpE($info["staff_name"]);
        if(!empty($info)){
            return $info;
        }
        return "";
    }
    //导出
    public function actionExport(){
        $url=$this->findApiUrl().'warehouse/set-inventory-warning/export?'.http_build_query(Yii::$app->request->queryParams);
        $data=Json::decode($this->findCurl()->get($url));
        //dumpE($data);
        $objPHPExcel = new \PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '工号')
            ->setCellValue('C1', '姓名')
            ->setCellValue('D1', '手机')
            ->setCellValue('E1', '邮箱')
            ->setCellValue('F1', '状态')
            ->setCellValue('G1', '操作人')
            ->setCellValue('H1', '最后操作日期')
            ->setCellValue('I1', '仓库')
            ->setCellValue('J1', '料号')
            ->setCellValue('K1', '商品名称')
            ->setCellValue('L1', '品牌')
            ->setCellValue('M1', '规格型号')
            ->setCellValue('N1', '库存上限')
            ->setCellValue('O1', '现有库存')
            ->setCellValue('P1', '库存下限');
        foreach ($data as $key => $val) {
            $num = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $num - 1)
                ->setCellValue('B' . $num, Html::decode($val['staff_code']))
                ->setCellValue('C' . $num, Html::decode($val['staff_name']))
                ->setCellValue('D' . $num, Html::decode($val['staff_mobile']))
                ->setCellValue('E' . $num, Html::decode($val['staff_email']))
                ->setCellValue('F' . $num, Html::decode($val['so_type']=="10"?"待提交":($val['so_type']=="20"?"审核中":($val['so_type']=="40"?"审核完成":"驳回"))))
                ->setCellValue('G' . $num, Html::decode($val['OPPER']))
                ->setCellValue('H' . $num, Html::decode($val['OPP_DATE']))
                ->setCellValue('I' . $num, Html::decode($val['wh_name']))
                ->setCellValue('J' . $num, Html::decode($val['part_no']))
                ->setCellValue('K' . $num, Html::decode($val['pdt_name']))
                ->setCellValue('L' . $num, Html::decode($val['BRAND_NAME_CN']))
                ->setCellValue('M' . $num, Html::decode($val['pdt_model']))
                ->setCellValue('N' . $num, Html::decode($val['up_nums']))
                ->setCellValue('O' . $num, Html::decode($val['invt_num']))
                ->setCellValue('P' . $num, Html::decode($val['down_nums']));
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
    //发邮件
    public function actionSendEmail($id){
        $this->layout = '@app/views/layouts/ajax';
        if(\Yii::$app->request->isPost){
            $params=\Yii::$app->request->post();
            //dumpE($params);
            $mail = new SendMail();
            $info=$mail->sendmail($params['selectaddress'],null,$params['emailtheme'],$params['content']);
            $result=$info->SendResult->status;
            if($result==1){
                return Json::encode(["msg"=>"邮件发送成功","flag"=>1, "url" => Url::to(['index'])]);
            }
            else{
                //SystemLog::addLog("邮件发送失败");
                return Json::encode(["msg"=>"邮件发送失败","flag"=>0]);
            }
        }else {
            $model = $this->getModel($id);
            $numinfo=$model[1];
            foreach ($numinfo as $key => $val){
                //var $nums=[];
                $numh=$val["invt_num"]-$val["up_nums"];//现有库存高于上限
                $numL=$val["invt_num"]-$val["down_nums"];//现有库存低于下限
                if($numh>0){
                    $numsH[$key]=$val;
                }
                if($numL<0) {
                    $numsL[$key] = $val;
                }
            }
          // dumpE($numsL);
            return $this->render("send-email", [
                'model'=>$model[0],
                'numh'=>$numsH,
                'numL'=>$numsL
            ]);
        }
    }



}