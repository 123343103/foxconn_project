<?php
/**
 * User: F1677929
 * Date: 2017/8/2
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
$this->title='出入库流水查询列表';
$this->params['homeLike']=['label'=>'仓储物流管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <?=$this->render('_search',['downlist'=>$downlist])?>
    <div class="table-head" style="margin-bottom:10px;">
        <p>出入库流水查询列表</p>
        <div class="float-right">
            <?php if(Menu::isAction('/warehouse/inout-stock-query/export')){?>
            <a id="export_btn">
                <div style="height: 23px;width: 55px;float: left;">
                    <p class="export-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
                </div>
            </a>
            <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <a href="<?=Url::to(['/index/index'])?>">
                <div style="height: 23px;width: 55px;float: left">
                    <p class="return-item-bgc" style="float: left;"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
                </div>
            </a>
        </div>
    </div>
    <div id="product_table" style="width:100%;"></div>
</div>
<script>
    $(function(){
        $("#product_table").datagrid({
            url:"<?=Url::to(['index'])?>",
            rownumbers:true,
            method:"get",
            singleSelect:true,
            pagination:true,
            columns:[[
            <?=$data?>
            ]],
            onLoadSuccess: function (product_table) {
                datagridTip('#product_table');
                showEmpty($(this), product_table.total, 1);
            }
        });


        //导出
        $("#export_btn").click(function(){
            var productTable=$("#product_table").datagrid('getData');
            if(productTable.total==0){
                layer.alert('无数据，不可执行导出！',{icon:2,time:5000});
                return false;
            }
            layer.confirm('确定导出吗？',{icon:2},
                function(){
                    layer.closeAll();
                    var url="<?=Url::to(['index'])?>";
                    url+='?export=1';
                    url+='&invh_code='+$("#invh_code").val();
                    url+='&inout_flag='+$("#inout_flag").val();
                    url+='&invh_date_start='+$("#invh_date_start").val();
                    url+='&invh_date_end='+$("#invh_date_end").val();
                    url+='&business_code='+$("#business_code").val();
                    url+='&inout_type='+$("#inout_type").val();
                    url+='&wh_type='+$("#wh_type").val();
                    url+='&whs_id='+$("#whs_id").val();
                    url+='&lor_id='+$("#lor_id").val();
                    url+='&product_info='+$("#product_info").val();
                    url+='&ATTR_NAME='+$("#ATTR_NAME").val();
                    url+='&batch_no='+$("#batch_no").val();
                    url+='&create_by='+$("#create_by").val();
                    url+='&invh_status='+$("#invh_status").val();
                    location.href=url;
                },
                function(){
                    layer.closeAll();
                }
            );
        });
    })
</script>