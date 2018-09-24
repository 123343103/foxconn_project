<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
$this->title='媒体类型设置列表';
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
        <p>媒体类型列表</p>
        <div class="float-right">
            <?php if(Menu::isAction('/crm/crm-media-type/add')){?>
                <a id="add_btn">
                    <div style="height: 23px;width: 55px;float: left;">
                        <p class="add-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-media-type/edit')){?>
                <a id="edit_btn">
                    <div style="height: 23px;width: 55px;float: left;">
                        <p class="update-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-media-type/delete')){?>
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
    <div id="media_table" style="width:100%;"></div>
</div>
<script>
    function deleteMediaType(id){
        layer.confirm('确定删除吗？',{icon:2},
            function(){
                $.ajax({
                    url:"<?=Url::to(['delete-media']);?>",
                    data:{"id":id},
                    dataType:'json',
                    success:function(data){
                        if(data.flag==1){
                            layer.alert(data.msg,{icon:1},function(){
                                layer.closeAll();
                                $("#media_table").datagrid('load').datagrid('clearSelections');
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
        $("#media_table").datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url;?>",
            rownumbers:true,
            method:"get",
            idField:"cmt_id",
            singleSelect:true,
            pagination:true,
            columns:[[
                {field:'ck',checkbox:true},
                {field:"cmt_code",title:"编码",width:150},
                {field:"cmt_type",title:"媒体类型",width:150},
                {field:"cmt_intro",title:"简介",width:200},
                {field:"mediaStatus",title:"状态",width:60},
                {field:"createBy",title:"档案建立人",width:80},
                {field:"create_at",title:"建档日期",width:130},
                {field:"updateBy",title:"最后修改人",width:80},
                {field:"update_at",title:"修改日期",width:130},
                {field:'cmt_id',title:'操作',width:60,formatter:function(value,rowData){
                    var str="";
                    if(rowData.del_flag==1){
                        <?php if(Menu::isAction('/crm/crm-media-type/delete')){?>
                        str+="<a class='icon-trash icon-large' style='margin-right:15px;' title='删除' onclick='event.stopPropagation();deleteMediaType("+value+");'></a>";
                        <?php }?>
                    }
                    <?php if(Menu::isAction('/crm/crm-media-type/edit')){?>
                    str+="<a class='icon-edit icon-large' title='修改' onclick='location.href=\"<?=Url::to(['edit'])?>?id="+value+"\";event.stopPropagation();'></a>";
                    <?php }?>
                    return str;
                }}
            ]],
            onLoadSuccess:function(data){
//                datagridTip("#media_table");
//                showEmpty($(this),data.total,0);
//                setMenuHeight();
            }
        });

        //查询
        $("#query_btn").click(function(){
            $("#media_table").datagrid('load',{
                "keyword":$("#keyword").val()
            });
        });
        $(document).keydown(function(event){
            if(event.keyCode==13){
                $("#media_table").datagrid('load',{
                    "keyword":$("#keyword").val()
                });
            }
        });

        //重置
        $("#reset_btn").click(function(){
            $("#keyword").val('');
            $("#media_table").datagrid('load',{
                "keyword":''
            });
        });

        //新增
        $('#add_btn').click(function(){
            $.fancybox({
                href:"<?=Url::to(['add'])?>",
                type:'iframe',
                padding:0,
                autoSize:false,
                width:480,
                height:300,
                fitToView:false
            });
        });

        //修改
        $("#edit_btn").click(function(){
            var obj=$("#media_table").datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择媒体类型！',{icon:2,time:5000});
                return false;
            }
            $.fancybox({
                href:"<?=Url::to(['edit'])?>?id="+obj.cmt_id,
                type:'iframe',
                padding:0,
                autoSize:false,
                width:480,
                height:350,
                fitToView:false
            });
        });

        //删除
        $("#delete_btn").click(function(){
            var obj=$("#media_table").datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择媒体类型！',{icon:2,time:5000});
                return false;
            }
            layer.confirm('确定删除吗？',{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['delete-media']);?>",
                        data:{"id":obj.cmt_id},
                        dataType:'json',
                        success:function(data){
                            if(data.flag==1){
                                layer.alert(data.msg,{icon:1},function(){
                                    layer.closeAll();
                                    $("#media_table").datagrid('load').datagrid('clearSelections');
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