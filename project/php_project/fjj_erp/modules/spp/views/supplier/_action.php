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
        <?php if(Menu::isAction('/spp/supplier/add')){?>
            <a href="<?=Url::to(['add'])?>">
                <div style="height: 23px;float: left;">
                    <p class="add-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
                </div>
            </a>
            <p style="float: left;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/spp/supplier/edit')){?>
            <a id="edit_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="update-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
                </div>
            </a>
            <p style="float: left;display:none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/spp/supplier/delete')){?>
            <a id="delete_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="delete-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;删除</p>
                </div>
            </a>
            <p style="float: left;display:none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/spp/supplier/check')){?>
            <a id="check_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="submit-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;送审</p>
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
        //修改
        $("#edit_btn").click(function(){
            var row=$("#supplier_table").datagrid("getSelected");
            location.href="<?=Url::to(['edit'])?>?id="+row.spp_id;
        });

        //删除
        $("#delete_btn").click(function(){
            layer.confirm('确定删除吗？',{icon:2},
                function(){
                    var rows=$("#supplier_table").datagrid("getChecked");
                    var id='';
                    $.each(rows,function(i,n){
                        id+=n.spp_id+'-';
                    });
                    id=id.substr(0,id.length-1);
                    $.ajax({
                        url:"<?=Url::to(['delete-supplier'])?>",
                        data:{"id":id},
                        dataType:"json",
                        success:function(data){
                            if(data.flag == 1){
                                layer.alert(data.msg,{icon:1,end:function(){
                                    $("#supplier_table").datagrid("reload");
                                }});
                            }
                        }
                    });
                },layer.closeAll()
            );
        });

        //送审
        $("#check_btn").click(function(){
            var row=$("#supplier_table").datagrid("getSelected");
            var id=row.spp_id;
            var url="<?=Url::to(['index'],true)?>";
            var type=row.type_id;
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
    })
</script>