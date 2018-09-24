<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title='商品库存查询';
$this->params['homeLike']=['label'=>'仓储物流管理','url'=>Url::home()];
$this->params['breadcrumbs'][]=$this->title;
?>
<style>
    .width-120{width: 140px;}
    thead tr th p {
        color: white;
    }
    .value-width{
        width:200px !important;
    }
    .label-width{
        width:80px;
    }
    .label-widths{
        width:160px;
    }
    .ml-220{
        margin-left: 130px;
    }
    .ml-230{
        margin-left: 70px;
    }
    .add-bottom {
        margin-bottom: 5px;
    }
    .width-100{width: 50px;}
</style>
<div class="content">
    <?php ActiveForm::begin([
        "id"=>"search-form",
        "method"=>"get",
        "action"=>['index']
    ]);?>
    <div class="mb-10">
        <label class="label-width label-align add-bottom" for="">商品类别</label><label>:</label>
        <?=Html::dropDownList("catg_id",\Yii::$app->request->get('catg_id'),
            $options['pdt_category'],["prompt"=>"请选择","class"=>"width-120"])?>
        <label for="" class="width-100">批次</label><label>:</label>
        <?=Html::dropDownList("sssss",\Yii::$app->request->get('batch_no'),
            $options['sssss'],["prompt"=>"请选择","class"=>"width-120"])?>
        <label for="" class="width-100">规格型号</label><label>:</label>
        <input type="text" class="width-120" name="tp_spec" value="<?=\Yii::$app->request->get('tp_spec')?>">
    </div>
    <div class="mb-10">
        <label for="" class="label-width label-align add-bottom">仓库名称</label><label>:</label>
        <?=Html::dropDownList("wh_name",\Yii::$app->request->get('wh_name'),$options['wh_name'],["prompt"=>"请选择","class"=>"width-120","style"=>"overflow:hidden"])?>
        <label for="" class="width-100">仓库代码</label><label>:</label>
        <?=Html::dropDownList("wh_code",\Yii::$app->request->get('wh_code'),$options['wh_code'],["prompt"=>"请选择","class"=>"width-120"])?>
        <label for="" class="width-100">仓库属性</label><label>:</label>
        <?=Html::dropDownList("wh_attr",\Yii::$app->request->get('wh_attr'),$options['wh_attr'],
            ["prompt"=>"请选择","class"=>"width-120"])?>
        <label for="" class="width-100">仓库类别</label><label>:</label>
        <select name="wh_type" class="width-120">
       <option value="">全部</option>
        <?php foreach ($options['wh_type'] as $key=>$val){?>
            <option value="<?=$val['bsp_id']?>"><?=$val['bsp_svalue']?></option>
        <?php }?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width label-align add-bottom" >料号</label><label>:</label>
        <input type="text" class="width-120" name="part_no" value="<?=\Yii::$app->request->get('pdt_no')?>">
        <label class="width-100" for="">商品名称</label><label>:</label>
        <input type="text" class="width-120" name="pdt_name" value="<?=\Yii::$app->request->get('pdt_name')?>">
        <label class="width-100" for="">品牌</label><label>:</label>
        <input type="text" class="width-120" name="brand" value="<?=\Yii::$app->request->get('brand_name')?>">
        <span class="ml-20">
            <input style="vertical-align: middle;" type="checkbox" id="show_zero" name="show_zero" value="1">
            是否显示零库存
        </span>
        <span class="ml-20">
            <input style="vertical-align: middle;" type="checkbox" id="count_by_wh" name="count_by_wh" value="1">
            只按仓库统计
        </span>
        <button id="search" type="button" class="search-btn-blue ml-40">查询</button>
        <button id="reset" type="reset" class="reset-btn-yellow ">重置</button>
    </div>
<?php ActiveForm::end(); ?>


    <?=\app\widgets\toolbar\Toolbar::widget([
        'title'=>'商品库列表',
        'menus'=>[
            [
                'label'=>'导出',
                'icon'=>'export-item-bgc',
                'url'=>Url::to(['index','export'=>1]),
                'dispose'=>'default'
            ],
            [
                'label'=>'返回',
                'icon'=>'return-item-bgc',
                'url'=>Url::to(['home']),
                'dispose'=>'default'
            ]
        ]
    ]);?>



    <div style="width:100%;" id="data"></div>
</div>
<script>
    $(function(){
        $("#data").datagrid({
            url:"<?=Url::to(['index'])?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            columns:[[
                {field: "wh_name", title: "仓库名称", width: 150},
                {field: "st_code", title: "储位", width: 150},
                {field: "catg_name", title: "商品类别", width: 150},
                {field: "part_no", title: "料号", width: 150},
                {field: "pdt_name", title: "商品名称", width: 150},
                {field: "batch", title: "批次", width: 150},
                {field: "brand", title: "品牌", width: 150},
                {field: "tp_spec", title: "规格型号", width: 150},
                {field: "invt_num", title: "现有库存", width: 150},
                {field: "unit", title: "单位", width: 150},
                {field: "wh_code", title: "仓库代码", width: 150},
                {field: "wh_attr", title: "仓库属性", width: 150},
                {field: "wh_type", title: "仓库类别", width: 150},
                {field: "wh_lev", title: "仓库级别", width: 150}
            ]]
        });
    });
    $("#search").click(function(){
        var queryArr=$("#search-form").serializeArray();
        params={};
        for(var x in queryArr)
        {
            params[queryArr[x].name]=queryArr[x].value;
        }
        $("#data").datagrid("reload",params);
    });
    $("#reset").click(function(){
        $("#data").datagrid("showColumn", 'st_code');
        $("#data").datagrid("showColumn", 'batch');
        $("#data").datagrid("reload",{});
    });
    $('#count_by_wh').click(function(){
        var queryArr=$("#search-form").serializeArray();
        params={};
        for(var x in queryArr)
        {
            params[queryArr[x].name]=queryArr[x].value;
        }
        if ($('#count_by_wh').is(':checked')==true) {
            $("#data").datagrid("hideColumn", 'st_code');
            $("#data").datagrid("hideColumn", 'batch');
            $("#data").datagrid("reload",params);
        }else {
            $("#data").datagrid("showColumn", 'st_code');
            $("#data").datagrid("showColumn", 'batch');
            $("#data").datagrid("reload",params);
        }
    });
    $("#show_zero").click(function () {
        var queryArr=$("#search-form").serializeArray();
        params={};
        for(var x in queryArr)
        {
            params[queryArr[x].name]=queryArr[x].value;
        }
        $("#data").datagrid("reload",params);
    })
</script>
