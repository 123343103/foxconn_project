<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/13
 * Time: 上午 10:06
 */
use yii\helpers\Url;
$this->title="社群营销新增";
$this->params["homeLike"]=["label"=>"客户关系管理","url"=>Url::to(['/'])];
$this->params["breadcrumbs"][]=["label"=>"网络社区营销","url"=>['index']];
$this->params["breadcrumbs"][]=["label"=>$this->title];
?>
<div class="content">
    <h2 class="head-first">新增社群营销</h2>
    <?=$this->render("_form",["options"=>$options])?>
</div>
<script>
    $(function(){
        ajaxSubmitForm($("#community-form"));
        $("#cmt_type").change(function(){
            var _this=$(this);
            var index=$(this).find(":selected").index();
            var ele=$("#switch-box .item").eq(index);
                ele.find(".publish_carrier").html("<option>请选择</option>");
                ele.find(".carrier_name").html("<option>请选择</option>");
                $.ajax({
                    type:"get",
                    async:false,
                    url:"get-publish-carriers?id="+_this.val(),
                    success:function(data){
                        ele.find(".publish_carrier").html(data);
                    }
                });
            $("#dynamic-area").empty().append(ele.clone(true));
        }).change();
        $(".publish_carrier").change(function(){
            var _this=$(this);
            _this.parents("#dynamic-area").find(".carrier_name").html("<option>请选择</option>");
            $.ajax({
                type:"get",
                url:"<?=Url::to(['get-carrier-names'])?>",
                data:{
                    type:_this.parents("form").find("#cmt_type").val(),
                    id:_this.val()
                },
                success:function(data){
                    _this.parents("#dynamic-area").find(".carrier_name").html(data);
                }
            });
        });


    });
</script>