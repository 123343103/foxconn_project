<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
* UploadForm is the model behind the upload form.
*/
class Upload extends Model
{
    /**
    * @var UploadedFile|Null file attribute
    */
    public $file;
    public $custAttach;
    public $sellerAttach;
    public $message;
    /**
    * @return array the validation rules.
    */
    public function rules()
    {
        return [
//               ['file'], 'file'],
                [['file'], 'file', 'maxFiles' => 10 ,'extensions' => 'xls,xlsx,doc,docx,txt,png,jpg,tif,pdf,zip,7z,rar','skipOnEmpty' => false, 'checkExtensionByMimeType' => true],
                [['message'], 'file', 'maxFiles' => 10 ,'extensions' => 'xls,xlsx,doc,docx,txt,png,jpg,tif,pdf,zip,7z,rar','skipOnEmpty' => false, 'checkExtensionByMimeType' => true],
                [['custAttach'], 'file', 'maxFiles' => 10 ,'extensions' => 'xls,xlsx,doc,docx,txt,png,jpg,tif,pdf,zip,7z,rar','skipOnEmpty' => false, 'checkExtensionByMimeType' => false],
                [['sellerAttach'], 'file', 'maxFiles' => 10 ,'extensions' => 'xls,xlsx,doc,docx,txt,png,jpg,tif,pdf,zip,7z,rar','skipOnEmpty' => false, 'checkExtensionByMimeType' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file'=>'多文件上传'
        ];
    }
}