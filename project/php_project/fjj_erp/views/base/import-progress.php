<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/5
 * Time: 下午 02:24
 */
?>
<html>
    <body>
    <script>parent.$('#progressbar_div').progressbar({value:<?=$percent?>})</script>
    <script>document.body.innerHTML=""</script>
    <p id="counter-info" class="mt-10" style="text-align: center;font-size:12px;">
        成功:<span class="success"><?=$success?></span>
        失败:<span class="error"><?=$error?></span>
        总数:<span class="total"><?=$total?></span>
    </p>
    </body>
</html>
