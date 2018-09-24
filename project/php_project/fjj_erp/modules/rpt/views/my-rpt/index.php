<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\HighchartsAsset::register($this);

$this->params['homeLike'] = ['label'=>'系统报表管理','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'我的报表'];
$get = Yii::$app->request->get();
if (!isset($get['TemplateSearch'])) {
    $get['TemplateSearch'] = null;
}
?>
<style type="text/css">
    .label-width{
        width:80px;
    }
    .value-width{
        width: 120px;
    }
</style>
<div class="content">
    <?php $form = ActiveForm::begin(['method' => 'get']); ?>
    <div class="mb-10">
        <label class="label-width">报表编码：</label>
        <input type="text" class="value-width" name="TemplateSearch[rptt_code]" value="<?= $get['TemplateSearch']['rptt_code'] ?>">
        <label class="label-width">名称或抬头：</label>
        <input type="text" class="width-100" name="TemplateSearch[rptt_name]" value="<?= $get['TemplateSearch']['rptt_name'] ?>">
<!--        <label class="width-100">状态</label>-->
<!--        <select class="width-80" name="TemplateSearch[sts_status]">-->
<!--            <option value="">请选择</option>-->
            <?php if (!empty($status->storeStatus)) { ?>
                <?php foreach ($status->storeStatus as $key => $val) { ?>
                    <option value="<?= $key ?>" <?= isset($get['TemplateSearch']['sts_status'])&&$get['TemplateSearch']['sts_status'] == $key ? "selected" : null ?>><?= $val ?></option>
                <?php } ?>
            <?php } ?>
<!--        </select>-->
        <?= Html::submitButton('查询', ['class' => 'button-blue ml-50']) ?>
        <?= Html::button('重置', ['class' => 'button-blue', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>
        <button type="button" class="button-blue to-preview">预览</button>
    </div>
    <?php ActiveForm::end(); ?>

    <div id="my-rpt"></div>
    <br/>
    <div class="border-1px mt-5" id="param-div">
        <?php $form = ActiveForm::begin(['id' => 'add-form','action'=>Url::to(['/rpt/rpt-manage/save-all'])]); ?>
        <input type="hidden" name="RptTemplate[rptt_type]" >
        <input type="hidden" name="RptTemplate[rptt_id]" >
        <div class="space-10"></div>
        <label class="label-width mb-10">显示类型：</label>
        <select class="value-width rpt-shape" name="RptTemplate[rptt_dtype]">
            <option value="">请选择...</option>
            <option value="column">柱状图</option>
            <option value="line">线型图</option>
            <option value="pie">圆饼图</option>
            <option value="area">区域图</option>
            <option value="scatter">散点图</option>
            <option value="bubble">气泡图</option>
        </select>
        <button type="button" class="button-blue-big preview is-ok" disabled="disabled">确定</button>
        <div class="tplParam-div" id="tplParam-div" style="margin-left: 80px; width:435px">
            <div class="template-params"></div>
        </div>
        <div class="space-10"></div>
        <div class="theSql">
            <textarea type="text" rows="4" class="width-549 mt-10 text-top easyui-validatebox validateboxs svp_content mb-10" data-options="require:true" name="RptTemplate[rptt_tempsql]" hidden>
            </textarea>
            <div class="sqlParam-div" style="margin-left: 105px; width:435px">
                <div class="paraList"></div>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
    <br/>
    <div id="rpt" style="display: none">
        <!--        这里预览报表弹窗-->
    </div>
</div>
<script>
    var title = '';
    var select = '';
    $(function () {
        $("#my-rpt").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: false,
            columns: [[
                {field:"rptt_code",title:"报表编号",width:150},
                {field:"rptt_name",title:"名称",width:200},
                {field:"rptt_title",title:"报表抬头",width:200},
                {field:"rptt_dtype",title:"默认显示类型",width:150,
                    formatter: function (value, row, index) {
                        switch (row.rptt_dtype)
                        {
                            case 'column':
                                return '柱状图';
                                break;
                            case 'line':
                                return '线型图';
                                break;
                            case 'pie':
                                return '圆饼图';
                                break;
                            case 'area':
                                return '区域图';
                                break;
                            case 'scatter':
                                return '点状图';
                                break;
                            case 'bubble':
                                return '气泡图';
                                break;
                        }
                    }
                },
                {field:"rptt_descr",title:"报表描述",width:150},
                {field:"rptt_ctime",title:"报表时间",width:150}
//                {field:"status",title:"报表状态",width:150}
            ]],
            onSelect: function (index,row) {
                console.log(row);
                select = row;
                // 加载参数列表
                $('input[name="RptTemplate[rptt_type]"]').val(row.rptt_type);
                $('input[name="RptTemplate[rptt_id]"]').val(row.rptt_id);
                title = row.rptt_title || row.rptt_name;
            },
            onLoadSuccess: function () {
                setMenuHeight();
            }
        })
    });

    // 点击预览
    $('.to-preview').on('click', function () {
        if(!select) {
            layer.alert('请选择一个报表')
            return false;
        }
        $.fancybox(
            {
                padding : [50],
                fitToView	: false,
                width		: 600,
                height		: 300,
                autoSize	: false,
                closeClick	: false,
                openEffect	: 'none',
                closeEffect	: 'none',
                href: '#param-div'
            }
        );
        if ( (select.rptt_type==10) || (select.rptt_type==11) ) {
            loadParams(true,select.rptt_id,false);
            $('.tplParam-div').show();
            $('.sqlParam-div').hide();
        } else {
            // sql对象
            $('textarea[name="RptTemplate[rptt_tempsql]"]').html(select.rptt_tempsql);
            $('select[name="RptTemplate[rptt_dtype]"]').val(select.rptt_dtype);
            $('.tplParam-div').hide();
            $('.sqlParam-div').show();
            checkSql();
        }
//        preview();
    });

    $('.is-ok').on('click', function () {
//        alert('abc')
        preview();
    })

    // 预览
    function preview() {
        var url = "<?= Url::to(['/rpt/rpt-manage/preview']) ?>";
        var dtype = $('.rpt-shape option:selected').val() || 'column';
        title = title || '无标题';
        $.ajax({
            type: 'post',
            dataType: 'json',
//                data: {sql: sql,params:params,id:TemplateId},
            data: $('#add-form').serialize(),
            url: url,
            success: function (data) {
                console.log('data:',data);
//                    $('#rpt').css('display','block');
                $.fancybox(
                    {
                        padding : [],
                        fitToView	: false,
                        width		: 560,
                        height		: 440,
                        autoSize	: false,
                        closeClick	: false,
                        openEffect	: 'none',
                        closeEffect	: 'none',
                        href: '#rpt'
                    }
                );

                console.log(title);
                var myChart = Highcharts.chart('rpt', {
                    chart: {
                        type: dtype,
                    },
                    title: {
                        text: title
                    },
                    xAxis: {
                        categories: data.xAxis,
                    },
                    credits: {
                        enabled:false
                    },
                    lang: {
                        noData: "没有正确数据显示该类型报表！"
                    },
                    noData: {
                        style: {
                            fontWeight: 'bold',
                            fontSize: '15px',
                            color: '#303030'
                        }
                    },
                    series: data.series
                });
            },
            error: function (data) {
                alert('预览不成功，请重新检查sql语句或者参数');
                console.log('err',data)
            }
        })
    }

    // 加载模板参数
    function loadParams( display, id, isDisabled ) {
        $.ajax({
            type: 'GET',
            dataType: 'json',
//            data: {id: $('.rpt-template option:selected').val()},
            data: {id: id},
            url: "<?= Url::to(['/rpt/rpt-manage/get-template-params']) ?>",
            success: function (data) {
                var rptShape = $('.rpt-shape');
//                $('.param-div').show();
//                $('#tbs').tabs('disableTab', 1);
                $('.preview').attr('disabled',isDisabled);
                $('.saveAll').attr('disabled',isDisabled);
                rptShape.val(data[0].rptt_dtype);
                rptShape.nextAll(':hidden').remove(); // 清除上次的input框
                $.each(data[0]['params'], function (i,item) {
                    rptShape.after('<input class="easyui-validatebox" type="hidden" name="RptParam['+i+'][RptParam][rptp_id]" value='+'"'+item.rptp_id+'"'+'>');
                    rptShape.after('<input class="easyui-validatebox key" type="hidden" name="RptParam['+i+'][RptParam][rptp_key]" value='+'"'+item.rptp_key+'"'+'>');
                    rptShape.after('<input class="easyui-validatebox" type="hidden" name="RptParam['+i+'][RptParam][rptp_name]" value='+'"'+item.rptp_name+'"'+'>');
                    rptShape.after('<input class="easyui-validatebox" type="hidden" name="RptParam['+i+'][RptParam][rptp_logic]" value='+'"'+item.rptp_logic+'"'+'>');
                    rptShape.after('<input class="easyui-validatebox val" type="hidden" name="RptParam['+i+'][RptParam][rptp_val]" value='+'"'+item.rptp_val+'"'+'>');
                })
                $('.template-params').datagrid({
                    data: data[0]['params'],
                    rownumbers: true,
                    singleSelect:true,
                    columns:[[
                        {field:"rptp_id",title:"参数id",align:'center',width:100,hidden:true},
                        {field:"rptp_key",title:"参数key",align:'center',width:100},
                        {field:"rptp_logic",title:"参数逻辑",align:'center',width:100},
                        {field:"rptp_name",title:"参数名",align:'center',width:100},
                        {field:"rptp_val",title:"参数值",align:'center',width:100,editor:'text'},
                        {field:'action',title:'Action',width:140,align:'center',
                            formatter:function(value,row,index){
                                if (row.editing){
                                    var s = '<a href="javascript:void(0)" onclick="savePatamsrow(this)">完成修改</a> ';
                                    var c = '<a href="javascript:void(0)" onclick="cancelPatamsrow(this)">取消修改</a>';
                                    return s+c;
                                } else {
                                    var e = '<a href="javascript:void(0)" onclick="editPatamsrow(this)">修改參數</a> ';
//                                        var d = '<a href="javascript:void(0)" onclick="deleterow(this)">Delete</a>';
//                                        return e+d;
                                    return e;
                                }
                            },
                            hidden:display
                        }
                    ]],

                    onEndEdit:function(index,row){
                        var ed = $(this).datagrid('getEditor', {
                            index: index,
                            field: 'rptp_val'
                        });
                    },
                    onBeforeEdit:function(index,row){
                        row.editing = true;
                        $(this).datagrid('refreshRow', index);
                        $('.preview').attr('disabled',true);
                    },
                    onAfterEdit:function(index,row){
                        row.editing = false;
                        $(this).datagrid('refreshRow', index);
//                        console.log($('.template-params').datagrid('getRows'))
                        $('input[name="RptParam['+index+'][RptParam][rptp_val]"]').val(row.rptp_val);
                        $('.preview').attr('disabled',false);
                    },
                    onCancelEdit:function(index,row){
                        row.editing = false;
                        $(this).datagrid('refreshRow', index);
                    },
                    onBeforeSelect: function (index,row) {
                        $('.template-params').datagrid('beginEdit', index);
                    },
                });
            },
            error: function (data) {
                console.log(data)
            }
        })
    };

    // 检查sql 提取参数修改参数
    function checkSql(){
        var postData = $('.theSql textarea').text().trim();
        if (postData == '') {
            alert('SQL语句不能为空！');
            return false;
        }
//                console.log(postData);
        $('.theSql textarea').text(postData);
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {sql: postData},
            url: "<?= Url::to(['/rpt/rpt-manage/check-sql']) ?>",
            success: function (data) {
                console.log('ajax',data);
                console.log(postData);
                if (data.flag!=0) {
                    $('.paraList').datagrid({
                        data:data,
                        rownumbers: true,
                        singleSelect:true,
                        autoSave:true,
                        columns:[[
                            {field:"key",title:"参数名",align:'center',width:100},
                            {field:"operator",title:"逻辑符",align:'center',width:100,
                                formatter:function(value,row){
                                    return row.operator || value;
                                },
                                editor:{
                                    type:'combobox',
                                    options:{
                                        valueField:'operator',
                                        textField:'text_field',
                                        data:
                                            [
                                                {'operator':'=','text_field':'='},
                                                {'operator':'>','text_field':'>'},
                                                {'operator':'>=','text_field':'>='},
                                                {'operator':'<=','text_field':'<='},
                                                {'operator':'!=','text_field':'!='},
                                                {'operator':' like ','text_field':' like '},
                                            ],
                                        required:true
                                    }
                                }
                            },
//                            {field:"action1",title:"参数值",width:120,align:'center',
//                                formatter: function (value,row,index) {
//                                    if (row.editing){
//                                        return "<input text='text' onblur='saverow(this)' value='"+row.operator+"'>"
//                                    } else {
////                                        return "<input text='text' onfocus='editrow(this)' value='"+row.operator+"'>"
//                                        return "<select value='"+row.operator+"'>"+"<option>=</option><option>></option><option>>=</option><option><=</option><option>!=</option><option> like </option></select>"
//                                    }
//                                }
//                            },
                            {field:"value",title:"参数值",width:120,align:'center',editor:'text',hidden:false},
//                            {field:"action2",title:"参数值",width:120,align:'center',
//                                formatter: function (value,row,index) {
//                                    if (row.editing){
//                                        return "<input text='text' onblur='saverow(this)' value='"+row.value+"'>"
//                                    } else {
//                                        return "<input text='text' onfocus='editrow(this)' value='"+row.value+"'>"
//                                    }
//                                }
//                            },
                            {field:'action',title:'Action',width:140,align:'center',
                                formatter:function(value,row,index){
                                    if (row.editing){
                                        var s = '<a href="javascript:void(0)" onclick="saverow(this)">完成修改</a> ';
                                        var c = '<a href="javascript:void(0)" onclick="cancelrow(this)">取消修改</a>';
                                        return s+c;
                                    } else {
                                        var e = '<a href="javascript:void(0)" onclick="editrow(this)">修改參數</a> ';
//                                        var d = '<a href="javascript:void(0)" onclick="deleterow(this)">Delete</a>';
//                                        return e+d;
                                        return e;
                                    }
                                },
                                hidden: true
                            }
                        ]],
//
                        onEndEdit:function(index,row){
                            var ed = $(this).datagrid('getEditor', {
                                index: index,
                                field: 'operator'
                            });
                            row.operator = $(ed.target).combobox('getText');
                        },
                        onBeforeEdit:function(index,row){
                            row.editing = true;
                            $(this).datagrid('refreshRow', index);
                            $('.preview').attr('disabled',true);
                            $('.saveAll').attr('disabled',true);
                        },
                        onAfterEdit:function(index,row){
                            row.editing = false;
                            $(this).datagrid('refreshRow', index);
                            changeData = $('.paraList').datagrid('getRows');
                            // 改变SQL框的内容 传递修改的参数给后台   禁用预览和保存功能（改变后必须再次校验）
                            var sqlStr = $('.theSql textarea').text().trim();
                            if (changeData) {
                                // 用于多个参数同时提交，不会改变原参数顺序（暂不支持多个同时修改）
                                changeData.sort(keysrt('opt_position',false));
                            }
                            var res = myArrRpl(sqlStr,changeData);
                            $('.theSql textarea').text(res);
                            checkSql(); // 修改某个参数后重新ajax验证SQL语句，并获得修改后参数的正确位置信息，否则下一次修改可能会出错
                        },
                        onCancelEdit:function(index,row){
                            row.editing = false;
                            $(this).datagrid('refreshRow', index);
                        },
//                        onClickRow: function (index,row) {
//                            $('.paraList').datagrid('beginEdit', index);
//                        },
                        onBeforeSelect: function (index,row) {
                            $('.paraList').datagrid('beginEdit', index);
                        },
//                        onBeforeUnselect: function (index,row) {
//                            saverow1(index);
//                            alert('unselect'+index)
//                        }
                    });
                    $('.preview').attr('disabled',false);
                    $('.saveAll').attr('disabled',false);
                } else {
                    $('.preview').attr('disabled',true);
                    $('.saveAll').attr('disabled',true);
                    $('.param-div').hide();
                    alert(data.msg);
                }
            },
            error: function (data) {
                alert('发生未知错误 请稍后重试');
                console.log(data)
            }
        })
    };

    // 改变值完成编辑  延迟执行自己定义的事件防止和框架事件冲突
    $('.tplParam-div').on('blur','input',function(){
        $(this).trigger("myOwnEvent2");
    });
    $('.tplParam-div').on('myOwnEvent2', 'input', function(){
        var rowIndex = getRowIndex(this);
        setTimeout(function () {
            $('.template-params').datagrid('endEdit', rowIndex);
        },100)
    });
    $('.sqlParam-div').on('blur','input',function(){
        $(this).trigger("myOwnEvent");
    });
    $('.sqlParam-div').on('myOwnEvent', 'input', function(){
        var rowIndex = getRowIndex(this);
        setTimeout(function () {
            $('.paraList').datagrid('endEdit', rowIndex);
        },100)
//        alert('myev')
    });

//    }
//    $('.sqlParam-div, .tplParam-div').on('change','input',function () {
//        alert('change')
//        var rowIndex = getRowIndex(this);
//        $('.paraList').datagrid('endEdit', rowIndex);
//    });

    // 查找位置替换字串函数
    function myArrRpl(ObjStr,arr) {
        var offset = 0;  // 处理如果有中文 或者参数长度变化
        for (var i = 0; i < ObjStr.length; i++) {
            var a = ObjStr.charAt(i);
            if (a.match(/[^\x00-\xff]/ig) != null) {
                offset -= 2;
            }
        }
        var obj_str = ObjStr;
        $.each(arr,function (i,n) {
            if (n.opt_position>n.val_position) {
                var str = obj_str.substring(0,n.opt_position+offset)+n.operator+obj_str.substring(n.opt_position+n.opt_len+offset);
                str = str.substring(0,n.val_position+offset)+n.value+str.substring(n.val_position+n.val_len+offset);
            } else {
                var str = obj_str.substring(0,n.val_position+offset)+n.value+obj_str.substring(n.val_position+n.val_len+offset);
                str = str.substring(0,n.opt_position+offset)+n.operator+str.substring(n.opt_position+n.opt_len+offset);
            }
//            console.log('第'+i+'次',offset);
            offset = offset+n.operator.length+n.value.length-n.val_len-n.opt_len;
            obj_str = str;
        });
        return obj_str;
    };

    function keysrt(key,desc) {
        return function(a,b){
            return desc ? ~~(a[key] < b[key]) : ~~(a[key] > b[key]);
        }
    };

    function getRowIndex(target){
//        console.log(target)
        var tr = $(target).closest('tr.datagrid-row');
//        console.log(parseInt(tr.attr('datagrid-row-index')))
        return parseInt(tr.attr('datagrid-row-index'));
    }
    function editrow(target){
//        alert('edit')
        $('.paraList').datagrid('beginEdit', getRowIndex(target));
//        console.log($(target))
//        $(target).focus();
    }
    function saverow(target){
        var rowIndex = getRowIndex(target);
        $('.paraList').datagrid('endEdit', rowIndex);
//        $('.paraList').datagrid('getRows')[rowIndex].value = $(target).val();
//        console.log($('.paraList').datagrid('getRows')[rowIndex].value);
//        console.log($(target).val());
//        $('.paraList').datagrid('refreshRow', rowIndex);
//        changeData = $('.paraList').datagrid('getRows');
//        // 改变SQL框的内容 传递修改的参数给后台   禁用预览和保存功能（改变后必须再次校验）
//        var sqlStr = $('.theSql textarea').text().trim();
//        if (changeData) {
//            // 用于多个参数同时提交，不会改变原参数顺序（暂不支持多个同时修改）
//            changeData.sort(keysrt('opt_position',false));
//        }
//        var res = myArrRpl(sqlStr,changeData);
//        $('.theSql textarea').text(res);
//        checkSql();
    }

    function saverow1(index){
//        datagrid-cell-c3-value
//        $('.paraList').datagrid('getRows')[0].value = $(target).val();
        $('.paraList').datagrid('endEdit', index);
//        $('.paraList').datagrid('getRows')[index].value = $(target).val();
        console.log($('.paraList').datagrid('getRows')[index].value);
//        console.log($(target).val());
//        $('.paraList').datagrid('reload');
//        row.editing = false;
//        console.log('index',0);
        $('.paraList').datagrid('refreshRow', index);
        changeData = $('.paraList').datagrid('getRows');
        // 改变SQL框的内容 传递修改的参数给后台   禁用预览和保存功能（改变后必须再次校验）
        var sqlStr = $('.theSql textarea').text().trim();
        if (changeData) {
            // 用于多个参数同时提交，不会改变原参数顺序（暂不支持多个同时修改）
            changeData.sort(keysrt('opt_position',false));
        }
        var res = myArrRpl(sqlStr,changeData);
        $('.theSql textarea').text(res);
        checkSql();
    }



    function cancelrow(target){
        $('.paraList').datagrid('cancelEdit', getRowIndex(target));
    }

    function getPatamsRowIndex(target){
        var tr = $(target).closest('tr.datagrid-row');
        return parseInt(tr.attr('datagrid-row-index'));
    }
    function editPatamsrow(target){
        $('.template-params').datagrid('beginEdit', getPatamsRowIndex(target));
    }
    function savePatamsrow(target){
        $('.template-params').datagrid('endEdit', getPatamsRowIndex(target));
    }
    function cancelPatamsrow(target){
        $('.template-params').datagrid('cancelEdit', getPatamsRowIndex(target));
    }

    function test1(tg) {
//        alert('获得焦点')
        $('.paraList').datagrid('beginEdit', getRowIndex(tg));
    }
    function test2(tg) {
//        alert('失去焦点')
        saverow(tg)
    }
    function test3() {
        alert('change')
    }
</script>