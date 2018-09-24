<?php
/**
 * User: F1677929
 * Date: 2017/3/8
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
\app\assets\JqueryUIAsset::register($this);
?>
<h1 class="head-first">新增缴费</h1>
<?php ActiveForm::begin([
    'id'=>'pay_form',
    'action'=>Url::to(['pay','id'=>$data['applyData']['acth_id']]),
]);?>
<div class="mb-20">
    <label class="width-100">日期</label>
    <input type="text" class="width-150 select-date" readonly="readonly" name="CrmActivePay[actpaym_date]" value="<?=empty($data['payData']['actpaym_date'])?date('Y-m-d'):$data['payData']['actpaym_date']?>">
</div>
<div class="mb-20">
    <label class="width-100"><span class="red">*</span>缴费金额</label>
    <input type="text" class="width-150 easyui-validatebox" data-options="required:true,validType:['length[0,20]','int']" name="CrmActivePay[actpaym_amount]" value="<?=$data['payData']['actpaym_amount']?>">
    <span>待缴费金额：<?=$data['applyData']['acth_payamount']?></span>
</div>
<div class="mb-20">
    <label class="width-100">是否开票</label>
    <select class="width-150" name="CrmActivePay[actpaym_isfp]">
        <option value="0" <?php
        if(empty($data['payData']['actpaym_isfp'])){
            if($data['applyData']['acth_isbill']=='0'){
                echo 'selected';
            }
        }else{
            if($data['payData']['actpaym_isfp']=='0'){
                echo 'selected';
            }
        }
        ?>>未开票</option>
        <option value="1" <?php
        if(empty($data['payData']['actpaym_isfp'])){
            if($data['applyData']['acth_isbill']=='1'){
                echo 'selected';
            }
        }else{
            if($data['payData']['actpaym_isfp']=='1'){
                echo 'selected';
            }
        }
        ?>>已开票</option>
    </select>
</div>
<div class="mb-20">
    <label class="width-100 vertical-top">缴费描述</label>
    <textarea class="easyui-validatebox" data-options="validType:'length[0,200]'" style="height:80px;width:500px;" name="CrmActivePay[actpaym_paydesription]"><?=$data['payData']['actpaym_paydesription']?></textarea>
</div>
<div class="mb-20">
    <label class="width-100 vertical-top">备注</label>
    <textarea class="easyui-validatebox" data-options="validType:'length[0,200]'" style="height:80px;width:500px;" name="CrmActivePay[actpaym_remark]"><?=$data['payData']['actpaym_remark']?></textarea>
</div>
<div class="text-center">
    <button class="button-blue mr-20" type="submit">确定</button>
    <button class="button-white" type="button" onclick="parent.$.fancybox.close()">取消</button>
</div>
<?php ActiveForm::end();?>
<script>
    $(function(){
        //ajax提交表单
        ajaxSubmitForm($("#pay_form"),'',
            function(data){
                parent.layer.alert(data.msg, {
                    icon: 1,
                    end: function () {
                        parent.$("#apply_table").datagrid('reload');
                        parent.$("#pay_info_tab").datagrid('reload');
                        parent.$.fancybox.close();
                    }
                })
            }
        );

        $(".select-date").click(function () {
            jeDate({
                dateCell: this,
                zIndex:8831,
                format: "YYYY-MM-DD",
                skinCell: "jedatedeep",
                isTime: false,
                okfun:function(elem) {
                    $(elem).change();
                },
                //点击日期后的回调, elem当前输入框ID, val当前选择的值
                choosefun:function(elem) {
                    $(elem).change();
                }
            })
        })
    })
</script>