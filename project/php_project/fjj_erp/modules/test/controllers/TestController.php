<?php
/**
 *staff控制器
 * User: F3859386
 * Date: 2016/9/12
 * Time: 上午 11:38
 */
namespace app\modules\test\controllers;
use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use app\controllers\BaseController;
use yii\helpers\Json;

/**
 * StaffController implements the CRUD actions for Staff model.
 */
class TestController extends BaseController
{
    private $_url = "test/test/";  //对应api控制器URL

    public function actionIndex($namenew=null,$nameold=null)
    {
        if (Yii::$app->request->getIsPost()) {

            $postData = Yii::$app->request->post();
            $licnameA = date('Ymd H:i:s');//获取当前时间
            $licnameA = str_replace(':', '', $licnameA);
            $licnameA = str_replace(' ', '', $licnameA);
            $licnameB = rand(0, 999);//获取0-999的随机数
            $licnameD = rand(1000, 1999);//获取1000-1999的随机数
            $licnameE = rand(2000, 2999);//获取2000-2999的随机数
            $licnameC = pathinfo($postData['license_name'], PATHINFO_EXTENSION);
            $remotefilelic = $licnameA . $licnameB . '.' . $licnameC;//公司营业执照证和三证合一新文件名

            if (is_uploaded_file($_FILES['upfiles-lic']['tmp_name'])) {
                $ftp_server = Yii::$app->ftpPath['ftpIP'];
                $port=Yii::$app->ftpPath['port'];
                if($port=="" ||$port==null)
                {
                    $port='0';
                }
                $ftp_user_name = Yii::$app->ftpPath['ftpUser'];
                $ftp_user_pass = Yii::$app->ftpPath['ftpPwd'];
                $father = "/test/";
                $filelic = $_FILES['upfiles-lic']['tmp_name'];
                $conn_id = ftp_connect($ftp_server,$port) or die("Couldn't connect to $ftp_server");
                $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
                if ($login_result) {
                    $fa = ftp_nlist($conn_id, $father);
                    if ($fa != null) {
                        $remote_filelic = $father . $remotefilelic;
                        if (ftp_put($conn_id, $remote_filelic, $filelic, FTP_BINARY)) {
//                            return $this->render('index',[
//                                'result'=>$remotefilelic
//                            ]);
                            return Json::encode(['msg' => "上传成功", "flag" => 1, "url" => Url::to(['index', 'namenew' => $remotefilelic,'nameold'=>$postData['license_name']])]);
                            //return "上传成功";
                        } else {
                            echo "失败";
                        }
                    } else {
                        $bb = @ftp_mkdir($conn_id, $father);//创建cmp文件夹
                        $remote_filelics = $bb . '/' . $remotefilelic;
                        if (ftp_put($conn_id, $remote_filelics, $filelic, FTP_BINARY)) {
                            return Json::encode(['msg' => "上传成功", "flag" => 1, "url" => Url::to(['index', 'namenew' => $remotefilelic,'nameold'=>$postData['license_name']])]);
                            //return "上传成功";
//                                    return Json::encode(['msg' => "上传成功", "flag" => 1]);
                        } else {
                            return "失败";
                        }
                    }
                } else {
                    echo '请先登录ftp';
                }
                ftp_close($conn_id);
            }
        }
        else{
            return $this->render('index',[
                'namenew'=>$namenew,
                'nameold'=>$nameold
            ]);
        }
    }
}
