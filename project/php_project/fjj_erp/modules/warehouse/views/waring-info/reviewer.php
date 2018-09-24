<?php
/**
 * User: F3859386
 * Date: 2017/5/8
 * Time: 16:28
 */
use app\assets\MultiSelectAsset;
use yii\widgets\ActiveForm;

MultiSelectAsset::register($this);
?>
<style>
    .ms-container{
        margin-left:75px;
    }
    .review{
        background-color: #d6d6d6;
        width: 80px;
        height: 80px;
        font-size: 14px;
        text-align: center;
        line-height:80px;
        overflow: hidden;
    }
    .icon-large{
        font-size: 20px;
        line-height:80px;
        color: #dce6f2;
        padding:0 10px;
    }
    .space-40 {
        width: 100%;
        height: 40px;
    }
    .mb-20 {
        margin-bottom: 20px;
    }
    .ml-20 {
        margin-left: 20px;
    }
    .ml-80 {
        margin-left: 80px;
    }
    .ml-180 {
        margin-left: 180px;
    }

</style>
<div class="no-padding width-750">
    <h2 class="head-first" >单据审核流程</h2>
    <?php $form = ActiveForm::begin(['id'=>"add-form"]) ?>
    <input type="hidden" name="reviewer" id="reviewer">
    <input type="hidden" name="arrayNum" value="<?=$arrayNum?>">
    <?php if($review['flag']==0){?>
        <div class="text-center">
            <div class="space-40"></div>
            <div class="space-40"></div>
            <div class="text-center index-head"><?= $review['msg'] ?></div>
            <div class="space-40"></div>
            <div class="space-40"></div>
            <button class="button-blue-big ml-20" type="button" onclick="parent.$.fancybox.close()">确定</button>
        </div>
    <?php }else if (empty($review['reviewer'])){?>
        <div class="text-center">
            <div class="space-40"></div>
            <div class="space-40"></div>
            <div class="text-center index-head"><?= '没有对应的审核规则' ?></div>
            <div class="space-40"></div>
            <div class="space-40"></div>
            <button class="button-blue-big ml-20" type="button" onclick="parent.$.fancybox.close()">确定</button>
        </div>
    <?php }else{?>
        <?php if($review['status']){?>
            <div class="mb-20">
                <div class="display-flex"><p class="head ml-80 mb-10">单据设置审核人及签核流：</p> <p class="head ml-180 mb-10">单据新的签核流： </p></div>
                <select multiple="multiple" id="my-select">
                    <?php foreach ($review['reviewer'] as $k => $v) { ?>
                        <option value="<?=$v['rule_child_id'] ?>"><?= !empty($v['review_role'])?$v['review_role']:$v['review_user'] ?></option>
                    <?php } ?>
                </select>
            </div>
        <?php }else{?>
            <div class="mb-20 mt-30 text-center width-750">
                <div class="overflow-auto width-700">
                    <div id="reviewer-info" class="wrap  inline-block"> </div>
                    <div class="space-10"></div>
                </div>
            </div>
            <div class="space-40"></div>
            <div class="space-40"></div>
            <div class="text-center index-head">确认送审?</div>
            <div class="space-40"></div>
            <div class="space-40"></div>
        <?php }?>
        <div class="mb-20 text-center">
            <button class="button-blue-big" type="submit" >确定</button>
            <button class="button-white-big ml-20" type="button" onclick="parent.$.fancybox.close()">取消</button>
        </div>
    <?php }?>

</div>
<?php ActiveForm::end() ?>
<script>
    $(function(){


        var $form=$("#add-form");
        $($form).on("beforeSubmit", function () {
            if($("#reviewer").val()==''){
                layer.alert("请选择审核人", {icon: 2, time: 5000});
                $("button[type='submit']").prop("disabled",false);
                return false;
            }

            var options = {
                dataType: 'json',
                success: function (data) {
                    if (data.flag == 1) {
                        layer.alert(data.msg, {
                            icon: 1,
                            end: function () {
                                parent.location.href = data.url;
                            }
                        });
                    }
                    if (data.flag == 0) {
                        layer.alert(data.msg, {
                            icon: 2
                        });
                        $("button[type='submit']").prop("disabled", false);
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


        var i=0;
        var val=[];
        $("#my-select").multiSelect({
            keepOrder: true,        //自由排序
            afterSelect: function(values){
                val[i]=[values];
                $("#reviewer").val(val);
                i++;
                var li=$('.ms-selection>.ms-list>.ms-selected>span');
                $(".index").remove();
                li.each(function(x,value){
                    $(value).before('<index class=\'index\'>'+(x+1)+":</index>");
                })
            },
            afterDeselect: function(values){
                val.splice($.inArray(values,val),1);
                $("#reviewer").val(val);
//                console.log($("#reviewer").val());
                i--;
                var li=$('.ms-selection>.ms-list>.ms-selected>span');
                $(".index").remove();
                li.each(function(x,value){
                    $(value).before('<index class=\'index\'>'+(x+1)+":</index>");
                })
            }
        });

        //不能编辑时的审核流程信息
        var enabled = "<?= $review['status'] ?>";
        if (enabled != 1) {
            var html='';
            var review='';
            $(<?= \yii\helpers\Json::encode($review['reviewer'])?>).each(function(x,value){
                review = value.review_user==null?value.review_role:value.review_user;
                var icon="<div class='icon-arrow-right icon-large'></div>";
                if(x==0){
                    icon='';
                }
//            if(x==3){
//               icon="<div class='space-40'></div>";
//            }
                html += icon+"<div class='review'>"+review+"</div>";

                $("#reviewer-info").html(html);
                val[x]=value.rule_child_id;
                $("#reviewer").val(val);
            });
        }
    });

</script>
