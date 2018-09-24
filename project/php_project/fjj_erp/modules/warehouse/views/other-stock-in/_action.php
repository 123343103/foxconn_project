<?php
/**
 * User: F1677929
 * Date: 2017/9/25
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
?>
<div class="table-head mb-10">
    <p><?=$this->title?></p>
    <div class="float-right">
        <?php if(Menu::isAction('/warehouse/other-stock-in/add')){?>
            <a id="add_btn">
                <div style="height: 23px;float: left;">
                    <p class="add-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
                </div>
            </a>
            <p style="float: left;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/warehouse/other-stock-in/edit')){?>
            <a id="edit_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="update-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
                </div>
            </a>
            <p style="float: left;display:none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/warehouse/other-stock-in/check')){?>
            <a id="check_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="submit-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;送审</p>
                </div>
            </a>
            <p style="float: left;display:none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/warehouse/other-stock-in/cancel')){?>
            <a id="cancel_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="setting11" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;取消</p>
                </div>
            </a>
            <p style="float: left;display:none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/warehouse/other-stock-in/put-away')){?>
            <a id="put_away_btn" style="display: none;">
                <div style="height: 23px;float: left;">
                    <p class="add-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;上架</p>
                </div>
            </a>
            <p style="float: left;display: none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/warehouse/other-stock-in/export')){?>
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
            location.href="<?=Url::to(['add'])?>";
        });

        //修改
        $("#edit_btn").click(function(){
            var row=$("#table1").datagrid("getSelected");
            location.href="<?=Url::to(['edit'])?>?id="+row.invh_id;
        });

        //送审
        $("#check_btn").click(function(){
            var row=$("#table1").datagrid("getSelected");
            var id=row.invh_id;
            var url="<?=Url::to(['list'],true)?>";
            var type=row.inout_flag;
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:750,
                height:480,
                afterClose:function(){
                    location.reload();
                }
            });
        });

        //取消
        $("#cancel_btn").click(function(){
            var rows=$("#table1").datagrid("getChecked");
            var id='';
            $.each(rows,function(i,n){
                id+=n.invh_id+'-';
            });
            id=id.substr(0,id.length-1);
            cancelFun(id);
        });

        //上架
        $("#put_away_btn").click(function(){
            var row=$("#table1").datagrid("getSelected");
            $.fancybox({
                href:"<?=Url::to(['put-away'])?>?id="+row.invh_id,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:800,
                height:600
            });
        });

        //导出
        $("#export_btn").click(function () {
            layer.confirm('确定导出吗？',{icon:2},
                function(){
                    layer.closeAll();
                    var url="<?=Url::to(['export'])?>";
                    url+='?val1='+$("#val1").val();
                    url+='&val2='+$("#val2").val();
                    url+='&val3='+$("#val3").val();
                    url+='&val4='+$("#val4").val();
                    url+='&val5='+$("#val5").val();
                    url+='&val6='+$("#val6").val();
                    url+='&val7='+$("#val7").val();
                    location.href=url;
                },layer.closeAll()
            );
        });
    })
</script>