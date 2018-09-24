<?php
/**
 * User: F1676624 Date: 2016/12/2
 */
use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '商品库管理', 'url' => ['/']];
$this->params['breadcrumbs'][] = ['label' => '商品库','url'=>['index']];
$this->params['breadcrumbs'][] = ['label' => '查看商品'];
$this->title = '查看商品';/*BUG修正 增加title*/
?>
<div class="content">
    <div class="mb-30">
        <div class="table-head">
            <p>商品库详情</p>
            <p class="float-right">
                <a id="edit"><span>编辑</span></a>
                <a id="back" onclick="history.go(-1)"><span>返回</span></a>
            </p>
        </div>
        <div class="mb-10"></div>
        <div class="head-second"><p>商品详情</p></div>


        <div class="space-30"></div>



        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">料号：</td>
                <td width="30%" class="no-border vertical-center"><?= $model->pdt_no ?></td>
                <td width="10%" class="no-border vertical-center">品名：</td>
                <td width="20%" class="no-border vertical-center"><?= $model->pdt_name ?></td>
                <td width="10%" class="no-border vertical-center">品牌：</td>
                <td width="20%" class="no-border vertical-center"><?= $model->brand_name ?></td>
            </tr>
        </table>


        <div class="space-30"></div>


        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">商品类别：</td>
                <td width="30%" class="no-border vertical-center">
                    <?= $model->type_1 . '->' . $model->type_2 . '->' . $model->type_3; ?>
                </td>
                    <td width="10%" class="no-border vertical-center">当前状态：</td>
                <td width="20%" class="no-border vertical-center"></td>
                <td width="10%" class="no-border vertical-center">库存：</td>
                <td width="20%" class="no-border vertical-center"></td>
            </tr>
        </table>

        <div class="space-30"></div>

        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">规格：</td>
                <td width="30%" class="no-border vertical-center"><?=$model->tp_spec; ?></td>
                <td width="10%" class="no-border vertical-center">单位：</td>
                <td width="20%" class="no-border vertical-center"><?=$model->unit;?></td>
                <td width="10%" class="no-border vertical-center">商品属性：</td>
                <td width="20%" class="no-border vertical-center"><?=$model->pdt_attribute; ?></td>
            </tr>
        </table>



        <div class="space-30"></div>

        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">材料：</td>
                <td width="30%" class="no-border vertical-center">
                </td>
                <td width="10%" class="no-border vertical-center">重量：</td>
                <td width="20%" class="no-border vertical-center"><?=$model->pdt_weight;?></td>
                <td width="10%" class="no-border vertical-center">运输方式：</td>
                <td width="20%" class="no-border vertical-center"></td>
            </tr>
        </table>



        <div class="space-30"></div>

        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">富贸料号：</td>
                <td width="30%" class="no-border vertical-center">
                </td>
                <td width="10%" class="no-border vertical-center">商品定位：</td>
                <td width="20%" class="no-border vertical-center"></td>
                <td width="10%" class="no-border vertical-center">供应商名称：</td>
                <td width="20%" class="no-border vertical-center"></td>
            </tr>
        </table>



        <div class="space-30"></div>




        <div class="space-30"></div>

        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">适用产业：</td>
                <td width="30%" class="no-border vertical-center">
                </td>
                <td width="10%" class="no-border vertical-center">包装规格：</td>
                <td width="20%" class="no-border vertical-center"></td>
                <td width="10%" class="no-border vertical-center">采购属性：</td>
                <td width="20%" class="no-border vertical-center"></td>
            </tr>
        </table>





        <div class="space-30"></div>

        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">存放条件：</td>
                <td width="30%" class="no-border vertical-center">
                </td>
                <td width="10%" class="no-border vertical-center">采购周期：</td>
                <td width="20%" class="no-border vertical-center"></td>
                <td width="10%" class="no-border vertical-center">保质期：</td>
                <td width="20%" class="no-border vertical-center"></td>
            </tr>
        </table>



        <div class="space-30"></div>



        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">是否批次管理：</td>
                <td width="30%" class="no-border vertical-center"><?=$model->pdt_isbatch; ?></td>
                <td width="10%" class="no-border vertical-center">默认仓库：</td>
                <td width="20%" class="no-border vertical-center"></td>
                <td width="10%" class="no-border vertical-center">庫存安全量：</td>
                <td width="20%" class="no-border vertical-center"><?=$model->pdt_safeqty; ?></td>
            </tr>
        </table>



        <div class="space-30"></div>



        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">是否线上销售：</td>
                <td width="30%" class="no-border vertical-center">
                </td>
                <td width="10%" class="no-border vertical-center">是否代理：</td>
                <td width="20%" class="no-border vertical-center"></td>
                <td width="10%" class="no-border vertical-center">定价类型：</td>
                <td width="20%" class="no-border vertical-center"></td>
            </tr>
        </table>



        <div class="space-30"></div>



        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">商品经理人：</td>
                <td width="30%" class="no-border vertical-center"><?= $model->pdt_manager ?></td>
                <td width="10%" class="no-border vertical-center">销售区域：</td>
                <td width="20%" class="no-border vertical-center"><?= $model->salearea ?></td>
                <td width="10%" class="no-border vertical-center">是否客制化：</td>
                <td width="20%" class="no-border vertical-center"><?= $model->iskz==1?"是":"否"; ?></td>
            </tr>
        </table>



        <div class="space-30"></div>



        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">开发中心：</td>
                <td width="30%" class="no-border vertical-center"><?= $model->center ?></td>
                <td width="10%" class="no-border vertical-center">开发部：</td>
                <td width="20%" class="no-border vertical-center"><?= $model->applydep ?></td>
                <td width="10%" class="no-border vertical-center">Community：</td>
                <td width="20%" class="no-border vertical-center"><?= $model->applydep ?></td>
            </tr>
        </table>



        <div class="space-30"></div>



        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">法务风险等级：</td>
                <td width="30%" class="no-border vertical-center"><?= $model->risk_level ?></td>
                <td width="10%" class="no-border vertical-center">是否拳头商品：</td>
                <td width="20%" class="no-border vertical-center"><?= $model->istitle==1?"是":"否"; ?></td>
                <td width="10%" class="no-border vertical-center">交货条件：</td>
                <td width="20%" class="no-border vertical-center"></td>
            </tr>
        </table>


        <div class="space-30"></div>



        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">付款条件：</td>
                <td width="30%" class="no-border vertical-center"></td>
                <td width="10%" class="no-border vertical-center">L/T（天）：</td>
                <td width="50%" class="no-border vertical-center"></td>
            </tr>
        </table>



        <div class="space-30"></div>



        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">备注：</td>
                <td width="90%" class="no-border vertical-center"></td>
            </tr>
        </table>
    </div>
    <div class="head-second"><p>价格详情</p></div>
    <div id="load-content" class="overflow-auto"></div>
    <div class="space-30"></div>
</div>
<script>
    $(function () {
        var id = getUrlParam('id');
        $('#load-content').load("<?=Url::to(['/ptdt/product-library/load-price']) ?>?id=" + id, function () {
            setMenuHeight();
        });
    });
    $("#edit").on("click", function () {
        var id = getUrlParam('id');
        window.location.href = "<?=Url::to(['edit'])?>?id=" + id;
    });
    //获取参数
    function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = window.location.search.substr(1).match(reg); //匹配目标参数
        if (r != null) return unescape(r[2]);
        return null; //返回参数值
    }
</script>
