<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2018/1/4
 * Time: 下午 05:11
 */
?>
<style>
    .size-14 {
        font-size: 14px;
    }
    .box{
        width: 500px;
        height: 220px;
        background: #CCCCCC;
        margin: 0 auto;
        overflow:auto;
        overflow-y:scroll;
    }
    .tables tr td{
        border-bottom: 1px solid #eee;
        height: 30px;
    }
</style>
<h3 class="head-first" style="margin-bottom: 10px;">物流信息</h3>
<?php if (!empty($model)) { ?>
    <div class="size-14">
        <label style="width:100px;color:#0087FF;">快递：</label>
        <span style="width:115px;color:#0087FF;"><?= $model[0]['log_cmp_name'] ?></span>
        <label style="color:#0087FF;">电话：</label>
        <span id="wh_code" style="width:150px;color:#0087FF;"><?= $model[0]['log_cont_pho'] ?></span>
        <label style="color:#0087FF;" id="datetime"></label>
    </div>
    <div class="content">
        <div class="mb-10 size-14">
            <label style="width: 80px;">时间</label>
            <label style="width:310px;">地点和跟踪进度</label>
        </div>
        <div class="box">
            <table class="tables" style="width:480px;">
                <tbody id="log-data">
                <?php foreach ($model as $key=>$val){?>
                <tr><td style="width: 130px;"><?= $val['ONWAYSTATUS_DATE']?></td>
                    <td style="width: 40px;"></td>
                    <td style="width: 310px"><?= $val['STATION']?>|<?= $val['ONWAYSTATUS']?>|<?= $val['STATION']?></td>
                </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
        <div style="text-align:center;margin-top: 15px;">
            <button type="button" class="button-white" onclick="parent.$.fancybox.close()">确定</button>
        </div>
    </div>
<?php } else { ?>
    <div class="content">
        <div style="text-align:center;">该订单暂时无物流信息</div>
        <div style="text-align:center;">
            <button type="button" class="button-white" onclick="parent.$.fancybox.close()">确定</button>
        </div>
    </div>
<?php } ?>
<script>
    $(function () {
        var star_time=$("#log-data tr:first td:first").text();
        var end_time=$("#log-data tr:last td:first").text();
        var date1 = new Date(star_time);
        var date2 = new Date(end_time);

        var s1 = date1.getTime(),s2 = date2.getTime();
        var total = (s2 - s1)/1000;
        var day = parseInt(total / (24*60*60));//计算整数天数
        var afterDay = total - day*24*60*60;//取得算出天数后剩余的秒数
        var hour = parseInt(afterDay/(60*60));//计算整数小时数
        var afterHour = total - day*24*60*60 - hour*60*60;//取得算出小时数后剩余的秒数
        var min = parseInt(afterHour/60);//计算整数分
        var afterMin = total - day*24*60*60 - hour*60*60 - min*60;//取得算出分后剩余的秒数
        $("#datetime").html("已耗时"+day+"天"+hour+"小时");
    })
</script>
