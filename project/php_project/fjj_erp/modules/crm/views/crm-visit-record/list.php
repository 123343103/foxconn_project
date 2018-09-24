<?php
/**
 * User: F1677929
 * Date: 2017/3/29
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
$this->title='拜访记录管理明细表';
$this->params['homeLike']=['label'=>'客户关系管理'];
$this->params['breadcrumbs'][]=['label'=>'拜访记录管理','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <?=$this->render('_list-search',['data'=>$data])?>
    <div class="table-head mb-10">
        <p>拜访记录明细表</p>
        <div class="float-right">
            <?php if(Menu::isAction('/crm/crm-visit-record/export')){?>
                <a id="export_btn">
                    <div style="height: 23px;width: 55px;float: left;">
                        <p class="export-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
            <?php }?>
            <a href="<?=Url::to(['index'])?>">
                <div style="height: 23px;width: 55px;float: left">
                    <p class="return-item-bgc" style="float: left;"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
                </div>
            </a>
        </div>
    </div>
    <div id="list_table" style="width:100%;"></div>
</div>
<script>
    $(function(){
        $("#list_table").datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url;?>",
            rownumbers:true,
            method:"get",
            singleSelect:true,
            pagination:true,
            columns:[[
                <?=$data['listTable']?>
            ]],
            onLoadSuccess:function(data){
                datagridTip("#list_table");
                showEmpty($(this),data.total,0);
                setMenuHeight();
            }
        });

        //数据导出
        $("#export_btn").click(function(){
            var obj=$("#list_table").datagrid('getData');
            if(obj.total==0){
                layer.alert('不可导出！',{icon:2,time:5000});
                return false;
            }
            layer.confirm('确定导出吗？',{icon:2},
                function(){
                    layer.closeAll();
                    var url="<?=Url::to(['list'])?>";
                    url+='?export=1';
                    url+='&cust_sname='+$("#cust_sname").val();
                    url+='&cust_type='+$("#cust_type").val();
                    url+='&sil_type='+$("#sil_type").val();
                    location.href=url;
                },
                layer.closeAll()
            );
        });
    })
</script>