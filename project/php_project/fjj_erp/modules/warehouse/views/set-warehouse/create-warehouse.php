<?php
/**
 * Created by PhpStorm.
 * User: F3860959
 * Date: 2017/6/8
 * Time: 上午 09:58
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use app\assets\MultiSelectAsset;
$this->title = '新增仓库';
$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '仓库设置', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;

MultiSelectAsset::register($this);
?>
<style>
    .fancybox-wrap {
        top: 0px !important;
        left: 0px !important;
    }
    .width-850{
        width: 850px;
    }
    .mt10-ml30{
        margin-left: 30px;
        margin-top: 10px;
    }
    .width-70{
      width: 70px;
    }
    .width-180{
        width: 180px;
    }
    .width-155{
        width: 155px;
    }
    .width-130{
        width: 130px;
    }
    .text{
        width: 555px;
        margin-left: 74px;
        margin-top: 2px;
    }
    .widthf-69{
        width: 69px;
        float: left;
    }
    .width-555{
        margin-left: 5px;
        width: 555px;
    }
    .userset{
        color: blue;
        font-size: 15px;
        width: 113px
    }
    .width-700{
        width: 700px;
    }
    .width-150{
        width: 150px;
    }
    .width-20{
        width: 20px;
    }
    .width-45{
        width: 45px;
    }
    .width-60{
        width: 60px;
    }
    .tabletext{
        text-align: center;
        width: 100%;
    }
    .adduser{
        margin-right: 100px;
        margin-top: 10px;
    }
    .width-row{
        width: 100%;
    }
    .height-10{
        height: 10px;
    }
    .height-30{
        height: 30px;
    }
    .width-50{
        width: 50px;
    }
    .width-188{
        width: 188px;
    }
    .ml-40{
        margin-left: 40px;
    }
</style>
    <div class="create-plan content">
        <h1 class="head-first">新增仓库</h1>
        <?php $form = \yii\widgets\ActiveForm::begin([
            'id' => 'create-warehouse',
            'enableAjaxValidation' => true,
            'action' => ['/warehouse/set-warehouse/create-warehouse']
        ]) ?>
        <h1 class="head-second" style="text-align:left;">仓库基本信息</h1>
        <div class="space-10"></div>
        <div class="mt10-ml30">
            <label class="label-align width-70" ><span class="red">*</span>仓库代码：</label>
            <input type="text" class="wh_code easyui-validatebox value-align width-180" maxlength="20"
                   data-options="required:'true',validType:'cknumoren'"
                   data-url="<?= \yii\helpers\Url::to(['/warehouse/set-warehouse/get-warehouse-info']) ?>"
                   name="BsWh[wh_code]"/>
            <label class="label-align width-180"><span class="red">*</span>仓库名称：</label>
            <input  type="text" class="wh_name  easyui-validatebox value-align width-188" maxlength="20"
                    data-options="required:'true'"
                    name="BsWh[wh_name]"/>
        </div>
        <div class="mt10-ml30">
            <label class="label-align width-70" ><span class="red">*</span>仓库性质：</label>
            <select name="BsWh[wh_nature]" class="easyui-validatebox validateboxs value-align width-180"
                    data-options="required:true">
                <option value="">请选择</option>
                <?php foreach($downList['wh_nature'] as $key => $val){ ?>
                      <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue']?></option>
                <?php  } ?>
            </select>
            <label class="label-align width-180"><span class="red">*</span>仓库属性：</label>
            <select name="BsWh[wh_attr]" class="easyui-validatebox validateboxs value-align width-188" data-options="required:true">
                <option value="">请选择</option>
                <?php foreach($downList['wh_attr'] as $key => $val){ ?>
                    <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue']?></option>
                <?php  } ?>
            </select>
        </div>
        <div class="mt10-ml30">
            <label class="label-align width-70"><span class="red">*</span>仓库类别：</label>
            <select name="BsWh[wh_type]" class="easyui-validatebox validateboxs value-align width-180" data-options="required:true">
                <option value="">请选择</option>
                <?php foreach($downList['wh_type'] as $key => $val){ ?>
                    <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue']?></option>
                <?php  } ?>
            </select>
            <label class="label-align width-180"><span class="red">*</span>仓库级别：</label>
            <select name="BsWh[wh_lev]" class=" easyui-validatebox validateboxs value-align width-188" data-options="required:true">
                <option value="">请选择</option>
                <?php foreach($downList['wh_lev'] as $key => $val){ ?>
                    <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue']?></option>
                <?php  } ?>
            </select>
        </div>
        <div class="mt10-ml30">
            <label class="label-align" style="width: 72px">是否报废仓：</label>
            <select name="BsWh[wh_yn]" class="easyui-validatebox validateboxs value-align width-180" data-options="required:true">
                <option value="N">否</option>
                <option value="Y">是</option>
            </select>
            <label class="label-align width-180">是否自提点：</label>
            <select name="BsWh[yn_deliv]" class=" easyui-validatebox validateboxs value-align width-188"
                    data-options="required:true">
                <option value="1">是</option>
                <option value="0">否</option>
            </select>
        </div>
        <div class="mt10-ml30">
            <label class="label-align width-70"><span class="red">*</span>法人：</label>
            <select name="BsWh[people]" class="easyui-validatebox validateboxs value-align width-180" data-options="required:true">
                <option value="" style="">请选择</option>
                <?php foreach($downList['people'] as $key => $val){ ?>
                    <option style="width: 160px" value="<?= $val['company_name'] ?>"><?= $val['company_name']?></option>
                <?php  } ?>
            </select>
            <label class="label-align width-180">创业公司：</label>
            <select name="BsWh[company]" class=" easyui-validatebox validateboxs value-align width-188" data-options="required:true">
                <option value="0">请选择</option>
            </select>
        </div>
        <div class="mt10-ml30">
            <label class="label-align width-70"><label class="red">*</label>仓库地址：</label>
            <select class=" disName  easyui-validatebox value-align width-130" data-options="required:'true'" id="disName_1">
                <option value="">请选择...</option>
                <?php foreach ($firmDisName as $key => $val) { ?>
                    <option value="<?= $key ?>"><?= $val ?></option>
                <?php } ?>
            </select>
            <select class="disName easyui-validatebox value-align width-130" data-options="required:'true'" id="disName_2">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll)) { ?>
                    <?php foreach ($districtAll['twoLevel'] as $val) { ?>
                        <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class=" disName easyui-validatebox value-align width-155" data-options="required:'true'" id="disName_3">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll)) { ?>
                    <?php foreach ($districtAll['threeLevel'] as $val) { ?>
                        <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class="disName easyui-validatebox value-align width-130" data-options="required:'true'" id="disName_4"
                    name="BsWh[district_id]">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll)) { ?>
                    <?php foreach ($districtAll['fourLevel'] as $val) { ?>
                        <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <div class="ax_文本框_单行_" id="u676">
                <input class="value-align text easyui-validatebox"  data-options="required:'true'" name="BsWh[wh_addr]" id="u676_input" type="text" placeholder="请输入详细地址" maxlength="20" onchange="this.value=this.value.substring(0, 20)">
            </div>
        </div>
        <div class="mt10-ml30">
            <label class="label-align widthf-69">备注：</label>
            <textarea class="value-align width-555" type="text" rows="3" data-options="required:true" maxlength="200" onchange="this.value=this.value.substring(0, 200)"
                      name="BsWh[remarks]"></textarea>
        </div>
        <div class="mt10-ml30">
            <label class="label-align width-70">状态：</label>
            <select name="BsWh[wh_state]" class="easyui-validatebox validateboxs value-align width-180" data-options="required:true">
                <option value="Y">启用</option>
                <option value="N">禁用</option>
            </select>
        </div>
        <div style="height: 20px;"></div>
        <h1 class="head-second" style="text-align:left;">仓库管理员设置</h1>
        <div style="margin-left: 35px">
                <table id="User" class="table width-700">
                   <thead>
                   <tr class="height-30">
                       <th class="width-20">序号</th>
                       <th class="width-45"><span style="color: red">*</span>工号</th>
                       <th class="width-45">姓名</th>
                       <th class="width-50"><span style="color: red">*</span>电话</th>
                       <th class="width-150">邮箱</th>
                       <th class="width-20">操作</th>
                   </tr>
                   </thead>
                    <tbody id="user">
                    <tr class="height-30">
                        <td>1</td>
                        <td><input id="_users" name="sta[0][WhAdm][emp_no]" type="text"
                                   data-options="required:'true'"
                                   class="tabletext easyui-validatebox" ></td>
                        <td><span id="_name" class="tabletext"></span></td>
                        <td><input id="_phone" name="sta[0][WhAdm][adm_phone]"
                                   data-options="required:'true',validType:'tel_mobile_c'"
                                   type="text" value="" class="tabletext easyui-validatebox"></td>
                        <td><input id="_email" data-options="validType: 'email'" name="sta[0][WhAdm][adm_email]"
                                   class="tabletext easyui-validatebox"
                                   data-option="validType:'email'"
                                   type="text" value="" class="tabletext"></td>
                        <td><a class="_adddel" onclick="deleteRow(this)"></a></td>
                    </tr>
                    </tbody>
                </table>
        </div>
        <div class="adduser">
        <p class="text-right mb-5" style="margin-right: 154px;">
            <a class="icon-plus" onclick="add_lites()">添加管理员</a>
        </p>
        </div>
        <div class="width-row height-10"></div>
        <div class="mb-20 text-center" style="margin-top: 10px">
            <button class="button-blue-big" type="submit" id="create-warehouse-submit">确定</button>
            <button class="button-white-big ml-40" type="button" id="close">返回</button>
        </div>
        <?php $form->end(); ?>
        <div class="width-row height-30"></div>
</div>




<script>
    //新增添加管理员行
       function add_lites(){
           $("._adddel").text("删除");
        var i=$("#User").find("tr").length-1;
        var obj=$("#user").append(
            '<tr id="user">'+
            '<td>' +(i+1)+'</td>'+
            '<td>' + '<input name="sta['+i+'][WhAdm][emp_no]" id="_users" class="tabletext easyui-validatebox" data-options="required:\'true\'" type="text" >' + '</td>'+
            '<td>' + '<span id="_name"></span>' + '</td>'+
            '<td>' + '<input type="text" id="_phone" name="sta['+i+'][WhAdm][adm_phone]" ' +
            'class="tabletext easyui-validatebox" ' +
            'data-options="required:\'true\',validType:\'tel_mobile_c\'">' + '</td>'+
            '<td>' + '<input type="text" id="_email" name="sta['+i+'][WhAdm][adm_email]" ' +
            'class="tabletext easyui-validatebox" ' +
            'data-options="validType:\'email\'">' + '</td>'+
            '<td><a onclick="deleteRow(this)">删除</a></td>' +
            '</tr>'
        );
        $.parser.parse(obj);

    }
    //删除管理员行
    function deleteRow(obj)
    {
        var tr= $("#user tr").length;
        if(tr>1){
            $(obj).parents("tr").remove();
        }
//        console.log(123);return false;
//        var i=user.parentNode.rowIndex;
//        document.getElementById('user').deleteRow(i);
        var a = $("#user tr").length;
        for(var i=0;i<a;i++){
            if(a==1)
            {
                $('#user').find('tr').eq(0).find('td').eq(5).find('a').addClass("_adddel");
                $('#user').find('tr').eq(0).find('td').eq(5).find('a').text("");
            }
            $('#user').find('tr').eq(i).find('td:first').text(i+1);
        }
    }

    $(function () {
        $(document).ready(function() {
            ajaxSubmitForm($("#create-warehouse"));
        })
    });

    /**
     *地址联动查询
     */
    $('.disName').on("change", function () {
        var $select = $(this);
        var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
        getNextDistrict($select, $url, "select");
    });
    /*
     *取消按钮（关闭弹窗）事件
     */
    $("#close").click(function () {
        location.href="<?=Url::to('index')?>";
    });

    //根据工号获取管理员基本信息
    $(document).on("change","#_users",function () {
        var arr=new Array();
        $staff=$(this);
        $staffcode=$(this).val();
//        alert($staffcode);
        var url = "<?= Url::to(['/purchase/purchase-apply/get-staff-info'])?>?code="+$staffcode;
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"code":$staffcode},
            url: url,
            beforeSend:function () {
                $("#User").find("tr").each(function () {
                    var par=$(this).find("._staffss").val();
                    arr.push(par);
                });
            },
            success: function (data) {
                if(data!=false){
                    if(arr.indexOf($staffcode)<0) {
                        $staff.parent().parent().find("#_name").text(data.staff_name);
                        $staff.parent().parent().find("#_users").addClass("_staffss");
                        $staff.parent().parent().find("#_phone").val(data.staff_mobile);
                        $staff.parent().parent().find("#_email").val(data.staff_email);
                    }else {
                        layer.alert("工号"+$staffcode+"已经添加过了,请重新输入工号",{icon:2});
                    }
                }else {
                    layer.alert("没有查到此工号",{icon: 2});
                }
            }
        })
    });
</script>
