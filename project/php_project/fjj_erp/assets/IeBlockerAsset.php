<?php
/**
 * Date: 2017/1/17
 * Time: 15:28
 */
namespace app\assets;
class IeBlockerAsset extends \yii\web\AssetBundle{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'ie-blocker/ie-blocker.css',
    ];
    public $js = [
        'ie-blocker/ie-blocker.zhCN.js',
    ];
    public $jsOptions = [ 'condition' => 'lte IE 8' ];
    public $cssOptions = [ 'condition' => 'lte IE 8' ];
}