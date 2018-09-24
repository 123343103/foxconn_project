<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/7/31
 * Time: 上午 09:40
 */
use yii\helpers\Url;
use app\classes\Menu;

if ($data['AppropriationInfo']['chh_statusH'] == '待提交')
{
    $this->title = '调拨单详情页';
}
else
{
    $this->title = '调拨单审核详情页';
}
$this->params['homeLike'] = ['label' => '仓储物流管理', 'url' => Url::to(['/index/index'])];
$this->params['breadcrumbs'][] = ['label' => '调拨单详情页', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <h1 class="head-first"><?= $this->title ?></h1>
    <div class="mb-10">
        <?php if ($data[0]['chh_status'] == '待提交' || $data[0]['chh_status'] == '驳回') { ?>
            <?php if (Menu::isAction('/warehouse/allocation/edit')) { ?>
                <button type="button" class="button-blue"
                        onclick="location.href='<?= Url::to(['edit', 'id' => $id]) ?>'">
                    修改
                </button>
            <?php } ?>
            <?php if (Menu::isAction('/warehouse/allocation/delete')) { ?>
                <button id="delete_btn" type="button" class="button-blue">删除</button>
            <?php } ?>
            <?php if (Menu::isAction('/warehouse/allocation/check')) { ?>
                <button id="check_btn" type="button" class="button-blue">送审</button>
            <?php } ?>
        <?php } ?>
        <?= Menu::isAction('/warehouse/allocation/index') ? "<button type='button' class='button-blue' style='width:80px;' onclick='location.href=\"" . Url::to(['index']) . "\"'>切换列表</button>" : '' ?>
<!--        <button type="button" class="button-blue" onclick="history.go(-1)">返回</button>-->
    </div>
    <h1 class="head-second" >调拨单基本信息</h1>
    <div style="margin:0 0 20px 40px;">
        <input type="hidden" id="chh_id" value="<?php echo $id ?>">
        <span style="width:90px;text-align:right;">调拨单号：</span>
        <span style="width:200px;"><?= $data[0]['chh_code'] ?></span>
        <span style="width:90px; text-align:right;">调拨类型：</span>
        <span style="width:200px;"><?= $data[0]['business_type_desc'] ?></span>
    </div>
    <div style="margin:0 0 20px 40px;">
        <span style="width:90px; text-align:right;">调拨单位：</span>
        <span style="width:200px;"><?= $data[0]['depart_id'] ?></span>
        <span style="width:90px; text-align:right;">单据状态：</span>
        <span style="width:200px;"><?= $data[0]['chh_status'] ?></span>
    </div>
    <div style="margin:0 0 20px 40px;">
        <span style="width:90px; text-align:right;">调出仓库：</span>
        <span style="width:200px;"><?= $data[0]['wh_id'] ?></span>
        <span style="width:90px; text-align:right;">出库状态：</span>
        <span style="width:200px;"><?= $data[0]['o_status'] ?></span>
    </div>
    <div style="margin:0 0 20px 40px;">
        <span style="width:90px;text-align:right;">调入仓库：</span>
        <span style="width:200px;"><?= $data[0]['wh_id2'] ?></span>
        <span style="width:90px; text-align:right;">入库状态：</span>
        <span style="width:200px;"><?= $data[0]['in_status'] ?></span>
    </div>
    <div style="margin:0 0 30px 40px;">
        <span style="width:90px; text-align:right;">制单人：</span>
        <span style="width:200px;"><?= $data[0]['create_by'] ?></span>
        <span style="width:90px;text-align:right;">制单日期：</span>
        <span style="width:200px;"><?= $data[0]['create_at'] ?></span>
    </div>
    <h1 class="head-second" >商品基本信息</h1>
    <div style="overflow:auto;">
        <table class="table" style="width:1380px;">
            <thead>
            <tr>
                <th style="width:30px;">序号</th>
                <th style="width:150px;">料号</th>
                <th style="width:150px;">商品名称</th>
                <th style="width:100px;">品牌</th>
                <th style="width:150px;">规格型号</th>
                <th style="width:150px;">批次</th>
                <th style="width:100px;">现有库存量</th>
                <th style="width:100px;">调拨数量</th>
                <th style="width:100px;">出仓储位</th>
<!--                <th style="width:100px;">入仓储位</th>-->
                <th style="width:100px;">单位</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($data)) { ?>
                <?php foreach ($data as $key => $val) { ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $val['part_no'] ?></td>
                        <td><?= $val['pdt_name'] ?></td>
                        <td><?= $val['brand'] ?></td>
                        <td><?= $val['tp_spec'] ?></td>
                        <td><?= $val['chl_bach'] ?></td>
                        <td><?= $val['before_num1'] ?></td>
                        <td><?= sprintf('%.2f', $val['chl_num']) ?></td>
                        <td><?= $val['st_id'] ?></td>
<!--                        <td>--><?//= $val['Ist_code'] ?><!--</td>-->
                        <td><?= $val['unit'] ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div style="height: 20px;"></div>
    <div class="mb-20" style="overflow: auto; margin-top: 10px">
        <?php if (!empty($verify)){ ?>
        <div >
            <h1 class="head-second mt-30" >签核记录</h1>
            <div>
                <table class="mb-30 product-list" style="width:990px;">
                    <thead>
                    <tr>
                        <th class="width-60">#</th>
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
    </div>
</div>
<script>
    $("#delete_btn").on("click", function () {
        var selectId = $("#chh_id").val();
        layer.confirm("确定要删除这条信息吗?",
            {
                btn: ['确定', '取消'],
                icon: 2
            },
            function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {
                        "id": selectId
                    },
                    url: "<?=Url::to(['/warehouse/allocation/delete']) ?>",
                    success: function (msg) {
                        if (msg.flag === 1) {
                            //console.log(msg.result);
                            layer.alert("删除成功！", {
                                icon: 1, end: function () {
                                    location.href="<?=Url::to(['index'])?>";
                                }
                            });
                        } else {
                            //console.log(msg.result);
                            layer.alert("删除失败！", {icon: 2});

                        }
                    },
                    error: function (msg) {
                        layer.alert("删除失败！", {icon: 2})
                    }
                })
            },
            function () {
                layer.closeAll();
            }
        )
    });
    //送审
    $("#check_btn").click(function(){
        var id=$("#chh_id").val();
        var url="<?=Url::to(['views'],true)?>?id="+ id;
        var type="<?=$data[0]['chhtype']?>";
        $.fancybox({
            href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
            type:"iframe",
            padding:0,
            autoSize:false,
            width:750,
            height:480
        });
    });
</script>
