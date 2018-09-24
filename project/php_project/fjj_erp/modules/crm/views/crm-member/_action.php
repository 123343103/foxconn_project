<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/1/12
 * Time: 10:12
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \app\classes\Menu;
?>
<style>
    .menu-one span{
        padding: 0 2px 0 2px !important;
    }
    #m-data,#m-data2{
        width:88px !important;
    }
    #m-data a,#m-data2 a{
        width: 88px !important;
        display: block;
    }
</style>
<div class="table-head">
    <p class="head">会员列表</p>
    <div class="float-right">
        <?= Menu::isAction('/crm/crm-member/create')?
//            Html::a("<span class='text-center ml--5'>新增</span>",Url::to(['create']), ['id' => 'create'])
            "<a id='create' href='".Url::to(['create'])."'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>新增</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
        <?= Menu::isAction('/crm/crm-member/update')?
//            Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'update'])
            "<a id='update' class='display-none'>
                    <div class='table-nav'>
                        <p class='update-item-bgc float-left'></p>
                        <p class='nav-font'>修改</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>

        <?= Menu::isAction('/crm/crm-member/delete')?
//            Html::a("<span class='text-center ml--5'>刪除</span>", null,['onclick'=>'cancle()'])
            "<a id='delete' class='display-none' onclick='cancle();'>
                    <div class='table-nav'>
                        <p class='delete-item-bgc float-left'></p>
                        <p class='nav-font'>删除</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
        <?= Menu::isAction('/crm/crm-member/visit-create')?
//            Html::a("<span class='text-center ml--5'>回访</span>", null, ['id' => 'backVisit'])
            "<a id='backVisit' class='display-none'>
                    <div class='table-nav'>
                        <p class='setting1 float-left'></p>
                        <p class='nav-font'>回访</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
        <?= Menu::isAction('/crm/crm-member/create-reminders')?
//            Html::a("<span class='width-70 text-center ml--5'>提醒事项</span>", null, ['id' => 'reminders'])
            "<a id='reminders' class='display-none'>
                    <div class='table-nav'>
                        <p class='setbcg2 float-left'></p>
                        <p class='nav-font'>提醒事项</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
        <?= Menu::isAction('/crm/crm-member/send-message')?
            '<div class="float-left">
                <a href="javascript:void(0)" id="m-send" class="menu-one text-center ml--5 width-90">
                    <div class="table-nav">
                        <p class="setbcg6 float-left"></p>
                        <p class="nav-font"">即时通讯</p>
                    </div>
                    <p class="float-left">&nbsp;|&nbsp;</p>
                </a>
            </div>'
        :'' ?>
        <?php if(Menu::isAction('/crm/crm-member/turn-investment') || Menu::isAction('/crm/crm-member/turn-sales') || Menu::isAction('/crm/crm-member/import') || Menu::isAction('/crm/crm-member/index?export=1')){ ?>
        <div class="float-left">
            <a href="javascript:void(0)" id="m-deal" class="menu-one text-center ml--5 width-90">
                <div class='table-nav width-80'>
                    <p class='setbcg5 float-left'></p>
                    <p class='nav-font'>数据处理</p>
                </div>
                <p class="float-left">&nbsp;|&nbsp;</p>
            </a>
        </div>
        <?php } ?>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
    <div id="m-data" class="display-none">
        <?= Menu::isAction('/crm/crm-member/turn-investment')?'<div class="turn_investment display-none"><a onclick="investment()" class="menu-span"><span>转招商开发</span></a></div>' :'' ?>
        <?= Menu::isAction('/crm/crm-member/turn-sales')?'<div class="turn_sales display-none"><a onclick="sales()" class="menu-span"><span>转销售</span></a></div>':'' ?>
        <?= Menu::isAction('/crm/crm-member/import')?'<div><a  id="showDiv" class="menu-span"><span>批量导入</span></a></div>':'' ?>
        <?= Menu::isAction('/crm/crm-member/export')?'<div><a id="export" class="menu-span"><span>批量导出</span></a></div>':'' ?>
    </div>
    <div id="m-data2" class="display-none">
        <div><a id="sendMessage" onclick="sendMessage()" class="menu-span"><span>发短信</span></a></div>
        <div><a id="sendEmail" onclick="sendEmail()" class="menu-span"><span>发邮件</span></a></div>
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

    $(function () {
        $('#m-deal').menubutton({
            menu: '#m-data',
            hasDownArrow:false
        });
        $('#m-send').menubutton({
            menu: '#m-data2',
            hasDownArrow:false
        });
        $('.menu-one').removeClass("l-btn l-btn-small l-btn-plain");
        $('.menu-one').find("span").removeClass("l-btn-left l-btn-text");

        /*会员信息导出 start*/
        $("#export").click(function () {
            layer.confirm("确定导出会员信息?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to(['export', 'CrmCustomerInfoSearch[member_comp]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['member_comp'] : null, 'CrmCustomerInfoSearch[member_name]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['member_name'] : null, 'CrmCustomerInfoSearch[member_regphone]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['member_regphone'] : null, 'CrmCustomerInfoSearch[member_regweb]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['member_regweb'] : null, 'CrmCustomerInfoSearch[member_type]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['member_type'] : null, 'CrmCustomerInfoSearch[member_source]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['member_source'] : null, 'CrmCustomerInfoSearch[member_reqflag]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['member_reqflag'] : null, 'CrmCustomerInfoSearch[member_visitflag]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['member_visitflag'] : null]) ?>") {
                        layer.closeAll();
                    } else {
                        layer.alert('导出会员信息错误!', {icon: 0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            );
        });
        /*会员信息导出 end*/
        /*会员信息导入 start*/
        ajaxSubmitForm($("#fileForm"));
//        $("#showDiv").fancybox({
//            padding: [],
//            centerOnScroll: true,
//            titlePosition: 'over',
//            title: '数据导入导出'
//        });
        //数据导入
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

        $('form #fileForm').on('beforeSubmit', function () {
            var form = new FormData($("#fileForm"));
            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: 'post',
                data: form,
                processData: false,
                contentType: false,
                success: function (data) {
//                    console.log(data);
                    $("#resultData").html(data);
                },
                error: function (info) {
                    alert("数据发生错误,重新上传");
                }
            });
        }).on('submit', function (e) {
            e.preventDefault();
//                console.log(e);
        });
        /*会员信息导入 end*/
    })

    /*转招商*/
    function investment(){
        var a = $("#data").datagrid("getSelected");
        var b = $("#data").datagrid("getChecked");
        var url = "<?=Url::to(['turn-investment']) ?>";
        var c = "转招商开发";
        data_process(a,b,url,c);
    }

    /*转销售*/
    function sales(){
        var a = $("#data").datagrid("getSelected");
        var b = $("#data").datagrid("getChecked");
        var url = "<?=Url::to(['turn-sales']) ?>";
        var c = "转销售";
        data_process(a,b,url,c);
    }
    /*发送邮件*/
    function sendEmail(){
        $.fancybox({
            width:800,
            height:600,
            padding:0,
            autoSize:false,
            type:"iframe",
            href:"<?=Url::to(['send-message','type'=>2])?>"
        });
    }

    /*发短信*/
    function sendMessage() {
        $.fancybox({
            width:800,
            height:600,
            autoSize:false,
            padding:0,
            type:"iframe",
            href:"<?=Url::to(['send-message','type'=>1])?>"
        });
    }

</script>