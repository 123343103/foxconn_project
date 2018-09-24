<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/7/10
 * Time: 上午 11:17
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '物流公司信息详情';
$this->params['homeLike'] = ['label' => '倉儲物流管理', 'url' => ''];
$this->params['breadcrumbs'][] = ['label' => '倉儲物流管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '物流公司信息详情', 'url' => ""];
?>
<style>
    .search-div {
        width: 990px;
    }

    .back-100 {
        width: 100%;
        height: 30px;
        background: #438EB8;
    }

    .table-heads {
        /*width: 990px;*/
        height: 30px;
    }

    .table-heads p {
        font-size: 16px;
        float: left;
        color: #fff;
        font-weight: bold;
        text-indent: 1em;
    }
</style>
<div class="search-div">
    <div class="table-heads back-100 mt-10">
        <p class="head mt-5">物流公司信息详情</p>
    </div>
    <div class="space-10 ml-10"></div>
    <input type="hidden" id="log_cmp_id" value="<?php echo $views['log_cmp_id'] ?>">
    <div style="border-bottom: 2px solid #00A1CB;">
        <button class="button-blue ml-20 mb-5" type="button" id="update">修改</button>
        <button class="button-blue ml-5 mb-5" type="button" id="delete">删除</button>
        <button class="button-white ml-5 mb-5" type="button" onclick="window.location.href = '<?= Url::to(["index"]) ?>';">
            切换列表
        </button>
    </div>
    <div style="font-size: 14px;">
        <div class="mb-20 mt-10">
            <label class="width-100 ml-50">公司中文名</label>
            <input id="staff_id" type="hidden" value="<?= Yii::$app->user->identity->staff_id ?>" name="HrStaff[staff_code]">
            <input id="log_cmp_name" readonly="readonly" class="width-200 easyui-validatebox"
                   data-options="required:true" data-attr="log_cmp_name" value="<?php echo $views['log_cmp_name'] ?>"
                   name="BsLogCmp[log_cmp_name]"/>
            <label class="width-100">公司英文名</label>
            <input id="log_cmp_EN" readonly="readonly" type="text" value="<?php echo $views['log_cmp_EN'] ?>"
                   class="width-200" data-attr="log_cmp_EN" name="BsLogCmp[log_cmp_EN]"/>
            <label class="width-100">承运代码</label>
            <input id="log_code" readonly="readonly" type="text" value="<?php echo $views['log_code'] ?>"
                   class="width-200 easyui-validatebox" data-options="required:true"
                   data-attr="log_code" name="BsLogCmp[log_code]"/>
            <div class="help-block"></div>
        </div>
        <div class="mt-20">
            <label class="width-100 ml-50">运输方式</label>
            <input id="para_name" readonly="readonly" type="text" value="<?php echo $views['para_name'] ?>"
                   class="width-200 " data-attr="para_name" name="BsLogCmp[para_name]"/>
            <label class="width-100">公司地址</label>
            <input id="log_addr" readonly="readonly" type="text" value="<?php echo $views['log_addr'] ?>"
                   class="width-510 " data-attr="log_addr" name="BsLogCmp[log_addr]"/>
            <div class="help-block">
            </div>
        </div>
        <div class="mt-20">
            <label class="width-100 ml-50">负责人</label>
            <input id="log_char" readonly="readonly" type="text" value="<?php echo $views['log_char'] ?>"
                   class="width-200 " data-attr="log_char" name="BsLogCmp[log_char]"/>
            <label class="width-100">电话</label>
            <input id="log_char_phone" readonly="readonly" type="text" value="<?php echo $views['log_char_phone'] ?>"
                   class="width-200 " data-attr="log_char_phone" name="BsLogCmp[log_char_phone]"/>
            <label class="width-100">e-mail</label>
            <input id="log_char_mail" readonly="readonly" type="text" value="<?php echo $views['log_char_mail'] ?>"
                   class="width-200 " data-attr="log_char_mail" name="BsLogCmp[log_char_mail]"/>
            <div class="help-block">
            </div>
        </div>
        <div class="mt-20">
            <label class="width-100 ml-50">联系人</label>
            <input id="log_cont" readonly="readonly" type="text" value="<?php echo $views['log_cont'] ?>"
                   class="width-200 easyui-validatebox" data-options="required:true"
                   data-attr="log_cont" name="BsLogCmp[log_cont]"/>
            <label class="width-100">电话</label>
            <input id="log_cont_pho" readonly="readonly" type="text" value="<?php echo $views['log_cont_pho'] ?>"
                   class="width-200 easyui-validatebox" data-options="required:true"
                   data-attr="log_cont_pho" name="BsLogCmp[log_cont_pho]"/>
            <label class="width-100">e-mail</label>
            <input id="log_cont_mail" readonly="readonly" type="text" value="<?php echo $views['log_cont_mail'] ?>"
                   class="width-200 " data-attr="log_cont_mail" name="BsLogCmp[log_cont_mail]"/>
            <div class="help-block">
            </div>
        </div>
        <div class="mt-20">
            <label class="width-100 ml-50">经营范围</label>
            <input id="log_scope" readonly="readonly" type="text" value="<?php echo $views['log_scope'] ?>"
                   class="width-510 easyui-validatebox" data-options="required:true"
                   data-attr="log_scope" name="BsLogCmp[log_scope]"/>
            <label class="width-100" for="BsLogCmp-log_type">公司类型</label>
            <input type="text" readonly="readonly" id="log_type" value="<?php echo $views['log_type'] ?>"
                   name="BsLogCmp[log_type]">
        </div>
        <div class="mt-20">
            <label class="width-100 ml-50">公司网址</label>
            <input id="log_url" readonly="readonly" type="text" value="<?php echo $views['log_url'] ?>"
                   class="width-200" data-attr="log_url" name="BsLogCmp[log_url]"/>
            <label class="width-100">备注</label>
            <input id="remarks" readonly="readonly" type="text" value="<?php echo $views['remarks'] ?>"
                   class="width-510" data-attr="remarks" name="BsLogCmp[remarks]"/>
        </div>
    </div>
</div>
<script>
    //修改
    $("#update").on("click", function () {
            window.location.href = "<?=Url::to(['update'])?>?id=" + $("#log_cmp_id").val();
    })
    //删除
    $("#delete").on("click", function () {
            var selectId = $("#log_cmp_id").val();
            layer.confirm("确定要删除这条信息吗?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        data: {
                            "id": selectId,
                            "staff_id":$("#staff_id").val()
                        },
                        url: "<?=Url::to(['/warehouse/logistics-company/delete']) ?>",
                        success: function (msg) {
                            if (msg.flag === 1) {
                                //console.log(msg.result);
                                layer.alert("删除成功！", {
                                    icon: 1, end: function () {
                                        window.location.href = "<?=Url::to(['index'])?>";
                                    }
                                });
                            } else {
                                //console.log(msg.result);
                                layer.alert("删除失败！", {icon: 2});

                            }
                        },
                        error: function (msg) {
                            layer.alert("删除失败！", {icon: 2})
                        }
                    })
                },
                function () {
                    layer.closeAll();
                }
            )
    })
</script>
