<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/13
 * Time: 上午 10:07
 */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title="媒体资源管理列表";
$this->params["homeLike"]=["label"=>"客户关系管理","url"=>Url::to(['/'])];
$this->params["breadcrumbs"][]=["label"=>"媒体资源管理列表","url"=>['index']];
?>
<div class="content">

    <h2 class="head-first">媒体资源管理列表</h2>
    <?php ActiveForm::begin(["method"=>"get"]) ?>
    <div class="mb-20">
        <label class="width-80" for="">需求编号</label>
        <input class="width-120" type="text" name="medic_code" value="<?=\Yii::$app->request->get('medic_code')?>">
        <label class="width-80" for="">媒体类型</label>
        <?=Html::dropDownList("media_type",\Yii::$app->request->get('media_type'),$options["mediaType"],["prompt"=>"请选择","class"=>"width-120"])?>
        <label class="width-80" for="">公司名称</label>
        <input class="width-300" type="text" name="cmp_name" value="<?=\Yii::$app->request->get('cmp_name')?>">
    </div>
    <div class="mb-20">
        <label class="width-80" for="">是否供应商</label>
        <?=Html::dropDownList("is_supplier",\Yii::$app->request->get('is_supplier'),$options["isSupplier"],["prompt"=>"请选择","class"=>"width-120"])?>
        <label class="width-80" for="">服务评级</label>
        <?=Html::dropDownList("service_level",\Yii::$app->request->get('service_level'),$options["serviceLevel"],["prompt"=>"请选择","class"=>"width-120"])?>
        <label class="width-80" for="">建立时间</label>
        <input name="start_time" class="width-120 select-date-time" type="text" value="<?=\Yii::$app->request->get('start_time')?>" readonly><span class="width-60 text-center">至</span><input name="end_time" class="width-120 select-date-time" type="text" value="<?=\Yii::$app->request->get('end_time')?>" readonly>
        <button type="submit" class="search-btn-blue ml-20">查询</button>
        <button type="button" onclick="window.location.href='index'" class="reset-btn-yellow">重置</button>
    </div>
    <?php ActiveForm::end() ?>

    <?=\app\widgets\toolbar\Toolbar::widget([
        'title'=>'媒体基础资料',
        'menus'=>[
            [
                'label'=>'新增',
                'icon'=>'add-item-bgc',
                'url'=>Url::to(['create']),
                'dispose'=>'default'
            ],
            [
                'label'=>'修改',
                'icon'=>'update-item-bgc',
                'options'=>['id'=>'edit']
            ],
            [
                'label'=>'详情',
                'icon'=>'details-item-bgc',
                'options'=>['id'=>'view']
            ],
            [
                'label'=>'删除',
                'icon'=>'delete-item-bgc',
                'options'=>['id'=>'remove']
            ],
            [
                'label'=>'新增服务内容',
                'icon'=>'details-item-bgc',
                'options'=>['id'=>'new-service']
            ],
            [
                'label'=>'返回',
                'icon'=>'return-item-bgc',
                'url'=>Url::home(),
                'dispose'=>'default'
            ]
        ]
    ])?>


    <div id="data"></div>

    <div class="space-30"></div>

    <iframe id="child-data" style="width:100%;height: 250px;"  frameborder="0"></iframe>

</div>


<script>
    $(function(){
        $("#data").datagrid({
            url: "<?=Url::current()?>",
            rownumbers: true,
            method: "get",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            columns:[[<?=$columns?>]],
            onLoadSuccess: function (data) {
                datagridTip($("#data"));
                showEmpty($(this),data.total,0);
            },
            onSelect:function(){
                var row=$("#data").datagrid("getSelected");
                $("#child-data").attr("src","<?=Url::to(['child-data'])?>?id="+row.medic_id);
            }
        });

        $("#edit").on("click",function(){
            var row=$("#data").datagrid("getSelected");
            if(!row){
                layer.alert("请选择一条记录",{icon:1});
            }
            window.location.href="<?=Url::to(['edit'])?>?id="+row.medic_id;
        });
        $("#view").on("click",function(){
            var row=$("#data").datagrid("getSelected");
            if(!row){
                layer.alert("请选择一条记录",{icon:1});
            }
            window.location.href="<?=Url::to(['view'])?>?id="+row.medic_id;
        });
        $("#remove").on("click",function(){
            var row=$("#data").datagrid("getSelected");
            if(!row){
                layer.alert("请选择一条记录",{icon:1});
            }
            layer.confirm("确定删除吗?",{"btn":["确定","取消"],icon:2},function(){
                $.ajax({
                    type:"get",
                    url:"<?=Url::to(['remove'])?>?id="+row.medic_id,
                    dataType:"json",
                    success:function(){
                        layer.alert("删除成功",{icon:1},function(){
                            layer.closeAll();
                            var index=$("#data").datagrid("getRowIndex",row);
                            $("#data").datagrid("deleteRow",index);
                        });
                    }
                });
            },function(){
                layer.closeAll();
            });
        });
        $("#new-service").on("click",function(){
            var row=$("#data").datagrid("getSelected");
            if(!row){
                layer.alert("请选择一条记录",{icon:1});
            }
            $.fancybox({
                width:760,
                height:500,
                autoSize:false,
                type:"iframe",
                padding:0,
                href:"<?=Url::to(['new-service'])?>?id="+row.medic_id
            });
        });

    });
</script>