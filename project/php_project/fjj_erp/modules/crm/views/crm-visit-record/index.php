<?php
/**
 * User: F1677929
 * Date: 2017/3/29
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use app\classes\Menu;

$this->title = '拜访记录管理';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <?= $this->render('_search', ['data' => $data]) ?>
    <div class="table-head mb-10">
        <p>客户信息列表</p>
        <div class="float-right">
            <?php if(Menu::isAction('/crm/crm-visit-record/add')){?>
                <a id="add_btn">
                    <div style="height: 23px;float: left;">
                        <p class="add-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增记录</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-visit-record/list')){?>
                <a href="<?=Url::to(['list'])?>">
                    <div style="height: 23px;float: left;">
                        <p class="setting10" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;切换明细表</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-visit-record/export')){?>
                <a id="export_btn">
                    <div style="height: 23px;float: left;">
                        <p class="export-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <a href="<?=Url::to(['/index/index'])?>">
                <div style="height: 23px;float: left">
                    <p class="return-item-bgc" style="float: left;"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
                </div>
            </a>
        </div>
    </div>
    <div id="record_main" style="width:100%;"></div>
    <div id="record_child_title"></div>
    <div id="record_child" style="width:100%;"></div>
</div>
<script>
    function deleteRecord(id){
        layer.confirm('确定删除吗？', {icon: 2},
            function () {
                $.ajax({
                    url: "<?=Url::to(['delete-child']);?>",
                    data: {"childId": id},
                    dataType: "json",
                    success: function (data) {
                        if (data.flag == 1) {
                            layer.alert(data.msg, {icon: 1}, function () {
                                layer.closeAll();
                                if (data.total == 1) {
                                    $("#record_main").datagrid('reload').datagrid('clearSelections');
                                }
                                $("#record_child").datagrid('reload').datagrid('clearSelections');
                            });
                        } else {
                            layer.alert(data.msg, {icon: 2});
                        }
                    }
                })
            },
            layer.closeAll()
        );
    }

    $(function () {
        var flag = '';//记录子表选中标志
        $("#record_main").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url;?>",
            rownumbers: true,
            method: "get",
            singleSelect: true,
            pagination: true,
            columns: [[
                <?=$data['mainTable']?>
            ]],
            onLoadSuccess: function (data) {
                if(data.total === 0){
                    $("#export_btn").hide().next().hide();
                }else{
                    $("#export_btn").show().next().show();
                }
                datagridTip("#record_main");
                showEmpty($(this),data.total,0);
                setMenuHeight();
            },
            onSelect: function (rowIndex, rowData) {
                $("#record_child_title").addClass("table-head").css({"margin-bottom":"5px","margin-top":"20px"}).html("<p class='head'>拜访记录列表</p>");
                $("#record_child").datagrid({
                    url: "<?= Url::to(['load-record']) ?>",
                    queryParams: {"mainId":rowData.sih_id},
                    rownumbers: true,
                    method: "get",
                    singleSelect: true,
                    pagination: true,
                    columns: [[
                        <?=$data['childTable']?>
                        {field:'sil_id',title:'操作',width:60,formatter:function(value,rowData){
                            var str="";
                            if(rowData.del_edit_flag==1){
                                <?php if(Menu::isAction('/crm/crm-visit-record/edit')){?>
                                str+="<a class='icon-edit icon-large' title='修改' onclick='location.href=\"<?=Url::to(['edit'])?>?childId="+value+"\";event.stopPropagation();'></a>";
                                <?php }?>
                            }
                            return str;
                        }}
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip("#record_child");
                        showEmpty($(this),data.total,0);
                        setMenuHeight();
                        $("#record_child").datagrid('clearSelections');
                    },
                    onSelect: function (rowIndex, rowData) {
                        if (rowData.sil_id == flag) {
                            $("#record_child").datagrid('clearSelections');
                            flag = '';
                        } else {
                            flag = rowData.sil_id;
                        }
                    }
                });
            }
        });

        //新增拜访记录
        $("#add_btn").click(function () {
            var obj = $("#record_main").datagrid('getSelected');
            if (obj == null) {
                window.location.href = "<?=Url::to(['add'])?>";
                return false;
            }
            if(obj.customerManager == ''){
                layer.alert("该客户无客户经理人，不可新增拜访记录！", {icon: 2, time: 5000});
                return false;
            }
            window.location.href = "<?=Url::to(['add'])?>?customerId=" + obj.cust_id;
        });

        //修改拜访记录
        $("#edit_btn").click(function () {
            var mainObj = $("#record_main").datagrid('getSelected');
            if (mainObj == null) {
                layer.alert("请选择客户！", {icon: 2, time: 5000});
                return false;
            }
            var childObj = $("#record_child").datagrid('getSelected');
            if (childObj == null) {
                layer.alert("请选择记录！", {icon: 2, time: 5000});
                return false;
            }
            if("<?=Yii::$app->user->identity->is_supper?>"=="1"){
                window.location.href="<?=Url::to(['edit'])?>?childId="+childObj.sil_id;
                return false;
            }
            $.ajax({
                url:"<?=Url::to(['record-new-judge'])?>",
                data:"childId="+childObj.sil_id,
                dataType:"json",
                success:function(data){
                    if(data.flag==0){
                        layer.alert(data.msg,{icon:2});
                    }else{
                        window.location.href="<?=Url::to(['edit'])?>?childId="+childObj.sil_id;
                    }
                }
            });
        });

        //查看
        $("#view_btn").click(function () {
            var mainObj = $("#record_main").datagrid('getSelected');
            if (mainObj == null) {
                layer.alert("请选择客户！", {icon: 2, time: 5000});
                return false;
            }
            var childObj = $("#record_child").datagrid('getSelected');
            if (childObj == null) {
                window.location.href = "<?=Url::to(['view-records'])?>?mainId=" + mainObj.sih_id;
                return false;
            }
            window.location.href = "<?=Url::to(['view-record'])?>?childId=" + childObj.sil_id;
        });

        //删除
        $("#delete_btn").click(function () {
            var mainObj = $("#record_main").datagrid('getSelected');
            if (mainObj == null) {
                layer.alert('请选择客户！', {icon: 2, time: 5000});
                return false;
            }
            var childObj = $("#record_child").datagrid('getSelected');
            if (childObj == null) {
                layer.alert('请选择记录！', {icon: 2, time: 5000});
                return false;
            }
            layer.confirm('确定删除吗？', {icon: 2},
                function () {
                    $.ajax({
                        url: "<?=Url::to(['delete-child']);?>",
                        data: {"childId": childObj.sil_id},
                        dataType: "json",
                        success: function (data) {
                            if (data.flag == 1) {
                                layer.alert(data.msg, {icon: 1}, function () {
                                    layer.closeAll();
                                    if (data.total == 1) {
                                        $("#record_main").datagrid('reload').datagrid('clearSelections');
                                    }
                                    $("#record_child").datagrid('reload').datagrid('clearSelections');
                                });
                            } else {
                                layer.alert(data.msg, {icon: 2});
                            }
                        }
                    })
                },
                layer.closeAll()
            );
        });

        //数据导出
        $("#export_btn").click(function () {
            var obj = $("#record_main").datagrid('getData');
            if (obj.total == 0) {
                layer.alert('不可导出！', {icon: 2, time: 5000});
                return false;
            }
            layer.confirm('确定导出吗？',{icon:2},
                function(){
                    layer.closeAll();
                    var url="<?=Url::to(['index'])?>";
                    url+='?export=1';
                    url+='&cust_sname='+$("#cust_sname").val();
                    url+='&cust_type='+$("#cust_type").val();
                    url+='&cust_salearea='+$("#cust_salearea").val();
                    location.href=url;
                },
                layer.closeAll()
            );
        });
    })
</script>