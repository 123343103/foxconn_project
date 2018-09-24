<?php
/**
 * 商品经理人列表
 * F3858995
 * 2016/10/22
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use app\classes\Menu;
use yii\widgets\Pjax;
use yii\grid\GridView;

$this->params['homeLike'] = ['label' => '商品开发'];
$this->params['breadcrumbs'][] = ['label' => '设置'];
$this->params['breadcrumbs'][] = ['label' => '商品经理人列表'];
$this->title = '商品经理人列表';/*BUG修正 增加title*/
$get=\Yii::$app->request->get();
?>
<div class="content">
    <div class="mt-20">
        <?php $search = ActiveForm::begin(
            ['method'=>"get","action"=>"index"]) ?>
        <div class="inline-block field-pdproductmanagersearch-searchpara">
            <label class="width-80">商品经理人</label>
            <?=Html::textInput("pm_name",$get["pm_name"],["class"=>"width-100"])?>
            <label class="width-80" for="">姓名</label>
            <?=Html::textInput("name",$get["name"],["class"=>"width-100"])?>
            <label class="width-80" for="">商品类别</label>
            <?=Html::dropDownList("category_id",$get["category_id"],$options["category"],["prompt"=>"请选择","class"=>"width-150"])?>
            <label class="width-80" for="">商品负责人</label>
            <?=Html::textInput("leader_name",$get["leader_name"],["class"=>"width-100"])?>
            <div class="help-block"></div>
        </div>
        <?= Html::submitButton('查询', ['class' => 'button-blue ml-50', 'type' => 'submit']) ?>
        <?= Html::resetButton('重置', ['class' => 'button-blue', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        <?php $search->end() ?>
    </div>
    <div class="table-head mt-20">
        <p class="head">商品经理人信息</p>
        <p class="float-right">
            <?= Menu::isAction('/ptdt/pm/add')?Html::a("<span class='text-center ml--5'>新增</span>",null, ['id'=>'add']):'' ?>
            <?= Menu::isAction('/ptdt/pm/edit')?Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'edit']):'' ?>
            <?= Menu::isAction('/ptdt/pm/delete')?Html::a("<span class='text-center ml--5'>刪除</span>", null,['id'=>'delete']):'' ?>
            <?= Menu::isAction('/ptdt/pm/add')?Html::a("<span class='text-center ml--5'>导入</span>", null,['id'=>'import']):'' ?>
            <?= Menu::isAction('/ptdt/pm/add')?Html::a("<span class='text-center ml--5'>导出</span>", null,['id'=>'export']):'' ?>
            <?= Menu::isAction('/ptdt/pm/add')?Html::a("<span class='text-center ml--5'>返回</span>", null,['id'=>'return']):'' ?>
        </p>
    </div>
    <div class="mt-10" >
        <div id="data">

        </div>
    </div>
</div>
<script>
    $(function(){
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers :true,
            method: "get",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            columns: [[
                {field: "pmName", title: "商品经理人",width:"200" },
                {field: "staff_code", title: "工号",width:"100"},
                {field: "position", title: "资位",width:"100"},
                {field: "typeName", title: "商品分类",width:"250"},
                {field: "parentName", title: "商品负责人",width:"200"},
                {field: "pm_desc", title: "备注",width:"200"},
                {field: "updator", title: "最后修改人",width:"278" },
                {field: "update_at", title: "最后更新时间",width:"278" }
            ]],
            onLoadSuccess : function(){
                setMenuHeight();
            }
        });
    })

        $("#add").on("click",function(){
            $.fancybox({
                type:"iframe",
                width:600,
                height:600,
                autoSize:false,
                href:"<?=Url::to(['add'])?>",
                padding:0
            });
        });
        $("#delete").on("click", function () {
            var selectId = $("#data").datagrid("getSelected");
            if (selectId == null) {
                layer.alert("请点击选择一条信息",{icon:2,time:5000});
            } else {
                layer.confirm("确定要删除这个吗",
                    {
                        btn:['确定', '取消'],
                        icon:2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            data: {"id": selectId['pm_id']},
                            url: "<?=Url::to(['/ptdt/pm/delete']) ?>",
                            success: function (msg) {
                                if( msg.flag === 1){
                                    layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
                                }else{
                                    layer.alert(msg.msg,{icon:2})
                                }
                            },
                            error :function(msg){
                                layer.alert(msg.msg,{icon:2})
                            }
                        })
                    },
                    function () {
                        layer.closeAll();
                    }
                )
            }
        });
        $("#edit").on("click",function(){
            var row = $("#data").datagrid("getSelected");
            if(!row){
                layer.alert("请点击选择一条信息",{icon:2})
                return false;
            }
            $.fancybox({
                type:"iframe",
                width:600,
                height:600,
                autoSize:false,
                href:"<?=Url::to(['edit'])?>?id="+row.pm_id,
                padding:0
            });
        });

        $("#export").on("click",function(){
            window.location.href="<?=Url::to(['index','export'=>1])?>";
        });

        $("#return").on("click",function(){
            window.location.href="<?=Url::home()?>";
        });
</script>