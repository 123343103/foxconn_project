<?php
/**
 * User: F1677929
 * Date: 2017/12/5
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
?>
<div class="table-head mb-10">
    <p>收货中心列表</p>
    <div class="float-right">
        <?php if(Menu::isAction('/warehouse/receipt-center-set/add')){?>
            <a id="add_btn">
                <div style="height: 23px;float: left;">
                    <p class="add-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
                </div>
            </a>
            <p style="float: left;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/warehouse/receipt-center-set/edit')){?>
            <a id="edit_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="update-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
                </div>
            </a>
            <p style="float: left;display:none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/warehouse/receipt-center-set/enabled')){?>
            <a id="enabled_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="submit-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;启用</p>
                </div>
            </a>
            <p style="float: left;display:none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/warehouse/receipt-center-set/disabled')){?>
            <a id="disabled_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="setbcg9" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;禁用</p>
                </div>
            </a>
            <p style="float: left;display:none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/warehouse/receipt-center-set/export')){?>
            <a id="export_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="export-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
                </div>
            </a>
            <p style="float: left;display:none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <a href="<?=Url::to(['/index/index'])?>">
            <div style="height: 23px;width: 55px;float: left">
                <p class="return-item-bgc" style="float: left;"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
            </div>
        </a>
    </div>
</div>
<script>
    $(function(){
        //新增
        $("#add_btn").click(function(){
            $.fancybox({
                href:"<?=Url::to(['add'])?>",
                type:"iframe",
                padding:0,
                autoSize:false,
                width:600,
                height:400
            });
        });

        //修改
        $("#edit_btn").click(function(event){
            var row=$("#table1").datagrid("getSelected");
            editFun(row.rcp_id,event);
        });

        //启用禁用操作
        $("#enabled_btn,#disabled_btn").click(function(){
            var rows=$("#table1").datagrid("getChecked");
            var id='';
            $.each(rows,function(i,n){
                id+=n.rcp_id+'-';
            });
            id=id.substr(0,id.length-1);
            var flag="";
            if(this.id=="enabled_btn"){
                flag="enabled";
            }
            if(this.id=="disabled_btn"){
                flag="disabled";
            }
            operationFun(id,flag);
        });

        //导出
        $("#export_btn").click(function () {
            layer.confirm('确定导出吗？',{icon:2},
                function(){
                    layer.closeAll();
                    var url="<?=Url::to(['export'])?>";
                    url+='?rcp_no='+$("#rcp_no").val();
                    url+='&rcp_name='+$("#rcp_name").val();
                    url+='&contact='+$("#contact").val();
                    location.href=url;
                },layer.closeAll()
            );
        });
    })
</script>