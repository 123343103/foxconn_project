<?php

namespace app\controllers;

use app\classes\Menu;
use app\modules\common\tools\SendMail;
use app\modules\system\models\SystemLog;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\db\QueryBuilder;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use app\classes\Curl;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\modules\common\tools\ExcelToArr;

/**
 * 控制器基類,需要权限管控的操作都需要继承这个
 * F3858995
 * 2016/9/20
 */
class BaseController extends Controller
{
    private $curl = null;

    private $apiUrl = null;

    public $ignorelist = [
//        "/index/index",
        "/index/forbidden",
        "/system/inform/index",
        "/hr/staff/my-info",
        "/index/edit-pwd",
        "/login/login-out",
        "/crm/crm-plan-manage/plan-count",
        "/base/district",
        "/hr/question-survey/survey-show",
    ];
    public $list = [];
    public $p_url = null;

    public function findApiUrl()
    {
        if (is_null($this->apiUrl)) {
            $this->apiUrl = Yii::$app->params['apiUrl'];
        }
        return $this->apiUrl;
    }

    public function beforeAction($action)
    {
        $permission = "/" . $this->getRoute();
        if (in_array($permission, $this->ignorelist)) {
            return true;
        }
        if (Yii::$app->user->isGuest) {
            echo "<script>window.top.location.href='" . Url::to(['/login/login']) . "'</script>";
            exit();
//            return $this->redirect(['/login/login']);
        }
        if (!Yii::$app->request->isAjax) {
            $permission = "/" . $this->getRoute();
            if (parent::beforeAction($action)) {
                if (in_array($permission, $this->ignorelist)) {
                    return true;
                }
                if (Yii::$app->user->identity->is_supper != 1) {
                    if (!Yii::$app->authManager->checkAccess(Yii::$app->user->identity->user_id, $permission) && !Menu::isAllow($this->p_url, $this->list[$permission])) {
                        if (!empty($this->list[$permission] || !Menu::isAllow($permission))) {
                            return $this->redirect(["/index/forbidden"]);
                        }
                    };
                }
//            Menu::generateMenu();
                return true;
            }
        } else {
            return true;
        }
    }


    /**
     * 生成CURL类
     * @return Curl|null
     */
    public function findCurl()
    {
        if (is_null($this->curl)) {
            $this->curl = new Curl;
        }
        return $this->curl;
    }


    /**
     * 错误处理类
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'upload' => [
                'class' => \app\widgets\upload\UploadAction::className()
            ],
            'district' => [
                'class' => \app\widgets\district\DistrictAction::className()
            ]
        ];

    }

    /**
     * 获取数据改为 datagrid 格式
     * @param $url
     * @param $index  导出时返回数据
     * @param $$type  有值时输出全部列
     * @return string
     */
    public function getField($url = null, $index = null, $type = null)
    {
        if ($url == null) {
            $url = '/' . $this->getRoute();
        }
        $url = $this->findApiUrl() . "system/display-list/get-url-field?url=" . $url . "&user=" . Yii::$app->user->identity->user_id . "&type=" . $type;
        $childModel = Json::decode($this->findCurl()->get($url));
        if (empty($childModel)) {
            //返回空,防止前端报错
            return '';
        }
        if (!empty($index)) {
            return $childModel;
        }
        $columns = '';
        foreach ($childModel as $val) {
            $columns .= "{field:'" . $val['field_field'] . "',title:'" . $val['field_title'] . "',width:" . $val['field_width'] . "},";
        }
        return $columns;
    }


    /**
     * 根据动态列导出客户
     * @param $data
     */
    public function exportFiled($data)
    {
        $filed = '';
        $filedVal = '';
        $fieldIndex = 1;
        $filedTitle = 'A';
        $fieldArr = [];
        $objPHPExcel = new \PHPExcel();
        $columns = $this->getField(null, true);
        $number = [['field_field' => true, 'field_title' => '序号']];
        $columns = array_merge($number, $columns);
        $excelIndex = '$objPHPExcel->setActiveSheetIndex(0)';
        //获取列
        foreach ($columns as $key => $value) {
            if ($fieldIndex > 24) {
                $fieldIndex = 1;
            }
            //宽度
            $objPHPExcel->getActiveSheet()->getColumnDimension($filedTitle)->setWidth(30);
            //标题垂直居中
            $objPHPExcel->getActiveSheet()->getStyle($filedTitle)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $filed .= '->setCellValue(\'' . $filedTitle . $fieldIndex . '\',\'' . $value['field_title'] . '\')';
            $filedTitle++;
            $fieldArr[$key] = $value['field_field'];
        }
        $filedTitle = 'A';
        eval($excelIndex . $filed . ';');
        foreach ($data as $key => $val) {
            $num = $key + 2;
            foreach ($fieldArr as $v) {
                $field_val = htmlspecialchars_decode(htmlspecialchars_decode(htmlspecialchars_decode(htmlspecialchars_decode($val[$v]))));
                if ($v === true) {
                    $field_val = $key + 1;
                }
                $filedVal .= '->setCellValue(\'' . $filedTitle . $num . '\',\' ' . $field_val . '\')';
                $filedTitle++;
            }
            $filedTitle = 'A';
            eval($excelIndex . $filedVal . ';');
            Html::decode($filedVal);
            $filedVal = '';
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
     * 2017-4-18
     * F1678688 李方波
     * 统一字段验证类
     * @param $id   字段惟一标识
     * @param $attr 验证属性
     * @param $val  验证属性值
     */
    public function actionValidate($id, $attr, $val, $scenario)
    {
        $val = urlencode($val);
        $url = $this->findApiUrl() . $this->module->id . "/" . $this->id . "/" . "validate";
        $url = $url . "?id={$id}&attr={$attr}&val={$val}&scenario={$scenario}";
        return $this->findCurl()->get($url);
    }

    public function actionImport()
    {
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $succ = 0;
            $err = 0;
            $err_log = [];
            $model->file = UploadedFile::getInstance($model, 'file');
            if (!$model->validate()) {
                echo "<script>parent.$('#import-ok').html('文件无效或损坏').fadeIn()</script>";
                ob_flush();
                flush();
                exit(0);
            }
            echo "<script>parent.$('#progressbar_div').show()</script>";
            $fileName = date('Y-m-d', time()) . '_' . rand(1, 9999);
            $fileExt = $model->file->extension;
            $resultSave = $model->file->saveAs('uploads/' . $fileName . '.' . $fileExt);

            if (!empty($resultSave)) {
                $url = $this->findApiUrl() . $this->_url . "import?companyId=" . \Yii::$app->user->identity->company_id . "&createBy=" . \Yii::$app->user->identity->staff_id;
                $e2a = new ExcelToArr();
                $arr = $e2a->excel2arry($fileName, $fileExt);
                $arr1 = array_slice($arr, 1);   // 处理第一行标题
                $count = count($arr1);
                $arr2 = array_chunk($arr1, 10); // 防止一次post传输的数据过大
                foreach ($arr2 as $key => $v) {
                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($v));
                    $data = Json::decode($curl->post($url));
                    $succ += $data["succ"];
                    $err += $data["error"];
                    $err_log = array_merge($err_log, $data["log"]);
                    echo $this->renderAjax("@app/views/base/import-progress", [
                        "total" => $count,
                        "success" => $succ,
                        "error" => $err,
                        "percent" => floor(($succ + $err) / $count * 100)
                    ]);
                    ob_flush();
                    flush();
                }
                $cache = \Yii::$app->cache;
                $key = "import-log-" . \Yii::$app->user->identity->staff_id;
                $cache->set($key, $err_log);
                echo "<script>parent.$('#import-ok,#import_over').fadeIn()</script>";
                if ($err > 0) {
                    echo "<script>parent.$('#err_log').fadeIn()</script>";
                }
            }
        } else {
            if (\Yii::$app->request->get("act") == "view-log") {
                $logs = \Yii::$app->cache->get("import-log-" . \Yii::$app->user->identity->staff_id);
                return $this->renderAjax("@app/views/base/import-log", ["logs" => $logs]);
            }
            $this->layout = "@app/views/layouts/ajax";
            return $this->render("@app/views/base/import");
        }
    }


//    即时通讯
    public function actionSendMessage($type, $url = "")
    {
        $feedBack = [];
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            $customers = explode(",", $params['customers']);
            $items = array_filter(explode(",", $params['Select']));
            $others = array_filter(explode(",", $params['Other']));
            $count = count($items) + count($others);
            $msgType = $params['CrmActImessage']['imesg_type'];
            echo "<table>";
            if ($msgType == 1) {
                $content = $params['CrmActImessage']['imesg_notes'];
                $n = 0;
                foreach ($customers as $k => $customer) {
                    $n++;
                    $result = 1;
                    $feedBack[] = [
                        0,
                        $content,
                        1,
                        $items[$k],
                        "发送成功",
                        date("Y-m-d H:i:s")
                    ];
                    if ($result == 1) {
                        $url = $this->findApiUrl() . $this->_url . "send-message";
                        $params["CrmActImessage"]["cust_id"] = $customer;
                        $params["CrmActImessage"]["imesg_type"] = 1;
                        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params));
                        Json::decode($curl->post($url));
                        echo "<tr><td width='100'>(" . ($k + 1) . "/" . $count . ")</td> <td width='200'>{$items[$k]}</td> <td width='100'>发送成功</td></tr>";
                    } else {
                        echo "<tr><td width='100'>(" . ($k + 1) . "/" . $count . ")</td> <td width='200'>{$items[$k]}</td> <td width='100'>发送失败</td></tr>";
                    }
                    ob_flush();
                    flush();
                }
                foreach ($others as $k => $mobile) {
                    $n++;
                    $result = 1;
                    $feedBack[] = [
                        0,
                        $content,
                        1,
                        $mobile,
                        "发送成功",
                        date("Y-m-d H:i:s")
                    ];
                    if ($result == 1) {
                        echo "<tr><td width='100'>(" . ($n) . "/" . $count . ")</td> <td width='200'>{$mobile}</td> <td width='100'>发送成功</td></tr>";
                    } else {
                        echo "<tr><td width='100'>(" . ($n) . "/" . $count . ")</td> <td width='200'>{$mobile}</td> <td width='100'>发送失败</td></tr>";
                    }
                    ob_flush();
                    flush();
                }
            }
            if ($msgType == 2) {
                $subject = $params['CrmActImessage']['imesg_subject'];
                $content = $params['CrmActImessage']['imesg_notes'];
                $n = 0;
                foreach ($customers as $k => $customer) {
                    $n++;
                    $client = new \SoapClient('http://imes.foxconn.com/mailintoface.asmx?WSDL');
                    $data = $client->send([
                        'from' => 'service@foxconnmall.com',
                        'toLst' => array($items[$k]),
                        'subject' => $subject,
                        'body' => $content,
                        'MessageType' => '邮件',
                        'isHtml' => 'true',
                        'strEncoding' => 'utf-8',
                    ]);
                    $result = $data->SendResult;
                    $feedBack[] = [
                        1,
                        $content,
                        $result->status,
                        $items[$k],
                        $result->msg,
                        date("Y-m-d H:i:s")
                    ];
                    if ($result->status == 1) {
                        $params["CrmActImessage"]["cust_id"] = $customer;
                        $params["CrmActImessage"]["imesg_type"] = 2;
                        $url = $this->findApiUrl() . $this->_url . "send-message";
                        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params));
                        Json::decode($curl->post($url));
                        echo "<tr><td width='100'>(" . ($n) . "/" . $count . ")</td> <td width='200'>{$items[$k]}</td> <td width='100'>发送成功</td></tr>";
                    } else {
                        echo "<tr><td width='100'>(" . ($n) . "/" . $count . ")</td> <td width='200'>{$items[$k]}</td> <td width='100'>发送失败</td></tr>";
                    }
                    ob_flush();
                    flush();
                }

                foreach ($others as $k => $email) {
                    $n++;
                    $client = new \SoapClient('http://imes.foxconn.com/mailintoface.asmx?WSDL');
                    $data = $client->send([
                        'from' => 'service@foxconnmall.com',
                        'toLst' => array($email),
                        'subject' => $subject,
                        'body' => $content,
                        'MessageType' => '邮件',
                        'isHtml' => 'true',
                        'strEncoding' => 'utf-8',
                    ]);
                    $result = $data->SendResult;
                    $feedBack[] = [
                        1,
                        $content,
                        $result->status,
                        $email,
                        $result->msg,
                        date("Y-m-d H:i:s")
                    ];
                    if ($result->status == 1) {
                        echo "<tr><td width='100'>(" . ($n) . "/" . $count . ")</td> <td width='200'>{$email}</td> <td width='100'>发送成功</td></tr>";
                    } else {
                        echo "<tr><td width='100'>(" . ($n) . "/" . $count . ")</td> <td width='200'>{$email}</td> <td width='100'>发送失败</td></tr>";
                    }
                    ob_flush();
                    flush();
                }
            }
            echo "</table>";
            \Yii::$app->db->createCommand()->batchInsert("send_log", ["Types", "conts", "yn_ok", "to_obj", "errs", "crt_date"], $feedBack)->execute();
        } else {
            if ($type == 1) {
                return $this->renderAjax("/common/send-msg", ["url" => $url]);
            }
            return $this->renderAjax("/common/send-email", ["url" => $url]);
        }
    }


}
