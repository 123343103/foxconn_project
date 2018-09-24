<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/27
 * Time: 下午 02:09
 */
use \yii\helpers\Html;
echo Html::beginTag("div",["style"=>"width:100%;overflow-x:scroll;"]);
echo Html::beginTag("table",$widget->options);
echo Html::beginTag("thead");
    if($widget->rownumber){
        echo Html::tag("th","行号",$widget->rownumberColOptions);
    }
    if($widget->checkable){
        echo Html::beginTag("th",$widget->checkColOptions);
        echo Html::checkbox("","",["class"=>"checkAll"]);
        echo Html::endTag("th");
    }
    foreach ($widget->fields as &$field){
        $field['options']["width"]=$field["width"];
        echo Html::tag("th",$field['title'],$field['options']);
    }
    if($widget->actions){
        echo Html::tag("th","操作",$widget->actionsColOptions);
    }
echo Html::endTag("thead");
echo Html::beginTag("tbody");
foreach ($widget->data as $k=>$row){
    echo Html::beginTag("tr");
    echo Html::hiddenInput("",$row[$widget->indexField],["class"=>'row_id']);
    if($widget->rownumber){
        echo Html::tag("td",$k+1,["class"=>"row-number"]);
    }
    if($widget->checkable){
        echo Html::beginTag("td");
        echo Html::checkbox("","",["class"=>"check-row"]);
        echo Html::endTag("td");
    }
    foreach(array_column($widget->fields,"field") as $item){
        $options=$row['options'];
        $options["title"]=$row[$item];
        echo Html::tag("td",\yii\helpers\StringHelper::truncate($row[$item],30),$row['options']);
    }
    if($widget->actions){
        echo Html::beginTag("td");
        foreach ($widget->actions as $action){
            $action['options']['data-url']=\yii\helpers\Url::to(array_merge($action['options']['url'],['id'=>$row[$widget->indexField]]));
            echo Html::a($action['name'],"#",$action['options']);
        }
        echo Html::endTag("td");
    }
    echo Html::endTag("tr");
}
echo Html::endTag("tbody");
echo Html::beginTag("tfoot",["style"=>$widget->data?"display:none":""]);
echo Html::beginTag("tr");
$fields_num=count($widget->fields);
$widget->actions && $fields_num++;
$widget->checkable && $fields_num++;
$widget->rownumber && $fields_num++;
echo Html::tag("td",$widget->emptyTip,["colspan"=>$fields_num,"class"=>"text-center"]);
echo Html::endTag("tr");
echo Html::endTag("tfoot");
echo Html::endTag("table");
?>
<script>
    $(function(){
        $(".checkAll").click(function(){
            $(this).parents(".table-view").find(":checkbox").prop("checked",$(this).prop("checked"));
        });
        $(".check-row").click(function(){
            var total=$(this).parents(".table-view").find("tbody .row_id").size();
            var checked=$(this).parents(".table-view").find("tbody :checked").size();
            $(".checkAll").prop("checked",total==checked);
        });

        $(".table-view").click(function(){
            var _this=$(this);
            var target=$(event.target);
            var rowid=target.parents("tr").find('.row_id').val();
            if(target.hasClass("row-edit")){
                window.location.href=target.data("url");
            }else if(target.hasClass("row-del")){
                layer.confirm(
                    "确定要删除吗?",{icon:2,time:5000},function(){
                        var options={
                            type:"get",
                            url:target.data("url"),
                            dataType:"json",
                            success:function(data){
                                layer.alert(data.msg,{icon:1},function(){
                                    layer.closeAll();
                                    target.parents("tr").remove();
                                    reindex(_this);
                                });
                            }
                        };
                        $.ajax(options);
                    });
            }
        });
    });

    function reindex(target){
        var t=target.find(".row-number");
        for(var x=0;x<t.size();x++){
            console.log(x);
            t.eq(x).text(x+1);
        }
    }
</script>

<style>
    table{
        table-layout: fixed;
    }
    td{
        word-break: break-all;
    }
</style>
