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
$this->params['homeLike']=['label'=>'客户关系系统'];
$this->params['breadcrumbs'][]=['label'=>'新增潜在客户'];
?>
<?php $form=ActiveForm::begin([
        "id"=>"visit-create-form"
]);?>
<div class="content">
    <h2 class="head-first">
        新增拜访
    </h2>
    <h2 class="head-second">
        客户信息
        <a id="select-customer" class="ml-10" href="javascript:void(0)">客户选择</a>
    </h2>
    <div class="mb-20">
        <input id="cust_id" name="cust_id" type="hidden" value="<?=$cust_info["cust_id"];?>">
        <label class="width-100" for="">公司名称</label>
        <input id="cust_sname"  class="width-120"  type="text" value="<?=$cust_info['cust_sname'];?>" disabled>
        <label class="width-100" for="">公司简称</label>
        <input id="cust_shortname" class="width-120" type="text" value="<?=$cust_info['cust_shortname'];?>" disabled>
        <label class="width-100" for="">公司电话</label>
        <input  id="cust_tel1" class="width-120" type="text" value="<?=$cust_info['cust_tel1'];?>" disabled>
    </div>


    <div class="mb-20">
        <label class="width-100" for="">详细地址</label>
        <select class="width-100 disName" disabled>
            <option value="">选择</option>
            <?php if($cust_info['cust_district_2']){ ?>
            <?php foreach($district[oneLevel] as $k=>$v){ ?>
                <option <?=$v[district_id]==$district[oneLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
            <?php }}else{ ?>
                <option value="1">中国</option>
            <?php } ?>
        </select>
        <select class="width-100 disName"  id="" disabled>
            <option value="">选择</option>
            <?php foreach($district[twoLevel] as $k=>$v){ ?>
                <option <?=$v[district_id]==$district[twoLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
            <?php } ?>
        </select>
        <select class="width-100 disName" id="" disabled>
            <option value="">选择</option>
            <?php foreach($district[threeLevel] as $k=>$v){ ?>
                <option <?=$v[district_id]==$district[threeLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
            <?php } ?>
        </select>
        <select class="width-100 disName" name="cust_district_2" id="" disabled>
            <option value="">选择</option>
            <?php foreach($district[fourLevel] as $k=>$v){ ?>
                <option <?=$v[district_id]==$district[fourLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
            <?php } ?>
        </select>
        <input name="cust_adress" class="width-350 ml-40" type="text" value="<?=$data['customerData']['cust_adress'];?>" disabled>
    </div>


    <div class="mb-20">
        <input class="width-120"  type="hidden" value="<?=\Yii::$app->user->identity->staff->staff_id;?>">
        <label class="width-100" for="">联系人</label>
        <input id="cust_contacts"  class="width-120"  type="text" value="<?=$cust_info["contacts"];?>" disabled>
        <label class="width-100" for="">职位</label>
        <input id="cust_position"  class="width-120" type="text" value="<?=$cust_info['position'];?>" disabled>
        <label class="width-100" for="">手机号码</label>
        <input id="cust_tel2"  class="width-120" type="text" value="<?=$cust_info['cust_tel2'];?>" disabled>
        <label class="width-100" for="">邮箱</label>
        <input id="cust_email"  class="width-120"  type="text" value="<?=$cust_info['cust_email'];?>" disabled>
    </div>


    <div class="mb-20">
        <label class="width-100" for="">是否会员</label>
        <select class="width-120" id="cust_ismember" disabled>
            <option <?=$cust_info['cust_ismember']==1?"selected":""?> value="1">是</option>
            <option <?=$cust_info['cust_ismember']==0?"selected":""?> value="0">否</option>
        </select>
        <label class="width-100" for="">注册时间</label>
        <input id="cust_regdate" class="width-120 select-date" type="text" value="<?=$cust_info['cust_regdate'];?>" disabled>
        <label class="width-100" for="">会员类别</label>
        <select class="width-120" id="member_type" disabled>
            <option value="">选择</option>
            <?php foreach($downList[member_type] as $k=>$v){ ?>
                <option <?=$v[bsp_id]==$cust_info[member_type]?"selected":""?> value="<?=$v[bsp_id];?>"><?=$v[bsp_svalue];?></option>
            <?php } ?>
        </select>
        <label class="width-100" for="">会员名</label>
        <input id="member_name" class="width-120" type="text" value="<?=$cust_info['member_name'];?>" disabled>
    </div>



    <div class="mb-20">
        <label class="width-100" for="">经营模式</label>
        <select class="width-120" id="" disabled>
            <option value="">选择</option>
            <?php foreach($downList[cust_businesstype] as $k=>$v){ ?>
                <option <?=$v[bsp_id]==$cust_info['cust_businesstype']?"selected":""?> value="<?=$v[bsp_id];?>"><?=$v[bsp_svalue];?></option>
            <?php } ?>
        </select>
        <label class="width-100" for="">经营范围</label>
        <input class="width-120" type="text" name="member_businessarea" value="<?=$cust_info['member_businessarea'];?>" disabled>
        <label class="width-100" for="">需求类别</label>
        <input class="width-120" type="text" value="<?=$cust_info['member_reqdesription'];?>" disabled>
    </div>

    <div class="mb-20">
        <label class="width-100" for="">需求类目</label>
        <select class="width-120" id="" disabled>
            <option value="">选择</option>
            <?php foreach($downList[productType] as $k=>$v){ ?>
                <option <?=$v[category_id]==$cust_info[member_reqitemclass]?"selected":""?> value="<?=$v[category_id];?>"><?=$v[category_sname];?></option>
            <?php } ?>
        </select>
        <label class="width-100" for="">潜在需求</label>
        <select class="width-120" id="" disabled>
            <option value="">选择</option>
            <?php foreach($downList[member_reqflag] as $k=>$v){ ?>
                <option <?=$v['bsp_id']==$cust_info['member_reqflag']?"selected":""?> value="<?=$v[bsp_id];?>"><?=$v[bsp_svalue];?></option>
            <?php } ?>
        </select>
        <label class="width-100" for="">需求特征</label>
        <input id="member_reqdesription" class="width-120" type="text" value="<?=$cust_info['member_reqcharacter'];?>" disabled>
    </div>

    <p style="text-align: right;"><a id="edit-customer" class="mr-10" href="javascript:void(0)">编辑客户资料</a></p>
    <h2 class="head-second mt-20">
        拜访信息
    </h2>
    <div class="mb-20">
        <input id="visit_cust_id" type="hidden" name="CrmVisitRecord[cust_id]">
        <input id="visit_cust_name" type="hidden" name="CrmVisitRecord[cust_name]">
        <input type="hidden" name="CrmVisitRecordChild[sil_staff_code]" value="<?=\Yii::$app->user->identity->staff->staff_code;?>">
        <label class="width-100" for="">拜访人</label>
        <input type="text" class="width-150" value="<?=\Yii::$app->user->identity->staff->staff_name;?>" disabled>
        <input type="hidden" name="CrmVisitRecordChild[sil_staff_code]" value="<?=\Yii::$app->user->identity->staff->staff_name;?>">
        <label class="width-100" for="">拜访日期</label>
        <input type="text" name="CrmVisitRecordChild[sil_date]" class="width-150 select-date easyui-validatebox"  data-options="required:true">
        <label class="width-100" for="">拜访类型</label>
        <select name="CrmVisitRecordChild[sil_type]" id="" class="width-150 easyui-validatebox"  data-options="required:true">
            <option value="">选择</option>
            <?php foreach($downList['visit_type'] as $v){?>
                <option value="<?=$v[bsp_id];?>"><?=$v[bsp_svalue];?></option>
            <?php } ?>
        </select>
        <a id="add-remind" href="javascript:void(0)">新增提醒消息</a>
    </div>
    <div class="mb-20">
        <label class="width-100" for="">开始时间</label>
        <input id="startDate" name="arriveDate" type="text" class="width-150 select-date-time easyui-validatebox" data-target="#endDate" data-type="le" data-options="required:true,validType:'timeCompare'">
        <label class="width-100" for="">结束时间</label>
        <input id="endDate" type="text" name="leaveDate" class="width-150 select-date-time easyui-validatebox" data-target="#startDate" data-type="ge" data-options="required:true,validType:'timeCompare'">
        <label class="width-100" for="">拜访用时</label>
        <input type="text" class="width-30 text-center day" id="day-1" name="day-1"/> 天
        <input type="text" class="width-30 text-center hours" id="hours-1" name="hours-1"/> 时
        <input type="text" class="width-30 text-center minutes" id="minutes-1" name="minutes-1"/> 分
    </div>
    <div class="mb-20">
        <label class="width-100" for="">拜访內容</label>
        <textarea style="vertical-align: top;" name="CrmVisitRecordChild[execute_project]" id="" cols="80" rows="5"></textarea>
    </div>
    <div class="mb-20">
        <label style="vertical-align: top;" class="width-100" for="">客户反馈或<br/>拜访总结</label>
        <textarea name="CrmVisitRecordChild[sil_interview_conclus]" id="" cols="80" rows="5"></textarea>
    </div>
    <div class="mb-20">
        <label style="vertical-align: top;" class="width-100" for="">备注</label>
        <textarea name="" id="" cols="80" rows="5"></textarea>
    </div>
    <div class="mb-20 text-center">
        <button type="submit" class="button-blue">确定</button>
        <button type="reset" class="button-white">清空</button>
        <button type="button" onclick="window.location.href='<?=Url::to(['index'])?>'" class="button-white">返回</button>
    </div>

</div>
<?php $form->end();?>


<script>
    $(function(){
        var customer;
        ajaxSubmitForm($("#visit-create-form"),function(){
            if($("#cust_id").val()==""){
                layer.alert("请选择客户",{icon:2});
                return false;
            }
            return true;
        });

        //选择客户
        $("#select-customer").click(function(){
            $.fancybox({
                href:"<?=\yii\helpers\Url::to(['select-customer'])?>",
                width:850,
                height:600,
                padding:0,
                autoSize: false,
                scrolling:false,
                type : 'iframe'
            });
        });


        //修改客户
        $("#edit-customer").click(function(){
            var cust_id=$("#cust_id").val();
            if(!cust_id){
                layer.alert("请选择客户",{icon:2,time:5000});
            }else{
                $.fancybox({
                    href:"<?=\yii\helpers\Url::to(['edit-customer'])?>?id="+cust_id,
                    width:1000,
                    height:800,
                    padding:0,
                    autoSize: false,
                    scrolling:false,
                    type : 'iframe'
                });
            }
        });



        $(".select-date-time").click(function () {
            jeDate({
                dateCell: this,
                isToday:false,
                zIndex:8831,
                format: "YYYY-MM-DD hh:mm",
                skinCell: "jedatedeep",
                isTime: true,
                ishmsVal:true,
                okfun:function(elem, val) {
                    $(elem).change();
                    if($("#endDate").val()){
                        dataTime($("#startDate").val(),$("#endDate").val());
                    }
                },
                //点击日期后的回调, elem当前输入框ID, val当前选择的值
                choosefun:function(elem, val) {
                    $(elem).change();
                    if($("#endDate").val()){
                        dataTime($("#startDate").val(),$("#endDate").val());
                    }
                },
                //选中日期后的回调, elem当前输入框ID, val当前选择的值
                clearfun:function(elem, val) {
                    $(elem).change();
                    if($("#endDate").val()){
                        dataTime(val,val);
                    }
                }
            })
        });


        //新增提醒
        $("#add-remind").click(function(){
            var cust_id=$("#cust_id").val();
            if(!cust_id){
                layer.alert("请选择客户",{icon:2,time:5000});
            }else{
                $.fancybox({
                    href:"<?=\yii\helpers\Url::to(['add-remind'])?>?id="+cust_id,
                    width:640,
                    height:400,
                    padding:0,
                    autoSize: false,
                    scrolling:false,
                    type : 'iframe'
                });
            }
        });

        //区域选择
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


        function dataTime(startDate,endDate,type){
            if(startDate=="" || endDate==""){
                return false;
            }
            var date3 = new Date(endDate).getTime() - new Date(startDate).getTime();   //时间差的毫秒数
            if(date3<0){
                $("#day-1").val("");
                $("#hours-1").val("");
                $("#minutes-1").val("");
                return false;
            }
            //计算出相差天数
            var days=Math.floor(date3/(24*3600*1000))
            //计算出小时数
            var leave1=date3%(24*3600*1000)
            var hours=Math.floor(leave1/(3600*1000))
            //计算天数后剩余的毫秒数
            var leave2=leave1%(3600*1000)
            //计算相差分钟数
            var minutes=Math.floor(leave2/(60*1000))
            if(type == 1){
                $("#day").val(days);
                $("#hours").val(hours);
                $("#minutes").val(minutes);
            }else{
                $("#day-1").val(days);
                $("#hours-1").val(hours);
                $("#minutes-1").val(minutes);
            }
        }
    });
</script>
