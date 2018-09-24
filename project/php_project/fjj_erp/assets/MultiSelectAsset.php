<?php
namespace app\assets;

use yii\web\AssetBundle;
/**
 * 多選
 *F3858995
 * 2016/10/7
 */
class MultiSelectAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'multi-select/css/multi-select.css'
    ];
    public $js = [
        'multi-select/js/jquery.multi-select.js',
        'multi-select/js/quicksearch.js'
    ];
    public $depends = [
        'app\assets\JqueryUIAsset'
    ];
}