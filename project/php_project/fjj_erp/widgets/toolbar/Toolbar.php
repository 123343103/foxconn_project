<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/8/16
 * Time: 上午 11:08
 */
namespace app\widgets\toolbar;
use app\classes\Menu;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;


class Toolbar extends Widget{

    public $title;
    public $menus;
    public function run(){
        $js=<<<JS
        
        $(function(){
            $(".table-nav").each(function(){
                var submenu=$(this).data("menu");
                if(typeof submenu!="undefined"){
                    $(this).menubutton({
                        menu:submenu,
                        hasDownArrow:false
                    });    
                }
            });
        });

        function visible(grid){
                var scene;
               var selected=$(grid).datagrid("getSelections");
               var checked=$(grid).datagrid("getChecked");
               if(selected.length==0){
                    if(checked.length==0){
                        scene=0;
                    }else if(checked.length==1){
                        scene=1;
                    }else{
                        scene=2;
                    }
               }else{
                   if(checked.length==1){
                       scene=1;
                   }else{
                       scene=2;
                   }
               }
               
               $(".table-nav").each(function(){
                   var target=$(this).parent();
                   if(typeof target.attr("when")!="undefined"){
                       var scenes=target.attr("when").split(",");
                       var flag=false;
                       for(var x=0;x<scenes.length;x++){
                           if(scenes[x]==scene){
                               flag=true;
                               break;
                           }
                       }
                       if(flag){
                        target.show();
                       }else{
                           target.hide();
                       }
                   }else{
                       target.show();
                   }
               });
               
               $(".menu-item a").each(function(){
                   var target=$(this);
                   if(typeof target.attr("when")!="undefined"){
                       var scenes=target.attr("when").split(",");
                       var flag=false;
                       for(var x=0;x<scenes.length;x++){
                           if(scenes[x]==scene){
                               flag=true;
                               break;
                           }
                       }
                       if(flag){
                        target.parents(".menu-item").show();    
                       }else{
                           target.parents(".menu-item").hide();
                       }
                   }else{
                       target.parents(".menu-item").show();
                   }
               });
        }
JS;

        $css=<<<CSS
        .menu-top{
            width:100px !important;
        }
CSS;

        $this->getView()->registerJs($js,View::POS_BEGIN);
        $this->getView()->registerCss($css);

        $html=Html::beginTag("div",["class"=>"table-head"]);
        $html.=Html::tag("p",$this->title,["class"=>"head"]);
        $html.=Html::beginTag("div",["class"=>"float-right"]);
        foreach ($this->menus as $k=>$menu){
            $url=is_array($menu['url'])?Url::to($menu['url']):$menu['url'];
            if($url && !$menu["except"] && !Menu::isAction($url)){
                continue;
            }
            if($menu["dispose"]){
                $menu["options"]["href"]=Url::to($menu["url"]);
            }
            $html.=Html::beginTag("a",$menu["options"]);
            if(count($menu["child"])>0) {
                $html .= Html::beginTag("div", ["class" => "table-nav", "data-menu" =>"#submenu_$k"]);
            }else{
                $html .= Html::beginTag("div", ["class" => "table-nav"]);
            }
            $k>0 && $html.=Html::tag("p","&nbsp;|&nbsp;",["class"=>"float-left"]);
            $html.=Html::tag("p","",["class"=>$menu["icon"]." float-left"]);
            $html.=Html::tag("p","&nbsp;".$menu["label"]);

            $html.=Html::endTag("div");

            if(count($menu["child"])>0){
                $html .= Html::beginTag("div", ["id" => "submenu_$k","style"=>"display:none"]);
                foreach ($menu["child"] as $child) {
                    $url=is_array($child['url'])?Url::to($child['url']):$child['url'];
                    if($url && !$child["except"] && !Menu::isAction($url)) {
                        continue;
                    }
                    $html .= Html::beginTag("div");
                    $html .= Html::beginTag("a",$child["options"]);
                    $html .= Html::tag("span",$child["label"]);
                    $html .= Html::endTag("a");
                    $html .= Html::endTag("div");
                }
                $html .= Html::endTag("div");
            }
            $html.=Html::endTag("a");
        }
        $html.=Html::endTag("div");
        return $html.=Html::endTag("div");
    }
}