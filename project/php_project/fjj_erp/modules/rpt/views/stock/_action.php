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
    <p></p>
    <div class="float-right">
            <a id="export_btn">
                <div style="height: 23px;float: left;">
                    <p class="export-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
                </div>
            </a>
            <p style="float: left;">&nbsp;|&nbsp;</p>
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
        //导出
        $("#export_btn").click(function () {
            layer.confirm('确定导出吗？',{icon:2},
                function(){
                    layer.closeAll();
                    var url="<?=Url::to(['export'])?>";
                    url+='?company='+$("#company").val();
                    url+='&wh_name='+$("#wh_name").val();
                    url+='&tp_spec='+$("#tp_spec").val();
                    url+='&part_no='+$("#part_no").val();
                    location.href=url;
                },layer.closeAll()
            );
        });
    })
</script>