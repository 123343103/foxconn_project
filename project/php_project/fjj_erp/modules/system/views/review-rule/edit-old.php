<?php
/**
 *  审核流修改页
 *  F3858995
 *  2016/10/28
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<h1 class="head-first"><?=$model->review_desc?>流程</h1>
<?php $form = ActiveForm::begin([
    'id'=>'add-form'
]); ?>
<div class="mt-40">
    <label>选择规则<?= $orgId ?></label>
    <select class="select-rule width-100">
        <option value="1">系统规则</option>
        <option value="2">部门规则</option>
    </select>
    <div class="select-org inline-block display-none">
        <label class="ml-20">选择部门</label>
        <select class="width-260 text-center" disabled name="org">
            <option value="">请选择部门</option>
            <?php foreach ($orgList as $k => $v) {?>
                <option value="<?= $v['organization_id'] ?>" data-org="<?= $v['organization_id'] ?>" <?= ($orgId==$v['organization_id'])?"selected=\"selected\"":null; ?> ><?= str_repeat('|-',$v['organization_level']).$v['organization_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <input type="hidden" name="business_code" value="<?= $business_code['business_code'] ?>">
    <input type="hidden" name="del" id="del" >
</div>
<div class="cnt">
    <div class="mt-20 review-rule">
        <span class='width-50'>单据</span>
        <span class="no-border">→</span>
        <span class=' show-span on-select' id="name-1" inputDiv="input-1">
        </span>
        <span class="no-border" id="add">&nbsp;&nbsp;<a>add</a></span>
        <span class="no-border" id="delete">&nbsp;&nbsp;<a class="red">delete</a></span>
    </div>
    <div class="input-form">
        <div class="mt-10 float-right width-150">
            <label>可变动选择审核人</label>
            <input type="checkbox" name="business_status" id="business_status" class="vertical-middle" value="20" <?= $model->business_status==20?"checked=\"checked\"":'' ?>>
        </div>
        <div class="review-rule-desc mt-40" id="input-1" >
            <div>
                <span class="mt-10 ml-30 ">
                    <input type="hidden" name="rule[1][id]" id="review_id_1" >
                    <input type="radio" name="rule[1][review_type]" id="review_user_1" value="user" checked>
                    <label class="no-after" for="review_user_1">审核人</label>
                    <input type="radio" name="rule[1][review_type]" id="review_role_1" class="ml-10" value="role">
                    <label class="no-after" for="review_role_1">审核角色</label>
                </span>
                <br/>
                <span class="user_input">
                <label class="mt-10 ml-10 width-70">审批人</label>
                    <input name="rule[1][user]" class="width-80 user" id="user_1">
                    <br/>
                      <label class="mt-10 ml-10 width-70">代理人一</label>
                    <input name="rule[1][agentOne]" class="width-80 agent" id="agentOne_1">
                    <label class="mt-10 ml-40 mb-10 width-70">代理人二</label>
                    <input name="rule[1][agentTwo]" class="width-80 agent" id="agentTwo_1">
                </span>
                <span class="role_input display-none">
                    <label class="mt-10 ml-10 width-70 mb-10">审批角色</label>
                    <input name="rule[1][role]" id="rule_1" class="width-100 role">
                </span>
            </div>
            <div class=" no-border-top">
                <p class="ml-10 pt-10 pb-10">审核条件:</p>
                <table class="table-small table-no-border">
                    <thead>
                    <tr class="no-border">
                        <th>条件</th>
                        <th>条件逻辑</th>
                        <th>条件参数</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <input type="hidden" name="rule[1][conditions][1][id]">
                        <td><input name="rule[1][conditions][1][name]" class="condition-select-1"></td>
                        <td><input name="rule[1][conditions][1][logic]" class="condition-logic-1"></td>
                        <td><input name="rule[1][conditions][1][para]"></td>
                    </tr>
                    <tr>
                        <input type="hidden" name="rule[1][conditions][2][id]">
                        <td><input name="rule[1][conditions][2][name]" class="condition-select-1"></td>
                        <td><input name="rule[1][conditions][2][logic]" class="condition-logic-1"></td>
                        <td><input name="rule[1][conditions][2][para]"></td>
                    </tr>
                    <tr>
                        <input type="hidden" name="rule[1][conditions][3][id]">
                        <td><input name="rule[1][conditions][3][name]" class="condition-select-1"></td>
                        <td><input name="rule[1][conditions][3][logic]" class="condition-logic-1"></td>
                        <td><input name="rule[1][conditions][3][para]"></td>
                    </tr>
                    <tr>
                        <input type="hidden" name="rule[1][conditions][4][id]">
                        <td><input name="rule[1][conditions][4][name]" class="condition-select-1"></td>
                        <td><input name="rule[1][conditions][4][logic]" class="condition-logic-1"></td>
                        <td><input name="rule[1][conditions][4][para]"></td>
                    </tr>
                    </tbody>
                </table>
                <div class="space-10 no-border"></div>
            </div>
        </div>
    </div>
    <div class="ml-260 mt-20">
        <?= Html::submitButton('保存', ['class' => 'button-blue-big']) ?>
        <?= Html::button('返回', ['class' => 'button-white-big ml-20','onclick'=>'history.go(-1)']) ?>
    </div>
</div>
<?php  $form->end(); ?>
<script>
    //检测表单是否已经修改过
    $(window).bind('beforeunload',function(){
        return '您输入的内容尚未保存，确定离开此页面吗？';
    });
    var data=<?= $data; ?>;
    var type="<?= $type; ?>";
    $(function () {

        //ajax提交表单
        var $form=$("form");
        $($form).on("beforeSubmit", function () {
            if (!$(this).form('validate')) {
                return false;
            }
            $("button[type='submit']").prop("disabled", false);
            var options = {
                dataType: 'json',
                success: function (data) {
                    if (data.flag == 1) {
                        layer.alert(data.msg, {
                            icon: 1,
                            end: function () {
//                                if (data.url != undefined) {
//                                    parent.location.href = data.url;
//                                }
                                var select = $('#tree').treeview('getSelected')[0];
                                var orgId = $("select[name='org'] option:selected").data('org');
                                if (orgId!='' && typeof(orgId)!='undefined') {
                                    $("#load").html('');
                                    $("#load").load("<?=\yii\helpers\Url::to(['/system/review-rule/edit']) ?>?id=" + select.id + '&orgId=' + orgId);
                                } else {
                                    $("#load").html('');
                                    $("#load").load("<?=\yii\helpers\Url::to(['/system/review-rule/edit']) ?>?id=" + select.id);
                                }
                            }
                        });
                    }
                    if (data.flag == 0) {
                        layer.alert(data.msg, {
                            icon: 2
                        });
                        $("button[type='submit']").prop("disabled", true);
                    }
                    $("button[type='submit']").attr('disabled',true);
                },
                error: function (data) {
                    layer.alert(data.responseText, {
                        icon: 2
                    });
                }
            };

            $($form).ajaxSubmit(options);
            return false;
        })
        var dataProvider= <?= $dataProvider; ?>;
        $(".condition-select-1").each(function(){
            getColumn($(this));
        });
        $(".condition-logic-1").each(function(){
            getLogic($(this));
        });
        $(".role").each(function(){
            getRole($(this));
        });
        $(".user").each(function(){
            getUser($(this));
        });
        $(".agent").each(function(){
            getAgent($(this));
        });
//        getColumn($(".condition-select-1"));
//        getRole(".role");
//        getUser(".user");
//        getAgent(".agent");

        $(document).on("click", "input[type='radio']", function () {
            var $div = $(this).parent().parent();
            if ($(this).val() == "user") {
                $div.find(".role_input").find("input").val("");
                $("[inputDiv='" + $div.parent().attr("id") + "']").html("");
                $div.find(".role_input").hide();
                $div.find(".user_input").show();
            } else {
                $div.find(".user_input").find("input").val("");
                $("[inputDiv='" + $div.parent().attr("id") + "']").html("");
                $div.find(".role_input").show();
                $div.find(".user_input").hide();
            }
        });
        var i = 1;
        $("#add").on("click", function () {
            $(".review-rule-desc").hide();
            var newSpan = "<span class='no-border'>→</span> <span class='show-span' id='name-" + ++i + "' inputDiv='input-" + i + "'>";
            var newTable = "<div class='review-rule-desc mt-40 ' id='input-" + i + "' >"
            newTable += "<div>";
            newTable += "<span class='mt-10 ml-30 '>";
            newTable += "<input type='hidden' name='rule[" + i + "][id]' id='review_id_" + i + "' >";
            newTable += "<input type='radio' name='rule[" + i + "][review_type]' id='review_user_" + i + "' value='user' checked>&nbsp;<label class='no-after ' for='review_user_" + i + "' >审核人</label>";
            newTable += "&nbsp;<input type='radio' name='rule[" + i + "][review_type]' id='review_role_" + i + "' class='ml-10' value='role'>&nbsp;<label class='no-after' for='review_role_" + i + "'>审核角色</label></span><br/>";
            newTable += "<span class='user_input' > <label class='mt-10 ml-10 width-70'>审批人</label><input name='rule[" + i + "][user]' class='width-80 user' id='user_" + i + "'> <br/>";
            newTable += "<label class='mt-10 ml-10 width-70'>代理人一</label><input name='rule[" + i + "][agentOne]' class='width-80 agent' id='agentOne_" + i + "'>";
            newTable += "<label class='mt-10 ml-40 mb-10 width-70'>代理人二</label><input name='rule[" + i + "][agentTwo]' class='width-80 agent' id='agentTwo_" + i + "'>";
            newTable += "</span> <span class='role_input display-none'> <label class='mt-10 ml-10 width-70 mb-10'>审批角色</label><input name='rule[" + i + "][role]' class='width-100 role' id='rule_" + i + "'></span>";
            newTable += " </div><div style='border-top: none;'>";
            newTable += " <p class='ml-10 pt-10 pb-10'>审核条件:</p>";
            newTable += "<table class='table-small table-no-border'>";
            newTable += "<thead><tr> <th>条件</th> <th>条件逻辑</th> <th>条件参数</th> </tr> </thead>";
            newTable += "<tbody><tr>";
//            newTable += "";
            newTable += " <td><input type='hidden' name='rule[" + i + "][conditions][1][id]'><input name='rule[" + i + "][conditions][1][name]' class='condition-select-" + i + "'></td><td><input name='rule[" + i + "][conditions][1][logic]' class='condition-logic-" + i + "'></td> <td><input name='rule[" + i + "][conditions][1][para]'></td> </tr>";
            newTable += " <td><input type='hidden' name='rule[" + i + "][conditions][2][id]'><input name='rule[" + i + "][conditions][2][name]' class='condition-select-" + i + "'></td><td><input name='rule[" + i + "][conditions][2][logic]' class='condition-logic-" + i + "'></td> <td><input name='rule[" + i + "][conditions][2][para]'></td> </tr>";
            newTable += " <td><input type='hidden' name='rule[" + i + "][conditions][3][id]'><input name='rule[" + i + "][conditions][3][name]' class='condition-select-" + i + "'></td><td><input name='rule[" + i + "][conditions][3][logic]' class='condition-logic-" + i + "'></td> <td><input name='rule[" + i + "][conditions][3][para]'></td> </tr>";
            newTable += " <td><input type='hidden' name='rule[" + i + "][conditions][4][id]'><input name='rule[" + i + "][conditions][4][name]' class='condition-select-" + i + "'></td><td><input name='rule[" + i + "][conditions][4][logic]' class='condition-logic-" + i + "'></td> <td><input name='rule[" + i + "][conditions][4][para]'></td> </tr>";
            newTable += " </tbody></table> <div class='space-10 no-border'></div></div></div>";
            $(".input-form").append(newTable);

            $(this).before(newSpan);
            $(".condition-logic-" + i).each(function(){
                getLogic(this);
            });
            $(".condition-select-" + i).each(function(){
                getColumn(this);
            });
            $("#name-" + i).click();
            $("[name='rule[" + i + "][user]']").each(function(){
                getUser(this);
            });
            $("[name='rule[" + i + "][role]']").each(function(){
                getRole(this);
            });
            $("[name='rule[" + i + "][agentTwo]']").each(function(){
                getAgent(this);
            });
            $("[name='rule[" + i + "][agentOne]']").each(function(){
                getAgent(this);
            });
        });

        //遍历出审核信息
        if(dataProvider !=null){
            $.each(dataProvider,function(n,val) {
                n++;
                $("[name='rule[" + n + "][id]']").val(val.rule_child_id);
                if(val.review_role_id != null && val.review_role_id != ''){
                    $("#review_role_" + n).click();
                    $("[name='rule[" + n + "][role]']").val(val.review_role_id);
                    setValue("[textboxname='rule[" + n + "][role]",val.review_role_id)
                }else{
                    $("[name='rule[" + n + "][user]']").val(val.review_user_id);
                    $("[name='rule[" + n + "][agentOne]']").val(val.agent_one_id);
                    $("[name='rule[" + n + "][agentTwo]']").val(val.agent_two_id);
                    setValue("[textboxname='rule[" + n + "][user]']",val.review_user_id)
                    setValue("[textboxname='rule[" + n + "][agentOne]']",val.agent_one_id)
                    setValue("[textboxname='rule[" + n + "][agentTwo]",val.agent_two_id)
                }
                var y=1;
                $.each(val.conditionModel,function(x,value){
                    $("[name='rule[" + n + "][conditions]["+y+"][id]']").val(value.condition_id);
                    $("[name='rule[" + n + "][conditions]["+y+"][name]']").val(value.column);
                    $("[name='rule[" + n + "][conditions]["+y+"][logic]']").val(value.condition_logic);
                    $("[name='rule[" + n + "][conditions]["+y+"][para]']").val(value.condition_value);
                    setValue("[textboxname='rule[" + n + "][conditions]["+y+"][name]']",value.column)
                    setValue("[textboxname='rule[" + n + "][conditions]["+y+"][logic]']",value.condition_logic)
                    y++
                });
                if(dataProvider.length !== n){
                    $('#add').click();
                }
            });
        }

        var del='';
        $("#delete").on("click",function() {
            if(0==i){
                return false;
            }
            var id = $("[name='rule[" + i + "][id]']").val();
            del+=id+',';
            $("#del").val(del)
            $span = $(this).prev().prev();
            $span.prev().remove();
            $span.remove();
            $("#input-"+i).remove();
            i--;
            $("#input-"+i).show();
        });

        $(document).on("click", ".show-span", function () {
            var inputDivId = $(this).attr("inputDiv");
            $(".on-select").removeClass("on-select");
            $(this).addClass("on-select");
            $(".review-rule-desc").hide();
            $("#" + inputDivId).show();
        })
        var type = "<?= !empty($orgId) ?>" || 0;
        if (type == 1) {
            $(".select-org").removeClass('display-none');
            $("select[name='org']").attr('disabled',false);
            $(".select-rule").val(2);
        }

        // 选择规则
        $(".select-rule").on('change', function () {
            var select = $('#tree').treeview('getSelected')[0];
            if ($(this).val() == '1') {
                $("#load").html('');
                $("#load").load("<?=\yii\helpers\Url::to(['/system/review-rule/edit']) ?>?id=" + select.id);
            } else {
                $(".cnt").remove();
                $(".select-org").removeClass('display-none');
                $("select[name='org']").attr('disabled',false);
            }
        })
    });

    // 选择部门
    $("select[name='org']").on('change', function () {
        var select = $('#tree').treeview('getSelected')[0];
        var orgId = $("select[name='org'] option:selected").data('org');
        if (orgId!='' && typeof(orgId)!='undefined') {
            $("#load").html('');
            $("#load").load("<?=\yii\helpers\Url::to(['/system/review-rule/edit']) ?>?id=" + select.id + '&orgId=' + orgId);
        } else {
            $('.cnt').remove();
        }
    });

    function getLogic($select) {
        $($select).combobox({
            data: data.logic,
            valueField: 'id',
            textField: 'text'
        })
    }
    function getColumn($select) {
        $($select).combobox({
            data: data.conditions,
            valueField: 'id',
            textField: 'text'

        })
    }
    function getAgent($select) {
        $($select).combobox({
            data: data.users,
            valueField: 'id',
            textField: 'text',
//            onSelect: function () {
////                combobox.datasource=null
//
//            }
        })
    }
    function getUser($select) {
        $($select).combobox({
            data: data.users,
            valueField: 'id',
            textField: 'text',
            onSelect: function (data) {
                var $parent = $(this).parent().parent().parent();
                $("[inputDiv='" + $parent.attr("id") + "']").html(data.text);

            }
        });
    }
    function getRole($select) {
        $($select).combobox({
            data: data.roles,
            valueField: 'id',
            textField: 'text',
            onSelect: function (data) {
                var $parent = $(this).parent().parent().parent();
                $("[inputDiv='" + $parent.attr("id") + "']").html(data.text);

            }
        });
    }
    function setValue($select,value){
        $($select).combobox({
            onLoadSuccess:function(){
                $($select).combobox('select',value);
//                    $($select).combobox('clear');
            }
        })
    }
    function deleteRule(id){
        option = {
            confirm:"确定要删除?"
        };
        layer.confirm(option.confirm,
            {
                btn:['確定', '取消'],
                icon:2
            },
            function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {"id": id},
                    url: "<?= Url::to(['delete-rule'])?>",
                    success: function (msg) {
                        if( msg.flag === 1){
                            layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
                        }else{
                            layer.alert(msg.msg,{icon:2})
                        }
                    },
                    error :function(msg){
                        layer.alert(msg.msg,{icon:2})
                    }
                })
            },
            function () {
                layer.closeAll();
            }
        )
    }
</script>