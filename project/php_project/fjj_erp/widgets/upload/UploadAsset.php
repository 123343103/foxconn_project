<?php

namespace app\widgets\upload;

use Yii;
use yii\web\AssetBundle;

/**
 * @author Xianan Huang <xianan_huang@163.com>
 */
class UploadAsset extends AssetBundle
{
    
    public $js = [
//    	'jquery-1.10.2.js',
        'webuploader.js',
        'upload.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
    
    /**
     * 初始化：sourcePath赋值
     * @see \yii\web\AssetBundle::init()
     */
    public function init()
    {
        $this->sourcePath = dirname(__FILE__).DIRECTORY_SEPARATOR . 'dist';
    }
}