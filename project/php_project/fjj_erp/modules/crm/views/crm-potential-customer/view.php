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
use \app\classes\Menu;
$this->title="客户信息";
$this->params['homeLike']=['label'=>'客户关系管理','url'=>['/']];
$this->params['breadcrumbs'][]=['label'=>'潜在客户列表','url'=>['index']];
$this->params['breadcrumbs'][]=['label'=>'客户信息'];
?>
<style type="text/css">
    tr{
        line-height: 25px;
    }
</style>
<div class="content">
    <h2 class="head-first">
        客户信息
        <span class="head-code">编号：<?= $model['cust_filernumber'] ?></span>
    </h2>

    <div class="mb-10">
        <?= Menu::isAction('/crm/crm-potential-customer/edit')?Html::button("修改",["id"=>"edit","class"=>"button-blue"]):'' ?>
        <?= Menu::isAction('/crm/crm-potential-customer/delete')?Html::button("删除",["id"=>"remove","class"=>"button-blue"]):'' ?>
        <?= Menu::isAction('/crm/crm-potential-customer/index')?Html::button("切换列表",["id"=>"to-list","class"=>"button-blue"]):'' ?>
        <?= Menu::isAction('/crm/crm-potential-customer/to-investment')?Html::button("转招商开发",["id"=>"switch_investment","style"=>"width:100px","class"=>"button-blue"]):'' ?>
        <?= Menu::isAction('/crm/crm-potential-customer/to-sale')?Html::button("转销售",["id"=>"switch_sale","class"=>"button-blue"]):'' ?>
    </div>

    <div class="border-bottom mb-10"></div>

    <h2 class="head-second">
        <a href="javascript:void(0)" onclick="$('#base-info').slideToggle();$(this).find('i').toggleClass('icon-caret-right')" class="ml-10">
            <i class="icon-caret-down"></i>
            客户基本信息
        </a>
    </h2>


    <div id="base-info">
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">公司名称：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['cust_sname'];?></td>
                <td width="10%" class="no-border vertical-center label-align">公司简称：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['cust_shortname'];?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">公司电话：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['cust_tel1'];?></td>
                <td width="10%" class="no-border vertical-center label-align">邮编：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['member_compzipcode'];?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">联系人：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['cust_contacts'];?></td>
                <td width="10%" class="no-border vertical-center label-align">部门：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['cust_department'];?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">职位：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['cust_position'];?></td>
                <td width="10%" class="no-border vertical-center label-align">职位职能：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$downList["cust_function"][$model['cust_function']];?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">联系方式：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['cust_tel2'];?></td>
                <td width="10%" class="no-border vertical-center label-align">邮箱：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['cust_email'];?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">详细地址：</td>
                <td width="90%" class="no-border vertical-center value-align"><?=$model['address'];?></td>
            </tr>
        </table>
    </div>



    <h2 class="head-second">
        <a href="javascript:void(0)" onclick="$('#detail-info').slideToggle();$(this).find('i').toggleClass('icon-caret-right')" class="ml-10">
            <i class="icon-caret-down"></i>
            客户详细信息
        </a>
    </h2>

    <div id="detail-info">
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">法人代表：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['cust_inchargeperson'];?></td>
                <td width="10%" class="no-border vertical-center label-align">注册时间：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['cust_regdate'];?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">注册货币：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=isset($downList[currency][$model[member_regcurr]])?$downList[currency][$model[member_regcurr]]:""?></td>
                <td width="10%" class="no-border vertical-center label-align">注册资金：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['cust_regfunds'];?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">公司类型：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=isset($downList[company_type][$model[cust_compvirtue]])?$downList[company_type][$model[cust_compvirtue]]:""?></td>
                <td width="10%" class="no-border vertical-center label-align">客户来源：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=isset($downList[customer_source][$model[member_source]])?$downList[customer_source][$model[member_source]]:""?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">经营范围：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=isset($downList[cust_businesstype][$model[cust_businesstype]])?$downList[cust_businesstype][$model[cust_businesstype]]:""?></td>
                <td width="10%" class="no-border vertical-center label-align">交易币种：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=isset($downList[currency][$model[member_curr]])?$downList[currency][$model[member_curr]]:""?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">年营业额：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['member_compsum'];?></td>
                <td width="10%" class="no-border vertical-center label-align">年采购额：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['cust_pruchaseqty'];?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">员工人数：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['cust_personqty'];?></td>
                <td width="10%" class="no-border vertical-center label-align">发票需求：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['member_compreq'];?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">潜在需求：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=isset($downList[member_reqflag][$model[member_reqflag]])?$downList[member_reqflag][$model[member_reqflag]]:""?></td>
                <td width="10%" class="no-border vertical-center label-align">需求类目：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=isset($downList[productType][$model[member_reqitemclass]])?$downList[productType][$model[member_reqitemclass]]:""?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">需求类别：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['member_reqdesription'];?></td>
                <td width="10%" class="no-border vertical-center label-align">主要市场：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['member_marketing'];?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">主要客户：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['member_compcust'];?></td>
                <td width="10%" class="no-border vertical-center label-align">主页：</td>
                <td width="40%" class="no-border vertical-center value-align"><?=$model['member_compwebside'];?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">范围说明：</td>
                <td width="90%" class="no-border vertical-center value-align"><?=$model['member_businessarea'];?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="10%" class="no-border vertical-center label-align">备注：</td>
                <td width="90%" class="no-border vertical-center value-align"><?=$model['member_remark'];?></td>
            </tr>
        </table>
    </div>



</div>
<script>
    $(function(){
        //新增潜在客户
        $("#create").click(function(){
            window.location.href="<?=Url::to(['create'])?>";
        });

        //修改潜在客户
        $("#edit").click(function(){
            window.location.href="<?=Url::to(['edit','id'=>\Yii::$app->request->get('id')])?>";
        });

        //删除潜在客户
        $("#remove").click(function(){
            layer.confirm("确定要删除选中的记录吗？",{
                btn: ['確定', '取消'],
                icon: 2
            },function(){
                $.get("<?=Url::to(['delete','id'=>\Yii::$app->request->get('id')])?>",function(res){
                    obj=JSON.parse(res);
                    if(obj.flag==1){
                        layer.alert("删除成功",{icon:1},function(){
                            window.location.href="<?=Url::to(['index'])?>";
                        });
                    }else{
                        layer.alert("删除失败",{icon:2},function(){
                            window.location.href="<?=Url::to(['index'])?>";
                        });
                    }
                });
            });
        });

        //切换到列表页
        $("#to-list").click(function(){
            window.location.href="<?=Url::to(['index'])?>";
        });
        //选择地区
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
                $select.next().html('<option value=>請選擇</option>');
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



        //转销售
        $("#switch_sale").click(function(){
                $.get("<?=Url::to(["to-sale"])?>?type=sale_status&customers=<?=\Yii::$app->request->get('id');?>",function(res){
                    obj=JSON.parse(res);
                    if(obj.flag==1){
                        layer.alert("操作成功",{icon:1},function(){
                            window.location.href="<?=Url::to(['index'])?>";
                        });
                    }else{
                        layer.alert("操作失败",{icon:2},function(){
                            window.location.href="<?=Url::to(['index'])?>";
                        });
                    }
                });
        });


        //转招商
        $("#switch_investment").click(function(){
                $.get("<?=Url::to(["to-investment"])?>?type=investment_status&customers=<?=\Yii::$app->request->get('id');?>",function(res) {
                    obj=JSON.parse(res);
                    if(obj.flag==1){
                        layer.alert("操作成功",{icon:1},function(){
                            window.location.href="<?=Url::to(['index'])?>";
                        });
                    }else{
                        layer.alert("操作失败",{icon:2},function(){
                            window.location.href="<?=Url::to(['index'])?>";
                        });
                    }
                });
        });

        $("#export").click(function(){
            window.location.href="<?=Url::to(['index','cust_sname'=>$model['cust_sname'],'export'=>1])?>";
        });

    });
</script>

<style>
    button:hover{border:none;}
</style>