<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/9/5
 * Time: 下午 04:30
 */
namespace app\widgets\ueditor;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

class Ueditor extends Widget{
    public $id='editor';
    public $name;
    public $width;
    public $height;
    public $content;
    public $config;
    public function init(){
        UeditorAsset::register($this->getView());
        if(empty($this->config['serverUrl'])){
            $this->config['serverUrl'] = Url::to(['/ueditor/index']);
        }
        $this->config["initialFrameWidth"]=$this->width;
        $this->config["initialFrameHeight"]=$this->height;
        $this->config["autoHeightEnabled"]=false;

        if (empty($this->config['toolbars'])) {
            //为了避免每次使用都输入乱七八糟的按钮，这里预先定义一些常用的编辑器按钮。
            //这是一个丑陋的二维数组
            $this->config['toolbars'] = [
                [
                    'fullscreen', 'source', 'undo', 'redo', '|',
                    'customstyle', 'paragraph', 'fontfamily', 'fontsize'
                ],
                [
                    'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat',
                    'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                    'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', '|',
                    'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                    'directionalityltr', 'directionalityrtl', 'indent', '|'
                ],
                [
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                    'link', 'unlink', '|','simpleupload',
                    'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'insertcode', 'pagebreak', '|',
                    'horizontal', 'inserttable', '|',
                    'print', 'preview', 'searchreplace', 'help'
                ]
            ];
        }
        parent::init();
    }
    public function run(){
        $id=$this->id;
        $instName="editor_".$this->id;
        $config=json_encode($this->config);
        $js=<<<JS
        var {$instName}=UE.getEditor('{$id}',{$config});
JS;
        $this->getView()->registerJs($js,View::POS_READY);
        return Html::textarea($this->name,Html::decode($this->content),[
            "id"=>$this->id,
            "style"=>"width:{$this->width}px;height:{$this->height}px;"
        ]);
    }

}