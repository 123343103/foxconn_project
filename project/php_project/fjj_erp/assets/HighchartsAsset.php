<?php

namespace app\assets;
class HighchartsAsset extends \yii\web\AssetBundle{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'highcharts/css/highcharts.css'
    ];
    public $js = [
        'highcharts/js/highcharts.js',
//        'highcharts/modules/stock.js',
    ];
    public $depends = [
        'app\assets\JqueryUIAsset'
    ];
}