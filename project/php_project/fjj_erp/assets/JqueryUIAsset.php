<?php
/**
 * Created by PhpStorm.
 * User: F3858995
 * Date: 2016/9/8
 * Time: 上午 09:47
 */
namespace app\assets;
class JqueryUIAsset extends \yii\web\AssetBundle{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/myMenu.css',
        'css/easydropdown.css',
        'css/fancybox/jquery.fancybox.css',
        'easyui/themes/bootstrap/easyui.css',
    ];
    public $js = [
        'layer/layer.js',
        'js/layer.config.js',
        'js/jquery-ui.min.js',
        'js/jquery.myMenu.js',
        'js/jquery.easydropdown.min.js',
        'js/jquery.fancybox.js',
        'js/jquery.form.js',
        'js/xlsx.full.min.js',
        'js/address/distpicker.data.min.js',
        'js/address/distpicker.min.js',
        'js/jquery.myFunction.js',
        'js/jquery.jqprint-0.3.js',
        'easyui/jquery.easyui.min.js',
        'easyui/jquery.easyui.ext.js',
        "easyui/locale/easyui-lang-zh_CN.js",
        "js/validator.js",
    ];
    public $depends = [
        'app\assets\AppAsset'
    ];

}