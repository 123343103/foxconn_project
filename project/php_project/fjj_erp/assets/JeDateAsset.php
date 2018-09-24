<?php
/**
 * User: F1677929
 * Date: 2017/9/30
 */
namespace app\assets;
use yii\web\AssetBundle;
/**
 * 日期时间资源类
 */
class JeDateAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'jedate/skin/jedate.css'
    ];
    public $js = [
//        'jedate/jquery.jedate.js',
//        'jedate/my.jedate.js'
        'My97DatePicker/WdatePicker.js'
    ];
    public $depends = [
        'app\assets\JqueryUIAsset'
    ];
}