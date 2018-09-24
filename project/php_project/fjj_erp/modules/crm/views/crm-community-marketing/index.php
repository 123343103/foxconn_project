<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/13
 * Time: 上午 10:06
 */
use \yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title="网络社区营销";
$this->params["homeLike"]=["label"=>"客户关系管理","url"=>Url::to(['/'])];
$this->params["breadcrumbs"][]=["label"=>$this->title];
?>
<div class="content">
    <h2 class="head-first">社群营销列表</h2>
    <?php ActiveForm::begin([
            "action"=>["index"],
            "method"=>"get"
    ]) ?>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left">营销方式</label>
        <?=Html::dropDownList("commu_type",\Yii::$app->request->get("commu_type"),$options["commu_type"],["prompt"=>"请选择","class"=>"width-200 commu_type"])?>
        <label class="width-60 ml-20 text-left">载体</label>
        <?=Html::dropDownList("cmt_id",\Yii::$app->request->get("cmt_id"),$options["publish_carrier"],["prompt"=>"请选择","class"=>"width-200 carrier_type"])?>
    </div>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left">载体名称</label>
        <?=Html::dropDownList("cmt_intor",\Yii::$app->request->get("cmt_intor"),$options["carrier_names"],["prompt"=>"请选择","class"=>"width-200 carrier_name"])?>
        <label class="width-60 ml-20 text-left">时间</label>
        <input name="start_time" type="text" class="width-200 select-date" value="<?=\Yii::$app->request->get('start_time')?>" readonly>
        至
        <input name="end_time" type="text" class="width-200 select-date" value="<?=\Yii::$app->request->get('end_time')?>" readonly>
        <button type="submit" class="search-btn-blue ml-20">查询</button>
        <button type="button" onclick="window.location.href='<?=Url::to(['index'])?>'" class="reset-btn-yellow">重置</button>
    </div>
    <?php ActiveForm::end() ?>


    <?=\app\widgets\toolbar\Toolbar::widget([
            'menus'=>[
                [
                    'label'=>'新增',
                    'icon'=>'add-item-bgc',
                    'url'=>Url::to(['create']),
                    'dispose'=>'default'
                ],
                [
                    'label'=>'编辑',
                    'icon'=>'update-item-bgc',
                    'hide'=>true,
                    'options'=>['id'=>'edit']
                ],
                [
                    'label'=>'详情',
                    'icon'=>'details-item-bgc',
                    'options'=>['id'=>'view']
                ],
                [
                    'label'=>'新增统计',
                    'icon'=>'details-item-bgc',
                    'hide'=>true,
                    'options'=>['id'=>'newCount']
                ],
                [
                    'label'=>'删除',
                    'icon'=>'delete-item-bgc',
                    'hide'=>true,
                    'options'=>['id'=>'delete']
                ],
                [
                    'label'=>'状态设置',
                    'icon'=>'details-item-bgc',
                    'options'=>['id'=>'statusSet']
                ],
                [
                    'label'=>'返回',
                    'icon'=>'return-item-bgc',
                    'url'=>Url::home(),
                    'dispose'=>'default'
                ]
            ]
    ])?>

    <div style="clear: both;"></div>

    <div id="data"></div>

    <div class="space-30"></div>

    <iframe id="count-data" style="width:100%;height: 200px;" src="" frameborder="0"></iframe>
</div>
<script>
    var childRow=null;
    $(function(){
        $("#data").datagrid({
            url: "<?=Url::current()?>",
            rownumbers: true,
            method: "get",
            idField: "commu_ID",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            columns:[[
                <?=$columns?>
                {field:'action',title:'操作',formatter:function(value,row,index){
                    return "&nbsp;&nbsp;<a class='icon-edit' href='javascript:void(0)' onclick='editRow("+row.commu_ID+")'></a>&nbsp;&nbsp;<a class='icon-trash' href='javascript:void(0)' onclick='delRow("+row.commu_ID+")'></a>&nbsp;&nbsp;";
                }}

            ]],
            onLoadSuccess: function (data) {
                datagridTip($("#data"));
                showEmpty($(this),data.total,0);
            },
            onSelect:function(index,row){
                childRow=null;
                if(row.commu_status=="有效"){
                    $(".hide").css("display","inline-block");
                }else{
                    $(".hide").css("display","none");
                }
                $("#count-data").attr("src","<?=Url::to(['count-data'])?>?id="+row.commu_ID);
            }
        });

        $("#edit").click(function(){
            var row=$("#data").datagrid("getSelected");
            window.location.href="edit?id="+row.commu_ID;
        });
        $("#view").click(function(){
            var row=$("#data").datagrid("getSelected");
            if(!row){
                layer.alert("请选择一条记录",{icon:1});
                return ;
            }
            if(!childRow){
                layer.alert("请选择一条子表记录",{icon:1});
                return ;
            }
            window.location.href="view?id="+row.commu_ID;
        });
        $("#delete").click(function(){
            var row=$("#data").datagrid("getSelected");
            var index=layer.confirm("确定删除吗?",{btn:["确定","取消"],icon:2},function(){
                $.ajax({
                    type:"get",
                    url:"remove?id="+row.commu_ID,
                    dataType:"json",
                    success:function(data){
                        if(data.flag==1){
                            $("#data").datagrid("deleteRow",$("#data").datagrid("getRowIndex",row));
                            layer.alert("删除成功",{icon:1});
                        }else{
                            layer.alert("删除失败",{icon:1});
                        }
                    }
                });
            },function(){
                layer.close(index);
            });
        });
        $("#statusSet").click(function(){
            var row=$("#data").datagrid("getSelected");
            if(!row){
                layer.alert("请选择一条记录",{icon:1});
                return ;
            }
            $.fancybox({
                type:"iframe",
                width:400,
                height:250,
                padding:0,
                autoSize:false,
                href:"<?=Url::to(['status-set'])?>?id="+row.commu_ID
            });
        });
        $("#newCount").click(function(){
            var row=$("#data").datagrid("getSelected");
            $.fancybox({
                width:700,
                height:600,
                padding:0,
                autoSize:false,
                type:"iframe",
                href:"new-count?id="+row.commu_ID
            });
        });


        $(".commu_type").change(function(){
            var _this=$(this);
            _this.parents("div").find(".carrier_type,.carrier_name").empty().html("<option value=''>请选择</option>");
            if(!_this.val()){
                return;
            }
            $.ajax({
                type:"get",
                async:false,
                url:"get-publish-carriers?id="+_this.val(),
                success:function(data){
                    _this.parents("div").find(".carrier_type").html(data);
                }
            });
        });
        $(".carrier_type").change(function(){
            var _this=$(this);
            _this.parents("div").find(".carrier_name").empty().html("<option value=''>请选择</option>");
            if(!_this.val()){
                return;
            }
            $.ajax({
                type:"get",
                url:"<?=Url::to(['get-carrier-names'])?>",
                data:{
                    type:_this.parents("div").find(".commu_type").val(),
                    id:_this.val()
                },
                success:function(data){
                    _this.parents("div").find(".carrier_name").html(data);
                }
            });
        });
    });
    function editRow(index){
        event.stopPropagation();
        window.location.href="<?=Url::to(['edit'])?>?id="+index;
    }
    function delRow(index){
        event.stopPropagation();
        layer.confirm("确定删除吗?",{icon:2},function(){
            $.ajax({
                type:"get",
                url:"<?=Url::to(['remove'])?>?id="+index,
                dataType:"json",
                success:function(res){
                    if(res.flag==1){
                        $("#data").datagrid("reload");
                        layer.alert("删除成功",{icon:1});
                    }else{
                        layer.alert("删除失败",{icon:2});
                    }
                }
            });
            return true;
        });
    }
</script>
