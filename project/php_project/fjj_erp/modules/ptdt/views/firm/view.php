<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

$this->title = '查看厂商信息';
$this->params['homeLike'] = ['label' => '商品开发管理'];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '厂商信息列表'];
$this->params['breadcrumbs'][] = ['label' => '厂商信息详情'];
?>
<div class="content">

    <h1 class="head-first">
        厂商信息详情
        <span class="head-code">编号：<?= $model->firm_code ?></span>
    </h1>
    <div class="mb-30">
        <div class="border-bottom mb-20">
            <?= Menu::isAction('/ptdt/firm/update') ? Html::button('修改', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["update"]) . '?id=' . $model->firm_id . '\'']) : '' ?>
            <?= Menu::isAction('/ptdt/firm/delete') ? Html::button('删除', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'id' => 'delete']) : '' ?>
            <?= Menu::isAction('/ptdt/firm/index') ? Html::button('切换列表', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) : '' ?>
            <?= Menu::isAction('/ptdt/firm/create') ? Html::button('新增厂商', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["create"]) . '\'']) : '' ?>
            <?= Menu::isAction('/ptdt/visit-plan/add') ? Html::button('新增计划', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'onclick' => 'window.location.href=\'' . Url::to(["/ptdt/visit-plan/add"]) . '?id=' . $model->firm_id . '\'']) : '' ?>
            <?= Menu::isAction('/ptdt/visit-resume/add') ? Html::button('新增拜访履历', ['class' => 'button-blue mb-10', 'style' => 'width:90px;', 'onclick' => 'window.location.href=\'' . Url::to(['/ptdt/visit-resume/add']) . '?firmId=' . $model->firm_id . '\'']) : '' ?>
            <?= Menu::isAction('/ptdt/firm-negotiation/create') ? Html::button('新增谈判', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["/ptdt/firm-negotiation/create"]) . "?firmId=" . $model->firm_id . '\'']) : '' ?>

        </div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">厂商名称：</td>
                <td width="35%" class="no-border vertical-center"><?= $model->firm_sname ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">简称：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $model->firm_shortname ?></td>
            </tr>
        </table>
        <div class="space-40"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">英文名称：</td>
                <td width="35%" class="no-border vertical-center"><?= $model->firm_ename ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">英文简称：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $model->firm_brand ?></td>
            </tr>
        </table>
        <div class="space-40"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">来源：</td>
                <td width="35%" class="no-border vertical-center"><?= $model->firmSource ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">类型：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $model->firmType ?></td>
            </tr>
        </table>
        <div class="space-40"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">地位：</td>
                <td width="35%" class="no-border vertical-center"><?= $model->firmPosition ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">是否为集团供应商：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $model->firm_issupplier == 1 ? '是' : '否'; ?></td>
            </tr>
        </table>
        <div class="space-40"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">公司地址：</td>
                <td width="87%" class="no-border vertical-center"><?= $disComAddress ?></td>
            </tr>
        </table>

        <div class="space-40"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">厂商负责人：</td>
                <td width="18%" class="no-border vertical-center"><?= $model->firm_compprincipal ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">公司联系电话：</td>
                <td width="18%"
                    class="no-border vertical-center"><?= $model->firm_comptel ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">邮箱：</td>
                <td width="18%"
                    class="no-border vertical-center"><?= $model->firm_compmail ?></td>
            </tr>
        </table>
        <div class="space-40"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">厂商联系人：</td>
                <td width="18%" class="no-border vertical-center"><?= $model->firm_contaperson_mail ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">联系人电话：</td>
                <td width="18%"
                    class="no-border vertical-center"><?= $model->firm_contaperson_tel ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">邮箱：</td>
                <td width="18%"
                    class="no-border vertical-center"><?= $model->firm_contaperson_mail ?></td>
            </tr>
        </table>

        <div class="space-40"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">分级分类：</td>
                <td width="87%" class="no-border vertical-center"><?= $model->category ?></td>
            </tr>
        </table>

        <div class="space-40"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">备注：</td>
                <td width="87%" class="no-border vertical-center"><?= $model->firm_remark1 ?></td>
            </tr>
        </table>
    </div>
    <div>
        <h2 class="head-second">
            创建人信息
        </h2>
        <div class="space-10 "></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">工号：</td>
                <td width="35%" class="no-border vertical-center"><?= $model->createBy->code ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">姓名：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $model->createBy->name ?></td>
            </tr>
        </table>

        <div class="space-30"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">部门：</td>
                <td width="35%" class="no-border vertical-center"><?= $model->createBy->organization_code ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">邮箱：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $model->createBy->mail ?></td>
            </tr>
        </table>

        <div class="space-30"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">创建时间：</td>
                <td width="35%" class="no-border vertical-center"><?= $model->create_at ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">联系方式：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $model->createBy->mobile ?></td>
            </tr>
        </table>

    </div>
    <div class="space-40 "></div>
    <button type="button" class="button-white-big ml-400" id="submit">
        返回
    </button>

    <script>
        /*window.onload = function(){
         console.log(3333);
         var textWidth = function(text){
         console.log(text);
         var sensor = $('<pre>'+ text +'</pre>').css({display: 'none'});
         console.log(sensor);
         $('body').append(sensor);
         var width = sensor.width();
         sensor.remove();
         return width;
         };
         //input宽度自适应
         $("input").unbind('keydown').bind('keydown', function(){
         $(this).width(textWidth($(this).val()));
         console.log(00);
         });
         console.log(textWidth());
         }*/
        $(function () {
            $("#submit").click(function () {
                console.log(11);
                window.location.href = "<?=Url::to(['index'])?>";

            });

            $("#delete").on("click", function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {"id": <?= $model->firm_id ?>},
                    url: "<?=Url::to(['/ptdt/firm/delete-count']) ?>",
                    success: function (msg) {
                        if (msg === 'false') {
                            layer.alert('无法删除', {icon: 2})
                        } else {
                            var index = layer.confirm("确定要删除这条记录吗?",
                                {
                                    btn: ['确定', '取消'],
                                    icon: 2
                                },
                                function () {
                                    $.ajax({
                                        type: "get",
                                        dataType: "json",
                                        async: false,
                                        data: {"id": <?= $model->firm_id ?>},
                                        url: "<?=Url::to(['delete']) ?>",
                                        success: function (msg) {
                                            if (msg.flag === 1) {
                                                layer.alert(msg.msg, {
                                                    icon: 1, end: function () {
                                                        location.href = '<?= Url::to(['index']) ?>'
                                                    }
                                                });
                                            } else {
                                                layer.alert(msg.msg, {icon: 2})
                                            }
                                        },
                                        error: function (msg) {
                                            layer.alert(msg.msg, {icon: 2})
                                        }
                                    })
                                },
                                function () {
                                    layer.closeAll();
                                }
                            )
                        }
                    }
                })

            });
        });
    </script>
