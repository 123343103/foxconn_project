<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/7/11
 * Time: 上午 09:59
 */
use yii\helpers\Url;
use app\classes\Menu;
$this->title='报废单详情页';
$this->params['homeLike']=['label'=>'仓储物流管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=['label'=>'库存报废','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=['label'=>'报废申请查询','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<style>
    .width-40{width: 40px;}
    .width-150{width: 150px;}
</style>
<div class="content">
    <h1 class="head-first"><?=$this->title?></h1>
    <div class="mb-10">
        <?php if($model[0]['chh_status']=='待提交' || $model[0]['chh_status']=='驳回'){?>
            <?php if(Menu::isAction('/warehouse/other-stock-in/edit')){?>
                <button type="button" class="button-blue" onclick="location.href='<?=Url::to(['update','id'=>$model[0]['chh_id']])?>'">修改</button>
            <?php }?>
            <?php if(Menu::isAction('/warehouse/other-stock-in/delete')){?>
                <button id="delete_btn" type="button" class="button-blue">作废</button>
            <?php }?>
<!--        --><?php //}?>
<!--        --><?php //if($model[0]['chh_status']=='待提交'){?>
            <?php if(Menu::isAction('/warehouse/other-stock-in/check')){?>
                <button id="check_btn" type="button" class="button-blue">送审</button>
            <?php }?>
        <?php }?>
        <?=Menu::isAction('/warehouse/other-stock-in/index')?"<button type='button' class='button-blue' style='width:80px;' onclick='location.href=\"".Url::to(['index'])."\"'>切换列表</button>":''?>
        <?=Menu::isAction('/warehouse/other-stock-in/index')?"<button type='button' class='button-blue' style='width:80px;' >打印</button>":''?>
<!--        <button type="button" class="button-blue" onclick="history.go(-1)">返回</button>-->
    </div>
    <h1 class="head-second">报废申请信息</h1>
    <table width="90%" class="no-border vertical-center ml-25 mb-20">
        <tr class="no-border">
            <td class="no-border vertical-center label-align" width="10%">报废单号:</td>
            <td class="no-border vertical-center" width="18%"><?= $model[0]['chh_code'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center label-align" width="10%">报废单状态:</td>
            <td class="no-border vertical-center" width="40%"><?= $model[0]['chh_status'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
        </tr>
    </table>
    <table width="90%" class="no-border vertical-center ml-25 mb-20">
        <tr class="no-border">
            <td class="no-border vertical-center label-align" width="10%">报废类别:</td>
            <td class="no-border vertical-center" width="18%"><?= $model[0]['chh_typeName'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center label-align" width="10%">报废仓库:</td>
            <td class="no-border vertical-center" width="40%"><?= $model[0]['wh_name'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
        </tr>
    </table>
    <table width="90%" class="no-border vertical-center ml-25 mb-20">
        <tr class="no-border">
            <td class="no-border vertical-center label-align" width="10%">申请人:</td>
            <td class="no-border vertical-center" width="18%"><?= $model[0]['create_by'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center label-align" width="10%">申请日期:</td>
            <td class="no-border vertical-center" width="40%"><?= $model[0]['create_at'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
        </tr>
    </table>
    <table width="90%" class="no-border vertical-center ml-25 mb-20">
        <tr class="no-border">
            <td class="no-border vertical-center label-align" width="10%">制单人:</td>
            <td class="no-border vertical-center" width="18%"><?= $model[0]['review_by'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center label-align" width="10%">制单时间:</td>
            <td class="no-border vertical-center" width="40%"><?= $model[0]['review_at'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
        </tr>
    </table>
    <input type="hidden" class="_idd" value="<?= $model[0]['chh_id']?>">
    <input type="hidden" class="_typess" value="<?=$model[0]['chh_type']?>">
    <h1 class="head-second">入库商品信息</h1>
    <div style="overflow:auto;">
        <table class="table" style="width: 2500px">
            <thead>
            <tr>
                <th class="width-40">序号</th>
                <th class="width-150">料号</th>
                <th class="width-150">品名</th>
                <th class="width-150">类别</th>
                <th class="width-150">规格型号</th>
                <th class="width-150">批次</th>
                <th class="width-150">单位</th>
                <th class="width-150">当前储位</th>
                <th class="width-150">现有库存</th>
                <th class="width-150">报废数量</th>
                <th class="width-150">报废方式</th>
                <th class="width-250">报废原因</th>
                <th class="width-150">存放库存</th>
                <th class="width-150">存放储位</th>
                <th class="width-150">资产元值(元)</th>
                <th class="width-150">处理价(元)</th>
                <?php foreach ($columns as $key => $val) { ?>
                    <th><p class="text-center width-150 color-w"><?= $val["field_title"] ?></p></th>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($model[1])){?>
                <?php foreach($model[1] as $key=>$val){?>
                    <?php if($val['mode']==0){
                        $mode="垃圾回收";
                     }else if($val['mode']==1){
                        $mode="销毁";
                    }else if($val['mode']==2){
                        $mode="废料变卖";
                    }else if($val['mode']==4){
                        $mode="低价转让";
                    }?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$val['pdt_no']?></td>
                        <td><?=$val['pdt_name']?></td>
                        <td><?=$val['catg_name']?></td>
                        <td><?=$val['tp_spec']?></td>
                        <td><?=$val['chl_bach']?></td>
                        <td><?=$val['unit']?></td>
                        <td><?=$val['st_id']?></td>
                        <td><?=$val['before_num1']?></td>
                        <td><?= sprintf("%.2f", $val['chl_num'])?></td>
                        <td><?=$mode?></td>
                        <td style="word-break:break-all;"><?=$val['chh_remark']?></td>
                        <td><?=$val['wh_id2']?></td>
                        <td><?=$val['st_id2']?></td>
                        <td><?=$val['']?></td>
                        <td><?=$val['deal_price']?></td>
                    </tr>
                <?php }?>
            <?php }?>
            </tbody>
        </table>
    </div>
    <div class="mb-20" style="overflow: auto; margin-top: 10px">
        <?php if (!empty($verify)){ ?>
        <div >
            <h1 class="head-second">签核记录</h1>
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
    $(function(){
        //删除
//        $("#delete_btn").click(function(){
//            var id=$("._idd").val();
////            alert(id);
//            layer.confirm('确定删除吗？',{icon:2},
//                function(){
//                    $.ajax({
//                        url:"<?//=Url::to(['delete-inv'])?>//",
//                        data:{"id":id},
//                        dataType:"json",
//                        success:function(data){
//                            if(data.flag==1){
//                                layer.alert(data.msg,{icon:1,end:function(){
//                                    location.href="<?//=Url::to(['index'])?>//";
//                                }});
//                            }else{
//                                layer.alert(data.msg,{icon:2});
//                            }
//                        }
//                    });
//                },
//                function(){
//                    layer.closeAll();
//                }
//            );
//        });

        //送审20180109wxt
        $("#check_btn").click(function () {
            var id=$("._idd").val();
//            alert(id);
            var url="<?=Url::to(['view'],true)?>?id="+id;
            var type=$("._typess").val();
//            alert(type);
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
        //作废
        $(".content").delegate("#delete_btn","click", function () {
            var id=$("._idd").val();
            $.fancybox({
                href: "<?=Url::to(['can-reason'])?>?id=" + id,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 500,
                height: 300
            });
        });
    })
</script>
