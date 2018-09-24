<?php
/**
 * Created by PhpStorm.
 * User: F3860942
 * Date: 2017/9/18
 * Time: 下午 03:42
 */

use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;
use yii\widgets\ActiveForm;

if ($isUpOrDown == 0) {
    $this->title = '商品上架审核';
} else if ($isUpOrDown == 1) {
    $this->title = '商品下架审核';
} else if ($isUpOrDown == 2) {
    $this->title = '上架商品编辑审核';
} else {
    $this->title = '商品重新上架審核';
}
$this->params['homeLike'] = ['label' => '审核管理'];
if ($isUpOrDown == 0) {
    $this->params['breadcrumbs'][] = ['label' => '商品上架审核'];
} else if ($isUpOrDown == 1) {
    $this->params['breadcrumbs'][] = ['label' => '商品下架审核'];
} else if ($isUpOrDown == 2) {
    $this->params['breadcrumbs'][] = ['label' => '上架商品编辑审核'];
} else {
    $this->params['breadcrumbs'][] = ['label' => '商品重新上架審核'];
}
?>
<style type="text/css">
    #tab_content {
        width: 800px;
        height: 40px;
    }

    #tab_bar {
        width: 1000px;
        height: 30px;
        float: left;
    }

    #tab_bar ul {
        padding: 0px;
        margin: 0px;
        height: 20px;
        text-align: center;
    }

    #tab_bar li {
        margin-right: 5px;
        list-style-type: none;
        float: left;
        width: 75px;
        height: 25px;
        background-color: #1f7ed0;
        line-height: 25px;
        color: #ffffff;
    }

    #tab_bar li.active {
        background: #97CBFF;
    }

    .tab_css {
        width: 1000px;
        height: 400px;
        background-color: #c9c9c9;
        display: none;
        float: left;
    }

    .label-width {
        width: 120px;
    }

    .value-width {
        width: 200px;
    }

    .detail img {
        max-width: 100%;
    }

    .mb-13 {
        margin-bottom: 12px;
    }
</style>
<div class="content">
    <?php if ($isUpOrDown == 2) { ?>
        <div class="content">
            <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
            <input type="hidden" name="id" value="<?= $id ?>" id="_ids">
            <?php ActiveForm::end(); ?>
            <h2 class="head-first">
                <?= $this->title; ?>
            </h2>
            <div class="border-bottom mb-10">
                <p>
                    <?= Html::button('通过', ['class' => 'button-blue width-80', 'id' => 'pass']) ?>
                    <?= Html::button('驳回', ['class' => 'button-blue width-80', 'id' => 'reject']) ?>
                    <?= Html::button('切换列表', ['class' => 'button-blue width-100', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?></p>
            </div>
            <h2 class="head-second text-left">
                基本信息
            </h2>
            <div style="width:82%">
                <table width="90%" class="no-border vertical-center ml-25"
                       style="border-collapse:separate; border-spacing:0px 15px;">
                    <tr class="no-border">
                        <td class="no-border vertical-center " width="6%" style="text-align: right">品&nbsp&nbsp&nbsp名：
                        </td>
                        <td
                            class="no-border vertical-center " width="14%"><?= $model["pdt_name"] ?></td>
                        <td width="4%" class="no-border vertical-center" style="text-align: right">品&nbsp&nbsp&nbsp牌：
                        </td>
                        <td width="10%" class="no-border vertical-center"><?= $model["BRAND_NAME_CN"] ?></td>
                    </tr>
                    <tr class="no-border">
                        <td class="no-border vertical-center " width="6%" style="text-align: right">商品标题：</td>
                        <td
                            class="no-border vertical-center " width="10%"><?= $model["pdt_title"] ?></td>
                        <td width="4%" class="no-border vertical-center" style="text-align: right">商品关键字：</td>
                        <td width="10%" class="no-border vertical-center"><?= $model["pdt_keyword"] ?></td>
                    </tr>
                    <tr class="no-border">
                        <td class="no-border vertical-center " width="6%" style="text-align: right">商品标签：</td>
                        <td
                            class="no-border vertical-center " width="10%"><?= $model["pdt_label"] ?></td>
                        <td width="4%" class="no-border vertical-center" style="text-align: right">商品属性：</td>
                        <td width="10%" class="no-border vertical-center"><?= $model["bsp_svalue2"] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    <?php } else { ?>
        <div class="content">
            <?php if ($isUpOrDown == 1) { ?>
            <div style="height: 535px;margin-bottom: 20px;">
                <?php } else { ?>
                <div style="height: 410px;margin-bottom: 20px;">
                    <?php } ?>
                    <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
                    <input type="hidden" name="id" value="<?= $id ?>" id="_ids">
                    <?php ActiveForm::end(); ?>
                    <h2 class="head-first">
                        <?= $this->title; ?>
                    </h2>
                    <div class="border-bottom mb-10 pb-10">
                        <p>
                            <?= Html::button('通过', ['class' => 'button-blue width-80', 'id' => 'pass']) ?>
                            <?= Html::button('驳回', ['class' => 'button-blue width-80', 'id' => 'reject']) ?>
                            <?= Html::button('切换列表', ['class' => 'button-blue width-100', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?></p>
                    </div>
                    <div class="space-5"></div>
                    <?php if ($isUpOrDown == 1) { ?>
                        <h2 class="head-second text-left">
                            商品下架原因
                        </h2>
                        <div style="width: 100%;margin-top: -10px;">
                            <table width="100%" class="no-border vertical-center ml-25"
                                   style="border-collapse:separate; border-spacing:0px 18px;">
                                <tr class="no-border">
                                    <td class="no-border vertical-center" style="text-align:right;padding-right: 4px;"
                                        width="12%">下架原因：
                                    </td>
                                    <?php if (!empty($model4['other_reason'])) { ?>
                                        <td class="no-border vertical-center"><?= $model4['other_reason'] ?></td>
                                    <?php } else { ?>
                                        <td class="no-border vertical-center"><?= $model4['rs_mark'] ?></td>
                                    <?php } ?>
                                    <td class="no-border vertical-center"></td>
                                </tr>
                                <tr class="no-border">
                                    <td class="no-border vertical-center" style="text-align:right;padding-right: 4px;">
                                        附&nbsp&nbsp件：
                                    </td>
                                    <td class="no-border vertical-center"><a
                                            href="<?= Yii::$app->ftpPath['httpIP'] ?><?= Yii::$app->ftpPath['PDT']['father'] ?><?= Yii::$app->ftpPath['PDT']['Off'] ?>/<?= $newFileName ?>/<?= $model4['file_new'] ?>"
                                            target="_blank"><?= $model4['file_old'] ?></a></td>
                                </tr>
                            </table>
                        </div>
                    <?php } ?>
                    <h2 class="head-second text-left">
                        商品信息
                    </h2>
                    <div style="margin-top: -10px;width: 100%;height: 260px;">
                        <div style="float: left;width:70%">
                            <table width="100%" class="no-border vertical-center ml-25"
                                   style="border-collapse:separate; border-spacing:0px 18px;">
                                <tr class="no-border">
                                    <td width="6%" class="no-border vertical-center" style="text-align: right">商品类别：
                                    </td>
                                    <td width="30%"
                                        class="no-border vertical-center"><?= $model["cat_three_level"] ?></td>
                                </tr>
                                <tr class="no-border">
                                    <td width="3%" class="no-border vertical-center" style="text-align: right">
                                        品&nbsp&nbsp名：
                                    </td>
                                    <td width="30%"
                                        class="no-border vertical-center"><?= $model["pdt_name"] ?></td>
                                </tr>
                                <tr class="no-border">
                                    <td width="3%" class="no-border vertical-center" style="text-align: right">
                                        品&nbsp&nbsp牌：
                                    </td>
                                    <td width="30%"
                                        class="no-border vertical-center"><?= $model["BRAND_NAME_CN"] ?></td>
                                </tr>
                                <tr class="no-border">
                                    <td width="3%" class="no-border vertical-center" style="text-align: right">计量单位：
                                    </td>
                                    <td width="30%"
                                        class="no-border vertical-center"><?= $model["bsp_svalue1"] ?></td>
                                </tr>
                                <tr class="no-border">
                                    <td width="3%" class="no-border vertical-center" style="text-align: right">商品属性：
                                    </td>
                                    <td width="30%" class="no-border vertical-center"><?= $model["bsp_svalue2"] ?></td>
                                </tr>
                                <tr class="no-border">
                                    <td width="3%" class="no-border vertical-center" style="text-align: right;">商品形态：
                                    </td>
                                    <td width="30%"
                                        class="no-border vertical-center"><?= $model["bsp_svalue3"] ?></td>
                                </tr>
                                <tr class="no-border">
                                    <td width="3%" class="no-border vertical-center" style="text-align: right;">关联商品：
                                    </td>
                                    <td width="30%" class="no-border vertical-center" id="relPdt">
                                        <?= $mdl ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="float:left;width:30%;height: 248px;padding-top: 15px;">
                            <div>
                     <span style="width: 100%">
                <?php if (count($model3) > 2) { ?>
                    <img src="<?= Yii::$app->ftpPath['httpIP'] ?><?= $model3[0]['fl_new'] ?>" width="150px"
                         height="150px" class="imgGroup" id="img_1"/>
                    <img src="<?= Yii::$app->ftpPath['httpIP'] ?><?= $model3[1]['fl_new'] ?>" width="150px"
                         height="150px" style="display: none;" class="imgGroup"
                         id="img_2"/>
                    <img src="<?= Yii::$app->ftpPath['httpIP'] ?><?= $model3[2]['fl_new'] ?>" width="150px"
                         height="150px" style="display: none;"
                         class="imgGroup" id="img_3"/>
                    <div id="tab_bar">
                    <ul>
                        <li style="width: 48px;background-color: inherit;border:1px solid #c8c8cd;color:#000000"
                            onclick="changePdtImg(1)">1</li>
                        <li style="width: 48px;margin-left: -5px;background-color: inherit;border:1px solid #c8c8cd;color:#000000"
                            onclick="changePdtImg(2)">2</li>
                        <li style="width: 48px;margin-left: -5px;background-color: inherit;border:1px solid #c8c8cd;color:#000000"
                            onclick="changePdtImg(3)">3</li>
                    </ul>
                </div>
                <?php } else if (count($model3) == 2) { ?>
                    <img src="<?= Yii::$app->ftpPath['httpIP'] ?><?= $model3[0]['fl_new'] ?>" width="150px"
                         height="150px" class="imgGroup" id="img_1"/>
                    <img src="<?= Yii::$app->ftpPath['httpIP'] ?><?= $model3[1]['fl_new'] ?>" width="150px"
                         height="150px" style="display: none;" class="imgGroup"
                         id="img_2"/>
                    <div id="tab_bar">
                    <ul>
                        <li style="width: 73px;background-color: inherit;border:1px solid #c8c8cd;color:#000000"
                            onclick="changePdtImg(1)">1</li>
                        <li style="width: 73px;margin-left: -5px;background-color: inherit;border:1px solid #c8c8cd;color:#000000"
                            onclick="changePdtImg(2)">2</li>
                    </ul>
                        </div>
                <?php } else if (count($model3) == 1) { ?>
                    <img src="<?= Yii::$app->ftpPath['httpIP'] ?><?= $model3[0]['fl_new'] ?>" width="150px"
                         height="150px" class="imgGroup" id="img_1"/>
                <?php } ?>
            </span>
                            </div>
                        </div>
                        <div style="height: 25px;">
                            <div style="float: left;margin-left: 52px;height: 25px"><label style="margin-left: 4px;">详细说明
                                    ：</label></div>
                            <div style="float: left;height: 25px"><img src="../../img/layout/u1456.png"
                                                                       id="pdt_details"/></div>
                        </div>
                    </div>
                </div>
                <div style="width:93%;height:auto;margin-left: 53px;display: none;" id="img_div"
                     class="detail">
                    <p><?= Html::decode($model12["details"]) ?></p>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="content">
        <?php if ($isUpOrDown == 2) { ?>
            <div class="space-10"></div>
        <?php } ?>
        <h2 class="head-second text-left">
            料号信息:<span style="margin-left: -4px"><?= $model4["part_no"] ?></span>
            <input type="hidden" value="<?= $model4["prt_pkid"] ?>" id="partno"/>
        </h2>
        <div class="table-content">
            <input type="hidden" value="<?= $isUpOrDown ?>" id="isUpOrDown">
            <div id="data">
                <div id="tab_content">
                    <div id="tab_bar">
                        <ul style="float: left">
                            <li class="_tabli active" id="tab1" onclick="showtabpage(this,1,'partnotab')">基本信息</li>
                            <?php if ($isUpOrDown != 2) { ?>
                                <li class="_tabli" id="tab2" onclick="showtabpage(this,2,'partnotab')">销售价格</li>
                            <?php } ?>
                            <li class="_tabli" id="tab3" onclick="showtabpage(this,3,'partnotab')">规格参数</li>
                            <li class="_tabli" id="tab4" onclick="showtabpage(this,4,'partnotab')">备货期</li>
                            <li class="_tabli" id="tab5" onclick="showtabpage(this,5,'partnotab')">发货地</li>
                            <li class="_tabli" id="tab6" onclick="showtabpage(this,6,'partnotab')">免运费收货地</li>
                            <li class="_tabli" id="tab7" onclick="showtabpage(this,7,'partnotab')">包装信息</li>
                            <?php if ($model13["yn_machine"] == 1) { ?>
                                <li class="_tabli" id="tab8" onclick="showtabpage(this,8,'partnotab')">设备用途</li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="pn-partnotab-1 partnotab">
                    <table class="no-border vertical-center ml-25"
                           style="border-collapse:separate; border-spacing:0px 18px;width:950px;">
                        <tr class="no-border">
                            <td class="no-border" width="10%" style="text-align: right">原产地：</td>
                            <td class="no-border" width="20%"><?= $model4["pdt_origin"] ?></td>
                            <td class="no-border" width="10%" style="text-align:right">型&nbsp&nbsp号：</td>
                            <td class="no-border" width="20%" style="word-break: break-all"><?= $model4["tp_spec"] ?></td>
                            <td class="no-border" width="10%" style="text-align:right">保质期(月)：</td>
                            <td class="no-border" width="20%"><?= $model4["warranty_period"] ?></td>
                        </tr>
                        <tr class="no-border">
                            <td class="no-border" width="12%" style="text-align:right">销售包装数量：</td>
                            <td class="no-border" width="20%"><?= $model4["pdt_qty"] ?></td>
                            <td class="no-border" width="10%" style="text-align: right">最小起订量：</td>
                            <td class="no-border" width="20%"><?= $model4["min_order"] ?></td>
                            <td class="no-border" width="10%" style="text-align:right">供应商：</td>
                            <td class="no-border" width="20%"><?= $model4["spp_sname"] ?></td>
                        </tr>
                        <tr class="no-border">
                            <td class="no-border" width="12%" style="text-align: right;">商品定位：</td>
                            <td class="no-border"
                                width="20%"><?php if ($model4["cm_pos"] == 1) echo "高"; else if ($model4["cm_pos"] == 2) echo "中"; else echo "低"; ?>
                            </td>
                            <td class="no-border" width="10%" style="text-align: right;">L/T(天)：</td>
                            <td class="no-border" width="20%"><?= $model4["l/t"] ?></td>
                            <td class="no-border" width="10%" style="text-align: right;">法务风险等级：</td>
                            <td class="no-border"
                                width="20%"><?php if ($model4["leg_lv"] == 0) echo "高"; else if ($model4["leg_lv"] == 1) echo "中"; else if ($model4["leg_lv"] == 2) echo "低"; else if ($model4["leg_lv"] == -1) echo "/"; ?></td>
                        </tr>
                        <tr class="no-border">
                            <td class="no-border" width="10%" style="text-align: right">是否可议价：</td>
                            <td class="no-border" width="20%"><?= $model4["yn_inquiry"] == 1 ? '是' : '否' ?></td>
                            <td class="no-border" width="12%" style="text-align:right">是否保税：</td>
                            <td class="no-border" width="20%"><?= $model4["yn_tax"] == 1 ? '是' : '否' ?></td>
                            <td class="no-border" width="10%" style="text-align:right">是否自提：</td>
                            <td class="no-border" width="20%"><?= $model4["isselftake"] == 1 ? '是' : '否' ?></td>
                            <input type="hidden" value="<?= $model4["isselftake"] ?>" id="isselftake"/>
                        </tr>
                        <tr class="no-border">
                            <td class="no-border" width="10%" style="text-align: right">是否代理：</td>
                            <td class="no-border" width="20%"><?= $model4["is_agent"] == 1 ? "是" : "否" ?></td>
                            <td class="no-border" width="12%" style="text-align:right">是否批次管理：</td>
                            <td class="no-border" width="20%">
                                <?php if ($model4["is_batch"] == 1) echo "是"; else echo "否"; ?>
                            </td>
                            <td class="no-border" width="10%" style="text-align:right">是否拳头商品：</td>
                            <td class="no-border" width="20%"><?= $model4["is_first"] == 1 ? '是' : '否' ?></td>
                        </tr>
                        <tr class="no-border">
                            <td class="no-border" style="text-align:right;">备&nbsp&nbsp注：</td>
                            <td class="no-border" colspan="4" style="word-break: break-all;"><?= $model4["marks"] ?></td>
                        </tr>
                    </table>
                    <div style="width: 100%;" id="prtwm">
                        <p id="wm" style="display: none;"><label>自提仓库 :</label></p>
                        <div id="data-area"></div>
                    </div>
                </div>
            </div>
            <div class="pn-partnotab-2 partnotab" style="display: none;">
                <div style="margin-bottom: 5px;"><p style="color: red;">提示：销售价格的"最小值"等于基本信息的"最小起订量"!</p></div>
                <div>
                    <table class="table">
                        <thead>
                        <th>最小值</th>
                        <th>最大值</th>
                        <th>价格</th>
                        <th>币种</th>
                        </thead>
                        <tbody>
                        <?php if (count($model5) > 0) { ?>
                            <?php
                            foreach ($model5 as $value) { ?>
                                <tr>
                                    <td><?= $value["minqty"] ?></td>
                                    <td><?= $value["maxqty"] ?></td>
                                    <td><?= $value["price"] == "-1.00" ? "面议" : $value["price"] ?></td>
                                    <td><?= $value["bsp_svalue"] ?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4" style="color: red;">没有相关数据！</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="pn-partnotab-3 partnotab" style="display: none;" id="data-source">
                <table class="no-border vertical-center"
                       style="border-collapse:separate; border-spacing:0px 15px;">
                    <?php foreach ($data as $value) { ?>
                        <tr class="no-border mb-10">
                            <td class="label-align no-border" style="font-size: 12px;white-space:nowrap;font-family: "
                                Microsoft
                                YaHei
                            ""><?=Html::decode($value['attr_name']) ?>：</td>
                            <td style="padding-left: 10px;word-break:break-all;"
                                class="no-border"><?= $value['attr_value'] ?></td>
                        </tr>
                    <?php } ?>

                </table>

            </div>
            <div class="pn-partnotab-4 partnotab" style="display: none;">
                <div style="margin-bottom: 5px;"><p style="color:red">提示：备货期的"最小值"等于基本信息的"最小起订量"!</p></div>
                <div>
                    <table class="table">
                        <thead>
                        <th>最小值</th>
                        <th>最大值</th>
                        <th>备货时间(天)</th><!--<?= $model6[0]["stock_Unit"] ?>-->
                        </thead>
                        <tbody>
                        <?php if (count($model6) > 0) { ?>
                            <?php
                            foreach ($model6 as $value) {
                                ?>
                                <tr>
                                    <td><?= $value["min_qty"] ?></td>
                                    <td><?= $value["max_qty"] ?></td>
                                    <td><?= $value["stock_time"] ?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4" style="color: red;">没有相关数据！</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="pn-partnotab-5 partnotab" style="display: none;">
                <div>
                    <table class="table">
                        <thead>
                        <th>序号</th>
                        <th>国家</th>
                        <th>省份</th>
                        <th>城市</th>
                        </thead>
                        <tbody>
                        <?php if (count($model7) > 0) { ?>
                            <?php $i = 1 ?>
                            <?php foreach ($model7 as $value) { ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $value["country_name"] ?></td>
                                    <td><?= $value["province_name"] ?></td>
                                    <td><?= $value["city_name"] ?></td>
                                </tr>
                                <?php $i = $i + 1 ?>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4" style="color: red;">没有相关数据！</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="pn-partnotab-6 partnotab" style="display: none;">
                <div>
                    <p>
                        <input type="hidden" value="<?= $model4["yn_free_delivery"] ?>" id="yn_free_delivery"/>
                        <input type="radio" style="margin-left: 20px" disabled="disabled"
                               id="checkOne"/><span>全国免运费</span>
                        <input type="radio" style="margin-left: 20px" disabled="disabled"
                               id="checkTwo"/><span>全国部分城市免运费</span>
                        <input type="radio" style="margin-left: 20px" disabled="disabled"
                               id="checkThree"/><span>全国不免运费</span>
                        <input type="radio" style="margin-left: 20px" disabled="disabled"
                               id="checkFour"/><span>全国部分城市不免运费</span>
                    </p>
                    <table class="table" style="margin-top: 10px;display: none;" id="freeModel">
                        <thead>
                        <th>序号</th>
                        <th>国家</th>
                        <th>省份</th>
                        <th>城市</th>
                        </thead>
                        <tbody>
                        <?php if (count($model9) > 0) { ?>
                            <?php $i = 1 ?>
                            <?php foreach ($model9 as $value) { ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $value["country_name"] ?></td>
                                    <td><?= $value["province_name"] ?></td>
                                    <td><?= $value["city_name"] ?></td>
                                </tr>
                                <?php $i++ ?>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4" style="color: red;">没有相关数据！</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="pn-partnotab-7 partnotab" style="display: none;">
                <div>
                    <p style="color:#0000FF;font-size: 14px;">基本包装信息</p>
                    <table class="table" style="width: 100%;margin-top: 5px">
                        <thead>
                        <th width="100px">序号</th>
                        <th width="100px">商品长</th>
                        <th width="100px">商品宽</th>
                        <th width="100px">商品高</th>
                        <th width="100px">商品重</th>
                        <th width="100px">使用富金机包装</th>
                        <th width="100px">使用栈板</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= $model8["pck_type1"]["pck_type"] ?></td>
                            <td><?= $model8["pck_type1"]["pdt_length"] ?></td>
                            <td><?= $model8["pck_type1"]["pdt_width"] ?></td>
                            <td><?= $model8["pck_type1"]["pdt_height"] ?></td>
                            <td><?= $model8["pck_type1"]["pdt_weight"] ?></td>
                            <td><input
                                    type="checkbox" <?php if ($model8["pck_type1"]["yn_pa_fjj"] == 1) { ?> checked="checked" <?php } ?>
                                    disabled="disabled"/></td>
                            <td><input
                                    type="checkbox" <?php if ($model8["pck_type1"]["yn_pallet"] == 1) { ?> checked="checked" <?php } ?>
                                    disabled="disabled"/>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 10px;">
                    <p style="color:#0000FF;font-size: 14px;">销售包装(内包装)</p>
                    <table class="table" style="width: 100%;margin-top: 5px">
                        <thead>
                        <th width="12%">序号</th>
                        <th width="12%">包装长</th>
                        <th width="12%">包装宽</th>
                        <th width="12%">包装高</th>
                        <th width="12%">包装毛重</th>
                        <th width="12%">净重</th>
                        <th width="16%">包装名称</th>
                        <th width="12%">销售包装数量</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= $model8["pck_type2"]["pck_type"] ?></td>
                            <td><?= $model8["pck_type2"]["pdt_length"] ?></td>
                            <td><?= $model8["pck_type2"]["pdt_width"] ?></td>
                            <td><?= $model8["pck_type2"]["pdt_height"] ?></td>
                            <td><?= $model8["pck_type2"]["pdt_weight"] ?></td>
                            <td><?= $model8["pck_type2"]["net_weight"] ?></td>
                            <td style="table-layout: fixed;WORD-BREAK: break-all; WORD-WRAP: break-word"><?= $model8["pck_type2"]["pdt_mater"] ?></td>
                            <td><?= $model8["pck_type2"]["pdt_qty"] ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 10px;">
                    <p style="color:#0000FF;font-size: 14px;">外包装</p>
                    <table class="table"  style="width: 100%;margin-top: 5px">
                        <thead>
                        <th width="100px">序号</th>
                        <th width="100px">包装长</th>
                        <th width="100px">包装宽</th>
                        <th width="100px">包装高</th>
                        <th width="100px">包装毛重</th>
                        <th width="100px">包装名称</th>
                        <th width="100px">外包装数量</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= $model8["pck_type3"]["pck_type"] ?></td>
                            <td><?= $model8["pck_type3"]["pdt_length"] ?></td>
                            <td><?= $model8["pck_type3"]["pdt_width"] ?></td>
                            <td><?= $model8["pck_type3"]["pdt_height"] ?></td>
                            <td><?= $model8["pck_type3"]["pdt_weight"] ?></td>
                            <td style="table-layout: fixed;WORD-BREAK: break-all; WORD-WRAP: break-word"><?= $model8["pck_type3"]["pdt_mater"] ?></td>
                            <td><?= $model8["pck_type3"]["pdt_qty"] ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 10px;">
                    <p style="color:#0000FF;font-size: 14px;">散货包装</p>
                    <table class="table" style="width: 100%;margin-top: 5px;">
                        <thead>
                        <th width="100px">序号</th>
                        <th width="100px">包装长</th>
                        <th width="100px">包装宽</th>
                        <th width="100px">包装高</th>
                        <th width="100px">包装毛重</th>
                        <th width="100px">包装名称</th>
                        <th width="100px">散货包装数量</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= $model8["pck_type4"]["pck_type"] ?></td>
                            <td><?= $model8["pck_type4"]["pdt_length"] ?></td>
                            <td><?= $model8["pck_type4"]["pdt_width"] ?></td>
                            <td><?= $model8["pck_type4"]["pdt_height"] ?></td>
                            <td><?= $model8["pck_type4"]["pdt_weight"] ?></td>
                            <td style="table-layout: fixed;WORD-BREAK: break-all; WORD-WRAP: break-word"><?= $model8["pck_type4"]["pdt_mater"] ?></td>
                            <td><?= $model8["pck_type4"]["pdt_qty"] ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 10px;">
                    <p style="color:#0000FF;font-size: 14px;">栈线包装</p>
                    <table class="table" style="width: 100%;margin-top: 5px">
                        <thead>
                        <th width="100px">序号</th>
                        <th width="100px">包装长</th>
                        <th width="100px">包装宽</th>
                        <th width="100px">包装高</th>
                        <th width="100px">包装毛重</th>
                        <th width="100px">板线数量</th>
                        <th width="100px">包装件数量</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= $model8["pck_type5"]["pck_type"] ?></td>
                            <td><?= $model8["pck_type5"]["pdt_length"] ?></td>
                            <td><?= $model8["pck_type5"]["pdt_width"] ?></td>
                            <td><?= $model8["pck_type5"]["pdt_height"] ?></td>
                            <td><?= $model8["pck_type5"]["pdt_weight"] ?></td>
                            <td><?= $model8["pck_type5"]["plate_num"] ?></td>
                            <td><?= $model8["pck_type5"]["pdt_qty"] ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="pn-partnotab-8 partnotab" style="display: none;">
                <div class="mb-20">
                    <p><span
                            style="margin-left: 20px;font-size: 12px"><label>延保方案：</label><?php if ($model4["machine_type"] == 0) echo "新机"; else if ($model4["machine_type"] == 1) echo "二手设备"; else echo "设备租赁"; ?></span>
                    </p>
                </div>
                <?php if ($model4["machine_type"] == 0) { ?>
                    <div id="newMachine" style="margin-top: 6px;">
                        <div style="font-size: 12px;margin-left: 20px;" class="mb-20">
                            <span>出厂年限：</span><span><?= $model10["out_year"] ?></span></div>
                    </div>
                <?php } ?>
                <?php if ($model4["machine_type"] == 1) { ?>
                    <div id="oldMachine" style="margin-top: 6px;">
                        <div style="font-size: 12px;margin-left: 20px;" class="mb-20">
                            <span>出厂年限：</span><span><?= $model10["out_year"] ?></span></div>
                        <div style="font-size: 12px;margin-left: 44px;margin-top: 10px;" class="mb-20">
                            <span>库存：</span><span><?= $model10["stock_num"] ?></span></div>
                        <div style="font-size: 12px;margin-left: 20px;margin-top: 10px;" class="mb-20">
                            <span>新旧程度：</span><?= $model10["recency"] ?><span></span>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($model4["machine_type"] == 2) { ?>
                    <div id="leaseMachine" style="margin-top: 6px;">
                        <div style="font-size: 12px;margin-left: 20px;" class="mb-20">
                            <span>出厂年限：</span><span><?= $model10["out_year"] ?></span></div>
                        <div style="font-size: 12px;margin-left: 44px;margin-top: 10px;" class="mb-20">
                            <span>租期：</span><span><?= $model10["rentals"] ?>个月</span></div>
                        <div style="font-size: 12px;margin-left: 44px;margin-top: 10px;" class="mb-20">
                            <span>租金：</span><span><?= $model10["rental_unit"] ?>
                                <!--<?= $model10["currency"] ?>-->RMB/月</span></div>
                        <div style="font-size: 12px;margin-left: 44px;margin-top: 10px;" class="mb-20">
                            <span>押金：</span><span><?= $model10["deposit"] ?>
                                <!--<?= $model10["currency"] ?>-->RMB/月</span>
                        </div>
                    </div>
                <?php } ?>
                <p style="margin-top: 20px;"><span style="font-weight: 900;">延保方案：</span></p>
                <table class="table" style="margin-top: 5px">
                    <thead>
                    <th>序号</th>
                    <th>延保时间(月)</th>
                    <th>延保费用(RMB)</th><!--<?= $model11[0]["bsp_svalue"] ?>-->
                    </thead>
                    <tbody>
                    <?php if (count($model11) > 0) { ?>
                        <?php $i = 1; ?>
                        <?php foreach ($model11 as $value) { ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= $value["wrr_prd"] ?></td>
                                <td><?= $value["wrr_fee"] ?></td>
                            </tr>
                            <?php $i++; ?>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4" style="color: red;">没有相关数据！</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <p style="margin-top: 20px;"><span style="font-weight: 900;">设备信息：</span></p>
                <div style="width:93%;margin-left: 50px;" class="detail">
                    <p><?= Html::decode($model14["details"]) ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <h2 class="head-second">审核记录</h2>
        <div class="mb-30">
            <div class="mb-20">
                <div id="record"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //面板切换
    function showtabpage(sender, pageindex, partno) {
        $("." + partno).css("display", "none");
        $(".pn-" + partno + "-" + pageindex).css("display", "block");
        $(sender).parent().children("li").removeClass("active");
        $(sender).addClass("active");
    }
    //商品主图切换
    function changePdtImg(index) {
        $(".imgGroup").hide();
        $("#img_" + index).show();
    }
</script>
<script>
    $(function () {
        //绑定料号规格参数
//        var partno=$("#partno").val();
//        $.ajax({
//            url:'<?//=Url::to(['build-prt-attr'])?>//',
//            type:'get',
//            data:{
//                id:partno
//            },
//            success:function(res){
//                $("#data-source").html(res);
//            }
//
//        });
        //审核记录详情
        $("#record").datagrid({
            url: "<?= Url::to(['/system/verify-record/load-record']);?>?id=" + <?= $id ?>,
            rownumbers: true,
            method: "get",
            idField: "vcoc_id",
            loadMsg: false,
//                    pagination: true,
            singleSelect: true,
//                    pageSize: 5,
//                    pageList: [5, 10, 15],
            columns: [[
                {
                    field: "verifyOrg", title: "审核节点", width: 150
                },
                {field: "verifyName", title: "审核人", width: 150},
                {
                    field: "vcoc_datetime", title: "审核时间", width: 156
                },
                {field: "verifyStatus", title: "操作", width: 150},
                {
                    field: "vcoc_remark", title: "审核意见", width: 200
                },
                {
                    field: "vcoc_computeip", title: "审核IP", width: 150
                },

            ]],
            onLoadSuccess: function (data) {
                datagridTip('#record');
                showEmpty($(this), data.total, 0);
            }
        });
        //商品详情展开和收起功能
        $("#pdt_details").click(function () {
            var img_atr = $(this).attr("src");

            if (img_atr == "../../img/layout/u1458.png") {
                $(this).attr("src", "../../img/layout/u1456.png");
                $("#img_div").slideUp(1000);
            }
            else {
                $(this).attr("src", "../../img/layout/u1458.png");
                $("#img_div").slideDown(1000);
            }
        })
        //驳回
        $("#reject").on("click", function () {
            var idss = $("#_ids").val();
            $.fancybox({
                href: "<?=Url::to(['opinion'])?>?id=" + idss,
                type: 'iframe',
                padding: 0,
                autoSize: false,
                width: 435,
                height: 280,
                fitToView: false
            });
        });
        //通过
        $("#pass").on("click", function () {
            var idss = $("#_ids").val();
            $.fancybox({
                href: "<?=Url::to(['pass-opinion'])?>?id=" + idss,
                type: 'iframe',
                padding: 0,
                autoSize: false,
                width: 435,
                height: 280,
                fitToView: false
            });
        });
        //自提仓库绑定数据
        var isUpOrDown = $("#isUpOrDown").val();
        $("#data-area").datagrid({
            url: "<?= Url::to(['/system/verify-record/get-prt-wm']);?>?id=" + <?= $id ?> +"&isUpOrDown=" + isUpOrDown,
            rownumbers: true,
            method: "get",
            idField: "vcoc_id",
            loadMsg: false,
//                    pagination: true,
            singleSelect: true,
//                    pageSize: 5,
//                    pageList: [5, 10, 15],
            columns: [[
                {
                    field: "wh_name", title: "仓库名", width: 200
                },
                {
                    field: "wh_address", title: "仓库地址", width: 350
                },
                {
                    field: "staff_name", title: "联系人", width: 200
                },
                {
                    field: "staff_mobile", title: "联系电话", width: 200
                },
            ]],
            onLoadSuccess: function (data) {
                showEmpty($(this), data.total, 0);
            }
        });
        //自提仓库的隐藏和显示切换
        $("#prtwm").hide();
        var isselftake = $("#isselftake").val();
        if (isselftake == 1) {
            $("#wm").css("display", "block");
            $("#prtwm").show();

        }
        //判断哪种运费方式被选中
        var yn_free_delivery = $("#yn_free_delivery").val();
        if (yn_free_delivery == 0) {
            $("#checkOne").attr("checked", "checked");
        }
        else if (yn_free_delivery == 1) {
            $("#checkTwo").attr("checked", "checked");
            $("#freeModel").show();
        }
        else if (yn_free_delivery == 2) {
            $("#checkThree").attr("checked", "checked");
        }
        else {
            $("#checkFour").attr("checked", "checked");
            $("#freeModel").show();
        }
    })
    //商品详情收起操作
    function hideImgdiv() {
        $("#img_div").slideUp(1000);
        $("#pdt_details").attr("src", "../../img/layout/u1456.png");
    }
</script>