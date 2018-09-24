<?php
/**
 * staff控制器
 * User: F3859386
 * Date: 2016/9/12
 * Time: 上午 11:38
 *
 */
namespace app\modules\hr\controllers;

use app\modules\hr\models\show\HrStaffShow;
use Yii;
use app\controllers\BaseActiveController;
use app\modules\hr\models\HrStaff;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\search\StaffSearch;
use app\modules\hr\models\HrStaffTitle;
use yii\web\NotFoundHttpException;

/**
 * StaffController implements the CRUD actions for Staff model.
 */
class StaffController extends BaseActiveController
{
    public $modelClass = 'app\modules\hr\models\HrStaff';

    public function actionIndex()
    {
        $searchModel = new StaffSearch();
        $queryParams=Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }


    /**
     * 查看功能
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return  HrStaffShow::findOne($id);
    }

    /**
     * 新增staff
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new HrStaff();
        //Ajax验证账号是否重复
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->getRequest()->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\bootstrap\ActiveForm::validate($model);
        }
            $model->load(Yii::$app->request->post());
            if(!$model->save()){
                return $this->error('新增失败');
            }
        return $this->success($model->staff_name,$model->staff_id);


    }


    /**
     * 更新staff
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = HrStaff::findOne($id);
        $model->load(Yii::$app->request->post());
        $model->save();
        if(!$model->save()) {
            return $this->error('更新失败');
        }
        return $this->success($model->staff_name,$model->staff_id);
    }

    /**
     * ajax删除数据
     * @param $id
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $model = HrStaff::findOne($id);
        $model->staff_status = HrStaff::STAFF_STATUS_DEL;
        if ($model->save()) {
            return $this->success($model->staff_name);
        } else {
            return $this->error();
        }
    }

    /**
     * 下拉菜单数据
     * @return mixed
     */
    public function actionDownList(){
        $downList['staffTitle']  = HrStaffTitle::getStaffTitleAll();
        $downList['organization']= HrOrganization::getOrgAll();
        return $downList;
    }

    /**
     * 导出
     * @return mixed
     */
    public function actionExport()
    {
        $searchModel = new StaffSearch();
        $queryParams=Yii::$app->request->queryParams;
        $dataProvider = $searchModel->export($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    public function actionModels($id)
    {
        return $this->findModel($id);
    }
    /**
     * 通过员工ID获取数据
     * @param $id
     * @return mixed
     */
    //返回部分信息
    public function actionReturnInfo($id)
    {
        $info = HrStaff::getStaffById($id);
        $staff['staff_id']=$info->staff_id;
        $staff['staff_name']=$info->staff_name;
        $staff['job_task']=$info->job_task;
        $staff['staff_mobile']=$info->staff_mobile;
        $staff['staff_tel']=$info->staff_tel;
        $staff['staff_code']=$info->staff_code;
        $staff['organization_code']=$info->organization_code;
        $organization=HrOrganization::byCodeOrg($staff['organization_code']);
        $staff['organization_name']=$organization['organization_name'];
        return $staff;
    }

    /**
     * 通过工号获取数据
     * @param $code
     * @return array|null|\yii\db\ActiveRecord
     */
    public function actionGetStaffInfo($code)
    {
        return  HrStaff::getStaffByIdCode($code);
    }

    /**
     * 验证工号是否存在
     * @return array|null|string|\yii\db\ActiveRecord
     */
    public function actionStaffValidation()
    {
        $post=Yii::$app->request->post();
        $val=false;
        if(!empty($post['id'])){
            $staff=HrStaff::getStaffById($post['id']);
            if($staff['staff_code']==$post['code']){
                $val='true';
            }
        }
        if($val === false){
            $model = HrStaff::getStaffByIdCode($post['code']);
            if(!empty($model)){
                $val='false';
            }else{
                $val='true';
            }
        }
        return $val;
    }
    /**
     * 插入Staff数据
     *
     * @param unknown $arr
     */
//    public  function actionSaveStaffData()
//    {
//        /* 关闭警告 */
////        error_reporting(E_ALL^E_NOTICE);
//        static $succ = 0;
//        static $err = 0;
//        $arr = Yii::$app->request->post();
//        //跳过第一列标题
//        unset($arr[1]);
//        foreach ($arr as $k => $v) {
//            $staffModel = new HrStaff();
//
//                // 根据工号查询数据，如存在则不插入数据
//                if (empty($v['A']) || $staffModel::getStaffByIdCode($v['A'])) {
//                    $err++;
//                    continue;
//                }
//                    $staffModel->staff_code = $v['A'];            //"工号"
//                    $staffModel->staff_name = isset($v["B"])?$v["B"]:null;            //"姓名"
//                    $staffModel->organization_code = isset($v["C"])?$v["C"]:null;     //"部门"
//                    $staffModel->job_task = isset($v["D"])?$v["D"]:null;     //"资位"
//                    $staffModel->position = HrStaffTitle::getTitleId($v['E'])['title_id'];     //"管理职"
//                    $staffModel->employment_date = isset($v["F"])?$v["F"]:null;     //"入厂日期"
//                    $staffModel->staff_mobile = isset($v["G"])?$v["G"]:null;     //"手机号码"
////                    $staffModel->former_name = isset($v["D"])?$v["D"]:null;           //"曾用名"
////                    $staffModel->english_name = isset($v["E"])?$v["E"]:null;          //"英文名"
////                    $staffModel->staff_sex = isset($v["F"])?$v["F"]:null;             //"性别"
////                    $staffModel->card_id = isset($v["G"])?$v["G"]:null;               //"身份证号码"
////                    $staffModel->birth_date = isset($v["H"])?$v["H"]:null;            //"出生年月日"
////                    $staffModel->staff_age = isset($v["I"])?$v["I"]:null;             //"年龄"
////                    $staffModel->annual_leave = isset($v["J"])?$v["J"]:null;          //"年休假"
////                    $staffModel->native_place = isset($v["K"])?$v["K"]:null;          //"籍贯"
//
////                    $staffModel->native_place_address = $v["L"];  //"地址"
////                    $staffModel->blood_type = isset($v["L"])?$v["L"]:null;            //"血型"
////                    $staffModel->staff_nation = isset($v["M"])?$v["M"]:null;          //"民族"
////                    $staffModel->staff_married = isset($v["N"])?$v["N"]:null;         //"婚否"
////                    $staffModel->health_condition = isset($v["O"])?$v["O"]:null;      //"健康状态"
////                    $staffModel->political_status = isset($v["P"])?$v["P"]:null;      //"政治面貌"
////                    $staffModel->party_time = isset($v["Q"])?$v["Q"]:null;            //"入党时间"
////                    $staffModel->residence_type = isset($v["R"])?$v["R"]:null;        //"户口类型"
////                    $staffModel->residence_address = isset($v["S"])?$v["S"]:null;     //"户口所在地"
////                    $staffModel->job_level = isset($v["T"])?$v["T"]:null;             //"职位级别"
////                    $staffModel->administrative_level = isset($v["U"])?$v["U"]:null;  //"行政级别"
////                    $staffModel->staff_type = isset($v["V"])?$v["V"]:null;            //"员工类型"
////                    $staffModel->job_task = isset($v["W"])?$v["W"]:null;              //"职务"
////                    $staffModel->job_title = isset($v["X"])?$v["X"]:null;             //"职称"
////                    $staffModel->employment_date = isset($v["Y"])?$v["Y"]:null;       //"入职时间"
////                    $staffModel->job_title_level = isset($v["Z"])?$v["Z"]:null;       //"职称级别"
////                    $staffModel->position = isset($v["AA"])?$v["AA"]:null;              //"岗位"
////                    $staffModel->attendance_type = isset($v["AB"])?$v["AB"]:null;       //"考勤类型"
////                    $staffModel->staff_seniority = isset($v["AC"])?$v["AC"]:null;       //"工龄"
////                    $staffModel->salary_date = $v["AD"];           //"起薪时间"
////                    $staffModel->staff_status = isset($v["AD"])?$v["AD"]:null;           //员工状态
////                    $staffModel->num_seniority = isset($v["AE"])?$v["AE"]:null;         //"总工龄"
////                    $staffModel->work_time = isset($v["AF"])?$v["AF"]:null;             //"参加工作时间"
////                    $staffModel->staff_tel = isset($v["AG"])?$v["AG"]:null;             //"联繫电话"
////                    $staffModel->staff_mobile = isset($v["AH"])?$v["AH"]:null;          //"手机号码"
////                    $staffModel->staff_email = isset($v["AI"])?$v["AI"]:null;           //"电子邮箱"
////                    $staffModel->card_address = isset($v["AJ"])?$v["AJ"]:null;          //"家庭地址"
////                    $staffModel->staff_qq = isset($v["AK"])?$v["AK"]:null;              //"QQ"
////                    $staffModel->other_contacts = isset($v["AL"])?$v["AL"]:null;        //"其他联繫方式"
////                    $staffModel->superior = $v["AO"];              //"直属上级"
////                    if (!empty($v["AP"])) {                        //"直属下级"
//                    //全角逗号转化为半角逗号
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
////                    $staffModel->opening_bank = isset($v["AM"])?$v["AM"]:null;          //"开户行"
////                    $staffModel->bank_account = isset($v["AN"])?$v["AN"]:null;          //"银行账号"
////                    $staffModel->staff_diploma = isset($v["AO"])?$v["AO"]:null;         //"学歷"
////                    $staffModel->staff_degree = isset($v["AP"])?$v["AP"]:null;          //"学位"
////                    $staffModel->staff_graduation_date = isset($v["AQ"])?$v["AQ"]:null;//"毕业日期"
////                    $staffModel->staff_school = isset($v["AR"])?$v["AR"]:null;          //"毕业学校"
////                    $staffModel->staff_major = isset($v["AS"])?$v["AS"]:null;           //"专业"
////                    $staffModel->computer_level = $v["AU"];        //"计算机水平"
////                    $staffModel->languages_0 = $v["AY"];           //"外语语种0"
////                    $staffModel->languages_1 = $v["AZ"];           //"外语语种1"
////                    $staffModel->languages_2 = $v["BA"];           //"外语语种2"
////                    $staffModel->language_level_0 = $v["BB"];      //"外语水平0"
////                    $staffModel->language_level_1 = $v["BC"];      //"外语水平1"
////                    $staffModel->language_level_2 = $v["BD"];      //"外语水平2"
////                    $staffModel->specialty = isset($v["AT"])?$v["AT"]:null;             //"特长"
////                    $staffModel->job_situation = $v["BF"];         //"职务情况"
////                    $staffModel->insurance_situation = $v["BG"];   //"社保情况"
////                    $staffModel->remark = isset($v["AU"])?$v["AU"]:null;                //"备註"
//                    // 插入数据
////                    $staffModel->staff_status = HrStaff::STAFF_STATUS_DEFAULT;
//                    $result = $staffModel->save();
//                    if ($result) {
//                        $succ++;
//                    } else {
//                        $err++;
//                    }
//        }
//        return $this->success('成功导入<span class="red">' . $succ . '<span>条数据,失败<span class="red">' . $err . '<span>条');
//
//    }

    public function actionImport($companyId,$createBy){
        $post = Yii::$app->request->post();
        static $succ = 0;
        static $err = 0;
        $log=[];
        foreach ($post as $k => $v) {
            $modelInfo=HrStaff::getStaffByIdCode($v['A']);
            if (empty($v['A']) && !empty($modelInfo)) {
                $err+=1;
                continue;
            }
            $trans=Yii::$app->db->beginTransaction();
            try {
                $model = new HrStaff();
                $model->staff_code = $v['A'];                   //"工号"
                $model->staff_name = trim($v["B"]);             //"姓名"
                $model->organization_code = trim($v["D"]);      //"部门"
                $model->job_level = trim($v["E"]);               //"资位"
                $model->position = HrStaffTitle::getTitleId(trim($v['F']))['title_id'];     //"管理职"
                $model->employment_date = trim($v["G"]);     //"入厂日期"
                $model->staff_mobile = trim($v["H"]);     //"手机号码"
//                $model->company_id = $companyId;
//                $model->create_by = $createBy;
                $model->save();
                if (!$model->save()) {
                    throw new \Exception(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE)."-".$model->staff_code);
                }
                $succ+=1;
                $trans->commit();
            } catch (\Exception $e) {
                $log[]=[
                    'file'=>basename(get_class()).":".$e->getLine(),
                    'message'=>$e->getMessage()
                ];
                $err+=1;
                $trans->rollBack();
            }
        }
        return ["succ"=>$succ,"error"=>$err,"log"=>$log];
    }


    /**
     * Finds the Staff model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Staff the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HrStaffShow::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
