<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/2/13
 * Time: 上午 09:33
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->title="潜在客户列表";
$this->params['homeLike']=['label'=>'客户关系管理','url'=>['/']];
$this->params['breadcrumbs'][]=['label'=>'潜在客户列表','url'=>['index']];
$this->params['breadcrumbs'][]=['label'=>'修改潜在客户'];
\app\assets\JeDateAsset::register($this);
?>
<style>
    .label-width{
        width: 120px;
    }
    .value-width{
        width:300px;
    }
</style>
<?php $form=ActiveForm::begin([
        "id"=>"edit-form"
]);?>
<div class="content">

    <h2 class="head-first">
        修改潜在客户
        <span class="head-code">客户编号：<?= $model['cust_filernumber'] ?></span>
    </h2>

    <h2 class="head-second">
        <a href="javascript:void(0)" onclick="$('#base-info').slideToggle();$(this).find('i').toggleClass('icon-caret-right')" class="ml-10">
            <i class="icon-caret-down"></i>
            客户基本信息
        </a>
    </h2>

    <div id="base-info">
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">公司名称：</label>
                <span class="value-width value-align"><?=$model['cust_sname'];?></span>
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">公司简称：</label>
                <span class="value-width value-align"><?=$model['cust_shortname'];?></span>
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">公司电话：</label>
                <input name="cust_tel1" class="value-width value-align easyui-validatebox" data-options="validType:'telphone'" type="text" value="<?=$model['cust_tel1'];?>" maxlength="20">
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">邮编：</label>
                <input name="member_compzipcode" class="value-width value-align easyui-validatebox" data-options="validType:'postcode'" type="text" value="<?=$model['member_compzipcode'];?>" placeholder="&nbsp;请输入:xxxxxx">
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for=""><span class="red">*</span>联系人：</label>
                <input name="cust_contacts" class="value-width value-align easyui-validatebox" data-options="required:true"  type="text" value="<?=$model['cust_contacts']?>" maxlength="20">
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">部门：</label>
                <input name="cust_department" class="value-width value-align" type="text" value="<?=$model['cust_department']?>" maxlength="20">
            </div>
            <input name="create_by"  type="hidden" value="<?=\Yii::$app->user->identity->staff->staff_id;?>">
        </div>


        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">职位：</label>
                <input name="cust_position" class="value-width value-align" type="text" value="<?=$model['cust_position']?>" maxlength="20">
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">职位职能：</label>
                <?=Html::dropDownList("cust_function",$model["cust_function"],$downList["cust_function"],["prompt"=>"请选择","class"=>"value-width"])?>
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for=""><span class="red">*</span>联系方式：</label>
                <input name="cust_tel2" class="value-width value-align easyui-validatebox" data-options="required:true,validType:'mobile'" type="text" value="<?=$model['cust_tel2'];?>" maxlength="15" placeholder="&nbsp;请输入：138 xxxx xxxx">
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for=""><span class="red">*</span>邮箱：</label>
                <input name="cust_email" class="value-width value-align easyui-validatebox" data-options="required:true,validType:'email'" type="text" value="<?=$model['cust_email'];?>" maxlength="50" placeholder="&nbsp;请输入：xxxx@xxx.xx">
            </div>
        </div>

        <div class="mb-10">
            <label class="label-width label-align" for=""><span class="red">*</span>详细地址：</label>
            <select style="width: 179px;" class="disName easyui-validatebox" data-options="required:true">
                <option value="">国</option>
                <?php if($model['cust_district_2']){ ?>
                    <?php foreach($district[oneLevel] as $k=>$v){ ?>
                        <option <?=$v[district_id]==$district[oneLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
                    <?php }}else{ ?>
                    <option value="1">中国</option>
                <?php } ?>
            </select>
            <select style="width: 179px;" class="disName easyui-validatebox" data-options="required:true"  id="">
                <option value="">省</option>
                <?php foreach($district[twoLevel] as $k=>$v){ ?>
                    <option <?=$v[district_id]==$district[twoLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
                <?php } ?>
            </select>
            <select style="width: 179px;" class="disName easyui-validatebox" data-options="required:true" id="">
                <option value="">市</option>
                <?php foreach($district[threeLevel] as $k=>$v){ ?>
                    <option <?=$v[district_id]==$district[threeLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
                <?php } ?>
            </select>
            <select style="width: 179px;" class="disName easyui-validatebox" data-options="required:true" name="cust_district_2" id="">
                <option value="">县/区</option>
                <?php foreach($district[fourLevel] as $k=>$v){ ?>
                    <option <?=$v[district_id]==$district[fourLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
                <?php } ?>
            </select>
            <div style="margin-left:120px;margin-top:10px;">&nbsp;<input name="cust_adress" class="value-align easyui-validatebox" data-options="required:true" type="text" value="<?=$model['cust_adress'];?>" style="width:728px;" maxlength="100" placeholder="&nbsp;公司详细地址，例如街道名称，门牌号码，楼层等信息"></div>
        </div>


    </div>

    <div class="space-10"></div>

    <h2 class="head-second">
        <a href="javascript:void(0)" onclick="$('#detail-info').slideToggle();$(this).find('i').toggleClass('icon-caret-right')" class="ml-10">
            <i class="icon-caret-down"></i>
            客户详细信息
        </a>
    </h2>

    <div id="detail-info">
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">法人代表：</label>
                <input name="cust_inchargeperson" class="value-width value-align" type="text" value="<?=$model['cust_inchargeperson'];?>" maxlength="20">
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">注册时间：</label>
                <input name="cust_regdate" class="value-width value-align select-date" onfocus="$(this).blur()" type="text" onclick="WdatePicker({ onpicked: function () { }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd'})" value="<?=$model['cust_regdate'];?>">
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">注册货币：</label>
                <?=Html::dropDownList("member_regcurr",$model["member_regcurr"],$downList["currency"],["prompt"=>"请选择","class"=>"value-width"])?>
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">注册资金：</label>
                <input name="cust_regfunds" class="value-width value-align easyui-validatebox" data-options="validType:'int'" type="text" value="<?=$model['cust_regfunds']?(int)$model['cust_regfunds']:"";?>" maxlength="15">
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">公司类型：</label>
                <?=Html::dropDownList("cust_compvirtue",$model["cust_compvirtue"],$downList["company_type"],["prompt"=>"请选择","class"=>"value-width"])?>
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">客户来源：</label>
                <?=Html::dropDownList("member_source",$model["cust_compvirtue"],$downList["customer_source"],["prompt"=>"请选择","class"=>"value-width"])?>
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">经营范围：</label>
                <?=Html::dropDownList("cust_businesstype",$model["cust_businesstype"],$downList["cust_businesstype"],["prompt"=>"请选择","class"=>"value-width"])?>
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">交易币种：</label>
                <?=Html::dropDownList("member_curr",$model["member_curr"],$downList["currency"],["prompt"=>"请选择","class"=>"value-width"])?>
            </div>
        </div>



        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">年营业额：</label>
                <span class="value-width">
                    <input style="width: 220px;" name="member_compsum" class="easyui-validatebox" data-options="validType:'int'" type="text" value="<?=$model['member_compsum'];?>" maxlength="15">
                    &nbsp;&nbsp;&nbsp;<?=Html::dropDownList("compsum_cur",$model["compsum_cur"]?$model["compsum_cur"]:100091,$downList["currency"],["prompt"=>"请选择","style"=>"width:65px"])?>
                </span>
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">年采购额：</label>
                <span class="value-width">
                    <input style="width: 220px;" class="easyui-validatebox" data-options="validType:'int'" type="text" name="cust_pruchaseqty" value="<?=$model['cust_pruchaseqty'];?>" maxlength="15">
                    &nbsp;&nbsp;&nbsp;<?=Html::dropDownList("pruchaseqty_cur",$model["pruchaseqty_cur"]?$model["pruchaseqty_cur"]:100091,$downList["currency"],["prompt"=>"请选择","style"=>"width:65px;"])?>
                </span>
            </div>
        </div>


        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">员工人数：</label>
                <input name="cust_personqty" class="value-width value-align easyui-validatebox" data-options="validType:'int'" type="text" value="<?=$model['cust_personqty'];?>" maxlength="20">
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">发票需求：</label>
                <?=Html::dropDownList("member_compreq",$model["member_compreq"],$downList["member_compreq"],["prompt"=>"请选择","class"=>"value-width"])?>
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">潜在需求：</label>
                <?=Html::dropDownList("member_reqflag",$model["member_reqflag"],$downList["member_reqflag"],["prompt"=>"请选择","class"=>"value-width"])?>
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">需求类目：</label>
                <?=Html::dropDownList("member_reqitemclass",$model["member_reqitemclass"],$downList["productType"],["prompt"=>"请选择","class"=>"value-width"])?>
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">需求类别：</label>
                <input name="member_reqdesription" class="value-width value-align" type="text" value="<?=$model['member_reqdesription']?>" maxlength="50">
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">主要市场：</label>
                <input name="member_marketing" class="value-width value-align" type="text" value="<?=$model['member_marketing'];?>" maxlength="50">
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">主要客户：</label>
                <input name="member_compcust" class="value-width value-align" type="text" value="<?=$model['member_compcust'];?>" maxlength="100">
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">主页：</label>
                <input name="member_compwebside" class="value-width value-align easyui-validatebox" data-options="validType:'www'" type="text" value="<?=$model['member_compwebside'];?>" maxlength="50" placeholder="&nbsp;请输入：www.xxxx.xxxx">
            </div>
        </div>



        <div class="mb-10">
            <label style="vertical-align: top;" class="label-width label-align" for="">范围说明：</label>
            <textarea class="value-align" name="member_businessarea" style="width:728px;height: 100px;" maxlength="200" placeholder="最多输入200"><?=$model['member_businessarea'];?></textarea>
        </div>


        <div class="mb-10">
            <label style="vertical-align: top;" class="label-width label-align" for="">备注：</label>
            <textarea class="value-align" name="member_remark" id="" style="width: 728px;height: 100px;" maxlength="200"  placeholder="最多输入200"><?=$model['member_remark'];?></textarea>
        </div>
    </div>

    <div class="mb-10 text-center">
        <button type="submit" class="button-blue">确定</button>
        <button type="button" onclick="window.history.go(-1)" class="button-white">返回</button>
    </div>

</div>
<?php $form->end();?>


<script>
    $(function(){
        ajaxSubmitForm($("#edit-form"));

//        $('.select-date').click(function(){
//            $(".select-date").jeDate({
//                format:"YYYY-MM-DD",
//                isTime:false,
//                minDate:"2014-09-19 00:00:00"
//            })
//        });

        //地区选择
        $('.disName').on("change", function () {
            var $select = $(this);
            //console.log($select);
            getNextDistrict($select);

            var distArr=[];
            $(this).prevAll(".disName").andSelf().each(function(){
                distArr.push($(this).children(":selected").html());
            });
            $("#cur-addr-input").val(distArr.join());
            $("#cur-addr-text").text(distArr.join());
        });
        //遞歸清除級聯選項
        function clearOption($select) {
            if ($select == null) {
                $select = $("#disName_1")
            }
            $tagNmae = $select.next().prop("tagName");
            if ($select.next().length != 0 && $tagNmae =='SELECT') {
                $select.next().html('<option value=>请选择</option>');
                clearOption($select.next());
            }
        }

        //获取下级地区
        function getNextDistrict($select) {
            var id = $select.val();
            //console.log(id);
            if (id == "") {
                clearOption($select);
                return;
            }
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {"id": id},
                url: "<?=Url::to(['/ptdt/firm/get-district']) ?>",
                success: function (data) {
//                        console.log(data);
                    var $nextSelect = $select.next("select");
//                        console.log();
                    clearOption($nextSelect);
                    $nextSelect.html('<option value>请选择</option>')
                    if ($nextSelect.length != 0)
                        for (var x in data) {
                            $nextSelect.append('<option value="' + data[x].district_id + '" >' + data[x].district_name + '</option>')
                        }
                }

            })
        }
    });
</script>
