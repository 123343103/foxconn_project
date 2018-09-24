<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\HighchartsAsset::register($this);
?>
<style type="text/css">
    .label-width{
        width:80px;
    }
    .value-width{
        width:140px;
    }
    .mt-10{
        margin-top: 10px;
    }
</style>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'add-form','action'=>Url::to(['/rpt/rpt-manage/save-all'])]); ?>
    <div class="border-1px mt-5">
        <input type="hidden" name="RptTemplate[rptt_type]" >
        <input type="hidden" name="RptTemplate[rptt_id]" >
        <label class="label-width mt-10"><span class="red ">*</span>模板编号：</label>
        <input class="value-width easyui-validatebox myChange" type="text" name="RptTemplate[rptt_code]" value="<?= $result['sts_code'] ?>" data-options="required:true">
        <label class="label-width mt-10"><span class="red">*</span>报表名称：</label>
        <input class="value-width easyui-validatebox myChange" type="text" name="RptTemplate[rptt_name]" value="<?= $result['sts_sname'] ?>" data-options="required:'true'">
        <label class="label-width  mt-10" >显示抬头：</label>
        <input class="value-width" data-options="required:true" type="text" name="RptTemplate[rptt_title]" >
        <label class="label-width">显示类型：</label>
        <select class="value-width rpt-shape" name="RptTemplate[rptt_dtype]">
            <option value="">请选择...</option>
            <option value="column">柱状图</option>
            <option value="line">线型图</option>
            <option value="pie">圆饼图</option>
            <option value="area">区域图</option>
            <option value="scatter">散点图</option>
            <option value="bubble">气泡图</option>
        </select>
        <div class="inline-block field-hrorganization-is_abandoned">
            <label class="label-width" for="RptTemplate[rptt_title]">状态：</label>
            <div class='display-style width-240 mt-10'>
                <div>
                    <input type="radio" name="RptTemplate[rptt_status]" value="10" <?= $model['is_abandoned']==0?'checked':''?>>启用&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="RptTemplate[rptt_status]" value="11" <?= $model['is_abandoned']==1?'checked':''?>>不启用
                </div>
            </div>
        </div>

        <div class="text-center mt-10">
            <div id="tbs" class="easyui-tabs mt-20" >
                <div title="引用对象" selected=true>
                    <div id="obj-data">
                        <!-- 选择模板对象后加载参数列表 -->
                        <label class="label-width mt-10"><span class="red">*</span>选择对象：</label>
                        <select name="RptTemplate[rptt_pid]" class="value-width rpt-template mt-10">
                            <option value="">请选择</option>
                            <?php foreach ($template as $key => $val) { ?>
                            <option value="<?= $key ?>"><?= $val ?></option>
                            <?php } ?>
                        </select>
                        <div class="mt-10 param-div" style="overflow-x: auto; margin-left: 85px;display: none;width:600px;"><div class="template-params"></div></div>
                        <div class="text-center mt-10 mb-10">
                            <button type="button" class="button-blue-big preview" disabled="disabled">预览</button>
                            <button type="submit" class="button-blue-big saveAll" disabled="disabled">保存</button>
                        </div>
                    </div>
                </div>
                <div title="引用SQL">
                    <div id="sql-data">
                        <div class="theSql">
                            <label class="label-width mt-10">模板SQL：</label>
<!--                            <textarea type="text" rows="3" class="width-549 text-top easyui-validatebox validateboxs svp_content" name="RptTemplate[rptt_title]">-->
<!--                                SELECT COUNT(commodity), pdq_source_type AS  name, COUNT(pdq_id) as shuliang FROM `pd_requirement` WHERE pdq_date>'2017-03-00' and pdq_date<'2017-03-31' GROUP BY pdq_source_type;-->
<!--                            </textarea>-->
                            <textarea type="text" rows="4" class="text-top easyui-validatebox validateboxs svp_content mb-10" data-options="require:true" name="RptTemplate[rptt_tempsql]" style="width: 600px;">
                                SELECT COUNT(commodity), if(pdq_source_type=100020,'類型一','類型二') AS  name, COUNT(pdq_id) as shuliang FROM `pd_requirement` WHERE pdq_date>'2017-03-00' and pdq_date<'2017-03-31' GROUP BY pdq_source_type;
                            </textarea>
                        </div>
                        <div class="width-549 param-div" style="overflow-x: auto; margin-left: 85px;"><div class="paraList"></div></div>
                        <label class="label-width mt-10"><span class="red">*</span>分类：</label>
                        <select name="RptTemplate[rptt_cat]" class="value-width mt-10 rpt-cat">
                            <option value="">请选择</option>
                            <?php foreach ($category as $key => $val) { ?>
                                <option value="<?= $val['rptc_id'] ?>"><?= $val['rptc_name'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="text-center mt-10 mb-10 bt-group">
                            <button type="button" class="button-blue-big checkSql">校验</button>
                            <button type="button" class="button-blue-big preview" disabled="disabled">预览</button>
                            <button type="submit" class="button-blue-big saveAll" disabled="disabled">保存</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <?php ActiveForm::end() ?>
    <div class="mt-20 border-1px  distribute">
        <label class="width-80" for="assignList">已分配用户</label>
        <button type="button" class="button-blue deleteAssign ml-20">删除</button>
        <div id="assignList"></div>
    </div>
</div>

<script>
    $(function(){
        // 初始化表单
        var addFrom = $("#add-form");
        ajaxSubmitForm(addFrom);
        addFrom.find('input,textarea').not(':button, :submit, :reset, :hidden').attr('readonly',true);// 初始化禁用表单
        addFrom.find('select').not(':button, :submit, :reset, :hidden').attr('disabled',true);// 初始化禁用表单
        addFrom.attr('disabled',true);

        // 保存前校验
        $('.saveAll').on('click', function () {
            if($('input[name="RptTemplate[rptt_code]"]').val() == ''){
                layer.alert("编码不能为空!", {icon: 2, time: 5000});
                return false;
            } else {
                var code = $("input[name='RptTemplate[rptt_code]']").val();
                checkCode(code);
                return false;
            };
            if($('input[name="RptTemplate[rptt_name]"]').val() == ''){
                layer.alert("名称不能为空!", {icon: 2, time: 5000});
                return false;
            };
            if ($('input[name="RptTemplate[rptt_type]"]').val() == '12') {
                if($('.rpt-cat').val() == ''){
                    layer.alert("分类不能为空!", {icon: 2, time: 5000});
                    return false;
                };
                if($('textarea[name="RptTemplate[rptt_tempsql]"]').val() == ''){
                    layer.alert("sql查询语句不能为空!", {icon: 2, time: 5000});
                    return false;
                };
            }
        });

        $('#tbs').tabs({
            onSelect:function(title){
                if (isadd == 1) {
                    if (title == '引用SQL') {
                        $('.rpt-shape').nextAll('input:hidden').remove();
                        $('input[name="RptTemplate[rptt_type]"]').val('12');
                    } else if (title == '引用对象') {
                        $('input[name="RptTemplate[rptt_type]"]').val('11');
                    }
                }
            }
        });


        // 校验解析sql
        var changeData = [];
        $('.checkSql').on('click', function () {
            checkSql();
        });

        // SQL预览
        $('.preview').on('click',function () {
            preview();
        })

        // 选择模板对象ajax加载参数列表
        $('.rpt-template').on('change', function () {
            $('input[name="RptTemplate[rptt_type]"]').val('11');
            var id = $('.rpt-template option:selected').val();
            $('.param-div').show();
            loadParams(false,id,false);
        });

        // 改变sql框 需要重新校验 禁用预览和保存
        $('.theSql textarea').on('change',function(){
            $('#tbs').tabs('disableTab',0);
            $('.checkSql').attr('disabled',false);
            $('.preview').attr('disabled',true);
            $('.saveAll').attr('disabled',true);
            $('.param-div').hide();
        });

        // 选择分类
        $('.rpt-cat').on('change', function () {
            $('#tbs').tabs('disableTab',0);
        })

        // 验证编码
//        $("input[name='RptTemplate[rptt_code]']").on('blur', function(){
//            checkCode($(this).val());
//        });
    });

    // 验证编码唯一
    function  checkCode(code) {
        if (isadd ==1) {
//            alert('shiqu')
            $.ajax({
                type: 'get',
                dataType: 'json',
                data: {code: code},
                url: "<?= Url::to(['/rpt/rpt-manage/is-code-exist']) ?>",
                success: function (data) {
                    console.log(data)
                    if (data == 1) {
                        layer.alert("编码已存在!", {icon: 2, time: 5000});
                        return false;
                    }
                },
                error: function (data) {
                    console.log(data)
                    return false;
                }
            })
        }
    }

    // 检查sql
    function checkSql(){
        var postData = $('.theSql textarea').val().trim();
        if (postData == '') {
            alert('SQL语句不能为空！');
            return false;
        }
//                console.log(postData);
        $('.theSql textarea').val(postData);
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {sql: postData},
            url: "<?= Url::to(['/rpt/rpt-manage/check-sql']) ?>",
            success: function (data) {
                $('.param-div').show();
                $('#tbs').tabs('disableTab', 0);
                console.log('ajax',data);
                if (data.flag!=0) {
                    $('.paraList').datagrid({
                        data:data,
//                        width:660,
//                        height:250,
//                        pagination:true,
                        rownumbers: true,
//                        method: "get",
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
                            {field:"value",title:"参数值",width:120,align:'center',editor:'text'},
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
                                }
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
                            var sqlStr = $('.theSql textarea').val().trim();
                            if (changeData) {
                                // 用于多个参数同时提交，不会改变原参数顺序（暂不支持多个同时修改）
                                changeData.sort(keysrt('opt_position',false));
                            }
                            var res = myArrRpl(sqlStr,changeData);
                            $('.theSql textarea').val(res);
                            checkSql(); // 修改某个参数后重新ajax验证SQL语句，并获得修改后参数的正确位置信息，否则下一次修改可能会出错
                        },
                        onCancelEdit:function(index,row){
                            row.editing = false;
                            $(this).datagrid('refreshRow', index);
                        }
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

    // 预览
    function preview() {
        var current_tabs = $('#tbs').tabs('getSelected');
        var tabs_title = current_tabs.panel('options').tab[0].textContent;
        var url = "<?= Url::to(['/rpt/rpt-manage/preview']) ?>";
        var dtype = $('.rpt-shape option:selected').val() || 'column';
        var title = $('input[name="RptTemplate[rptt_title]"]').val() || $('input[name="RptTemplate[rptt_name]"]').val() || '无标题';
        $(".rpt-shape").prop("disabled",false);
        var formData=$("form").serialize();
        $(".rpt-shape").prop("disabled",true);
        $.ajax({
            type: 'post',
            dataType: 'json',
//                data: {sql: sql,params:params,id:TemplateId},
            data:formData,
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
//                            type : 'iframe'
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
//                        yAxis: {
//                            title: {
//                                text: 'Fruit eaten'
//                            }
//                        },
//                        series: [{
//                            name: 'Jane',
//                            data: [15, 0, 4]
//                        }, {
//                            name: 'John',
//                            data: [5, 7, 3]
//                        }]
//                        series: [{"name":"100020","data":[1,12]},{"name":"100021","data":[1,4]}]
                    series: data.series
                });
//                    $('#rpt').append('<p>aaaaaaaa</p>')
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
                        {field:'action',title:'Action',width:165,align:'center',
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
                    },
                    onAfterEdit:function(index,row){
                        row.editing = false;
                        $(this).datagrid('refreshRow', index);
//                        console.log($('.template-params').datagrid('getRows'))
                        $('input[name="RptParam['+index+'][RptParam][rptp_val]"]').val(row.rptp_val);
                    },
                    onCancelEdit:function(index,row){
                        row.editing = false;
                        $(this).datagrid('refreshRow', index);
                    }
                });
            },
            error: function (data) {
                console.log(data)
            }
        })
    };

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
        var tr = $(target).closest('tr.datagrid-row');
        return parseInt(tr.attr('datagrid-row-index'));
    }
    function editrow(target){
        $('.paraList').datagrid('beginEdit', getRowIndex(target));
    }
    function saverow(target){
        $('.paraList').datagrid('endEdit', getRowIndex(target));
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
</script>