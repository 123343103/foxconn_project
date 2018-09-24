<?php
/**
 * 动态列
 * F3859386
 * 2017/03/21
 */
use yii\widgets\ActiveForm;
use app\assets\TreeAsset;
TreeAsset::register($this);

$this->params['homeLike'] = ['label'=>'系统设置'];
$this->params['breadcrumbs'][] = ['label'=>'动态列设置'];
$this->title = "动态列设置";

?>
<style>
    .width-70 {
        width: 70px;
    }
    .width-80 {
        width: 80px;
    }
    .width-300 {
        width: 300px;
    }
    .width-665 {
        width: 665px;
    }
    .height-30 {
        height: 30px;
    }
    .ml-15 {
        margin-left: 15px;
    }
    .ml-20 {
        margin-left: 20px;
    }
    .mb-10 {
        margin-bottom: 10px;
    }
    .mt-10 {
        margin-top: 10px;
    }
</style>
<div class="content">
    <div class="display-flex">
        <div class="width-300 float-left">
            <div id="tree"></div>
        </div>
        <div  class="width-665 ml-20 float-left">
             <?php $form=ActiveForm::begin(['id'=>'add-form','action'=>['edit']]); ?>
                <div id="_form"></div>
             <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>
<script>
    $(function(){
    var $form=$("#add-form");
        $($form).on("beforeSubmit", function () {
            if (!$(this).form('validate')) {
                return false;
            }
            var options = {
                dataType: 'json',
                success: function (data) {
                    if (data.flag == 1) {
                        var rule=$("#mySelect").val();
                        if(rule !=''){
                            getField(rule)
                        }
                        layer.alert(data.msg, {
                            icon: 1
                        });
                    }
                    if (data.flag == 0) {
                        layer.alert(data.msg, {
                            icon: 2
                        });
                    }
                },
                error: function (data) {
                    layer.alert(data.responseText, {
                        icon: 2
                    });
                }
            };

            $($form).ajaxSubmit(options);
            return false;
        });

    var tree = [
        <?= $tree ?>
    ];
    $('#tree').treeview({
        data: tree,         // data is not optional
        levels: 2,
        selected: true,
//        enableLinks: true,
//        highlightSelected: true,
        searchResultBackColor: "#DEDEDE",
        onNodeSelected: function(event, info) {
            $("#_form").html('');
            $.ajax({
                type: "get",
                dataType: "json",
                data: {"id": info.id},
                url: "<?=\yii\helpers\Url::to(['/system/display-list/get-field']) ?>",
                success: function (data) {
                    processing(data,info.id);
                    $("input[name*='index']").validatebox({
                        required:true,
                        validType:"number"
                    });
                    $("input[name*='width']").validatebox({
                        required:true,
                        validType:"number"
                    });
                },
                error :function(data){
                    layer.alert(data.msg,{icon:2})
                }
            })
        }
    });
    //下拉菜单触发
     $(document).on("change",'select#mySelect',function(){
                var rule=$(this).val();
                getField(rule)
     });

});
    function getField(rule){
        var id=$("#listId").val();
        $.ajax({
            type: "get",
            dataType: "json",
            data: {"id":id,"rule":rule},
            url: "<?=\yii\helpers\Url::to(['/system/display-list/get-field']) ?>",
            success: function (data) {
                $("#listSrt").html(listIndex(data));
            },
            error :function(data){
                layer.alert(data.msg,{icon:2})
            }
        })
    }
    //加载动态列数据
    function processing(data,listId){
        if(data==''){
            return false;
        }
        var tableStr="<table class='text-center mt-10 mb-10 ml-15 width-630'>" +
                "<tr class='height-30'>" +
                "<td>#</td>" +
                "<td>栏位</td>" +
                "<td>栏位标题</td>" +
                "<td class='width-80'>栏位宽</td>" +
                "<td class='width-70'>是否显示</td>" +
                "<td class='width-80'>排序</td>" +
                "</tr>"+
                "<tbody id='listSrt'>";
//                "</div>";
        var tableEnd="</tbody>"+
            "<input type='hidden' id='listId' value='"+listId+"' name='ddi_sid'>"+
                     "</table>";
        $("#_form").html(listAction()+tableStr+listIndex(data)+tableEnd);

    }

    //按钮操作部分
    function listAction(){
        var rule =<?= $rule ?>;
        var options='';
        var actionStr='';
        //遍历出所有角色
        $.each(rule,function(n,value){
            options+="<option value="+value.name+">"+value.title+"</option>";
        });
         actionStr="<h2 class='head-first'>" +
            "<button type='submit' class='button-blue'>保存</button>" +
            "<button type='reset' class='button-blue' onclick=\"window.location='<?= \yii\helpers\Url::to(['/index'])?>'\">返回</button>" +
            "<span class='ml-15'>分配给:</span> "+
            "<select id='mySelect' name='rule'>" +
            "<option value=''>所有人</option>" +
            options+
            "</select>"+
            "</h2>" +
            "<div style='border: 1px solid #000000' class='mt-10'>" +
            "<span class='text-left mt-10 ml-15'>动态列属性设置:</span>";
        return actionStr;
    }

    //获取字段数据
    function listIndex(data){
        var index='';
        var checked='';
        $.each(data,function(n,value){
            if(value.field_display==10){
                checked="checked='checked'"
            }else{
                checked="";
            }
            index+="<tr><td class='width-30'>"+(n+1)+"</td><input type='hidden' name='field["+(n+1)+"][field_index]' class='width-40 no-border text-center'  value='"+value.field_index+"' /><input type='hidden' name='field["+(n+1)+"][field_id]' value='"+value.field_id+"'/>";
            index+="<td class='width-170'><span>"+value.field_field+"</span><input type='hidden' name='field["+(n+1)+"][field_field]' value='"+value.field_field+"'/>";
            index+="<td><input type='text' name='field["+(n+1)+"][field_title]' class='width-200  no-border text-center' value='"+value.field_title+"' class='no-border'/></td>";
            index+="<td><input type='text' name='field["+(n+1)+"][field_width]' class='width-120 no-border text-center' value='"+value.field_width+"' /></td>";
            index+="<td><input type='checkbox' class='no-border' value=1 name='field["+(n+1)+"][field_display]' "+checked+"/></td>";
            index+='<td><a href="javascript:;" onclick="up(this)">上移</a>&nbsp;&nbsp;<a href="javascript:;" onclick="down(this)">下移</a></td></tr>';
        });
        return index;
    }
    function up(obj) {
        var tr = obj.parentNode.parentNode;
        var tbody = tr.parentNode;
        var tb = tbody.parentNode;
        var rowIdx = 0;
        for (var i = 0; i < tb.rows.length; i++) {
            if (tb.rows[i] == tr) {
                rowIdx = i;
                break;
            }
        }
        if (rowIdx == 1)return;
        var preTr = tb.rows[rowIdx - 1];
        var Tr = tb.rows[rowIdx];
        var nextNextObj = tr.nextSibling;
        tbody.removeChild(preTr);
        if (nextNextObj)tbody.insertBefore(preTr, nextNextObj);
        else tbody.appendChild(preTr);


        var num = tr.cells[0].innerHTML;

        tr.cells[0].parentNode.childNodes[1].value = preTr.cells[0].innerHTML;
        tr.cells[0].innerHTML = preTr.cells[0].innerHTML;

        preTr.cells[0].parentNode.childNodes[1].value = num;
        preTr.cells[0].innerHTML = num;
    }
    function down(obj) {
        var tr = obj.parentNode.parentNode;
        var tbody = tr.parentNode;
        var tb = tbody.parentNode;
        var rowIdx = 0;
        for (var i = 0; i < tb.rows.length; i++) {
            if (tb.rows[i] == tr) {
                rowIdx = i;
                break;
            }
        }
        if (rowIdx == tb.rows.length - 1)return;
        var nextTr = tb.rows[rowIdx + 1];
        var Tr = tb.rows[rowIdx];
        var nextNextObj = nextTr.nextSibling;
        tbody.removeChild(tr);
        if (nextNextObj)tbody.insertBefore(tr, nextNextObj);
        else tbody.appendChild(tr);


        var num = tr.cells[0].innerHTML;

        tr.cells[0].parentNode.childNodes[1].value = nextTr.cells[0].innerHTML;
        tr.cells[0].innerHTML = nextTr.cells[0].innerHTML;

        nextTr.cells[0].parentNode.childNodes[1].value = num;
        nextTr.cells[0].innerHTML = num;

    }
</script>
