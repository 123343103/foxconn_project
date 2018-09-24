<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\TreeAsset;
use app\assets\MultiSelectAsset;
TreeAsset::register($this);
MultiSelectAsset::register($this);

$this->params['homeLike'] = ['label'=>'系统报表管理','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'报表模板'];
?>
<style>
    .select-cls {
        background-color: #00caf6;
    }
    .float-left button:hover{
        border: 0px;
    }
    .ms-container .ms-list {
        height: 200px;
    }
</style>
<div class="content" style="overflow: auto;">
    <div id="tree" style="width: 24%;float: left;margin-right:1%;">
    </div>
    <div id="" style="width:75%;float: left;border: #dddddd 1px solid; box-sizing:border-box;">
        <div class="table-head mb-10 mt-10 mb-10" style="margin-left: 20px;">
            <p class="float-left">
                <button id="add" class="pl-10 pr-10 button-blue-small" type="button">新增</button>
                <button id="edit" class="button-blue-small pl-10 pr-10" disabled="disabled" type="button">修改</button>
<!--                <a href="javascript:void(0)" id="m-design" class="back-color-ddd">设计</a>-->
                <button id="distribute" class=" pl-10 pr-10 button-blue-small" disabled="disabled" type="button">分配</button>
                <button id="preview" class="back-color-ddd pl-10 pr-10 button-blue-small" disabled="disabled" type="button">浏览</button>
                <button id="delete" class="back-color-ddd pl-10 pr-10 button-blue-small" disabled="disabled" type="button">删除</button>
                <button id="close" class="back-color-ddd pl-10 pr-10 button-blue-small" type="button" onclick="location.href='<?=\yii\helpers\Url::to(["index"]) ?>'">返回</button>
            </p>
<!--            <div id="m-data" style="width:60px;display: none;">-->
<!--                <div><a id="import_btn" class="menu-span pl-10 pr-10 button-blue-small" disabled="disabled" type="button"><span>参数设置</span></a></div>-->
<!--                <div><a id="export_btn" class="menu-span pl-10 pr-10 button-blue-small" disabled="disabled" type="button"><span>引用对象</span></a></div>-->
<!--            </div>-->
        </div>
        <div class="space-10"></div>
        <div>
            <?= $this->render('_form',[
                'template' => $template,
                'category' => $category
            ]) ?>
        </div>
        <div id="rpt" style="display: none">
            <!--        这里预览报表弹窗-->
        </div>
        <div id="assign" style="display: none">
            <!--        这里分配报表弹窗-->
            <?php $form = ActiveForm::begin(['id' => 'select-form','action'=>Url::to(['/rpt/rpt-manage/assign-save'])]); ?>
            <br/><h2 class="text-center">分配报表</h2><br/>
            <input type="hidden" name="RptTemplate[rptt_id]" >
            <div style="margin-bottom: 10px">
                <h3 style="margin-left: 80px;">选择分配角色</h3>
                <div class="space-10"></div>
                <select multiple="multiple" id="my-select" name="RptAssign[role][]">
                    <?php foreach ($roles as $k => $v) { ?>
                        <option value="<?= $v->name ?>"><?= $v->title ?></option>
                    <?php } ?>
                </select>
            </div>
            <h3 style="margin-left: 80px;">选择分配用户</h3>
            <div class="space-10"></div>
            <div class="overflow-auto" style="margin-left: 100px;">
                <input type="text" placeholder="工号or账号or名字 模糊查找" name="UserSearch[user_account]" class="width-200" style="height: 30px;">
                <img id="userSearch" src="<?= Url::to('@web/img/icon/search.png') ?>" alt="搜索" style="cursor: pointer; vertical-align: bottom; margin-left: -4px;">
            </div>
            <div class="space-10"></div>
            <div style="margin-left: 100px; width: 600px;">
                <div id="user-list"><!-- 用户列表datagrid --></div>
            </div>
            <div style="width:600px;margin-bottom: 10px; margin-left: 100px;margin-top: 10px;">
                <table id="supplier_contact_person_tab1" style="display: none;">
                    <thead>
                    <tr>
                        <th style="width:160px;">姓名</th>
                        <th style="width:165px;">工号</th>
                        <th style="width:170px;">登陆账号</th>
                    </tr>
                    </thead>
                    <tbody id="user_tbody"></tbody>
                </table>
            </div>
            <div class="mb-10 text-center">
                <button class="button-blue-big assign-save" type="submit" >提交</button>
                <button class="button-white-big ml-20" type="button" onclick="location.href='<?=\yii\helpers\Url::to(["index"]) ?>'">返回</button>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>

<script>
    var isadd = 0; // 是否处于新增状态
    $(function(){
        var tree = [
            <?= $tree ?>
        ];
        var TemplateId = '';
        var addForm = $('#add-form');
        var tbs = $('#tbs');
        ajaxSubmitForm($('#select-form'));
        getAssignList();


        $('#tree').treeview({
            data: tree,         // data is not optional
            levels: 4,
            collapsed: true,
            unique: true,
            onNodeSelected: function(event, data) {
                $('.distribute').show();
                $('#add').attr('disabled',false);
                $('.preview').attr('disabled','true');
                $('.checkSql').attr('disabled','true');
                $('.saveAll').attr('disabled','true');
                addForm.find('select').not(':button, :submit, :reset, :hidden').attr('disabled',true);
                var idStr = mySubStr(data.text,'style="display:none;">','</span>','');
                if (!idStr) {
                    return false;
                } else if (idStr.indexOf('templateId')==0) {
                    TemplateId = mySubStr(idStr,'templateId:','');
                    var type = 'tpl';
//                    console.log('is template',id);
                } else if (idStr.indexOf('rptId:')==0) {
                    TemplateId = mySubStr(idStr,'rptId:','');
                    var type = 'rpt';
                    console.log(TemplateId);
                } else {
                    TemplateId = mySubStr(idStr,'SqlTplId:','');
                    var type = 'SqlTpl';
                }
                $('input[name="RptTemplate[rptt_id]"]').val(TemplateId);
                // ajax获取选中的模板/报表信息，填充显示在表单中
                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    data: {id:TemplateId},
                    url: "<?= Url::to(['/rpt/rpt-manage/get-rpt']) ?>",
                    success: function (data) {
                        isadd = 0;
                        $('#add').removeClass('select-cls');
                        $('#edit').removeClass('select-cls');
                        addForm.find('input,textarea').not(':button, :submit, :reset, :hidden').attr('readonly',true);
                        addForm.find('select').not(':button, :submit, :reset, :hidden').attr('disabled',true);
                        console.log('data:',data);
//                        console.log('data:',data[0].rptt_tempsql);
                        $('input[name="RptTemplate[rptt_code]"]').val(data[0].rptt_code);
                        $('input[name="RptTemplate[rptt_name]"]').val(data[0].rptt_name);
                        $('input[name="RptTemplate[rptt_title]"]').val(data[0].rptt_title);
                        $('select[name="RptTemplate[rptt_dtype]"]').val(data[0].rptt_dtype);
                        $('select[name="RptTemplate[rptt_pid]"]').val(data[0].rptt_pid);
                        $('select[name="RptTemplate[rptt_cat]"]').val(data[0].rptt_cat);
                        $('textarea[name="RptTemplate[rptt_tempsql]"]').val(data[0].rptt_tempsql);
                        $('.distribute').show();

                        $('#distribute').attr('disabled',false);
                        $('#preview').attr('disabled',false);
//                        $('#add').html('新增');
                        $('.param-div').show();
                        if (type=='tpl') {
                            // 禁用某个tabs
                            $('#tbs').hide();
                            $('input[name="RptTemplate[rptt_type]"]').val('10');
                            loadParams(true,TemplateId,true);
//                            $('#obj-data .preview').attr('disabled',true);
//                            $('#obj-data .saveAll').attr('disabled',true);
                        } else if (type=='rpt') {
                            tbs.show();
                            tbs.tabs('disableTab', 1);
                            tbs.tabs('enableTab', 0);
                            tbs.tabs('select', 0);
                            $('#delete').attr('disabled',false);
                            $('#edit').attr('disabled',false);
                            loadParams(true,TemplateId,true);
                            $('input[name="RptTemplate[rptt_type]"]').val('11');
                        } else {
                            $('.param-div').hide();
                            $('#delete').attr('disabled',false);
                            $('#edit').attr('disabled',false);
                            $('.checkSql').attr('disabled',true);
                            tbs.show();
                            tbs.tabs('disableTab', 0);
                            tbs.tabs('enableTab', 1);
                            tbs.tabs('select', 1);
                            $('input[name="RptTemplate[rptt_type]"]').val('12');
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
                getAssignList(TemplateId);
            },
            onNodeUnselected: function () {
                // 取消选中则禁用某些操作
//                alert('aaaa')
                $('#add').attr('disabled',false);
                $('#distribute').attr('disabled',true);
                $('#delete').attr('disabled',true);
                $('#preview').attr('disabled',true);
                $('#edit').attr('disabled',true);
                $('.checkSql').attr('disabled',true);
                $('.preview').attr('disabled',true);
                $('.saveAll').attr('disabled',true);
                addForm.find('input,textarea').not(':button, :submit, :reset, :hidden').attr('readonly',true);
                addForm.find('select').not(':button, :submit, :reset, :hidden').attr('disabled',true);
                $('.param-div').show();
            }
        });

        // 下拉菜单
//        $('#m-design').menubutton({
//            menu: '#m-data',
//            hasDownArrow:false
//        });
//        $('#m-design').removeClass("l-btn l-btn-small l-btn-plain");
//        $('#m-design').find("span").removeClass("l-btn-left l-btn-text");

        // 点击新增button
        $('#add').click(function () {
//            $(this).addClass('select-cls');
            var selected = $('#tree').treeview('getSelected');
            var addFrom = $('#add-form');
            $('input[name="RptTemplate[rptt_id]"]').val('');
            if (selected.length != 0) {
                $('#tree').treeview('unselectNode',selected[0].nodeId)
            }
//            if ($('#add').html()=='新增') {
//                $('#add').html('重置')
//            }
//            console.log(TemplateId);
            tbs.show();
            tbs.tabs('enableTab', 0);
            tbs.tabs('enableTab', 1);
            tbs.tabs('select', 1);
            addFrom.find('input,textarea').not(':button, :submit, :reset, :hidden').attr('readonly',false);
            addFrom.find('input,select,textarea').not(':button, :submit, :reset, :hidden').attr('disabled',false);
            addFrom.find('input,textarea,select').not(':button, :submit, :reset, :hidden, :radio').val('').removeAttr('checked').removeAttr('selected');//新增时清空表单
            tbs.tabs('select', 0);
            addFrom.find('input,textarea').not(':button, :submit, :reset, :hidden').attr('readonly',false);
            addFrom.find('input,select,textarea').not(':button, :submit, :reset, :hidden').attr('disabled',false);
            addFrom.find('input,textarea,select').not(':button, :submit, :reset, :hidden, :radio').val('').removeAttr('checked').removeAttr('selected');//新增时清空表单
            $('.distribute').hide();
            $('.param-div').hide();
            isadd = 1;
        })

        // 点击修改button
        $('#edit').on('click', function () {
            $('#add').attr('disabled',true);
            $('#distribute').attr('disabled',true);
            $('#delete').attr('disabled',true);
            $('#preview').attr('disabled',true);
//            $(this).addClass('select-cls');
            var TemplateId = $('input[name="RptTemplate[rptt_id]"]').val();
            $('.param-div').show();
            loadParams(false,TemplateId,false);
            $('#add-form').find('input,textarea').not(':button, :submit, :reset, :hidden').attr('readonly',false);
            $('#add-form').find('input,textarea').not(':button, :submit, :reset, :hidden').removeClass('validatebox-readonly');
            $('#add-form').find('input,select,textarea').not(':button, :submit, :reset, :hidden').attr('disabled',false);
            $('.checkSql').attr('disabled',false);
            $('input[name="RptTemplate[rptt_code]"]').attr('readonly','readonly');
//            $('input[name="RptTemplate[rptt_id]"]').val(TemplateId);
        })

        // 菜单预览按钮
        $('#preview').on('click',function () {
            preview();
        })

        // 分配
        $('#distribute').on('click',function () {
            $.fancybox(
                {
                    padding : [],
                    fitToView	: false,
                    width		: 760,
                    height		: 440,
                    autoSize	: false,
                    closeClick	: false,
                    openEffect	: 'none',
                    closeEffect	: 'none',
                    href: '#assign'
                }
            );
            $("#my-select").multiSelect({
                'selectableOptgroup': true
            });
            // 查询与显示user
            getUserList($('input[name="UserSearch[user_account]"]').val());
            $("#userSearch").on("click", function () {
                getUserList($('input[name="UserSearch[user_account]"]').val());
            });
        });

        // 去除只读属性
        $('.myChange').on('change', function (event) {
            event.stopImmediatePropagation();
            $(this).removeAttr("readonly");
        });

        // 删除分配列表 删除后重新加载
        $('.deleteAssign').on('click', function () {
            var selects = $('#assignList').datagrid('getSelections'); // 选中的数据项中取数据
            if (selects.length) {
                // ajax执行删除操作
                layer.confirm("确定删除吗?",{icon:2},
                    function () {
                        $.ajax({
                            type: 'post',
                            dataType: 'json',
                            data: {postData: JSON.stringify(selects)},
                            url: "<?= Url::to(['/rpt/rpt-manage/delete-assign']) ?>",
                            success: function (data) {
                                console.log('data',data)
                                $("#assignList").datagrid('reload').datagrid('clearSelections');
                                if(data.status==1){
                                    layer.alert(data.msg,{icon:1},function(){
                                        layer.closeAll();
                                    });
                                }else{
                                    layer.alert(data.msg,{icon:2});
                                }
                            },
                            error: function (data) {
                                console.log(JSON.stringify(selects));
                            }
                        });
                    },
                    layer.closeAll()
                );
            } else {
                layer.alert("请选择角色/用户！",{icon:2,time:5000});
                return false;
            }
        });

        // 删除报表 同时删除报表参数 分配关系
        $('#delete').on('click', function () {
            var rptId = $('input[name="RptTemplate[rptt_id]"]').val();
            layer.confirm("确定删除吗?",{icon:2},
                function () {
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        data: {rptId: rptId},
                        url: "<?= Url::to(['/rpt/rpt-manage/delete-rpt']) ?>",
                        success: function (data) {
                            console.log('data',data)
                            if(data.status==1){
                                layer.alert(data.msg,{icon:1},function(){
                                    window.location.href="<?=Url::to(['/rpt/rpt-manage/index'])?>";
                                });
                            }else{
                                layer.alert(data.msg,{icon:2});
                            }
                        },
                        error: function (data) {
                            layer.alert('网络错误！',{icon:2});
                            console.log(data);
                        }
                    });
                },
                layer.closeAll()
            );
        })
    });

    // 字符串截取函数（截取begiStr和endStr之间的字符串）
    function mySubStr(str,beginStr,endStr) {
        var beginIndex = str.indexOf(beginStr)+beginStr.length;
        if (endStr!='') {
            var endIndex = str.indexOf(endStr);
            var length = endIndex-beginIndex;
        } else {
            var length = str.length-beginIndex;
        }
        var res = str.substr(beginIndex,length);
        return res;
    }

    // 加载分配列表
    function getAssignList(tpId) {
        $("#assignList").datagrid({
            url:"<?=\yii\helpers\Url::to(['get-assign-list'])?>",
            pagination:true,
            rownumbers: true,
            idField: "rpta_id",
            method: "get",
            singleSelect:false,
//            pageSize: 5,
            pageList: [5,10,20],
            queryParams:{'tpId': tpId || $('input[name="UserSearch[user_account]"]').val()},
            columns:[[
                {field:"roru",title:"用户/角色 ID",width:166,hidden:true},
                {field:"role_user",title:"用户/角色",width:166,
                    formatter: function (value, row, index) {
                        if (row.rpta_type == 10) {
                            return '角色';
                        } else {
                            return '用户';
                        }
                    }
                },
                {field:"name",title:"名称",width:166,
                    formatter: function (value, row, index) {
                        if (row.rpta_type == 10) {
                            return row.title;
                        } else {
                            return row.staff_name;
                        }
                    }
                },
                {field:"assign_by",title:"分配人",width:166},
                {field:"cdate",title:"分配时间",width:166},
//                {field:"udate",title:"更新时间",width:100}
            ]],
            onSelect: function (index,row) {
//                $('#assignList').datagrid('deleteRow',index);
                console.log($('#assignList').datagrid('getSelections'))
            },
            onLoadSuccess: function () {
                setMenuHeight();
            }
        });
    }

    // 加载用户列表
    function getUserList(param) {
        $("#user-list").datagrid({
            url:"<?=\yii\helpers\Url::to(['user-search'])?>",
            method: "get",
            pagination:true,
            idField: "user_id",
            rownumbers: true,
            singleSelect:false,
            selectOnCheck:true,
            checkOnSelect:true,
            queryParams:{'UserSearch[user_account]':param || $('input[name="UserSearch[user_account]"]').val()},
            pageSize: 5,
            pageList: [5,10,20],
            columns:[[
                {field:"cbx",checkbox:true,width:50},
                {field:"user_id",title:"ID",width:100,hidden:true},
                {field:"user_name",title:"姓名",width:180,
                    formatter: function (value, row, index) {
                        if (row.staffInfo) {
                            return row.staffInfo.staff_name;
                        } else {
                            return null;
                        }
                    }
                },
                {field:"staff_code",title:"工号",width:180,
                    formatter: function (value, row, index) {
                        if (row.staffInfo) {
                            return row.staffInfo.staff_code;
                        } else {
                            return null;
                        }
                    }
                },
                {field:"user_account",title:"登陆账号",width:180},
            ]],
            onSelect: function (index,row) {
                addElement(row.user_id,row);

            },
            onUnselect: function (index,row) {
                var trClass = '.index-'+row.user_id;
                removeElement(trClass);
            }
        });
    }

    function addElement(idx,row) {
        if (!idx) {
            alert('idx不存在！')
        }
        var k = 100;
        var contactTr="<tr class="+'index-'+idx+">";
        contactTr+="<td style='display: none;'><input type='text' name='RptAssign[user][]' style='width:100%;' class='no-border text-center ' data-options='validType:\"length[0,4]\"' value="+row.user_id+"></td>";
        contactTr+="<td><input type='text' style='width:100%;' class='no-border text-center' value="+row.staffInfo.staff_name+"></td>";
        contactTr+="<td><input type='text' style='width:100%;' class='no-border text-center ' data-options='validType:\"length[0,4]\"' value="+row.staffInfo.staff_code+"></td>";
        contactTr+="<td><input type='text' style='width:100%;' class='no-border text-center ' data-options='validType:\"length[0,4]\"' value="+row.user_account+"></td>";
        contactTr+="</tr>";
        var $contactPersonTr=$("#user_tbody").append(contactTr);
        $.parser.parse($contactPersonTr);//重要，js动态添加easyui-validatebox需要单独解析
    }

    function removeElement(trClass) {
        $(trClass).remove();
    }
</script>