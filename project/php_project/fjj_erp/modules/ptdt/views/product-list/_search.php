<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<style type="text/css">
    .label-width{
        width:40px;
    }
    .value-width{
        width:120px;
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
            <label class="label-width label-align">品 名：</label>
            <?=Html::textInput('pdt_name',\Yii::$app->request->get('pdt_name'),['class'=>'value-width value-align'])?>
        </div>
        <div class="inline-block">
            <label class="label-width label-align">料号：</label>
            <?=Html::textInput('part_no',\Yii::$app->request->get('part_no'),['class'=>'value-width value-align'])?>
        </div>
        <div class="inline-block">
            <label class="label-width label-align">类别：</label>
            <?=Html::dropDownList("",\Yii::$app->request->get('catg_id'),$options["category"],["prompt"=>"请选择","class"=>"cat_level","style"=>"width:120px;"])?>
            <?=Html::dropDownList("",null,null,["prompt"=>"请选择","class"=>"cat_level","style"=>"width:120px;"])?>
            <?=Html::dropDownList("",null,null,["prompt"=>"请选择","class"=>"cat_level","style"=>"width:120px;"])?>
        </div>
        <input type="hidden" name="catg_id" id="catg_id" value="">
        <div class="inline-block">
            <?= Html::button('查询', ['class' => 'btn-search search-btn-blue','style'=>'margin-left:20px;']) ?>
            <?= Html::button('重置', ['class' => 'btn-reset reset-btn-yellow']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<div class="space-10"></div>
<script>
    var params={
        status:"selling"
    };
    $(function(){
        $("#export").click(function(){
            window.location.href="<?=Url::to(['export'])?>?status="+params.status+"&"+$("#search-form").serialize();
        });
        $(".btn-search").click(function(){
            var queryArr=$("#search-form").serializeArray();
            for(var x in queryArr)
            {
                params[queryArr[x].name]=queryArr[x].value;
            }
            $("#data-area").datagrid("load",$.extend(params,{page:1,rows:10}));
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