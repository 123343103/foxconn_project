<?php
/**
 * User: F3859386
 * Date: 2017/10/20
 */
namespace app\modules\hr\controllers;

use app\classes\Menu;
use app\controllers\BaseController;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * 问卷调查控制器
 */
class QuestionSurveyController extends BaseController
{
    //权限过滤
    public function beforeAction($action)
    {
        $this->ignorelist=array_merge($this->ignorelist,[
            "/hr/question-survey/look",
            "/hr/question-survey/get-addr",
            "/hr/question-survey/qsn-count-result",
            "/hr/question-survey/clo-reason",
        ]);
        return parent::beforeAction($action);
    }
    public $_url = "hr/question-survey/";  //对应api控制器URL

    private function downList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * @return string
     * 调查问卷新增
     */
    public function actionAdd()
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $postData['BsQstInvst']['invst_end']=$postData['BsQstInvst']['invst_end']." 23:59:59";
            $url = $this->findApiUrl() . $this->_url . "add";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
//dumpE($data);
            if ($data['status']) {
//                SystemLog::addLog($data['data']['msg']);
                return Json::encode(['msg' => "新增成功!", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "新增失败，请检查您的问题或者选项是否填写完整正确！", "flag" => 0]);
            }
        } else {
            $downList = $this->getDownList();
            $urls = $this->findApiUrl() . "hr/staff/view?id=" . Yii::$app->user->identity->staff_id;
            $data = Json::decode($this->findCurl()->get($urls));
//            print_r($data);
//            print_r($downList);
//        dumpE($downList);
            return $this->render('add', [
                'downList' => $downList,
                'data' => $data
            ]);
        }
    }

    //问卷调查列表
    public function actionIndex()
    {
        $url = $this->findApiUrl() . 'hr/question-survey/index';
        if (Yii::$app->request->isAjax) {
            $url .= '?' . http_build_query(Yii::$app->request->queryParams);
//            if(empty(Yii::$app->user->identity->is_supper)){
//                $url .= '&staffId=' . Yii::$app->user->identity->staff_id;
//            }
            $url .= '&' . http_build_query(Yii::$app->request->queryParams);
            $dataProvider = $this->findCurl()->get($url);
            if (Menu::isAction('/hr/question-survey/view')) {
                //给问卷主题添加单击事件
                $dataProvider = Json::decode($dataProvider);
                if (!empty($dataProvider['rows'])) {
                    foreach ($dataProvider['rows'] as &$val) {
                        if ($val['yn_close'] == 0) {
                            $val['invst_subj'] = "<a onclick='window.location.href=\"" . Url::to(['view', 'id' => $val['invst_id']]) . "\";event.stopPropagation();'>" . $val['invst_subj'] . "</a>";
                        } else {
                            $val['invst_subj'] = "<a style='color: grey' onclick='window.location.href=\"" . Url::to(['view', 'id' => $val['invst_id']]) . "\";event.stopPropagation();'>" . $val['invst_subj'] . "</a>";
                        }
                    }
                }
                return Json::encode($dataProvider);
            }
            return $dataProvider;
//            return $this->findCurl()->get($url);
        }
        $fields = $this->getField("/hr/question_survey/index");
        $dataProvider = $this->findCurl()->get($url);
        $data = Json::decode($dataProvider);
//        dumpE($data);
        return $this->render('index', ['data' => $data, 'fields' => $fields]);
    }

    //问卷详情
    public function actionViews($id)
    {
        $model = $this->getModel($id);
        return $this->render("view", [
            'model' => $model
        ]);
    }

//答卷列表
    public function actionResponsesList($id)
    {
        $url = $this->findApiUrl() . 'hr/question-survey/responses-list';
        $url .= '?id=' . $id;
        if (Yii::$app->request->isAjax) {
            $url .= '?' . http_build_query(Yii::$app->request->queryParams);
            $url .= '&' . http_build_query(Yii::$app->request->queryParams);
            $dataProvider = $this->findCurl()->get($url);
            if (Menu::isAction('/hr/question-survey/view')) {
                //给工号添加单击事件
                $dataProvider = Json::decode($dataProvider);
                if (!empty($dataProvider['rows'])) {
                    foreach ($dataProvider['rows'] as &$val) {
                        $val['staff_code'] = "<a onclick='window.location.href=\"" . Url::to(['view', 'id' => $val['invst_id'], 'answ_id' => $val['answ_id']]) . "\";event.stopPropagation();'>" . $val['staff_code'] . "</a>";
                    }
                }
                return Json::encode($dataProvider);
            }
            return $dataProvider;
        }
        $dataProvider = $this->findCurl()->get($url);
        $data = Json::decode($dataProvider);
//    dumpE($data);
        return $this->render('responses-list', ['data' => $data]);
    }

    //待填写问卷信息
    public function actionSurveyShow($index)
    {
        //获取登录人信息和登录人部门信息
        $org = $this->getOrgInfo();
        $oid = $org["organization_id"];
        $staff_code = Yii::$app->user->identity->staff->staff_code;
        $url = $this->findApiUrl() . 'hr/question-survey/survey-show?oid=' . $oid . "&staff_code=" . $staff_code;
        if (Yii::$app->request->isAjax) {
            $url .= '&' . http_build_query(Yii::$app->request->queryParams);
            $url .= '&' . http_build_query(Yii::$app->request->queryParams);
            $dataProvider = $this->findCurl()->get($url);
            if (Menu::isAction('/hr/question-survey/view')) {
                //给问卷主题添加单击事件
                $dataProvider = Json::decode($dataProvider);
                return Json::encode($dataProvider);
            }
            return $dataProvider;
//            return $this->findCurl()->get($url);
        }
        $fields = $this->getField("/hr/question_survey/survey-show");
        $dataProvider = $this->findCurl()->get($url);
        $data = Json::decode($dataProvider);
//        dumpE($data);
        $this->layout = '@app/views/layouts/ajax.php';
        $userinfo = Yii::$app->user->identity->staff;
        return $this->render('survey-show', ['data' => $data, 'fields' => $fields, 'userinfo' => $userinfo,
            'org' => $org, 'index' => $index]);
    }

    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            return 'false';
        }
    }

    //删除问卷
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $data = Json::decode($this->findCurl()->delete($url));
        if ($data['status']) {
//            SystemLog::addLog('问卷资料删除:'.$data['msg']);
            return Json::encode(["msg" => "删除成功", "flag" => 1, "url" => Url::to(['index'])]);
        } else {
            return Json::encode(["msg" => "删除失败", "flag" => 0]);
        }
    }

    //批量删除问卷
    public function actionDeletes()
    {
        $post = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "deletes";
        if (!empty($post)) {
            $url .= "?" . http_build_query($post);
        }
        $data = Json::decode($this->findCurl()->get($url));
        if ($data['status']) {
            return Json::encode(["msg" => "删除成功", "flag" => 1, "url" => Url::to(['index'])]);
        } else {
            return Json::encode(["msg" => "删除失败", "flag" => 0]);
        }
    }

    //关闭按钮
    public function actionCloses()
    {
        $post = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "closes";
        if (!empty($post)) {
            $url .= "?" . http_build_query($post);
        }
        $data = Json::decode($this->findCurl()->get($url));
        if ($data['status']) {
            return Json::encode(["msg" => "关闭成功", "flag" => 1, "url" => Url::to(['index'])]);
        } else {
            return Json::encode(["msg" => "关闭失败", "flag" => 0]);
        }
    }
   //获取地址
    public function actionGetAddr($id)
    {
        $url = $this->findApiUrl() . 'hr/question-survey/get-addr';
        $url .= '?id=' . $id;
        if (Yii::$app->request->isAjax) {
            $url .= '?' . http_build_query(Yii::$app->request->queryParams);
            $url .= '&' . http_build_query(Yii::$app->request->queryParams);
            $dataProvider = $this->findCurl()->get($url);
            return $dataProvider;
        }
        $dataProvider = $this->findCurl()->get($url);
        $data = Json::decode($dataProvider);
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render('get-addr', ['data' => $data]);
    }
    public function actionUpdateInvstState($id, $invst_id)
    {
        $url = $this->findApiUrl() . $this->_url . "update-wh-state?id=" . $id . "&invst_id=" . $invst_id;
        $data = $this->findCurl()->delete($url);
        if (json_decode($data)->status == 1) {
            return Json::encode(['msg' => json_decode($data)->msg . "成功!", "flag" => 1, "url" => Url::to(['index'])]);
        } else {
            return Json::encode(['msg' => json_decode($data)->msg . "失败!", 'flag' => 0]);
        }
    }


    /**
     * @return mixed
     * 公共参数
     */
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        return Json::decode($this->findCurl()->get($url));
    }

    //问卷详情
    public function actionView($id, $answ_id = null)
    {
        $url = $this->findApiUrl() . 'hr/question-survey/views?invstid=' . $id . '&answ_id=' . $answ_id;
        $data = $this->findCurl()->get($url);
        $data = Json::decode($data);
        $data["invstcontent"][0]["survey_objects"] = "";
        $data["invstcontents"][0]["survey_objects"] = "";
        $i = 1;
        foreach ($data['dpt'] as $keys => $value) {
            if ($i != count($data['dpt'])) {
                $data["invstcontent"][0]["survey_objects"] .= $data['dpt'][$keys]['organization_name'] . "丶";
                $data["invstcontents"][0]["survey_objects"] .= $data['dpt'][$keys]['organization_name'] . "丶";
            } else {
                $data["invstcontent"][0]["survey_objects"] .= $data['dpt'][$keys]['organization_name'];
                $data["invstcontents"][0]["survey_objects"] .= $data['dpt'][$keys]['organization_name'];
            }
            $i++;
        }
        if (!empty($data["invstcontent"])) {
            foreach ($data["invstcontent"] as $key => $list)//更改类型文本和时间格式
            {
                $data["invstcontent"][$key]["invst_start"] = date("Y/m/d", strtotime($data["invstcontent"][$key]["invst_start"]));
                $data["invstcontent"][$key]["invst_end"] = date("Y/m/d", strtotime($data["invstcontent"][$key]["invst_end"]));
                $data["invstcontents"][$key]["invst_start"] = date("Y/m/d", strtotime($data["invstcontents"][$key]["invst_start"]));
                $data["invstcontents"][$key]["invst_end"] = date("Y/m/d", strtotime($data["invstcontents"][$key]["invst_end"]));
                if ($data["invstcontent"][$key]["cnt_type"] == '1') {
                    $data["invstcontent"][$key]["cnt_type"] = '单选';
                } else if ($data["invstcontent"][$key]["cnt_type"] == '2') {
                    $data["invstcontent"][$key]["cnt_type"] = '多选';
                }
                if ($data["invstcontent"][$key]["cnt_type"] == '3') {
                    $data["invstcontent"][$key]["cnt_type"] = '文本';
                }
                if ($data["invstcontent"][$key]["cnt_type"] == '4') {
                    $data["invstcontent"][$key]["cnt_type"] = '判断';
                }
            }
        } else {
            foreach ($data["invstcontents"] as $key => $list)//更改类型文本和时间格式
            {
                $data["invstcontents"][$key]["invst_start"] = date("Y/m/d", strtotime($data["invstcontents"][$key]["invst_start"]));
                $data["invstcontents"][$key]["invst_end"] = date("Y/m/d", strtotime($data["invstcontents"][$key]["invst_end"]));
            }
        }
        if (empty($staff_id) && !empty(Yii::$app->user->identity->staff_id)) {
            $urls = $this->findApiUrl() . "hr/staff/view?id=" . Yii::$app->user->identity->staff_id;
            $data["users"] = Json::decode($this->findCurl()->get($urls));
        } else if (!empty($staff_id)) {
            $urls = $this->findApiUrl() . "hr/staff/view?id=" . $staff_id;
            $data["users"] = Json::decode($this->findCurl()->get($urls));
        } else {
            $data["users"] = null;
        }
//        print_r($data);
        $this->layout = "@app/views/layouts/ajax.php";//去除模板
        return $this->render('view', [
            'data' => $data,
            'invstid' => $id,
            'answ_id' => $answ_id
        ]);
    }

    public function actionGetDataAnswer($invstid, $answ_id)
    {
        $url = $this->findApiUrl() . 'hr/question-survey/views?invstid=' . $invstid . '&answ_id=' . $answ_id;
        $data = $this->findCurl()->get($url);
        return $data;
    }

    //关闭问卷
    public function actionCloReason($id)
    {
        if ($data = Yii::$app->request->post()) {
            $url = $this->findApiUrl() . 'hr/question-survey/clo-reason';
            $url .= '?id=' . $id;
            $ret = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data))->post($url);
            return $ret;

//            $result=json_decode($curl->post($url),true);
//            if($result['status']==1){
//                return json_encode(['msg'=>$result['msg'],'flag'=>1]);
//            }
//            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render('clo-reason');
    }

    //问卷预览
    public function actionLooks()
    {
        $postData = Yii::$app->request->post();
        if (empty(Yii::$app->user->identity->staff_id)) {
            $postData["users"] = null;
        } else {
            $urls = $this->findApiUrl() . "hr/staff/view?id=" . Yii::$app->user->identity->staff_id;
            $postData["users"] = Json::decode($this->findCurl()->get($urls));
        }
        $postData = serialize($postData);
        return Json::encode(['msg' => "预览成功!", "flag" => 2, 'datas' => $postData, "url" => Url::to(['look'])]);
    }

    public function actionLook($datas)
    {
        $datas = unserialize($datas);
        $data = $datas;
        $ids = '';
        if (!empty($data['BsQstInvst']['dpt_id'])) {
            for ($i = 0; $i < count($data['BsQstInvst']['dpt_id']); $i++) {
                if ($i == count($data['BsQstInvst']['dpt_id']) - 1) {
                    $ids .= $data['BsQstInvst']['dpt_id'][$i];
                } else {
                    $ids .= $data['BsQstInvst']['dpt_id'][$i] . ",";
                }
            }
        } else {
            $ids = '';
        }
        $url = $this->findApiUrl() . 'hr/question-survey/get-list?organization_id=' . $data['BsQstInvst']['invst_dpt'] . '&organization_ids=' . $ids . '&bsp_id=' . $data['BsQstInvst']['invst_type'];
        $dataProvider = Json::decode($this->findCurl()->get($url));
        $dataProvider['datas'] = "";
        for ($i = 0; $i < count($dataProvider['organization_names']); $i++) {
            $dataProvider['datas'] .= $dataProvider['organization_names'][$i][0]['organization_name'] . ',';
        }
//        print_r($data);
        $this->layout = "@app/views/layouts/ajax.php";//去除模板
        return $this->render('look', [
            'data' => $data,
            'dataProvider' => $dataProvider
        ]);

    }

    //统计调查问卷结果
    public function actionQsnCountResult($id)
    {
        $a = "";
        $b = "";
        $url = $this->findApiUrl() . $this->_url . "qsn-count-result?id=" . $id;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        foreach ($dataProvider['InvstCon'] as $val) {
            switch ($val['cnt_type']) {
                case 1:
                    $val['cnt_type'] = "【单选】";
                    break;
                case 2:
                    $val['cnt_type'] = "【多选】";
                    break;
                case 3:
                    $val['cnt_type'] = "【文本】";
                    break;
                case 4:
                    $val['cnt_type'] = "【判断】";
                    break;
            }

            $b .= $val['cnt_id'] . "," . $val['cnt_tpc'] . "," . $val['cnt_type'] . ",";
        }
        $b = explode(',', $b);
        $b = array_filter($b);
//        dumpE($dataProvider);
        return $this->render('qsn-count-result', [
            'model' => $dataProvider,
            'b' => $b
        ]);
    }

    //查看【文本】答案
    public function actionLoadAnsw($invstid, $cntid, $c,$d)
    {
        $queryParam = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "load-answ";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
//        dumpE($dataProvider);
        return $this->renderAjax("load-answ", [
            'model' => $dataProvider,
            'c' => $c,
            'd' => $d
        ]);
    }

    public function getOrgInfo()
    {
        if (empty(Yii::$app->user->identity->staff->organization_code)) {
            return null;
        }
        $url = $this->findApiUrl() . "/hr/organization/get-org-info?organization_code=" . Yii::$app->user->identity->staff->organization_code;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    //导出
    public function actionExport()
    {
        $url = $this->findApiUrl() . $this->_url . "export";
        $queryParam = Yii::$app->request->queryParams;
//        $queryParam['id'] = $id;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        $dataProvider = Json::decode($dataProvider);
        return $this->getExcelData($dataProvider);
    }

    private function getExcelData($dataProvider)
    {
        $data = $dataProvider['tr'];
        $title =  $dataProvider['title'];
//        foreach ($data as $key => $val) {
//            unset($data[$key]['answ_id']);
//            unset($data[$key]['cnt_tpc']);
//        }
//        $headArr = [
//            '工号',
//            '姓名',
//            '调研日期',
//        ];
        $headArr = $dataProvider['th'];
        $this->getExcels($headArr, $data,$title);
    }

    private function getExcels($headArr, $data, $title)
    {

        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xls";
        // 创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        // 设置表头
        $key = "A";
//        foreach ($title as $v) {
//            $colum = $key;
//            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
//            if ($key == "Z") {
//                $key = "AA";
//            } elseif ($key == "AZ") {
//                $key = "BA";
//            } else {
//                $key++;
//            }
//        }
        foreach ($headArr as $v) {
            $colum =$key;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '3', $v);
            if ($key == "Z") {
                $key = "AA";
            } elseif ($key == "AZ") {
                $key = "BA";
            } else {
                $key++;
            }
        }
        $column = 4;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach ($title as $k => $r) {
            $span="A". 1;
                $objActSheet->setCellValue($span, $r);
        }
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
        SystemLog::addLog('导出问卷结果');
        exit();
    }

}
