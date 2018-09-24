<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/13
 * Time: 17:10
 */
use yii\helpers\Url;
use yii\helpers\Html;
use \app\classes\Menu;
use yii\widgets\ActiveForm;
?>
<style>
    #m-visit span{
        width:100px !important;
    }
    #m-data2{
        width:90px !important;
    }
    #m-deal span{
        width:100px !important;
    }
    #m-data{
        width:90px !important;
    }
    .wd-b{
        display: block;
    }
    .width-70{
        width:90px;
    }
</style>



<div class="table-head">
    <p class="head">客户列表</p>
    <div class="float-right">
        <?= Menu::isAction('/crm/crm-customer-info/credit-create')?
//            Html::a("<span class='text-center ml--5'>账信申请</span>", null,['id'=>'credit_apply','class'=>'display-none'])
            "<a id='credit_apply' class='display-none'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>账信申请</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
        <?= Menu::isAction('/crm/crm-customer-info/create')?
//            Html::a("<span>新增</span>",Url::to(['create']), ['id' => 'create'])
            "<a id='create' href='".Url::to(['create'])."'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>新增</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
        <?= Menu::isAction('/crm/crm-customer-info/update')?
//            Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'update'])
            "<a id='update' class='display-none'>
                    <div class='table-nav'>
                        <p class='update-item-bgc float-left'></p>
                        <p class='nav-font'>修改</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>

<!--        <a href="javascript:void(0)" id="m-visit" class="menu-one text-center ml--5 width-90">拜访管理</a>-->
<!--        <a href="javascript:void(0)" id="m-deal" class="menu-one text-center ml--5 width-70">数据处理</a>-->
        <?php if(Menu::isAction('/crm/crm-customer-info/plan-create') || Menu::isAction('/crm/crm-customer-info/record-add')){ ?>
        <div class="float-left display-none" id="visit">
            <a href="javascript:void(0)" id="m-visit" class="menu-one text-center ml--5 width-90">
                <div class='table-nav'>
                    <p class='setting1 float-left'></p>
                    <p class='nav-font'>拜访管理</p>
                </div>
                <p class="float-left">&nbsp;|&nbsp;</p>
            </a>
        </div>
        <?php } ?>
        <?php if(Menu::isAction('/crm/crm-customer-info/person-inch') || Menu::isAction('/crm/crm-customer-info/turn-investment') || Menu::isAction('/crm/crm-customer-info/throw-sea') || Menu::isAction('/crm/crm-customer-info/customer-info') || Menu::isAction('/crm/crm-customer-info/import') || Menu::isAction('/crm/crm-customer-info/export')){ ?>
        <div class="float-left">
            <a href="javascript:void(0)" id="m-deal" class="menu-one text-center ml--5 width-90">
                <div class='table-nav'>
                    <p class='setbcg5 float-left'></p>
                    <p class='nav-font'>数据处理</p>
                </div>
                <p class="float-left">&nbsp;|&nbsp;</p>
            </a>
        </div>
        <?php } ?>
        <?= Menu::isAction('/crm/crm-customer-info/delete')?
//            Html::a("<span class='text-center ml--5'>删除</span>", null,['onclick'=>'cancle()'])
            "<a id='delete' onclick='cancle();' class='display-none'>
                    <div class='table-nav'>
                        <p class='delete-item-bgc float-left'></p>
                        <p class='nav-font'>删除</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
        <?= Menu::isAction('/crm/crm-customer-info/activation')?
//            Html::a("<span class='text-center ml--5'>删除</span>", null,['onclick'=>'cancle()'])
            "<a id='activation' onclick='activation();' class='display-none'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>激活</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
    <div id="m-data" class="display-none">
        <?php if(Menu::isAction("/crm/crm-customer-info/person-inch")){ ?>
            <div class="take_inch display-none"><a onclick="inch()" id="inch" class="width-70 wd-b"><span>认领</span></a></div>
        <?php } ?>
        <?php if(Menu::isAction("/crm/crm-customer-info/assign")){ ?>
            <div class="take_assign display-none"><a onclick="assign()" id="assign" class="width-70 wd-b"><span>分配</span></a></div>
        <?php } ?>
        <?php if(Menu::isAction("/crm/crm-customer-info/turn-investment")){ ?>
            <div class="take_investment display-none"><a onclick="investment()" class="width-70 wd-b"><span>荐招商</span></a></div>
        <?php } ?>
        <?php if(Menu::isAction("/crm/crm-customer-info/throw-sea")){ ?>
            <div class="take_sea display-none"><a onclick="throwSea()" class="width-70 wd-b"><span>抛至公海</span></a></div>
        <?php } ?>
        <?php if(Menu::isAction("/crm/crm-customer-info/customer-info")){ ?>
            <div id="apply" class="display-none"><a class="width-70 wd-b"><span>代码申请</span></a></div>
        <?php } ?>
        <?php if(Menu::isAction("/crm/crm-customer-info/import")){ ?>
            <div><a id="showDiv" class="menu-span"><span>批量导入</span></a></div>
        <?php } ?>
        <?php if(Menu::isAction("/crm/crm-customer-info/export")){ ?>
            <div id="export"><a class="width-70"><span>批量导出</span></a></div>
        <?php } ?>
    </div>
    <div id="m-data2" class="display-none">
        <?php if(Menu::isAction("/crm/crm-customer-info/plan-create")){ ?>
        <div><a id="visit-plan"><span class=" width-90">添加拜访计划</span></a></div>
        <?php } ?>
        <?php if(Menu::isAction("/crm/crm-customer-info/record-add")){ ?>
        <div><a id="visit-record"><span class=" width-90">添加拜访记录</span></a></div>
        <?php } ?>
    </div>
</div>
<div style="display:none">
    <div id="inline" style="width:500px; height:260px; overflow:auto">
        <div class="pop-head">
            <p>导入导出</p>
        </div>
        <div class="mt-40">
            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
                'action' => ['insert-excel'],
                'id' => 'fileForm',
                'fieldConfig' => [
                    'errorOptions' => ['class' => 'error-notice mt-10'],
//                            'labelOptions'=>['class'=>'width-100'],
                    'inputOptions' => ['class' => 'width-200']
                ]
            ]); ?>
            <div class="ml-40">
                <div class="inline-block field-uploadForm">
                    <input type="hidden" name="UploadForm[file]" value="">
                    <input type="file" id="uploadForm" class="width-200 easyui-validatebox" name="UploadForm[file]" data-options="required:'true'" missingMessage="请选择文件">
                </div>
                <?= Html::submitButton('确认', ['class' => 'button-blue ml-20', 'id' => 'sub']) ?>&nbsp;
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>

    $(function(){
        $('#m-deal').menubutton({
            menu: '#m-data',
            hasDownArrow:false
        });
        $('#m-visit').menubutton({
            menu: '#m-data2',
            hasDownArrow:false
        });
        $('.menu-one').removeClass("l-btn l-btn-small l-btn-plain");
        $('.menu-one').find("span").removeClass("l-btn-left l-btn-text");

        $("#showDiv").click(function(){
            $.fancybox({
                type:"iframe",
                href:"<?=Url::to(['import'])?>",
                padding:0,
                autoSize:false,
                width:500,
                height:200
            });
        });
    })

    /*认领信息*/
    function inch(){
        var rows=$("#data").datagrid("getChecked");
        var customers=[];
        var loginId = '<?= $loginId ?>';
        var username='<?=\Yii::$app->user->identity->staff->staff_name?>';
        for(var x=0;x<rows.length;x++){
            customers.push(rows[x].cust_id);
        }
        var c = '<?= $e['sale_status'] ?>';
        var b = '<?= $isSuper ?>';
        if(rows.length==0){
            layer.alert("请选择一条客户信息!",{icon:2,time:5000});
        }else{
            var status = rows[0].personinch_status;
            var isManager=0;
            var n=rows.filter(function(row){
                var managers=row.manager.split(",");
                var managers=managers.filter(function(i){
                    return i==username;
                });
                return managers.length>0;
            });
            if(n.length==rows.length){
                status=10;
            }else{
                status=0;
            }
            var num = '';
            if(n.length == 1){
                num = 1
            }else{
                num = 0;
            }
            if(status == '0'){
                $("#inch").attr("href", '<?= Url::to(['/crm/crm-customer-info/person-inch']) ?>?customers='+customers.join(",")+"&status="+status +'&ml=' + num);
            }else{
                ccpichId = "<?=\Yii::$app->user->identity->staff_id?>";
                $("#inch").attr("href", '<?= Url::to(['/crm/crm-customer-info/person-inch']) ?>?customers='+customers.join(",")+'&ccpichId='+ccpichId+"&status="+status +'&ml=' + num);
            }
            $("#inch").fancybox({
                padding: [],
                fitToView: false,
                width: 450,
                height: 300,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe'
            });
        }
    };

    /*转招商*/
    function investment(){
        var a = $("#data").datagrid("getSelected");
        var b = $("#data").datagrid("getChecked");
        var url = "<?=Url::to(['turn-investment']) ?>";
        var c = "转招商";
        data_process(a,b,url,c);
    }

    function throwSea(){
        var a = $("#data").datagrid("getSelected");
        var b = $("#data").datagrid("getChecked");
        var url = "<?=Url::to(['throw-sea']) ?>";
        var c = "抛至公海";
        data_process(a,b,url,c);
    }

    function assign(){
        var b = $("#data").datagrid("getChecked");
        if(b.length == 0){
            layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
            return false;
        }
        $("#assign").fancybox({
            padding: [],
            fitToView: false,
            width: 700,
            height: 500,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href:'<?= Url::to(["assign"]) ?>'
        });
    }
</script>