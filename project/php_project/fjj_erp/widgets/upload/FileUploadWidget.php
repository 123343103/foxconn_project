<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2018/1/20
 * Time: 15:26
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


class FileUploadWidget extends Widget
{
    public $name;//隐藏域name属性
    public $items;//修改时传入图片列表项
    public $extensions;//可上传文件类型
    public $url_prefix;//资源地址前缀
    public $addible=true;//是否可添加

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
		height: 30px;
		line-height: 40px;
		position: relative;
		float: left;
		margin-right:10px;
        width:80px;
		display: block;
		border:#eee 1px solid;
		background: #1e7fd0;
		border-radius: 5px;
	}

    .picker input{
		display: none;
	}
	.preview{
		list-style: none;
	}
	.preview li{
	    float:left;
	}
	.notice{
		font-size: 12px;
		color:#fff;
		line-height:30px;
	}
CSS;
        $this->getView()->registerCss($css);

        $swf=Url::to("@web/Uploader.swf");

        //自定义脚本注入
        $js=<<<JS
		$(".preview-widget").each(function(index){
			var _this=$(this);
			_this.find(".picker").attr("id","picker"+index);
			WebUploader.create({
			    pick:{
			        id:"#picker"+index,
			        multiple:false,
			    },
			    swf:"{$swf}",
				auto:true,
				accept:{
				  extensions:'{$this->extensions}'  
				},
				server:'{$server}',
				fileNumLimit:1
			})
			.on("uploadSuccess",function(file,res){
			    // console.log(file);return false;
			    if(res.state=="SUCCESS"){
			        if(document.getElementById("WU_FILE_0"))
                    {
                        layer.alert('上传成功',{icon:2,time:500});
                        $('.file_new').val(res.url);
                        $('.file_name1').text(file.name);
                        $('.file_name2').val(file.name);
                    }else{
			            var li=$("<li id='WU_FILE_0'></li>");
                        _this.find(".preview li").last().before(li);
                        this.makeThumb(file,function(err,ret){
                            if(err){
                                li.text("error");
                            }
                        });
                        layer.alert('上传成功',{icon:2,time:500});
                        li.append("<input type='hidden' class='file_new' name='LCrmCreditApply[{$this->name}]' value='"+res.url+"'>");   
                        li.append("<span class='file_name1' style='display:block;height:25px;line-height:25px;margin-right:10px;'>"+ file.name +"</span><input type='hidden' class='file_name2' name='LCrmCreditApply[file_old]' value='"+ file.name +"'>");
                    }
					  
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
		});
        
JS;

        //仅预览而不上传的页面不注入js
        $this->addible && $this->getView()->registerJs($js);


//        $icon=Url::to("@web/img/uploads/Supplyuploads.png");


        //模板生成
        $html=Html::beginTag("div",["class"=>"preview-widget"]);
        $html.=Html::beginTag("ul",["class"=>"preview"]);
        if($this->addible){
            $html.=Html::beginTag("li",["class"=>"picker"]);
        $this->addible && $html.=Html::tag("div","选 择 文 件",["class"=>"notice"]);
            $html.=Html::endTag("li");
        }
        $html.=Html::endTag("ul");
        $html.=Html::tag("div","",["style"=>"clear:both"]);
//        $this->addible && $html.=Html::tag("div","第一张图为默认主图，或点击图片左下角选择主图，图片尺寸为800*800像素，图片请避免全文字",["class"=>"notice","style"=>"clear:both"]);
        $html.=Html::endTag("div");
        return $html;

    }
}