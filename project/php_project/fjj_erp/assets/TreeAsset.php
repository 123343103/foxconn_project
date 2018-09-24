<?php
/**
 * Created by PhpStorm.
 * User: F3858995
 * Date: 2016/9/8
 * Time: 上午 09:47
 */
namespace app\assets;
class TreeAsset extends \yii\web\AssetBundle{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'treeview/dist/bootstrap-treeview.css',
    ];
    public $js = [
        'treeview/dist/bootstrap-treeview.min.js'
    ];
    public $depends = [
//        'yii\bootstrap\BootstrapAsset',
//        'app\assets\AppAsset',
    ];
}