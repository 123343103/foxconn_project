<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/6/30
 * Time: 15:54
 */
use yii\helpers\Html;
use yii\helpers\Url;
use app\classes\Menu;
$this->title="行程记录表";
$this->params['homeLike']=['label'=>'客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '行程记录表'];
?>
<div class="content">
    <?php  echo $this->render('_search', [
        'downList'=>$downList,
        'queryParam' => $queryParam
    ]); ?>

    <div class="table-head">
        <p class="head">行程记录表</p>
        <div class="float-right">
            <?= Menu::isAction('/crm/crm-person-record/index')?
            "<a id='export'>
                <div class='table-nav'>
                    <p class='export-item-bgc float-left'></p>
                    <p class='nav-font'>导出</p>
                </div>
            </a>
            <p class='float-left'>&nbsp;|&nbsp;</p>"
             :'' ?>
            <a href="<?= Url::to(['/index/index']) ?>">
                <div class='table-nav'>
                    <p class='return-item-bgc float-left'></p>
                    <p class='nav-font'>返回</p>
                </div>
            </a>
        </div>
    </div>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data">
        </div>
    </div>
</div>
<script>
    var result = eval(<?= $dataProvider ?>);
    $(function(){
        $("#data").datagrid({
//            url: "<?//=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>//",
            rownumbers: true,
            loadFilter:pagerFilter,
            method: "get",
            idField: "svp_id",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            selectOnCheck:false,
            checkOnSelect:true,
            pageSize:10,
            pageList:[10,20,30],
            columns: [[
                <?= $columns ?>
            ]],

            onLoadSuccess:function(data){
                setMenuHeight();
                datagridTip('#data');
                showEmpty($(this),data.total,0);
                $("#data").datagrid("loaded");
            },

        }).datagrid('loadData', result);
        /*导出*/
        $("#export").click(function () {
            layer.confirm("确定导出信息?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to(['index', 'export' => 1,
                            'CrmVisitRecordChildSearch[staff_name]' => !empty($queryParam) ? $queryParam['CrmVisitRecordChildSearch']['staff_name'] : null,
                            'CrmVisitRecordChildSearch[organization_code]' => !empty($queryParam) ? $queryParam['CrmVisitRecordChildSearch']['organization_code'] : null,
                            'CrmVisitRecordChildSearch[start]' => !empty($queryParam) ? $queryParam['CrmVisitRecordChildSearch']['start'] : null,
                            'CrmVisitRecordChildSearch[end]' => !empty($queryParam) ? $queryParam['CrmVisitRecordChildSearch']['end'] : null,
                            'CrmVisitRecordChildSearch[sale_area]' => !empty($queryParam) ? $queryParam['CrmVisitRecordChildSearch']['sale_area'] : null,
                            'CrmVisitRecordChildSearch[sts_id]' => !empty($queryParam) ? $queryParam['CrmVisitRecordChildSearch']['sts_id'] : null]) ?>") {
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
</script>
