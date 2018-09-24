<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/9/13
 * Time: 上午 08:37
 */

namespace app\widgets\ueditor;
use yii\web\Controller;

header("Content-Type: text/html; charset=utf-8");

class UeditorController extends Controller
{
    public $config=[];

    public function init(){
        parent::init();
        \Yii::$app->request->enableCsrfValidation=false;
        if(file_exists(__DIR__ . '/config.json')){
            $this->config=json_decode(preg_replace("/\/\*[\s\S]+?\*\//", '', file_get_contents(__DIR__ . '/config.json')), true);
        }
    }

    public function actionIndex(){
        $action=\Yii::$app->request->get("action","config");
        switch($action){
            case "config":
                return json_encode($this->config);
                break;
            case "uploadimage":
                return $this->uploadImage();
                break;
            case "uploadscrawl":
                return $this->uploadScrawl();
                break;
            case "uploadvideo":
                return $this->uploadVideo();
                break;
            case "uploadfile":
                return $this->uploadFile();
                break;
            case "listimage":
                return $this->listImage();
                break;
            case "listfile":
                return $this->listFile();
                break;
            case "catchimage":
                return $this->catchImage();
                break;
            default:
                break;

        }
    }

    private function uploadImage(){
        $base64 = "upload";
        $config = array(
            "pathFormat" => $this->config['imagePathFormat'],
            "maxSize" => $this->config['imageMaxSize'],
            "allowFiles" => $this->config['imageAllowFiles']
        );
        $fieldName = $this->config['imageFieldName'];
        $up = new FtpUploader($fieldName, $config, $base64);
        return json_encode($up->getFileInfo());
    }

    private function uploadScrawl(){
        $base64 = "upload";
        $config = array(
            "pathFormat" => $this->config['scrawlPathFormat'],
            "maxSize" => $this->config['scrawlMaxSize'],
            "oriName" => "scrawl.png"
        );
        $fieldName = $this->config['scrawlFieldName'];
        $base64 = "base64";
        $up = new FtpUploader($fieldName, $config, $base64);
        return json_encode($up->getFileInfo());
    }

    private function uploadVideo(){
        $base64 = "upload";
        $config = array(
            "pathFormat" => $this->config['videoPathFormat'],
            "maxSize" => $this->config['videoMaxSize'],
            "allowFiles" => $this->config['videoAllowFiles']
        );
        $fieldName = $this->config['videoFieldName'];
        $up = new FtpUploader($fieldName, $config, $base64);
        return json_encode($up->getFileInfo());
    }

    private function uploadFile(){
        $base64 = "upload";
        $config = array(
            "pathFormat" => $this->config['filePathFormat'],
            "maxSize" => $this->config['fileMaxSize'],
            "allowFiles" => $this->config['fileAllowFiles']
        );
        $fieldName = $this->config['fileFieldName'];
        $up = new FtpUploader($fieldName, $config, $base64);
        return json_encode($up->getFileInfo());
    }

    private function listImage(){
        $allowFiles = $this->config['imageManagerAllowFiles'];
        $listSize = $this->config['imageManagerListSize'];
        $path = $this->config['imageManagerListPath'];

        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $end = $start + $size;

        $ftp=new Ftp();
        $allowFiles=explode("|",$allowFiles);
        $files = $ftp->matchFiles($path,$allowFiles);
        if (!count($files)) {
            return json_encode(array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => count($files)
            ));
        }

        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
            $list[] = $files[$i];
        }

        $result = json_encode(array(
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => count($files)
        ));

        return $result;

    }

    private function listFile(){
        $allowFiles = $this->config['fileManagerAllowFiles'];
        $listSize = $this->config['fileManagerListSize'];
        $path = $this->config['fileManagerListPath'];

        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $end = $start + $size;

        $ftp=new Ftp();
        $allowFiles=explode("|",$allowFiles);
        $files = $ftp->matchFiles($path,$allowFiles);
        if (!count($files)) {
            return json_encode(array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => count($files)
            ));
        }

        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
            $list[] = $files[$i];
        }

        $result = json_encode(array(
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => count($files)
        ));

        return $result;
    }

    private function catchImage(){
        $config = array(
            "pathFormat" => $this->config['catcherPathFormat'],
            "maxSize" => $this->config['catcherMaxSize'],
            "allowFiles" => $this->config['catcherAllowFiles'],
            "oriName" => "remote.png"
        );
        $fieldName = $this->config['catcherFieldName'];
        /* 抓取远程图片 */
        $list = array();
        if (isset($_POST[$fieldName])) {
            $source = $_POST[$fieldName];
        } else {
            $source = $_GET[$fieldName];
        }
        foreach ($source as $imgUrl) {
            $item = new FtpUploader($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            array_push($list, array(
                "state" => $info["state"],
                "url" => $info["url"],
                "size" => $info["size"],
                "title" => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source" => htmlspecialchars($imgUrl)
            ));
        }

        /* 返回抓取数据 */
        return json_encode(array(
            'state'=> count($list) ? 'SUCCESS':'ERROR',
            'list'=> $list
        ));
    }
}