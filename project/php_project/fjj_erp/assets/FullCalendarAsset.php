<?php
/**
 * User: F1677929
 * Date: 2017/10/9
 */
namespace app\assets;
use yii\web\AssetBundle;
/**
 * 行事历资源类
 */
class FullCalendarAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'fullcalendar/fullcalendar.min.css'
    ];
    public $js = [
        'fullcalendar/lib/moment.min.js',
        'fullcalendar/fullcalendar.js',
        'fullcalendar/locale/zh-cn.js'
    ];
    public $depends = [
        'app\assets\JqueryUIAsset'
    ];
}