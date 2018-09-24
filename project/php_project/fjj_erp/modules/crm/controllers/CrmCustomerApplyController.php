<?php
namespace app\modules\crm\controllers;

/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2016/12/15
 * Time: 下午 04:55
 */
use \app\controllers\BaseController;
use app\models\Upload;
use app\modules\common\models\BsBusinessType;
use \app\modules\crm\models\CrmCustomerApply;
use app\modules\crm\models\CrmCustomerInfo;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\modules\common\models\BsDistrict;
use app\modules\system\models\SystemLog;
use yii\web\UploadedFile;
use app\modules\hr\models;

class CrmCustomerApplyController extends BaseController
{
    private $_url = 'crm/crm-customer-apply/'; //对应api

    //客户编码申请列表
    public function actionIndex()
    {
//        dumpE(date('d'));
        $url = $this->findApiUrl() . "hr/staff/view?id=" . Yii::$app->user->identity->staff_id;
        $data = Json::decode($this->findCurl()->get($url));
        $url = $this->findApiUrl() . $this->_url . "is-supper?id=" . Yii::$app->user->identity->getId();
        $result = Json::decode($this->findCurl()->get($url));
        $url = $this->findApiUrl() . $this->_url . "employee-info?staff_code=" . $data['staff_code'];
        $data0 = Json::decode($this->findCurl()->get($url));
        $staffName = $data["staff_name"];//$data["staff_name"]
        $queryParam = Yii::$app->request->queryParams;
        if ($queryParam['CrmCustomerApplySearch']['cust_type1'] == 1 || $result == true || $data0['isrule'] != 1) {
            $staffName0 = '';
        } else {
            $staffName0 = $staffName;
        }
        $url = $this->findApiUrl() . $this->_url . "index?companyId=" . Yii::$app->user->identity->company_id . "&staffName=" . $staffName0;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = Json::decode($this->findCurl()->get($url));
            foreach ($dataProvider['rows'] as $key => $val) {
                $dataProvider['rows'][$key]['cust_filernumber'] = '<a href="' . Url::to(['view', 'id' => $val['cust_id']]) . '">' . $val['cust_filernumber'] . '</a>';
            }
            return Json::encode($dataProvider);
        }
        $export = Yii::$app->request->get('export');
//        if (isset($export)) {
//            $this->exportFiled(Json::decode($this->findCurl()->get($url))['rows']);
//        }
        $downList = $this->getDownList();
        $district = $this->getDistrict();
        $custLevel = $this->getLevel();
        $columns = $this->getField("/crm/crm-customer-apply/index");
        $typeId = BsBusinessType::find()->select('business_type_id')->where(['business_code' => 'khbmsh'])->asArray()->one();
        $typeId = $typeId['business_type_id'];
//        dumpE($typeId);
        return $this->render('index', [
            'result' => $result,
            'staffName' => $staffName,
            'downList' => $downList,
            'district' => $district,
            'custLevel' => $custLevel,
            'queryParam' => $queryParam,
            'columns' => $columns,
            'typeId' => $typeId,
        ]);
    }

    /**
     * @return Action
     * 新增客户
     */
    public function actionSaveCustomer()
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();

            $licnameA = date('Y-m-d H:i:s');//获取当前时间
            $licnameA = str_replace(':', '', $licnameA);
            $licnameA = str_replace(' ', '', $licnameA);
            $licnameB = rand(0, 999);//获取0-999的随机数
            $licnameD = rand(1000, 1999);//获取1000-1999的随机数
            $licnameE = rand(2000, 2999);//获取2000-2999的随机数
            $licnameC = pathinfo($postData['CrmC']['o_license'], PATHINFO_EXTENSION);
            $licnameF = pathinfo($postData['CrmC']['o_reg'], PATHINFO_EXTENSION);
            $licnameG = pathinfo($postData['CrmC']['o_cerft'], PATHINFO_EXTENSION);
            $remotefilelic = $licnameA . $licnameB . '.' . $licnameC;//公司营业执照证和三证合一新文件名
            $remotefiletax = $licnameA . $licnameD . '.' . $licnameF;//税务登记证新文件名
            $remotefileorg = $licnameA . $licnameE . '.' . $licnameG;//一般纳税人资格证新文件名
            $postData['CrmC']['bs_license'] = $remotefilelic;//公司营业执照证和三证合一新文件名
            $postData['CrmC']['tx_reg'] = $remotefiletax;//税务登记证新文件名
            $postData['CrmC']['qlf_certf'] = $remotefileorg;//一般纳税人资格证新文件名

            $postData['CrmCustomerInfo']['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['CrmCustomerInfo']['company_id'] = Yii::$app->user->identity->company_id;
            $postData['CrmCustomerApply']['applyperson'] = Yii::$app->user->identity->staff_id;
            $postData['CrmCustomerApply']['applydep'] = Yii::$app->user->identity->staff->organization_code;
            $postData['CrmCustomerApply']['company_id'] = Yii::$app->user->identity->company_id;
            $postData['CrmCustomerApply']['is_delete'] = 10;

//            $url = $this->findApiUrl() . $this->_url . "create-customer";
//            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
//            $data = Json::decode($curl->post($url));
            //dumpE($data);
            //上传文件
//            dumpE();
            if (is_uploaded_file($_FILES['upfiles-lic']['tmp_name']) &&
                is_uploaded_file($_FILES['upfiles-tax']['tmp_name']) ||
                is_uploaded_file($_FILES['upfiles-org']['tmp_name'])
            ) {
//                dumpE("dfsaf");
                $ftp_server = Yii::$app->ftpPath['ftpIP'];
                $port=Yii::$app->ftpPath['port'];
                if($port=="" ||$port==null)
                {
                    $port='0';
                }
                $ftp_user_name = Yii::$app->ftpPath['ftpUser'];
                $ftp_user_pass = Yii::$app->ftpPath['ftpPwd'];
                $father = Yii::$app->ftpPath['CMP']['father'];
                $pathlcn = Yii::$app->ftpPath['CMP']['BsLcn'];
                $pathreg = Yii::$app->ftpPath['CMP']['TaxReg'];
                $pathqlf = Yii::$app->ftpPath['CMP']['TaxQlf'];
                $years = substr(date('Y'), -2);//当前年份的后两位
                $month = date('m');
                $day = date('d');
                $uploadaddress = $years . $month . $day;
                //$upaddress=trim($_POST['gonghao']);
                $filelic = $_FILES['upfiles-lic']['tmp_name'];
                $filetax = $_FILES['upfiles-tax']['tmp_name'];
                $fileorg = $_FILES['upfiles-org']['tmp_name'];
                $conn_id = ftp_connect($ftp_server,$port) or die("Couldn't connect to $ftp_server");
                $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
                if ($login_result) {
                    $fa = ftp_nlist($conn_id, $father);
//                    $reg = ftp_nlist($conn_id, $pathreg);
//                    $qlf = ftp_nlist($conn_id, $pathqlf);
                    if ($fa != null)//有bslcns、 txrg  、txqlf其中一个文件夹
                    {
                        if ($postData['CrmC']['crtf_type'] == 0) {
                            //上传公司营业执照证
                            $i = 0;
                            $m = 0;
                            foreach ($fa as $lc) {
                                $pathl = $father . $pathlcn;//公司营业执照证书的二级目录
                                $pathr = $father . $pathreg;//税务登记证书的二级目录
                                $pathq = $father . $pathqlf;//一般纳税人资格证书的二级目录
//                                dumpE($lc.$pathr);
                                if ($lc == $pathl) //判断bslcns文件夹是否存在
                                {
                                    $lcnchild = ftp_nlist($conn_id, $pathl);
                                    if ($lcnchild != null) //有时间文件夹
                                    {
                                        foreach ($lcnchild as $ch) {
                                            if ($ch == $pathl . '/' . $uploadaddress) {
                                                $remote_filelic = $ch . '/' . $remotefilelic;
                                                if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                    $i++;
                                                } else {
                                                    $m++;
                                                    echo "失败1";
                                                }
                                            } else {
                                                $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                                $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                                if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                    $i++;
                                                } else {
                                                    $m++;
                                                    echo "失败2";
                                                }
                                            }
                                        }
                                    } else {//没有时间文件夹
                                        $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                        $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                        if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                            $i++;
                                        } else {
                                            $m++;
                                            echo "失败3";
                                        }
                                    }
                                } else //
                                {
                                    $bb = @ftp_mkdir($conn_id, $pathl);//创建公司营业执照证书的二级目录
                                    $cc = @ftp_chdir($conn_id, $pathl);//选择公司营业执照证书的二级目录
                                    $dd = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);//创建日期文件夹
                                    $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                        $i++;
                                    } else {
                                        $m++;
                                        echo "失败4";
                                    }
                                }
                                if ($lc == $pathr) //判断txrg文件夹是否存在
                                {
                                    $lcnchild = ftp_nlist($conn_id, $pathr);
                                    if ($lcnchild != null) //有时间文件夹
                                    {
                                        foreach ($lcnchild as $ch) {
                                            if ($ch == $pathr . '/' . $uploadaddress) {
                                                $remote_filetax = $ch . '/' . $remotefiletax;
                                                if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                    $i++;
                                                } else {
                                                    $m++;
                                                    echo "失败5";
                                                }
                                            } else {
                                                $bb = @ftp_mkdir($conn_id, $pathr . '/' . $uploadaddress);
                                                $remote_filetax = $pathr . '/' . $uploadaddress . '/' . $remotefiletax;
                                                if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                    $i++;
                                                } else {
                                                    $m++;
                                                    echo "失败6";
                                                }
                                            }
                                        }
                                    } else {//没有时间文件夹
                                        $bb = @ftp_mkdir($conn_id, $pathr . '/' . $uploadaddress);
                                        $remote_filetax = $pathr . '/' . $uploadaddress . '/' . $remotefiletax;
                                        if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                            $i++;
                                        } else {
                                            $m++;
                                            echo "失败7";
                                        }
                                    }
                                } else //
                                {
                                    $bb = @ftp_mkdir($conn_id, $pathr);//创建税务登记的二级目录
                                    $cc1 = @ftp_chdir($conn_id, $pathr);//选择税务登记的二级目录
                                    $dd1 = @ftp_mkdir($conn_id, $pathr . '/' . $uploadaddress);//创建日期文件夹
                                    $remote_filetax = $pathr . '/' . $uploadaddress . '/' . $remotefiletax;
                                    if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                        $i++;
                                    } else {
                                        $m++;
                                        echo "失败8";
                                    }
                                }
                                if (!empty($_FILES['upfiles-org']['name'])) //判断一般纳税人资格证是否为空,如果为空则不上传，否则就上传
                                {
                                    if ($lc == $pathq) //判断txqlf文件夹是否存在
                                    {
                                        $lcnchild = ftp_nlist($conn_id, $pathq);
                                        if ($lcnchild != null) //有时间文件夹
                                        {
                                            foreach ($lcnchild as $ch) {
                                                if ($ch == $pathq . '/' . $uploadaddress) {
                                                    $remote_fileorg = $ch . '/' . $remotefileorg;
                                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $m++;
                                                        echo "失败9";
                                                    }
                                                } else {
                                                    $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                                    $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $m++;
                                                        echo "失败10";
                                                    }
                                                }
                                            }
                                        } else {//没有时间文件夹
                                            $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                            $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                            if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                $i++;
                                            } else {
                                                $m++;
                                                echo "失败11";
                                            }
                                        }
                                    } else //
                                    {
                                        $bb = @ftp_mkdir($conn_id, $pathq);//创建一般纳税人资格证的二级目录
                                        $cc2 = @ftp_chdir($conn_id, $pathq);//选择一般纳税人资格证的二级目录
                                        $dd2 = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                        if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                            $i++;
                                        } else {
                                            $m++;
                                            echo "失败12";
                                        }
                                    }
                                }
                            }
                            if ($i >= 2 && $m == 0) {
                                $url = $this->findApiUrl() . $this->_url . "create-customer";
                                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                $data = Json::decode($curl->post($url));
//                                dumpE($data);
                                if ($data['status'] == 1) {
                                    SystemLog::addLog($data['data']['msg']);
                                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
                                } else {
                                    return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
                                }
                            }
//                            dumpE($i);
                        } else {
                            //上传公司营业执照证
                            $s = 0;
                            $k = 0;
                            foreach ($fa as $lc) {
                                $pathl = $father . $pathlcn;//公司营业执照证书的二级目录
                                $pathq = $father . $pathqlf;//一般纳税人资格证书的二级目录
                                if ($lc == $pathl) //判断bslcns文件夹是否存在
                                {
                                    $lcnchild = ftp_nlist($conn_id, $pathl);
                                    if ($lcnchild != null) //有时间文件夹
                                    {
                                        foreach ($lcnchild as $ch) {
                                            if ($ch == $pathl . '/' . $uploadaddress) {
                                                $remote_filelic = $ch . '/' . $remotefilelic;
                                                if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                    $s++;
                                                } else {
                                                    $k++;
                                                    echo "失败1";
                                                }
                                            } else {
                                                $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                                $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                                if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                    $s++;
                                                } else {
                                                    $k++;
                                                    echo "失败2";
                                                }
                                            }
                                        }
                                    } else {//没有时间文件夹
                                        $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                        $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                        if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                            $s++;
                                        } else {
                                            $k++;
                                            echo "失败3";
                                        }
                                    }
                                } else //
                                {
                                    $bb = @ftp_mkdir($conn_id, $pathl);//创建公司营业执照证书的二级目录
                                    $cc = @ftp_chdir($conn_id, $pathl);//选择公司营业执照证书的二级目录
                                    $dd = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);//创建日期文件夹
                                    $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                        $s++;
                                    } else {
                                        $k++;
                                        echo "失败4";
                                    }
                                }
                                if (!empty($_FILES['upfiles-org']['name'])) //判断一般纳税人资格证是否为空,如果为空则不上传，否则就上传
                                {
                                    if ($lc == $pathq) //判断txqlf文件夹是否存在
                                    {
                                        $lcnchild = ftp_nlist($conn_id, $pathq);
                                        if ($lcnchild != null) //有时间文件夹
                                        {
                                            foreach ($lcnchild as $ch) {
                                                if ($ch == $pathq . '/' . $uploadaddress) {
                                                    $remote_fileorg = $ch . '/' . $remotefileorg;
                                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                        $s++;
                                                    } else {
                                                        $k++;
                                                        echo "失败5";
                                                    }
                                                } else {
                                                    $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                                    $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                        $s++;
                                                    } else {
                                                        $k++;
                                                        echo "失败6";
                                                    }
                                                }
                                            }
                                        } else {//没有时间文件夹
                                            $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                            $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                            if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                $s++;
                                            } else {
                                                $k++;
                                                echo "失败7";
                                            }
                                        }
                                    } else //
                                    {
                                        $bb = @ftp_mkdir($conn_id, $pathq);//创建公司营业执照证书的二级目录
                                        $cc2 = @ftp_chdir($conn_id, $pathq);//选择公司营业执照证书的二级目录
                                        $dd2 = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                        if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                            $s++;
                                        } else {
                                            $k++;
                                            echo "失败8";
                                        }
                                    }
                                }
                            }
                            if ($s >= 1 && $k == 0) {
                                $url = $this->findApiUrl() . $this->_url . "create-customer";
                                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                $data = Json::decode($curl->post($url));
                                if ($data['status'] == 1) {
                                    SystemLog::addLog($data['data']['msg']);
                                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
                                } else {
                                    return Json::encode(['msg' => "发生未知错误，新增失败2", "flag" => 0]);
                                }
                            }
//                            dumpE($s);
                        }

                    } else //没有bslcns、txrg、txqlf文件夹
                    {
                        if (!empty($_FILES['upfiles-org']['name']))//判断一般纳税人资格证是否为空,如果为空则不上传，否则就上传
                        {
                            if ($postData['CrmC']['crtf_type'] == 0) {
                                $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                                $cc = @ftp_chdir($conn_id, $father);//选择cmp文件夹
                                $bb1 = @ftp_mkdir($conn_id, $pathlcn);//创建bslcns文件夹
                                $bb2 = @ftp_mkdir($conn_id, $pathreg);//创建txrg文件夹
                                $bb3 = @ftp_mkdir($conn_id, $pathqlf);//创建txqlf文件夹
                                $cc1 = @ftp_chdir($conn_id, $bb1);//选择bslcns文件夹
                                $dd = @ftp_mkdir($conn_id, $bb1 . '/' . $uploadaddress);//创建日期文件夹
                                $cc2 = @ftp_chdir($conn_id, $bb2);//选择bslcns文件夹
                                $dd1 = @ftp_mkdir($conn_id, $bb2 . '/' . $uploadaddress);//创建日期文件夹
                                $cc3 = @ftp_chdir($conn_id, $bb3);//选择bslcns文件夹
                                $dd2 = @ftp_mkdir($conn_id, $bb3 . '/' . $uploadaddress);//创建日期文件夹
                                $remote_filelics = $dd . '/' . $remotefilelic;
                                $remote_filetaxs = $dd1 . '/' . $remotefiletax;
                                $remote_fileorgs = $dd2 . '/' . $remotefileorg;
                                if (ftp_put($conn_id, $remote_filelics, $filelic, FTP_BINARY)
                                    && ftp_put($conn_id, $remote_fileorgs, $fileorg, FTP_BINARY)
                                    && ftp_put($conn_id, $remote_filetaxs, $filetax, FTP_BINARY)
                                ) {
                                    $url = $this->findApiUrl() . $this->_url . "create-customer";
                                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                    $data = Json::decode($curl->post($url));
                                    if ($data['status'] == 1) {
                                        SystemLog::addLog($data['data']['msg']);
                                        return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
                                    } else {
                                        return Json::encode(['msg' => "发生未知错误，新增失败3", "flag" => 0]);
                                    }
                                } else {
                                    echo "失败";
                                }
                            } else {
                                $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                                $cc = @ftp_chdir($conn_id, $father);//选择cmp文件夹
                                $bb1 = @ftp_mkdir($conn_id, $pathlcn);//创建bslcns文件夹
                                $bb3 = @ftp_mkdir($conn_id, $pathqlf);//创建txqlf文件夹
                                $cc1 = @ftp_chdir($conn_id, $bb1);//选择bslcns文件夹
                                $dd = @ftp_mkdir($conn_id, $bb1 . '/' . $uploadaddress);//创建日期文件夹
                                $cc3 = @ftp_chdir($conn_id, $bb3);//选择bslcns文件夹
                                $dd3 = @ftp_mkdir($conn_id, $bb3 . '/' . $uploadaddress);//创建日期文件夹
                                $remote_filelics1 = $dd . '/' . $remotefilelic;
                                $remote_fileorgs1 = $dd3 . '/' . $remotefileorg;
                                if (ftp_put($conn_id, $remote_filelics1, $filelic, FTP_BINARY)
                                    && ftp_put($conn_id, $remote_fileorgs1, $fileorg, FTP_BINARY)
                                ) {
                                    $url = $this->findApiUrl() . $this->_url . "create-customer";
                                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                    $data = Json::decode($curl->post($url));
                                    if ($data['status'] == 1) {
                                        SystemLog::addLog($data['data']['msg']);
                                        return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
                                    } else {
                                        return Json::encode(['msg' => "发生未知错误，新增失败4", "flag" => 0]);
                                    }
                                } else {
                                    echo "失败";
                                }
                            }
                        } else {
                            if ($postData['CrmC']['crtf_type'] == 0) {
                                $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                                $cc = @ftp_chdir($conn_id, $father);//选择cmp文件夹
                                $bb1 = @ftp_mkdir($conn_id, $pathlcn);//创建bslcns文件夹
                                $bb2 = @ftp_mkdir($conn_id, $pathreg);//创建txrg文件夹
//                                $bb3 = @ftp_mkdir($conn_id, $pathqlf);//创建txqlf文件夹
                                $cc1 = @ftp_chdir($conn_id, $bb1);//选择bslcns文件夹
                                $dd = @ftp_mkdir($conn_id, $bb1 . '/' . $uploadaddress);//创建日期文件夹
                                $cc2 = @ftp_chdir($conn_id, $bb2);//选择bslcns文件夹
                                $dd1 = @ftp_mkdir($conn_id, $bb2 . '/' . $uploadaddress);//创建日期文件夹
//                                $cc3 = @ftp_chdir($conn_id, $bb3);//选择bslcns文件夹
//                                $dd2 = @ftp_mkdir($conn_id, $bb3 . '/' . $uploadaddress);//创建日期文件夹
                                $remote_filelics = $dd . '/' . $remotefilelic;
                                $remote_filetaxs = $dd1 . '/' . $remotefiletax;
//                                $remote_fileorgs = $dd2 . '/' . $remotefileorg;
                                if (ftp_put($conn_id, $remote_filelics, $filelic, FTP_BINARY)
                                    && ftp_put($conn_id, $remote_filetaxs, $filetax, FTP_BINARY)
                                ) //                                    && ftp_put($conn_id, $remote_fileorgs, $fileorg, FTP_BINARY)
                                {
                                    $url = $this->findApiUrl() . $this->_url . "create-customer";
                                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                    $data = Json::decode($curl->post($url));
                                    if ($data['status'] == 1) {
                                        SystemLog::addLog($data['data']['msg']);
                                        return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
                                    } else {
                                        return Json::encode(['msg' => "发生未知错误，新增失败5", "flag" => 0]);
                                    }
                                } else {
                                    echo "失败";
                                }
                            } else {
                                $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                                $cc = @ftp_chdir($conn_id, $father);//选择cmp文件夹
                                $bb1 = @ftp_mkdir($conn_id, $pathlcn);//创建bslcns文件夹
//                                $bb3 = @ftp_mkdir($conn_id, $pathqlf);//创建txqlf文件夹
                                $cc1 = @ftp_chdir($conn_id, $bb1);//选择bslcns文件夹
                                $dd = @ftp_mkdir($conn_id, $bb1 . '/' . $uploadaddress);//创建日期文件夹
//                                $cc3 = @ftp_chdir($conn_id, $bb3);//选择bslcns文件夹
//                                $dd3 = @ftp_mkdir($conn_id, $bb3 . '/' . $uploadaddress);//创建日期文件夹
                                $remote_filelics1 = $dd . '/' . $remotefilelic;
//                                $remote_fileorgs1 = $dd3 . '/' . $remotefileorg;
                                if (ftp_put($conn_id, $remote_filelics1, $filelic, FTP_BINARY)
//                                    && ftp_put($conn_id, $remote_fileorgs1, $fileorg, FTP_BINARY)
                                ) {
                                    $url = $this->findApiUrl() . $this->_url . "create-customer";
                                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                    $data = Json::decode($curl->post($url));
                                    if ($data['status'] == 1) {
                                        SystemLog::addLog($data['data']['msg']);
                                        return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
                                    } else {
                                        return Json::encode(['msg' => "发生未知错误，新增失败6", "flag" => 0]);
                                    }
                                } else {
                                    echo "失败";
                                }
                            }
                        }

                    }
                } else {
                    echo '请先登录ftp';
                }
                ftp_close($conn_id);
            }

            $url = $this->findApiUrl() . $this->_url . "create-customer";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg']);
                return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        } else {
            $district = $this->getDistrict();
            $downList = $this->getDownList();
            return $this->render("create", [
                'downList' => $downList,
                'district' => $district,
            ]);
        }

    }

    /**
     * @param $id
     * @return string
     * 更新客户信息
     */
    public function actionUpdateCustomer($id = null)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $msg = "保存成功";
            if ($postData['statusApply'] == 20) {
                $msg = "申请成功";
            }
            $custId = $postData['CrmCustomerInfo']['cust_id'];
            $applyInfo = $this->getApply($custId);

            $licnameA = date('Ymd H:i:s');//获取当前时间
            $licnameA = str_replace(':', '', $licnameA);
            $licnameA = str_replace(' ', '', $licnameA);
            $licnameB = rand(0, 999);//获取0-999的随机数
            $licnameD = rand(1000, 1999);//获取0-999的随机数
            $licnameE = rand(2000, 2999);//获取0-999的随机数
            $licnameC = pathinfo($postData['CrmC']['o_license'], PATHINFO_EXTENSION);
            $licnameF = pathinfo($postData['CrmC']['o_reg'], PATHINFO_EXTENSION);
            $licnameG = pathinfo($postData['CrmC']['o_cerft'], PATHINFO_EXTENSION);
            $remotefilelic = $licnameA . $licnameB . '.' . $licnameC;//公司营业执照证和三证合一新文件名
            $remotefiletax = $licnameA . $licnameD . '.' . $licnameF;//税务登记证新文件名
            $remotefileorg = $licnameA . $licnameE . '.' . $licnameG;//一般纳税人资格证新文件名
            $filename = $this->getCertf($postData['CrmCustomerInfo']['cust_id']);//获取认证信息
            if (!empty($postData['CrmC']['o_license']) && !empty($_FILES['upfiles-lic']['name'])) {
                $postData['CrmC']['bs_license'] = $remotefilelic;//公司营业执照证和三证合一新文件名
            } else {
                $postData['CrmC']['bs_license'] = $filename['bs_license'];//如果没有选择文件就使用原来的文件名
            }
            if (!empty($postData['CrmC']['o_reg']) && !empty($_FILES['upfiles-tax']['name'])) {
                $postData['CrmC']['tx_reg'] = $remotefiletax;//税务登记证新文件名
            } else {
                $postData['CrmC']['tx_reg'] = $filename['tx_reg'];//如果没有选择文件就使用原来的文件名
            }
            if (!empty($postData['CrmC']['o_cerft']) && !empty($_FILES['upfiles-org']['name'])) {
                $postData['CrmC']['qlf_certf'] = $remotefileorg;//一般纳税人资格证新文件名
            } else {
                if (empty($postData['CrmC']['o_cerft']))//如果不上传一般纳税人资格证书文件名就为空
                {
                    $postData['CrmC']['qlf_certf'] = "";
                    $postData['CrmC']['o_cerft'] = "";
                } else {
                    $postData['CrmC']['qlf_certf'] = $filename['qlf_certf'];//如果没有选择文件就使用原来的文件名
                }
            }
            $postData['CrmCustomerInfo']['update_by'] = Yii::$app->user->identity->staff_id;
            $postData['CrmCustomerApply']['cust_id'] = $postData['CrmCustomerInfo']['cust_id'];
            $postData['CrmCustomerApply']['applyperson'] = Yii::$app->user->identity->staff_id;
            $organiztion = Yii::$app->user->identity->staff->organization_code;
            if (!isset($organiztion)) {
                $organiztion = "";
            }
            $postData['CrmCustomerApply']['applydep'] = $organiztion;
            $postData['CrmCustomerApply']['company_id'] = Yii::$app->user->identity->company_id;
            $postData['CrmCustomerApply']['is_delete'] = '10';
            $status = $postData['statusApply']; //$postData['CrmCustomerApply']['status'];
            if (empty($applyInfo['cust_id']) || $status == 10 || $status == 20) {
//                if ($postData['statusApply'] == 20) {
//                    $postData['CrmCustomerApply']['status'] = '30';//新增申请和修改申请时的提交
//                }
//                $url = $this->findApiUrl() . $this->_url . "update?id=" . $custId;
//                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
//                $data = Json::decode($curl->put($url));
                //上传文件
                $isexist = 0;
                if ($postData['CrmC']['crtf_type'] == 0) {
                    if (!empty($_FILES['upfiles-lic']['name'])  //empty($postData['upfiles-lic']
                        || !empty($_FILES['upfiles-tax']['name'])
                        || !empty($_FILES['upfiles-org']['name'])
                    ) {
                        $isexist = 1;
                    }
                    if (empty($_FILES['upfiles-lic']['name'])
                        && empty($_FILES['upfiles-tax']['name'])
                        && empty($_FILES['upfiles-org']['name'])
                    ) {
                        $isexist = 0;
                    }
                } else {
                    if (!empty($_FILES['upfiles-lic']['name']) || !empty($_FILES['upfiles-tax']['name'])) {
                        $isexist = 1;
                    }
                }
                if ($isexist == 0) //判断证件是否为空
                {
//                    dumpE("fdsa");
                    $url = $this->findApiUrl() . $this->_url . "update?id=" . $custId;
                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                    $data = Json::decode($curl->put($url));
                    $isApply = Yii::$app->request->get('is_apply');
                    $status=Yii::$app->request->get('status');
                    if ($data['status'] == 1) {
                        SystemLog::addLog($data['data']['msg']);
                        if (!empty($isApply)) {
                            return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1,'status'=>$status])]);
                        } else {
                            return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                        }
                    } else {
                        return Json::encode(['msg' => $data['msg'].'发生错误1', "flag" => 0]);
                    }
                } else {
                    if (is_uploaded_file($_FILES['upfiles-lic']['tmp_name']) ||
                        is_uploaded_file($_FILES['upfiles-tax']['tmp_name']) ||
                        is_uploaded_file($_FILES['upfiles-org']['tmp_name'])
                    ) {
                        $ftp_server = Yii::$app->ftpPath['ftpIP'];
                        $port = Yii::$app->ftpPath['port'];
                        if($port=="" ||$port==null)
                        {
                            $port='0';
                        }
                        $ftp_user_name = Yii::$app->ftpPath['ftpUser'];
                        $ftp_user_pass = Yii::$app->ftpPath['ftpPwd'];
                        $father = Yii::$app->ftpPath['CMP']['father'];
                        $pathlcn = Yii::$app->ftpPath['CMP']['BsLcn'];
                        $pathreg = Yii::$app->ftpPath['CMP']['TaxReg'];
                        $pathqlf = Yii::$app->ftpPath['CMP']['TaxQlf'];
                        $years = substr(date('Y'), -2);//当前年份的后两位
                        $month = date('m');
                        $day = date('d');
                        $uploadaddress = $years . $month . $day;
                        //$upaddress=trim($_POST['gonghao']);
                        $filelic = $_FILES['upfiles-lic']['tmp_name'];
                        $filetax = $_FILES['upfiles-tax']['tmp_name'];
                        $fileorg = $_FILES['upfiles-org']['tmp_name'];
                        $conn_id = ftp_connect($ftp_server,$port) or die("Couldn't connect to $ftp_server");
                        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
                        if ($login_result) {
                            $fa = ftp_nlist($conn_id, $father);
                            $reg = ftp_nlist($conn_id, $pathreg);
                            $qlf = ftp_nlist($conn_id, $pathqlf);
                            if ($fa != null)//有bslcns、 txrg  、txqlf其中一个文件夹
                            {
                                if ($postData['CrmC']['crtf_type'] == 0) {
                                    //上传公司营业执照证
                                    $i = 0;
                                    $k = 0;
                                    foreach ($fa as $lc) {
                                        $pathl = $father . $pathlcn;//公司营业执照证书的二级目录
                                        $pathr = $father . $pathreg;//税务登记证书的二级目录
                                        if (!empty($_FILES['upfiles-lic']['name'])) {
                                            if ($lc == $pathl) //判断bslcns文件夹是否存在
                                            {
                                                $lcnchild = ftp_nlist($conn_id, $pathl);
                                                if ($lcnchild != null) //有时间文件夹
                                                {
                                                    foreach ($lcnchild as $ch) {
                                                        if ($ch == $pathl . '/' . $uploadaddress) {
                                                            $remote_filelic = $ch . '/' . $remotefilelic;
                                                            if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                                $i++;
                                                            } else {
                                                                $k++;
                                                                return Json::encode(['msg' => "发生错误2", "flag" => 0]);
                                                            }
                                                        } else {
                                                            $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                                            $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                                            if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                                $i++;
                                                            } else {
                                                                $k++;
                                                                return Json::encode(['msg' => "发生错误3", "flag" => 0]);
                                                            }
                                                        }
                                                    }
                                                } else {//没有时间文件夹
                                                    $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                                    $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        return Json::encode(['msg' => "发生错误4", "flag" => 0]);
                                                    }
                                                }
                                            } else //
                                            {
                                                $bb = @ftp_mkdir($conn_id, $pathl);//创建公司营业执照证书的二级目录
                                                $cc = @ftp_chdir($conn_id, $pathl);//选择公司营业执照证书的二级目录
                                                $dd = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);//创建日期文件夹
                                                $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                                if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                    $i++;
                                                } else {
                                                    $k++;
                                                    return Json::encode(['msg' => "发生错误5", "flag" => 0]);
                                                }
                                            }
                                        }
                                        if (!empty($_FILES['upfiles-tax']['name'])) {
                                            if ($lc == $pathr) //判断txrg文件夹是否存在
                                            {
                                                $lcnchild = ftp_nlist($conn_id, $pathr);
                                                if ($lcnchild != null) //有时间文件夹
                                                {
                                                    foreach ($lcnchild as $ch) {
                                                        if ($ch == $pathr . '/' . $uploadaddress) {
                                                            $remote_filetax = $ch . '/' . $remotefiletax;
                                                            if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                                $i++;
                                                            } else {
                                                                $k++;
                                                                return Json::encode(['msg' => "发生错误6", "flag" => 0]);
                                                            }
                                                        } else {
                                                            $bb = @ftp_mkdir($conn_id, $pathr . '/' . $uploadaddress);
                                                            $remote_filetax = $pathr . '/' . $uploadaddress . '/' . $remotefiletax;
                                                            if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                                $i++;
                                                            } else {
                                                                $k++;
                                                                return Json::encode(['msg' => "发生错误7", "flag" => 0]);
                                                            }
                                                        }
                                                    }
                                                } else {//没有时间文件夹
                                                    $bb = @ftp_mkdir($conn_id, $pathr . '/' . $uploadaddress);
                                                    $remote_filetax = $pathr . '/' . $uploadaddress . '/' . $remotefiletax;
                                                    if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        return Json::encode(['msg' => "发生错误8", "flag" => 0]);
                                                    }
                                                }
                                            } else //
                                            {
                                                $bb = @ftp_mkdir($conn_id, $pathr);//创建税务登记的二级目录
                                                $cc1 = @ftp_chdir($conn_id, $pathr);//选择税务登记的二级目录
                                                $dd1 = @ftp_mkdir($conn_id, $pathr . '/' . $uploadaddress);//创建日期文件夹
                                                $remote_filetax = $pathr . '/' . $uploadaddress . '/' . $remotefiletax;
//                                        dumpE($remote_filetax);
                                                if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                    $i++;
                                                } else {
                                                    $k++;
                                                    return Json::encode(['msg' => "发生错误9", "flag" => 0]);
                                                }
                                            }
                                        }
                                        if (!empty($_FILES['upfiles-org']['name'])) //如果一般纳税人资格证为空就不上传，否则就上传
                                        {
                                            $pathq = $father . $pathqlf;//一般纳税人资格证书的二级目录
                                            if ($lc == $pathq) //判断txqlf文件夹是否存在
                                            {
                                                $lcnchild = ftp_nlist($conn_id, $pathq);
                                                if ($lcnchild != null) //有时间文件夹
                                                {
                                                    foreach ($lcnchild as $ch) {
                                                        if ($ch == $pathq . '/' . $uploadaddress) {
                                                            $remote_fileorg = $ch . '/' . $remotefileorg;
                                                            if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                                $i++;
                                                            } else {
                                                                $k++;
                                                                return Json::encode(['msg' => "发生错误10", "flag" => 0]);
                                                            }
                                                        } else {
                                                            $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                                            $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                                            if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                                $i++;
                                                            } else {
                                                                $k++;
                                                                return Json::encode(['msg' => "发生错误11", "flag" => 0]);
                                                            }
                                                        }
                                                    }
                                                } else {//没有时间文件夹
                                                    $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                                    $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        return Json::encode(['msg' => "发生错误12", "flag" => 0]);
                                                    }
                                                }
                                            } else //
                                            {
                                                $bb = @ftp_mkdir($conn_id, $pathq);//创建一般纳税人资格证的二级目录
                                                $cc2 = @ftp_chdir($conn_id, $pathq);//选择一般纳税人资格证的二级目录
                                                $dd2 = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);//创建日期文件夹
                                                $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                                if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                    $i++;
                                                } else {
                                                    $k++;
                                                    return Json::encode(['msg' => "发生错误13", "flag" => 0]);
                                                }
                                            }
                                        }
                                    }
                                    if ($i >= 2 && $k == 0) {
                                        $url = $this->findApiUrl() . $this->_url . "update?id=" . $custId;
                                        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                        $data = Json::decode($curl->put($url));
                                        $isApply = Yii::$app->request->get('is_apply');
                                        $status=Yii::$app->request->get('status');
                                        if ($data['status'] == 1) {
                                            SystemLog::addLog($data['data']['msg']);
                                            if (!empty($isApply)) {
                                                return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1,'status'=>$status])]);
                                            } else {
                                                return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                            }
                                        } else {
                                            return Json::encode(['msg' => $data['msg'].'发生错误14', "flag" => 0]);
                                        }
                                    }
                                } else {
                                    //上传公司三证合一证
                                    $m = 0;
                                    $n = 0;
                                    foreach ($fa as $lc) {
                                        $pathl = $father . $pathlcn;//公司三证合一证的二级目录
                                        if (!empty($_FILES['upfiles-lic']['name'])) {
                                            if ($lc == $pathl) //判断bslcns文件夹是否存在
                                            {
                                                $lcnchild = ftp_nlist($conn_id, $pathl);
                                                if ($lcnchild != null) //有时间文件夹
                                                {
                                                    foreach ($lcnchild as $ch) {
                                                        if ($ch == $pathl . '/' . $uploadaddress) {
                                                            $remote_filelic = $ch . '/' . $remotefilelic;
                                                            if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                                $m++;
                                                            } else {
                                                                $n++;
                                                                return Json::encode(['msg' => "发生错误15", "flag" => 0]);
                                                            }
                                                        } else {
                                                            $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                                            $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                                            if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                                $m++;
                                                            } else {
                                                                $n++;
                                                                return Json::encode(['msg' => "发生错误16", "flag" => 0]);
                                                            }
                                                        }
                                                    }
                                                } else {//没有时间文件夹
                                                    $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                                    $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                        $m++;
                                                    } else {
                                                        $n++;
                                                        return Json::encode(['msg' => "发生错误17", "flag" => 0]);
                                                    }
                                                }
                                            } else //
                                            {
                                                $bb = @ftp_mkdir($conn_id, $pathl);//创建公司三证合一证书的二级目录
                                                $cc = @ftp_chdir($conn_id, $pathl);//选择公司三证合一证书的二级目录
                                                $dd = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);//创建日期文件夹
                                                $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                                if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                    $m++;
                                                } else {
                                                    $n++;
                                                    return Json::encode(['msg' => "发生错误18", "flag" => 0]);
                                                }
                                            }
                                        }
                                        if (!empty($_FILES['upfiles-org']['name'])) //如果一般纳税人资格证为空就不上传，否则就上传
                                        {
                                            $pathq = $father . $pathqlf;//一般纳税人资格证书的二级目录
                                            if ($lc == $pathq) //判断txqlf文件夹是否存在
                                            {
                                                $lcnchild = ftp_nlist($conn_id, $pathq);
                                                if ($lcnchild != null) //有时间文件夹
                                                {
                                                    foreach ($lcnchild as $ch) {
                                                        if ($ch == $pathq . '/' . $uploadaddress) {
                                                            $remote_fileorg = $ch . '/' . $remotefileorg;
                                                            if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                                $m++;
                                                            } else {
                                                                $n++;
                                                                return Json::encode(['msg' => "发生错误19", "flag" => 0]);
                                                            }
                                                        } else {
                                                            $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                                            $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                                            if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                                $m++;
                                                            } else {
                                                                $n++;
                                                                return Json::encode(['msg' => "发生错误20", "flag" => 0]);
                                                            }
                                                        }
                                                    }
                                                } else {//没有时间文件夹
                                                    $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                                    $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                        $m++;
                                                    } else {
                                                        $n++;
                                                        return Json::encode(['msg' => "发生错误21", "flag" => 0]);
                                                    }
                                                }
                                            } else //
                                            {
                                                $bb = @ftp_mkdir($conn_id, $pathq);//创建一般纳税人资格证书的二级目录
                                                $cc2 = @ftp_chdir($conn_id, $pathq);//选择一般纳税人资格证书的二级目录
                                                $dd2 = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);//创建日期文件夹
                                                $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                                if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                    $m++;
                                                } else {
                                                    $n++;
                                                    return Json::encode(['msg' => "发生错误22", "flag" => 0]);
                                                }
                                            }
                                        }
                                    }
                                    if ($m >= 1 && $n == 0) {
                                        $url = $this->findApiUrl() . $this->_url . "update?id=" . $custId;
                                        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                        $data = Json::decode($curl->put($url));
                                        $isApply = Yii::$app->request->get('is_apply');
                                        $status=Yii::$app->request->get('status');
                                        if ($data['status'] == 1) {
                                            SystemLog::addLog($data['data']['msg']);
                                            if (!empty($isApply)) {
                                                return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1,'status'=>$status])]);
                                            } else {
                                                return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                            }
                                        } else {
                                            return Json::encode(['msg' =>  $data['msg'].'发生错误23', "flag" => 0]);
                                        }
                                    }
                                }
                            } else //没有bslcns、txrg、txqlf文件夹
                            {
                                if (!empty($_FILES['upfiles-org']['name'])) //如果一般纳税人资格证为空就不上传，否则就上传
                                {
                                    if ($postData['CrmC']['crtf_type'] == 0) {
                                        $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                                        $cc = @ftp_chdir($conn_id, $father);//选择cmp文件夹
                                        $bb1 = @ftp_mkdir($conn_id, $pathlcn);//创建bslcns文件夹
                                        $bb2 = @ftp_mkdir($conn_id, $pathreg);//创建txrg文件夹
                                        $bb3 = @ftp_mkdir($conn_id, $pathqlf);//创建txqlf文件夹
                                        $cc1 = @ftp_chdir($conn_id, $bb1);//选择bslcns文件夹
                                        $dd = @ftp_mkdir($conn_id, $bb1 . '/' . $uploadaddress);//创建日期文件夹
                                        $cc2 = @ftp_chdir($conn_id, $bb2);//选择bslcns文件夹
                                        $dd1 = @ftp_mkdir($conn_id, $bb2 . '/' . $uploadaddress);//创建日期文件夹
                                        $cc3 = @ftp_chdir($conn_id, $bb3);//选择bslcns文件夹
                                        $dd2 = @ftp_mkdir($conn_id, $bb3 . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_filelics = $dd . '/' . $remotefilelic;
                                        $remote_filetaxs = $dd1 . '/' . $remotefiletax;
                                        $remote_fileorgs = $dd2 . '/' . $remotefileorg;
                                        if (ftp_put($conn_id, $remote_filelics, $filelic, FTP_BINARY)
                                            && ftp_put($conn_id, $remote_fileorgs, $fileorg, FTP_BINARY)
                                            && ftp_put($conn_id, $remote_filetaxs, $filetax, FTP_BINARY)
                                        ) {
                                            $url = $this->findApiUrl() . $this->_url . "update?id=" . $custId;
                                            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                            $data = Json::decode($curl->put($url));
                                            $isApply = Yii::$app->request->get('is_apply');
                                            $status=Yii::$app->request->get('status');
                                            if ($data['status'] == 1) {
                                                SystemLog::addLog($data['data']['msg']);
                                                if (!empty($isApply)) {
                                                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1,'status'=>$status])]);
                                                } else {
                                                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                                }
                                            } else {
                                                return Json::encode(['msg' =>  $data['msg'].'发生错误24', "flag" => 0]);
                                            }
                                        } else {
                                            return Json::encode(['msg' => "发生错误25", "flag" => 0]);
                                        }
                                    } else {
                                        $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                                        $cc = @ftp_chdir($conn_id, $father);//选择cmp文件夹
                                        $bb1 = @ftp_mkdir($conn_id, $pathlcn);//创建bslcns文件夹
                                        $bb3 = @ftp_mkdir($conn_id, $pathqlf);//创建txqlf文件夹
                                        $cc1 = @ftp_chdir($conn_id, $bb1);//选择bslcns文件夹
                                        $dd = @ftp_mkdir($conn_id, $bb1 . '/' . $uploadaddress);//创建日期文件夹
                                        $cc3 = @ftp_chdir($conn_id, $bb3);//选择bslcns文件夹
                                        $dd3 = @ftp_mkdir($conn_id, $bb3 . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_filelics1 = $dd . '/' . $remotefilelic;
                                        $remote_fileorgs1 = $dd3 . '/' . $remotefileorg;
                                        if (ftp_put($conn_id, $remote_filelics1, $filelic, FTP_BINARY)
                                            && ftp_put($conn_id, $remote_fileorgs1, $fileorg, FTP_BINARY)
                                        ) {
                                            $url = $this->findApiUrl() . $this->_url . "update?id=" . $custId;
                                            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                            $data = Json::decode($curl->put($url));
                                            $isApply = Yii::$app->request->get('is_apply');
                                            $status=Yii::$app->request->get('status');
                                            if ($data['status'] == 1) {
                                                SystemLog::addLog($data['data']['msg']);
                                                if (!empty($isApply)) {
                                                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1,'status'=>$status])]);
                                                } else {
                                                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                                }
                                            } else {
                                                return Json::encode(['msg' =>  $data['msg'].'发生错误26', "flag" => 0]);
                                            }
                                        } else {
                                            return Json::encode(['msg' => "发生错误27", "flag" => 0]);
                                    }
                                    }
                                } else {
                                    if ($postData['CrmC']['crtf_type'] == 0) {
                                        $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                                        $cc = @ftp_chdir($conn_id, $father);//选择cmp文件夹
                                        $bb1 = @ftp_mkdir($conn_id, $pathlcn);//创建bslcns文件夹
                                        $bb2 = @ftp_mkdir($conn_id, $pathreg);//创建txrg文件夹
//                                    $bb3 = @ftp_mkdir($conn_id, $pathqlf);//创建txqlf文件夹
                                        $cc1 = @ftp_chdir($conn_id, $bb1);//选择bslcns文件夹
                                        $dd = @ftp_mkdir($conn_id, $bb1 . '/' . $uploadaddress);//创建日期文件夹
                                        $cc2 = @ftp_chdir($conn_id, $bb2);//选择bslcns文件夹
                                        $dd1 = @ftp_mkdir($conn_id, $bb2 . '/' . $uploadaddress);//创建日期文件夹
//                                    $cc3 = @ftp_chdir($conn_id, $bb3);//选择bslcns文件夹
//                                    $dd2 = @ftp_mkdir($conn_id, $bb3 . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_filelics = $dd . '/' . $remotefilelic;
                                        $remote_filetaxs = $dd1 . '/' . $remotefiletax;
//                                    $remote_fileorgs = $dd2 . '/' . $remotefileorg;
                                        if (ftp_put($conn_id, $remote_filelics, $filelic, FTP_BINARY)
                                            && ftp_put($conn_id, $remote_filetaxs, $filetax, FTP_BINARY)
//                                        && ftp_put($conn_id, $remote_fileorgs, $fileorg, FTP_BINARY)
                                        ) {
                                            $url = $this->findApiUrl() . $this->_url . "update?id=" . $custId;
                                            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                            $data = Json::decode($curl->put($url));
                                            $isApply = Yii::$app->request->get('is_apply');
                                            $status=Yii::$app->request->get('status');
                                            if ($data['status'] == 1) {
                                                SystemLog::addLog($data['data']['msg']);
                                                if (!empty($isApply)) {
                                                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1,'status'=>$status])]);
                                                } else {
                                                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                                }
                                            } else {
                                                return Json::encode(['msg' =>  $data['msg'].'发生错误28', "flag" => 0]);
                                            }
                                        } else {
                                            return Json::encode(['msg' => "发生错误29", "flag" => 0]);
                                        }
                                    } else {
                                        $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                                        $cc = @ftp_chdir($conn_id, $father);//选择cmp文件夹
                                        $bb1 = @ftp_mkdir($conn_id, $pathlcn);//创建bslcns文件夹
//                                    $bb3 = @ftp_mkdir($conn_id, $pathqlf);//创建txqlf文件夹
                                        $cc1 = @ftp_chdir($conn_id, $bb1);//选择bslcns文件夹
                                        $dd = @ftp_mkdir($conn_id, $bb1 . '/' . $uploadaddress);//创建日期文件夹
//                                    $cc3 = @ftp_chdir($conn_id, $bb3);//选择bslcns文件夹
//                                    $dd3 = @ftp_mkdir($conn_id, $bb3 . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_filelics1 = $dd . '/' . $remotefilelic;
//                                    $remote_fileorgs1 = $dd3 . '/' . $remotefileorg;
                                        if (ftp_put($conn_id, $remote_filelics1, $filelic, FTP_BINARY)
//                                        && ftp_put($conn_id, $remote_fileorgs1, $fileorg, FTP_BINARY)
                                        ) {
                                            $url = $this->findApiUrl() . $this->_url . "update?id=" . $custId;
                                            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                            $data = Json::decode($curl->put($url));
                                            $isApply = Yii::$app->request->get('is_apply');
                                            $status=Yii::$app->request->get('status');
                                            if ($data['status'] == 1) {
                                                SystemLog::addLog($data['data']['msg']);
                                                if (!empty($isApply)) {
                                                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1,'status'=>$status])]);
                                                } else {
                                                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                                }
                                            } else {
                                                return Json::encode(['msg' =>  $data['msg'].'发生错误30', "flag" => 0]);
                                            }
                                        } else {
                                            return Json::encode(['msg' => "发生错误31", "flag" => 0]);
                                        }
                                    }
                                }
                            }
                        } else {
                            echo '请先登录ftp';
                        }
                        ftp_close($conn_id);
                    }
                }

//                $isApply = Yii::$app->request->get('is_apply');
//                if ($data['status'] == 1) {
//                    SystemLog::addLog($data['data']['msg']);
//                    if (!empty($isApply)) {
//                        return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
//                    } else {
//                        return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
//                    }
//                } else {
//                    return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
//                }
            } else if ($postData['statusApply'] == 10) {
                return Json::encode(['msg' => "已保存,请勿重复保存", "flag" => 0]);
            } else {
                return Json::encode(['msg' => "已申请,请勿重复申请", "flag" => 0]);
            }
        } else {
            $model = $this->getModel($id);
            $districtId2 = $model['cust_district_2'];
            $districtId3 = $model['cust_district_3'];
            $districtAll2 = $this->getAllDistrict($districtId2);
            $districtAll3 = $this->getAllDistrict($districtId3);
            $downList = $this->getDownList();
            $district = $this->getDistrict();
            return $this->render("update", [
                'model' => $model,
                'downList' => $downList,
                'district' => $district,
                'districtAll2' => $districtAll2,
                'districtAll3' => $districtAll3,
            ]);
        }
    }

    //新增公司申請編碼
    public function actionCreate($id = null)
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $custId = $post['CrmCustomerInfo']['cust_id'];
            $applyInfo = $this->getApply($custId);
            $status = $post['CrmCustomerApply']['status'];
            if (empty($applyInfo['cust_id']) || $status == 50 || $status == 10) {
                $postData['CrmCustomerApply']['cust_id'] = $post['CrmCustomerInfo']['cust_id'];
                $postData['CrmCustomerApply']['applyperson'] = Yii::$app->user->identity->staff_id;
                $postData['CrmCustomerApply']['applydep'] = Yii::$app->user->identity->staff->organization_code;
                $postData['CrmCustomerApply']['company_id'] = Yii::$app->user->identity->company_id;
                $postData['CrmCustomerApply']['is_delete'] = '10';
                $postData['CrmCustomerApply']['status'] = '20';
                $url = $this->findApiUrl() . $this->_url . "update?id=" . $custId;
                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                $data = Json::decode($curl->put($url));
                if ($data['status'] == 1) {
                    return Json::encode(['msg' => "申请成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
                } else {
                    return Json::encode(['msg' => "发生未知错误，申请失败", "flag" => 0]);
                }
            } else {
                return Json::encode(['msg' => "已申请,请勿重复申请", "flag" => 0]);
            }
        } else {
            if (!empty($id)) {
                $model = $this->getCusInfo($id);
                $districtId2 = $model['cust_district_2'];
                $districtId3 = $model['cust_district_3'];
                $districtAll2 = $this->getAllDistrict($districtId2);
                $districtAll3 = $this->getAllDistrict($districtId3);
                $downList = $this->getDownList();
                $district = $this->getDistrict();
                $salearea = $this->getSalearea();
                $country = $this->getCountry();
                $industryType = $this->getIndustryType();
                return $this->render("create", [
                    'model' => $model,
                    'downList' => $downList,
                    'district' => $district,
                    'salearea' => $salearea,
                    'country' => $country,
                    'industryType' => $industryType,
                    'districtAll2' => $districtAll2,
                    'districtAll3' => $districtAll3,
                    'id' => $id
                ]);
            } else {
                $downList = $this->getDownList();
                $district = $this->getDistrict();
                $salearea = $this->getSalearea();
                $country = $this->getCountry();
                $industryType = $this->getIndustryType();
                return $this->render("create", [
                    'downList' => $downList,
                    'district' => $district,
                    'salearea' => $salearea,
                    'country' => $country,
                    'industryType' => $industryType,
                ]);
            }
        }
    }

    /**
     * @param $id
     * @return string
     * 更新客户及新增编码申请
     */
    public function actionCustomerInfo($id, $status = null)
    {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $post = Yii::$app->request->post();
            $custId = $post['CrmCustomerInfo']['cust_id'];
            $applyInfo = $this->getApply($custId);;

            $licnameA = date('Y-m-d H:i:s');//获取当前时间
            $licnameA = str_replace(':', '', $licnameA);
            $licnameA = str_replace(' ', '', $licnameA);
            $licnameB = rand(0, 999);//获取0-999的随机数
            $licnameD = rand(1000, 1999);//获取1000-1999的随机数
            $licnameE = rand(2000, 2999);//获取2000-2999的随机数
            $licnameC = pathinfo($postData['CrmC']['o_license'], PATHINFO_EXTENSION);
            $licnameF = pathinfo($postData['CrmC']['o_reg'], PATHINFO_EXTENSION);
            $licnameG = pathinfo($postData['CrmC']['o_cerft'], PATHINFO_EXTENSION);
            $remotefilelic = $licnameA . $licnameB . '.' . $licnameC;//公司营业执照证和三证合一新文件名
            $remotefiletax = $licnameA . $licnameD . '.' . $licnameF;//税务登记证新文件名
            $remotefileorg = $licnameA . $licnameE . '.' . $licnameG;//一般纳税人资格证新文件名
            $postData['CrmC']['bs_license'] = $remotefilelic;//公司营业执照证和三证合一新文件名
            $postData['CrmC']['tx_reg'] = $remotefiletax;//税务登记证新文件名
            $postData['CrmC']['qlf_certf'] = $remotefileorg;//一般纳税人资格证新文件名

            if (empty($applyInfo)) {
                $postData['CrmCustomerInfo']['update_by'] = Yii::$app->user->identity->staff_id;

//                $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
//                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
//                $data = Json::decode($curl->put($url));
                //上传文件
                if (is_uploaded_file($_FILES['upfiles-lic']['tmp_name']) ||
                    is_uploaded_file($_FILES['upfiles-tax']['tmp_name']) ||
                    is_uploaded_file($_FILES['upfiles-org']['tmp_name'])
                ) {
                    $ftp_server = Yii::$app->ftpPath['ftpIP'];
                    $port = Yii::$app->ftpPath['port'];
                    if($port=="" ||$port==null)
                    {
                        $port='0';
                    }
                    $ftp_user_name = Yii::$app->ftpPath['ftpUser'];
                    $ftp_user_pass = Yii::$app->ftpPath['ftpPwd'];
                    $father = Yii::$app->ftpPath['CMP']['father'];
                    $pathlcn = Yii::$app->ftpPath['CMP']['BsLcn'];
                    $pathreg = Yii::$app->ftpPath['CMP']['TaxReg'];
                    $pathqlf = Yii::$app->ftpPath['CMP']['TaxQlf'];
                    $years = substr(date('Y'), -2);//当前年份的后两位
                    $month = date('m');
                    $day = date('d');
                    $uploadaddress = $years . $month . $day;
                    //$upaddress=trim($_POST['gonghao']);
                    $filelic = $_FILES['upfiles-lic']['tmp_name'];
                    $filetax = $_FILES['upfiles-tax']['tmp_name'];
                    $fileorg = $_FILES['upfiles-org']['tmp_name'];
                    $conn_id = ftp_connect($ftp_server,$port) or die("Couldn't connect to $ftp_server");
                    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
                    if ($login_result) {
                        $fa = ftp_nlist($conn_id, $father);
                        $reg = ftp_nlist($conn_id, $pathreg);
                        $qlf = ftp_nlist($conn_id, $pathqlf);
                        if ($fa != null)//有bslcns、 txrg  、txqlf其中一个文件夹
                        {
                            if ($postData['CrmC']['crtf_type'] == 0) {
                                //上传公司营业执照证
                                $i = 0;
                                $k = 0;
                                foreach ($fa as $lc) {
                                    $pathl = $father . $pathlcn;//公司营业执照证书的二级目录
                                    $pathr = $father . $pathreg;//税务登记证书的二级目录
                                    $pathq = $father . $pathqlf;//一般纳税人资格证书的二级目录
//                                dumpE($lc.$pathr);
                                    if ($lc == $pathl) //判断bslcns文件夹是否存在
                                    {
                                        $lcnchild = ftp_nlist($conn_id, $pathl);
                                        if ($lcnchild != null) //有时间文件夹
                                        {
                                            foreach ($lcnchild as $ch) {
                                                if ($ch == $pathl . '/' . $uploadaddress) {
                                                    $remote_filelic = $ch . '/' . $remotefilelic;
                                                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        echo "失败";
                                                    }
                                                } else {
                                                    $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                                    $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        echo "失败";
                                                    }
                                                }
                                            }
                                        } else {//没有时间文件夹
                                            $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                            $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                            if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                $i++;
                                            } else {
                                                $k++;
                                                echo "失败";
                                            }
                                        }
                                    } else //
                                    {
                                        $bb = @ftp_mkdir($conn_id, $pathl);//创建公司营业执照证书的二级目录
                                        $cc = @ftp_chdir($conn_id, $pathl);//选择公司营业执照证书的二级目录
                                        $dd = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                        if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                            $i++;
                                        } else {
                                            $k++;
                                            echo "失败";
                                        }
                                    }
                                    if ($lc == $pathr) //判断txrg文件夹是否存在
                                    {
                                        $lcnchild = ftp_nlist($conn_id, $pathr);
                                        if ($lcnchild != null) //有时间文件夹
                                        {
                                            foreach ($lcnchild as $ch) {
                                                if ($ch == $pathr . '/' . $uploadaddress) {
                                                    $remote_filetax = $ch . '/' . $remotefiletax;
                                                    if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        echo "失败";
                                                    }
                                                } else {
                                                    $bb = @ftp_mkdir($conn_id, $pathr . '/' . $uploadaddress);
                                                    $remote_filetax = $pathr . '/' . $uploadaddress . '/' . $remotefiletax;
                                                    if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        echo "失败";
                                                    }
                                                }
                                            }
                                        } else {//没有时间文件夹
                                            $bb = @ftp_mkdir($conn_id, $pathr . '/' . $uploadaddress);
                                            $remote_filetax = $pathr . '/' . $uploadaddress . '/' . $remotefiletax;
                                            if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                $i++;
                                            } else {
                                                $k++;
                                                echo "失败";
                                            }
                                        }
                                    } else //
                                    {
                                        $bb = @ftp_mkdir($conn_id, $pathr);//创建税务登记的二级目录
                                        $cc1 = @ftp_chdir($conn_id, $pathr);//选择税务登记的二级目录
                                        $dd1 = @ftp_mkdir($conn_id, $pathr . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_filetax = $pathr . '/' . $uploadaddress . '/' . $remotefiletax;
                                        if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                            $i++;
                                        } else {
                                            $k++;
                                            echo "失败";
                                        }
                                    }
                                    if ($lc == $pathq) //判断txqlf文件夹是否存在
                                    {
                                        $lcnchild = ftp_nlist($conn_id, $pathq);
                                        if ($lcnchild != null) //有时间文件夹
                                        {
                                            foreach ($lcnchild as $ch) {
                                                if ($ch == $pathq . '/' . $uploadaddress) {
                                                    $remote_fileorg = $ch . '/' . $remotefileorg;
                                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        echo "失败";
                                                    }
                                                } else {
                                                    $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                                    $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                        $i++;
                                                    } else {
                                                        $k++;
                                                        echo "失败";
                                                    }
                                                }
                                            }
                                        } else {//没有时间文件夹
                                            $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                            $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                            if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                $i++;
                                            } else {
                                                $k++;
                                                echo "失败";
                                            }
                                        }
                                    } else //
                                    {
                                        $bb = @ftp_mkdir($conn_id, $pathq);//创建一般纳税人资格证的二级目录
                                        $cc2 = @ftp_chdir($conn_id, $pathq);//选择一般纳税人资格证的二级目录
                                        $dd2 = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                        if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                            $i++;
                                        } else {
                                            $k++;
                                            echo "失败";
                                        }
                                    }
                                }
                                if ($i >= 3 && $k == 0) {
                                    $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
                                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                    $data = Json::decode($curl->put($url));
                                    if ($data['status'] == 1) {
                                        SystemLog::addLog($data['data']['msg']);
                                        return Json::encode(['msg' => "申请成功", "flag" => 1, "url" => Url::to(['/crm/crm-customer-apply/view', 'id' => $id])]);
                                    } else {
                                        return Json::encode(['msg' => json::encode($data)."发生未知错误，保存失败", "flag" => 0]);
                                    }
                                }
//                                dumpE('i' . $i);
                            } else {
                                //上传公司营业执照证
                                $m = 0;
                                $n = 0;
                                foreach ($fa as $lc) {
                                    $pathl = $father . $pathlcn;//公司营业执照证书的二级目录
                                    $pathq = $father . $pathqlf;//一般纳税人资格证书的二级目录
                                    if ($lc == $pathl) //判断bslcns文件夹是否存在
                                    {
                                        $lcnchild = ftp_nlist($conn_id, $pathl);
                                        if ($lcnchild != null) //有时间文件夹
                                        {
                                            foreach ($lcnchild as $ch) {
                                                if ($ch == $pathl . '/' . $uploadaddress) {
                                                    $remote_filelic = $ch . '/' . $remotefilelic;
                                                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                        $m++;
                                                    } else {
                                                        $n++;
                                                        echo "失败";
                                                    }
                                                } else {
                                                    $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                                    $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                        $m++;
                                                    } else {
                                                        $n++;
                                                        echo "失败";
                                                    }
                                                }
                                            }
                                        } else {//没有时间文件夹
                                            $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                            $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                            if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                $m++;
                                            } else {
                                                $n++;
                                                echo "失败";
                                            }
                                        }
                                    } else //
                                    {
                                        $bb = @ftp_mkdir($conn_id, $pathl);//创建公司营业执照证书的二级目录
                                        $cc = @ftp_chdir($conn_id, $pathl);//选择公司营业执照证书的二级目录
                                        $dd = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_filelic = $pathl . '/' . $uploadaddress . '/' . $remotefilelic;
                                        if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                            $m++;
                                        } else {
                                            $n++;
                                            echo "失败";
                                        }
                                    }
                                    if ($lc == $pathq) //判断txqlf文件夹是否存在
                                    {
                                        $lcnchild = ftp_nlist($conn_id, $pathq);
                                        if ($lcnchild != null) //有时间文件夹
                                        {
                                            foreach ($lcnchild as $ch) {
                                                if ($ch == $pathq . '/' . $uploadaddress) {
                                                    $remote_fileorg = $ch . '/' . $remotefileorg;
                                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                        $m++;
                                                    } else {
                                                        $n++;
                                                        echo "失败";
                                                    }
                                                } else {
                                                    $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                                    $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                        $m++;
                                                    } else {
                                                        $n++;
                                                        echo "失败";
                                                    }
                                                }
                                            }
                                        } else {//没有时间文件夹
                                            $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                            $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                            if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                                $m++;
                                            } else {
                                                $n++;
                                                echo "失败";
                                            }
                                        }
                                    } else //
                                    {
                                        $bb = @ftp_mkdir($conn_id, $pathq);//创建公司营业执照证书的二级目录
                                        $cc2 = @ftp_chdir($conn_id, $pathq);//选择公司营业执照证书的二级目录
                                        $dd2 = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);//创建日期文件夹
                                        $remote_fileorg = $pathq . '/' . $uploadaddress . '/' . $remotefileorg;
                                        if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                            $m++;
                                        } else {
                                            $n++;
                                            echo "失败";
                                        }
                                    }
                                }
                                if ($m >= 2 && $n == 0) {
                                    $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
                                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                    $data = Json::decode($curl->put($url));
                                    if ($data['status'] == 1) {
                                        SystemLog::addLog($data['data']['msg']);
                                        return Json::encode(['msg' => "申请成功", "flag" => 1, "url" => Url::to(['/crm/crm-customer-apply/view', 'id' => $id])]);
                                    } else {
                                        return Json::encode(['msg' => json::encode($data)."发生未知错误，保存失败", "flag" => 0]);
                                    }
                                }
//                                dumpE('m' . $m);
                            }

                        } else //没有bslcns、txrg、txqlf文件夹
                        {
                            if ($postData['CrmC']['crtf_type'] == 0) {
                                $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                                $cc = @ftp_chdir($conn_id, $father);//选择cmp文件夹
                                $bb1 = @ftp_mkdir($conn_id, $pathlcn);//创建bslcns文件夹
                                $bb2 = @ftp_mkdir($conn_id, $pathreg);//创建txrg文件夹
                                $bb3 = @ftp_mkdir($conn_id, $pathqlf);//创建txqlf文件夹
                                $cc1 = @ftp_chdir($conn_id, $bb1);//选择bslcns文件夹
                                $dd = @ftp_mkdir($conn_id, $bb1 . '/' . $uploadaddress);//创建日期文件夹
                                $cc2 = @ftp_chdir($conn_id, $bb2);//选择bslcns文件夹
                                $dd1 = @ftp_mkdir($conn_id, $bb2 . '/' . $uploadaddress);//创建日期文件夹
                                $cc3 = @ftp_chdir($conn_id, $bb3);//选择bslcns文件夹
                                $dd2 = @ftp_mkdir($conn_id, $bb3 . '/' . $uploadaddress);//创建日期文件夹
                                $remote_filelics = $dd . '/' . $remotefilelic;
                                $remote_filetaxs = $dd1 . '/' . $remotefiletax;
                                $remote_fileorgs = $dd2 . '/' . $remotefileorg;
                                if (ftp_put($conn_id, $remote_filelics, $filelic, FTP_BINARY)
                                    && ftp_put($conn_id, $remote_fileorgs, $fileorg, FTP_BINARY)
                                    && ftp_put($conn_id, $remote_filetaxs, $filetax, FTP_BINARY)
                                ) {
                                    $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
                                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                    $data = Json::decode($curl->put($url));
                                    if ($data['status'] == 1) {
                                        SystemLog::addLog($data['data']['msg']);
                                        return Json::encode(['msg' => "申请成功", "flag" => 1, "url" => Url::to(['/crm/crm-customer-apply/view', 'id' => $id])]);
                                    } else {
                                        return Json::encode(['msg' => json::encode($data)."发生未知错误，保存失败", "flag" => 0]);
                                    }
                                } else {
                                    echo "失败";
                                }
                            } else {
                                $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                                $cc = @ftp_chdir($conn_id, $father);//选择cmp文件夹
                                $bb1 = @ftp_mkdir($conn_id, $pathlcn);//创建bslcns文件夹
                                $bb3 = @ftp_mkdir($conn_id, $pathqlf);//创建txqlf文件夹
                                $cc1 = @ftp_chdir($conn_id, $bb1);//选择bslcns文件夹
                                $dd = @ftp_mkdir($conn_id, $bb1 . '/' . $uploadaddress);//创建日期文件夹
                                $cc3 = @ftp_chdir($conn_id, $bb3);//选择bslcns文件夹
                                $dd3 = @ftp_mkdir($conn_id, $bb3 . '/' . $uploadaddress);//创建日期文件夹
                                $remote_filelics1 = $dd . '/' . $remotefilelic;
                                $remote_fileorgs1 = $dd3 . '/' . $remotefileorg;
                                if (ftp_put($conn_id, $remote_filelics1, $filelic, FTP_BINARY)
                                    && ftp_put($conn_id, $remote_fileorgs1, $fileorg, FTP_BINARY)
                                ) {
                                    $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
                                    $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                    $data = Json::decode($curl->put($url));
                                    if ($data['status'] == 1) {
                                        SystemLog::addLog($data['data']['msg']);
                                        return Json::encode(['msg' => "申请成功", "flag" => 1, "url" => Url::to(['/crm/crm-customer-apply/view', 'id' => $id])]);
                                    } else {
                                        return Json::encode(['msg' => json::encode($data)."发生未知错误，保存失败", "flag" => 0]);
                                    }
                                } else {
                                    echo "失败";
                                }
                            }
                        }
                    } else {
                        echo '请先登录ftp';
                    }
                    ftp_close($conn_id);
                }
//                if ($data['status'] == 1) {
//                    SystemLog::addLog($data['data']['msg']);
//                    return Json::encode(['msg' => "申请成功", "flag" => 1, "url" => Url::to(['/crm/crm-customer-apply/view','id'=>$id])]);
//                } else {
//                    return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
//                }
            } else {
                return Json::encode(['msg' => "请勿重复保存", "flag" => 0]);
            }

        } else {
            $url = $this->findApiUrl() . "hr/staff/view?id=" . Yii::$app->user->identity->staff_id;
            $data = Json::decode($this->findCurl()->get($url));
            $staffname = $data['staff_name'];
            $url = $this->findApiUrl() . $this->_url . "is-supper?id=" . Yii::$app->user->identity->getId();
            $result = Json::decode($this->findCurl()->get($url));
            $model = $this->getCusInfo($id);//客户信息
            $caModel = $this->actionApplyInfo($id);//编码申请信息
            $districtId2 = $model['cust_district_2'];
            $districtId3 = $model['cust_district_3'];
            $districtAll2 = $this->getAllDistrict($districtId2);
            $districtAll3 = $this->getAllDistrict($districtId3);
            $downList = $this->getDownList();
            $district = $this->getDistrict();
            $salearea = $this->getSalearea();
            $country = $this->getCountry();
            $crmcertf = $this->getCrmCertf($id);
//           dumpE($crmcertf);
            $industryType = $this->getIndustryType();
//            $keyValue = $key;//页面开关
            return $this->render("update", [
                'status' => $status,
                'result' => $result,
                'staffname' => $staffname,
                'model' => $model,
                'caModel' => $caModel,
                'downList' => $downList,
                'district' => $district,
                'salearea' => $salearea,
                'country' => $country,
                'industryType' => $industryType,
                'districtAll2' => $districtAll2,
                'districtAll3' => $districtAll3,
                'crmcertf' => $crmcertf
            ]);
        }
    }

    public function getApply($id)
    {
        $url = $this->findApiUrl() . $this->_url . "apply?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * @return string
     * 保存客戶信息及申請編碼
     */
    public function actionCreateCustomer()
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $custId = $postData['CrmCustomerInfo']['cust_id'];
            $postData['CrmCustomerInfo']['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['CrmCustomerInfo']['company_id'] = Yii::$app->user->identity->company_id;
            $postData['CrmCustomerApply']['applyperson'] = Yii::$app->user->identity->staff_id;
            $postData['CrmCustomerApply']['applydep'] = Yii::$app->user->identity->staff->organization_code;
            $postData['CrmCustomerApply']['company_id'] = Yii::$app->user->identity->company_id;
            $postData['CrmCustomerApply']['is_delete'] = 10; //新增状态
            $postData['CrmCustomerApply']['status'] = 20;    //待审核状态
            $licnameA = date('Y-m-d H:i:s');//获取当前时间
            $licnameA = str_replace(':', '', $licnameA);
            $licnameB = rand(0, 999);//获取0-999的随机数
            $licnameD = rand(1000, 1999);//获取0-999的随机数
            $licnameE = rand(2000, 2999);//获取0-999的随机数
            $licnameC = pathinfo($postData['CrmC']['o_license'], PATHINFO_EXTENSION);
            $licnameF = pathinfo($postData['CrmC']['o_reg'], PATHINFO_EXTENSION);
            $licnameG = pathinfo($postData['CrmC']['o_cerft'], PATHINFO_EXTENSION);
            $remotefilelic = $licnameA . $licnameB . '.' . $licnameC;//公司营业执照证和三证合一新文件名
            $remotefiletax = $licnameA . $licnameD . '.' . $licnameF;//税务登记证新文件名
            $remotefileorg = $licnameA . $licnameE . '.' . $licnameG;//一般纳税人资格证新文件名
            $postData['CrmC']['bs_license'] = $remotefilelic;//公司营业执照证和三证合一新文件名
            $postData['CrmC']['tx_reg'] = $remotefiletax;//税务登记证新文件名
            $postData['CrmC']['qlf_certf'] = $remotefileorg;//一般纳税人资格证新文件名

//            $url = $this->findApiUrl() . $this->_url . "create-customer";
//            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
//            $data = Json::decode($curl->post($url));
            //dumpE($data);
            if (is_uploaded_file($_FILES['upfiles-lic']['tmp_name']) ||
                is_uploaded_file($_FILES['upfiles-tax']['tmp_name']) ||
                is_uploaded_file($_FILES['upfiles-org']['tmp_name'])
            ) {
                $ftp_server = Yii::$app->ftpPath['ftpIP'];
                $port = Yii::$app->ftpPath['port'];
                if($port=="" ||$port==null)
                {
                    $port='0';
                }
                $ftp_user_name = Yii::$app->ftpPath['ftpUser'];
                $ftp_user_pass = Yii::$app->ftpPath['ftpPwd'];
                $father = Yii::$app->ftpPath['CMP']['father'];
                $pathlcn = Yii::$app->ftpPath['CMP']['BsLcn'];
                $pathreg = Yii::$app->ftpPath['CMP']['TaxReg'];
                $pathqlf = Yii::$app->ftpPath['CMP']['TaxQlf'];
                $years = substr(date('Y'), -2);//当前年份的后两位
                $month = date('m');
                $day = date('d');
                $uploadaddress = $years . $month . $day;
                //$upaddress=trim($_POST['gonghao']);
                $filelic = $_FILES['upfiles-lic']['tmp_name'];
                $filetax = $_FILES['upfiles-tax']['tmp_name'];
                $fileorg = $_FILES['upfiles-org']['tmp_name'];
                $conn_id = ftp_connect($ftp_server,$port) or die("Couldn't connect to $ftp_server");
                $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
                if ($login_result) {
                    $fa = ftp_nlist($conn_id, $father);
                    $reg = ftp_nlist($conn_id, $pathreg);
                    $qlf = ftp_nlist($conn_id, $pathqlf);
                    if ($fa != null)//有bslcns、 txrg  、txqlf其中一个文件夹
                    {
                        if ($postData['CrmC']['crtf_type'] == 0) {
                            //上传公司营业执照证
                            foreach ($fa as $lc) {
                                $pathl = $father . $pathlcn;//公司营业执照证书的二级目录
                                $pathr = $father . $pathreg;//税务登记证书的二级目录
                                $pathq = $father . $pathqlf;//一般纳税人资格证书的二级目录
//                                dumpE($lc.$pathr);
                                if ($lc == $pathl) //判断bslcns文件夹是否存在
                                {
                                    $lcnchild = ftp_nlist($conn_id, $pathl);
                                    if ($lcnchild != null) //有时间文件夹
                                    {
                                        foreach ($lcnchild as $ch) {
                                            $remote_filelic = $ch . '/' . $remotefilelic;
                                            if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                //dumpE("fgdaf000");
                                                $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                                $data = Json::decode($curl->post($url));
                                                $isApply = Yii::$app->request->get('is_apply');
                                                if ($data['status'] == 1) {
                                                    SystemLog::addLog($data['data']['msg']);
                                                    if (!empty($isApply)) {
                                                        return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                                    } else {
                                                        return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                                    }
                                                } else {
                                                    return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                                }
                                            } else {
                                                echo "失败";
                                            }
                                        }
                                    } else {//没有时间文件夹
                                        $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                        $remote_filelic = $bb . '/' . $remotefilelic;
                                        if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                            $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                            $data = Json::decode($curl->post($url));
                                            $isApply = Yii::$app->request->get('is_apply');
                                            if ($data['status'] == 1) {
                                                SystemLog::addLog($data['data']['msg']);
                                                if (!empty($isApply)) {
                                                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                                } else {
                                                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                                }
                                            } else {
                                                return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                            }
                                        } else {
                                            echo "失败";
                                        }
                                    }
                                } else //
                                {
                                    $bb = @ftp_mkdir($conn_id, $pathl);//创建公司营业执照证书的二级目录
                                    $cc = @ftp_chdir($conn_id, $pathl);//选择公司营业执照证书的二级目录
                                    $dd = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);//创建日期文件夹
                                    $remote_filelic = $dd . '/' . $remotefilelic;
                                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                        $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                        $data = Json::decode($curl->post($url));
                                        $isApply = Yii::$app->request->get('is_apply');
                                        if ($data['status'] == 1) {
                                            SystemLog::addLog($data['data']['msg']);
                                            if (!empty($isApply)) {
                                                return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                            } else {
                                                return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                            }
                                        } else {
                                            return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                        }
                                    } else {
                                        echo "失败";
                                    }
                                }
                                if ($lc == $pathr) //判断txrg文件夹是否存在
                                {
                                    $lcnchild = ftp_nlist($conn_id, $pathr);
                                    if ($lcnchild != null) //有时间文件夹
                                    {
                                        foreach ($lcnchild as $ch) {
                                            $remote_filetax = $ch . '/' . $remotefiletax;
                                            if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                                $data = Json::decode($curl->post($url));
                                                $isApply = Yii::$app->request->get('is_apply');
                                                if ($data['status'] == 1) {
                                                    SystemLog::addLog($data['data']['msg']);
                                                    if (!empty($isApply)) {
                                                        return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                                    } else {
                                                        return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                                    }
                                                } else {
                                                    return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                                }
                                            } else {
                                                echo "失败";
                                            }
                                        }
                                    } else {//没有时间文件夹
                                        $bb = @ftp_mkdir($conn_id, $pathr . '/' . $uploadaddress);
                                        $remote_filetax = $bb . '/' . $remotefiletax;
                                        if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                            $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                            $data = Json::decode($curl->post($url));
                                            $isApply = Yii::$app->request->get('is_apply');
                                            if ($data['status'] == 1) {
                                                SystemLog::addLog($data['data']['msg']);
                                                if (!empty($isApply)) {
                                                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                                } else {
                                                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                                }
                                            } else {
                                                return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                            }
                                        } else {
                                            echo "失败";
                                        }
                                    }
                                } else //
                                {
                                    $bb = @ftp_mkdir($conn_id, $pathr);//创建税务登记的二级目录
                                    $cc1 = @ftp_chdir($conn_id, $pathr);//选择税务登记的二级目录
                                    $dd1 = @ftp_mkdir($conn_id, $pathr . '/' . $uploadaddress);//创建日期文件夹
                                    $remote_filetax = $dd1 . '/' . $remotefiletax;
                                    if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                        $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                        $data = Json::decode($curl->post($url));
                                        $isApply = Yii::$app->request->get('is_apply');
                                        if ($data['status'] == 1) {
                                            SystemLog::addLog($data['data']['msg']);
                                            if (!empty($isApply)) {
                                                return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                            } else {
                                                return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                            }
                                        } else {
                                            return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                        }
                                    } else {
                                        echo "失败";
                                    }
                                }
                                if ($lc == $pathq) //判断txqlf文件夹是否存在
                                {
                                    $lcnchild = ftp_nlist($conn_id, $pathq);
                                    if ($lcnchild != null) //有时间文件夹
                                    {
                                        foreach ($lcnchild as $ch) {
                                            $remote_filetax = $ch . '/' . $remotefiletax;
                                            if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                                $data = Json::decode($curl->post($url));
                                                $isApply = Yii::$app->request->get('is_apply');
                                                //dumpE($data['status']);
                                                if ($data['status'] == 1) {
                                                    SystemLog::addLog($data['data']['msg']);
                                                    if (!empty($isApply)) {
                                                        return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                                    } else {
                                                        return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                                    }
                                                } else {
                                                    return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                                }
                                            } else {
                                                echo "失败";
                                            }
                                        }
                                    } else {//没有时间文件夹
                                        $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                        $remote_fileorg = $bb . '/' . $remotefiletax;
                                        if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                            $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                            $data = Json::decode($curl->post($url));
                                            $isApply = Yii::$app->request->get('is_apply');
                                            if ($data['status'] == 1) {
                                                SystemLog::addLog($data['data']['msg']);
                                                if (!empty($isApply)) {
                                                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                                } else {
                                                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                                }
                                            } else {
                                                return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                            }
                                        } else {
                                            echo "失败";
                                        }
                                    }
                                } else //
                                {
                                    $bb = @ftp_mkdir($conn_id, $pathq);//创建一般纳税人资格证的二级目录
                                    $cc2 = @ftp_chdir($conn_id, $pathq);//选择一般纳税人资格证的二级目录
                                    $dd2 = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);//创建日期文件夹
                                    $remote_fileorg = $dd2 . '/' . $remotefileorg;
                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                        $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                        $data = Json::decode($curl->post($url));
                                        $isApply = Yii::$app->request->get('is_apply');
                                        if ($data['status'] == 1) {
                                            SystemLog::addLog($data['data']['msg']);
                                            if (!empty($isApply)) {
                                                return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                            } else {
                                                return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                            }
                                        } else {
                                            return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                        }
                                    } else {
                                        echo "失败";
                                    }
                                }
                            }
                        } else {
                            //上传公司营业执照证
                            foreach ($fa as $lc) {
                                $pathl = $father . $pathlcn;//公司营业执照证书的二级目录
                                $pathq = $father . $pathqlf;//一般纳税人资格证书的二级目录
                                if ($lc == $pathl) //判断bslcns文件夹是否存在
                                {
                                    $lcnchild = ftp_nlist($conn_id, $pathl);
                                    if ($lcnchild != null) //有时间文件夹
                                    {
                                        foreach ($lcnchild as $ch) {
                                            $remote_filelic = $ch . '/' . $remotefilelic;
                                            if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                                $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                                $data = Json::decode($curl->post($url));
                                                $isApply = Yii::$app->request->get('is_apply');
                                                if ($data['status'] == 1) {
                                                    SystemLog::addLog($data['data']['msg']);
                                                    if (!empty($isApply)) {
                                                        return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                                    } else {
                                                        return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                                    }
                                                } else {
                                                    return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                                }
                                            } else {
                                                echo "失败";
                                            }
                                        }
                                    } else {//没有时间文件夹
                                        $bb = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);
                                        $remote_filelic = $bb . '/' . $remotefilelic;
                                        if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                            $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                            $data = Json::decode($curl->post($url));
                                            $isApply = Yii::$app->request->get('is_apply');
                                            if ($data['status'] == 1) {
                                                SystemLog::addLog($data['data']['msg']);
                                                if (!empty($isApply)) {
                                                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                                } else {
                                                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                                }
                                            } else {
                                                return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                            }
                                        } else {
                                            echo "失败";
                                        }
                                    }
                                } else //
                                {
                                    $bb = @ftp_mkdir($conn_id, $pathl);//创建公司营业执照证书的二级目录
                                    $cc = @ftp_chdir($conn_id, $pathl);//选择公司营业执照证书的二级目录
                                    $dd = @ftp_mkdir($conn_id, $pathl . '/' . $uploadaddress);//创建日期文件夹
                                    $remote_filelic = $dd . '/' . $remotefilelic;
                                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                        $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                        $data = Json::decode($curl->post($url));
                                        $isApply = Yii::$app->request->get('is_apply');
                                        if ($data['status'] == 1) {
                                            SystemLog::addLog($data['data']['msg']);
                                            if (!empty($isApply)) {
                                                return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                            } else {
                                                return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                            }
                                        } else {
                                            return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                        }
                                    } else {
                                        echo "失败";
                                    }
                                }
                                if ($lc == $pathq) //判断txqlf文件夹是否存在
                                {
                                    $lcnchild = ftp_nlist($conn_id, $pathq);
                                    if ($lcnchild != null) //有时间文件夹
                                    {
                                        foreach ($lcnchild as $ch) {
                                            $remote_filetax = $ch . '/' . $remotefiletax;
                                            if (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)) {
                                                $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                                $data = Json::decode($curl->post($url));
                                                $isApply = Yii::$app->request->get('is_apply');
                                                if ($data['status'] == 1) {
                                                    SystemLog::addLog($data['data']['msg']);
                                                    if (!empty($isApply)) {
                                                        return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                                    } else {
                                                        return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                                    }
                                                } else {
                                                    return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                                }
                                            } else {
                                                echo "失败";
                                            }
                                        }
                                    } else {//没有时间文件夹
                                        $bb = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);
                                        $remote_fileorg = $bb . '/' . $remotefileorg;
                                        if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                            $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                            $data = Json::decode($curl->post($url));
                                            $isApply = Yii::$app->request->get('is_apply');
                                            if ($data['status'] == 1) {
                                                SystemLog::addLog($data['data']['msg']);
                                                if (!empty($isApply)) {
                                                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                                } else {
                                                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                                }
                                            } else {
                                                return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                            }
                                        } else {
                                            echo "失败";
                                        }
                                    }
                                } else //
                                {
                                    $bb = @ftp_mkdir($conn_id, $pathq);//创建公司营业执照证书的二级目录
                                    $cc2 = @ftp_chdir($conn_id, $pathq);//选择公司营业执照证书的二级目录
                                    $dd2 = @ftp_mkdir($conn_id, $pathq . '/' . $uploadaddress);//创建日期文件夹
                                    $remote_fileorg = $dd2 . '/' . $remotefileorg;
                                    if (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY)) {
                                        $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                        $data = Json::decode($curl->post($url));
                                        $isApply = Yii::$app->request->get('is_apply');
                                        if ($data['status'] == 1) {
                                            SystemLog::addLog($data['data']['msg']);
                                            if (!empty($isApply)) {
                                                return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                            } else {
                                                return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                            }
                                        } else {
                                            return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                        }
                                    } else {
                                        echo "失败";
                                    }
                                }
                            }
                        }

                    } else //没有bslcns、txrg、txqlf文件夹
                    {
                        if ($postData['CrmC']['crtf_type'] == 0) {
                            $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                            $cc = @ftp_chdir($conn_id, $father);//选择cmp文件夹
                            $bb1 = @ftp_mkdir($conn_id, $pathlcn);//创建bslcns文件夹
                            $bb2 = @ftp_mkdir($conn_id, $pathreg);//创建txrg文件夹
                            $bb3 = @ftp_mkdir($conn_id, $pathqlf);//创建txqlf文件夹
                            $cc1 = @ftp_chdir($conn_id, $bb1);//选择bslcns文件夹
                            $dd = @ftp_mkdir($conn_id, $bb1 . '/' . $uploadaddress);//创建日期文件夹
                            $cc2 = @ftp_chdir($conn_id, $bb2);//选择bslcns文件夹
                            $dd1 = @ftp_mkdir($conn_id, $bb2 . '/' . $uploadaddress);//创建日期文件夹
                            $cc3 = @ftp_chdir($conn_id, $bb3);//选择bslcns文件夹
                            $dd2 = @ftp_mkdir($conn_id, $bb3 . '/' . $uploadaddress);//创建日期文件夹
                            $remote_filelics = $dd . '/' . $remotefilelic;
                            $remote_filetaxs = $dd1 . '/' . $remotefiletax;
                            $remote_fileorgs = $dd2 . '/' . $remotefileorg;
                            if (ftp_put($conn_id, $remote_filelics, $filelic, FTP_BINARY)
                                && ftp_put($conn_id, $remote_fileorgs, $fileorg, FTP_BINARY)
                                && ftp_put($conn_id, $remote_filetaxs, $filetax, FTP_BINARY)
                            ) {
                                $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                $data = Json::decode($curl->post($url));
                                $isApply = Yii::$app->request->get('is_apply');
                                if ($data['status'] == 1) {
                                    SystemLog::addLog($data['data']['msg']);
                                    if (!empty($isApply)) {
                                        return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                    } else {
                                        return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                    }
                                } else {
                                    return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                }
                            } else {
                                echo "失败";
                            }
                        } else {
                            $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                            $cc = @ftp_chdir($conn_id, $father);//选择cmp文件夹
                            $bb1 = @ftp_mkdir($conn_id, $pathlcn);//创建bslcns文件夹
                            $bb3 = @ftp_mkdir($conn_id, $pathqlf);//创建txqlf文件夹
                            $cc1 = @ftp_chdir($conn_id, $bb1);//选择bslcns文件夹
                            $dd = @ftp_mkdir($conn_id, $bb1 . '/' . $uploadaddress);//创建日期文件夹
                            $cc3 = @ftp_chdir($conn_id, $bb3);//选择bslcns文件夹
                            $dd3 = @ftp_mkdir($conn_id, $bb3 . '/' . $uploadaddress);//创建日期文件夹
                            $remote_filelics1 = $dd . '/' . $remotefilelic;
                            $remote_fileorgs1 = $dd3 . '/' . $remotefileorg;
                            if (ftp_put($conn_id, $remote_filelics1, $filelic, FTP_BINARY)
                                && ftp_put($conn_id, $remote_fileorgs1, $fileorg, FTP_BINARY)
                            ) {
                                $url = $this->findApiUrl() . $this->_url . "create-customer?id=" . $custId;
                                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
                                $data = Json::decode($curl->post($url));
                                $isApply = Yii::$app->request->get('is_apply');
                                if ($data['status'] == 1) {
                                    SystemLog::addLog($data['data']['msg']);
                                    if (!empty($isApply)) {
                                        return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $custId, 'is_apply' => 1])]);
                                    } else {
                                        return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
                                    }
                                } else {
                                    return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
                                }
                            } else {
                                echo "失败";
                            }
                        }
                    }
                } else {
                    echo '请先登录ftp';
                }
                ftp_close($conn_id);
            }
//            $isApply = Yii::$app->request->get('is_apply');
//            if ($data['status'] == 1) {
//                SystemLog::addLog($data['data']['msg']);
//                if (!empty($isApply)) {
//                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['/crm/crm-customer-apply/view', 'id' => $data['data']['id'], 'is_apply' => 1])]);
//                } else {
//                    return Json::encode(['msg' => "申请成功", "flag" => 1, "url" => Url::to(['/crm/crm-customer-apply/view', 'id' => $data['data']['id']])]);
//                }
//            } else {
//                return Json::encode(['msg' => "发生未知错误，申请失败", "flag" => 0]);
//            }
        } else {
            $downList = $this->getDownList();
            $district = $this->getDistrict();
            return $this->render("create", [
                'downList' => $downList,
                'district' => $district,
            ]);
        }
    }

    //選擇已有公司信息
    public function actionSelectCom()
    {
        $url = $this->findApiUrl() . $this->_url . "select-coms?companyId=" . Yii::$app->user->identity->company_id . "&managerId=" . Yii::$app->user->identity->staff_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }
        return $this->renderAjax('select-com', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAddCustomer()
    {
        $this->layout = '@app/views/layouts/ajax';
        return $this->render('create', [
//            'downList' => $downList,
        ]);
    }

    /**
     * @param $id
     * @return string
     * 客户详情
     */
    public function actionView($id,$iss=null)
    {
        $model = $this->getModel($id);
        $capplyInfo = $this->getApply($id);
        $capplyId = $capplyInfo['capply_id'];
        $capplyStatus = $capplyInfo['status'];
        $typeId = BsBusinessType::find()->select('business_type_id')->where(['business_code' => 'khbmsh'])->asArray()->one();
        $typeId = $typeId['business_type_id'];
        $verify = $this->getVerify($capplyId, $typeId);//審核信息
        $crmcertf = $this->getCrmCertf($id);
        $newnName1 = $crmcertf['bs_license'];
        $newnName1 = substr($newnName1, 2, 6);
        $newnName1 = str_replace('-', '', $newnName1);
        $newnName2 = $crmcertf['tx_reg'];
        $newnName2 = substr($newnName2, 2, 6);
        $newnName2 = str_replace('-', '', $newnName2);
        $newnName3 = $crmcertf['qlf_certf'];
        $newnName3 = substr($newnName3, 2, 6);
        $newnName3 = str_replace('-', '', $newnName3);
        $isApply = Yii::$app->request->get('is_apply');
        if($iss==null)
        {
            $iss=Yii::$app->request->get('status');
        }
        return $this->render('view', [
            'newnName1' => $newnName1,
            'newnName2' => $newnName2,
            'newnName3' => $newnName3,
            'iss'=>$iss,
            'model' => $model,
            'capplyId' => $capplyId,
            'status' => $capplyStatus,
            'capplyInfo' => $capplyInfo,
            'verify' => $verify,
            'crmcertf' => $crmcertf,
            'isApply' => $isApply,
            'typeId' => $typeId
        ]);
    }

    /**
     * 获取审核记录
     * @param $id
     * @param $type
     * @return mixed
     */
    public function getVerify($id, $type)
    {
        $url = $this->findApiUrl() . "/system/verify-record/find-verify?id=" . $id . "&type=" . $type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    /**
     * 送审
     */
    public function actionCheck($id)
    {
        $dataResult = $this->check($id);

        if ($dataResult) {
            return Json::encode(["msg" => "送审成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "送审失敗", "flag" => 0]);
        }
    }


    /**
     * 送审
     * @param $id
     */
    protected function check($id)
    {
        $data['id'] = $id;
        $data['type'] = 13;
        $data['staff'] = Yii::$app->user->identity->staff_id;
        //送审,审核流程
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
        $verifyUrl = $this->findApiUrl() . '/system/verify-record/verify-record';
        return Json::decode($curl->post($verifyUrl));
    }

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

    public function getCrmCertf($id)
    {
        $url = $this->findApiUrl() . $this->_url . "crm-certf?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        }
//        else {
//            throw new NotFoundHttpException('页面未找到');
//        }
    }

    /*根据地址五级获取全部信息*/
    public function getAllDistrict($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-info/get-all-district?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*根据地址五级查出所有信息*/
    public function actionGetAllDistrict($id)
    {
        $disId = BsDistrict::find()->where(['district_id' => $id])->one();
        $name = $disId['district_name'];
        $dis1 = BsDistrict::find()->where(['district_id' => $disId['district_pid']])->one();
        $dis2 = BsDistrict::find()->where(['district_id' => $dis1['district_pid']])->one();
        $dis3 = BsDistrict::find()->where(['district_id' => $dis2['district_pid']])->one();
        $dis4 = BsDistrict::find()->where(['district_id' => $dis3['district_pid']])->one();
//        $disName = $dis4['district_name'].$dis3['district_name'].$dis2['district_name'].$dis1['district_name'].$disId['district_name'];
        $arr = [$dis3, $dis2, $dis1, $disId];
        return $arr;
    }

    /**
     * @param $id
     * @return string
     * 删除客户信息
     */
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']['msg']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "删除失败", "flag" => 0]);
        }

    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 客户详情
     */
    private function getCusInfo($id)
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
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 申请详情
     */
    public function actionApplyInfo($id)
    {
        $url = $this->findApiUrl() . $this->_url . "apply?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    public function getCertf($id)
    {
        $url = $this->findApiUrl() . $this->_url . "certf?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*客户类型等下拉菜单*/
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*获取所在地区一级地址*/
    public function getDistrict()
    {
        $url = $this->findApiUrl() . $this->_url . "get-district";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*地区所属军区*/
    public function actionGetDistrictSalearea($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-district-salearea?id=" . $id;
        return $this->findCurl()->get($url);
    }

    /*营销范围*/
    public function getSalearea()
    {
        $url = $this->findApiUrl() . $this->_url . "salearea";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*国家*/
    public function getCountry()
    {
        $url = $this->findApiUrl() . $this->_url . "get-country";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*行业类别*/
    public function getIndustryType()
    {
        $url = $this->findApiUrl() . $this->_url . "industry-type";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*客户类型等下拉菜单*/
    public function getLevel()
    {
        $url = $this->findApiUrl() . $this->_url . "cust-level";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function uploadfile()
    {
        if (is_uploaded_file($_FILES['upfiles-lic']['tmp_name'])
//                is_uploaded_file($_FILES['upfiles-tax']['tmp_name'])||
//                is_uploaded_file($_FILES['upfiles-org']['tmp_name'])
        ) {
            $ftp_server = "10.134.100.164";
            $ftp_user_name = "ebs";
            $ftp_user_pass = "ebs2013";
            //$upaddress=trim($_POST['gonghao']);
            $filelic = $_FILES['upfiles-lic']['tmp_name'];
//                $filetax = $_FILES['upfiles-tax']['tmp_name'];
//                $fileorg = $_FILES['upfiles-org']['tmp_name'];
            $conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
            if ($login_result) {
                $a = ftp_nlist($conn_id, "/test/");
                if ($a != null) {
                    foreach ($a as $c) {
                        if ($c == '/test/') {
                            $remote_filelic = '/test/' . '/' . $_FILES['upfiles-lic']['name'];
//                                $remote_filetax = '/test/' . '/' . $_FILES['upfiles-tax']['name'];
//                                $remote_fileorg = '/test/' . '/' . $_FILES['upfiles-org']['name'];
//                            echo $remote_filelic . '<br/>';
//                                    .$remote_filetax.'<br/>'.$remote_fileorg.'<br/>';
                            if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                                echo "文件:" . $_FILES['upfiles-lic']['name'] . "上传成功";
                            }
//                                elseif (ftp_put($conn_id, $remote_filetax, $filelic, FTP_BINARY))
//                                {
//                                    echo "文件:" . $_FILES['upfiles-tax']['name'] . "上传成功";
//                                }
//                                elseif (ftp_put($conn_id, $remote_fileorg, $filelic, FTP_BINARY))
//                                {
//                                    echo "文件:" . $_FILES['upfiles-org']['name'] . "上传成功";
//                                }
                            else {
                                echo "上传失败";
                            }
                            return true;
                        } else {
                            $bb = @ftp_mkdir($conn_id, "/test/");
//                                $remote_file ="$bb".'/'.$_FILES['uploadfile']['name'];
                            $remote_filelic = '/test/' . '/' . $_FILES['upfiles-lic']['name'];
//                                $remote_filetax = '/test/' . '/' . $_FILES['upfiles-tax']['name'];
//                                $remote_fileorg = '/test/' . '/' . $_FILES['upfiles-org']['name'];
                            echo $remote_filelic . '<br/>';
//                                    .$remote_filetax.'<br/>'.$remote_fileorg.'<br/>';
                            if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)
//                                &&ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY)
                            ) {
                                echo "文件:" . $_FILES['upfiles-lic']['name'] . "上传成功";
                            }
//                                elseif (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY))
//                                {
//                                    echo "文件:" . $_FILES['upfiles-tax']['name'] . "上传成功";
//                                }
//                                elseif (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY))
//                                {
//                                    echo "文件:" . $_FILES['upfiles-org']['name'] . "上传成功";
//                                }
                            else {
                                echo "上传失败";
                            }
                            return true;
                        }
                    }
                } else {
                    $bb = @ftp_mkdir($conn_id, "/test/");
                    $remote_filelic = '/test/' . '/' . $_FILES['upfiles-lic']['name'];
//                        $remote_filetax = '/test/' . '/' . $_FILES['upfiles-tax']['name'];
//                        $remote_fileorg = '/test/' . '/' . $_FILES['upfiles-org']['name'];
                    echo $remote_filelic . '<br/>';
//                            .$remote_filetax.'<br/>'.$remote_fileorg.'<br/>';
                    if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
                        echo "文件:" . $_FILES['uploadfile']['name'] . "上传成功";
                    }
//                        elseif (ftp_put($conn_id, $remote_filetax, $filetax, FTP_BINARY))
//                        {
//                            echo "文件:" . $_FILES['upfiles-tax']['name'] . "上传成功";
//                        }
//                        elseif (ftp_put($conn_id, $remote_fileorg, $fileorg, FTP_BINARY))
//                        {
//                            echo "文件:" . $_FILES['upfiles-org']['name'] . "上传成功";
//                        }
                    else {
                        echo "上传失败";
                    }
                    return true;
                }
            } else {
                echo '请先登录ftp';
            }
            ftp_close($conn_id);
        }
    }

    //取消客户代码申请
    public function actionCannelCustApply($capply_id)
    {
        $url = $this->findApiUrl() . $this->_url . 'cannel-cust-apply?capply_id=' . $capply_id;
        $data = $this->findCurl()->get($url);
        return $data;
    }

    //取消客户代码申请
    public function actionCannel($capply_id, $remark = null)
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . 'cannel-apply?capply_id=' . $capply_id . "&remark=" . $remark;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->get($url));
            if ($data['status'] == 1) {
                SystemLog::addLog('取消代码申请');
                return Json::encode(['msg' => "取消成功", "flag" => 1,"url"=>Url::to('index')]);
            }
            return Json::encode(['msg' => $data['msg'], "flag" => 0]);
        }
        $this->layout = '@app/views/layouts/ajax';
        return $this->render('cannel', ['capply_id' => $capply_id]);
    }

    public function actionCannelApply($capply_id,$remark)
    {
        $post = Yii::$app->request->post();
        $url = $this->findApiUrl() . $this->_url . 'cannel-apply?capply_id=' . $capply_id . "&remark=" . $remark;
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $data = Json::decode($curl->get($url));
        if ($data['status'] == 1) {
            SystemLog::addLog('取消代码申请');
            return Json::encode(['msg' => "取消成功", "flag" => 1]);
        }
        return Json::encode(['msg' => $data['msg'], "flag" => 0]);

    }

    //审核完成时的保存
    public function actionSaveCustInfo()
    {
        $postData=Yii::$app->request->post();
        $custId = $postData['CrmCustomerInfo']['cust_id'];
        $url = $this->findApiUrl() . $this->_url . "save-cust-info?id=" . $custId;
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = Json::decode($curl->put($url));
        if ($data['status'] == 1) {
            SystemLog::addLog($data['data']['msg']);
                return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $custId])]);
        } else {
            return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
        }

    }
    //导出客户代码申请列表
    public function actionExport()
    {
        $url = $this->findApiUrl() . "hr/staff/view?id=" . Yii::$app->user->identity->staff_id;
        $data = Json::decode($this->findCurl()->get($url));
        $url = $this->findApiUrl() . $this->_url . "is-supper?id=" . Yii::$app->user->identity->getId();
        $result = Json::decode($this->findCurl()->get($url));
        $url = $this->findApiUrl() . $this->_url . "employee-info?staff_code=" . $data['staff_code'];
        $data0 = Json::decode($this->findCurl()->get($url));
        $staffName = $data["staff_name"];//$data["staff_name"]
        $queryParam = Yii::$app->request->queryParams;
        if ($queryParam['CrmCustomerApplySearch']['cust_type1'] == 1 || $result == true || $data0['isrule'] != 1) {
            $staffName0 = '';
        } else {
            $staffName0 = $staffName;
        }
        $url = $this->findApiUrl() . $this->_url . "index?companyId=" . Yii::$app->user->identity->company_id . "&staffName=" . $staffName0;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        \Yii::$app->controller->action->id = 'index';
        SystemLog::addLog('导出客户代码申请列表');
        @ini_set('max_execution_time', 600);
        @ini_set('memory_limit', '1024M');
        return $this->exportFiled($dataProvider['rows']);
    }
    //详情送审
    public function actionReviewer($type, $id, $url = null)
    {
        if ($post = Yii::$app->request->post()) {
            $post['id'] = $id;    //单据ID
            $post['type'] = $type;  //审核流类型
            $post['staff'] = Yii::$app->user->identity->staff_id;//送审人ID
            $verifyUrl = $this->findApiUrl() . "system/verify-record/verify-record";
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
        $urls = $this->findApiUrl() ."system/verify-record/reviewer?type=" . $type . '&staff_id=' . Yii::$app->user->identity->staff_id;
        $review = Json::decode($this->findCurl()->get($urls));
        return $this->renderAjax('reviewer', [
            'review' => $review,
        ]);
    }
}