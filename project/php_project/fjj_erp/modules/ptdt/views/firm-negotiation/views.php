<?php

use yii\helpers\Html;
use app\classes\Menu;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $negotiation app\modules\ptdt\models\PdNegotiation */

$this->title = '谈判履历';
$this->params['homeLike'] = ['label' => '商品开发管理'];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '谈判履历列表', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '谈判详情', 'url' => ""];

//dumpE($negotiation);
?>
<div class="content">
    <h1 class="head-first">
        谈判详情
        <span class="head-code">编号：<?= $negotiation['pdn_code'] ?></span>
    </h1>
    <div class="border-bottom mb-20 height-40">
        <?= Html::button('切换列表', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        <?= Menu::isAction('/ptdt/firm-negotiation/create') ? Html::button('新增谈判', ['class' => 'button-blue width-80', 'id' => 'create']) : '' ?>
    </div>
    <div class="mb-30">
        <h2 class="head-second">
            厂商基本信息
        </h2>
        <div class="mb-10">
            <label class="width-100">注册公司名称</label>
            <span class="width-200 text-top"><?= $firmInfo['firm_sname'] ?></span>
            <label class="width-100 ">简称</label>
            <span class="width-150 text-top"><?= $firmInfo['firm_shortname'] ?></span>
            <label class="width-100 ">品牌</label>
            <span class="width-200 text-top"><?= $firmInfo['firm_brand'] ?></span>
        </div>
        <div class="mb-10">
            <label class="width-100">英文全称</label>
            <span class="width-200 text-top"><?= $firmInfo['firm_ename'] ?></span>
            <label class="width-150">是否为集团供应商</label>
            <span class="width-150 text-top"><?= $firmInfo['issupplier'] ?></span>
        </div>
        <div class="mb-10">
            <label class="width-100">厂商类型</label>
            <span class="width-200 text-top"><?= $firmInfo['firmType'] ?></span>
            <label class="width-100">厂商来源</label>
            <span class="width-200 text-top"><?= $firmInfo['firmSource'] ?></span>
        </div>
        <div class="mb-10">
            <label class="width-100">厂商地址</label>
            <span class="width-750 text-top"><?= $firmInfo['firmAddress']['fullAddress'] ?></span>
        </div>
        <div class="mb-10">
            <label class="width-100 ">分级分类</label>
            <span class="width-750 text-top"><?= $firmInfo['category'] ?></span>
        </div>
    </div>
<!--    <h2 class="head-second">谈判记录</h2>-->
    <?php if(!empty($child)){ ?>
    <?php foreach($child as $key=>$value){ ?>
            <div class="easyui-panel" id="<?= 'child_'.$key ?>">
            <div class="mb-10">
                <label class="width-100">谈判日期</label>
                <span class="width-200 text-top"><?= $value['pdnc_date'] ?></span>
                <label class="width-100">谈判时间</label>
                <span class="width-200 text-top"><?= $value['pdnc_time'] ?></span>
                <label class="width-100">谈判地点</label>
                <span class="width-250 text-top"><?= $value['pdnc_location'] ?></span>
            </div>
            <div class="mb-10">
                <label class="width-100">厂商主谈人</label>
                <span class="width-200 text-top"><?= $reception[$key]['rece_sname'] ?></span>
                <label class="width-100">职位</label>
                <span class="width-200 text-top"><?= $reception[$key]['rece_position'] ?></span>
                <label class="width-100">厂商联系电话</label>
                <span class="width-200 text-top"><?= $reception[$key]['rece_mobile'] ?></span>
            </div>
            <div class="mb-10">
                <label class="width-100">商品经理人</label>
                <span class="width-200 text-top"><?= $value['productPerson']['name'] ?></span>
                <label class="width-100 ">职位</label>
                <span class="width-200 text-top"><?= $value['productPerson']['title'] ?></span>
                <label class="width-100 ">联系电话</label>
                <span class="width-200 text-top"><?= $value['productPerson']['mobile'] ?></span>
            </div>
            <?php if (!empty($accompany)) { ?>
                <?php foreach ($accompany as $key => $val) { ?>
                    <div class="mb-10">
                        <label class="width-100">陪同人员</label>
                        <span class="width-200 text-top"><?= $val['staffInfo']['staffName'] ?></span>
                        <label class="width-100 ">职位</label>
                        <span class="width-200 text-top"><?= $val['staffInfo']['staffJob'] ?></span>
                        <label class="width-100 ">联系电话</label>
                        <span class="width-200 text-top"><?= $val['staffInfo']['staffMobile'] ?></span>
                    </div>
                <?php } ?>
            <?php } ?>
            <div class="mb-30">
                <div class="space-10"></div>
                <div class="mb-10">
                    <label class="width-150">过程描述</label>
                    <span class="width-665 text-top"><?= $value['process_descript'] ?></span>
                </div>
                <div class="space-10"></div>
                <div class="mb-10">
                    <label class="width-150">谈判结论</label>
                    <span class="width-200 text-top"><?= $value['bsPubdata']['concluse'] ?></span>
                </div>
                <div class="space-10"></div>
                <div class="mb-10">
                    <label class="width-150">追踪事项</label>
                    <span class="width-665 text-top"><?= $value['trace_matter'] ?></span>
                </div>
                <div class="space-10"></div>
                <div class="mb-10">
                    <label class="width-150">下次谈判注意事项</label>
                    <span class="width-665 text-top"><?= $value['next_notice'] ?></span>
                </div>
                <div class="space-10"></div>
                <div class="mb-10">
                    <label class="width-150">其他</label>
                    <span class="width-665 text-top"><?= $value['negotiate_others'] ?></span>
                </div>
                <?php ;
                if (!empty($value['attachment'])) {
                    ?>
                    <div class="mb-10">
                        <label class="width-150">附件</label>
                        <span class="width-665 text-top"><?= Html::a($value['attachment_name'], Url::to(['/']) . $value['attachment']) ?></span>
                    </div>
                <?php } ?>
            </div>
        <script>
            $(function (){
                $('#<?= 'child_'.$key ?>').panel({
                    title:"<span style='color:black;padding-left:0;'>谈判记录：&nbsp;&nbsp;&nbsp;&nbsp;</span>"+
                    "<?=Menu::isAction('/ptdt/firm-negotiation/update') && $negotiation['pdn_status']==10?"<a href='".Url::to(['update','cid'=>$value['pdnc_id']])."'>修改</a>&nbsp;&nbsp;&nbsp;&nbsp;":"" ?><a href='<?=Url::to(['view','cid'=>$value['pdnc_id']])?>'>详情</a>&nbsp;&nbsp;&nbsp;&nbsp;<?=Menu::isAction('/ptdt/firm-negotiation/delete') && $negotiation['pdn_status']==10?"<a id='delete'>删除</a>":"" ?>",
                    collapsible:true,
                    collapsed:<?= $key==0?0:1?>,
                    onCollapse:function(){setMenuHeight()},
                    onExpand:function(){setMenuHeight()}
                });
            });
        </script>
    </div>
    <div class="space-10"></div>
    <?php } ?>
    <?php } ?>
</div>

