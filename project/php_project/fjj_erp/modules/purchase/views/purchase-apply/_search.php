<?php
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
?>
<style>
    .width-200{
        width: 180px;
    }
    .mb-10{
        margin-bottom: 10px;
    }
</style>
<div class="crm-customer-info-search">
    <?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
    <div class="mb-10">
        <label class="label-align" style="width:70px;">单据类型：</label>
        <select class="width-200" id="req_dct">
            <option value="">请选择...</option>
            <?php foreach($data['req_dct'] as $key=>$val){?>
            <option value="<?=$key?>"><?=$val?></option>
            <?php }?>
        </select>
        <label style="width:80px;">申请日期：</label>
        <input id="start_date" type="text" class="Wdate width-200" readonly="readonly">
        <label>至</label>
        <input id="end_date" type="text" class="Wdate width-200" readonly="readonly">
    </div>
    <div class="mb-10">
        <label class="label-align" style="width:70px;">请购形式：</label>
        <select class="width-200" id="req_rqf">
            <option value="">请选择...</option>
            <?php foreach($data['req_rqf'] as $key=>$val){?>
                <option value="<?=$key?>"><?=$val?></option>
            <?php }?>
        </select>
        <label class="label-align" style="width:76px;">请购单状态：</label>
        <select class="width-200" id="req_status">
            <option value="">请选择...</option>
            <?php foreach($data['req_status'] as $key=>$val){?>
                <option value="<?=$val['prch_id']?>"><?=$val['prch_name']?></option>
            <?php }?>
        </select>
        <label class="label-align" style="width:70px;">法人：</label>
        <select class="width-200" id="leg_id">
            <option value="">请选择...</option>
            <?php foreach($data['leg_id'] as $key=>$val){?>
                <option value="<?=$val['company_id']?>"><?=$val['company_name']?></option>
            <?php }?>
        </select>
    </div>
<div class="mb-10">
    <label class="label-align" style="width:70px;">采购区域：</label>
    <select class="width-200" id="area_id">
        <option value="">请选择...</option>
        <?php foreach($data['area_id'] as $key=>$val){?>
            <option value="<?=$val['factory_id']?>"><?=$val['factory_name']?></option>
        <?php }?>
    </select>
    <label class="label-align" style="width:76px;">采购部门：</label>
    <select class="width-200" id="req_dpt_id">
        <option value="">请选择...</option>
        <?php foreach($data['req_dpt_id'] as $key=>$val){?>
            <option value="<?=$val['organization_id']?>"><?=$val['organization_name']?></option>
        <?php }?>
    </select>
    <label class="label-align" style="width:70px;">请购单号：</label>
    <input type="text" class="width-200" id="req_no">
        <button id="search" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
        <button id="reset" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
</div>
    <?php ActiveForm::end(); ?>
</div>
  <script>
  function loadData(){
  $("#data").datagrid('load',{
      "req_dct":$("#req_dct").val(),
      "req_rqf":$("#req_rqf").val(),
      "area_id":$("#area_id").val(),
      "leg_id":$("#leg_id").val(),
      "req_no":$("#req_no").val(),
      "req_dpt_id":$("#req_dpt_id").val(),
      "req_status":$("#req_status").val(),
      "start_date":$("#start_date").val(),
      "end_date":$("#end_date").val()
  }).datagrid('clearSelections').datagrid('clearChecked');
  }

  $(function() {
      //申请时间
      $("#start_date").click(function(){
          WdatePicker({
              skin:"whyGreen",
              dateFmt:"yyyy-MM-dd",
              maxDate:"#F{$dp.$D('end_date',{d:0})}"
          });
      });
      $("#end_date").click(function(){
          WdatePicker({
              skin:"whyGreen",
              dateFmt:"yyyy-MM-dd",
              minDate:"#F{$dp.$D('start_date',{d:0})}"
          });
      });

  //查询
  $("#search").click(function () {
      $("#edit_btn").hide().next().hide();
      $("#censorship_btn").hide().next().hide();
      $("#cancel_btn").hide().next().hide();
  loadData();
  });
  $(document).keydown(function (event) {
  if (event.keyCode == 13) {
  loadData();
  }
  });

  //重置
  $("#reset").click(function () {
      $("#edit_btn").hide().next().hide();
      $("#censorship_btn").hide().next().hide();
      $("#cancel_btn").hide().next().hide();
  $("input,select").val("");
  loadData();
  });
  });
  </script>