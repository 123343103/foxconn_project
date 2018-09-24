<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/9/1
 * Time: 上午 11:08
 */
namespace app\widgets;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

class RealTimeSearch extends Widget{
    public $name;
    public $value;
    public $options;
    public $url;
    public $select=true;
    public $delay=1000;
    public function init(){
        $params=\Yii::$app->request->get();
        if(isset($params[$this->name])){
            $this->value=$params[$this->name];
        }
        $this->options['autocomplete']="off";
        $this->options['style']=$this->options['style'].';outline:none;';
    }
    public function run(){
        $html=Html::beginTag("div",["class"=>"real-search-box","style"=>"position: relative;display: inline-block;"]);
        $html.=Html::input("text",$this->name,$this->value,$this->options);
        $html.=Html::ul([],["class"=>"rec-list","style"=>"display:none;position:absolute;background: #fff;z-index:1000;border:#ccc 1px solid;margin-top:-1px;left:0px;right:0px;padding:10px;width:auto;min-height:100px;max-height:300px;overflow-x:hidden;overflow-y:auto;"]);
        $html.=Html::endTag("div");
        $this->getView()->registerJs("
            $('.real-search-box').click(function(){
                event.stopPropagation();
                if(event.target.nodeName=='LI'){
                    if('{$this->select}'==1){
                     $(this).find('input').val($(event.target).text());   
                    }
                }
            });
            $('.real-search-box input').focus(function(){
                    $(this).next('.rec-list').show();
             });
            $('.real-search-box input').keyup(function(){
                var _this=$(this);
                setTimeout(function(){
                    $.ajax({
                        type:'get',
                        url:'".$this->url."',
                        async:false,
                        data:{
                            val:_this.val(),
                        },
                        dataType:'json',
                        success:function(res){
                            _this.next().html('');
                            res.forEach(function(row,index){
                                _this.next().append($('<li>'+row.name+'</li>').css({'line-height':'20px','white-space':'nowrap','overflow':'hidden'}))
                            });
                        }
                    });
                },".$this->delay.");
             });
             $('body').click(function(){
                $('.real-search-box>.rec-list').hide();
             });
         ");
        return $html;
    }
}