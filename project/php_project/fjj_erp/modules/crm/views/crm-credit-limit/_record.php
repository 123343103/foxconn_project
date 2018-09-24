<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/8/2
 * Time: 9:48
 */
use yii\helpers\Url;
use yii\helpers\Html;
use app\classes\Menu;
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
$this->title = '信用额度申请履历';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '信用额度查询','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .space-10{
        width:100%;
        height:10px;
    }
    .width-80{
        width:80px;
    }
    .width-200{
        width:200px;
    }
</style>
<div class="content">
    <h1 class="head-first"><?= $this->title ?></h1>

    <div class="mb-30 create_content">
        <div class="mb-10">
            <label class="width-80 qlabel-align">客户全称</label><label>：</label>
            <span class="width-200 qvalue-align"><?=  $model['cust_sname'] ?></span>
            <label class="width-80 qlabel-align">客户代码</label><label>：</label>
            <span class="width-200 qvalue-align"><?=  $model['apply_code'] ?></span>
        </div>
        <?php $form = ActiveForm::begin([
            'action' => ['list?id='.$id],
            'method' => 'get',
        ]); ?>
        <div class="mb-10">
            <div class="inline-block">
                <label class="qlabel-align width-80">账信类型</label>
                <label>：</label>
                <select name="CrmCreditLimitSearch[credit_type]" class="width-200 qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['verifyType'] as $key => $val) {?>
                        <option value="<?= $val['business_type_id'] ?>" <?= isset($queryParam['CrmCreditLimitSearch']['credit_type'])&&$queryParam['CrmCreditLimitSearch']['credit_type']==$val['business_type_id']?"selected":null ?>><?= $val['business_value'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-80 qlabel-align">申请日期</label>
                <label>：</label>
                <input type="text" class="select-date width-200 Wdate qvalue-align" name="CrmCreditLimitSearch[start]" value="<?= $queryParam['CrmCreditLimitSearch']['start'] ?>" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd' })" onfocus="this.blur()">
                <label class="no-after">至</label>
                <input type="text" class="select-date width-200 Wdate qvalue-align" name="CrmCreditLimitSearch[end]" value="<?= $queryParam['CrmCreditLimitSearch']['end'] ?>" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd' })" onfocus="this.blur()">
            </div>

            <?= Html::submitButton('查询', ['class' => 'search-btn-blue', 'type' => 'submit']) ?>
            <?= Html::button('重置', ['class' => 'reset-btn-yellow', 'onclick'=>'window.location.href="'.Url::to(['list','id'=>$id]).'"']) ?>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="space-10"></div>
        <div class="table-head overflow-auto">
            <p class="head">信用额度维护表</p>
            <div class="float-right">
                <a id='export'>
                    <div class='table-nav'>
                        <p class='export-item-bgc float-left'></p>
                        <p class='nav-font'>导出</p>
                    </div>
                </a>
                <p class="float-left">&nbsp;|&nbsp;</p>
                <a href="<?= Url::to(['/index/index']) ?>">
                    <div class='table-nav'>
                        <p class='return-item-bgc float-left'></p>
                        <p class='nav-font'>返回</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="space-10"></div>
        <div id="data">

        </div>
    </div>
</div>
<script>
    $(function(){
        var id = '<?= $id ?>';
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "laid",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 15,
            pageList: [15, 30, 45],
            columns: [[
                <?= $columns ?>
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#data');
                showEmpty($(this),data.total,0);
                setMenuHeight();
            }
        });

        $("#export").click(function () {
            layer.confirm("确定导出信息?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to(['export-list', 'id' => $id,
                                    'CrmCreditLimitSearch[credit_type]' => !empty($queryParam) ? $queryParam['CrmCreditLimitSearch']['credit_type'] : null,
                                    'CrmCreditLimitSearch[start]' => !empty($queryParam) ? $queryParam['CrmCreditLimitSearch']['start'] : null,
                                    'CrmCreditLimitSearch[end]' => !empty($queryParam) ? $queryParam['CrmCreditLimitSearch']['end'] : null]) ?>") {
                        layer.closeAll();
                    } else {
                        layer.alert('导出账信申请信息错误!', {icon: 0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            );
        });
    })
    function btnPrints(){
        $('.create_content').jqprint({
            debug: false,
            importCSS: true,
            printContainer: true,
            operaSupport: false
        })
    }
</script>
