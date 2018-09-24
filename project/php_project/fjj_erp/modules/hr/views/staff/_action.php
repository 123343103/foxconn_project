<?php
/**
 * User: F3859386
 * Date: 2016/9/13
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\classes\Menu;

?>

<div class="table-head">
    <p class="head">人员信息</p>
    <div class="float-right">
        <?= Menu::isAction('/hr/staff/create') ?
//            Html::a("<span>新增</span>",Url::to(['create']), ['id' => 'create'])
            "<a id='create' href='" . Url::to(['create']) . "'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>新增</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            : '' ?>
        <?= Menu::isAction('/hr/staff/update') ?
//            Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'update'])
            "<a id='update'>
                    <div class='table-nav'>
                        <p class='update-item-bgc float-left'></p>
                        <p class='nav-font'>修改</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            : '' ?>
        <?= Menu::isAction('/hr/staff/view') ? "<a id='viewOne'>
<div class='table-nav'>
<p class='details-item-bgc float-left'></p>
<p class='nav-font'>详情</p>
</div>
<p class=\"float-left\">&nbsp;|&nbsp;</p>
</a>" : '' ?>
        <?= Menu::isAction('/hr/staff/insert-excel-staff') ? "<a id='importDiv'>
<div class='table-nav'>
<p class='icon-upload float-left'>
</p>
<p class='nav-font'>导入</p>
</div>
<p class='float-left'>&nbsp;|&nbsp;</p>
</a>" : '' ?>
        <?= Menu::isAction('/hr/staff/export')?
            "<a id='export'>
<div class='table-nav'>
<p class='export-item-bgc float-left'></p>
<p class='nav-font'>导出</p>
</div>
<p class='float-left'>&nbsp;|&nbsp;</p>
</a>"
            :'' ?>
        <?= Menu::isAction('/hr/staff/delete') ?
//            Html::a("<span class='text-center ml--5'>删除</span>", null,['onclick'=>'cancle()'])
            "<a id='delete'>
                    <div class='table-nav'>
                        <p class='delete-item-bgc float-left'></p>
                        <p class='nav-font'>删除</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            : '' ?>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
</div>

<script>
    $(document).ready(function () {

        submitForm($("#fileForm"));

        $("#showDiv").fancybox({
            padding: [],
            height: '500',
            centerOnScroll: true,
            titlePosition: 'over'
//            title:'数据导入'
        });
        //数据导入
        $("#importDiv").click(function () {
            $.fancybox({
                type: "iframe",
                href: "<?=Url::to(['import'])?>",
                padding: 0,
                autoSize: false,
                width: 500,
                height: 200
            });
        });

        $('#export').click(function () {
            var index = layer.confirm("确定要导出人员信息?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to(['export', $search])?>") {
                        layer.closeAll();
                    } else {
                        layer.alert('导出人员信息发生错误', {icon: 0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            )
        });

        $('form #fileForm').on('beforeSubmit', function () {
            var form = new FormData($("#fileForm"));
            var $form = $(this);
            $.ajax({
                url: $form.attr('action'),
                type: 'get',
                data: form,
                processData: false,
                contentType: false,
                success: function (data) {
                    $("#resultData").html(data);
                },
                error: function (info) {
                    alert("数据发生错误,重新上传");
                }
            });
        }).on('submit', function (e) {
            e.preventDefault();
        });
    })

    function submitForm($form) {

        $($form).on("beforeSubmit", function () {
            if (!$(this).form('validate')) {
                return false;
            }
            if (navigator.userAgent.indexOf("MSIE 9.0") > 0) {
                var filepath = $("#uploadform-file").val();
                var extStart = filepath.lastIndexOf(".");
                var ext = filepath.substring(extStart, filepath.length).toUpperCase();
                if (ext == '') {
                    $(".error-notice").html('请上传一个文件。')
                    return false;
                }
                if (ext != ".XLS" && ext != ".XLSX") {
                    $(".error-notice").html('只允许使用以下文件扩展名的文件：xls, xlsx。')
                    return false;
                }
            }
            $(".fileButton").prop("disabled", true);
            var options = {
                dataType: 'json',
                success: function (data) {
                    if (data.flag == 1) {
                        layer.alert(data.msg, {
                            icon: 1,
                            end: function () {
                                if (data.url != undefined) {
                                    parent.location.href = data.url;
                                }

                            }
                        });
                    }
                    if (data.flag == 0) {
                        layer.alert(data.msg, {
                            icon: 2
                        });
                        $("button[type='submit']").prop("disabled", false);
                    }
                },
                error: function (data) {
                    layer.alert(data.responseText, {
                        icon: 2
                    });
                }
            };

            $($form).ajaxSubmit(options);
            return false;
        })
    }
</script>
