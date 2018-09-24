<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

$this->params['homeLike'] = ['label' => '仓储物流管理', 'url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][] = ['label' => '库存预警人员列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '库存预警人员编辑页面'];
$this->title = '库存预警人员修改页面';

?>
<div class="content">
    <h1 class="head-first">
        <?= Html::encode($this->title) ?>
    </h1>
    <?php $form = \yii\widgets\ActiveForm::begin([
        'id' => 'update',
        //'enableAjaxValidation' => true,
        //'action' => ['/warehouse/set-inventory-warning/update?id=' . $model['staff_code']]
    ]) ?>



<!--    <h1 class="head-first">-->
<!--        库存预警/报废通知人员新增/编辑-->
<!--    </h1>-->
    <div class="mb-30">
        <h2 class="head-second" style="text-align: center">
            <p>预警人员基本信息 </p>
        </h2>
    </div>
    <div class="mb-10">
            <div class="inline-block">
                <label for="InvWarner-staff_code" class="width-50" style="width: 47px">工号</label>
                <input class="width-130 easyui-validatebox" ID="staff_code"
                        name="InvWarner[staff_code]"  value="<?=$model['staff_code'] ?>"   readonly="true">
                <label for="staff_name" class="width-100" style="margin-top: 20px">姓名</label>
                <input type="text" class="staff_name width-130"  readonly="true"  value="<?= $model['staff_name'] ?>"/>
            </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label for="InvWarner-staff_mobile" class="width-50" style="width: 47px">手机</label>
            <input type="text" class="staff_mobile width-130" readonly="true" value="<?= $model['staff_mobile'] ?>"/>
            <label for="staff_email" class="width-100">邮箱</label>
            <input type="text" class="staff_email width-130"  readonly="true" value="<?= $model['staff_email'] ?>"/>
            </div>
        </div>
    <div class="mb-30">
        <div class="inline-block">
            <label for="staff_email"  class="width-53">操作人</label>
            <input type="text" class="staff_email width-130"  readonly="true" value="<?= $Opper['staff_name']?>"/>
        </div>
    </div>
    <div class="mb-30">
        <h2 class="head-second" style="text-align: center">
            <p>预警人员商品信息 </p>
        </h2>
    </div>
    <div style="width:100%;" class="mb-50"style="    width: 60%;">
        <p class=" width-100 float-left">选择所负责的商品</p>
        <p class="float-right " style="line-height: 25px;">
<!--            <button type="button" onclick="vacc_add()" class="button-blue text-center">+ 添&nbsp;加</button>-->
            <button type="button" id="numsadd" class="button-blue text-center">+ 批量</button>
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
                    <td style="width:10%"><input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%"></td>
                    <td style="width:10%"><input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%"></td>
                    <td style="width:10%"><input type='text' class='width-120 easyui-validatebox validatebox-text validatebox-invalid'     name='partnoArr[0][BsInvWarn][part_no]'  ></td>
                    <td style="width:10%"><input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%"></td>
                    <td style="width:10%"><input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%"></td>
                    <td style="width:10%"><input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%"></td>
                    <td style="width:10%"><input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%"></td>
                    <td style="width:10%"><input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%"></td>
                    <td style="width:10%"><input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%"></td>
                    <td style="width:10%;">
                        <a onclick="vacc_onedel(this)">删除</a></td>
                </tr>
            <?php if(!empty($whinfo)){?>
                <?php foreach($whinfo as $key=>$val){?>
                    <tr class="vacc_body_tr">
                        <td style="width: 5%" ><input type='checkbox'   class='width-15 no-border text-center ck' name='selected' /></td>
                        <td style="width:0%;display: none"><input type="text" class="no-border  text-center" onfocus=this.blur();  name="partnoArr[<?=$key+1?>][BsInvWarnH][inv_id]" value="<?=$val['inv_id']?>"  ></td>
                        <td style="width:0%;display: none"><input type="text" class="no-border  text-center" onfocus=this.blur();  name="partnoArr[<?=$key+1?>][BsInvWarnH][wh_id]" value="<?=$val['wh_id']?>"  ></td>
                        <td style="width:10%"><input type="text" class="no-border  text-center" onfocus=this.blur();   value="<?=$val['wh_name']?>"  ></td>
                        <td style="width:10%" class="category_sname"><?=$val['category_sname']?></td>
                        <td style="width:10%"><input type="text"  class="no-border  text-center" onfocus=this.blur();  name="partnoArr[<?=$key+1?>][BsInvWarn][part_no]" value="<?=$val['part_no']?>" ></td>
                        <td style="width:10%" class="pdt_name"><?=$val['pdt_name']?></td>
                        <td style="width:10%" class="BRAND_NAME_CN"><?=$val['BRAND_NAME_CN']?></td>
                        <td style="width:10%" class="pdt_model"><?=$val['pdt_model']?></td>
                        <td style="width:10%" class="up_nums"><?=$val['up_nums']?></td>
                        <td style="width:10%" class="invt_num"><?=$val['invt_num']?></td>
                        <td style="width:10%" class="down_nums"><?=$val['down_nums']?></td>
                        <td style="width: 10%"><a onclick='vacc_onedel(this)'>删除</a></td>
                    </tr>
                <?php }?>
            <?php }?>
            </tbody>
        </table>
    </div>
    <div >
        <label for="" class="width-100;text-align:left" >备注</label>
        <textarea style="width: 70%; height: 80px" name="InvWarner[remarks]" maxlength="200" placeholder="最多200个字符" value="<?=$whinfo[0]["remarks"]?>"><?= $model["remarks"]?></textarea>
    </div>
    <input style="display: none" value="<?=$model['so_type']?>" name="so_type"/>
    <input style="display: none" value="" id="is_apply" name="is_apply"/>
    <div class="space-20"></div>
    <div class="mb-20 text-center">

        <?php if($model['so_type']!="40"){?>
            <button class="button-blue-big" type="submit" id="create-warehouse-submit">保存</button>
        <?php }?>
        <button class="button-blue-big ml-20" type="save" id="apply_form">提交</button>
        <button class="button-white-big ml-20 close" type="button" onclick="window.location.href='<?=Url::to(['index'])?>'">取消</button>
    </div>
    <?php $form->end(); ?>
</div>

<script>

    $(function () {
            ajaxSubmitForm($("#update"));
        //全选
        $(':checkbox[name=all]').click(function () {
            if(this.checked){
                $(':checkbox').attr('checked','checked');
            }else {
                $(':checkbox').removeAttr('checked');
            }
        });
    })
//提交按钮事件
$("#apply_form").click(function () {
   // var isApply=1;
    //var so_type="<?=$Opper['so_type']?>"
    $("#is_apply").val("1");
    $("#update").attr('action', '<?= \yii\helpers\Url::to(['/warehouse/set-inventory-warning/create'])?>');
})

    //添加商品料号
//    var i = 100;
//    function vacc_add(){
//        $("#vacc_body").append(
//            '<tr class="vacc_body_tr">' +
//            '<td style="width: 5%" >' +
//            "<input type='checkbox'   class='width-15 no-border text-center ck' name='selected' />"+
//            "</td>" +
//            '<td style="width:10%;">' +
//            "<input style='text-align: center' type='text' class='width-200 easyui-validatebox validatebox-text validatebox-invalid' data-options='required:true' placeholder='请点击输入仓库' onblur='wh_name(this)'  name='partnoArr["+i+"][BsInvWarn][wh_code]'>" +
//            "</td>" +
//            '<td  style="width:10%">' +
//            "<input  style='text-align: center' type='text' class='width-200 easyui-validatebox validatebox-text validatebox-invalid' data-options='required:true' placeholder='请点击输入料号' onblur='part_no(this)'  name='partnoArr["+i+"][BsInvWarn][part_no]' >" +
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
    //批量删除商品料号
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
    //单个删除
    function vacc_onedel(obj){
        var tr= $("#vacc_body tr").length;
        $(obj).parents("tr").remove();
    }
    //根据仓库查询商品信息
//    function wh_name(obj) {
//        //  var type = $(obj).parents("tr").find("input");
//        var code = $(obj).val();
//        if(!code){
//            return
//        }
//        get_whname(code);
//    }
//    var whname="";
//    function get_whname(code ){
//        $.ajax({
//            type: 'GET',
//            dataType: 'json',
//            data: {"code": code},
//
//            success: function (data) {
//                if (!data) {
//                    return layer.alert("未找到该仓库,请重新输入！", {icon: 2, time: 5000});
//                }else {
//                    whname=data.wh_code;
//                    console.log(whname);
//                }
//            }
//        })
//    }
//    //根据仓库及料号查询商品信息
//    function  part_no(obj) {
//        var type = $(obj).parents("tr").find("input");
//        var code = $(obj).val();
//        // console.log(code);
//        if(type.eq(1).val()==""){
//            return layer.alert("请先输入仓库！", {icon: 2, time: 5000});
//        }
//        if (!code) {
//            return;
//        }
//        get_partno_info(type,code,whname);
//    }
//    function  get_partno_info(type,code,whname) {
//        $.ajax({
//            type: 'GET',
//            dataType: 'json',
//            data: {"code": code,"whname":whname},

//            success: function (data) {
//                if (!data) {
//                    //type.eq(2).val(data.category_sname);
//                    return layer.alert("未找到该料号！", {icon: 2, time: 5000});
//
//                }
//                //console.log(data);
//                type.eq(3).val(data.category_sname);
//                type.eq(4).val(data.pdt_name);
//                type.eq(5).val(data.BRAND_NAME_CN);
//                type.eq(6).val(data.pdt_model);
//                type.eq(7).val(data.up_nums);
//                type.eq(8).val(data.down_nums);
//            }
//        });
//    }

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
</script>
