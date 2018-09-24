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
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<?php $form=ActiveForm::begin([
        "action"=>["edit","id"=>$id],
        "id"=>"edit-form"
]);?>
    <h2 class="head-first">
        修改潜在客户
    </h2>

    <div class="mb-20">
        <label class="width-100" for="">公司名称</label>
        <input name="cust_sname" class="width-120 easyui-validatebox" data-options="required:true"  type="text" value="<?=$model['cust_sname'];?>">
        <label class="width-100" for="">公司简称</label>
        <input name="cust_shortname" class="width-120" type="text" value="<?=$model['cust_shortname'];?>">
        <label class="width-100" for="">公司电话</label>
        <input name="cust_tel1" class="width-120 easyui-validatebox" data-options="validType:'telphone'" type="text" value="<?=$model['cust_tel1'];?>">
    </div>

    <div class="mb-20">
        <input name="create_by" class="width-120"  type="hidden" value="<?=\Yii::$app->user->identity->staff->staff_id;?>">
        <label class="width-100" for="">联系人</label>
        <input name="cust_contacts" class="width-120"  type="text" value="<?=$model['cust_contacts']?>">
        <label class="width-100" for="">职位</label>
        <input name="cust_position" class="width-120" type="text" value="<?=$model['cust_position'];?>">
        <label class="width-100" for="">手机号码</label>
        <input name="cust_tel2" class="width-120 easyui-validatebox" data-options="required:true,validType:'mobile'" type="text" value="<?=$model['cust_tel2'];?>">
        <label class="width-100" for="">邮箱</label>
        <input name="cust_email" class="width-120 easyui-validatebox" data-options="required:true,validType:'email'" type="text" value="<?=$model['cust_email'];?>">
    </div>


    <div class="mb-20">
        <label class="width-100" for="">是否会员</label>
        <select class="width-120" name="cust_ismember" id="">
            <option value="1">是</option>
            <option value="0">否</option>
        </select>
        <label class="width-100" for="">会员类别</label>
        <select class="width-120" name="member_type" id="">
            <option value="">选择</option>
            <?php foreach($downList[member_type] as $k=>$v){ ?>
                <option <?=$v[bsp_id]==$model[member_type]?"selected":""?> value="<?=$v[bsp_id];?>"><?=$v[bsp_svalue];?></option>
            <?php } ?>
        </select>
        <label class="width-100" for="">注册网站</label>
        <select class="width-120" name="member_regweb" id="">
            <option value="">选择</option>
            <?php foreach($downList[cust_regweb] as $k=>$v){ ?>
                <option <?=$v[bsp_id]==$model[member_regweb]?"selected":""?> value="<?=$v[bsp_id];?>"><?=$v[bsp_svalue];?></option>
            <?php } ?>
        </select>
        <label class="width-100" for="">需求类别</label>
        <input name="member_reqdesription" class="width-120" type="text" value="<?=$model['member_reqdesription'];?>">
    </div>

    <div class="mb-20">
        <label class="width-100" for="">详细地址</label>
        <select class="width-100 disName easyui-validatebox" data-options="required:true">
            <option value="">选择</option>
            <?php if($model['cust_district_2']){ ?>
            <?php foreach($district[oneLevel] as $k=>$v){ ?>
                <option <?=$v[district_id]==$district[oneLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
            <?php }}else{ ?>
                <option value="1">中国</option>
            <?php } ?>
        </select>
        <select class="width-100 disName easyui-validatebox" data-options="required:true"  id="">
            <option value="">选择</option>
            <?php foreach($district[twoLevel] as $k=>$v){ ?>
                <option <?=$v[district_id]==$district[twoLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
            <?php } ?>
        </select>
        <select class="width-100 disName easyui-validatebox" data-options="required:true" id="">
            <option value="">选择</option>
            <?php foreach($district[threeLevel] as $k=>$v){ ?>
                <option <?=$v[district_id]==$district[threeLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
            <?php } ?>
        </select>
        <select class="width-100 disName easyui-validatebox" data-options="required:true" name="cust_district_2" id="">
            <option value="">选择</option>
            <?php foreach($district[fourLevel] as $k=>$v){ ?>
                <option <?=$v[district_id]==$district[fourLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
            <?php } ?>
        </select>
        <input name="cust_adress" class="width-350 ml-40 easyui-validatebox" data-options="required:true" type="text" value="<?=$model['cust_adress'];?>">
    </div>



    <div class="mb-20">
        <label class="width-100" for="">客户来源</label>
        <select class="width-80" name="customer_source" id="">
            <option value="">选择</option>
            <?php foreach($downList[customer_source] as $k=>$v){ ?>
                <option <?=$v[bsp_id]==$model[member_source]?"selected":""?> value="<?=$v[bsp_id];?>"><?=$v[bsp_svalue];?></option>
            <?php } ?>
        </select>
        <label class="width-100" for="">经营模式</label>
        <select class="width-80" name="cust_businesstype" id="">
            <option value="">选择</option>
            <?php foreach($downList[cust_businesstype] as $k=>$v){ ?>
                <option <?=$v[bsp_id]==$model[cust_businesstype]?"selected":""?> value="<?=$v[bsp_id];?>"><?=$v[bsp_svalue];?></option>
            <?php } ?>
        </select>
        <label class="width-100" for="">经营范围</label>
<input class="width-120" type="text" name="member_businessarea" value="<?=$model[member_businessarea];?>">
    </div>

    <div class="mb-20">
        <label class="width-100" for="">法人代表</label>
        <input name="cust_inchargeperson" class="width-120" type="text" value="<?=$model['cust_inchargeperson'];?>">
        <label class="width-100" for="">注册时间</label>
        <input name="cust_regdate" class="width-120 select-date" type="text" value="<?=$model['cust_regdate'];?>">
        <label class="width-100" for="">注册资金</label>
        <input name="cust_regfunds" class="width-120 easyui-validatebox" data-options="validType:'price'" type="text" value="<?=$model['cust_regdate'];?>">
    </div>

    <div class="mb-20">
        <label class="width-100" for="">公司类型</label>
        <select class="width-120" name="cust_compvirtue" id="">
            <option value="">选择</option>
            <?php foreach($downList[company_type] as $k=>$v){ ?>
                <option <?=$v[bsp_id]==$model[cust_compvirtue]?"selected":""?> value="<?=$v[bsp_id];?>"><?=$v[bsp_svalue];?></option>
            <?php } ?>
        </select>
        <label class="width-100" for="">员工人数</label>
        <input name="cust_personqty" class="width-120 easyui-validatebox" data-options="validType:'int'" type="text" value="<?=$model['cust_personqty'];?>">
        <label class="width-100" for="">注册货币</label>
        <select class="width-120" name="member_regcurr" id="">
            <option value="">选择</option>
            <?php foreach($downList[currency] as $k=>$v){ ?>
                <option <?=$v[bsp_id]==$model[member_regcurr]?"selected":""?> value="<?=$v[bsp_id];?>"><?=$v[bsp_svalue];?></option>
            <?php } ?>
        </select>
    </div>

    <div class="mb-20">
        <label class="width-100" for="">年营业额</label>
        <input name="member_compsum" class="width-120 easyui-validatebox" data-options="validType:'price'" type="text" value="<?=$model['member_compsum'];?>">
        <label class="width-100" for="">交易币种</label>
        <select class="width-120" name="member_curr" id="">
            <option value="">选择</option>
            <?php foreach($downList[currency] as $k=>$v){ ?>
                <option <?=$v[bsp_id]==$model[member_curr]?"selected":""?> value="<?=$v[bsp_id];?>"><?=$v[bsp_svalue];?></option>
            <?php } ?>
        </select>
        <label class="width-100" for="">邮编</label>
        <input name="member_compzipcode" class="width-120 easyui-validatebox" data-options="validType:'postcode'"  type="text" value="<?=$model['member_compzipcode'];?>">
    </div>

    <div class="mb-20">
        <label class="width-100" for="">年采购额</label>
        <input class="width-120 easyui-validatebox" data-options="validType:'price'" type="text" name="cust_pruchaseqty" value="<?=$model['cust_pruchaseqty'];?>">
        <label class="width-100" for="">发票需求</label>
        <input name="member_compreq" class="width-120" type="text" value="<?=$model['member_compreq'];?>">
        <label class="width-100" for="">主要市场</label>
        <input name="member_marketing" class="width-120" type="text" value="<?=$model['member_marketing'];?>">
    </div>

    <div class="mb-20">
        <label class="width-100" for="">需求类目</label>
        <select class="width-120" name="member_reqitemclass" id="">
            <option value="">选择</option>
            <?php foreach($downList[productType] as $k=>$v){ ?>
                <option <?=$v[category_id]==$model[member_reqitemclass]?"selected":""?> value="<?=$v[category_id];?>"><?=$v[category_sname];?></option>
            <?php } ?>
        </select>
        <label class="width-100" for="">主页</label>
        <input name="member_compwebside" class="width-350 easyui-validatebox" data-options="validType:'url'" type="text" value="<?=$model['member_compwebside'];?>">
    </div>

    <div class="mb-20">
        <label class="width-100" for="">潜在需求</label>
        <select class="width-80" name="member_reqflag" id="">
            <option value="">选择</option>
            <?php foreach($downList[member_reqflag] as $k=>$v){ ?>
                <option <?=$v[bsp_id]==$model[member_reqflag]?"selected":""?> value="<?=$v[bsp_id];?>"><?=$v[bsp_svalue];?></option>
            <?php } ?>
        </select>
        <label class="width-100" for="">主要客户</label>
        <input name="member_compcust" class="width-350" type="text" value="<?=$model['member_compcust'];?>">
    </div>

    <div class="mb-20">
        <label style="vertical-align: top;" class="width-100" for="">备注</label>
        <textarea name="member_remark" id="" cols="68" rows="4"><?=$model['member_remark'];?></textarea>
    </div>

    <div class="mb-20 text-center">
        <button type="submit" class="button-blue">确定</button>
        <button onclick="parent.$.fancybox.close()" class="button-white">返回</button>
    </div>

<?php $form->end();?>


<script>
    $(function(){

        $("#edit-form").ajaxForm(function(res){
            res=JSON.parse(res);
            parent.layer.alert(res.msg,{icon:1});
            parent.$.fancybox.close();
            parent.window.location.reload();
        });


        $(".select-date").click(function(){
            jeDate({
                dateCell: this,
                zIndex:8831,
                format:"YYYY-MM-DD hh:mm:ss",
                skinCell: "jedatedeep",
                isTime: false
            });
        });

        //地区选择
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
    });
</script>
