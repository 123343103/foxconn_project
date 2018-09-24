<?php
/**
 *  F3858995
 *  2016/10/17
 */
use yii\widgets\ActiveForm;
?>
<style>
    .width-50 {
        width: 50px;
    }
    .width-60 {
        width: 60px;
    }
    .width-70 {
        width: 70px;
    }
    .width-190 {
        width: 190px;
    }
    .width-650 {
        width: 650px;
    }
    .ml-50 {
        margin-left: 50px;
    }
    .mt-20 {
        margin-top: 20px;
    }
    .height-40 {
        height: 40px;
    }
    .height-54 {
        height: 54px;
    }
    .height-230 {
        height: 230px;
    }
</style>
<div class="ml-10">
    <?php $form = ActiveForm::begin(['id'=>'add-form']) ?>
    <h2 class='head-first width-650' >
        <button type='submit' class='button-blue'>保存</button>
        <button type='reset' class='button-blue' onclick="window.location='<?= \yii\helpers\Url::to(['/index'])?>'">返回</button>
    </h2>
    <div style="border: 1px solid #000000" class="width-650 height-54 ">
        <label class="ml-50 mt-20">生成示例</label><span id="code"></span>
        <label class="ml-50 mt-20">表名</label><?= $code ?>
    </div>
    <div style="border: 1px solid #000000" class="width-650 height-230 mt-20">
        <div>
            <label> 编码生成规则</label>

            <table class="table-no-border mt-20">
                <tbody>
                    <tr>
                        <th class="width-50"></th>
                        <th  class="width-70">前缀一</th>
                        <th  class="width-70">前缀二</th>
                        <th  class="width-190">日期</th>
                        <th  class="width-70">流水号</th>
                        <th  class="width-190">截取字段</th>
                    </tr>
                    <tr class="height-40">
                        <td class="text-right"><label>组成</label></td>
                        <td class="text-center"><input class="width-50 text-center  rule_1" name="BsFormCodeRule[rule_one]" value="<?= $model['rule_one']?>" id="rule_one"/></td>
                        <td class="text-center"><input class="width-50 text-center rule_2" name="BsFormCodeRule[rule_two]" value="<?= $model['rule_two']?>" id="rule_two"/></td>
                        <td class="text-center">
                            <select name="BsFormCodeRule[rule_three]" class="width-60 text-center rule_3" id="rule_three">
                                <option value=''>取消</option>
                                <option value=1 <?= $model['rule_three']==1?"selected":''; ?>>YYYY</option>
                                <option value=2 <?= $model['rule_three']==2?"selected":''; ?>>YY</option>
                                <option value=3 <?= $model['rule_three']==3?"selected":''; ?>>MM</option>
                                <option value=4 <?= $model['rule_three']==4?"selected":''; ?>>DD</option>
                            </select>
                            <select name="BsFormCodeRule[rule_four]" class="width-60 text-center rule_4" id="rule_four">
                                <option value=''>取消</option>
                                <option value=1 <?= $model['rule_four']==1?"selected":''; ?>>YYYY</option>
                                <option value=2 <?= $model['rule_four']==2?"selected":''; ?>>YY</option>
                                <option value=3 <?= $model['rule_four']==3?"selected":''; ?>>MM</option>
                                <option value=4 <?= $model['rule_four']==4?"selected":''; ?>>DD</option>
                            </select>
                            <select name="BsFormCodeRule[rule_five]" class="width-50 text-center rule_5" id="rule_five">
                                <option value=''>取消</option>
                                <option value=1 <?= $model['rule_five']==1?"selected":''; ?>>YYYY</option>
                                <option value=2 <?= $model['rule_five']==2?"selected":''; ?>>YY</option>
                                <option value=3 <?= $model['rule_five']==3?"selected":''; ?>>MM</option>
                                <option value=4 <?= $model['rule_five']==4?"selected":''; ?>>DD</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <input class="width-50 text-center easyui-validatebox rule_6" name="BsFormCodeRule[serial_number_start]" value="<?= $model['serial_number_start']?>" id="number_start"/>
                        </td>
                        <td class="text-right">
                            <input class="width-60 text-center" name="BsFormCodeRule[field]" placeholder="字段名称" value="<?= $model['field']?>" id="field"/>
                            <input class="width-60 text-center positive" name="BsFormCodeRule[field_begin]" placeholder="开始位置" value="<?= $model['field_begin']?>" id="field"/>
                            <input class="width-60 text-center positive" name="BsFormCodeRule[field_end]" placeholder="结束位置" value="<?= $model['field_end']?>" id="field"/>
                        </td>
                    </tr>
                    <tr class="height-40">
                        <td class="text-right"><label>顺序</label></td>
                        <td class="text-center">
                            <select class="width-50" name="BsFormCodeRule[rule_one_index]" id="index_1">
<!--                                <option value=undefined>取消</option>-->
                                <option value=1 <?= $model['rule_one_index']==1 || !isset($model['rule_one_index'])?"selected":''; ?>>1</option>
                                <option value=2 <?= $model['rule_one_index']==2?"selected":''; ?>>2</option>
                                <option value=3 <?= $model['rule_one_index']==3?"selected":''; ?>>3</option>
                                <option value=4 <?= $model['rule_one_index']==4?"selected":''; ?>>4</option>
                                <option value=5 <?= $model['rule_one_index']==5?"selected":''; ?>>5</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <select class="width-50" name="BsFormCodeRule[rule_two_index]" id="index_2">
<!--                                <option>取消</option>-->
                                <option value="1" <?= $model['rule_two_index']==1?"selected":''; ?>>1</option>
                                <option value="2" <?= $model['rule_two_index']==2 || !isset($model['rule_two_index'])?"selected":''; ?>>2</option>
                                <option value="3" <?= $model['rule_two_index']==3?"selected":''; ?>>3</option>
                                <option value="4" <?= $model['rule_two_index']==4?"selected":''; ?>>4</option>
                                <option value="5" <?= $model['rule_two_index']==5?"selected":''; ?>>5</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <select class="width-50" name="BsFormCodeRule[rule_three_index]" id="index_3">
<!--                                <option>取消</option>-->
                                <option value="1" <?= $model['rule_three_index']==1?"selected":''; ?>>1</option>
                                <option value="2" <?= $model['rule_three_index']==2?"selected":''; ?>>2</option>
                                <option value="3" <?= $model['rule_three_index']==3 || !isset($model['rule_three_index'])?"selected":''; ?>>3</option>
                                <option value="4" <?= $model['rule_three_index']==4?"selected":''; ?>>4</option>
                                <option value="5" <?= $model['rule_three_index']==5?"selected":''; ?>>5</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <select class="width-50" name="BsFormCodeRule[start_index]" id="index_4">
<!--                                <option>取消</option>-->
                                <option value="1" <?= $model['start_index']==1?"selected":''; ?>>1</option>
                                <option value="2" <?= $model['start_index']==2?"selected":''; ?>>2</option>
                                <option value="3" <?= $model['start_index']==3?"selected":''; ?>>3</option>
                                <option value="4" <?= $model['start_index']==4 || !isset($model['start_index'])?"selected":''; ?>>4</option>
                                <option value="5" <?= $model['start_index']==5?"selected":''; ?>>5</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <select class="width-50" name="BsFormCodeRule[field_index]" id="index_5">
                                <option value="1" <?= $model['field_index']==1?"selected":''; ?>>1</option>
                                <option value="2" <?= $model['field_index']==2?"selected":''; ?>>2</option>
                                <option value="3" <?= $model['field_index']==3?"selected":''; ?>>3</option>
                                <option value="4" <?= $model['field_index']==4?"selected":''; ?>>4</option>
                                <option value="5" <?= $model['field_index']==5 || !isset($model['field_index'])?"selected":''; ?>>5</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php $form->end() ?>
</div>
<script>
    $(function(){
        getCode();
//
//        $("#rule_one").validatebox({
//            required:true
//        });
        $("#number_start").validatebox({
            validType:'int'
        });
        $(".positive").validatebox({
            validType:'positive'
        });
        var indexS=$("select[id^='index_']");
        var ruleS=$("select[class^='rule_']");
        var obj=[];
        indexS.each(function(i,item){
            obj[i]="'#"+$(item).attr("id")+"'";
        });

        indexS.validatebox({
            required:true,
            validType:"same["+obj+"]"
        });


        var $form=$("#add-form");
        $($form).on("beforeSubmit", function () {
            if (!$(this).form('validate')) {
                return false;
            }
            var options = {
                dataType: 'json',
                success: function (data) {
                    if (data.flag == 1) {
                        layer.alert(data.msg, {
                            icon: 1
                        });
                    }
                    if (data.flag == 0) {
                        layer.alert(data.msg, {
                            icon: 2
                        });
                    }
                },
                error: function (data) {
                    layer.alert(data.responseText, {
                        icon: 2
                    });
                }
            };

            $($form).ajaxSubmit(options);
            return false;
        });

        $("select,input").change(function(){
           var select=[];
            $("select[id^='index_']").each(function(index,item){
                select[index]=$(item).val()
            })
            var nary=select.sort();

            for(var i=0;i<select.length;i++){

                if (nary[i]==nary[i+1]){

                    $("#code").html('');
                    return false;

                }
            }
            getCode()
        });

    });
    function getCode(){
        var rule_one    = $("#rule_one").val();
        var rule_two    = $("#rule_two").val();
        var rule_three=$("#rule_three").val();
        var rule_four=$("#rule_four").val();
        var rule_five=$("#rule_five").val();
        var number_start= $("#number_start").val();
        var field= 'XX';
        var index_1     = $("#index_1").val();
        var index_2     = $("#index_2").val();
        var index_3     = $("#index_3").val();
        var index_4     = $("#index_4").val();
        var index_5     = $("#index_5").val();
        var code='';
        var index=[''];
         index[index_1]=rule_one;
         index[index_2]=rule_two;
         index[index_3]=getDate(rule_three)+getDate(rule_four)+getDate(rule_five);
         index[index_4]=number_start;
         index[index_5]=field;
        $(index).each(function (index, obj) {
            if(obj!=undefined){
                code+=obj;
            }
        });
         $("#code").html(code)
    }

     function getDate(val){
         var now = new Date();
         var year = now.getFullYear();    //年
         var month = now.getMonth() + 1;   //月
         if(month<10){
             month="0"+month;
         }
         var day = now.getDate();      //日
         if(day<10){
             day="0"+day;
         }
        switch(val){
            case '1':
                return year;
                break;
            case '2':
                year+="";
                return  year.slice(2);
                break;
            case '3':
                return month;
                break;
            case '4':
                return day;
                break;
            default:
                return '';
        }

    }

</script>