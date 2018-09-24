<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2018/3/13
 * Time: 14:31
 */
namespace app\assets;
use yii\web\AssetBundle;
/**
 * 日期时间资源类
 */
class UploadAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/dist/webuploader.css'
    ];
    public $js = [
//        'jedate/jquery.jedate.js',
//        'jedate/my.jedate.js'
        'js/dist/webuploader.js'
    ];
    public $depends = [
        'app\assets\JqueryUIAsset'
    ];
}