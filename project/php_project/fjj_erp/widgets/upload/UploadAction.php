<?php
namespace app\widgets\upload;
use yii\base\Action;
use app\widgets\upload\Uploader;
error_reporting(E_ERROR);
class UploadAction extends Action{
    public $scene="defaults";
    public $allowRatio="";
	public function init(){
		\Yii::$app->request->enableCsrfValidation = false;
	}
	public function run(){
		$config = array(
		    "pathFormat" =>"{$this->scene}/{yy}{mm}{dd}/{yyyy}{mm}{dd}{rand:10}",
		    "maxSize" => 51200000,
		    "allowFiles" =>[
		        ".png", ".jpg", ".jpeg", ".gif",".zip",".pdf",".xlsx",".xls"
		    ],
            "allowRatio"=>$this->allowRatio
		);

		/* 生成上传实例对象并完成上传 */
//		$up = new Uploader("file", $config,"file");
        try{
            $up = new FtpUploader("file", $config,"file");
            return json_encode($up->getFileInfo());
        }catch (\Exception $e){
            return json_encode($e->getMessage());
        }

		/* 返回数据 */
	}
}
?>