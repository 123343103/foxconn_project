<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
$this->title='活动名称列表';
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
        <p>活动名称列表</p>
        <div class="float-right">
            <?php if(Menu::isAction('/crm/crm-active-name/add')){?>
                <a id="add_btn">
                    <div style="height: 23px;width: 55px;float: left;">
                        <p class="add-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-active-name/edit')){?>
                <a id="edit_btn">
                    <div style="height: 23px;width: 55px;float: left;">
                        <p class="update-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-active-name/cancel-active')){?>
                <a id="cancel_btn">
                    <div style="height: 23px;float: left;">
                        <p class="delete-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;取消活动</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-active-name/stop-active')){?>
                <a id="stop_btn">
                    <div style="height: 23px;float: left;">
                        <p class="delete-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;终止活动</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-active-name/delete')){?>
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
    <div id="active_name_table" style="width:100%;"></div>
</div>
<script>
    function deleteActiveName(id){
        layer.confirm('确定删除吗？',{icon:2},
            function(){
                $.ajax({
                    url:"<?=Url::to(['delete-active-name']);?>",
                    data:{"nameId":id},
                    dataType:"json",
                    success:function(data){
                        if(data.flag==1){
                            layer.alert(data.msg,{icon:1},function(){
                                layer.closeAll();
                                $("#active_name_table").datagrid('reload').datagrid('clearSelections');
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

    $(function(){
        $("#active_name_table").datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url;?>",
            rownumbers:true,
            method:"get",
            singleSelect:true,
            pagination:true,
            columns:[[
                {field:'ck',checkbox:true},
                <?=$data?>
                {field:'actbs_id',title:'操作',width:60,formatter:function(value,rowData){
                    var str="";
                    if(rowData.del_flag==1){
                        <?php if(Menu::isAction('/crm/crm-active-name/delete')){?>
                        str+="<a class='icon-trash icon-large' style='margin-right:15px;' title='删除' onclick='event.stopPropagation();deleteActiveName("+value+");'></a>";
                        <?php }?>
                    }
                    <?php if(Menu::isAction('/crm/crm-active-name/edit')){?>
                    str+="<a class='icon-edit icon-large' title='修改' onclick='location.href=\"<?=Url::to(['edit'])?>?nameId="+value+"\";event.stopPropagation();'></a>";
                    <?php }?>
                    return str;
                }}
            ]],
            onLoadSuccess:function(data){
                datagridTip("#active_name_table");
                showEmpty($(this),data.total,0);
            },
            onSelect:function(rowIndex,rowData){
                if(rowData.activeNameStatus=='未开始'){
                    $("#cancel_btn").show().next().show();
                }else{
                    $("#cancel_btn").hide().next().hide();
                }
                if(rowData.activeNameStatus=='进行中'){
                    $("#stop_btn").show().next().show();
                }else{
                    $("#stop_btn").hide().next().hide();
                }
            }
        });

        //查询
        $("#query_btn").click(function(){
            $("#active_name_table").datagrid('load',{
                "keyword":$("#keyword").val()
            });
        });
        $(document).keydown(function(event){
            if(event.keyCode==13){
                $("#active_name_table").datagrid('load',{
                    "keyword":$("#keyword").val()
                });
            }
        });

        //重置
        $("#reset_btn").click(function(){
            $("#keyword").val('');
            $("#active_name_table").datagrid('load',{
                "keyword":''
            });
        });

        //新增活动名称
        $("#add_btn").click(function(){
            window.location.href="<?=Url::to(['add'])?>";
        });

        //修改活动名称
        $("#edit_btn").click(function(){
            var obj=$("#active_name_table").datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择活动！',{icon:2,time:5000});
                return false;
            }
            window.location.href="<?=Url::to(['edit'])?>?nameId="+obj.actbs_id;
        });

        //查看
        $("#view_btn").click(function(){
            var obj=$("#active_name_table").datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择活动！',{icon:2,time:5000});
            }
            window.location.href="<?=Url::to(['view'])?>?nameId="+obj.actbs_id;
        });

        //取消活动
        $("#cancel_btn").click(function(){
            var obj=$("#active_name_table").datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择活动！',{icon:2,time:5000});
                return false;
            }
            if(obj.activeNameStatus!='未开始'){
                layer.alert('该活动不是未开始！',{icon:2,time:5000});
                return false;
            }
            layer.confirm('确定取消吗？',{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['cancel-active']);?>",
                        data:{"nameId":obj.actbs_id},
                        dataType:"json",
                        success:function(data){
                            if(data.flag==1){
                                layer.alert(data.msg,{icon:1},function(){
                                    layer.closeAll();
                                    $("#active_name_table").datagrid('reload').datagrid('clearSelections');
                                });
                            }else{
                                layer.alert(data.msg,{icon:2});
                            }
                        }
                    })
                },
                layer.closeAll()
            )
        });

        //终止活动
        $("#stop_btn").click(function(){
            var obj=$("#active_name_table").datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择活动！',{icon:2,time:5000});
                return false;
            }
            if(obj.activeNameStatus!='进行中'){
                layer.alert('该活动不是进行中！',{icon:2,time:5000});
                return false;
            }
            layer.confirm('确定终止吗？',{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['stop-active']);?>",
                        data:{"nameId":obj.actbs_id},
                        dataType:"json",
                        success:function(data){
                            if(data.flag==1){
                                layer.alert(data.msg,{icon:1},function(){
                                    layer.closeAll();
                                    $("#active_name_table").datagrid('reload').datagrid('clearSelections');
                                });
                            }else{
                                layer.alert(data.msg,{icon:2});
                            }
                        }
                    })
                },
                layer.closeAll()
            )
        });

        //删除
        $("#delete_btn").click(function(){
            var obj=$("#active_name_table").datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择活动！',{icon:2,time:5000});
                return false;
            }
            layer.confirm('确定删除吗？',{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['delete-active-name']);?>",
                        data:{"nameId":obj.actbs_id},
                        dataType:"json",
                        success:function(data){
                            if(data.flag==1){
                                layer.alert(data.msg,{icon:1},function(){
                                    layer.closeAll();
                                    $("#active_name_table").datagrid('reload').datagrid('clearSelections');
                                });
                            }else{
                                layer.alert(data.msg,{icon:2});
                            }
                        }
                    })
                },
                layer.closeAll()
            )
        });
    })
</script>