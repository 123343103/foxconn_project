<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '文件上传', 'url' => ''];
$this->params['breadcrumbs'][] = ['label' => '文件上传'];
?>
<div>
    <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'add-form']); ?>
    <div class="mb-10" style="margin-top: 20px;margin-left: 50px;">
        <label class="comvalue label-align" id="business">文件名：</label>
        <input class="" type="text" readonly="readonly" maxlength="120" id="license_name"
               value="" style="width: 300px;" name="license_name">
        <input type="file" style="width: 70px;" multiple="multiple" name="upfiles-lic" id="upfiles-lic"
               class="up-btn" onchange="license('upfiles-lic')"/>
    </div>
    <div style="margin-left: 200px;margin-top: 20px;">
        <button id="apply-form" class="button-blue-big"
                type="submit">提交
        </button>
    </div>
    <img id="imgname" style="width: 500px; height: 500px;margin-left: 50px;margin-top: 20px;" />
    <!--<a id="result"><?=$nameold?></a>-->
    <?php \yii\widgets\ActiveForm::end(); ?>
</div>
<script>
    function change(obj) {
        var dom = document.getElementById(obj);
        var mList = dom.value;
        str = mList.substring(mList.lastIndexOf("\\") + 1);
        return str;
    }
    function license(obj) {
        $("#license_name").val(change(obj));
    }
    $(function () {
        $("#apply-form").click(function () {
            $("#add-form").attr('action', '<?= \yii\helpers\Url::to(['/test/test/index']) ?>');
        });
        ajaxSubmitForm($("#add-form"));
        var namenew="<?=$namenew?>";
        var ad='http://10.134.100.101:81/test';
        //$("#result").attr('href',ad+'/'+namenew);
        $("#imgname").attr('src',ad+'/'+namenew);
    })
</script>


<!--<div class="content">-->
<!--    --><?php //echo $this->render('_search',[
//        'search' => $search
//    ]);
//    ?>
<!--    <div class="table-content">-->
<!--     --><?php //echo $this->render('_action');?>
<!--        <div id="data"></div>-->
<!--    </div>-->
<!--</div>-->
<!--<script>-->
<!--    $(function() {-->
<!--        $("#data").datagrid({-->
<!--            url: "<? //=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>
//            rownumbers: true,
//            method: "get",
//            idField: "user_id",
//            loadMsg: false,
//            pagination: true,
//            singleSelect: true,
//            columns: [[
//                {field: "user_id", title: "ID", width: 150},
//                {field: "user_name", title: "姓名", width: 150},
//                {field: "english_name", title: "英文名", width: 150},
//                {field: "user_sex", title: "性别", width: 150},
//                {field: "user_nation", title: "名族", width: 150},
//                {field: "user_age", title: "年龄", width: 157},
//            ]],
//            onLoadSuccess: function (data) {
//                $('.datagrid-header-row td').eq(0).html('&nbsp;序号&nbsp;').css('color','white');
//            },
//        });
//
//
//        $("#update").on("click",function(){
//            var getSelected = $("#data").datagrid("getSelected");
//            if(getSelected == null){
//                layer.alert("请点击选择一条人员信息!",{icon:2,time:5000});
//            }else{
//                var id = $("#data").datagrid("getSelected")['user_id'];
//                window.location.href = "<? //=Url::to(['update'])?>//?id=" + id;
//            }
//        });
//        $("#deletion").on("click",function(){
//            var a = $("#data").datagrid("getSelected");
//            if(a == null){
//                layer.alert("请选择一条人员信息!",{icon:2,time:5000});
//            } else {
//                var id = $("#data").datagrid("getSelected")['user_id'];
//                var index = layer.confirm("确定要删除这条记录吗?",
//                    {
//                        btn:['确定', '取消'],
//                        icon:2
//                    },
//                    function () {
//                        $.ajax({
//                            type: "get",
//                            dataType: "json",
//                            async: false,
//                            data: {"id": id},
//                            url: "<? //=Url::to(['/test/test/delete']) ?>//",
//                            success: function (msg) {
//                                if( msg.flag === 1){
//                                    layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
//                                }else{
//                                    layer.alert(msg.msg,{icon:2})
//                                }
//                            },
//                            error :function(msg){
//                                layer.alert(msg.msg,{icon:2})
//                            }
//                        })
//                    },
//                    function () {
//                        layer.closeAll();
//                    }
//                )
//            }
//        })
//    })
//
//</script> -->



