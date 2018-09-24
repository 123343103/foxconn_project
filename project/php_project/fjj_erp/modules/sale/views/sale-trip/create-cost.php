<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/1/20
 * Time: 上午 09:12
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = '出差費用报销单';
$this->params['homeLike'] = ['label' => 'CRM'];
$this->params['breadcrumbs'][] = ['label' => '出差申请及消单报告列表'];
$this->params['breadcrumbs'][] = ['label' => '出差费用报销单'];
?>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
</h1>

<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<div class="mb-20 float-right">
    <label for="">档案编号</label>
    <input type="text" class="width-200" name="CrmCustomerInfo[cust_filernumber]"
           value="<?/*= $customerInfo['cust_filernumber'] */?>">
</div>
<br/>
<h2 class="head-three">
    <i class="icon-caret-down"></i>
    <a href="javascript:void(0)">客户基本信息</a>
</h2>
<div>
    <div class="mb-20">
        <label class="width-80"><span class="red">*</span>客户全称</label>
        <input class="width-200 easyui-validatebox" name="CrmCustomerInfo[cust_sname]"
               value="<?/*= $customerInfo['cust_sname'] */?>" data-options="required:'true'" id="custSname">
        <span style="width:65px; margin-left:10px;" id="error_notice"></span>
        <label class="width-120"><span class="red">*</span>客户简称</label>
        <input class="width-200 easyui-validatebox" name="CrmCustomerInfo[cust_shortname]"
               data-options="required:'true'" value="<?/*= $customerInfo['cust_shortname'] */?>">
    </div>
    <div class="mb-20">
        <input type="hidden" id="cust_manager" name="CrmCustomerInfo[cust_manager]" class="staff_id" value="<?/*= $customerInfo['cust_manager_id'] */?>">
        <label class="width-80"><span class="red">*</span>客户经理人</label>
        <input type="text" onblur="setStaffInfo(this)" placeholder=" 请输入工号" maxlength="30"
               class="width-200 easyui-validatebox" id="pfrc_person" data-options="required:'true'"
               value="<?/*= $customerInfo['cust_manager'] */?>">
        <span class="width-80 staff_name"></span>
        <label style="width:115px;"><span class="red">*</span>客户联系人电话</label>
        <input class="width-200 easyui-validatebox" type="text" id="cust_tel2"
               name="CrmCustomerInfo[cust_tel2]" data-options="required:'true'"
               value="<?/*= $customerInfo['cust_tel2'] */?>">
    </div>
    <div class="mb-20">
        <label class="width-80"><span class="red">*</span>所在地区</label>
        <select class="width-200 easyui-validatebox" id="custArea" name="CrmCustomerInfo[cust_area]"
                data-options="required:'true'">
            <option value="">---请选择---</option>
            <?php /*foreach ($district as $key => $val) { */?><!--
                <option
                    value="<?/*= $val['district_id'] */?>" <?/*= $customerInfo['cust_area'] == $val['district_id'] ? "selected" : null; */?>><?/*= $val['district_name'] */?></option>
            --><?php /*} */?>
        </select>

        <label class="width-200">营销区域</label>
        <select class="width-200" id="custSalearea" name="CrmCustomerInfo[cust_salearea]"
                data-options="required:'true'">
            <option value="">---请选择---</option>
            <?php /*foreach ($salearea as $key => $val) { */?><!--
                <option
                    value="<?/*= $val['csarea_id'] */?>" <?/*= $customerInfo['cust_salearea'] == $val['csarea_id'] ? "selected" : null; */?>><?/*= $val['csarea_name'] */?></option>
            --><?php /*} */?>
        </select>
    </div>
    <div class="mb-20">
        <label class="width-80"><span class="red">*</span>客户类型</label>
        <select class="width-200 easyui-validatebox" name="CrmCustomerInfo[cust_type]"
                data-options="required:'true'">
            <option value>不限</option>
            <?php /*foreach ($downList['customerType'] as $key => $val) { */?><!--
                <option
                    value="<?/*= $val['bsp_id'] */?>" <?/*= $customerInfo['cust_type'] == $val['bsp_id'] ? "selected" : null; */?>><?/*= $val['bsp_svalue'] */?></option>
            --><?php /*} */?>
        </select>

        <label class="width-100"><span class="red">*</span>客户类别</label>
        <select class="width-200 easyui-validatebox" name="CrmCustomerInfo[cust_class]"
                data-options="required:'true'">
            <option value>不限</option>
            <?php /*foreach ($downList['customerClass'] as $key => $val) { */?><!--
                <option
                    value="<?/*= $val['bsp_id'] */?>" <?/*= $customerInfo['cust_class'] == $val['bsp_id'] ? "selected" : null; */?>><?/*= $val['bsp_svalue'] */?></option>
            --><?php /*} */?>
        </select>

        <label class="width-100"><span class="red">*</span>客户等级</label>
        <select class="width-200 easyui-validatebox" name="CrmCustomerInfo[cust_level]"
                data-options="required:'true'">
            <option value>不限</option>
           <!-- <?php /*foreach ($downList['custLevel'] as $key => $val) { */?>
                <option
                    value="<?/*= $val['bsp_id'] */?>" <?/*= $customerInfo['cust_level'] == $val['bsp_id'] ? "selected" : null; */?>><?/*= $val['bsp_svalue'] */?></option>
            --><?php /*} */?>
        </select>
    </div>
    <div class="mb-20">
        <label class="width-80"><span class="red">*</span>详细地址</label>
        <select class="width-130 disName easyui-validatebox" name="" id="disName_1" data-options="required:'true'">
            <option value="">---请选择---</option>
            <?php /*foreach ($country as $key => $val) { */?><!--
                <option
                    value="<?/*= $val['district_id'] */?>"><?/*= $val['district_name'] */?></option>
            --><?php /*} */?>
        </select>
        <select class="width-130 disName easyui-validatebox" name="" id="disName_2" data-options="required:'true'">
            <option value="">---请选择---</option>
        </select>
        <select class="width-130 disName easyui-validatebox" name="" id="disName_3" data-options="required:'true'">
            <option value="">---请选择---</option>
        </select>
        <select class="width-130 disName easyui-validatebox" name="CrmCustomerInfo[cust_district_2]" id="disName_4"
                data-options="required:'true'">
            <option value="">---请选择---</option>
        </select>
        <input class="width-300 easyui-validatebox" type="text" data-options="required:'true'"
               name="CrmCustomerInfo[cust_adress]" value="<?/*= $customerInfo['cust_adress'] */?>">
    </div>
</div>
    <div class="text-center">
        <button type="submit" class="button-blue-big">保存</button>
        <button class="button-white-big">取消</button>
    </div>
<?php ActiveForm::end(); ?>
</div>