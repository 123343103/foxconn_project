<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/9/5
 * Time: 下午 04:32
 */
namespace app\widgets\ueditor;
use yii\web\AssetBundle;
use yii\web\View;

class UeditorAsset extends AssetBundle{
    public $js=[
        "ueditor.config.js",
        "ueditor.all.min.js",
        "lang/zh-cn/zh-cn.js"
    ];

    public $css=[
        "themes/default/css/ueditor.css"
    ];

    public $cssOptions=["position"=>View::POS_LOAD];

    public $publishOptions = [
        'except' => [
            'php/',
            'index.html',
            '.gitignore'
        ]
    ];

    public function init()
    {
        $this->sourcePath = dirname(__FILE__).DIRECTORY_SEPARATOR."dist";
    }
}