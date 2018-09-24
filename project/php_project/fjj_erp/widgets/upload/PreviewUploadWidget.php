<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/9/14
 * Time: 上午 09:41
 */

namespace app\widgets\upload;


use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;


/*使用示例:
 *
 *<?=\app\widgets\upload\PreviewUploadWidget::widget([
                    "url_prefix"=>"ftp://10.134.100.164/",
                    "extensions"=>"png,jpg",
                    "items"=>[
                        ["url"=>"defaults/20170914/1505355987454415.jpg"],
                        ["url"=>"defaults/20170914/1505355991191921.jpg"],
                        ["url"=>"defaults/20170914/1505356014280050.jpg"],
                        ["url"=>"defaults/20170914/1505357529925527.jpg"],
                        ["url"=>"defaults/20170914/1505358187152762.jpg"],
                        ["url"=>"defaults/20170914/1505358366354581.jpg"],
                        ["url"=>"defaults/20170914/1505358558447272.jpg"],
                        ["url"=>"defaults/20170914/1505358558447272.jpg"],
                        ["url"=>"defaults/20170914/1505358558447272.jpg"],
                        ["url"=>"defaults/20170914/1505358558447272.jpg"]
                    ]
])?>
 *
 */





class PreviewUploadWidget extends Widget
{
    public $name;//隐藏域name属性
    public $items;//修改时传入图片列表项
    public $extensions;//可上传文件类型
    public $url_prefix;//资源地址前缀
    public $addible=true;//是否可添加
    public $allowRatio="800*800";//允许上传的分辨率

    public function init(){
        if(!$this->url_prefix){
            $this->url_prefix=\Yii::$app->ftpPath["httpIP"];
        }
    }

    public function run(){

        //webuploader插件资源注入
        UploadAsset::register($this->getView());

        //上传请求地址
        $server=Url::to(['upload']);

        //样式注入
        $css=<<<CSS
	.picker{
		text-align: center;;
		color:#fff;
		width:110px;
		height: 110px;
		line-height: 40px;
		position: relative;
		float: left;
		margin-right:10px;
	}
	.picker *{
		position: absolute;
		left:0px;
		right:0px;
		top:0px;
		bottom: 0px;
	}
	.picker input{
		display: none;
	}
	.preview{
		list-style: none;
	}
	.preview li{
		width:110px;
		display: block;
		float: left;
		margin-right:10px;
		position: relative;
		border:#eee 1px solid;
	}
	.preview li img{
	    width: 110px;
	    height: 110px;
	}
	.preview li .main{
		position: absolute;
		left:10px;
		bottom:10px;
		color: #fff;
		background: #000;
		font-size: 10px;
		width:40px;
		height: 20px;
		line-height: 20px;
		text-align: center;
		cursor: pointer;
	}
	.preview li:first-child .main{
		display: none;
	}
	.preview li .remove{
		position: absolute;
		right:10px;
		bottom:10px;
		color: #fff;
		background: #000;
		font-size: 10px;
		width:40px;
		height: 20px;
		line-height: 20px;
		text-align: center;
		cursor: pointer;
	}

    .no-img{
        height:25px;
        line-height:25px;
        color:darkred;
    }
    .load-error{
        width:110px;
        height:110px;
        line-height:110px;
        text-align:center;
        border:#eee 1px solid;
        color:darkred;
    }
    
	.notice{
		font-size: 12px;
		margin-top: 10px;
		margin-bottom: 10px;
		color:darkred;
	}
CSS;
        $this->getView()->registerCss($css);

        $swf=Url::to("@web/Uploader.swf");
        //自定义脚本注入
        $js=<<<JS
        $(".preview-widget img").error(function(){
            $(this).parent().empty().append($("<div class='load-error'></div>").text("图片无法加载"));
        });
        $(".preview-widget img").load(function(){
            $(this).parent().find(".remove,.main").show();
        });
		$(".preview-widget").each(function(index){
			var _this=$(this);
			_this.find(".picker").attr("id","picker"+index);
			WebUploader.create({
			    pick:{
			        id:"#picker"+index
			    },
			    swf:"{$swf}",
				auto:true,
				accept:{
				  extensions:'{$this->extensions}'  
				},
				server:'{$server}'
			})
			.on("uploadProgress",function(file,perc){
				$(file.id).append("<p>"+perc+"</p>");
			})
			.on("uploadSuccess",function(file,res){
			    if(res.state=="SUCCESS"){
					var li=$("<li id="+file.id+"></li>");
					_this.find(".preview li").last().before(li);
					this.makeThumb(file,function(err,ret){
						if(err){
							li.text("error");
						}else{
							var img=$("<img width='110' height='110' src="+ret+" />");
							var rmBtn=$("<span class='remove'>删除</span>").show();
							var mainBtn=$("<span class='main'>主图</span>").show();
							li.append(mainBtn).append(rmBtn).prepend(img);
						}
					});
					li.append("<input type='hidden' name='{$this->name}' value='"+res.url+"'>");   
			    }else{
			        alert(res.state);
			    }
			})
			.on("uploadError",function(file,reason){
				alert("上传出错");
			})
			.on("error",function(err){
			    switch(err){
			        case "Q_TYPE_DENIED":
			            alert("不允许的文件类型");
			            break;
			    }
			});
			
			_this.click(function(event){
			    var event=event || window.event;
			    var target=event.target || event.srcElement;
			    if($(target).hasClass("remove")){
			        $(target).parents("li").remove();
			    }
			    if($(target).hasClass("main")){
			        $(".main").show();
			        $(target).parents("li").find(".main").hide();
			        $(target).parents(".preview").find("li").first().before($(target).parents("li"));
			    }
			});
		});
        
JS;

        //仅预览而不上传的页面不注入js
        $this->addible && $this->getView()->registerJs($js);


        $icon=Url::to("@web/img/uploads/Supplyuploads.png");


        //模板生成
        $html=Html::beginTag("div",["class"=>"preview-widget"]);
        $html.=Html::beginTag("ul",["class"=>"preview"]);
        if(count($this->items)==0 && !$this->addible){
            $html.=Html::tag("p","----还没有上传过图片----",["class"=>"no-img"]);
        }else{
            foreach ($this->items as $k=>$item){
                $html.=Html::beginTag("li");
                $html.=Html::img(trim($this->url_prefix,"/")."/".trim($item,"/"));
                if($this->addible){
                    if($k==0){
                        $html.=Html::tag("span","主图",["class"=>"main","style"=>"display:'none'"]);
                    }else{
                        $html.=Html::tag("span","主图",["class"=>"main"]);
                    }
                }
                $this->addible && $html.=Html::tag("span","删除",["class"=>"remove"]);
                $this->addible && $html.=Html::hiddenInput("{$this->name}",$item);
                $html.=Html::endTag("li");
            }
        }
        if($this->addible){
            $html.=Html::beginTag("li",["class"=>"picker"]);
            $html.=Html::img($icon);
            $html.=Html::endTag("li");
        }
        $html.=Html::endTag("ul");
        $html.=Html::tag("div","",["style"=>"clear:both"]);
        $this->addible && $html.=Html::tag("div","第一张图为默认主图，或点击图片左下角选择主图，图片尺寸为800*800像素，图片请避免全文字",["class"=>"notice","style"=>"clear:both"]);
        $html.=Html::endTag("div");
        return $html;

    }
}