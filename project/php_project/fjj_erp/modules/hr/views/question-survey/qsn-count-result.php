<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title='问卷统计结果';
$this->params['homeLike']=['label'=>'人事管理'];
$this->params['breadcrumbs'][]=['label'=>'问卷列表','url' => "index"];
$this->params['breadcrumbs'][]=$this->title;
?>
<style>
    ._prolength
    {
        width: 225px;
        height: 19px;
        margin-left: 50px;
        float: left;
    }
    .text-centers
    {
        margin-left: 180px;
    }
</style>
<div class="content">
    <h1 class="head-first">问卷统计结果</h1>
    <div class="m-10">
        <label class="width-110">问卷主题：<?php echo $model["bsqsn"]['query']['invst_subj']?></label>
        <span class="width-200 text-top"></span>
    </div>
    <div class="m-10">
        <label class="width-110 djsl">答卷数量：<?php echo $model["bsqsn"]['query']['clo_nums']?>份</label>
        <span class="width-200 text-top"></span>
    </div>
    <?php $_p=1;?>
    <?php for ($i=0;$i<count($b);$i=$i+3){;?>
    <div class="m-10">
        <label class="width-110">第&nbsp;&nbsp;<?php echo $_p ?>&nbsp;&nbsp; 题：</label>
        <span class="width-200 text-top"><?php echo $b[$i+1]?><?php echo $b[$i+2]?></span>
    </div>
    <div class="m-10">
        <?php if($b[$i+2]!="【文本】"){;?>
        <table>
            <thead>
                <th style="width: 182px;height: 36px">选项</th>
                <th style="width: 45px;">小计</th>
                <th style="width: 327px;">比例</th>
            </thead>
            <?php foreach ($model['rows'] as $items){;?>
                <?php if($b[$i]==$items['cnt_id']){;?>
            <tbody class="_bai_value">
                <tr style="height: 30px;">
                    <td style="text-align: center"><?php echo $items['opt_name']?></td>
                    <td style="text-align: center"><?php echo $items['opt_nums']?></td>
                    <td class="progressBarID"><div class="_prolength"><?php echo ($items['opt_rate'])*100?></div></td>
                </tr>
            </tbody>
                <?php };?>
            <?php };?>
        </table>
            <?php }else{;?>
                <div class="m-10" style="margin-bottom: 10px;">
                    <a onclick="checkanw(<?php echo $model['rows'][0]['invst_id']?>,<?php echo $b[$i]?>,<?php echo $_p?>,'<?php echo $b[$i+1] ?>')">【查看本题详细答案】</a>
                </div>
            <?php };?>
        <?php $_p++?>
        <?php };?>
    </div>
    <div style="margin-top: 50px;"></div>
    <div class="text-centers">
        <button class="button-white-big" onclick="history.go(-1);" type="button">返回问卷列表</button>
    </div>
</div>

<script>
    $(function () {
        $("._bai_value").find("._prolength").each(function () {
            $(this).progressbar({
                value: $(this).text()                                       //真正影响进度条的只有这一个参数
            });
        });
//        var id=$("._djfs").val();
//        $.ajax({
//            type: "get",
//            dataType: "json",
//            async: false,
//            data: {"id":id},
//            url: "<?//=\yii\helpers\Url::to(['/hr/question-survey/closes']) ?>//",
//            success: function (msg) {
//                if( msg.flag === 1){
//                    layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
//                }else{
//                    layer.alert(msg.msg,{icon:2})
//                }
//            },
//            error :function(msg){
//                layer.alert(msg.msg,{icon:2})
//            }
//        })
    });
    function checkanw(a,b,c,d) {
        $.fancybox({
            autoSize: false,
            fitToView: false,
            height: 370,
            width: 600,
            closeClick: true,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= \yii\helpers\Url::to(['load-answ'])?>?invstid="+a+"&cntid="+b+"&c="+c+"&d="+d+" "
        })
    }
</script>