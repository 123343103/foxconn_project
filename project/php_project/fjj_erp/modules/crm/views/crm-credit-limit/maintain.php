<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/7/28
 * Time: 11:16
 */
use \yii\widgets\ActiveForm;
use \yii\helpers\Url;
\app\assets\JeDateAsset::register($this);

$this->title="批量维护信用额度";
$this->params['homeLike']=['label'=>'销售管理'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<style>
    .table-font {
        font-size: 12px;
    }
    .table-all {
        margin-left: 20px;
        margin-right: 20px;
        width: auto;
    }
    .tb-box {
        width: 990px;
        overflow: auto;
    }
    td {
        min-width: 85px;
    }
    .table select, .table input {
        max-width: 110px;
        text-align: center;
    }
</style>
<div class="content">
    <div class="mb-10">
        <span>请先下载模板,然后严格按照模板导入信用额度信息.</span>
        <a href="<?= Url::to(['export-tpl']) ?>" class="red">下载模板</a>
    </div>
<!--    <div class="mb-10">-->
<!--        <input type="file" class="width-200">-->
<!--        <button type="button" class="button-blue" onclick="check()">添加</button>-->
    <input type="file"/>
    <span class="red">*只能上传excel文件</span>
    <button class="button-blue add-btn">添加</button>

    <!--    <div id="demo"></div>-->
    </div>
    <?php $form = ActiveForm::begin([
            'id'=>'add-form'
    ]); ?>
    <div class="mb-10 table-all table-head ">
        <div class="mb-10">
            <p class="head">批量维护信用额度</p>
            <button type="button" class="button-blue float-right  add">新增</button>
        </div>
        <div class="tb-box"><!-- 表格宽度不定 包起来滚动条自适应 -->
            <table class="table table-font">
                <thead>
                <tr class="table-thead">
                    <th>序号</th>
                    <th>法人</th>
                    <th>客户代码</th>
                    <th>客户名称</th>
                    <th>币别</th>
                    <th>申请额度</th>
                    <th>信用额度类型</th>
                    <th>授信额度</th>
                    <th>有效期</th>
                    <th>备注</th>
                </tr>
                </thead>
                <tbody class="table-body">
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="button-blue-big">导入信息</button>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $(function(){
        ajaxSubmitForm($("#add-form"));
        $('.add').click(function(){
            if (!$('#add-form').form('validate')) {
                $("button[type='submit']").prop("disabled", false);
                layer.alert("数据填写不完整，请填写完后再添加。",{icon:2,time:5000});
                return false;
            }
            var a = $(".table-body tr").length;
            var select =
                '<select name="limit['+ parseInt(a+1) +'][]" >'+
                '<option value="">请选择...</option>'+
                '<?php foreach ($downList["credit_type"] as $key => $val) { ?>'+
                '<option value="<?= $val['id'] ?>" <?= $model['credit_type'] == $val['id'] ? 'selected' : null ?>><?= $val['credit_name'] ?></option>'+
                '<?php } ?>'+
                '</select>';
            var tr = "<tr><td>"+ parseInt(a+1) + "<input name=\"limit["+ parseInt(a+1) +"][]\" value=\"\" type=\"hidden\"></td><td><input name=\"limit["+ parseInt(a+1) +"][]\" value=\"\" type=\"hidden\"></td><td><input name=\"limit["+ parseInt(a+1) +"][]\" value=\"\" class=\"easyui-validatebox\" data-options=\"required:true\" ></td><td><input name=\"limit["+ parseInt(a+1) +"][]\" value=\"\" type=\"hidden\"></td><td><input name=\"limit["+ parseInt(a+1) +"][]\" value=\"\" type=\"hidden\"></td><td><input name=\"limit["+ parseInt(a+1) +"][]\" value=\"\" type=\"hidden\"></td><td>"+ select +"</td><td><input name=\"limit["+ parseInt(a+1) +"][]\" value=\"\" class=\"easyui-validatebox\" data-options=\"required:true\"></td><td><input name=\"limit["+ parseInt(a+1) +"][]\" value=\"\" class=\"easyui-validatebox select-date\" data-options=\"required:true\"></td><td><input name=\"limit["+ parseInt(a+1) +"][]\" value=\"\" class=\"easyui-validatebox\" data-options=\"required:true,validType:'length[0,33]'\" placeholder=\"不为空,不能超过33个字\"></td></tr>";
            var obj = $('.table-body').append(tr);
            $.parser.parse(obj);
        });
        
        $('.add-btn').click(function () {
            importf($('input[type="file"]').get(0));
            $(this).attr('disabled',true);
        })

        $(document).on('click','.select-date',function () {
            $(this).jeDate({
                format:"YYYY-MM-DD",
                zIndex:5 //菜单栏弹出层的层级为7(myMenu.css)
            })
            $(this).click();
        });
    });
    var wb;//读取完成的数据
    var rABS = false; //是否将文件读取为二进制字符串
    function importf(obj) {//导入
        if(obj.files[0] == undefined) {
            layer.alert("缺少文件",{icon:2,time:5000});
            return;
        }
        var f = obj.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            var data = e.target.result;
            if(rABS) {
                wb = XLSX.read(btoa(fixdata(data)), {//手动转化
                    type: 'base64'
                });
            } else {
                wb = XLSX.read(data, {
                    type: 'binary'
                });
            }
            var arr = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]],{header:1, raw:true});//转换获取excel数据
//            $('.table-body').html('');
            var oldLength = $(".table-body tr").length; // 已经存在的表格行数
            for(var i=oldLength+1;i<oldLength+arr.length;i++){
                if (arr[i-oldLength].length != 0) {
                    $('.table-body').append(
                            '<tr class="tr_'+ i +'">'+'</tr>'
                    );
                    for(var a=0;a<arr[i-oldLength].length;a++){
                        if (a == 0) {
                            $('.tr_'+i).append(
                                '<td>'+ i + '<input type="hidden" name="limit['+ i +'][]" value="'+ arr[i-oldLength][a] +'"></td>'
                            );
                            continue;
                        }
                        if (a == 6) {
                            var select =
                                '<select style="display:none" name="limit['+ parseInt(i-oldLength) +'][]" >'+
                                '<option value="">请选择...</option>'+
                                '<?php foreach ($downList["credit_type"] as $key => $val) { ?>'+
                                '<option value="<?= $val['id'] ?>" '+ (arr[i-oldLength][a] == '<?= $val["credit_name"] ?>' ? 'selected' : '') +'><?= $val['credit_name'] ?></option>'+
                                '<?php } ?>'+
                                '</select>';
                            $('.tr_'+i).append(
                                '<td>'+ arr[i-oldLength][a] + select + '</td>'
                            );
                            continue;
                        }
                        $('.tr_'+i).append(
                            '<td>'+ arr[i-oldLength][a] + '<input type="hidden" name="limit['+ i +'][]" value="'+ arr[i-oldLength][a] +'"></td>'
                        );
                    }
                }
                $.parser.parse(obj);
            }
        };
        if(rABS) {
            reader.readAsArrayBuffer(f);
        } else {
            reader.readAsBinaryString(f);
        }
    }

    function fixdata(data) { //文件流转BinaryString
        var o = "",
            l = 0,
            w = 10240;
        for(; l < data.byteLength / w; ++l) o += String.fromCharCode.apply(null, new Uint8Array(data.slice(l * w, l * w + w)));
        o += String.fromCharCode.apply(null, new Uint8Array(data.slice(l * w)));
        return o;
    }
</script>
