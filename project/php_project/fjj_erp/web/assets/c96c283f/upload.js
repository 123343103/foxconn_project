$(function(){
	var t;
	var uploader = WebUploader.create({
		 auto: true, // 选完文件后，是否自动上传 
		 swf: 'Uploader.swf' // swf文件路径 
		});

	$("body").append("<input id='upfile'  type='file' style='display:none;' />");

	$(".upBtn").click(function(){
		t=$(this);
		$("#upfile").click();
	});

	$("#upfile").change(function(){
		uploader = WebUploader.create({
		 auto: true, // 选完文件后，是否自动上传 
		 swf: 'Uploader.swf', // swf文件路径 
		server: t.data("server") // 文件接收服务端 
		});				
		uploader.on("uploadSuccess",function(file,res){
			if(res.state!="SUCCESS"){
				alert(res.state);
			}else{
					if(t.data("target-type")=="text"){
						$(t.data("target")).val(res.url);
					}
					else{
                        t.before("<a class='"+t.data('target')+"_label' href='"+res.url+"'>"+file.name+"</a>");
                        t.after("<input class='"+t.data('target')+"_hidden' type='hidden' name="+t.data("target-name")+" value="+res.url+" />");
                        t.css("display","none");
                        t.after("<a href='javascript:void(0);' onclick='delFile($(this))'> 删除</a>");
					}
			}
			if(t.data("callback")){
                uploadCallback(t,res);
			}
		});
		uploader.addFiles(this.files);
		uploader.upload();
	});
});

function delFile(t){
	t.next(":hidden").remove();
	t.prev(".upBtn").prev("a").remove();
	t.prev(".upBtn").css("display","inline-block");
	t.remove();

}