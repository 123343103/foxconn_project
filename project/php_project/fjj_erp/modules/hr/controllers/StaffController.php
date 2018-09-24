<?php
/**
 *staff控制器
 * User: F3859386
 * Date: 2016/9/12
 * Time: 上午 11:38
 */
namespace app\modules\hr\controllers;

use app\modules\system\models\SystemLog;
use Yii;
use app\models\UploadForm;
use app\controllers\BaseController;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;

/**
 * StaffController implements the CRUD actions for Staff model.
 */
class StaffController extends BaseController
{
    public $_url = "hr/staff/";  //对应api控制器URL

    public function actionIndex(){

    $url = $this->findApiUrl() . $this->_url . "index";
    $queryParam = Yii::$app->request->queryParams;
    if (!empty($queryParam)) {
        $url .= "?" . http_build_query($queryParam);
    }
    $dataProvider = $this->findCurl()->get($url);
    if (Yii::$app->request->isAjax) {
        return $dataProvider;
    }
    return $this->render('index', [
        'search'=>$queryParam['StaffSearch'],
        'downList'=>$this->downList(),
        'model'=>new UploadForm()
    ]);
}

    /**
     * 导出
     * @return mixed
     */
    public function actionExport()
    {
        $url = $this->findApiUrl() . $this->_url . "export";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);

        $dataProvider=Json::decode($dataProvider);
        //导出
//        $export = Yii::$app->request->get('export');
//        if (isset($export)) {
          return  $this->getExcelData($dataProvider['rows']);
//        }
    }


    /**
     * 查看
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {

        $url = $this->findApiUrl() . $this->_url . "view?id=".$id;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $this->render('view', [
            'model' => $dataProvider,
        ]);
    }

    /**
     * 选择部门
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionSelectDepart()
    {
        $this->layout = "@app/views/layouts/ajax.php";
        $url = $this->findApiUrl() . "/hr/organization/index?isIcon=false";
        $dataProvider = Json::decode($this->findCurl()->get($url));
//        dumpE($dataProvider);die();
        return $this->render('select-depart', [
            'tree'  =>$dataProvider
        ]);
    }
    /**
     * 查看个人资料
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionMyInfo()
    {
        $url = $this->findApiUrl() . $this->_url . "view?id=".Yii::$app->user->identity->staff_id;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $this->render('my-info', [
            'model' => $dataProvider,
        ]);
    }

    /**
     * 新增staff
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog('人事资料新增:'.$data['msg']);
                return Json::encode(['msg' => "新增员工完成", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        }
        $downList=$this->downList();
        return $this->render('create', [
            'downList' => $downList,
        ]);
    }


    /**
     * 更新staff
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['PdFirmReport']['update_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog('人事资料修改:'.$data['msg']);
                return Json::encode(['msg' => "编辑员工完成", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }
            $downList=$this->downList();
            $model = $this->getModel($id);
            return $this->render('update', [
                'model' => $model,
                'downList'=>$downList
            ]);

    }

    /**
     * 删除数据
     */
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $data = Json::decode($this->findCurl()->delete($url));
        if ($data['status']) {
            SystemLog::addLog('人事资料删除:'.$data['msg']);
            return Json::encode(["msg" => "删除成功", "flag" => 1, "url" => Url::to(['index'])]);
        } else {
            return Json::encode(["msg" => "删除失败", "flag" => 0]);
        }
    }


//    //Ajax验证账号是否重複
//    public function actionAjaxValidation(){
//        $model = new User();
//        if (Yii::$app->request->isAjax && $model->load(Yii::$app->getRequest()->post())) {
//            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//            return \yii\bootstrap\ActiveForm::validate($model);
//        }
//    }

    private function downList(){
        $url = $this->findApiUrl() . $this->_url . "down-list";
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * 导出staff
     * @param $arr
     */
    private function getExcelData($data)
    {
//        $params = ['staff_id', 'staff_avatar', 'remark', 'insurance_situation', 'job_situation', 'language_level_0', 'language_level_1', 'language_level_2',
//            'languages_0', 'languages_1', 'languages_2', 'computer_level', 'superior', 'subordinate', 'salary_date'
//        ];
//        $list = $this->field($arr, $params);
        //排除不要的数据
        foreach ($data as $key=>$val){
            unset($data[$key]['staff_id']);
        }
        $headArr = [
            '工号',
            '姓名',
            '单位',
            '资位',
            '管理职',
            '入厂日期',
            '手机号码',
        ];
        $this->getExcels($headArr, $data);
    }

    /**
     *
     * 获取excel数据
     * @param $headArr
     * @param $data
     */
    private function getExcels($headArr, $data)
    {


        // 导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
//        import("Org.Util.PHPExcel");
//        import("Org.Util.PHPExcel.Writer.Excel5");
//        import("Org.Util.PHPExcel.IOFactory.php");
//        $staffModel=new StaffSearch();
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xlsx";
        // 创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();

        // 设置表头
        $key = "A";

        foreach ($headArr as $v) {
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

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output'); // 文件通过浏览器下载
        SystemLog::addLog('导出人事资料');
        exit();
    }


//    /**
//     * excel插入数据
//     */
//    public function actionInsertExcelStaff()
//    {
//        $model = new UploadForm();
//        $model->file = UploadedFile::getInstance($model, 'file');
//            $fileName =time().mt_rand(0, 99);
//            $fileExt = $model->file->extension;
//            $resultSave = $model->file->saveAs('uploads/staffFile/' . $fileName . '.' . $fileExt);
//            $data=$this->getStaffData($fileName, $fileExt);
//            if(!$resultSave || empty($data)){
//                 return Json::encode(["msg" => '文件数据错误,导入失败', "flag" => 0, "url" => Url::to(['index'])]);
//            }
//            $url = $this->findApiUrl().$this->_url."save-staff-data";
//            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
//            $data = Json::decode($curl->post($url));
//            if ($data['status']) {
//                SystemLog::addLog('导入员工数据');
//                return Json::encode(["msg" => $data['msg'], "flag" => 1, "url" => Url::to(['index'])]);
//            } else {
//                return Json::encode(["msg" => "导入失败", "flag" => 0]);
//            }
//    }

    /**
     * 将excel表格转变成数组
     *
     * @param  $filename
     * @param string $exts
     */
    private function getStaffData($filename, $exts = "xls")
    {
        // 导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
//        import("Org.Util.PHPExcel");
        // 创建PHPExcel对象，注意，不能少了\
        $PHPExcel = new \PHPExcel();
        // 如果excel文件后缀名为.xls，导入这个类
        if ($exts == 'xls') {
//            import("Org.Util.PHPExcel.Reader.Excel5");
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } elseif ($exts == 'xlsx') {
//                import("Org.Util.PHPExcel.Reader.Excel2007");
                $PHPReader = new \PHPExcel_Reader_Excel2007();
            }
        // 载入文件
        $transaction = Yii::$app->db->beginTransaction();
        try {
        $PHPExcel = $PHPReader->load('uploads/staffFile/' . $filename . '.' . $exts);
        } catch (\Exception $e) {
            $transaction->rollBack();
        }
        // 获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet = $PHPExcel->getSheet(0);

        // 获取总列数
        $allColumn = $currentSheet->getHighestColumn();

        // 获取总行数
        $allRow = $currentSheet->getHighestRow();

        // 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
            // 从哪列开始，A表示第一列
            if (strlen($allColumn) == 2) {
                $Column = "Z";
            } else {
                $Column = $allColumn;
            }
            for ($currentColumn = "A"; $currentColumn <= $Column; $currentColumn++) {
                if ($currentColumn == "AA") {
                    $Column = $allColumn;
                }
                $address = $currentColumn . $currentRow;
                // 读取到的数据，保存到数组$arr中
                $cell = $currentSheet->getCell($address)->getValue();
                // $cell =

                if ($cell instanceof PHPExcel_RichText) {
                    $cell = $cell->__toString();
                }
                $data[$currentRow][$currentColumn] = $cell;
            }
        }
        return $data;
    }

    /**
     * 插入Staff数据
     *
     * @param unknown $arr
     */
//    private function saveStaffData($arr)
//    {
//        static $succ = 0;
//        static $err = 0;
//        foreach ($arr as $k => $v) {
//            $staffModel = new HrStaff();
//            $v['A'] = "$v[A]";
//            //跳过第一列标题
//            if ($k >= 2) {
//                // 根据工号查询数据，如存在则不插入数据
//                if ("$v[A]" != '' && !$staffModel::find()->where(['staff_code' => "$v[A]"])->one()) {
//                    $staffModel->staff_code = $v['A'];            //"工号"
//                    $staffModel->organization_code = $v["B"];     //"部门"
//                    $staffModel->staff_name = $v["C"];            //"姓名"
//                    $staffModel->former_name = $v["D"];           //"曾用名"
//                    $staffModel->english_name = $v["E"];          //"英文名"
//                    $staffModel->staff_sex = $v["F"];             //"性别"
//                    $staffModel->card_id = $v["G"];               //"身份证号码"
//                    $staffModel->birth_date = $v["H"];            //"出生年月日"
//                    $staffModel->staff_age = $v["I"];             //"年龄"
//                    $staffModel->annual_leave = $v["J"];          //"年休假"
//                    $staffModel->native_place = $v["K"];          //"籍贯"
////                    $staffModel->native_place_address = $v["L"];  //"地址"
//                    $staffModel->blood_type = $v["L"];            //"血型"
//                    $staffModel->staff_nation = $v["M"];          //"民族"
//                    $staffModel->staff_married = $v["N"];         //"婚否"
//                    $staffModel->health_condition = $v["O"];      //"健康状态"
//                    $staffModel->political_status = $v["P"];      //"政治面貌"
//                    $staffModel->party_time = $v["Q"];            //"入党时间"
//                    $staffModel->residence_type = $v["R"];        //"户口类型"
//                    $staffModel->residence_address = $v["S"];     //"户口所在地"
//                    $staffModel->job_level = $v["T"];             //"职位级别"
//                    $staffModel->administrative_level = $v["U"];  //"行政级别"
//                    $staffModel->staff_type = $v["V"];            //"员工类型"
//                    $staffModel->job_task = $v["W"];              //"职务"
//                    $staffModel->job_title = $v["X"];             //"职称"
//                    $staffModel->employment_date = $v["Y"];       //"入职时间"
//                    $staffModel->job_title_level = $v["Z"];       //"职称级别"
//                    $staffModel->position = $v["AA"];              //"岗位"
//                    $staffModel->attendance_type = $v["AB"];       //"考勤类型"
//                    $staffModel->staff_seniority = $v["AC"];       //"工龄"
////                    $staffModel->salary_date = $v["AD"];           //"起薪时间"
//                    $staffModel->staff_status = $v["AD"];           //员工状态
//                    $staffModel->num_seniority = $v["AE"];         //"总工龄"
//                    $staffModel->work_time = $v["AF"];             //"参加工作时间"
//                    $staffModel->staff_tel = $v["AG"];             //"联繫电话"
//                    $staffModel->staff_mobile = $v["AH"];          //"手机号码"
//                    $staffModel->staff_email = $v["AI"];           //"电子邮箱"
//                    $staffModel->card_address = $v["AJ"];          //"家庭地址"
//                    $staffModel->staff_qq = $v["AK"];              //"QQ"
//                    $staffModel->other_contacts = $v["AL"];        //"其他联繫方式"
////                    $staffModel->superior = $v["AO"];              //"直属上级"
////                    if (!empty($v["AP"])) {                        //"直属下级"
////                        //全角逗号转化为半角逗号
////                        $staffStr = preg_replace('[^%&\',;=?$\x22]+', ",", $v["AP"]);
////                        $staffStr = explode(",", $vAP);
////                        foreach ($vArr as $val) {
////                            $staffInfo = $staffModel::find()->where(['staff_code' => $val])->select('staff_id')->one();
////                            if (!empty($staffInfo)) {
////                                $staffArr[] = $staffInfo->staff_id;
////                            }else{
////                                $staffStr.=$val.',';
////                            }
////                        }
////                        dumpE($staffStr);
////                    }
//                    //序列化，去除重复值，去除空值 :array_filter($staffArr)
////                    $staffModel->subordinate = $staffStr;
//                    $staffModel->opening_bank = $v["AM"];          //"开户行"
//                    $staffModel->bank_account = $v["AN"];          //"银行账号"
//                    $staffModel->staff_diploma = $v["AO"];         //"学历"
//                    $staffModel->staff_degree = $v["AP"];          //"学位"
//                    $staffModel->staff_graduation_date = $v["AQ"];//"毕业日期"
//                    $staffModel->staff_school = $v["AR"];          //"毕业学校"
//                    $staffModel->staff_major = $v["AS"];           //"专业"
////                    $staffModel->computer_level = $v["AU"];        //"计算机水平"
////                    $staffModel->languages_0 = $v["AY"];           //"外语语种0"
////                    $staffModel->languages_1 = $v["AZ"];           //"外语语种1"
////                    $staffModel->languages_2 = $v["BA"];           //"外语语种2"
////                    $staffModel->language_level_0 = $v["BB"];      //"外语水平0"
////                    $staffModel->language_level_1 = $v["BC"];      //"外语水平1"
////                    $staffModel->language_level_2 = $v["BD"];      //"外语水平2"
//                    $staffModel->specialty = $v["AT"];             //"特长"
////                    $staffModel->job_situation = $v["BF"];         //"职务情况"
////                    $staffModel->insurance_situation = $v["BG"];   //"社保情况"
//                    $staffModel->remark = $v["AU"];                //"备注"
//                    // 插入数据
////                    dump(ActiveForm::validate($staffModel));
//                    $result = $staffModel->save();
//                    if ($result) {
//                        $succ++;
//                    } else {
//                        $err++;
//                    }
//                } else {
//                    $err++;
//                }
//            }
//        }
//        return ('成功导入<span class="red">' . $succ . '<span>条数据,失败<span class="red">' . $err . '<span>条');
//
//    }

//    /**
//     * 删除数组元素
//     * @param integer $id
//     * @return mixed
//     */
//
//    protected function field($arr, $params)
//    {
//        foreach ($params as $param) {
//            foreach ($arr as $key => $val) {
//                unset($arr[$key]["$param"]);
//            }
//        }
//        return $arr;
//    }

    /*staffInfo*/
    public function actionGetStaffInfo($code){
        $url = $this->findApiUrl().'hr/staff/get-staff-info?code='.$code;
        $info=$this->findCurl()->get($url);
        if(!empty($info)){
            return $info;
        }
        return "";
    }

    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=".$id;
        $model = Json::decode($this->findCurl()->get($url));
        if (!$model) {
            throw new NotFoundHttpException("页面未找到");
        }
        return $model;
    }
    //返回部分信息
    public function actionGetInfo($id)
    {
        $url = $this->findApiUrl() . $this->_url . "return-info?id=".$id;
        return $this->findCurl()->get($url);
    }

    //验证工号唯一性
    public function actionStaffValidation()
    {
        $data=Yii::$app->request->get();
        $url = $this->findApiUrl() . $this->_url . "staff-validation";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
        $result = Json::decode($curl->post($url));
        return $result;
    }

    //验证工号唯一性
    public function actionValidationStaff()
    {
        $data=Yii::$app->request->get();
        $url = $this->findApiUrl() . $this->_url . "staff-validation";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
        $result = Json::decode($curl->post($url));
        return $result;
    }

    //导入模板
    public function actionDownTemplate()
    {
        $headArr = ['工号','姓名','部门名称','部门代码','资位','管理职','入厂日期','手机号码'];
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "人事信息导入模板.xlsx";
        $objPHPExcel = new \PHPExcel();
        $key = "A";
        foreach ($headArr as $v) {
            $colum = $key;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(15);
            // $objPHPExcel->getActiveSheet($v)->getStyle($key)->getAlignment($key)->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            if ($key == "Z") {
                $key = "AA";
            } elseif ($key == "AZ") {
                $key = "BA";
            } else {
                $key++;
            }
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . 2, 'F1234567')
            ->setCellValue('B' . 2, '张三')
            ->setCellValue('C' . 2, '富金机电商事业处')
            ->setCellValue('D' . 2, 'THA00007')
            ->setCellValue('E' . 2, '师一')
            ->setCellValue('F' . 2, '無')
            ->setCellValue('G' . 2, '2017-01-01')
            ->setCellValue('H' . 2, '135XXXXXXXX');
        $fileName = "hrstaff.xlsx";
        $fileName = iconv("utf-8", "gb2312", $fileName);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean(); // 清除缓冲区,避免乱码

        //以下导出-excel2003版本
//        header('Content-Type: application/vnd.ms-excel');
//        header("Content-Disposition: attachment;filename=" . $fileName);
//        header('Cache-Control: max-age=0');
//        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//        $objWriter->save('php://output'); // 文件通过浏览器下载

        //以下导出-excel2007版本
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_clean();    //用于清除缓冲区的内容,兼容
        $objWriter->save('php://output');
        exit();
    }
}
