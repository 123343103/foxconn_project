<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/2/20
 * Time: 上午 10:52
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\modules\common\models\BsTransaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mb-30">

    <?php $form = ActiveForm::begin(
        ['id' => 'add-form']
    ); ?>
    <div class="mb-10">
        <div class="inline-block field-bstransaction-tac_code required">
            <label class="width-100 " for="bstransaction-tac_code">类别编码</label>
            <input type="text" id="SaleCostType-stcl_code" class="width-200 easyui-validatebox" data-options="required:'true'" name="SaleCostType[scost_code]" value="<?=$model->scost_code?>">
        </div>
    </div>
    <div class="space-20"></div>
    <div class="mb-10">
        <div class="mb-10">
            <div class="inline-block field-bstransaction-remarks">
                <label class="width-100 vertical-top" for="bstransaction-remarks">类型描述</label>
                <textarea id="bstransaction-remarks" class="width-500" name="SaleCostType[scost_description]" rows="3"><?=$model->scost_description?></textarea>
                <div class="help-block"></div>
            </div>                </div>
    </div>
    <div class="space-20"></div>
    <div class="mb-10">
        <div class="inline-block field-bstransaction-tac_code required">
            <label class="width-100 " for="bstransaction-tac_code">状态</label>
            <!--<input type="text" id="bstransaction-tac_code" class="width-250 easyui-validatebox" data-options="required:'true'" name="BsTransaction[tac_code]" value="<?/*=$model->tac_code*/?>">-->
            <select name="SaleCostType[scost_status]" class="width-200">
                <option>请选择</option>
                <option value="1">有效</option>
                <option value="0">无效</option>
            </select>
        </div>
    </div>
    <div class="space-20"></div>
    <div class="mb-10">
        <div class="mb-10">
            <div class="inline-block field-bstransaction-remarks">
                <label class="width-100 vertical-top" for="bstransaction-remarks">备注</label>
                <textarea id="bstransaction-remarks" class="width-500" name="SaleCostType[scost_remark]" rows="3"><?=$model->remark?></textarea>
                <div class="help-block"></div>
            </div>                </div>
    </div>
    <div>
        <!--<h2 class="head-second text-center" style="">
            出差计划
        </h2>-->
        <div class="space-10"></div>
        <h2 class="head-three">
        </h2>
        <div class="mb-20" style="width:99%;">
            <p class="ml-50 width-100 float-left">费用组合</p>
            <p style="line-height: 25px;" class="float-right mr-50 ">
                <button class="button-blue text-center" onclick="vacc_add()" type="button">+ 添&nbsp;加</button>
            </p>
            <div class="space-10 clear"></div>
            <table class="table-small">
                <thead>
                <tr>
                    <th class="width-200">费用代码</th>
                    <th class="width-200">费用名称</th>
                    <th class="width-200">状态</th>
                    <th class="width-200">描述</th>
                    <th class="width-200">操作</th>
                </tr>
                </thead>
                <tbody id="vacc_body">
                <tr>
                    <td>
                        <!--<input type="text" name="vacc[]" placeholder="代码" readonly class="width-150  no-border text-center " onblur="job_num(this)">-->
                        <select  id="costListC" name="vacc[]" class="width-150   text-center" onchange="job_num(this)">
                            <!--<option class="text-center">请选择费用分类</option>-->
                            <option>请选择</option>
                            <?php foreach ($saleCostListValue as $key => $val) { ?>
                                <?php if (isset($model)) { ?>
                                    <option
                                        value="<?= $key ?>" <?= $model->stcl_id == $key ? "selected" : null ?>><?= $val ?> </option>
                                <?php } else { ?>
                                    <option value="<?= $key ?>"><?= $val ?> </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <!--<a href="<?/*=Url::to(['/sale/sale-cost-type/select-com']) */?>" id="cost-search" class="fancybox.ajax"></a>-->
                        <i class="icon-search"></i>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><a onclick="reset(this)">重置</a> <a onclick="vacc_del(this)">删除</a></td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton('确&nbsp认' ,['class' =>'button-blue-big ml-300','id'=>'submit']) ?>&nbsp;
        <?= Html::resetButton('取&nbsp消', ['class' => 'button-white-big ml-20','id'=>'goback' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $(function(){
//        ajaxSubmitForm($("#add-form"));//ajax提交
        $('#goback').click(function () {
            history.back(-1);
        });
    })
    //刪除
    function vacc_del(obj){
        var tr= $("#vacc_body tr").length;
        if(tr>1){
            $(obj).parents("tr").remove();
        }
    }
    //添加家费用类别
    function vacc_add() {
        $("#vacc_body").append(
            '<tr>' +
            '<td>' +
            /*'<input onblur="job_num(this)" type="text" readonly class="width-150  no-border text-center" placeholder="代码" name="vacc[]">' +*/
            '<select  id="" name="vacc[]" class="width-150   text-center" onchange="job_num(this)">'+
            '<option>请选择'+'</option>'+
            <?php foreach ($saleCostListValue as $key => $val) { ?>
            <?php if (isset($model)) { ?>
            '<option value="<?= $key ?>" <?= $model->stcl_id == $key ? "selected" : null ?>><?= $val ?> </option>'+
            <?php } else { ?>
            '<option value="<?= $key ?>"><?= $val ?> </option>'+
            <?php } ?>
            <?php } ?>
            '</select>'+
            '&nbsp;'+'<i id="code_search" class=" icon-search"></i>' +
            "</td>" +
            '<td>' +
            '</td>' +
            '<td>' +
            '</td>' +
            '<td>' +
            '</td>' +
            '<td><a onclick="reset(this)">重置</a> <a onclick="vacc_del(this)">删除</a></td>' +
            '</tr>'
        );
    }
    function job_num(obj) {
        var codeList = [];
        var i=0;
        $("select[name='vacc[]']").each(function(index,item){
            codeList[i]=$(this).val();
            i++;

        });
        console.log(codeList.pop());
        var td = $(obj).parents("tr").find("td");
        //费用类别信息
        var code = $(obj).val();
        console.log($.inArray(code,codeList));
        if ($.inArray(code,codeList)!==-1){
            layer.alert("请勿重复选择!", {icon: 2})
            vacc_del(obj);
        }else {
        if (!code) {
            return
        }
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"id": code},
            url: "<?= Url::to(['/sale/sale-cost-type/get-cost-list'])?>",
            success: function (data) {
                $(obj).val($(obj).val().toUpperCase());
                td.eq(1).text(data.stcl_sname);
                td.eq(2).text('有效');
                //td.eq(2).text(data.stcl_status);
                td.eq(3).text(data.stcl_description);
            },
            error: function (data) {
                layer.alert("发生未知错误!", {icon: 2})
            }
        })
        }
    }
    //重置
    function reset(obj){
        var td = $(obj).parents("tr").find("td");
//        $(obj).parents("tr").find(td.eq(0)).text("");
        $(obj).parents("tr").find(td.eq(1)).text("");
        $(obj).parents("tr").find(td.eq(2)).text("");
        $(obj).parents("tr").find(td.eq(3)).text("");
        $(obj).parents("tr").find("input").val("");
    };

</script>
