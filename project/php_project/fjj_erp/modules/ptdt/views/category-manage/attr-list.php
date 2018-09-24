<?php
/**
 * User: F1677929
 * Date: 2017/9/6
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title='类别属性维护('.$data['catg_name'].')';
$this->params['homeLike']=['label'=>'商品开发管理'];
$this->params['breadcrumbs'][]=['label'=>'商品类别管理','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<style>
    .width-60 {
        width:60px;
    }
    .width-80 {
        width:80px;
    }
    .width-100 {
        width:100px;
    }
    .width-200 {
        width:200px;
    }
    .ml-10 {
        margin-left: 10px;
    }
</style>
<div class="content">
    <div class="mb-10">
        <label class="width-80">属性名称</label>
        <input id="attr_name" type="text" class="width-200">
        <label class="width-60">状态</label>
        <select id="status" class="width-100">
            <option value="">全部</option>
            <option value="1" selected="selected">启用</option>
            <option value="0">禁用</option>
        </select>
        <button id="query_btn" type="button" class="search-btn-blue ml-10">查询</button>
        <button id="reset_btn" type="button" class="reset-btn-yellow ml-10">重置</button>
    </div>
    <div class="table-head mb-10">
        <p><?=$this->title?></p>
        <div class="float-right">
            <a id="add_btn">
                <div style="height: 23px;width: 55px;float: left;">
                    <p class="add-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
                </div>
            </a>
            <p style="float: left;">&nbsp;|&nbsp;</p>
            <a href="<?=Url::to(['index'])?>">
                <div style="height: 23px;width: 55px;float: left">
                    <p class="return-item-bgc" style="float: left;"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
                </div>
            </a>
        </div>
    </div>
    <div id="attr_table" style="width:100%;"></div>
</div>
<script>
    //启用属性
    function enableAttr(id){
        layer.confirm('确定将数据设置启用吗？',{icon:2},
            function(){
                $.ajax({
                    url:"<?=Url::to(['enable-attr'])?>",
                    data:{"id":id},
                    dataType:"json",
                    success:function(data){
                        if(data.flag==1){
                            layer.alert(data.msg,{icon:1},function(){
                                layer.closeAll();
                                $("#attr_table").datagrid("reload");
                            });
                        }else{
                            layer.alert(data.msg,{icon:2});
                        }
                    }
                })
            },
            layer.closeAll()
        )
    }

    //禁用属性
    function disableAttr(id){
        layer.confirm('确定将数据设置禁止用吗？',{icon:2},
            function(){
                $.ajax({
                    url:"<?=Url::to(['disable-attr'])?>",
                    data:{"id":id},
                    dataType:"json",
                    success:function(data){
                        if(data.flag==1){
                            layer.alert(data.msg,{icon:1},function(){
                                layer.closeAll();
                                $("#attr_table").datagrid("reload");
                            });
                        }else{
                            layer.alert(data.msg,{icon:2});
                        }
                    }
                })
            },
            layer.closeAll()
        )
    }

    //修改属性
    function editAttr(id){
        $.fancybox({
            href:"<?=Url::to(['edit-attr'])?>?id="+id,
            type:"iframe",
            padding:0,
            autoSize:false,
            width:500,
            height:500,
            fitToView:false
        });
    }

    //查看属性
    function viewAttr(id){
        $.fancybox({
            href:"<?=Url::to(['view-attr'])?>?id="+id,
            type:"iframe",
            padding:0,
            autoSize:false,
            width:500,
            height:500,
            fitToView:false
        });
    }

    $(function(){
        var $attrTable=$("#attr_table");
        $attrTable.datagrid({
            url:"<?=Url::to(['attr-list'])?>",
            queryParams:{"id":<?=$data['catg_id']?>},
            rownumbers:true,
            method:"get",
            singleSelect:true,
            pagination:true,
            columns:[[
                {field:"attr_name",title:"属性名称",width:200,formatter:function(value,rowData){
                    return "<a onclick='event.stopPropagation();viewAttr("+rowData.catg_attr_id+");'>"+value+"<a/>";
                }},
                {field:"attrType",title:"资料格式",width:200},
                {field:"isRequired",title:"是否必填",width:200},
                {field:"status",title:"状态",width:200,formatter:function(value){
                    if(value==='0'){
                        return "禁用";
                    }
                    if(value==='1'){
                        return "启用";
                    }
                }},
                {field:"catg_attr_id",title:"操作",width:116,formatter:function(value,rowData){
                    var str="";
                    if(rowData.status==='0'){
                        str+="<a class='icon-ok-circle icon-large' style='margin-right:30px;' title='启用' onclick='event.stopPropagation();enableAttr("+value+");'></a>";
                    }
                    if(rowData.status==='1'){
                        str+="<a class='icon-remove-circle icon-large' style='margin-right:30px;' title='禁用' onclick='event.stopPropagation();disableAttr("+value+");'></a>";
                    }
                    str+="<a class='icon-edit icon-large' title='修改' onclick='event.stopPropagation();editAttr("+value+");'></a>";
                    return str;
                }}
            ]],
            onLoadSuccess:function(data){
                datagridTip($attrTable);
                showEmpty($(this),data.total,0);
                setMenuHeight();
            }
        });

        //查询
        $("#query_btn").click(function(){
            $attrTable.datagrid("load",{
                "id":<?=$data['catg_id']?>,
                "attr_name":$("#attr_name").val(),
                "status":$("#status").val()
            });
        });
        $(document).keydown(function(event){
            if(event.keyCode==13){
                $attrTable.datagrid("load",{
                    "id":<?=$data['catg_id']?>,
                    "attr_name":$("#attr_name").val(),
                    "status":$("#status").val()
                });
            }
        });

        //重置
        $("#reset_btn").click(function(){
            $("#attr_name").val('');
            $("#status").find("option:eq(1)").prop("selected",true);
            $attrTable.datagrid("load",{
                "id":<?=$data['catg_id']?>,
                "attr_name":$("#attr_name").val(),
                "status":$("#status").val()
            });
        });

        //新增
        $("#add_btn").click(function(){
            $.fancybox({
                href:"<?=Url::to(['add-attr','id'=>$data['catg_id']])?>",
                type:"iframe",
                padding:0,
                autoSize:false,
                width:500,
                height:500,
                fitToView:false
            });
        });
    })
</script>