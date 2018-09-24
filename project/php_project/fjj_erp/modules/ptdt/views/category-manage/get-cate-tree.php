<?php
/**
 *  F1677943
 * 2017/10/14
 */
use app\assets\MultiSelectAsset;
use app\assets\TreeAsset;
use yii\helpers\Url;

MultiSelectAsset::register($this);
TreeAsset::register($this);
$this->params['homeLike'] = ['label' => '商品开发管理'];
$this->params['breadcrumbs'][] = ['label' => '商品类别管理','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => $title['catg_name'] . '第三阶类别关联'];
$this->title = $title['catg_name'] . '类别关联设置';
?>

<style>
    #cover {
        display: none;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.44);
    }

    #coverShow {
        display: none;
        position: fixed;
        z-index: 2;
        top: 50%;
        left: 50%;
        border: 1px solid #fff;
        margin-top: -140px;
        background: #fff;
    }
</style>
<div class="content">

    <h1 class="head-first">
        <?= $title['catg_name'] ?>第三阶类别关联
    </h1>
    <div>
        <div style="width: 10%;float: left; ">
            关联类别名称有:
        </div>

        <div style="width: 88%;height: auto;margin-left: 10%;">
            <?php foreach ($list as $key => $val) { ?>
                <?= $val['name1'] ?>-><?= $val['name2'] ?>-><?= $val['name3'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php } ?>
        </div>
        <div style="clear: both"></div>
    </div>

    <?php echo $this->render('tree', ['catgid' => $catgid]); ?>
    <div class="mb-20 text-center">
        <button class="button-blue-big" type="button" id="submit" onclick="submit()">确定</button>
        <button class="button-white-big ml-20" type="button" id="back">返回</button>
    </div>


    <div id="cover"></div>
    <div id="coverShow">
        <table align="center" border="0" cellspacing="0" cellpadding="0"
               style="border-collapse: collapse; height: 30px; min-height: 30px;">
            <tr>
                <td height="30" style="font-size: 12px;">数据加载中，请稍后......</td>
            </tr>

        </table>
    </div>
</div>
<script>
    function submit() {
        var id = $("#catg_id").val();
        var rid = [];
        $(".tree-checkbox1").each(function () {
            var s = $(this).next();
            var a = s[0].childNodes[2].innerText;
            if (a == 3) {
                rid.push(s[0].childNodes[1].innerText);
            }
        })
        var check1 = check(id, rid);
        if (check1 == 1) {
                //判断删除的数据有没有被使用
                $.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: "<?= Url::to(["save"])?>",
                    async: false,
                    data: {
                        id: id,
                        rid: rid.join()
                    },
                    success: function (data) {
                        if (data == 1) {
                            layer.alert("保存成功", {icon: 2, time: 3000});
                            window.location.href = "<?=Url::to(['index'])?>";


                        } else {
                            layer.alert("保存出现错误,保存失败!", {icon: 2})
                        }
                    },
                    error: function (xhr, type) {
                        layer.alert("保存出现错误,保存失败!", {icon: 2})
                    }
                });


        } else {
            layer.alert(check1 + "被应用 无法删除!", {icon: 2});
        }
    }
    function check(id, rid) {
        var a = 0;
        $.ajax({
                type: 'get',
                url: "<?= Url::to(["check"])?>?id=" + id + "&rid=" + rid,
                async: false,
                success: function (data) {
                    a = data;
                },
                error: function (xhr, type) {
                }
            }
        );
        return a;
    }
</script>

