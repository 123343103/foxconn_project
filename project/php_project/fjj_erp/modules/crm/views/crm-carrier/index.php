<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
$this->title='载体名称设置列表';
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
        <p>载体名称列表</p>
        <div class="float-right">
            <?php if(Menu::isAction('/crm/crm-carrier/add')){?>
                <a id="add_btn">
                    <div style="height: 23px;width: 55px;float: left;">
                        <p class="add-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-carrier/edit')){?>
                <a id="edit_btn">
                    <div style="height: 23px;width: 55px;float: left;">
                        <p class="update-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-carrier/delete')){?>
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
    <div id="carrier_table" style="width:100%;"></div>
</div>
<script>
    function deleteCarrierName(id){
        layer.confirm('确定删除吗？',{icon:2},
            function(){
                $.ajax({
                    url:"<?=Url::to(['delete-carrier']);?>",
                    data:{"id":id},
                    dataType:'json',
                    success:function(data){
                        if(data.flag==1){
                            layer.alert(data.msg,{icon:1},function(){
                                layer.closeAll();
                                $("#carrier_table").datagrid('load').datagrid('clearSelections');
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
        //jquery变量
        var $carrierTable=$("#carrier_table");

        //datagrid表格
        $carrierTable.datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url;?>",
            rownumbers:true,
            method:"get",
            singleSelect:true,
            pagination:true,
            columns:[[
                {field:'ck',checkbox:true},
                {field:"cc_code",title:"编码",width:150},
                {field:"cc_name",title:"载体名称",width:150},
                {field:"carrierType",title:"载体",width:200},
                {field:"saleWay",title:"所属社群营销方式",width:200},
                {field:"carrierStatus",title:"状态",width:60},
                {field:"createBy",title:"档案建立人",width:80},
                {field:"create_at",title:"建档日期",width:130},
                {field:"updateBy",title:"最后修改人",width:80},
                {field:"update_at",title:"修改日期",width:130},
                {field:'cc_id',title:'操作',width:60,formatter:function(value,rowData){
                    var str="";
                    if(rowData.del_flag==1){
                        <?php if(Menu::isAction('/crm/crm-carrier/delete')){?>
                        str+="<a class='icon-trash icon-large' style='margin-right:15px;' title='删除' onclick='event.stopPropagation();deleteCarrierName("+value+");'></a>";
                        <?php }?>
                    }
                    <?php if(Menu::isAction('/crm/crm-carrier/edit')){?>
                    str+="<a class='icon-edit icon-large' title='修改' onclick='location.href=\"<?=Url::to(['edit'])?>?id="+value+"\";event.stopPropagation();'></a>";
                    <?php }?>
                    return str;
                }}
            ]],
            onLoadSuccess:function(data){
                datagridTip("#carrier_table");
                showEmpty($(this),data.total,0);
//                setMenuHeight();
            }
        });

        //查询
        $("#query_btn").click(function(){
            $carrierTable.datagrid('load',{
                "keyword":$("#keyword").val()
            });
        });
        $(document).keydown(function(event){
            if(event.keyCode==13){
                $carrierTable.datagrid('load',{
                    "keyword":$("#keyword").val()
                });
            }
        });

        //重置
        $("#reset_btn").click(function(){
            $("#keyword").val('');
            $carrierTable.datagrid('load',{
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
                height:320,
                fitToView:false
            });
        });

        //修改
        $("#edit_btn").click(function(){
            var obj=$carrierTable.datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择载体！',{icon:2,time:5000});
                return false;
            }
            $.fancybox({
                href:"<?=Url::to(['edit'])?>?id="+obj.cc_id,
                type:'iframe',
                padding:0,
                autoSize:false,
                width:480,
                height:360,
                fitToView:false
            });
        });

        //删除
        $("#delete_btn").click(function(){
            var obj=$carrierTable.datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择载体！',{icon:2,time:5000});
                return false;
            }
            layer.confirm('确定删除吗？',{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['delete-carrier']);?>",
                        data:{"id":obj.cc_id},
                        dataType:'json',
                        success:function(data){
                            if(data.flag==1){
                                layer.alert(data.msg,{icon:1},function(){
                                    layer.closeAll();
                                    $carrierTable.datagrid('load').datagrid('clearSelections');
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