<?php
/**
 * User: F1677929
 * Date: 2017/12/18
 */
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
?>
<div class="head-first">生成收货单</div>
<?php ActiveForm::begin()?>
<div class="mb-10">
    <label style="width:150px;">收货通知单号：</label>
    <span style="width:200px;"><?=$data['arr1']['rcpnt_no']?></span>
    <label style="width:150px;">采购部门：</label>
    <span style="width:200px;"><?=$data['arr1']['organization_name']?></span>
</div>
<div class="mb-10">
    <label style="width:150px;">收货中心：</label>
    <span style="width:557px;"><?=$data['arr1']['rcp_name']?></span>
</div>
<div class="mb-10">
    <label style="width:150px;"><span style="color:red;">*</span>送货人：</label>
    <input type="text" style="width:200px;" class="easyui-validatebox" data-options="required:true,validType:'maxLength[10]'" name="RcpGoods[deliverer]">
    <label style="width:150px;"><span style="color:red;">*</span>联系方式：</label>
    <input type="text" style="width:200px;" class="easyui-validatebox" data-options="required:true,validType:'tel_mobile_c'" placeholder="请输入手机或座机号码" name="RcpGoods[deiver_tel]">
</div>
<div class="mb-10">
    <label style="width:150px;"><span style="color:red;">*</span>收货人：</label>
    <input type="text" style="width:200px;" class="easyui-validatebox" data-options="required:true,validType:'maxLength[10]'" name="RcpGoods[consignee]">
    <label style="width:150px;"><span style="color:red;">*</span>联系方式：</label>
    <input type="text" style="width:200px;" class="easyui-validatebox" data-options="required:true,validType:'tel_mobile_c'" placeholder="请输入手机或座机号码" name="RcpGoods[con_tel]">
</div>
<div class="mb-10">
    <label style="width:150px;">操作员：</label>
    <span style="width:200px;"><?=$data['arr1']['staff_name']?></span>
    <label style="width:150px;">操作时间：</label>
    <span style="width:200px;"><?=date("Y-m-d")?></span>
</div>
<div style="margin:0 15px 10px;">
    <div>商品信息</div>
    <div style="overflow:auto;">
        <table class="table"
            <?php if($data['arr1']['rcpnt_type']==1){?>
                style="width:970px;"
            <?php }?>
            <?php if($data['arr1']['rcpnt_type']==2){?>
                style="width:770px;"
            <?php }?>
            <?php if($data['arr1']['rcpnt_type']==3){?>
                style="width:770px;"
            <?php }?>
        >
            <thead>
            <tr>
                <th style="width:40px;">序号</th>
                <th style="width:150px;">料号</th>
                <th style="width:200px;">品名</th>
                <?php if($data['arr1']['rcpnt_type']==1){?>
                    <th style="width:200px;">供应商名称</th>
                <?php }?>
                <th style="width:100px;">送货数量</th>
                <th style="width:80px;">单位</th>
                <th style="width:100px;"><span style="color:red;">*</span>收货数量</th>
                <th style="width:100px;"><span style="color:red;">*</span>收货日期</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data['arr2'] as $key=>$val){?>
                <tr>
                    <td><?=$key+1?></td>
                    <td>
                        <?=$val['part_no']?>
                        <input type="hidden" name="arr[<?=$key?>][RcpGoodsDt][part_no]" value="<?=$val['part_no']?>">
                        <input type="hidden" name="arr[<?=$key?>][RcpGoodsDt][rcpdt_id]" value="<?=$val['rcpdt_id']?>">
                    </td>
                    <td><?=$val['pdt_name']?></td>
                    <?php if($data['arr1']['rcpnt_type']==1){?>
                        <td><?=$val['spp_fname']?></td>
                    <?php }?>
                    <td><?=$val['delivery_num']?></td>
                    <td><?=$val['unit']?></td>
                    <td>
                        <?php if($data['arr1']['rcpnt_type']==1){?>
                            <input type="text" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="required:true,validType:['two_decimal']" name="arr[<?=$key?>][RcpGoodsDt][rcpg_num]" value="<?=$val['delivery_num']?>">
                        <?php }?>
                        <?php if($data['arr1']['rcpnt_type']==2){?>
                            <input type="hidden" name="arr[<?=$key?>][RcpGoodsDt][rcpg_num]" value="<?=$val['delivery_num']?>"><?=$val['delivery_num']?>
                        <?php }?>
                        <?php if($data['arr1']['rcpnt_type']==3){?>
                            <input type="hidden" name="arr[<?=$key?>][RcpGoodsDt][rcpg_num]" value="<?=$val['chwh_num']?>"><?=$val['chwh_num']?>
                        <?php }?>
                    </td>
                    <td><input type="text" style="width:100%;text-indent:5px;" class="easyui-validatebox Wdate" data-options="required:true" readonly="readonly" onclick="WdatePicker({skin:'whyGreen',minDate:'<?=$data['arr1']['prch_date']?substr($data['arr1']['prch_date'],0,10):substr($data['arr1']['creat_date'],0,10)?>',maxDate:'%y-%M-%d',onpicked:function(){$(this).validatebox('validate')},oncleared:function(){$(this).validatebox('validate')}})" name="arr[<?=$key?>][RcpGoodsDt][rcpg_date]" value="<?=date('Y-m-d')?>"></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>
<div style="text-align:center;">
    <button type="submit" class="button-blue">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<?php ActiveForm::end()?>
<script>
    $(function(){
        ajaxSubmitForm("form","",
            function(data){
                if(data.flag==1){
                    parent.$(".fancybox-overlay").hide();
                    parent.$(".fancybox-wrap").hide();
                    parent.layer.alert(data.msg,{
                        icon:1,
                        end:function(){
                            parent.$("#table1").datagrid('reload');
                            parent.$.fancybox.close();
                        }
                    });
                }
                if(data.flag==0){
                    parent.layer.alert(data.msg,{icon:2});
                    $("button[type='submit']").prop("disabled",false);
                }
            }
        );
    })
</script>
