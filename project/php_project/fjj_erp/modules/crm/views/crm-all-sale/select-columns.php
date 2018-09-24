<?php
/**
 * User: F3859386
 * Date: 2017/5/8
 * Time: 16:28
 */
use app\assets\MultiSelectAsset;
use yii\widgets\ActiveForm;

MultiSelectAsset::register($this);
?>
<div class="no-padding width-750">
        <h2 class="head-first" >选择导出列</h2>
<?php $form = ActiveForm::begin(['id'=>"select-columns"]) ?>
<div class="mb-20">
    <div class="display-flex"><p class="head ml-80 mb-10">所有列：</p> <p class="head ml-280 mb-10">导出列： </p></div>
    <select multiple="multiple" id="my-select" name="columns[]">
        <option value="cust_filernumber-系统编号">系统编号</option>
        <option value="cust_code-客户代码">客户代码</option>
        <option value="cust_sname-客户名称">客户名称</option>
        <option value="cust_shortname-客户简称">客户简称</option>
        <option value="cust_type-类型">类型</option>
        <option value="cust_level-等级">等级</option>
        <option value="cust_tel1-公司电话">公司电话</option>
        <option value="cust_fax-传真">传真</option>
        <option value="cust_inchargeperson-公司法人">公司法人</option>
        <option value="member_regweb-公司网址">公司网址</option>
        <option value="cust_contacts-联系人">联系人</option>
        <option value="cust_tel1-联系电话">联系电话</option>
        <option value="cust_email-邮箱">邮箱</option>
        <option value="custManager-客户经理人">客户经理人</option>
        <option value="cust_salearea-所在军区">所在军区</option>
        <option value="customerAddress-公司地址">公司地址</option>
        <option value="cust_compscale-公司规模">公司规模</option>
        <option value="member_reqitemclass-需求类目">需求类目</option>
        <option value="cust_businesstype-经营类型">经营类型</option>
        <option value="cust_compvirtue-公司属性">公司属性</option>
        <option value="cust_personqty-员工人数">员工人数</option>
        <option value="cust_regdate-注册时间">注册时间</option>
        <option value="cust_regfunds-注册资金">注册资金</option>
        <option value="cust_islisted-是否上市">是否上市</option>
        <option value="member_compsum-年营业额">年营业额</option>
        <option value="cust_tax_code-税籍编码">税籍编码</option>
        <option value="cust_t-客户属性">客户属性</option>
        <option value="cust_ismember-是否会员">是否会员</option>
        <option value="member_name-会员名">会员名</option>
        <option value="cust_email-邮箱" >邮箱</option>
        <option value="cust_tel2-注册手机">注册手机</option>
    </select>
    </div>
<div class="mb-20 text-center">
    <button class="button-blue-big" type="submit" >确定</button>
    <button class="button-white-big ml-20" type="button" onclick="parent.$.fancybox.close()">取消</button>
</div>
</div>
<?php ActiveForm::end() ?>
<script>
    $(function(){
        $("#my-select").multiSelect();
    });
</script>
