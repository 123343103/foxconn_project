<?php
/**
 * User: F1677929
 * Date: 2016/10/27
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
use yii\helpers\Html;
$this->title='公共参数详情';
$this->params['homeLike']=['label'=>'系统平台设置'];
$this->params['breadcrumbs'][]=['label'=>'系统公用参数设置','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <div class="table-head" style="margin-bottom:5px;">
        <p>公共参数详情</p>
<!--        <p class="float-right">-->
        <div class="float-right">
            <?=Menu::isAction('/common/public-data/add')?
                '<a id="add_btn">
                    <div class="table-nav">
                        <p class="add-item-bgc float-left"></p>
                        <p class="nav-font">新增</p>
                    </div>
                </a><span class="float-left wd-tc-10">|</span>'
                : '' ?>
<!--                Html::a("<span class='ml--5'>新增</span>",null,['id'=>'add_btn']):''?>-->
            <?=Menu::isAction('/common/public-data/edit')?
                '<a id="edit_btn" >
                    <div class="table-nav">
                        <p class="update-item-bgc float-left"></p>
                        <p class="nav-font">修改</p>
                    </div>
                    <p class="float-left">&nbsp;|&nbsp;</p>
                </a>'
                :'' ?>
<!--                Html::a("<span class='ml--5'>修改</span>",null,['id'=>'edit_btn']):''?>-->
            <?=Menu::isAction('/common/public-data/delete')?
                '<a id="delete_btn">
                    <div class="table-nav">
                        <p class="delete-item-bgc float-left"></p>
                        <p class="nav-font">删除</p>
                    </div>
                    <p class="float-left">&nbsp;|&nbsp;</p>
                </a>'
                :'' ?>
<!--                Html::a("<span class='ml--5'>刪除</span>",null,['id'=>'delete_btn']):''?>-->
            <a href="<?= Url::to(['index']) ?>">
                <div class='table-nav'>
                    <p class='return-item-bgc float-left'></p>
                    <p class='nav-font'>返回</p>
                </div>
            </a>

<!--        </p>-->
        </div>
    </div>
    <div id="view_data" style="width:100%;"></div>
</div>
<script>
    $(function(){
        $("#view_data").datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url;?>",
//            rownumbers:true,
            method:"get",
            pagination:true,
            singleSelect:true,
            pageSize:10,
            pageList:[10,20,30],
            columns:[[
                {field:"bsp_sname",title:"参数名称",width:190},
                {field:"bsp_stype",title:"参数代码",width:311},
                {field:"bsp_svalue",title:"参数值",width:311},
                {field:"bsp_status",title:"状态",width:150,formatter:function (value, row, index) {
                    if (row.bsp_status == 10){
                        return '启用';
                    }else if (row.bsp_status == 11){
                        return '禁用';
                    }else {
                        return '';
                    }
                }}
            ]],
            onLoadSuccess:function(data){
                console.log(data);
                showEmpty($(this),data.total,0);
                setMenuHeight();
            }
        });

        //新增
        $("#add_btn").click(function(){
            $.fancybox({
                href:"<?=Url::to(['add','val'=>$val])?>",
                type:"iframe",
                padding:0,
                autoSize:false,
                width:450,
                height:280,
                fitToView:false
            });
        });

        //修改
        $("#edit_btn").click(function(){
            var obj=$("#view_data").datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择一条数据！',{icon:2,time:5000});
                return false;
            }
            $.fancybox({
                href:"<?=Url::to(['edit'])?>?id="+obj.bsp_id,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:450,
                height:280,
                fitToView:false
            });
        });

        //删除
        $("#delete_btn").click(function(){
            var obj=$("#view_data").datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择一条数据！',{icon:2,time:5000});
                return false;
            }
            layer.confirm('确定删除吗？',{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['delete-name']);?>",
                        data:{"id":obj.bsp_id},
                        dataType:"json",
                        success:function(data){
                            if(data.flag==1){
                                layer.alert(data.msg,{icon:1},function(){
                                    layer.closeAll();
                                    $("#view_data").datagrid('reload').datagrid('clearSelections');
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