<?php
/**
 * User: F1677929
 * Date: 2017/12/16
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
?>
<div class="table-head mb-10">
    <p>商品收货通知列表</p>
    <div class="float-right">
        <?php if(Menu::isAction('/warehouse/receipt-notice/generate-receipt-bill')){?>
            <a id="generate_btn" style="display: none;">
                <div style="height: 23px;float: left;">
                    <p class="add-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;生成收货单</p>
                </div>
            </a>
            <p style="float: left;display: none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/warehouse/receipt-notice/cancel-receipt')){?>
            <a id="cancel_btn" style="display: none;">
                <div style="height: 23px;float: left;">
                    <p class="setting11" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;取消收货</p>
                </div>
            </a>
            <p style="float: left;display: none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/warehouse/receipt-notice/export')){?>
            <a id="export_btn" style="display: none;">
                <div style="height: 23px;float: left;">
                    <p class="export-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
                </div>
            </a>
            <p style="float: left;display: none;">&nbsp;|&nbsp;</p>
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
        //生成收货单
        $("#generate_btn").click(function(){
            var row=$("#table1").datagrid("getSelected");
            $.fancybox({
                href:"<?=Url::to(['generate-receipt-bill'])?>?id="+row.rcpnt_id,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:800,
                height:600
            });
        });

        //取消收货
        $("#cancel_btn").click(function(){
            var rows=$("#table1").datagrid("getChecked");
            var id='';
            $.each(rows,function(i,n){
                id+=n.rcpnt_id+'-';
            });
            id=id.substr(0,id.length-1);
            cancelFun(id);
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
                    location.href=url;
                },layer.closeAll()
            );
        });
    })
</script>