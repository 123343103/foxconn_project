<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<div class="content">
    <?php $form = ActiveForm::begin([
        'action' => ['create'],
//        'method' => 'get',
        'id'=>'add-form'
    ]); ?>
    <div class="mb-30">
        <h2 class="head-second" style="text-align: left">
            <p>预警人员基本信息 </p>
        </h2>
    </div>
<!--    data-url="--><?//= \yii\helpers\Url::to(['/hr/staff/get-staff-info']) ?><!--"-->
<!--    onkeydown="javascript:if(event.keyCode==13) Checkstaff(this);-->
<!--    onblur="Checkstaff(this)"-->
    <div class="mb-10">
        <div class="inline-block  ">
            <label for="InvWarner-staff_code" class="width-44" style="width: 47px">工号</label>
            <input class="width-130 text-center easyui-validatebox validatebox-text validatebox-invalid"  id="staff_code"  data-options="required:true,validType:'length[0,30]'"
                   name="InvWarner[staff_code]" data-options="required:'true'" onkeydown="javascript:if(event.keyCode==13) return false;"  onkeyup="javascript:if(event.keyCode==13) Checkstaff(this);">
            <label for="staff_name" class="width-100" style="margin-top: 20px">姓名</label>
            <input type="text" class="staff_name width-130"  readonly="true"  id="staff_name" style="text-align: center"/>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block  ">
            <label for="InvWarner-staff_mobile" class="width-44" style="width: 47px">手机</label>
            <input type="text" class="staff_mobile width-130" readonly="true"  id="staff_mobile" style="text-align: center"/>
            <label for="staff_email" class="width-100">邮箱</label>
            <input type="text" class="staff_email width-130"  readonly="true" id="staff_email" style="text-align: center"/>
        </div>
    </div>
    <div class="mb-30">
        <div class="inline-block  ">
            <label for="staff_email"  class="width-53">操作人</label>
            <input type="text" style="text-align: center" class="staff_email width-130"  readonly="true" id="staff_email" value="<?= $opper['staff_name']?>"/>
         </div>
    </div>
    <div class="mb-30">
        <h2 class="head-second" style="text-align: left">
            <p>预警人员商品信息 </p>
        </h2>
    <div style="width:100%;" class="mb-50"style="    width: 60%;">
        <p class=" width-100 float-left">选择所负责的商品</p>
        <p class="float-right " style="line-height: 25px;">
<!--            href="--><?//= Url::to(['/warehouse/set-inventory-warning/numadd'])?><!--"-->
<!--            <button type="button" onclick="vacc_add()" class="button-blue text-center">+ 添&nbsp;加</button>-->
            <button type="button" id="numsadd" class="button-blue text-center fancybox.ajax" ">+ 批量</button>
            <button type="button" onclick="vacc_del(this)" class="button-blue text-center">- 刪除</button>
        </p>
        <div class="space-10 clear"></div>
        <table class="table-small" style="width: 100%">
            <tbody id="vacc_body">
            <tr class="vacc_body_tr">
                <!--                <th>序号</th>-->
                <th class="unselect" ><input type="checkbox" onclick="selectAll()"  id="controlAll" name="all"/></th>
                <th>仓库</th>
                <th>商品类别</th>
                <th>料号</th>
                <th>商品名称</th>
                <th>品牌</th>
                <th>规格型号</th>
                <th>库存上限</th>
                <th>现有库存</th>
                <th>库存下限</th>
                <th>操作</th>
            </tr>
            <tr class="vacc_body_tr" style="display: none">
                <td style="width: 5%" ></td>
                <td style="width:10%"><input type='text' class='width-150 easyui-validatebox validatebox-text validatebox-invalid'  placeholder='请点击输入仓库'   name='partnoArr[0][BsInvWarn][wh_code]' ></td>
                <td style="width:10%"><input type='text' class='width-150 easyui-validatebox validatebox-text validatebox-invalid'  placeholder='请点击输入料号'   name='partnoArr[0][BsInvWarn][part_no]' ></td>
                <td style="width:10%"><input type='text' class='width-120 easyui-validatebox validatebox-text validatebox-invalid'     name='partnoArr[0][BsInvWarn][wh_code]' ></td>
                <td style="width:10%"><input type='text' class='width-120 easyui-validatebox validatebox-text validatebox-invalid'   name='partnoArr[0][BsInvWarn][part_no]' ></td>
                <td style="width:10%"><input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%"></td>
                <td style="width:10%"><input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%"></td>
                <td style="width:10%"><input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%"></td>
                <td style="width:10%"><input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%"></td>
                <td style="width:10%"><input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%"></td>
                <td style="width:10%;">
                    <a onclick="vacc_onedel(this)">删除</a></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div >
        <label for="" class="width-100;text-align:left" >备注</label>
        <textarea style="width: 70%; height: 80px" name="InvWarner[remarks]" maxlength="200" placeholder="最多200个字符"></textarea>
        <input style="display: none" value="" id="is_apply" name="is_apply" />
    </div>
    <div class="space-20"></div>
    <div class="mb-20 text-center">
        <button class="button-blue-big" type="submit" id="create-warehouse-submit" onkeydown="javascript:if(event.keyCode==13) return false;">保存</button>
        <button class="button-blue-big ml-20" type="save" id="apply_form" onkeydown="javascript:if(event.keyCode==13) return false;">提交</button>
        <button class="button-white-big ml-20 close" type="button" onclick="window.location.href='<?=Url::to(['index'])?>'">取消</button>
    </div>
</div>
    </div>
<?php $form->end(); ?>
<script>
    $(document).ready(function() {
        ajaxSubmitForm($("#add-form"));

        $("#apply_form").click(function () {
            //document.getElementById("is_apply").value="1";
            $("#is_apply").val("1");
            $("#add-form").attr('action', '<?= \yii\helpers\Url::to(['/warehouse/set-inventory-warning/create'])?>');
        })
    });

    $(function () {
        //全选
        $(':checkbox[name=all]').click(function () {
            if(this.checked){
                $(':checkbox').attr('checked','checked');
            }else {
                $(':checkbox').removeAttr('checked');
            }
        });
    })
//    //批量添加商品料号
//    var i = 100;
//    function vacc_add(){
//        $("#vacc_body").append(
//            '<tr class="vacc_body_tr">' +
//            '<td style="width: 5%" >' +
//            "<input style='text-align: center' type='checkbox'   class='width-15 no-border text-center ck' name='selected' />"+
//            "</td>" +
//            '<td style="width:10%">' +
//            "<input style='text-align: center' type='text' class='width-150 easyui-validatebox validatebox-text validatebox-invalid' data-options='required:true' placeholder='请点击输入仓库' onblur='wh_name(this)'  name='partnoArr["+i+"][BsInvWarn][wh_code]' >" +
//            "</td>" +
//            '<td  style="width:10%">' +
//            "<input type='text' class='width-150 easyui-validatebox validatebox-text validatebox-invalid' data-options='required:true' placeholder='请点击输入料号' onblur='part_no(this)'  name='partnoArr["+i+"][BsInvWarn][part_no]' >" +
//            "</td>" +
//            '<td style="width:10%">' +
//            '<input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%">' +
//            '</td >' +
//            '<td style="width:10%">' +
//            '<input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%">' +
//            '</td>' +
//            '<td style="width:10%">' +
//            '<input type="text" class=" no-border text-center" onfocus=this.blur(); style="width: 100%">' +
//            '</td>' +
//            '<td style="width:10%">' +
//            '<input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%">' +
//            '</td>' +
//            '<td style="width:10%">' +
//            '<input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%" >' +
//            '</td>' +
//            '<td style="width: 10%">' +
//            '<input type="text" class="no-border text-center" onfocus=this.blur();  style="width: 100%" >' +
//            '</td>' +
//            '</tr>'
//        );
////        $.parser.parse($("#vacc_body").find("tr:last"));//easyui解析
//        i++;
//    }
    //删除商品料号
    function vacc_del(obj) {
        var s=$(".ck:checked");
        if(s.length<1){
            return layer.alert("请选择需要删除的信息！", {icon: 2, time: 5000});
        }else {
            s.each(function(){
                   s.closest('tr').remove();
            });
        };
    };
    function vacc_onedel(obj){
        var tr= $("#vacc_body tr").length;
         $(obj).parents("tr").remove();
    }
    //全选
    function selectAll(){
        var checklist = document.getElementsByName ("selected");
        if(document.getElementById("controlAll").checked)
        {
            for(var i=0;i<checklist.length;i++)
            {
                checklist[i].checked = 1;
            }
        }else{
            for(var j=0;j<checklist.length;j++)
            {
                checklist[j].checked = 0;
            }
        }
    }
    $("#numsadd").on("click",function () {
        $('#numsadd').fancybox({
            autoSize: true,
            fitToView: false,
            height: 500,
            width: 800,
            closeClick: true,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['numadd'])?>?ProductInfoSearch[wh_id]="+1
        });
    })
    function  Checkstaff(obj) {
        var code=$(obj).val();
        if(!code){
            return;
        }
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"code": code},
            url: "<?=Url::to(['/warehouse/set-inventory-warning/get-check-info']) ?>",
            success: function (data) {
                if (data.length>0) {
                    $(obj).val("");
                    return layer.alert("该预警人员已存在,   请重新输入！", {icon: 2, time: 5000});
                    //$(obj).focus();
                }else {
                    get_hr_info(code);
                }
            }
        });
    }
    function get_hr_info(code) {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"code": code},
            url: "<?=Url::to(['/hr/staff/get-staff-info']); ?>",
            success: function (data) {
                if (data) {
                   // console.log(data);
                    document.getElementById('staff_name').value = data.staff_name;
                    document.getElementById('staff_mobile').value = data.staff_mobile;
                    document.getElementById('staff_email').value = data.staff_email;
                }else {
                    return layer.alert("未找到该员工,   请重新输入！", {icon: 2, time: 5000});
                }

            }
        });

    }
</script>
