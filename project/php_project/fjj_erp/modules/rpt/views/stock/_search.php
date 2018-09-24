<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\JeDateAsset::register($this);
?>
<style type="text/css">
    .label-width{
        width:87px;
    }
    .value-width{
        width:165px;
    }
    .width-150{
        width:166px;
    }
    .width-100{
        width:165px;
    }
    .no-after{
        margin-left:39px;
        margin-right:34px;
    }
</style>
<?php
$form = ActiveForm::begin([
    'id'=>'search-form',
    'action' => ['index'],
    'method' => 'get',
]);
?>
<div class="search-div" style="margin:0;">
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align">创业公司：</label>
            <?=Html::textInput('company',\Yii::$app->request->get('company'),['class'=>'value-width value-align'])?>
        </div>
        <div class="inline-block">
            <label class="label-width label-align">仓库名称：</label>
            <?=Html::textInput('wh_name',\Yii::$app->request->get('wh_name'),['class'=>'value-width value-align'])?>
        </div>
        <div class="inline-block">
            <label class="label-width label-align">仓库代码：</label>
            <?=Html::textInput('wh_code',\Yii::$app->request->get('wh_code'),['class'=>'value-width value-align'])?>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
           <label class="label-width label-align">鸿海料号：</label>
           <?=Html::textInput('part_no',\Yii::$app->request->get('part_no'),['class'=>'value-width value-align'])?>
        </div>
        <div class="inline-block">
          <label class="label-width label-align">日期：</label>
          <input type="text" id="startTime" class="width-150 Wdate" name="LogSearch[startTime]" value="<?= $search['startTime']?>" onclick="WdatePicker({skin:'whyGreen',maxDate:'#F{$dp.$D(\'endTime\')}'})">
        </div>
        <input type="hidden" name="catg_id" id="catg_id" value="">
        <div class="inline-block">
            <label class="no-after">—</label>
            <input type="text" id="endTime" class="width-100 Wdate" name="LogSearch[endTime]" value="<?= $search['endTime']?>" onclick="WdatePicker({skin:'whyGreen',minDate:'#F{$dp.$D(\'startTime\')}'})">
            <?= Html::button('查询', ['class' => 'btn-search search-btn-blue','style'=>'margin-left:20px;']) ?>
            <?= Html::button('重置', ['class' => 'btn-reset reset-btn-yellow']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<div class="space-10"></div>
<script>
    var queryParams={
//        status:"selling"
    };
    $(function(){
        $("#export").click(function(){
            window.location.href="<?=Url::to(['export'])?>?status="+params.status+"&"+$("#search-form").serialize();
        });
        $(".btn-search").click(function(){
            var queryArr=$("#search-form").serializeArray();
            for(var x in queryArr)
            {
                queryParams[queryArr[x].name]=queryArr[x].value;
            }
            $("#data").datagrid("load",$.extend(queryParams,{page:2,rows:10}));
        });

        $(".btn-reset").click(function(){
            $("#search-form").find("input,select").val("");
            $("#search-form").find("select").trigger("change");
            $(".btn-search").trigger("click");
        });

        $(".cat_level").change(function(event){
            var _this=$(this);
            $("#catg_id").val(_this.val());
            var event=event || window.event;
            var target=event.srcElement || event.target;
            if(_this.index()==3){
                return;
            }
            _this.nextAll().html("<option value=''>请选择</option>");
            if(_this.val()!=""){
                $.ajax({
                    url:'<?=Url::to(['get-next-level'])?>',
                    type:'get',
                    data:{
                        id:_this.val()
                    },
                    success:function(res){
                        _this.next().html(res);
                    }

                });
            }
        });
    });
</script>