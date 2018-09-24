<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
$this->title='活动类型设置列表';
$this->params['homeLike']=['label'=>'客户关系管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=['label'=>'活动相关设置','url'=>Url::to(['/crm/crm-active-set/index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <div class="mb-20">
        <label class="width-60">查询</label>
        <input id="keyword" type="text" class="width-300">
        <button id="query_btn" type="button" class="search-btn-blue ml-10">查询</button>
        <button id="reset_btn" type="button" class="reset-btn-yellow ml-10">重置</button>
    </div>
    <div class="table-head" style="margin-bottom:10px;">
        <p>活动类型列表</p>
        <div class="float-right">
            <?php if(Menu::isAction('/crm/crm-active-type/add')){?>
                <?php if($flag){?>
                    <a id="add_btn">
                        <div style="height: 23px;width: 55px;float: left;">
                            <p class="add-item-bgc" style="float: left"></p>
                            <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
                        </div>
                    </a>
                    <p style="float: left;">&nbsp;|&nbsp;</p>
                <?php }else{?>
                    <a>
                        <div style="height: 23px;width: 55px;float: left;">
                            <p class="add-item-bgc" style="float: left;"></p>
                            <p style="font-size: 14px;margin-top: 2px;color:#aaaaaa;">&nbsp;新增</p>
                        </div>
                    </a>
                    <p style="float: left;">&nbsp;|&nbsp;</p>
                <?php }?>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-active-type/edit')){?>
                <a id="edit_btn">
                    <div style="height: 23px;width: 55px;float: left;">
                        <p class="update-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-active-type/delete')){?>
                <a id="delete_btn">
                    <div style="height: 23px;width: 55px;float: left;">
                        <p class="delete-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;删除</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <a href="<?=Url::to(['/crm/crm-active-set/index'])?>">
                <div style="height: 23px;width: 55px;float: left">
                    <p class="return-item-bgc" style="float: left;"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
                </div>
            </a>
        </div>
    </div>
    <div id="active_type_table" style="width:100%;"></div>
</div>
<script>
    function deleteActiveType(id){
        layer.confirm('确定删除吗？',{icon:2},
            function(){
                $.ajax({
                    url:"<?=Url::to(['delete-active-type']);?>",
                    data:{"typeId":id},
                    dataType:'json',
                    success:function(data){
                        if(data.flag==1){
                            layer.alert(data.msg,{icon:1},function(){
                                location.reload();
                            });
                        }else{
                            layer.alert(data.msg,{icon:2});
                        }
                    }
                })
            },
            function(){
                layer.closeAll();
            }
        )
    }

    $(function(){
        $("#active_type_table").datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url;?>",
            rownumbers:true,
            method:"get",
            idField:"acttype_id",
            singleSelect:true,
            pagination:true,
            pageSize:10,
            pageList:[10,20,30],
            columns:[[
                {field:'ck',checkbox:true},
                <?=$data?>
                {field:'acttype_id',title:'操作',width:60,formatter:function(value,rowData){
                    var str="";
                    if(rowData.del_flag==1){
                        <?php if(Menu::isAction('/crm/crm-active-type/delete')){?>
                        str+="<a class='icon-trash icon-large' style='margin-right:15px;' title='删除' onclick='event.stopPropagation();deleteActiveType("+value+");'></a>";
                        <?php }?>
                    }
                    <?php if(Menu::isAction('/crm/crm-active-type/edit')){?>
                    str+="<a class='icon-edit icon-large' title='修改' onclick='location.href=\"<?=Url::to(['edit'])?>?typeId="+value+"\";event.stopPropagation();'></a>";
                    <?php }?>
                    return str;
                }}
            ]],
            onLoadSuccess:function(data){
//                datagridTip("#active_type_table");
//                showEmpty($(this),data.total,0);
//                setMenuHeight();
                //编码
                $(".type_code").click(function(){
                    $.fancybox({
                        href:"<?=Url::to(['view'])?>?typeId="+$(this).data("id"),
                        type:'iframe',
                        padding:0,
                        autoSize:false,
                        width:460,
                        height:350,
                        fitToView:false
                    });
                    return false;
                });
            }
        });

        //查询
        $("#query_btn").click(function(){
            $("#active_type_table").datagrid('load',{
                "keyword":$("#keyword").val()
            });
        });
        $(document).keydown(function(event){
            if(event.keyCode==13){
                $("#active_type_table").datagrid('load',{
                    "keyword":$("#keyword").val()
                });
            }
        });

        //重置
        $("#reset_btn").click(function(){
            $("#keyword").val('');
            $("#active_type_table").datagrid('load',{
                "keyword":''
            });
        });

        //新增
        $("#add_btn").click(function(){
            $.fancybox({
                href:"<?=Url::to(['add'])?>",
                type:'iframe',
                padding:0,
                autoSize:false,
                width:480,
                height:350,
                fitToView:false
            });
        });

        //修改
        $("#edit_btn").click(function(){
            var obj=$("#active_type_table").datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择活动类型！',{icon:2,time:5000});
                return false;
            }
            $.fancybox({
                href:"<?=Url::to(['edit'])?>?typeId="+obj.acttype_id,
                type:'iframe',
                padding:0,
                autoSize:false,
                width:480,
                height:400,
                fitToView:false
            });
        });

        //查看
        $("#view_btn").click(function(){
            var obj=$("#active_type_table").datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择活动类型！',{icon:2,time:5000});
                return false;
            }
            $.fancybox({
                href:"<?=Url::to(['view'])?>?typeId="+obj.acttype_id,
                type:'iframe',
                padding:0,
                autoSize:false,
                width:460,
                height:350,
                fitToView:false
            });
        });

        //删除
        $("#delete_btn").click(function(){
            var obj=$("#active_type_table").datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择活动类型！',{icon:2,time:5000});
                return false;
            }
            layer.confirm('确定删除吗？',{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['delete-active-type']);?>",
                        data:{"typeId":obj.acttype_id},
                        dataType:'json',
                        success:function(data){
                            if(data.flag==1){
                                layer.alert(data.msg,{icon:1},function(){
//                                    layer.closeAll();
//                                    $("#active_type_table").datagrid('reload').datagrid('clearSelections');
                                    location.reload();
                                });
                            }else{
                                layer.alert(data.msg,{icon:2});
                            }
                        }
                    })
                },
                function(){
                    layer.closeAll();
                }
            )
        });
    })
</script>