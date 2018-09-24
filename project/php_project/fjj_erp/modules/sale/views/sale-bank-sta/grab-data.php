<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2018/1/27
 * Time: 上午 08:14
 */
use yii\helpers\Url;
\app\assets\JeDateAsset::register($this);
?>
<h1 class="head-first">抓取数据</h1>
<div style="margin-top: 70px;font-size: 14px;text-align: center;">
    <label>请选择数据时间：</label>
    <input type="text" id="startDate" class="Wdate value-align " style="width: 180px;"
           onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd', maxDate: '%y-%M-%d %H:%m' })"
           name="startDate"
           readonly="readonly"
    >
</div>
<div style="margin-top: 40px;text-align:center">
    <button class="button-blue-big" id="getdata">抓取数据</button>
</div>
<script>
    $(function () {
        $("#getdata").click(function () {
            var date = $("#startDate").val();
            if(date=="")
            {
                alert("抓取日期不能为空!");
                return false;
            }
            $("#getdata").attr('disabled', 'disbaled');
            $.ajax({
                type: "POST",
                dataType: "json",
                data: {date: date},
                url: "<?=Url::to(['grab-data']) ?>",
                success: function (res) {
                    if (res.flag == 1) {
                        layer.alert(res.msg, {icon: 2});
                    }
                    else {
                        layer.alert(res.msg, {icon: 2});
                    }
                    $("#getdata").attr('disabled', false);
                },
                error: function (res) {
                    layer.alert("抓取失败!",{icon: 2});
                    $("#getdata").attr('disabled', false);
                }
            });
        })
    })
</script>


