<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/12/8
 * Time: 上午 09:53
 */
use yii\helpers\Url;
use app\classes\Menu;
?>
<div class="table-head mb-10">
    <p><?=$this->title?></p>
    <div class="float-right">
        <?php if(Menu::isAction('/warehouse/logisticsorder/update')){?>
            <a id="edit_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="update-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
                </div>
            </a>
            <p style="float: left;display:none;">&nbsp;|&nbsp;</p>
        <?php }?>
            <a id="check_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="submit-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;送审</p>
                </div>
            </a>
            <p style="float: left;display:none;">&nbsp;|&nbsp;</p>
        <a href="<?=Url::to(['/index/index'])?>">
            <div style="height: 23px;width: 55px;float: left">
                <p class="return-item-bgc" style="float: left;"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
            </div>
        </a>
    </div>
</div>
<script>
$(function () {
    //修改
    $("#edit_btn").click(function(){
        var rows=$("#data").datagrid("getSelected");
        var type = rows.sr_type;
        var id=rows.ord_lg_id;
        location.href = "<?=Url::to('update')?>?id="+id;
    });
    //送审
    $("#check_btn").click(function () {
        var row = $("#data").datagrid("getSelected");
        var id = row.ord_lg_id;
        var url = "<?=Url::to(['index'], true)?>";
        var type = 61;//审核单据类型
        $.fancybox({
            href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
            type: "iframe",
            padding: 0,
            autoSize: false,
            width: 750,
            height: 480,
            afterClose: function () {
                location.reload();
            }
        });
    });

})
</script>
