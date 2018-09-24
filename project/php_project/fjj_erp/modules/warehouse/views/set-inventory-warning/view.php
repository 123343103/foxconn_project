<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

$this->params['homeLike'] = ['label' => '库存预警/报废通知员详情', 'url' => ''];
$this->params['breadcrumbs'][] = ['label' => '库存预警'];
$this->params['breadcrumbs'][] = ['label' => '库存预警人员/报废通知列表'];
$this->params['breadcrumbs'][] = ['label' => '库存预警/报废通知人员详情'];
$this->title = '库存预警/报废通知人员详情';
?>
<div class="content">
    <h1 class="head-first">
        库存预警通知员详情
    </h1>

    <div class="mb-30">
        <div class="border-bottom mb-20">
            <?php if($hrstaffinfo['so_type']==10||$hrstaffinfo['so_type']==50||$hrstaffinfo['so_type']==40){?>
                <?= Html::button('修改', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["update"]) .'?id=' . $hrstaffinfo['LIW_PKID'] . '\'']) ?>
                <?= Html::button('送审', ['class' => 'button-blue width-80', 'id'=>'check']) ?>
            <?php } ?>
            <?= Html::button('切换列表', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
            <?= Html::button('返回', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["/index/index"]) . '\'']) ?>
        </div>
    </div>

    <div class="mb-30">
        <h2 class="head-second" style="text-align: center">
            <p>预警人员基本信息</p>
        </h2>
        <table width="80%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">工号:</td>
                <td class="no-border vertical-center" width="20%"><?= $hrstaffinfo['staff_code']?></td>

                <td class="no-border vertical-center" width="13%">姓名:</td>
                <td class="no-border vertical-center" width="20%"><?= $hrstaffinfo['staff_name']?></td>

                <td class="no-border vertical-center" width="13%">手机:</td>
                <td class="no-border vertical-center" width="20%"><?= $hrstaffinfo['staff_mobile']?></td>
            </tr>
        </table>

        <table width="80%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">邮箱:</td>
                <td class="no-border vertical-center" width="20%"><?= $hrstaffinfo['staff_email']?></td>
                <td class="no-border vertical-center" width="13%">操作人:</td>
                <td class="no-border vertical-center" width="20%"><?= $hrstaffinfo['OPPER']?></td>
                <td class="no-border vertical-center" width="13%">最后操作时间:</td>
                <td class="no-border vertical-center" width="20%"><?= $hrstaffinfo['OPP_DATE']?></td>
            </tr>
        </table>

    </div>

    <div class="mb-30">
        <h2 class="head-second" style="text-align: center">
            <p>预警人员仓库信息</p>
        </h2>
        <div class="mb-10" >
            <label class="width-120 no-after" style="text-align: left">所负责的商品信息：</label>
            <table class="mb-10 " style="text-align: center">
                <tr class="height-30">
                    <th class="width-140">序号</th>
                    <th class="width-140">仓库</th>
                    <th class="width-140">商品类别</th>
                    <th class="width-140">料号</th>
                    <th class="width-140">商品名称</th>
                    <th class="width-140">品牌</th>
                    <th class="width-140">规格型号</th>
                    <th class="width-140">库存上限</th>
                    <th class="width-140">现有库存</th>
                    <th class="width-140">库存下限</th>
                </tr>
                <tbody>

                <?php foreach ($whinfo as $key=>$val){ ?>
                    <tr class="height-30">
                        <td><?= $key+=1 ?></td>
                        <td><?= $val['wh_name']?></td>
                        <td><?= $val['category_sname']?></td>
                        <td><?= $val['part_no']?></td>
                        <td><?= $val['pdt_name']?></td>
                        <td><?= $val['BRAND_NAME_CN']?></td>
                        <td><?= $val['pdt_model']?></td>
                        <td><?= $val['up_nums']?></td>
                        <td><?= $val['invt_num']?></td>
                        <td><?= $val['down_nums']?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="mb-20">
        <label>备注</label>
        <span><?= $hrstaffinfo['remarks']?></span>
    </div>
    <?php if (!empty($verify)){ ?>
        <div >
            <h2 class="head-second" style="text-align: center">
                <p>审核进度</p>
            </h2>
            <table class="mb-30 product-list" style="width:990px;">
                <thead>
                <tr>
                    <th class="width-60">序号</th>
                    <th class="width-70">签核节点</th>
                    <th class="width-60">签核人员</th>
                    <th>签核日期</th>
                    <th class="width-60">操作</th>
                    <th>签核意见</th>
                    <th>签核人IP</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($verify as $key=>$val){ ?>
                    <tr>
                        <th><?= $key+1 ?></th>
                        <th><?= $val['verifyOrg'] ?></th>
                        <th><?= $val['verifyName'] ?></th>
                        <th><?= $val['vcoc_datetime'] ?></th>
                        <th><?= $val['verifyStatus'] ?></th>
                        <th><?= $val['vcoc_remark'] ?></th>
                        <th><?= $val['vcoc_computeip'] ?></th>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>
<script>
    $(function () {
        var isApply = "<?= $isApply ?>";
        var id="<?=$id?>"
        if (isApply == 1) {
            var url="<?=Url::to(['view'],true)?>?id="+id;
            var type=46;
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:750,
                height:480
            });
        }
        $("#check").on("click",function(){
            var id = <?=$hrstaffinfo['LIW_PKID']?>;
            var url="<?=Url::to(['view'],true)?>?id="+<?=$hrstaffinfo['LIW_PKID']?>;
            var type=46;
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:750,
                height:480
            });
        });
    })

</script>



