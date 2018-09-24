<?php
/**
 * User: G0007903
 * Date: 2017/10/25
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
$this->params['homeLike'] = ['label' => '人事管理'];
$this->params['breadcrumbs'][] = ['label' => '问卷列表','url'=>"index"];
$this->params['breadcrumbs'][] = ['label' => '答卷列表'];
$this->title = '答卷列表';/*BUG修正 增加title*/
?>
<style>
    .mb-10{
        margin-bottom: 10px;
    }
    .m-l{
        float: left;
    }
    .value-width{
        width:150px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .space-30{
        width: 100%;
        height: 30px;
    }
    .space-10{
        width: 100%;
        height: 10px;
    }
</style>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]);?>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
    </h1>
<div class="mb-10 m-l">
    <label class="qlabel-align">工号:</label>
    <input class="value-width value-align easyui-validatebox" type="text"
    data-options="required:'true',validType:'maxLength[200]'" maxlength="200" id="staff_code">
</div>
    <div class="mb-10 m-l ml-20">
        <label class="qlabel-align">姓名:</label>
        <input class="value-width value-align easyui-validatebox" type="text"
               data-options="required:'true',validType:'maxLength[200]'" maxlength="200" id="staff_name">
    </div>
    <div class="mb-10 m-l ml-20">
        <label class="qlabel-align">部门:</label>
        <input class="value-width value-align easyui-validatebox" type="text"
               data-options="required:'true',validType:'maxLength[200]'" maxlength="200" id="dpt_name">
    </div>
    <button id="search" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
    <button id="reset" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
<div class="space-30"></div>
    <div class="table-content">
    <div class="table-head">
        <p class="head" style="margin-left: 20px">问卷主题:<span style="color:#1f7ed0"><?=$data["invst_subj"]?></span></p>
        <a href="<?=Url::to('index')?>" id="return" style="float: right;margin-top: 3px;margin-right: 70px">
            <p class="return-item-bgc"></p>
            <p>返回列表</p>
        </a>
        <p style="float: right;margin-top: 3px;display: none">&nbsp;|&nbsp;&nbsp;</p>
        <a id="export" style="float: right;margin-right: 10px;display: none">
        <p class="export-item-bgc"></p>
        <p>导出</p>
        </a>
    </div>
        <div class="space-10"></div>
</div>
    <div style="margin-left: 22px">
        <div id="data" style="width: 900px;"></div>
</div>
    <script>
        $(function() {
            $("#data").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
//                queryParams:{"id":<?//=$data['answ_id']?>//},
                rownumbers: true,
                method: "get",
                idField: "answ_id",
//                queryParams:{"id":<?//=$data['answ_id']?>//},
                loadMsg: "加载数据请稍候。。。",
                pagination: true,
                singleSelect: true,
                checkOnSelect: false,
                selectOnCheck: false,
                columns: [[
//                    {field: "answ_id", title: "答卷ID", width: 286, hidden: 'true'},
                    {field: "staff_code", title: "工号",width: 100},
//                    {field: "invst_subj", title: "主题",hidden:true},
//                    {field: "answ_id", title: "ID", width: 286, hidden: 'true'},
                    {field: "staff_name", title: "姓名", width: 100},
                    {field: "dpt_name", title: "部门", width: 358},
                    {field: "answ_datetime", title: "回答时间", width: 150},
                    {field: "opper", title: "操作", width: 120, formatter: function (value, rowData, rowIndex) {
//                        console.log(value);
//                        console.log(rowData);
//                        console.log(rowIndex);
                        var str="<i>";
                        str+="<a title='查看详情' href='<?=Url::to('view')?>?id="+rowData['invst_id']+"&answ_id="+rowData['answ_id']+"'>查看详情</a>";
                        str+="</i>";
                        return str;
                    }}
                ]],
                onLoadSuccess: function (data) {
                    if (data.total!=0){
                        $("#export").show().next().show();
                    }
                    $('.datagrid-header-row td').eq(0).html('&nbsp;序号').css('font-size', '13px').css('color','white');
                    datagridTip($("#data"));
                    showEmpty($(this), data.total,1);
                }
            });
        });
        function loadData(){
            $("#data").datagrid('load',{
                "staff_code":$("#staff_code").val(),
                "staff_name":$("#staff_name").val(),
                "dpt_name":$("#dpt_name").val(),
                "invst_subj":$("#invst_subj").val()
            }).datagrid('clearSelections').datagrid('clearChecked');
        }
        $(function() {
            //查询
            $("#search").click(function () {
                loadData();
            });
            $(document).keydown(function (event) {
                if (event.keyCode == 13) {
                    loadData();
                }
            });
           //导出
            $('#export').click(function () {
                var id = '<?=$data["invst_id"]?>';
                var index = layer.confirm("确定要导出人员信息?",
                    {   fix:false,
                        btn: ['确定', '取消'],
                        icon: 2
                    },
                    function(){
                        layer.closeAll();
                        var url="<?=Url::to(['export'])?>";
                        url+="?id=<?=$data['invst_id']?>";
                        url+="&subj=<?=$data['invst_subj']?>";
                        url+='&staff_code='+$("#staff_code").val();
                        url+='&staff_name='+$("#staff_name").val();
                        url+='&dpt_name='+$("#dpt_name").val();
//                        alert(url);
                        location.href=url;
                    },
                    function () {
                        layer.closeAll();
                    }
                )
            });
            //重置
            $("#reset").click(function () {
                $("#update").hide().next().hide();
                $("#cancel").hide().next().hide();
                $("#stop").hide().next().hide();
                $("#add").hide().next().hide();
                $("input,select").val("");
                loadData();
            });
        });
    </script>