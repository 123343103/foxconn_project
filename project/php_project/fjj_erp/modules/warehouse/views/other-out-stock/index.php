<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/13
 * Time: 上午 11:31
 */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\classes\Menu;
use app\assets\JeDateAsset;
JeDateAsset::register($this);
$this->title='其他出库单列表';
$this->params['homeLike']=['label'=>'仓储物流管理'];
$this->params['breadcrumbs'][]=$this->title;
?>
<style>
    .width-80 {
        width: 80px;
    }

    .width-150 {
        width: 150px;
    }
    .width-120 {
        width: 120px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .ml-10{
        margin-left: 10px;
    }
</style>
<div class="content">
<?php ActiveForm::begin([
        "method"=>"GET"
]);?>
    <div class="mb-20">
        <label for="" class="width-80">出库单号：</label>
        <input type="text" class="width-120" name="o_whcode" value="<?=\Yii::$app->request->get('o_whcode')?>">
        <label for="" class="width-80">出库单状态：</label>
        <?=Html::dropDownList("o_whstatus",\Yii::$app->request->get('o_whstatus'),$options["o_whstatus"],["prompt"=>"请选择","class"=>"width-120"])?>
        <label for="" class="width-80">出货日期：</label>
        <input type="text" id="start_time" readonly="readonly" class="width-120" name="o_start_date" value="<?=\Yii::$app->request->get('o_start_date')?>">
        至
        <input type="text" id="end_time" readonly="readonly" class="width-120" name="o_end_date" value="<?=\Yii::$app->request->get('o_end_date')?>">
    </div>

    <div style="height: 10px;"></div>
    <div class="mb-20">
        <label for="" class="width-80">单据类型：</label>
        <?=Html::dropDownList("o_whtype",$model['o_whtype'],$options["o_whtype"],["prompt"=>"请选择","class"=>"width-120"])?>
        <label for="" class="width-80">法人：</label>
        <?=Html::dropDownList("bs_cmp",\Yii::$app->request->get('bs_cmp'),$options["company"],["prompt"=>"请选择","class"=>"width-120"])?>
        <label for="" class="width-80">申请部门：</label>
        <?=Html::dropDownList("organization",\Yii::$app->request->get('organization'),$options["organization"],["prompt"=>"请选择","class"=>"width-120"])?>
        <label for="" class="width-80">出 仓 仓 库： </label>
        <select name="o_whid" id="wh_name" class="width-120 easyui-validatebox" data-options="required:true">
            <option value="0" data-code="0">请选择</option>
            <?php foreach ($options['warehouse'] as $key=>$val){?>
                <option value="<?= $val['wh_id']?>" data-code="<?= $val['wh_code']?>" <?= !empty(\Yii::$app->request->get('o_whid'))&&\Yii::$app->request->get('o_whid')==$val['wh_id']?"selected":null ?>><?= $val['wh_name']?></option>
            <?php }?>
        </select>
        <button class="search-btn-blue ml-20" type="submit">查询</button>
        <button class="reset-btn-yellow ml-10" type="button" onclick="window.location.href='<?=Url::to(['index'])?>'">重置</button>
    </div>
    <div style="height: 10px;"></div>
    <?php ActiveForm::end();?>

    <?=\app\widgets\toolbar\Toolbar::widget([
        'title'=>'出库单列表',
        'menus'=>[
            [
                'label'=>'新增',
                'icon'=>'add-item-bgc',
                'url'=>Url::to(['create']),
                'dispose'=>'default'
            ],
            [
                'label'=>'修改',
                'hide'=>true,
                'icon'=>'update-item-bgc',
                'options'=>['id'=>'edit','style'=>['display'=>'none']]
            ],
            [
                'label'=>'送审',
                'hide'=>true,
                'icon'=>'audit-item-bgc',
                'options'=>['id'=>'check','style'=>['display'=>'none']]
            ],
            [
                'label'=>'取消出库',
                'hide'=>true,
                'icon'=>'setting11',
                'options'=>['id'=>'cancel','style'=>['display'=>'none']]
            ],
            [
                'label'=>'导出',
                'icon'=>'export-item-bgc',
                'options'=>['id'=>'export']
            ],
            [
                'label'=>'返回',
                'icon'=>'return-item-bgc',
                'url'=>Url::home(),
                'dispose'=>'default'
            ]
        ]
    ]);?>
    <div style="height: 10px;"></div>
    <div id="data" style="width:100%;"></div>
    <div id="child_table_title"></div>
    <div id="child_table"></div>
    
    <div class="space-30"></div>

    <div id="tip" style="width:300px;height:250px;display: none;">
        <h3 class="head-first">提示消息</h3>
        <div class="content">
            <p class="mb-10">取消原因：</p>
            <div id="cancel-resaon"></div>
            <div class="text-center mt-20"><button class="button-white" onclick="$.fancybox.close()">关闭</button></div>
        </div>
    </div>
</div>
<script>
    $(function(){
        //管控开始时间
        $("#start_time").click(function () {
            WdatePicker({
                skin: 'whyGreen',
//                minDate: '%y-%M-{%d+1}',
                dateFmt: 'yyyy-MM-dd',
                isShowToday: true,
                maxDate: '#F{$dp.$D(\'end_time\');}'
            })
        });
        //结束时间
        $("#end_time").click(function () {
            if ($("#start_time").val() === '') {
                layer.alert('请先选择开始时间', {icon: 2});
                return false;
            }
            WdatePicker({
                skin: 'whyGreen',
                isShowToday: true,
                dateFmt: 'yyyy-MM-dd',
                minDate: '#F{$dp.$D(\'start_time\');}'
            })
        });
        var $childTableTitle = $("#child_table_title");
        var $childTable = $("#child_table");
        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
        $("#data").datagrid({
            url:"<?=Url::current()?>",
            rownumbers: true,
            method: "get",
            idField: "o_whpkid",
            loadMsg: "加载数据请稍候。。。",
            pagination:true,
            pageSize:10,
            pageList:[10,20,30],
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            columns:[[
                {field: 'ck', checkbox: true},
                {field: "o_whcode", width: 150, title: "出库单号"},
                {field: "o_whstatus", width: 100, title: "出库单状态",formatter:function(value,rowData){
                    if (rowData.o_whstatus == "已取消") {
                    return "<a class='tip' style='color:#cd0a0a'>已取消</a>";
                    }
                    else
                    {
                        return rowData.o_whstatus;
                    }
                }},
                {field: "bsp_svalue", width: 100, title: "单据类型"},
                {field: "company_name", width: 100, title: "法人"},
                {field: "o_wh_name", width: 100, title: "出仓仓库"},
                {field: "i_wh_name", width: 100, title: "入仓仓库"},
                {field: "plan_odate", width: 100, title: "出货日期"},
                {field: "organization_name", width: 100, title: "申请部门"},
                {field: "create_name", width: 100, title: "申请人"},
                {field: "creat_date", width: 100, title: "制单日期"},
                {field:"action",title:"操作",formatter:function(value,rowData){
                    var str = "<i>";
                    if (rowData.o_whstatus == "待提交" || rowData.o_whstatus == "驳回") {
                        <?php if(Menu::isAction('/warehouse/allocation/edit')){?>
                        str += "<a class='icon-check-minus icon-large' style='margin-right:15px;' title='取消' onclick='cancel("+rowData.o_whpkid+");event.stopPropagation();'></a>";
                        <?php }?>
                        <?php if(Menu::isAction('/warehouse/allocation/delete')){?>
                        str += "<a class='icon-edit icon-large' style='margin-right:15px;' title='修改' onclick='location.href=\"<?=Url::to(['edit'])?>?id=" + rowData.o_whpkid + "\";event.stopPropagation();'></a>";
                        <?php }?>
                    }
//                    str += "<a class='icon-eye-open icon-large' title='查看' onclick='location.href=\"<?//=Url::to(['views'])?>//?id=" + value + "\";event.stopPropagation();'></a>";
                    str += "</i>";
                    return str;
                }}
            ]],
            onSelect:function(value,row){
                var index = $("#data").datagrid("getRowIndex", row.o_whpkid);
                $(".datagrid-menu .hide").removeClass("hide");
                $("#cancel-resaon").html(row.remarks);
                $('#data').datagrid("uncheckAll");
                if (isCheck && !isSelect && !onlyOne) {
                    var a = $("#data").datagrid("getChecked");
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                    isCheck = false;
                }
                $('#data').datagrid('checkRow', index);
                if (row.o_whstatus == '待提交' || row.o_whstatus == '驳回') {
                    $("#edit").show();
                    $("#cancel").show();
                    $("#check").show();
                }
                else
                {
                    $("#edit").hide();
                    $("#cancel").hide();
                    $("#check").hide();
                }
                $childTableTitle.addClass("table-head mb-5 mt-20").html("<p>商品信息</p>").show().next().show();
                $childTable.datagrid({
                    url: "<?=Url::to(['load-product'])?>",
                    queryParams: {"id": row.o_whpkid},
                    rownumbers: true,
                    method: "get",
                    idField: "o_whdtid",
                    singleSelect: true,
//                    pagination:true,
                    columns: [[
                        {field: "part_no", title: "料号", width: "150"},
                        {field: "pdt_name", title: "品名", width: "150"},
                        {field: "tp_spec", title: "规格/型号", width: "150"},
                        {field: "st_code", title: "储位", width: "150"},
                        {field: "batch_no", title: "批次", width: "150"},
                        {field: "unit_name", title: "单位", width: "100"},
                        {field: "invt_num", title: "可用库存数量", width: "100"},
                        {field: "o_whnum", title: "出库数量", width: "100"},
                        {field: "remarks", title: "备注", width: "100"}
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip($childTable);
                        setMenuHeight();
                        showEmpty($(this), data.total, 0);
                    }
                });
            },
            onCheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    for (var d = 0; d < a.length; d++) {
                        if (a[d]['o_whstatus'] == '待提交' || a[d]['o_whstatus'] == "驳回") {
                            $("#edit").show();
                            $("#cancel").show();
                            $("#check").show();
                        }
                        else {
                            $("#edit").hide();
                            $("#cancel").hide();
                            $("#check").hide();
                        }
                    }
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        $('#data').datagrid('selectRow', rowIndex);
                    }
                } else if (a.length == 0) {
                    $("#edit").hide();
                    $("#cancel").hide();
                    $("#check").hide();
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
                else {
                    var a1 = $("#data").datagrid("getChecked");
                    $('#data').datagrid("unselectAll");
                    $("#edit").hide();
                    $("#cancel").hide();
                    $("#check").hide();
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    for (var d = 0; d < a.length; d++) {
                        if (a[d]['o_whstatus'] == '待提交' || a[d]['o_whstatus'] == "驳回") {
                            $("#edit").show();
                            $("#cancel").show();
                            $("#check").show();
                        }
                        else {
                            $("#edit").hide();
                            $("#cancel").hide();
                            $("#check").hide();
                        }
                    }
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                    }
                } else if (a.length == 0) {
                    $("#edit").hide();
                    $("#cancel").hide();
                    $("#check").hide();
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
                else {
                    var a1 = $("#data").datagrid("getChecked");
                    $('#data').datagrid("unselectAll");
                    $("#edit").hide();
                    $("#cancel").hide();
                    $("#check").hide();
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#child_table_title").hide().next().hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#child_table_title").hide().next().hide();
            }
        });

        //修改
        $("#edit").click(function(){
            var row=$("#data").datagrid("getSelected");
            if(!row){
                layer.alert("请选择一条记录",{icon:2});
            }
            window.location.href="<?=Url::to(['edit'])?>?id="+row.o_whpkid;
        });
        //送审
        $("#check").click(function(){
            var row=$("#data").datagrid("getSelected");
            var url="<?=Url::to(['view'],true)?>?id="+row.o_whpkid;
            var tpList=<?= $businessType?>;
            var changeType = "其它出库";
            for (var k in tpList) {
                if (changeType == tpList[k]) {
                    var type = k;
                    break;
                }
            }
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+row.o_whpkid+"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:750,
                height:480
            });
        });
//        $(".views").click(function(){
//            var row=$("#data").datagrid("getSelected");
//            window.location.href="<?//=Url::to(['view'])?>//?id="+row.invh_id;
//        });
//        $("#remove").click(function(){
//            var row=$("#data").datagrid("getSelected");
//            if(!row){
//                layer.alert("请选择一条记录",{icon:2});
//            }else{
//                layer.confirm("确定删除吗?",{btns:["确定","取消"],icon:2},function(){
//                    $.ajax({
//                        type:"get",
//                        dataType:"json",
//                        url:"<?//=Url::to(['remove'])?>//?id="+row.invh_id,
//                        success:function(data){
//                            if(data.flag==1){
//                                layer.alert(data.msg,{icon:1});
//                                $("#data").datagrid("reload");
//                                return true;
//                            }
//                            layer.alert(data.msg,{icon:2});
//                        }
//                    });
//                },function(){
//                    layer.closeAll();
//                });
//            }
//        });

        //取消出库
        $("#cancel").click(function(){
            var row=$("#data").datagrid("getSelected");
            if(!row){
                layer.alert("请选择一条记录",{icon:2});
            }
            $.fancybox({
                type:"iframe",
                padding:0,
                width:400,
                height:300,
                href:"<?=Url::to(['cancel'])?>?id="+row.o_whpkid
            });
        });
        //导出
        $("#export").click(function(){
            window.location.href="<?=Url::to(['index','export'=>1])?>";
        });

        $(".datagrid").click(function(){
            if(event.target.className=="tip"){
                $.fancybox({
                    padding:0,
                    href:"#tip"
                });
            }
        });
    });
    function delRow(index,id){
        layer.confirm("确定删除吗?",{btns:["确定","取消"],icon:2},function(){
            $.ajax({
                type:"get",
                dataType:"json",
                url:"<?=Url::to(['remove'])?>?id="+id,
                success:function(data){
                    if(data.flag==1){
                        layer.alert(data.msg,{icon:1});
                        $("#data").datagrid("reload");
                        return true;
                    }
                    layer.alert(data.msg,{icon:2});
                }
            });
        },function(){
            layer.closeAll();
        });
    }

    function cancel(id)
    {
        $.fancybox({
            type:"iframe",
            padding:0,
            width:400,
            height:300,
            href:"<?=Url::to(['cancel'])?>?id="+id
        });
    }
    function editRow(id){
        window.location.href="<?=Url::to(['edit'])?>?id="+id;
    }
    function viewRow(id){
        window.location.href="<?=Url::to(['view'])?>?id="+id;
    }
</script>