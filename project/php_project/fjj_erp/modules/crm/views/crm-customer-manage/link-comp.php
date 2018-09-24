<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/28
 * Time: 15:02
 */
use \yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\JeDateAsset::register($this);
?>
<div>
    <style>
        .label-width{
            width:100px;
        }
        .value-width{
            width:200px;
        }
        select:disabled{
            background:rgb(235, 235, 228);
        }
    </style>
    <?php $form = ActiveForm::begin([
        'id' => 'cust-linkcomp',
    ]) ?>
    <?php if ($id != null && $lincId == null) { ?>
        <h2 class="head-first">新增关联公司</h2>
    <?php } else if ($id != null && $lincId != null) { ?>
        <h2 class="head-first">修改关联公司</h2>
    <?php } ?>
    <div class="ml-30 mt-40">

        <div class="mb-10">
            <input type="hidden" name="cust_id" value="<?= $id ?>">
            <div class="inline-block">
                <label class="label-width label-align"><span class="red">*</span>公司名称：</label>
                <input type="text" class="value-width value-align easyui-validatebox" data-options="required:'true'" name="CrmCustLinkcomp[linc_name]" value="<?= $result['linc_name'] ?>" maxlength="20"/>
            </div>
            <div class="inline-block">
                <label class="label-width label-align">关联性质：</label>
                <input type="text" class="value-width value-align" name="CrmCustLinkcomp[relational_character]" value="<?= $result['relational_character'] ?>" maxlength="20"/>
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align">投资金额：</label>
                <span class="value-width value-align">
                    <input type="text" class="value-align easyui-validatebox" name="CrmCustLinkcomp[total_investment]" data-options="validType:'int'"  value="<?= $result['total_investment'] ?>" maxlength="15" style="width: 120px;" >
                    <select name="CrmCustLinkcomp[total_investment_cur]" style="width:75px;">
                        <option value="">请选择...</option>
                        <?php foreach ($downList['tradeCurrency'] as $key => $val){ ?>
                            <?php if($result['total_investment_cur']=="" && $val["bsp_svalue"]=="RMB"){ ?>
                                <option selected value="<?= $val['bsp_id'] ?>" ><?= $val['bsp_svalue'] ?></option>
                                <?php }else{ ?>
                                <option value="<?= $val['bsp_id'] ?>" <?= $result['total_investment_cur'] == $val['bsp_id']?'selected':null; ?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                        <?php } ?>
                    </select>
                </span>
            </div>
            <div class="inline-block">
                <label class="label-width label-align">持股比率：</label>
                <input type="text" class="value-width value-align easyui-validatebox" name="CrmCustLinkcomp[shareholding_ratio]" data-options="validType:'percent'" value="<?= $result['shareholding_ratio'] ?>" maxlength="10">&nbsp;（%）
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">经营类型：</label>
                <select class="value-width value-align" name="CrmCustLinkcomp[linc_type]">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['managementType'] as $key => $val) { ?>
                        <option
                                value="<?= $val['bsp_id'] ?>" <?= $result['linc_type'] == $val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">注册时间：</label>
                <input onclick="WdatePicker({ onpicked: function () { }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd'})" class="value-width value-align Wdate" type="text" name="CrmCustLinkcomp[linc_date]" readonly="readonly"
                       value="<?= $result['linc_date']?>">
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label for="" class="label-width label-align">公司负责人：</label>
                <input type="text"  class="value-width value-align" name="CrmCustLinkcomp[linc_incpeople]" value="<?= $result['linc_incpeople'] ?>" maxlength="20">
            </div>
            <div class="inline-block">
                <label for="" class="label-width label-align">联系电话：</label>
                <input type="text"  class="value-width value-align easyui-validatebox" data-options="validType:'mobile'" name="CrmCustLinkcomp[linc_tel]" value="<?= $result['linc_tel'] ?>" maxlength="20" placeholder="请输入：136xxxxxxxx">
            </div>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">公司地址：</label>
            <select style="width:125px;" class="disName value-align" id="disName_1">
                <option value="">请选择...</option>
                <?php foreach ($downList['country'] as $key => $val) { ?>
                    <option
                            value="<?= $val['district_id'] ?>" <?=$val['district_id']==$districtAll2['oneLevelId']?'selected':null?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select style="width:125px;" class="disName value-align" id="disName_2">
                <option value="">请选择...</option>
                <?php if(!empty($districtAll2)){?>
                    <?php foreach($districtAll2['twoLevel'] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll2['twoLevelId']?'selected':null?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <select style="width:125px;" class="disName value-align" id="disName_3">
                <option value="">请选择...</option>
                <?php if(!empty($districtAll2)){?>
                    <?php foreach($districtAll2['threeLevel'] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll2['threeLevelId']?'selected':null?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <select style="width:125px;" class="disName value-align" id="disName_4" name="CrmCustLinkcomp[linc_district]">
                <option value="">请选择...</option>
                <?php if(!empty($districtAll2)){?>
                    <?php foreach($districtAll2['fourLevel'] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll2['fourLevelId']?'selected':null?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <input class="value-align" style="width:507px;margin-left: 104px;margin-top: 10px;" type="text" name="CrmCustLinkcomp[linc_address]"
                   value="<?= $result['linc_address'] ?>" maxlength="50" placeholder="最多输入50个字">
        </div>
        <div class="space-20"></div>
        <?php if ($id != null && $lincId == null) { ?>
            <div class="mb-10 text-center">
                <button class="button-blue-big" type="submit" id="linkcomp-add">确定</button>
                <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
            </div>
        <?php } ?>
        <?php if ($id != null && $lincId != null) { ?>
            <div class="mb-10 text-center">
                <button class="button-blue-big" type="submit" id="linkcomp-edit">确定</button>
                <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
            </div>
        <?php } ?>
        <?php ActiveForm::end() ?>
    </div>
</div>
<script>
    $("#linkcomp-add").one("click", function () {
        $("#cust-linkcomp").attr('action', '<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/create-link-comp', 'id' => $id]) ?>');
        return ajaxSubmitForm($("#cust-linkcomp"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$("#relateCompany").datagrid("reload");
            parent.$.fancybox.close();
        });
    });

    $("#linkcomp-edit").one("click", function () {
        $("#cust-linkcomp").attr('action', '<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/update-link-comp', 'id' => $lincId]) ?>');
        return ajaxSubmitForm($("#cust-linkcomp"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$("#relateCompany").datagrid("reload");
            parent.$.fancybox.close();
        });
    });
    $(function () {
        $.extend($.fn.validatebox.defaults.rules, {
            percent:{
                validator:function(value){
                    return /^\d+(\.\d{0,2})?$/.test(value) && value<=100;
                },
                message:"请输入最多两位小数,并且小于100的数值"
            },
        });
        $('.disName').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select,$url,"select");
        });


    })
</script>